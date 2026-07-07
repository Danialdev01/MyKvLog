<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    /**
     * Generate PDF untuk semua log atau mengikut julat tarikh.
     * Dipanggil dari butang "Cetak Log" di halaman print.
     */
    public function generatePdf(Request $request)
    {
        $user = auth()->user();

        // Tentukan sama ada nak cetak semua atau ikut julat tarikh
        $printType = $request->input('print_type', 'all');

        if ($printType === 'range') {
            $request->validate([
                'start_date' => ['required', 'date'],
                'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            ]);

            $logs = $user->logs()
                ->with('references')
                ->whereBetween('log_date', [$request->start_date, $request->end_date])
                ->orderBy('log_date', 'asc')
                ->get();

            $filename = 'log-' . $request->start_date . '-hingga-' . $request->end_date . '.pdf';
        } else {
            $logs = $user->logs()
                ->with('references')
                ->orderBy('log_date', 'asc')
                ->get();

            $filename = 'semua-log-' . date('Y-m-d') . '.pdf';
        }

        if ($logs->isEmpty()) {
            return redirect()->route('print')->with('error', 'Tiada log untuk tempoh yang dipilih.');
        }

        // Convert gambar kepada base64 (dalam format yang DomPDF boleh handle)
        $logs = $this->tambahBase64Gambar($logs);

        $html = view('pdf.pdf-log', [
            'logs'    => $logs,
            'user'    => $user,
            'today'   => date('d/m/Y'),
        ])->render();

        $pdf = $this->buatPdf($html);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Generate PDF untuk satu log sahaja (mengikut ID).
     */
    public function generatePdfSingle($id)
    {
        $user = auth()->user();

        $log = $user->logs()->with('references')->where('log_id', $id)->first();

        if (!$log) {
            return redirect()->route('print')->with('error', 'Log tidak dijumpai.');
        }

        $filename = 'log-hari-' . $log->log_day . '-' . $log->log_date->format('Y-m-d') . '.pdf';

        $logs = collect([$log]);
        $logs = $this->tambahBase64Gambar($logs);

        $html = view('pdf.pdf-log', [
            'logs'  => $logs,
            'user'  => $user,
            'today' => date('d/m/Y'),
        ])->render();

        $pdf = $this->buatPdf($html);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Loop semua log dan convert setiap gambar kepada base64 JPEG.
     *
     * Kenapa JPEG? DomPDF boleh embed JPEG TANPA perlukan PHP GD extension.
     * PNG pula memerlukan GD.
     *
     * Strategi:
     * - Baca gambar dari disk public (tempat upload sebenar), fallback ke S3 untuk fail lama
     * - Kalau gambar memang JPEG/JPG → baca terus, convert ke base64 (tak perlu GD)
     * - Kalau gambar PNG/GIF/lain → cuba convert ke JPEG guna GD kalau ada
     * - Kalau gagal → image_base64 = null, image_error simpan sebab untuk view
     */
    private function tambahBase64Gambar($logs)
    {
        // Semak sekali je sama ada GD extension dipasang dalam server
        $gdAda = extension_loaded('gd');

        foreach ($logs as $log) {
            foreach ($log->references as $ref) {

                $fileContent = $this->bacaGambar($ref->reference_image);

                if ($fileContent === null) {
                    \Log::warning('PDF: reference image not found in storage', [
                        'reference_id' => $ref->getKey(),
                        'path'         => $ref->reference_image,
                    ]);
                    $ref->image_base64 = null;
                    $ref->image_error  = 'missing';
                    continue;
                }

                // Kesan jenis MIME dari kandungan fail sendiri (bukan extension nama fail)
                $mimeType = (new \finfo(FILEINFO_MIME_TYPE))->buffer($fileContent) ?: 'unknown';

                // ===== KES 1: Gambar sudah JPEG — boleh guna terus =====
                // DomPDF boleh proses JPEG tanpa GD extension
                if (in_array($mimeType, ['image/jpeg', 'image/jpg'], true)) {
                    $ref->image_base64 = 'data:image/jpeg;base64,' . base64_encode($fileContent);
                    continue;
                }

                // ===== KES 2: Gambar PNG/GIF/WebP/lain — perlukan GD untuk convert =====
                if ($gdAda) {
                    // GD ada — convert gambar kepada JPEG dalam memory
                    $ref->image_base64 = $this->convertKeJpegBase64($fileContent, $mimeType);
                    if ($ref->image_base64 === null) {
                        $ref->image_error = 'convert';
                        $ref->image_mime  = $mimeType;
                    }
                } else {
                    // GD takde — log untuk ops, dan biar view tunjuk mesej ringkas
                    \Log::warning('GD extension missing — cannot embed image in PDF', [
                        'reference_id' => $ref->getKey(),
                        'mime'         => $mimeType,
                    ]);
                    $ref->image_base64 = null;
                    $ref->image_error  = 'gd';
                    $ref->image_mime   = $mimeType; // simpan mime untuk tunjuk mesej berguna
                }
            }
        }

        return $logs;
    }

    /**
     * Baca kandungan gambar dari storage.
     * Upload baru disimpan dalam disk public (lihat LogController::store),
     * tapi fail lama mungkin masih dalam S3 — cuba kedua-duanya.
     */
    private function bacaGambar(string $path): ?string
    {
        foreach (['public', 's3'] as $disk) {
            try {
                $content = Storage::disk($disk)->get($path);
                if ($content !== null && $content !== '') {
                    return $content;
                }
            } catch (\Throwable $e) {
                // Disk tak boleh dicapai (cth. credential S3 salah) — cuba disk seterusnya
            }
        }

        return null;
    }

    /**
     * Convert sebarang format gambar (PNG, GIF, dll) kepada JPEG base64.
     * Fungsi ini dipanggil hanya kalau GD extension ada.
     *
     * @param string $fileContent  — kandungan fail gambar dalam binary
     * @param string $mimeType     — jenis gambar (image/png, image/gif, dll)
     * @return string|null         — base64 string atau null kalau gagal
     */
    private function convertKeJpegBase64(string $fileContent, string $mimeType): ?string
    {
        // Cuba cipta GD image object dari binary content
        $gdImage = @imagecreatefromstring($fileContent);

        // Kalau gagal buat GD object (fail rosak ke format tak dikenal)
        if ($gdImage === false) {
            return null;
        }

        // Untuk PNG yang ada transparency — kita fill background putih dulu
        // sebab JPEG tak support transparency, nanti jadi hitam kalau skip step ni
        if ($mimeType === 'image/png') {
            $lebar  = imagesx($gdImage);
            $tinggi = imagesy($gdImage);

            // Cipta canvas baru dengan background putih
            $canvasPutih = imagecreatetruecolor($lebar, $tinggi);
            $putih = imagecolorallocate($canvasPutih, 255, 255, 255);
            imagefill($canvasPutih, 0, 0, $putih);

            // Tampal gambar asal di atas canvas putih
            imagecopy($canvasPutih, $gdImage, 0, 0, 0, 0, $lebar, $tinggi);

            imagedestroy($gdImage);
            $gdImage = $canvasPutih;
        }

        // Render gambar ke JPEG dalam memory (output buffer)
        ob_start();
        imagejpeg($gdImage, null, 85); // 85 = kualiti JPEG yang ok untuk PDF
        $jpegContent = ob_get_clean();

        // Bersihkan memory
        imagedestroy($gdImage);

        // Return dalam format base64 untuk letak dalam src=""
        return 'data:image/jpeg;base64,' . base64_encode($jpegContent);
    }

    /**
     * Fungsi helper untuk convert HTML jadi PDF menggunakan DomPDF.
     */
    private function buatPdf(string $html): string
    {
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
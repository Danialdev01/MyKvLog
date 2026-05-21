<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    protected string $apiKey;
    protected string $baseUrl = 'https://openrouter.ai/api/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.key') ?? env('OPENROUTER_API_KEY');
    }

    public function generateField(Request $request)
    {
        $validated = $request->validate([
            'field' => ['required', 'string', 'in:log_summary,log_knowledge,log_tools,log_note,log_location,log_place'],
            'log_day' => ['required', 'integer', 'min:1', 'max:365'],
            'log_location' => ['nullable', 'string'],
            'log_place' => ['nullable', 'string'],
            'log_summary' => ['nullable', 'string'],
            'log_knowledge' => ['nullable', 'string'],
            'log_tools' => ['nullable', 'string'],
            'log_note' => ['nullable', 'string'],
            'reference' => ['nullable', 'string'],
        ]);

        $field = $validated['field'];
        $prompt = $this->buildFieldPrompt($field, $validated);
        $model = config('services.openrouter.default_model', 'minimax/minimax-m2.5');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url', 'http://localhost'),
                'X-Title' => 'MyKvLog',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Anda adalah penulis log latihan industri untuk pelajar KV Malaysia. Tulis dalam Bahasa Melayu SAHAJA. Jangan gunakan bahasa Inggeris, bahasa Cina, atau mana-mana bahasa lain. Output maksimum 4 klausa dalam SATU perenggan. Tulis dalam bahasa pertama, tense lampau. Pastikan tiada ralat grammatik. Jangan tambah preamble, penjelasan, atau pengenalan - hanya output sahaja.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 800,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $message = $data['choices'][0]['message'] ?? [];
                $content = $message['content'] ?? null;
                $finishReason = $data['choices'][0]['finish_reason'] ?? null;

                if ($content) {
                    return response()->json([
                        'success' => true,
                        'content' => trim($content),
                        'field' => $field,
                    ]);
                }

                if ($finishReason === 'length' && !empty($message['reasoning'])) {
                    $reasoning = $message['reasoning'];
                    if (preg_match('/["\']([^"\']{10,})["\']/', $reasoning, $matches)) {
                        return response()->json([
                            'success' => true,
                            'content' => trim($matches[1]),
                            'field' => $field,
                        ]);
                    }
                }
            }

            Log::error('OpenRouter API error: ' . $response->body());
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate content. Please try again.',
            ], 500);

        } catch (\Exception $e) {
            Log::error('AI Controller exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

protected function buildFieldPrompt(string $field, array $data): string
    {
        $day = $data['log_day'];
        $location = $data['log_location'] ?? '';
        $place = $data['log_place'] ?? '';
        $jobScope = $data['job_scope'] ?? '';
        $summary = $data['log_summary'] ?? '';
        $knowledge = $data['log_knowledge'] ?? '';
        $tools = $data['log_tools'] ?? '';
        $note = $data['log_note'] ?? '';
        $reference = $data['reference'] ?? '';

        $refText = $reference ? "Rujukan: {$reference}. " : '';

        $prompts = [
            'log_location' => "Cadangkan satu nama jabatan, unit, atau bahagian yang sesuai untuk latihan industri KV ini. Berdasarkan: Hari {$day}, skop kerja: {$jobScope}. Balas hanya dengan nama jabatan sahaja dalam Bahasa Melayu yang grammatik betul, contoh: 'Jabatan IT' atau 'Makmal Kejuruteraan Elektrik'.",

            'log_place' => "Cadangkan satu lokasi atau tempat kerja yang sesuai untuk latihan industri KV ini. Berdasarkan: Hari {$day}, jabatan: {$location}, skop kerja: {$jobScope}. Balas hanya dengan lokasi sahaja dalam Bahasa Melayu yang grammatik betul, contoh: 'Aras 2, Blok A, Kuala Lumpur' atau 'Bilik Server B, Tingkat 3'.",

            'log_summary' => "Anda adalah penulis log latihan industri profesional. Tulis dalam Bahasa Melayu SAHAJA. {$refText}Berdasarkan input pengguna: '{$summary}'. Tulis 2-4 KLAUSA dalam Bahasa Melayu yang grammatik betul dan professional tentang aktiviti harian. PRIORITIKAN input pengguna. Konteks: Hari {$day}, jabatan: {$location}, skop kerja: {$jobScope}. Tulis dalam bahasa pertama, tense lampau. Pastikan tiada ralat grammatik. Pastikan output adalah 2-4 klausa dalam SATU perenggan SAHAJA. Tulis sekarang:",

            'log_knowledge' => "Anda adalah penulis log latihan industri profesional. Tulis dalam Bahasa Melayu SAHAJA. {$refText}Aktiviti yang dilakukan: '{$summary}'. Berbasiskan aktiviti tersebut, tulis 2-4 KLAUSA dalam Bahasa Melayu yang grammatik betul tentang pengetahuan dan kemahiran yang diperolehi atau dipertingkatkan. Fokus pada apa yang ANDA pelajari, bukan pada aktiviti itu sendiri. Konteks: Hari {$day}, jabatan: {$location}, skop kerja: {$jobScope}. Tulis dalam bahasa pertama, tense lampau. Contoh output: 'Saya telah belajar cara menggunakan Figma untuk mereka bentuk wireframe.', 'Kemahiran pensuisan dan kabel saya telah bertambah baik.', 'Saya memahami proses pengurusan fail dalam sistem berasaskan web.' Pastikan tiada ralat grammatik. Pastikan output adalah 2-4 klausa dalam SATU perenggan SAHAJA. Tulis sekarang:",

            'log_tools' => "Anda adalah penulis log latihan industri profesional. Tulis dalam Bahasa Melayu SAHAJA. {$refText}Berdasarkan aktiviti: '{$summary}' dan alat sedia ada: '{$tools}'. Senaraikan alat dan peralatan yang digunakan semasa latihan industri dalam Bahasa Melayu yang grammatik betul. PRIORITIKAN aktiviti pengguna. Konteks: Hari {$day}, jabatan: {$location}, skop kerja: {$jobScope}. Senaraikan sebagai nilai terpisah koma SAHAJA, contoh: 'Komputer, Internet, Google Chrome, Microsoft Word'. Maksimum 6 item. Pastikan tiada ralat grammatik. Balas hanya dengan senarai alat sahaja:",

            'log_note' => "Anda adalah penulis log latihan industri profesional. Tulis dalam Bahasa Melayu SAHAJA. {$refText}Aktiviti hari ini: '{$summary}'. Pengetahuan yang diperolehi: '{$knowledge}'. Tulis 1-2 KLAUSA dalam Bahasa Melayu yang grammatik betul tentang nota tambahan, pemerhatian peribadi, atau rumusan pengalaman hari ini. Konteks: Hari {$day}, jabatan: {$location}. Fokus pada pemerhatian peribadi seperti kesukaran yang dihadapi, cara mengatasi masalah, atau nasihat untuk hari hadapan. Tulis dalam bahasa pertama, tense lampau. Pastikan tiada ralat grammatik. Pastikan output adalah 1-2 klausa dalam SATU perenggan SAHAJA. Tulis sekarang:",
        ];

        return $prompts[$field] ?? "Generate content for {$field}:";
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'log_summary' => ['required', 'string', 'max:2000'],
            'log_day' => ['required', 'integer', 'min:1', 'max:365'],
            'log_location' => ['nullable', 'string'],
            'log_place' => ['nullable', 'string'],
            'log_tools' => ['nullable', 'string'],
            'log_knowledge' => ['nullable', 'string'],
            'job_scope' => ['nullable', 'string'],
        ]);

        $prompt = $this->buildPrompt($validated);
        $model = config('services.openrouter.default_model', 'minimax/minimax-m2.5');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url', 'http://localhost'),
                'X-Title' => 'MyKvLog',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Anda adalah penulis log latihan industri untuk pelajar KV Malaysia. Tulis dalam Bahasa Melayu SAHAJA. Jangan gunakan bahasa Inggeris, bahasa Cina, atau mana-mana bahasa lain. Output maksimum 4 klausa. Tulis dalam bahasa pertama, tense lampau. Pastikan tiada ralat grammatik. Pastikan output adalah 2-4 klausa dalam SATU perenggan SAHAJA. Jangan tambah preamble, penjelasan, atau pengenalan - hanya output entri log sahaja.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 800,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;

                if ($content) {
                    return response()->json([
                        'success' => true,
                        'content' => trim($content),
                    ]);
                }
            }

            Log::error('OpenRouter API error: ' . $response->body());
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate log. Please try again.',
            ], 500);

        } catch (\Exception $e) {
            Log::error('AI Controller exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    protected function buildPrompt(array $data): string
    {
        $location = $data['log_location'] ?? 'jabatan';
        $place = $data['log_place'] ?? '';
        $day = $data['log_day'];
        $tools = $data['log_tools'] ?? '';
        $knowledge = $data['log_knowledge'] ?? '';
        $jobScope = $data['job_scope'] ?? '';

        $prompt = "Anda adalah penulis log latihan industri profesional. Tulis dalam Bahasa Melayu SAHAJA. Jangan gunakan bahasa Inggeris, bahasa Cina, atau mana-mana bahasa lain.\n\n";
        $prompt .= "Input pengguna: {$data['log_summary']}\n";
        $prompt .= "Hari: {$day}\n";
        $prompt .= "Jabatan: {$location}\n";
        $prompt .= "Skop kerja: {$jobScope}\n";

        if ($place) {
            $prompt .= "Lokasi: {$place}\n";
        }
        if ($tools) {
            $prompt .= "Alat: {$tools}\n";
        }
        if ($knowledge) {
            $prompt .= "Pembelajaran: {$knowledge}\n";
        }

        $prompt .= "\nBerdasarkan input di atas, tulis 2-4 KLAUSA dalam Bahasa Melayu yang grammatik betul. PRIORITIKAN input pengguna. Tulis dalam bahasa pertama, tense lampau. Pastikan tiada ralat grammatik. Pastikan output adalah 2-4 klausa dalam SATU perenggan SAHAJA. Jangan lebih daripada 4 klausa. Tulis sekarang:";

        return $prompt;
    }

    public function models()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/models');

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'models' => $response->json()['data'] ?? [],
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch models',
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
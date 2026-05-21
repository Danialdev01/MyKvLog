<?php

namespace Database\Seeders;

use App\Models\DefaultModel;
use App\Models\Log;
use App\Models\Reference;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['user_email' => 'hafizuddin@mykvlog.test'],
            [
                'user_hash_password' => bcrypt('password123'),
                'user_type_login' => 'email',
                'user_ai_usage' => 0,
                'user_status' => 'active',
                'user_created_at' => now(),
            ]
        );

        DefaultModel::firstOrCreate(
            ['user_id' => $user->user_id],
            [
                'default_internship_period' => 90,
                'default_department' => 'IT Department',
                'default_company' => 'KV Ipoh',
                'default_job_scope' => 'Network infrastructure maintenance, server administration, cable management',
                'default_status' => 'active',
                'default_created_at' => now(),
            ]
        );

        $this->seedLogsForUser($user);

        $danial = User::firstOrCreate(
            ['user_email' => 'danialirfan0125@gmail.com'],
            [
                'user_hash_password' => bcrypt('danialdev'),
                'user_type_login' => 'email',
                'user_ai_usage' => 0,
                'user_status' => 'active',
                'user_created_at' => now(),
            ]
        );

        DefaultModel::firstOrCreate(
            ['user_id' => $danial->user_id],
            [
                'default_internship_period' => 90,
                'default_department' => 'Software Engineering',
                'default_company' => 'Tech Solutions Malaysia',
                'default_job_scope' => 'Web development, API integration, database management',
                'default_status' => 'active',
                'default_created_at' => now(),
            ]
        );

        $this->seedLogsForUser($danial);
    }

    private function seedLogsForUser(User $user): void
    {
        Log::create([
            'user_id' => $user->user_id,
            'log_day' => 42,
            'log_location' => $user->defaults->default_department ?? 'IT Department',
            'log_date' => '2025-05-01',
            'log_section' => 'Latihan Praktikal',
            'log_summary' => 'Membantu dalam pemasangan fizikal dan organisasi rak server dalam pusat data. Tugas yang dilakukan termasuk penghalaan dan pelabelan kabel rangkaian mengikut sistem pengekodan warna yang ditetapkan.',
            'log_knowledge' => 'Belajar teknik pengurusan kabel yang betul dan protokol keselamatan pusat data.',
            'log_tools' => 'Cable tester, punch down tool, label printer, cable ties',
            'log_note' => 'Pengalaman hands-on yang hebat dengan persediaan rak server.',
            'log_status' => 'completed',
            'log_created_at' => now(),
            'log_updated_at' => now(),
        ]);

        Log::create([
            'user_id' => $user->user_id,
            'log_day' => 41,
            'log_location' => $user->defaults->default_department ?? 'IT Department',
            'log_date' => '2025-04-30',
            'log_section' => 'Latihan Praktikal',
            'log_summary' => 'Menyertai sesi penyelesaian masalah rangkaian dan attended the morning briefing dengan penyelia IT mengenai kemas kini projek infrastruktur rangkaian semasa.',
            'log_knowledge' => 'Pemahaman tentang kaedah penyelesaian masalah rangkaian dan pelaporan status projek.',
            'log_tools' => 'Diagnostic software, network scanner',
            'log_note' => 'Pengalaman pembelajaran yang baik dalam komunikasi berkumpulan.',
            'log_status' => 'completed',
            'log_created_at' => now(),
            'log_updated_at' => now(),
        ]);

        Log::create([
            'user_id' => $user->user_id,
            'log_day' => 40,
            'log_location' => $user->defaults->default_department ?? 'IT Department',
            'log_date' => '2025-04-29',
            'log_section' => 'Latihan Praktikal',
            'log_summary' => 'Menjalankan penyelenggaraan sistem CCTV termasuk pembersihan kamera, pelarasan sudut, dan semakan sistem rakaman. Juga menyelesaikan penulisan laporan mingguan.',
            'log_knowledge' => 'Prosedur penyelenggaraan CCTV dan amalan dokumentasi.',
            'log_tools' => 'CCTV viewer software, cleaning kit, lens adjustment tool',
            'log_note' => 'Laporan dihantar kepada penyelia.',
            'log_status' => 'completed',
            'log_created_at' => now(),
            'log_updated_at' => now(),
        ]);
    }
}
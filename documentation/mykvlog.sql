-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2026 at 09:34 AM
-- Server version: 12.2.2-MariaDB
-- PHP Version: 8.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mykvlog`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `defaults`
--

CREATE TABLE `defaults` (
  `default_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `default_internship_period` varchar(255) NOT NULL DEFAULT '90',
  `default_department` varchar(255) DEFAULT NULL,
  `default_location` varchar(255) DEFAULT NULL,
  `default_company` varchar(255) DEFAULT NULL,
  `default_job_scope` text DEFAULT NULL,
  `default_updated_at` timestamp NULL DEFAULT NULL,
  `default_created_at` timestamp NULL DEFAULT NULL,
  `default_status` varchar(255) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `defaults`
--

INSERT INTO `defaults` (`default_id`, `user_id`, `default_internship_period`, `default_department`, `default_location`, `default_company`, `default_job_scope`, `default_updated_at`, `default_created_at`, `default_status`) VALUES
(3, 2, '90', 'Digital Development', 'Strato Solutions SDN BHD', 'Strato Solutions SDN BHD', 'Web developer Intern', '2026-05-19 20:05:19', '2026-05-19 19:01:35', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `log_day` int(11) NOT NULL,
  `log_location` varchar(255) DEFAULT NULL,
  `log_place` varchar(255) DEFAULT NULL,
  `log_date` date NOT NULL,
  `log_section` varchar(255) DEFAULT NULL,
  `log_summary` text DEFAULT NULL,
  `log_knowledge` text DEFAULT NULL,
  `log_tools` text DEFAULT NULL,
  `log_note` text DEFAULT NULL,
  `log_updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `log_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `log_status` varchar(255) NOT NULL DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`log_id`, `user_id`, `log_day`, `log_location`, `log_place`, `log_date`, `log_section`, `log_summary`, `log_knowledge`, `log_tools`, `log_note`, `log_updated_at`, `log_created_at`, `log_status`) VALUES
(1, 1, 42, 'IT Department', NULL, '2025-05-01', 'Latihan Praktikal', 'Membantu dalam pemasangan fizikal dan organisasi rak server dalam pusat data. Tugas yang dilakukan termasuk penghalaan dan pelabelan kabel rangkaian mengikut sistem pengekodan warna yang ditetapkan.', 'Belajar teknik pengurusan kabel yang betul dan protokol keselamatan pusat data.', 'Cable tester, punch down tool, label printer, cable ties', 'Pengalaman hands-on yang hebat dengan persediaan rak server.', '2026-05-18 23:21:26', '2026-05-18 23:21:26', 'completed'),
(2, 1, 41, 'IT Department', NULL, '2025-04-30', 'Latihan Praktikal', 'Menyertai sesi penyelesaian masalah rangkaian dan attended the morning briefing dengan penyelia IT mengenai kemas kini projek infrastruktur rangkaian semasa.', 'Pemahaman tentang kaedah penyelesaian masalah rangkaian dan pelaporan status projek.', 'Diagnostic software, network scanner', 'Pengalaman pembelajaran yang baik dalam komunikasi berkumpulan.', '2026-05-18 23:21:26', '2026-05-18 23:21:26', 'completed'),
(3, 1, 40, 'IT Department', NULL, '2025-04-29', 'Latihan Praktikal', 'Menjalankan penyelenggaraan sistem CCTV termasuk pembersihan kamera, pelarasan sudut, dan semakan sistem rakaman. Juga menyelesaikan penulisan laporan mingguan.', 'Prosedur penyelenggaraan CCTV dan amalan dokumentasi.', 'CCTV viewer software, cleaning kit, lens adjustment tool', 'Laporan dihantar kepada penyelia.', '2026-05-18 23:21:26', '2026-05-18 23:21:26', 'completed'),
(4, 2, 42, 'Software Engineering', NULL, '2025-05-01', 'Latihan Praktikal', 'Membantu dalam pemasangan fizikal dan organisasi rak server dalam pusat data. Tugas yang dilakukan termasuk penghalaan dan pelabelan kabel rangkaian mengikut sistem pengekodan warna yang ditetapkan.', 'Belajar teknik pengurusan kabel yang betul dan protokol keselamatan pusat data.', 'Cable tester, punch down tool, label printer, cable ties', 'Pengalaman hands-on yang hebat dengan persediaan rak server.', '2026-05-18 23:21:26', '2026-05-18 23:21:26', 'completed'),
(5, 2, 41, 'Software Engineering', NULL, '2025-04-30', 'Latihan Praktikal', 'Menyertai sesi penyelesaian masalah rangkaian dan attended the morning briefing dengan penyelia IT mengenai kemas kini projek infrastruktur rangkaian semasa.', 'Pemahaman tentang kaedah penyelesaian masalah rangkaian dan pelaporan status projek.', 'Diagnostic software, network scanner', 'Pengalaman pembelajaran yang baik dalam komunikasi berkumpulan.', '2026-05-18 23:21:26', '2026-05-18 23:21:26', 'completed'),
(6, 2, 40, 'Software Engineering', NULL, '2025-04-29', 'Latihan Praktikal', 'Menjalankan penyelenggaraan sistem CCTV termasuk pembersihan kamera, pelarasan sudut, dan semakan sistem rakaman. Juga menyelesaikan penulisan laporan mingguan.', 'Prosedur penyelenggaraan CCTV dan amalan dokumentasi.', 'CCTV viewer software, cleaning kit, lens adjustment tool', 'Laporan dihantar kepada penyelia.', '2026-05-18 23:21:26', '2026-05-18 23:21:26', 'completed'),
(7, 2, 4, 'Digital Development', 'Strato Solutions SDN BHD', '2026-05-20', NULL, 'Pada hari yang keempat, saya telah menghasilkan email dan kata laluan untuk login sistem dan menyimpannya dengan selamat dalam pangkalan data. Saya juga telah menjalankan pengujian terhadap sistem tersebut untuk memastikan fungsi login berfungsi dengan baik dan tiada masalah keselamatan dikesan.', 'Saya telah memahami cara mengendalikan data sensitif seperti emel dan kata laluan secara selamat dalam sistem. Saya telah belajar prosedur penyimpanan credential dengan enkripsi dalam pangkalan data untuk mengelakkan kebocoran data. Kemahiran saya dalam melaksanakan pengujian keselamatan terhadap sistem log masuk telah meningkat dengan baik. Saya juga telah memahami kepentingan penggunaan teknik hashing dan salt dalam penyimpanan kata laluan untuk meningkatkan keselamatan.', 'Komputer, Internet, Visual Studio Code, Git, Google Chrome, Microsoft Word', 'Saya berdepan dengan kesukaran untuk memahami sepenuhnya operasi teknik hashing dan salt dalam penyimpanan kata laluan, namun saya berjaya mengatasinya dengan membuat rujukan kepada dokumentasi dan secara berulang. Saya belajar bahawa penting untuk tidak bergegas dalam mengimplementasikan sistem credential dan sentiasa mengutamakan aspek keselamatan dalam setiap pembangunan sistem pada masa hadapan.', '2026-05-19 23:56:45', '2026-05-19 23:56:45', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000002_create_logs_table', 1),
(5, '2024_01_01_000003_create_references_table', 1),
(6, '2024_01_01_000004_create_defaults_table', 1),
(7, '2026_05_20_035951_add_default_location_to_defaults_table', 2),
(8, '2026_05_20_040141_add_log_place_to_logs_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `references`
--

CREATE TABLE `references` (
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `reference_file` varchar(255) DEFAULT NULL,
  `reference_image` varchar(255) DEFAULT NULL,
  `reference_diagram` varchar(255) DEFAULT NULL,
  `reference_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reference_status` varchar(255) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dEJKPdge8MX6115Zqj04rgKOgddpZyrzJ2gOIKwP', NULL, '127.0.0.1', 'curl/8.20.0', 'eyJfdG9rZW4iOiJBbDVFclpkSEtFTXY0RGppcGVqNFpnYVhZV0RlaGRpcHBTOXhRTERlIiwiZXJyb3JzIjp7ImRlZmF1bHQiOnsiZm9ybWF0IjoiOm1lc3NhZ2UiLCJtZXNzYWdlcyI6eyJnb29nbGVfZXJyb3IiOlsiR2FnYWwgbG9nIG1hc3VrIGRlbmdhbiBHb29nbGUuIFNpbGEgY3ViYSBsYWdpLiJdfX19LCJfZmxhc2giOnsibmV3IjpbXSwib2xkIjpbImVycm9ycyJdfX0=', 1779178060),
('I06BuenJy4ufWH0eNsSah4Uo90nb9MQhhN9YbWyh', NULL, '127.0.0.1', 'curl/8.20.0', 'eyJfdG9rZW4iOiJaVllyTWpvWHZoaldvanBzdmtkRjVSOUxKcm85SXNtOFdIT1pnMmRmIiwic3RhdGUiOiJWSHNlVlNrZmVqN0R3dEJWWDNYVVozYU1XbzdhRll6cnplZzVaNEoyIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1779176733),
('kTJoIGfKzfw7agrEcTTGnHak57wZfc8r8VXux050', NULL, '127.0.0.1', 'curl/8.20.0', 'eyJfdG9rZW4iOiJFSEcxVE9aTTBIclJpdWowRmRuWjc3Vm96Uzg5TGtJbjl2SHg3aVJNIiwiZXJyb3JzIjp7ImRlZmF1bHQiOnsiZm9ybWF0IjoiOm1lc3NhZ2UiLCJtZXNzYWdlcyI6eyJnb29nbGVfZXJyb3IiOlsiR2FnYWwgbG9nIG1hc3VrIGRlbmdhbiBHb29nbGUuIFNpbGEgY3ViYSBsYWdpLiJdfX19LCJfZmxhc2giOnsibmV3IjpbXSwib2xkIjpbImVycm9ycyJdfSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAxXC9hdXRoXC9nb29nbGVcL2NhbGxiYWNrP2NvZGU9dGVzdCIsInJvdXRlIjoiYXV0aC5nb29nbGUuY2FsbGJhY2sifX0=', 1779178611),
('mVcGDSdonGN5QPgVpL2sCcsKhutNJOIqHrNuUYYr', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:148.0) Gecko/20100101 Firefox/148.0', 'eyJfdG9rZW4iOiIwbXBHcnI5SjNpbTdLWjN6OTJCQ1UyWFE1M2ZOV2pXODVsQzFwQXVvIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDEiLCJyb3V0ZSI6ImhvbWUifSwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDFcL2Rhc2hib2FyZCJ9fQ==', 1779178439),
('PlfAdpzUO29xfh6EwlzSXI6UyiJXOZEvL2bC6ntv', NULL, '127.0.0.1', 'curl/8.20.0', 'eyJfdG9rZW4iOiJwNXBITlJQYU9CV3BvWmtVWGtGS2FIeTBua2RKVVNTNFBocHQ0NGxNIiwiZXJyb3JzIjp7ImRlZmF1bHQiOnsiZm9ybWF0IjoiOm1lc3NhZ2UiLCJtZXNzYWdlcyI6eyJnb29nbGVfZXJyb3IiOlsiR2FnYWwgbG9nIG1hc3VrIGRlbmdhbiBHb29nbGUuIFNpbGEgY3ViYSBsYWdpLiJdfX19LCJfZmxhc2giOnsibmV3IjpbXSwib2xkIjpbImVycm9ycyJdfSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAxXC9hdXRoXC9nb29nbGVcL2NhbGxiYWNrP2NvZGU9dGVzdCIsInJvdXRlIjoiYXV0aC5nb29nbGUuY2FsbGJhY2sifX0=', 1779178636),
('qK2khX8BEQJOO1QY7T1kMqaaW2wIq9C4DR2x6Oit', NULL, '127.0.0.1', 'curl/8.20.0', 'eyJfdG9rZW4iOiI1amxlRFZRVVY3OFJHM2VZNVhFcHB0Q08zRXptdTQ4d0hSSVFmWUVWIiwiZXJyb3JzIjp7ImRlZmF1bHQiOnsiZm9ybWF0IjoiOm1lc3NhZ2UiLCJtZXNzYWdlcyI6eyJnb29nbGVfZXJyb3IiOlsiR2FnYWwgbG9nIG1hc3VrIGRlbmdhbiBHb29nbGUuIFNpbGEgY3ViYSBsYWdpLiJdfX19LCJfZmxhc2giOnsibmV3IjpbXSwib2xkIjpbImVycm9ycyJdfSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAxXC9hdXRoXC9nb29nbGVcL2NhbGxiYWNrP2NvZGU9dGVzdCIsInJvdXRlIjoiYXV0aC5nb29nbGUuY2FsbGJhY2sifX0=', 1779178448),
('W0HHolxZFHWc75e1vnMjWikWLM4NRDW9ahhtBCER', NULL, '127.0.0.1', 'curl/8.20.0', 'eyJfdG9rZW4iOiJaNEZpYXVCVzM3SkFkZ2JRZWNFSDI1VHI1OXB0RXR0MzJtQlhIVVcwIiwiZXJyb3JzIjp7ImRlZmF1bHQiOnsiZm9ybWF0IjoiOm1lc3NhZ2UiLCJtZXNzYWdlcyI6eyJnb29nbGVfZXJyb3IiOlsiR29vZ2xlIGxvZ2luIGZhaWxlZC4gUGxlYXNlIHRyeSBhZ2Fpbi4iXX19fSwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6WyJlcnJvcnMiXX19', 1779177854),
('xh6XFRq1ibFjaUbmvAXAtEbUNv4Qy9PQKcV3u7oE', NULL, '127.0.0.1', 'curl/8.20.0', 'eyJfdG9rZW4iOiJ4WHpZQ2w4RFhNcUNyTTk3aDRucDlQM0FrQjZrZDg1dmlHNXZlUWNvIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDEifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1779176684);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_hash_password` varchar(255) NOT NULL,
  `user_type_login` varchar(255) DEFAULT NULL,
  `user_ai_usage` int(11) NOT NULL DEFAULT 0,
  `user_loggedin_at` timestamp NULL DEFAULT NULL,
  `user_updated_at` timestamp NULL DEFAULT NULL,
  `user_created_at` timestamp NULL DEFAULT NULL,
  `user_status` varchar(255) NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_email`, `user_hash_password`, `user_type_login`, `user_ai_usage`, `user_loggedin_at`, `user_updated_at`, `user_created_at`, `user_status`, `remember_token`) VALUES
(1, 'hafizuddin@mykvlog.test', '$2y$12$/0lS.7oNw9gZNGnp9BZDYejLjzKre7waGdDdpjicE3vvQs98CNH0i', 'email', 0, NULL, NULL, '2026-05-18 23:21:26', 'active', NULL),
(2, 'danialirfan0125@gmail.com', '$2y$12$NIHC1ext3djmP/p8gpLCB.vsfIr38fVeQHAKqxwASXndt/w5PCezm', 'email', 0, NULL, NULL, '2026-05-18 23:21:26', 'active', NULL),
(3, 'developersdani@gmail.com', '$2y$12$1f/J8cgDJda8oezBlIJDf.YINuNzKZdLir6XsLcnIFFbCSCDQa0ZC', 'google', 0, NULL, NULL, '2026-05-19 00:27:34', 'active', NULL),
(4, 'm-12244915@moe-dl.edu.my', '$2y$12$xzKGuA1lqf0itbmNtvCZBOoRS5asgBJHfe0PoTedVglmnAaIvViQ2', 'google', 0, NULL, NULL, '2026-05-19 00:27:47', 'active', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `defaults`
--
ALTER TABLE `defaults`
  ADD PRIMARY KEY (`default_id`),
  ADD KEY `defaults_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `references`
--
ALTER TABLE `references`
  ADD PRIMARY KEY (`reference_id`),
  ADD KEY `references_log_id_foreign` (`log_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_user_email_unique` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `defaults`
--
ALTER TABLE `defaults`
  MODIFY `default_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `references`
--
ALTER TABLE `references`
  MODIFY `reference_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `defaults`
--
ALTER TABLE `defaults`
  ADD CONSTRAINT `defaults_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `references`
--
ALTER TABLE `references`
  ADD CONSTRAINT `references_log_id_foreign` FOREIGN KEY (`log_id`) REFERENCES `logs` (`log_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Nov 2024 pada 18.41
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lmshatese`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `access`
--

CREATE TABLE `access` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `last_chapter_accessed` int(11) DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chapters`
--

CREATE TABLE `chapters` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `chapter_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `chapters`
--

INSERT INTO `chapters` (`id`, `course_id`, `title`, `content`, `created_at`, `chapter_number`) VALUES
(33, 33, 'Bab 1', '', '2024-11-13 11:24:56', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `chapters_guest`
--

CREATE TABLE `chapters_guest` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `chapters_guest`
--

INSERT INTO `chapters_guest` (`id`, `course_id`, `title`, `content`, `created_at`) VALUES
(9, 10, 'Bab 1', '', '2024-11-14 02:07:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `instructor_id`, `cover_image`) VALUES
(33, 'Jikoshukai', '<p>Ini adalah materi jikoshukai</p>', 3, '67348c072d72c.jpeg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `courses_guest`
--

CREATE TABLE `courses_guest` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `features` text DEFAULT NULL,
  `what_youll_learn` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `courses_guest`
--

INSERT INTO `courses_guest` (`id`, `title`, `description`, `instructor_id`, `cover_image`, `created_at`, `features`, `what_youll_learn`) VALUES
(10, 'Hiragana', '<p>Dalam materi ini, peserta akan mempelajari beberapa karakter hiragana beserta cara penulisan dan pengucapannya yang benar. Selain itu, materi ini juga mencakup latihan membaca, menulis, dan mengidentifikasi kata-kata yang menggunakan hiragana. Tujuan utamanya adalah untuk membangun dasar yang kuat dalam bahasa Jepang, karena hiragana digunakan untuk menulis kata-kata asli Jepang, partikel, dan akhiran.</p>', 0, '673434f85d60b_cover materi .png', '2024-11-13 05:11:20', NULL, NULL),
(11, 'Katakana', '<p>Dalam materi ini, peserta akan mempelajari beberapa karakter hiragana beserta cara penulisan dan pengucapannya yang benar. Selain itu, materi ini juga mencakup latihan membaca, menulis, dan mengidentifikasi kata-kata yang menggunakan Katakana. Tujuan utamanya adalah untuk membangun dasar yang kuat dalam bahasa Jepang, karena Katakana pun digunakan untuk menulis kata-kata asli Jepang, partikel, dan akhiran.</p>', 0, '67343527c873a_cover materi  (1).png', '2024-11-13 05:12:07', NULL, NULL),
(12, 'Kanji', '<div class=\"flex max-w-full flex-col flex-grow\">\r\n<div class=\"min-h-8 text-message flex w-full flex-col items-end gap-2 whitespace-normal break-words [.text-message+&amp;]:mt-5\" dir=\"auto\" data-message-author-role=\"assistant\" data-message-id=\"33524485-8b4c-46de-bf1b-85cd6609964e\" data-message-model-slug=\"gpt-4o-mini\">\r\n<div class=\"flex w-full flex-col gap-1 empty:hidden first:pt-[3px]\">\r\n<div class=\"markdown prose w-full break-words dark:prose-invert light\">\r\n<p>&nbsp;Kanji mengajarkan sistem tulisan logografis dalam bahasa Jepang, di mana setiap karakter mewakili makna tertentu. Peserta akan mempelajari karakter-kanji dasar, cara membaca, menulis, dan memahami penggunaannya dalam kata dan kalimat. <button class=\"rounded-lg text-token-text-secondary hover:bg-token-main-surface-secondary\" aria-label=\"Copy\" data-testid=\"copy-turn-action-button\"></button></p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>', 0, '6734356f94fea_cover materi  (2).png', '2024-11-13 05:13:19', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `enrolled_at`) VALUES
(28, 2, 33, '2024-11-13 11:33:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `guest_access`
--

CREATE TABLE `guest_access` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `access_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guest_access`
--

INSERT INTO `guest_access` (`id`, `full_name`, `email`, `phone`, `access_time`) VALUES
(4, 'Muhammad Jaki', 'admin@gmail.com', '085864139786', '2024-10-30 04:40:10'),
(5, 'zakzak', 'admin@gmail.com', '085864139786', '2024-10-30 04:40:39'),
(6, 'Muhammad Jaki', 'dzakykurniawan26@gmail.com', '085864139786', '2024-10-30 07:55:10'),
(7, 'Muhammad Jaki', 'dzakikurniawan26@gmail.com', '085864139786', '2024-10-30 08:01:56'),
(8, 'zakzak', 'dimas@gmail.com', '085864139786', '2024-10-30 08:03:29'),
(9, 'Okarun', 'okarunsama@gmail.com', '089872645466', '2024-11-01 02:26:20'),
(10, 'aa', 'dzakikurniawan26@gmail.com', '085864139786', '2024-11-01 05:15:38'),
(11, 'zaldy', 'hahshwhehe@gmail.com', '452545154516', '2024-11-04 07:14:20'),
(12, 'Riky gunawan', 'admin@gmail.com', '085864139786', '2024-11-04 12:47:02'),
(13, 'Okarun', 'polapoli74@gmail.com', '08765675432', '2024-11-04 12:54:08'),
(14, 'zara', 'dimas@gmail.com', '087363478374', '2024-11-04 12:59:41'),
(15, 'zakzak', 'dzakykurniawan26@gmail.com', '0875678987', '2024-11-04 13:05:16'),
(16, 'sukiyo', 'polapoli74@gmail.com', '087656787654', '2024-11-04 13:24:07'),
(17, 'duh cape', 'bhbhbh@gmail.com', '09334383746', '2024-11-04 13:30:22'),
(18, 'bobon', 'dimas@gmail.com', '08723647388172', '2024-11-04 13:35:39'),
(19, 'Greal asgy', 'asgyridoya@gmail.com', '085849615789', '2024-11-04 13:54:32'),
(20, 'zarasas', 'dzakykurniawan26@gmail.com', '085864139786', '2024-11-04 13:58:27'),
(21, 'zakzak', 'admin@gmail.com', '085864139786', '2024-11-05 06:03:46'),
(22, 'Ratu', 'nyaawang@gmail.com', '085864139786', '2024-11-06 06:41:21'),
(23, 'huouh', 'admin@gmail.com', '085864139786', '2024-11-11 02:13:25'),
(24, 'onearth', 'admin@gmail.com', '085864139786', '2024-11-11 02:34:59'),
(25, 'onearth', 'admin@gmail.com', '085864139786', '2024-11-11 02:35:17'),
(26, 'Okarun', 'dimas@gmail.com', '085864139786', '2024-11-13 04:11:01'),
(27, 'ratu', '123@gmail.com', '12345678', '2024-11-13 04:15:20'),
(28, 'Greal asgy', 'fufufafa@gmail.com', '08547855695', '2024-11-13 05:17:16'),
(29, 'sasknd', 'djc@gmail.com', '902380', '2024-11-13 06:05:43'),
(30, 'dewi ayu n', 'anjaihuhuhu@gmail.com', '08786524232', '2024-11-13 11:17:15'),
(31, 'onearth', 'dzakykurniawan26@gmail.com', '085864139786', '2024-11-14 02:07:50'),
(32, 'zakzak', 'dzakikurniawan26@gmail.com', '085864139786', '2024-11-14 16:09:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` enum('a','b','c','d') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `quizzes`
--

INSERT INTO `quizzes` (`id`, `chapter_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(80, 33, '<p>WADAW</p>', 'wadidaw', 'uwaw', 'huouh', 'omaga', 'a'),
(81, 33, '<p>OMFG</p>', 'oh my fck gd', 'what', 'whuthehell', 'waw', 'a');

-- --------------------------------------------------------

--
-- Struktur dari tabel `quiz_completions`
--

CREATE TABLE `quiz_completions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `is_passed` tinyint(1) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_progress`
--

CREATE TABLE `student_progress` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `sub_chapter_id` int(11) DEFAULT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_chapters`
--

CREATE TABLE `sub_chapters` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `sub_chapter_number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sub_chapters`
--

INSERT INTO `sub_chapters` (`id`, `chapter_id`, `sub_chapter_number`, `title`, `content`, `created_at`) VALUES
(52, 33, 0, '1.1 Prinsip Ekonomi', '<h3>1. Pengenalan Huruf Jepang</h3>\r\n<p class=\"mb-2 last:mb-0\">Bahasa Jepang menggunakan tiga sistem penulisan utama:</p>\r\n<ul>\r\n<li><strong>Hiragana (ひらがな)</strong>: digunakan untuk kata-kata asli Jepang dan partikel.</li>\r\n<li><strong>Katakana (カタカナ)</strong>: digunakan untuk kata-kata serapan dari bahasa asing.</li>\r\n<li><strong>Kanji (漢字)</strong>: karakter yang berasal dari Tiongkok, digunakan untuk kata-kata yang memiliki makna tertentu.</li>\r\n</ul>\r\n<h4>Contoh Hiragana dan Katakana</h4>\r\n<table>\r\n<thead>\r\n<tr>\r\n<th>Hiragana</th>\r\n<th>Katakana</th>\r\n<th>Romaji</th>\r\n<th>Arti</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>あ</td>\r\n<td>ア</td>\r\n<td>a</td>\r\n<td>a</td>\r\n</tr>\r\n<tr>\r\n<td>い</td>\r\n<td>イ</td>\r\n<td>i</td>\r\n<td>i</td>\r\n</tr>\r\n<tr>\r\n<td>う</td>\r\n<td>ウ</td>\r\n<td>u</td>\r\n<td>u</td>\r\n</tr>\r\n<tr>\r\n<td>え</td>\r\n<td>エ</td>\r\n<td>e</td>\r\n<td>e</td>\r\n</tr>\r\n<tr>\r\n<td>お</td>\r\n<td>オ</td>\r\n<td>o</td>\r\n<td>o</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h3>2. Kosakata Dasar</h3>\r\n<p class=\"mb-2 last:mb-0\">Berikut adalah beberapa kosakata dasar dalam bahasa Jepang:</p>\r\n<table>\r\n<thead>\r\n<tr>\r\n<th>Bahasa Indonesia</th>\r\n<th>Bahasa Jepang</th>\r\n<th>Romaji</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>Selamat pagi</td>\r\n<td>おはようございます</td>\r\n<td>Ohayou gozaimasu</td>\r\n</tr>\r\n<tr>\r\n<td>Selamat siang</td>\r\n<td>こんにちは</td>\r\n<td>Konnichiwa</td>\r\n</tr>\r\n<tr>\r\n<td>Selamat malam</td>\r\n<td>こんばんは</td>\r\n<td>Konbanwa</td>\r\n</tr>\r\n<tr>\r\n<td>Terima kasih</td>\r\n<td>ありがとうございます</td>\r\n<td>Arigatou gozaimasu</td>\r\n</tr>\r\n<tr>\r\n<td>Maaf</td>\r\n<td>ごめんなさい</td>\r\n<td>Gomen nasai</td>\r\n</tr>\r\n<tr>\r\n<td>Ya</td>\r\n<td>はい</td>\r\n<td>Hai</td>\r\n</tr>\r\n<tr>\r\n<td>Tidak</td>\r\n<td>いいえ</td>\r\n<td>Iie</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h3>3. Frasa Dasar</h3>\r\n<p class=\"mb-2 last:mb-0\">Beberapa frasa yang sering digunakan dalam percakapan sehari-hari:</p>\r\n<ul>\r\n<li><strong>Apa kabar?</strong>\r\n<ul>\r\n<li>お元気ですか？ (Ogenki desu ka?)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Nama saya...</strong>\r\n<ul>\r\n<li>私の名前は...です。 (Watashi no namae wa ... desu.)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Di mana kamar kecil?</strong>\r\n<ul>\r\n<li>トイレはどこですか？ (Toire wa doko desu ka?)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Saya tidak mengerti.</strong>\r\n<ul>\r\n<li>わかりません。 (Wakarimasen.)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Tolong bantu saya.</strong>\r\n<ul>\r\n<li>助けてください。 (Tasukete kudasai.)</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n<h3>4. Angka</h3>\r\n<p class=\"mb-2 last:mb-0\">Berikut adalah angka dari 1 sampai 10 dalam bahasa Jepang:</p>\r\n<table>\r\n<thead>\r\n<tr>\r\n<th>Angka</th>\r\n<th>Bahasa Jepang</th>\r\n<th>Romaji</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>1</td>\r\n<td>一</td>\r\n<td>ichi</td>\r\n</tr>\r\n<tr>\r\n<td>2</td>\r\n<td>二</td>\r\n<td>ni</td>\r\n</tr>\r\n<tr>\r\n<td>3</td>\r\n<td>三</td>\r\n<td>san</td>\r\n</tr>\r\n<tr>\r\n<td>4</td>\r\n<td>四</td>\r\n<td>shi/yon</td>\r\n</tr>\r\n<tr>\r\n<td>5</td>\r\n<td>五</td>\r\n<td>go</td>\r\n</tr>\r\n<tr>\r\n<td>6</td>\r\n<td>六</td>\r\n<td>roku</td>\r\n</tr>\r\n<tr>\r\n<td>7</td>\r\n<td>七</td>\r\n<td>shichi/nana</td>\r\n</tr>\r\n<tr>\r\n<td>8</td>\r\n<td>八</td>\r\n<td>hachi</td>\r\n</tr>\r\n<tr>\r\n<td>9</td>\r\n<td>九</td>\r\n<td>kyuu/ku</td>\r\n</tr>\r\n<tr>\r\n<td>10</td>\r\n<td>十</td>\r\n<td>juu</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><audio controls=\"controls\"><source src=\"../quizgambar/media_notyou.mp3\" type=\"audio/mpeg\"></audio></p>\r\n<p><video style=\"width: 512px; height: 288px;\" poster=\"\" controls=\"controls\" width=\"512\" height=\"288\"><source src=\"../quizgambar/media_cover-rahmatan-lil-alamin-zaku-x-rem.mp4\" type=\"video/mp4\"></video></p>\r\n<h3>5. Pelajaran Tambahan</h3>\r\n<ul>\r\n<li><strong>Perkenalan Diri</strong>: Latih memperkenalkan diri dengan menyebutkan nama, usia, dan hobi.</li>\r\n<li><strong>Kata Kerja Dasar</strong>: Pelajari beberapa kata kerja dasar seperti \"makan\" (食べる/taberu), \"minum\" (飲む/nomu), dan \"tidur\" (寝る/neru).</li>\r\n<li><strong>Pertanyaan Umum</strong>: Latih membuat pertanyaan sederhana seperti \"Apa itu?\" (それは何ですか？/Sore wa nan desu ka?) atau \"Siapa dia?\" (彼は誰ですか？/Kare wa dare desu ka?).</li>\r\n</ul>', '2024-11-13 11:53:42'),
(55, 33, 0, '1.2 Ngoding', '<h3>1. Pengenalan Huruf Jepang</h3>\r\n<p class=\"mb-2 last:mb-0\">Bahasa Jepang menggunakan tiga sistem penulisan utama:</p>\r\n<ul>\r\n<li><strong>Hiragana (ひらがな)</strong>: digunakan untuk kata-kata asli Jepang dan partikel.</li>\r\n<li><strong>Katakana (カタカナ)</strong>: digunakan untuk kata-kata serapan dari bahasa asing.</li>\r\n<li><strong>Kanji (漢字)</strong>: karakter yang berasal dari Tiongkok, digunakan untuk kata-kata yang memiliki makna tertentu.</li>\r\n</ul>\r\n<h4>Contoh Hiragana dan Katakana</h4>\r\n<table>\r\n<thead>\r\n<tr>\r\n<th>Hiragana</th>\r\n<th>Katakana</th>\r\n<th>Romaji</th>\r\n<th>Arti</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>あ</td>\r\n<td>ア</td>\r\n<td>a</td>\r\n<td>a</td>\r\n</tr>\r\n<tr>\r\n<td>い</td>\r\n<td>イ</td>\r\n<td>i</td>\r\n<td>i</td>\r\n</tr>\r\n<tr>\r\n<td>う</td>\r\n<td>ウ</td>\r\n<td>u</td>\r\n<td>u</td>\r\n</tr>\r\n<tr>\r\n<td>え</td>\r\n<td>エ</td>\r\n<td>e</td>\r\n<td>e</td>\r\n</tr>\r\n<tr>\r\n<td>お</td>\r\n<td>オ</td>\r\n<td>o</td>\r\n<td>o</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h3>2. Kosakata Dasar</h3>\r\n<p class=\"mb-2 last:mb-0\">Berikut adalah beberapa kosakata dasar dalam bahasa Jepang:</p>\r\n<table>\r\n<thead>\r\n<tr>\r\n<th>Bahasa Indonesia</th>\r\n<th>Bahasa Jepang</th>\r\n<th>Romaji</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>Selamat pagi</td>\r\n<td>おはようございます</td>\r\n<td>Ohayou gozaimasu</td>\r\n</tr>\r\n<tr>\r\n<td>Selamat siang</td>\r\n<td>こんにちは</td>\r\n<td>Konnichiwa</td>\r\n</tr>\r\n<tr>\r\n<td>Selamat malam</td>\r\n<td>こんばんは</td>\r\n<td>Konbanwa</td>\r\n</tr>\r\n<tr>\r\n<td>Terima kasih</td>\r\n<td>ありがとうございます</td>\r\n<td>Arigatou gozaimasu</td>\r\n</tr>\r\n<tr>\r\n<td>Maaf</td>\r\n<td>ごめんなさい</td>\r\n<td>Gomen nasai</td>\r\n</tr>\r\n<tr>\r\n<td>Ya</td>\r\n<td>はい</td>\r\n<td>Hai</td>\r\n</tr>\r\n<tr>\r\n<td>Tidak</td>\r\n<td>いいえ</td>\r\n<td>Iie</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h3>3. Frasa Dasar</h3>\r\n<p class=\"mb-2 last:mb-0\">Beberapa frasa yang sering digunakan dalam percakapan sehari-hari:</p>\r\n<ul>\r\n<li><strong>Apa kabar?</strong>\r\n<ul>\r\n<li>お元気ですか？ (Ogenki desu ka?)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Nama saya...</strong>\r\n<ul>\r\n<li>私の名前は...です。 (Watashi no namae wa ... desu.)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Di mana kamar kecil?</strong>\r\n<ul>\r\n<li>トイレはどこですか？ (Toire wa doko desu ka?)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Saya tidak mengerti.</strong>\r\n<ul>\r\n<li>わかりません。 (Wakarimasen.)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Tolong bantu saya.</strong>\r\n<ul>\r\n<li>助けてください。 (Tasukete kudasai.)</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n<h3>4. Angka</h3>\r\n<p class=\"mb-2 last:mb-0\">Berikut adalah angka dari 1 sampai 10 dalam bahasa Jepang:</p>\r\n<table>\r\n<thead>\r\n<tr>\r\n<th>Angka</th>\r\n<th>Bahasa Jepang</th>\r\n<th>Romaji</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>1</td>\r\n<td>一</td>\r\n<td>ichi</td>\r\n</tr>\r\n<tr>\r\n<td>2</td>\r\n<td>二</td>\r\n<td>ni</td>\r\n</tr>\r\n<tr>\r\n<td>3</td>\r\n<td>三</td>\r\n<td>san</td>\r\n</tr>\r\n<tr>\r\n<td>4</td>\r\n<td>四</td>\r\n<td>shi/yon</td>\r\n</tr>\r\n<tr>\r\n<td>5</td>\r\n<td>五</td>\r\n<td>go</td>\r\n</tr>\r\n<tr>\r\n<td>6</td>\r\n<td>六</td>\r\n<td>roku</td>\r\n</tr>\r\n<tr>\r\n<td>7</td>\r\n<td>七</td>\r\n<td>shichi/nana</td>\r\n</tr>\r\n<tr>\r\n<td>8</td>\r\n<td>八</td>\r\n<td>hachi</td>\r\n</tr>\r\n<tr>\r\n<td>9</td>\r\n<td>九</td>\r\n<td>kyuu/ku</td>\r\n</tr>\r\n<tr>\r\n<td>10</td>\r\n<td>十</td>\r\n<td>juu</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h3>5. Pelajaran Tambahan</h3>\r\n<ul>\r\n<li><strong>Perkenalan Diri</strong>: Latih memperkenalkan diri dengan menyebutkan nama, usia, dan hobi.</li>\r\n<li><strong>Kata Kerja Dasar</strong>: Pelajari beberapa kata kerja dasar seperti \"makan\" (食べる/taberu), \"minum\" (飲む/nomu), dan \"tidur\" (寝る/neru).</li>\r\n<li><strong>Pertanyaan Umum</strong>: Latih membuat pertanyaan sederhana seperti \"Apa itu?\" (それは何ですか？/Sore wa nan desu ka?) atau \"Siapa dia?\" (彼は誰ですか？/Kare wa dare desu ka?).</li>\r\n</ul>', '2024-11-13 23:56:18'),
(58, 33, 0, '1.3 woi haha', '<h3>1. Tata Bahasa Dasar</h3>\r\n<h4>a. Struktur Kalimat</h4>\r\n<p class=\"mb-2 last:mb-0\">Struktur kalimat dasar dalam bahasa Jepang adalah&nbsp;<strong>Subjek + Objek + Kata Kerja</strong>. Contoh:</p>\r\n<ul>\r\n<li>私はリンゴを食べます。<br>(Watashi wa ringo o tabemasu.)<br><em>Saya makan apel.</em></li>\r\n</ul>\r\n<h4>b. Partikel</h4>\r\n<p class=\"mb-2 last:mb-0\">Partikel adalah kata yang digunakan untuk menunjukkan hubungan antara kata dalam kalimat. Beberapa partikel penting:</p>\r\n<ul>\r\n<li><strong>は (wa)</strong>: Menunjukkan subjek.</li>\r\n<li><strong>を (o)</strong>: Menunjukkan objek.</li>\r\n<li><strong>に (ni)</strong>: Menunjukkan arah atau waktu.</li>\r\n<li><strong>で (de)</strong>: Menunjukkan tempat atau cara.</li>\r\n<li><strong>と (to)</strong>: Menunjukkan \"dan\" atau \"dengan\".</li>\r\n</ul>\r\n<h4>c. Bentuk Kata Kerja</h4>\r\n<p class=\"mb-2 last:mb-0\">Kata kerja dalam bahasa Jepang memiliki beberapa bentuk:</p>\r\n<ul>\r\n<li><strong>Bentuk Dasar</strong>: 食べる (taberu) - makan</li>\r\n<li><strong>Bentuk Masa Lalu</strong>: 食べた (tabeta) - telah makan</li>\r\n<li><strong>Bentuk Negatif</strong>: 食べない (tabenai) - tidak makan</li>\r\n<li><strong>Bentuk Masa Depan</strong>: 食べます (tabemasu) - akan makan</li>\r\n</ul>\r\n<h3>2. Kosakata Lanjutan</h3>\r\n<p class=\"mb-2 last:mb-0\">Berikut adalah beberapa kosakata lanjutan yang dapat digunakan dalam percakapan sehari-hari:</p>\r\n<table>\r\n<thead>\r\n<tr>\r\n<th>Bahasa Indonesia</th>\r\n<th>Bahasa Jepang</th>\r\n<th>Romaji</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>Sekolah</td>\r\n<td>学校</td>\r\n<td>Gakkou</td>\r\n</tr>\r\n<tr>\r\n<td>Teman</td>\r\n<td>友達</td>\r\n<td>Tomodachi</td>\r\n</tr>\r\n<tr>\r\n<td>Makanan</td>\r\n<td>食べ物</td>\r\n<td>Tabemono</td>\r\n</tr>\r\n<tr>\r\n<td>Minuman</td>\r\n<td>飲み物</td>\r\n<td>Nomimono</td>\r\n</tr>\r\n<tr>\r\n<td>Buku</td>\r\n<td>本</td>\r\n<td>Hon</td>\r\n</tr>\r\n<tr>\r\n<td>Mobil</td>\r\n<td>車</td>\r\n<td>Kuruma</td>\r\n</tr>\r\n<tr>\r\n<td>Musik</td>\r\n<td>音楽</td>\r\n<td>Ongaku</td>\r\n</tr>\r\n<tr>\r\n<td>Film</td>\r\n<td>映画</td>\r\n<td>Eiga</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h3>3. Frasa Lanjutan</h3>\r\n<p class=\"mb-2 last:mb-0\">Berikut adalah beberapa frasa yang lebih kompleks untuk membantu dalam percakapan:</p>\r\n<ul>\r\n<li><strong>Saya suka ...</strong>\r\n<ul>\r\n<li>私は...が好きです。 (Watashi wa ... ga suki desu.)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Saya tidak suka ...</strong>\r\n<ul>\r\n<li>私は...が好きじゃないです。 (Watashi wa ... ga suki janai desu.)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Apa hobi Anda?</strong>\r\n<ul>\r\n<li>あなたの趣味は何ですか？ (Anata no shumi wa nan desu ka?)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Saya berasal dari ...</strong>\r\n<ul>\r\n<li>私は...から来ました。 (Watashi wa ... kara kimashita.)</li>\r\n</ul>\r\n</li>\r\n<li><strong>Bagaimana cuacanya hari ini?</strong>\r\n<ul>\r\n<li>今日は天気はどうですか？ (Kyou wa tenki wa dou desu ka?)</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n<h3>4. Kata Kerja Tambahan</h3>\r\n<p class=\"mb-2 last:mb-0\">Berikut adalah beberapa kata kerja yang sering digunakan:</p>\r\n<table>\r\n<thead>\r\n<tr>\r\n<th>Kata Kerja</th>\r\n<th>Romaji</th>\r\n<th>Arti</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>行く</td>\r\n<td>iku</td>\r\n<td>pergi</td>\r\n</tr>\r\n<tr>\r\n<td>来る</td>\r\n<td>kuru</td>\r\n<td>datang</td>\r\n</tr>\r\n<tr>\r\n<td>見る</td>\r\n<td>miru</td>\r\n<td>melihat</td>\r\n</tr>\r\n<tr>\r\n<td>聞く</td>\r\n<td>kiku</td>\r\n<td>mendengar</td>\r\n</tr>\r\n<tr>\r\n<td>読む</td>\r\n<td>yomu</td>\r\n<td>membaca</td>\r\n</tr>\r\n<tr>\r\n<td>書く</td>\r\n<td>kaku</td>\r\n<td>menulis</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h3>5. Latihan Percakapan</h3>\r\n<p class=\"mb-2 last:mb-0\">Cobalah berlatih percakapan sederhana dengan menggunakan kosakata dan frasa yang telah dipelajari:</p>\r\n<p class=\"mb-2 last:mb-0\"><strong>Contoh Percakapan:</strong></p>\r\n<ul>\r\n<li>\r\n<p class=\"mb-2 last:mb-0\">A: こんにちは！あなたの名前は何ですか？<br>(Konnichiwa! Anata no namae wa nan desu ka?)<br><em>Halo! Siapa nama Anda?</em></p>\r\n</li>\r\n<li>\r\n<p class=\"mb-2 last:mb-0\">B: 私の名前はアリです。あなたは？<br>(Watashi no namae wa Ari desu. Anata wa?)<br><em>Nama saya Ari. Anda?</em></p>\r\n</li>\r\n<li>\r\n<p class=\"mb-2 last:mb-0\">A: 私はジョンです。あなたの趣味は何ですか？<br>(Watashi wa Jon desu. Anata no shumi wa nan desu ka?)<br><em>Nama saya Jon. Apa hobi Anda?</em></p>\r\n</li>\r\n<li>\r\n<p class=\"mb-2 last:mb-0\">B: 私は音楽が好きです。あなたは？<br>(Watashi wa ongaku ga suki desu. Anata wa?)<br><em>Saya suka musik. Anda?</em></p>\r\n</li>\r\n</ul>\r\n<h3>6. Latihan Menulis</h3>\r\n<p class=\"mb-2 last:mb-0\">Cobalah untuk menulis kalimat-kalimat sederhana menggunakan kosakata dan struktur kalimat yang telah dipelajari. Misalnya:</p>\r\n<ul>\r\n<li>Saya pergi ke sekolah setiap hari</li>\r\n</ul>', '2024-11-14 00:39:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_chapters_guest`
--

CREATE TABLE `sub_chapters_guest` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sub_chapters_guest`
--

INSERT INTO `sub_chapters_guest` (`id`, `chapter_id`, `title`, `content`, `created_at`) VALUES
(9, 9, '1.1 juniji', '<h2 class=\"wp-block-heading\" style=\"box-sizing: border-box; margin-block: 0.5rem 1rem; font-family: var( --e-global-typography-primary-font-family ), Sans-serif; font-weight: var( --e-global-typography-primary-font-weight ); line-height: 1.2; color: var( --e-global-color-primary ); font-size: 2rem;\">Contoh kalimat perkenalan diri dalam bahasa Jepang</h2>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Nah bagaimana sih contoh jikoshoukai bahasa Jepang? berikut Lister berikan contohnya untuk berbagai situasi.</p>\r\n<h3 class=\"wp-block-heading\" style=\"box-sizing: border-box; margin-block: 0.5rem 1rem; font-family: var( --e-global-typography-secondary-font-family ), Sans-serif; font-weight: var( --e-global-typography-secondary-font-weight ); line-height: 1.2; color: var( --e-global-color-text ); font-size: 1.75rem;\">1. Contoh Perkenalan diri dalam bahasa Jepang di sekolah</h3>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Berikut Lister berikan contoh Perkenalan Diri dalam Bahasa Jepang dan Indonesia.</p>\r\n<figure class=\"wp-block-table\" style=\"margin: 0px 0px 1em; box-sizing: border-box; overflow-x: auto; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">\r\n<table style=\"background-color: transparent; width: 631.95px; margin-block-end: 15px; font-size: 0.9em; border-spacing: 0px; border-collapse: collapse;\">\r\n<tbody style=\"box-sizing: border-box;\">\r\n<tr style=\"box-sizing: border-box;\">\r\n<td class=\"has-text-align-center\" style=\"box-sizing: border-box; padding: 0.5em; line-height: 1.5; vertical-align: top; border: 1px solid; text-align: center; background-color: rgba(128, 128, 128, 0.07);\" data-align=\"center\"><span style=\"box-sizing: border-box; font-weight: bolder;\">Jikoshoukai bahasa Jepang</span></td>\r\n<td class=\"has-text-align-center\" style=\"box-sizing: border-box; padding: 0.5em; line-height: 1.5; vertical-align: top; border: 1px solid; text-align: center; background-color: rgba(128, 128, 128, 0.07);\" data-align=\"center\"><span style=\"box-sizing: border-box; font-weight: bolder;\">Jikoshoukai bahasa Indonesia</span></td>\r\n</tr>\r\n<tr style=\"box-sizing: border-box;\">\r\n<td class=\"has-text-align-center\" style=\"box-sizing: border-box; padding: 0.5em; line-height: 1.5; vertical-align: top; border: 1px solid; text-align: center;\" data-align=\"center\">こんにちは、みなさん。はじめまして。<br style=\"box-sizing: border-box;\" />私の名前はトモです。<br style=\"box-sizing: border-box;\" />横浜から来ました。<br style=\"box-sizing: border-box;\" />15歳です。<br style=\"box-sizing: border-box;\" />朝のサイクリングが好きで、自転車部に入っています。<br style=\"box-sizing: border-box;\" />将来はアスリートになりたいと思っています。<br style=\"box-sizing: border-box;\" />友達になれてうれしいです。これからよろしくお願いします！</td>\r\n<td class=\"has-text-align-center\" style=\"box-sizing: border-box; padding: 0.5em; line-height: 1.5; vertical-align: top; border: 1px solid; text-align: center;\" data-align=\"center\">Nama saya Tomo, saya berasal dari Yokohama, saya berumur 15 tahun. Saya menyukai sepeda pagi dan saya ikut club sepeda. Jika sudah besar saya ingin menjadi atlit. Senang bertemu teman-teman, semoga kita bisa berteman dengan baik!</td>\r\n</tr>\r\n<tr style=\"box-sizing: border-box;\">\r\n<td class=\"has-text-align-center\" style=\"box-sizing: border-box; padding: 0.5em; line-height: 1.5; vertical-align: top; border: 1px solid; text-align: center; background-color: rgba(128, 128, 128, 0.07);\" data-align=\"center\">こんにちは、みなさん。はじめまして。<br style=\"box-sizing: border-box;\" />私の名前はリサです。<br style=\"box-sizing: border-box;\" />16歳で、京都から来ました。<br style=\"box-sizing: border-box;\" />高校2年生です。<br style=\"box-sizing: border-box;\" />趣味は音楽を聴くことと、テニスをすることです。<br style=\"box-sizing: border-box;\" />これから皆さんと一緒に楽しく勉強したいと思っています。<br style=\"box-sizing: border-box;\" />どうぞよろしくお願いします。</td>\r\n<td class=\"has-text-align-center\" style=\"box-sizing: border-box; padding: 0.5em; line-height: 1.5; vertical-align: top; border: 1px solid; text-align: center; background-color: rgba(128, 128, 128, 0.07);\" data-align=\"center\">Halo semuanya. Senang bertemu dengan kalian.<br style=\"box-sizing: border-box;\" />Nama saya Risa.<br style=\"box-sizing: border-box;\" />Saya berusia 16 tahun dan berasal dari Kyoto.<br style=\"box-sizing: border-box;\" />Saya siswa kelas 2 SMA.<br style=\"box-sizing: border-box;\" />Hobi saya adalah mendengarkan musik dan bermain tenis.<br style=\"box-sizing: border-box;\" />Saya berharap bisa belajar dengan menyenangkan bersama kalian semua.<br style=\"box-sizing: border-box;\" />Mohon kerjasamanya.</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<figcaption class=\"wp-element-caption\" style=\"box-sizing: border-box; font-size: 16px; color: #333333; line-height: 1.4; font-style: italic;\">Perkenalan Diri dalam Bahasa Jepang</figcaption>\r\n</figure>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">&nbsp;</p>\r\n<h3 class=\"wp-block-heading\" style=\"box-sizing: border-box; margin-block: 0.5rem 1rem; font-family: var( --e-global-typography-secondary-font-family ), Sans-serif; font-weight: var( --e-global-typography-secondary-font-weight ); line-height: 1.2; color: var( --e-global-color-text ); font-size: 1.75rem;\">2. Contoh perkenalan diri dalam bahasa Jepang saat interview</h3>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\"><span style=\"box-sizing: border-box; font-weight: bolder;\">a. Perkenalan 1</span></p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">おはようございます。本日お会いできてうれしいです。<br style=\"box-sizing: border-box;\" />私の名前はミシェルです。<br style=\"box-sizing: border-box;\" />カナダ出身で、大阪にいる叔父のもとに来ています。<br style=\"box-sizing: border-box;\" />25歳です。<br style=\"box-sizing: border-box;\" />京都大学で環境衛生を専攻しました。<br style=\"box-sizing: border-box;\" />この会社で、私の知識を役立てることができればと思っています。<br style=\"box-sizing: border-box;\" />どうぞよろしくお願いいたします。</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\"><em style=\"box-sizing: border-box;\">Ohayou gozaimasu. Honjitsu oai dekite ureshii desu. Watashi no namae wa Misheru desu. Nijuu go-sai desu. Kyouto daigaku de kankyou eisei o senkou shimashita. Kono kaisha de, watashi no chishiki o yakudateru koto ga dekireba to omotteimasu. Douzo yoroshiku onegai itashimasu</em></p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Selamat pagi, saya senang bisa bertemu anda hari ini.</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Nama saya Michele, saya berasal dari Canada dan disini saya ikut paman di Osaka. Saya berumur 25 tahun. Saya merupakan lulusan Kyoto University di jurusan Kesehatan Lingkungan. Saya harap ilmu saya bisa diterapkan di perusahaan ini. Mohon bantuannya agar bisa bekerja sama dengan baik!</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\"><span style=\"box-sizing: border-box; font-weight: bolder;\">b. Perkenalan 2</span></p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">こんにちは、はじめまして。Konnichiwa, hajimemashite<br style=\"box-sizing: border-box;\" />私の名前はヒロミです。Watashi no namae wa Hiromi desu<br style=\"box-sizing: border-box;\" />33歳で、現在ニュー東京ホテルでシェフとして働いています。<br style=\"box-sizing: border-box;\" />自然や釣りが好きです。Sanjuu san-sai de, genzai Nyuutoukyou Hoteru de shefu toshite hataraiteimasu Shizen ya tsuri ga suki desu<br style=\"box-sizing: border-box;\" />もしシェフでなければ、ツアーガイドとして働きたいと思っています。Moshi shefu de nakereba, tsuaa gaido toshite hatarakitai to omotteimasu<br style=\"box-sizing: border-box;\" />どうぞよろしくお願いいたします。Douzo yoroshiku onegai itashimasu</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Halo, perkenalkan nama saya Hiromi, Sya berumur 33 tahun dan sekarang bekerja sebagai Cheff di Hotel New Tokyo. Saya menyukai alam dan memancing. Jika tidak bekerja sebagai Cheff saya ingin bekerja sebagai tour guide. Salam kenal!</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Jadi itulah beberapa contoh perkenalan diri dalam bahasa Jepang atau Jikoshoukai yang bisa kamu pelajari dari Lister.</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Improve bahasa Jepangmu dengan baik sehingga bisa memperkenalkan diri dengan baik pula.</p>\r\n<h2 class=\"wp-block-heading\" style=\"box-sizing: border-box; margin-block: 0.5rem 1rem; font-family: var( --e-global-typography-primary-font-family ), Sans-serif; font-weight: var( --e-global-typography-primary-font-weight ); line-height: 1.2; color: var( --e-global-color-primary ); font-size: 2rem;\">Belajar bahasa Jepang All Skill bersama Lister</h2>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Lister menghadirkan kursus bahasa Jepang yang bisa membantu kamu belajar bahasa Jepang untuk keperluan komunnikasi sehari-hari.</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Termasuk urusan Jikoshoukai dan juga improve kemampuan komunikasi seperti percakapan maupun kosakata.</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Kelas dilakukan secara&nbsp;<span style=\"box-sizing: border-box; font-weight: bolder;\">online&nbsp;</span>yang dipandu oleh tutor terbaik dan berpengalaman. Banyak tutor Lister yang merupakan lulusan dari kampus luar negeri maupun kampus TOP di Indonesia.</p>\r\n<p style=\"box-sizing: border-box; margin-block: 0px 0.9rem; color: #0c0c0c; font-family: Rubik, sans-serif; font-size: 18px;\">Kamu juga bisa ambil kelas trial terlebih dahulu, 2x pertemuan mulai dari 399ribu! Dapatkan pengalaman belajar bersama Lister!</p>', '2024-11-14 02:07:25'),
(16, 9, 'yutub', '<p><iframe title=\"YouTube video player\" src=\"https://www.youtube.com/embed/6npZg0DwE0I?si=F0t8TgCZi6k_mcVS\" width=\"800\" height=\"450\" frameborder=\"0\" allowfullscreen=\"allowfullscreen\"></iframe></p>', '2024-11-14 16:48:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','instructor','student') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_blocked` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `phone`, `is_blocked`, `created_at`, `profile_picture`) VALUES
(1, 'admin', '$2y$10$nR8f8eW2FGqefrG4BJEHpe2ZCptZ8KGd0qigJ0EO6zL58/K.W38gq', 'admin', 'dzakikurniawan26@gmail.com', '085864139786', 0, '2024-10-16 01:41:03', NULL),
(2, 'zakzak', '$2y$10$WFZ1f.Is6B0Fx/ngISnA6uXFdPeFCoXKcN0CtcuioMwV8bzI.eYw.', 'student', 'woilah90@gmail.com', '08373847821', 0, '2024-10-16 01:48:52', '6728d5953777b.jpeg'),
(3, 'instruktur', '$2y$10$sbLXmXk2H5cOGigakovRu.5zOMsPu2ro6m5Lz6l8gUCtkRtX/1o9G', 'instructor', 'admin@gmail.com', '02392847561', 0, '2024-10-16 01:56:49', NULL),
(5, 'fufufafa', '$2y$10$PgYAh7L/fjUA8IIVJVS9z.gHN2V7aY/6tnodhAQcg7HQaCWEwfEFC', 'student', 'fufufafa@gmail.com', '08637463123', 0, '2024-10-24 04:30:17', NULL),
(6, 'nate', '$2y$10$0Jie/F706FKtdYNmliT8w.mpbsHhU/cVog3/cmale7sM4y5iVr9Ym', 'student', 'nateriver@gmail.com', '08373847820', 0, '2024-10-30 05:49:06', NULL),
(7, 'riky', '$2y$10$93/I/mGZX6plRDRsbxINIO0GJEqfVfPCW7hIqhHxm8CdL6V35JD96', 'instructor', 'hatese.riky@gmail.com', '+62 838 0813 2228', 0, '2024-11-01 05:39:10', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indeks untuk tabel `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indeks untuk tabel `chapters_guest`
--
ALTER TABLE `chapters_guest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indeks untuk tabel `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indeks untuk tabel `courses_guest`
--
ALTER TABLE `courses_guest`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indeks untuk tabel `guest_access`
--
ALTER TABLE `guest_access`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_ibfk_1` (`chapter_id`);

--
-- Indeks untuk tabel `quiz_completions`
--
ALTER TABLE `quiz_completions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_completion` (`student_id`,`quiz_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indeks untuk tabel `student_progress`
--
ALTER TABLE `student_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_progress` (`student_id`,`course_id`,`chapter_id`,`sub_chapter_id`,`quiz_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `chapter_id` (`chapter_id`),
  ADD KEY `sub_chapter_id` (`sub_chapter_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indeks untuk tabel `sub_chapters`
--
ALTER TABLE `sub_chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Indeks untuk tabel `sub_chapters_guest`
--
ALTER TABLE `sub_chapters_guest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `chapters_guest`
--
ALTER TABLE `chapters_guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `courses_guest`
--
ALTER TABLE `courses_guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `guest_access`
--
ALTER TABLE `guest_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT untuk tabel `quiz_completions`
--
ALTER TABLE `quiz_completions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- AUTO_INCREMENT untuk tabel `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT untuk tabel `sub_chapters`
--
ALTER TABLE `sub_chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT untuk tabel `sub_chapters_guest`
--
ALTER TABLE `sub_chapters_guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `access`
--
ALTER TABLE `access`
  ADD CONSTRAINT `access_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `access_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chapters`
--
ALTER TABLE `chapters`
  ADD CONSTRAINT `chapters_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chapters_guest`
--
ALTER TABLE `chapters_guest`
  ADD CONSTRAINT `chapters_guest_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses_guest` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `quiz_completions`
--
ALTER TABLE `quiz_completions`
  ADD CONSTRAINT `quiz_completions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_completions_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `student_progress`
--
ALTER TABLE `student_progress`
  ADD CONSTRAINT `student_progress_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_progress_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_progress_ibfk_3` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_progress_ibfk_4` FOREIGN KEY (`sub_chapter_id`) REFERENCES `sub_chapters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_progress_ibfk_5` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sub_chapters`
--
ALTER TABLE `sub_chapters`
  ADD CONSTRAINT `sub_chapters_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sub_chapters_guest`
--
ALTER TABLE `sub_chapters_guest`
  ADD CONSTRAINT `sub_chapters_guest_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters_guest` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

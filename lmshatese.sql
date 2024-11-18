-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Okt 2024 pada 07.14
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
(16, 19, 'Materi 1', '', '2024-10-23 03:23:40', 1),
(17, 19, 'Materi 2', '', '2024-10-23 03:23:50', 2);

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
(19, 'Jikoshukai', '<p>-asd</p>', 3, '67186c32d8cd1.jpeg');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `courses_guest`
--

INSERT INTO `courses_guest` (`id`, `title`, `description`, `instructor_id`, `cover_image`, `created_at`) VALUES
(2, 'Testing', 'Halo ini test', 0, '6715caad71dae_coverjapan3.jpeg', '2024-10-21 03:29:49'),
(5, 'Gintsuki', 'Slss', 0, '6715eb570c608_coverjapan2.jpeg', '2024-10-21 05:49:11'),
(6, 'Seirei mahou', 'Jmais', 0, '6715ec0433032_coverjapan.jpeg', '2024-10-21 05:52:04');

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
(12, 2, 19, '2024-10-23 03:28:00');

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
(23, 16, 'Where', 'why', 'Dimana', 'Kemana', 'Sama siapa', 'b'),
(24, 16, 'Tsuki', 'suka', 'bulan', 'senang', 'rindu', 'b'),
(25, 16, 'jajang myeon', 'korea', 'indonesia', 'jepang', 'taiwan', 'a');

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
-- Struktur dari tabel `sub_chapters`
--

CREATE TABLE `sub_chapters` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sub_chapters`
--

INSERT INTO `sub_chapters` (`id`, `chapter_id`, `title`, `content`, `created_at`) VALUES
(22, 16, '1.1 Konten pertama', '<h2>Masalah pada Proses Pengembangan Aplikasi</h2>\\r\\n<p dir=\\\"ltr\\\"><em>Jalan-jalan ke kota Banjarmasin</em><em><br>Jangan lupa membeli bakmi</em><em><br>Hai para Developer dan IT Operations</em><em><br>Selamat datang di Dicoding Academy</em></p>\\r\\n<p dir=\\\"ltr\\\">Bagaimana pantunnya? Bagus, kan? Pantun di atas kami dedikasikan untuk Anda para Developer dan IT Operations (atau siapa pun yang telah memenuhi prasyarat kemampuan) yang siap menimba ilmu sebanyak-banyaknya di kelas ini.</p>\\r\\n<p dir=\\\"ltr\\\">Sebagai seorang Developer, IT Operations, atau apa pun jenis profesi IT yang digeluti saat ini, kami yakin nyaris separuh dari Anda mungkin sudah pernah berkecimpung dalam proses pengembangan aplikasi. Jika belum, setidaknya mungkin sedikit familier dengan proses tersebut.</p>\\r\\n<p dir=\\\"ltr\\\">Kita semua tahu bahwa proses pengembangan aplikasi itu kompleks, bahkan di beberapa kasus bisa jadi melibatkan banyak sekali pihak. Salah satu problem yang cukup menjengkelkan adalah dari model pengembangan aplikasi itu sendiri. Pada kenyataannya, masih banyak perusahaan dan organisasi yang menggunakan model tradisional nan penuh tantangan seperti Waterfall hingga kini. Memang apa masalahnya? Nanti coba kita ulik bersama.</p>\\r\\n<p dir=\\\"ltr\\\">Tidak hanya dari segi model, ada juga beberapa masalah lain yang menghantui proses pengembangan aplikasi, seperti arsitektur yang monolitik dan proses yang manual. Selain itu, struktur tim yang tertutup pun bisa menjadi&nbsp;<em>bottleneck&nbsp;</em>sehingga menyebabkan keterlambatan dan ketidakefisienan dalam proses penyajian (<em>delivery</em>) aplikasi. Faktor-faktor tersebut juga bisa mengakibatkan kegagalan tim dalam menghadirkan aplikasi yang stabil dan berkualitas tinggi. Tentu ini menjadi mimpi buruk bagi perusahaan.</p>\\r\\n<p dir=\\\"ltr\\\">Oke, supaya lebih detail dalam memahami masalah-masalah ini, mending langsung saja kita bedah satu per satu.</p>\\r\\n<p dir=\\\"ltr\\\">&nbsp;</p>\\r\\n<h3 dir=\\\"ltr\\\">Model Waterfall</h3>\\r\\n<p dir=\\\"ltr\\\">Waterfall adalah salah satu dari sekian banyak model proses pengembangan aplikasi (alias SDLC atau Software Development Life Cycle). Model Waterfall ini merupakan metode kerja yang menekankan fase-fase yang berurutan dan sistematis. Disebut waterfall lantaran proses yang terjadi dalam mengembangkan sebuah perangkat lunak atau aplikasi mengalir satu arah &ldquo;ke bawah&rdquo; bak air terjun.</p>\\r\\n<p dir=\\\"ltr\\\">Gambar di bawah ini adalah contoh sederhana dari penerapan model Waterfall.&nbsp;</p>\\r\\n<p dir=\\\"ltr\\\"><a class=\\\"zoomable-image-anchor\\\" title=\\\"dos:c9f9f9ce1d1a22b04ede00d0c9328b0820220517120257.jpeg\\\" href=\\\"https://www.dicoding.com/academies/382/tutorials/24073\\\" data-toggle=\\\"modal\\\" data-target=\\\"#image-zoom-modal\\\"><img class=\\\"fr-fic fr-dii\\\" src=\\\"https://dicoding-web-img.sgp1.cdn.digitaloceanspaces.com/original/academy/dos:c9f9f9ce1d1a22b04ede00d0c9328b0820220517120257.jpeg\\\" alt=\\\"dos:c9f9f9ce1d1a22b04ede00d0c9328b0820220517120257.jpeg\\\"></a></p>\\r\\n<p dir=\\\"ltr\\\">Pada model waterfall, setiap fase saling bergantung satu sama lain. Kita tak bisa lanjut ke fase berikutnya sebelum fase yang sedang dikerjakan saat ini benar-benar selesai. Misalnya, kita tak bisa melakukan pengujian (<em>test</em>) jika proses pengodean (<em>code</em>) dari keseluruhan aplikasi belum beres. Begitu juga dengan proses deploy, kita tidak bisa men-<em>deploy</em>&nbsp;aplikasi jika keseluruhan komponen belum lolos fase pengujian (<em>test</em>). Begitu seterusnya.</p>', '2024-10-23 03:25:23'),
(23, 16, '1.2 Ngoding', '<h3>Penjelasan Perubahan:</h3>\\r\\n<ol>\\r\\n<li><strong>Query untuk Kuis</strong>: Menggunakan <code>chapter_id</code> dari sub-chapter untuk mendapatkan kuis yang terkait. Hal ini mencegah pencarian kolom yang tidak ada (<code>sub_chapter_id</code>).</li>\\r\\n<li><strong>Validasi Input</strong>: Memastikan bahwa <code>course_id</code> dan <code>sub_chapter_id</code> tidak bernilai 0 sebelum melanjutkan.</li>\\r\\n<li><strong>Pengambilan Sub-Chapter</strong>: Memastikan sub-chapter yang diambil berasal dari kursus yang relevan.</li>\\r\\n</ol>\\r\\n<h3>3. <strong>Periksa Database</strong></h3>\\r\\n<p>Jika kolom <code>sub_chapter_id</code> diperlukan dalam logika Anda, pastikan untuk memperbarui skema database untuk menambahkannya ke tabel <code>quizzes</code>.</p>\\r\\n<p>Setelah melakukan perubahan ini, jalankan kembali aplikasi Anda dan lihat apakah error masih muncul. Jika ada masalah lebih lanjut, berikan detail error yang baru jika ada.</p>', '2024-10-23 03:26:22'),
(24, 17, '2.2 gtre', '<h3>Penjelasan Perubahan:</h3>\\r\\n<ol>\\r\\n<li><strong>Query untuk Kuis</strong>: Menggunakan <code>chapter_id</code> dari sub-chapter untuk mendapatkan kuis yang terkait. Hal ini mencegah pencarian kolom yang tidak ada (<code>sub_chapter_id</code>).</li>\\r\\n<li><strong>Validasi Input</strong>: Memastikan bahwa <code>course_id</code> dan <code>sub_chapter_id</code> tidak bernilai 0 sebelum melanjutkan.</li>\\r\\n<li><strong>Pengambilan Sub-Chapter</strong>: Memastikan sub-chapter yang diambil berasal dari kursus yang relevan.</li>\\r\\n</ol>\\r\\n<h3>3. <strong>Periksa Database</strong></h3>\\r\\n<p>Jika kolom <code>sub_chapter_id</code> diperlukan dalam logika Anda, pastikan untuk memperbarui skema database untuk menambahkannya ke tabel <code>quizzes</code>.</p>\\r\\n<p>Setelah melakukan perubahan ini, jalankan kembali aplikasi Anda dan lihat apakah error masih muncul. Jika ada masalah lebih lanjut, berikan detail error yang baru jika ada.</p>', '2024-10-23 03:27:20');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `phone`, `is_blocked`, `created_at`) VALUES
(1, 'admin', '$2y$10$nR8f8eW2FGqefrG4BJEHpe2ZCptZ8KGd0qigJ0EO6zL58/K.W38gq', 'admin', 'dzakikurniawan26@gmail.com', '085864139786', 0, '2024-10-16 01:41:03'),
(2, 'zakzak', '$2y$10$WFZ1f.Is6B0Fx/ngISnA6uXFdPeFCoXKcN0CtcuioMwV8bzI.eYw.', 'student', 'nyaawang@gmail.com', '085864139786', 0, '2024-10-16 01:48:52'),
(3, 'instruktur', '$2y$10$izYFhSpM/A3KDVves3.i8O/64h.eYTwbVBVVO1aRiXXTbwt1FyayC', 'instructor', 'admin@gmail.com', '02392847561', 0, '2024-10-16 01:56:49');

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
  ADD KEY `student_id` (`student_id`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `chapters_guest`
--
ALTER TABLE `chapters_guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `courses_guest`
--
ALTER TABLE `courses_guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `quiz_completions`
--
ALTER TABLE `quiz_completions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT untuk tabel `sub_chapters`
--
ALTER TABLE `sub_chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `sub_chapters_guest`
--
ALTER TABLE `sub_chapters_guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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

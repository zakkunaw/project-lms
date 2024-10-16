-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Okt 2024 pada 06.57
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
(3, 12, 'Chapter 1 ', '<p>TATA CARA PENULISAN ARTIKEL ILMIAH</p>\\r\\n\\r\\n<p>1. PEDOMAN UMUM a. Naskah merupakan ringkasan hasil penelitian b. Naskah ditulis dengan huruf Time New Roman font 11. Panjang naskah sekitar 8&ndash;15 halaman dan diketik 1 spasi. c. Seting halaman adalah 2 kolom dengan equal with coloumn dan jarak antar kolom 5 mm, sedangkan Judul, Identitas Penulis, dan Abstract ditulis dalam 1 kolom. d. Ukuran kertas adalah A4 dengan lebar batas-batas tepi (margin) adalah 3,5 cm untuk batas atas, bawah dan kiri, sedang kanan adalah 2,0 cm. 2. SISTIMATIKA PENULISAN a. Bagian awal : judul, nama penulis, abstraksi. b. Bagian utama : berisi pendahuluan, Kajian literature dan pengembangan hipotesis (jika ada), cara/metode penelitian, hasil penelitian dan pembahasan, dan kesimpulan dan saran (jika ada). c. Bagian akhir : ucapan terima kasih (jika ada), keterangan simbol (jika ada), dan daftar pustaka. 3. JUDUL DAN NAMA PENULIS a. Judul dicetak dengan huruf besar/kapital, dicetak tebal (bold) dengan jenis huruf Times New Roman font 12, spasi tunggal dengan jumlah kata maksimum 15. b. Nama penulis ditulis di bawah judul tanpa gelar, tidak boleh disingkat, diawali dengan huruf kapital, tanpa diawali dengan kata &rdquo;oleh&rdquo;, u penulis kedua, ketiga dan seterusnya. c. Nama perguruan tinggi dan alamat surel (email) semua penulis ditulis di bawah nama penulis dengan huruf Times New Roman font 10.</p>\\r\\n', '2024-10-16 04:34:13', 1),
(4, 12, 'Chapter 2', '<p>Template Artikel Ilmiah JUDUL DITULIS DENGAN FONT TIMES NEW ROMAN 12 CETAK TEBAL (MAKSIMUM 12 KATA) Mahasiswa1 , Pembimbing12 , Pembimbing23 (Font Times New Roman 10 Cetak Tebal dan NamaTidak Boleh Disingkat) 123Nama Prodi Nama Jurusan Fakultas Seni dan Desain 1Email penulis1@cde.ac.id 2Email penulis1@cde.ac.id 3Email penulis1@cde.ac.id [Font Times New Roman 10 spasi tunggal, tidak di cetak tebal] Abstract [Times New Roman 11 Cetak Tebal dan Miring] Abstrak bahasa Inggris/Indonesia. tidak boleh lebih dari 200 kata, boleh berkisar antara 80 - 100 kata, dalam satu alinea tanpa acuan (referensi) tanpa singkatan/akronim, dan tanpa footnote. Abstrak ditulis bukan dalam bentuk matematis, pertanyaan, dan dugaan. Abstrak berisi: tujuan penelitian, metode pelaksanaan, teknik analisis dan hasil kegiatan. Disajikan dengan rata kiri dan rata kanan, diketik dalam satu paragraph, dan ditulis tanpa menjorok (indent) pada awal kalimat. dengan font Times New Roman huruf 11, spasi tunggal,. Keywords: Maksimum 5 kata kunci dipisahkan dengan tanda koma. [Font Times New Roman 11 spasi tunggal, dan cetak miring] PENDAHULUAN Bagian pendahuluan berisi latar belakang, konteks penelitian, urgensi permasalahan, hasil kajian pustaka utama yang menjadi landasan penelitian, hasil-hasil riset sebelumnya yang relevan dengan kajian penelitian, dan tujuan penelitian. Penulis sangat disarankan menggunakan referensi artikel jurnal bereputasi dari terbitan terbaru untuk dijadikan landasan penelitian. Seluruh bagian pendahuluan disajikan secara terintegrasi dalam bentuk paragraf, tidak dibagi bagian perbagian yang ditulis dengan model pembaban laporan penelitian/skripsi/tesis disertasi. Panjang bagian pendahuluan 15&mdash;20 % dari total naskah. [Times New Roman, 11, normal, Spasi 1]. Lihat terbitan TANRA sebelumnya untuk menyesuaikan isi tulisan dan gaya selingkung. METODE Bagian Metode penelitian menjelaskan tentang: pendekatan, ruang lingkup atau objek, definisi operasional variable/deskripsi fokus penelitian, tempat, populasi dan sampel/informan, bahan dan alat utama, teknik pengumpulan data, dan teknik analisis data. Bagian ini berisi uraian prosedur dan langkah-langkah penelitian yang bersifat khas sesuai dengan topik yang dikaji. Panjang bagian metode berkisar 10&mdash;15 % total panjang naskah. Seluruh bagian meotode juga disajikan secara terintegrasi dalam bentuk paragraf, tidak dibagi bagian perbagian yang ditulis dengan model pembaban laporan penelitian/skripsi/tesis disertasi [Times New Roman, 11, normal, Spasi 1]. HASIL DAN PEMBAHASAN Hasil Sub judul di hasil, tuliskan dengan huruf kapital diawal kata saja, bold. Seperti bagian yang lain, tidak diberikan nomor, huruf, atau bullet. Bahasa asing, bahasa daerah, dan istilah tidak baku dicetak dengan huruf miring. Bagian hasil dan pembahasan menyajikan hasil penelitian. Hasil penelitian disajikan dengan lengkap dan sesuai ruang lingkup penelitian. Hasil penelitian dapat dilengkapi dengan tabel, grafik (gambar), dan/atau bagan. Tabel dan gambar diberi nomor dan judul. Hasil analisis data dimaknai dengan benar. Hasil penelitian dapat dilengkapi dengan tabel, grafik (gambar), dan/atau bagan. [Times New Roman, 11, normal]. Tabel 1. Judul Tabel dengan Bold, Spasi 1,</p>\\r\\n', '2024-10-16 04:35:57', 2);

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
(12, 'Jikoshukai', 'Mengenal dan belajar lebih dalam bahasa jepang untuk pemula', 3, '670f420e971a1.jpeg');

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
(5, 2, 12, '2024-10-16 04:36:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `sub_chapter_id` int(11) NOT NULL,
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

INSERT INTO `quizzes` (`id`, `sub_chapter_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(3, 3, 'Jikoshukai adalah', 'kenal', 'perkenalan', 'jancuk', 'pemerintah', 'b'),
(4, 4, 'TEST', 'TEST', 'test', 'tset', 'teset', 'a');

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

--
-- Dumping data untuk tabel `quiz_completions`
--

INSERT INTO `quiz_completions` (`id`, `student_id`, `quiz_id`, `is_passed`, `completed_at`) VALUES
(1, 2, 3, 0, '2024-10-16 04:50:12'),
(2, 2, 3, 1, '2024-10-16 04:50:19'),
(3, 2, 3, 0, '2024-10-16 04:50:32'),
(4, 2, 3, 1, '2024-10-16 04:55:34');

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
(3, 3, '1.1 Apa itu naruhodo', '<p>Template Artikel Ilmiah JUDUL DITULIS DENGAN FONT TIMES NEW ROMAN 12 CETAK TEBAL (MAKSIMUM 12 KATA) Mahasiswa1 , Pembimbing12 , Pembimbing23 (Font Times New Roman 10 Cetak Tebal dan NamaTidak Boleh Disingkat) 123Nama Prodi Nama Jurusan Fakultas Seni dan Desain 1Email penulis1@cde.ac.id 2Email penulis1@cde.ac.id 3Email penulis1@cde.ac.id [Font Times New Roman 10 spasi tunggal, tidak di cetak tebal] Abstract [Times New Roman 11 Cetak Tebal dan Miring] Abstrak bahasa Inggris/Indonesia. tidak boleh lebih dari 200 kata, boleh berkisar antara 80 - 100 kata, dalam satu alinea tanpa acuan (referensi) tanpa singkatan/akronim, dan tanpa footnote. Abstrak ditulis bukan dalam bentuk matematis, pertanyaan, dan dugaan. Abstrak berisi: tujuan penelitian, metode pelaksanaan, teknik analisis dan hasil kegiatan. Disajikan dengan rata kiri dan rata kanan, diketik dalam satu paragraph, dan ditulis tanpa menjorok (indent) pada awal kalimat. dengan font Times New Roman huruf 11, spasi tunggal,. Keywords: Maksimum 5 kata kunci dipisahkan dengan tanda koma. [Font Times New Roman 11 spasi tunggal, dan cetak miring] PENDAHULUAN Bagian pendahuluan berisi latar belakang, konteks penelitian, urgensi permasalahan, hasil kajian pustaka utama yang menjadi landasan penelitian, hasil-hasil riset sebelumnya yang relevan dengan kajian penelitian, dan tujuan penelitian. Penulis sangat disarankan menggunakan referensi artikel jurnal bereputasi dari terbitan terbaru untuk dijadikan landasan penelitian. Seluruh bagian pendahuluan disajikan secara terintegrasi dalam bentuk paragraf, tidak dibagi bagian perbagian yang ditulis dengan model pembaban laporan penelitian/skripsi/tesis disertasi. Panjang bagian pendahuluan 15&mdash;20 % dari total naskah. [Times New Roman, 11, normal, Spasi 1]. Lihat terbitan TANRA sebelumnya untuk menyesuaikan isi tulisan dan gaya selingkung. METODE Bagian Metode penelitian menjelaskan tentang: pendekatan, ruang lingkup atau objek, definisi operasional variable/deskripsi fokus penelitian, tempat, populasi dan sampel/informan, bahan dan alat utama, teknik pengumpulan data, dan teknik analisis data. Bagian ini berisi uraian prosedur dan langkah-langkah penelitian yang bersifat khas sesuai dengan topik yang dikaji. Panjang bagian metode berkisar 10&mdash;15 % total panjang naskah. Seluruh bagian meotode juga disajikan secara terintegrasi dalam bentuk paragraf, tidak dibagi bagian perbagian yang ditulis dengan model pembaban laporan penelitian/skripsi/tesis disertasi [Times New Roman, 11, normal, Spasi 1]. HASIL DAN PEMBAHASAN Hasil Sub judul di hasil, tuliskan dengan huruf kapital diawal kata saja, bold. Seperti bagian yang lain, tidak diberikan nomor, huruf, atau bullet. Bahasa asing, bahasa daerah, dan istilah tidak baku dicetak dengan huruf miring. Bagian hasil dan pembahasan menyajikan hasil penelitian. Hasil penelitian disajikan dengan lengkap dan sesuai ruang lingkup penelitian. Hasil penelitian dapat dilengkapi dengan tabel, grafik (gambar), dan/atau bagan. Tabel dan gambar diberi nomor dan judul. Hasil analisis data dimaknai dengan benar. Hasil penelitian dapat dilengkapi dengan tabel, grafik (gambar), dan/atau bagan. [Times New Roman, 11, normal]. Tabel 1. Judul Tabel dengan Bold, Spasi 1,</p>\\r\\n', '2024-10-16 04:34:59'),
(4, 4, '2.2', '<p>PENDAHULUAN Bagian pendahuluan berisi latar belakang, konteks penelitian, urgensi permasalahan, hasil kajian pustaka utama yang menjadi landasan penelitian, hasil-hasil riset sebelumnya yang relevan dengan kajian penelitian, dan tujuan penelitian. Penulis sangat disarankan menggunakan referensi artikel jurnal bereputasi dari terbitan terbaru untuk dijadikan landasan penelitian. Seluruh bagian pendahuluan disajikan secara terintegrasi dalam bentuk paragraf, tidak dibagi bagian perbagian yang ditulis dengan model pembaban laporan penelitian/skripsi/tesis disertasi. Panjang bagian pendahuluan 15&mdash;20 % dari total naskah. [Times New Roman, 11, normal, Spasi 1]. Lihat terbitan TANRA sebelumnya untuk menyesuaikan isi tulisan dan gaya</p>\\r\\n', '2024-10-16 04:47:01');

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
-- Indeks untuk tabel `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`);

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
  ADD KEY `sub_chapter_id` (`sub_chapter_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `quiz_completions`
--
ALTER TABLE `quiz_completions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `sub_chapters`
--
ALTER TABLE `sub_chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`sub_chapter_id`) REFERENCES `sub_chapters` (`id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

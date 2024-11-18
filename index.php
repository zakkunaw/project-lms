<?php
// index.php
session_start();
require_once 'includes/db_connect.php';

// Fetch dua kursus teratas dari tabel courses_guest
$sql_courses_guest = "SELECT * FROM courses_guest LIMIT 3";
$courses_guest = $conn->query($sql_courses_guest);

// Handle akses kursus gratis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['access_course'])) {
  $course_id = intval($_POST['course_id']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);

  // Simpan data tamu atau buat sesi
  $_SESSION['guest_email'] = $email;
  $_SESSION['guest_phone'] = $phone;

  // Redirect ke halaman kursus dengan batasan
  header("Location: courses/course.php?id=$course_id&limit=5");
  exit();
}
?>

<html lang="id">

<head>
  <style></style>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link rel="icon" href="favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/landingpage.css" type="text/css" rel="stylesheet" />
  <link href="assets/css/styles.css" type="text/css" rel="stylesheet" />
  <script src="assets/js/scripts.js" type="text/javascript"></script>
</head>

<body>

  <nav style="font-family: 'consolas' , sans-serif;"
    class="flex items-center justify-between p-4 mx-auto w-full md:w-3/4">
    <div class="flex items-center">
      <img
        alt="Company logo with text 'HKS' and a circular design"
        class="h-12 w-12"
        height="50"
        src="hateselogo.png"
        width="50" />
      <!-- Desktop Menu -->
      <ul class="hidden md:flex space-x-8 ml-4 text-lg">
        <!-- Ditampilkan di layar besar -->
        <li><a class="hover:underline" href="index.php">Home</a></li>
        <li><a class="hover:underline" href="index.php#roadmap">Roadmap</a></li>
        <li><a class="hover:underline" href="index.php#course">Course</a></li>
        <li><a class="hover:underline" href="index.php#contact">Contact</a></li>
      </ul>
    </div>
    <div class="hidden md:flex space-x-4">
      <!-- Ditampilkan di layar besar -->
      <a href="login.php" class="border border-black rounded-xl px-4 py-1">Login</a>
      <a href="index.php#course" class="border border-black rounded-xl px-4 py-1">
        Free Trial
      </a>
    </div>
    <!-- Hamburger Icon for Mobile -->
    <div class="md:hidden flex items-center">
      <button id="menu-toggle" class="text-black focus:outline-none">
        <svg
          class="w-6 h-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
      </button>
    </div>
  </nav>

  <!-- Overlay for when sidebar is open -->
  <div id="overlay"></div>

  <!-- Mobile Menu (Sidebar) -->
  <div id="mobile-menu" class="md:hidden flex flex-col space-y-4 p-4">
    <ul class="flex flex-col space-y-4">
      <li><a class="hover:underline" href="index.php">Home</a></li>
      <li><a class="hover:underline" href="index.php#roadmap">Roadmap</a></li>
      <li><a class="hover:underline" href="index.php#course">Course</a></li>
      <li><a class="hover:underline" href="index.php#contact">Contact</a></li>
    </ul>
    <div class="flex space-x-4 mt-4">
      <a href="login.php" class="border border-black rounded-full px-4 py-1">
        Login
      </a>
      <a href="index.php#course" class="border border-black rounded-full px-4 py-1">
        Free Trial
      </a>
    </div>
  </div>
  <!-- END NDVBAR --> <!-- END NDVBAR --> <!-- END NDVBAR --> <!-- END NDVBAR --> <!-- END NDVBAR --> <!-- END NDVBAR -->
  <!-- END NDVBAR --> <!-- END NDVBAR --> <!-- END NDVBAR --> <!-- END NDVBAR --> <!-- END NDVBAR --> <!-- END NDVBAR -->

  <!-- CAROUSEL --><!-- CAROUSEL --><!-- CAROUSEL --><!-- CAROUSEL --><!-- CAROUSEL --><!-- CAROUSEL --><!-- CAROUSEL -->
  <div class="carousel-container">
    <div class="carousel">
      <img alt="Background image of a city street with neon lights" class="desktop-image" height="350" src="image/carousel1.jpeg" width="1065" />
      <img alt="Background image of a city street with neon lights" class="mobile-image" height="450" src="image/carousel1-mb.png" style="display: none;" width="800" />
    </div>
    <div class="arrow left">
      <svg fill="none" height="42" viewbox="0 0 25 42" width="25" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" d="M1.80593 23.4125L19.627 41.398L24.0814 36.9024L8.48763 21.1647L24.0814 5.427L19.627 0.931427L1.80593 18.9169C1.21534 19.5131 0.883573 20.3216 0.883573 21.1647C0.883573 22.0077 1.21534 22.8163 1.80593 23.4125Z" fill="#2574B6" fill-rule="evenodd">
        </path>
      </svg>
    </div>
    <div class="arrow right">
      <svg fill="none" height="42" viewbox="0 0 25 42" width="25" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" d="M23.194 23.4125L5.37293 41.398L0.918457 36.9024L16.5122 21.1647L0.918457 5.427L5.37293 0.931427L23.194 18.9169C23.7845 19.5131 24.1163 20.3216 24.1163 21.1647C24.1163 22.0077 23.7845 22.8163 23.194 23.4125Z" fill="#2574B6" fill-rule="evenodd">
        </path>
      </svg>
    </div>
  </div>
  <script>
    // Fungsi resize yang tetap dipertahankan
    function handleResize() {
      const desktopImage = document.querySelector('.desktop-image');
      const mobileImage = document.querySelector('.mobile-image');

      if (window.innerWidth <= 768) {
        desktopImage.style.display = 'none';
        mobileImage.style.display = 'block';
      } else {
        desktopImage.style.display = 'block';
        mobileImage.style.display = 'none';
      }
    }

    window.addEventListener('resize', handleResize);
    window.addEventListener('load', handleResize);

    // Fungsi carousel dengan 3 gambar
    $(document).ready(function() {
      const images = [{
          desktop: 'LMS HTS/page 1.png',
          mobile: 'LMS HTS/mobile/page 1.png'
        },
        {
          desktop: 'LMS HTS/page 2.png',
          mobile: 'LMS HTS/mobile/page 2.png'
        },
        {
          desktop: 'LMS HTS/page 3.png',
          mobile: 'LMS HTS/mobile/page 3.png'
        }
      ];

      let currentIndex = 0;

      function showImage(index) {
        const desktopImage = document.querySelector('.desktop-image');
        const mobileImage = document.querySelector('.mobile-image');

        desktopImage.src = images[index].desktop;
        mobileImage.src = images[index].mobile;

        handleResize();
      }

      function nextSlide() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
      }

      function prevSlide() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(currentIndex);
      }

      // Event listener untuk tombol arrow
      $('.arrow').on('click touchstart', function(e) {
        e.preventDefault();
        if ($(this).hasClass('right')) {
          nextSlide();
        } else {
          prevSlide();
        }
        resetTimer();
      });

      // Auto slide setiap 3 detik
      let slideTimer = setInterval(nextSlide, 7000);

      function resetTimer() {
        clearInterval(slideTimer);
        slideTimer = setInterval(nextSlide, 7000);
      }

      // Touch events untuk mobile swipe
      let touchStartX = 0;
      let touchEndX = 0;

      $('.carousel').on('touchstart', function(e) {
        touchStartX = e.originalEvent.touches[0].clientX;
      });

      $('.carousel').on('touchend', function(e) {
        touchEndX = e.originalEvent.changedTouches[0].clientX;
        handleSwipe();
      });

      function handleSwipe() {
        const swipeThreshold = 50;
        const difference = touchStartX - touchEndX;

        if (Math.abs(difference) > swipeThreshold) {
          if (difference > 0) {
            nextSlide();
          } else {
            prevSlide();
          }
          resetTimer();
        }
      }

      // Inisialisasi gambar pertama
      showImage(currentIndex);

      // Menu toggle functionality yang tetap dipertahankan
      $("#menu-toggle").click(function() {
        $("#mobile-menu").toggleClass("open");
        $("#overlay").toggleClass("show");
      });

      $("#overlay").click(function() {
        $("#mobile-menu").removeClass("open");
        $("#overlay").removeClass("show");
      });
    });

    // FUNGSI TAP CARD SERVICE //#   // FUNGSI TAP CARD SERVICE //#   // FUNGSI TAP CARD SERVICE //#

            document.addEventListener('DOMContentLoaded', function() {
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach(card => {
                card.addEventListener('click', function() {
                    serviceCards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
</script>
  <!-- CAROUSEL --><!-- CAROUSEL --><!-- CAROUSEL --><!-- CAROUSEL --><!-- CAROUSEL --><!-- CAROUSEL --><!-- CAROUSEL -->

  <style>
    .container {
      border-radius: 10px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      width: 100%;
      max-width: 1105px;
    }
  </style>
  <div class="container">
<div class="marquee-section">
    <div class="marquee-bar">
        <span>Selamat Datang di LMS Hatese | Coba Kursus Kami Secara Gratis</span>
    </div>
</div>

    <div class="text-section">
        <div class="text-content">
            <h1>
                Belajar Bahasa Jepang<br />
                Mudah dan Fleksibel
            </h1>
            <p>
                Dapatkan akses belajar kapan saja, di mana saja. 
                Bergabunglah sekarang dan mulailah perjalanan Anda menuju penguasaan bahasa Jepang!
            </p>
       <button data-aos="zoom-in" onclick="window.open('https://wa.me/6285864139786?text=Hai%20saya%20tertarik%20untuk%20mendaftarkan%20akun%20di%20LMS%20Hatese%20%F0%9F%98%8A', '_blank')">Daftar Sekarang !</button>
        </div>
        <div class="image-section">
            <img src="image/japan.jpeg" alt="Placeholder image" width="100" height="100" />
        </div>
    </div>



  </div>

  <div class="container">
    <br>
    <br>
    <div class="separator" data-aos="fade-right"></div>
    <section class="course">
    <div class="course-header" data-aos="flip-up" id="course">
        <h1 style="font-family: 'Consolas', sans-serif;">Course list</h1>
        <p style="font-family: 'Consolas', sans-serif;">
          Dapatkan akses gratis untuk mencoba kursus kami. 
          Temukan cara belajar yang tepat untuk Anda dan tingkatkan keterampilan Anda!
        </p>
    </div>
      <div class="course-list" data-aos="fade-up">
        <?php while ($course = $courses_guest->fetch_assoc()): ?>
          <div class="course-item">
            <img
              class="course-image"
              alt="<?= htmlspecialchars($course['title']) ?>"
              height="275"
              src="assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>"
              width="366"
              data-toggle="modal"
              data-target="#accessCourseModal"
              data-course-id="<?= $course['id'] ?>"
              data-course-title="<?= htmlspecialchars($course['title']) ?>"
              style="cursor: pointer;" />
            <p><?= htmlspecialchars($course['title']) ?></p>
          </div>
        <?php endwhile; ?>
      </div>

    </section>
  </div>
<section class="service text-center mt-5">
    <div class="container" style="font-family: 'Consolas', sans-serif;">
        <div class="separator" data-aos="fade-right"></div>
        <h2 style="color: black;" class="section-title" data-aos="fade-up" data-aos-anchor-placement="top-bottom">Hatese Services: Pembelajaran Bahasa Jepang Online!</h2>
        <h3 style="color: black;" class="section-subtitle" data-aos="fade-up" data-aos-anchor-placement="top-bottom">Kursus Fleksibel dan Efektif</h3>
        <div class="row">
            <div class="col-md-4" data-aos="zoom-in-up">
                <div class="service-card active">
                    <i class="fas fa-book"></i>
                    <h3>Dasar Bahasa Jepang</h3>
                    <p>Pelajari dasar-dasar bahasa Jepang dengan metode yang mudah dipahami dan menyenangkan.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="zoom-in-up">
                <div class="service-card">
                    <i class="fas fa-comments"></i>
                    <h3>Komunikasi Sehari-hari</h3>
                    <p>Tingkatkan kemampuan berbicara Anda dalam bahasa Jepang untuk komunikasi sehari-hari.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="zoom-in-up">
                <div class="service-card">
                    <i class="fas fa-pencil-alt"></i>
                    <h3>Penulisan Kanji</h3>
                    <p>Belajar menulis dan memahami kanji dengan teknik yang efektif dan efisien untuk kemajuan.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="zoom-in-up">
            <div class="service-card">
                <i class="fas fa-photo-video"></i>
                <h3>Materi Media</h3> <!-- Updated title -->
                <p>Belajar bahasa Jepang melalui materi audio dan video untuk meningkatkan pemahaman Anda.</p> <!-- Expanded description -->
            </div>
            </div>
            <div class="col-md-4" data-aos="zoom-in-up">
                <div class="service-card">
                  <i class="fab fa-whatsapp"></i> <!-- WhatsApp icon -->
                  <h3>Konsultasi</h3> <!-- Updated title -->
                  <p>Bertanya langsung secara pribadi kepada Sensei sepuasnya melalui aplikasi pesan.</p>
              </div>

            </div>
            <div class="col-md-4" data-aos="zoom-in-up">
                <div class="service-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Persiapan JFT</h3>
                    <p>Persiapkan diri Anda untuk ujian JFT dengan materi dan latihan soal yang lengkap.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include Intro.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/4.2.2/introjs.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/4.2.2/intro.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Event listener for "Mulai Panduan" button
        document.getElementById('start-guide').addEventListener('click', function () {
            // Start Intro.js guide
            introJs().setOptions({
                steps: [
                    {
                        intro: "Selamat datang di roadmap kursus Bahasa Jepang!"
                    },
                    {
                        element: '#step1',
                        intro: "Tahap 1: Pengenalan Huruf. Pelajari dan latih membaca, menulis, serta pengucapan Hiragana dan Katakana."
                    },
                    {
                        element: '#step2',
                        intro: "Tahap 2: Dasar Tata Bahasa dan Kanji Awal. Mulai belajar tata bahasa dasar dan hafal Kanji awal."
                    },
                    {
                        element: '#step3',
                        intro: "Tahap 3: Tata Bahasa Menengah. Pelajari tata bahasa menengah dan tambah hafalan Kanji."
                    },
                    {
                        element: '#step4',
                        intro: "Tahap 4: Level Menengah Lanjut. Pelajari tata bahasa lanjutan dan praktek percakapan formal & informal."
                    },
                    {
                        element: '#step5',
                        intro: "Tahap 5: Latihan & Persiapan Ujian. Review materi dan latihan soal ujian untuk persiapan JFT BASIC A2 / JLPT N4."
                    }
                ]
            }).start();
        });
    });
</script>

<div class="header-roadmap" id="roadmap">
    <h1 data-aos="fade-up"
     data-aos-anchor-placement="top-bottom" >ROADMAP KURSUS BAHASA JEPANG</h1>
  </div>
  <div class="container roadmap">
    <button class="button-guide btn" style="background-color: #4F64A9; color: #ffff;" id="start-guide" data-aos="fade-up"
     data-aos-anchor-placement="top-bottom">Mulai Panduan</button>  
    <div class="column-roadmap now-roadmap" id="step1">
        <h3 data-aos="zoom-in">Tahap 1: Pengenalan Huruf</h3>
        <div class="card-roadmap">
            <div class="card-body-roadmap" data-aos="fade-right">
                <h5 class="card-title-roadmap">Hiragana</h5>
                <p class="card-text-roadmap">Pelajari dan latih membaca, menulis, serta pengucapan.</p>
            </div>
        </div>
        <div class="card-roadmap">
            <div class="card-body-roadmap" data-aos="fade-left">
                <h5 class="card-title-roadmap">Katakana</h5>
                <p class="card-text-roadmap">Fokus pada kata serapan; latih membaca, menulis, dan pengucapan.</p>
            </div>
        </div>
        <div class="card-roadmap">
            <div class="card-body-roadmap" data-aos="fade-right">
                <h5 class="card-title-roadmap">Catatan</h5>
                <p class="card-text-roadmap">Biasakan membaca kalimat sederhana sebelum belajar Kanji.</p>
            </div>
        </div>
    </div>
    <div class="column-roadmap next-roadmap" data-aos="zoom-in" id="step2">
        <h3>Tahap 2: Dasar Tata Bahasa dan Kanji Awal</h3>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Bab 1-5</h5>
                <p class="card-text-roadmap">Salam, perkenalan, kata kerja dasar, kalimat sederhana. Mulai hafal 20-30 Kanji.</p>
            </div>
        </div>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Bab 6-10</h5>
                <p class="card-text-roadmap">Kata sifat, bentuk lampau, kata kerja transitif & intransitif. Tambah 40-50 Kanji. Latihan deskripsi singkat.</p>
            </div>
        </div>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Bab 11-12</h5>
                <p class="card-text-roadmap">Permintaan, kalimat negatif, waktu & tanggal. Pelajari Kanji terkait topik ini.</p>
            </div>
        </div>
    </div>
    <div class="column-roadmap future-roadmap" data-aos="zoom-out" id="step3">
        <h3>Tahap 3: Tata Bahasa Menengah</h3>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Bab 13-17</h5>
                <p class="card-text-roadmap">Partikel kompleks, bentuk perintah, tata bahasa "TE". Kanji sehari-hari. Latihan percakapan sederhana.</p>
            </div>
        </div>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Bab 18-21</h5>
                <p class="card-text-roadmap">Bentuk potensi, pengandaian. Tambah Kanji tentang waktu dan angka. Latihan kalimat kompleks.</p>
            </div>
        </div>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Bab 22-25</h5>
                <p class="card-text-roadmap">Ungkapan sopan, percakapan formal. Target hafal 100-150 Kanji.</p>
            </div>
        </div>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Checkpoint</h5>
                <p class="card-text-roadmap">Siap memahami percakapan sederhana dan membaca kalimat dasar.</p>
            </div>
        </div>
    </div>
</div>
<div class="roadmap">
    <div class="column-roadmap now-roadmap" id="step4">
        <h3>Tahap 4: Level Menengah Lanjut</h3>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Bab 26-30</h5>
                <p class="card-text-roadmap">Bentuk pasif & kausatif. Kanji lingkungan kerja/sekolah. Latihan kalimat kompleks.</p>
            </div>
        </div>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Bab 31-35</h5>
                <p class="card-text-roadmap">Tata bahasa formal & honorifik. Target hafal 200-250 Kanji. Praktek percakapan formal & informal.</p>
            </div>
        </div>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Checkpoint Akhir</h5>
                <p class="card-text-roadmap">Kemampuan setara JFT BASIC A2 / JLPT N4.</p>
            </div>
        </div>
    </div>
    <div class="column-roadmap next-roadmap" id="step5">
        <h3>Tahap 5: Latihan & Persiapan Ujian JFT BASIC A2 / JLPT N4</h3>
        <div class="card-roadmap">
            <div class="card-body-roadmap">
                <h5 class="card-title-roadmap">Review materi dan latihan soal ujian</h5>
                <p class="card-text-roadmap">Fokus pada pemahaman bacaan dan listening percakapan umum dalam bahasa Jepang.</p>
            </div>
        </div>
    </div>
</div>




<!-- PENGAJAR -->
<div class="container pengajar-hatese" style="font-family: 'consolas' , sans-serif;" data-aos="fade-up">
    <h2 style="color:black;" >Pengajar professional yang memberikan materi</h2>
    <p>Inilah beberapa pengajar professional yang telah membantu ribuan pelajar mencapai tujuan mereka dengan materi yang dirancang khusus.</p>
    
    <div class="carousel-container-pengajar">
        <button class="carousel-button-pengajar left" onclick="moveCarousel(-1)">
            <i class="fas fa-arrow-left"></i>
        </button>
        
        <div class="carousel-pengajar">
            <div class="card-pengajar card-green-pengajar">
                <div class="card-body-pengajar">
                    <p>Pengajar dengan keahlian luas dalam bidang teknologi dan pendidikan, berkomitmen memberikan materi yang mudah dipahami oleh semua kalangan.</p>
                </div>
                <div class="card-footer-pengajar">
                    <img alt="Portrait of Lance Jarvis" height="60" src="https://storage.googleapis.com/a1aa/image/nE0wFGQyaKL8K9wN3WyePPodvISAZmfkjRYV38YiGMMOFfanA.jpg" width="60"/>
                    <h5>Lance Jarvis</h5>
                    <div class="social-icons-pengajar">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="card-pengajar card-purple-pengajar">
                <div class="card-body-pengajar">
                    <p>Berpengalaman dalam pendidikan online dengan pendekatan interaktif yang membuat pembelajaran lebih menarik dan efektif.</p>
                </div>
                <div class="card-footer-pengajar">
                    <img alt="Portrait of Ericka Lynda" height="60" src="https://storage.googleapis.com/a1aa/image/v5sFEOX4TK6RJtKJWKQe1a9O2zxVanPKcwQNz6cIdB7miv2JA.jpg" width="60"/>
                    <h5>Ericka Lynda</h5>
                    <div class="social-icons-pengajar">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="card-pengajar card-blue-pengajar">
                <div class="card-body-pengajar">
                    <p>Ahli dalam materi praktikal yang memungkinkan siswa mengembangkan keterampilan langsung dan siap.</p>
                </div>
                <div class="card-footer-pengajar">
                    <img alt="Portrait of Neil Wilford" height="60" src="https://storage.googleapis.com/a1aa/image/GhEceUVgbrVLKiXyxt8gNFJKsgr4aZ1jwiOyQYjyMS7liv2JA.jpg" width="60"/>
                    <h5>Neil Wilford</h5>
                    <div class="social-icons-pengajar">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="carousel-button-pengajar right" onclick="moveCarousel(1)">
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>


<!-- TESTIMONI -->


    <div class="containers">
      <div class="help-section-testimoni">
        <div class="image-container-testimoni">
          <img id="carouselImage" src="image/keuntungan2.png" alt="Book cover image 1" style="width: 300px; height: 300px; margin:0 auto;"/>
          <div class="carousel-control-prev-testimoni" onclick="prevImage()">
            <i class="fas fa-chevron-left carousel-icon-testimoni"></i>
          </div>
          <div class="carousel-control-next-testimoni" onclick="nextImage()">
            <i class="fas fa-chevron-right carousel-icon-testimoni"></i>
          </div>
        </div>
        <div class="text-container-testimoni">
          <h2>Keuntungan Belajar di LMS Hatese</h2>
          <p>Belajar di LMS Hatese memberikan Anda akses ke materi yang lengkap dan terstruktur untuk memudahkan proses belajar.</p>
         <a class="btn btn-primary btn-testimoni" href="https://wa.me/6285864139786?text=Hai%20saya%20tertarik%20untuk%20mendaftarkan%20akun%20di%20LMS%20Hatese%20%F0%9F%98%8A" target="_blank">
            Buruan daftar sekarang!
        </a>
        </div>
      </div>
    </div>
    <script>
      const images = [
        "image/keuntungan3.png",
        "image/keuntungan4.png",
        "image/keuntungan5.png"
      ];
      let carouselIndex = 0;

      function showImage(index) {
        const imgElement = document.getElementById('carouselImage');
        imgElement.src = images[index];
      }

      function prevImage() {
        carouselIndex = (carouselIndex > 0) ? carouselIndex - 1 : images.length - 1;
        showImage(carouselIndex);
      }

      function nextImage() {
        carouselIndex = (carouselIndex < images.length - 1) ? carouselIndex + 1 : 0;
        showImage(carouselIndex);
      }
    </script>
<!-- TESTIMONI -->

  <!-- FOOTER -->
  <style>
    footer {
      background-color: #4F64A9;
      width: 100%;
    }

    .footer-ct {
      width: 70%;
    }
  </style>  <!-- FOOTER -->
<!-- FOOTER -->
<footer style="font-family: 'consolas';" id="contact" class="text-white py-8 mt-8">
  <div class="mx-auto w-full md:w-3/4 px-4">
    <div class="flex flex-col md:flex-row justify-between">
      <!-- Logo and Contact Info -->
      <div class="flex-1 mb-8 md:mb-0">
        <div class="flex items-center mb-4">
          <img 
            src="hateselogo.png" 
            alt="Hatese logo" 
            class="w-12 h-12 mr-3" 
            width="50" 
            height="50"
          />
          <div>
            <h2 class="text-lg font-bold">HATESE</h2>
            <p>(Harapan Terang Sejahtera)</p>
          </div>
        </div>
        <p class="mb-2">
          <i class="fas fa-map-marker-alt mr-2"></i> Indonesian<br>Jawa Barat, Cirebon
        </p>
        <p class="mb-2">
          <i class="fas fa-phone-alt mr-2"></i> +62 XXXX-XXX-XXX
        </p>
        <p class="mb-2">
          <i class="fas fa-envelope mr-2"></i> example@gmail.com
        </p>
        <div class="flex space-x-4 mt-2">
          <a href="#" class="text-white"><i class="fas fa-globe"></i></a>
          <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-white"><i class="fab fa-tiktok"></i></a>
          <a href="#" class="text-white"><i class="fab fa-facebook"></i></a>
        </div>
      </div>
      <!-- About Us -->
      <div class="flex-1">
        <h2 class="text-lg font-bold mb-4">TENTANG KAMI</h2>
        <p class="text-justify">
          Perusahaan ini berdiri pada tanggal 03 Juli 2024 di Kota Cirebon, untuk mencukupi permintaan Tenaga Kerja Asing terutama ke Negara Jepang. PT. HARAPAN TERANG SEJAHTERA mempunyai slogan "SOLUSI MASA DEPAN".
        </p>
      </div>
    </div>
    <!-- Footer Copyright -->
    <div class="mt-8">
      <p class="text-left">© 2024 Copyright, LMS Hatese. All Rights Reserved</p>
    </div>
  </div>
</footer>


  <!-- Modal -->
  <div class="modal fade" id="accessCourseModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Akses Kursus Gratis</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="accessCourseForm" method="POST" action="process_guest_access.php">
            <input type="hidden" id="modalCourseId" name="course_id">
            <div class="form-group">
              <label for="guestFullName">Nama Lengkap</label>
              <input type="text" class="form-control" id="guestFullName" name="full_name" required>
            </div>
            <div class="form-group">
              <label for="guestEmail">Email</label>
              <input type="email" class="form-control" id="guestEmail" name="email" required>
            </div>
            <div class="form-group">
              <label for="guestPhone">Nomor Telepon</label>
              <input type="tel" class="form-control" id="guestPhone" name="phone" required>
            </div>
            <button type="submit" class="btn btn-primary">Akses Kursus</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->

  <!-- navbar click -->
  <script>
    $(document).ready(function() {
      $('.course-image').click(function() {
        var courseId = $(this).data('course-id');
        var courseTitle = $(this).data('course-title');
        $('#modalCourseId').val(courseId);
        $('#modalTitle').text('Akses Kursus: ' + courseTitle);
      });
    });
    // Modalloading
    document.getElementById('accessCourseForm').addEventListener('submit', function(e) {
      e.preventDefault();

      let timerInterval;
      Swal.fire({
        title: 'Data Sedang di Proses',
        html: 'Harap menunggu beberapa saat.',
        timer: 2000,
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading();
          const timer = Swal.getHtmlContainer().querySelector('b');
          timerInterval = setInterval(() => {
            timer.textContent = Swal.getTimerLeft();
          }, 100);
        },
        willClose: () => {
          clearInterval(timerInterval);
        }
      }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
          // Proceed with the form submission
          e.target.submit();
        }
      });
    });


     let currentIndex = 0;

        function moveCarousel(direction) {
            const carousel = document.querySelector('.carousel-pengajar');
            const cards = document.querySelectorAll('.card-pengajar');
            const cardWidth = cards[0].offsetWidth + 30; // card width + margin
            const maxIndex = cards.length - 1;

            currentIndex += direction;

            if (currentIndex < 0) {
                currentIndex = 0;
            } else if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
            }

            const offset = -currentIndex * cardWidth;
            carousel.style.transform = `translateX(${offset}px)`;
        }
    // Modal loading end
  </script>
  <!-- Navbar click -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    rel="stylesheet" />
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</body>

</html>
<script>
  AOS.init();
</script>
<?php
session_start();
include_once __DIR__ . '/includes/db.php';
include_once __DIR__ . '/includes/functions.php';

// Query untuk mendapatkan event yang statusnya 'open'
$query = "SELECT * FROM events WHERE event_status = 'open'";
$result = prepare_query($conn, $query, []);

// Pastikan query berjalan dengan benar
if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Enjoy the best events and entertainment services.">
    <title>Landing Page</title>
    <link rel="stylesheet" href="assets/css/index-style.css"> <!-- CSS terpisah -->
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>DESTER</h1>
        </div>

        <nav class="navbar">
            <ul class="navbar-list">
                <li class="navbar-item"><a href="#events" class="navbar-link">Events</a></li>
                <?php if ($isLoggedIn): ?>
                    <li class="navbar-item"><a href="user/profile.php" class="navbar-link">Profile</a></li>
                    <li class="navbar-item"><a href="user/myevents.php" class="navbar-link">My Events</a></li>
                    <li class="navbar-item"><a href="user/logout.php" class="navbar-link">Logout</a></li>
                <?php else: ?>
                    <li class="navbar-item"><a href="user/login.php" class="navbar-link">Login</a></li>
                    <li class="navbar-item"><a href="user/register.php" class="navbar-link">Signup</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="hamburger-menu" aria-label="Open menu" onclick="toggleMenu()">
            &#9776;
        </div>
    </header>

   
<!-- Carousel di bawah navbar -->
<div class="carousel-container">
    <div class="carousel-slide">
        <div class="carousel-item active">
            <video autoplay muted loop playsinline class="carousel-video">
                <source src="uploads/animation.mp4" type="video/mp4">
            </video>
            <!-- Teks yang akan diketik secara manual -->
            <div id="welcome-text" class="welcome-text">
                Welcome to the World of Events
            </div>
        </div>
    </div>
</div>
    <section id="events">
        <h1>Available Events</h1>

        <div class="events-container">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($event = $result->fetch_assoc()): ?>
                    <div class="event-box">
                        <!-- Menampilkan gambar event sesuai dengan path yang disimpan di database -->
                        <?php 
                        $event_image = !empty($event['event_image']) ? 'uploads/' . htmlspecialchars($event['event_image']) : 'uploads/default-image.jpg'; 
                        ?>
                        <img src="<?php echo $event_image; ?>" alt="<?php echo htmlspecialchars($event['event_name']); ?>" class="event-image">
                        
                        <!-- Tampilkan nama, tanggal, dan lokasi event -->
                        <h2><?php echo htmlspecialchars($event['event_name']); ?></h2>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>

                        <!-- Tautan ke detail -->
                        <a href="user/event_detail.php?event_id=<?php echo urlencode($event['id']); ?>" class="details-link">More Details</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No events available at the moment.</p>
            <?php endif; ?>
        </div>

        <?php mysqli_close($conn); ?>
    </section>
    <footer class="footer">
    <div class="social-media-links">
        <!-- Link Instagram 1 dengan nama di kiri dan logo Instagram di kanan -->
        <div class="social-media-item">
            <span>Felix Octaniel </span>
            <a href="https://www.instagram.com/felixdlw?igsh=dHNyZnl2OXN2bXFt" target="_blank">
                <img src="uploads/logoig.png" alt="Instagram">
            </a>
        </div>

        <!-- Link Instagram 2 dengan nama di kiri dan logo Instagram di kanan -->
        <div class="social-media-item">
            <span>Farrel Nayaka</span>
            <a href="https://www.instagram.com/farrelnayakaa?igsh=MTJsbm8zanc1NzA0cg==" target="_blank">
            <img src="uploads/logoig.png" alt="Instagram">
            </a>
        </div>
        <!-- Link Instagram 3 dengan nama di kiri dan logo Instagram di kanan -->
        <div class="social-media-item">
            <span>Parsaulian Mian</span>
            <a href="https://www.instagram.com/lians2817?igsh=ZHIxMTdrNDdvamxi" target="_blank">
            <img src="uploads/logoig.png" alt="Instagram">
            </a>
        </div>

        <!-- Link Instagram 4 dengan nama di kiri dan logo Instagram di kanan -->
        <div class="social-media-item">
            <span>Evan Luthfi</span>
            <a href="https://www.instagram.com/evanluthfi.w?igsh=MTl5MGhtc3AxeGNlcg==" target="_blank">
            <img src="uploads/logoig.png" alt="Instagram">
            </a>
        </div>
    </div>
    <p>&copy; thankyou all</p>
</footer>




    <script>
        // Class Carousel untuk menangani pergerakan gambar
        class Carousel {
            constructor(container, prevButton, nextButton) {
                this.container = document.querySelector(container);
                this.slides = this.container.querySelectorAll('.carousel-item');
                this.totalSlides = this.slides.length;
                this.currentSlide = 0;
                this.prevButton = document.querySelector(prevButton);
                this.nextButton = document.querySelector(nextButton);

                this.init();
            }

            init() {
                this.prevButton.addEventListener('click', () => this.moveSlide(-1));
                this.nextButton.addEventListener('click', () => this.moveSlide(1));
            }

            moveSlide(direction) {
                this.slides[this.currentSlide].classList.remove('active');
                this.currentSlide = (this.currentSlide + direction + this.totalSlides) % this.totalSlides;
                this.slides[this.currentSlide].classList.add('active');
                this.container.querySelector('.carousel-slide').style.transform = `translateX(-${this.currentSlide * 100}%)`;
            }
        }

        // Inisialisasi carousel
        document.addEventListener('DOMContentLoaded', () => {
            const carousel = new Carousel('.carousel-container', '#carousel-prev', '#carousel-next');
        });

        // Fungsi untuk toggle menu pada layar kecil
        function toggleMenu() {
            const navbarList = document.querySelector('.navbar-list');
            navbarList.classList.toggle('active');
        }
    </script>
    <script>
    // Fungsi untuk scroll vertikal ke bawah
    function scrollToBottom() {
        window.scrollTo({
            top: document.body.scrollHeight, 
            behavior: 'smooth'
        });
    }

    // Fungsi untuk scroll horizontal ke kanan
    function scrollToRight() {
        window.scrollTo({
            left: document.body.scrollWidth, 
            behavior: 'smooth'
        });
    }
</script>

    <!-- Tombol untuk scroll vertikal ke bawah -->
<button id="scrollDownButton" class="scroll-button scroll-vertical" onclick="scrollToBottom()">&#x25BC;</button>
<script>
    // Dapatkan elemen welcome text
    const welcomeText = document.getElementById('welcome-text');

    // Tampilkan teks WELCOME saat halaman dimuat
    window.onload = function() {
        welcomeText.classList.add('visible'); // Tambahkan kelas visible untuk memunculkan teks
    };
</script>


</body>
</html>

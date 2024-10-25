<?php
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

// Dapatkan data event dari database
$event_id = 1; 
$query = "SELECT event_name, image_url FROM events WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id); 
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if ($user['role'] == 'admin') {
    header("Location: ../admin/dashboard.php"); 
} else {
    header("Location: ../index.php"); 
}
exit();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Enjoy the best movies and series. Watch now!">
    <title>Landing Page</title>
    <link rel="stylesheet" href="../assets/css/index-style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>MyLogo</h1>
        </div>
        <div class="hamburger-menu" aria-label="Open menu" onclick="toggleMenu()">
            &#9776; 
        </div>

        <nav class="navbar">
            <ul class="navbar-list">
                <li class="navbar-item"><a href="events.php" class="navbar-link">events</a></li>
                <li class="navbar-item"><a href="login.php" class="navbar-link">Login</a></li>
                <li class="navbar-item"><a href="register.php" class="navbar-link">Signup</a></li>
            </ul>
        </nav>
        <div class="header-icons">
            <span class="icon" aria-label="Search">&#128269;</span> 
            <span class="icon" aria-label="Notifications">&#128276;</span>
            <span class="icon" aria-label="Profile">&#128100;</span> 
        </div>
    </header>

    <section id="hero" class="hero-section">
        <div class="hero-text">
            <h1 class="hero-title">Watch the Best Movies & Series</h1>
            <p class="hero-description">Discover the latest and greatest movies and series. Enjoy streaming now!</p>
            <a href="#movies" class="cta-button">Watch Now</a>
        </div>
        <div class="hero-image-placeholder">
            <div class="placeholder">Featured Content</div> 
        </div>
        <div class="event-image">
            <img src="/uploads/<?php echo htmlspecialchars($event['image_url']); ?>" alt="<?php echo htmlspecialchars($event['event_name']); ?>">
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2024 Streaming Service. All rights reserved.</p>
    </footer>

    
    <script>
        function toggleMenu() {
            const navbar = document.querySelector('.navbar');
            navbar.classList.toggle('active');
        }
    </script>
</body>
</html>

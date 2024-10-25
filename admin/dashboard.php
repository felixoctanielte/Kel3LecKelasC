<?php

class AdminDashboard {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        session_start();
        $this->check_admin_access();
    }

    // Fungsi untuk memeriksa apakah user sudah login dan memiliki role admin
    private function check_admin_access() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header("Location: ../user/login.php");
            exit();
        }
    }

    // Fungsi untuk mendapatkan event dan jumlah pendaftar
    public function get_events() {
        // Menyertakan kapasitas dan total registrants di query
        $query = "SELECT events.id, events.event_name, events.max_participants, 
                  COUNT(registrations.id) as total_registrants 
                  FROM events 
                  LEFT JOIN registrations ON events.id = registrations.event_id 
                  GROUP BY events.id";
        $result = $this->conn->query($query);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        return $result;
    }

    // Fungsi untuk menampilkan pesan selamat datang
    public function show_welcome_message() {
        echo "<h1>Welcome to the Admin Dashboard</h1>";
        echo "<p>Anda login sebagai admin dengan ID: " . htmlspecialchars($_SESSION['user_id']) . "</p>";
    }

    // Fungsi untuk menampilkan tabel event
    public function display_events_table($events) {
        echo '<table class="admin-dashboard-events-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="admin-dashboard-table-header">Event Name</th>';
        echo '<th class="admin-dashboard-table-header">Total Registrants</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        while ($event = $events->fetch_assoc()) {
            $total_registrants = htmlspecialchars($event['total_registrants']);
            $max_participants = htmlspecialchars($event['max_participants']);
            echo '<tr>';
            echo '<td class="admin-dashboard-table-data">' . htmlspecialchars($event['event_name']) . '</td>';
            echo '<td class="admin-dashboard-table-data">' . "{$total_registrants}/{$max_participants}" . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }
}

// Sertakan file yang dibutuhkan
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/functions.php'; // Pastikan hanya di-include sekali

// Buat instance dari class AdminDashboard
$dashboard = new AdminDashboard($conn);
$events = $dashboard->get_events();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dash-style.css">
</head>
<body class="admin-dashboard">
<header class="admin-dashboard-header">
    <div class="header-container">
        <h1 class="admin-dashboard-title">Admin Dashboard</h1>
        <nav class="admin-dashboard-nav">
            <ul class="admin-dashboard-nav-list">
                <li class="admin-dashboard-nav-item"><a href="manage_events.php" class="admin-dashboard-nav-link">Event Management</a></li>
                <li class="admin-dashboard-nav-item"><a href="manage_users.php" class="admin-dashboard-nav-link">User Management</a></li>
                <li class="admin-dashboard-nav-item"><a href="view_registrations.php" class="admin-dashboard-nav-link">Event Registrations</a></li>
                <li class="admin-dashboard-nav-item">
                    <form method="POST" action="../user/logout.php" style="display: inline;">
                        <button type="submit" class="admin-dashboard-nav-link-button">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</header>

<section class="admin-dashboard-section">
    <h2 class="admin-dashboard-section-title">Available Events</h2>
    <?php
    // Menampilkan tabel event menggunakan fungsi yang sudah ada di AdminDashboard
    $dashboard->display_events_table($events);
    ?>
</section>

<script>
    // Fungsi untuk menampilkan dan menyembunyikan menu pada mobile
    function toggleMenu() {
        var nav = document.querySelector('.admin-dashboard-nav');
        nav.classList.toggle('active');
    }
</script>
</body>
</html>

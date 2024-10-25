<?php
session_start(); 

// Mengaktifkan error reporting untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Koneksi ke database dan fungsi tambahan
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

// Class EventManager untuk mengelola event
class EventManager {
    private $conn;
    private $user_id;

    // Konstruktor untuk menerima koneksi database
    public function __construct($db_conn) {
        $this->conn = $db_conn;

        // Memeriksa apakah koneksi berhasil
        if ($this->conn->connect_error) {
            die("Koneksi database gagal: " . $this->conn->connect_error);
        }

        // Memeriksa apakah user_id tersedia di session
        if (isset($_SESSION['user_id'])) {
            $this->user_id = $this->clean_input($_SESSION['user_id']); // Ambil user_id dari session
        } else {
            echo "<p class='error-message'>User ID tidak ditemukan. Silakan login terlebih dahulu.</p>";
            exit;
        }
    }

    // Fungsi membersihkan input untuk menghindari serangan XSS
    private function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Fungsi untuk mengupload file (gambar/banner)
    private function upload_file($file, $directory) {
        if (!empty($file['name'])) {
            $target_file = $directory . basename($file['name']);
            // Pastikan direktori upload ada
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return $file['name']; // Mengembalikan nama file yang di-upload
            } else {
                return null;
            }
        }
        return null;
    }

    // Fungsi untuk membuat event baru
    public function create_event($event_data, $files) {
        // Ambil data dari formulir
        $event_name = $this->clean_input($event_data['event_name']);
        $event_date = $this->clean_input($event_data['event_date']);
        $event_time = $this->clean_input($event_data['event_time']);
        $location = $this->clean_input($event_data['location']);
        $description = $this->clean_input($event_data['description']);
        $max_participants = $this->clean_input($event_data['max_participants']);
        $status = $this->clean_input($event_data['status']);
    
        // Validasi upload gambar dan banner
        if (empty($files['event_image']['name']) || empty($files['event_banner']['name'])) {
            return "Gambar dan banner harus di-upload.";
        }
    
        // Proses upload gambar
        $image = $this->upload_file($files['event_image'], "../uploads/");
        $banner = $this->upload_file($files['event_banner'], "../uploads/");
    
        if (!$image || !$banner) {
            return "Gagal mengupload gambar atau banner.";
        }
    
        // Periksa apakah kombinasi event_name, event_date, dan user_id sudah ada
        $check_query = "SELECT * FROM events WHERE user_id = ? AND event_name = ? AND event_date = ?";
        $stmt_check = $this->conn->prepare($check_query);
        $stmt_check->bind_param('iss', $this->user_id, $event_name, $event_date);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
    
        if ($result_check->num_rows > 0) {
            return "Event dengan nama dan tanggal yang sama sudah ada untuk user ini.";
        }
    
        // Query untuk menyimpan event baru ke dalam database
        $query = "INSERT INTO events (user_id, event_name, event_date, event_time, location, description, max_participants, status, event_image, event_banner)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        if ($stmt === false) {
            return "Gagal menyiapkan statement: " . $this->conn->error;
        }
    
        $stmt->bind_param('isssssssss', $this->user_id, $event_name, $event_date, $event_time, $location, $description, $max_participants, $status, $image, $banner);
    
        if ($stmt->execute()) {
            return "Event baru berhasil ditambahkan.";
        } else {
            return "Gagal menambahkan event baru: " . $stmt->error;
        }
    
        $stmt->close(); // Menutup statement setelah selesai
    }

    // Fungsi untuk menampilkan event dengan status 'open'
    public function get_open_events() {
        $query = "SELECT event_name, event_image FROM events WHERE status = 'open'";
        $result = $this->conn->query($query);

        if ($result === false) {
            return "Gagal mengambil event: " . $this->conn->error;
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Variabel untuk menampung pesan notifikasi
$notification = "";

// Jika form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_event'])) {
    $eventManager = new EventManager($conn); // Buat instance dari EventManager
    $notification = $eventManager->create_event($_POST, $_FILES); // Buat event baru
}

// Jika ingin menampilkan event yang sedang open
$eventManager = new EventManager($conn);
$open_events = $eventManager->get_open_events();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Event Baru</title>
    <link rel="stylesheet" href="../assets/css/create-style.css"> <!-- Hubungkan dengan CSS -->
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="nav-title">EVENT</div>
    <ul class="nav-list">
        <li><a class="nav-link" href="create_events.php">Buat Event</a></li>
        <li><a class="nav-link" href="manage_events.php">Daftar Event</a></li>
    </ul>
</nav>

<!-- Form untuk membuat event -->
<div class="container">
    <h2>Buat Event Baru</h2>
    <?php if ($notification): ?>
        <p class="notification-message"><?= $notification; ?></p>
    <?php endif; ?>
    <form class="form" method="POST" action="create_events.php" enctype="multipart/form-data">
        <label for="event_name">Nama Event:</label> 
        <input type="text" id="event_name" name="event_name" required><br>
        
        <label for="event_date">Tanggal Event:</label>
        <input type="date" id="event_date" name="event_date" required><br>
        
        <label for="event_time">Waktu Event:</label>
        <input type="time" id="event_time" name="event_time" required><br>
        
        <label for="location">Lokasi:</label>
        <input type="text" id="location" name="location" required><br>
        
        <label for="description">Deskripsi:</label>
        <textarea id="description" name="description" required></textarea><br>
        
        <label for="max_participants">Jumlah Maksimum Partisipan:</label>
        <input type="number" id="max_participants" name="max_participants" required><br>
        
        <label for="status">Status Event:</label>
        <select id="status" name="status" required>
            <option value="open">Open</option>
            <option value="closed">Closed</option>
            <option value="canceled">Canceled</option>
        </select><br>
        
        <label for="event_image">Gambar Event (wajib):</label>
        <input type="file" id="event_image" name="event_image" accept="image/*" required><br>
        
        <label for="event_banner">Banner Event (wajib):</label>
        <input type="file" id="event_banner" name="event_banner" accept="image/*" required><br>
        
        <input type="hidden" name="create_event" value="1">
        <button type="submit" class="button">Buat Event</button>
    </form>

</div>

</body>
</html>
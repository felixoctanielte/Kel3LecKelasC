<?php
session_start();
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

// Cek apakah user telah login
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil detail user dari database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found.";
    exit;
}

$user = $result->fetch_assoc();  // Ambil data user sebagai associative array

// Cek role user
if ($user['role'] == 'admin') {
    header("Location: ../admin/dashboard.php"); // Redirect ke halaman admin
    exit;
} else {
    // Ambil event yang telah didaftarkan oleh user
    $query = "SELECT events.id AS event_id, events.event_name, events.event_date, events.location 
              FROM registrations 
              INNER JOIN events ON registrations.event_id = events.id 
              WHERE registrations.user_id = ?";
    $result = prepare_query($conn, $query, ['i', $user_id]);

    // Tampilkan halaman untuk user biasa
}
?>

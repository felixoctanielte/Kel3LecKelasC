<?php
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';


if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']); // Pastikan ID adalah integer

    // Query untuk mendapatkan data event berdasarkan ID
    $query = "SELECT * FROM events WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $event_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Mengambil hasil query
    $event = mysqli_fetch_assoc($result);

    // Jika event tidak ditemukan, tampilkan pesan error
    if (!$event) {
        echo "<p class='error-message'>Event tidak ditemukan!</p>";
        exit;
    }
} else {
    echo "<p class='error-message'>Event ID tidak diberikan!</p>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menggunakan clean_input untuk membersihkan input
    $max_participants = clean_input($_POST['max_participants']);
    $status = clean_input($_POST['status']);
    $event_date = clean_input($_POST['event_date']);
    $location = clean_input($_POST['location']);
    $event_time = clean_input($_POST['event_time']);

    // Validasi input
    if (!is_numeric($max_participants) || $max_participants <= 0) {
        $message = "<p class='error-message'>Jumlah partisipan tidak valid!</p>";
    } else {
        // Query untuk mengupdate event
        $query = "UPDATE events SET max_participants = ?, status = ?, event_date = ?, location = ?, event_time = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'issssi', $max_participants, $status, $event_date, $location, $event_time, $event_id); 
        
        if (mysqli_stmt_execute($stmt)) {
            $message = "<p class='success-message'>Event berhasil diupdate!</p>";
        } else {
            $message = "<p class='error-message'>Gagal memperbarui event!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event (Jumlah Slot & Status)</title>
    <link rel="stylesheet" href="../assets/css/edit-style.css"> 
</head>
<body>

<nav class="navbar">
    <ul class="nav-list">
        <li><a class="nav-link" href="manage_events.php">Daftar Event</a></li>
        <li><a class="nav-link" href="dashboard.php">Kembali ke Dashboard</a></li>       
    </ul>
</nav>


<div class="form-container">
    <h2>Edit Event</h2>
    <form class="form" method="POST" action="edit_event.php?id=<?php echo $event_id; ?>">
        <label for="max_participants">Jumlah Maksimum Partisipan:</label>
        <input type="number" id="max_participants" name="max_participants" value="<?php echo htmlspecialchars($event['max_participants']); ?>" required min="1"><br>
        
        <label for="status">Status Event:</label>
        <select id="status" name="status" required>
            <option value="open" <?php if($event['status'] == 'open') echo 'selected'; ?>>Open</option>
            <option value="closed" <?php if($event['status'] == 'closed') echo 'selected'; ?>>Closed</option>
            <option value="canceled" <?php if($event['status'] == 'canceled') echo 'selected'; ?>>Canceled</option>
        </select><br>
        
        <label for="event_date">Tanggal Event:</label>
        <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required><br>
        
        <label for="location">Lokasi Event:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required><br>
        
        <label for="event_time">Waktu Event:</label>
        <input type="time" id="event_time" name="event_time" value="<?php echo htmlspecialchars($event['event_time']); ?>" required><br>
        
        <button type="submit" class="button">Update Event</button>
    </form>
</div>


<?php if (isset($message)) : ?>
    <div id="notification" class="notification">
        <?php echo $message; ?>
    </div>
    <script>
        // Menampilkan notifikasi jika ada pesan
        var notification = document.getElementById("notification");
        notification.classList.add("show");
        
        // Menyembunyikan notifikasi setelah 3 detik
        setTimeout(function() {
            notification.classList.remove("show");
        }, 3000);
    </script>
<?php endif; ?>

</body>
</html>

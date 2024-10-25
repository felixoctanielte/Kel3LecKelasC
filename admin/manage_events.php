<?php
session_start();
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Alihkan ke halaman login jika belum login
    exit;
}

// Menangani pengiriman form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_event']) && isset($_POST['event_id'])) {
        // Menghapus event berdasarkan ID
        $event_id = intval($_POST['event_id']);
        $query = "DELETE FROM events WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $event_id);
        
        if ($stmt->execute()) {
            header("Location: manage_events.php?delete_success=1");
            exit;
        } else {
            $error_message = "Gagal menghapus event";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Event Terdaftar</title>
    <link rel="stylesheet" href="../assets/css/manage-style.css">
</head>
<body>


<nav class="navbar">
    <ul class="nav-list">
        <a href="dashboard.php" class="button">BACK</a>
        <li>
            <a href="create_events.php" class="button">Buat Event Baru</a>
        </li>
    </ul>
</nav>
<div class="container">
    <h2 class="center-text">Daftar Event Terdaftar</h2>
    <div class="table-wrapper">
        <table class="table" id="eventTable">
            <thead>
                <tr>
                    <th>Nama Event</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM events";
                $result = $conn->query($query);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr id='event-".$row['id']."'>";
                        echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['event_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>
                            <a href='edit_event.php?id=" . $row['id'] . "' class='button'>Edit</a>
                            <form action='manage_events.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='delete_event' value='1'>
                                <input type='hidden' name='event_id' value='" . $row['id'] . "'>
                                <button type='submit' class='button' onclick='return confirm(\"Apakah Anda yakin ingin menghapus event ini?\")'>Delete</button>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Belum ada event terdaftar</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>



<div class="centered-notification">
    <?php if (isset($error_message)) : ?>
        <p class='error-message'><?= $error_message; ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['delete_success'])) : ?>
        <p class='success-message'></p>
    <?php endif; ?>
</div>



</body>
</html>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
    $event_id = intval($_POST['event_id']);
    
    $query = "DELETE FROM registrations WHERE user_id = ? AND event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $event_id);

    if ($stmt->execute()) {
        echo '<p style="color: green;">Berhasil membatalkan registrasi dari event!</p>';
    } else {
        echo '<p style="color: red;">Gagal membatalkan registrasi. Coba lagi.</p>';
    }
    $stmt->close();
}

$query = "SELECT events.id, events.event_name, events.event_date, events.location 
          FROM registrations 
          INNER JOIN events ON registrations.event_id = events.id 
          WHERE registrations.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events</title>
    <link rel="stylesheet" href="../assets/css/myevents-style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">My Events</div>
        <ul class="navbar-list">
            <li><a href="profile.php">Profile</a></li>
            <li><a href="../index.php">Back</a></li>
        </ul>
        <div class="hamburger" onclick="toggleMenu()">&#9776;</div>
    </nav>

    <div class="myevents-container">
        <h1>Event yang Sudah Didaftarkan</h1>

        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($event = $result->fetch_assoc()): ?>
                    <li class="event-item">
                        <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                        <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                        <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" class="cancel-btn">Cancel Registration</button>
                        </form>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Anda belum mendaftarkan diri ke event manapun.</p>
        <?php endif; ?>

    </div>

    <script>
        function toggleMenu() {
            const navbarList = document.querySelector('.navbar-list');
            navbarList.classList.toggle('active');
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>

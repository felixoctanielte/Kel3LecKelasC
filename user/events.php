<?php

include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$query = "SELECT * FROM events WHERE event_status = 'open'";
$result = prepare_query($conn, $query, []);


if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}


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
    <title>Available Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Available Events</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <ul>
            <?php while ($event = $result->fetch_assoc()): ?>
                <li>
                    <h2><?php echo htmlspecialchars($event['event_name']); ?></h2>
                    <p><?php echo htmlspecialchars($event['description']); ?></p>
                    <a href="event_detail.php?event_id=<?php echo urlencode($event['id']); ?>">More Details</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No events available at the moment.</p>
    <?php endif; ?>
    <?php mysqli_close($conn); ?>
</body>
</html>

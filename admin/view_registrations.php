<?php
include_once __DIR__ . '/../includes/db.php';


if (!$conn) {
    die("Database connection not initialized.");
}

$query = "SELECT users.name AS user_name, users.email AS user_email, events.event_name 
          FROM registrations 
          JOIN events ON registrations.event_id = events.id
          JOIN users ON registrations.user_id = users.id";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registrations</title>
    <link rel="stylesheet" href="../assets/css/view-style.css">
</head>
<body>

<header>
    <div class="navbar">
        <h1>Event Registrations</h1>
        <div class="hamburger" onclick="toggleMenu()">&#9776;</div>
        <div class="menu" id="menu">
            <a href="dashboard.php">Dashboard</a>
        </div>
    </div>
</header>
<div class="content">
    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Event Name</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($registration = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($registration['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($registration['user_email']); ?></td>
                    <td><?php echo htmlspecialchars($registration['event_name']); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No registrations found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<a href="export_csv.php" class="button">Export to CSV</a>

<script>
    function toggleMenu() {
        const menu = document.getElementById("menu");
        menu.classList.toggle("show");
    }
</script>



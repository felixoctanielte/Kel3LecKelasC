<?php
session_start();
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['event_id'])) {
    die('Event ID not provided.');
}

$event_id = intval($_POST['event_id']);

$query = "SELECT * FROM registrations WHERE user_id = ? AND event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "You are already registered for this event.";
    $stmt->close();
    exit;
}

$query = "INSERT INTO registrations (user_id, event_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $event_id);

if ($stmt->execute()) {
    header("Location: registered_events.php");
    exit;
} else {
    echo "Registration failed.";
}

$stmt->close();
mysqli_close($conn);
?>

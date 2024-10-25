<?php

include_once __DIR__ . '/../includes/db.php';


if (!$conn) {
    die("Database connection not initialized.");
}

// Query untuk mendapatkan data yang akan diekspor ke CSV
$query = "SELECT users.name AS user_name, users.email AS user_email, events.event_name 
          FROM registrations 
          JOIN events ON registrations.event_id = events.id
          JOIN users ON registrations.user_id = users.id";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}


header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=registrations.csv');
header('Pragma: no-cache');
header('Expires: 0');


$output = fopen('php://output', 'w');

fputcsv($output, array('User Name', 'Email', 'Event Name'));


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}


fclose($output);
exit(); 
?>

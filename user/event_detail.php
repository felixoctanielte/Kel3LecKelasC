<?php
session_start();
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/functions.php';


if (isset($_POST['ajax_register']) && isset($_POST['event_id'])) {
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'You must be logged in to register.']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $event_id = intval($_POST['event_id']);

   
    $query = "SELECT * FROM registrations WHERE user_id = ? AND event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        echo json_encode(['status' => 'success', 'message' => 'You are already registered for this event.']);
        exit;
    }

    
    $query = "INSERT INTO registrations (user_id, event_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $event_id);

    if ($stmt->execute()) {
        
        echo json_encode(['status' => 'success', 'message' => 'Successfully registered for the event!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed. Please try again.']);
    }
    $stmt->close();
    mysqli_close($conn);
    exit;
}


if (!isset($_GET['event_id'])) {
    die('Event ID not provided.');
}

$event_id = intval($_GET['event_id']);


$query = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows === 0) {
    die('Event not found.');
}


$event = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Event Detail Page">
    <title><?php echo htmlspecialchars($event['event_name']); ?> - Event Detail</title>
    <link rel="stylesheet" href="../assets/css/detail-style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar">
        <div class="logo">Event System</div>
        <ul class="navbar-list">
            <li><a href="myevents.php">My Events</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="javascript:history.back()">Back</a></li>
        </ul>
        <div class="hamburger" onclick="toggleMenu()">&#9776;</div>
    </nav>

    <section id="event-detail">
        <h2><?php echo htmlspecialchars($event['event_name']); ?></h2>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
        <div id="notification"></div>
        <button id="register-btn" data-event-id="<?php echo $event_id; ?>">Register</button>
    </section>

    <footer class="footer">
        <p>&copy; 2024 Event System. All rights reserved.</p>
    </footer>

    <script>
        $(document).ready(function() {
            $('#register-btn').click(function() {
                var event_id = $(this).data('event-id'); 
                $.ajax({
                    url: '', 
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        ajax_register: true, 
                        event_id: event_id
                    },
                    success: function(response) {
                        
                        if (response.status === 'success') {
                            $('#notification').html('<p style="color: green;">' + response.message + '</p>');
                        } else {
                            $('#notification').html('<p style="color: red;">' + response.message + '</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        
                        $('#notification').html('<p style="color: red;">An error occurred: ' + error + '</p>');
                    }
                });
            });
        });
        function toggleMenu() {
            const navbarList = document.querySelector('.navbar-list');
            navbarList.classList.toggle('active');
        }
    </script>
</body>
</html>

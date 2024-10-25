<?php
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';


// Query untuk melihat seluruh user dan event yang pernah diikuti
$query = "SELECT users.id, users.name, users.email, GROUP_CONCAT(events.event_name SEPARATOR ', ') AS event_list
          FROM users
          LEFT JOIN registrations ON users.id = registrations.user_id
          LEFT JOIN events ON registrations.event_id = events.id
          GROUP BY users.id";
$result = prepare_query($conn, $query, []);

// Proses penghapusan user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user_id'])) {
    $user_id = $_POST['delete_user_id'];
    $query = "DELETE FROM users WHERE id = ?";
    prepare_query($conn, $query, ['i', $user_id]);
    header("Location: manage_users.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/manage-style.css">
</head>
<body>



<nav class="navbar">
        <ul class="nav-list">
            <li><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li>
                <form method="POST" action="../user/logout.php" style="display: inline;">
                    <button type="submit" class="nav-link">Logout</button>
                </form>
            </li>
        </ul>
</nav>

    <h1 class="page-title">Manage Users</h1>
    <div class="user-management">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered Events</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['event_list']); ?></td>
                    <td>
                        <form method="POST" action="manage_users.php">
                            <input type="hidden" name="delete_user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="delete-button">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>

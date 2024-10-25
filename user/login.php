<?php
session_start();

include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

$notification = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);

    $query = "SELECT id, password, role FROM users WHERE email = ?";
    $result = prepare_query($conn, $query, ['s', $email]);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            $notification = "Email atau password salah.";
        }
    } else {
        $notification = "Pengguna dengan email tersebut tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main>
        <header>
            <h1>Login</h1>
        </header>

        <?php if ($notification): ?>
            <div class="notification">
                * <?= $notification; ?>
            </div>
        <?php endif; ?>

        <div id="successMessage" class="notification" style="display: none; color: green;"></div>

        <form method="POST" action="login.php">
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <p class="login-message">Already have an account? <a href="register.php">Register</a></p>
    </main>

    <script>
        if (sessionStorage.getItem('registrationSuccess')) {
            document.getElementById('successMessage').textContent = sessionStorage.getItem('registrationSuccess');
            document.getElementById('successMessage').style.display = 'block';

            sessionStorage.removeItem('registrationSuccess');
        }
    </script>
</body>
</html>

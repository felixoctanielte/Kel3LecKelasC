<?php
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';


class UserRegistration {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($name, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Cek apakah email sudah terdaftar
        if ($this->checkEmailExists($email)) {
            return "Email sudah terdaftar. Silakan gunakan email lain.";
        } else {
            // SQL untuk menyimpan data ke database
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param("sss", $name, $email, $hashed_password);
                if ($stmt->execute()) {
                    return "success"; // Return "success" jika berhasil
                } else {
                    return "Error: " . $stmt->error;
                }
                $stmt->close(); // Menutup statement
            } else {
                return "Error preparing statement: " . $this->conn->error;
            }
        }
    }

    private function checkEmailExists($email) {
        $check_email_sql = "SELECT email FROM users WHERE email = ?";
        if ($stmt = $this->conn->prepare($check_email_sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $exists = $stmt->num_rows > 0;
            $stmt->close();
            return $exists;
        } else {
            return false;
        }
    }
}

// Cek apakah form sudah disubmit
$notification = ""; // Variabel untuk menyimpan pesan notifikasi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if ($name && $email && $password) {
        // Buat objek dari class UserRegistration
        $registration = new UserRegistration($conn);
        $message = $registration->register($name, $email, $password);

        if ($message == "success") {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('successMessage').textContent = 'Registrasi sukses! Silakan login.';
                        document.getElementById('successMessage').style.display = 'block';
                    });
                  </script>";
        } else {
            $notification = $message;
        }
    } else {
        $notification = "Semua field wajib diisi!";
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main>
        <header>
            <h1>Register</h1>
        </header>

        
        <div id="successMessage" class="notification" style="display: none; color: green;"></div>
        <?php if ($notification): ?>
            <div class="notification" style="color: red;">
                * <?= $notification; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <label for="name">Nama:</label>
            <input type="text" name="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Sign Up</button>
        </form>
        <p class="login-message">Already have an account? <a href="login.php">Login here</a></p>
    </main>
</body>
</html>

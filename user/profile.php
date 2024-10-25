<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$result = prepare_query($conn, $query, ['i', $user_id]);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die('User not found or query failed.');
}

$event_query = "SELECT events.event_name, events.event_date, events.location 
                FROM registrations 
                INNER JOIN events ON registrations.event_id = events.id 
                WHERE registrations.user_id = ?";
$event_result = prepare_query($conn, $event_query, ['i', $user_id]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $profile_image = $_FILES['profile_image']['name'] ?? null;

    $query_parts = [];
    $params = [];
    $param_types = '';

    if (!empty($name)) {
        $query_parts[] = "name = ?";
        $params[] = $name;
        $param_types .= 's';
    }

    if (!empty($email)) {
        $query_parts[] = "email = ?";
        $params[] = $email;
        $param_types .= 's';
    }

    if ($password) {
        $query_parts[] = "password = ?";
        $params[] = $password;
        $param_types .= 's';
    }

    if ($profile_image) {
        $target_dir = "uploads-profile/";
        $target_file = $target_dir . basename($profile_image);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $query_parts[] = "profile_image = ?";
                $params[] = $profile_image;
                $param_types .= 's';
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload gambar.']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Format file tidak didukung. Hanya JPG, JPEG, PNG, dan GIF yang diizinkan.']);
            exit();
        }
    }

    if (!empty($query_parts)) {
        $query = "UPDATE users SET " . implode(", ", $query_parts) . " WHERE id = ?";
        $params[] = $user_id;
        $param_types .= 'i';

        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param($param_types, ...$params);
            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Profil berhasil diperbarui!', 
                    'name' => $name, 
                    'email' => $email, 
                    'profile_image' => $profile_image ?? $user['profile_image']
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat memperbarui profil.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat mempersiapkan query.']);
        }
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="/project/assets/css/profile-style.css">
</head>
<body>
    <div class="profile-container">
        <h1>Profil Saya</h1>
        <div class="profile-info">
            <div class="profile-image">
                <img src="uploads-profile/<?php echo htmlspecialchars($user['profile_image'] ?? 'default-avatar.png'); ?>" alt="Profile Image" id="currentProfileImage" onerror="this.onerror=null; this.src='default-avatar.png';">
            </div>
            <div class="profile-details">
                <p>Nama: <span id="profileName"><?php echo htmlspecialchars($user['name']); ?></span></p>
                <p>Email: <span id="profileEmail"><?php echo htmlspecialchars($user['email']); ?></span></p>
            </div>
        </div>
        
        <div id="responseMessage" class="notification"></div>

        <div class="edit-profile-form">
            <h2>Edit Profil</h2>
            <form id="editProfileForm" method="POST" enctype="multipart/form-data">
                <label for="profile_image">Gambar Profil:</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*"><br>

                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

                <label for="password">Password Baru (Opsional):</label>
                <input type="password" id="password" name="password"><br>

                <button type="submit">Update Profile</button>
            </form>
            
            <button class="btn-back" onclick="window.location.href = document.referrer;">Back</button>
        </div>

        <div class="registered-events">
            <h2>Acara yang Sudah Diregistrasi</h2>
            <?php if ($event_result->num_rows > 0): ?>
                <ul>
                    <?php while ($event = $event_result->fetch_assoc()): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                            <p>Tanggal: <?php echo htmlspecialchars($event['event_date']); ?></p>
                            <p>Lokasi: <?php echo htmlspecialchars($event['location']); ?></p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Belum ada event yang diregistrasi.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.getElementById('editProfileForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'profile.php', true);

            xhr.onload = function() {
                console.log('Response from server:', this.responseText);
                try {
                    var response = JSON.parse(this.responseText);
                    var responseMessage = document.getElementById('responseMessage');

                    if (response.status === 'success') {
                        responseMessage.style.color = 'green';
                        responseMessage.innerHTML = response.message;

                        document.getElementById('profileName').innerText = response.name;
                        document.getElementById('profileEmail').innerText = response.email;

                        if (response.profile_image) {
                            var profileImage = document.getElementById('currentProfileImage');
                            profileImage.src = 'uploads-profile/' + response.profile_image;
                        }
                    } else {
                        responseMessage.style.color = 'red';
                        responseMessage.innerHTML = response.message;
                    }
                } catch (e) {
                    console.error("Parsing JSON gagal:", e);
                    console.error("Response yang diterima:", this.responseText);
                    document.getElementById('responseMessage').innerHTML = 'Terjadi kesalahan pada server.';
                }
            };

            xhr.onerror = function() {
                console.error('Error saat mengirim request ke server.');
                document.getElementById('responseMessage').innerHTML = 'Terjadi kesalahan saat menghubungi server.';
            };

            xhr.send(formData);
        });
    </script>
</body>
</html>

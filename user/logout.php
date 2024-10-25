<?php
session_start();

$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

session_unset();
session_destroy();

if ($user_role === 'admin') {
    header("Location: ../admin/login.php");
} else {
    header("Location: ../user/login.php");
}
exit();
?>

<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>

<?php
session_start();

session_unset();
session_destroy();

header("Location: ../user/login.php");
exit();
?>

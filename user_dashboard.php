<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: user_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="styles.css?v=4">
<head><title>Client Dashboard</title></head>
<body>
<header>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<header>
<nav>
    <a href="index.php">Home</a> |
    <a href="logout.php">Logout</a> |
    <a href="#explore">Explore</a>
</nav>
<p>This is your dashboard.</p>
</body>
</html>

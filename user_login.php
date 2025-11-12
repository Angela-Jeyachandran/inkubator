<?php
ini_set('session.cookie_path', '/');
session_name('inkubator_session');
session_start();

$usersFile = 'users.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

    if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $users[$username]['role'] ?? 'client';
        header('Location: user_dashboard.php');
        exit;
    } else {
        $error = "Invalid login.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css?v=4">
    <title>Client Login</title>
</head>
<header>
    <h1>Client Login</h1>
</header>
<br>
<body>
<?php // if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <label for="username"><b>Username</b></label>
    <input type="text" name="username" placeholder="Enter username" required>
    <br>
    <br>
    <label for="password"><b>Password</b></label>
    <input type="password" name="password" placeholder="Enter password" required>
    <br>
    <br>
    <button type="submit">Login</button>
</form>
<!--<p><a href="register.php">Register</a></p>-->
</body>
</html>

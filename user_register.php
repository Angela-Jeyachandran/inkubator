<?php
$usersFile = 'users.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username && $password) {
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        if (isset($users[$username])) {
            $error = "Username already exists!";
        } else {
            $users[$username] = password_hash($password, PASSWORD_DEFAULT);
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            $success = "Registered! <a href='login.php'>Login now</a>.";
        }
    } else {
        $error = "Fill in both fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
<form method="POST">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button type="submit">Register</button>
</form>
<p><a href="login.php">Login</a></p>
</body>
</html>

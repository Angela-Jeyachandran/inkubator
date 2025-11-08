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
<head>
<link rel="stylesheet" href="styles.css?v=4">
<title>Client Register</title>
</head>
<header>
<h1>Client Register</h1>
</header>
<br>
<body>

<?php // if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php // if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

<form method="POST">
    <label for="username"><b>Username</b></label>
    <input type="text" name="username" placeholder="Enter username">
    <br>
    <br>
    <label for="password"><b>Password</b></label>
    <input type="password" name="password" placeholder="Enter password">
    <br>
    <br>
    <button type="submit">Sign up</button>
</form>
<!--<p><a href="login.php">Login</a></p>-->
</body>
</html>

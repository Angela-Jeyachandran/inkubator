<?php
session_start();
$usersFile = 'users.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid login.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
    <link rel="stylesheet" href="styles.css?v=4">
    <title>Artist Login</title>
   </head>
    <header>
        <h1>Artist Login</h1> 
    </header>
</br>
<body>    
    <form method="POST" action="">

        <div class="container">
            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter username" name="username" required>
            <br>
            <br>
            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter password" name="password" required>
            <br>
            <br>
            <button type="submit">Login</button>
            <br>
            <label>
                <input type="checkbox" name="remember"> Remember me
            </label>
        </div>

        <div class="container">
            <span class="pass"><a href="#">Forgot password?</a></span>
        </div>
    </form>
</body>

</html>
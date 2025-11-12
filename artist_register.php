<?php
session_start();
$usersFile = 'users.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $pass_repeat = trim($_POST['pass_repeat']);
    $email = trim($_POST['email']);

    if ($username && $password && $pass_repeat && $email) {
        if ($password !== $pass_repeat) {
            $error = "Passwords do not match.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address.";
        } else {
            $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

            if (isset($users[$username])) {
                $error = "Username already exists.";
            } else {
                $users[$username] = [
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'email' => $email
                    'role' => 'artist'
                ];
                file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'artist';
                header('Location: artist_dashboard.php');
                exit;
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
    <link rel="stylesheet" href="styles.css?v=4">
    <title>Artist Registration</title>
   </head>
    <header>
        <h1>Create an Artist Account</h1> 
    </header>
</br>
<body>    
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="POST" action="">

        <label for="username"><b>Username</b></label>
        <input type="text" placeholder="Enter username" name="username" required>
        <br>
        <br>
        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter password" name="password" required>
        <br>
        <br>
        <label for="pass_repeat"><b>Re-enter password</b></label>
        <input type="password" placeholder="Re-enter password" name="pass_repeat" required>
        <br>
        <br>
        <label for="email"><b>Email</b></label>
        <input type="text"  placeholder="Enter Email" name="email" required>
        <br>
        <br>
        <br>
        <div class="clearfix">
            <button type="submit" class="signup_btn">Sign Up</button>
        </div>

    </form>
</body>

</html>
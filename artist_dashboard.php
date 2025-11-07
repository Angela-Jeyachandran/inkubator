<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$uploadDir = 'uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    
    $allowedTypes = ['image/jpeg', 'image/png'];
    
    if ($file['error'] === 0) {
        if (in_array($file['type'], $allowedTypes)) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $destination = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $success = "Image uploaded successfully.";
            } else {
                $error = "Failed to move uploaded file.";
            }
        } else {
            $error = "Only JPG and PNG files allowed.";
        }
    } else {
        $error = "Error uploading file.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
   <head>
    <link rel="stylesheet" href="styles.css?v=4">
    <title><?php echo htmlspecialchars($_SESSION['username']); ?>'s Dashboard</title>
   </head>
    <header>
        <h1><?php echo htmlspecialchars($_SESSION['username']); ?>'s Dashboard</h1> 
    </header>
</br>
<body>
<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<p>This is your dashboard.</p>
<br><br>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <div class="submit_flash">
        <form method="POST" action="" enctype="multipart/form-data">
        <label>Select tattoo flash to upload:</label>
        <input type="file" name="image" required>
        <br><br>
        <div class="flash_submit_btn">
            <button type="Submit">Upload</button>
        </div>
    </div>
    <br>
    


<br><br>
<a href="user_logout.php">Logout</a>
</body>
</html>

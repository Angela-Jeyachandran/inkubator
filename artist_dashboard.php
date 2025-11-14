<?php
// Keeps session consistent across the pages
ini_set('session.cookie_path', '/');
session_name('inkubator_session');
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$uploadDir = 'uploads/';

// may not need this? 
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
                
                // keywords
                $keywords = trim($_POST['keywords']);
                $metaFile = 'uploads/meta.json';
                $metaData = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : [];

                $metaData[] = [
                    'username' => $_SESSION['username'],
                    'filename' => $filename,
                    'keywords' => array_map('trim', explode(',', $keywords)),
                    'timestamp' => date('Y-m-d H:i:s')
                ];

                file_put_contents($metaFile, json_encode($metaData, JSON_PRETTY_PRINT));

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
        <div class="logout_btn">
            <form action="logout.php">
                <button class="logout_btn" type="submit">Logout</button>
            </form>
        </div>
        <img src="inkubator_logo.png" alt="logo" width="350" height="100">

        <h1><?php echo htmlspecialchars($_SESSION['username']); ?>'s Dashboard</h1> 

        <nav class="main-nav">    
            <a href="tattoo_search.php">Browse Uploaded Designs</a>
            <!--<a href="#">Messages</a>-->
        </nav>
    </header>
</br>
<body>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>

    <div class="submit_flash">
        <form method="POST" action="" enctype="multipart/form-data">
            <label>Select tattoo flash to upload:</label>
            <input type="file" name="image" required>

            <br><br>

            <label>Enter keywords (comma-separated):</label>
            <input type="text" name="keywords" required placeholder="e.g. american traditional, minimalist, floral">
        <br><br>

        <div class="flash_submit_btn">
            <button type="Submit">Upload</button>
        </div>
        </form>

    </div>
    <br><hr><br>

    <h3>Your Tattoo Designs:</h3>
    <?php
    $metaFile = 'uploads/meta.json';
    if (file_exists($metaFile)) {
        $metaData = json_decode(file_get_contents($metaFile), true);
        echo '<div class="gallery-grid">';
        foreach ($metaData as $item) {
            if ($item['username'] === $_SESSION['username']) {
                echo '<div class="gallery-item">';
                echo '<img src="uploads/' . htmlspecialchars($item['filename']) . '" alt="Tattoo flash">';
                echo '<div class="keywords">';
                echo '<strong>Keywords:</strong> ' . htmlspecialchars(implode(', ', $item['keywords']));
                echo '</div>';
                echo '</div>';
            }
        }
        echo '</div>';
    } else {
        echo "<p>No uploads yet.</p>";
    }
    ?>

</body>
</html>
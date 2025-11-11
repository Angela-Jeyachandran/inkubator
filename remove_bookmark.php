<?php
// Keeps session consistent across the pages 
ini_set('session.cookie_path', '/');
session_name('inkubator_session');
session_start();

// If not logged in goes to home page
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}

$bookmarksFile = 'bookmarks.json';
$username = $_SESSION['username'];
$filename = $_POST['filename'] ?? '';

if ($filename === '') {
    die('Invalid request');
}

// Put bookmarks into array
$data = file_exists($bookmarksFile) ? json_decode(file_get_contents($bookmarksFile), true) : [];
if (!is_array($data)) $data = [];

// Removes bookmark
if (isset($data[$username])) {
    $data[$username] = array_values(array_filter(
        $data[$username],
        fn($f) => $f !== $filename
    ));
}

// Updates bookmarks.json file
file_put_contents($bookmarksFile, json_encode($data, JSON_PRETTY_PRINT));

// Stays on user dashboard
header('Location: user_dashboard.php');
exit;
?>

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

// Ensure file is readable/writable
if (!file_exists($bookmarksFile)) {
    file_put_contents($bookmarksFile, json_encode([], JSON_PRETTY_PRINT));
}

// Load existing bookmarks
$data = json_decode(file_get_contents($bookmarksFile), true);
if (!is_array($data)) {
    $data = [];
}

// Add new bookmark if not already 
if (!isset($data[$username])) {
    $data[$username] = [];
}

if (!in_array($filename, $data[$username], true)) {
    $data[$username][] = $filename;
}

// Save changes 
file_put_contents($bookmarksFile, json_encode($data, JSON_PRETTY_PRINT));

// Keeps on search page
if (!empty($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'tattoo_search.php')) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: tattoo_search.php');
}
exit;

?>

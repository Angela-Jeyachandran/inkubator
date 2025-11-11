<?php
// Keeps session consistent across the pages 
ini_set('session.cookie_path', '/');
session_name('inkubator_session');
session_start();
// Debug statement
// echo "<p>DEBUG SESSION: " . ($_SESSION['username'] ?? 'NONE') . "</p>";

// If not logged in goes to home page
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);

// File that stores bookmarks
$bookmarksFile = 'bookmarks.json';
$bookmarked = [];

// Load the saved tattoos if exists
if (file_exists($bookmarksFile)) {
    $data = json_decode(file_get_contents($bookmarksFile), true);
    if (isset($data[$_SESSION['username']]) && is_array($data[$_SESSION['username']])) {
        $bookmarked = $data[$_SESSION['username']];
    }
}

?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="styles.css?v=4">
</head>
<body>
<header class="dashboard-header">
    <div class="logout_btn">
        <form action="user_logout.php">
            <button class="logout_btn" type="submit">Logout</button>
        </form>
    </div>
    <img src="inkubator_logo.png" alt="logo" width="350" height="100">

    <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p><?php echo count($bookmarked); ?> saved tattoos</p>

    <nav class="main-nav">
    <a href="about.html">About</a>
    <a href="tattoo_search.php">Find Your Next Ink</a>
    <a href="logout.php">Messages</a>
</nav>
</header>

<main class="dashboard-content">
    <section class="bookmarked-section">
    <h3>My Saved Tattoos</h3>
        <div class="tattoo-section">
            <!-- Shows bookmarked tattoos -->
            <?php if (!empty($bookmarked)): ?>
            <div class="results-grid">
                <?php foreach ($bookmarked as $file): ?>
                    <div class="result-item">
                        <img src="uploads/<?php echo htmlspecialchars($file, ENT_QUOTES); ?>" alt="Tattoo idea">
                        <form action="remove_bookmark.php" method="POST">
                            <input type="hidden" name="filename" value="<?php echo htmlspecialchars($file, ENT_QUOTES); ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="tattoo-section">
                <!-- If no tattoos saved -->
                <p>You haven’t saved any tattoos yet.</p>
                <a href="tattoo_search.php" class="explore-btn">Start saving ideas</a>
            </div>
        <?php endif; ?>
        </div>
    </section>
</main>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: user_login.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
// Placeholder array for bookmarked tattoos
$bookmarked = [];

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
    <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p><?php echo count($bookmarked); ?> saved tattoos</p>
</header>

<nav class="main-nav">
    <a href="about.html">About</a>
    <a href="tattoo_search.php">Find Your Next Ink</a>
    <a href="logout.php">Messages</a>
</nav>

<main class="dashboard-content">
    <section class="bookmarked-section">
    <h3>My Saved Tattoos</h3>
        <div class="tattoo-section">
        <p>You haven’t saved any tattoos yet.</p>
        <a href="explore.php" class="explore-btn">Start saving ideas</a>
        </div>
    </section>
</main>
</body>
</html>

<?php
// Keeps session consistent across the pages 
ini_set('session.cookie_path', '/');
session_name('inkubator_session');
session_start();

echo "<pre>";
print_r($_SESSION);
echo "</pre>";
// Debug statement
// echo "<p>DEBUG SESSION: " . ($_SESSION['username'] ?? 'NONE') . "</p>";

$metaFile = 'uploads/meta.json';
$results = [];
$queryRaw = isset($_GET['q']) ? $_GET['q'] : '';
$query = strtolower(trim($queryRaw));
$queries = [];

if ($query !== '') {
    $queries = preg_split('/[\s,]+/', $query);
    $queries = array_filter(array_map('trim', $queries), fn($v) => $v !== '');
}

$metaData = [];
if (file_exists($metaFile)) {
    $contents = trim(file_get_contents($metaFile));
    if ($contents !== '') {
        $decoded = json_decode($contents, true);
        if (is_array($decoded)) {
            $metaData = $decoded;
        } else {
            error_log("tattoo_search.php: Failed to parse $metaFile: " . json_last_error_msg());
            $metaData = [];
        }
    }
}

if (!empty($queries) && !empty($metaData)) {
    $seen = []; // to prevent duplicate results
    foreach ($metaData as $item) {
        if (!isset($item['keywords']) || !is_array($item['keywords']) || !isset($item['filename'])) {
            continue;
        }
        foreach ($item['keywords'] as $kw) {
            $kwLower = strtolower(trim($kw));
            foreach ($queries as $q) {
                if ($q === '') continue;
                if (strpos($kwLower, $q) !== false) {
                    if (!in_array($item['filename'], $seen, true)) {
                        $results[] = $item;
                        $seen[] = $item['filename'];
                    }
                    continue 3;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Find Your Next Ink</title>
    <link rel="stylesheet" href="styles.css?v=4">
</head>
<body>
    <header>
        <div class="logout_btn">
            <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['client', 'artist'])): ?>
            <!-- Show logout button if user is logged in -->
            <form action="user_logout.php" method="post">
                <button class="logout_btn" type="submit">Logout</button>
            </form>
            <?php endif; ?>
        </div>
        <img src="inkubator_logo.png" alt="logo" width="350" height="100">
        <nav class="main-nav">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'client'): ?>
            <!-- Client nav -->
            <a href="user_dashboard.php">Dashboard</a>
            <a href="#">Messages</a>
        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'artist'): ?>
            <!-- Artist nav -->
            <a href="artist_dashboard.php">Dashboard</a>
            <a href="#">Messages</a>
        <?php else: ?>
            <!-- Guest nav -->
            <a href="index.html">Home</a>
            <a href="login_options.html">Login</a>
            <a href="register_options.html">Register</a>
        <?php endif; ?>
        </nav>

        <h1>Find Your Next Ink</h1>
    </header>

    <p>Enter keywords for the type of tattoo you might want and we will show you some ideas.</p>

    <form method="GET" action="">
        <input type="text" name="q" placeholder="Enter keywords (e.g. floral, irezumi, minimalist, etc.)"
            value="<?php echo htmlspecialchars($queryRaw, ENT_QUOTES); ?>">
        <button type="submit">Search</button>
    </form>

    <hr>

    <!--Success message shown when saved-->
    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
        <div class="success-message">
            Bookmark saved successfully!
        </div>
    <?php endif; ?>

    <?php if ($query !== ''): ?>
        <h2>Results for "<?php echo htmlspecialchars($queryRaw, ENT_QUOTES); ?>"</h2>

        <?php if (!empty($results)): ?>
            <div class="results-grid">
                <?php foreach ($results as $item): ?>
                    <div class="result-item">
                        <img src="uploads/<?php echo htmlspecialchars($item['filename'], ENT_QUOTES); ?>" alt="">
                        <p><strong>By:</strong> <?php echo isset($item['username']) ? htmlspecialchars($item['username']) : 'Unknown'; ?></p>
                        <p><strong>Keywords:</strong> <?php echo htmlspecialchars(implode(', ', $item['keywords']), ENT_QUOTES); ?></p>
                        <!-- Logged-in user Bookmarking -->
                        <?php if (isset($_SESSION['username'])): ?>
                            <form action="bookmark.php" method="POST">
                                <input type="hidden" name="filename" value="<?php echo htmlspecialchars($item['filename'], ENT_QUOTES); ?>">
                                <button type="submit">Bookmark</button>
                            </form>
                        <!-- Guest Browsing -->
                        <?php else: ?>
                            <p class="login-prompt">
                            <a href="login.php" class = "button">Login or create an account</a> 
                            <br>
                            to bookmark this design.
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

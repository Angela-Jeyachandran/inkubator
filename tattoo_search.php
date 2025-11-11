<?php
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
            <form action="user_logout.php">
                <button class="logout_btn" type="submit">Logout</button>
            </form>
        </div>
        <img src="inkubator_logo.png" alt="logo" width="350" height="100">
        <nav class="main-nav">
            <a href="about.html">About</a>
            <a href="user_dashboard.php">Dashboard</a>
            <a href="logout.php">Messages</a>
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

    <?php if ($query !== ''): ?>
        <h2>Results for "<?php echo htmlspecialchars($queryRaw, ENT_QUOTES); ?>"</h2>

        <?php if (!empty($results)): ?>
            <div class="results-grid">
                <?php foreach ($results as $item): ?>
                    <div class="result-item">
                        <img src="uploads/<?php echo htmlspecialchars($item['filename'], ENT_QUOTES); ?>" alt="">
                        <p><strong>By:</strong> <?php echo isset($item['username']) ? htmlspecialchars($item['username']) : 'Unknown'; ?></p>
                        <p><strong>Keywords:</strong> <?php echo htmlspecialchars(implode(', ', $item['keywords']), ENT_QUOTES); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

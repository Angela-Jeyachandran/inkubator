<?php
$metaFile = 'uploads/meta.json';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['q'])) {
    $queries = preg_split('/[\s,]+/', $query); 

    foreach ($metaData as $item) {
        foreach ($item['keywords'] as $kw) {
            foreach ($queries as $q) {
                if (strpos(strtolower($kw), $q) !== false) {
                    $results[] = $item;
                    break 2; 
                }
            }
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
   <head>
    <link rel="stylesheet" href="styles.css?v=4">
    <title>Find Your Next Ink</title>
   </head>
    <header>
    <div class="logout_btn">
        <form action="user_logout.php">
            <button class="logout_btn" type="submit">Logout</button>
        </form>
    </div>
        <h1>Find Your Next Ink</h1> 
    </header>
</br>
<body>
    <p>Enter keywords for the type of tattoo you might want and we will show you some ideas.</p>
    
    <form method="GET" action="">
        <input type="text" name="q" placeholder="Enter comma-separated keywords (e.g. floral, irezumi, minimalist, etc.)"
            value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']): '';?>" required>
        <button type="submit">Search</button>
    </form>

    <hr>

    <?php if (!empty($_GET['q'])): ?>
        <h2>Your Inkspiration:</h2>
        <?php if (!empty($results)): ?>
            <div class="results-grid">
                <?php foreach ($results as $item): ?>
                    <div class="result-item">
                        <img src="uploads/<?php echo htmlspecialchars($item['filename']); ?>" alt="">
                        <p><strong>By:</strong> <?php echo htmlspecialchars($item['username']); ?></p>
                        <p><strong>Keywords:</strong> <?php echo htmlspecialchars(implode(', ', $item['keywords'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>
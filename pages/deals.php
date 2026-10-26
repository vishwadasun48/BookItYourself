<?php
// Featured stays are displayed until a discount field/table is added.
$conn = new mysqli('localhost', 'root', '', 'bookityourself_db');
if ($conn->connect_error) {
    http_response_code(500);
    die('Database connection failed. Please check XAMPP MySQL settings.');
}
$conn->set_charset('utf8mb4');
$result = $conn->query('SELECT id, title, location, hero_image, rating, price_per_night FROM stays ORDER BY rating DESC LIMIT 12');
if (!$result) {
    http_response_code(500);
    die('Unable to load stays. Please check the stays table.');
}
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Deals & Featured Stays | BookItYourself</title>
  <link rel="stylesheet" href="/bookityourself/css/deals.css">
</head>
<body>
  <div id="header-placeholder"></div>
  <main class="deals-container">
    <section class="deals-hero">
      <span class="eyebrow">BOOKITYOURSELF PICKS</span>
      <h1>Explore Deals & Featured Stays</h1>
      <p>Find your next getaway among our highly rated hotels and villas.</p>
      <p class="deals-note">Special discount prices will appear here when offers are added to the database.</p>
    </section>
    <section class="deals-section" aria-labelledby="featured-title">
      <div class="section-heading">
        <div><h2 id="featured-title">Featured stays</h2><p>Popular places worth exploring</p></div>
        <a class="all-stays-link" href="/bookityourself/pages/hotels.php">View all stays &rarr;</a>
      </div>
      <div class="deals-grid">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($stay = $result->fetch_assoc()): ?>
            <a class="deal-card" href="/bookityourself/pages/samplestay.php?id=<?= (int)$stay['id'] ?>">
              <img src="/bookityourself/Images/Stays/<?= e(basename((string)$stay['hero_image'])) ?>" alt="<?= e($stay['title']) ?>" loading="lazy">
              <div class="deal-content">
                <h3><?= e($stay['title']) ?></h3>
                <p class="location"><?= e($stay['location']) ?></p>
                <div class="deal-meta"><span>★ <?= number_format((float)$stay['rating'], 1) ?></span><strong>From LKR <?= number_format((float)$stay['price_per_night']) ?> <small>/ night</small></strong></div>
              </div>
            </a>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="empty-state">No stays available right now. Please check back later.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>
  <div id="footer-placeholder"></div>
  <script>
    async function loadPartial(id, path) {
      try {
        const response = await fetch(path);
        if (!response.ok) throw new Error('Unable to load ' + path);
        document.getElementById(id).innerHTML = await response.text();
      } catch (error) { console.error(error); }
    }
    loadPartial('header-placeholder', '/bookityourself/includes/header.php');
    loadPartial('footer-placeholder', '/bookityourself/includes/footer.php');
  </script>
</body>
</html>
<?php $conn->close(); ?>

<?php
// 1. Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bookityourself_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// 2. Search query එක ලබාගැනීම
$destination = isset($_GET['destination']) ? trim($_GET['destination']) : '';

// 3. Database එකෙන් අදාළ location එකට අදාළ stays සෙවීම
$stmt = $conn->prepare("SELECT * FROM stays WHERE location LIKE ? ORDER BY rating DESC");
$searchTerm = "%" . $destination . "%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results - BookItYourself</title>
  <link rel="stylesheet" href="/bookityourself/css/searchresult.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <div id="header-placeholder"></div>

  <main class="container">
    <div class="search-header-info">
      <h1>Search Results for: "<span><?php echo htmlspecialchars($destination); ?></span>"</h1>
      <p><?php echo $result->num_rows; ?> stays found matching your destination.</p>
    </div>

    <section class="results-section">
      <div class="grid-3">
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($stay = $result->fetch_assoc()): ?>
            <a href="samplestay.php?id=<?php echo $stay['id']; ?>" class="stay-card">
              <div class="card-img-wrapper">
                <img src="../Images/Stays/<?php echo htmlspecialchars($stay['hero_image']); ?>" alt="<?php echo htmlspecialchars($stay['title']); ?>" class="stay-image">
                <span class="room-badge"><?php echo htmlspecialchars($stay['room_type']); ?></span>
              </div>
              <div class="card-content">
                <h3><?php echo htmlspecialchars($stay['title']); ?></h3>
                <p class="location-text"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($stay['location']); ?></p>
                <div class="stay-meta">
                  <span class="stay-details">
                    <span class="rating"><i class="fa-solid fa-star"></i> <?php echo number_format($stay['rating'], 1); ?></span> 
                    <span class="review-count">(<?php echo $stay['total_reviews']; ?> reviews)</span>
                  </span>
                  <span class="price">From LKR <?php echo number_format($stay['price_per_night']); ?></span>
                </div>
              </div>
            </a>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="no-results">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <h3>No stays found in this location!</h3>
            <p>Try searching for another destination like Bentota, Galle, or Kandy.</p>
            <a href="../homehtml.php" class="btn-back">Back to Home</a>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <div id="footer-placeholder"></div>

  <script>
    fetch('/bookityourself/includes/header.php')
      .then(response => response.text())
      .then(data => {
        document.getElementById('header-placeholder').innerHTML = data;
      });

    fetch('/bookityourself/includes/footer.php')
      .then(response => response.text())
      .then(data => {
        document.getElementById('footer-placeholder').innerHTML = data;
      });
  </script>

</body>
</html>
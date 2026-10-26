<?php
// 1. Database Connection එක හදාගැනීම
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bookityourself_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// 2. Database එකේ ඇති සියලුම Stays ලබාගැනීම
$sql = "SELECT * FROM stays ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Hotels & Stays - BookItYourself</title>
  <link rel="stylesheet" href="/bookityourself/css/hotels.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <!-- Header Placeholder -->
  <div id="header-placeholder"></div>

  <main class="container">
    <div class="page-header">
      <h1>Explore All Hotels & Stays</h1>
      <p>Discover our wide range of luxury villas, beach houses, and cozy mountain escapes.</p>
    </div>

    <!-- Hotels Grid Section -->
    <section class="hotels-section">
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
          <p class="no-data">Currently, no hotels or stays available.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <!-- Footer Placeholder -->
  <div id="footer-placeholder"></div>

  <script>
    // Header සහ Footer Load කරගැනීමට
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
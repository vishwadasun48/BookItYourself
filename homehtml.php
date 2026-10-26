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

// 2. Popular Stays (Rating එක වැඩිම Stays මුලින්ම එන සේ 6ක් ලබාගැනීම)
$sql = "SELECT * FROM stays ORDER BY rating DESC LIMIT 6";
$result = $conn->query($sql);

// 3. Database එකේ stays තියෙන Unique Locations (Destination) ටික ලබාගැනීම
$loc_sql = "SELECT DISTINCT location FROM stays ORDER BY location ASC";
$loc_result = $conn->query($loc_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BookItYourself - Customer Home Page</title>
  <link rel="stylesheet" href="/bookityourself/css/homecss.css">
</head>
<body>

<div id="header-placeholder"></div>

  <main class="container">
    <section class="hero">
      <h1>Find your perfect stay</h1>
      <p>Hotels and villas for every trip, budget and occasion.</p>

      <!-- Search Form (GET method එක හරහා searchresult.php වෙත යැවීම) -->
      <form class="search-card" action="pages/searchresult.php" method="GET">
        <div class="search-field">
          <label for="destination">Destination</label>
          <input type="text" id="destination" name="destination" list="districts-list" placeholder="Where are you going?" required>
          <datalist id="districts-list">
            <?php if ($loc_result && $loc_result->num_rows > 0): ?>
              <?php while($loc = $loc_result->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($loc['location']); ?>">
              <?php endwhile; ?>
            <?php endif; ?>
          </datalist>
        </div>
        <div class="search-field">
          <label for="check-in">Check-in</label>
          <input type="date" id="check-in" name="check_in">
        </div>
        <div class="search-field">
          <label for="check-out">Check-out</label>
          <input type="date" id="check-out" name="check_out">
        </div>
        <div class="search-field">
          <label for="guests">Guests</label>
          <input type="number" id="guests" name="guests" min="1" placeholder="1">
        </div>
        <button type="submit" class="btn-search">Search</button>
      </form>
    </section>

    <!-- Dynamic Popular Stays Section -->
    <section>
      <h2 class="section-title">Popular stays</h2>
      <div class="grid-3">

        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($stay = $result->fetch_assoc()): ?>
            <a href="pages/samplestay.php?id=<?php echo $stay['id']; ?>" class="stay-card">
              <img src="Images/Stays/<?php echo htmlspecialchars($stay['hero_image']); ?>" alt="<?php echo htmlspecialchars($stay['title']); ?>" class="stay-image">
              <h3><?php echo htmlspecialchars($stay['title']); ?></h3>
              <div class="stay-meta">
                <span class="stay-details">
                  <span class="rating">★ <?php echo number_format($stay['rating'], 1); ?></span> • <?php echo htmlspecialchars($stay['location']); ?>
                </span>
                <span class="price">From LKR <?php echo number_format($stay['price_per_night']); ?></span>
              </div>
            </a>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No stays available right now.</p>
        <?php endif; ?>

      </div>
    </section>

    <section>
      <h2 class="section-title">Why BookItYourself?</h2>
      <div class="grid-3">
        <div class="feature-card">
          <h4>Verified stays</h4>
          <p>Quality listings from registered owners</p>
        </div>
        <div class="feature-card">
          <h4>Flexible choice</h4>
          <p>Compare hotels and villas in one place</p>
        </div>
        <div class="feature-card">
          <h4>Secure booking</h4>
          <p>Clear prices and protected payments</p>
        </div>
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
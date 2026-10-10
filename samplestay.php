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

// 2. URL එකෙන් stay ID එක ලබාගැනීම (ID එකක් නැත්නම් default ලෙස 1 ගනී)
$stay_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// 3. Select කරපු Stay එකේ සියලුම විස්තර Database එකෙන් ලබාගැනීම
$stay_stmt = $conn->prepare("SELECT * FROM stays WHERE id = ?");
$stay_stmt->bind_param("i", $stay_id);
$stay_stmt->execute();
$stay_result = $stay_stmt->get_result();

if ($stay_result->num_rows == 0) {
    die("සමාවන්න, ඔබ සෙවූ Stay එක හමු වූයේ නැත!");
}
$stay = $stay_result->fetch_assoc();

// 4. එම Stay එකට අදාළ Facilities (පහසුකම්) ලබාගැනීම
$fac_stmt = $conn->prepare("
    SELECT f.name, f.icon_class 
    FROM facilities f 
    INNER JOIN stay_facilities sf ON f.id = sf.facility_id 
    WHERE sf.stay_id = ?
");
$fac_stmt->bind_param("i", $stay_id);
$fac_stmt->execute();
$facilities = $fac_stmt->get_result();

// 5. Gallery Images ලබාගැනීම
$img_stmt = $conn->prepare("SELECT image_path FROM stay_images WHERE stay_id = ?");
$img_stmt->bind_param("i", $stay_id);
$img_stmt->execute();
$images = $img_stmt->get_result();

// 6. Guest Reviews ලබාගැනීම
$rev_stmt = $conn->prepare("SELECT * FROM reviews WHERE stay_id = ?");
$rev_stmt->bind_param("i", $stay_id);
$rev_stmt->execute();
$reviews = $rev_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($stay['title']); ?> - BookItYourself</title>
  <link rel="stylesheet" href="stay.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <div id="header-placeholder"></div>

  <main class="container">
    <!-- Dynamic Hero Banner Photo -->
    <div class="stay-banner">
      <img src="Images/Stays/<?php echo htmlspecialchars($stay['hero_image']); ?>" alt="<?php echo htmlspecialchars($stay['title']); ?>" class="banner-img">
    </div>

    <div class="stay-content-grid">
      
      <!-- වම් පැත්තේ තොරතුරු -->
      <div class="stay-main-info">
        <div class="title-header">
          <div>
            <h1><?php echo htmlspecialchars($stay['title']); ?></h1>
            <div class="rating-info">
              <span class="stars"><i class="fa-solid fa-star"></i> <?php echo number_format($stay['rating'], 1); ?></span>
              <span class="reviews">• <?php echo $stay['total_reviews']; ?> Reviews</span>
            </div>
          </div>
          <div class="price-box">
            <span class="price-label">From LKR</span>
            <span class="price-amount"><?php echo number_format($stay['price_per_night']); ?></span>
            <span class="price-period">/ Night</span>
          </div>
        </div>

        <!-- Description -->
        <section class="section-block">
          <h2>About This Property</h2>
          <p class="description">
            <?php echo nl2br(htmlspecialchars($stay['description'])); ?>
          </p>
        </section>

        <!-- Dynamic Facilities -->
        <section class="section-block">
          <h2>Facilities</h2>
          <div class="facilities-grid">
            <?php if ($facilities->num_rows > 0): ?>
              <?php while($fac = $facilities->fetch_assoc()): ?>
                <div class="facility-item">
                  <i class="<?php echo htmlspecialchars($fac['icon_class']); ?>"></i> 
                  <?php echo htmlspecialchars($fac['name']); ?>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <p>Facilities ඇතුළත් කර නැත.</p>
            <?php endif; ?>
          </div>
        </section>

        <!-- Dynamic Map -->
        <section class="section-block">
          <h2>Map</h2>
          <div class="map-box">
            <iframe 
              src="<?php echo htmlspecialchars($stay['map_url']); ?>" 
              width="100%" height="200" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy">
            </iframe>
          </div>
        </section>
      </div>

      <!-- දකුණු පැත්තේ Booking Form සහ Gallery -->
      <div class="stay-sidebar">
        
        <!-- Booking Card -->
        <div class="booking-card">
          <form class="booking-form" action="add_to_cart.php" method="POST">
            <!-- Hidden Dynamic Fields -->
            <input type="hidden" name="stay_id" value="<?php echo $stay['id']; ?>">
            <input type="hidden" name="property_name" value="<?php echo htmlspecialchars($stay['title']); ?>">
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($stay['location']); ?>">
            <input type="hidden" name="price_per_night" value="<?php echo $stay['price_per_night']; ?>">
            <input type="hidden" name="image" value="<?php echo htmlspecialchars($stay['hero_image']); ?>">
            <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($stay['room_type']); ?>">

            <div class="form-group">
              <label>Stay Duration</label>
              <div class="date-inputs">
                <input type="date" name="check_in" value="<?php echo date('Y-m-d'); ?>" required>
                <span>to</span>
                <input type="date" name="check_out" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label>Guests</label>
              <input type="number" name="guests" min="1" value="2" required>
            </div>

            <button type="submit" class="btn-book">Book Now / Add to Cart</button>
          </form>
        </div>

        <!-- Dynamic Gallery Grid -->
        <div class="gallery-grid">
          <?php if ($images->num_rows > 0): ?>
            <?php while($img = $images->fetch_assoc()): ?>
              <img src="Images/Stays/<?php echo htmlspecialchars($img['image_path']); ?>" alt="Gallery Image">
            <?php endwhile; ?>
          <?php endif; ?>
        </div>

        <!-- Dynamic Reviews -->
        <div class="reviews-card">
          <div class="reviews-header">
            <h3>Guest Reviews</h3>
            <a href="#" class="view-all">View all</a>
          </div>
          <?php if ($reviews->num_rows > 0): ?>
            <?php while($rev = $reviews->fetch_assoc()): ?>
              <div class="review-item">
                <div class="avatar"><?php echo strtoupper(substr($rev['reviewer_name'], 0, 1)); ?></div>
                <p>"<?php echo htmlspecialchars($rev['comment']); ?>"</p>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p style="font-size: 12px; color: #64748b;">Reviews එකතු කර නැත.</p>
          <?php endif; ?>
        </div>

      </div>

    </div>
  </main>

  <div id="footer-placeholder"></div>

</body>
</html>
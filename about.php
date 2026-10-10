<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - BookItYourself</title>
  <link rel="stylesheet" href="homecss.css">
  <link rel="stylesheet" href="about.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <!-- Header Include -->
  <?php include 'header.php'; ?>

  <main class="about-container">
    
    <!-- Hero Banner Section -->
    <section class="about-hero">
      <h1>About BookItYourself</h1>
      <p>Your trusted partner in finding the perfect getaway, luxury hotels, and private villas across Sri Lanka.</p>
    </section>

    <!-- Mission & Vision -->
    <section class="mission-section">
      <div class="mission-card">
        <i class="fa-solid fa-compass"></i>
        <h2>Our Mission</h2>
        <p>To make travel accommodation booking effortless, transparent, and accessible for everyone by connecting travelers directly with verified stays.</p>
      </div>

      <div class="mission-card">
        <i class="fa-solid fa-eye"></i>
        <h2>Our Vision</h2>
        <p>To become Sri Lanka’s leading dynamic online stay-booking platform, recognized for trust, innovation, and exceptional customer experience.</p>
      </div>
    </section>

    <!-- Why Choose Us -->
    <section class="features-section">
      <h2>Why Book With Us?</h2>
      <div class="features-grid">
        <div class="feature-item">
          <i class="fa-solid fa-hotel"></i>
          <h3>Verified Stays</h3>
          <p>Every hotel and villa on our platform is handpicked and quality-checked.</p>
        </div>

        <div class="feature-item">
          <i class="fa-solid fa-shield-halved"></i>
          <h3>Secure Booking</h3>
          <p>Your payments and account details are protected with high-level security.</p>
        </div>

        <div class="feature-item">
          <i class="fa-solid fa-tag"></i>
          <h3>Best Rates</h3>
          <p>No hidden fees! Enjoy clear pricing and exclusive seasonal deals.</p>
        </div>

        <div class="feature-item">
          <i class="fa-solid fa-headset"></i>
          <h3>24/7 Support</h3>
          <p>Our dedicated support team is always here to assist you with your bookings.</p>
        </div>
      </div>
    </section>

    <!-- Call To Action -->
    <section class="about-cta">
      <h2>Ready to start your next journey?</h2>
      <a href="hotels.php" class="btn-explore">Explore Hotels & Villas</a>
    </section>

  </main>

  <!-- Footer Include -->
  <?php include 'footer.php'; ?>

</body>
</html>
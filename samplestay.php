<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bentota Luxury Escape - BookItYourself</title>
  <link rel="stylesheet" href="stay.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <div id="header-placeholder"></div>

  <main class="container">
    <div class="stay-banner">
      <img src="bentota-hero.jpg" alt="Bentota Luxury Escape" class="banner-img">
    </div>

    <div class="stay-content-grid">
      
      <div class="stay-main-info">
        <div class="title-header">
          <div>
            <h1>Bentota Luxury Escape</h1>
            <div class="rating-info">
              <span class="stars"><i class="fa-solid fa-star"></i> 4.9</span>
              <span class="reviews">• 32 Reviews</span>
            </div>
          </div>
          <div class="price-box">
            <span class="price-label">From LKR</span>
            <span class="price-amount">45,000</span>
            <span class="price-period">/ Night</span>
          </div>
        </div>

        <section class="section-block">
          <h2>About This Property</h2>
          <p class="description">
            Bentota Luxury Escape is a respected beachfront villa overlooking the Indian Ocean. Enjoy private ocean views, spacious luxury suites, direct beach access, and ultimate tranquility for your getaway.
          </p>
        </section>

        <section class="section-block">
          <h2>Facilities</h2>
          <div class="facilities-grid">
            <div class="facility-item"><i class="fa-solid fa-wind"></i> A/C</div>
            <div class="facility-item"><i class="fa-solid fa-wifi"></i> Wi-Fi</div>
            <div class="facility-item"><i class="fa-solid fa-person-swimming"></i> Pool</div>
            <div class="facility-item"><i class="fa-solid fa-umbrella-beach"></i> Beachfront</div>
            <div class="facility-item"><i class="fa-solid fa-square-parking"></i> Parking</div>
            <div class="facility-item"><i class="fa-solid fa-utensils"></i> Restaurant</div>
          </div>
        </section>

        <section class="section-block">
          <h2>Map</h2>
          <div class="map-box">
            <iframe 
              src="https://maps.google.com/maps?q=Bentota&t=&z=13&ie=UTF8&iwloc=&output=embed" 
              width="100%" height="200" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy">
            </iframe>
          </div>
        </section>
      </div>

      <div class="stay-sidebar">
        
        <div class="booking-card">
          <form class="booking-form" action="add_to_cart.php" method="POST">
          <!-- Hidden fields for Stay details -->
            <input type="hidden" name="stay_id" value="1">
            <input type="hidden" name="property_name" value="Bentota Luxury Escape">
            <input type="hidden" name="location" value="Bentota">
            <input type="hidden" name="price_per_night" value="45000">
            <input type="hidden" name="image" value="bentota-hero.jpg">
            <input type="hidden" name="room_type" value="Deluxe Sea View">

          <div class="form-group">
            <label>Stay Duration</label>
            <div class="date-inputs">
              <input type="date" name="check_in" value="2026-09-01" required>
                <span>to</span>
              <input type="date" name="check_out" value="2026-09-02" required>
            </div>
        </div>

    <div class="form-group">
      <label>Guests</label>
      <input type="number" name="guests" min="1" value="2" required>
    </div>

    <button type="submit" class="btn-book">Book Now / Add to Cart</button>
  </form>
</div>

        <div class="gallery-grid">
          <img src="thumb1.jpg" alt="Gallery 1">
          <img src="thumb2.jpg" alt="Gallery 2">
          <img src="thumb3.jpg" alt="Gallery 3">
          <img src="thumb4.jpg" alt="Gallery 4">
        </div>

        <div class="reviews-card">
          <div class="reviews-header">
            <h3>Guest Reviews</h3>
            <a href="#" class="view-all">View all</a>
          </div>
          <div class="review-item">
            <div class="avatar">J</div>
            <p>"Perfect luxury getaway. Beautiful pool and clean beachfront in Bentota."</p>
          </div>
        </div>

      </div>

    </div>
  </main>

  <div id="footer-placeholder"></div>

</body>
</html>
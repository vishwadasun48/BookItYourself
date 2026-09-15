<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BookItYourself - Customer Home Page</title>
  <link rel="stylesheet" href="homecss.css">
</head>
<body>

<div id="header-placeholder"></div>

  <main class="container">
    <section class="hero">
      <h1>Find your perfect stay</h1>
      <p>Hotels and villas for every trip, budget and occasion.</p>

      <form class="search-card" onsubmit="event.preventDefault();">
        <div class="search-field">
          <label for="destination">Destination</label>
          <input type="text" id="destination" list="districts-list" placeholder="Where are you going?">
          <datalist id="districts-list">
            <option value="Colombo">
            <option value="Gampaha">
            <option value="Kalutara">
            <option value="Galle">
            <option value="Matara">
            <option value="Hambantota">
            <option value="Kandy">
            <option value="Nuwara Eliya">
            <option value="Badulla">
            <option value="Monaragala">
            <option value="Ratnapura">
            <option value="Kegalle">
            <option value="Matale">
            <option value="Anuradhapura">
            <option value="Polonnaruwa">
            <option value="Kurunegala">
            <option value="Puttalam">
            <option value="Trincomalee">
            <option value="Batticaloa">
            <option value="Ampara">
            <option value="Jaffna">
            <option value="Mannar">
            <option value="Kilinochchi">
            <option value="Vavuniya">
            <option value="Mullaitivu">
          </datalist>
        </div>
        <div class="search-field">
          <label for="check-in">Check-in</label>
          <input type="date" id="check-in">
        </div>
        <div class="search-field">
          <label for="check-out">Check-out</label>
          <input type="date" id="check-out">
        </div>
        <div class="search-field">
          <label for="guests">Guests</label>
          <input type="number" id="guests" min="1" placeholder="1">
        </div>
        <button type="submit" class="btn-search">Search</button>
      </form>
    </section>

    <section>
      <h2 class="section-title">Popular stays</h2>
      <div class="grid-3">

        <a href="samplestay.php" class="stay-card">
          <img src="beach villa.jpg" alt="Beach Villa" class="stay-image">
          <h3>Bentota Luxury Escape</h3>
          <div class="stay-meta">
            <span class="stay-details"><span class="rating">★★★★★ 4.8</span> • Bentota</span>
            <span class="price">From LKR 12,500</span>
          </div>
        </a>

        <a href="#" class="stay-card">
          <img src="city hotel.jpg" alt="City Hotel" class="stay-image">
          <h3>City Hotel</h3>
          <div class="stay-meta">
            <span class="stay-details"><span class="rating">★★★★★ 4.8</span> • Colombo</span>
            <span class="price">From LKR 12,500</span>
          </div>
        </a>

        <a href="#" class="stay-card">
          <img src="mountain villa.jpg" alt="Mountain Villa" class="stay-image">
          <h3>Mountain Villa</h3>
          <div class="stay-meta">
            <span class="stay-details"><span class="rating">★★★★★ 4.8</span> • Colombo</span>
            <span class="price">From LKR 12,500</span>
          </div>
        </a>
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
  fetch('header.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('header-placeholder').innerHTML = data;
    });

  fetch('footer.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('footer-placeholder').innerHTML = data;
    });
</script>

</body>
</html>
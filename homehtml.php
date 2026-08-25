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
          <input type="text" id="destination" placeholder="Where are you going?">
        </div>
        <div class="search-field">
          <label for="check-in">Check-in</label>
          <input type="text" id="check-in" placeholder="Add date">
        </div>
        <div class="search-field">
          <label for="check-out">Check-out</label>
          <input type="text" id="check-out" placeholder="Add date">
        </div>
        <div class="search-field">
          <label for="guests">Guests</label>
          <input type="text" id="guests" placeholder="Add guests">
        </div>
        <button type="submit" class="btn-search">Search</button>
      </form>
    </section>

    <section>
      <h2 class="section-title">Popular stays</h2>
      <div class="grid-3">

        <a href="#" class="stay-card">
          <img src="beach villa.jpg" alt="Beach Villa" class="stay-image">
          <h3>Beach Villa</h3>
          <div class="stay-meta">
            <span class="stay-details"><span class="rating">★★★★★ 4.8</span> • Colombo</span>
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

<script>
  fetch('header.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('header-placeholder').innerHTML = data;
    });
</script>

</body>
</html>
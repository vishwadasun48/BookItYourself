<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bookityourself_db");

$location  = isset($_GET['location']) ? trim($_GET['location']) : '';
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 0;

$sql = "SELECT * FROM stays WHERE 1=1";
$params = [];
$types = "";

if (!empty($location)) {
    $sql .= " AND location LIKE ?";
    $params[] = "%" . $location . "%";
    $types .= "s";
}

if ($max_price > 0) {
    $sql .= " AND price_per_night <= ?";
    $params[] = $max_price;
    $types .= "d";
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Stays - BookItYourself</title>
  <link rel="stylesheet" href="homecss.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background: #0f172a; color: #fff; font-family: 'Inter', sans-serif; }
    .search-box { max-width: 1000px; margin: 30px auto; background: #1e293b; padding: 20px; border-radius: 12px; display: flex; gap: 15px; }
    .search-box input { padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; flex: 1; }
    .search-box button { background: #007a78; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
    .stays-grid { max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
    .stay-card { background: #1e293b; border-radius: 10px; overflow: hidden; border: 1px solid #334155; }
    .stay-card img { width: 100%; height: 180px; object-fit: cover; }
    .stay-details { padding: 15px; }
  </style>
</head>
<body>

  <?php include 'header.php'; ?>

  <form class="search-box" action="search.php" method="GET">
    <input type="text" name="location" placeholder="Search location (e.g. Galle)" value="<?php echo htmlspecialchars($location); ?>">
    <input type="number" name="max_price" placeholder="Max Price per night" value="<?php echo $max_price > 0 ? $max_price : ''; ?>">
    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
  </form>

  <div class="stays-grid">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($stay = $result->fetch_assoc()): ?>
        <div class="stay-card">
          <img src="Images/Stays/<?php echo htmlspecialchars($stay['hero_image']); ?>" alt="Stay">
          <div class="stay-details">
            <h3><?php echo htmlspecialchars($stay['title']); ?></h3>
            <p><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($stay['location']); ?></p>
            <p><strong>LKR <?php echo number_format($stay['price_per_night']); ?></strong> / night</p>
            <a href="samplestay.php?id=<?php echo $stay['id']; ?>" style="color: #38bdf8; text-decoration: none;">View Details →</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="grid-column: 1/-1; text-align: center; color: #94a3b8;">සෙවුමට අදාළ Stays කිසිවක් හමු නොවීය.</p>
    <?php endif; ?>
  </div>

  <?php include 'footer.php'; ?>

</body>
</html>
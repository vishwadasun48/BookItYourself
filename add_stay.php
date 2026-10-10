<?php
session_start();

// Admin Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "bookityourself_db");
$message = "";
$error = "";

// Fetch all available facilities for checkboxes
$facilities_result = $conn->query("SELECT * FROM facilities");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $location    = trim($_POST['location']);
    $description = trim($_POST['description']);
    $price       = (float)$_POST['price_per_night'];
    $room_type   = trim($_POST['room_type']);
    $map_url     = trim($_POST['map_url']);
    $rating      = 5.0; 
    $reviews     = 0;
    
    $selected_facilities = isset($_POST['facilities']) ? $_POST['facilities'] : [];

    $upload_dir = "Images/Stays/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Hero Image Upload Handling
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
        $hero_tmp  = $_FILES['hero_image']['tmp_name'];
        $hero_name = time() . '_hero_' . basename($_FILES['hero_image']['name']);
        $target_hero = $upload_dir . $hero_name;

        if (move_uploaded_file($hero_tmp, $target_hero)) {
            // Insert main stay details
            $stmt = $conn->prepare("INSERT INTO stays (title, location, description, price_per_night, room_type, hero_image, map_url, rating, total_reviews) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdsssdi", $title, $location, $description, $price, $room_type, $hero_name, $map_url, $rating, $reviews);

            if ($stmt->execute()) {
                $stay_id = $stmt->insert_id; // newly created stay id
                $stmt->close();

                // Insert selected facilities into stay_facilities table
                if (!empty($selected_facilities)) {
                    foreach ($selected_facilities as $facility_id) {
                        $f_stmt = $conn->prepare("INSERT INTO stay_facilities (stay_id, facility_id) VALUES (?, ?)");
                        $f_stmt->bind_param("ii", $stay_id, $facility_id);
                        $f_stmt->execute();
                        $f_stmt->close();
                    }
                }

                // Handle Sample Images (Up to 4 images)
                if (isset($_FILES['sample_images'])) {
                    $file_count = count($_FILES['sample_images']['name']);
                    
                    for ($i = 0; $i < $file_count; $i++) {
                        if ($_FILES['sample_images']['error'][$i] === UPLOAD_ERR_OK) {
                            $sample_tmp  = $_FILES['sample_images']['tmp_name'][$i];
                            $sample_name = time() . '_sample_' . $i . '_' . basename($_FILES['sample_images']['name'][$i]);
                            $target_sample = $upload_dir . $sample_name;

                            if (move_uploaded_file($sample_tmp, $target_sample)) {
                                $img_stmt = $conn->prepare("INSERT INTO stay_images (stay_id, image_path) VALUES (?, ?)");
                                $img_stmt->bind_param("is", $stay_id, $sample_name);
                                $img_stmt->execute();
                                $img_stmt->close();
                            }
                        }
                    }
                }

                $message = "Stay එක, Facilities සහ පින්තූර සාර්ථකව ඇතුළත් කරන ලදී!";
            } else {
                $error = "Database Error: " . $conn->error;
            }
        } else {
            $error = "ප්‍රධාන පින්තූරය Upload කිරීමේදී දෝෂයක් මතු විය.";
        }
    } else {
        $error = "කරුණාකර ප්‍රධාන පින්තූරයක් (Hero Image) තෝරන්න.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Stay - Admin Dashboard</title>
  <link rel="stylesheet" href="homecss.css">
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background-color: #0f172a; color: #fff; font-family: 'Inter', sans-serif; }
    .form-container { max-width: 700px; margin: 40px auto; background: #1e293b; padding: 30px; border-radius: 12px; border: 1px solid #334155; }
    .form-container h2 { margin-bottom: 20px; font-size: 24px; color: #fff; }
    .input-group { margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
    .input-group label { font-size: 14px; color: #94a3b8; font-weight: 500; }
    .input-group input, .input-group textarea { width: 100%; padding: 12px; background: #0f172a; border: 1px solid #334155; border-radius: 8px; color: #fff; font-size: 15px; }
    .input-group textarea { resize: vertical; height: 120px; }
    .input-group input:focus, .input-group textarea:focus { border-color: #007a78; outline: none; }
    .facilities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; background: #0f172a; padding: 15px; border-radius: 8px; border: 1px solid #334155; }
    .facility-item { display: flex; align-items: center; gap: 8px; color: #f8fafc; font-size: 14px; cursor: pointer; }
    .btn-submit { background: #007a78; color: #fff; border: none; padding: 12px 20px; font-size: 16px; font-weight: 600; border-radius: 8px; cursor: pointer; width: 100%; transition: background 0.2s; }
    .btn-submit:hover { background: #005f5d; }
    .alert-success { background: #10b981; color: #fff; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
    .alert-error { background: #ef4444; color: #fff; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
    .back-link { display: inline-block; margin-bottom: 20px; color: #38bdf8; text-decoration: none; font-weight: 500; }
    .back-link:hover { text-decoration: underline; }
  </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

  <div class="form-container">
    <a href="admin_dashboard.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    <h2><i class="fa-solid fa-hotel"></i> Add New Stay / Villa</h2>

    <?php if (!empty($message)): ?>
      <div class="alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="add_stay.php" method="POST" enctype="multipart/form-data">
      <div class="input-group">
        <label>Property Title</label>
        <input type="text" name="title" placeholder="උදා: Luxury Beach Villa - Galle" required>
      </div>

      <div class="input-group">
        <label>Location</label>
        <input type="text" name="location" placeholder="උදා: Galle, Sri Lanka" required>
      </div>

      <div class="input-group">
        <label>Price Per Night (LKR)</label>
        <input type="number" step="0.01" name="price_per_night" placeholder="උදා: 25000" required>
      </div>

      <div class="input-group">
        <label>Room Type</label>
        <input type="text" name="room_type" placeholder="උදා: Deluxe Double Room / Private Villa" required>
      </div>

      <!-- Facilities Checkboxes -->
      <div class="input-group">
        <label>Facilities Available</label>
        <div class="facilities-grid">
          <?php if ($facilities_result && $facilities_result->num_rows > 0): ?>
            <?php while($fac = $facilities_result->fetch_assoc()): ?>
              <label class="facility-item">
                <input type="checkbox" name="facilities[]" value="<?php echo $fac['id']; ?>">
                <i class="<?php echo htmlspecialchars($fac['icon_class']); ?>"></i> <?php echo htmlspecialchars($fac['name']); ?>
              </label>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="input-group">
        <label>Hero Image (Main Photo)</label>
        <input type="file" name="hero_image" accept="Image/*" required>
      </div>

      <div class="input-group">
        <label>Sample Images (Select up to 4 photos)</label>
        <input type="file" name="sample_images[]" accept="image/*" multiple required>
        <small style="color: #94a3b8; font-size: 12px;">Ctrl කී එක ඔබාගෙන පින්තූර 4ක් එකවර තෝරාගත හැක.</small>
      </div>

      <div class="input-group">
        <label>Google Map Embed URL</label>
        <input type="text" name="map_url" placeholder="https://www.google.com/maps/embed?..." required>
      </div>

      <div class="input-group">
        <label>Description</label>
        <textarea name="description" placeholder="Property එක பற்றிய විස්තරය මෙහි ලියන්න..." required></textarea>
      </div>

      <button type="submit" class="btn-submit">Add Stay</button>
    </form>
  </div>

</body>
</html>
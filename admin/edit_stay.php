<?php
session_start();

// Admin Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "bookityourself_db");
$message = "";
$error = "";

// Get Stay ID from URL
if (!isset($_GET['id'])) {
    header("Location: manage_stays.php");
    exit();
}

$stay_id = (int)$_GET['id'];

// Fetch all available facilities
$facilities_result = $conn->query("SELECT * FROM facilities");

// Handle Update Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $location    = trim($_POST['location']);
    $description = trim($_POST['description']);
    $price       = (float)$_POST['price_per_night'];
    $room_type   = trim($_POST['room_type']);
    $map_url     = trim($_POST['map_url']);
    $selected_facilities = isset($_POST['facilities']) ? $_POST['facilities'] : [];
    $delete_images       = isset($_POST['delete_images']) ? $_POST['delete_images'] : [];

    $upload_dir = __DIR__ . "/../Images/Stays/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Check if a new hero image is uploaded
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['hero_image']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['hero_image']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            $stmt = $conn->prepare("UPDATE stays SET title=?, location=?, description=?, price_per_night=?, room_type=?, hero_image=?, map_url=? WHERE id=?");
            $stmt->bind_param("sssdsssi", $title, $location, $description, $price, $room_type, $file_name, $map_url, $stay_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $error = "ප්‍රධාන පින්තූරය Upload කිරීමේදී දෝෂයක් මතු විය.";
        }
    } else {
        $stmt = $conn->prepare("UPDATE stays SET title=?, location=?, description=?, price_per_night=?, room_type=?, map_url=? WHERE id=?");
        $stmt->bind_param("sssdssi", $title, $location, $description, $price, $room_type, $map_url, $stay_id);
        $stmt->execute();
        $stmt->close();
    }

    // Delete selected sample images
    if (!empty($delete_images)) {
        foreach ($delete_images as $img_id) {
            $img_id = (int)$img_id;
            // Fetch image path to delete file from folder
            $q = $conn->prepare("SELECT image_path FROM stay_images WHERE id = ? AND stay_id = ?");
            $q->bind_param("ii", $img_id, $stay_id);
            $q->execute();
            $res = $q->get_result();
            if ($img_row = $res->fetch_assoc()) {
                $file_path = $upload_dir . $img_row['image_path'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            $q->close();

            // Delete from database
            $del_stmt = $conn->prepare("DELETE FROM stay_images WHERE id = ? AND stay_id = ?");
            $del_stmt->bind_param("ii", $img_id, $stay_id);
            $del_stmt->execute();
            $del_stmt->close();
        }
    }

    // Update Facilities: Delete old associations and insert newly checked ones
    $conn->query("DELETE FROM stay_facilities WHERE stay_id = $stay_id");
    if (!empty($selected_facilities)) {
        foreach ($selected_facilities as $facility_id) {
            $f_stmt = $conn->prepare("INSERT INTO stay_facilities (stay_id, facility_id) VALUES (?, ?)");
            $f_stmt->bind_param("ii", $stay_id, $facility_id);
            $f_stmt->execute();
            $f_stmt->close();
        }
    }

    // Handle New Sample Images Upload (if selected)
    if (isset($_FILES['sample_images']) && !empty($_FILES['sample_images']['name'][0])) {
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

    if (empty($error)) {
        $message = "Stay එක, Facilities සහ පින්තූර සාර්ථකව Update කරන ලදී!";
    }
}

// Fetch existing stay details
$stmt = $conn->prepare("SELECT * FROM stays WHERE id = ?");
$stmt->bind_param("i", $stay_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_stays.php");
    exit();
}

$stay = $result->fetch_assoc();
$stmt->close();

// Fetch existing sample images
$sample_images_result = $conn->query("SELECT * FROM stay_images WHERE stay_id = $stay_id");

// Fetch already selected facilities for this stay
$current_facilities = [];
$cf_result = $conn->query("SELECT facility_id FROM stay_facilities WHERE stay_id = $stay_id");
while($row = $cf_result->fetch_assoc()) {
    $current_facilities[] = $row['facility_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Stay - Admin Dashboard</title>
  <link rel="stylesheet" href="/bookityourself/css/homecss.css">
  <link rel="stylesheet" href="/bookityourself/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .sample-img-preview { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 10px; }
    .img-card { background: #0f172a; padding: 8px; border-radius: 8px; border: 1px solid #334155; text-align: center; }
    .img-card img { width: 80px; height: 60px; object-fit: cover; border-radius: 6px; display: block; margin-bottom: 6px; }
    .img-card label { font-size: 12px; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; }
    .facilities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; background: #0f172a; padding: 15px; border-radius: 8px; border: 1px solid #334155; }
    .facility-item { display: flex; align-items: center; gap: 8px; color: #f8fafc; font-size: 14px; cursor: pointer; }
  </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

  <div class="form-container">
    <a href="manage_stays.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Manage Stays</a>
    <h2><i class="fa-solid fa-pen-to-square"></i> Edit Stay / Villa</h2>

    <?php if (!empty($message)): ?>
      <div class="alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="edit_stay.php?id=<?php echo $stay_id; ?>" method="POST" enctype="multipart/form-data">
      <div class="input-group">
        <label>Property Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($stay['title']); ?>" required>
      </div>

      <div class="input-group">
        <label>Location</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($stay['location']); ?>" required>
      </div>

      <div class="input-group">
        <label>Price Per Night (LKR)</label>
        <input type="number" step="0.01" name="price_per_night" value="<?php echo $stay['price_per_night']; ?>" required>
      </div>

      <div class="input-group">
        <label>Room Type</label>
        <input type="text" name="room_type" value="<?php echo htmlspecialchars($stay['room_type']); ?>" required>
      </div>

      <!-- Facilities Checkboxes -->
      <div class="input-group">
        <label>Facilities Available</label>
        <div class="facilities-grid">
          <?php if ($facilities_result && $facilities_result->num_rows > 0): ?>
            <?php while($fac = $facilities_result->fetch_assoc()): ?>
              <label class="facility-item">
                <input type="checkbox" name="facilities[]" value="<?php echo $fac['id']; ?>" <?php echo in_array($fac['id'], $current_facilities) ? 'checked' : ''; ?>>
                <i class="<?php echo htmlspecialchars($fac['icon_class']); ?>"></i> <?php echo htmlspecialchars($fac['name']); ?>
              </label>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="input-group">
        <label>Current Hero Image</label>
        <div style="margin-bottom: 10px;">
          <img src="../Images/Stays/<?php echo htmlspecialchars($stay['hero_image']); ?>" alt="Current Hero Image" style="width: 100px; height: 70px; object-fit: cover; border-radius: 6px;">
        </div>
        <label>Change Hero Image (Optional)</label>
        <input type="file" name="hero_image" accept="image/*">
      </div>

      <!-- Sample Images with Delete Option -->
      <div class="input-group">
        <label>Current Sample Images (Check to Delete)</label>
        <div class="sample-img-preview">
          <?php if ($sample_images_result && $sample_images_result->num_rows > 0): ?>
            <?php while($s_img = $sample_images_result->fetch_assoc()): ?>
              <div class="img-card">
                <img src="../Images/Stays/<?php echo htmlspecialchars($s_img['image_path']); ?>" alt="Sample Image">
                <label>
                  <input type="checkbox" name="delete_images[]" value="<?php echo $s_img['id']; ?>"> Delete
                </label>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p style="color: #94a3b8; font-size: 13px;">Sample images කිසිවක් ඇතුළත් කර නැත.</p>
          <?php endif; ?>
        </div>
        <label style="margin-top: 10px;">Add More Sample Images (Optional)</label>
        <input type="file" name="sample_images[]" accept="image/*" multiple>
      </div>

      <div class="input-group">
        <label>Google Map Embed URL</label>
        <input type="text" name="map_url" value="<?php echo htmlspecialchars($stay['map_url']); ?>" required>
      </div>

      <div class="input-group">
        <label>Description</label>
        <textarea name="description" required><?php echo htmlspecialchars($stay['description']); ?></textarea>
      </div>

      <button type="submit" class="btn-submit">Update Stay</button>
    </form>
  </div>

</body>
</html>
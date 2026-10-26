<?php
session_start();

// Admin Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "bookityourself_db");

// Delete Stay Action
if (isset($_GET['delete'])) {
    $stay_id = (int)$_GET['delete'];
    
    // Delete query
    $stmt = $conn->prepare("DELETE FROM stays WHERE id = ?");
    $stmt->bind_param("i", $stay_id);
    if ($stmt->execute()) {
        header("Location: manage_stays.php?msg=deleted");
        exit();
    }
    $stmt->close();
}

// Fetch all stays
$result = $conn->query("SELECT * FROM stays ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Stays - Admin Dashboard</title>
  <link rel="stylesheet" href="/bookityourself/css/homecss.css">
  <link rel="stylesheet" href="/bookityourself/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .manage-container { max-width: 1100px; margin: 0 auto; }
    .table-responsive { width: 100%; overflow-x: auto; margin-top: 20px; }
    .admin-table { width: 100%; border-collapse: collapse; background: #1e293b; border-radius: 12px; overflow: hidden; border: 1px solid #334155; }
    .admin-table th, .admin-table td { padding: 15px; text-align: left; border-bottom: 1px solid #334155; font-size: 14px; }
    .admin-table th { background-color: #0f172a; color: #94a3b8; font-weight: 600; }
    .admin-table tr:hover { background-color: rgba(51, 65, 85, 0.4); }
    .stay-thumb { width: 50px; height: 50px; border-radius: 6px; object-fit: cover; }
    .action-btns { display: flex; gap: 10px; }
    .btn-edit { background: #2563eb; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500; }
    .btn-delete { background: #ef4444; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500; }
    .btn-edit:hover { background: #1d4ed8; }
    .btn-delete:hover { background: #dc2626; }
    .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .btn-add { background: #007a78; color: #fff; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
    .btn-add:hover { background: #005f5d; }
  </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

  <div class="manage-container">
    <div class="top-bar">
      <a href="admin_dashboard.php" class="back-link" style="margin-bottom:0;"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
      <a href="add_stay.php" class="btn-add"><i class="fa-solid fa-plus"></i> Add New Stay</a>
    </div>

    <h2><i class="fa-solid fa-hotel"></i> Manage Hotels & Villas</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
      <div class="alert-success" style="margin-top: 15px;">Stay එක සාර්ථකව මකා දමන ලදී!</div>
    <?php endif; ?>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Location</th>
            <th>Price / Night</th>
            <th>Room Type</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td>
                  <img src="../Images/Stays/<?php echo htmlspecialchars($row['hero_image']); ?>" alt="Stay Image" class="stay-thumb">
                </td>
                <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['location']); ?></td>
                <td>LKR <?php echo number_format($row['price_per_night']); ?></td>
                <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                <td>
                  <div class="action-btns">
                    <!-- අනාගතයේදී Edit කිරීමට edit_stay.php ලින්ක් කළ හැක -->
                    <a href="edit_stay.php?id=<?php echo $row['id']; ?>" class="btn-edit"><i class="fa-solid fa-pen"></i> Edit</a>
                    <a href="manage_stays.php?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('මෙම Stay එක මකා දැමීමට ඔබට අවශ්‍ය බව විශ්වාසද?');"><i class="fa-solid fa-trash"></i> Delete</a>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align: center; color: #94a3b8; padding: 30px;">තවදුරටත් Stays ඇතුළත් කර නැත.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
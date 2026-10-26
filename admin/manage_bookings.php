<?php
session_start();

// Admin Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "bookityourself_db");

// Handle Delete Booking/Cart Item
if (isset($_GET['delete'])) {
    $cart_id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_bookings.php?msg=deleted");
    exit();
}

// Fetch Bookings joining users and stays tables
$query = "
    SELECT c.id as cart_id, c.check_in, c.check_out, c.guests, c.created_at, 
           u.username, u.email, u.phone, 
           s.title as stay_title, s.price_per_night 
    FROM cart c
    JOIN users u ON c.user_id = u.id
    JOIN stays s ON c.stay_id = s.id
    ORDER BY c.id DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Bookings - Admin Dashboard</title>
  <link rel="stylesheet" href="/bookityourself/css/homecss.css">
  <link rel="stylesheet" href="/bookityourself/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .manage-container { max-width: 1200px; margin: 0 auto; }
    .table-responsive { width: 100%; overflow-x: auto; margin-top: 20px; }
    .admin-table { width: 100%; border-collapse: collapse; background: #1e293b; border-radius: 12px; overflow: hidden; border: 1px solid #334155; }
    .admin-table th, .admin-table td { padding: 15px; text-align: left; border-bottom: 1px solid #334155; font-size: 14px; }
    .admin-table th { background-color: #0f172a; color: #94a3b8; font-weight: 600; }
    .admin-table tr:hover { background-color: rgba(51, 65, 85, 0.4); }
    .btn-delete { background: #ef4444; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500; display: inline-block; }
    .btn-delete:hover { background: #dc2626; }
    .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
  </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

  <div class="manage-container">
    <div class="top-bar">
      <a href="admin_dashboard.php" class="back-link" style="margin-bottom:0;"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <h2><i class="fa-solid fa-calendar-check"></i> Manage Customer Bookings</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
      <div class="alert-success" style="margin-top: 15px;">Booking විස්තරය සාර්ථකව මකා දමන ලදී!</div>
    <?php endif; ?>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Contact</th>
            <th>Property</th>
            <th>Check-In / Out</th>
            <th>Guests</th>
            <th>Total Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): 
              // දින ගණන ගණනය කර මුළු මිල සෙවීම
              $check_in_date = new DateTime($row['check_in']);
              $check_out_date = new DateTime($row['check_out']);
              $night_count = $check_in_date->diff($check_out_date)->days;
              if ($night_count <= 0) $night_count = 1;
              $total_price = $row['price_per_night'] * $night_count;
            ?>
              <tr>
                <td>#<?php echo $row['cart_id']; ?></td>
                <td>
                  <strong><?php echo htmlspecialchars($row['username']); ?></strong><br>
                  <small style="color: #94a3b8;"><?php echo htmlspecialchars($row['email']); ?></small>
                </td>
                <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['stay_title']); ?></td>
                <td>
                  <small>In: <?php echo $row['check_in']; ?></small><br>
                  <small>Out: <?php echo $row['check_out']; ?></small>
                </td>
                <td><?php echo $row['guests']; ?> Guests</td>
                <td>LKR <?php echo number_format($total_price); ?></td>
                <td>
                  <a href="manage_bookings.php?delete=<?php echo $row['cart_id']; ?>" class="btn-delete" onclick="return confirm('මෙම Booking එක මකා දැමීමට අවශ්‍ය බව විශ්වාසද?');">
                    <i class="fa-solid fa-trash"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" style="text-align: center; color: #94a3b8; padding: 30px;">තවදුරටත් Booking වාර්තා ඇතුළත් කර නැත.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
<?php
session_start();

// Admin Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "bookityourself_db");

// Fetching Dashboard Statistics
$total_users    = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='user'")->fetch_assoc()['count'];
$total_stays    = $conn->query("SELECT COUNT(*) as count FROM stays")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM cart")->fetch_assoc()['count'] ?? 0;

// Total Revenue Calculation based on cart/bookings and stays price
$revenue_query = "
    SELECT SUM(s.price_per_night * c.guests) as total_revenue 
    FROM cart c 
    JOIN stays s ON c.stay_id = s.id
";
$revenue_result = $conn->query($revenue_query)->fetch_assoc();
$total_revenue = $revenue_result['total_revenue'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - BookItYourself</title>
  <link rel="stylesheet" href="/bookityourself/css/homecss.css">
  <link rel="stylesheet" href="/bookityourself/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    .welcome-banner {
      background: #1e293b;
      padding: 30px;
      border-radius: 16px;
      border: 1px solid #334155;
      margin-bottom: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .welcome-banner h1 {
      font-size: 24px;
      color: #fff;
      margin-bottom: 8px;
    }
    .welcome-banner p {
      color: #94a3b8;
      font-size: 14px;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }
    .stat-card {
      background: #1e293b;
      padding: 25px;
      border-radius: 16px;
      border: 1px solid #334155;
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .stat-icon {
      width: 60px;
      height: 60px;
      background: rgba(0, 122, 120, 0.15);
      color: #007a78;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }
    .stat-info h3 {
      font-size: 24px;
      color: #fff;
      margin-bottom: 5px;
    }
    .stat-info p {
      color: #94a3b8;
      font-size: 14px;
      font-weight: 500;
    }
    .quick-actions {
      background: #1e293b;
      padding: 30px;
      border-radius: 16px;
      border: 1px solid #334155;
    }
    .quick-actions h2 {
      font-size: 20px;
      color: #fff;
      margin-bottom: 20px;
    }
    .action-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
    }
    .action-btn {
      background: #0f172a;
      border: 1px solid #334155;
      padding: 15px 20px;
      border-radius: 10px;
      color: #fff;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 500;
      transition: all 0.2s;
    }
    .action-btn:hover {
      border-color: #007a78;
      background: rgba(0, 122, 120, 0.1);
    }
    .action-btn i {
      color: #007a78;
      font-size: 18px;
    }
  </style>
</head>
<body>

  <!-- Admin Navigation Header -->
  <?php include 'admin_header.php'; ?>

  <div class="dashboard-container">
    
    <!-- Welcome Banner -->
    <div class="welcome-banner">
      <div>
        <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋</h1>
        <p>Here is what is happening with BookItYourself today.</p>
      </div>
      <div>
        <a href="add_stay.php" class="btn-add"><i class="fa-solid fa-plus"></i> Add New Stay</a>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon"><i class="fa-solid fa-hotel"></i></div>
        <div class="stat-info">
          <h3><?php echo $total_stays; ?></h3>
          <p>Total Stays</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
        <div class="stat-info">
          <h3><?php echo $total_bookings; ?></h3>
          <p>Total Bookings</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
          <h3><?php echo $total_users; ?></h3>
          <p>Registered Users</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-info">
          <h3>LKR <?php echo number_format($total_revenue); ?></h3>
          <p>Total Revenue</p>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <h2>Quick Management</h2>
      <div class="action-grid">
        <a href="manage_stays.php" class="action-btn">
          <i class="fa-solid fa-pen-to-square"></i> Manage Stays
        </a>
        <a href="manage_bookings.php" class="action-btn">
          <i class="fa-solid fa-list-check"></i> View Bookings
        </a>
        <a href="add_stay.php" class="action-btn">
          <i class="fa-solid fa-circle-plus"></i> Add New Property
        </a>
        <a href="/bookityourself/homehtml.php" target="_blank" class="action-btn">
          <i class="fa-solid fa-globe"></i> Browse Live Site
        </a>
      </div>
    </div>

  </div>

</body>
</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="admin-navbar">
  <div class="admin-nav-container">
    <a href="admin_dashboard.php" class="admin-logo">
      <i class="fa-solid fa-user-shield"></i> Admin Panel
    </a>
    <ul class="admin-nav-links">
      <li><a href="admin_dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
      <li><a href="manage_stays.php"><i class="fa-solid fa-hotel"></i> Manage Stays</a></li>
      <li><a href="add_stay.php"><i class="fa-solid fa-plus"></i> Add Stay</a></li>
      <li><a href="manage_bookings.php"><i class="fa-solid fa-calendar-check"></i> Bookings</a></li>
      <li><a href="/bookityourself/homehtml.php" target="_blank"><i class="fa-solid fa-globe"></i> View Site</a></li>
      <li><a href="admin_logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
    </ul>
  </div>
</nav>
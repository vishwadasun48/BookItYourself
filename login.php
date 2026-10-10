<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "bookityourself_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Register
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        $message = "මෙම Email එක දැනටමත් ලියාපදිංචි කර ඇත!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'user';
            header("Location: account.php");
            exit();
        } else {
            $message = "ලියාපදිංචිය අසාර්ථකයි: " . $conn->error;
        }
        $stmt->close();
    }
    $check_email->close();
}

// Login
if (isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Admin කෙනෙක් නම් admin_dashboard.php වෙතත්, User කෙනෙක් නම් account.php වෙතත් යැවීම
            if ($row['role'] === 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                header("Location: account.php");
                exit();
            }
        } else {
            $message = "මුරපදය (Password) වැරදිය!";
        }
    } else {
        $message = "මෙම Email එකෙන් ගිණුමක් හමු නොවීය!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In / Register - BookItYourself</title>
  <link rel="stylesheet" href="homecss.css">
  <link rel="stylesheet" href="auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    body {
      display: block !important;
      margin: 0;
      padding: 0;
    }
    .auth-page-wrapper {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
      box-sizing: border-box;
    }
  </style>
</head>
<body>

  <!-- Header Include -->
  <?php include 'header.php'; ?>

  <div class="auth-page-wrapper">
    <div class="auth-container">
      <?php if (!empty($message)): ?>
        <div class="alert"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <div class="forms-wrapper">
        <!-- Login Form -->
        <div class="form-box">
          <h2>Sign In</h2>
          <form action="login.php" method="POST">
            <div class="input-group">
              <label>Email</label>
              <input type="email" name="email" required>
            </div>
            <div class="input-group">
              <label>Password</label>
              <input type="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn">Sign In</button>
          </form>
        </div>

        <div class="divider"></div>

        <!-- Register Form -->
        <div class="form-box">
          <h2>Register</h2>
          <form action="login.php" method="POST">
            <div class="input-group">
              <label>Username</label>
              <input type="text" name="username" required>
            </div>
            <div class="input-group">
              <label>Email</label>
              <input type="email" name="email" required>
            </div>
            <div class="input-group">
              <label>Password</label>
              <input type="password" name="password" required>
            </div>
            <button type="submit" name="register" class="btn btn-secondary">Register</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
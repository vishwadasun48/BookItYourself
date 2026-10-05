<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database සම්බන්ධතාවය
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bookityourself_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Registration ක්‍රියාවලිය
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Password Encryption

    // Email එක කලින් භාවිත කර ඇත්දැයි පරීක්ෂා කිරීම
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_num_rows > 0) {
        $message = "මෙම Email එක දැනටමත් ලියාපදිංචි කර ඇත!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $message = "ලියාපදිංචිය සාර්ථකයි! දැන් Login වන්න.";
        } else {
            $message = "ලියාපදිංචිය අසාර්ථකයි: " . $conn->error;
        }
        $stmt->close();
    }
    $check_email->close();
}

// Login ක්‍රියාවලිය
if (isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: homehtml.php"); // Login වූ පසු Home Page එකට Redirect වීම
            exit();
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
  <link rel="stylesheet" href="auth.css">
</head>
<body>

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

</body>
</html>
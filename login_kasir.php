<?php
session_start();
require 'koneksi/kon.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $q = mysqli_prepare($conn, "SELECT * FROM kasir WHERE username = ?");
    mysqli_stmt_bind_param($q, "s", $username);
    mysqli_stmt_execute($q);
    $result = mysqli_stmt_get_result($q);

    if ($result && mysqli_num_rows($result) === 1) {
        $kasir = mysqli_fetch_assoc($result);
        if (password_verify($password, $kasir['password'])) {
            $_SESSION['is_kasir'] = true;
            $_SESSION['kasir_username'] = $kasir['username'];
            header("Location: kasir.php");
            exit;
        }
    }

    $error = "Username atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Kasir</title>
  <link rel="stylesheet" href="css/admin.css"> <!-- Bisa pake CSS yang sama -->
  <link href="icon/seleraku.ico" rel="shortcut icon"></link>
</head>
<body>
  <div class="login-container">
    <h2>Login Kasir</h2>
    <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
    </form>
  </div>
</body>
</html>

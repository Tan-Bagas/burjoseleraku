<?php
session_start();
require 'koneksi/kon.php';

session_start();
if (!isset($_SESSION['is_admin'])) {
    header('Location: login_admin.php');
    exit;
}

$menu = mysqli_query($conn, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Menu - Admin</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="icon/seleraku.ico" rel="shortcut icon"></link>
</head>
<body>

<div class="navbar">ADMIN - KELOLA MENU </div>

<div class="atasanmenu">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
  <a href="form_menu.php" class="btn-green">+ Tambah Menu</a>
  <a href="logout.php" class="btn-red">Logout</a>
  </div>


  <div class="menu-container">
    <?php while($m = mysqli_fetch_assoc($menu)): ?>
      <div class="menu-card">
        <img src="img/<?= htmlspecialchars($m['gambar']) ?>" alt="<?= htmlspecialchars($m['nama_menu']) ?>">
        <div class="menu-info">
          <h3><?= htmlspecialchars($m['nama_menu']) ?></h3>
          <p><?= htmlspecialchars($m['deskripsi']) ?></p>
          <p class="harga">Rp<?= number_format($m['harga']) ?></p>
          <div style="margin-top:10px;">
            <a href="form_menu.php?id=<?= $m['id'] ?>" class="btn-edit">edit</a>
            <a href="proses_menu.php?hapus=<?= $m['id'] ?>" onclick="return confirm('Hapus menu ini?')" class="btn-delete">🗑</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</body>
</html>

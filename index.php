<?php
session_start();
require 'koneksi/kon.php';

$menu = mysqli_query($conn, "SELECT * FROM menu");
if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pemesanan - Seleraku</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="icon/seleraku.ico" rel="shortcut icon"></link>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
  MENU SELERAKU
</div>

<div class="atasanmenu">
  <!-- MENU CONTAINER -->
<p class="tulisanjir">Menu Bubur</p>
  <div class="menu-container">
    <?php while($m = mysqli_fetch_assoc($menu)): ?>
      <div class="menu-card">
        <img src="img/<?= htmlspecialchars($m['gambar']) ?>" alt="<?= htmlspecialchars($m['nama_menu']) ?>">
        <div class="menu-info">
          <h3><?= htmlspecialchars($m['nama_menu']) ?></h3>
          <p><?= htmlspecialchars($m['deskripsi']) ?></p>
          <p class="harga">Rp<?= number_format($m['harga']) ?></p>
          <form method="post" action="aksi_keranjang.php">
            <input type="hidden" name="id" value="<?= $m['id'] ?>">
            <input type="number" name="qty" value="1" min="1" required>
            <button type="submit">+ Tambah</button>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- KERANJANG DI BAWAH -->
<?php if (!empty($_SESSION['keranjang'])): ?>
<div id="keranjangPopup" class="keranjang-popup">
  <form method="post" action="proses_pesan.php">
    <ul>
    <?php
    $total = 0;
    foreach ($_SESSION['keranjang'] as $id => $qty):
        $q = mysqli_query($conn, "SELECT * FROM menu WHERE id=$id");
        $m = mysqli_fetch_assoc($q);
        $subtotal = $m['harga'] * $qty;
        $total += $subtotal;
    ?>
      <li>
        <?= htmlspecialchars($m['nama_menu']) ?> (<?= $qty ?> x Rp<?= number_format($m['harga']) ?>)
        = Rp<?= number_format($subtotal) ?>
        <a href="aksi_keranjang.php?hapus=<?= $id ?>">🗑</a>
      </li>
    <?php endforeach; ?>
    </ul>
    <p><strong>Total: Rp<?= number_format($total) ?></strong></p>
    <input type="number" name="nomor_meja" placeholder="Nomor Meja" min="1" max="10" required>
    <button type="submit">Pesan Sekarang</button>
  </form>
</div>
<?php endif; ?>

</body>
</html>

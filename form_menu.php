<?php
require 'koneksi/kon.php';

session_start();
if (!isset($_SESSION['is_admin'])) {
    header('Location: login_admin.php');
    exit;
}

$id = isset($_GET['id']) ? $_GET['id'] : '';
$data = ['nama_menu' => '', 'deskripsi' => '', 'harga' => '', 'gambar' => ''];

if ($id) {
    $q = mysqli_query($conn, "SELECT * FROM menu WHERE id=$id");
    $data = mysqli_fetch_assoc($q);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $id ? 'Edit' : 'Tambah' ?> Menu</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="icon/seleraku.ico" rel="shortcut icon"></link>
</head>
<body>
<div class="navbar"><?= $id ? 'Edit' : 'Tambah' ?> Menu</div>

<div class="atasanmenu">
  <form action="proses_menu.php" method="post" enctype="multipart/form-data" style="max-width:500px; margin:auto;">
    <input type="hidden" name="id" value="<?= $id ?>">

    <label>Nama Menu</label>
    <input type="text" name="nama_menu" value="<?= htmlspecialchars($data['nama_menu']) ?>" required style="width:100%; padding:10px; margin-bottom:10px;">

    <label>Deskripsi</label>
    <textarea name="deskripsi" required style="width:100%; padding:10px; margin-bottom:10px;"><?= htmlspecialchars($data['deskripsi']) ?></textarea>

    <label>Harga</label>
    <input type="number" name="harga" value="<?= $data['harga'] ?>" required style="width:100%; padding:10px; margin-bottom:10px;">

    <label>Gambar <?= $id ? '(Kosongkan jika tidak ingin ubah)' : '' ?></label>
    <input type="file" name="gambar" <?= $id ? '' : 'required' ?> style="margin-bottom:20px;">

    <button type="submit"><?= $id ? 'Update' : 'Tambah' ?> Menu</button>
  </form>
</div>

</body>
</html>

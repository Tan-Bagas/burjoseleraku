<?php
require 'koneksi/kon.php';

session_start();
if (!isset($_SESSION['is_admin'])) {
    header('Location: login_admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga = intval($_POST['harga']);

    // Upload gambar jika ada
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '-' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'img/' . $gambar);
    }

    if ($id) {
        // Edit
        $update = "UPDATE menu SET 
          nama_menu='$nama',
          deskripsi='$deskripsi',
          harga='$harga'" .
          (!empty($gambar) ? ", gambar='$gambar'" : "") . 
          " WHERE id=$id";
        mysqli_query($conn, $update);
    } else {
        // Tambah
        mysqli_query($conn, "INSERT INTO menu (nama_menu, deskripsi, harga, gambar) VALUES ('$nama', '$deskripsi', '$harga', '$gambar')");
    }

    header('Location: admin_menu.php');
    exit;
}

// Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM menu WHERE id=$id");
    header('Location: admin_menu.php');
    exit;
}

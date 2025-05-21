<?php
session_start();
require 'koneksi/kon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['keranjang'])) {
    $meja = (int) $_POST['nomor_meja'];
    $tanggal = date('Y-m-d H:i:s');

    // Simpan ke tabel pesanan
    $sql = "INSERT INTO pesanan (nomor_meja, tanggal) VALUES ($meja, '$tanggal')";
    mysqli_query($conn, $sql);

    $id_pesanan = mysqli_insert_id($conn);

    // Simpan setiap item ke tabel pesanan_detail
    foreach ($_SESSION['keranjang'] as $id_menu => $qty) {
        $menu = mysqli_query($conn, "SELECT harga FROM menu WHERE id = $id_menu");
        $m = mysqli_fetch_assoc($menu);
        $harga = $m['harga'];

        $insert = "INSERT INTO pesanan_detail (id_pesanan, id_menu, jumlah, harga) 
                   VALUES ($id_pesanan, $id_menu, $qty, $harga)";
        mysqli_query($conn, $insert);
    }

    // Kosongkan keranjang
    unset($_SESSION['keranjang']);

    echo "<script>alert('Pesanan berhasil dikirim!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Keranjang kosong atau data tidak valid.'); window.location='index.php';</script>";
}

<?php
session_start();

// Tambah ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $qty = $_POST['qty'];

    if (!isset($_SESSION['keranjang'][$id])) {
        $_SESSION['keranjang'][$id] = $qty;
    } else {
        $_SESSION['keranjang'][$id] += $qty;
    }
    header('Location: index.php');
    exit;
}

// Hapus item dari keranjang
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    unset($_SESSION['keranjang'][$id]);
    header('Location: index.php');
    exit;
}

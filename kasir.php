<?php

date_default_timezone_set('Asia/Jakarta');

require 'koneksi/kon.php';

// Ambil semua pesanan dari database
$pesanan = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY tanggal DESC");

session_start();
if (!isset($_SESSION['is_kasir'])) {
    header("Location: login_kasir.php");
    exit;
}

// Cek jika tombol 'hapus' ditekan
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    // Hapus dulu detail pesanan, lalu pesanan utama
    mysqli_query($conn, "DELETE FROM pesanan_detail WHERE id_pesanan = $id");
    mysqli_query($conn, "DELETE FROM pesanan WHERE id = $id");
    header("Location: kasir.php");
    exit;
}

// Cek jika tombol 'batalkan' ditekan
if (isset($_GET['batal'])) {
    $id = (int) $_GET['batal'];
    mysqli_query($conn, "UPDATE pesanan SET status = 'DiBatalkan' WHERE id = $id");
    header("Location: kasir.php");
    exit;
}

// Cek jika tombol 'tandai sudah dibayar' ditekan
if (isset($_GET['bayar'])) {
    $id = (int) $_GET['bayar'];
    mysqli_query($conn, "UPDATE pesanan SET status = 'Sudah Dibayar' WHERE id = $id");
    header("Location: kasir.php");
    exit;
}

// Fungsi ambil detail item tiap pesanan
function getDetailPesanan($conn, $id_pesanan) {
    $query = "SELECT d.*, m.nama_menu 
              FROM pesanan_detail d
              JOIN menu m ON d.id_menu = m.id
              WHERE d.id_pesanan = $id_pesanan";
    return mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kasir - Seleraku</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="icon/seleraku.ico" rel="shortcut icon"></link>
    <style>
        /* Tambahan gaya cepat */
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1024px;
            margin: auto;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .btn {
            background: #28a745;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f0f0f0;
        }
        .action-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .left-buttons a,
        .right-buttons a {
            display: inline-block;
            margin-top: 10px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
            margin-right: 50px;
        }


    </style>
</head>
<body>

    <div class="container">
        <div class="header">
        <h2>📦 Pesanan Masuk</h2>
        <a href="logoutkasir.php" class="logout-btn">Logout</a>
    </div>

    <br><br>

    <?php while ($p = mysqli_fetch_assoc($pesanan)): ?>
        <div class="card">
            <h3>🪑 Meja: <?= $p['nomor_meja'] ?> | 🕒 
                <?php
                    $dt = new DateTime($p['tanggal']);
                ?>
                <?= $dt->format("d M Y") ?>
            </h3>
            <?php
                $color = 'red';
                if ($p['status'] == 'Sudah Dibayar') {
                    $color = 'green';
                } elseif ($p['status'] == 'Dibatalkan') {
                    $color = 'gray';
                }
            ?>
            <p>Status: 
                <strong style="color: <?= $color ?>;">
                    <?= $p['status'] ?>
                </strong>
            </p>
            <table>
                <tr>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
                <?php
                $detail = getDetailPesanan($conn, $p['id']);
                $total = 0;
                while ($d = mysqli_fetch_assoc($detail)):
                    $subtotal = $d['jumlah'] * $d['harga'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= $d['nama_menu'] ?></td>
                    <td><?= $d['jumlah'] ?></td>
                    <td>Rp<?= number_format($d['harga']) ?></td>
                    <td>Rp<?= number_format($subtotal) ?></td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <th colspan="3">Total</th>
                    <th>Rp<?= number_format($total) ?></th>
                </tr>
            </table>
            <div class="action-row">
                <div class="left-buttons">
                    <?php if ($p['status'] == 'Belum Dibayar'): ?>
                        <a href="kasir.php?bayar=<?= $p['id'] ?>" class="btn">Tandai Sudah Dibayar</a>
                        <a href="kasir.php?batal=<?= $p['id'] ?>" class="btn" style="background: #dc3545; margin-left: 10px;">Batalkan</a>
                    <?php endif; ?>
                </div>
                <div class="right-buttons">
                    <a href="edit_pesanan.php?id=<?= $p['id'] ?>" class="btn" style="background:rgb(112, 114, 241);">Ubah Meja</a>
                    <a href="kasir.php?hapus=<?= $p['id'] ?>" class="btn" style="background:rgb(255, 74, 74); margin-left: 10px;" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">🗑️ Hapus</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>

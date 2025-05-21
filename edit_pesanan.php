<?php
require 'koneksi/kon.php';

$id = (int) $_GET['id'];
$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pesanan WHERE id = $id"));

$error = "";

if (isset($_POST['simpan'])) {
    $meja = (int) $_POST['nomor_meja'];

    // Validasi batas nomor meja
    if ($meja >= 1 && $meja <= 10) {
        mysqli_query($conn, "UPDATE pesanan SET nomor_meja = '$meja' WHERE id = $id");
        header("Location: kasir.php");
        exit;
    } else {
        $error = "meja hanya ada nomor 1-10";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Nomor Meja</title>
    <link href="icon/seleraku.ico" rel="shortcut icon"></link>
    <style>
          * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('bg/cangijo.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0
        }

        .container {
            max-width: 400px;
            margin: 150px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        @media (max-width: 480px) {
        
            .container {
                margin: 30px 15px;
                padding: 15px;
            }

            h2 {
                font-size: 20px;
            }

            button, input[type="number"] {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Nomor Meja</h2>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="nomor_meja">Nomor Meja</label>
            <input 
                type="number" 
                id="nomor_meja" 
                name="nomor_meja" 
                min="1" 
                max="10" 
                value="<?= htmlspecialchars($pesanan['nomor_meja']) ?>" 
                required
            >
            <button type="submit" name="simpan">Simpan</button>
        </form>
    </div>
</body>
</html>

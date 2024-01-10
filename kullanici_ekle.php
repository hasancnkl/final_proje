<?php
session_start();

$db_sunucu = 'localhost';
$db_adı = 'otopark_otomasyonu';
$db_kullanici = 'root';
$db_sifre = '';

try {
    $pdo = new PDO("mysql:host={$db_sunucu};dbname={$db_adı}", $db_kullanici, $db_sifre);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mail = $_POST['mail'];
    $sifre = $_POST['sifre'];
    $adsoyad = $_POST['adsoyad'];

    $ekle_sorgu = $pdo->prepare("INSERT INTO kullanici_giris (mail, sifre, adsoyad) VALUES (?, ?, ?)");
    $ekle_sorgu->execute([$mail, $sifre, $adsoyad]);
    try {
       

        $_SESSION['ekle_mesaji'] = 'Yeni kullanıcı başarıyla eklendi.';
        $_SESSION['ekle_durumu'] = 'success';
        echo '<div class="alert alert-primary text-center" role="alert">
        <strong> kayıt Başarılı </strong></div>';
          header('Refresh:2; admin_panel.php');
        exit();
       
    } catch (PDOException $e) {
        $_SESSION['ekle_mesaji'] = 'Yeni kullanıcı eklenirken bir hata oluştu.';
        $_SESSION['ekle_durumu'] = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Kullanıcı Ekle</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

       
        form {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        form h2 {
            text-align: center;
            color: #3498db;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        form input:focus {
            border-color: #3498db;
        }

        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            box-sizing: border-box;
            transition: background-color 0.3s;
        }

        form input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>


<form action="kullanici_ekle.php" method="post" style="width: 100%; max-width: 400px; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); box-sizing: border-box;">
    <h2>Yeni Kullanıcı Ekle</h2>

    <label for="mail">E-posta:</label>
    <input type="email" id="mail" name="mail" required>

    <label for="sifre">Şifre:</label>
    <input type="password" id="sifre" name="sifre" required>

    <label for="adsoyad">Adı Soyadı:</label>
    <input type="text" id="adsoyad" name="adsoyad" required>

    <input type="submit" name="ekle" value="Kullanıcı Ekle">

    
    <a href="admin_panel.php" style="display: block; text-align: center; margin-top: 10px; text-decoration: none; color: #3498db;">Geri</a>
</form>

</body>
</html>

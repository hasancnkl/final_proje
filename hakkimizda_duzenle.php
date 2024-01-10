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


$bilgiler_sorgu = $pdo->query("SELECT * FROM hakkimizda WHERE id = 1");
$hakkimizda_veriler = $bilgiler_sorgu->fetch(PDO::FETCH_ASSOC);

if (!$hakkimizda_veriler) {
    die("Hakkımızda bilgileri bulunamadı.");
}

if (isset($_POST['firma_adi']) && isset($_POST['hosgeldiniz_baslik'])) {
    
    $firma_adi = $_POST['firma_adi'];
    $hosgeldiniz_baslik = $_POST['hosgeldiniz_baslik'];
    $vizyon_baslik = $_POST['vizyon_baslik'];
    $vizyon_aciklama = $_POST['vizyon_aciklama'];
    $misyon_baslik = $_POST['misyon_baslik'];
    $misyon_aciklama = $_POST['misyon_aciklama'];
    $iletisim_eposta = $_POST['iletisim_eposta'];
    $iletisim_telefon = $_POST['iletisim_telefon'];
    $iletisim_adres = $_POST['iletisim_adres'];

    
    $guncelle_sorgu = $pdo->prepare("UPDATE hakkimizda SET 
        firma_adi = :firma_adi, 
        hosgeldiniz_baslik = :hosgeldiniz_baslik, 
        vizyon_baslik = :vizyon_baslik, 
        vizyon_aciklama = :vizyon_aciklama, 
        misyon_baslik = :misyon_baslik, 
        misyon_aciklama = :misyon_aciklama, 
        iletisim_eposta = :iletisim_eposta, 
        iletisim_telefon = :iletisim_telefon, 
        iletisim_adres = :iletisim_adres 
        WHERE id = 1");

    $guncelle_sorgu->bindParam(':firma_adi', $firma_adi, PDO::PARAM_STR);
    $guncelle_sorgu->bindParam(':hosgeldiniz_baslik', $hosgeldiniz_baslik, PDO::PARAM_STR);
    $guncelle_sorgu->bindParam(':vizyon_baslik', $vizyon_baslik, PDO::PARAM_STR);
    $guncelle_sorgu->bindParam(':vizyon_aciklama', $vizyon_aciklama, PDO::PARAM_STR);
    $guncelle_sorgu->bindParam(':misyon_baslik', $misyon_baslik, PDO::PARAM_STR);
    $guncelle_sorgu->bindParam(':misyon_aciklama', $misyon_aciklama, PDO::PARAM_STR);
    $guncelle_sorgu->bindParam(':iletisim_eposta', $iletisim_eposta, PDO::PARAM_STR);
    $guncelle_sorgu->bindParam(':iletisim_telefon', $iletisim_telefon, PDO::PARAM_STR);
    $guncelle_sorgu->bindParam(':iletisim_adres', $iletisim_adres, PDO::PARAM_STR);

    try {
        $guncelle_sorgu->execute();
        echo '<div style="background-color: #2ecc71; color: #fff; padding: 10px; text-align: center;">Bilgiler başarıyla güncellendi.</div>';
        header('Refresh:2; admin_panel.php');
        
    } catch (PDOException $e) {
        die("Güncelleme hatası: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hakkımızda Düzenle</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            line-height: 1.6;
        }

        header {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 36px;
        }

        main {
            background-color: #ecf0f1;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
        }

        form {
            max-width: 1000px;
            margin:0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
            display: inline-block;
        }

        button:hover {
            background-color: #2980b9;
        }

        footer {
            margin-top: 20px;
            background-color: #34495e;
            color: #ecf0f1;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Hakkımızda Düzenle</h1>
    </header>

    <main>
        <section id="about">
            <form action="hakkimizda_duzenle.php" method="post">
                <label for="firma_adi">Firma Adı:</label>
                <input type="text" name="firma_adi" value="<?php echo $hakkimizda_veriler['firma_adi']; ?>" required>

                <label for="hosgeldiniz_baslik">Hoş Geldiniz Başlık:</label>
                <input type="text" name="hosgeldiniz_baslik" value="<?php echo $hakkimizda_veriler['hosgeldiniz_baslik']; ?>" required>

                <label for="vizyon_baslik">Vizyon Başlık:</label>
                <input type="text" name="vizyon_baslik" value="<?php echo $hakkimizda_veriler['vizyon_baslik']; ?>">

                <label for="vizyon_aciklama">Vizyon Açıklama:</label>
                <textarea name="vizyon_aciklama"><?php echo $hakkimizda_veriler['vizyon_aciklama']; ?></textarea>

                <label for="misyon_baslik">Misyon Başlık:</label>
                <input type="text" name="misyon_baslik" value="<?php echo $hakkimizda_veriler['misyon_baslik']; ?>">

                <label for="misyon_aciklama">Misyon Açıklama:</label>
                <textarea name="misyon_aciklama"><?php echo $hakkimizda_veriler['misyon_aciklama']; ?></textarea>

                <label for="iletisim_eposta">İletişim E-Posta:</label>
                <input type="email" name="iletisim_eposta" value="<?php echo $hakkimizda_veriler['iletisim_eposta']; ?>">

                <label for="iletisim_telefon">İletişim Telefon:</label>
                <input type="tel" name="iletisim_telefon" value="<?php echo $hakkimizda_veriler['iletisim_telefon']; ?>">

                <label for="iletisim_adres">İletişim Adres:</label>
                <textarea name="iletisim_adres"><?php echo $hakkimizda_veriler['iletisim_adres']; ?></textarea>

                <button type="submit">Bilgileri Güncelle</button>
                <form>
                <a href="admin_panel.php">Geri</a>
                </form>
            </form>
            
        </section>
    </main>

    
</body>

</html>

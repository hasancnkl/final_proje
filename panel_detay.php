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


$kullanici_id = isset($_GET['id']) ? $_GET['id'] : null;
$secilen_arac_plaka = isset($_GET['arac']) ? $_GET['arac'] : null;


if (!$kullanici_id) {
    die("Geçersiz kullanıcı ID");
}


$sorgu_kullanici = $pdo->prepare("SELECT * FROM kullanici_giris WHERE id = :id");
$sorgu_kullanici->bindParam(':id', $kullanici_id);
$sorgu_kullanici->execute();
$kullanici = $sorgu_kullanici->fetch(PDO::FETCH_ASSOC);


$sorgu_arac = $pdo->prepare("SELECT * FROM arac_kayit WHERE musteri_id = :id AND arac_plaka = :arac_plaka");
$sorgu_arac->bindParam(':id', $kullanici_id);
$sorgu_arac->bindParam(':arac_plaka', $secilen_arac_plaka);
$sorgu_arac->execute();
$arac = $sorgu_arac->fetch(PDO::FETCH_ASSOC);


$arac_plaka = $arac['arac_plaka'] ?? 'Bilgi bulunamadı';
$arac_giris_tarih = $arac['arac_giris_tarih'] ?? 'Bilgi bulunamadı';
$arac_cikis_tarih = $arac['arac_cikis_tarih'] ?? null;
$ucret = $arac['ucret'] ?? 'Bilgi bulunamadı';
$odendi = isset($arac['odendi']) ? ($arac['odendi'] ? 'Ödendi' : 'Ödenmedi') : 'Bilgi bulunamadı';
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Detayları</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
    }

    header {
        background-color: #3498db;
        color: #fff;
        padding: 15px 0;
        text-align: center;
    }

    h1 {
        margin: 0;
        font-size: 28px;
    }

    main {
        display: flex;
        justify-content: space-between;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 20px;
        padding: 20px;
    }

    .car-list {
        flex-basis: 30%;
    }

    .car-details {
        flex-basis: 65%;
    }

    .car-list table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .car-list th,
    .car-list td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
        background-color: #f2f2f2;
    }

    .car-list th {
        background-color: #3498db;
        color: #fff;
    }

    .car-list td a {
        color: #3498db;
        text-decoration: none;
        font-weight: bold;
    }

    .car-list td a:hover {
        text-decoration: underline;
    }

    .car-details p {
        margin-bottom: 16px;
    }

    .user-details,
    .car-details {
        margin-top: 20px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .user-details label,
    .car-details label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .user-details p,
    .car-details p {
        margin-bottom: 16px;
    }

    .user-details a {
        display: block;
        margin-top: 20px;
        text-align: center;
        color: #3498db;
        text-decoration: none;
        font-weight: bold;
    }
</style>

</head>

<body>
    <header>
        <h1>Kullanıcı Detayları</h1>
    </header>

    <main>
        <div class="car-list">
            <table>
                <tr>
                    <th>Araç Plakası</th>
                </tr>
                <?php
                $sorgu_arac_plakalari = $pdo->prepare("SELECT arac_plaka FROM arac_kayit WHERE musteri_id = :id");
                $sorgu_arac_plakalari->bindParam(':id', $kullanici_id);
                $sorgu_arac_plakalari->execute();
                $arac_plakalari = $sorgu_arac_plakalari->fetchAll(PDO::FETCH_ASSOC);

                foreach ($arac_plakalari as $arac_plaka) {
                    echo "<tr><td><a href='?id={$kullanici_id}&arac={$arac_plaka['arac_plaka']}'>{$arac_plaka['arac_plaka']}</a></td></tr>";
                }
                ?>
            </table>
        </div>
        <div class="car-details">
            <div class="user-details">
                <label for="id">ID:</label>
                <p><?php echo $kullanici['id'] ?? 'Bilgi bulunamadı'; ?></p>

                <label for="mail">Mail:</label>
                <p><?php echo $kullanici['mail'] ?? 'Bilgi bulunamadı'; ?></p>

                <label for="adsoyad">Adı Soyadı:</label>
                <p><?php echo $kullanici['adsoyad'] ?? 'Bilgi bulunamadı'; ?></p>
            </div>
            <div class="car-details">

                <label for="arac_giris_tarih">Araç Giriş Tarihi:</label>
                <p><?php echo $arac_giris_tarih; ?></p>

                <label for="arac_cikis_tarih">Araç Çıkış Tarihi:</label>
                <p><?php echo $arac_cikis_tarih; ?></p>

                <label for="ucret">Ücret:</label>
                <p><?php echo $ucret; ?></p>

                <label for="odendi">Ödeme Durumu:</label>
                <p><?php echo $odendi; ?></p>

                <a href="admin_panel.php">Geri Dön</a>
            </div>
        </div>
    </main>
</body>

</html>

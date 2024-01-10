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

if (isset($_GET['id'])) {
    $kullanici_id = $_GET['id'];

   
    $araclar_sorgu = $pdo->prepare("SELECT * FROM arac_kayit WHERE musteri_id = :musteri_id");
    $araclar_sorgu->bindParam(":musteri_id", $kullanici_id, PDO::PARAM_INT);
    $araclar_sorgu->execute();
    $araclar = $araclar_sorgu->fetchAll(PDO::FETCH_ASSOC);

    
    foreach ($araclar as $arac) {
        $arac_id = $arac['arac_id'];
        $arac_silme_sorgu = $pdo->prepare("DELETE FROM arac_kayit WHERE arac_id = :arac_id");
        $arac_silme_sorgu->bindParam(":arac_id", $arac_id, PDO::PARAM_INT);
        $arac_silme_sorgu->execute();
    }

    
    $kullanici_silme_sorgu = $pdo->prepare("DELETE FROM kullanici_giris WHERE id = :musteri_id");
    $kullanici_silme_sorgu->bindParam(":musteri_id", $kullanici_id, PDO::PARAM_INT);
    $kullanici_silme_sorgu->execute();

   
    if ($kullanici_silme_sorgu->rowCount() > 0) {
        $_SESSION['silme_mesaji'] = 'Kullanıcı ve araçları başarıyla silindi.';
        $_SESSION['silme_durumu'] = 'success';
    } else {
        $_SESSION['silme_mesaji'] = 'Kullanıcı silme işlemi başarısız.';
        $_SESSION['silme_durumu'] = 'error';
    }

    
    header('Location: admin_panel.php');
    exit();
}
?>

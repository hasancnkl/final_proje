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
    $mesaj_id = $_GET['id'];

    $silme_sorgu = $pdo->prepare("DELETE FROM iletisim_mesaj WHERE id = ?");
    $silme_sonuc = $silme_sorgu->execute([$mesaj_id]);

    
    $bilgilendirme_mesaji = ($silme_sonuc) ? 'Mesaj başarıyla silindi.' : 'Mesaj silinirken bir hata oluştu.';
    $bilgilendirme_durumu = ($silme_sonuc) ? 'success' : 'error';
} else {
    $bilgilendirme_mesaji = 'Geçersiz mesaj ID\'si.';
    $bilgilendirme_durumu = 'error';
}


$_SESSION['silme_mesaji'] = $bilgilendirme_mesaji;
$_SESSION['silme_durumu'] = $bilgilendirme_durumu;

header('Location: admin_panel.php');
exit();
?>

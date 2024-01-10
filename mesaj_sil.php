<?php
try {
    $db = new PDO('mysql:host=localhost; dbname=otopark_otomasyonu', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    if (isset($_POST['sil'])) {
        $sil = $db->prepare('DELETE FROM iletisim_mesaj');
        $sil->execute();
        if ($sil->rowCount() > 0) {
            echo '<div class="success-alert">
            <strong> Mesajlarınız başarıyla silindi. </strong></div>';
            header('Refresh:2; anasayfa.php');
        } else {
            echo '<div class="error-alert">
            <strong> Mesajları silme başarısız. </strong></div>';
        }
    }
} catch (PDOException $e) {
    echo 'Hata: ' . $e->getMessage();
}
?>

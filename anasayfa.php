<?php

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

?>

<?php require 'header.php'; 

$musteri_id = $_SESSION["musteri_id"];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $arac_plaka = $_POST['arac_plaka'];
    $arac_kat = $_POST['arac_kat'];
    $arac_blok = $_POST['arac_blok'];

    if (! $arac_plaka || ! $arac_kat || ! $arac_blok) {
        echo '<div class="alert alert-danger text-center" role="alert">
        <strong> Boş Alan Bırakmayınız </strong></div>';
        header('Refresh:2; anasayfa.php');
    } else {
        $kaydet = $pdo->prepare("INSERT INTO arac_kayit SET arac_plaka = :arac_plaka, arac_kat = :arac_kat, arac_blok = :arac_blok, musteri_id = :musteri_id");

        $kaydet->bindParam(':arac_plaka', $arac_plaka);
        $kaydet->bindParam(':arac_kat', $arac_kat);
        $kaydet->bindParam(':arac_blok', $arac_blok);
        $kaydet->bindParam(':musteri_id', $musteri_id);

        $kaydet->execute();
        
        if ($kaydet) {
            echo '<div class="alert alert-success text-center" role="alert">
            <strong> Kayıt Başarılı </strong></div>';
            header('Refresh:2; parkedenarac.php');
        } else {
            echo '<div class="alert alert-danger text-center" role="alert">
            <strong> Kayıt Başarısız </strong></div>';
        }
    }
}
?>

<style>
    body {
        background-image: url('resim/arkaplan1.jpg');
        background-repeat: no-repeat;
        background-position: center center;
        background-attachment: fixed;
        background-size: cover;
        color: #fff; 
    }

    #container {
        width: 400px;
        margin: 0 auto;
        margin-top: 50px; 
        opacity: 0.9;
        background-color: rgba(0, 0, 0, 0.7);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .card {
        background-color: rgba(255, 255, 255, 0.1); 
        border: 1px solid #fff; 
    }

    .form {
        width: 80%;
        margin: 0 auto;
    }

    .form-control {
        margin-bottom: 15px;
    }

    .btn-danger,
    .btn-primary {
        width: 48%; 
    }

    .btn-danger:hover,
    .btn-primary:hover {
        opacity: 0.8;
    }

</style>

<div id="container" class="container p-5">
    <div class="card p-5">
        <div class="form">
        

<h1 class="text-center mb-5"><strong>Araç Kayıt</strong></h1>
            <form action="anasayfa.php" method="post">
                <input type="text" name="arac_plaka" class="form-control" placeholder="Plaka Giriniz"><br>
                
                <select name="arac_kat" class="form-control">
                    <option value="">Kat Seçiniz</option>
                    <option value="Kat 1">Kat 1</option>
                    <option value="Kat 2">Kat 2</option>
                    <option value="Kat 3">Kat 3</option>
                </select><br>
                
                <select name="arac_blok" class="form-control">
                    <option value="">Blok Seçiniz</option>
                    <option value="A Blok">A Blok</option>
                    <option value="B Blok">B Blok</option>
                    <option value="C Blok">C Blok</option>
                    <option value="D Blok">D Blok</option>
                    <option value="E Blok">E Blok</option>
                </select><br>

                <div class="text-center">
                    <button type="reset" class="btn btn-danger">Sıfırla</button>
                    <button type="submit" name="kaydet" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>



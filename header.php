<?php
require 'baglan.php';

function checkLogin() {
    
    if (!isset($_SESSION['mail'])) {
        header('location:index.php?izinsizgiris');
        exit();
    }
}

function searchAndExit() {
    global $db;

    if (isset($_POST['bul'])) {
        $bul = $_POST['bul'];
        if (!$bul) {
            echo '<div class="alert alert-success text-center" role="alert">
                <strong> Arama Yapmak İçin Birşeyler Yazınız </strong></div>';
            header('Refresh:2; parkedenarac.php');
        } else {
            
            $plakabul = $db->prepare('SELECT * FROM arac_kayit WHERE arac_plaka LIKE :arac_plaka');
            $plakabul->execute([':arac_plaka' => '%' . $bul . '%']);

            if ($plakabul->rowCount()) {
                foreach ($plakabul as $plaka) {
                    echo '<div class="alert alert-success text-center" role="alert">' .
                        $plaka['arac_plaka'] .
                        '<strong>Girilen plaka hala otoparkta</strong></div>';
                    header('Refresh:9; parkedenarac.php');
                }
            } else {
                echo '<div class="alert alert-primary text-center" role="alert">
                <strong> Girilen Plaka Otoparkta Yoktur </strong></div>';
                header('Refresh:9; parkedenarac.php');
            }
        }
    }

    if (isset($_POST['cikis'])) {
        header('location:exit.php');
        exit();
    }
}


checkLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Otopark Otomasyonu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url("image/pic-3.png");
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="anasayfa.php"><strong>Anasayfa</strong></a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link" href="parkedenarac.php"><strong>Park Durumu</strong></a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="iletisim.php"><strong>İletişim</strong></a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="hakkimizda.php"><strong>Hakkımızda</strong></a>
        </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" method="post" action="parkedenarac.php">
        <input class="form-control mr-sm-2" type="search" placeholder="Arama yap" name="bul" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit" style="margin:10px">Arama</button>
        <button type="submit" name="cikis" class="btn bg-danger" style="color: #fff;">Çıkış Yap</button>
    </form>
</nav>
<br>

<?php

searchAndExit();


$now = new DateTime('now', new DateTimeZone('Europe/Istanbul'));
$tarih = $now->format('d-m-Y');
$saat = $now->format('H:i:s');
?>

<div class="alert alert-info text-center" role="alert">
    <strong><?php echo "Bugün $tarih, Saat $saat"; ?></strong>
</div>

</body>

</html>

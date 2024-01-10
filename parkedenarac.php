<?php

$db_sunucu = 'localhost';
$db_adı = 'otopark_otomasyonu';
$db_kullanici = 'root';
$db_sifre = "";

try {
    $pdo = new PDO("mysql:host={$db_sunucu};dbname={$db_adı}", $db_kullanici, $db_sifre);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}

require 'header.php';

$musteri_id = @$_SESSION["musteri_id"];
$saatBasiUcret = 5;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aracID = isset($_POST['arac_id']) ? $_POST['arac_id'] : null;

    $updateQuery = $pdo->prepare('UPDATE arac_kayit SET odendi = "1" WHERE arac_id = :arac_id');
    $updateQuery->bindParam(':arac_id', $aracID, PDO::PARAM_INT);

    if (!$updateQuery->execute()) {
        die('Ödeme işlemi sırasında bir hata oluştu: ' . print_r($updateQuery->errorInfo(), true));
    }

    
    $updateUcretQuery = $pdo->prepare('UPDATE arac_kayit SET ucret = :ucret WHERE arac_id = :arac_id');

    $seciliArac = $pdo->prepare("SELECT * FROM arac_kayit WHERE arac_id = :arac_id");
    $seciliArac->bindParam(':arac_id', $aracID, PDO::PARAM_INT);
    $seciliArac->execute();
    $seciliArac = $seciliArac->fetch(PDO::FETCH_ASSOC);

    if ($seciliArac) {
        $girisTarihi = $seciliArac['arac_giris_tarih'];
        $cikisTarihi = $seciliArac['arac_cikis_tarih'];

        $timestamp1 = strtotime($girisTarihi);
        $timestamp2 = $cikisTarihi ? strtotime($cikisTarihi) : strtotime($girisTarihi);

        $ucret = ucretHesapla($timestamp1, $timestamp2, $saatBasiUcret);

        
        $updateUcretQuery->bindParam(':ucret', $ucret, PDO::PARAM_STR);
        $updateUcretQuery->bindParam(':arac_id', $aracID, PDO::PARAM_INT);

        if (!$updateUcretQuery->execute()) {
            die('Ücret güncelleme sırasında bir hata oluştu: ' . print_r($updateUcretQuery->errorInfo(), true));
        } else {
            echo '<div class="alert alert-primary text-center" role="alert">
        <strong> Ücret başarıyla ödendi </strong></div>';
          header('Refresh:2; parkedenarac.php');
        }
    }
}


$araclar = $pdo->query("SELECT * FROM arac_kayit WHERE musteri_id ='$musteri_id'")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otopark Otomasyonu</title>
    <style>
        body {
            background-image: url('resim/arkaplan1.jpg');
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            background-size: cover;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #fff;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .btn {
            padding: 5px 10px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #4285f4;
        }

        .btn-success {
            background-color: #0f9d58;
        }

        .btn-danger {
            background-color: #db4437;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <table class="table table-dark text-center">
        <thead>
            <tr>
                <th scope="col">Sıra</th>
                <th scope="col">Plaka</th>
                <th scope="col">Kat</th>
                <th scope="col">Blok</th>
                <th scope="col">Giriş Tarihi</th>
                <th scope="col">Çıkış Tarihi</th>
                <th scope="col">Düzenle</th>
                <th scope="col">Araç Çıkış</th>
                <th scope="col">Kayıt Sil</th>
                <th scope="col">Ücret</th>
                <th scope="col">Ödeme</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php
            foreach ($araclar as $sira => $kayit) {
                $sira++;
                $id = $kayit['arac_id'];
                $plaka = $kayit['arac_plaka'];
                $kat = $kayit['arac_kat'];
                $blok = $kayit['arac_blok'];
                $girisTarihi = $kayit['arac_giris_tarih'];
                $cikisTarihi = $kayit['arac_cikis_tarih'];
                $odendi = $kayit['odendi'];
                $timestamp1 = strtotime($girisTarihi);
                $timestamp2 = $cikisTarihi ? strtotime($cikisTarihi) : strtotime($girisTarihi);

                $ucret = ucretHesapla($timestamp1, $timestamp2, $saatBasiUcret);
            ?>
                <tr>
                    <th><?php echo $sira ?></th>
                    <td><?php echo $plaka ?></td>
                    <td><?php echo $kat ?></td>
                    <td><?php echo $blok ?></td>
                    <td><?php echo $girisTarihi ?></td>
                    <td><?php echo $cikisTarihi ? $cikisTarihi : '-' ?></td>
                    <td><a href="duzenle.php?id=<?php echo $id ?>"><button class="btn btn-primary">Düzenle</button></a></td>
                    <td>
                        <?php
                        if (!$cikisTarihi) {
                            echo '<a href="araccikis.php?id=' . $id . '"><button class="btn btn-success">Araç Çıkış</button></a>';
                        } else {
                            echo '<button class="btn btn-success" disabled>Araç Çıkış</button>';
                        }
                        ?>
                    </td>
                    <td><a href="sil.php?id=<?php echo $id ?>"><button class="btn btn-danger">Kayıt Sil</button></a></td>
                    <td><?php echo number_format($ucret, 2, '.', '') ?></td>
                    <td>
                        <?php
                        if (!$odendi && $cikisTarihi) {
                            echo '<form method="post" action=""><input type="hidden" name="arac_id" value="' . $id . '"><button type="submit" class="btn btn-success">Öde</button></form>';
                        } elseif ($odendi) {
                            echo 'Ödendi';
                        }
                        ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php
    function ucretHesapla($girisZamani, $cikisZamani, $saatlikUcret)
    {
        $farkSaniye = $cikisZamani - $girisZamani;
        $saatler = $farkSaniye / 3600;

        $ucret = $saatler * $saatlikUcret;

        return $ucret;
    }
    ?>

</body>

</html>

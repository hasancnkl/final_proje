<?php
require 'baglan.php';

if (isset($_POST['giris'])) {
    $kullanici_adi = $_POST['kullanici_adi'];
    $parola = $_POST['parola'];

    $sorgu = $db->prepare('SELECT * FROM admins WHERE kullanici_adi=:kullanici_adi AND parola=:parola');
    $sorgu->execute([
        'kullanici_adi' => $kullanici_adi,
        'parola' => $parola
    ]);

    $say = $sorgu->rowCount();

    if ($say == 1) {
        $_SESSION['kullanici_adi'] = $kullanici_adi;
        echo '<div class="alert alert-primary text-center" role="alert">
     <strong> Giriş Başarılı </strong></div>';
       header('Refresh:2; admin_panel.php');
        }
     else{
        echo '<div class="alert alert-danger text-center" role="alert">
        <strong> Giriş Bilgileri Hatalı </strong></div>';
    header('Refresh:2; admin_giris.php');
        }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Otopark Otomasyonu - Yönetici Giriş</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            background-image: url('resim/arkaplan1.jpg');
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            background-size: cover;
            height: 100vh;
            color: #fff;
        }

        #container {
            width: 400px;
            margin: 0 auto;
            margin-top: 100px;
            opacity: 0.9;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        #baslik {
            padding: 15px 0;
            margin-bottom: 25px;
            border: 1px solid #fff;
            background: #211572;
            text-align: center;
            border-radius: 15px;
        }

        .form {
            width: 80%;
            margin: 0 auto;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn-primary,
        .btn-danger,
        .btn-secondary {
            width: 100%;
        }

        .btn-primary:hover,
        .btn-danger:hover,
        .btn-secondary:hover {
            opacity: 0.8;
        }

        
        .geri-btn {
            margin-top: 10px;
            width: 70%;
            font-size: smaller;
        }
    </style>
</head>
<body>
    <header>
        <div id="container" class="container p-4">
            <div id="baslik" class="text-center"><h2>YÖNETİCİ GİRİŞ</h2></div>
            <div class="card p-5">
                <div class="form">
                    <div class="text-center"><h2>Giriş Yapınız</h2></div>
                    <form action="admin_giris.php" method="post">
                        <div class="mb-3">
                            <input type="text" name="kullanici_adi" class="form-control" placeholder="Kullanıcı adınızı giriniz" required><br>
                            <input type="password" name="parola" class="form-control" placeholder="Parola Giriniz" required><br>
                            <div class="text-center">
                                <input type="submit" class="btn btn-primary" name="giris" value="GİRİŞ YAP">
                            </div>
                            <div class="text-center">
                                <a href="index.php" class="btn btn-danger geri-btn">GERİ</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
<?php include 'footer.php'; ?>

<?php
  require 'baglan.php';
?>
<?php
            if (isset($_POST['kayitol'])) {
              $adsoyad = $_POST['adsoyad'];
              $mail = $_POST['mail'];
              $sifre = $_POST['sifre'];

              $kontrolSorgusu = $db->prepare('SELECT * FROM kullanici_giris WHERE mail = :mail');
              $kontrolSorgusu->execute(['mail' => $mail]);
              $say = $kontrolSorgusu->rowCount();

              if ($say > 0) {
                echo '<div class="alert alert-danger text-center" role="alert">
                    <strong>Bu e-posta adresi zaten kayıtlı!</strong></div>';
              } else {
                $kullaniciEkle = $db->prepare('INSERT INTO kullanici_giris (adsoyad, mail, sifre) VALUES (:adsoyad, :mail, :sifre)');
                $kullaniciEkle->execute([
                  'adsoyad' => $adsoyad,
                  'mail' => $mail,
                  'sifre' => $sifre
                ]);

                if ($kullaniciEkle) {
                  echo '<div class="alert alert-success text-center" role="alert">
                      <strong>Kayıt Başarılı, Giriş Yapabilirsiniz</strong></div>';
                  header('Refresh:2; index.php');
                } else {
                  echo '<div class="alert alert-danger text-center" role="alert">
                      <strong>Kayıt İşlemi Başarısız</strong></div>';
                }
              }
            }
          ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Otopark Otomasyonu</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="admin.css">
  <style>
    body {
      background-image: url("resim/arkaplan1.jpg");
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
    .btn-danger {
      width: 100%;
    }

    .btn-primary:hover,
    .btn-danger:hover {
      opacity: 0.8;
    }

  </style>
</head>
<body>
  <header>
    <div id="container">
      <div id="baslik"><h2>OTOPARK OTOMASYONU</h2></div>
      <div class="card p-5">
        <div class="form">
          

          <div class="text-center"><h2>Kayıt Ol</h2></div>
          <form method="post">
          <div class="mb-3">
  <input type="text" name="adsoyad" class="form-control" placeholder="Ad Soyad Giriniz" required>
  <input type="email" name="mail" class="form-control" placeholder="Mail Giriniz" required>
  <input type="password" name="sifre" class="form-control" placeholder="Şifre Giriniz" required>
  <div class="text-center">
    <input type="submit" class="btn btn-primary" name="kayitol" value="Kayıt Ol">
    <a href="index.php" class="btn btn-danger">GİRİŞ EKRANI</a>
  </div>
</div>
          </form>
        </div>
      </div>
    </div>
  </header>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
<?php include 'footer.php'; ?>

<?php require 'header.php'; 
date_default_timezone_set('Europe/Istanbul');


        $duzenle = $db->query("SELECT * FROM arac_kayit WHERE arac_id=".(int)$_GET['id']);
        $sonuc = $duzenle->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $cikis_tarih = $_POST['arac_cikis_tarih'];

          if (isset($_POST['gerigel'])) {
            echo '<div class="alert alert-warning text-center" role="alert">
                  <strong>İşlem İptal Edildi</strong></div>';
            header('Refresh:2; parkedenarac.php');
          } elseif ($cikis_tarih != "") {
            if ($db->query("UPDATE arac_kayit SET arac_cikis_tarih = '$cikis_tarih' WHERE arac_id=".$_GET['id'])) {
              echo '<div class="alert alert-success text-center" role="alert">
                    <strong>İşlem Başarılı</strong></div>';
              header('Refresh:2; parkedenarac.php');
            }
          } else {
            echo '<div class="alert alert-danger text-center" role="alert">
                  <strong>Lütfen Boş Alanları Doldurun</strong></div>';
          }
        }
       

?>

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
    width: 48%;
  }

  .btn-primary:hover,
  .btn-danger:hover {
    opacity: 0.8;
  }

</style>

<div class="container container-cikis">
  <div class="card p-5">
    <div class="form">
      <form action="" method="POST">
      
        <table class="table">
          <tr>
            <td colspan="2">
              <h1 class="text-center">Araç Çıkış</h1>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <b style="color:#3498DB;">Araç Giriş Tarihi</b><br><b> <?php echo $sonuc['arac_giris_tarih'] ?><br><br></b> 
              <input type="text" name="arac_cikis_tarih" class="form-control" value="<?php echo date('d-m-Y H:i:s') ?>">
            </td>
          </tr>
          <tr>
            <td>
              <button type="submit" name="gerigel" class="btn bg-danger" style="color: #fff; font-size:20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                  <path fill-rule="evenodd"
                    d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5ZM10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5Z" />
                </svg>Geri Gel
              </button>
            </td>
            <td>
              <button type="submit" class="btn bg-primary" style="color: #fff; float: right; font-size:20px;" name="kaydet">Kaydet</button>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>


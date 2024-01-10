<?php require 'header.php'; ?>

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


$sql = "SELECT * FROM hakkimizda";
try {
    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>

            <div class="container mt-5">
                <div class="jumbotron text-center">
                    <h2 class="display-4 font-weight-bold"><?= $row["firma_adi"] ?></h2>
                    <p class="lead"><?= $row["hosgeldiniz_baslik"] ?></p>
                    <p class="lead"><?= $row["hosgeldiniz_aciklama"] ?></p>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 offset-md-3">
                        <h3 class="text-primary text-center font-weight-bold"><?= $row["vizyon_baslik"] ?></h3>
                        <p class="text-center"><?= $row["vizyon_aciklama"] ?></p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 offset-md-3">
                        <h3 class="text-primary text-center font-weight-bold"><?= $row["misyon_baslik"] ?></h3>
                        <p class="text-center"><?= $row["misyon_aciklama"] ?></p>
                    </div>
                </div>

                <div class="contact-info mt-4 text-center">
                    <h3 class="text-primary font-weight-bold">İletişim</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th class="text-center">E-posta</th>
                            <td><?= $row["iletisim_eposta"] ?></td>
                        </tr>
                        <tr>
                            <th class="text-center">Telefon</th>
                            <td><?= $row["iletisim_telefon"] ?></td>
                        </tr>
                        <tr>
                            <th class="text-center">Adres</th>
                            <td><?= $row["iletisim_adres"] ?></td>
                        </tr>
                    </table>
                </div>

                <div class="contact-button mt-4 text-center">
                    <a href="iletisim.php" class="btn btn-primary">İletişim Sayfası</a>
                </div>
            </div>
<?php
        }
    } else {
        echo "Veri bulunamadı";
    }
} catch (PDOException $e) {
    die("Veri çekme hatası: " . $e->getMessage());
}
?>

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #495057;
    }

    .container {
        max-width: 800px;
        margin: 50px auto;
        padding: 30px;
        background-color: #fff;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    h2,
    h3 {
        color: #007bff;
        margin-bottom: 20px;
    }

    p {
        color: #6c757d;
        line-height: 1.6;
    }

    strong {
        color: #343a40;
    }

    .contact-info {
        margin-top: 30px;
        border-top: 1px solid #ced4da;
        padding-top: 15px;
    }

    .contact-info strong {
        display: block;
        margin-bottom: 5px;
        color: #343a40;
    }

    .contact-button {
        margin-top: 15px;
    }

    .btn {
        padding: 10px 20px;
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: #c82333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ced4da;
    }

    th,
    td {
        padding: 15px;
        text-align: left;
    }

    th {
        background-color: #f8f9fa;
    }
</style>

<?php require 'footer.php'; ?>

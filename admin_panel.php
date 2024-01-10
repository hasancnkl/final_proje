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

if (!isset($_SESSION['admin_id']) && isset($_POST['giris'])) {
 
}

if (isset($_GET['id'])) {
    
}

$kullanicilar_sorgu = $pdo->query("SELECT * FROM kullanici_giris");
$kullanicilar = $kullanicilar_sorgu->fetchAll(PDO::FETCH_ASSOC);

$araclar_sorgu = $pdo->query("SELECT COUNT(*) FROM arac_kayit");
$toplam_arac_sayisi = $araclar_sorgu->fetchColumn();

$en_son_kullanicilar_sorgu = $pdo->query("SELECT * FROM kullanici_giris ORDER BY id DESC LIMIT 5");
$en_son_kullanicilar = $en_son_kullanicilar_sorgu->fetchAll(PDO::FETCH_ASSOC);

$mesajlar_sorgu = $pdo->query("SELECT * FROM iletisim_mesaj");
$mesajlar = $mesajlar_sorgu->fetchAll(PDO::FETCH_ASSOC);

$silmeMesaji = isset($_SESSION['silme_mesaji']) ? $_SESSION['silme_mesaji'] : null;
$silmeDurumu = isset($_SESSION['silme_durumu']) ? $_SESSION['silme_durumu'] : null;

unset($_SESSION['silme_mesaji']);
unset($_SESSION['silme_durumu']);

$hakkimizda_duzenle= "hakkimizda_duzenle.php";
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f2f2f2;
        color: #333;
    }

    header {
        background-color: #2c3e50;
        color: #ecf0f1;
        padding: 15px 0;
        text-align: center;
    }

    h1 {
        margin: 0;
        font-size: 28px;
    }

    nav {
        background-color: #34495e;
        overflow: hidden;
    }

    nav a {
        float: left;
        display: block;
        color: #ecf0f1;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 16px;
    }

    nav a:hover {
        background-color: #2c3e50;
    }

    main {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 20px;
        padding: 20px;
    }

    footer {
        margin-top: 20px;
        background-color: #2c3e50;
        color: #ecf0f1;
        text-align: center;
        padding: 10px;
    }

    .error-message {
        color: #e74c3c;
        margin-bottom: 10px;
    }

    .dashboard-stats {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
    }

    .stat-box {
        text-align: center;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #ecf0f1;
        margin-bottom: 20px;
    }

    .user-list {
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #3498db;
        color: #fff;
    }

    .user-list a {
        display: inline-block;
        padding: 8px 16px;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        margin-right: 5px;
    }

    .user-list a:hover {
        background-color: #2980b9;
    }

    .add-button {
        display: inline-block;
        padding: 8px 16px;
        background-color: #27ae60;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 10px;
    }

    .add-button:hover {
        background-color: #219c56;
    }

    .message-list {
        margin-top: 20px;
    }

    .message-list table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .message-list th, .message-list td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    .message-list th {
        background-color: #3498db;
        color: #fff;
    }

    .message-list .message-content {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .message-list a {
        display: inline-block;
        padding: 8px 16px;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        background-color: #e74c3c;
        margin-top: 10px;
    }

    .message-list a:hover {
        background-color: #c0392b;
    }

    #hakkimizda {
    background-color: #ecf0f1;
    padding: 20px;
    border-radius: 5px;
    margin-top: 20px;
}

#hakkimizda p {
    font-size: 16px;
    line-height: 1.5;
}

.edit-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #3498db;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 10px;
    transition: background-color 0.3s ease-in-out;
}

.edit-button:hover {
    background-color: #2980b9;
}
</style>

</head>
<body>
    <header>
        <h1>Otopark Otomasyonu - Admin Paneli</h1>
        <p>Hoş geldiniz, <?php echo isset($_SESSION['kullanici_adi']) ? $_SESSION['kullanici_adi'] : ''; ?>!</p>
    </header>

    <nav>
    <a href="#dashboard">Panel</a>
    <a href="#users">Kullanıcılar</a>
    <a href="#messages">Mesajlar</a>
    <a href="#hakkimizda">Hakkımızda</a>
    <a href="exit.php">Çıkış Yap</a>
</nav>

    <main>
        <?php if (isset($hata_mesaji)) : ?>
            <div class="error-message"><?php echo $hata_mesaji; ?></div>
        <?php endif; ?>

        <section id="dashboard">
    <h2>Genel Bakış</h2>

    <div class="dashboard-stats">
        <div class="stat-box">
            <h3>Toplam Kullanıcı Sayısı</h3>
            <p><?php echo count($kullanicilar); ?></p>
        </div>

        <div class="stat-box">
            <h3>Toplam Araç Sayısı</h3>
            <p><?php echo $toplam_arac_sayisi; ?></p>
        </div>

        <div class="stat-box">
            <h3>En Son Eklenen Kullanıcılar</h3>
            <ul class="recent-users-list">
                <?php foreach ($en_son_kullanicilar as $son_kullanici) : ?>
                    <li><?php echo $son_kullanici['adsoyad']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>


        <section id="users">
            <h2>Kullanıcılar</h2>
            <div class="user-list">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kullanıcı Adı</th>
                            <th>Adı Soyadı</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kullanicilar as $kullanici) : ?>
                            <tr>
                                <td><?php echo $kullanici['id']; ?></td>
                                <td><?php echo $kullanici['mail']; ?></td>
                                <td><?php echo $kullanici['adsoyad']; ?></td>
                                <td>
                                    <a href="panel_detay.php?id=<?php echo $kullanici['id']; ?>" style="background-color: #3498db;">Detay</a>
                                    <a href="panel_duzenle.php?id=<?php echo $kullanici['id']; ?>" style="background-color: #ffc107;">Düzenle</a>
                                    <a href="panel_kullanici_sil.php?id=<?php echo $kullanici['id']; ?>" onclick="return confirm('Silmek istediğinize emin misiniz?')" style="background-color: #e74c3c;">Sil</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a class="add-button" href="kullanici_ekle.php" style="background-color: #27ae60;">Yeni Kullanıcı Ekle</a>
            </div>
        </section>

        <section id="messages">
            <h2>Mesajlar</h2>
            <div class="message-list">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>İsim</th>
                            <th>Email</th>
                            <th>Konu</th>
                            <th>Mesaj</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mesajlar as $mesaj) : ?>
                            <tr>
                                <td><?php echo $mesaj['id']; ?></td>
                                <td><?php echo $mesaj['isim']; ?></td>
                                <td><?php echo $mesaj['email']; ?></td>
                                <td><?php echo $mesaj['konu']; ?></td>
                                <td><?php echo $mesaj['mesaj']; ?></td>
                                <td>
                                    <a href="panel_mesaj_sil.php?id=<?php echo $mesaj['id']; ?>" onclick="return confirm('Bu mesajı silmek istediğinizden emin misiniz?')" style="background-color: #e74c3c;">Sil</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <section id="hakkimizda">
        <h2>Hakkımızda</h2>
        <p>Hakkımızda kısmını duzenlemek için aşağıdaki butona basabilirsiniz.</p>
        
        <a class="edit-button" href="<?php echo $hakkimizda_duzenle; ?>">Hakkımızda Düzenle</a>
    </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Admin Paneli</p>
    </footer>
</body>
</html>

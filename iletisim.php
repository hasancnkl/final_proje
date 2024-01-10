<?php require 'header.php'; ?>

<?php

if (isset($_POST['Gonder'])) {

    $isim = $_POST['isim'];
    $email = $_POST['email'];
    $konu = $_POST['konu'];
    $mesaj = $_POST['mesaj']; 

    if (!$isim || !$email || !$konu || !$mesaj) {
        echo '<div class="error-alert">
        <strong> Boş Alan Bırakmayınız </strong></div>';
    } else {
        try {
            $db = new PDO('mysql:host=localhost; dbname=otopark_otomasyonu', 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $Gonder = $db->prepare('INSERT INTO iletisim_mesaj SET
                    isim = ?,
                    email = ?,
                    konu = ?,
                    mesaj = ?
                ');

            $Gonder->execute([$isim, $email, $konu, $mesaj]);

            if ($Gonder->rowCount() > 0) {
                echo '<div class="success-alert">
                <strong> Mesaj gönderme Başarılı </strong></div>';
                header('Refresh:2; anasayfa.php');
            } else {
                echo '<div class="error-alert">
                <strong> Mesaj gönderme Başarısız </strong></div>';
            }
        } catch (PDOException $e) {
            echo 'Hata: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

    <style>
        body {
            background-image: linear-gradient(to right, rgba(121, 119, 119, 0.025), rgb(127, 128, 125));
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
        }

        h2 {
            text-align: center;
            color: #fff;
            margin-top: 50px;
        }

        .contact {
            background-color: rgba(255, 255, 255, 0.9);
            width: 60%;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }

        label {
            display: inline-block;
            width: 100px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
        }

        textarea {
            padding: 10px;
            margin-bottom: 15px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
            color: #000;
            
        }

        input[type="submit"],
        input[type="button"] {
            background-color: #5500FF;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 10px;
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #4200C3;
        }

        .btn-danger {
            background-color: #FF0000;
            color: #fff;
        }

        .success-alert,
        .error-alert {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .success-alert {
            color: #4CAF50;
            background-color: #e7f4e7;
        }

        .error-alert {
            color: #D8000C;
            background-color: #FFD2D2;
        }

        h2 {
            color: black;
            font-weight: bold;
            font-size: 24px;
        }

        #mesaj {
            height: 500px;
            display: block;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
            color: #000;
        }

        #editor {
            height: 500x; 
        }
        
        .ck.ck-content {
            color: black !important; 
        }
        
    </style>
</head>

<body>
    <div class="contact">
        <h2>İletişim</h2>
        <form action="iletisim.php" method="POST">

            <label for="isim">İsim:</label>
            <input type="text" id="isim" name="isim" required><br>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="konu">Konu:</label>
            <input type="text" id="konu" name="konu" required><br>
            <label for="mesaj">Mesajınız:</label>
            <textarea id="mesaj" name="mesaj" style="display: none;"></textarea>
            <div id="editor"></div>
            <input type="submit" name="Gonder" value="Gönder">
        </form>

        <form action="mesaj_sil.php" method="POST">
            <input type="submit" name="sil" class="btn btn-danger" value="MESAJLARIMI SİL">
        </form>
    </div>
</body>

</html>
<script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                height: 500
            })
            .then(editor => {
                editor.model.document.on('change', () => {
                    document.getElementById('mesaj').value = editor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>

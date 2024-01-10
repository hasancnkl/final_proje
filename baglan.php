<?php
    try {
        $db = New PDO('mysql:host=localhost; dbname=otopark_otomasyonu', 'root', '');
    } 
    catch (Exception $e) {
        $e -> getMessage();
    }
    ob_start();
    session_start();
?>
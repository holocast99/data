<?php

// database_v2.php'nin EN ÜSTÜNE ekle:
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");


// YENİ KONFİGÜRASYON (Değiştirilecek alanlar)
$servername = "sql303.infinityfree.com";
$username = "if0_38364589";
$password = "tuALe2wa8J0FwxX";
$dbname = "if0_38364589_gndgur";

// Bağlantıyı özelleştirilmiş hata mesajlarıyla oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Hata kontrolü (öncekiyle aynı yapı)
if ($conn->connect_error) {
    die("<div style='background:#ffebee; padding:15px; border-radius:8px; margin:20px;'>
        <h3 style='color:#d32f2f'>⚠️ Bağlantı Hatası!</h3>
        <p>Hata: ".$conn->connect_error."</p>
        <p>✅ Kontrol Listesi:</p>
        <ul>
            <li>Sunucu IP/FQDN</li>
            <li>Kullanıcı adı/şifre</li>
            <li>Firewall ayarları</li>
        </ul>
    </div>");
}

// Notes tablosu (öncekiyle aynı)
$conn->query("CREATE TABLE IF NOT EXISTS notes_v2 (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    groupName VARCHAR(100) NOT NULL,
    responsible VARCHAR(100) NOT NULL,
    projectName VARCHAR(255),
    topic VARCHAR(255),
    description TEXT,
    date VARCHAR(10) NOT NULL,
    changeDate VARCHAR(10),
    completed BOOLEAN DEFAULT 0
)");
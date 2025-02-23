<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "catdata";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตั้งค่าภาษาให้รองรับ UTF-8
$conn->set_charset("utf8mb4");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("❌ เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
} 
?>

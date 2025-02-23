<?php
include 'db.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM catbreeds WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // แสดงรายละเอียด
        echo "<h4>" . htmlspecialchars($row['name_th']) . " (" . htmlspecialchars($row['name_en']) . ")</h4>";
        echo "<p><strong>คำอธิบาย:</strong> " . htmlspecialchars($row['description']) . "</p>";

        // แสดงภาพ
        if ($row['image_url']) {
            echo "<p><strong>รูปภาพ:</strong><br>";
            echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='Image' class='img-fluid' width='300'></p>";
        } else {
            echo "<p><strong>รูปภาพ:</strong> ไม่มีภาพ</p>";
        }

        // สามารถเพิ่มข้อมูลอื่นๆ ตามต้องการ
    } else {
        echo "<p class='text-center text-danger'>❌ ไม่พบข้อมูลสายพันธุ์นี้</p>";
    }
}
?>

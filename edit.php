<?php
include 'db.php';

header('Content-Type: text/html; charset=utf-8');  // ตั้งค่า charset เป็น UTF-8

// รับ id จาก URL
$id = $_GET['id'];
$sql = "SELECT * FROM catbreeds WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// ตรวจสอบว่าเป็น POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_th = $_POST['name_th'];
    $name_en = $_POST['name_en'];
    $description = $_POST['description'];
    $characteristics = $_POST['characteristics'];
    $care_instructions = $_POST['care_instructions'];
    $image_url = $_POST['image_url'];
    $is_visible = isset($_POST['is_visible']) ? 1 : 0;

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE catbreeds SET 
            name_th='$name_th', 
            name_en='$name_en', 
            description='$description', 
            characteristics='$characteristics', 
            care_instructions='$care_instructions', 
            image_url='$image_url', 
            is_visible='$is_visible' 
            WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        // รีไดเรกต์กลับไปที่หน้า index
        header("Location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลสายพันธุ์แมว</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-4">✏️ แก้ไขข้อมูลสายพันธุ์แมว</h2>

    <form method="post">
        <!-- ชื่อสายพันธุ์ (ภาษาไทย) -->
        <div class="mb-3">
            <label for="name_th" class="form-label">ชื่อสายพันธุ์ (ภาษาไทย)</label>
            <input type="text" class="form-control" id="name_th" name="name_th" value="<?= $row['name_th']; ?>" required>
        </div>

        <!-- ชื่อสายพันธุ์ (ภาษาอังกฤษ) -->
        <div class="mb-3">
            <label for="name_en" class="form-label">ชื่อสายพันธุ์ (ภาษาอังกฤษ)</label>
            <input type="text" class="form-control" id="name_en" name="name_en" value="<?= $row['name_en']; ?>" required>
        </div>

        <!-- คำอธิบาย -->
        <div class="mb-3">
            <label for="description" class="form-label">คำอธิบาย</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?= $row['description']; ?></textarea>
        </div>

        <!-- ลักษณะสายพันธุ์ -->
        <div class="mb-3">
            <label for="characteristics" class="form-label">ลักษณะสายพันธุ์</label>
            <textarea class="form-control" id="characteristics" name="characteristics" rows="3"><?= $row['characteristics']; ?></textarea>
        </div>

        <!-- วิธีดูแล -->
        <div class="mb-3">
            <label for="care_instructions" class="form-label">วิธีดูแล</label>
            <textarea class="form-control" id="care_instructions" name="care_instructions" rows="3"><?= $row['care_instructions']; ?></textarea>
        </div>

        <!-- URL ของรูปภาพ -->
        <div class="mb-3">
            <label for="image_url" class="form-label">URL ของรูปภาพ</label>
            <input type="text" class="form-control" id="image_url" name="image_url" value="<?= $row['image_url']; ?>" />
        </div>

        <!-- ตัวเลือกการแสดงผล -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_visible" name="is_visible" <?= $row['is_visible'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_visible">แสดงผลข้อมูล</label>
        </div>

        <!-- ปุ่มบันทึกและย้อนกลับ -->
        <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-secondary">🔙 ย้อนกลับ</a>
            <button type="submit" class="btn btn-success">✅ บันทึก</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

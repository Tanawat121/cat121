<?php
include 'db.php';

// ตั้งค่าภาษาไทยสำหรับ MySQL
mysqli_set_charset($conn, "utf8mb4");

// กำหนด Content-Type ให้รองรับภาษาไทย
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $name_th = trim($_POST["name_th"]);
    $name_en = trim($_POST["name_en"]);
    $description = trim($_POST["description"]);
    $characteristics = trim($_POST["characteristics"]);
    $care_instructions = trim($_POST["care_instructions"]);
    $image_url = trim($_POST["image_url"]);
    $is_visible = isset($_POST["is_visible"]) ? 1 : 0;

    // ป้องกัน XSS
    $name_th = htmlspecialchars($name_th, ENT_QUOTES, 'UTF-8');
    $name_en = htmlspecialchars($name_en, ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
    $characteristics = htmlspecialchars($characteristics, ENT_QUOTES, 'UTF-8');
    $care_instructions = htmlspecialchars($care_instructions, ENT_QUOTES, 'UTF-8');
    $image_url = htmlspecialchars($image_url, ENT_QUOTES, 'UTF-8');

    // ตรวจสอบ URL รูปภาพ
    if (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
        $message = "❌ URL รูปภาพไม่ถูกต้อง กรุณาใส่ URL ที่ถูกต้อง";
        echo "<script>alert(" . json_encode($message, JSON_UNESCAPED_UNICODE) . "); window.history.back();</script>";
        exit;
    }

    // SQL Query
    $sql = "INSERT INTO catbreeds (name_th, name_en, description, characteristics, care_instructions, image_url, is_visible) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Prepare Error: " . $conn->error);
    }

    $stmt->bind_param("ssssssi", $name_th, $name_en, $description, $characteristics, $care_instructions, $image_url, $is_visible);

    if ($stmt->execute()) {
        header("Location: index.php?success=1");
        exit;
    } else {
        header("Location: index.php?error=" . urlencode($stmt->error));
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสายพันธุ์แมว</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-4">➕ เพิ่มสายพันธุ์แมว</h2>
    
    <div class="card shadow p-4">
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">ชื่อสายพันธุ์ (ภาษาไทย)</label>
                <input type="text" class="form-control" name="name_th" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ชื่อสายพันธุ์ (ภาษาอังกฤษ)</label>
                <input type="text" class="form-control" name="name_en" required>
            </div>
            <div class="mb-3">
                <label class="form-label">คำอธิบาย</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">ลักษณะทั่วไป</label>
                <textarea class="form-control" name="characteristics" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">คำแนะนำการเลี้ยงดู</label>
                <textarea class="form-control" name="care_instructions" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">URL รูปภาพ</label>
                <input type="text" class="form-control" name="image_url">
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_visible" checked>
                <label class="form-check-label">แสดงผลข้อมูล</label>
            </div>
            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">🔙 ย้อนกลับ</a>
                <button type="submit" class="btn btn-success">✅ บันทึก</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
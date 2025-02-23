<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // ดึงค่าปัจจุบันของ is_visible
    $query = "SELECT is_visible FROM catbreeds WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($is_visible);
    $stmt->fetch();
    $stmt->close();

    // สลับค่าระหว่าง 1 และ 0
    $new_visibility = $is_visible ? 0 : 1;

    // อัปเดตค่าในฐานข้อมูล
    $updateQuery = "UPDATE catbreeds SET is_visible = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ii", $new_visibility, $id);
    if ($updateStmt->execute()) {
        echo $new_visibility;
    } else {
        echo "error";
    }
    $updateStmt->close();
}
?>

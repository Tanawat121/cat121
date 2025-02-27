<?php
include 'db.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // ดึงค่าปัจจุบันของ visible
    $query = "SELECT visible FROM catbreeds WHERE id = $id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newStatus = $row['visible'] ? 0 : 1; // สลับค่าระหว่าง 1 และ 0

        // อัปเดตค่าลงฐานข้อมูล
        $updateQuery = "UPDATE catbreeds SET visible = $newStatus WHERE id = $id";
        if ($conn->query($updateQuery)) {
            echo $newStatus; // ส่งค่าใหม่กลับไป
        } else {
            echo "error";
        }
    }
}
?>

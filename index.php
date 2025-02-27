<?php
include 'db.php';
header('Content-Type: text/html; charset=utf-8');

$sql = "SELECT * FROM catbreeds WHERE visible = 1";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลแมว</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center text-success">🐱 แมวยอดนิยม</h2>
        <div class="d-flex justify-content-between mb-3">
            <a href="add.php" class="btn btn-success">➕ เพิ่มสายพันธุ์</a>
            <button id="resetVisibility" class="btn btn-secondary">🔄 แสดงทั้งหมด</button>
        </div>

        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>ชื่อไทย</th>
                            <th>ชื่ออังกฤษ</th>
                            <th>รูปภาพ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= htmlspecialchars($row['name_th']); ?></td>
                                <td><?= htmlspecialchars($row['name_en']); ?></td>
                                <td>
                                    <?php if ($row['image_url']) { ?>
                                        <img src="<?= htmlspecialchars($row['image_url']); ?>" width="100" height="100">
                                    <?php } else { ?>
                                        <p>ไม่พบรูปภาพ</p>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm view-details" data-id="<?= $row['id']; ?>">
                                    🔍 ดูรายละเอียด
                                    </button>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">✏️ แก้ไข</a>
                                    <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบ?');">🗑️ ลบ</a>
                                    <button class="btn toggle-visibility btn-sm <?= $row['visible'] ? 'btn-success' : 'btn-danger' ?>" data-id="<?= $row['id']; ?>">
                                        <?= $row['visible'] ? '✅ แสดง' : '❌ ซ่อน' ?>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">รายละเอียดสายพันธุ์</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="detailsContent">
                        <p class="text-center">⏳ กำลังโหลด...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $("#resetVisibility").click(function() {
            if (confirm("ยืนยันการแสดงสายพันธุ์ที่ถูกซ่อนทั้งหมด?")) {
                $.ajax({
                    url: "reset_visibility.php",
                    type: "POST",
                    success: function(response) {
                        if (response === "success") {
                            $(".toggle-visibility").removeClass("btn-danger").addClass("btn-success").text("✅ แสดง");
                            alert("✔️ สายพันธุ์ทั้งหมดถูกแสดงเรียบร้อย!");
                        } else {
                            alert("❌ เกิดข้อผิดพลาดในการอัปเดตข้อมูล");
                        }
                    },
                    error: function() {
                        alert("❌ เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์!");
                    }
                });
            }
        });

        $(".toggle-visibility").click(function() {
        let button = $(this);
        let catId = button.data("id");

        $.ajax({
            url: "toggle_visibility.php",
            type: "POST",
            data: { id: catId },
            success: function(response) {
                if (response === "1") {
                    button.removeClass("btn-danger").addClass("btn-success").text("✅ แสดง");
                } else if (response === "0") {
                    button.removeClass("btn-success").addClass("btn-danger").text("❌ ซ่อน");
                } else {
                    alert("เกิดข้อผิดพลาด: " + response);
                }
            },
            error: function() {
                alert("❌ เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์");
            }
        });
    });

    $(".view-details").click(function() {
            let catId = $(this).data("id");

            $("#detailsContent").html("<p class='text-center'>⏳ กำลังโหลด...</p>");

            $.ajax({
                url: "view_details.php",
                type: "POST",
                data: { id: catId },
                success: function(response) {
                    // แสดงรายละเอียดและรูปภาพใน modal
                    $("#detailsContent").html(response);
                    $("#detailsModal").modal("show");
                },
                error: function() {
                    $("#detailsContent").html("<p class='text-center text-danger'>❌ โหลดข้อมูลไม่สำเร็จ</p>");
                }
            });
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

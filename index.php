<?php
include 'db.php';
header('Content-Type: text/html; charset=utf-8');

$sql = "SELECT * FROM catbreeds";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🐱 จัดการข้อมูลแมวยอดนิยม</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* 🌟 พื้นหลัง Gradient + ฟ้อนต์หรูหรา */
        body {
            background: linear-gradient(135deg, #d4fc79, #96e6a1);
            font-family: 'Poppins', sans-serif;
        }

        /* 🏷️ Card สวยๆ */
        .card {
            border-radius: 15px;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.2);
        }

        /* 🐾 ตารางดูนุ่มขึ้น */
        .table-hover tbody tr:hover {
            background: #e2f7c6;
            transition: 0.3s;
        }

        /* 📸 รูปแมว */
        .cat-img {
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
        }
        .cat-img:hover {
            transform: scale(1.1);
        }

        /* 🎭 ปุ่มดูแพงขึ้น */
        .btn {
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }

        /* 🔮 Modal ให้ดูเป็นมิติ */
        .modal-content {
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* ✨ เพิ่มไอคอนให้มี animation */
        .bi {
            transition: 0.3s;
        }
        .bi:hover {
            transform: rotate(10deg);
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center text-white fw-bold mb-4">🐱 แมวยอดนิยม</h2>

        <div class="d-flex justify-content-end mb-3">
            <a href="add.php" class="btn btn-success shadow"><i class="bi bi-plus-circle"></i> เพิ่มสายพันธุ์</a>
        </div>

        <div class="card p-4 shadow-lg">
            <div class="card-body">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-success text-dark">
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
                                <td class="fw-bold"><?= htmlspecialchars($row['name_th']); ?></td>
                                <td><?= htmlspecialchars($row['name_en']); ?></td>
                                <td>
                                    <?php if ($row['image_url']) { ?>
                                        <img src="<?= htmlspecialchars($row['image_url']); ?>" class="cat-img" width="100" height="100">
                                    <?php } else { ?>
                                        <p class="text-muted">ไม่พบรูปภาพ</p>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm view-details" data-id="<?= $row['id']; ?>">
                                        <i class="fa-solid fa-eye"></i> ดู
                                    </button>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fa-solid fa-pen"></i> แก้ไข
                                    </a>
                                    <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบสายพันธุ์นี้?');">
                                        <i class="fa-solid fa-trash"></i> ลบ
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 🖼️ Modal แสดงรายละเอียด -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="detailsModalLabel">รายละเอียดสายพันธุ์</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="detailsContent" class="text-center">
                        <p class="text-secondary">⏳ กำลังโหลด...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $(".view-details").click(function() {
            let catId = $(this).data("id");
            $("#detailsContent").html("<p class='text-secondary'>⏳ กำลังโหลด...</p>");

            $.ajax({
                url: "view_details.php",
                type: "POST",
                data: { id: catId },
                success: function(response) {
                    $("#detailsContent").html(response);
                    $("#detailsModal").modal("show");
                },
                error: function() {
                    $("#detailsContent").html("<p class='text-danger'>❌ โหลดข้อมูลไม่สำเร็จ</p>");
                }
            });
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
include 'db.php';

$sql = "UPDATE catbreeds SET visible = 1";

if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "error";
}

$conn->close();
?>

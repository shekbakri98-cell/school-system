<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
}
header("Location: index.php");
?>
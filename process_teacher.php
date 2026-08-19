<?php
include 'db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO teachers (full_name, gender, religion, phone, guardian_contact, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $_POST['teacher_name'], $_POST['gender'], $_POST['religion'], $_POST['phone'], $_POST['guardian_contact'], $_POST['address']);
    $stmt->execute();
    echo "<script>alert('Milkiidhaan galmeeffameera!'); window.location.href='index.php';</script>";
}
?>
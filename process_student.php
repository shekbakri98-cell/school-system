<?php
include 'db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO students (class_name, section_name, roll_no, full_name, gender, religion, father_contact, mother_contact, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $_POST['class'], $_POST['section'], $_POST['roll_no'], $_POST['name'], $_POST['gender'], $_POST['religion'], $_POST['father_contact'], $_POST['mother_contact'], $_POST['address']);
    $stmt->execute();
    echo "<script>alert('Milkiidhaan galmeeffameera!'); window.location.href='index.php';</script>";
}
?>

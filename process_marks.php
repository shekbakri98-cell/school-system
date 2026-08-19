<?php
include 'db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $written = intval($_POST['written']); $mcq = intval($_POST['mcq']); $sba = intval($_POST['sba']);
    $total = $written + $mcq + $sba;
    if ($total >= 80) $grade = 'A'; elseif ($total >= 60) $grade = 'B'; elseif ($total >= 45) $grade = 'C'; else $grade = 'F';

    $stmt = $conn->prepare("INSERT INTO student_marks (class_name, exam_name, student_name, written_score, mcq_score, sba_score, total_score, grade) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssgiiiis", $_POST['class'], $_POST['exam'], $_POST['student_name'], $written, $mcq, $sba, $total, $grade);
    $stmt->execute();
    echo "<script>alert('Milkiidhaan galmeeffameera!'); window.location.href='index.php';</script>";
}
?>
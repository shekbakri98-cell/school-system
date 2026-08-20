
?>
<?php
$servername = "mysql-anewar.alwaysdata.net"; 
$username = "anewar_admin"; 
$password = "015661Emran@";      
$dbname = "anewar_school_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kuusaa odeeffannoo waliin walitti hidhuun hin danda'amne: " . $conn->connect_error);
}

$msg = "";
$msg_type = "";
$page = isset($_GET['page']) ? $_GET['page'] : 'student_form';
?>

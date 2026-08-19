<?php
// Odeeffannoo sarvarii Alwaysdata keetii isa sirrii
$servername = "mysql-anewar.alwaysdata.net"; 
$username = "anewar_admin"; // User yommuu uumtu 'anewar_' itti dabalama
$password = "015661Emran@";      // Iccitii USERS irratti uumte galchi
$dbname = "anewar_school_db"; // Maqaa database keetii guutuu

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kuusaa odeeffannoo waliin walitti hidhuun hin danda'amne: " . $conn->connect_error);
}
?>
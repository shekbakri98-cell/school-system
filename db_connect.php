<?php
$servername = "mysql-anewar.alwaysdata.net"; 
$username = "anewar_admin"; 
$password = "015661Emran@";      
$dbname = "anewar_school_db"; 

// Walitti hidhamiinsa uumuu
$conn = new mysqli($servername, $username, $password, $dbname);

// Walitti hidhamuu isaa mirkaneessuu
if ($conn->connect_error) {
    die("Kuusaa odeeffannoo waliin walitti hidhuun hin danda'amne: " . $conn->connect_error);
}
echo "Kuusaa odeeffannoo (Database) waliin milkiidhaan walitti hidhameera!";
?>

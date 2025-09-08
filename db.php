<?php 

$host = "localhost";
$user = "root";
$pass = "";
$db = "360";

$conn = new mysqli($host, $user, $pass, $db);

if(!$conn) {
    echo "Error: " . $conn->error;          
}
?>
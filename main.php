<?php

$user_id = $user['user_id'];
$sql = "SELECT * FROM users WHERE user_id = $user_id"; 
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);

switch($row['role']) {
    case 'admin':
        $file = 'admin/home.php';
        break;
    case 'manager':
        $file = 'manager/home.php';
        break;
    case 'employee':
        $file = 'employee/home.php';
        break;
    case 'ceo':
        $file = 'ceo/home.php';
        break;
    default:
        echo "Role not found";
        break;
}

include "pages/$file";



?>
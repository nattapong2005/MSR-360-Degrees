<?php

include 'db.php';
include 'css.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}


$user = $_SESSION['user'];
$user_id = $user['user_id'];
$sql = "SELECT users.name,departments.department_name, users.role FROM users JOIN departments ON users.department_id = departments.department_id WHERE users.user_id = " . $user_id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
// 

switch ($row['role']) {
    case 'admin':
        $user_role = "ผู้ดูแลระบบ";
        break;
    case 'manager':
        $user_role = "หัวหน้าแผนก";
        break;
    case 'employee':
        $user_role = "พนักงาน";
        break;
    case 'ceo':
        $user_role = "ผู้บริหาร";
        break;
    default:
        echo "Role not found";
        break;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSR 360 Degrees</title>
</head>

<body>

    <div class="flex">
        <?php include 'components/sidebar.php'; ?>
        <main class="flex-grow p-6">
        <div class=" mx-auto mt-2">
            <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = "";
            }

            switch ($page) {
                // Evalute type
                case 'employee_type':
                    include 'pages/employee/evaluate_type.php';
                    break;
                case 'manager_type':
                    include 'pages/manager/evaluate_type.php';
                    break;
                case 'ceo_type':
                    include 'pages/ceo/evaluate_type.php';
                    break;
                // ---------
                case 'peer_evaluate':
                    include 'evaluation_type/peer_evaluate.php';
                    break;
                case 'cross_evaluate':
                    include 'evaluation_type/cross_evaluate.php';
                    break;
                case 'manager_evaluate':
                    include 'evaluation_type/manager_evaluate.php';
                    break;
                case 'self_evaluate':
                    include 'evaluation_type/self_evaluate.php';
                    break;
                case 'ceo_evaluate':
                    include 'evaluation_type/ceo_evaluate.php';
                    break;
                case 'evaluate_type':
                    include 'pages/employee/evaluate_type.php';
                    break;
                // Form Pages
                case 'manager_form_evaluate':
                    include 'evaluation_type/form/manager.php';
                    break;
                case 'cross_form_evaluate':
                    include 'evaluation_type/form/cross.php';
                    break;
                case 'peer_form_evaluate':
                    include 'evaluation_type/form/peer.php';
                    break;
                case 'department_form':
                    include 'evaluation_type/form/department_form/form.php';
                    break;
                // ----
                case 'account':
                    include 'account.php';
                    break;
                case 'ceo_dashboard':
                    include 'pages/ceo/dashboard.php';
                    break;
                // Dashboard kub
                case 'manager_dashboard':
                    include 'pages/dashboard/manager.php';
                    break;
                case 'self_dashboard':
                    include 'pages/dashboard/self.php';
                    break;
                case 'peer_dashboard':
                    include 'pages/dashboard/peer.php';
                    break;
                case 'cross_dashboard':
                    include 'pages/dashboard/cross.php';
                    break;
                case 'dashboard':
                    include 'pages/admin/dashboard.php';
                    break;
                default:
                    include 'main.php';
                    break;
            }
            ?>
        </div>
    </main>
    </div>


</body>

</html>
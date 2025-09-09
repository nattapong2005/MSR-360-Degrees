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

    <section class="max-w-7xl mx-auto ">
        <div class="bg-[#320A6B] rounded-b-4xl">
            <div class="container mx-auto p-8 flex justify-between items-center ">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">ระบบประเมินพนักงาน 360 องศา</h1>
                    <h1 class="text-xl">สวัสดีคุณ, <?= $row['name'] ?> ตำแหน่ง <?= $user_role ?> แผนก <?= $row['department_name'] ?></h1>
                </div>
                <div>
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="logout.php" class="text-white hover:bg-blue-800 px-3 py-2 rounded bg-[#320A6B]">ออกจากระบบ</a>
                    <?php else: ?>
                        <a href="login.php" class="text-white hover:bg-blue-800 px-3 py-2 rounded">เข้าสู่ระบบ</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto mt-10">
        <div class="bg-white p-6 shadow-md">
            <div class="flex justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-4">ยินดีต้อนรับสู่ระบบประเมินพนักงาน 360 องศา</h2>
                    <p class="text-gray-700 mb-4">ระบบนี้ช่วยให้คุณสามารถประเมินพนักงานในองค์กรของคุณได้อย่างมีประสิทธิภาพและครอบคลุมทุกมุมมอง</p>
                    <ul class="list-disc list-inside text-gray-700">
                        <li>ประเมินจากผู้จัดการ</li>
                        <li>ประเมินจากเพื่อนร่วมงาน</li>
                        <li>ประเมินจากตัวเอง</li>
                        <li>และอื่นๆ</li>
                    </ul>
                </div>
                <div>
                    <a class="bg-[#320A6B] text-white hover:bg-blue-800 px-3 py-2 rounded" href="javascript:history.back()">ย้อนกลับ</a>
                </div>
            </div>
        </div>

    </section>

    <div class="max-w-7xl mx-auto mt-2">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = "";
        }

        switch ($page) {
            case 'test':
                include 'test.php';
                break;
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
            case 'evaluate_type':
                include 'pages/employee/evaluate_type.php';
                break;
                // Form Pages
            case 'manager_form_evaluate':
                include 'evaluation_type/form/manager.php';
                break;
                // ----
            case 'profile':
                include 'profile.php';
                break;
            case 'ceo':
                include 'ceo/home.php';
                break;
            default:
                include 'main.php';
                break;
        }
        ?>
    </div>



</body>

</html>
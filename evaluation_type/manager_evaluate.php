<?php


// -- น่าจะไม่ใช้ละ --
// $sql = "SELECT
//     manager.user_id, manager.name FROM users AS employee JOIN users AS manager ON employee.manager_id = manager.user_id WHERE employee.user_id = $user_id";
// $query = mysqli_query($conn, $sql);
$period_id = $_SESSION['period_id'];

// เช็คว่าคุณประเมินหัวหน้าไปแล้วหรือยัง
$sqlCheck = "SELECT manager.user_id,manager.name,ev.status,departments.department_name FROM users AS employee 
JOIN users AS manager ON employee.manager_id = manager.user_id 
JOIN departments ON manager.department_id = departments.department_id
LEFT JOIN evaluations AS ev ON ev.subject_id = manager.user_id AND ev.evaluator_id = employee.user_id 
AND ev.period_id = $period_id WHERE employee.user_id = $user_id";

$queryCheck = mysqli_query($conn, $sqlCheck);
// $rowCheck = mysqli_fetch_assoc($queryCheck);


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $manager_id = $_POST['manager_id'];
//     $_SESSION['manager_id'] = $manager_id;
//     header('location: ?page=manager_form_evaluate');
// }

?>

<section class="p-6">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-3xl font-extrabold text-gray-800  flex items-center gap-3">
            <i class="fas fa-user-tie"></i> ประเมินหัวหน้าแผนก
        </h1>
        <a class="bg-red-700 text-white hover:bg-red-800 px-3 py-2 rounded" href="javascript:history.back()"><i class="fa-solid fa-backward"></i> ย้อนกลับ</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border border-gray-300 shadow-lg rounded-lg">
            <thead class="bg-red-700 text-white">
                <tr>
                    <th class="py-3 px-4 border-b border-gray-300"><i class="fas fa-user"></i> ชื่อ-สกุล</th>
                    <th class="py-3 px-4 border-b border-gray-300"><i class="fas fa-building"></i> แผนก</th>
                    <th class="py-3 px-4 border-b border-gray-300"><i class="fas fa-info-circle"></i> สถานะ</th>
                    <th class="py-3 px-4 border-b border-gray-300"><i class="fas fa-tasks"></i> การดำเนินการ</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <?php

                while ($rowCheck = mysqli_fetch_assoc($queryCheck)) {

                    // $_SESSION['manager_id'] = $row['user_id'];
                ?>
                    <tr class="hover:bg-slate-100 transition">

                        <td class="py-2 px-4 border-b border-slate-200"><?php echo htmlspecialchars($rowCheck['name']); ?></td>
                        <td class="py-2 px-4 border-b border-slate-200"><?php echo htmlspecialchars($rowCheck['department_name']); ?></td>
                        <td class="py-3 px-4 border-b border-slate-200">
                            <?php if ($rowCheck['status'] == 'completed'): ?>
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <i class="fas fa-check-circle"></i> ประเมินแล้ว
                                </span>
                            <?php else: ?>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <i class="fas fa-exclamation-circle"></i> ยังไม่ได้ประเมิน
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?page=manager_form_evaluate&manager_id=<?php echo $rowCheck['user_id']; ?>" class="bg-black text-sm text-white font-semibold px-2.5 py-1 rounded-full cursor-pointer">ประเมิน</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>
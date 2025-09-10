<?php

$period_id = $_SESSION['period_id'];
$department_id = $_GET['department_id'];

// คนในแผนกที่ส่งมาจาก cross_evaluate.php
$sql = "SELECT * FROM users WHERE department_id = $department_id AND user_id != $user_id";
$query = mysqli_query($conn, $sql);


// เช็คว่าคุณประเมินไปแล้วหรือยัง
$sqlCheck = "SELECT u.user_id, u.name, ev.status FROM users AS u LEFT JOIN evaluations AS ev ON u.user_id = ev.subject_id AND ev.evaluator_id = $user_id AND ev.period_id = $period_id WHERE u.department_id = $department_id AND u.user_id != $user_id";
$queryCheck = mysqli_query($conn, $sqlCheck);
$rowCheck = mysqli_fetch_assoc($queryCheck);

$sqlDept = "SELECT * FROM departments WHERE department_id = $department_id";
$queryDept = mysqli_query($conn, $sqlDept);
$rowDept = mysqli_fetch_assoc($queryDept);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    // $_SESSION['manager_id'] = $manager_id;
    header('location: ?page=department_form&department_id=' . $department_id . '&subject_id=' . $subject_id);
}

?>

<section class="">
    <h1 class="text-3xl font-bold mb-2">แผนก <?= $rowDept['department_name'] ?></h1>
    <div class="overflow-x-auto">
        <table class="w-full text-left border border-gray-300 shadow-lg rounded-lg">
            <thead class="bg-[#320A6B] text-white">
                <tr>
                    <th class="py-3 px-4 border-b border-slate-300">ชื่อ-สกุล</th>
                    <th class="py-3 px-4 border-b border-slate-300">สถานะ</th>
                    <th class="py-3 px-4 border-b border-slate-300">การดำเนินการ</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <?php
                while ($row = mysqli_fetch_assoc($query)) {
                    // $_SESSION['manager_id'] = $row['user_id'];
                ?>
                    <tr class="hover:bg-slate-100 transition">
                        <td class="py-2 px-4 border-b border-slate-200"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td class="py-3 px-4 border-b border-slate-200">
                            <?php if ($rowCheck['status'] == 'completed'): ?>
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    ประเมินแล้ว
                                </span>
                            <?php else: ?>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    ยังไม่ได้ประเมิน
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="subject_id" value="<?php echo $row['user_id']; ?>">
                                <button type="submit" class="bg-[#320A6B] text-sm text-white font-semibold px-2.5 py-1 rounded-full cursor-pointer">ประเมิน</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>
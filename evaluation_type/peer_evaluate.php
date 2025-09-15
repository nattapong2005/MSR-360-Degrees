<?php

$period_id = $_SESSION['period_id'];
$department_id = $user['department_id'];
$manager_id = $user['manager_id'];

// $sqlUser = "SELECT * FROM users WHERE  department_id = $department_id AND user_id != $user_id AND user_id != $manager_id";
// $queryUser = mysqli_query($conn, $sqlUser);

// เช็คว่าคุณประเมินคนนี้ไปแล้วหรือยัง
$sqlCheck = "SELECT peer.user_id,peer.name,ev.status,d.department_name FROM users as self  
JOIN users as peer ON self.department_id = peer.department_id 
AND self.user_id != peer.user_id AND peer.user_id != self.manager_id 
JOIN departments AS d ON peer.department_id = d.department_id
LEFT JOIN evaluations as ev ON ev.subject_id = peer.user_id 
AND ev.evaluator_id = self.user_id AND ev.period_id = $period_id WHERE self.user_id = $user_id";

$queryCheck = mysqli_query($conn, $sqlCheck);
// $rowCheck = mysqli_fetch_assoc($queryCheck);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    // $_SESSION['manager_id'] = $manager_id;
    header('location: ?page=manager_form_evaluate');
}
?>


<section class="p-6">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-3xl font-extrabold text-gray-800  flex items-center gap-3">
            <i class="fas fa-users"></i> ประเมินในแผนก
        </h1>
        <a class="bg-[#16213E]/90 text-white hover:bg-red-800 px-3 py-2 rounded" href="javascript:history.back()"><i class="fa-solid fa-backward"></i> ย้อนกลับ</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border border-gray-300 shadow-lg rounded-lg">
            <thead class="bg-[#16213E] text-white">
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
                            <a href="?page=peer_form_evaluate&subject_id=<?php echo $rowCheck['user_id']; ?>" class="bg-[#16213E] hover:bg-red-700 text-sm text-white font-semibold px-2.5 py-1 rounded-full cursor-pointer"><i class="fas fa-pen-to-square"></i> ประเมิน</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>
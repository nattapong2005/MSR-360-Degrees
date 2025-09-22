<?php
$department_id = $user['department_id'];

// $sql = "SELECT d.department_id, d.department_name FROM departments d 
// JOIN manager_responsibilities mr ON d.department_id = mr.department_id WHERE mr.user_id = $user_id";

// ตรวจสอบว่าเราประเมินแผนกไหนบ้าง
$sql = "SELECT d.icon,d.color,d.department_id,d.department_name FROM departments d 
JOIN department_rules dr ON d.department_id = dr.subject_department_id 
WHERE dr.evaluator_department_id = $department_id AND d.department_id != $department_id";

$query = mysqli_query($conn, $sql);



?>

<section class="px-4 py-6 min-h-screen">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-3">
                <i class="fas fa-people-arrows"></i> ประเมินต่างแผนก
            </h1>
            <p class="text-gray-600 mt-1 flex items-center gap-2">
                แผนกที่คุณสามารถประเมินได้ดังนี้
            </p>
        </div>
        <div>
            <a class="bg-[#16213E]/90 text-white hover:bg-red-800 px-3 py-2 rounded" href="javascript:history.back()"><i class="fa-solid fa-backward"></i> ย้อนกลับ</a>

        </div>
    </div>
    <!-- รายการแผนก -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <a href="?page=cross_form_evaluate&department_id=<?= $row['department_id'] ?>"
                class="flex flex-col items-center justify-center text-center gap-3 w-full font-semibold bg-white p-5 rounded-lg shadow-md border border-gray-300 hover:border-red-700 group">
                <div class="w-16 h-16 text-[<?php echo $row['color'] ?>] rounded-full flex items-center justify-center text-2xl mb-4  transition">
                    <i class="<?php echo $row['icon'] ?> text-5xl"></i>
                </div>
                <span class="text-lg">แผนก <?= htmlspecialchars($row['department_name']) ?></span>
            </a>
        <?php } ?>
    </div>
</section>
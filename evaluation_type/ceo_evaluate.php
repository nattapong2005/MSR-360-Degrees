<?php 

$period_id = $_SESSION['period_id'];
$sql = "SELECT manager.user_id,manager.name,d.department_name,ev.status FROM users AS manager JOIN departments as d ON manager.department_id = d.department_id
LEFT JOIN evaluations AS ev ON ev.subject_id = manager.user_id AND ev.evaluator_id = $user_id AND ev.period_id = $period_id
WHERE manager.role = 'manager'";
$query = mysqli_query($conn, $sql)


?>


<section class="p-6">
    <h1 class="text-3xl font-bold mb-2">ประเมินหัวหน้าแผนก</h1>
    <div class="overflow-x-auto">
        <table class="w-full text-left border border-gray-300 shadow-lg rounded-lg">
            <thead class="bg-[#320A6B] text-white">
                <tr>
                    <th class="py-3 px-4 border-b border-slate-300">ชื่อ-สกุล</th>
                    <th class="py-3 px-4 border-b border-slate-300">แผนก</th>
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
                        <td class="py-2 px-4 border-b border-slate-200"><?php echo htmlspecialchars($row['department_name']); ?></td>
                        <td class="py-3 px-4 border-b border-slate-200">
                            <?php if ($row['status'] == 'completed'): ?>
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
                            <a href="?page=manager_form_evaluate&manager_id=<?php echo $row['user_id']; ?>" class="bg-[#320A6B] text-sm text-white font-semibold px-2.5 py-1 rounded-full cursor-pointer">ประเมิน</a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>
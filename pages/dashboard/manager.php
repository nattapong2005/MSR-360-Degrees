<?php

$role = $user['role'];

$period_id = $_SESSION['period_id'];

// รับค่าจาก form ถ้ามี
$filter_department = isset($_GET['department']) ? $_GET['department'] : '';

// ดึงรายชื่อแผนกทั้งหมด สำหรับ dropdown
$dept_query = "SELECT department_id, department_name FROM departments";
$dept_result = mysqli_query($conn, $dept_query);


// --- 1. การตั้งค่า Pagination ---
$records_per_page = 15; // กำหนดจำนวนรายการต่อหน้า
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($current_page - 1) * $records_per_page;


// --- 2. สร้างส่วน FROM และ WHERE กลางเพื่อใช้ซ้ำ ---
$base_sql = "FROM answers AS ans
    JOIN evaluations AS ev ON ans.evaluation_id = ev.evaluation_id
    JOIN users AS subject ON ev.subject_id = subject.user_id
    JOIN users AS evaluator ON ev.evaluator_id = evaluator.user_id
    JOIN departments AS d_subject ON subject.department_id = d_subject.department_id
    JOIN departments AS d_evaluator ON evaluator.department_id = d_evaluator.department_id
    WHERE subject.role = 'manager' AND ev.period_id = " . (int)$period_id;

// เพิ่ม filter ตามแผนกถ้าเลือกมา (ใช้ prepared statement เพื่อความปลอดภัย)
$params = [];
$types = '';
if (!empty($filter_department)) {
    $base_sql .= " AND subject.department_id = ?";
    $params[] = $filter_department;
    $types .= 'i';
}


// --- 3. นับจำนวนข้อมูลทั้งหมด (สำหรับคำนวณหน้า) ---
$count_sql = "SELECT COUNT(*) AS total " . $base_sql;
$stmt_count = mysqli_prepare($conn, $count_sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt_count, $types, ...$params);
}
mysqli_stmt_execute($stmt_count);
$count_result = mysqli_stmt_get_result($stmt_count);
$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $records_per_page);


// --- 4. ดึงข้อมูลสำหรับหน้าปัจจุบัน (เพิ่ม LIMIT และ OFFSET) ---
$data_sql = "SELECT subject.name AS subject_name, d_subject.department_name AS subject_department, evaluator.name AS evaluator_name,
    d_evaluator.department_name AS evaluator_department, ans.score AS score, ans.comment AS comment "
    . $base_sql
    . " ORDER BY subject.name ASC, evaluator.name ASC"
    . " LIMIT ? OFFSET ?";

$stmt_data = mysqli_prepare($conn, $data_sql);
$params[] = $records_per_page;
$params[] = $offset;
$types .= 'ii';
mysqli_stmt_bind_param($stmt_data, $types, ...$params);
mysqli_stmt_execute($stmt_data);
$result = mysqli_stmt_get_result($stmt_data);

?>

<div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-5">
        <h2 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-chart-simple"></i> รายงานผลการประเมินผลหัวหน้าแผนก</h2>
        <a class="bg-[#16213E]/90 text-white hover:bg-red-800 px-3 py-2 rounded" href="javascript:history.back()"><i class="fa-solid fa-backward"></i> ย้อนกลับ</a>
    </div>

    <form method="GET" class="mb-6 flex items-center gap-4">
        <input type="hidden" name="page" value="manager_dashboard">
        <!-- <label for="department" class="text-gray-700 font-medium">ค้นหาตามแผนก:</label> -->
        <select name="department" id="department" class="border rounded px-3 py-2">
            <option value="">-- ทุกแผนก --</option>
            <?php mysqli_data_seek($dept_result, 0); // รีเซ็ต pointer ของ result set 
            ?>
            <?php while ($dept = mysqli_fetch_assoc($dept_result)) : ?>
                <option value="<?= $dept['department_id'] ?>" <?= ($filter_department == $dept['department_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dept['department_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="bg-[#16213E] text-white px-4 py-2 rounded hover:bg-blue-800">ค้นหา</button>
        <a href="index.php?page=manager_dashboard" class="text-sm text-gray-500 hover:underline ml-2">รีเซ็ต</a>
    </form>

    <?php if ($total_records > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead class="bg-[#16213E] text-white">
                    <tr>
                        <th class="p-2 text-left text-sm">ชื่อหัวหน้าแผนก</th>
                        <th class="p-2 text-left text-sm">ผู้ประเมิน</th>
                        <th class="p-2 text-left text-sm">คะแนน</th>
                        <th class="p-2 text-left text-sm">ความคิดเห็น</th>
                        <?php if ($role == "admin"): ?>
                            <th class="p-2 text-left text-sm">จัดการ</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr class="hover:bg-gray-100">
                            <td class="p-2"><?= htmlspecialchars($row['subject_name']) . " (" . htmlspecialchars($row['subject_department']) . ")" ?></td>
                            <td class="p-2"><?= htmlspecialchars($row['evaluator_name']) . " (" . htmlspecialchars($row['evaluator_department']) . ")" ?></td>
                            <td class="p-2 font-bold"><?= htmlspecialchars($row['score']) ?></td>
                            <td class="p-2 w-[622px]"><?= nl2br(htmlspecialchars($row['comment'])) ?></td>
                            <?php if ($role == "admin"): ?>
                                <td class="p-2"><a href="#" class="text-red-600 hover:underline">ลบ</a></td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center items-center">
            <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php
                    // สร้างลิงก์โดยยังคงค่า filter เดิมไว้
                    $link = "?page=manager_dashboard&p=$i";
                    if (!empty($filter_department)) {
                        $link .= "&department=$filter_department";
                    }
                    $is_active = ($i == $current_page);
                    // กำหนด class ตามสถานะของหน้า
                    $class = $is_active
                        ? 'z-10 bg-[#16213E] text-white'
                        : 'bg-white text-gray-500 hover:bg-gray-50';
                    ?>
                    <a href="<?= $link ?>" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium <?= $class ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </nav>
        </div>

    <?php else: ?>
        <p class="text-red-600 font-medium">ไม่พบข้อมูล</p>
    <?php endif; ?>
</div>
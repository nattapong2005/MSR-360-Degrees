<?php
$sql_departments = "SELECT * FROM departments ORDER BY department_name ASC";
$query_departments = mysqli_query($conn, $sql_departments);

$search_condition = "";
$selected_department_id = '';
if (isset($_GET['department_id']) && !empty($_GET['department_id'])) {
    $selected_department_id = mysqli_real_escape_string($conn, $_GET['department_id']);
    $search_condition = "WHERE users.department_id = '{$selected_department_id}'";
}

// ===== 1. ส่วนตั้งค่า Pagination ที่เพิ่มเข้ามา =====
$results_per_page = 10; // กำหนดจำนวนรายการที่ต้องการแสดงต่อหน้า

// คำนวณหาจำนวนข้อมูลทั้งหมดที่ตรงตามเงื่อนไขการค้นหา
$sql_count_total = "SELECT COUNT(users.user_id) AS total 
                    FROM users 
                    JOIN departments ON users.department_id = departments.department_id 
                    {$search_condition}";

$query_count_total = mysqli_query($conn, $sql_count_total);
$total_results_row = mysqli_fetch_assoc($query_count_total);
$total_results = $total_results_row['total'];

// คำนวณจำนวนหน้าทั้งหมด
$total_pages = ceil($total_results / $results_per_page);

// หาหน้าปัจจุบันที่กำลังแสดงผล (ถ้าไม่มีการส่งค่ามา ให้เป็นหน้า 1)
$current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($current_page < 1) {
    $current_page = 1;
} elseif ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
}

// คำนวณหาลำดับเริ่มต้นของข้อมูลในหน้าปัจจุบันสำหรับ SQL LIMIT
$start_limit_from = ($current_page - 1) * $results_per_page;
// ===== จบส่วนตั้งค่า Pagination =====


// 3. ปรับ SQL หลักให้รองรับการค้นหาและ Pagination
$sql = "SELECT users.user_id,users.name, users.email, users.role, users.created_at, departments.department_name 
        FROM users
        JOIN departments ON users.department_id = departments.department_id
        {$search_condition} -- นำเงื่อนไขการค้นหามาต่อ
        ORDER BY
        CASE 
            WHEN users.role = 'ceo' THEN 1 
            WHEN users.role = 'manager' THEN 2 
            WHEN users.role = 'admin' THEN 3 
            ELSE 4 
        END
        LIMIT {$start_limit_from}, {$results_per_page}"; // เพิ่ม LIMIT เพื่อแบ่งหน้า
$query = mysqli_query($conn, $sql);
?>

<section class="p-4 sm:p-6 lg:p-8 bg-gray-50 min-h-screen">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3 sm:mb-0">
            <i class="fa-solid fa-users"></i>
            จัดการข้อมูลพนักงาน
        </h1>
        <a href="?page=create_user" class="cursor-pointer bg-[#16213E] hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md ">
            <i class="fa-solid fa-plus mr-2"></i>เพิ่มพนักงาน
        </a>
    </div>

    <div class=" p-3 mb-3">
        <form method="GET" action="" class="flex flex-col sm:flex-row items-center gap-4">
            <input type="hidden" name="page" value="user_manage">
            <div class="w-full sm:w-auto">
                <select name="department_id" id="department_id" class="border rounded px-3 py-2">
                    <option value="">-- แสดงทุกแผนก --</option>
                    <?php
                    mysqli_data_seek($query_departments, 0);
                    while ($dept = mysqli_fetch_assoc($query_departments)) {
                        $selected = ($dept['department_id'] == $selected_department_id) ? 'selected' : '';
                        echo "<option value='{$dept['department_id']}' {$selected}>" . htmlspecialchars($dept['department_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="w-full sm:w-auto mt-2 sm:mt-0 sm:self-end">
                <button type="submit" class="w-full sm:w-auto bg-[#16213E] cursor-pointer text-sm hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    <i class="fa-solid fa-search mr-2"></i>ค้นหา
                </button>
            </div>
        </form>
    </div>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#16213E] text-white">
                    <tr>
                        <th class="p-4 font-semibold text-center">#</th>
                        <th class="p-4 font-semibold"><i class="fa-solid fa-file-signature"></i> ชื่อ-สกุล</th>
                        <th class="p-4 font-semibold"><i class="fa-solid fa-building"></i> แผนก</th>
                        <th class="p-4 font-semibold text-center"><i class="fa-brands fa-web-awesome"></i> ตำแหน่ง</th>
                        <th class="p-4 font-semibold text-center"><i class="fa-solid fa-gear"></i> ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                    // ===== 2. แก้ไขตัวนับลำดับ =====
                    $count = $start_limit_from; // ให้ตัวนับเริ่มต้นตามลำดับของหน้านั้นๆ
                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                            $count++;
                            $user_role = '';
                            $role_class = '';

                            switch ($row['role']) {
                                case 'admin':
                                    $user_role = "ผู้ดูแลระบบ";
                                    $role_class = "bg-red-100 text-red-800";
                                    break;
                                case 'manager':
                                    $user_role = "หัวหน้าแผนก";
                                    $role_class = "bg-yellow-100 text-yellow-800";
                                    break;
                                case 'employee':
                                    $user_role = "พนักงาน";
                                    $role_class = "bg-green-100 text-green-800";
                                    break;
                                case 'ceo':
                                    $user_role = "ผู้บริหาร";
                                    $role_class = "bg-indigo-100 text-indigo-800";
                                    break;
                                default:
                                    $user_role = "ไม่ระบุ";
                                    $role_class = "bg-gray-100 text-gray-800";
                                    break;
                            }
                    ?>
                            <tr class="hover:bg-gray-100 transition-colors duration-200">
                                <td class="p-4 text-center font-medium text-gray-500"><?= $count; ?></td>
                                <td class="p-4">
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($row['name']); ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($row['email']); ?></div>
                                </td>
                                <td class="p-4 text-gray-700"><?= htmlspecialchars($row['department_name']); ?></td>
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full <?= $role_class; ?>"><?= $user_role; ?></span>
                                </td>
                                <td class="p-4 text-center space-x-2">
                                    <a href="?page=edit_user&user_id=<?= $row['user_id']; ?>" class="text-yellow-500 hover:text-yellow-700 transition-colors" title="แก้ไข"><i class="fas fa-pencil-alt fa-fw"></i></a>
                                    <button onclick="deleteUser(<?= $row['user_id']; ?>)" class="cursor-pointer text-red-500 hover:text-red-700 transition-colors" title="ลบ"><i class="fas fa-trash-alt fa-fw"></i></button>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                -- ไม่พบข้อมูลพนักงาน --
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php if ($total_pages > 1) : // แสดงส่วนนี้ก็ต่อเมื่อมีมากกว่า 1 หน้า 
        ?>
            <div class="p-4 flex flex-col sm:flex-row justify-between items-center bg-gray-50 border-t">
                <span class="text-sm text-gray-700 mb-2 sm:mb-0">
                    หน้า <?= $current_page ?> จาก <?= $total_pages ?> (ทั้งหมด <?= $total_results ?> รายการ)
                </span>
                <div class="inline-flex -space-x-px rounded-md shadow-sm">
                    <?php
                    // สร้าง URL พื้นฐานเพื่อคงค่าการค้นหาไว้
                    $base_url = "?page=user_manage&department_id={$selected_department_id}";

                    // ปุ่ม "ก่อนหน้า"
                    if ($current_page > 1) {
                        echo "<a href='{$base_url}&p=" . ($current_page - 1) . "' class='relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50'>ก่อนหน้า</a>";
                    }

                    // สร้างลิงก์ตัวเลข
                    for ($p = 1; $p <= $total_pages; $p++) {
                        if ($p == $current_page) {
                            // หน้าปัจจุบัน
                            echo "<span aria-current='page' class='relative z-10 inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600'>" . $p . "</span>";
                        } else {
                            // หน้าอื่นๆ
                            echo "<a href='{$base_url}&p={$p}' class='relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50'>" . $p . "</a>";
                        }
                    }

                    // ปุ่ม "ถัดไป"
                    if ($current_page < $total_pages) {
                        echo "<a href='{$base_url}&p=" . ($current_page + 1) . "' class='relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50'>ถัดไป</a>";
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>


<script>
    function deleteUser(user_id) {
        Swal.fire({
            title: 'ยืนยันหรือไม่?',
            text: 'คุณต้องการลบข้อมูลพนักงานหรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?page=user_manage&delete_user_id=' + user_id;
            }
        });
    }
</script>

<?php

if (isset($_GET['delete_user_id'])) {

    $user_id = $_GET['delete_user_id'];
    $sql = "DELETE FROM users WHERE user_id = $user_id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        ToastWithRedirect("success", "ลบข้อมูลเรียบร้อย", "?page=user_manage");
    } else {
        showToast("error", "ลบข้อมูลไม่สําเร็จ");
    }
}

?>
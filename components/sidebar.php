<?php
$current_page = $_GET['page'] ?? 'main';
?>

<aside class="w-64 min-h-screen bg-[#222831] text-white flex flex-col flex-shrink-0 shadow-lg">
    <div class="p-6 border-b border-gray-600">
        <h2 class="text-xl font-bold">ระบบประเมิน 360°</h2>
        <p class="text-sm mt-4">สวัสดีคุณ, <?= htmlspecialchars($row['name']) ?></p>
        <p class=" mt-1">แผนก: <?= htmlspecialchars($row['department_name']) ?></p>
        <p class="">ตำแหน่ง: <?= htmlspecialchars($user_role) ?></p>
    </div>

    <nav class="flex-grow p-4">
        <ul class="space-y-2">
            <li>
                <a href="index.php" class="block px-4 py-2 rounded-md hover:bg-red-700 transition <?= ($current_page == 'main' || $current_page == '') ? 'bg-red-700' : '' ?>">
                    <i class="fa-solid fa-house"></i> หน้าหลัก
                </a>
            </li>
            <li>
                <a href="?page=account" class="block px-4 py-2 rounded-md hover:bg-red-700 transition <?= ($current_page == 'account' || $current_page == '') ? 'bg-red-700' : '' ?>">
                    <i class="fa-solid fa-gear"></i> บัญชี
                </a>
            </li>

            <?php
            // เมนูสำหรับ Admin
            if ($row['role'] == 'admin') {
                echo '<li><a href="?page=dashboard" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'dashboard') ? 'bg-red-700' : '') . '">แผงควบคุม</a></li>';
            }

            // เมนูสำหรับ CEO
            if ($row['role'] == 'ceo') {
                // echo '<li><a href="?page=ceo_type" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'ceo_type') ? 'bg-red-700' : '') . '">ประเมิน</a></li>';
                echo '<li><a href="?page=ceo_dashboard" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'ceo_dashboard') ? 'bg-red-700' : '') . '">รายงานผล</a></li>';
            }

            // เมนูสำหรับ Manager
            // if ($row['role'] == 'manager') {
            //     echo '<li><a href="?page=manager_type" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'manager_type') ? 'bg-red-700' : '') . '">ประเมิน</a></li>';
            //     // echo '<li><a href="?page=manager_dashboard" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'manager_dashboard') ? 'bg-red-700' : '') . '">รายงานผล</a></li>';
            // }

            // เมนูสำหรับ Employee
            // if ($row['role'] == 'employee') {
            //     echo '<li><a href="?page=employee_type" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'employee_type') ? 'bg-red-700' : '') . '">ประเมิน</a></li>';
            // }
            ?>
                        <li>
                <a href="logout.php" class="block px-4 py-2 rounded-md hover:bg-red-700 transition">
                    <i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ
                </a>
            </li>

        </ul>
    </nav>

    <div class="p-4 border-t border-gray-600">
        <p>&copy; <?= date('Y') ?> Masaru Marketing</p>
    </div>
</aside>
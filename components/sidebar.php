<?php
$current_page = $_GET['page'] ?? 'main';
?>

<aside id="sidebar" class="w-64 min-h-screen bg-[#16213E] text-white flex flex-col shadow-lg fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-20">
    <div class="relative p-6 border-b border-gray-600">
        <button id="close-sidebar" class="md:hidden absolute top-4 right-4 text-white">
            <i class="fas fa-times text-2xl"></i> </button>

        <h2 class="text-xl font-bold">ระบบประเมิน 360°</h2>
        <p class="text-sm mt-4">สวัสดีคุณ : <?= htmlspecialchars($row['name']) ?></p>
        <p class=" mt-1">แผนก : <?= htmlspecialchars($row['department_name']) ?></p>
        <p class="">ตำแหน่ง : <?= htmlspecialchars($user_role) ?></p>
    </div>

    <nav class="flex-grow p-4">
        <ul class="space-y-2">
            <li>
                <a href="index.php" class="block px-4 py-2 rounded-md hover:bg-red-700 transition <?= ($current_page == 'main' || $current_page == '') ? 'bg-red-700' : '' ?>">
                    <i class="fa-solid fa-house"></i> หน้าหลัก
                </a>
            </li>

            <?php
            // เมนูสำหรับ Admin
            if ($row['role'] == 'admin') {
                echo '<li><a href="?page=user_manage" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'user_manage') ? 'bg-red-700' : '') . '"><i class="fa-solid fa-users-gear"></i> จัดการพนักงาน</a></li>';
                echo '<li><a href="?page=department" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'department') ? 'bg-red-700' : '') . '"><i class="fa-solid fa-hotel"></i> จัดการแผนก</a></li>';
                echo '<li><a href="?page=question" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'question') ? 'bg-red-700' : '') . '"><i class="fa-solid fa-book"></i> จัดการฟอร์ม</a></li>';
                echo '<li><a href="?page=evaluation" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'evaluation') ? 'bg-red-700' : '') . '"><i class="fa-solid fa-calendar"></i> จัดการรอบประเมิน</a></li>';
                echo '<li><a href="?page=select_department" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'select_department') ? 'bg-red-700' : '') . '"><i class="fa-solid fa-pen"></i> จัดการสิทธิ์ประเมิน</a></li>';
            }

            // เมนูสำหรับ CEO
            // if ($row['role'] == 'ceo') {
            //     echo '<li><a href="?page=ceo_dashboard" class="block px-4 py-2 rounded-md hover:bg-red-700 transition ' . (($current_page == 'ceo_dashboard') ? 'bg-red-700' : '') . '">รายงานผล</a></li>';
            // }
            ?>
            <li>
                <a href="?page=account" class="block px-4 py-2 rounded-md hover:bg-red-700 transition <?= ($current_page == 'account' || $current_page == '') ? 'bg-red-700' : '' ?>">
                    <i class="fa-solid fa-user"></i> บัญชี
                </a>
            </li>
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
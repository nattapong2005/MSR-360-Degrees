<?php

if (isset($_GET['period_id'])) {
    $_SESSION['period_id'] = $_GET['period_id'];
}

$period_id = $_SESSION['period_id'];
$sql = "SELECT * FROM evaluation_periods WHERE period_id = $period_id";
$query = mysqli_query($conn, $sql);
$period = mysqli_fetch_assoc($query);

$evaluates = [
    ['title' => 'ประเมินหัวหน้าแผนก', "url" => "?page=manager_dashboard", 'icon' => 'fa-users'],
    ['title' => 'ประเมินของแผนก', 'url' => '?page=peer_dashboard', 'icon' => 'fa-user-tie'],
    ['title' => 'ประเมินตนเอง', 'url' => '?page=self_dashboard', 'icon' => 'fa-user-check'],
];

?>


<section class="px-4 py-6 bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2"><i class="fa-solid fa-book"></i> รายงาน<?= $period['period_name'] ?> </h1>
            <p>เลือกหมวดหมู่ของการประเมิน</p>
        </div>
        <a class="bg-[#16213E]/90 text-white hover:bg-red-800 px-3 py-2 rounded" href="javascript:history.back()"><i class="fa-solid fa-backward"></i> ย้อนกลับ</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($evaluates as $ev) { ?>
            <a href="<?= $ev['url']; ?>" class="group block bg-white border border-gray-200 rounded-xl p-6 shadow hover:shadow-lg hover:border-red-600 transition duration-300 ease-in-out">
                <div class="flex flex-col items-center justify-center h-full">
                    <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-red-600 group-hover:text-white transition">
                        <i class="fas <?= $ev['icon']; ?>"></i>
                    </div>
                    <h2 class="text-center text-gray-700 font-semibold text-lg group-hover:text-red-600">
                        <?= $ev['title']; ?>
                    </h2>
                </div>
            </a>
        <?php } ?>
    </div>
</section>
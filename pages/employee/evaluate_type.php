<?php

if (isset($_GET['period_id'])) {
    $_SESSION['period_id'] = $_GET['period_id'];
}


$period_id = $_SESSION['period_id'];
$sql = "SELECT * FROM evaluation_periods WHERE period_id = $period_id";
$query = mysqli_query($conn, $sql);
$period = mysqli_fetch_assoc($query);


$evaluates = [
    ['title' => 'ประเมินในแผนก', "url" => "?page=peer_evaluate", 'icon' => 'fa-users', 'description' => 'ประเมินเพื่อนร่วมงานที่อยู่ในแผนกเดียวกันกับคุณ','color' => '#9333ea'],
    ['title' => 'ประเมินต่างแผนก', "url" => "?page=cross_evaluate", 'icon' => 'fa-people-arrows', 'description' => 'ประเมินเพื่อนร่วมงานที่อยู่ต่างแผนกตามสิทธิ์ที่ได้รับ','color' => '#eab308'],
    ['title' => 'ประเมินหัวหน้าแผนก', 'url' => '?page=manager_evaluate', 'icon' => 'fa-user-tie', 'description' => 'ประเมินหัวหน้าแผนกหรือผู้บังคับบัญชาของคุณ','color' => '#10b981'],
    ['title' => 'ประเมินตนเอง', 'url' => '?page=self_evaluate', 'icon' => 'fa-user-check', 'description' => 'ทำแบบประเมินเพื่อสะท้อนและประเมินผลการทำงานของคุณเอง','color' => '#dc2626'],
];

?>

<section class="px-4 py-6 max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-3xl font-extrabold text-gray-800"><i class="fa-solid fa-book"></i> <?= $period['period_name'] ?> </h1>
        <a class="bg-[#16213E]/90 text-white hover:bg-red-800 px-3 py-2 rounded" href="javascript:history.back()"><i class="fa-solid fa-backward"></i> ย้อนกลับ</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6 ">
        <?php foreach ($evaluates as $ev) { ?>
            <a href="<?= $ev['url']; ?>" class="group block bg-white border border-gray-200 rounded-xl p-6 shadow hover:shadow-lg hover:border-red-600 transition duration-300 ease-in-out">
                <div class="flex gap-5 items-center">
                    <div class="w-16 h-16 bg-[<?php echo $ev['color']; ?>]/30 text-[<?php echo $ev['color']; ?>] rounded-lg items-center flex justify-center text-2xl mb-4 group-hover:bg-red-600 group-hover:text-white transition">
                        <i class="fas <?= $ev['icon']; ?>"></i>
                    </div>
                    <div class="flex flex-col">
                        <h2 class="text-gray-700 font-semibold text-lg group-hover:text-red-600">
                            <?= $ev['title']; ?>
                        </h2>
                        <p><?= $ev['description']; ?></p>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</section>
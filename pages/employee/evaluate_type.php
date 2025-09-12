<?php

$period_id = $_SESSION['period_id'];
$sql = "SELECT * FROM evaluation_periods WHERE period_id = $period_id";
$query = mysqli_query($conn, $sql);
$period = mysqli_fetch_assoc($query);

$evaluates = [
    ['title' => 'ประเมินในแผนก', "url" => "?page=peer_evaluate", 'icon' => 'fa-users'],
    ['title' => 'ประเมินต่างแผนก', "url" => "?page=cross_evaluate", 'icon' => 'fa-people-arrows'],
    ['title' => 'ประเมินหัวหน้าแผนก', 'url' => '?page=manager_evaluate', 'icon' => 'fa-user-tie'],
    ['title' => 'ประเมินตนเอง', 'url' => '?page=self_evaluate', 'icon' => 'fa-user-check'],
];

?>

<section class="px-4 py-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-6 "><i class="fa-solid fa-book"></i> <?= $period['period_name'] ?> </h1>

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
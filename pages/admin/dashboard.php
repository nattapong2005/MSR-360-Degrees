<?php

$period_id = $_SESSION['period_id'];
$sql = "SELECT * FROM evaluation_periods WHERE period_id = $period_id";
$query = mysqli_query($conn, $sql);
$period = mysqli_fetch_assoc($query);

$evaluates = [
    ['title' => 'ประเมินหัวหน้าแผนก', 'url' => '?page=manager_dashboard'],
    ['title' => 'ประเมินของแผนก', 'url' => '?page=peer_dashboard'],
    // ['title' => 'ประเมินต่างแผนก', 'url' => '?page=cross_dashboard'],
    ['title' => 'ประเมินตนเอง', 'url' => '?page=self_dashboard'],
];

?>

<section class="px-3">
    <h1 class="text-3xl font-bold mb-2">รายงาน<?= $period['period_name'] ?></h1>
    <p>เลือกหมวดหมู่ของการประเมิน</p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
        <?php foreach ($evaluates as $ev) { ?>
            <a href="<?php echo $ev['url']; ?>" class="bg-white shadow-sm rounded-lg p-4 hover:shadow-xl transition-shadow duration-300 ">
                <h1 class="text-2xl font-bold mb-2 text-center"><?php echo $ev['title']; ?></h1>
            </a>
        <?php } ?>
    </div>
</section>
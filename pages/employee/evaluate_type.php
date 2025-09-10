<?php

$evaluates = [
    ['title' => 'ประเมินในแผนก', "url" => "?page=peer_evaluate"],
    ['title' => 'ประเมินต่างแผนก', "url" => "?page=cross_evaluate"],
    ['title' => 'ประเมินหัวหน้าแผนก', 'url' => '?page=manager_evaluate'],
    ['title' => 'ประเมินตนเอง', 'url' => '?page=self_evaluate'],
];

?>

<section class="px-3">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
        <?php foreach ($evaluates as $ev) { ?>
            <a href="<?php echo $ev['url']; ?>" class="bg-white shadow-sm rounded-lg p-4 hover:shadow-xl transition-shadow duration-300 ">
                <h1 class="text-2xl font-bold mb-2 text-center"><?php echo $ev['title']; ?></h1>
            </a>
        <?php } ?>
    </div>
</section>
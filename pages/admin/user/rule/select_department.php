<?php

$sql = "SELECT * FROM departments";
$query = mysqli_query($conn, $sql);

?>

<section class="px-4 py-6 min-h-screen">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-5"><i class="fa-solid fa-pen"></i> จัดการสิทธิ์ในการประเมิน</h1>
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <?php while($row = mysqli_fetch_assoc($query)) { ?>
            <a href="?page=department_rule&department_id=<?= $row['department_id']; ?>" class="flex flex-col items-center justify-center text-center gap-3 w-full font-semibold bg-white p-5 rounded-lg shadow-md border border-gray-300 hover:border-red-700 group">
                <div class="flex flex-col items-center justify-center h-full">
                    <div class="w-16 h-16 text-[<?php echo $row['color'] ?>] rounded-full flex items-center justify-center text-2xl mb-4 transition">
                        <i class="<?php echo $row['icon'] ?> text-5xl"></i>
                    </div>
                   <span class="text-lg">แผนก <?= htmlspecialchars($row['department_name']) ?></span>
                </div>
            </a>
        <?php } ?>
    </div>
</section>

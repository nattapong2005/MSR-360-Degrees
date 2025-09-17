<?php

$sql = "SELECT * FROM departments";
$query = mysqli_query($conn, $sql);

?>

<section class="px-4 py-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-5"><i class="fa-solid fa-pen"></i> จัดการสิทธิ์ในการประเมิน</h1>
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <?php while($row = mysqli_fetch_assoc($query)) { ?>
            <a href="?page=department_rule&department_id=<?= $row['department_id']; ?>" class="group block bg-white border border-gray-200 rounded-xl p-6 shadow hover:shadow-lg hover:border-red-600 transition duration-300 ease-in-out">
                <div class="flex flex-col items-center justify-center h-full">
                    <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-red-600 group-hover:text-white transition">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h2 class="text-center text-gray-700 font-semibold text-lg group-hover:text-red-600">
                        <?= $row['department_name']; ?>
                    </h2>
                </div>
            </a>
        <?php } ?>
    </div>
</section>
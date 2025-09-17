<?php 
$sql = "SELECT * FROM evaluation_periods WHERE status = 'active'";
$query = mysqli_query($conn, $sql);
?>

<section class="px-4 py-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-6 "><i class="fa-solid fa-list-ul"></i> รายการประเมินทั้งหมด</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <a href="?page=manager_type&period_id=<?php echo $row['period_id'] ?>" class="group block bg-white border border-gray-200 rounded-xl p-6 shadow hover:shadow-lg hover:border-red-600 transition duration-300 ease-in-out">
                <div class="flex flex-col items-center justify-center h-full">
                    <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xl mb-4 group-hover:bg-red-600 group-hover:text-white transition">
                        <i class="fa-solid fa-calendar text-2xl"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-center text-gray-700 group-hover:text-red-600">
                        <?php echo $row['period_name']; ?>
                    </h2>
                </div>
                
            </a>
        <?php } ?>
    </div>
</section>

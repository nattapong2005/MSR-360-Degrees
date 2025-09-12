<?php 
$sql = "SELECT * FROM evaluation_periods WHERE status = 'active'";
$query = mysqli_query($conn, $sql);
?>

<section class="px-3">
    <h1 class="text-3xl font-bold mb-2">รายการประเมินทั้งหมด</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <a href="?page=dashboard" class="bg-white border-s-4 border-red-600 shadow-sm rounded-lg p-4 hover:shadow-xl transition-shadow duration-300">
                <h1 class="text-2xl font-bold mb-2 text-center"><?php echo $row['period_name']; ?></h1>
                <?php $_SESSION['period_id'] = $row['period_id']; ?>
            </a>
        <?php } ?>
    </div>
</section>

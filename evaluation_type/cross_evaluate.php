<?php

// ตรวจสอบว่าเราประเมินแผนกไหนบ้าง
$sql = "SELECT d.department_id, d.department_name FROM departments d 
JOIN manager_responsibilities mr ON d.department_id = mr.department_id WHERE mr.user_id = $user_id";
$query = mysqli_query($conn, $sql);

if (isset($_SESSION['manager_id'])) {
    unset($_SESSION['manager_id']);
}

?>

<section class="px-3">
    <div class="mb-2">
        <h1 class="text-3xl font-bold mb-2">ประเมินต่างแผนก</h1>
        <p>แผนกที่คุณสามารถประเมินได้ดังนี้</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <a href="?page=cross_form_evaluate&department_id=<?= $row['department_id'] ?>" class="w-full cursor-pointer font-bold hover:bg-slate-100 text-[#320A6B] bg-white border border-[#320A6B] p-4 rounded hover:-translate-y-1 transition-transform duration-300">
               <div class="text-center"> แผนก <?= $row['department_name'] ?></div>
            </a>
            
        <?php } ?>
    </div>
</section>
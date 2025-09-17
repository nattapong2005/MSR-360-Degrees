<?php

$evaluator_department_id = $_GET['department_id'];

$my_perms = [];
$sqlMyPerms = "SELECT subject_department_id FROM department_rules WHERE evaluator_department_id = $evaluator_department_id";
$queryMyPerms = mysqli_query($conn, $sqlMyPerms);
while ($rule = mysqli_fetch_assoc($queryMyPerms)) {
    $my_perms[] = $rule['subject_department_id'];
}


?>
<section class="p-6 lg:p-10  bg-white shadow-xl rounded-lg">
    <div class="md:col-span-2">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-sitemap"></i>
                กำหนดสิทธิ์การประเมิน
            </h2>
            <a class="bg-[#16213E]/90 text-white hover:bg-red-800 px-3 py-2 rounded" href="javascript:history.back()"><i class="fa-solid fa-backward"></i> ย้อนกลับ</a>
        </div>


        <form method="POST" class="p-4 border border-gray-300 rounded-lg bg-gray-50">
            <input type="hidden" name="evaluator_department_id" value="<?= $evaluator_department_id ?>">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php
                $sqlD = "SELECT department_id,department_name FROM departments WHERE departments.department_id != $evaluator_department_id";
                $queryD = mysqli_query($conn, $sqlD);
                while ($rowD = mysqli_fetch_assoc($queryD)) {
                    $is_checked = in_array($rowD['department_id'], $my_perms);
                ?>
                    <label class="flex items-center space-x-3 cursor-pointer select-none p-2 rounded-md hover:bg-gray-200 transition-colors">
                        <input <?= $is_checked ? 'checked' : '' ?> type="checkbox" name="subject_department_id[]" value="<?= htmlspecialchars($rowD['department_id']) ?>" class="h-5 w-5 text-blue-600 border-gray-400 rounded focus:ring-blue-500">
                        <span class="text-gray-800 font-medium"><?= htmlspecialchars($rowD['department_name']) ?></span>
                    </label>
                <?php } ?>
            </div>
            <div class="flex justify-end mt-5">
                <button type="submit" class="cursor-pointer px-8 py-3 bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <i class="fa-solid fa-save mr-2"></i> บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </form>
    </div>
</section>


<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sqlDelete = "DELETE FROM department_rules WHERE evaluator_department_id = '$evaluator_department_id'";
    $queryDelete = mysqli_query($conn, $sqlDelete);

    if (!$queryDelete) {
        showToast("error", "เกิดข้อผิดพลาดในการบันทึกการเปลี่ยนแปลง");
    }

    if (!isset($_POST['subject_department_id']) || empty($_POST['subject_department_id'])) {
        showToast("error", "กรุณาเลือกแผนก");
        return;
    }

    $evaluator_department_id = $_POST['evaluator_department_id'];
    $subject_department_id = $_POST['subject_department_id'];
    foreach ($subject_department_id as $subject_id) {
        $sql = "INSERT INTO department_rules (evaluator_department_id, subject_department_id) VALUES ($evaluator_department_id, $subject_id)";
        $query = mysqli_query($conn, $sql);
    }
    if ($query) {
        ToastWithRedirect("success", "บันทึกการเปลี่ยนแปลงสำเร็จ", "?page=select_department");
    } else {
        showToast("error", "เกิดข้อผิดพลาดการบันทึกข้อมูลลงฐานข้อมูล");
    }
}

?>
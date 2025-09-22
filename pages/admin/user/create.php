<?php

$sqlDept = "SELECT department_id,department_name FROM departments";
$queryDept = mysqli_query($conn, $sqlDept);

$sqlManager = "SELECT users.user_id,users.name,departments.department_name FROM users 
JOIN departments ON users.department_id = departments.department_id
WHERE users.role IN ('manager', 'ceo')";
$queryManager = mysqli_query($conn, $sqlManager);


?>

<section class="p-6 lg:p-10 min-h-screen">
    <div class="max-w-5xl mx-auto bg-white shadow-xl rounded-lg p-6 md:p-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-user-plus"></i>
                เพิ่มข้อมูลพนักงาน
            </h1>
            <a class="bg-[#16213E]/90 text-white hover:bg-red-800 px-3 py-2 rounded" href="javascript:history.back()"><i class="fa-solid fa-backward"></i> ย้อนกลับ</a>
        </div>

        <form action="" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-สกุล</label>
                <input name="name" type="text" placeholder="กรอกชื่อ-นามสกุล"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                <input name="email" type="text" placeholder="example.likeshop@gmail.com"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                <input name="password" type="password" placeholder="กรอกรหัสผ่าน ไม่ต่ำกว่า 8 ตัวอักษร"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่ง</label>
                <select name="role" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    <option value="" selected>-- เลือกตำแหน่ง --</option>
                    <option value="ceo">ผู้บริหาร</option>
                    <option value="admin">ผู้ดูแลระบบ</option>
                    <option value="manager">หัวหน้าแผนก</option>
                    <option value="employee">พนักงาน</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">แผนก</label>
                <select name="department_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    <option value="" selected>-- เลือกแผนก --</option>
                    <?php
                    while ($rowDept = mysqli_fetch_assoc($queryDept)) {
                    ?>
                        <option value="<?= $rowDept['department_id'] ?>"><?= "{$rowDept['department_name']}" ?></option>

                    <?php } ?>

                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">หัวหน้า</label>
                <select name="manager_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    <option value="" selected>-- เลือกหัวหน้า --</option>
                    <?php
                    while ($rowManager = mysqli_fetch_assoc($queryManager)) {
                    ?>
                        <option value="<?= $rowManager['user_id'] ?>"><?= "{$rowManager['name']} ({$rowManager['department_name']})" ?></option>

                    <?php } ?>

                </select>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit"
                    class="cursor-pointer px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fa-solid fa-save mr-2"></i> บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</section>

<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);
    $role = $_POST['role'];
    $department_id = $_POST['department_id'];
    $manager_id = $_POST['manager_id'];

    if (empty(trim($name))) return showToast("error", "กรุณากรอกชื่อ-นามสกุล");
    if (empty(trim($email))) return showToast("error", "กรุณากรอกอีเมล");
    if (empty(trim($password))) return showToast("error", "กรุณากรอกรหัสผ่าน");
    if (empty(trim($role))) return showToast("error", "กรุณาเลือกตำแหน่ง");
    if (empty(trim($department_id))) return showToast("error", "กรุณาเลือกแผนก");
    if (empty(trim($manager_id))) return showToast("error", "กรุณาเลือกหัวหน้า");

    $sqlCheck = "SELECT users.email FROM users WHERE users.email = '$email'";
    $queryCheck = mysqli_query($conn, $sqlCheck);
    if (mysqli_num_rows($queryCheck) > 0) {
        return showToast("error", "อีเมลนี้ถูกใช้งานแล้ว");
    }

    $sql = "INSERT INTO users (name,email,password,role,department_id,manager_id) 
    VALUES ('$name','$email','$password','$role','$department_id','$manager_id')";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        showToast("success", "เพิ่มข้อมูลเรียบร้อย");
    }
}


?>
<?php
// รับ user_id จาก URL เพื่อดึงข้อมูลผู้ใช้ที่จะแก้ไข
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    die("ไม่พบผู้ใช้ที่จะแก้ไข");
}

$sqlDept = "SELECT department_id, department_name FROM departments";
$queryDept = mysqli_query($conn, $sqlDept);

$sqlManager = "SELECT users.user_id, users.name, departments.department_name FROM users 
JOIN departments ON users.department_id = departments.department_id
WHERE users.role IN ('manager', 'ceo')";
$queryManager = mysqli_query($conn, $sqlManager);

// ดึงข้อมูลผู้ใช้ที่จะแก้ไข
$sqlUser = "SELECT * FROM users WHERE user_id = $user_id";
$resultUser = mysqli_query($conn, $sqlUser);
if (mysqli_num_rows($resultUser) == 0) {
    die("ไม่พบข้อมูลผู้ใช้");
}
$user = mysqli_fetch_assoc($resultUser);

?>

<section class="p-6 lg:p-10 min-h-screen">
    <div class="max-w-5xl mx-auto bg-white shadow-xl rounded-lg p-6 md:p-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-user-pen"></i>
                แก้ไขข้อมูลพนักงาน
            </h1>
            <a class="bg-[#16213E]/90 text-white hover:bg-red-800 px-3 py-2 rounded" href="javascript:history.back()"><i class="fa-solid fa-backward"></i> ย้อนกลับ</a>
        </div>

        <form action="" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-สกุล</label>
                <input name="name" type="text" placeholder="กรอกชื่อ-นามสกุล" value="<?= htmlspecialchars($user['name']) ?>"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                <input name="email" type="text" placeholder="example.likeshop@gmail.com" value="<?= htmlspecialchars($user['email']) ?>"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน (ถ้าไม่เปลี่ยนเว้นว่างไว้)</label>
                <input name="password" type="password" placeholder="กรอกรหัสผ่าน ไม่ต่ำกว่า 8 ตัวอักษร"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่ง</label>
                <select name="role" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" required>
                    <option value="" <?= $user['role'] == '' ? 'selected' : '' ?>>-- เลือกตำแหน่ง --</option>
                    <option value="ceo" <?= $user['role'] == 'ceo' ? 'selected' : '' ?>>ผู้บริหาร</option>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>ผู้ดูแลระบบ</option>
                    <option value="manager" <?= $user['role'] == 'manager' ? 'selected' : '' ?>>หัวหน้าแผนก</option>
                    <option value="employee" <?= $user['role'] == 'employee' ? 'selected' : '' ?>>พนักงาน</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">แผนก</label>
                <select name="department_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" required>
                    <option value="" <?= $user['department_id'] == '' ? 'selected' : '' ?>>-- เลือกแผนก --</option>
                    <?php
                    mysqli_data_seek($queryDept, 0); // reset pointer เพื่อวนซ้ำใหม่
                    while ($rowDept = mysqli_fetch_assoc($queryDept)) {
                    ?>
                        <option value="<?= $rowDept['department_id'] ?>" <?= $user['department_id'] == $rowDept['department_id'] ? 'selected' : '' ?>><?= $rowDept['department_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">หัวหน้า</label>
                <select name="manager_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" required>
                    <option value="" <?= $user['manager_id'] == '' ? 'selected' : '' ?>>-- เลือกหัวหน้า --</option>
                    <?php
                    mysqli_data_seek($queryManager, 0); // reset pointer
                    while ($rowManager = mysqli_fetch_assoc($queryManager)) {
                    ?>
                        <option value="<?= $rowManager['user_id'] ?>" <?= $user['manager_id'] == $rowManager['user_id'] ? 'selected' : '' ?>><?= $rowManager['name'] ?> (<?= $rowManager['department_name'] ?>)</option>
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
    $password = $_POST['password']; // ถ้าว่าง = ไม่เปลี่ยนรหัสผ่าน
    $role = $_POST['role'];
    $department_id = $_POST['department_id'];
    $manager_id = $_POST['manager_id'];

    if (empty(trim($name))) return showToast("error", "กรุณากรอกชื่อ-นามสกุล");
    if (empty(trim($email))) return showToast("error", "กรุณากรอกอีเมล");
    if (empty(trim($role))) return showToast("error", "กรุณาเลือกตำแหน่ง");
    if (empty(trim($department_id))) return showToast("error", "กรุณาเลือกแผนก");
    if (empty(trim($manager_id))) return showToast("error", "กรุณาเลือกหัวหน้า");

    // ตรวจสอบอีเมลซ้ำ แต่ยกเว้นตัวเอง
    $sqlCheck = "SELECT users.email FROM users WHERE users.email = '$email' AND users.user_id != $user_id";
    $queryCheck = mysqli_query($conn, $sqlCheck);
    if (mysqli_num_rows($queryCheck) > 0) {
        return showToast("error", "อีเมลนี้ถูกใช้งานแล้ว");
    }



    if (!empty(trim($password))) {
        if(strlen($password) < 8) {
            showToast("error", "รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร");
            return;
        }
        // ถ้ามีการเปลี่ยนรหัสผ่าน ให้เข้ารหัสใหม่
        $password = md5($password);
        $sqlUpdate = "UPDATE users SET
            name = '$name',
            email = '$email',
            password = '$password',
            role = '$role',
            department_id = '$department_id',
            manager_id = '$manager_id'
            WHERE user_id = $user_id";
    } else {
        // ไม่เปลี่ยนรหัสผ่าน
        $sqlUpdate = "UPDATE users SET
            name = '$name',
            email = '$email',
            role = '$role',
            department_id = '$department_id',
            manager_id = '$manager_id'
            WHERE user_id = $user_id";
    }

    $queryUpdate = mysqli_query($conn, $sqlUpdate);

    if ($queryUpdate) {
        ToastWithRedirect("success", "แก้ไขข้อมูลเรียบร้อย", "?page=user_manage");
    } else {
        showToast("error", "เกิดข้อผิดพลาดในการแก้ไขข้อมูล");
    }
}
?>

<?php

$sql = "SELECT users.name,users.email,users.role,users.created_at,departments.department_name FROM users 
JOIN departments ON users.department_id = departments.department_id WHERE users.user_id = $user_id";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);

switch ($row['role']) {
    case 'admin':
        $user_role = "ผู้ดูแลระบบ";
        break;
    case 'manager':
        $user_role = "หัวหน้าแผนก";
        break;
    case 'employee':
        $user_role = "พนักงาน";
        break;
    case 'ceo':
        $user_role = "ผู้บริหาร";
        break;
    default:
        $user_role = "ไม่พบข้อมูล";
        break;
}

$created_date = date("d F Y, H:i", strtotime($row['created_at']));

?>

<section class="container mx-auto max-w-4xl w-full bg-white rounded-xl shadow-lg p-6 md:p-8">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">ข้อมูลบัญชี</h1>
        <p class="text-gray-500 mt-1">ข้อมูลทั่วไปเกี่ยวกับบัญชีของคุณ</p>
    </div>

    <form action="" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-600 mb-1">ชื่อ-สกุล</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?php echo htmlspecialchars($row['name']); ?>"
                    disabled
                    class="w-full bg-gray-100 rounded-lg p-2.5 text-gray-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">อีเมล</label>
                <div class="w-full bg-gray-100 rounded-lg p-2.5 text-gray-600">
                    <?php echo htmlspecialchars($row['email']); ?>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">ตำแหน่ง</label>
                <div class="w-full bg-gray-100 rounded-lg p-2.5 text-gray-600">
                    <?php echo htmlspecialchars($user_role); ?>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">แผนก</label>
                <div class="w-full bg-gray-100 rounded-lg p-2.5 text-gray-600">
                    <?php echo htmlspecialchars($row['department_name']); ?>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">บัญชีสร้างเมื่อ</label>
                <div class="w-full bg-gray-100 rounded-lg p-2.5 text-gray-600">
                    <?php echo htmlspecialchars($created_date); ?>
                </div>
            </div>

            <div class="md:col-span-2 mt-4">
                <hr>
                <h2 class="text-xl font-semibold text-gray-800 pt-6">เปลี่ยนรหัสผ่าน</h2>
                <p class="text-sm text-gray-500">เว้นว่างไว้หากไม่ต้องการเปลี่ยน</p>
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-600 mb-1">รหัสผ่านใหม่</label>
                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                    placeholder="••••••••••">
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-600 mb-1">ยืนยันรหัสผ่านใหม่</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    class="w-full border border-gray-300 rounded-lg p-2.5 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                    placeholder="••••••••••">
            </div>
        </div>

        <div class="mt-8 text-right">
            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 cursor-pointer">
                <i class="fa-solid fa-floppy-disk"></i> บันทึกการเปลี่ยนแปลง
            </button>
        </div>

    </form>
</section>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($new_password) && empty($confirm_password)) {
        showToast("error", "กรุณากรอกรหัสผ่านใหม่");
        return;
    }

    if (empty($new_password) || empty($confirm_password)) {
        showToast("error", "กรุณากรอกยืนยันรหัสผ่านใหม่");;
        return;
    }

    if ($new_password !== $confirm_password) {
        showToast("error", "รหัสผ่านไม่ตรงกัน");
        return;
    }

    if (strlen($new_password) < 8) {
        showToast("error", "รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร");
        return;
    }

    if ($new_password == $confirm_password) {
        $hashed_password = md5($new_password);
        $update_sql = "UPDATE users SET password = '$hashed_password' WHERE user_id = $user_id";
        if (mysqli_query($conn, $update_sql)) {
            ToastWithRedirect("success", "เปลี่ยนรหัสผ่านสําเร็จ", "logout.php");
        } else {
            // echo "Error updating record: " . mysqli_error($conn);
            showToast("error", "เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน");
        }
    }
}

?>
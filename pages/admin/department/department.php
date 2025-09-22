<?php

$sql = "SELECT * FROM departments";
$query = mysqli_query($conn, $sql);

?>
<section class="px-4 py-6 min-h-screen">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3 sm:mb-0">
            <i class="fa-solid fa-hotel"></i>
            จัดการข้อมูลแผนก
        </h1>
        <button onclick="showAddEvaluationModal()" class="cursor-pointer bg-[#16213E] hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md ">
            <i class="fa-solid fa-plus mr-2"></i>เพิ่มแผนก
        </button>
    </div>
    <div class="overflow-x-auto bg-white shadow-xl rounded-xl">
        <table class="w-full text-left">
            <thead class="bg-[#16213E] text-white">
                <tr>
                    <th class="p-4 font-semibold">#</th>
                    <th class="p-4 font-semibold"><i class="fa-solid fa-file-signature"></i> ชื่อแผนก</th>
                    <th class="p-4 font-semibold"><i class="fa-solid fa-gear"></i> ดำเนินการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php
                $count = 0;
                while ($row = mysqli_fetch_assoc($query)) {
                    $count++;
                ?>
                    <tr class="hover:bg-gray-100 transition-colors duration-200">
                        <td class="p-4"><?= $count ?></td>
                        <td class="p-4"><?= $row['department_name'] ?></td>
                        <td class="p-4 space-x-2">
                            <!-- <a class="text-yellow-500 hover:text-yellow-700 transition-colors" title="แก้ไข"><i class="fas fa-pencil-alt fa-fw"></i></a> -->
                            <button onclick="showEditEvaluationModal(<?= $row['department_id']; ?>, '<?= addslashes(htmlspecialchars($row['department_name'])); ?>')" class="cursor-pointer text-yellow-500 hover:text-yellow-700 transition-colors" title="แก้ไข"><i class="fas fa-pencil-alt fa-fw"></i></button>
                            <button onclick="deleteDepartment(<?= $row['department_id']; ?>)" class="cursor-pointer text-red-500 hover:text-red-700 transition-colors" title="ลบ"><i class="fas fa-trash-alt fa-fw"></i></button>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
</section>
<script>
    function showAddEvaluationModal() {
        Swal.fire({
            title: 'เพื่มชื่อแผนก',
            input: 'text',
            inputLabel: 'ชื่อแผนก',
            inputPlaceholder: 'เช่น IT',
            showCancelButton: true,
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก',
            preConfirm: (departmentName) => {
                if (!departmentName || departmentName.trim() === '') {
                    Swal.showValidationMessage('กรุณากรอกชื่อแผนก');
                    return false;
                }
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                const input = document.createElement('input');
                input.name = 'department_name';
                input.value = departmentName;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function showEditEvaluationModal(departmentId, currentName) {
        Swal.fire({
            title: 'แก้ไขชื่อแผนก',
            html: `
                <div class="space-y-4 text-left p-4">
                    <div>
                        <label for="swal-input-name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อแผนก</label>
                        <input id="swal-input-name" class="w-full py-2 px-3 border border-gray-300 rounded" value="${currentName}" placeholder="เช่น IT">
                    </div>
                </div>`,
            showCancelButton: true,
            confirmButtonText: 'บันทึกการแก้ไข',
            cancelButtonText: 'ยกเลิก',
            focusConfirm: false,
            preConfirm: () => {
                const newName = document.getElementById('swal-input-name').value;
                if (!newName || newName.trim() === '') {
                    Swal.showValidationMessage('กรุณากรอกชื่อแผนก');
                    return false;
                }
                return {
                    name: newName
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                // Input สำหรับระบุว่าเป็นการ "update"
                const actionInput = document.createElement('input');
                actionInput.name = 'action';
                actionInput.value = 'update';
                form.appendChild(actionInput);

                // Input สำหรับ department_id
                const idInput = document.createElement('input');
                idInput.name = 'department_id';
                idInput.value = departmentId;
                form.appendChild(idInput);

                // Input สำหรับชื่อใหม่
                const nameInput = document.createElement('input');
                nameInput.name = 'department_name';
                nameInput.value = result.value.name;
                form.appendChild(nameInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function deleteDepartment(department_id) {
        Swal.fire({
            title: 'ยืนยันหรือไม่?',
            text: 'หากแผนกนี้มีพนักงานอยู่ จะไม่สามารถลบได้!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?page=department&delete_department_id=' + department_id;
            }
        });
    }
</script>

<?php

if (isset($_GET['delete_department_id'])) {

    $department_id = $_GET['delete_department_id'];
    $sql = "DELETE FROM departments WHERE department_id = $department_id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        ToastWithRedirect("success", "ลบข้อมูลเรียบร้อย", "?page=department");
    } else {
        ToastWithRedirect("error", "ลบข้อมูลไม่สําเร็จ", "?page=department");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'update') {

        $department_id = $_POST['department_id'];
        $department_name = $_POST['department_name'];
        $status = $_POST['status'];

        $sqlUpdate = "UPDATE departments SET department_name = '$department_name' WHERE department_id = $department_id";
        $queryUpdate =  mysqli_query($conn, $sqlUpdate);
        if ($queryUpdate) {
            ToastWithRedirect("success", "แก้ไขแผนกสําเร็จ", "?page=department");
        } else {
            ToastWithRedirect("error", "แก้ไขแผนกไม่สําเร็จ", "?page=department");
        }

    } else {
        $department_name = $_POST['department_name'];
        $sqlInsert = "INSERT INTO departments (department_name) VALUES ('$department_name')";
        $queryInsert =  mysqli_query($conn, $sqlInsert);
        if ($queryInsert) {
            ToastWithRedirect("success", "เพิ่มรายแผนกสำเร็จ", "?page=department");
        } else {
            ToastWithRedirect("error", "เพิ่มแผนกไม่สําเร็จ", "?page=department");
        }
    }
}

?>
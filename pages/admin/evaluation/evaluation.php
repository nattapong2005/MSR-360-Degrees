<?php

$sql = "SELECT * FROM evaluation_periods";
$query = mysqli_query($conn, $sql);

?>
<section class="px-4 py-6 min-h-screen">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3 sm:mb-0">
            <i class="fa-solid fa-calendar"></i>
            จัดการรายการประเมิน
        </h1>
        <button onclick="showAddEvaluationModal()" class="cursor-pointer bg-[#16213E] hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md ">
            <i class="fa-solid fa-plus mr-2"></i>เพิ่มการประเมิน
        </button>
    </div>
    <div class="overflow-x-auto bg-white shadow-xl rounded-xl">
        <table class="w-full text-left">
            <thead class="bg-[#16213E] text-white">
                <tr>
                    <th class="p-4 font-semibold">#</th>
                    <th class="p-4 font-semibold"><i class="fa-solid fa-file-signature"></i> ชื่อการะประเมิน</th>
                    <th class="p-4 font-semibold"><i class="fa-solid fa-file-signature"></i> สถานะ</th>
                    <th class="p-4 font-semibold"><i class="fa-solid fa-gear"></i> ดำเนินการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php
                $count = 0;
                while ($row = mysqli_fetch_assoc($query)) {
                    switch ($row['status']) {
                        case 'active':
                            $status = "เปิดใช้งาน";
                            $status_class = "bg-green-100 text-green-800";
                            break;
                        case 'closed':
                            $status = "ปิดใช้งาน";
                            $status_class = "bg-red-100 text-red-800";
                            break;
                        default:
                            $status = "ไม่ระบุ";
                            $status_class = "bg-gray-100 text-gray-800";
                            break;
                    }
                    $count++;
                ?>
                    <tr class="hover:bg-gray-100 transition-colors duration-200">
                        <td class="p-4"><?= $count ?></td>
                        <td class="p-4"><?= $row['period_name'] ?></td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs font-bold rounded-full <?= $status_class; ?>"><?= $status; ?></span>
                        </td>
                        <td class="p-4 space-x-2">
                            <!-- <a class="text-yellow-500 hover:text-yellow-700 transition-colors" title="แก้ไข"><i class="fas fa-pencil-alt fa-fw"></i></a> -->
                            <button onclick="showEditEvaluationModal(<?= $row['period_id']; ?>, '<?= addslashes(htmlspecialchars($row['period_name'])); ?>', '<?= $row['status']; ?>')" class="cursor-pointer text-yellow-500 hover:text-yellow-700 transition-colors" title="แก้ไข"><i class="fas fa-pencil-alt fa-fw"></i></button>
                            <button onclick="deletePeriod(<?= $row['period_id']; ?>)" class="cursor-pointer text-red-500 hover:text-red-700 transition-colors" title="ลบ"><i class="fas fa-trash-alt fa-fw"></i></button>
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
            title: 'เพิ่มรายการประเมิน',
            input: 'text',
            inputLabel: 'ชื่อรอบประเมิน',
            inputPlaceholder: 'เช่น การประเมินปี 2568',
            showCancelButton: true,
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก',
            preConfirm: (periodName) => {
                if (!periodName || periodName.trim() === '') {
                    Swal.showValidationMessage('กรุณากรอกชื่อรอบประเมิน');
                    return false;
                }
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                const input = document.createElement('input');
                input.name = 'period_name';
                input.value = periodName;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function showEditEvaluationModal(periodId, currentName, currentStatus) {
        Swal.fire({
            title: 'แก้ไขรายการประเมิน',
            html: `
                <div class="space-y-4 text-left p-4">
                    <div>
                        <label for="swal-input-name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อรอบประเมิน</label>
                        <input id="swal-input-name" class="w-full py-2 px-3 border border-gray-300 rounded" value="${currentName}" placeholder="เช่น การประเมินปี 2568">
                    </div>
                    <div>
                        <label for="swal-select-status" class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
                        <select id="swal-select-status" class="w-full py-2 px-3 border border-gray-300 rounded">
                            <option value="active" ${currentStatus === 'active' ? 'selected' : ''}>เปิดใช้งาน</option>
                            <option value="closed" ${currentStatus === 'closed' ? 'selected' : ''}>ปิดใช้งาน</option>
                        </select>
                    </div>
                </div>`,
            showCancelButton: true,
            confirmButtonText: 'บันทึกการแก้ไข',
            cancelButtonText: 'ยกเลิก',
            focusConfirm: false,
            preConfirm: () => {
                const newName = document.getElementById('swal-input-name').value;
                const newStatus = document.getElementById('swal-select-status').value;
                if (!newName || newName.trim() === '') {
                    Swal.showValidationMessage('กรุณากรอกชื่อรอบประเมิน');
                    return false;
                }
                return {
                    name: newName,
                    status: newStatus
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

                // Input สำหรับ period_id
                const idInput = document.createElement('input');
                idInput.name = 'period_id';
                idInput.value = periodId;
                form.appendChild(idInput);

                // Input สำหรับชื่อใหม่
                const nameInput = document.createElement('input');
                nameInput.name = 'period_name';
                nameInput.value = result.value.name;
                form.appendChild(nameInput);

                // Input สำหรับสถานะใหม่
                const statusInput = document.createElement('input');
                statusInput.name = 'status';
                statusInput.value = result.value.status;
                form.appendChild(statusInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function deletePeriod(period_id) {
        Swal.fire({
            title: 'ยืนยันหรือไม่?',
            text: 'คุณต้องการลบรายการประเมินหรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?page=evaluation&delete_period_id=' + period_id;
            }
        });
    }
</script>

<?php

if (isset($_GET['delete_period_id'])) {

    $period_id = $_GET['delete_period_id'];
    $sql = "DELETE FROM evaluation_periods WHERE period_id = $period_id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        ToastWithRedirect("success", "ลบข้อมูลเรียบร้อย", "?page=evaluation");
    } else {
        ToastWithRedirect("error", "ลบข้อมูลไม่สําเร็จ", "?page=evaluation");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'update') {

        $period_id = $_POST['period_id'];
        $period_name = $_POST['period_name'];
        $status = $_POST['status'];

        $sqlUpdate = "UPDATE evaluation_periods SET period_name = '$period_name', status = '$status' WHERE period_id = $period_id";
        $queryUpdate =  mysqli_query($conn, $sqlUpdate);
        if ($queryUpdate) {
            ToastWithRedirect("success", "แก้ไขรายการประเมินสําเร็จ", "?page=evaluation");
        } else {
            ToastWithRedirect("error", "แก้ไขรายการประเมินไม่สําเร็จ", "?page=evaluation");
        }

    } else {
        $period_name = $_POST['period_name'];
        $sqlInsert = "INSERT INTO evaluation_periods (period_name) VALUES ('$period_name')";
        $queryInsert =  mysqli_query($conn, $sqlInsert);
        if ($queryInsert) {
            ToastWithRedirect("success", "เพิ่มรายการประเมินสําเร็จ", "?page=evaluation");
        } else {
            ToastWithRedirect("error", "เพิ่มรายการประเมินไม่สําเร็จ", "?page=evaluation");
        }
    }
}

?>
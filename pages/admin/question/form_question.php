<?php

if (!isset($_GET['department_id']) || !filter_var($_GET['department_id'], FILTER_VALIDATE_INT)) {
    die("เกิดข้อผิดพลาด: ไม่พบ ID ของแผนก หรือรูปแบบ ID ไม่ถูกต้อง");
}
$department_id = intval($_GET['department_id']);

// 2. การตั้งค่า Pagination
$items_per_page = 10; // กำหนดจำนวนรายการต่อหน้า
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? intval($_GET['p']) : 1;
$offset = ($current_page - 1) * $items_per_page;

// 3. ดึงชื่อแผนกมาแสดงที่หัวเรื่อง (เพื่อ UX ที่ดี) - (MySQLi Procedural)
$department_name = "ไม่พบข้อมูล";
$sql_dept_name = "SELECT department_name FROM departments WHERE department_id = $department_id";
$result_dept_name = mysqli_query($conn, $sql_dept_name);
if ($row = mysqli_fetch_assoc($result_dept_name)) {
    $department_name = $row['department_name'];
} else {
    die("ไม่พบแผนกที่มี ID: " . htmlspecialchars($department_id));
}

// 4. นับจำนวนคำถามทั้งหมดสำหรับแผนกนี้เพื่อใช้ในการแบ่งหน้า - (MySQLi Procedural)
$sql_count = "SELECT COUNT(*) as total FROM questions WHERE department_id = $department_id";
$result_count = mysqli_query($conn, $sql_count);
$total_items = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_items / $items_per_page);

// 5. ดึงข้อมูลคำถามสำหรับหน้าที่ปัจจุบัน - (MySQLi Procedural)
$sql_page = "SELECT question_id, question_text, max_score FROM questions WHERE department_id = $department_id ORDER BY question_id ASC LIMIT $items_per_page OFFSET $offset";
$query = mysqli_query($conn, $sql_page);

$sqlScore = "SELECT SUM(max_score) AS total_score FROM questions WHERE department_id = $department_id";
$queryScore = mysqli_query($conn, $sqlScore);
$rowScore = mysqli_fetch_assoc($queryScore);

?>

<section class="container mx-auto px-4 py-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fa-solid fa-list-check"></i>
                <span>จัดการคำถาม</span>
            </h1>
            <div class="flex items-center gap-3 mt-2">
                <p class="text-lg text-gray-600">สำหรับแผนก: <span class="font-semibold text-blue-700"><?= htmlspecialchars($department_name) ?></span></p>
                <p>คะแนนทั้งหมด: <span class="font-semibold text-blue-700"><?= $rowScore['total_score'] ?></span></p>
            </div>
        </div>
        <div class="flex items-center gap-2 mt-4 sm:mt-0">
            <a href="javascript:history.back()" class="bg-gray-200 text-gray-800 hover:bg-gray-300 px-4 py-2 rounded-lg transition-colors">
                <i class="fa-solid fa-chevron-left"></i> ย้อนกลับ
            </a>
            <button onclick="addQuestion()" class="cursor-pointer bg-[#16213E] hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>เพิ่มคำถามใหม่</span>
            </button>
        </div>
    </div>

    <!-- Questions Table -->
    <div class="overflow-x-auto bg-white shadow-xl rounded-xl">
        <table class="w-full text-left">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-4 font-semibold w-[5%]">#</th>
                    <th class="p-4 font-semibold w-[65%]"><i class="fa-solid fa-comment-dots mr-2"></i>คำถาม</th>
                    <th class="p-4 font-semibold w-[15%] text-center"><i class="fa-solid fa-star mr-2"></i>คะแนน</th>
                    <th class="p-4 font-semibold w-[15%] text-center"><i class="fa-solid fa-gear mr-2"></i>ดำเนินการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">

                <?php if (mysqli_num_rows($query) > 0) : ?>
                    <?php
                    $count = $offset + 1;
                    while ($row = mysqli_fetch_assoc($query)) : ?>
                        <tr class="hover:bg-gray-100 transition-colors duration-200">
                            <td class="p-4 font-medium text-gray-600"><?= $count ?></td>
                            <td class="p-4 text-gray-800"><?= htmlspecialchars($row['question_text']) ?></td>
                            <td class="p-4 text-center font-bold text-gray-700"><?= htmlspecialchars($row['max_score']) ?></td>
                            <td class="p-4 space-x-4 text-center">
                                <button onclick="editQuestion(<?= $row['question_id']; ?>, '<?= $row['question_text']; ?>', '<?= $row['max_score']; ?>')" class="cursor-pointer text-yellow-500 hover:text-yellow-700 transition-colors" title="แก้ไข">
                                    <i class="fas fa-pencil-alt fa-fw text-lg"></i>
                                </button>
                                <button onclick="deleteQuestion(<?= $row['question_id']; ?>)" class="cursor-pointer text-red-500 hover:text-red-700 transition-colors" title="ลบ">
                                    <i class="fas fa-trash-alt fa-fw text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    <?php
                        $count++;
                    endwhile; ?>
                <?php else : ?>
                    <!-- Empty State Row -->
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">
                            <i class="fa-solid fa-box-open fa-3x mb-3 text-gray-400"></i>
                            <p class="font-semibold text-xl">ไม่พบคำถาม</p>
                            <p>ยังไม่มีคำถามสำหรับแผนกนี้ในระบบ</p>
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    <?php if ($total_pages > 1) : ?>
        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center text-gray-600">
            <div class="mb-4 sm:mb-0">
                แสดงหน้าที่ <strong><?= $current_page ?></strong> จาก <strong><?= $total_pages ?></strong> (ทั้งหมด <strong><?= $total_items ?></strong> รายการ)
            </div>
            <div class="flex items-center space-x-1">
                <!-- Previous Button -->
                <a href="?page=form_question&department_id=<?= $department_id ?>&p=<?= $current_page - 1 ?>" class="<?= $current_page <= 1 ? 'pointer-events-none text-gray-400' : 'hover:bg-gray-200' ?> px-3 py-2 rounded-lg transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <a href="?page=form_question&department_id=<?= $department_id ?>&p=<?= $i ?>" class="<?= $i == $current_page ? 'bg-blue-600 text-white font-bold' : 'hover:bg-gray-200' ?> px-4 py-2 rounded-lg transition-colors">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Next Button -->
                <a href="?page=form_question&department_id=<?= $department_id ?>&p=<?= $current_page + 1 ?>" class="<?= $current_page >= $total_pages ? 'pointer-events-none text-gray-400' : 'hover:bg-gray-200' ?> px-3 py-2 rounded-lg transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    <?php endif; ?>

</section>


<script>
    function addQuestion() {
        Swal.fire({
            title: 'เพิ่มคำถามใหม่',
            html: `
                <div class="space-y-4 text-left p-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">รายการประเมิน</label>
                        <input type="text" id="question_name" class="w-full py-2 px-3 border border-gray-300 rounded" value="" placeholder="เช่น ความพึงพอใจในระบบ">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">คะแนน</label>
                        <input type="number" id="score" class="w-full py-2 px-3 border border-gray-300 rounded" value="" placeholder="กรอกคะแนน 1-100">
                    </div>
                </div>`,
            showCancelButton: true,
            confirmButtonText: 'บันทึก',
            cancelButtonText: 'ยกเลิก',
            preConfirm: () => {
                const question_name = document.getElementById('question_name').value;
                const score = document.getElementById('score').value;
                if (!question_name || question_name.trim() === '') {
                    Swal.showValidationMessage('กรุณากรอกคำถาม');
                    return false;
                }
                if (!score || score.trim() === '') {
                    Swal.showValidationMessage('กรุณากรอกคะแนน');
                    return false;
                }
                return {
                    question_name: question_name,
                    score: score
                };

            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = "POST";
                form.style.display = "none";

                const question_input = document.createElement('input');
                question_input.name = "question_name";
                question_input.value = result.value.question_name;
                form.appendChild(question_input);

                const score_input = document.createElement('input');
                score_input.name = "score";
                score_input.value = result.value.score;
                form.appendChild(score_input);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function editQuestion(q_id, q_name, q_score) {
        Swal.fire({
            title: 'แก้ไขรายการคําถาม',
            html: `
                <div class="space-y-4 text-left p-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">รายการคำถาม</label>
                        <textarea id="q_name" rows="3" cols="20" class="w-full py-2 px-3 border border-gray-300 rounded">${q_name}</textarea>
                    </div>
                  <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">คะแนน</label>
                        <input id="q_score" min="0" max="100" class="w-full py-2 px-3 border border-gray-300 rounded" value="${q_score}">
                    </div>
                </div>`,
            showCancelButton: true,
            confirmButtonText: 'บันทึกการแก้ไข',
            cancelButtonText: 'ยกเลิก',
            focusConfirm: false,
            preConfirm: () => {
                const qName = document.getElementById('q_name').value;
                const qScore = document.getElementById('q_score').value;
                if (!qName || qName.trim() === '') {
                    Swal.showValidationMessage('กรุณากรอกคำถาม');
                    return false;
                }
                if (!qScore || qScore.trim() === '') {
                    Swal.showValidationMessage('กรุณากรอกคะแนน');
                    return false;
                }
                return {
                    qName: qName,
                    qScore: qScore
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                const actionInput = document.createElement('input');
                actionInput.name = 'action';
                actionInput.value = 'update';
                form.appendChild(actionInput);

                const questionId = document.createElement('input');
                questionId.name = 'q_id';
                questionId.value = q_id;
                form.appendChild(questionId);

                const qNameInput = document.createElement('input');
                qNameInput.name = 'q_name';
                qNameInput.value = result.value.qName;
                form.appendChild(qNameInput);

                const qScoreInput = document.createElement('input');
                qScoreInput.name = 'q_score';
                qScoreInput.value = result.value.qScore;
                form.appendChild(qScoreInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }



    function deleteQuestion(question_id) {
        Swal.fire({
            title: 'ยืนยันหรือไม่?',
            text: 'คุณต้องการลบคำถามหรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?page=form_question&department_id=<?= $department_id ?>&delete_question_id=' + question_id;
            }
        });
    }
</script>

<?php

if (isset($_GET['delete_question_id'])) {
    $question_id = $_GET['delete_question_id'];
    $sql = "DELETE FROM questions WHERE question_id = $question_id";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        ToastWithRedirect("success", "ลบคําถามสําเร็จ", "?page=form_question&department_id=$department_id");
    } else {
        ToastWithRedirect("error", "ลบคําถามไม่สําเร็จ", "?page=form_question&department_id=$department_id");
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $q_id = $_POST['q_id'];
        $q_name = $_POST['q_name'];
        $q_score = $_POST['q_score'];
        
        $sql = "UPDATE questions SET question_text = '$q_name', max_score = '$q_score' WHERE question_id = $q_id";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            ToastWithRedirect("success", "แก้ไขคําถามสําเร็จ", "?page=form_question&department_id=$department_id");
        } else {
            ToastWithRedirect("error", "แก้ไขคําถามไม่สําเร็จ", "?page=form_question&department_id=$department_id");
        }
        
    } else {

        $question_name = $_POST['question_name'];
        $score = $_POST['score'];
        $sql = "INSERT INTO questions (question_text, max_score, department_id) VALUES ('$question_name', '$score', $department_id)";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            ToastWithRedirect("success", "เพิ่มคําถามสําเร็จ", "?page=form_question&department_id=$department_id");
        } else {
            ToastWithRedirect("error", "เกิดข้อผิดพลาด", "?page=form_question&department_id=$department_id");
        }
    }
}

?>
<?php
$department_id = $user['department_id'];
$sql = "SELECT * FROM questions WHERE department_id = $department_id";
$query = mysqli_query($conn, $sql);
$period_id = $_SESSION['period_id'];


$sqlCheck = "SELECT ev.period_id,ev.status FROM evaluations as ev 
WHERE ev.subject_id = $user_id AND ev.evaluator_id = $user_id AND ev.status = 'completed' AND ev.period_id = $period_id";
$queryCheck = mysqli_query($conn, $sqlCheck);
$rowCheck = mysqli_fetch_assoc($queryCheck);

if (mysqli_num_rows($queryCheck) > 0) {
    $color = "bg-green-500";
    $status = "ประเมินแล้ว";
} else {
    $status = "ยังไม่ได้ประเมิน";
    $color = "bg-yellow-500";
}

?>

<?php
mysqli_begin_transaction($conn);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scores = $_POST['scores'];
    $comment = $_POST['comment'];

    $total_score = 0;
    foreach ($scores as $question_id => $score) {
        $total_score += (int)$score;
    }
    try {
        $sqlEval = "INSERT INTO evaluations (period_id, subject_id, evaluator_id, evaluation_type_id, status, submission_date) VALUES ($period_id, $user_id, $user_id, 4, 'completed', NOW())";
        mysqli_query($conn, $sqlEval);
        $evaluation_id = mysqli_insert_id($conn);
        $sqlAns = "INSERT INTO answers (evaluation_id, score, comment) VALUES ($evaluation_id, $total_score, '$comment' )";
        mysqli_query($conn, $sqlAns);
        mysqli_commit($conn);
        echo "<script>alert('ส่งแบบประเมินเรียบร้อยแล้ว'); window.location.href='index.php';</script>";
        ToastWithRedirect("success", "ส่งแบบประเมินเรียบร้อยแล้ว", "index.php");
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->$getMessage();
    }
}
?>

<section class=" p-6">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-3xl font-extrabold flex items-center gap-3">
            <i class="fas fa-user-check"></i> ประเมินตนเอง
        </h1>
        <a class="bg-[#16213E]/90 text-white hover:bg-red-800 px-3 py-2 rounded" href="javascript:history.back()"><i class="fa-solid fa-backward"></i> ย้อนกลับ</a>
    </div>
    <form action="" method="POST">
        <div class="space-y-6">
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-medium text-gray-700">
                            <?= htmlspecialchars($row['question_text']) ?>
                        </h2>
                        <div class="flex items-center gap-3">
                            <label for="score_<?= $row['question_id'] ?>" class="text-sm text-gray-600">(คะแนนเต็ม <?= $row['max_score'] ?>)</label>
                            <select
                                name="scores[<?= $row['question_id'] ?>]"
                                id="score_<?= $row['question_id'] ?>"
                                class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                required>
                                <option value="">เลือกคะแนน</option>
                                <?php for ($i = 1; $i <= $row['max_score']; $i++) { ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm mt-6">
            <label class="block font-bold">ความคิดเห็นเพิ่มเติม</label>
            <textarea
                name="comment"
                placeholder="กรอกความคิดเห็นเพิ่มเติม (ถ้ามี)"
                class="w-full border border-gray-300 rounded px-3 py-3 mt-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                rows="4"></textarea>
        </div>


        <div class="mt-8 text-right">
            <?php
            if ($status == "ประเมินแล้ว") {
                echo '<button type="button" class="bg-gray-400 text-white font-semibold px-6 py-2 rounded shadow cursor-not-allowed" disabled>ประเมินแล้ว</button>';
            } else {
            ?>
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
                    ส่งแบบประเมิน
                </button>
            <?php } ?>
        </div>
    </form>
</section>
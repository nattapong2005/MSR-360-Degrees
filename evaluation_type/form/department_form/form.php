<?php

$department_id = $_GET['department_id'];
$period_id = $_SESSION['period_id'];
$subject_id = $_GET['subject_id'];

// หาคำถามของแผนกนี้
$sql = "SELECT * FROM questions WHERE department_id = $department_id";
$query = mysqli_query($conn, $sql);

$sqlSub = "SELECT * FROM users WHERE user_id = $subject_id";   
$querySub = mysqli_query($conn, $sqlSub);   
$rowSub = mysqli_fetch_assoc($querySub);

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
        $sqlEval = "INSERT INTO evaluations (period_id, subject_id, evaluator_id, evaluation_type_id, status, submission_date) VALUES ($period_id, $subject_id, $user_id, 2, 'completed', NOW())";
        mysqli_query($conn, $sqlEval);
        $evaluation_id = mysqli_insert_id($conn);
        $sqlAns = "INSERT INTO answers (evaluation_id, score, comment) VALUES ($evaluation_id, $total_score, '$comment' )";
        mysqli_query($conn, $sqlAns);
        mysqli_commit($conn);
        echo "<script>alert('ส่งแบบประเมินเรียบร้อยแล้ว'); window.location.href='index.php';</script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->$getMessage();
    }
}

?>

<h1 class="text-3xl font-bold mt-8 mb-4 text-gray-800">แบบประเมินคุณ, <?php echo $rowSub['name']; ?></h1>
<section class="bg-white border border-gray-300 shadow-md rounded-lg p-6">
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
            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
                ส่งแบบประเมิน
            </button>
        </div>
    </form>
</section>
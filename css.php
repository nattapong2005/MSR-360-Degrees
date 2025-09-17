<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Sarabun&display=swap');

    * {
        font-family: "Sarabun", sans-serif;
    }

    body {
        background-color: #f6f9fc;
    }

    .active {
        background-color: #ff5722;
        color: white;
    }
</style>

<?php
function showToast($type = "success", $message = "ดำเนินการสำเร็จ")
{
    echo "
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        Toast.fire({
            icon: '$type',
            title: '$message'
        });
    </script>
    ";
}

function ToastWithRedirect($icon = 'warning', $title = 'ใส่ข้อความข้า', $redirectUrl = 'index.php')
{
    echo "
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: '$icon',
            title: '$title',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        setTimeout(() => {
            window.location.href = '$redirectUrl';
        }, 1000);
    </script>
    ";
}

function showConfirm($confirmMessage, $toastMessage, $redirectUrl)
{
echo <<<EOT
<script>
Swal.fire({
    title: 'ยืนยันหรือไม่?',
    text: '$confirmMessage',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'ยืนยัน',
    cancelButtonText: 'ยกเลิก',
}).then((result) => {
    if (result.isConfirmed) {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: '$toastMessage',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true
            
        }).then(() => {
            window.location.href = '$redirectUrl';
        });
    }
});
</script>
EOT;
}

?>
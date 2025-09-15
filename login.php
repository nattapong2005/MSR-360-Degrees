<?php

include 'css.php';
include 'db.php';

session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);
    $sql = "SELECT * FROM users WHERE email='$email' AND password ='$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $row;
        header("Location: index.php");
        exit();
    } else {
        $error = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body class="">

    <div class="flex min-h-screen">


        <!-- Left Pane: Image -->
        <div class="hidden lg:block w-1/2 bg-cover bg-center">
            <div class="w-full h-full bg-black bg-opacity-40 flex items-center justify-center p-12">
                <div class="text-white text-center">
                    <h1 class="text-4xl font-bold mb-4">ปลดล็อกศักยภาพทีมของคุณ</h1>
                    <p class="text-lg text-gray-200">รับฟังความคิดเห็นรอบด้านเพื่อการพัฒนาอย่างยั่งยืนด้วยระบบประเมิน 360°</p>
                    <img src="https://it-msr.vercel.app/logo/logo.png" class="mx-auto w-72" alt="Logo">
                </div>
            </div>
        </div>

        <!-- Right Pane: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-12">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="text-center mb-8">

                    <h1 class="text-3xl font-bold text-gray-800 mt-4">360° Feedback</h1>
                    <p class="text-gray-500">เข้าสู่ระบบเพื่อประเมินผลการทำงาน</p>
                </div>

                <?php if ($error) { ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5" role="alert">
                        <span class="block sm:inline"><?= $error ?></span>
                    </div>
                <?php } ?>

                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">

                    <form action="" method="POST" class="space-y-6">
                        <!-- ช่องกรอกอีเมล -->
                        <div>
                            <label for="email" class="block text-gray-700 text-sm font-medium mb-2">อีเมล</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </span>
                                <input type="email" id="email" name="email" placeholder="กรอกอีเมล" required
                                    class="w-full pl-10 pr-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition duration-300">
                            </div>
                        </div>

                        <!-- ช่องกรอกรหัสผ่าน -->
                        <div>
                            <label for="password" class="block text-gray-700 text-sm font-medium mb-2">รหัสผ่าน</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <input type="password" id="password" name="password" placeholder="••••••••" required
                                    class="w-full pl-10 pr-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition duration-300">
                            </div>
                        </div>

                        <!-- <a href="#" class="block text-sm text-right font-medium text-red-600 hover:text-red-500 transition duration-300">ลืมรหัสผ่าน?</a> -->

                        <!-- ปุ่มเข้าสู่ระบบ -->
                        <div>
                            <button type="submit"
                                class="cursor-pointer w-full bg-red-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300">
                                เข้าสู่ระบบ
                            </button>
                        </div>
                    </form>

                    <!-- ตัวคั่น -->
                    <div class="my-6 flex items-center">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="flex-shrink mx-4 text-gray-400 text-sm">หรือเข้าสู่ระบบด้วย</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <!-- ปุ่ม Social Login -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="cursor-pointer w-full flex items-center justify-center gap-2 py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-100 transition duration-300">
                            <img src="https://www.google.com/favicon.ico" alt="Google" class="w-5 h-5">
                            <span class="font-medium text-gray-700">Google</span>
                        </button>

                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</html>
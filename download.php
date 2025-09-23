<?php
require 'db.php'; // เชื่อม DB ถ้าต้องการ
if (!isset($_GET['file'])) exit;

$folder = __DIR__ . '/template/';
$file = basename($_GET['file']); // ป้องกัน ../
$path = $folder . $file;

if (file_exists($path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
} else {
    echo "File not found";
}

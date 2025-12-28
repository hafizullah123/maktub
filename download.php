<?php
if (!isset($_GET['file'])) {
    die("File not specified.");
}

$file = basename($_GET['file']); // prevent directory traversal
$filepath = "uploads/" . $file;

if (!file_exists($filepath)) {
    die("File not found.");
}

// Force download headers
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;

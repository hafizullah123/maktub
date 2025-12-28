<?php
$conn = new mysqli("localhost","root","","maktub");
if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}

// Collect form data safely
$maktub_number = $_POST['maktub_number'] ?? '';
$maktub_date   = $_POST['maktub_date'] ?? '';
$sender_source = $_POST['sender_source'] ?? '';
$mur_sal_aly   = $_POST['mur_sal_aly'] ?? '';
$marja_eghdam  = $_POST['marja_eghdam'] ?? '';
$taqibi        = $_POST['taqibi'] ?? '';
$maktub_type   = $_POST['maktub_type'] ?? '';
$subject       = $_POST['subject'] ?? '';
$zamaym        = $_POST['zamaym'] ?? '';

// Handle PDF upload
$pdf = null;
if (!empty($_FILES['pdf']['name'])) {
    $pdf = time() . '_' . basename($_FILES['pdf']['name']);
    if (!move_uploaded_file($_FILES['pdf']['tmp_name'], "uploads/" . $pdf)) {
        die("Failed to upload PDF file.");
    }
}

// Prepare statement
$stmt = $conn->prepare("
    INSERT INTO maktub_simple
    (maktub_number, maktub_date, sender_source, mur_sal_aly, marja_eghdam, taqibi, maktub_type, subject, zamaym, kpdfdesc)
    VALUES (?,?,?,?,?,?,?,?,?,?)
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters (10 values → 10 's')
$stmt->bind_param(
    "ssssssssss",
    $maktub_number,
    $maktub_date,
    $sender_source,
    $mur_sal_aly,
    $marja_eghdam,
    $taqibi,
    $maktub_type,
    $subject,
    $zamaym,
    $pdf
);

// Execute
$stmt->execute();

// Redirect
header("Location: list.php");
exit;

<?php
$conn = new mysqli("localhost", "root", "", "maktub");
if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}

// Collect form data safely
$maktub_number  = $_POST['maktub_number'] ?? '';
$maktub_date    = $_POST['maktub_date'] ?? '';
$sender_source  = $_POST['sender_source'] ?? '';
$mur_sal_aly    = $_POST['mur_sal_aly'] ?? '';
$marja_eghdam   = $_POST['marja_eghdam'] ?? '';
$taqibi         = $_POST['taqibi'] ?? '';
$maktub_type    = $_POST['maktub_type'] ?? '';
$subject        = $_POST['subject'] ?? '';
$dosya_morba    = $_POST['dosya_morba'] ?? '';
$zamaym         = $_POST['zamaym'] ?? '';
$hifz_shud      = isset($_POST['hifz_shud']) ? 1 : 0; // New field

// Handle PDF upload
$pdf = null;
if (!empty($_FILES['pdf']['name'])) {
    // Create uploads directory if it doesn't exist
    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }
    
    $pdf = time() . '_' . basename($_FILES['pdf']['name']);
    $target_file = "uploads/" . $pdf;
    
    // Check if file is a PDF
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($file_type != "pdf") {
        die("فقط فایل PDF مجاز است.");
    }
    
    if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $target_file)) {
        die("آپلود فایل PDF ناموفق بود.");
    }
}

// Prepare statement with the new field
$stmt = $conn->prepare("
    INSERT INTO maktub_simple 
    (maktub_number, maktub_date, sender_source, mur_sal_aly, marja_eghdam, 
     taqibi, maktub_type, subject, dosya_morba, zamaym, kpdfdesc, hifz_shud)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    die("خطا در آماده‌سازی کوئری: " . $conn->error);
}

// Bind parameters - 12 values now
$stmt->bind_param(
    "sssssssssssi",  // "i" for integer at the end for hifz_shud
    $maktub_number,
    $maktub_date,
    $sender_source,
    $mur_sal_aly,
    $marja_eghdam,
    $taqibi,
    $maktub_type,
    $subject,
    $dosya_morba,
    $zamaym,
    $pdf,
    $hifz_shud  // New field
);

// Execute statement
if ($stmt->execute()) {
    // Success message
    echo "<script>
        alert('مکتوب با موفقیت ثبت شد!');
        window.location.href = 'list.php';
    </script>";
} else {
    die("خطا در ثبت داده: " . $stmt->error);
}

// Close statement and connection
$stmt->close();
$conn->close();

exit;
?>
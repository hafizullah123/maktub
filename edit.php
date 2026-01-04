<?php
// Database connection
$conn = new mysqli("localhost","root","","maktub");
if ($conn->connect_error) die("DB Error");

$message = "";
$id = $_GET['id'] ?? 0;

// Fetch record to edit
$record = null;
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM maktub_simple WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
    $stmt->close();
    
    if (!$record) {
        die("رکورد یافت نشد");
    }
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $maktub_type = $_POST['maktub_type'] ?? '';
    $maktub_number = $_POST['maktub_number'] ?? '';
    $maktub_date = $_POST['maktub_date'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $sender_source = $_POST['sender_source'] ?? '';
    $mur_sal_aly = $_POST['mur_sal_aly'] ?? '';
    $marja_eghdam = $_POST['marja_eghdam'] ?? '';
    $dosya_morba = $_POST['dosya_morba'] ?? '';
    $hifz_shud = $_POST['hifz_shud'] ?? '0';
    
    // Handle file upload (PDF)
    $kpdfdesc = $record['kpdfdesc'] ?? ''; // Keep existing file
    
    if (isset($_FILES['kpdfdesc']) && $_FILES['kpdfdesc']['error'] == 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = time() . '_' . basename($_FILES['kpdfdesc']['name']);
        $targetFile = $uploadDir . $fileName;
        
        // Check if file is PDF
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if ($fileType == 'pdf') {
            if (move_uploaded_file($_FILES['kpdfdesc']['tmp_name'], $targetFile)) {
                // Delete old file if exists and new file uploaded successfully
                if (!empty($record['kpdfdesc']) && file_exists($uploadDir . $record['kpdfdesc'])) {
                    unlink($uploadDir . $record['kpdfdesc']);
                }
                $kpdfdesc = $fileName;
            }
        }
    }
    
    // Update query
    $stmt = $conn->prepare("UPDATE maktub_simple SET 
        maktub_type = ?, 
        maktub_number = ?, 
        maktub_date = ?, 
        subject = ?, 
        sender_source = ?, 
        mur_sal_aly = ?, 
        marja_eghdam = ?, 
        dosya_morba = ?, 
        hifz_shud = ?, 
        kpdfdesc = ? 
        WHERE id = ?");
    
    $stmt->bind_param("ssssssssssi", 
        $maktub_type, 
        $maktub_number, 
        $maktub_date, 
        $subject, 
        $sender_source, 
        $mur_sal_aly, 
        $marja_eghdam, 
        $dosya_morba, 
        $hifz_shud, 
        $kpdfdesc, 
        $id);
    
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>اطلاعات با موفقیت به‌روزرسانی شد.</div>";
        // Refresh record data
        $stmt = $conn->prepare("SELECT * FROM maktub_simple WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $record = $result->fetch_assoc();
        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger'>خطا در به‌روزرسانی: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ویرایش مکتوب</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f3f7f8;
            --primary: #5fa8a0;
            --primary-dark: #4c928a;
        }
        body {
            font-family: Vazir, Tahoma;
            background: var(--bg);
            padding: 20px;
            font-size: 14px;
        }
        .container {
            max-width: 900px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .page-header {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 15px;
            margin-bottom: 25px;
            color: #065f46;
        }
        .btn-save {
            background: var(--primary);
            color: white;
            padding: 8px 25px;
        }
        .btn-save:hover {
            background: var(--primary-dark);
            color: white;
        }
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 8px 20px;
        }
        .form-label {
            font-weight: bold;
            color: #374151;
        }
        .file-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .current-file {
            background: #e8f5e9;
            padding: 8px;
            border-radius: 5px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="page-header">
            <h3><i class="fas fa-edit"></i> ویرایش مکتوب شماره: <?php echo $record['maktub_number'] ?? ''; ?></h3>
            <p class="text-muted">شناسه رکورد: <?php echo $id; ?></p>
        </div>
        
        <?php echo $message; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">نوع مکتوب *</label>
                    <select name="maktub_type" class="form-select" required>
                        <option value="صادره" <?php echo ($record['maktub_type'] ?? '') == 'صادره' ? 'selected' : ''; ?>>صادره</option>
                        <option value="وارده" <?php echo ($record['maktub_type'] ?? '') == 'وارده' ? 'selected' : ''; ?>>وارده</option>
                        <option value="استعلام" <?php echo ($record['maktub_type'] ?? '') == 'استعلام' ? 'selected' : ''; ?>>استعلام</option>
                        <option value="پیشنهاد" <?php echo ($record['maktub_type'] ?? '') == 'پیشنهاد' ? 'selected' :''; ?>>پیشنهاد</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">نمبر مکتوب *</label>
                    <input type="text" name="maktub_number" class="form-control" 
                           value="<?php echo htmlspecialchars($record['maktub_number'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">تاریخ *</label>
                    <input type="date" name="maktub_date" class="form-control" 
                           value="<?php echo $record['maktub_date'] ?? ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">وضعیت</label>
                    <select name="hifz_shud" class="form-select">
                        <option value="0" <?php echo ($record['hifz_shud'] ?? '0') == '0' ? 'selected' : ''; ?>>جوابیه</option>
                        <option value="1" <?php echo ($record['hifz_shud'] ?? '0') == '1' ? 'selected' : ''; ?>>ابلاغیه</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">موضوع *</label>
                <input type="text" name="subject" class="form-control" 
                       value="<?php echo htmlspecialchars($record['subject'] ?? ''); ?>" required>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">مرجع (ارسالی کننده)</label>
                    <input type="text" name="sender_source" class="form-control" 
                           value="<?php echo htmlspecialchars($record['sender_source'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">مرسل‌الیه (گیرنده)</label>
                    <input type="text" name="mur_sal_aly" class="form-control" 
                           value="<?php echo htmlspecialchars($record['mur_sal_aly'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">اقدام (مرجع اقدام)</label>
                <input type="text" name="marja_eghdam" class="form-control" 
                       value="<?php echo htmlspecialchars($record['marja_eghdam'] ?? ''); ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label">دوسیه/مرجع</label>
                <input type="text" name="dosya_morba" class="form-control" 
                       value="<?php echo htmlspecialchars($record['dosya_morba'] ?? ''); ?>">
            </div>
            
            
            <div class="mb-4">
                <label class="form-label">فایل PDF</label>
                
                <?php if (!empty($record['kpdfdesc'])): ?>
                    <div class="current-file mb-2">
                        <i class="fas fa-file-pdf text-danger"></i>
                        فایل فعلی: <a href="uploads/<?php echo $record['kpdfdesc']; ?>" target="_blank">
                            <?php echo $record['kpdfdesc']; ?>
                        </a>
                        <span class="text-muted">(برای تغییر فایل، فایل جدید انتخاب کنید)</span>
                    </div>
                <?php endif; ?>
                
                <input type="file" name="kpdfdesc" class="form-control" accept=".pdf">
                <div class="file-info">فرمت مجاز: PDF - حداکثر حجم: 5MB</div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-back" onclick="window.location.href='list.php'">
                    <i class="fas fa-arrow-right"></i> بازگشت به لیست
                </button>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set today's date as default for date field if empty
        document.addEventListener('DOMContentLoaded', function() {
            const dateField = document.querySelector('input[name="maktub_date"]');
            if (dateField && !dateField.value) {
                const today = new Date().toISOString().split('T')[0];
                dateField.value = today;
            }
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
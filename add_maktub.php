<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "maktub");
if ($conn->connect_error) {
    die("اتصال به پایگاه داده ناموفق بود: " . $conn->connect_error);
}

$message = "";
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $maktub_number = $_POST['maktub_number'] ?? '';
    $maktub_date = $_POST['maktub_date'] ?? '';
    $sender_source = $_POST['sender_source'] ?? '';
    $mur_sal_aly = $_POST['mur_sal_aly'] ?? '';
    $marja_eghdam = $_POST['marja_eghdam'] ?? '';
    $taqibi = $_POST['taqibi'] ?? '';
    $maktub_type = $_POST['maktub_type'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $dosya_morba = $_POST['dosya_morba'] ?? '';
    $zamaym = $_POST['zamaym'] ?? '';
    $hifz_shud = isset($_POST['hifz_shud']) ? 1 : 0;
    
    // Handle file upload (PDF)
    $kpdfdesc = '';
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
                $kpdfdesc = $fileName;
            } else {
                $message = "<div class='alert alert-warning'>آپلود فایل PDF با مشکل مواجه شد.</div>";
            }
        } else {
            $message = "<div class='alert alert-warning'>فقط فایل‌های PDF مجاز هستند.</div>";
        }
    }
    
    // Prepare and execute insert query
    $stmt = $conn->prepare("INSERT INTO maktub_simple 
        (maktub_number, maktub_date, sender_source, mur_sal_aly, marja_eghdam, 
         taqibi, maktub_type, subject, dosya_morba, zamaym, kpdfdesc, hifz_shud) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssssssssi", 
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
        $kpdfdesc, 
        $hifz_shud);
    
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>مکتوب با موفقیت ثبت شد.</div>";
        $success = true;
        
        // Clear form if success
        if ($success) {
            $_POST = array(); // Clear POST data
        }
    } else {
        $message = "<div class='alert alert-danger'>خطا در ثبت مکتوب: " . $conn->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ثبت مکتوب جدید - سیستم مدیریت مکاتیب</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f3f7f8;
            --card: #ffffff;
            --primary: #5fa8a0;
            --primary-dark: #4c928a;
            --soft: #eef3f5;
            --text: #374151;
        }
        body {
            font-family: Vazir, Tahoma;
            background: var(--bg);
            padding: 20px;
            font-size: 14px;
            color: var(--text);
        }
        .container {
            max-width: 1000px;
            background: var(--card);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .page-header {
            border-bottom: 3px solid var(--primary);
            padding-bottom: 15px;
            margin-bottom: 25px;
            color: #065f46;
        }
        .page-header h2 {
            font-weight: 700;
        }
        .form-section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }
        .section-title {
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px dashed #d1fae5;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-label {
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 6px;
            font-size: 13px;
        }
        .form-control, .form-select {
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(95, 168, 160, 0.1);
        }
        .required::after {
            content: " *";
            color: #dc2626;
        }
        .btn-submit {
            background: var(--primary);
            color: white;
            padding: 10px 30px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-back {
            background: #6b7280;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background: #4b5563;
            color: white;
            text-decoration: none;
        }
        .form-hint {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
            font-style: italic;
        }
        .file-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f0fdfa;
            border-radius: 6px;
            border: 1px solid #d1fae5;
        }
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .char-count {
            font-size: 11px;
            color: #6b7280;
            text-align: left;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="page-header">
            <h2><i class="fas fa-file-alt"></i> ثبت مکتوب جدید</h2>
            <p class="text-muted mb-0">فرم ثبت مکتوب جدید در سیستم</p>
        </div>
        
        <?php echo $message; ?>
        
        <form method="POST" enctype="multipart/form-data" id="maktubForm">
            <!-- بخش اول: اطلاعات پایه -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i> اطلاعات پایه مکتوب
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label required">نوع مکتوب</label>
                        <select name="maktub_type" class="form-select" required>
                            <option value="">-- انتخاب کنید --</option>
                            <option value="صادره" <?php echo ($_POST['maktub_type'] ?? '') == 'صادره' ? 'selected' : ''; ?>>صادره</option>
                            <option value="وارده" <?php echo ($_POST['maktub_type'] ?? '') == 'وارده' ? 'selected' : ''; ?>>وارده</option>
                            <option value="استعلام" <?php echo ($_POST['maktub_type'] ?? '') == 'استعلام' ? 'selected' : ''; ?>>استعلام</option>
                           <option value="پیشنهاد" <?php echo ($_POST['maktub_type'] ?? '') == 'پیشنهاد' ? 'selected' : ''; ?>>پیشنهاد</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">شماره مکتوب</label>
                        <input type="text" name="maktub_number" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['maktub_number'] ?? ''); ?>" 
                               placeholder="مثال: ۱۲۳" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">تاریخ مکتوب</label>
                        <input type="date" name="maktub_date" class="form-control" 
                               value="<?php echo $_POST['maktub_date'] ?? date('Y-m-d'); ?>" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label required">موضوع مکتوب</label>
                        <textarea name="subject" class="form-control" rows="2" 
                                  placeholder="موضوع مکتوب را به طور خلاصه وارد کنید" 
                                  required><?php echo htmlspecialchars($_POST['subject'] ?? ''); ?></textarea>
                        <div class="char-count">حداکثر ۵۰۰ کاراکتر</div>
                    </div>
                </div>
            </div>
            
            <!-- بخش دوم: اطلاعات ارتباطی -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-users"></i> اطلاعات ارتباطی
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">مرجع (ارسالی کننده)</label>
                        <input type="text" name="sender_source" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['sender_source'] ?? ''); ?>" 
                               placeholder="نام مرجع ارسال کننده" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">مرسل‌الیه (گیرنده)</label>
                        <input type="text" name="mur_sal_aly" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['mur_sal_aly'] ?? ''); ?>" 
                               placeholder="نام گیرنده مکتوب">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">اقدام (مرجع اقدام)</label>
                        <input type="text" name="marja_eghdam" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['marja_eghdam'] ?? ''); ?>" 
                               placeholder="مرجع اقدام کننده">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">تقیبی</label>
                        <input type="text" name="taqibi" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['taqibi'] ?? ''); ?>" 
                               placeholder="اطلاعات تقیبی">
                    </div>
                </div>
            </div>
            
            <!-- بخش سوم: اطلاعات تکمیلی -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-file-invoice"></i> اطلاعات تکمیلی
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">دوسیه/مرجع</label>
                        <input type="text" name="dosya_morba" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['dosya_morba'] ?? ''); ?>" 
                               placeholder="شماره یا نام دوسیه">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ضمیمه</label>
                        <input type="text" name="zamaym" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['zamaym'] ?? ''); ?>" 
                               placeholder="اطلاعات ضمیمه">
                    </div>
                </div>
            </div>
            
            <!-- بخش چهارم: وضعیت و فایل -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-cog"></i> فایل PDF
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="checkbox-wrapper">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="hifz_shud" 
                                       id="hifz_shud" value="1" <?php echo ($_POST['hifz_shud'] ?? 0) == 1 ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="hifz_shud">
                                    ابلاغیه (در صورت عدم انتخاب، جوابیه در نظر گرفته می‌شود)
                                </label>
                            </div>
                        </div>
                        <div class="form-hint">ابلاغیه: نیاز به پاسخ دارد | جوابیه: پاسخ به ابلاغیه قبلی</div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">فایل PDF مکتوب</label>
                        <input type="file" name="kpdfdesc" class="form-control" accept=".pdf">
                        <div class="file-info">فرمت مجاز: PDF - حداکثر حجم: 20 مگابایت</div>
                    </div>
                </div>
            </div>
            
            <!-- دکمه‌های عملیاتی -->
            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                <a href="list.php" class="btn-back">
                    <i class="fas fa-arrow-right"></i> بازگشت به لیست
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> ذخیره مکتوب
                </button>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set max length for subject textarea
            const subjectTextarea = document.querySelector('textarea[name="subject"]');
            if (subjectTextarea) {
                subjectTextarea.setAttribute('maxlength', '500');
                
                subjectTextarea.addEventListener('input', function() {
                    const charCount = this.nextElementSibling;
                    if (charCount && charCount.classList.contains('char-count')) {
                        charCount.textContent = this.value.length + ' / ۵۰۰ کاراکتر';
                    }
                });
                
                // Trigger input event to update count
                if (subjectTextarea.value) {
                    subjectTextarea.dispatchEvent(new Event('input'));
                }
            }
            
            // Form validation
            const form = document.getElementById('maktubForm');
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.style.borderColor = '#dc2626';
                        field.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)';
                        
                        // Add error message if not exists
                        if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('text-danger')) {
                            const errorMsg = document.createElement('div');
                            errorMsg.className = 'text-danger mt-1 small';
                            errorMsg.textContent = 'این فیلد الزامی است';
                            field.parentNode.appendChild(errorMsg);
                        }
                    } else {
                        field.style.borderColor = '';
                        field.style.boxShadow = '';
                        
                        // Remove error message if exists
                        if (field.nextElementSibling && field.nextElementSibling.classList.contains('text-danger')) {
                            field.nextElementSibling.remove();
                        }
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    // Show alert
                    if (!document.querySelector('.alert.alert-danger')) {
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger mt-3';
                        alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> لطفاً فیلدهای الزامی را پر کنید.';
                        form.insertBefore(alertDiv, form.firstChild);
                        
                        // Scroll to top
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            });
            
            // Remove error styles on input
            form.querySelectorAll('[required]').forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.style.borderColor = '';
                        this.style.boxShadow = '';
                        
                        // Remove error message if exists
                        if (this.nextElementSibling && this.nextElementSibling.classList.contains('text-danger')) {
                            this.nextElementSibling.remove();
                        }
                    }
                });
            });
            
            // File size validation
            const fileInput = document.querySelector('input[name="kpdfdesc"]');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const maxSize = 20 * 1024 * 1024; // 20MB
                    if (this.files[0] && this.files[0].size > maxSize) {
                        alert('حجم فایل نباید بیشتر از ۲۰ مگابایت باشد.');
                        this.value = '';
                    }
                });
            }
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
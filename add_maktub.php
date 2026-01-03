<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ثبت مکتوب جدید</title>
<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8fafc;
    padding: 20px;
    color: #334155;
    line-height: 1.5;
}

.container {
    max-width: 900px;
    margin: 40px auto;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

.header {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    padding: 24px 30px;
    text-align: center;
}

.header h1 {
    font-size: 1.8rem;
    margin-bottom: 6px;
    font-weight: 600;
}

.header p {
    font-size: 0.95rem;
    opacity: 0.9;
}

.form-content {
    padding: 30px;
}

.form-section {
    margin-bottom: 28px;
}

.form-section-title {
    font-size: 1.1rem;
    color: #1e40af;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e2e8f0;
    font-weight: 600;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* Four columns */
    gap: 18px;
}

.form-group {
    margin-bottom: 16px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #475569;
    font-size: 0.9rem;
}

.required::after {
    content: " *";
    color: #ef4444;
}

input, textarea, select {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 0.95rem;
    font-family: inherit;
    transition: all 0.2s;
    background-color: #f8fafc;
}

input:focus, textarea:focus, select:focus {
    outline: none;
    border-color: #3b82f6;
    background-color: white;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

textarea {
    min-height: 100px;
    resize: vertical;
    line-height: 1.5;
}

.checkbox-container {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background-color: #f1f5f9;
    border-radius: 8px;
    margin-top: 8px;
}

.checkbox-container input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #10b981;
}

.checkbox-container label {
    margin-bottom: 0;
    font-weight: normal;
    cursor: pointer;
}

.file-upload {
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background-color: #f8fafc;
    margin-top: 8px;
    transition: all 0.2s;
}

.file-upload:hover {
    border-color: #3b82f6;
    background-color: #f1f5f9;
}

.file-upload input[type="file"] {
    display: none;
}

.file-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    color: #64748b;
}

.file-label i {
    font-size: 1.8rem;
    color: #3b82f6;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.submit-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(to right, #10b981, #059669);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    letter-spacing: 0.5px;
}

.submit-btn:hover {
    background: linear-gradient(to right, #059669, #047857);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.submit-btn:active {
    transform: translateY(0);
}

.helper-text {
    display: block;
    margin-top: 6px;
    font-size: 0.85rem;
    color: #64748b;
}

@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: repeat(2, 1fr); /* Tablet: 2 columns */
    }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr; /* Mobile: 1 column */
    }
    
    .container {
        margin: 20px auto;
    }
    
    .form-content {
        padding: 20px;
    }
    
    body {
        padding: 10px;
    }
}
</style>
</head>

<body>
<div class="container">
    <div class="header">
        <h1>📄 ثبت مکتوب جدید</h1>
        <p>اطلاعات مکتوب جدید را در فرم زیر وارد نمایید</p>
    </div>

    <div class="form-content">
        <form id="maktubForm" action="save_maktub.php" method="POST" enctype="multipart/form-data">

            <!-- اطلاعات اصلی -->
            <div class="form-section">
                <div class="form-section-title">اطلاعات اصلی</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="maktub_type" class="required">نوع مکتوب</label>
                        <select name="maktub_type" id="maktub_type" required>
                            <option value="">انتخاب کنید</option>
                            <option value="صادره">صادره</option>
                            <option value="وارده">وارده</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="maktub_number" class="required">نمبر مکتوب</label>
                        <input type="text" name="maktub_number" id="maktub_number" required>
                    </div>

                    <div class="form-group">
                        <label for="maktub_date" class="required">تاریخ</label>
                        <input type="date" name="maktub_date" id="maktub_date" required>
                    </div>

                    <div class="form-group">
                        <label for="marja_eghdam">مرجع اقدام</label>
                        <input type="text" name="marja_eghdam" id="marja_eghdam">
                    </div>
                </div>
            </div>

            <!-- اطلاعات طرفین -->
            <div class="form-section">
                <div class="form-section-title">اطلاعات طرفین</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="sender_source" class="required">مرجع ارسال</label>
                        <input type="text" name="sender_source" id="sender_source" required>
                    </div>

                    <div class="form-group">
                        <label for="mur_sal_aly" class="required">مرسل‌الیه</label>
                        <input type="text" name="mur_sal_aly" id="mur_sal_aly" required>
                    </div>

                    <div class="form-group">
                        <label for="taqibi">تعقبی</label>
                        <input type="text" name="taqibi" id="taqibi">
                    </div>

                    <div class="form-group">
                        <label for="zamaym">ضمائم</label>
                        <input type="text" name="zamaym" id="zamaym">
                    </div>
                </div>
            </div>

            <!-- محتوای مکتوب -->
            <div class="form-section">
                <div class="form-section-title">محتوای مکتوب</div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="subject" class="required">موضوع</label>
                        <textarea name="subject" id="subject" required></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label for="dosya_morba" class="required">دوسیه مربوطه</label>
                        <textarea name="dosya_morba" id="dosya_morba" required></textarea>
                    </div>
                </div>
            </div>

            <!-- فایل پیوست -->
            <div class="form-section">
                <div class="form-section-title">فایل پیوست</div>
                <div class="form-group full-width">
                    <label>فایل PDF</label>
                    <div class="file-upload">
                        <label class="file-label" for="pdf">
                            <span style="font-size: 2rem;">📎</span>
                            <span>برای آپلود فایل کلیک کنید</span>
                            <span class="helper-text">فقط فایل‌های PDF قابل قبول هستند</span>
                        </label>
                        <input type="file" name="pdf" id="pdf" accept=".pdf">
                    </div>
                    <div id="fileName" style="margin-top: 8px; font-size: 0.9rem; color: #3b82f6;"></div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">ثبت مکتوب</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('maktub_date').value = today;

    document.getElementById('pdf').addEventListener('change', function() {
        const fileNameDiv = document.getElementById('fileName');
        if (this.files.length > 0) {
            fileNameDiv.textContent = 'فایل انتخاب شده: ' + this.files[0].name;
            fileNameDiv.style.color = '#10b981';
        } else {
            fileNameDiv.textContent = '';
        }
    });
});
</script>
</body>
</html>

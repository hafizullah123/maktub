<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ثبت مکتوب جدید</title>

<style>
*{
    box-sizing:border-box;
    margin:0;
    padding:0;
}

body{
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
    background:#f1f5f9;
    color:#334155;
    min-height:100vh;
    overflow-y:auto;
}

/* Container */
.container{
    max-width:1400px;
    margin:16px auto;
    background:#ffffff;
    border-radius:10px;
    box-shadow:0 6px 20px rgba(0,0,0,.08);
    border:1px solid #e2e8f0;
}

/* Header */
.header{
    padding:18px 24px;
    background:linear-gradient(135deg,#2563eb,#1e40af);
    color:#fff;
    text-align:center;
}

.header h1{
    font-size:1.5rem;
    margin-bottom:4px;
}

.header p{
    font-size:.85rem;
    opacity:.9;
}

/* Form */
.form-content{
    padding:20px 24px;
}

.form-section{
    background:#f9fafb;
    padding:16px;
    border-radius:8px;
    border:1px solid #e5e7eb;
    margin-bottom:16px;
}

.form-section-title{
    font-size:1rem;
    margin-bottom:12px;
    padding-bottom:6px;
    border-bottom:1px solid #e5e7eb;
    color:#1e40af;
    font-weight:600;
}

/* Grid */
.form-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:12px;
}

.two-column{
    grid-template-columns:repeat(2,1fr);
}

.form-group.full-width{
    grid-column:1/-1;
}

/* Inputs */
label{
    font-size:.8rem;
    font-weight:600;
    margin-bottom:4px;
    display:block;
}

.required::after{
    content:" *";
    color:#dc2626;
}

input,select,textarea{
    width:100%;
    padding:9px 10px;
    border:1px solid #cbd5e1;
    border-radius:6px;
    font-size:.85rem;
    background:#f8fafc;
}

textarea{
    min-height:70px;
    resize:vertical;
}

input:focus,select:focus,textarea:focus{
    outline:none;
    border-color:#2563eb;
    background:#fff;
}

/* Checkbox */
.checkbox-container{
    display:flex;
    align-items:center;
    gap:8px;
    padding:8px;
    background:#eef2ff;
    border-radius:6px;
}

/* File upload */
.file-upload{
    padding:16px;
    border:2px dashed #cbd5e1;
    border-radius:8px;
    text-align:center;
    background:#f8fafc;
    cursor:pointer;
}

.file-label{
    display:flex;
    flex-direction:column;
    gap:6px;
    cursor:pointer;
}

/* Button */
.form-actions{
    margin-top:18px;
}

.submit-btn{
    width:100%;
    padding:12px;
    font-size:1rem;
    border:none;
    border-radius:8px;
    background:linear-gradient(to right,#10b981,#059669);
    color:#fff;
    font-weight:600;
    cursor:pointer;
}

/* Responsive */
@media(max-width:1200px){
    .form-grid{grid-template-columns:repeat(2,1fr);}
    .two-column{grid-template-columns:1fr;}
}

@media(max-width:768px){
    .form-grid{grid-template-columns:1fr;}
}
</style>
</head>

<body>

<div class="container">
    <div class="header">
        <h1>📄 ثبت مکتوب جدید</h1>
        <p>لطفاً معلومات مکتوب را به دقت وارد نمایید</p>
    </div>

    <div class="form-content">
        <form method="POST" action="save_maktub.php" enctype="multipart/form-data">

            <!-- اطلاعات اصلی -->
            <div class="form-section">
                <div class="form-section-title">اطلاعات اصلی</div>
                <div class="form-grid">

                    <div class="form-group">
                        <label class="required">نوع مکتوب</label>
                        <select name="maktub_type" required>
                            <option value="">انتخاب کنید</option>
                            <option value="صادره">صادره</option>
                            <option value="وارده">وارده</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="required">نمبر مکتوب</label>
                        <input type="text" name="maktub_number" required>
                    </div>

                    <div class="form-group">
                        <label class="required">تاریخ (هجری شمسی)</label>
                        <input
                            type="text"
                            name="shamsi_date"
                            id="shamsi_date"
                            placeholder="۱۴۰۳/۰۷/۱۵"
                            required
                        >
                    </div>

                </div>
            </div>

            <!-- اطلاعات طرفین -->
            <div class="form-section">
                <div class="form-section-title">اطلاعات طرفین</div>
                <div class="form-grid">

                    <div class="form-group">
                        <label class="required">مرجع ارسال</label>
                        <input type="text" name="sender_source" required>
                    </div>

                    <div class="form-group">
                        <label class="required">مرسل‌الیه</label>
                        <input type="text" name="mur_sal_aly" required>
                    </div>

                    <div class="form-group">
                        <label>مرجع اقدام</label>
                        <input type="text" name="marja_eghdam">
                    </div>

                    <div class="form-group">
                        <label>تعقیبی</label>
                        <input type="text" name="taqibi">
                    </div>

                </div>
            </div>

            <!-- اطلاعات تکمیلی -->
            <div class="form-section">
                <div class="form-section-title">اطلاعات تکمیلی</div>
                <div class="form-grid">

                    <div class="form-group">
                        <label>ضمائم</label>
                        <input type="text" name="zamaym">
                    </div>

                    <div class="form-group">
                        <label>حفظ شد</label>
                        <div class="checkbox-container">
                            <input type="checkbox" name="hifz_shud" value="1">
                            <span>مکتوب حفظ شده است</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- محتوا و فایل -->
            <div class="form-section">
                <div class="form-section-title">محتوا و فایل</div>
                <div class="form-grid two-column">

                    <div class="form-group">
                        <label class="required">موضوع</label>
                        <textarea name="subject" required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="required">دوسیه مربوطه</label>
                        <textarea name="dosya_morba" required></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label class="required">فایل PDF</label>
                        <label class="file-upload">
                            <div class="file-label">
                                <span style="font-size:1.6rem;">📎</span>
                                <span>برای آپلود فایل PDF کلیک کنید</span>
                            </div>
                            <input type="file" name="pdf" id="pdf" accept=".pdf" hidden>
                        </label>
                        <div id="fileName" style="margin-top:6px;font-size:.85rem;color:#059669;"></div>
                    </div>

                </div>
            </div>

            <div class="form-actions">
                <button class="submit-btn">ثبت مکتوب</button>
            </div>

        </form>
    </div>
</div>

<script>
// Auto-fill today's Hijri Shamsi (editable)
document.addEventListener('DOMContentLoaded', () => {
    const today = new Date();
    const shamsi = new Intl.DateTimeFormat('fa-IR-u-ca-persian', {
        year:'numeric',
        month:'2-digit',
        day:'2-digit'
    }).format(today);

    document.getElementById('shamsi_date').value = shamsi;
});

// Allow only numbers and /
document.getElementById('shamsi_date').addEventListener('input', function(){
    this.value = this.value.replace(/[^۰-۹0-9\/]/g,'');
});

// Show selected file name
document.getElementById('pdf').addEventListener('change', function(){
    if(this.files.length){
        document.getElementById('fileName').innerText =
            'فایل انتخاب شده: ' + this.files[0].name;
    }
});
</script>

</body>
</html>

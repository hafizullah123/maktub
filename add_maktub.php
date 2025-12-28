<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<title>ثبت مکتوب</title>

<style>
body{
    font-family: Tahoma, Arial;
    background: linear-gradient(135deg,#eef2f3,#dfe9f3);
    padding: 30px;
}
.form-box{
    max-width: 560px;
    margin: auto;
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
.form-box h2{
    text-align: center;
    margin-bottom: 20px;
    color: #2c3e50;
}
label{
    font-weight: bold;
    margin-top: 12px;
    display: block;
    color: #444;
    font-size: 14px;
}
input, textarea, select{
    width: 100%;
    padding: 10px 12px;
    margin-top: 6px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    background: #fafafa;
}
textarea{min-height:80px}
button{
    margin-top: 20px;
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg,#27ae60,#2ecc71);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}
small{color:#888;font-size:12px}
</style>
</head>

<body>

<div class="form-box">
<h2>📄 ثبت مکتوب جدید</h2>

<form id="maktubForm" action="save_maktub.php" method="POST" enctype="multipart/form-data">

    <label>نوع مکتوب</label>
    <select name="maktub_type" required>
        <option value="">انتخاب کنید</option>
        <option value="صادره">صادره</option>
        <option value="وارده">وارده</option>
    </select>

    <label>نمبر مکتوب</label>
    <input type="text" name="maktub_number" required>

    <label>تاریخ</label>
    <input type="date" name="maktub_date" required>

    <label>مرجع ارسال</label>
    <input type="text" name="sender_source" required>

    <label>مرسل‌الیه</label>
    <input type="text" name="mur_sal_aly" required>

    <label>مرجع اقدام</label>
    <input type="text" name="mur_egdam">

    <label>تعقبی</label>
    <input type="text" name="taqibi">

    <label>موضوع</label>
    <textarea name="subject" required></textarea>

    <label>ضمائم</label>
    <input type="text" name="zamaym">

    <label>فایل PDF</label>
    <input type="file" name="pdf" accept=".pdf">
    <small>فقط فایل PDF قابل قبول است</small>

    <button type="submit">ثبت مکتوب</button>
</form>
</div>

<script>
document.getElementById("maktubForm").addEventListener("submit", function () {
    setTimeout(() => {
        this.reset();   // ✅ clears the form
    }, 500);
});
</script>

</body>
</html>

<?php
$conn = new mysqli("localhost","root","","maktub");
if ($conn->connect_error) die("DB Error");

/* UPDATE RECORD */
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("
        UPDATE maktub_simple SET
        maktub_type=?, maktub_number=?, maktub_date=?, sender_source=?,
        mur_sal_aly=?, marja_eghdam=?, taqibi=?, subject=?, zamaym=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "sssssssssi",
        $_POST['maktub_type'],
        $_POST['maktub_number'],
        $_POST['maktub_date'],
        $_POST['sender_source'],
        $_POST['mur_sal_aly'],
        $_POST['marja_eghdam'],
        $_POST['taqibi'],
        $_POST['subject'],
        $_POST['zamaym'],
        $_POST['id']
    );
    $stmt->execute();
}

/* FETCH DATA */
$result = $conn->query("SELECT * FROM maktub_simple ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<title>لیست مکاتیب</title>

<style>
body{font-family:Tahoma;background:#f4f6f8;padding:20px}

/* SEARCH PANEL */
.search-panel{
    background:#fff;
    padding:15px;
    border-radius:8px;
    margin-bottom:15px;
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    align-items:center;
}
.search-panel input, .search-panel select{
    padding:8px;
    border:1px solid #ccc;
    border-radius:5px;
    font-size:14px;
}

/* TABLE */
table{width:100%;border-collapse:collapse;background:#fff}
th,td{border:1px solid #ddd;padding:8px;text-align:right;font-size:13px}
th{background:#2c3e50;color:#fff}
.edit-btn{color:#27ae60;font-weight:bold;cursor:pointer}

/* MODAL */
.modal{
    display:none;position:fixed;top:0;left:0;width:100%;height:100%;
    background:rgba(0,0,0,0.5);overflow:auto;z-index:999
}
.modal-content{
    background:#fff;max-width:520px;margin:5% auto;padding:20px;
    border-radius:10px;max-height:85vh;overflow-y:auto
}
.close{float:left;color:red;cursor:pointer;font-weight:bold}

/* FORM */
label{display:block;margin-top:10px;font-weight:bold}
input,textarea,select{
    width:100%;padding:7px;margin-top:5px;
    border:1px solid #ccc;border-radius:5px
}
textarea{min-height:70px}
button{
    margin-top:15px;padding:10px;width:100%;
    background:#3498db;color:#fff;border:none;border-radius:6px
}
</style>
</head>

<body>

<!-- SEARCH & FILTER -->
<div class="search-panel">
    <input type="text" id="searchNumber" placeholder="جستجو نمبر مکتوب">
    <input type="text" id="searchSubject" placeholder="جستجو موضوع">
    <select id="filterType">
        <option value="">همه مکاتیب</option>
        <option value="صادره">صادره</option>
        <option value="وارده">وارده</option>
    </select>
</div>

<table id="dataTable">
<thead>
<tr>
    <th>نمبر</th>
    <th>تاریخ</th>
    <th>مرجع ارسال</th>
    <th>مرسل‌الیه</th>
    <th>مرجع اقدام</th>
    <th>تعقیبی</th>
    <th>نوع</th>
    <th>موضوع</th>
    <th>ضمائم</th>
    <th>PDF</th>
    <th>عملیات</th>
</tr>
</thead>

<tbody>
<?php while($r=$result->fetch_assoc()): ?>
<tr>
<td class="col-number"><?= htmlspecialchars($r['maktub_number']) ?></td>
<td><?= htmlspecialchars($r['maktub_date']) ?></td>
<td><?= htmlspecialchars($r['sender_source']) ?></td>
<td><?= htmlspecialchars($r['mur_sal_aly']) ?></td>
<td><?= htmlspecialchars($r['marja_eghdam']) ?></td>
<td><?= htmlspecialchars($r['taqibi']) ?></td>
<td class="col-type"><?= htmlspecialchars($r['maktub_type']) ?></td>
<td class="col-subject"><?= htmlspecialchars($r['subject']) ?></td>
<td><?= htmlspecialchars($r['zamaym']) ?></td>
<td>
<?php if($r['kpdfdesc']): ?>
<a href="download.php?file=<?= urlencode($r['kpdfdesc']) ?>">PDF</a>
<?php else: ?>
ندارد
<?php endif; ?>
</td>
<td>
<span class="edit-btn" onclick='openModal(<?= json_encode($r) ?>)'>✏️ ویرایش</span>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<!-- MODAL UPDATE -->
<div class="modal" id="editModal">
<div class="modal-content">
<span class="close" onclick="closeModal()">✖</span>

<form method="POST">
<input type="hidden" name="id" id="id">

<label>نوع مکتوب</label>
<select name="maktub_type" id="maktub_type">
    <option value="صادره">صادره</option>
    <option value="وارده">وارده</option>
</select>

<label>نمبر مکتوب</label>
<input type="text" name="maktub_number" id="maktub_number">

<label>تاریخ</label>
<input type="date" name="maktub_date" id="maktub_date">

<label>مرجع ارسال</label>
<input type="text" name="sender_source" id="sender_source">

<label>مرسل‌الیه</label>
<input type="text" name="mur_sal_aly" id="mur_sal_aly">

<label>مرجع اقدام</label>
<input type="text" name="marja_eghdam" id="marja_eghdam">

<label>تعقیبی</label>
<input type="text" name="taqibi" id="taqibi">

<label>موضوع</label>
<textarea name="subject" id="subject"></textarea>

<label>ضمائم</label>
<input type="text" name="zamaym" id="zamaym">

<button name="update">ذخیره تغییرات</button>
</form>
</div>
</div>

<script>
const searchNumber = document.getElementById("searchNumber");
const searchSubject = document.getElementById("searchSubject");
const filterType = document.getElementById("filterType");

searchNumber.onkeyup = filterTable;
searchSubject.onkeyup = filterTable;
filterType.onchange = filterTable;

function filterTable(){
    let num = searchNumber.value.toLowerCase();
    let sub = searchSubject.value.toLowerCase();
    let type = filterType.value;

    document.querySelectorAll("#dataTable tbody tr").forEach(row=>{
        let rNum = row.querySelector(".col-number").innerText.toLowerCase();
        let rSub = row.querySelector(".col-subject").innerText.toLowerCase();
        let rType = row.querySelector(".col-type").innerText;

        let show =
            rNum.includes(num) &&
            rSub.includes(sub) &&
            (type=="" || rType===type);

        row.style.display = show ? "" : "none";
    });
}

/* MODAL JS */
function openModal(data){
    document.getElementById("editModal").style.display="block";
    document.body.style.overflow="hidden";
    for(let k in data){
        let el=document.getElementById(k);
        if(el) el.value=data[k];
    }
}
function closeModal(){
    document.getElementById("editModal").style.display="none";
    document.body.style.overflow="auto";
}
</script>

</body>
</html>

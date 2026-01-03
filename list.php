<?php
$conn = new mysqli("localhost","root","","maktub");
if ($conn->connect_error) die("DB Error");
$result = $conn->query("SELECT * FROM maktub_simple ORDER BY id DESC");
$total = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<title>سیستم مدیریت مکاتیب</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
 --bg:#f3f7f8;
 --card:#ffffff;
 --primary:#5fa8a0;
 --primary-dark:#4c928a;
 --soft:#eef3f5;
 --text:#374151;
}
body{
 font-family:Vazir,Tahoma;
 background:var(--bg);
 padding:12px;
 font-size:13px;
 color:var(--text);
}
.container{max-width:1450px;margin:auto}

/* HEADER */
.header{
 background:var(--card);
 padding:12px 16px;
 border-radius:10px;
 box-shadow:0 2px 6px rgba(0,0,0,.05);
 display:flex;
 justify-content:space-between;
 align-items:center;
 margin-bottom:10px;
}
.header h5{
 margin:0;
 color:#065f46;
 font-weight:700;
}
.header .btn{
 background:var(--primary);
 color:#fff;
 font-size:12px;
 padding:6px 12px;
 border-radius:6px;
}
.header .btn:hover{background:var(--primary-dark)}

/* SEARCH */
.search-box{
 background:var(--card);
 padding:10px;
 border-radius:10px;
 box-shadow:0 2px 6px rgba(0,0,0,.05);
 margin-bottom:10px;
}
.search-grid{
 display:grid;
 grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
 gap:8px;
}
.search-box input,.search-box select{
 padding:6px;
 font-size:12px;
 border-radius:6px;
 border:1px solid #d1d5db;
}

/* TABLE */
.table-box{
 background:var(--card);
 padding:10px;
 border-radius:10px;
 box-shadow:0 2px 6px rgba(0,0,0,.05);
}
.data-table{
 width:100%;
 border-collapse:collapse;
 min-width:1150px;
}
.data-table th{
 background:var(--soft);
 padding:8px;
 font-size:12px;
 border-bottom:1px solid #e5e7eb;
}
.data-table td{
 padding:7px;
 border-bottom:1px solid #edf1f4;
}
.data-table tr:hover{background:#f8fafc}

/* BADGES */
.badge{font-size:11px;padding:3px 7px;border-radius:6px}
.badge-s{background:#e0f2f1;color:#065f46}
.badge-v{background:#e8f5e9;color:#1b5e20}
.badge-e{background:#ede7f6;color:#4527a0}
.badge-j{background:#fff7ed;color:#9a3412}

.link-btn{color:#2563eb;cursor:pointer;text-decoration:underline}
.icon-btn{color:#e11d48;font-size:16px;cursor:pointer}

/* MODAL */
.modal-header{background:#f0fdfa}
.modal-title{color:#065f46}
</style>
</head>

<body>
<div class="container">

<!-- HEADER -->
<div class="header">
 <h5><i class="fa fa-folder-open"></i> لیست مکاتیب (<?= $total ?>)</h5>
 <div>
  <a href="index.php" class="btn"><i class="fa fa-plus"></i> ثبت جدید</a>
  <a href="export_excel.php" class="btn"><i class="fa fa-file-excel"></i> اکسل</a>
 </div>
</div>

<!-- SEARCH -->
<div class="search-box">
 <div class="search-grid">
  <input id="sNumber" placeholder="نمبر مکتوب">
  <input id="sSubject" placeholder="موضوع">
  <select id="sType">
   <option value="">نوع</option>
   <option>صادره</option>
   <option>وارده</option>
  </select>
  <select id="sStatus">
   <option value="">وضعیت</option>
   <option value="1">ابلاغیه</option>
   <option value="0">جوابیه</option>
  </select>
 </div>
</div>

<!-- TABLE -->
<div class="table-box">
<table class="data-table" id="dataTable">
<thead>
<tr>
<th>نوع</th><th>نمبر</th><th>تاریخ</th><th>مرجع</th>
<th>مرسل‌الیه</th><th>اقدام</th><th>موضوع</th>
<th>دوسیه</th><th>وضعیت</th><th>PDF</th>
</tr>
</thead>
<tbody>
<?php while($r=$result->fetch_assoc()): ?>
<tr>
<td><?= $r['maktub_type']=='صادره'
?'<span class="badge badge-s">صادره</span>'
:'<span class="badge badge-v">وارده</span>' ?></td>

<td>
<span class="link-btn openModal"
 data-number="<?= $r['maktub_number'] ?>"
 data-date="<?= $r['maktub_date'] ?>"
 data-subject="<?= htmlspecialchars($r['subject']) ?>"
 data-body="<?= htmlspecialchars($r['matn'] ?? '') ?>"
 data-sender="<?= htmlspecialchars($r['sender_source']) ?>"
 data-rec="<?= htmlspecialchars($r['mur_sal_aly']) ?>"
 data-action="<?= htmlspecialchars($r['marja_eghdam']) ?>"
 data-file="<?= $r['kpdfdesc'] ?>">
<?= $r['maktub_number'] ?>
</span>
</td>

<td><?= $r['maktub_date'] ?></td>
<td><?= $r['sender_source'] ?></td>
<td><?= $r['mur_sal_aly'] ?></td>
<td><?= $r['marja_eghdam'] ?></td>
<td><?= mb_strimwidth($r['subject'],0,40,'...') ?></td>
<td><?= mb_strimwidth($r['dosya_morba'],0,30,'...') ?></td>
<td><?= $r['hifz_shud']
?'<span class="badge badge-e">ابلاغیه</span>'
:'<span class="badge badge-j">جوابیه</span>' ?></td>

<td>
<?php if($r['kpdfdesc']): ?>
<i class="fa fa-file-pdf icon-btn openModal"
 data-number="<?= $r['maktub_number'] ?>"
 data-date="<?= $r['maktub_date'] ?>"
 data-subject="<?= htmlspecialchars($r['subject']) ?>"
 data-body="<?= htmlspecialchars($r['matn'] ?? '') ?>"
 data-sender="<?= htmlspecialchars($r['sender_source']) ?>"
 data-rec="<?= htmlspecialchars($r['mur_sal_aly']) ?>"
 data-action="<?= htmlspecialchars($r['marja_eghdam']) ?>"
 data-file="<?= $r['kpdfdesc'] ?>"></i>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="detailModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h6 class="modal-title" id="mTitle"></h6>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<p><b>تاریخ:</b> <span id="mDate"></span></p>
<p><b>مرجع:</b> <span id="mSender"></span></p>
<p><b>مرسل‌الیه:</b> <span id="mRec"></span></p>
<p><b>اقدام:</b> <span id="mAction"></span></p>
<hr>
<p id="mBody" style="white-space:pre-line"></p>
<hr>
<a id="mPdf" class="btn btn-sm btn-outline-danger d-none" target="_blank">
<i class="fa fa-file-pdf"></i> مشاهده PDF
</a>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const inputs=document.querySelectorAll('#sNumber,#sSubject,#sType,#sStatus');
const rows=document.querySelectorAll('#dataTable tbody tr');

inputs.forEach(i=>i.addEventListener('input',filter));
inputs.forEach(i=>i.addEventListener('change',filter));

function filter(){
 rows.forEach(r=>{
  let ok=true;
  if(sNumber.value && !r.cells[1].innerText.includes(sNumber.value)) ok=false;
  if(sSubject.value && !r.cells[6].innerText.includes(sSubject.value)) ok=false;
  if(sType.value && r.cells[0].innerText.trim()!=sType.value) ok=false;
  if(sStatus.value){
   let h=r.cells[8].innerText.includes('ابلاغیه')?'1':'0';
   if(h!=sStatus.value) ok=false;
  }
  r.style.display=ok?'':'none';
 })
}

document.querySelectorAll('.openModal').forEach(el=>{
 el.onclick=()=>{
  mTitle.innerText='مکتوب شماره: '+el.dataset.number;
  mDate.innerText=el.dataset.date;
  mSender.innerText=el.dataset.sender;
  mRec.innerText=el.dataset.rec;
  mAction.innerText=el.dataset.action;
  mBody.innerText=el.dataset.body;
  if(el.dataset.file){
   mPdf.href='uploads/'+el.dataset.file;
   mPdf.classList.remove('d-none');
  }else mPdf.classList.add('d-none');
  new bootstrap.Modal(detailModal).show();
 }
})
</script>

</body>
</html>
<?php $conn->close(); ?>

<?php
$conn = new mysqli("localhost","root","","maktub");
if ($conn->connect_error) die("DB Error");
$result = $conn->query("SELECT * FROM maktub_simple ORDER BY maktub_number DESC");
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
.container{max-width:1550px;margin:auto}

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
 overflow-x:auto;
}
.data-table{
 width:100%;
 border-collapse:collapse;
 min-width:1250px;
}
.data-table th{
 background:var(--soft);
 padding:8px;
 font-size:12px;
 border-bottom:1px solid #e5e7eb;
 white-space:nowrap;
}
.data-table td{
 padding:7px;
 border-bottom:1px solid #edf1f4;
 vertical-align:top;
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
.edit-btn{color:#0d9488;font-size:15px;cursor:pointer;margin-right:8px}

/* MODAL - IMPROVED DESIGN */
.modal-header{
 background: linear-gradient(135deg, #f0fdfa 0%, #e0f2f1 100%);
 border-bottom: 2px solid #5fa8a0;
}
.modal-title{
 color:#065f46;
 font-weight:700;
 font-size:16px;
}
.modal-body{
 background:#fafdfc;
}
.detail-card{
 background:white;
 border-radius:8px;
 padding:15px;
 margin-bottom:15px;
 border:1px solid #e0f2f1;
 box-shadow:0 2px 4px rgba(0,0,0,0.05);
}
.detail-title{
 color:#065f46;
 font-weight:600;
 font-size:13px;
 margin-bottom:5px;
 border-bottom:1px dashed #e0f2f1;
 padding-bottom:4px;
}
.detail-content{
 color:#374151;
 font-size:13px;
 line-height:1.6;
}
.detail-row{
 display:grid;
 grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));
 gap:12px;
 margin-bottom:10px;
}
.detail-item{
 background:#f8fafc;
 padding:8px 10px;
 border-radius:6px;
 border-right:3px solid #5fa8a0;
}
.detail-label{
 color:#6b7280;
 font-size:11px;
 margin-bottom:2px;
}
.detail-value{
 color:#111827;
 font-size:12px;
 font-weight:500;
}
.text-content{
 background:white;
 padding:12px;
 border-radius:6px;
 border:1px solid #e5e7eb;
 max-height:200px;
 overflow-y:auto;
 line-height:1.7;
}
.file-badge{
 background:#fff7ed;
 color:#9a3412;
 padding:4px 10px;
 border-radius:6px;
 font-size:11px;
 display:inline-flex;
 align-items:center;
 gap:5px;
}
.pdf-btn{
 background:#dc2626;
 color:white;
 padding:6px 15px;
 border-radius:6px;
 text-decoration:none;
 display:inline-flex;
 align-items:center;
 gap:6px;
 font-size:12px;
 transition:all 0.3s;
}
.pdf-btn:hover{
 background:#b91c1c;
 color:white;
 transform:translateY(-2px);
 box-shadow:0 4px 8px rgba(220, 38, 38, 0.2);
}
.modal-footer{
 background:#f8fafc;
 border-top:1px solid #e5e7eb;
}

/* Zamaym badge style */
.zamaym-badge{
 background:#f3e8ff;
 color:#7c3aed;
 font-size:11px;
 padding:3px 8px;
 border-radius:4px;
 display:inline-block;
 margin:2px;
}
.zamaym-container{
 max-width:200px;
 max-height:60px;
 overflow-y:auto;
}
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
   <option>استعلام</option>
    <option>پیشنهاد</option>
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
<th>نوع</th>
<th>نمبر</th>
<th>تاریخ</th>
<th>مرجع</th>
<th>مرسل‌الیه</th>
<th>اقدام</th>
<th>موضوع</th>
<th>دوسیه</th>
<th>ضمیمه‌ها</th>
<th>وضعیت</th>
<th>ویرایش</th>
<th>PDF</th>
</tr>
</thead>
<tbody>
<?php while($r=$result->fetch_assoc()): 
// Process zamaym field
$zamaym_items = [];
if (!empty($r['zamaym'])) {
    $zamaym_items = explode(',', $r['zamaym']);
    $zamaym_items = array_map('trim', $zamaym_items);
}
?>
<tr>
<td><?= $r['maktub_type']=='صادره'
?'<span class="badge badge-s">صادره</span>'
:'<span class="badge badge-v">وارده</span>' 

?></td>

<td>
<span class="link-btn openModal"
 data-id="<?= $r['id'] ?>"
 data-type="<?= $r['maktub_type'] ?>"
 data-number="<?= $r['maktub_number'] ?>"
 data-date="<?= $r['maktub_date'] ?>"
 data-subject="<?= htmlspecialchars($r['subject']) ?>"
 data-body="<?= htmlspecialchars($r['matn'] ?? '') ?>"
 data-sender="<?= htmlspecialchars($r['sender_source']) ?>"
 data-rec="<?= htmlspecialchars($r['mur_sal_aly']) ?>"
 data-action="<?= htmlspecialchars($r['marja_eghdam']) ?>"
 data-dosya="<?= htmlspecialchars($r['dosya_morba'] ?? '') ?>"
 data-zamaym="<?= htmlspecialchars($r['zamaym'] ?? '') ?>"
 data-status="<?= $r['hifz_shud'] ?>"
 data-status-text="<?= $r['hifz_shud'] ? 'ابلاغیه' : 'جوابیه' ?>"
 data-file="<?= $r['kpdfdesc'] ?>">
<?= $r['maktub_number'] ?>
</span>
</td>

<td><?= $r['maktub_date'] ?></td>
<td><?= mb_strimwidth($r['sender_source'],0,25,'...') ?></td>
<td><?= mb_strimwidth($r['mur_sal_aly'] ?? '',0,25,'...') ?></td>
<td><?= mb_strimwidth($r['marja_eghdam'] ?? '',0,25,'...') ?></td>
<td><?= mb_strimwidth($r['subject'],0,40,'...') ?></td>
<td><?= mb_strimwidth($r['dosya_morba'] ?? '',0,30,'...') ?></td>
<td class="zamaym-container">
<?php if (!empty($zamaym_items)): ?>
    <?php foreach($zamaym_items as $item): ?>
        <?php if (!empty($item)): ?>
        <div class="zamaym-badge"><?= htmlspecialchars($item) ?></div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <span class="text-muted">--</span>
<?php endif; ?>
</td>
<td><?= $r['hifz_shud']
?'<span class="badge badge-e">ابلاغیه</span>'
:'<span class="badge badge-j">جوابیه</span>' ?></td>

<td>
<i class="fas fa-edit edit-btn" 
   onclick="window.location.href='edit.php?id=<?= $r['id'] ?>'"
   title="ویرایش مکتوب"></i>
</td>

<td>
<?php if($r['kpdfdesc']): ?>
<i class="fa fa-file-pdf icon-btn openModal"
 data-id="<?= $r['id'] ?>"
 data-type="<?= $r['maktub_type'] ?>"
 data-number="<?= $r['maktub_number'] ?>"
 data-date="<?= $r['maktub_date'] ?>"
 data-subject="<?= htmlspecialchars($r['subject']) ?>"
 data-body="<?= htmlspecialchars($r['matn'] ?? '') ?>"
 data-sender="<?= htmlspecialchars($r['sender_source']) ?>"
 data-rec="<?= htmlspecialchars($r['mur_sal_aly']) ?>"
 data-action="<?= htmlspecialchars($r['marja_eghdam']) ?>"
 data-dosya="<?= htmlspecialchars($r['dosya_morba'] ?? '') ?>"
 data-zamaym="<?= htmlspecialchars($r['zamaym'] ?? '') ?>"
 data-status="<?= $r['hifz_shud'] ?>"
 data-status-text="<?= $r['hifz_shud'] ? 'ابلاغیه' : 'جوابیه' ?>"
 data-file="<?= $r['kpdfdesc'] ?>"></i>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>

<!-- IMPROVED MODAL -->
<div class="modal fade" id="detailModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h6 class="modal-title"><i class="fas fa-file-alt"></i> جزئیات کامل مکتوب</h6>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body p-3">
<div class="detail-card">
<div class="detail-title">📋 اطلاعات پایه</div>
<div class="detail-row">
<div class="detail-item">
<div class="detail-label">شماره مکتوب</div>
<div class="detail-value" id="mNumber"></div>
</div>
<div class="detail-item">
<div class="detail-label">تاریخ</div>
<div class="detail-value" id="mDate"></div>
</div>
<div class="detail-item">
<div class="detail-label">نوع</div>
<div class="detail-value">
<span id="mTypeBadge" class="badge"></span>
</div>
</div>
<div class="detail-item">
<div class="detail-label">وضعیت</div>
<div class="detail-value">
<span id="mStatusBadge" class="badge"></span>
</div>
</div>
</div>
</div>

<div class="detail-card">
<div class="detail-title">👥 اطلاعات ارتباطی</div>
<div class="detail-row">
<div class="detail-item">
<div class="detail-label">مرجع (ارسالی کننده)</div>
<div class="detail-value" id="mSender"></div>
</div>
<div class="detail-item">
<div class="detail-label">مرسل‌الیه (گیرنده)</div>
<div class="detail-value" id="mRec"></div>
</div>
<div class="detail-item">
<div class="detail-label">اقدام (مرجع اقدام)</div>
<div class="detail-value" id="mAction"></div>
</div>
</div>
</div>

<div class="detail-card">
<div class="detail-title">📄 موضوع و دوسیه</div>
<div class="detail-row">
<div class="detail-item" style="grid-column: span 2;">
<div class="detail-label">موضوع</div>
<div class="detail-value" id="mSubject"></div>
</div>
<div class="detail-item" style="grid-column: span 2;">
<div class="detail-label">دوسیه/مرجع</div>
<div class="detail-value" id="mDosya"></div>
</div>
</div>
</div>

<div class="detail-card">
<div class="detail-title">📎 ضمیمه‌ها (Zamaym)</div>
<div id="zamaymSection">
<div id="zamaymContent"></div>
</div>
</div>

<div class="detail-card">
<div class="detail-title">📝 متن مکتوب</div>
<div class="text-content" id="mBody"></div>
</div>

<div class="detail-card">
<div class="detail-title">📎 پیوست‌ها</div>
<div id="fileSection" class="d-none">
<div class="d-flex align-items-center justify-content-between">
<div>
<span class="file-badge">
<i class="fas fa-file-pdf"></i>
<span id="fileName"></span>
</span>
</div>
<div>
<a id="mPdf" class="pdf-btn" target="_blank">
<i class="fas fa-external-link-alt"></i> مشاهده PDF
</a>
</div>
</div>
</div>
<div id="noFile" class="text-center py-3 text-muted">
<i class="fas fa-paperclip" style="font-size:24px;opacity:0.5;"></i>
<div class="mt-2">فایل پیوستی وجود ندارد</div>
</div>
</div>
</div>

<div class="modal-footer">
<div class="d-flex justify-content-between w-100">
<div class="text-muted small">
شناسه: <span id="mId" class="fw-bold"></span>
</div>
<div>
<button class="btn btn-sm btn-outline-secondary me-2" data-bs-dismiss="modal">
<i class="fas fa-times"></i> بستن
</button>
<a id="editLink" class="btn btn-sm btn-primary">
<i class="fas fa-edit"></i> ویرایش
</a>
</div>
</div>
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
   let h=r.cells[9].innerText.includes('ابلاغیه')?'1':'0';
   if(h!=sStatus.value) ok=false;
  }
  r.style.display=ok?'':'none';
 })
}

document.querySelectorAll('.openModal').forEach(el=>{
 el.onclick=()=>{
  // Set all details
  mId.innerText = el.dataset.id;
  mNumber.innerText = el.dataset.number;
  mDate.innerText = el.dataset.date;
  mSubject.innerText = el.dataset.subject;
  mSender.innerText = el.dataset.sender || '---';
  mRec.innerText = el.dataset.rec || '---';
  mAction.innerText = el.dataset.action || '---';
  mDosya.innerText = el.dataset.dosya || '---';
  mBody.innerText = el.dataset.body || 'متن مکتوب وارد نشده است.';
  
  // Set zamaym content
  const zamaymContent = document.getElementById('zamaymContent');
  if(el.dataset.zamaym) {
    const zamaymItems = el.dataset.zamaym.split(',').map(item => item.trim()).filter(item => item);
    if(zamaymItems.length > 0) {
      let zamaymHtml = '<div class="d-flex flex-wrap gap-2">';
      zamaymItems.forEach(item => {
        zamaymHtml += `<span class="zamaym-badge">${item}</span>`;
      });
      zamaymHtml += '</div>';
      zamaymContent.innerHTML = zamaymHtml;
    } else {
      zamaymContent.innerHTML = '<div class="text-muted">ضمیمه‌ای وجود ندارد</div>';
    }
  } else {
    zamaymContent.innerHTML = '<div class="text-muted">ضمیمه‌ای وجود ندارد</div>';
  }
  
  // Set type badge
  const typeBadge = document.getElementById('mTypeBadge');
  if(el.dataset.type === 'صادره') {
    typeBadge.className = 'badge badge-s';
    typeBadge.innerText = 'صادره';
  } else {
    typeBadge.className = 'badge badge-v';
    typeBadge.innerText = 'وارده';
  }
  
  // Set status badge
  const statusBadge = document.getElementById('mStatusBadge');
  if(el.dataset.status === '1') {
    statusBadge.className = 'badge badge-e';
    statusBadge.innerText = 'ابلاغیه';
  } else {
    statusBadge.className = 'badge badge-j';
    statusBadge.innerText = 'جوابیه';
  }
  
  // Handle PDF file
  if(el.dataset.file) {
    document.getElementById('fileSection').classList.remove('d-none');
    document.getElementById('noFile').classList.add('d-none');
    fileName.innerText = el.dataset.file;
    mPdf.href = 'uploads/' + el.dataset.file;
  } else {
    document.getElementById('fileSection').classList.add('d-none');
    document.getElementById('noFile').classList.remove('d-none');
  }
  
  // Set edit link
  editLink.href = 'edit.php?id=' + el.dataset.id;
  
  // Show modal
  new bootstrap.Modal(detailModal).show();
 }
})
</script>

</body>
</html>
<?php $conn->close(); ?>
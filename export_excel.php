<?php
$conn = new mysqli("localhost","root","","maktub");
if ($conn->connect_error) die("DB Error");

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=maktub_list.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo '<meta charset="UTF-8">';

echo "<table border='1'>
<tr style='background:#d1fae5;font-weight:bold'>
<th>نوع</th>
<th>نمبر مکتوب</th>
<th>تاریخ</th>
<th>مرجع ارسال</th>
<th>مرسل‌الیه</th>
<th>مرجع اقدام</th>
<th>موضوع</th>
<th>دوسیه</th>
<th>وضعیت</th>
<th>PDF</th>
</tr>";

$res = $conn->query("SELECT * FROM maktub_simple ORDER BY id DESC");
while($r=$res->fetch_assoc()){

$pdf = $r['kpdfdesc']
? "<a href='http://localhost/maktub/uploads/{$r['kpdfdesc']}'>مشاهده PDF</a>"
: "-";

echo "<tr>
<td>{$r['maktub_type']}</td>
<td>{$r['maktub_number']}</td>
<td>{$r['maktub_date']}</td>
<td>{$r['sender_source']}</td>
<td>{$r['mur_sal_aly']}</td>
<td>{$r['marja_eghdam']}</td>
<td>{$r['subject']}</td>
<td>{$r['dosya_morba']}</td>
<td>".($r['hifz_shud']?'ابلاغیه':'جوابیه')."</td>
<td>$pdf</td>
</tr>";
}

echo "</table>";
$conn->close();
exit;

<?php
$conn = new mysqli("localhost","root","","maktub");
if ($conn->connect_error) {
    die("DB Error");
}

$search = $_GET['search'] ?? '';
$type   = $_GET['type'] ?? '';

$where = "WHERE 1";
if ($search != '') {
    $search = $conn->real_escape_string($search);
    $where .= " AND (subject LIKE '%$search%' OR maktub_number LIKE '%$search%')";
}
if ($type != '') {
    $type = $conn->real_escape_string($type);
    $where .= " AND maktub_type='$type'";
}

$data = $conn->query("SELECT * FROM maktub_simple $where ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<title>داشبورد مکتوب‌ها</title>

<style>
*{box-sizing:border-box}
body{
    margin:0;
    font-family:Tahoma, Arial;
    background:#f1f5f9;
}

/* Layout */
.wrapper{
    display:flex;
    height:100vh;
}

/* Sidebar */
.sidebar{
    width:220px;
    background:#0f172a;
    color:#fff;
    padding:20px;
}
.sidebar h2{
    text-align:center;
    margin-bottom:25px;
    font-size:20px;
}
.sidebar div{
    padding:10px;
    border-radius:6px;
    margin-bottom:8px;
    background:#1e293b;
    text-align:center;
}

/* Main */
.main{
    flex:1;
    display:flex;
    flex-direction:column;
}

/* Top bar */
.topbar{
    background:#fff;
    padding:15px 20px;
    border-bottom:1px solid #e5e7eb;
    font-size:18px;
    font-weight:bold;
}

/* Content */
.content{
    padding:20px;
    overflow:hidden;
}

/* Filter */
.filter{
    background:#fff;
    padding:15px;
    border-radius:10px;
    display:flex;
    gap:10px;
    margin-bottom:15px;
}
.filter input,
.filter select,
.filter button{
    padding:8px 10px;
    border:1px solid #cbd5f5;
    border-radius:6px;
}
.filter button{
    background:#2563eb;
    color:#fff;
    border:none;
    cursor:pointer;
}

/* Table */
.table-box{
    background:#fff;
    border-radius:10px;
    height:calc(100vh - 200px);
    overflow:hidden;
}
.table-scroll{
    height:100%;
    overflow-y:auto;
}
table{
    width:100%;
    border-collapse:collapse;
}
thead{
    background:#2563eb;
    color:#fff;
}
th,td{
    padding:10px;
    border-bottom:1px solid #e5e7eb;
    font-size:14px;
    text-align:center;
}

/* Badges */
.in{
    background:#dcfce7;
    color:#166534;
    padding:4px 12px;
    border-radius:20px;
}
.out{
    background:#fee2e2;
    color:#991b1b;
    padding:4px 12px;
    border-radius:20px;
}
</style>
</head>

<body>

<div class="wrapper">

    <!-- Sidebar -->
<!-- Sidebar -->
<div class="sidebar" style="background:#fff; color:#000; width:200px; padding:15px; border-radius:10px;">
    <h2 style="text-align:center; margin-bottom:15px; color:#111;">سیستم مکتوب</h2>
    <a href="index.php" style="display:block; padding:8px 10px; margin-bottom:5px; border-radius:6px; background:#f0f0f0; color:#111; text-align:center; text-decoration:none;">داشبورد</a>
    <a href="add_maktub.php" style="display:block; padding:8px 10px; margin-bottom:5px; border-radius:6px; background:#f0f0f0; color:#111; text-align:center; text-decoration:none;">ثبت مکتوب</a>
    <a href="list.php" style="display:block; padding:8px 10px; margin-bottom:5px; border-radius:6px; background:#f0f0f0; color:#111; text-align:center; text-decoration:none;">گزارشات</a>
</div>




    <!-- Main -->
    <div class="main">

        <div class="topbar">داشبورد مکتوب‌ها</div>

        <div class="content">

            <!-- Search / Filter -->
            <form class="filter" method="GET">
                <input type="text" name="search" placeholder="موضوع یا نمبر" value="<?= htmlspecialchars($search) ?>">
                <select name="type">
                    <option value="">همه</option>
                    <option value="صادره" <?= $type=='صادره'?'selected':'' ?>>صادره</option>
                    <option value="وارده" <?= $type=='وارده'?'selected':'' ?>>وارده</option>
                    <option value="استعلام" <?= $type=='استعلام'?'selected':'' ?>>استعلام</option>
                    <option value="پیشنهاد" <?= $type=='پیشنهاد'?'selected':'' ?>>پیشنهاد</option>
                </select>
                <button>جستجو</button>
            </form>

            <!-- Table -->
            <div class="table-box">
                <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نوع</th>
                            <th>نمبر</th>
                            <th>تاریخ</th>
                            <th>مرجع ارسال</th>
                            <th>موضوع</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($data->num_rows): $i=1; ?>
                        <?php while($row=$data->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <span class="<?= $row['maktub_type']=='صادره'?'out':'in' ?>">
                                    <?= $row['maktub_type'] ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['maktub_number']) ?></td>
                            <td><?= htmlspecialchars($row['maktub_date']) ?></td>
                            <td><?= htmlspecialchars($row['sender_source']) ?></td>
                            <td><?= htmlspecialchars($row['subject']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">داده‌ای وجود ندارد</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>

        </div>

    </div>

</div>

</body>
</html>

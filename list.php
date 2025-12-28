<?php
$conn = new mysqli("localhost","root","","maktub");
if ($conn->connect_error) die("DB Error");

/* UPDATE RECORD */
if (isset($_POST['update'])) {
    $hifz_shud = isset($_POST['hifz_shud']) ? 1 : 0;
    
    $stmt = $conn->prepare("
        UPDATE maktub_simple SET
        maktub_type=?, maktub_number=?, maktub_date=?, sender_source=?,
        mur_sal_aly=?, marja_eghdam=?, taqibi=?, subject=?, zamaym=?, dosya_morba=?, hifz_shud=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "sssssssssssi",
        $_POST['maktub_type'],
        $_POST['maktub_number'],
        $_POST['maktub_date'],
        $_POST['sender_source'],
        $_POST['mur_sal_aly'],
        $_POST['marja_eghdam'],
        $_POST['taqibi'],
        $_POST['subject'],
        $_POST['zamaym'],
        $_POST['dosya_morba'],
        $hifz_shud,
        $_POST['id']
    );
    
    if ($stmt->execute()) {
        $success_message = "✅ مکتوب با موفقیت به‌روزرسانی شد.";
    } else {
        $error_message = "❌ خطا در به‌روزرسانی: " . $stmt->error;
    }
    
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* FETCH DATA */
$result = $conn->query("SELECT * FROM maktub_simple ORDER BY id DESC");
$total_records = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>لیست مکاتیب</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --primary: #3498db;
        --primary-light: #5dade2;
        --secondary: #2c3e50;
        --success: #27ae60;
        --danger: #e74c3c;
        --warning: #f39c12;
        --light: #f8f9fa;
        --dark: #343a40;
        --gray: #95a5a6;
        --border: #dee2e6;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Vazir', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f7fa;
        padding: 15px;
        color: var(--dark);
        line-height: 1.5;
        overflow-x: hidden;
    }

    .container {
        max-width: 100%;
        margin: 0 auto;
        min-height: 100vh;
    }

    /* Header */
    .header {
        background: white;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 15px;
        border-left: 4px solid var(--primary);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .page-title h1 {
        color: var(--secondary);
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 8px;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.9rem;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background: #2980b9;
    }

    /* Filters */
    .filters-section {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 10px;
    }

    .filter-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid var(--border);
        border-radius: 5px;
        font-size: 0.9rem;
        background: var(--light);
    }

    .filter-input:focus {
        outline: none;
        border-color: var(--primary);
    }

    /* Table Container */
    .table-container {
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .table-title {
        font-size: 1.1rem;
        color: var(--secondary);
        font-weight: 600;
    }

    /* Scrollable Table - FIXED */
    .table-scroll-container {
        overflow-x: auto;
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 5px;
        -webkit-overflow-scrolling: touch;
        position: relative;
    }

    .table-scroll-container::-webkit-scrollbar {
        height: 8px;
    }

    .table-scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-scroll-container::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 4px;
    }

    .table-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #2980b9;
    }

    /* Table */
    .data-table {
        width: 100%;
        min-width: 1300px; /* Increased for more columns */
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .data-table thead {
        background: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .data-table th {
        padding: 12px 10px;
        text-align: right;
        font-weight: 600;
        color: var(--dark);
        border-bottom: 2px solid var(--border);
        white-space: nowrap;
        background: #f8f9fa;
        position: sticky;
        top: 0;
    }

    .data-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.2s;
    }

    .data-table tbody tr:hover {
        background: rgba(52, 152, 219, 0.05);
    }

    .data-table td {
        padding: 10px;
        color: var(--dark);
        vertical-align: top;
        max-width: 200px;
    }

    /* Column widths */
    .col-number {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary);
        white-space: nowrap;
        min-width: 120px;
    }

    .col-date {
        font-family: 'Courier New', monospace;
        color: var(--secondary);
        white-space: nowrap;
        min-width: 100px;
    }

    /* Compact Content */
    .compact-text {
        max-height: 60px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        line-height: 1.4;
        min-width: 200px;
    }

    .content-label {
        font-size: 0.8rem;
        color: var(--gray);
        margin-bottom: 3px;
        display: block;
    }

    /* Status Badges */
    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-sadere {
        background: rgba(52, 152, 219, 0.15);
        color: var(--primary);
    }

    .badge-varede {
        background: rgba(46, 204, 113, 0.15);
        color: var(--success);
    }

    .badge-eblaghiye {
        background: rgba(155, 89, 182, 0.15);
        color: #9b59b6;
    }

    .badge-javabiye {
        background: rgba(241, 196, 15, 0.15);
        color: #f1c40f;
    }

    /* Action Buttons */
    .btn-action {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-edit {
        background: rgba(52, 152, 219, 0.1);
        color: var(--primary);
        border: 1px solid rgba(52, 152, 219, 0.3);
    }

    .btn-edit:hover {
        background: var(--primary);
        color: white;
    }

    .btn-download {
        background: rgba(155, 89, 182, 0.1);
        color: #9b59b6;
        border: 1px solid rgba(155, 89, 182, 0.3);
        text-decoration: none;
        margin-right: 5px;
    }

    .btn-download:hover {
        background: #9b59b6;
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 15px;
        color: var(--gray);
    }

    .empty-state i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 10px;
        display: block;
    }

    /* Modal - FIXED FOR VISIBLE BUTTONS */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        animation: fadeIn 0.3s ease;
        overflow-y: auto;
        padding: 20px;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        max-width: 90%;
        width: 700px;
        margin: 20px auto;
        border-radius: 10px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        animation: slideIn 0.4s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: 90vh;
        position: relative;
    }

    @keyframes slideIn {
        from { transform: translateY(-30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        background: var(--primary);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
        position: sticky;
        top: 0;
        z-index: 20;
    }

    .modal-header h3 {
        font-size: 1.2rem;
        font-weight: 600;
    }

    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .modal-body {
        padding: 20px;
        overflow-y: auto;
        flex-grow: 1;
        max-height: calc(90vh - 140px); /* Adjusted for header and footer */
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .form-input {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--border);
        border-radius: 6px;
        font-size: 0.9rem;
        background: white;
        transition: all 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
        line-height: 1.5;
    }

    .checkbox-container {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: rgba(52, 152, 219, 0.05);
        border-radius: 6px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
        margin: 15px 0;
    }

    .checkbox-container:hover {
        border-color: var(--primary);
    }

    .checkbox-container input {
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
    }

    .modal-footer {
        padding: 20px;
        background: #f8f9fa;
        border-top: 2px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        flex-shrink: 0;
        position: sticky;
        bottom: 0;
        z-index: 20;
    }

    .btn-save {
        background: var(--success);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        min-width: 150px;
    }

    .btn-save:hover {
        background: #229954;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
    }

    .btn-cancel {
        background: var(--gray);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 120px;
    }

    .btn-cancel:hover {
        background: #7f8c8d;
    }

    /* Text Popup */
    .popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 2000;
        animation: fadeIn 0.3s ease;
        overflow-y: auto;
        padding: 20px;
    }

    .popup-content {
        background: white;
        max-width: 90%;
        width: 500px;
        margin: 5% auto;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        position: relative;
        animation: slideIn 0.4s ease;
    }

    .popup-close {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--danger);
        color: white;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .popup-title {
        color: var(--secondary);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border);
        font-size: 1.2rem;
    }

    .popup-text {
        max-height: 400px;
        overflow-y: auto;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        line-height: 1.8;
        white-space: pre-wrap;
        font-size: 0.95rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        body {
            padding: 10px;
        }
        
        .header-content {
            flex-direction: column;
            align-items: stretch;
        }
        
        .header-actions {
            justify-content: center;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .modal {
            padding: 10px;
        }
        
        .modal-content {
            margin: 10px auto;
            width: 95%;
            max-height: 95vh;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .modal-body {
            max-height: calc(95vh - 140px);
        }
        
        .modal-footer {
            flex-direction: column;
            gap: 10px;
        }
        
        .btn-save, .btn-cancel {
            width: 100%;
            justify-content: center;
        }
        
        .table-scroll-container {
            margin-left: -10px;
            margin-right: -10px;
            width: calc(100% + 20px);
        }
        
        .data-table {
            min-width: 1100px;
        }
    }

    @media (max-width: 480px) {
        .data-table {
            min-width: 1000px;
        }
        
        .modal-content {
            width: 98%;
        }
        
        .popup-content {
            width: 95%;
        }
    }

    /* Scroll indicator for table */
    .scroll-hint {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--primary);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: bounce 2s infinite;
        z-index: 5;
        opacity: 0.7;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(-50%) translateX(0); }
        50% { transform: translateY(-50%) translateX(-5px); }
    }

    .table-wrapper {
        position: relative;
    }
</style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="page-title">
                    <h1>📋 لیست مکاتیب</h1>
                </div>
                <div class="header-actions">
                    <a href="index.php" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        ثبت مکتوب جدید
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <div class="filters-grid">
                <input type="text" id="searchNumber" class="filter-input" placeholder="جستجوی نمبر مکتوب">
                <input type="text" id="searchSubject" class="filter-input" placeholder="جستجوی موضوع">
                <select id="filterType" class="filter-input">
                    <option value="">همه انواع مکتوب</option>
                    <option value="صادره">صادره</option>
                    <option value="وارده">وارده</option>
                </select>
                <select id="filterHifz" class="filter-input">
                    <option value="">همه وضعیت‌ها</option>
                    <option value="1">ابلاغیه</option>
                    <option value="0">جوابیه</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <div class="table-header">
                <div class="table-title">
                    <i class="fas fa-list"></i>
                    مکاتیب ثبت شده (<?= $total_records ?>)
                </div>
            </div>

            <?php if($total_records > 0): ?>
            <div class="table-wrapper">
                <div class="scroll-hint" title="برای دیدن بقیه جدول به سمت چپ اسکرول کنید">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="table-scroll-container">
                    <table class="data-table" id="dataTable">
                        <thead>
                            <tr>
                                <th>نوع</th>
                                <th>نمبر مکتوب</th>
                                <th>تاریخ</th>
                                <th>مرجع ارسال</th>
                                <th>مرسل‌الیه</th>
                                <th>مرجع اقدام</th>
                                <th>موضوع</th>
                                <th>دوسیه مربوطه</th>
                                <th>نوع مکاتبه</th>
                                <th>ضمائم</th>
                                <th>فایل</th>
                                <th>ویرایش</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($r = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <span class="badge <?= $r['maktub_type'] == 'صادره' ? 'badge-sadere' : 'badge-varede' ?>">
                                        <?= htmlspecialchars($r['maktub_type']) ?>
                                    </span>
                                </td>
                                <td class="col-number"><?= htmlspecialchars($r['maktub_number']) ?></td>
                                <td class="col-date"><?= htmlspecialchars($r['maktub_date']) ?></td>
                                <td><?= htmlspecialchars($r['sender_source']) ?></td>
                                <td><?= htmlspecialchars($r['mur_sal_aly']) ?></td>
                                <td><?= htmlspecialchars($r['marja_eghdam']) ?></td>
                                <td>
                                    <div>
                                        <span class="content-label">موضوع:</span>
                                        <div class="compact-text">
                                            <?= htmlspecialchars($r['subject']) ?>
                                        </div>
                                        <?php if(strlen($r['subject']) > 100): ?>
                                            <button onclick="showFullText('موضوع', '<?= htmlspecialchars(addslashes($r['subject'])) ?>')" 
                                                    class="btn-action" 
                                                    style="font-size:0.75rem; color:var(--primary); padding:2px 5px; background:none; border:none; cursor:pointer">
                                                بیشتر...
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="content-label">دوسیه:</span>
                                        <div class="compact-text">
                                            <?= htmlspecialchars($r['dosya_morba']) ?>
                                        </div>
                                        <?php if(strlen($r['dosya_morba']) > 100): ?>
                                            <button onclick="showFullText('دوسیه', '<?= htmlspecialchars(addslashes($r['dosya_morba'])) ?>')" 
                                                    class="btn-action" 
                                                    style="font-size:0.75rem; color:var(--success); padding:2px 5px; background:none; border:none; cursor:pointer">
                                                بیشتر...
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if($r['hifz_shud'] == 1): ?>
                                        <span class="badge badge-eblaghiye">ابلاغیه</span>
                                    <?php else: ?>
                                        <span class="badge badge-javabiye">جوابیه</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars(mb_substr($r['zamaym'], 0, 30)) . (mb_strlen($r['zamaym']) > 30 ? '...' : '') ?></td>
                                <td>
                                    <?php if($r['kpdfdesc']): ?>
                                        <a href="download.php?file=<?= urlencode($r['kpdfdesc']) ?>" 
                                           target="_blank" 
                                           class="btn-download btn-action" 
                                           title="دانلود فایل PDF">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    <?php else: ?>
                                        <span style="color:var(--gray); font-size:0.8rem;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn-edit btn-action" onclick='openModal(<?= json_encode($r) ?>)' title="ویرایش">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>مکتوبی یافت نشد</h3>
                <p>هنوز هیچ مکتوبی در سیستم ثبت نشده است.</p>
                <a href="index.php" class="btn-primary" style="margin-top: 15px; display: inline-flex;">
                    <i class="fas fa-plus-circle"></i>
                    ثبت اولین مکتوب
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>ویرایش مکتوب</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="form-grid">
                        <div>
                            <label class="form-label">نوع مکتوب</label>
                            <select name="maktub_type" id="maktub_type" class="form-input" required>
                                <option value="صادره">صادره</option>
                                <option value="وارده">وارده</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="form-label">نمبر مکتوب</label>
                            <input type="text" name="maktub_number" id="maktub_number" class="form-input" required>
                        </div>
                        
                        <div>
                            <label class="form-label">تاریخ</label>
                            <input type="date" name="maktub_date" id="maktub_date" class="form-input" required>
                        </div>
                        
                        <div>
                            <label class="form-label">مرجع ارسال</label>
                            <input type="text" name="sender_source" id="sender_source" class="form-input" required>
                        </div>
                        
                        <div>
                            <label class="form-label">مرسل‌الیه</label>
                            <input type="text" name="mur_sal_aly" id="mur_sal_aly" class="form-input" required>
                        </div>
                        
                        <div>
                            <label class="form-label">مرجع اقدام</label>
                            <input type="text" name="marja_eghdam" id="marja_eghdam" class="form-input">
                        </div>
                        
                        <div>
                            <label class="form-label">تعقیبی</label>
                            <input type="text" name="taqibi" id="taqibi" class="form-input">
                        </div>
                        
                        <div>
                            <label class="form-label">ضمائم</label>
                            <input type="text" name="zamaym" id="zamaym" class="form-input">
                        </div>
                    </div>
                    
                    <div style="margin: 20px 0;">
                        <label class="form-label">موضوع</label>
                        <textarea name="subject" id="subject" class="form-input form-textarea" required></textarea>
                    </div>
                    
                    <div style="margin: 20px 0;">
                        <label class="form-label">دوسیه مربوطه</label>
                        <textarea name="dosya_morba" id="dosya_morba" class="form-input form-textarea" required></textarea>
                    </div>
                    
                    <div class="checkbox-container" onclick="document.getElementById('hifz_shud').click()">
                        <input type="checkbox" name="hifz_shud" id="hifz_shud" value="1">
                        <label style="margin: 0; cursor: pointer; font-weight: 600;">مکتوب حفظ شده است (ابلاغیه)</label>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal()">لغو</button>
                    <button type="submit" name="update" class="btn-save">
                        <i class="fas fa-save"></i>
                        ذخیره تغییرات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Text Popup -->
    <div class="popup-overlay" id="textPopup">
        <div class="popup-content">
            <button class="popup-close" onclick="closePopup()">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="popup-title" id="popupTitle">مشاهده کامل متن</h3>
            <div class="popup-text" id="popupText"></div>
        </div>
    </div>

    <script>
        // Search and Filter
        const searchNumber = document.getElementById("searchNumber");
        const searchSubject = document.getElementById("searchSubject");
        const filterType = document.getElementById("filterType");
        const filterHifz = document.getElementById("filterHifz");

        function filterTable(){
            let num = searchNumber.value.toLowerCase();
            let sub = searchSubject.value.toLowerCase();
            let type = filterType.value;
            let hifz = filterHifz.value;

            document.querySelectorAll("#dataTable tbody tr").forEach(row=>{
                let rNum = row.querySelector("td:nth-child(2)").innerText.toLowerCase();
                let rSubject = row.querySelector("td:nth-child(7) .compact-text").innerText.toLowerCase();
                let rDosya = row.querySelector("td:nth-child(8) .compact-text").innerText.toLowerCase();
                let rType = row.querySelector("td:nth-child(1) .badge").innerText;
                let rHifz = row.querySelector("td:nth-child(9) .badge").innerText;
                
                let isHifz = rHifz.includes("ابلاغیه") ? "1" : "0";
                let textMatch = rSubject.includes(sub) || rDosya.includes(sub);
                
                let show =
                    rNum.includes(num) &&
                    textMatch &&
                    (type=="" || rType===type) &&
                    (hifz=="" || isHifz===hifz);

                row.style.display = show ? "" : "none";
            });
        }

        searchNumber.addEventListener('input', filterTable);
        searchSubject.addEventListener('input', filterTable);
        filterType.addEventListener('change', filterTable);
        filterHifz.addEventListener('change', filterTable);

        /* Modal Functions */
        function openModal(data){
            document.getElementById("editModal").style.display="block";
            document.body.style.overflow="hidden";
            
            // Fill all fields
            for(let k in data){
                let el = document.getElementById(k);
                if(el) {
                    if(el.type === 'checkbox') {
                        el.checked = data[k] == 1;
                    } else {
                        el.value = data[k];
                    }
                }
            }
            // Set select value
            document.getElementById('maktub_type').value = data.maktub_type;
            
            // Scroll modal to top
            document.querySelector('.modal-body').scrollTop = 0;
        }

        function closeModal(){
            document.getElementById("editModal").style.display="none";
            document.body.style.overflow="auto";
        }

        // Show Full Text
        function showFullText(type, text){
            event.preventDefault();
            document.getElementById('textPopup').style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            document.getElementById('popupTitle').textContent = 'مشاهده کامل ' + type;
            document.getElementById('popupText').textContent = text;
        }

        function closePopup(){
            document.getElementById('textPopup').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modals on outside click
        window.onclick = function(event) {
            let editModal = document.getElementById('editModal');
            let textPopup = document.getElementById('textPopup');
            
            if (event.target == editModal) closeModal();
            if (event.target == textPopup) closePopup();
        };

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closePopup();
            }
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                if (document.getElementById("editModal").style.display === "block") {
                    document.querySelector('.btn-save').click();
                }
            }
        });

        // Initialize
        window.onload = function() {
            searchNumber.focus();
            
            // Hide scroll hint after 5 seconds
            setTimeout(() => {
                const scrollHint = document.querySelector('.scroll-hint');
                if (scrollHint) {
                    scrollHint.style.opacity = '0';
                    setTimeout(() => {
                        scrollHint.style.display = 'none';
                    }, 500);
                }
            }, 5000);
            
            // Auto-hide scroll hint on scroll
            const tableContainer = document.querySelector('.table-scroll-container');
            if (tableContainer) {
                tableContainer.addEventListener('scroll', function() {
                    const scrollHint = document.querySelector('.scroll-hint');
                    if (scrollHint) {
                        scrollHint.style.opacity = '0';
                        setTimeout(() => {
                            scrollHint.style.display = 'none';
                        }, 300);
                    }
                });
            }
        };

        // Prevent form submission on Enter key in search fields
        searchNumber.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') e.preventDefault();
        });
        
        searchSubject.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') e.preventDefault();
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>
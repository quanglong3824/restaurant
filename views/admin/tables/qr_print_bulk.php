<?php // views/admin/tables/qr_print_bulk.php — Bulk Print QR Codes ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In hàng loạt mã QR - <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .print-controls {
            max-width: 210mm;
            margin: 0 auto 20px;
            padding: 15px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .print-controls h2 {
            font-family: 'Playfair Display', serif;
            color: #1a1a1a;
            font-size: 1.25rem;
        }

        .print-controls .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: #D4AF37;
            color: white;
        }

        .btn-primary:hover {
            background: #b8941f;
        }

        .btn-outline {
            background: white;
            border: 1px solid #ddd;
            color: #333;
        }

        .btn-outline:hover {
            background: #f5f5f5;
        }

        /* A4 Page Layout */
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 20px;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 10mm;
            page-break-after: always;
        }

        .qr-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(5, 1fr);
            gap: 5mm;
            height: calc(297mm - 20mm);
        }

        .qr-card {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 10px 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: white;
            position: relative;
        }

        .qr-card-header {
            text-align: center;
            margin-bottom: 6px;
            width: 100%;
        }

        .qr-card-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 11px;
            font-weight: 700;
            color: #D4AF37;
            letter-spacing: 0.8px;
            margin-bottom: 1px;
        }

        .qr-card-header p {
            font-size: 6px;
            color: #666;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .qr-divider {
            border-top: 1px solid #D4AF37;
            border-bottom: 1px solid #D4AF37;
            padding: 4px 0;
            margin: 4px 0;
            width: 100%;
            text-align: center;
        }

        .qr-divider h2 {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .qr-code-wrapper {
            position: relative;
            width: 70px;
            height: 70px;
            margin: 4px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* QR code canvas inside the wrapper */
        .qr-code-wrapper > div {
            width: 70px;
            height: 70px;
        }

        .qr-code-wrapper canvas {
            width: 70px;
            height: 70px;
        }

        .qr-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 18px;
            height: 18px;
            background: white;
            padding: 2px;
            border-radius: 3px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .qr-card-footer {
            text-align: center;
            margin-top: 6px;
        }

        .qr-card-footer p {
            font-size: 8px;
            font-weight: 600;
            color: #333;
            margin-bottom: 1px;
        }

        .qr-card-footer span {
            font-size: 6px;
            color: #888;
            text-transform: uppercase;
        }

        .page-info {
            text-align: center;
            padding-top: 10px;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            margin-top: 10px;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .print-controls {
                display: none !important;
            }

            .a4-page {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                min-height: auto !important;
                page-break-after: always;
                break-after: page;
            }

            .a4-page:last-child {
                page-break-after: auto;
                break-after: auto;
            }

            .qr-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8mm;
                width: 100%;
                height: auto !important;
            }

            .qr-card {
                border: 1px solid #ddd;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .page-info {
                display: none;
            }

            /* Hide empty placeholder cards */
            .qr-card[style*="visibility: hidden"] {
                display: none !important;
                visibility: hidden !important;
                position: absolute !important;
            }

            /* Ensure QR codes print properly */
            .qr-code-wrapper > div {
                page-break-inside: avoid;
            }

            /* Force background graphics printing */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="print-controls">
        <h2><i class="fas fa-qrcode"></i> In QR <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></h2>
        <div style="display: flex; gap: 10px;">
            <a href="<?= BASE_URL ?>/admin/tables?type=<?= $type ?>" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> In ngay
            </button>
        </div>
    </div>

    <?php if (empty($tables)): ?>
        <div class="a4-page">
            <div class="empty-message">
                <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem; color: #D4AF37;"></i>
                <h3>Không có <?= $type === 'room' ? 'phòng' : 'bàn' ?> nào có mã QR</h3>
                <p>Vui lòng tạo mã QR cho <?= $type === 'room' ? 'phòng' : 'bàn' ?> trước khi in.</p>
            </div>
        </div>
    <?php else: ?>
        <?php 
        $itemsPerPage = 10;
        $totalPages = ceil(count($tables) / $itemsPerPage);
        
        for ($page = 0; $page < $totalPages; $page++):
            $startIndex = $page * $itemsPerPage;
            $endIndex = min($startIndex + $itemsPerPage, count($tables));
            $pageTables = array_slice($tables, $startIndex, $itemsPerPage);
        ?>
            <div class="a4-page">
                <div class="qr-grid">
                    <?php foreach ($pageTables as $index => $t): ?>
                        <div class="qr-card">
                            <div class="qr-card-header">
                                <h1>AURORA HOTEL PLAZA</h1>
                                <p>Restaurant & Bar</p>
                            </div>
                            
                            <div class="qr-divider">
                                <h2><?= $type === 'room' ? 'PHÒNG' : 'BÀN' ?> <?= e(strtoupper($t['name'])) ?></h2>
                            </div>
                            
                            <div class="qr-code-wrapper">
                                <div id="qr-<?= $t['id'] ?>"></div>
                            </div>
                            
                            <div class="qr-card-footer">
                                <p>QUÉT MÃ ĐỂ ĐẶT MÓN</p>
                                <span>Cảm ơn Quý khách / Thank you!</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php 
                    // Fill empty cells to maintain grid layout
                    for ($i = count($pageTables); $i < $itemsPerPage; $i++): 
                    ?>
                        <div class="qr-card" style="visibility: hidden;"></div>
                    <?php endfor; ?>
                </div>
                
                <div class="page-info">
                    Trang <?= $page + 1 ?> / <?= $totalPages ?> • <?= count($tables) ?> <?= $type === 'room' ? 'phòng' : 'bàn' ?> • <?= date('d/m/Y H:i') ?>
                </div>
            </div>
        <?php endfor; ?>
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php foreach ($tables as $t): ?>
                <?php if (!empty($t['qr_token'])): ?>
                    new QRCode(document.getElementById('qr-<?= $t['id'] ?>'), {
                        text: '<?= BASE_URL ?>/q?t=<?= $t['qr_token'] ?>',
                        width: 70,
                        height: 70,
                        colorDark: '#000000',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.H,
                        margin: 0
                    });
                <?php endif; ?>
            <?php endforeach; ?>
        });
    </script>
</body>
</html>
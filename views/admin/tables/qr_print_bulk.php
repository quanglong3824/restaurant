<?php // views/admin/tables/qr_print_bulk.php — Bulk Print QR Codes ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In hàng loạt mã QR - <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        /* Continuous flow layout - no wasted space */
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5mm;
            padding: 5mm;
            width: 190mm;
            margin: 0 auto;
        }

        .qr-card {
            width: 42mm;
            height: 52mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            position: relative;
            page-break-inside: avoid;
            border: 0.1mm dashed #ddd; /* Cutting guide */
        }

        .qr-code-wrapper {
            position: relative;
            width: 35mm;
            height: 35mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* QR code canvas/image inside the specific container */
        .qr-code-wrapper > div canvas, 
        .qr-code-wrapper > div img {
            width: 35mm !important;
            height: 35mm !important;
            display: block;
        }

        .qr-logo-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8mm;
            height: 8mm;
            background: white;
            padding: 1mm;
            border-radius: 2px;
            z-index: 100;
            display: block;
        }

        .qr-table-name {
            margin-top: 2mm;
            font-size: 9px;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .page-break {
            page-break-after: always;
            break-after: page;
            display: block;
            height: 0;
            visibility: hidden;
        }

        @media print {
            @page {
                size: A4;
                margin: 5mm;
            }

            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .print-controls {
                display: none !important;
            }

            .qr-grid {
                width: 190mm;
                margin: 0 auto;
                gap: 5mm;
            }

            .qr-card {
                border-color: #eee;
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
        <div style="text-align: center; padding: 50px;">
            <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem; color: #D4AF37;"></i>
            <h3>Không có <?= $type === 'room' ? 'phòng' : 'bàn' ?> nào có mã QR</h3>
            <p>Vui lòng tạo mã QR cho <?= $type === 'room' ? 'phòng' : 'Bàn' ?> trước khi in.</p>
        </div>
    <?php else: ?>
        <div class="qr-grid">
            <?php foreach ($tables as $t): ?>
                <div class="qr-card">
                    <div class="qr-code-wrapper">
                        <div id="qr-<?= $t['id'] ?>"></div>
                        <img src="<?= BASE_URL ?>/public/src/logo/favicon.png" class="qr-logo-center">
                    </div>
                    <div class="qr-table-name">
                        <?= $type === 'room' ? 'ROOM' : 'TABLE' ?> <?= e(strtoupper($t['name'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php foreach ($tables as $t): ?>
                <?php if (!empty($t['qr_token'])): ?>
                    new QRCode(document.getElementById('qr-<?= $t['id'] ?>'), {
                        text: '<?= BASE_URL ?>/q?t=<?= $t['qr_token'] ?>',
                        width: 150,
                        height: 150,
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
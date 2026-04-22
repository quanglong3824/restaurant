<?php // views/admin/tables/qr_instructions.php — Customer Instructions for QR Ordering ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hướng dẫn đặt món QR - Aurora Hotel Plaza</title>
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
            max-width: 297mm;
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

        /* A4 Landscape Page */
        .a4-page {
            width: 297mm;
            height: 210mm;
            margin: 0 auto 20px;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 15mm;
            page-break-after: always;
            position: relative;
        }

        .instruction-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            height: 100%;
        }

        .instruction-card {
            border: 2px solid #D4AF37;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            background: white;
        }

        .instruction-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #D4AF37;
        }

        .instruction-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
            object-fit: contain;
        }

        .instruction-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 800;
            color: #D4AF37;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .instruction-header p {
            font-size: 11px;
            color: #666;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .instruction-title {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        .instruction-steps {
            flex: 1;
        }

        .step {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            align-items: flex-start;
        }

        .step-number {
            width: 36px;
            height: 36px;
            background: #D4AF37;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .step-content {
            flex: 1;
        }

        .step-content h3 {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .step-content p {
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }

        .instruction-footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #D4AF37;
            text-align: center;
        }

        .instruction-footer h2 {
            font-size: 14px;
            font-weight: 700;
            color: #D4AF37;
            margin-bottom: 8px;
        }

        .hotline {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 10px;
        }

        .hotline-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .hotline-item i {
            color: #D4AF37;
            font-size: 16px;
        }

        .thank-you {
            margin-top: 15px;
            font-size: 11px;
            color: #888;
            font-style: italic;
        }

        .qr-demo {
            text-align: center;
            margin: 15px 0;
        }

        .qr-demo-box {
            display: inline-block;
            padding: 10px;
            border: 1px solid #D4AF37;
            border-radius: 8px;
            background: #fafafa;
        }

        .qr-demo-box p {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        @media print {
            @page {
                size: A4 landscape;
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
                padding: 10mm !important;
                width: 100% !important;
                height: auto !important;
                page-break-after: always;
            }

            .a4-page:last-child {
                page-break-after: auto;
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
        <h2><i class="fas fa-file-instruction"></i> Hướng dẫn đặt món QR</h2>
        <div style="display: flex; gap: 10px;">
            <a href="<?= BASE_URL ?>/admin/tables" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> In hướng dẫn
            </button>
        </div>
    </div>

    <?php
    $logoPath = BASE_URL . '/public/src/logo/favicon.png';
    $restaurantName = 'AURORA HOTEL PLAZA';
    $restaurantSubtitle = 'RESTAURANT & BAR';
    $hotline1 = '098 765 4321';
    $hotline2 = '012 345 6789';
    ?>

    <div class="a4-page">
        <div class="instruction-container">
            <?php for ($i = 1; $i <= 2; $i++): ?>
            <div class="instruction-card">
                <div class="instruction-header">
                    <img src="<?= $logoPath ?>" alt="Logo" class="instruction-logo" onerror="this.style.display='none'">
                    <h1><?= $restaurantName ?></h1>
                    <p><?= $restaurantSubtitle ?></p>
                </div>

                <div class="instruction-title">
                    <i class="fas fa-qrcode" style="color: #D4AF37; margin-right: 8px;"></i>
                    HƯỚNG DẪN ĐẶT MÓN QUA QR CODE
                </div>

                <div class="instruction-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3>Quét mã QR trên bàn</h3>
                            <p>Sử dụng camera điện thoại hoặc ứng dụng quét QR để quét mã</p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3>Truy cập menu điện tử</h3>
                            <p>Hệ thống sẽ tự động mở trang web menu của nhà hàng</p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3>Chọn món ăn & đồ uống</h3>
                            <p>Duyệt qua thực đơn và chọn những món bạn muốn gọi</p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h3>Xác nhận đơn hàng</h3>
                            <p>Kiểm tra lại đơn và nhấn "Đặt món" để gửi đến bếp</p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <h3>Thanh toán</h3>
                            <p>Nhân viên sẽ mang hóa đơn đến bàn sau khi dùng bữa</p>
                        </div>
                    </div>
                </div>

                <div class="qr-demo">
                    <div class="qr-demo-box">
                        <i class="fas fa-mobile-alt" style="font-size: 24px; color: #D4AF37;"></i>
                        <p>Mở camera & quét mã QR</p>
                    </div>
                </div>

                <div class="instruction-footer">
                    <h2><i class="fas fa-headset"></i> HỖ TRỢ 24/7</h2>
                    <div class="hotline">
                        <div class="hotline-item">
                            <i class="fas fa-phone-alt"></i>
                            <span><?= $hotline1 ?></span>
                        </div>
                        <div class="hotline-item">
                            <i class="fas fa-phone-alt"></i>
                            <span><?= $hotline2 ?></span>
                        </div>
                    </div>
                    <p class="thank-you">Cảm ơn Quý khách / Thank you for dining with us!</p>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
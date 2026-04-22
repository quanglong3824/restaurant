<?php // views/admin/tables/qr_download.php — Print QR Code ?>
<div class="qr-print-container">
    <div class="no-print mb-4 d-flex gap-2">
        <button onclick="window.print()" class="btn-gold"><i class="fas fa-print me-1"></i> In mã QR</button>
        <a href="<?= BASE_URL ?>/admin/qr-codes" class="btn-ghost"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
    </div>

    <div class="qr-card-printable" id="printableArea">
        <div class="qr-card-header">
            <i class="fas fa-utensils"></i>
            <h2>AURORA</h2>
            <p>RESTAURANT</p>
        </div>
        
        <div class="qr-code-wrapper">
            <div id="qrcode"></div>
            <?php 
                $logoPath = BASE_URL . '/public/src/logo/favicon.png'; 
            ?>
            <img src="<?= $logoPath ?>" class="qr-logo" alt="Logo" onerror="console.error('QR Logo failed to load from: ' + this.src)">
        </div>
        
        <div class="qr-card-footer">
            <div class="table-number">BÀN <?= e($tableName) ?></div>
            <div class="table-number-en" style="font-size: 1.2rem; color: #666; margin-bottom: 15px;">TABLE <?= e($tableName) ?></div>
            <p>Quét mã để xem menu & đặt món</p>
            <small>Scan to order</small>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const url = '<?= BASE_URL ?>/q?t=<?= $token ?>';
        new QRCode(document.getElementById("qrcode"), {
            text: url,
            width: 250,
            height: 250,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H, // Changed to H (High) for better logo tolerance
            margin: 2
        });
    });
</script>

<style>
    .qr-print-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px 0;
    }
    
    .qr-card-printable {
        width: 350px;
        background: white;
        padding: 40px;
        border: 1px solid #eee;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    
    .qr-card-header {
        margin-bottom: 30px;
    }
    
    .qr-card-header i {
        font-size: 2rem;
        color: var(--gold);
        margin-bottom: 10px;
    }
    
    .qr-card-header h2 {
        font-family: 'Playfair Display', serif;
        font-weight: 800;
        letter-spacing: 2px;
        margin: 0;
    }
    
    .qr-card-header p {
        font-size: 0.7rem;
        letter-spacing: 4px;
        margin: 0;
        color: var(--text-muted);
    }
    
    .qr-code-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
        padding: 15px;
        background: #fdfdfd;
        border-radius: 12px;
        position: relative;
    }

    .qr-logo {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 50px; /* Slightly larger */
        height: 50px;
        background: white;
        padding: 4px;
        border-radius: 10px;
        border: 2px solid #eee;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10; /* Ensure it's on top */
        display: block !important;
    }
    
    .qr-card-footer .table-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--gold-dark);
        margin-bottom: 10px;
    }
    
    .qr-card-footer p {
        font-weight: 600;
        margin: 0;
    }
    
    .qr-card-footer small {
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    @media print {
        .no-print { display: none !important; }
        body { background: white; }
        .admin-sidebar, .admin-topbar { display: none !important; }
        .admin-body { margin-left: 0 !important; padding: 0 !important; }
        .qr-card-printable { 
            box-shadow: none !important; 
            border: none !important;
            width: 100% !important;
        }
    }
</style>

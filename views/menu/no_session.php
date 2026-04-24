<?php 
// views/menu/no_session.php — No active session found (Bilingual Vietnamese/English)
$currentLang = $_COOKIE['aurora_lang'] ?? $_SESSION['lang'] ?? 'vi';
$isEn = $currentLang === 'en';

// Text translations
$TEXT = [
    'session_not_found_vi' => 'KHÔNG TÌM THẤY PHIÊN',
    'session_not_found_en' => 'SESSION NOT FOUND',
    'desc_vi' => 'Hệ thống không thể xác định thiết bị này hoặc bạn chưa quét mã QR tại bàn.',
    'desc_en' => 'System could not identify this device or you have not scanned the QR code at the table.',
    'how_to_start_vi' => 'Cách bắt đầu:',
    'how_to_start_en' => 'How to start:',
    'step1_vi' => '1. Tìm mã QR dán trên bàn/phòng của bạn.',
    'step1_en' => '1. Find the QR code sticker on your table/room.',
    'step2_vi' => '2. Sử dụng Camera điện thoại để quét mã.',
    'step2_en' => '2. Use your phone Camera to scan the code.',
    'step3_vi' => '3. Cấp quyền vị trí để xem thực đơn.',
    'step3_en' => '3. Provide location verification to view the menu.',
    'back_home_vi' => 'VỀ TRANG CHỦ',
    'back_home_en' => 'BACK TO HOME',
];
?>
<div class="no-session-container">
    <div class="no-session-card">
        <div class="no-session-icon">
            <i class="fas fa-search-location"></i>
        </div>
        
        <!-- Bilingual Title -->
        <h2>
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['session_not_found_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['session_not_found_en'] ?></span>
        </h2>
        
        <!-- Bilingual Description -->
        <p>
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['desc_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['desc_en'] ?></span>
        </p>
        
        <div class="instruction-box">
            <!-- Bilingual Instruction Header -->
            <h4>
                <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['how_to_start_vi'] ?></span>
                <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['how_to_start_en'] ?></span>
            </h4>
            <ul>
                <li>
                    <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['step1_vi'] ?></span>
                    <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['step1_en'] ?></span>
                </li>
                <li>
                    <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['step2_vi'] ?></span>
                    <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['step2_en'] ?></span>
                </li>
                <li>
                    <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['step3_vi'] ?></span>
                    <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['step3_en'] ?></span>
                </li>
            </ul>
        </div>

        <!-- Language Toggle Button -->
        <button onclick="toggleNoSessionLang()" class="lang-toggle-btn" style="width: 100%; padding: 10px; font-size: 0.85rem; border-radius: 10px; font-weight: 600; border: 1px solid #e2e8f0; cursor: pointer; background: #f8fafc; color: #64748b; margin-bottom: 12px;">
            <i class="fas fa-globe me-2"></i>
            <span><?= $isEn ? '🇻🇳 Tiếng Việt' : '🇬🇧 English' ?></span>
        </button>

        <a href="<?= BASE_URL ?>" class="btn-gold-premium w-100">
            <i class="fas fa-home me-2"></i>
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['back_home_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['back_home_en'] ?></span>
        </a>
    </div>
</div>

<script>
function toggleNoSessionLang() {
    const currentLang = '<?= $currentLang ?>';
    const newLang = currentLang === 'vi' ? 'en' : 'vi';
    document.cookie = 'aurora_lang=' + newLang + '; path=/; max-age=31536000; SameSite=Lax';
    localStorage.setItem('aurora_lang', newLang);
    window.location.reload();
}
</script>

<style>
.no-session-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
    background: #f8fafc;
}
.no-session-card {
    background: #fff;
    padding: 40px 30px;
    border-radius: 28px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    text-align: center;
    max-width: 400px;
}
.no-session-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 20px;
}
.no-session-card h2 {
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 15px;
    letter-spacing: 1px;
}
.no-session-card p {
    color: #64748b;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 25px;
}
.instruction-box {
    text-align: left;
    background: #f8fafc;
    padding: 20px;
    border-radius: 16px;
    margin-bottom: 30px;
    border: 1px dashed #cbd5e1;
}
.instruction-box h4 {
    font-size: 0.85rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 10px;
}
.instruction-box ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.instruction-box li {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 8px;
}
.lang-toggle-btn:hover {
    background: var(--gold, #d4af37) !important;
    color: #fff !important;
    border-color: var(--gold, #d4af37) !important;
}
</style>
<?php 
// views/orders/thank_you.php — Thank You Page (Bilingual Vietnamese/English)
$currentLang = $_COOKIE['aurora_lang'] ?? $_SESSION['lang'] ?? 'vi';
$isEn = $currentLang === 'en';

// Text translations
$TEXT = [
    'payment_successful_vi' => 'Thanh toán thành công!',
    'payment_successful_en' => 'Payment Successful!',
    'thank_you_vi' => 'Cảm ơn bạn đã dùng餐 tại Aurora.<br>Chúc bạn một ngày tuyệt vời!',
    'thank_you_en' => 'Thank you for dining with us at Aurora.<br>Have a wonderful day and see you again!',
    'rate_experience_vi' => 'Đánh giá trải nghiệm',
    'rate_experience_en' => 'Rate your experience',
    'ratings_vi' => ['Rất tệ', 'Tệ', 'Bình thường', 'Hài lòng', 'Tuyệt vời! Cảm ơn bạn!'],
    'ratings_en' => ['Very Bad', 'Bad', 'Normal', 'Satisfied', 'Excellent! Thank you!'],
    'home_vi' => 'TRANG CHỦ',
    'home_en' => 'HOME',
];
?>
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; text-align: center; padding: 20px;">
    
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); max-width: 400px; width: 100%; border: 1px solid var(--border);">
        <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px;">
            <i class="fas fa-check"></i>
        </div>
        
        <!-- Bilingual Title -->
        <h2 class="playfair" style="color: var(--text-dark); margin-bottom: 10px; font-weight: 800; font-size: 1.8rem;">
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['payment_successful_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['payment_successful_en'] ?></span>
        </h2>
        
        <!-- Bilingual Subtitle -->
        <p style="color: var(--text-muted); line-height: 1.5; font-size: 0.95rem; margin-bottom: 25px;">
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'block' ?>;"><?= $TEXT['thank_you_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'block' : 'none' ?>;"><?= $TEXT['thank_you_en'] ?></span>
        </p>
        
        <hr style="border: 0; border-top: 1px dashed #cbd5e1; margin-bottom: 25px;">
        
        <!-- Bilingual Rating Header -->
        <h4 style="font-size: 1rem; color: var(--text-dark); font-weight: 700; margin-bottom: 15px;">
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['rate_experience_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['rate_experience_en'] ?></span>
        </h4>
        
        <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 25px; font-size: 2rem; color: #cbd5e1;" id="starRating">
            <i class="fas fa-star" onclick="rate(1)" style="cursor: pointer; transition: color 0.2s;"></i>
            <i class="fas fa-star" onclick="rate(2)" style="cursor: pointer; transition: color 0.2s;"></i>
            <i class="fas fa-star" onclick="rate(3)" style="cursor: pointer; transition: color 0.2s;"></i>
            <i class="fas fa-star" onclick="rate(4)" style="cursor: pointer; transition: color 0.2s;"></i>
            <i class="fas fa-star" onclick="rate(5)" style="cursor: pointer; transition: color 0.2s;"></i>
        </div>
        
        <p id="ratingMessage" style="color: var(--gold); font-weight: 700; font-size: 0.9rem; min-height: 20px; margin-bottom: 20px; transition: all 0.3s; opacity: 0; transform: translateY(10px);"></p>

        <!-- Language Toggle Button -->
        <button onclick="toggleThankYouLang()" class="lang-toggle" style="width: 100%; padding: 10px; font-size: 0.85rem; border-radius: 10px; font-weight: 600; border: 1px solid #e2e8f0; cursor: pointer; background: #f8fafc; color: #64748b; margin-bottom: 15px;">
            <i class="fas fa-globe me-2"></i>
            <span><?= $isEn ? '🇻🇳 Tiếng Việt' : '🇬🇧 English' ?></span>
        </button>

        <button onclick="window.location.href='<?= BASE_URL ?>/qr/landing'" class="btn-gold" style="width: 100%; padding: 12px; font-size: 1rem; border-radius: 12px; font-weight: 700; border: none; cursor: pointer;">
            <i class="fas fa-home me-2"></i>
            <span class="lang-vi" style="display: <?= $isEn ? 'none' : 'inline' ?>;"><?= $TEXT['home_vi'] ?></span>
            <span class="lang-en" style="display: <?= $isEn ? 'inline' : 'none' ?>;"><?= $TEXT['home_en'] ?></span>
        </button>
    </div>
</div>

<script>
const thankYouLang = '<?= $currentLang ?>';
const ratingsVi = <?= json_encode($TEXT['ratings_vi']) ?>;
const ratingsEn = <?= json_encode($TEXT['ratings_en']) ?>;

function rate(stars) {
    const starEls = document.querySelectorAll('#starRating i');
    starEls.forEach((el, index) => {
        if (index < stars) {
            el.style.color = '#f59e0b';
            el.style.transform = 'scale(1.1)';
        } else {
            el.style.color = '#cbd5e1';
            el.style.transform = 'scale(1)';
        }
    });
    
    const msgEl = document.getElementById('ratingMessage');
    const msgs = thankYouLang === 'en' ? ratingsEn : ratingsVi;
    
    msgEl.textContent = msgs[stars - 1];
    msgEl.style.opacity = '1';
    msgEl.style.transform = 'translateY(0)';
}

function toggleThankYouLang() {
    const newLang = thankYouLang === 'vi' ? 'en' : 'vi';
    document.cookie = 'aurora_lang=' + newLang + '; path=/; max-age=31536000; SameSite=Lax';
    localStorage.setItem('aurora_lang', newLang);
    window.location.reload();
}

// Clear cart storage
sessionStorage.removeItem('aurora_cart');
</script>
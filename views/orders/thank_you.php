<?php 
// views/orders/thank_you.php — Thank You Page (English Only)
?>
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; text-align: center; padding: 20px;">
    
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); max-width: 400px; width: 100%; border: 1px solid var(--border);">
        <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px;">
            <i class="fas fa-check"></i>
        </div>
        
        <h2 class="playfair" style="color: var(--text-dark); margin-bottom: 10px; font-weight: 800; font-size: 1.8rem;">
            Payment Successful!
        </h2>
        
        <p style="color: var(--text-muted); line-height: 1.5; font-size: 0.95rem; margin-bottom: 25px;">
            Thank you for dining with us at Aurora.<br>Have a wonderful day and see you again!
        </p>
        
        <hr style="border: 0; border-top: 1px dashed #cbd5e1; margin-bottom: 25px;">
        
        <h4 style="font-size: 1rem; color: var(--text-dark); font-weight: 700; margin-bottom: 15px;">
            Rate your experience
        </h4>
        
        <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 25px; font-size: 2rem; color: #cbd5e1;" id="starRating">
            <i class="fas fa-star" onclick="rate(1)" style="cursor: pointer; transition: color 0.2s;"></i>
            <i class="fas fa-star" onclick="rate(2)" style="cursor: pointer; transition: color 0.2s;"></i>
            <i class="fas fa-star" onclick="rate(3)" style="cursor: pointer; transition: color 0.2s;"></i>
            <i class="fas fa-star" onclick="rate(4)" style="cursor: pointer; transition: color 0.2s;"></i>
            <i class="fas fa-star" onclick="rate(5)" style="cursor: pointer; transition: color 0.2s;"></i>
        </div>
        
        <p id="ratingMessage" style="color: var(--gold); font-weight: 700; font-size: 0.9rem; min-height: 20px; margin-bottom: 20px; transition: all 0.3s; opacity: 0; transform: translateY(10px);"></p>

        <button onclick="window.location.href='<?= BASE_URL ?>/qr/landing'" class="btn-gold" style="width: 100%; padding: 12px; font-size: 1rem; border-radius: 12px; font-weight: 700; border: none; cursor: pointer;">
            <i class="fas fa-home me-2"></i>
            HOME
        </button>
    </div>
</div>

<script>
const ratings = ['Very Bad', 'Bad', 'Normal', 'Satisfied', 'Excellent! Thank you!'];

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
    msgEl.textContent = ratings[stars - 1];
    msgEl.style.opacity = '1';
    msgEl.style.transform = 'translateY(0)';
}

sessionStorage.removeItem('aurora_cart');
</script>
<?php // views/home.php — Simple Home Landing ?>
<div class="home-container">
    <div class="home-content">
        <div class="home-logo">
            <img src="<?= BASE_URL ?>/public/src/logo/logo-white-ui.png" alt="Aurora Logo">
        </div>
        <h1 class="playfair">AURORA</h1>
        <p class="subtitle">HOTEL PLAZA RESTAURANT</p>

        <div class="home-actions">
            <?php if (Auth::isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/tables" class="btn-gold-premium">
                    <i class="fas fa-desktop me-2"></i> ENTER SYSTEM
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login" class="btn-gold-premium">
                    <i class="fas fa-sign-in-alt me-2"></i> LOGIN
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .home-container {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
        text-align: center;
        padding: 20px;
    }

    .home-logo img {
        max-width: 150px;
        margin-bottom: 20px;
        filter: drop-shadow(0 0 10px rgba(212, 175, 55, 0.3));
    }

    .home-content h1 {
        font-size: 3rem;
        letter-spacing: 5px;
        margin-bottom: 5px;
        color: var(--gold);
    }

    .home-content .subtitle {
        font-size: 0.9rem;
        letter-spacing: 3px;
        color: #94a3b8;
        margin-bottom: 40px;
        text-transform: uppercase;
    }

    .btn-gold-premium {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #d4af37, #b8860b);
        color: white;
        text-decoration: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s;
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.2);
    }

    .btn-gold-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(212, 175, 55, 0.4);
        color: white;
    }
</style>
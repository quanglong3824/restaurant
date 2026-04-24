<?php 
// views/menu/no_session.php — No active session found
?>
<div class="no-session-container">
    <div class="no-session-card">
        <div class="no-session-icon">
            <i class="fas fa-search-location"></i>
        </div>
        <h2>SESSION NOT FOUND</h2>
        <p>System could not identify this device or you have not scanned the QR code at the table.</p>
        
        <div class="instruction-box">
            <h4>How to start:</h4>
            <ul>
                <li>1. Find the QR code sticker on your table/room.</li>
                <li>2. Use your phone Camera to scan the code.</li>
                <li>3. Provide location verification to view the menu.</li>
            </ul>
        </div>

        <a href="<?= BASE_URL ?>" class="btn-gold-premium w-100">BACK TO HOME</a>
    </div>
</div>

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
</style>

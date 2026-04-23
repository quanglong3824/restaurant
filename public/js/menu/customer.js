console.log("%c AURORA POS SYSTEM %c Optimized by LongDev ", "background:#1e293b;color:#d4af37;padding:5px;border-radius:5px 0 0 5px;font-weight:bold", "background:#d4af37;color:#1e293b;padding:5px;border-radius:0 5px 5px 0;font-weight:bold");

/**
 * Customer Menu JS — Aurora Restaurant
 * Bilingual Support VI/EN
 */

let cart = [];
let currentItem = null;

// Translation dictionary for notifications and messages
const MESSAGES = {
    vi: {
        devMode: 'DEV MODE: Kiểm tra vị trí đã tắt. Bạn có thể test từ bất kỳ đâu.',
        locationVerified: '✅ Vị trí đã xác thực. Bạn đang trong khu vực nhà hàng.',
        locationNotVerified: '⚠️ Chưa xác thực vị trí. Vui lòng bấm nút xác thực.',
        addedToCart: 'Đã thêm',
        orderSuccess: 'Xác nhận đặt món thành công!',
        orderError: 'Lỗi gửi order. Vui lòng thử lại.',
        networkError: 'Lỗi kết nối máy chủ. Vui lòng kiểm tra mạng.',
        callWaiterConfirm: 'Bạn muốn gọi nhân viên phục vụ?',
        callPaymentConfirm: 'Bạn muốn yêu cầu thanh toán?',
        requestSent: 'Yêu cầu đã được gửi đến nhân viên!',
        requestError: 'Gửi yêu cầu thất bại.',
        connectionError: 'Lỗi kết nối.',
        emptyCart: 'Giỏ hàng đang trống.',
        continueOrdering: 'TIẾP TỤC CHỌN MÓN',
        outOfStock: 'Hết hàng',
        noMenu: 'Chưa có thực đơn',
        contactStaff: 'Vui lòng liên hệ nhân viên để được hỗ trợ',
        noResult: 'Không tìm thấy món phù hợp',
        clearSearch: 'Xoá tìm kiếm',
        yourCart: 'Giỏ hàng của bạn',
        viewCart: 'XEM GIỎ',
        orderNotes: 'GHI CHÚ ĐƠN HÀNG',
        orderNotesPlaceholder: 'VD: Không lấy hành, ít cay...',
        total: 'Tổng cộng',
        confirmOrder: 'XÁC NHẬN ĐẶT MÓN',
        addToOrder: 'THÊM VÀO ĐƠN HÀNG',
        processing: 'ĐANG XỬ LÝ...',
        locationChecking: 'Đang kiểm tra...',
        locationVerified: 'Đã xác thực',
        locationDenied: 'Bị từ chối',
        locationOutOfRange: 'Ngoài phạm vi',
        locationOK: 'OK',
        startExperience: 'BẮT ĐẦU TRẢI NGHIỆM',
        verifying: 'ĐANG XÁC THỰC...',
        success: 'XÁC THỰC THÀNH CÔNG!',
        retry: 'THỬ LẠI XÁC THỰC',
        browserNoSupport: 'Trình duyệt không hỗ trợ định vị.',
        permissionDenied: 'BẠN ĐÃ TỪ CHỐI ĐỊNH VỊ. Vui lòng cho phép trong Cài đặt và Tải lại trang.',
        locationUnavailable: 'Thông tin vị trí không khả dụng.',
        locationTimeout: 'Hết thời gian yêu cầu vị trí.',
        pleaseAllowLocation: 'Vui lòng cấp quyền định vị: ',
        youAreFar: 'Bạn đang ở xa nhà hàng',
        scanAtTable: 'Vui lòng quét mã tại bàn.',
        meters: 'm',
        leavingArea: 'BẠN ĐÃ RỜI KHỎI KHU VỰC',
        menuLocked: 'Thực đơn tạm thời bị khoá để bảo mật đơn hàng',
        distance: 'Khoảng cách',
        pleaseReturn: 'Vui lòng quay lại khu vực để tiếp tục',
        privacyNotice: 'Bằng cách tiếp tục, bạn đồng ý với chính sách bảo mật của chúng tôi.',
        forSecurity: 'Để bảo mật đơn hàng và tốc độ phục vụ tối ưu, vui lòng xác nhận vị trí của bạn.',
        instantConfirm: 'Đơn hàng xác nhận ngay lập tức',
        noHistory: 'Không lưu lịch sử vị trí',
        autoDelete: 'Tự động xoá khi rời đi',
        quickOptions: 'Tuỳ chọn nhanh',
        noOptions: 'Chưa có Tùy chọn cấu hình sẵn cho món này (Thiết lập tại Admin).',
        notePlaceholder: 'Ghi chú thêm (No onion, less spicy...)',
        preliminaryBill: 'Hoá đơn tạm tính',
        requestPayment: 'YÊU CẦU THANH TOÁN',
        continueOrdering2: 'TIẾP TỤC ĐẶT MÓN',
        noItemsOrdered: 'Bàn chưa có món nào được gọi.',
        subtotal: 'Tổng tiền món',
        orderDetails: 'Chi tiết đơn hàng',
        callReception: 'Call Reception',
        callWaiter: 'Call Waiter',
        bill: 'Bill',
        payment: 'Payment',
        refresh: 'Làm mới',
        myTables: 'Bàn của tôi'
    },
    en: {
        devMode: 'DEV MODE: Location checking disabled. You can test from anywhere.',
        locationVerified: '✅ Location verified. You are in the restaurant area.',
        locationNotVerified: '⚠️ Location not verified. Please press the verify button.',
        addedToCart: 'Added',
        orderSuccess: 'Order confirmed successfully!',
        orderError: 'Order submission error. Please try again.',
        networkError: 'Connection error. Please check your network.',
        callWaiterConfirm: 'Do you want to call the waiter?',
        callPaymentConfirm: 'Do you want to request payment?',
        requestSent: 'Request sent to staff!',
        requestError: 'Request failed.',
        connectionError: 'Connection error.',
        emptyCart: 'Your cart is empty.',
        continueOrdering: 'CONTINUE ORDERING',
        outOfStock: 'Out of stock',
        noMenu: 'No menu available',
        contactStaff: 'Please contact staff for assistance',
        noResult: 'No dishes found matching your search',
        clearSearch: 'Clear search',
        yourCart: 'Your cart',
        viewCart: 'VIEW',
        orderNotes: 'ORDER NOTES',
        orderNotesPlaceholder: 'e.g., No onion, less spicy...',
        total: 'Total',
        confirmOrder: 'CONFIRM ORDER',
        addToOrder: 'ADD TO ORDER',
        processing: 'PROCESSING...',
        locationChecking: 'Checking...',
        locationVerified: 'Verified',
        locationDenied: 'Denied',
        locationOutOfRange: 'Out of range',
        locationOK: 'OK',
        startExperience: 'START EXPERIENCE',
        verifying: 'VERIFYING...',
        success: 'VERIFICATION SUCCESSFUL!',
        retry: 'RETRY VERIFICATION',
        browserNoSupport: 'Browser does not support geolocation.',
        permissionDenied: 'LOCATION PERMISSION DENIED. Please allow in Settings and Reload page.',
        locationUnavailable: 'Location information unavailable.',
        locationTimeout: 'Location request timeout.',
        pleaseAllowLocation: 'Please allow geolocation: ',
        youAreFar: 'You are far from the restaurant',
        scanAtTable: 'Please scan the code at the table.',
        meters: 'm',
        leavingArea: 'YOU HAVE LEFT THE AREA',
        menuLocked: 'Menu is temporarily locked for order security',
        distance: 'Distance',
        pleaseReturn: 'Please return to the area to continue',
        privacyNotice: 'By continuing, you agree to our privacy policy.',
        forSecurity: 'For order security and optimal service speed, please confirm your location.',
        instantConfirm: 'Instant order confirmation',
        noHistory: 'No location history stored',
        autoDelete: 'Auto-delete when leaving',
        quickOptions: 'Quick Options',
        noOptions: 'No options configured for this item (Set up at Admin).',
        notePlaceholder: 'Additional notes (No onion, less spicy...)',
        preliminaryBill: 'Preliminary Bill',
        requestPayment: 'REQUEST PAYMENT',
        continueOrdering2: 'CONTINUE ORDERING',
        noItemsOrdered: 'No items ordered yet.',
        subtotal: 'Subtotal',
        orderDetails: 'Order Details',
        callReception: 'Call Reception',
        callWaiter: 'Call Waiter',
        bill: 'Bill',
        payment: 'Payment',
        refresh: 'Refresh',
        myTables: 'My Tables'
    }
};

// Get current language
function getCurrentLang() {
    return localStorage.getItem('aurora_lang') || 'vi';
}

// Get message by key
function t(key) {
    const lang = getCurrentLang();
    return MESSAGES[lang][key] || MESSAGES['vi'][key] || key;
}

/** Đọc giá trị cookie theo tên */
function _getCookie(name) {
    const m = document.cookie.match('(?:^|; )' + name.replace(/([.*+?^=!:${}()|[\]/\\])/g, '\\$1') + '=([^;]*)');
    return m ? decodeURIComponent(m[1]) : null;
}

document.addEventListener('DOMContentLoaded', () => {
    // Skip location features in DEV_MODE
    if (CUSTOMER_CONFIG.devMode) {
        console.log("%c DEV MODE: Location checking disabled ", "background:#10b981;color:#fff;padding:5px;border-radius:5px;font-weight:bold");
        // Auto-verify location and show menu
        localStorage.setItem(`locationVerified_table_${CUSTOMER_CONFIG.tableId}`, 'true');
        document.getElementById('locationOverlay')?.style.setProperty('display', 'none');
        document.getElementById('menuWrapper')?.style.setProperty('display', 'block');
        document.getElementById('frozenOverlay')?.style.setProperty('display', 'none');
    }
    
    createLocationIndicator();
    if (!CUSTOMER_CONFIG.devMode) {
        checkLocation();
        startLocationWatcher();
    }
    loadCart();
    setupCategoryNav();
    setupSearch();
    updateCartUI();
    
    // Automatically show bill if coming from status page's 'Check Bill' button
    if (CUSTOMER_CONFIG.showBill && typeof showBillTam === 'function') {
        setTimeout(() => showBillTam(), 800);
    }
});

// ── Location Status Badge (in Header) ────────────────────────────
function createLocationIndicator() {
    // Use badge in header instead of floating indicator
    const badge = document.getElementById('locStatusBadge');
    if (!badge) return;
    
    // In DEV_MODE, show special status
    if (CUSTOMER_CONFIG.devMode) {
        badge.style.display = 'flex';
        updateLocationIndicator('granted', 'DEV MODE');
        badge.addEventListener('click', () => {
            showToast('🔧 DEV MODE: Kiểm tra vị trí đã tắt. Bạn có thể test từ bất kỳ đâu.');
        });
        return;
    }
    
    // Hide badge initially - will show after verification
    badge.style.display = 'none';
}

function updateLocationIndicator(status, label) {
    const badge = document.getElementById('locStatusBadge');
    const labelEl = document.getElementById('locStatusText');
    if (!badge) return;
    
    // Reset classes
    badge.classList.remove('loc-granted', 'loc-denied', 'loc-checking');
    badge.classList.add(`loc-${status}`);
    
    if (labelEl) {
        labelEl.textContent = label || 'Định vị';
    }
}

let locationWatcher = null;
async function startLocationWatcher() {
    // Only monitor if already verified and wrapper is visible
    if (localStorage.getItem(`locationVerified_table_${CUSTOMER_CONFIG.tableId}`) !== 'true') return;
    if (!navigator.geolocation) return;

    // Kiểm tra quyền trước khi gọi watchPosition để tránh popup lặp
    try {
        if (navigator.permissions) {
            const permStatus = await navigator.permissions.query({ name: 'geolocation' });
            if (permStatus.state === 'denied') {
                console.warn('Geolocation permission denied, skipping watcher');
                updateLocationIndicator('denied', 'Bị từ chối');
                return;
            }
            if (permStatus.state === 'granted') {
                updateLocationIndicator('granted', 'Đã xác thực');
            }
            // Listen for permission changes
            permStatus.onchange = () => {
                if (permStatus.state === 'denied') {
                    updateLocationIndicator('denied', 'Bị từ chối');
                    if (locationWatcher !== null) {
                        navigator.geolocation.clearWatch(locationWatcher);
                        locationWatcher = null;
                    }
                } else if (permStatus.state === 'granted') {
                    updateLocationIndicator('granted', 'Đã xác thực');
                }
            };
        }
    } catch(e) {
        // permissions API not supported, proceed anyway
    }

    // Use watchPosition for high efficiency/real-time updates
    locationWatcher = navigator.geolocation.watchPosition(
        (position) => {
            const distance = calculateDistance(
                position.coords.latitude, 
                position.coords.longitude, 
                CUSTOMER_CONFIG.restaurantCoords.lat, 
                CUSTOMER_CONFIG.restaurantCoords.lng
            );

            const frozenOverlay = document.getElementById('frozenOverlay');
            const frozenDistVal = document.getElementById('frozenDistVal');
            
            if (distance > CUSTOMER_CONFIG.maxDistance) {
                // Out of range -> Freeze everything
                updateLocationIndicator('denied', `Ngoài phạm vi (${Math.round(distance)}m)`);
                if (frozenOverlay) {
                    frozenOverlay.style.display = 'flex';
                    if (frozenDistVal) frozenDistVal.textContent = Math.round(distance);
                    document.body.style.overflow = 'hidden';
                }
            } else {
                // Back in range -> Unfreeze
                updateLocationIndicator('granted', `OK (${Math.round(distance)}m)`);
                if (frozenOverlay) {
                    frozenOverlay.style.display = 'none';
                    document.body.style.overflow = '';
                }
            }
        },
        (err) => {
            console.warn("Location monitoring error:", err.message);
            if (err.code === err.PERMISSION_DENIED) {
                updateLocationIndicator('denied', 'Bị từ chối');
            }
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 5000 }
    );
}

function checkLocation() {
    const overlay = document.getElementById('locationOverlay');
    const wrapper = document.getElementById('menuWrapper');
    const btn = document.getElementById('btnAllowLocation');
    const errorEl = document.getElementById('locationError');

    // Skip if already verified in this session
    if (localStorage.getItem(`locationVerified_table_${CUSTOMER_CONFIG.tableId}`) === 'true') {
        if (overlay) overlay.style.display = 'none';
        if (wrapper) wrapper.style.display = 'block';
        
        // Show badge in header and start watcher
        const badge = document.getElementById('locStatusBadge');
        if (badge) badge.style.display = 'flex';
        updateLocationIndicator('granted', 'Đã xác thực');
        startLocationWatcher();
        return;
    }

    // Force overlay visible if not verified
    if (overlay) overlay.style.display = 'flex';
    if (wrapper) wrapper.style.display = 'none';
    updateLocationIndicator('checking', 'Chưa xác thực');

    const requestLocation = async (isInitial = false) => {
        // Only update button state if not an initial silent check
        if (isInitial !== true) {
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ĐANG XÁC THỰC...';
                btn.disabled = true;
            }
            if (errorEl) errorEl.style.display = 'none';
            updateLocationIndicator('checking', 'Đang kiểm tra...');
        }

        if (!navigator.geolocation) {
            if (isInitial !== true) showLocError("Trình duyệt không hỗ trợ định vị.");
            updateLocationIndicator('denied', 'Không hỗ trợ');
            return;
        }

        // Kiểm tra quyền trước bằng Permissions API (tránh popup lặp khi reload)
        if (isInitial === true && navigator.permissions) {
            try {
                const perm = await navigator.permissions.query({ name: 'geolocation' });
                if (perm.state === 'denied') {
                    // Quyền bị từ chối → không gọi getCurrentPosition (tránh popup)
                    updateLocationIndicator('denied', 'Bị từ chối');
                    return;
                }
                if (perm.state === 'prompt') {
                    // Quyền chưa được cấp → không gọi silent check (chờ user bấm nút)
                    updateLocationIndicator('checking', 'Chờ xác thực');
                    return;
                }
                // perm.state === 'granted' → tiếp tục kiểm tra khoảng cách
            } catch(e) {
                // Permissions API không hỗ trợ, tiếp tục bình thường
            }
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                const distance = calculateDistance(
                    userLat, userLng, 
                    CUSTOMER_CONFIG.restaurantCoords.lat, 
                    CUSTOMER_CONFIG.restaurantCoords.lng
                );

                // Update real-time distance badge
                const liveDist = document.getElementById('liveDistance');
                const distVal = document.getElementById('distVal');
                if (liveDist && distVal) {
                    distVal.textContent = Math.round(distance);
                    liveDist.style.display = 'inline-flex';
                    // Color code based on distance
                    if (distance > CUSTOMER_CONFIG.maxDistance) {
                        liveDist.style.background = 'rgba(239, 68, 68, 0.1)';
                        liveDist.style.color = '#f87171';
                        liveDist.style.borderColor = 'rgba(239, 68, 68, 0.3)';
                    } else {
                        liveDist.style.background = 'rgba(16, 185, 129, 0.1)';
                        liveDist.style.color = '#10b981';
                        liveDist.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                    }
                }

                // If this was an initial check, stop here. 
                // Wait for user to click button for final gatekeep.
                if (isInitial === true) return;


                if (distance > CUSTOMER_CONFIG.maxDistance) {
                    showLocError(`Bạn đang ở xa nhà hàng (${Math.round(distance)}m). Vui lòng quét mã tại bàn.`);
                    updateLocationIndicator('denied', `Xa (${Math.round(distance)}m)`);
                } else {
                    // Xác thực thành công — lưu trạng thái + visitor token vào localStorage
                    localStorage.setItem(`locationVerified_table_${CUSTOMER_CONFIG.tableId}`, 'true');

                    // Lưu visitor token vào localStorage ĐỊNH DANH TOÀN CỤC THIẾT BỊ
                    const _vt = _getCookie('qr_visitor_token');
                    if (_vt) {
                        localStorage.setItem('qr_global_device_id', _vt);
                        localStorage.setItem(`qr_vt_${CUSTOMER_CONFIG.tableId}`, _vt);
                    }
                    if (btn) {
                        btn.innerHTML = '<i class="fas fa-check-circle me-2"></i> XÁC THỰC THÀNH CÔNG!';
                        btn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                    }
                    updateLocationIndicator('granted', `OK (${Math.round(distance)}m)`);
                    
                    // Show badge in header
                    const badge = document.getElementById('locStatusBadge');
                    if (badge) badge.style.display = 'flex';
                    
                    startLocationWatcher();
                    setTimeout(() => {
                        if (overlay) {
                            overlay.style.transition = 'opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                            overlay.style.opacity = '0';
                            setTimeout(() => {
                                overlay.style.display = 'none';
                                wrapper.style.display = 'block';
                            }, 600);
                        } else {
                            wrapper.style.display = 'block';
                        }
                    }, 500);
                }

            },
            (err) => {
                if (isInitial !== true) {
                    let msg = "Vui lòng cấp quyền định vị: ";
                    switch(err.code) {
                        case err.PERMISSION_DENIED: 
                            msg = "BẠN ĐÃ TỪ CHỐI ĐỊNH VỊ. Vui lòng cho phép trong Cài đặt và Tải lại trang."; 
                            updateLocationIndicator('denied', 'Bị từ chối');
                            break;
                        case err.POSITION_UNAVAILABLE: 
                            msg += "Thông tin vị trí không khả dụng."; 
                            updateLocationIndicator('denied', 'Không có tín hiệu');
                            break;
                        case err.TIMEOUT: 
                            msg += "Hết thời gian yêu cầu vị trí."; 
                            updateLocationIndicator('denied', 'Hết thời gian');
                            break;
                    }
                    showLocError(msg);
                }
            },
            { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
        );
    };

    // Auto trigger silent check to update distance UI (chỉ khi đã có quyền)
    setTimeout(() => {
        if (localStorage.getItem(`locationVerified_table_${CUSTOMER_CONFIG.tableId}`) !== 'true') {
            requestLocation(true);
        }
    }, 800);

    if (btn) {
        btn.addEventListener('click', () => requestLocation(false));
    }

    function showLocError(msg) {
        if (errorEl) {
            errorEl.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i> ${msg}`;
            errorEl.style.display = 'block';
        }
        if (btn) {
            btn.innerHTML = '<i class="fas fa-location-arrow me-2"></i> THỬ LẠI XÁC THỰC';
            btn.disabled = false;
        }
    }
}

/**
 * Calculates distance in meters using Haversine formula
 */
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371e3; // Earth radius in meters
    const φ1 = lat1 * Math.PI / 180;
    const φ2 = lat2 * Math.PI / 180;
    const Δφ = (lat2 - lat1) * Math.PI / 180;
    const Δλ = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
              Math.cos(φ1) * Math.cos(φ2) *
              Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c;
}

function setupCategoryNav() {
    const pills = document.querySelectorAll('.cat-pill');
    pills.forEach(pill => {
        pill.addEventListener('click', (e) => {
            // Smooth scroll to section handled by browser if using href="#id"
            // But we want to ensure the pills update
            pills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
        });
    });

    // Intersection Observer to update active category on scroll
    const sections = document.querySelectorAll('.menu-section');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id.replace('cat-', '');
                pills.forEach(p => {
                    if (p.dataset.category === id) {
                        p.classList.add('active');
                        // Scroll pill into view horizontally
                        p.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    } else {
                        p.classList.remove('active');
                    }
                });
            }
        });
    }, { threshold: 0.2 });

    sections.forEach(s => observer.observe(s));
}

function setupSearch() {
    const searchInput = document.getElementById('menuSearch');
    const typeBtns = document.querySelectorAll('.type-btn');
    const categoryPills = document.querySelectorAll('.cat-pill');
    
    if (!searchInput) return;

    const filterMenu = () => {
        const query = searchInput.value.toLowerCase().trim();
        const activeType = document.querySelector('.type-btn.active').dataset.type;
        const cards = document.querySelectorAll('.menu-item-card');
        
        cards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const nameEn = (card.dataset.nameEn || '').toLowerCase();
            const type = card.dataset.type;
            
            const isMatchText = name.includes(query) || nameEn.includes(query);
            const isMatchType = (activeType === 'all' || type === activeType);
            
            card.style.display = (isMatchText && isMatchType) ? 'flex' : 'none';
        });

        // Hide/Show sections and category pills
        document.querySelectorAll('.menu-section').forEach(section => {
            const sectionType = section.dataset.type;
            const hasVisibleItems = Array.from(section.querySelectorAll('.menu-item-card'))
                .some(card => card.style.display !== 'none');
            
            const shouldShowSection = hasVisibleItems && (activeType === 'all' || sectionType === activeType);
            section.style.display = shouldShowSection ? 'block' : 'none';
            
            // Sync category pills visibility
            const catId = section.id.replace('cat-', '');
            categoryPills.forEach(pill => {
                if (pill.dataset.category === catId) {
                    pill.style.display = shouldShowSection ? 'inline-block' : 'none';
                }
            });
        });
        
        // Ensure "All" pill is always visible if not searching
        const allPill = document.querySelector('.cat-pill[data-category="all"]');
        if (allPill) allPill.style.display = (query === '' && activeType === 'all') ? 'inline-block' : 'none';
    };

    searchInput.addEventListener('input', filterMenu);
    
    typeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            typeBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filterMenu();
            
            // Scroll to top of menu when changing type
            window.scrollTo({ top: document.querySelector('.menu-sections').offsetTop - 100, behavior: 'smooth' });
        });
    });
}

/**
 * Format currency - European Standard
 * Format: 1000000 → "1.000.000 VND"
 */
function formatCurrency(amount) {
    const num = parseFloat(amount) || 0;
    // Format with thousand separator (dot) and add VND suffix
    return num.toLocaleString('de-DE') + ' VND';
}

function loadCart() {
    const saved = localStorage.getItem(`cart_table_${CUSTOMER_CONFIG.tableId}`);
    if (saved) {
        try {
            cart = JSON.parse(saved);
        } catch (e) {
            cart = [];
        }
    }
}

function saveCart() {
    localStorage.setItem(`cart_table_${CUSTOMER_CONFIG.tableId}`, JSON.stringify(cart));
    updateCartUI();
}

function updateCartUI() {
    const cartBar = document.getElementById('cartBar');
    const cartCount = document.getElementById('cartCount');
    const cartTotal = document.getElementById('cartTotal');
    
    const totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    if (totalCount > 0) {
        cartBar.classList.remove('hidden');
        cartCount.textContent = totalCount;
        cartTotal.textContent = formatCurrency(totalPrice);
    } else {
        cartBar.classList.add('hidden');
    }

    // Also update modal if open
    updateCartModal();
}

function quickAdd(itemId, itemName, itemPrice, itemNameEn = '') {
    // Validate data
    if (!itemId || !itemName || isNaN(itemPrice)) {
        console.error('Invalid item data:', { itemId, itemName, itemPrice });
        showToast('Lỗi: Không thể thêm món này');
        return;
    }
    
    const id = parseInt(itemId);
    const name = itemName;
    const nameEn = itemNameEn || '';
    const price = parseFloat(itemPrice);
    
    const existing = cart.find(item => item.id === id && !item.note);
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({ id, name, nameEn, price, quantity: 1, note: '' });
    }
    saveCart();
    showToast(`${t('addedToCart')} ${name}`);
}

function showToast(msg) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> <span>${msg}</span>`;
    document.body.appendChild(toast);
    
    // Add CSS for toast if not exists
    if (!document.getElementById('toastStyles')) {
        const style = document.createElement('style');
        style.id = 'toastStyles';
        style.innerHTML = `
            .toast-notification {
                position: fixed;
                top: 20px;
                left: 0;
                right: 0;
                margin: auto;
                background: rgba(15, 23, 42, 0.9);
                color: white;
                padding: 12px 25px;
                border-radius: 50rem;
                z-index: 9999;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                animation: toastFadeIn 0.3s forwards, toastFadeOut 0.3s 2.7s forwards;
                font-weight: 600;
                font-size: 0.9rem;
                white-space: nowrap;
                max-width: -moz-fit-content;
                max-width: fit-content;
            }
            @keyframes toastFadeIn { from { top: -50px; opacity: 0; } to { top: 20px; opacity: 1; } }
            @keyframes toastFadeOut { from { top: 20px; opacity: 1; } to { top: -50px; opacity: 0; } }
        `;
        document.head.appendChild(style);
    }

    setTimeout(() => toast.remove(), 3000);
}

// Update indicator label on language change
function updateIndicatorLang() {
    const indicator = document.getElementById('locStatusIndicator');
    if (indicator && CUSTOMER_CONFIG.devMode) {
        const label = indicator.querySelector('.loc-label');
        if (label) label.textContent = 'DEV MODE';
    } else if (indicator) {
        const isVerified = localStorage.getItem(`locationVerified_table_${CUSTOMER_CONFIG.tableId}`) === 'true';
        const label = indicator.querySelector('.loc-label');
        if (label) {
            if (isVerified) {
                label.textContent = t('locationVerified');
            } else {
                label.textContent = t('locationNotVerified');
            }
        }
    }
}

// Show item detail by ID - reads data from DOM card attributes
function showItemDetailById(itemId) {
    const card = document.querySelector(`.menu-item-card[data-id="${itemId}"]`);
    if (!card) {
        console.error('Card not found for item:', itemId);
        return;
    }
    
    // Build item object from DOM data attributes (kebab-case)
    const item = {
        id: parseInt(card.dataset.id),
        name: card.dataset.name || '',
        name_en: card.dataset['name-en'] || '',
        price: parseFloat(card.dataset.price),
        description: card.dataset.description || '',
        description_en: card.dataset['description-en'] || '',
        image: card.dataset.image || '',
        note_options: card.dataset.options || '',
        note_options_en: card.dataset['options-en'] || ''
    };
    
    // Validate item data
    if (!item.id || isNaN(item.price)) {
        console.error('Invalid item data:', item);
        showToast('Lỗi: Không thể xem chi tiết món này');
        return;
    }
    
    showItemDetail(item);
}

function showItemDetail(item) {
    // FALLBACK cực mạnh: Đọc từ JSON, nếu ko có thì móc từ DOM Dataset
    let rawVi = item.note_options || '';
    let rawEn = item.note_options_en || '';
    
    const card = document.querySelector(`.menu-item-card[data-id="${item.id}"]`);
    if (card) {
        if (!rawVi) rawVi = card.dataset.options || '';
        if (!rawEn) rawEn = card.dataset['options-en'] || '';
    }

    const optsVi = rawVi.split(',').map(o => o.trim()).filter(Boolean);
    const optsEn = rawEn.split(',').map(o => o.trim()).filter(Boolean);
    
    // Mix song ngữ
    const isEn = (document.documentElement.lang === 'en');
    let displayOpts = [];
    
    if (isEn && optsEn.length > 0) {
        displayOpts = optsEn; 
    } else {
        optsVi.forEach((val, idx) => {
            const enVal = optsEn[idx];
            displayOpts.push(enVal ? `${val} / ${enVal}` : val);
        });
    }
    
    currentItem = { ...item, quantity: 1, note: '' };
    document.getElementById('detailName').textContent = typeof currentLang !== 'undefined' && currentLang === 'en' && item.name_en ? item.name_en : item.name;
    document.getElementById('detailPrice').textContent = formatCurrency(item.price);
    document.getElementById('detailDesc').textContent = item.description || (typeof currentLang !== 'undefined' && currentLang === 'en' ? 'No description for this item.' : 'Không có mô tả cho món ăn này.');
    document.getElementById('detailQty').textContent = '1';
    document.getElementById('detailNote').value = '';
    
    const imgContainer = document.getElementById('detailImg');
    if (item.image) {
        imgContainer.style.backgroundImage = `url(${CUSTOMER_CONFIG.baseUrl}/public/uploads/${item.image})`;
        imgContainer.innerHTML = '';
    } else {
        imgContainer.style.backgroundImage = 'none';
        imgContainer.style.backgroundColor = '#f1f5f9';
        imgContainer.innerHTML = '<i class="fas fa-utensils" style="font-size:3rem; color:#cbd5e1; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);"></i>';
    }

    // Render Options Chips (Trùng tu UI)
    const optsWrap = document.getElementById('detailOptsWrap');
    const optsContainer = document.getElementById('detailOptsContainer');
    optsContainer.innerHTML = '';
    
    if (displayOpts.length > 0) {
        optsWrap.style.display = 'block';
        displayOpts.forEach((opt) => {
            const chip = document.createElement('div');
            // Cập nhật class premium cho chip
            chip.className = 'opt-chip-premium';
            chip.innerHTML = `<span>${opt}</span><i class="fas fa-check-circle check-icon"></i>`;
            
            chip.onclick = () => {
                const noteInput = document.getElementById('detailNote');
                let currentNote = noteInput.value.trim();
                const optParts = currentNote.split(',').map(p => p.trim()).filter(Boolean);
                
                const optIdx = optParts.indexOf(opt);
                if (optIdx > -1) {
                    optParts.splice(optIdx, 1);
                    chip.classList.remove('active');
                } else {
                    optParts.push(opt);
                    chip.classList.add('active');
                }
                noteInput.value = optParts.join(', ');
            };
            optsContainer.appendChild(chip);
        });
    } else {
        optsWrap.style.display = 'block';
        optsContainer.innerHTML = '<span style="color:#94a3b8; font-size:0.75rem;"><i class="fas fa-info-circle me-1"></i>Chưa có Tùy chọn cấu hình sẵn cho món này (Thiết lập tại Admin).</span>';
    }

    updateDetailTotal();
    document.getElementById('itemDetailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeItemDetail() {
    document.getElementById('itemDetailModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function changeDetailQty(delta) {
    if (!currentItem) return;
    currentItem.quantity = Math.max(1, currentItem.quantity + delta);
    document.getElementById('detailQty').textContent = currentItem.quantity;
    updateDetailTotal();
}

function updateDetailTotal() {
    const total = currentItem.price * currentItem.quantity;
    const btnAdd = document.getElementById('btnAddOrder');
    if (btnAdd) {
        btnAdd.innerHTML = `<i class="fas fa-cart-plus me-2"></i> TH\u00caM V\u00c0O \u0110\u01a0N H\u00c0NG — ${formatCurrency(total)}`;
    }
}

function addFromDetail() {
    currentItem.note = document.getElementById('detailNote').value.trim();
    
    // Find item with SAME ID and SAME NOTE
    const existing = cart.find(item => item.id === currentItem.id && item.note === currentItem.note);
    if (existing) {
        existing.quantity += currentItem.quantity;
    } else {
        cart.push({ ...currentItem });
    }
    
    saveCart();
    closeItemDetail();
    showToast(`Đã thêm ${currentItem.name}`);
}

function toggleCartModal() {
    const modal = document.getElementById('cartModal');
    const isHidden = modal.classList.contains('hidden');
    
    if (isHidden) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        updateCartModal();
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function updateCartModal() {
    const container = document.getElementById('cartItemsList');
    const modalTotal = document.getElementById('modalCartTotal');
    
    if (cart.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-shopping-basket fa-3x text-light mb-3"></i>
                <p class="text-muted">Giỏ hàng đang trống.</p>
                <button class="btn-gold mt-3" onclick="toggleCartModal()">TIẾP TỤC CHỌN MÓN</button>
            </div>
        `;
        modalTotal.textContent = '0₫';
        return;
    }

    let html = '';
    let total = 0;
    
    cart.forEach((item, index) => {
        let displayName = typeof currentLang !== 'undefined' && currentLang === 'en' && (item.nameEn || item.name_en) ? (item.nameEn || item.name_en) : item.name;
        let noteLabel = typeof currentLang !== 'undefined' && currentLang === 'en' ? 'Note' : 'Lưu ý';
        total += item.price * item.quantity;
        html += `
            <div class="cart-item" style="display:flex; justify-content:space-between; align-items:center; padding:15px 0; border-bottom:1px solid var(--border);">
                <div style="flex:1;">
                    <div style="font-weight:700; color:var(--text-dark);">${displayName}</div>
                    <div style="color:var(--gold-dark); font-weight:600; font-size:0.85rem;">${formatCurrency(item.price)}</div>
                    ${item.note ? `<div style="font-style:italic; font-size:0.75rem; color:var(--text-light); margin-top:4px;">${noteLabel}: ${item.note}</div>` : ''}
                </div>
                <div class="qty-selector" style="background:#f1f5f9; padding:5px 10px; border-radius:10px; display:flex; align-items:center; gap:15px; scale:0.8;">
                    <button class="qty-btn" style="width:30px; height:30px; font-size:0.8rem;" onclick="changeCartQty(${index}, -1)"><i class="fas fa-minus"></i></button>
                    <span class="qty-value" style="font-size:1rem; min-width:20px;">${item.quantity}</span>
                    <button class="qty-btn" style="width:30px; height:30px; font-size:0.8rem;" onclick="changeCartQty(${index}, 1)"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    modalTotal.textContent = formatCurrency(total);
}

function changeCartQty(index, delta) {
    cart[index].quantity += delta;
    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }
    saveCart();
    if (cart.length === 0) {
        toggleCartModal();
    }
}

async function submitOrder() {
    if (cart.length === 0) return;

    const notes = document.getElementById('orderNotes').value;
    const btn = document.getElementById('btnSubmitOrder');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';

    const formData = new FormData();
    formData.append('cart', JSON.stringify(cart));
    formData.append('notes', notes);

    try {
        const response = await fetch(`${CUSTOMER_CONFIG.baseUrl}/qr/order/submit`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            cart = [];
            saveCart();
            showToast('Xác nhận đặt món thành công!');
            setTimeout(() => {
                window.location.href = `${CUSTOMER_CONFIG.baseUrl}/qr/order/status`;
            }, 1000);
        } else {
            alert(result.error || 'Lỗi gửi order. Vui lòng thử lại.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (e) {
        console.error(e);
        alert('Lỗi kết nối máy chủ. Vui lòng kiểm tra mạng.');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

async function callWaiter(type) {
    if (!confirm(type === 'payment' ? 'Bạn muốn yêu cầu thanh toán?' : 'Bạn muốn gọi nhân viên phục vụ?')) return;

    try {
        const url = type === 'payment' ? `${CUSTOMER_CONFIG.baseUrl}/qr/support/request-bill` : `${CUSTOMER_CONFIG.baseUrl}/qr/support/call-waiter`;
        const response = await fetch(url, { method: 'POST' });
        
        const result = await response.json();
        if (result.success) {
            showToast(result.message || 'Yêu cầu đã được gửi đến nhân viên!');
            if (type === 'payment') {
                showPaymentOverlay();
            }
        } else {
            alert(result.error || 'Gửi yêu cầu thất bại.');
        }
    } catch (e) {
        alert('Lỗi kết nối.');
    }
}

function showPaymentOverlay() {
    let overlay = document.getElementById('paymentLoadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'paymentLoadingOverlay';
        overlay.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.95); z-index:99999; display:flex; flex-direction:column; justify-content:center; align-items:center; backdrop-filter:blur(5px); animation:fadeIn 0.3s;';
        overlay.innerHTML = `
            <div class="spinner" style="width:50px; height:50px; border-width:4px; border-color:#d4af37 transparent #d4af37 transparent; margin-bottom:20px;"></div>
            <h3 style="color:#1e293b; font-weight:800; font-family:'Playfair Display', serif; text-align:center;">Đang xử lý Thanh Toán</h3>
            <p style="color:#64748b; font-size:0.9rem; margin-top:10px; text-align:center; max-width:80%;">Vui lòng chờ nhân viên mang hóa đơn đến bàn. <br>Hệ thống tự động chuyển trang khi hoàn tất!</p>
        `;
        document.body.appendChild(overlay);
    }
}

// Polling kiểm tra trạng thái bill
function startStatusPolling() {
    const checkStatus = async () => {
        try {
            const res = await fetch(`${CUSTOMER_CONFIG.baseUrl}/qr/order/poll-status?t=${Date.now()}`);
            const data = await res.json();
            
            if (data.status === 'completed') {
                window.location.href = `${CUSTOMER_CONFIG.baseUrl}/qr/thank-you`;
            } else if (data.status === 'wait_payment') {
                showPaymentOverlay();
            } else if (data.status === 'open' || data.status === 'idle') {
                const overlay = document.getElementById('paymentLoadingOverlay');
                if (overlay) overlay.remove();
            }
        } catch(e) {}
    };

    // Chạy ngay lập tức lần đầu khi load trang
    checkStatus();
    // Sau đó mới lặp lại mỗi 5s
    setInterval(checkStatus, 5000);
}

// Bắt đầu polling ngay khi khởi động trang Khách
if (window.location.pathname.includes('/qr/')) {
    startStatusPolling();
}

// Close modals when clicking backdrop
document.querySelectorAll('.modal-backdrop').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
});

// Language Toggle
function toggleLanguage() {
    currentLang = currentLang === 'vi' ? 'en' : 'vi';
    localStorage.setItem('aurora_lang', currentLang);
    document.cookie = "aurora_lang=" + currentLang + "; path=/; max-age=31536000; SameSite=Lax";
    location.reload();
}

// Clear Search
function clearMenuSearch() {
    const searchEl = document.getElementById('menuSearch');
    const clearBtn = document.getElementById('btnClearSearch');
    if (searchEl) {
        searchEl.value = '';
        if (clearBtn) clearBtn.style.display = 'none';
        // Trigger input event to re-filter
        searchEl.dispatchEvent(new Event('input'));
    }
}

// Close Bill Modal
function closeBillTam() {
    document.getElementById('billTamModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Format Price (alias for formatCurrency)
function formatPrice(price) {
    return formatCurrency(price);
}

// Show Bill
function showBillTam() {
    document.getElementById('billTamModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Auto-hide Headers on Scroll (Premium UX)
let lastScrollY = window.scrollY;
let scrollThreshold = 10;
let headerHidden = false;

function initHeaderScrollHide() {
    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;
        const scrollDiff = Math.abs(currentScrollY - lastScrollY);
        
        // Only trigger if scrolled enough
        if (scrollDiff < scrollThreshold) return;
        
        const headers = document.querySelectorAll('.menu-header-animated');
        if (headers.length === 0) {
            lastScrollY = currentScrollY;
            return;
        }
        
        // Scrolling down and past threshold - hide headers
        if (currentScrollY > lastScrollY && currentScrollY > 150) {
            if (!headerHidden) {
                headers.forEach(header => {
                    header.style.transform = 'translateY(-100%)';
                    header.style.opacity = '0';
                });
                headerHidden = true;
            }
        } 
        // Scrolling up - show headers
        else if (currentScrollY < lastScrollY) {
            if (headerHidden) {
                headers.forEach(header => {
                    header.style.transform = 'translateY(0)';
                    header.style.opacity = '1';
                });
                headerHidden = false;
            }
        }
        
        lastScrollY = currentScrollY;
    }, { passive: true });
}

// Add CSS for animated headers
function addHeaderAnimationStyles() {
    if (document.getElementById('headerAnimationStyles')) return;
    
    const style = document.createElement('style');
    style.id = 'headerAnimationStyles';
    style.textContent = `
        .menu-header-animated,
        .category-nav.menu-header-animated {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
                        opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform, opacity;
        }
        
        .category-nav.menu-header-animated {
            transform: translateY(0) !important;
            position: sticky;
            top: 0;
            z-index: 99;
        }
    `;
    document.head.appendChild(style);
}

// Initialize header scroll hide on DOM ready
addHeaderAnimationStyles();
initHeaderScrollHide();

// Scroll Spy for Category Navigation
function initScrollSpy() {
    const sections = document.querySelectorAll('.menu-section');
    const navLinks = document.querySelectorAll('.cat-pill');
    
    if (sections.length === 0 || navLinks.length === 0) return;
    
    // Remove existing observer if any
    if (window.scrollSpyObserver) {
        window.scrollSpyObserver.disconnect();
    }
    
    const observerOptions = {
        root: null,
        rootMargin: '-100px 0px -50% 0px',
        threshold: 0
    };
    
    window.scrollSpyObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                if (!id) return;
                
                const categoryId = id.replace('cat-', '');
                
                navLinks.forEach(link => {
                    if (link.dataset.category === categoryId) {
                        link.classList.add('active');
                        // Auto-scroll nav to keep active pill visible
                        link.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    } else {
                        link.classList.remove('active');
                    }
                });
            }
        });
    }, observerOptions);
    
    sections.forEach(section => {
        if (section.style.display !== 'none') {
            window.scrollSpyObserver.observe(section);
        }
    });
}

// Initialize scroll spy when menu is shown
function initMenuNavigation() {
    setTimeout(initScrollSpy, 300);
}

// Type Tab Filter
function initTypeFilter() {
    const typeTabs = document.querySelectorAll('.type-tab');
    typeTabs.forEach(btn => {
        btn.addEventListener('click', () => {
            typeTabs.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const type = btn.dataset.type;
            
            document.querySelectorAll('.menu-section').forEach(sec => {
                sec.style.display = (type === 'all' || sec.dataset.type === type) ? 'block' : 'none';
            });
            
            document.querySelectorAll('.cat-pill[data-type]').forEach(pill => {
                pill.style.display = (type === 'all' || pill.dataset.type === type) ? 'inline-block' : 'none';
            });
            
            // Re-init scroll spy for visible sections
            setTimeout(initScrollSpy, 100);
        });
    });
}

// Category Pill Click - Smooth Scroll
function initCategoryPillClick() {
    document.querySelectorAll('.cat-pill').forEach(pill => {
        pill.addEventListener('click', (e) => {
            e.preventDefault();
            const category = pill.dataset.category;
            
            document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            
            if (category === 'all') {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                const section = document.getElementById(`cat-${category}`);
                if (section && section.style.display !== 'none') {
                    const headerOffset = 180;
                    const sectionTop = section.getBoundingClientRect().top + window.pageYOffset - headerOffset;
                    window.scrollTo({ top: sectionTop, behavior: 'smooth' });
                }
            }
        });
    });
}

// Initialize everything when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
} else {
    initAll();
}

function initAll() {
    // Wait for menu wrapper to be visible
    const checkMenuVisible = setInterval(() => {
        const wrapper = document.getElementById('menuWrapper');
        const isVerified = localStorage.getItem(`locationVerified_table_${CUSTOMER_CONFIG.tableId}`) === 'true';
        
        if (wrapper && (wrapper.style.display === 'block' || isVerified)) {
            clearInterval(checkMenuVisible);
            initTypeFilter();
            initCategoryPillClick();
            initMenuNavigation();
        }
    }, 200);
    
    // Also init on location verify
    const btn = document.getElementById('btnAllowLocation');
    if (btn) {
        btn.addEventListener('click', () => {
            setTimeout(() => {
                initTypeFilter();
                initCategoryPillClick();
                initMenuNavigation();
            }, 500);
        });
    }
}

// ============================================================
// orders-index.js — Order Detail View Logic
// Aurora Restaurant — Professional Waiter Interface
// ============================================================

document.addEventListener('DOMContentLoaded', function() {
    // Handle guest count update button
    const updateGuestBtn = document.querySelector('.btn-gold[onclick*="submitGuestCountUpdate"]');
    if (updateGuestBtn) {
        updateGuestBtn.addEventListener('click', submitGuestCountUpdate);
    }

    // Sync radio and input
    document.querySelectorAll('input[name="guest_count_radio"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            document.querySelector('input[name="guest_count_input"]').value = e.target.value;
        });
    });
    document.querySelector('input[name="guest_count_input"]').addEventListener('input', (e) => {
        const val = e.target.value;
        document.querySelectorAll('input[name="guest_count_radio"]').forEach(radio => {
            if (radio.value === val) radio.checked = true;
            else radio.checked = false;
        });
    });
});

// Handle guest count update
function submitGuestCountUpdate() {
    const form = document.getElementById('formUpdateGuestCount');
    if (!form) return;

    const formData = new FormData(form);
    let guestCount = formData.get('guest_count_input');

    if (!guestCount || guestCount === "" || parseInt(guestCount) <= 0) {
        guestCount = formData.get('guest_count_radio') || 1;
    }

    const btn = form.querySelector('button');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG LƯU...';

    fetch(ORDERS_CONFIG.baseUrl + '/orders/update-guest-count', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            order_id: form.querySelector('input[name="order_id"]').value,
            guest_count: guestCount
        })
    })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Có lỗi xảy ra!');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
}

// Change quantity
function changeQty(itemId, orderId, delta) {
    const qtyEl = document.getElementById('qty-' + itemId);
    if (!qtyEl) return;
    const current = parseInt(qtyEl.textContent);
    const newQty = Math.max(0, current + delta);
    fetch(ORDERS_CONFIG.baseUrl + '/orders/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ item_id: itemId, order_id: orderId, qty: newQty })
    })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                if (newQty === 0) location.reload();
                else {
                    qtyEl.textContent = newQty;
                    document.getElementById('orderTotal').textContent = data.total_fmt;
                }
            }
        });
}

// Remove item
function removeItem(itemId, orderId) {
    if (!confirm('Xóa món này khỏi order?')) return;
    fetch(ORDERS_CONFIG.baseUrl + '/orders/remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ item_id: itemId, order_id: orderId })
    })
        .then(r => r.json())
        .then(data => { if (data.ok) location.reload(); });
}

// Close table without payment
function confirmClose(tableId, orderId) {
    if (!confirm('Bạn có chắc chắn muốn hủy bàn này?')) return;

    fetch(ORDERS_CONFIG.baseUrl + '/tables/close', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            table_id: tableId,
            order_id: orderId,
            ajax: '1',
            redirect_to_order: '1'
        })
    })
        .then(r => r.json())
        .then(res => {
            if (res.ok) {
                location.href = ORDERS_CONFIG.baseUrl + '/orders?table_id=' + tableId + '&order_id=' + orderId;
            } else {
                alert(res.message || 'Có lỗi xảy ra khi đóng bàn');
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra khi đóng bàn');
        });
}

// Confirm payment
function confirmPayment(tableId, orderId, total) {
    document.getElementById('closeTableId').value = tableId;
    document.getElementById('closeOrderId').value = orderId;
    document.getElementById('modalTotalAmount').textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
    Aurora.openModal('modalClose');
}

// Submit payment form
function handleSubmitPayment(e) {
    const isQuickCancel = document.getElementById('isQuickCancel').value === "1";
    const form = document.getElementById('formCloseTable');
    const checkPaid = document.getElementById('checkPaid');
    const checkPrintBill = document.getElementById('checkPrintBill');
    if (!isQuickCancel && !checkPaid.checked) { alert('Vui lòng xác nhận đã nhận đủ tiền!'); return; }
    
    const btn = document.getElementById('btnSubmitPayment');
    btn.disabled = true; 
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
    
    const params = new URLSearchParams(new FormData(form));
    params.append('ajax', '1');
    params.append('redirect_to_order', '1');
    
    fetch(ORDERS_CONFIG.baseUrl + '/tables/close', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params
    })
    .then(r => {
        if (!r.ok) throw new Error('Lỗi phản hồi: ' + r.status);
        return r.json();
    })
    .then(res => {
        if (res.ok) {
            // If print bill checkbox is checked, redirect to print page
            if (checkPrintBill.checked && !isQuickCancel) {
                const printUrl = ORDERS_CONFIG.baseUrl + '/orders/print?order_id=' + params.get('order_id') + '&payment_method=' + params.get('payment_method');
                window.location.href = printUrl;
            } else {
                // Just close modal and reload page
                Aurora.closeModal('modalClose');
                // Show success message
                alert('Thanh toán thành công!');
                // Reload to update order status
                setTimeout(() => {
                    location.href = ORDERS_CONFIG.baseUrl + '/orders?table_id=' + params.get('table_id') + '&order_id=' + params.get('order_id');
                }, 500);
            }
        } else {
            alert(res.message || 'Có lỗi xảy ra!');
            btn.disabled = false; 
            btn.innerHTML = '<i class="fas fa-redo me-2"></i> THỬ LẠI';
        }
    })
    .catch(err => {
        console.error('Payment error:', err);
        alert('Lỗi: ' + err.message);
        btn.disabled = false; 
        btn.innerHTML = '<i class="fas fa-redo me-2"></i> THỬ LẠI';
    });
}

// Split Order Functions
function toggleSplitItem(itemId) {
    const chk = document.getElementById('chk-' + itemId);
    if (chk) {
        chk.checked = !chk.checked;
        const plate = chk.closest('.item-row') || chk.closest('.item-plate');
        if (plate) plate.classList.toggle('selected-for-split', chk.checked);
        updateSplitCount();
    }
}

function updateSplitCount() {
    const checked = document.querySelectorAll('input[name="split_items[]"]:checked');
    const count = checked.length;
    const countEl = document.getElementById('splitCount');
    if (countEl) countEl.textContent = count + ' món';
    
    // Update visual state for all plates based on checkbox
    document.querySelectorAll('input[name="split_items[]"]').forEach(chk => {
        const plate = chk.closest('.item-row') || chk.closest('.item-plate');
        if (plate) plate.classList.toggle('selected-for-split', chk.checked);
    });
}

function openConfirmSplitModal() {
    const checked = document.querySelectorAll('input[name="split_items[]"]:checked');
    if (checked.length === 0) {
        alert('Vui lòng chọn ít nhất một món để tách!');
        return;
    }
    document.getElementById('modalSplitCountText').textContent = checked.length + ' món';
    Aurora.openModal('modalConfirmSplit');
}

function submitSplitOrder() {
    const targetTableId = document.getElementById('splitTargetTableId').value;
    const checked = document.querySelectorAll('input[name="split_items[]"]:checked');
    const guestCount = document.querySelector('input[name="split_guest_count"]:checked').value;

    if (targetTableId === "") {
        alert('Vui lòng chọn bàn đích!');
        return;
    }

    const btn = document.querySelector('button[onclick="submitSplitOrder()"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ĐANG XỬ LÝ...';

    const params = new URLSearchParams();
    params.append('source_table_id', ORDERS_CONFIG.tableId);
    params.append('order_id', ORDERS_CONFIG.orderId);
    params.append('target_table_id', targetTableId);
    params.append('guest_count', guestCount);
    checked.forEach(chk => params.append('item_ids[]', chk.value));

    fetch(ORDERS_CONFIG.baseUrl + '/tables/split', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            alert('Tách bàn thành công!');
            window.location.href = ORDERS_CONFIG.baseUrl + '/tables';
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
            btn.disabled = false;
            btn.innerHTML = 'XÁC NHẬN TÁCH BÀN';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Lỗi hệ thống!');
        btn.disabled = false;
        btn.innerHTML = 'XÁC NHẬN TÁCH BÀN';
    });
}

// Confirm order via AJAX
document.addEventListener('DOMContentLoaded', function () {
    const confirmForms = document.querySelectorAll('form[action$="/orders/confirm"]');
    confirmForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ĐANG XỬ LÝ...';

            fetch(form.getAttribute('action'), {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        showAlert(data.message || 'Đã xác nhận món thành công!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert(data.message || 'Có lỗi xảy ra!', 'danger');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Có lỗi xảy ra khi xác nhận món!', 'danger');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });
    });

    // Fix: Khi vào trang từ đường vòng (customer QR), ẩn nút xác nhận món nếu không còn draft items
    // Kiểm tra xem còn món draft nào không bằng cách tìm section-label có text "CHỜ XÁC NHẬN"
    setTimeout(() => {
        let hasDraftSection = false;
        document.querySelectorAll('.section-label').forEach(label => {
            if (label.textContent.includes('CHỜ XÁC NHẬN')) hasDraftSection = true;
        });
        
        const confirmBtn = document.querySelector('form[action$="/orders/confirm"] button[type="submit"]');
        
        // Nếu không còn section "CHỜ XÁC NHẬN" thì ẩn nút confirm
        if (!hasDraftSection && confirmBtn) {
            const confirmForm = confirmBtn.closest('form');
            if (confirmForm) confirmForm.style.display = 'none';
        }
    }, 300);
});

// Show alert
function showAlert(message, type) {
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}" aria-hidden="true"></i>
        ${message}
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.style.transition = 'opacity 0.4s ease';
        alertDiv.style.opacity = '0';
        setTimeout(() => {
            if (alertDiv.parentNode) alertDiv.remove();
        }, 400);
    }, 3000);
}

// ── Note Modal Functions ─────────────────────────────────────
let _noteItemId = 0;
let _noteOrderId = 0;
let _noteOpts = [];
let _noteSelectedOpts = [];

function openNoteModal(itemId, orderId, opts, itemName, currentNote) {
    _noteItemId = itemId;
    _noteOrderId = orderId;
    _noteOpts = opts || [];
    _noteSelectedOpts = [];

    document.getElementById('note-item-name').textContent = itemName;

    // Parse current note into parts
    const currentParts = currentNote ? currentNote.split(',').map(s => s.trim()).filter(Boolean) : [];
    
    // Split into selected options and free text
    const selectedOpts = currentParts.filter(p => _noteOpts.includes(p));
    const freeText = currentParts.filter(p => !_noteOpts.includes(p)).join(', ');

    _noteSelectedOpts = [...selectedOpts];
    document.getElementById('note-custom-text').value = freeText;

    // Render chip options
    const container = document.getElementById('note-opts-container');
    container.innerHTML = '';
    
    if (_noteOpts.length > 0) {
        _noteOpts.forEach(opt => {
            const isActive = selectedOpts.includes(opt);
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.textContent = opt;
            chip.className = 'note-chip' + (isActive ? ' active' : '');
            chip.style.cssText = `
                padding: 8px 14px;
                background: ${isActive ? 'var(--gold)' : '#f8fafc'};
                border: 2px solid ${isActive ? 'var(--gold)' : '#e5e5e5'};
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 700;
                color: ${isActive ? '#fff' : 'var(--text)'};
                cursor: pointer;
                transition: all 0.15s;
            `;
            
            chip.onclick = () => {
                const idx = _noteSelectedOpts.indexOf(opt);
                if (idx >= 0) {
                    _noteSelectedOpts.splice(idx, 1);
                    chip.style.background = '#f8fafc';
                    chip.style.borderColor = '#e5e5e5';
                    chip.style.color = 'var(--text)';
                    chip.classList.remove('active');
                } else {
                    _noteSelectedOpts.push(opt);
                    chip.style.background = 'var(--gold)';
                    chip.style.borderColor = 'var(--gold)';
                    chip.style.color = '#fff';
                    chip.classList.add('active');
                }
            };
            
            container.appendChild(chip);
        });
    }

    // Show modal
    const modal = document.getElementById('modalItemNote');
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => {
            const input = document.getElementById('note-custom-text');
            if (input) input.focus();
        }, 150);
    }
}

function closeNoteModal() {
    const modal = document.getElementById('modalItemNote');
    if (modal) modal.style.display = 'none';
}

function submitItemNote() {
    const freeText = document.getElementById('note-custom-text').value.trim();
    const parts = [..._noteSelectedOpts];
    if (freeText) parts.push(freeText);
    const note = parts.join(', ');

    const btn = document.getElementById('btn-save-note');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';

    fetch(ORDERS_CONFIG.baseUrl + '/orders/update-note', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            item_id: _noteItemId,
            order_id: _noteOrderId,
            note: note
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            closeNoteModal();
            showAlert('Đã lưu ghi chú!', 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showAlert(data.message || 'Lỗi lưu ghi chú', 'danger');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> LƯU';
        }
    })
    .catch(() => {
        showAlert('Lỗi kết nối!', 'danger');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> LƯU';
    });
}

// Close modal on backdrop click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalItemNote');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) closeNoteModal();
        });
    }
});

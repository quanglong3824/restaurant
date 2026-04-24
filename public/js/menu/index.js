let currentItem = null;
let currentSet = null;

function handleOpenSetModal(set) {
    currentSet = set;
    document.getElementById('modalSetName').textContent = set.name;
    document.getElementById('modalSetPrice').textContent = formatMoney(set.price);
    document.getElementById('modalSetDesc').textContent = set.description || '';

    const list = document.getElementById('modalSetItemsList');
    list.innerHTML = '';

    if (set.items && set.items.length > 0) {
        set.items.forEach(it => {
            const itemDiv = document.createElement('div');
            itemDiv.style.cssText = 'display:flex; align-items:center; justify-content:space-between; padding:0.75rem; background:#fff; border-radius:12px; border:1px solid var(--border);';
            itemDiv.innerHTML = `
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <input type="checkbox" checked disabled style="width:18px; height:18px; accent-color:var(--gold);">
                    <div>
                        <div style="font-weight:700; font-size:0.9rem;">${it.name}</div>
                        <small style="color:var(--text-muted);">Số lượng: ${it.quantity}</small>
                    </div>
                </div>
            `;
            list.appendChild(itemDiv);
        });
    }

    Aurora.openModal('modalSetDetail');
}

function confirmAddSetToOrder() {
    const tableId = MENU_CONFIG.tableId;
    if (!tableId) { alert('Vui lòng chọn bàn!'); return; }

    const f = new URLSearchParams();
    f.append('order_id', MENU_CONFIG.orderId || 0);
    f.append('table_id', tableId);
    f.append('set_id', currentSet.id);

    // Prepare items array for the controller
    currentSet.items.forEach((it, idx) => {
        f.append(`items[${idx}][menu_item_id]`, it.menu_item_id);
        f.append(`items[${idx}][quantity]`, it.quantity);
    });

    fetch(MENU_CONFIG.baseUrl + '/orders/add-set', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: f
    }).then(res => res.json()).then(res => {
        if (res.ok) {
            Aurora.closeModal('modalSetDetail');
            showToast('Đã thêm Combo!');
            updateCartUI(res);
        } else {
            alert(res.message || 'Lỗi khi thêm Combo');
        }
    });
}

function formatMoney(amount) { return new Intl.NumberFormat('vi-VN').format(amount) + '₫'; }
function toggleCart(show) {
    const c = document.getElementById('cartCol');
    const o = document.getElementById('cartOverlay');
    if (!c) return;
    if (show) { c.classList.add('is-visible'); o?.classList.add('is-visible'); document.body.style.overflow = 'hidden'; }
    else { c.classList.remove('is-visible'); o?.classList.remove('is-visible'); document.body.style.overflow = ''; }
}

function handleBodyClick(e) {
    if (window.innerWidth >= 1024) return;
    // Nếu click trực tiếp vào cart-body hoặc vùng không chứa class quan trọng -> Đóng giỏ
    const isItemRow = e.target.closest('.cart-item-row');
    const isButton = e.target.closest('button, a');
    if (!isItemRow && !isButton) {
        toggleCart(false);
    }
}

function updateCartUI(data) {
    if (!data.ok) return;
    if (data.order_id) {
        MENU_CONFIG.orderId = data.order_id;
    }
    const body = document.querySelector('.cart-body');
    const totalEl = document.getElementById('orderTotal');
    const btnContainer = document.getElementById('cartActionBtn');
    const headerSub = document.querySelector('.cart-header small');

    if (totalEl) totalEl.textContent = data.total_fmt;
    if (headerSub && data.items && data.items.length > 0) {
        headerSub.textContent = 'Có món đang chọn';
    }

    if (body) {
        if (!data.items || data.items.length === 0) {
            body.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Bàn chưa có món</p>
                    <p class="text-muted small">Chọn món để bắt đầu order</p>
                </div>`;
            if (btnContainer) btnContainer.innerHTML = `<button disabled class="cart-action-btn ghost w-100">BÀN CHƯA CÓ MÓN</button>`;
        } else {
            let draftsHtml = ''; let pendingHtml = ''; let confirmedHtml = ''; let draftCount = 0;
            data.items.forEach(it => {
                const isDraft = it.status === 'draft';
                const isPending = it.status === 'pending';
                const isConfirmed = it.status === 'confirmed';
                const isCooking = it.status === 'cooking';
                const isServed = it.status === 'served';
                // Render note chips
                const noteParts = (it.note || '').split(',').map(s => s.trim()).filter(Boolean);
                const noteHtml = noteParts.length
                    ? `<div style="display:flex;flex-wrap:wrap;gap:.3rem;margin:.2rem 0;">${noteParts.map(n => `<span style="background:rgba(212,175,55,.13);color:var(--gold-dark,#785e0a);border-radius:12px;padding:.1rem .45rem;font-size:.68rem;font-weight:700;">${n}</span>`).join('')}</div>`
                    : '';
                // item_options để truyền vào modal
                const opts = JSON.stringify(it.item_options || []);
                const currentNote = (it.note || '').replace(/'/g, "\\'");

                const itemHtml = `<div class="cart-item-row" data-item-id="${it.id}">
                    <div style="display:flex; align-items:center; gap:0.5rem; flex:1;">
                        ${(isConfirmed || isCooking || isServed) ? `
                        <input type="checkbox" class="item-select-cb" 
                                data-item-id="${it.id}" 
                                onchange="toggleSplitButton()"
                                onclick="event.stopPropagation()">
                        ` : ''}
                        <div style="flex:1;">
                            <div class="cart-item-name" style="font-weight:700; font-size:0.95rem; margin-bottom:4px;">${it.item_name}</div>
                            ${noteHtml}
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <span class="cart-item-price" style="font-size:0.85rem; color:var(--gold-dark); font-weight:700;">${it.price_fmt}</span>
                                ${isDraft ? `
                                <div class="qty-control" style="display:inline-flex; align-items:center; background:var(--surface-2); border-radius:20px; padding:2px 8px;">
                                    <button onclick="event.stopPropagation(); changeCartQty(${it.id}, -1)" style="border:none; background:none; padding:4px; cursor:pointer;"><i class="fas fa-minus" style="font-size:0.7rem;"></i></button>
                                    <span style="width:24px; text-align:center; font-weight:800; font-size:0.85rem;">${it.quantity}</span>
                                    <button onclick="event.stopPropagation(); changeCartQty(${it.id}, 1)" style="border:none; background:none; padding:4px; cursor:pointer;"><i class="fas fa-plus" style="font-size:0.7rem;"></i></button>
                                </div>
                                ` : `
                                <span style="font-size:0.85rem; color:var(--text-muted); font-weight:700;">x${it.quantity}</span>
                                `}
                            </div>
                        </div>
                    </div>
                    <div style="text-align:right; display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                        <div style="font-weight:800; font-size:0.95rem;">${it.subtotal_fmt}</div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            ${isDraft ? `
                                <button onclick="event.stopPropagation(); openCartNoteModal(${it.id}, ${opts}, '${currentNote}')" style="border:none;background:none;color:var(--gold,#d4af37);padding:3px;cursor:pointer;font-size:.9rem;" title="Ghi chú">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="event.stopPropagation(); removeCartItem(${it.id})" style="border:none; background:none; color:var(--danger); padding:4px; cursor:pointer; font-size:0.9rem;" title="Xóa món">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            ` : ''}
                            <span class="cart-item-status ${it.status}" style="font-size:0.7rem; padding:2px 8px; border-radius:6px; font-weight:700;">
                                ${isConfirmed ? '<i class="fas fa-check-circle" style="color:#10b981;"></i> Đã xác nhận' : 
                                  (isPending ? '<i class="fas fa-clock" style="color:#f59e0b;"></i> Chờ xác nhận' : 
                                  (isCooking ? '<i class="fas fa-fire" style="color:#ef4444;"></i> Đang nấu' : 
                                  (isServed ? '<i class="fas fa-check" style="color:#10b981;"></i> Đã phục vụ' : 
                                  '<i class="fas fa-edit" style="color:#94a3b8;"></i> Nháp')))}
                            </span>
                        </div>
                    </div>
                </div>`;

                if (isDraft) {
                    draftsHtml += itemHtml;
                    draftCount++;
                } else if (isPending) {
                    pendingHtml += itemHtml;
                } else {
                    confirmedHtml += itemHtml;
                }
            });

            let finalHtml = '';
            if (draftsHtml) {
                finalHtml += `<div class="section-label"><i class="fas fa-edit"></i> Món đang chọn (Nháp)</div>${draftsHtml}`;
            }
            if (pendingHtml) {
                finalHtml += `<div class="section-label" style="color:#f59e0b"><i class="fas fa-clock"></i> Chờ xác nhận (QR khách gửi)</div>${pendingHtml}`;
            }
            if (confirmedHtml) {
                finalHtml += `<div class="section-label"><i class="fas fa-check-circle" style="color:#10b981"></i> Đã xác nhận (Đang làm)</div><div class="confirmed-section">${confirmedHtml}</div>`;
            }
            body.innerHTML = finalHtml;

            if (btnContainer) {
                const currentOrderId = MENU_CONFIG.orderId;
                let btnsHtml = '';
                
                if (draftCount > 0) {
                    btnsHtml += `<button type="button" onclick="confirmOrderAjax(${currentOrderId})" class="cart-action-btn gold w-100 mb-2"><i class="fas fa-check-circle"></i> XÁC NHẬN MÓN (${draftCount} món)</button>`;
                } else if (data.items.length > 0) {
                    btnsHtml += `<a href="${MENU_CONFIG.baseUrl}/orders?table_id=${MENU_CONFIG.tableId}&order_id=${currentOrderId}" class="cart-action-btn success w-100 mb-2"><i class="fas fa-check-circle"></i> XEM BILL</a>`;
                }
                
                // Luôn render nút tách bàn nhưng ẩn đi, JS sẽ hiện khi có checkbox được tích
                btnsHtml += `<button type="button" id="splitTableBtn" onclick="openSplitModal()" class="cart-action-btn" style="background:#dc3545; color:white; display:none; border:none; width:100%;"><i class="fas fa-cut"></i> TÁCH BÀN (<span id="selectedCount">0</span>)</button>`;
                
                btnContainer.innerHTML = btnsHtml;
            }
        }
    }
}

function changeCartQty(itemId, delta) {
    fetch(MENU_CONFIG.baseUrl + '/orders/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ item_id: itemId, order_id: MENU_CONFIG.orderId, qty: 'delta:' + delta })
    }).then(r => r.json()).then(data => { if (data.ok) updateCartUI(data); });
}

function removeCartItem(itemId) {
    if (!confirm('Bạn có chắc muốn xoá món này?')) return;
    fetch(MENU_CONFIG.baseUrl + '/orders/remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ item_id: itemId, order_id: MENU_CONFIG.orderId })
    }).then(r => r.json()).then(data => { if (data.ok) updateCartUI(data); });
}

function confirmOrderAjax(orderId) {
    if (!confirm('Xác nhận các món nháp này?')) return;
    fetch(MENU_CONFIG.baseUrl + '/orders/confirm', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ order_id: orderId, table_id: MENU_CONFIG.tableId })
    }).then(r => r.json()).then(res => { if (res.ok) { showToast('Đã xác nhận món thành công!'); updateCartUI(res); } });
}

let _menuSelectedOpts = [];

function handleOpenItemModal(el) {
    const d = el.dataset;
    currentItem = { id: d.id, name: d.name, price: parseFloat(d.price), orderId: d.order, qty: 1 };
    _menuSelectedOpts = [];

    document.getElementById('modalItemName').textContent = d.name;
    document.getElementById('modalItemPrice').textContent = formatMoney(d.price);
    document.getElementById('modalItemDesc').textContent = d.desc || '';
    document.getElementById('modalItemNote').value = '';

    const imgEl = document.getElementById('modalItemImg');
    const placeholder = document.getElementById('modalItemImgPlaceholder');
    imgEl.querySelectorAll('img').forEach(i => i.remove());
    if (d.img) {
        placeholder.style.display = 'none';
        const i = document.createElement('img');
        i.src = d.img; i.style.cssText = 'width:100%; height:100%; object-fit:cover;';
        imgEl.appendChild(i);
    } else { placeholder.style.display = 'flex'; }

    // Render chip options
    const optsWrap = document.getElementById('modalItemOptsWrap');
    const optsContainer = document.getElementById('modalItemOptsContainer');
    optsContainer.innerHTML = '';
    let opts = [];
    try { opts = JSON.parse(d.options || '[]'); } catch(e) { opts = []; }
    if (opts.length > 0) {
        optsWrap.style.display = 'block';
        opts.forEach(opt => {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.textContent = opt;
            chip.style.cssText = 'padding:.28rem .65rem;border-radius:20px;font-size:.8rem;border:1.5px solid #e2e8f0;background:#f8fafc;color:#64748b;cursor:pointer;transition:all .18s;font-weight:500;';
            chip.onclick = () => {
                const idx = _menuSelectedOpts.indexOf(opt);
                if (idx >= 0) {
                    _menuSelectedOpts.splice(idx, 1);
                    chip.style.background = '#f8fafc';
                    chip.style.borderColor = '#e2e8f0';
                    chip.style.color = '#64748b';
                    chip.style.fontWeight = '500';
                } else {
                    _menuSelectedOpts.push(opt);
                    chip.style.background = 'rgba(212,175,55,.15)';
                    chip.style.borderColor = 'var(--gold,#d4af37)';
                    chip.style.color = 'var(--gold-dark,#785e0a)';
                    chip.style.fontWeight = '700';
                }
            };
            optsContainer.appendChild(chip);
        });
    } else {
        optsWrap.style.display = 'none';
    }

    document.getElementById('orderControlsSection').style.display = d.order ? 'block' : 'none';
    if (d.order) updateModalUI();
    Aurora.openModal('modalItemDetail');
}

function changeModalQty(delta) { if (!currentItem) return; currentItem.qty = Math.max(1, currentItem.qty + delta); updateModalUI(); }
function updateModalUI() { document.getElementById('modalItemQty').textContent = currentItem.qty; document.getElementById('modalBtnTotal').textContent = formatMoney(currentItem.qty * currentItem.price); }

function confirmAddToOrder() {
    const tableId = MENU_CONFIG.tableId;
    if (!tableId) { alert('Vui lòng chọn bàn!'); return; }

    // Gộp chip đã chọn + free text thành note
    const freeText = document.getElementById('modalItemNote').value.trim();
    const parts = [..._menuSelectedOpts];
    if (freeText) parts.push(freeText);
    const note = parts.join(', ');

    const f = new FormData();
    f.append('order_id', MENU_CONFIG.orderId || 0); 
    f.append('table_id', tableId);
    f.append('menu_item_id', currentItem.id);
    f.append('qty', currentItem.qty); 
    f.append('note', note);
    
    fetch(MENU_CONFIG.baseUrl + '/orders/add', { 
        method: 'POST', 
        body: f 
    })
    .then(res => res.json())
    .then(res => {
        if (res.ok) { 
            Aurora.closeModal('modalItemDetail'); 
            showToast('Đã thêm món!'); 
            updateCartUI(res); 
        } else { 
            alert('Lỗi: ' + (res.message || 'Không thể thêm món'));
            console.error('Add item error:', res);
        }
    })
    .catch(err => {
        alert('Lỗi kết nối: ' + err.message);
        console.error('Fetch error:', err);
    });
}

function quickAdd(e, itemId, orderId) {
    e.stopPropagation();
    const tableId = MENU_CONFIG.tableId;
    if (!tableId) { alert('Vui lòng chọn bàn trước khi gọi món!'); return; }

    const f = new FormData(); 
    f.append('order_id', orderId || 0); 
    f.append('table_id', tableId);
    f.append('menu_item_id', itemId); 
    f.append('qty', 1);

    fetch(MENU_CONFIG.baseUrl + '/orders/add', { 
        method: 'POST', 
        body: f 
    })
    .then(res => res.json())
    .then(res => {
        if (res.ok) { 
            showToast('Đã thêm món!'); 
            updateCartUI(res); 
        } else { 
            alert('Lỗi: ' + (res.message || 'Không thể thêm món'));
            console.error('Quick add error:', res);
        }
    })
    .catch(err => {
        alert('Lỗi kết nối: ' + err.message);
        console.error('Fetch error:', err);
    });
}

function showToast(msg) {
    const t = document.getElementById('addToast');
    if (!t) return;
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2000);
}


// ==================== SPLIT TABLE FUNCTIONS ====================

let selectedItems = [];

function toggleSplitButton() {
    const checkboxes = document.querySelectorAll('.item-select-cb:checked');
    selectedItems = Array.from(checkboxes).map(cb => parseInt(cb.dataset.itemId));
    
    const splitBtn = document.getElementById('splitTableBtn');
    const countSpan = document.getElementById('selectedCount');
    
    if (selectedItems.length > 0) {
        splitBtn.style.display = 'flex';
        countSpan.textContent = selectedItems.length;
    } else {
        splitBtn.style.display = 'none';
    }
}

function openSplitModal() {
    if (selectedItems.length === 0) {
        // If no items selected via checkbox, get all draft items
        const allCheckboxes = document.querySelectorAll('.item-select-cb');
        if (allCheckboxes.length === 0) {
            alert('Không có món nháp để tách!');
            return;
        }
    }
    
    // Populate selected items list
    const itemsList = document.getElementById('splitItemsList');
    const countSpan = document.getElementById('splitItemCount');
    
    let html = '';
    selectedItems.forEach(itemId => {
        const itemRow = document.querySelector(`.cart-item-row[data-item-id="${itemId}"]`);
        if (itemRow) {
            const itemName = itemRow.querySelector('.cart-item-name').textContent;
            const itemPrice = itemRow.querySelector('.cart-item-price').textContent;
            html += `<div style="display:flex; justify-content:space-between; align-items:center; padding:0.5rem; border-bottom:1px solid var(--border);">
                <span style="font-size:0.8rem; font-weight:600;">${itemName}</span>
                <span style="font-size:0.75rem; color:var(--gold-dark);">${itemPrice}</span>
            </div>`;
        }
    });
    
    itemsList.innerHTML = html || '<div class="text-muted small">Chưa chọn món nào</div>';
    countSpan.textContent = selectedItems.length;
    
    // Open modal
    Aurora.openModal('modalSplitTable');
}

function confirmSplitTable() {
    if (selectedItems.length === 0) {
        alert('Vui lòng chọn món cần tách!');
        return;
    }
    
    const targetTable = document.getElementById('splitTargetTable').value;
    if (!targetTable) {
        alert('Vui lòng chọn bàn để tách!');
        return;
    }
    
    const btn = document.querySelector('#modalSplitTable .btn-gold');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
    
    const formData = new FormData();
    formData.append('table_id', MENU_CONFIG.tableId);
    formData.append('order_id', MENU_CONFIG.orderId);
    formData.append('target_table_id', targetTable === 'new' ? 0 : targetTable);
    selectedItems.forEach(id => formData.append('item_ids[]', id));
    formData.append('ajax', '1');
    
    fetch(MENU_CONFIG.baseUrl + '/tables/split', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(formData)
    })
    .then(r => r.json())
    .then(res => {
        if (res.ok) {
            Aurora.closeModal('modalSplitTable');
            alert('Tách bàn thành công! Bàn mới sẽ được tạo.');
            // Reload to update UI
            setTimeout(() => {
                location.href = MENU_CONFIG.baseUrl + '/menu?table_id=' + (targetTable === 'new' ? res.new_order_id : targetTable) + '&order_id=' + res.new_order_id;
            }, 1000);
        } else {
            alert(res.message || 'Có lỗi xảy ra!');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(err => {
        console.error('Split error:', err);
        alert('Lỗi: ' + err.message);
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

// ==================== END SPLIT FUNCTIONS ====================

document.querySelectorAll('.filter-pill').forEach(pill => {
    pill.addEventListener('click', () => {
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('is-active'));
        pill.classList.add('is-active');
        const f = pill.dataset.filter;
        document.querySelectorAll('.menu-section').forEach(s => { s.style.display = (f === 'all' || s.dataset.section === f) ? 'block' : 'none'; });
    });
});

@extends('layouts.vertical', ['subtitle' => 'POS'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'POS', 'subtitle' => 'Point of Sale'])

    <div class="row">

        {{-- Left: Scan + Cart --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Sales Screen</h5>
                        <p class="card-subtitle mb-0">Scan barcode and add items quickly.</p>
                    </div>
                    <button class="btn btn-light btn-sm" id="clearCartBtn">
                        <iconify-icon icon="solar:refresh-outline"></iconify-icon>
                        Clear
                    </button>
                </div>

                <div class="card-body">

                    {{-- Alerts --}}
                    <div id="posMessage"></div>

                    {{-- Barcode input --}}
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <iconify-icon icon="solar:barcode-linear"></iconify-icon>
                                </span>
                                <input type="text" class="form-control" id="barcodeInput"
                                    placeholder="Scan barcode and press Enter..." autocomplete="off">
                            </div>
                            <small class="text-muted">Tip: keep cursor here (auto focus).</small>
                        </div>

                        <div class="col-md-4 d-flex gap-2">
                            <button class="btn btn-outline-secondary w-100" id="addByBarcodeBtn">
                                Add
                            </button>
                            <button class="btn btn-outline-dark w-100" id="focusBtn">
                                Focus
                            </button>
                        </div>
                    </div>

                    {{-- Product search (by name/brand/barcode) --}}
                    <div class="row mb-3">
                        <div class="col-md-8 position-relative">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <iconify-icon icon="solar:rounded-magnifer-linear"></iconify-icon>
                                </span>
                                <input type="text" class="form-control" id="productSearchInput"
                                    placeholder="Search product by name / brand / barcode...">
                            </div>

                            {{-- dropdown results --}}
                            <div class="list-group position-absolute w-100 shadow-sm mt-1 d-none" id="searchResults"
                                style="z-index: 999;">
                            </div>

                            <small class="text-muted">Use this if barcode doesn't work.</small>
                        </div>
                    </div>


                    {{-- Cart Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-centered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th style="width: 140px;">Price</th>
                                    <th style="width: 160px;" class="text-center">Qty</th>
                                    <th style="width: 140px;" class="text-end">Line Total</th>
                                    <th style="width: 70px;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="cartBody">
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Scan a barcode to start...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- Right: Summary --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Summary</h5>
                </div>

                <div class="card-body">
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Subtotal</span>
                            <strong id="subtotalText">0.00</strong>
                        </div>

                        <div class="d-flex justify-content-between mt-2 align-items-center">
                            <span class="text-muted">Discount</span>
                            <div class="input-group input-group-sm" style="width: 140px;">
                                <span class="input-group-text">Rs</span>
                                <input type="number" step="0.01" min="0" value="0" class="form-control"
                                    id="discountInput">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2 align-items-center">
                            <span class="text-muted">Tax (%)</span>
                            <div class="input-group input-group-sm" style="width: 140px;">
                                <span class="input-group-text">%</span>
                                <input type="number" step="0.01" min="0" value="0" class="form-control"
                                    id="taxInput">
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total</span>
                            <strong class="fs-4" id="totalText">0.00</strong>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100" id="completeBtn" disabled>
                        <iconify-icon icon="solar:card-send-outline"></iconify-icon>
                        Complete Sale
                    </button>

                   
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const productSearchInput = document.getElementById('productSearchInput');
            const searchResults = document.getElementById('searchResults');
            let searchTimer = null;


            const barcodeInput = document.getElementById('barcodeInput');
            const posMessage = document.getElementById('posMessage');
            const cartBody = document.getElementById('cartBody');
            const emptyRow = document.getElementById('emptyRow');

            const subtotalText = document.getElementById('subtotalText');
            const totalText = document.getElementById('totalText');
            const discountInput = document.getElementById('discountInput');
            const taxInput = document.getElementById('taxInput');
            const completeBtn = document.getElementById('completeBtn');

            const addBtn = document.getElementById('addByBarcodeBtn');
            const focusBtn = document.getElementById('focusBtn');
            const clearCartBtn = document.getElementById('clearCartBtn');

            // Cart in memory
            // cart[productId] = {id,name,barcode,price,qty,stock_qty}
            let cart = {};

            // Auto-focus barcode field
            function focusBarcode() {
                barcodeInput.focus();
                barcodeInput.select();
            }
            focusBarcode();
            focusBtn.addEventListener('click', focusBarcode);

            function showMsg(type, message) {
                posMessage.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
                setTimeout(() => posMessage.innerHTML = '', 2000);
            }

            function money(n) {
                return (parseFloat(n || 0)).toFixed(2);
            }

            function calcTotals() {
                let subtotal = 0;

                Object.values(cart).forEach(item => {
                    subtotal += item.price * item.qty;
                });

                const discount = parseFloat(discountInput.value || 0);
                const taxPercent = parseFloat(taxInput.value || 0);

                const afterDiscount = Math.max(subtotal - discount, 0);
                const taxAmount = afterDiscount * (taxPercent / 100);
                const total = afterDiscount + taxAmount;

                subtotalText.textContent = money(subtotal);
                totalText.textContent = money(total);

                completeBtn.disabled = Object.keys(cart).length === 0;
            }

            function renderCart() {
                cartBody.innerHTML = '';

                const items = Object.values(cart);

                if (items.length === 0) {
                    cartBody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="5" class="text-center text-muted py-4">
                        Scan a barcode to start...
                    </td>
                </tr>
            `;
                    calcTotals();
                    return;
                }

                items.forEach(item => {
                    const lineTotal = item.price * item.qty;

                    const row = document.createElement('tr');
                    row.setAttribute('data-id', item.id);

                    row.innerHTML = `
                <td>
                    <div>
                        <h6 class="mb-0">${item.name}</h6>
                        <small class="text-muted">${item.barcode} ${item.category ? ' | ' + item.category : ''}</small>
                        <div class="text-muted small">Stock: ${item.stock_qty}</div>
                    </div>
                </td>

                <td>
                    <span class="badge bg-light text-dark">Rs ${money(item.price)}</span>
                </td>

                <td class="text-center">
                    <div class="d-inline-flex align-items-center gap-2">
                        <button class="btn btn-light btn-sm qty-minus" type="button">
                            <iconify-icon icon="solar:minus-circle-outline"></iconify-icon>
                        </button>

                        <input type="number"
                               class="form-control form-control-sm qty-input"
                               style="width: 70px;"
                               min="1"
                               value="${item.qty}">

                        <button class="btn btn-light btn-sm qty-plus" type="button">
                            <iconify-icon icon="solar:add-circle-outline"></iconify-icon>
                        </button>
                    </div>
                </td>

                <td class="text-end">
                    <strong>Rs ${money(lineTotal)}</strong>
                </td>

                <td class="text-center">
                    <button class="btn p-0 text-danger fs-5 remove-item" type="button" title="Remove">
                        <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                    </button>
                </td>
            `;

                    cartBody.appendChild(row);
                });

                calcTotals();
            }

            async function fetchProductByBarcode(barcode) {
                const url = `{{ route('pos.productByBarcode') }}?barcode=${encodeURIComponent(barcode)}`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();

                if (!res.ok || !data.success) {
                    throw new Error(data.message || 'Product not found');
                }

                return data.product;
            }

            function addToCart(product) {
                if (product.stock_qty <= 0) {
                    showMsg('warning', 'Out of stock!');
                    return;
                }

                if (cart[product.id]) {
                    // don't allow exceed stock
                    if (cart[product.id].qty + 1 > product.stock_qty) {
                        showMsg('warning', 'Not enough stock for more quantity.');
                        return;
                    }
                    cart[product.id].qty += 1;
                } else {
                    cart[product.id] = {
                        id: product.id,
                        name: product.name,
                        barcode: product.barcode,
                        category: product.category,
                        price: parseFloat(product.selling_price),
                        qty: 1,
                        stock_qty: product.stock_qty
                    };
                }

                renderCart();
            }

            async function handleBarcodeAdd() {
                const barcode = barcodeInput.value.trim();
                if (!barcode) return;

                try {
                    const product = await fetchProductByBarcode(barcode);
                    addToCart(product);
                    barcodeInput.value = '';
                    focusBarcode();
                } catch (e) {
                    showMsg('danger', e.message);
                    barcodeInput.select();
                }
            }

            // Enter to add
            barcodeInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleBarcodeAdd();
                }
            });

            addBtn.addEventListener('click', function() {
                handleBarcodeAdd();
            });

            // Discount/Tax recalc
            discountInput.addEventListener('input', calcTotals);
            taxInput.addEventListener('input', calcTotals);

            // Clear cart
            clearCartBtn.addEventListener('click', function() {
                cart = {};
                discountInput.value = 0;
                taxInput.value = 0;
                renderCart();
                focusBarcode();
            });

            // Cart actions (event delegation)
            cartBody.addEventListener('click', function(e) {
                const row = e.target.closest('tr');
                if (!row) return;
                const id = row.getAttribute('data-id');
                if (!id) return;

                // remove
                if (e.target.closest('.remove-item')) {
                    delete cart[id];
                    renderCart();
                    focusBarcode();
                    return;
                }

                // minus
                if (e.target.closest('.qty-minus')) {
                    if (cart[id].qty > 1) cart[id].qty -= 1;
                    renderCart();
                    focusBarcode();
                    return;
                }

                // plus
                if (e.target.closest('.qty-plus')) {
                    if (cart[id].qty + 1 > cart[id].stock_qty) {
                        showMsg('warning', 'Not enough stock for more quantity.');
                        return;
                    }
                    cart[id].qty += 1;
                    renderCart();
                    focusBarcode();
                    return;
                }
            });

            cartBody.addEventListener('input', function(e) {
                if (!e.target.classList.contains('qty-input')) return;

                const row = e.target.closest('tr');
                const id = row.getAttribute('data-id');

                let qty = parseInt(e.target.value || 1, 10);
                if (isNaN(qty) || qty < 1) qty = 1;

                if (qty > cart[id].stock_qty) {
                    qty = cart[id].stock_qty;
                    showMsg('warning', 'Qty limited to available stock.');
                }

                cart[id].qty = qty;
                renderCart();
                focusBarcode();
            });

            function hideSearchResults() {
                searchResults.classList.add('d-none');
                searchResults.innerHTML = '';
            }

            function showSearchResults(items) {
                if (!items.length) {
                    hideSearchResults();
                    return;
                }

                searchResults.innerHTML = items.map(p => `
        <button type="button"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                data-product='${JSON.stringify(p).replace(/'/g, "&apos;")}'>
            <div>
                <div class="fw-semibold">${p.name}</div>
                <small class="text-muted">${p.barcode} ${p.category ? ' | ' + p.category : ''}</small>
            </div>
            <div class="text-end">
                <div class="fw-semibold">Rs ${money(p.selling_price)}</div>
                <small class="${p.stock_qty <= 0 ? 'text-danger' : 'text-muted'}">Stock: ${p.stock_qty}</small>
            </div>
        </button>
    `).join('');

                searchResults.classList.remove('d-none');
            }

            async function searchProducts(q) {
                const url = `{{ route('pos.searchProducts') }}?q=${encodeURIComponent(q)}`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                return data.products || [];
            }

            // Typing search (debounced)
            productSearchInput.addEventListener('input', function() {
                const q = this.value.trim();

                clearTimeout(searchTimer);

                if (q.length < 2) {
                    hideSearchResults();
                    return;
                }

                searchTimer = setTimeout(async () => {
                    try {
                        const products = await searchProducts(q);
                        showSearchResults(products);
                    } catch (e) {
                        hideSearchResults();
                    }
                }, 250);
            });

            // Click result -> add to cart
            searchResults.addEventListener('click', function(e) {
                const btn = e.target.closest('.list-group-item');
                if (!btn) return;

                const product = JSON.parse(btn.getAttribute('data-product'));
                addToCart(product);
                productSearchInput.value = '';
                hideSearchResults();
                focusBarcode();
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#searchResults') && !e.target.closest('#productSearchInput')) {
                    hideSearchResults();
                }
            });

            // ✅ COMPLETE SALE (Step 12)
            completeBtn.addEventListener('click', async function() {

                const items = Object.values(cart).map(i => ({
                    product_id: i.id,
                    qty: i.qty
                }));

                if (items.length === 0) {
                    showMsg('info', 'Cart is empty.');
                    return;
                }

                const payload = {
                    items: items,
                    discount_total: parseFloat(discountInput.value || 0), // ✅ match DB column
                    tax_percent: parseFloat(taxInput.value || 0),
                    payment_method: 'cash', // later you can add dropdown
                    customer_id: null // later you can add customer select
                };

                try {
                    completeBtn.disabled = true;

                    const res = await fetch(`{{ route('pos.storeSale') }}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await res.json();

                    if (!res.ok || !data.success) {
                        throw new Error(data.message || 'Failed to complete sale.');
                    }

                    // ✅ Clear cart immediately (recommended)
cart = {};
discountInput.value = 0;
taxInput.value = 0;
renderCart();
focusBarcode();

// ✅ SweetAlert options
Swal.fire({
    title: 'Sale Completed!',
    html: `
        <div class="text-muted">Invoice No: <strong>${data.invoice_no}</strong></div>
        <div class="text-muted mt-1">What do you want to do next?</div>
    `,
    icon: 'success',
    showCancelButton: true,
    showDenyButton: true,

    confirmButtonText: 'Print Thermal',
    denyButtonText: 'Open Invoice',
    cancelButtonText: 'Close',

    confirmButtonColor: '#0d6efd',
    denyButtonColor: '#6c757d',
    cancelButtonColor: '#adb5bd'
}).then((result) => {

    if (result.isConfirmed) {
        // ✅ Thermal 80mm (auto print)
        window.open(`{{ url('/pos/invoice') }}/${data.sale_id}/thermal`, "_blank");
    } else if (result.isDenied) {
        // ✅ Normal A4 Invoice page
        window.open(`{{ url('/pos/invoice') }}/${data.sale_id}`, "_blank");
    }

    // always refocus barcode after closing
    focusBarcode();
});


                    // showMsg('success', `Sale completed. Invoice: ${data.invoice_no}`);

                    // // ✅ open thermal print immediately (optional)
                    // // window.open(`{{ url('/pos/invoice') }}/${data.sale_id}/thermal`, "_blank");

                    // // ✅ OR open A4 invoice page
                    // window.open(`{{ url('/pos/invoice') }}/${data.sale_id}`, "_blank");

                    // // reset cart
                    // cart = {};
                    // discountInput.value = 0;
                    // taxInput.value = 0;
                    // renderCart();
                    // focusBarcode();

                    // Step 13 (later): print invoice
                    // window.open(`/pos/invoice/${data.sale_id}`, "_blank");

                } catch (e) {
                    showMsg('danger', e.message);
                    focusBarcode();
                } finally {
                    completeBtn.disabled = Object.keys(cart).length === 0;
                }
            });



        });
    </script>
@endsection

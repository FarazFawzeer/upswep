@extends('layouts.vertical', ['subtitle' => 'Stock Entry Create'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Stock Entry', 'subtitle' => 'Create'])

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">New Stock Entry</h5>
                <p class="card-subtitle mb-0">Add purchased stock and update product quantities.</p>
            </div>
            <a href="{{ route('admin.stock-entries.index') }}" class="btn btn-light btn-sm">Back</a>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="stockEntryForm" action="{{ route('admin.stock-entries.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Supplier</label>
                        <select class="form-select" name="supplier_id">
                            <option value="">Select Supplier (Optional)</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Entry Date</label>
                        <input type="date" class="form-control" name="entry_date" value="{{ date('Y-m-d') }}"
                            required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <input type="text" class="form-control" name="notes" placeholder="Ex: Invoice #123">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Products</h6>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addRowBtn">
                        + Add Row
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-centered" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%;">Product</th>
                                <th style="width: 15%;">Qty</th>
                                <th style="width: 15%;">Unit Cost</th>
                                <th style="width: 15%;">Line Total</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody"></tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Qty</span>
                                <strong id="totalQty">0</strong>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">Total Cost</span>
                                <strong id="totalCost">0.00</strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex justify-content-end align-items-end">
                        <button type="submit" class="btn btn-primary">Save Stock Entry</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Products data from controller (IMPORTANT: send $products as array)
            const products = @json($products);

            const itemsBody = document.getElementById('itemsBody');
            const addRowBtn = document.getElementById('addRowBtn');

            function productOptionsHtml(selectedId = '') {
                let html = `<option value="">Select Product</option>`;
                products.forEach(p => {
                    const selected = (String(p.id) === String(selectedId)) ? 'selected' : '';
                    html += `<option value="${p.id}" ${selected}>${p.name} (${p.barcode})</option>`;
                });
                return html;
            }

            function newRow(index) {
                return `
                <tr>
                    <td>
                        <select class="form-select product-select" name="items[${index}][product_id]" required>
                            ${productOptionsHtml()}
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control qty-input" name="items[${index}][qty]" value="1" min="1" required>
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control cost-input" name="items[${index}][unit_cost]" placeholder="0.00" required>
                    </td>
                    <td>
                        <input type="text" class="form-control line-total" value="0.00" readonly>
                        <input type="hidden" class="line-total-hidden" name="items[${index}][line_total]" value="0.00">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-light btn-sm remove-row">
                            <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                        </button>
                    </td>
                </tr>`;
            }

            function rebuildRowNames() {
                const rows = itemsBody.querySelectorAll('tr');
                rows.forEach((row, i) => {
                    row.querySelector('.product-select').setAttribute('name', `items[${i}][product_id]`);
                    row.querySelector('.qty-input').setAttribute('name', `items[${i}][qty]`);
                    row.querySelector('.cost-input').setAttribute('name', `items[${i}][unit_cost]`);
                    row.querySelector('.line-total-hidden').setAttribute('name', `items[${i}][line_total]`);
                });
            }

            function calcRowTotal(row) {
                const qty = parseInt(row.querySelector('.qty-input').value || 0, 10);
                const cost = parseFloat(row.querySelector('.cost-input').value || 0);

                const line = qty * cost;
                row.querySelector('.line-total').value = line.toFixed(2);
                row.querySelector('.line-total-hidden').value = line.toFixed(2);

                return {
                    qty,
                    line
                };
            }

            function calcTotals() {
                let totalQty = 0;
                let totalCost = 0;

                const rows = itemsBody.querySelectorAll('tr');
                rows.forEach(row => {
                    const data = calcRowTotal(row);
                    if (data.qty > 0) {
                        totalQty += data.qty;
                        totalCost += data.line;
                    }
                });

                document.getElementById('totalQty').textContent = totalQty;
                document.getElementById('totalCost').textContent = totalCost.toFixed(2);
            }

            // Add initial row
            itemsBody.insertAdjacentHTML('beforeend', newRow(0));
            calcTotals();

            // Add row button
            addRowBtn.addEventListener('click', function() {
                const index = itemsBody.querySelectorAll('tr').length;
                itemsBody.insertAdjacentHTML('beforeend', newRow(index));
            });

            // Event delegation
            itemsBody.addEventListener('input', function(e) {
                if (
                    e.target.classList.contains('qty-input') ||
                    e.target.classList.contains('cost-input')
                ) {
                    calcTotals();
                }
            });

            itemsBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('product-select')) {
                    // autofill unit cost from product default cost_price
                    const pid = e.target.value;
                    const row = e.target.closest('tr');
                    const costInput = row.querySelector('.cost-input');

                    if (pid && (!costInput.value || parseFloat(costInput.value) === 0)) {
                        const p = products.find(x => String(x.id) === String(pid));
                        if (p) costInput.value = parseFloat(p.cost_price).toFixed(2);
                    }
                    calcTotals();
                }
            });

            itemsBody.addEventListener('click', function(e) {
                const btn = e.target.closest('.remove-row');
                if (!btn) return;

                btn.closest('tr').remove();
                rebuildRowNames();
                calcTotals();
            });

        });
    </script>
@endsection

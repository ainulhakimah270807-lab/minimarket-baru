<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Retail POS - Transaksi Pembayaran</title>
    <!-- CSS Utama -->
    <link rel="stylesheet" href="{{ url(asset('css/chasier.css')) }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <div class="pos-container">
        <!-- HEADER -->
        <header class="header">
            <div>
                <div class="store-name">TOKO RETAIL MAKMUR</div>
                <div class="store-info">
                    Jl. Contoh No. 123 • Jember • Telp. 0812-xxxx-xxxx
                </div>
            </div>
            <div class="transaction-info">
                <div>No. Transaksi</div>
                <div class="transaction-number" id="transactionNumber">
                    {{ $transactionNumber ?? 'TRX-' . date('Ymd') . '-001' }}
                </div>
                <div id="currentDate"></div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="main">
            <!-- ================= LEFT ================= -->
            <section>
                <!-- SEARCH & CUSTOMER CARD -->
                <div class="card">
                    <div class="card-header">Tambah Barang</div>
                    <div class="card-body">
                        <!-- SEARCH -->
                        <div class="search-area">
                            <div class="search-input">
                                <input type="text" id="searchProduct" placeholder="Cari nama barang / kode / barcode..." autocomplete="off">
                                <div class="product-results" id="productResults"></div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="searchProduct()">Cari</button>
                        </div>
                        <!-- CUSTOMER -->
                        <div class="customer-area">
                            <div class="form-group">
                                <label>Pelanggan</label>
                                <select class="form-control">
                                    <option>Umum</option>
                                    <option>Pelanggan Member</option>
                                    <option>Member VIP</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>No. Member / HP</label>
                                <input type="text" class="form-control" placeholder="Opsional">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CART CARD -->
                <div class="card" style="margin-top:15px">
                    <div class="card-header">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span>Keranjang Belanja</span>
                            <span id="itemCount">0 Item</span>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th width="40">No</th>
                                    <th>Barang</th>
                                    <th width="100">Harga</th>
                                    <th width="120" class="text-center">Qty</th>
                                    <th width="120" class="text-right">Subtotal</th>
                                    <th width="40"></th>
                                </tr>
                            </thead>
                            <tbody id="cartBody">
                                <tr id="emptyRow">
                                    <td colspan="6" class="empty-cart">
                                        Keranjang masih kosong.<br>
                                        Silakan cari atau scan barang.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ================= RIGHT ================= -->
            <aside>
                <!-- PAYMENT SUMMARY CARD -->
                <div class="card">
                    <div class="card-header">Ringkasan Pembayaran</div>
                    <div class="card-body">
                        <div class="payment-summary">
                            <!-- TOTAL ITEM -->
                            <div class="summary-row">
                                <span>Total Item</span>
                                <strong id="totalQty">0</strong>
                            </div>
                            <!-- SUBTOTAL -->
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <strong id="subtotal" data-raw="0">Rp 0</strong>
                            </div>
                            <!-- DISCOUNT -->
                            <div class="summary-row">
                                <span>Diskon (%)</span>
                                <input type="number" id="discountPercent" value="0" min="0" max="100" onchange="calculateTotal()" onkeyup="calculateTotal()">
                            </div>
                            <div class="summary-row">
                                <span>Diskon (Rp)</span>
                                <input type="number" id="discountAmount" value="0" min="0" onchange="calculateTotal()" onkeyup="calculateTotal()">
                            </div>
                            <!-- TAX -->
                            <div class="summary-row">
                                <span>Pajak / PPN</span>
                                <input type="number" id="tax" value="0" min="0" onchange="calculateTotal()" onkeyup="calculateTotal()">
                            </div>
                            <!-- OTHER FEE -->
                            <div class="summary-row">
                                <span>Biaya Lain</span>
                                <input type="number" id="otherFee" value="0" min="0" onchange="calculateTotal()" onkeyup="calculateTotal()">
                            </div>
                            <!-- TOTAL AKHIR -->
                            <div class="total-box">
                                <div class="total-label">TOTAL AKHIR</div>
                                <div class="total-value" id="grandTotal" data-value="0">Rp 0</div>
                            </div>

                            <!-- PAYMENT BOX -->
                            <div class="payment-box">
                                <div class="payment-label">Uang Dibayar</div>
                                <input type="number" id="payment" class="payment-input" placeholder="0" oninput="calculateChange()">

                                <!-- NOMINAL CEPAT -->
                                <div class="payment-label" style="margin-top:10px">Nominal Cepat</div>
                                <div class="quick-amounts">
                                    <button type="button" class="btn-quick" onclick="setQuickAmount(40000)">40rb</button>
                                    <button type="button" class="btn-quick" onclick="setQuickAmount(50000)">50rb</button>
                                    <button type="button" class="btn-quick" onclick="setQuickAmount(100000)">100rb</button>
                                    <button type="button" class="btn-quick" onclick="setQuickAmount('pas')">Uang Pas</button>
                                </div>

                                <!-- PAYMENT METHOD -->
                                <div class="payment-label" style="margin-top:15px">Metode Pembayaran</div>
                                <div class="payment-method">
                                    <button type="button" class="active" onclick="selectPayment(this)">Tunai</button>
                                    <button type="button" onclick="selectPayment(this)">QRIS</button>
                                    <button type="button" onclick="selectPayment(this)">Debit</button>
                                    <button type="button" onclick="selectPayment(this)">Kredit</button>
                                    <button type="button" onclick="selectPayment(this)">E-Wallet</button>
                                    <button type="button" onclick="selectPayment(this)">Transfer</button>
                                </div>

                                <!-- CHANGE BOX -->
                                <div class="change-box" id="changeBox">
                                    <div class="change-label">KEMBALIAN</div>
                                    <div class="change-value" id="change">Rp 0</div>
                                </div>
                            </div>

                            <!-- ACTION AREA -->
                            <div class="action-area">
                                <button type="button" class="btn btn-warning" onclick="holdTransaction()">Tahan</button>
                                <button type="button" class="btn btn-danger" onclick="cancelTransaction()">Batal</button>
                                <button type="button" class="btn btn-success btn-pay" onclick="processPayment()">BAYAR & CETAK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </main>
    </div>

    <!-- FOOTER STATUS BAR -->
    <footer class="footer-statusbar">
        <div>Kasir: <strong>Admin Kasir</strong></div>
        <div class="shortcuts">
            <span>F2</span> Cari Barang • <span>F4</span> Input Bayar • <span>ESC</span> Batal
        </div>
    </footer>

    <!-- SweetAlert2 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Skrip JavaScript POS -->
    <script>
        // ==========================================
        // KASIR RETAIL POS - MAIN SCRIPT
        // ==========================================

        let cart = [];

        document.addEventListener("DOMContentLoaded", function() {
            updateCurrentDate();
            initEventListeners();
        });

        // 1. Tampilkan Tanggal Hari Ini di Header
        function updateCurrentDate() {
            const dateElement = document.getElementById('currentDate');
            if (dateElement) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const today = new Date();
                dateElement.innerText = today.toLocaleDateString('id-ID', options);
            }
        }

        // 2. Event Listeners untuk Shortcut & Input
        function initEventListeners() {
            document.addEventListener('keydown', function(e) {
                if (e.key === 'F2') {
                    e.preventDefault();
                    document.getElementById('searchProduct').focus();
                } else if (e.key === 'F4') {
                    e.preventDefault();
                    document.getElementById('payment').focus();
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    cancelTransaction();
                }
            });

            const searchInput = document.getElementById('searchProduct');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    let keyword = this.value.trim();
                    if (keyword.length > 1) {
                        showProductResults([
                            { id: 1, code: 'BRG001', name: 'Minyak Goreng 2L', price: 35000, stock: 15 },
                            { id: 2, code: 'BRG002', name: 'Gula Pasir 1kg', price: 16000, stock: 20 },
                            { id: 3, code: 'BRG003', name: 'Beras Premium 5kg', price: 65000, stock: 10 }
                        ].filter(p => p.name.toLowerCase().includes(keyword.toLowerCase())));
                    } else {
                        hideProductResults();
                    }
                });
            }
        }

        function showProductResults(products) {
            let resultsContainer = document.getElementById('productResults');
            if (!resultsContainer) return;

            if (products.length === 0) {
                resultsContainer.innerHTML = '<div class="product-result-item">Barang tidak ditemukan</div>';
                resultsContainer.style.display = 'block';
                return;
            }

            let html = '';
            products.forEach(p => {
                html += `<div class="product-result-item" onclick="addToCart(${p.id}, '${p.name}', ${p.price})" style="padding: 8px; cursor: pointer; border-bottom: 1px solid #eee;">
                            <span>${p.name} - Rp ${p.price.toLocaleString('id-ID')}</span><br>
                            <span style="color: #666; font-size: 12px;">Stok: ${p.stock}</span>
                         </div>`;
            });
            resultsContainer.innerHTML = html;
            resultsContainer.style.display = 'block';
        }

        function hideProductResults() {
            let resultsContainer = document.getElementById('productResults');
            if (resultsContainer) {
                resultsContainer.style.display = 'none';
            }
        }

        // 3. Tambah Produk ke Keranjang
        function addToCart(id, name, price) {
            let existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.qty += 1;
                existingItem.subtotal = existingItem.qty * existingItem.price;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    qty: 1,
                    subtotal: price
                });
            }

            document.getElementById('searchProduct').value = '';
            hideProductResults();
            renderCart();
        }

        // 4. Render Tabel Keranjang Belanja
        function renderCart() {
            let cartBody = document.getElementById('cartBody');
            let itemCount = document.getElementById('itemCount');
            let totalQtyEl = document.getElementById('totalQty');
            let subtotalEl = document.getElementById('subtotal');

            if (cart.length === 0) {
                cartBody.innerHTML = `
                    <tr id="emptyRow">
                        <td colspan="6" class="empty-cart" style="text-align: center; padding: 20px;">
                            Keranjang masih kosong.<br>Silakan cari atau scan barang.
                        </td>
                    </tr>`;
                itemCount.innerText = '0 Item';
                totalQtyEl.innerText = '0';
                subtotalEl.innerText = 'Rp 0';
                subtotalEl.setAttribute('data-raw', 0);
                calculateTotal();
                return;
            }

            let html = '';
            let totalItems = 0;
            let totalQty = 0;
            let rawSubtotal = 0;

            cart.forEach((item, index) => {
                totalItems += 1;
                totalQty += item.qty;
                rawSubtotal += item.subtotal;

                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.name}</td>
                        <td>Rp ${item.price.toLocaleString('id-ID')}</td>
                        <td class="text-center">
                            <button type="button" class="btn-qty" onclick="updateQty(${item.id}, -1)">-</button>
                            <span style="margin: 0 8px; font-weight: 600;">${item.qty}</span>
                            <button type="button" class="btn-qty" onclick="updateQty(${item.id}, 1)">+</button>
                        </td>
                        <td class="text-right">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                        <td class="text-center">
                            <button type="button" class="btn-delete" onclick="removeItem(${item.id})" title="Hapus">&times;</button>
                        </td>
                    </tr>
                `;
            });

            cartBody.innerHTML = html;
            itemCount.innerText = `${totalItems} Jenis (${totalQty} Item)`;
            totalQtyEl.innerText = totalQty;
            subtotalEl.innerText = `Rp ${rawSubtotal.toLocaleString('id-ID')}`;
            subtotalEl.setAttribute('data-raw', rawSubtotal);

            calculateTotal();
        }

        function updateQty(id, change) {
            let item = cart.find(i => i.id === id);
            if (item) {
                item.qty += change;
                if (item.qty <= 0) {
                    removeItem(id);
                } else {
                    item.subtotal = item.qty * item.price;
                    renderCart();
                }
            }
        }

        function removeItem(id) {
            cart = cart.filter(i => i.id !== id);
            renderCart();
        }

        // 5. Hitung Grand Total
        function calculateTotal() {
            let subtotalEl = document.getElementById('subtotal');
            let rawSubtotal = parseFloat(subtotalEl.getAttribute('data-raw')) || 0;
            
            let discPercent = parseFloat(document.getElementById('discountPercent').value) || 0;
            let discAmountInput = parseFloat(document.getElementById('discountAmount').value) || 0;
            let tax = parseFloat(document.getElementById('tax').value) || 0;
            let otherFee = parseFloat(document.getElementById('otherFee').value) || 0;

            let totalDiscount = discAmountInput;
            if (discPercent > 0) {
                totalDiscount += (rawSubtotal * discPercent) / 100;
            }

            let grandTotal = rawSubtotal - totalDiscount + tax + otherFee;
            if (grandTotal < 0) grandTotal = 0;

            let grandTotalEl = document.getElementById('grandTotal');
            grandTotalEl.innerText = `Rp ${grandTotal.toLocaleString('id-ID')}`;
            grandTotalEl.setAttribute('data-value', grandTotal);

            calculateChange();
        }

        function setQuickAmount(amount) {
            let grandTotal = parseFloat(document.getElementById('grandTotal').getAttribute('data-value')) || 0;
            let paymentInput = document.getElementById('payment');

            if (amount === 'pas') {
                paymentInput.value = grandTotal;
            } else {
                paymentInput.value = amount;
            }
            calculateChange();
        }

        function calculateChange() {
            let grandTotal = parseFloat(document.getElementById('grandTotal').getAttribute('data-value')) || 0;
            let payment = parseFloat(document.getElementById('payment').value) || 0;
            let changeEl = document.getElementById('change');

            let change = payment - grandTotal;
            if (change < 0) {
                changeEl.innerText = 'Rp 0 (Kurang)';
                changeEl.style.color = '#e74c3c';
            } else {
                changeEl.innerText = `Rp ${change.toLocaleString('id-ID')}`;
                changeEl.style.color = '#27ae60';
            }
        }

        function selectPayment(button) {
            let buttons = button.parentElement.getElementsByTagName('button');
            for (let btn of buttons) {
                btn.classList.remove('active');
            }
            button.classList.add('active');
        }

        // 9. Tombol Tahan Transaksi (Hold) - Menggunakan SweetAlert2
        function holdTransaction() {
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Keranjang masih kosong!'
                });
                return;
            }
            Swal.fire({
                icon: 'info',
                title: 'Transaksi Ditahan',
                text: 'Transaksi berhasil disimpan ke daftar pending.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                resetTransaction();
            });
        }

        // 10. Tombol Batal Transaksi - Menggunakan SweetAlert2
        function cancelTransaction() {
            if (cart.length === 0) return;
            
            Swal.fire({
                title: 'Batalkan Transaksi?',
                text: 'Semua item di keranjang akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetTransaction();
                    Swal.fire({
                        icon: 'success',
                        title: 'Dibatalkan',
                        text: 'Transaksi telah dibatalkan.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        // 11. Proses Pembayaran & SweetAlert2 Sukses
     // 11. Proses Pembayaran & SweetAlert2 Sukses dengan Detail Belanja
// 11. Proses Pembayaran & SweetAlert2 Sukses + Print ke Console
function processPayment() {
    if (cart.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Keranjang Kosong',
            text: 'Silakan tambahkan barang terlebih dahulu sebelum melakukan pembayaran.'
        });
        return;
    }

    let grandTotal = parseFloat(document.getElementById('grandTotal').getAttribute('data-value')) || 0;
    let payment = parseFloat(document.getElementById('payment').value) || 0;

    if (payment < grandTotal) {
        Swal.fire({
            icon: 'error',
            title: 'Pembayaran Kurang!',
            text: 'Jumlah uang yang dibayarkan masih kurang dari Total Akhir.',
        });
        return;
    }

    let change = payment - grandTotal;
    let transactionNumber = document.getElementById('transactionNumber')?.innerText.trim() || 'TRX-UNKNOWN';

    // 1. Cetak rincian transaksi ke Console Browser (F12 -> Console)
    console.log("==========================================");
    console.log(`TRANSAKSI BERHASIL: ${transactionNumber}`);
    console.log("==========================================");
    console.log("Daftar Belanjaan:", cart);
    console.log(`Total Akhir : Rp ${grandTotal.toLocaleString('id-ID')}`);
    console.log(`Uang Bayar  : Rp ${payment.toLocaleString('id-ID')}`);
    console.log(`Kembalian   : Rp ${change.toLocaleString('id-ID')}`);
    console.log("==========================================");

    // 2. Susun daftar item belanja untuk ditampilkan di alert
    let detailItems = '';
    cart.forEach(item => {
        detailItems += `• ${item.name} (${item.qty}x) - Rp ${item.subtotal.toLocaleString('id-ID')}<br>`;
    });

    // 3. Tampilkan SweetAlert2
    Swal.fire({
        icon: 'success',
        title: 'Transaksi Berhasil!',
        html: `
            <div style="text-align: left; font-size: 13px; margin-top: 10px; max-height: 150px; overflow-y: auto; border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 8px 0;">
                <b>Daftar Belanja:</b><br>
                ${detailItems}
            </div>
            <div style="text-align: left; font-size: 13px; margin-top: 8px;">
                <b>Total Akhir:</b> Rp ${grandTotal.toLocaleString('id-ID')}<br>
                <b>Bayar:</b> Rp ${payment.toLocaleString('id-ID')}<br>
                <b>Kembalian:</b> Rp ${change.toLocaleString('id-ID')}
            </div>
        `,
        confirmButtonText: 'OK'
    }).then(() => {
        resetTransaction();
    });
}
    </script>
</body>
</html>
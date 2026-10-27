<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Retail POS - Transaksi Pembayaran</title>
    <link rel="stylesheet" href="{{ url(asset('css/chasier.css')) }}">
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
                                <strong id="subtotal">Rp 0</strong>
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
                                <div class="total-value" id="grandTotal">Rp 0</div>
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

    <script src="{{ url(asset('js/chasier.js')) }}"></script>
</body>
</html>

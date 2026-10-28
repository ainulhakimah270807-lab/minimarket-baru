/* ==========================================================================
   POS CASHIER JAVASCRIPT (chasier.js)
   Aplikasi Minimarket - Praktikum Ke-3
   ========================================================================== */

/* ============================================
   1. SAMPLE PRODUCT DATA
   ============================================ */
const products = [
    {
        id: 1,
        code: "BRG001",
        barcode: "899123456001",
        name: "Indomie Goreng",
        price: 3500
    },
    {
        id: 2,
        code: "BRG002",
        barcode: "899123456002",
        name: "Aqua 600ml",
        price: 4000
    },
    {
        id: 3,
        code: "BRG003",
        barcode: "899123456003",
        name: "Teh Botol Sosro",
        price: 5000
    },
    {
        id: 4,
        code: "BRG004",
        barcode: "899123456004",
        name: "Beras 5 Kg",
        price: 75000
    },
    {
        id: 5,
        code: "BRG005",
        barcode: "899123456005",
        name: "Minyak Goreng 1 Liter",
        price: 18000
    },
    {
        id: 6,
        code: "BRG006",
        barcode: "899123456006",
        name: "Gula Pasir 1 Kg",
        price: 17000
    }
];

/* ============================================
   2. FORMAT RUPIAH
   ============================================ */
function formatRupiah(value) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0
    }).format(value);
}

/* ============================================
   3. CART STATE & INITIALIZATION
   ============================================ */
let cart = [];
const searchInput = document.getElementById("searchProduct");

if (searchInput) {
    searchInput.addEventListener("input", searchProduct);

    // Auto-scan / Enter key on search input
    searchInput.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            const keyword = searchInput.value.trim().toLowerCase();
            if (!keyword) return;

            // Search exact match for code/barcode or single match
            const exactProduct = products.find(
                (p) => p.code.toLowerCase() === keyword || p.barcode.toLowerCase() === keyword
            );

            if (exactProduct) {
                addToCart(exactProduct.id);
            } else {
                const results = products.filter(
                    (p) =>
                        p.name.toLowerCase().includes(keyword) ||
                        p.code.toLowerCase().includes(keyword) ||
                        p.barcode.includes(keyword)
                );

                if (results.length === 1) {
                    addToCart(results[0].id);
                }
            }
        }
    });
}

// Close search dropdown on clicking outside
document.addEventListener("click", function (event) {
    const resultBox = document.getElementById("productResults");
    if (resultBox && searchInput) {
        if (!searchInput.contains(event.target) && !resultBox.contains(event.target)) {
            resultBox.style.display = "none";
        }
    }
});

/* ============================================
   4. SEARCH PRODUCT FUNCTION
   ============================================ */
function searchProduct() {
    const keyword = searchInput.value.toLowerCase().trim();
    const resultBox = document.getElementById("productResults");

    if (!resultBox) return;

    if (!keyword) {
        resultBox.style.display = "none";
        return;
    }

    const results = products.filter(
        (product) =>
            product.name.toLowerCase().includes(keyword) ||
            product.code.toLowerCase().includes(keyword) ||
            product.barcode.includes(keyword)
    );

    if (results.length === 0) {
        resultBox.innerHTML = `
            <div class="product-item" style="color: #64748b; justify-content: center; cursor: default;">
                Barang tidak ditemukan
            </div>
        `;
    } else {
        resultBox.innerHTML = results
            .map(
                (product) => `
            <div class="product-item" onclick="addToCart(${product.id})">
                <div>
                    <div class="product-name">${product.name}</div>
                    <div class="product-code">${product.code} - ${product.barcode}</div>
                </div>
                <div class="product-price">${formatRupiah(product.price)}</div>
            </div>
        `
            )
            .join("");
    }
    resultBox.style.display = "block";
}

/* ============================================
   5. ADD TO CART & RENDER CART
   ============================================ */
function addToCart(productId) {
    const product = products.find((p) => p.id === productId);
    if (!product) return;

    const existing = cart.find((item) => item.id === productId);

    if (existing) {
        existing.qty++;
    } else {
        cart.push({
            ...product,
            qty: 1
        });
    }

    if (searchInput) {
        searchInput.value = "";
    }
    const resultBox = document.getElementById("productResults");
    if (resultBox) {
        resultBox.style.display = "none";
    }

    renderCart();
}

function renderCart() {
    const body = document.getElementById("cartBody");
    if (!body) return;

    if (cart.length === 0) {
        body.innerHTML = `
            <tr id="emptyRow">
                <td colspan="6" class="empty-cart">
                    Keranjang masih kosong.
                    <br>
                    Silakan cari atau scan barang.
                </td>
            </tr>
        `;
        calculateTotal();
        return;
    }

    body.innerHTML = cart
        .map(
            (item, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <strong>${item.name}</strong>
                    <div style="font-size:11px; color:#6c757d">
                        ${item.code}
                    </div>
                </td>
                <td>${formatRupiah(item.price)}</td>
                <td>
                    <div class="qty-control">
                        <button onclick="changeQty(${item.id}, -1)">−</button>
                        <input type="number" value="${item.qty}" min="1" onchange="updateQty(${item.id}, this.value)">
                        <button onclick="changeQty(${item.id}, 1)">+</button>
                    </div>
                </td>
                <td class="text-right">
                    <strong>${formatRupiah(item.price * item.qty)}</strong>
                </td>
                <td class="text-center">
                    <button class="remove-btn" onclick="removeItem(${item.id})" title="Hapus Barang">×</button>
                </td>
            </tr>
        `
        )
        .join("");

    calculateTotal();
}

/* ============================================
   6. CART QUANTITY & ITEM MODIFICATIONS
   ============================================ */
function changeQty(id, amount) {
    const item = cart.find((item) => item.id === id);
    if (!item) return;

    item.qty += amount;

    if (item.qty <= 0) {
        removeItem(id);
        return;
    }

    renderCart();
}

function updateQty(id, qty) {
    const item = cart.find((item) => item.id === id);
    if (!item) return;

    item.qty = Math.max(1, parseInt(qty) || 1);
    renderCart();
}

function removeItem(id) {
    cart = cart.filter((item) => item.id !== id);
    renderCart();
}

/* ============================================
   7. CALCULATIONS (TOTAL & GRAND TOTAL)
   ============================================ */
function calculateTotal() {
    let totalQty = 0;
    let subtotal = 0;

    cart.forEach((item) => {
        totalQty += item.qty;
        subtotal += item.price * item.qty;
    });

    const discountPercentEl = document.getElementById("discountPercent");
    const discountAmountEl = document.getElementById("discountAmount");
    const taxEl = document.getElementById("tax");
    const otherFeeEl = document.getElementById("otherFee");

    const discountPercent = discountPercentEl ? parseFloat(discountPercentEl.value) || 0 : 0;
    const manualDiscount = discountAmountEl ? parseFloat(discountAmountEl.value) || 0 : 0;
    const tax = taxEl ? parseFloat(taxEl.value) || 0 : 0;
    const otherFee = otherFeeEl ? parseFloat(otherFeeEl.value) || 0 : 0;

    const percentDiscount = subtotal * (discountPercent / 100);
    const totalDiscount = percentDiscount + manualDiscount;
    const grandTotal = Math.max(0, subtotal - totalDiscount + tax + otherFee);

    const totalQtyEl = document.getElementById("totalQty");
    const itemCountEl = document.getElementById("itemCount");
    const subtotalEl = document.getElementById("subtotal");
    const grandTotalEl = document.getElementById("grandTotal");

    if (totalQtyEl) totalQtyEl.innerText = totalQty;
    if (itemCountEl) itemCountEl.innerText = totalQty + " Item";
    if (subtotalEl) subtotalEl.innerText = formatRupiah(subtotal);
    if (grandTotalEl) grandTotalEl.innerText = formatRupiah(grandTotal);

    calculateChange();
}

function getGrandTotal() {
    let subtotal = 0;
    cart.forEach((item) => {
        subtotal += item.price * item.qty;
    });

    const discountPercent = parseFloat(document.getElementById("discountPercent")?.value) || 0;
    const discountAmount = parseFloat(document.getElementById("discountAmount")?.value) || 0;
    const tax = parseFloat(document.getElementById("tax")?.value) || 0;
    const otherFee = parseFloat(document.getElementById("otherFee")?.value) || 0;

    const discount = (subtotal * discountPercent) / 100 + discountAmount;
    return Math.max(0, subtotal - discount + tax + otherFee);
}

/* ============================================
   8. CALCULATE CHANGE & SHORTAGE
   ============================================ */
function calculateChange() {
    const grandTotal = getGrandTotal();
    const paymentInput = document.getElementById("payment");
    const payment = parseFloat(paymentInput?.value) || 0;

    const changeBox = document.getElementById("changeBox");
    const changeElement = document.getElementById("change");
    const changeLabel = document.querySelector(".change-label");

    if (!changeBox || !changeElement) return;

    const change = payment - grandTotal;

    if (change >= 0) {
        changeBox.classList.remove("short-payment");
        changeElement.innerText = formatRupiah(change);
        if (changeLabel) changeLabel.innerText = "KEMBALIAN";
    } else {
        changeBox.classList.add("short-payment");
        changeElement.innerText = formatRupiah(Math.abs(change));
        if (changeLabel) changeLabel.innerText = "UANG KURANG";
    }
}

/* ============================================
   9. QUICK NOMINAL AMOUNTS
   ============================================ */
function setQuickAmount(amount) {
    const paymentInput = document.getElementById("payment");
    if (!paymentInput) return;

    if (amount === "pas") {
        paymentInput.value = getGrandTotal();
    } else {
        paymentInput.value = amount;
    }
    calculateChange();
}

/* ============================================
   10. PAYMENT METHOD SELECTION
   ============================================ */
function selectPayment(button) {
    document.querySelectorAll(".payment-method button").forEach((btn) => {
        btn.classList.remove("active");
    });
    button.classList.add("active");
}

/* ============================================
   11. TRANSACTION ACTIONS
   ============================================ */
function holdTransaction() {
    if (cart.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Perhatian",
            text: "Tidak ada transaksi untuk ditahan."
        });
        return;
    }

    Swal.fire({
        icon: "info",
        title: "Transaksi Ditahan",
        text: "Transaksi berhasil disimpan ke daftar pending.",
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        resetTransactionState();
    });
}

let transactionCounter = 1;

function processPayment() {
    if (cart.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Keranjang Kosong",
            text: "Silakan tambahkan barang terlebih dahulu."
        });
        return;
    }

    const total = getGrandTotal();
    const payment = parseFloat(document.getElementById("payment")?.value) || 0;

    if (payment < total) {
        Swal.fire({
            icon: "error",
            title: "Pembayaran Kurang",
            text: "Jumlah uang yang dibayarkan masih kurang dari Total Akhir."
        });
        return;
    }

    const change = payment - total;

    // Tampilkan SweetAlert sukses transaksi
    Swal.fire({
        icon: "success",
        title: "Pembayaran Berhasil!",
        html: `
            <div style="text-align: left; margin-top: 10px; font-size: 14px;">
                <b>Total:</b> ${formatRupiah(total)}<br>
                <b>Bayar:</b> ${formatRupiah(payment)}<br>
                <b>Kembali:</b> ${formatRupiah(change)}
            </div>
        `,
        confirmButtonText: "OK"
    }).then(() => {
        resetTransactionState();

        // Increment nomor transaksi
        transactionCounter++;
        const now = new Date();
        const dateStr = now.getFullYear().toString() +
            (now.getMonth() + 1).toString().padStart(2, '0') +
            now.getDate().toString().padStart(2, '0');
        const trxEl = document.getElementById("transactionNumber");
        if (trxEl) {
            trxEl.innerText = `TRX-${dateStr}-${transactionCounter.toString().padStart(3, '0')}`;
        }
    });
}

function cancelTransaction() {
    if (cart.length === 0) return;

    Swal.fire({
        title: "Batalkan Transaksi?",
        text: "Semua item di keranjang akan dihapus!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, Batalkan!",
        cancelButtonText: "Tidak"
    }).then((result) => {
        if (result.isConfirmed) {
            resetTransactionState();
            Swal.fire({
                icon: "success",
                title: "Dibatalkan",
                text: "Transaksi telah dibatalkan.",
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

// Helper untuk mereset state form kasir
function resetTransactionState() {
    cart = [];
    const paymentEl = document.getElementById("payment");
    const discountPercentEl = document.getElementById("discountPercent");
    const discountAmountEl = document.getElementById("discountAmount");
    const taxEl = document.getElementById("tax");
    const otherFeeEl = document.getElementById("otherFee");

    if (paymentEl) paymentEl.value = "";
    if (discountPercentEl) discountPercentEl.value = 0;
    if (discountAmountEl) discountAmountEl.value = 0;
    if (taxEl) taxEl.value = 0;
    if (otherFeeEl) otherFeeEl.value = 0;

    renderCart();
}
/* ============================================
   12. DATE & KEYBOARD SHORTCUTS
   ============================================ */
function updateDateTime() {
    const dateEl = document.getElementById("currentDate");
    if (dateEl) {
        dateEl.innerText = new Date().toLocaleString("id-ID", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit"
        });
    }
}

// Initial Date update & interval
updateDateTime();
setInterval(updateDateTime, 1000);

// Global Keyboard Shortcuts
document.addEventListener("keydown", function (event) {
    // F2 = fokus pencarian
    if (event.key === "F2") {
        event.preventDefault();
        if (searchInput) searchInput.focus();
    }

    // F4 = fokus pembayaran
    if (event.key === "F4") {
        event.preventDefault();
        const paymentEl = document.getElementById("payment");
        if (paymentEl) paymentEl.focus();
    }

    // ESC = batal
    if (event.key === "Escape") {
        cancelTransaction();
    }
});

/* ============================================
   13. INITIAL RENDER
   ============================================ */
renderCart();

# Minimarket POS - Sistem Kasir & Inventaris Toko
Praktikum Workshop Sistem Informasi Web Framework (Minggu 5 - Acara 17, 18, 19, 20)

**Nama Mahasiswa**: Ainul Hakimah  
**NIM**: E41252793  
**Dosen Pengampu**: David Juli Ariyadi, S.Kom., M.Kom  

---

### 📌 Deskripsi Proyek
Aplikasi Point of Sales (POS) dan Manajemen Inventaris Minimarket berbasis Laravel dengan integrasi tema DASHMIN. Dikembangkan untuk memenuhi tugas praktikum framework web yang mencakup pengelolaan basis data relasional, pemantauan stok otomatis, serta validasi formulir terpadu.

### 🚀 Modul & Fitur Praktikum:
- **Acara 17 - Laravel Query Builder**:
  - Rekapitulasi laporan stok dan nilai aset finansial menggunakan `selectRaw()` agregasi SQL (`COUNT`, `SUM(stock)`, `SUM(price*stock)`).
  - Join tabel `products` dan `categories` dengan filter dinamis `when()`.
- **Acara 18 - Eloquent ORM (Part 1)**:
  - Model `Product` dengan proteksi `$fillable` dan relasi `belongsTo(Category::class)`.
  - Eager Loading `Product::with('category')` untuk optimasi kueri database.
  - Indikator status stok visual: **Stok Menipis (≤ 10 unit)** vs **Stok Aman (> 10 unit)**.
- **Acara 19 - Eloquent ORM (Part 2)**:
  - Relasi One-to-Many `Category` ke `Product` dengan agregasi `Category::withCount('products')`.
  - Reusable Local Query Scope `Product::lowStock()`.
  - Mekanisme **Soft Deletes** (`deleted_at`), penelusuran data arsip `onlyTrashed()`, dan pemulihan produk via `restore()`.
- **Acara 20 - Form and Validation**:
  - Validasi melalui Form Request (`ProductRequest`) dengan pesan berbahasa Indonesia.
  - Otomatisasi pembuatan Kode Barang unik (`Product::generateSku()`) melalui hook `prepareForValidation()`.
  - Indikator visual realtime status stok pada form penambahan produk.

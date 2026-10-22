# 📋 Product Backlog — Sistem Pengelolaan Pesanan MR. COFFEE

> **Proyek:** Sistem Pengelolaan Pesanan pada UMKM Warung Kopi  
> **Disusun oleh:** Irma Safitri (S03 — Frontend Developer)  
> **Tanggal:** 23 Oktober 2026  
> **Jira Board:** [MSP Board](https://kurniawaniqbal241.atlassian.net/jira/software/projects/MSP/boards/2/backlog)  
> **Status:** Living Document — diperbarui setiap awal sprint

---

## 📌 Catatan Revisi dari Jira

Backlog di Jira (MSP Board) telah direvisi dan disempurnakan dalam dokumen ini:

| Jira ID | Item Lama (Jira) | Masalah | Revisi |
|:---:|:---|:---|:---|
| MSP-1 | Wawancara | Bukan user story, ini task | Dipindah ke Sprint Backlog S1 sebagai task |
| MSP-2 | Inisialisasi Kerangka Projek | Overlap dengan MSP-3 & MSP-5 | Digabung menjadi 1 epic "Inisiasi Proyek" |
| MSP-3 | Project Initiation | Overlap dengan MSP-2 | Digabung ke epic "Inisiasi Proyek" |
| MSP-4 | Survey Dan Dokumentasi | Terlalu umum | Dipecah jadi task spesifik di Sprint Backlog |
| MSP-5 | Menyiapkan Project Charter, Stakeholder Registration, WBS | Ini task, bukan user story | Dipindah ke Sprint Backlog S1 sebagai task |
| MSP-9 | Mulai Pengembangan | Terlalu vague | Dipecah menjadi user stories per fitur |
| MSP-10 | Desain UI/UX | Ini task, bukan user story | Dipindah ke Sprint Backlog S2 sebagai task |
| MSP-11 | Desain ERD | Ini task, bukan user story | Dipindah ke Sprint Backlog S2 sebagai task |
| MSP-12 | Desain Arsitektur | Ini task, bukan user story | Dipindah ke Sprint Backlog S2 sebagai task |

> ⚠️ **Penting:** Item di Jira yang bersifat *task* (bukan fitur produk) sebaiknya dipindahkan ke **Sprint Backlog** masing-masing sprint, bukan di Product Backlog. Product Backlog hanya berisi **fitur/fungsionalitas produk** dalam format User Story.

---

## 🏗️ Daftar Epic

| Epic ID | Nama Epic | Deskripsi | Sprint Target |
|:---:|:---|:---|:---:|
| EP-01 | Autentikasi & Otorisasi | Sistem login untuk kasir dan pengelolaan akses | Sprint 3 |
| EP-02 | Manajemen Menu | CRUD data menu dan harga warung kopi | Sprint 3 |
| EP-03 | Pengelolaan Pesanan | Input, pencatatan, dan tracking pesanan pelanggan | Sprint 3 |
| EP-04 | Pembayaran | Perhitungan total otomatis dan pencatatan pembayaran | Sprint 3 |
| EP-05 | Monitoring Status Pesanan | Tracking status pesanan real-time | Sprint 3 |
| EP-06 | Riwayat & Laporan | Penyimpanan transaksi dan laporan penjualan | Sprint 3 |

---

## 📝 User Stories

### EP-01: Autentikasi & Otorisasi

#### US-01 · Login Kasir
| Field | Detail |
|:---|:---|
| **ID** | US-01 |
| **Epic** | EP-01 Autentikasi & Otorisasi |
| **User Story** | Sebagai **kasir**, saya ingin **login ke sistem menggunakan username dan password**, agar **hanya pengguna yang berwenang yang dapat mengakses sistem pesanan**. |
| **Prioritas** | 🔴 High |
| **Story Point** | 3 |
| **Sprint** | Sprint 3 |
| **PIC** | Iqbal (Auth) + Jean (DB: users table) |

**Acceptance Criteria:**
- [ ] Kasir dapat login dengan username dan password yang valid
- [ ] Sistem menampilkan pesan error jika kredensial salah
- [ ] Setelah login berhasil, kasir diarahkan ke halaman dashboard
- [ ] Kasir yang belum login tidak dapat mengakses halaman sistem (redirect ke login)

---

#### US-02 · Logout Kasir
| Field | Detail |
|:---|:---|
| **ID** | US-02 |
| **Epic** | EP-01 Autentikasi & Otorisasi |
| **User Story** | Sebagai **kasir**, saya ingin **logout dari sistem**, agar **akun saya aman saat meninggalkan komputer**. |
| **Prioritas** | 🟡 Medium |
| **Story Point** | 1 |
| **Sprint** | Sprint 3 |
| **PIC** | Iqbal (Auth) |

**Acceptance Criteria:**
- [ ] Terdapat tombol/link logout yang terlihat jelas di navbar
- [ ] Setelah logout, kasir diarahkan ke halaman login
- [ ] Session kasir dihapus setelah logout

---

### EP-02: Manajemen Menu

#### US-03 · Melihat Daftar Menu
| Field | Detail |
|:---|:---|
| **ID** | US-03 |
| **Epic** | EP-02 Manajemen Menu |
| **User Story** | Sebagai **kasir**, saya ingin **melihat daftar seluruh menu beserta harga**, agar **saya mengetahui menu yang tersedia saat menerima pesanan**. |
| **Prioritas** | 🔴 High |
| **Story Point** | 2 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller) + Irma (View) |

**Acceptance Criteria:**
- [ ] Halaman menampilkan daftar menu dalam bentuk tabel/kartu
- [ ] Setiap item menampilkan: nama menu, kategori, harga
- [ ] Daftar menu dapat di-scroll jika jumlahnya banyak

---

#### US-04 · Menambah Menu Baru
| Field | Detail |
|:---|:---|
| **ID** | US-04 |
| **Epic** | EP-02 Manajemen Menu |
| **User Story** | Sebagai **kasir**, saya ingin **menambahkan menu baru ke dalam sistem**, agar **daftar menu selalu up-to-date ketika ada menu baru**. |
| **Prioritas** | 🔴 High |
| **Story Point** | 3 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller) + Irma (View) + Jean (Migration) |

**Acceptance Criteria:**
- [ ] Terdapat form untuk menambah menu baru (nama, kategori, harga)
- [ ] Validasi input: nama wajib diisi, harga harus angka positif
- [ ] Setelah berhasil ditambah, menu muncul di daftar
- [ ] Sistem menampilkan notifikasi sukses/gagal

---

#### US-05 · Mengubah Data Menu
| Field | Detail |
|:---|:---|
| **ID** | US-05 |
| **Epic** | EP-02 Manajemen Menu |
| **User Story** | Sebagai **kasir**, saya ingin **mengubah nama atau harga menu yang sudah ada**, agar **informasi menu tetap akurat**. |
| **Prioritas** | 🟡 Medium |
| **Story Point** | 2 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller) + Irma (View) |

**Acceptance Criteria:**
- [ ] Terdapat tombol edit pada setiap item menu
- [ ] Form edit menampilkan data menu saat ini
- [ ] Setelah disimpan, perubahan langsung terlihat di daftar
- [ ] Validasi input sama seperti tambah menu

---

#### US-06 · Menghapus Menu
| Field | Detail |
|:---|:---|
| **ID** | US-06 |
| **Epic** | EP-02 Manajemen Menu |
| **User Story** | Sebagai **kasir**, saya ingin **menghapus menu yang sudah tidak dijual**, agar **daftar menu tidak membingungkan**. |
| **Prioritas** | 🟡 Medium |
| **Story Point** | 2 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller) + Irma (View) |

**Acceptance Criteria:**
- [ ] Terdapat tombol hapus pada setiap item menu
- [ ] Sistem menampilkan konfirmasi sebelum menghapus
- [ ] Menu yang sudah memiliki riwayat pesanan tidak dihapus permanen (soft delete)
- [ ] Setelah dihapus, menu hilang dari daftar

---

### EP-03: Pengelolaan Pesanan

#### US-07 · Membuat Pesanan Baru
| Field | Detail |
|:---|:---|
| **ID** | US-07 |
| **Epic** | EP-03 Pengelolaan Pesanan |
| **User Story** | Sebagai **kasir**, saya ingin **mencatat pesanan pelanggan dengan memilih menu dan jumlah**, agar **pesanan tercatat secara digital dan tidak ada yang tertukar**. |
| **Prioritas** | 🔴 High (Core Feature) |
| **Story Point** | 5 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller + Service) + Irma (View) + Jean (Migration) |

**Acceptance Criteria:**
- [ ] Kasir dapat memilih menu dari daftar menu yang tersedia
- [ ] Kasir dapat menentukan jumlah (qty) untuk setiap item
- [ ] Kasir dapat menambahkan beberapa item menu dalam satu pesanan
- [ ] Subtotal per item dihitung otomatis (harga × qty)
- [ ] Kasir dapat menghapus item dari pesanan sebelum konfirmasi
- [ ] Terdapat field opsional untuk nama pelanggan atau nomor meja
- [ ] Setelah dikonfirmasi, pesanan tersimpan dengan status "Menunggu"

---

#### US-08 · Melihat Daftar Pesanan Aktif
| Field | Detail |
|:---|:---|
| **ID** | US-08 |
| **Epic** | EP-03 Pengelolaan Pesanan |
| **User Story** | Sebagai **kasir**, saya ingin **melihat daftar semua pesanan yang sedang aktif**, agar **saya dapat memantau pesanan yang belum selesai**. |
| **Prioritas** | 🔴 High |
| **Story Point** | 3 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller) + Irma (View) |

**Acceptance Criteria:**
- [ ] Halaman menampilkan daftar pesanan aktif (status: Menunggu / Diproses)
- [ ] Setiap pesanan menampilkan: ID pesanan, nama pelanggan/nomor meja, total item, total harga, status, waktu pemesanan
- [ ] Pesanan diurutkan berdasarkan waktu (terbaru di atas)

---

### EP-04: Pembayaran

#### US-09 · Menghitung Total Pembayaran Otomatis
| Field | Detail |
|:---|:---|
| **ID** | US-09 |
| **Epic** | EP-04 Pembayaran |
| **User Story** | Sebagai **kasir**, saya ingin **melihat total pembayaran yang dihitung otomatis oleh sistem**, agar **proses pembayaran lebih cepat dan tidak terjadi kesalahan hitung**. |
| **Prioritas** | 🔴 High |
| **Story Point** | 3 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Service: kalkulasi) + Irma (View) |

**Acceptance Criteria:**
- [ ] Total pembayaran dihitung otomatis dari seluruh item pesanan
- [ ] Total ditampilkan dengan format rupiah (Rp)
- [ ] Total diperbarui secara real-time saat item ditambah/dihapus

---

#### US-10 · Mencatat Pembayaran
| Field | Detail |
|:---|:---|
| **ID** | US-10 |
| **Epic** | EP-04 Pembayaran |
| **User Story** | Sebagai **kasir**, saya ingin **mencatat pembayaran pelanggan dan melihat kembalian**, agar **proses transaksi tercatat dengan rapi**. |
| **Prioritas** | 🔴 High |
| **Story Point** | 3 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller) + Irma (View) + Jean (Migration: payments) |

**Acceptance Criteria:**
- [ ] Kasir dapat memasukkan jumlah uang yang dibayarkan pelanggan
- [ ] Sistem menghitung dan menampilkan kembalian
- [ ] Sistem memvalidasi bahwa uang bayar ≥ total pesanan
- [ ] Setelah pembayaran dikonfirmasi, transaksi tersimpan di database
- [ ] Metode pembayaran tercatat (Tunai)

---

#### US-11 · Mencetak/Melihat Struk Pembayaran
| Field | Detail |
|:---|:---|
| **ID** | US-11 |
| **Epic** | EP-04 Pembayaran |
| **User Story** | Sebagai **kasir**, saya ingin **melihat atau mencetak struk pembayaran**, agar **pelanggan mendapat bukti transaksi**. |
| **Prioritas** | 🟡 Medium |
| **Story Point** | 3 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller) + Irma (View: receipt layout) |

**Acceptance Criteria:**
- [ ] Struk menampilkan: nama warung, tanggal/waktu, daftar item + qty + harga, total, bayar, kembalian
- [ ] Struk dapat dicetak melalui browser (print dialog)
- [ ] Layout struk rapi dan mudah dibaca

---

### EP-05: Monitoring Status Pesanan

#### US-12 · Mengubah Status Pesanan
| Field | Detail |
|:---|:---|
| **ID** | US-12 |
| **Epic** | EP-05 Monitoring Status Pesanan |
| **User Story** | Sebagai **kasir/waiter**, saya ingin **mengubah status pesanan dari "Menunggu" ke "Diproses" ke "Selesai"**, agar **semua pihak mengetahui progres pesanan**. |
| **Prioritas** | 🔴 High |
| **Story Point** | 3 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller) + Irma (View: status badge) |

**Acceptance Criteria:**
- [ ] Terdapat tombol/dropdown untuk mengubah status pesanan
- [ ] Status yang tersedia: Menunggu → Diproses → Selesai
- [ ] Perubahan status langsung terlihat di daftar pesanan
- [ ] Status ditampilkan dengan warna berbeda (Menunggu: kuning, Diproses: biru, Selesai: hijau)

---

#### US-13 · Memantau Status Pesanan Real-Time
| Field | Detail |
|:---|:---|
| **ID** | US-13 |
| **Epic** | EP-05 Monitoring Status Pesanan |
| **User Story** | Sebagai **kasir**, saya ingin **memantau status semua pesanan secara real-time di satu halaman**, agar **saya tahu pesanan mana yang sudah siap atau masih diproses**. |
| **Prioritas** | 🟡 Medium |
| **Story Point** | 3 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller) + Irma (View: dashboard status) |

**Acceptance Criteria:**
- [ ] Halaman monitoring menampilkan pesanan yang dikelompokkan per status
- [ ] Jumlah pesanan per status ditampilkan (badge angka)
- [ ] Halaman auto-refresh atau bisa di-refresh manual

---

### EP-06: Riwayat & Laporan

#### US-14 · Melihat Riwayat Transaksi
| Field | Detail |
|:---|:---|
| **ID** | US-14 |
| **Epic** | EP-06 Riwayat & Laporan |
| **User Story** | Sebagai **kasir**, saya ingin **melihat riwayat seluruh transaksi yang sudah selesai**, agar **data penjualan tersimpan dan bisa ditelusuri**. |
| **Prioritas** | 🟡 Medium |
| **Story Point** | 3 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller) + Irma (View) |

**Acceptance Criteria:**
- [ ] Halaman menampilkan daftar transaksi yang sudah selesai
- [ ] Setiap transaksi menampilkan: tanggal, ID pesanan, total, metode bayar
- [ ] Kasir dapat melihat detail transaksi (daftar item yang dipesan)
- [ ] Riwayat dapat difilter berdasarkan rentang tanggal

---

#### US-15 · Melihat Laporan Penjualan Sederhana
| Field | Detail |
|:---|:---|
| **ID** | US-15 |
| **Epic** | EP-06 Riwayat & Laporan |
| **User Story** | Sebagai **kasir/pemilik**, saya ingin **melihat laporan penjualan harian**, agar **saya mengetahui total pendapatan dan jumlah transaksi hari ini**. |
| **Prioritas** | 🟢 Low |
| **Story Point** | 3 |
| **Sprint** | Sprint 3 |
| **PIC** | Rizqi (Controller: aggregation) + Irma (View: dashboard) |

**Acceptance Criteria:**
- [ ] Dashboard menampilkan ringkasan penjualan hari ini: total transaksi, total pendapatan
- [ ] Menampilkan menu terlaris (opsional)
- [ ] Data dapat dilihat per tanggal tertentu

---

## 📊 Ringkasan Product Backlog

### Berdasarkan Prioritas

| Prioritas | Jumlah | User Stories |
|:---|:---:|:---|
| 🔴 High | 9 | US-01, US-03, US-04, US-07, US-08, US-09, US-10, US-12 |
| 🟡 Medium | 5 | US-02, US-05, US-06, US-11, US-13, US-14 |
| 🟢 Low | 1 | US-15 |
| **Total** | **15** | |

### Berdasarkan Epic

| Epic | User Stories | Total Story Points |
|:---|:---|:---:|
| EP-01 Autentikasi | US-01, US-02 | 4 |
| EP-02 Manajemen Menu | US-03, US-04, US-05, US-06 | 9 |
| EP-03 Pengelolaan Pesanan | US-07, US-08 | 8 |
| EP-04 Pembayaran | US-09, US-10, US-11 | 9 |
| EP-05 Monitoring Status | US-12, US-13 | 6 |
| EP-06 Riwayat & Laporan | US-14, US-15 | 6 |
| **Total** | **15 stories** | **42 SP** |

### Berdasarkan PIC (Sprint 3 — Pengembangan)

| PIC | Tugas Utama | User Stories Terlibat | Estimasi SP |
|:---|:---|:---|:---:|
| **Iqbal** (PM) | Auth, Middleware, Integrasi | US-01, US-02 | 4 |
| **Rizqi** (BE) | Controllers, Routes, Services | US-03–US-15 (semua backend) | ~42 (shared) |
| **Irma** (FE) | Blade Views, CSS, JS | US-03–US-15 (semua frontend) | ~42 (shared) |
| **Jean** (DB) | Migrations, Models, Seeders | US-01, US-04, US-07, US-10 | ~12 |

---

## 🔄 Pemetaan ke Sprint

| Sprint | Fokus | Backlog Items |
|:---:|:---|:---|
| **Sprint 1** | Perencanaan | *(Tidak ada user story — fokus pada project charter, WBS, product backlog)* |
| **Sprint 2** | Perancangan | *(Tidak ada user story — fokus pada ERD, Use Case, Mockup UI, Arsitektur)* |
| **Sprint 3** | Pengembangan | US-01 s/d US-15 (seluruh fitur dikerjakan) |
| **Sprint 4** | Pengujian | *(Testing user stories dari Sprint 3, bug fix, dokumentasi)* |

---

## 📎 Rekomendasi Revisi Jira

Berikut saran revisi agar Jira Board sesuai dengan Product Backlog ini:

### Sprint 1 (Jira: MSP Sprint 1)
Hapus item yang bukan product backlog, pindahkan ke Sprint Backlog:

| Action | Jira ID | Keterangan |
|:---:|:---:|:---|
| ❌ Hapus dari backlog | MSP-1 | "Wawancara" → ini task, bukan fitur produk |
| ❌ Hapus dari backlog | MSP-2 | "Inisialisasi Kerangka Projek" → task |
| ❌ Hapus dari backlog | MSP-3 | "Project Initiation" → overlap dengan MSP-2 |
| ❌ Hapus dari backlog | MSP-4 | "Survey Dan Dokumentasi" → task |
| ❌ Hapus dari backlog | MSP-5 | "Menyiapkan Project Charter..." → task |

> 💡 Sprint 1 seharusnya berisi task perencanaan di Sprint Backlog, bukan di Product Backlog.

### Sprint 2 (Jira: MSP Sprint 2)
| Action | Jira ID | Keterangan |
|:---:|:---:|:---|
| ❌ Hapus dari backlog | MSP-9 | "Mulai Pengembangan" → terlalu vague, pecah jadi user stories |
| ❌ Hapus dari backlog | MSP-10 | "Desain UI/UX" → ini task perancangan |
| ❌ Hapus dari backlog | MSP-11 | "Desain ERD" → ini task perancangan |
| ❌ Hapus dari backlog | MSP-12 | "Desain Arsitektur" → ini task perancangan |

### Sprint 3 (Buat Baru di Jira)
Tambahkan 15 user stories (US-01 s/d US-15) sebagai item baru di Sprint 3.

### Sprint 4 (Buat Baru di Jira)
Tambahkan task testing & dokumentasi.

---

<p align="center">
  <i>Dokumen ini disusun oleh <b>Irma Safitri (S03)</b> — Sprint 1, MPPL Kelompok 8</i>
</p>

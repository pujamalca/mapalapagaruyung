# Panduan Export Data - Mapala Pagaruyung

Panduan ini menjelaskan cara mengexport data dari sistem Mapala Pagaruyung ke file CSV atau Excel untuk keperluan backup, reporting, atau analisis data.

## Daftar Isi
1. [Fitur Export](#fitur-export)
2. [Cara Menggunakan Export](#cara-menggunakan-export)
3. [Jenis Export yang Tersedia](#jenis-export-yang-tersedia)
4. [Format Data Export](#format-data-export)
5. [Tips dan Best Practices](#tips-dan-best-practices)
6. [Use Cases Export](#use-cases-export)

---

## Fitur Export

### Keunggulan Sistem Export

✅ **Format File Fleksibel** - Export ke CSV atau Excel (.xlsx)
✅ **Filter Data** - Export hanya data yang difilter atau semua data
✅ **Background Processing** - Export besar diproses di background
✅ **Progress Tracking** - Monitor progress export real-time
✅ **Notifikasi** - Notifikasi otomatis saat export selesai
✅ **Download Manager** - Kelola dan download hasil export kapan saja
✅ **Formatted Data** - Data sudah diformat dalam Bahasa Indonesia
✅ **Batch Export** - Dapat export data dalam jumlah besar

---

## Cara Menggunakan Export

### Langkah-langkah Dasar

1. **Buka halaman resource yang ingin diexport**
   - Misalnya: Admin Panel → Keanggotaan → Anggota

2. **Klik tombol "Export"**
   - Tombol berwarna biru dengan icon download
   - Terletak di header bagian kanan

3. **Pilih kolom yang ingin diexport**
   - Centang/uncheck kolom sesuai kebutuhan
   - Secara default semua kolom ter-centang

4. **Pilih format file**
   - **CSV** - Lebih ringan, cocok untuk data besar
   - **XLSX** - Excel format, mendukung formatting

5. **Klik "Export"**
   - Untuk data kecil (<1000 baris): download langsung
   - Untuk data besar: diproses di background

6. **Download hasil export**
   - Jika diproses di background, cek di menu "Exports"
   - Download file hasil export

### Export dengan Filter

Anda bisa mengexport hanya data yang difilter:

1. **Terapkan filter terlebih dahulu**
   - Gunakan search box untuk mencari
   - Gunakan filter kolom
   - Gunakan tab untuk filter by status

2. **Klik tombol "Export"**
   - Export hanya akan mencakup data yang terfilter
   - Jumlah record yang akan diexport ditampilkan

3. **Pilih "Export current page" atau "Export all matching"**
   - **Current page**: Export hanya halaman yang sedang dilihat
   - **All matching**: Export semua data yang cocok dengan filter

---

## Jenis Export yang Tersedia

### 1. Export Anggota

**Lokasi:** Admin Panel → Keanggotaan → Anggota → Export Anggota

**Kolom yang diexport:**
- Data Pribadi: Nama, Email, Username, No. Telepon
- Informasi Organisasi: Angkatan, Divisi, Status Aktif, Role
- Alamat & Identitas: Alamat, Golongan Darah
- Kontak Darurat: Nama, No. Telepon, Hubungan
- Info Kesehatan: Alergi, Kondisi Medis
- Bio: Deskripsi singkat
- Metadata: Tanggal dibuat, Terakhir diupdate

**Use Case:**
- Backup data anggota
- Laporan keanggotaan untuk rapat
- Analisis demografis anggota
- Mail merge untuk surat/email blast
- Daftar kontak darurat

---

### 2. Export Peralatan

**Lokasi:** Admin Panel → Inventaris → Peralatan → Export Peralatan

**Kolom yang diexport:**
- Identifikasi: Kode Alat, Nama, Kategori
- Spesifikasi: Merek, Model, Berat, Dimensi
- Inventaris: Jumlah Total, Jumlah Tersedia, Jumlah Dipinjam
- Kondisi: Status, Kondisi Fisik
- Pembelian: Tanggal Beli, Harga Beli
- Maintenance: Maintenance Terakhir, Maintenance Berikutnya
- Lokasi: Tempat penyimpanan
- Catatan: Notes

**Use Case:**
- Audit inventaris peralatan
- Laporan nilai aset
- Perencanaan maintenance
- Identifikasi peralatan yang perlu dibeli
- Stock opname periodik

---

### 3. Export Ekspedisi

**Lokasi:** Admin Panel → Aktivitas → Ekspedisi → Export Ekspedisi

**Kolom yang diexport:**
- Identifikasi: Kode, Judul, Tujuan
- Klasifikasi: Tipe, Tingkat Kesulitan
- Jadwal: Tanggal Mulai, Tanggal Selesai, Durasi
- Peserta: Jumlah Peserta, Peserta Maksimal, Koordinator
- Status: Status Ekspedisi, Ekspedisi Resmi
- Budget: Estimasi, Budget Aktual
- Detail: Base Camp, Ketinggian, Deskripsi, Tujuan
- Metadata: Tanggal dibuat

**Use Case:**
- Laporan kegiatan tahunan
- Analisis tren ekspedisi
- Perhitungan budget
- Database ekspedisi historical
- Proposal kegiatan

---

### 4. Export Program Pelatihan

**Lokasi:** Admin Panel → Aktivitas → Program Pelatihan → Export Program Pelatihan

**Kolom yang diexport:**
- Identifikasi: Kode, Judul, Tipe, Tingkat
- Jadwal: Tanggal Mulai, Tanggal Selesai, Durasi (Jam)
- Lokasi: Tempat pelaksanaan
- Peserta: Jumlah Peserta, Peserta Maksimal, Koordinator
- Status: Status Program, Wajib/Tidak
- Biaya: Biaya Pendaftaran
- Sertifikat: Diterbitkan, Prefix Nomor
- Detail: Deskripsi, Tujuan, Persyaratan, Materi
- Metadata: Tanggal dibuat

**Use Case:**
- Laporan program pelatihan
- Evaluasi efektivitas training
- Perencanaan jadwal training
- Database training historical
- Rekap penerbitan sertifikat

---

### 5. Export Kompetisi/Event

**Lokasi:** Admin Panel → Aktivitas → Kompetisi/Event → Export Kompetisi

**Kolom yang diexport:**
- Identifikasi: Kode, Judul, Tipe, Tingkat
- Jadwal: Tanggal Mulai, Tanggal Selesai
- Lokasi: Tempat kompetisi
- Penyelenggara: Nama Penyelenggara, Koordinator Tim
- Status: Status Kompetisi
- Pendaftaran: Batas Pendaftaran, Biaya, Peserta Max
- Peserta: Jumlah Peserta
- Detail: Deskripsi, Peraturan, Hadiah, Website
- Metadata: Tanggal dibuat

**Use Case:**
- Laporan prestasi kompetisi
- Database kompetisi yang diikuti
- Analisis partisipasi kompetisi
- Laporan penghargaan/prestasi
- Planning kompetisi tahun depan

---

### 6. Export Peminjaman Peralatan

**Lokasi:** Admin Panel → Inventaris → Peminjaman Peralatan → Export Peminjaman

**Kolom yang diexport:**
- Identifikasi: Kode Peminjaman, Peminjam, Email
- Peralatan: Nama, Kode, Jumlah Dipinjam
- Jadwal: Tanggal Pinjam, Jatuh Tempo, Tanggal Kembali
- Status: Status Peminjaman, Terlambat (Ya/Tidak), Hari Terlambat
- Kondisi: Kondisi Sebelum, Kondisi Setelah
- Denda: Denda Keterlambatan
- Approval: Disetujui Oleh, Tanggal Disetujui
- Detail: Tujuan Peminjaman, Catatan
- Metadata: Tanggal dibuat

**Use Case:**
- Laporan peminjaman peralatan
- Rekap denda keterlambatan
- Analisis utilitas peralatan
- Identifikasi frequent borrower
- Audit peminjaman

---

## Format Data Export

### Format Tanggal
Semua tanggal diexport dalam format: **DD/MM/YYYY**
Contoh: 25/12/2024

Tanggal dengan waktu: **DD/MM/YYYY HH:MM**
Contoh: 25/12/2024 14:30

### Format Angka
- Angka biasa: tanpa separator (contoh: 1500)
- Uang: "Rp " + format Indonesia (contoh: Rp 1.500.000)

### Format Boolean
- Ya/Tidak (bukan 1/0 atau true/false)
- Aktif/Tidak Aktif untuk status

### Format Enum
Semua enum diterjemahkan ke Bahasa Indonesia:
- Status: Menunggu, Disetujui, Sedang Dipinjam, dll
- Kondisi: Sangat Baik, Baik, Cukup, Kurang Baik, Rusak
- Tipe: Pendakian, Hiking, Camping, dll

### Format Relasi
- Ditampilkan sebagai nama, bukan ID
- Multiple relations: dipisah dengan koma
- Contoh: "Member, Senior Member, Alumni"

---

## Tips dan Best Practices

### 1. Pilih Kolom yang Diperlukan
- Jangan export semua kolom jika tidak perlu
- Kolom yang lebih sedikit = file lebih kecil & cepat
- Pilih kolom sesuai tujuan export

### 2. Gunakan Filter untuk Export Spesifik
**Contoh use case:**
- Export hanya anggota aktif
- Export peralatan yang perlu maintenance
- Export ekspedisi tahun 2024
- Export peminjaman yang terlambat

### 3. Pilih Format yang Tepat

**CSV (Comma-Separated Values):**
- ✅ Lebih ringan dan cepat
- ✅ Cocok untuk data besar (>10,000 rows)
- ✅ Universal, bisa dibuka di berbagai aplikasi
- ✅ Cocok untuk import ke sistem lain
- ❌ Tidak mendukung formatting (bold, warna, dll)
- ❌ Tidak mendukung multiple sheets

**XLSX (Excel):**
- ✅ Mendukung formatting
- ✅ Mendukung formula (jika ditambahkan manual)
- ✅ Lebih user-friendly untuk end user
- ✅ Bisa dianalisis langsung di Excel
- ❌ File size lebih besar
- ❌ Lebih lambat untuk data sangat besar

### 4. Export Berkala untuk Backup
**Rekomendasi jadwal backup:**
- **Harian:** Peminjaman peralatan
- **Mingguan:** Anggota (jika ada perubahan)
- **Bulanan:** Peralatan, Ekspedisi, Training, Kompetisi
- **Tahunan:** Full backup semua data

### 5. Penamaan File yang Konsisten
Sistem akan otomatis memberi nama file, tapi Anda bisa rename:

**Format rekomendasi:**
```
[JenisData]_[TanggalExport]_[Keterangan].xlsx
```

**Contoh:**
- `Anggota_20241225_Backup.xlsx`
- `Peralatan_20241231_Audit.xlsx`
- `Ekspedisi_2024_LaporanTahunan.xlsx`
- `Peminjaman_202412_Terlambat.xlsx`

### 6. Verifikasi Data Setelah Export
Setelah export, selalu lakukan:
- ✅ Cek jumlah baris (sesuai ekspektasi?)
- ✅ Buka file dan lihat sample data
- ✅ Pastikan tidak ada data kosong yang tidak seharusnya
- ✅ Cek format tanggal dan angka

### 7. Simpan Export di Tempat Aman
- **Cloud storage:** Google Drive, Dropbox, OneDrive
- **Local backup:** External hard drive
- **Password protection:** Untuk data sensitif
- **Retention policy:** Hapus backup lama sesuai kebijakan

### 8. Export untuk Analisis Data
Data yang sudah diexport bisa dianalisis dengan:
- **Microsoft Excel:** Pivot tables, charts, formulas
- **Google Sheets:** Kolaborasi real-time, Google Data Studio
- **Python/R:** Data science & machine learning
- **Power BI / Tableau:** Business intelligence & visualization
- **SPSS:** Statistical analysis

---

## Use Cases Export

### Use Case 1: Laporan Keanggotaan Tahunan

**Tujuan:** Membuat laporan statistik keanggotaan untuk rapat tahunan

**Langkah:**
1. Export Anggota (filter: is_active = true)
2. Pilih kolom: Nama, Email, Angkatan, Divisi, Role
3. Format: XLSX
4. Analisis di Excel:
   - Pivot table: jumlah per angkatan
   - Pivot table: jumlah per divisi
   - Pie chart: distribusi role
   - Trend chart: pertumbuhan anggota per tahun

---

### Use Case 2: Audit Inventaris Peralatan

**Tujuan:** Melakukan stock opname dan penilaian aset

**Langkah:**
1. Export Peralatan (semua data)
2. Pilih kolom: Kode, Nama, Kategori, Jumlah Total, Jumlah Tersedia, Kondisi, Harga Beli, Lokasi
3. Format: XLSX
4. Proses:
   - Print atau bawa tablet untuk stock opname fisik
   - Cek kondisi fisik vs kondisi di sistem
   - Update kondisi yang berbeda
   - Hitung total nilai aset: SUM(Jumlah Total × Harga Beli)

---

### Use Case 3: Analisis Budget Ekspedisi

**Tujuan:** Menganalisis pengeluaran ekspedisi untuk perencanaan budget tahun depan

**Langkah:**
1. Export Ekspedisi (filter: tahun 2024, status = completed)
2. Pilih kolom: Judul, Tujuan, Peserta, Estimasi Budget, Budget Aktual
3. Format: XLSX
4. Analisis:
   - Total pengeluaran tahun 2024
   - Rata-rata budget per ekspedisi
   - Rata-rata budget per peserta
   - Variance: (Budget Aktual - Estimasi Budget)
   - Identifikasi ekspedisi yang over/under budget

---

### Use Case 4: Rekap Denda Peminjaman

**Tujuan:** Menghitung total denda keterlambatan untuk pengelolaan keuangan

**Langkah:**
1. Export Peminjaman Peralatan (filter: late_penalty > 0)
2. Pilih kolom: Peminjam, Peralatan, Hari Terlambat, Denda Keterlambatan, Tanggal Kembali
3. Format: XLSX
4. Analisis:
   - SUM(Denda Keterlambatan) = Total denda
   - TOP 10 borrower dengan denda terbanyak
   - Tren keterlambatan per bulan
   - Peralatan yang paling sering terlambat dikembalikan

---

### Use Case 5: Database Alumni

**Tujuan:** Membuat database alumni untuk networking

**Langkah:**
1. Export Anggota (filter: role = Alumni)
2. Pilih kolom: Nama, Email, Phone, Angkatan, Divisi, Bio
3. Format: CSV
4. Gunakan untuk:
   - Import ke mailing list (Mailchimp, Google Groups)
   - CRM alumni management
   - LinkedIn Alumni network
   - Event invitation list

---

### Use Case 6: Sertifikat Training

**Tujuan:** Generate sertifikat untuk peserta training yang lulus

**Langkah:**
1. Export Training Programs (filter: certificate_issued = true)
2. Export Participants untuk training tertentu
3. Format: CSV
4. Mail merge:
   - Template sertifikat di Word/Google Docs
   - Mail merge dengan data peserta
   - Generate PDF sertifikat individual
   - Email blast sertifikat ke peserta

---

## Troubleshooting

### Export Gagal
**Masalah:** Export button tidak bekerja atau error

**Solusi:**
- Refresh halaman dan coba lagi
- Cek koneksi internet
- Coba dengan jumlah data lebih kecil (gunakan filter)
- Hubungi administrator

### File Terlalu Besar
**Masalah:** Export menghasilkan file yang sangat besar

**Solusi:**
- Pilih hanya kolom yang diperlukan
- Gunakan filter untuk export subset data
- Export per periode (per bulan/quarter)
- Gunakan CSV instead of XLSX

### Data Tidak Lengkap
**Masalah:** Export hanya menampilkan sebagian data

**Solusi:**
- Pastikan tidak ada filter aktif yang membatasi data
- Pilih "Export all matching" bukan "Export current page"
- Cek apakah ada batasan pada user permission

### Format Tanggal Salah di Excel
**Masalah:** Excel menampilkan tanggal dengan format yang aneh

**Solusi:**
- Buka file Excel → Select kolom tanggal
- Format Cells → Date → Pilih format DD/MM/YYYY
- Atau set regional settings di Excel ke Indonesia

---

## Support

Jika mengalami kendala saat export, hubungi administrator sistem atau buat issue di repository GitHub.

**Happy Exporting! 📊📈**

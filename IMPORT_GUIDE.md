# Panduan Import Data - Mapala Pagaruyung

Panduan ini menjelaskan cara mengimport data ke sistem Mapala Pagaruyung menggunakan file CSV atau Excel.

## Daftar Isi
1. [Import Anggota](#1-import-anggota)
2. [Import Peralatan](#2-import-peralatan)
3. [Import Ekspedisi](#3-import-ekspedisi)
4. [Import Program Pelatihan](#4-import-program-pelatihan)
5. [Import Kompetisi](#5-import-kompetisi)
6. [Tips dan Best Practices](#tips-dan-best-practices)

---

## 1. Import Anggota

### Lokasi Menu
Admin Panel → Keanggotaan → Anggota → Import Anggota

### Kolom yang Tersedia

| Kolom | Wajib | Tipe | Contoh | Keterangan |
|-------|-------|------|--------|------------|
| `name` | Ya | Teks | John Doe | Nama lengkap anggota |
| `email` | Ya | Email | john@example.com | Email unik untuk login |
| `username` | Tidak | Teks | johndoe | Username untuk login |
| `phone` | Tidak | Teks | 08123456789 | Nomor telepon |
| `password` | Ya | Teks | password123 | Password awal (min 8 karakter) |
| `cohort` | Tidak | Teks | Kader XXIV | Nama angkatan (harus sudah ada) |
| `division` | Tidak | Teks | Divisi Pendakian | Nama divisi (harus sudah ada) |
| `address` | Tidak | Teks | Jl. Contoh No. 123 | Alamat lengkap |
| `blood_type` | Tidak | Teks | A+ | Golongan darah |
| `emergency_contact_name` | Tidak | Teks | Jane Doe | Nama kontak darurat |
| `emergency_contact_phone` | Tidak | Teks | 08198765432 | No. telepon kontak darurat |
| `emergency_contact_relation` | Tidak | Teks | Orang Tua | Hubungan dengan kontak darurat |
| `allergies` | Tidak | Teks | Tidak ada | Alergi yang dimiliki |
| `medical_conditions` | Tidak | Teks | Sehat | Kondisi medis khusus |
| `bio` | Tidak | Teks | Member aktif | Bio singkat |
| `is_active` | Tidak | Boolean | 1 | Status aktif (1=aktif, 0=tidak) |
| `role` | Ya | Teks | Member | Role: Member, Senior Member, atau Alumni |

### Template CSV

```csv
name,email,username,phone,password,cohort,division,address,blood_type,emergency_contact_name,emergency_contact_phone,emergency_contact_relation,allergies,medical_conditions,bio,is_active,role
John Doe,john@example.com,johndoe,08123456789,password123,Kader XXIV,Divisi Pendakian,"Jl. Contoh No. 123",A+,Jane Doe,08198765432,Orang Tua,Tidak ada,Sehat,Member aktif Mapala,1,Member
Jane Smith,jane@example.com,janesmith,08129876543,password123,Kader XXIII,Divisi Panjat Tebing,"Jl. Test No. 456",B+,John Smith,08197654321,Sibling,Seafood,Sehat,Alumni Mapala yang aktif,1,Alumni
```

### Catatan Penting
- Email harus unik (tidak boleh duplikat)
- Cohort dan Division harus sudah dibuat terlebih dahulu
- Password akan di-hash otomatis saat import
- Role yang valid: `Member`, `Senior Member`, `Alumni`

---

## 2. Import Peralatan

### Lokasi Menu
Admin Panel → Inventaris → Peralatan → Import Peralatan

### Kolom yang Tersedia

| Kolom | Wajib | Tipe | Contoh | Keterangan |
|-------|-------|------|--------|------------|
| `code` | Ya | Teks | EQ-001 | Kode unik peralatan |
| `name` | Ya | Teks | Tenda Dome 4 Orang | Nama peralatan |
| `category` | Ya | Teks | Tenda | Nama kategori (harus sudah ada) |
| `brand` | Tidak | Teks | Consina | Merek/brand |
| `model` | Tidak | Teks | Magnum 4 | Model peralatan |
| `quantity_total` | Ya | Angka | 10 | Jumlah total |
| `quantity_available` | Tidak | Angka | 8 | Jumlah tersedia (default = total) |
| `unit` | Tidak | Teks | unit | Satuan |
| `condition` | Tidak | Teks | good | excellent, good, fair, poor, damaged |
| `status` | Tidak | Teks | available | available, borrowed, maintenance, retired |
| `purchase_date` | Tidak | Tanggal | 2024-01-15 | Tanggal pembelian (YYYY-MM-DD) |
| `purchase_price` | Tidak | Angka | 500000 | Harga beli |
| `location` | Tidak | Teks | Gudang A - Rak 1 | Lokasi penyimpanan |
| `weight` | Tidak | Angka | 2.5 | Berat dalam kg |
| `dimensions` | Tidak | Teks | 40x30x20 cm | Dimensi |
| `notes` | Tidak | Teks | Kondisi baik | Catatan tambahan |

### Template CSV

```csv
code,name,category,brand,model,quantity_total,quantity_available,unit,condition,status,purchase_date,purchase_price,location,weight,dimensions,notes
EQ-001,Tenda Dome 4 Orang,Tenda,Consina,Magnum 4,10,8,unit,good,available,2024-01-15,500000,Gudang A - Rak 1,2.5,40x30x20 cm,Kondisi baik
EQ-002,Carrier 60L,Carrier,Eiger,Excelsior 60L,15,12,unit,excellent,available,2024-02-01,750000,Gudang A - Rak 2,1.8,65x35x25 cm,Baru dibeli
```

### Catatan Penting
- Code harus unik
- Category harus sudah dibuat terlebih dahulu di Equipment Categories
- Jika `quantity_available` tidak diisi, akan sama dengan `quantity_total`
- Nilai default `condition`: good, `status`: available, `unit`: unit

---

## 3. Import Ekspedisi

### Lokasi Menu
Admin Panel → Aktivitas → Ekspedisi → Import Ekspedisi

### Kolom yang Tersedia

| Kolom | Wajib | Tipe | Contoh | Keterangan |
|-------|-------|------|--------|------------|
| `expedition_code` | Tidak | Teks | EXP-2024-001 | Kode unik (auto-generate jika kosong) |
| `title` | Ya | Teks | Pendakian Gunung Kerinci | Judul ekspedisi |
| `destination` | Ya | Teks | Gunung Kerinci, Jambi | Tujuan ekspedisi |
| `type` | Tidak | Teks | climbing | climbing, hiking, camping, expedition, exploration |
| `difficulty` | Tidak | Teks | hard | easy, moderate, hard, extreme |
| `start_date` | Ya | Tanggal | 2024-01-15 | Tanggal mulai (YYYY-MM-DD) |
| `end_date` | Ya | Tanggal | 2024-01-20 | Tanggal selesai (YYYY-MM-DD) |
| `duration_days` | Tidak | Angka | 5 | Durasi dalam hari |
| `max_participants` | Tidak | Angka | 20 | Jumlah peserta maksimal |
| `coordinator_email` | Tidak | Email | coord@example.com | Email koordinator (harus terdaftar) |
| `status` | Tidak | Teks | completed | planning, open_registration, preparation, ongoing, completed, cancelled |
| `estimated_budget` | Tidak | Angka | 5000000 | Estimasi budget |
| `actual_budget` | Tidak | Angka | 4800000 | Budget aktual |
| `base_camp_location` | Tidak | Teks | Kersik Tuo | Lokasi base camp |
| `altitude` | Tidak | Angka | 3805 | Ketinggian dalam mdpl |
| `description` | Tidak | Teks | Ekspedisi pendakian... | Deskripsi |
| `objectives` | Tidak | Teks | Mencapai puncak... | Tujuan ekspedisi |
| `route_description` | Tidak | Teks | Jalur pendakian... | Deskripsi rute |
| `report_summary` | Tidak | Teks | Berhasil mencapai... | Ringkasan laporan |

### Template CSV

```csv
expedition_code,title,destination,type,difficulty,start_date,end_date,duration_days,max_participants,coordinator_email,status,estimated_budget,actual_budget,base_camp_location,altitude,description,objectives,route_description,report_summary
EXP-2024-001,Pendakian Gunung Kerinci,"Gunung Kerinci, Jambi",climbing,hard,2024-01-15,2024-01-20,5,20,coord@example.com,completed,5000000,4800000,Kersik Tuo,3805,Ekspedisi pendakian ke puncak tertinggi di Sumatera,Mencapai puncak dan dokumentasi flora fauna,Jalur via Kersik Tuo,Berhasil mencapai puncak dengan 18 peserta
```

### Catatan Penting
- `expedition_code` akan di-generate otomatis jika tidak diisi
- `coordinator_email` harus email user yang sudah terdaftar
- Untuk historical data, gunakan status `completed`

---

## 4. Import Program Pelatihan

### Lokasi Menu
Admin Panel → Aktivitas → Program Pelatihan → Import Program Pelatihan

### Kolom yang Tersedia

| Kolom | Wajib | Tipe | Contoh | Keterangan |
|-------|-------|------|--------|------------|
| `training_code` | Tidak | Teks | TRN-2024-001 | Kode unik (auto-generate jika kosong) |
| `title` | Ya | Teks | BKP Tingkat I | Judul training |
| `type` | Tidak | Teks | bkp | bkp, technical, leadership, survival, navigation, first_aid, other |
| `level` | Tidak | Teks | beginner | beginner, intermediate, advanced |
| `start_date` | Ya | Tanggal | 2024-02-01 | Tanggal mulai (YYYY-MM-DD) |
| `end_date` | Ya | Tanggal | 2024-02-05 | Tanggal selesai (YYYY-MM-DD) |
| `duration_hours` | Tidak | Angka | 40 | Durasi dalam jam |
| `location` | Tidak | Teks | Basecamp Mapala | Lokasi pelaksanaan |
| `max_participants` | Tidak | Angka | 30 | Jumlah peserta maksimal |
| `coordinator_email` | Tidak | Email | coord@example.com | Email koordinator (harus terdaftar) |
| `status` | Tidak | Teks | completed | planning, open_registration, preparation, ongoing, completed, cancelled |
| `registration_fee` | Tidak | Angka | 500000 | Biaya pendaftaran |
| `description` | Tidak | Teks | Pelatihan dasar... | Deskripsi |
| `objectives` | Tidak | Teks | Memberikan pengetahuan... | Tujuan training |
| `requirements` | Tidak | Teks | Anggota aktif... | Persyaratan peserta |
| `materials_covered` | Tidak | Teks | Teknik mendaki... | Materi yang diajarkan |
| `certificate_issued` | Tidak | Boolean | 1 | Apakah ada sertifikat (1=ya, 0=tidak) |
| `certificate_number_prefix` | Tidak | Teks | BKP-I-2024 | Prefix nomor sertifikat |

### Template CSV

```csv
training_code,title,type,level,start_date,end_date,duration_hours,location,max_participants,coordinator_email,status,registration_fee,description,objectives,requirements,materials_covered,certificate_issued,certificate_number_prefix
TRN-2024-001,BKP Tingkat I,bkp,beginner,2024-02-01,2024-02-05,40,Basecamp Mapala + Gunung Singgalang,30,coord@example.com,completed,500000,Pelatihan dasar pendakian gunung untuk anggota baru,Memberikan pengetahuan dasar pendakian yang aman,Anggota aktif Mapala sehat jasmani dan rohani,"Teknik mendaki, navigasi, survival, SAR",1,BKP-I-2024
```

### Catatan Penting
- `training_code` akan di-generate otomatis jika tidak diisi
- Type BKP adalah pelatihan wajib untuk anggota baru

---

## 5. Import Kompetisi

### Lokasi Menu
Admin Panel → Aktivitas → Kompetisi/Event → Import Kompetisi

### Kolom yang Tersedia

| Kolom | Wajib | Tipe | Contoh | Keterangan |
|-------|-------|------|--------|------------|
| `competition_code` | Tidak | Teks | COMP-2024-001 | Kode unik (auto-generate jika kosong) |
| `title` | Ya | Teks | Lomba Panjat Tebing | Judul kompetisi |
| `type` | Tidak | Teks | climbing | climbing, hiking, navigation, survival, photography, other |
| `level` | Tidak | Teks | national | local, regional, national, international |
| `start_date` | Ya | Tanggal | 2024-03-10 | Tanggal mulai (YYYY-MM-DD) |
| `end_date` | Ya | Tanggal | 2024-03-12 | Tanggal selesai (YYYY-MM-DD) |
| `location` | Ya | Teks | Jakarta Convention Center | Lokasi kompetisi |
| `organizer` | Tidak | Teks | FPTI Jakarta | Penyelenggara |
| `coordinator_email` | Tidak | Email | coord@example.com | Email koordinator tim (harus terdaftar) |
| `status` | Tidak | Teks | completed | upcoming, ongoing, completed, cancelled |
| `registration_deadline` | Tidak | Tanggal | 2024-03-01 | Batas pendaftaran (YYYY-MM-DD) |
| `max_participants` | Tidak | Angka | 50 | Jumlah peserta maksimal |
| `registration_fee` | Tidak | Angka | 250000 | Biaya pendaftaran |
| `description` | Tidak | Teks | Kompetisi panjat... | Deskripsi |
| `rules` | Tidak | Teks | Mengikuti standar IFSC | Peraturan |
| `prizes` | Tidak | Teks | Juara 1: Rp 10jt | Info hadiah |
| `website_url` | Tidak | URL | https://lomba.example.com | URL website kompetisi |

### Template CSV

```csv
competition_code,title,type,level,start_date,end_date,location,organizer,coordinator_email,status,registration_deadline,max_participants,registration_fee,description,rules,prizes,website_url
COMP-2024-001,Lomba Panjat Tebing Tingkat Nasional,climbing,national,2024-03-10,2024-03-12,Jakarta Convention Center,FPTI Jakarta,coord@example.com,completed,2024-03-01,50,250000,Kompetisi panjat tebing bergengsi tingkat nasional,Mengikuti standar IFSC,"Juara 1: Rp 10.000.000, Juara 2: Rp 7.000.000",https://lomba.example.com
```

### Catatan Penting
- `competition_code` akan di-generate otomatis jika tidak diisi
- `coordinator_email` adalah email koordinator TIM (bukan penyelenggara)

---

## Tips dan Best Practices

### 1. Format File
- Gunakan format CSV atau Excel (.xlsx)
- Encoding UTF-8 untuk karakter Indonesia
- Pastikan header kolom sesuai dengan nama yang ditentukan

### 2. Format Tanggal
- Selalu gunakan format: `YYYY-MM-DD`
- Contoh: `2024-12-25` untuk 25 Desember 2024

### 3. Boolean Values
- Gunakan `1` untuk True/Ya
- Gunakan `0` untuk False/Tidak
- Contoh: `is_active=1`, `certificate_issued=0`

### 4. Relasi Data
- Pastikan data master (Cohort, Division, Category) sudah dibuat sebelum import
- Gunakan nama persis seperti yang ada di database
- Email coordinator harus sudah terdaftar sebagai user

### 5. Validasi
- Periksa data duplikat (email, code) sebelum import
- Pastikan semua kolom wajib terisi
- Validasi format email dan tanggal

### 6. Import Bertahap
Untuk data besar, lakukan import secara bertahap:
1. Master data (Cohort, Division, Categories) terlebih dahulu
2. User/Anggota
3. Equipment
4. Historical activities (Expeditions, Training, Competitions)

### 7. Backup
- Selalu backup database sebelum melakukan import besar
- Test dengan sample data kecil terlebih dahulu

### 8. Handling Errors
- Sistem akan menampilkan baris yang gagal diimport
- Download error report untuk melihat detail kesalahan
- Perbaiki data dan import ulang hanya baris yang gagal

### 9. Performance
- Import maksimal 1000 baris per batch untuk performa optimal
- Untuk data lebih besar, split menjadi beberapa file

### 10. Verifikasi
Setelah import, verifikasi data:
- Cek jumlah data yang berhasil diimport
- Periksa sample data di list view
- Pastikan relasi data terhubung dengan benar

---

## Contoh Workflow Import Lengkap

### 1. Persiapan Master Data
```bash
1. Buat Cohort (Kader XXIII, Kader XXIV, dll)
2. Buat Division (Divisi Pendakian, Divisi Panjat Tebing, dll)
3. Buat Equipment Categories (Tenda, Carrier, dll)
```

### 2. Import Anggota
```bash
1. Siapkan file CSV dengan data anggota
2. Pastikan cohort dan division sudah ada
3. Import via menu Anggota → Import
4. Verifikasi hasil import
```

### 3. Import Peralatan
```bash
1. Siapkan file CSV dengan data equipment
2. Pastikan category sudah ada
3. Import via menu Peralatan → Import
4. Verifikasi hasil import
```

### 4. Import Historical Activities
```bash
1. Import Ekspedisi historical
2. Import Program Pelatihan historical
3. Import Kompetisi historical
4. Verifikasi semua data
```

---

## Troubleshooting

### Error: "Email already exists"
- Email sudah terdaftar di sistem
- Gunakan email yang berbeda atau update data existing

### Error: "Cohort not found"
- Cohort belum dibuat
- Buat cohort terlebih dahulu atau kosongkan kolom cohort

### Error: "Category not found"
- Category belum dibuat
- Buat category di Equipment Categories terlebih dahulu

### Error: "Invalid date format"
- Format tanggal salah
- Gunakan format YYYY-MM-DD

### Error: "Coordinator email not found"
- User dengan email tersebut belum terdaftar
- Daftarkan user terlebih dahulu atau kosongkan kolom coordinator

---

## Support

Jika mengalami kendala saat import, hubungi administrator sistem atau buat issue di repository GitHub.

**Selamat menggunakan fitur import Mapala Pagaruyung!**

# Panduan Dashboard & Analytics - Mapala Pagaruyung

Panduan lengkap untuk memahami dan menggunakan Dashboard Admin dengan berbagai widget analytics dan statistik.

## Daftar Isi
1. [Overview Dashboard](#overview-dashboard)
2. [Stats Overview Widget](#stats-overview-widget)
3. [Chart Widgets](#chart-widgets)
4. [Table Widgets](#table-widgets)
5. [Cara Menggunakan Dashboard](#cara-menggunakan-dashboard)
6. [Interpretasi Data](#interpretasi-data)
7. [Best Practices](#best-practices)

---

## Overview Dashboard

Dashboard Admin Mapala Pagaruyung dirancang untuk memberikan insights cepat tentang kondisi organisasi, aktivitas, dan inventaris peralatan. Dashboard ini menampilkan berbagai widget yang dapat membantu dalam pengambilan keputusan dan monitoring operasional.

### Fitur Dashboard

✅ **Real-time Statistics** - Data terkini dari database
✅ **Visual Charts** - Grafik interaktif untuk analisis tren
✅ **Quick Actions** - Akses cepat ke data yang perlu tindakan
✅ **Responsive Design** - Tampilan optimal di semua ukuran layar
✅ **Auto-refresh** - Data selalu up-to-date
✅ **Customizable** - Widget dapat dikonfigurasi sesuai kebutuhan

---

## Stats Overview Widget

Widget utama yang menampilkan 6 kartu statistik penting di bagian atas dashboard.

### 1. Anggota Aktif

**Metric Utama:** Jumlah Member + Senior Member yang aktif
**Deskripsi:** Total Alumni sebagai context
**Chart:** Tren pertumbuhan anggota aktif (7 data points)

**Interpretasi:**
- 🟢 **Hijau**: Sistem sehat dengan jumlah anggota yang stabil
- **Trend Up**: Menunjukkan pertumbuhan positif keanggotaan
- **Trend Down**: Perlu strategi rekrutmen atau retensi

**Action Items:**
- Jika trend turun: Review program rekrutmen
- Monitoring rasio Member:Alumni untuk regenerasi

### 2. Utilitas Peralatan

**Metric Utama:** Persentase peralatan yang sedang dipinjam
**Rumus:** `((Total - Tersedia) / Total) * 100%`
**Deskripsi:** Jumlah unit tersedia vs total

**Interpretasi:**
- 🔴 **>70% (Merah)**: Utilitas sangat tinggi - bagus untuk ROI, tapi perlu monitoring availability
- 🟡 **40-70% (Kuning)**: Utilitas sedang - kondisi ideal
- 🔴 **<40% (Merah)**: Utilitas rendah - peralatan kurang terpakai atau terlalu banyak stock

**Action Items:**
- Jika >80%: Pertimbangkan pembelian peralatan tambahan
- Jika <30%: Evaluasi kebutuhan pemeliharaan atau dispose equipment
- Monitoring peak seasons (musim liburan, semester break)

### 3. Ekspedisi Tahun Ini

**Metric Utama:** Jumlah ekspedisi selesai tahun berjalan
**Deskripsi:** Jumlah ekspedisi ongoing
**Chart:** Tren ekspedisi per bulan

**Interpretasi:**
- Target normal: 10-15 ekspedisi/tahun (rata-rata 1-2/bulan)
- Peak season: Libur semester dan liburan panjang
- Low season: UTS, UAS, awal semester

**Action Items:**
- Bandingkan dengan target tahunan
- Monitor keseimbangan tipe ekspedisi (climbing, hiking, dll)
- Planning ekspedisi untuk filling slow months

### 4. Program Pelatihan

**Metric Utama:** Jumlah program pelatihan selesai tahun ini
**Deskripsi:** Program sedang berjalan
**Chart:** Tren training completions

**Interpretasi:**
- BKP wajib minimal 1-2x per tahun
- Training teknis 3-4x per tahun optimal
- Leadership training 2x per tahun

**Action Items:**
- Pastikan BKP terjadwal untuk kader baru
- Balance antara technical & soft skills training
- Monitor completion rate dan feedback peserta

### 5. Peminjaman Pending

**Metric Utama:** Jumlah permintaan peminjaman pending approval
**Deskripsi:** Jumlah peminjaman terlambat (URGENT!)
**Alert:** 🔴 Jika ada yang terlambat

**Interpretasi:**
- **Pending = 0**: ✅ Semua request sudah diproses
- **Pending >5**: ⚠️ Bottleneck pada approval process
- **Terlambat >0**: 🔴 URGENT - perlu tindakan segera!

**Action Items:**
- Pending: Segera review dan approve/reject
- Terlambat: Contact peminjam, hitung denda, collect penalty
- System: Set auto-reminder untuk due date

### 6. Kompetisi Mendatang

**Metric Utama:** Kompetisi dalam 30 hari ke depan
**Deskripsi:** Window: 30 hari
**Chart:** Trend participation

**Interpretasi:**
- Optimal: 1-2 kompetisi/bulan
- Monitor balance regional vs national/international
- Perhatikan clash dengan kegiatan internal

**Action Items:**
- Prepare team untuk upcoming competitions
- Budget planning untuk pendaftaran & transport
- Coordination dengan trainer untuk preparation

---

## Chart Widgets

### 1. Tren Ekspedisi per Bulan (Line Chart)

**Lokasi:** Row 2, Full Width

**Fitur:**
- Filter: Tahun Ini / 6 Bulan Terakhir / Semua Waktu
- Data: Hanya completed expeditions
- Y-Axis: Jumlah ekspedisi
- X-Axis: Bulan

**Insights yang Bisa Didapat:**
- Identifikasi peak season & low season
- Pattern musiman (libur semester, liburan)
- Trend pertumbuhan aktivitas year-over-year
- Forecast kebutuhan peralatan berdasarkan pola historis

**Use Case:**
1. **Planning**: Tentukan jadwal ekspedisi untuk merata-kan beban
2. **Resource**: Prediksi kebutuhan equipment di peak months
3. **Marketing**: Timing optimal untuk rekrutmen (pra-peak season)

### 2. Pertumbuhan Anggota (Line Chart with 2 Lines)

**Lokasi:** Row 3, Full Width

**Data Series:**
- **Line 1 (Hijau)**: Total Anggota kumulatif
- **Line 2 (Orange, Dashed)**: Anggota Baru per bulan

**Period:** 12 bulan terakhir

**Insights yang Bisa Didapat:**
- Growth rate keanggotaan
- Efektivitas rekrutmen per periode
- Churn rate (implisit dari perbedaan total vs new)
- Impact program rekrutmen tertentu

**Interpretasi:**
- **Total Line Going Up**: ✅ Pertumbuhan positif
- **New Member Spike**: Hasil rekrutmen sukses (biasanya awal semester)
- **Flat Growth**: Warning - perlu strategi rekrutmen baru
- **Decline**: 🔴 Critical - ada issue retention atau rekrutmen

**Action Items:**
- Spike setelah rekrutmen: Pastikan onboarding & engagement program
- Flat period: Launch targeted recruitment campaign
- Monitor cohort-to-cohort comparison

### 3. Distribusi Peralatan per Kategori (Doughnut Chart)

**Lokasi:** Row 5, Full Width

**Data:** Total quantity peralatan per kategori
**Colors:** 8 warna berbeda untuk visibility

**Insights yang Bisa Didapat:**
- Komposisi inventory peralatan
- Kategori dengan stock paling banyak/sedikit
- Budget allocation historical
- Planning pembelian equipment baru

**Interpretasi:**
- **Balanced Distribution**: Ideal untuk versatility
- **Heavy on Tenda/Carrier**: Cocok untuk expedition-focused org
- **Thin on Specialized Gear**: Mungkin perlu investasi (climbing, navigation)

**Action Items:**
- Identifikasi kategori yang under-equipped
- Budget prioritization untuk pembelian
- Consider selling/retiring over-stocked categories

### 4. Distribusi Anggota (Bar Chart with Filter)

**Lokasi:** Row 7, Half Width

**Filters:**
- **Per Angkatan**: Distribusi berdasarkan cohort
- **Per Divisi**: Distribusi berdasarkan division

**Insights yang Bisa Didapat:**
- **Per Angkatan**: Regenerasi dan age composition
- **Per Divisi**: Specialization distribution dan capacity per divisi

**Interpretasi Per Angkatan:**
- Pyramid shape (many junior, few senior): ✅ Healthy regeneration
- Inverse pyramid: 🔴 Warning - recruitment issue
- Even distribution: Stable tapi perlu monitor recruitment

**Interpretasi Per Divisi:**
- **Balanced**: Versatile organization
- **Heavy on one division**: Specialized focus (good/bad depending on strategy)
- **Empty divisions**: Consider consolidation atau targeted recruitment

**Action Items:**
- Angkatan: Planning mentorship programs (senior → junior)
- Divisi: Recruitment fokus untuk fill gaps
- Cross-training untuk balance skills

---

## Table Widgets

### 1. Ekspedisi Terbaru

**Lokasi:** Row 4, Full Width
**Data:** 10 ekspedisi terbaru (ongoing, preparation, completed)
**Sort:** Latest start_date first

**Columns:**
- Kode Ekspedisi
- Judul
- Tujuan
- Status (color-coded badge)
- Tanggal Mulai
- Jumlah Peserta
- Koordinator

**Features:**
- ✅ Search by title, destination
- ✅ Sort by date
- ✅ Quick view action (eye icon)
- ✅ Color-coded status badges

**Use Case:**
1. **Quick Reference**: Cek status ekspedisi yang sedang/baru berjalan
2. **Coordination**: Contact koordinator untuk follow-up
3. **Monitoring**: Track participants dan progress

**Status Colors:**
- 🟢 **Completed**: Success
- 🟡 **Ongoing**: Warning (perlu monitoring)
- 🔵 **Preparation**: Info
- 🔴 **Cancelled**: Danger

### 2. Peminjaman yang Perlu Diproses

**Lokasi:** Row 5, Full Width
**Data:** Pending borrowings + Overdue borrowings
**Sort:** Latest first

**Columns:**
- Kode Peminjaman
- Peminjam (name)
- Peralatan
- Jumlah
- Status (smart badge - shows days late if overdue!)
- Jatuh Tempo (red if overdue)
- Denda (only shown if >0)

**Features:**
- ✅ Search by borrower, equipment
- ✅ Smart status showing "Terlambat X hari"
- ✅ Automatic denda calculation display
- ✅ Quick action to view/process
- ✅ Empty state with positive message

**Priority Actions:**
1. **🔴 Overdue**: URGENT - Contact borrower, process return, collect penalty
2. **🟡 Pending**: Review request, check equipment availability, approve/reject
3. **🔵 Due Soon**: Reminder to borrower

**Empty State:**
Jika tidak ada yang perlu diproses, menampilkan:
> "Tidak ada peminjaman yang perlu diproses
> Semua peminjaman sudah diproses dengan baik! ✅"

---

## Cara Menggunakan Dashboard

### 1. Akses Dashboard

**URL:** `https://yourdomain.com/admin`
**Redirect:** Setelah login, otomatis ke dashboard

**Permissions Required:**
- `access-admin-panel` permission

### 2. Reading the Dashboard

**Top to Bottom Flow:**
1. **Stats Cards** (Row 1): Quick overview, identify urgent issues
2. **Charts** (Row 2-3): Understand trends dan patterns
3. **Tables** (Row 4-5): Actionable items yang perlu follow-up

**Color Coding:**
- 🔴 **Red/Danger**: Urgent action needed
- 🟡 **Yellow/Warning**: Monitor closely
- 🔵 **Blue/Info**: Informational
- 🟢 **Green/Success**: All good
- ⚪ **Gray**: Neutral/Inactive

### 3. Interacting with Widgets

**Charts:**
- Hover over data points untuk detail
- Click legend untuk hide/show data series
- Use filters untuk adjust time range

**Tables:**
- Click column headers untuk sort
- Use search box untuk find specific records
- Click eye icon untuk quick view detail
- Pagination controls di bottom

**Cards:**
- Click card (jika clickable) untuk drill-down
- Hover untuk tooltip dengan additional info
- Mini charts show 7-point trend

### 4. Refresh Data

Data di dashboard adalah real-time dari database.

**Manual Refresh:**
- Refresh browser (F5 atau Cmd+R)
- Filament auto-refresh di beberapa widgets

**Automatic Refresh:**
- Stats widgets: On page load
- Charts: On filter change
- Tables: On search/sort/pagination

### 5. Export Data

Untuk analisis lebih lanjut:
1. Navigate ke resource page (e.g., Anggota, Ekspedisi)
2. Use Export feature (blue button)
3. Open in Excel/Google Sheets for deeper analysis

---

## Interpretasi Data

### Monthly Review Checklist

**Setiap Bulan, Review:**

1. ✅ **Member Statistics**
   - Total anggota vs target
   - New members this month
   - Alumni yang perlu re-engagement

2. ✅ **Activity Metrics**
   - Ekspedisi completed vs planned
   - Training programs delivered
   - Upcoming events preparation

3. ✅ **Equipment Health**
   - Utilization rate trend
   - Overdue borrowings (should be 0!)
   - Equipment needing maintenance

4. ✅ **Financial Indicators**
   - Budget spent vs allocated (check expedition actual vs estimated)
   - Penalties collected
   - Registration fees collected

### Quarterly Review Checklist

**Setiap 3 Bulan:**

1. ✅ **Trend Analysis**
   - Member growth rate
   - Activity frequency patterns
   - Equipment utilization changes

2. ✅ **Strategic Alignment**
   - Are we hitting annual targets?
   - Division balance check
   - Program mix evaluation (expedition:training:competition ratio)

3. ✅ **Resource Planning**
   - Equipment purchase needs
   - Budget reallocation
   - Human resource gaps

### Annual Review Checklist

**Setiap Tahun (End of Year):**

1. ✅ **Full Year Performance**
   - Total expeditions vs target
   - Training programs delivered
   - Competition participations & achievements
   - Member growth absolute & percentage

2. ✅ **Strategic Planning for Next Year**
   - Set new targets based on trends
   - Budget allocation for equipment
   - Program calendar planning
   - Recruitment strategy

---

## Best Practices

### For Admin Users

1. **Daily Check (5 menit)**
   - Check Peminjaman Pending widget
   - Quick scan Stats Overview untuk anomalies
   - Address urgent items (overdue, pending approvals)

2. **Weekly Review (15 menit)**
   - Review recent expeditions table
   - Check equipment utilization trend
   - Monitor upcoming events preparation

3. **Monthly Deep Dive (30 menit)**
   - Analyze all charts untuk trends
   - Export data untuk detailed reports
   - Planning session dengan team leads

4. **Dashboard Hygiene**
   - Resolve pending items promptly
   - Keep data accurate (input aktivitas tepat waktu)
   - Regular cleanup (cancel stale requests, update statuses)

### For Organization Leaders

1. **Strategic Monitoring**
   - Focus on trend charts (not just current numbers)
   - Compare year-over-year performance
   - Identify leading indicators (e.g., member growth → future activity level)

2. **Decision Making**
   - Use data untuk justify budget requests
   - Identify resource constraints early
   - Track impact of programs/initiatives

3. **Communication**
   - Share dashboard insights in meetings
   - Create monthly dashboard screenshots untuk records
   - Use data dalam laporan ke stakeholders

### Dashboard Optimization Tips

1. **Performance**
   - Dashboard designed untuk fast loading
   - Charts use aggregated data, not raw records
   - Pagination pada tables untuk limit queries

2. **Mobile Access**
   - Dashboard responsive untuk tablet/mobile
   - Priority info shown first on small screens
   - Touch-friendly interactions

3. **Customization**
   - Widgets can be reordered (modify Dashboard.php)
   - Add/remove widgets based on needs
   - Adjust chart filters untuk your use case

---

## Troubleshooting

### Widget Not Showing

**Problem:** Widget tidak muncul di dashboard

**Solutions:**
1. Check file ada di `app/Filament/Admin/Widgets/`
2. Namespace benar: `App\Filament\Admin\Widgets`
3. Extends class yang benar (StatsOverviewWidget, ChartWidget, TableWidget)
4. Registered di Dashboard::getWidgets()

### Data Tidak Update

**Problem:** Data di widget masih lama

**Solutions:**
1. Clear cache: `php artisan cache:clear`
2. Refresh browser (Ctrl+F5 / Cmd+Shift+R)
3. Check database connection
4. Verify data actually exists in database

### Chart Not Rendering

**Problem:** Chart area kosong atau error

**Solutions:**
1. Check browser console untuk JS errors
2. Ensure Chart.js loaded properly
3. Verify getData() returns correct format
4. Check data tidak null/empty

### Performance Issues

**Problem:** Dashboard lambat loading

**Solutions:**
1. Add database indexes on frequently queried columns
2. Use eager loading (`with()`) pada relationships
3. Consider caching chart data for X minutes
4. Limit table rows (use pagination)

---

## Advanced Features

### Custom Widgets

Anda bisa membuat widget custom sendiri:

```php
php artisan make:filament-widget CustomWidget --resource=ResourceName
```

**Widget Types:**
- StatsOverviewWidget: Untuk statistics cards
- ChartWidget: Untuk charts (line, bar, pie, doughnut, etc.)
- TableWidget: Untuk data tables
- Widget: Generic widget dengan custom view

### Widget Visibility Control

Control widget visibility based on permissions:

```php
public static function canView(): bool
{
    return auth()->user()->can('view-analytics');
}
```

### Widget Polling

Auto-refresh widget setiap X detik:

```php
protected static ?string $pollingInterval = '30s';
```

---

## Support

Jika mengalami masalah atau perlu bantuan:
1. Check dokumentasi Filament: https://filamentphp.com/docs
2. Review kode widget di `app/Filament/Admin/Widgets/`
3. Hubungi administrator sistem
4. Create issue di repository GitHub

**Happy Monitoring! 📊📈**

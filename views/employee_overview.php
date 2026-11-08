<?php
/**
 * FILE: views/employee_overview.php
 * FUNGSI: Menampilkan halaman ringkasan karyawan (Tugas 1)
 */
include 'views/header.php';
?>
<h2>Halaman Ringkasan Karyawan</h2>
<p style="margin-bottom: 2rem; color: #666;">
    Data ringkasan ini dihitung secara langsung dari database menggunakan fungsi agregat PostgreSQL: <code>COUNT()</code>, <code>SUM()</code>, dan <code>AVG()</code>.
</p>

<?php if ($overview): ?>
<div class="dashboard-cards">
    <div class="card">
        <h3>Total Karyawan</h3>
        <div class="number"><?php echo $overview['total_employees']; ?> orang</div>
    </div>
    <div class="card">
        <h3>Total Gaji per Bulan</h3>
        <div class="number" style="font-size: 1.5rem;">Rp <?php echo number_format($overview['total_monthly_salary'], 0, ',', '.'); ?></div>
    </div>
    <div class="card">
        <h3>Rata-rata Masa Kerja</h3>
        <div class="number"><?php echo $overview['avg_years_of_service']; ?> tahun</div>
    </div>
</div>
<?php else: ?>
<div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 8px;">
    <p style="color: #666;">Tidak ada data ringkasan karyawan.</p>
</div>
<?php endif; ?>

<?php include 'views/footer.php'; ?>
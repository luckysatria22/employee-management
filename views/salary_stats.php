<?php
/**
 * FILE: views/salary_stats.php
 * FUNGSI: Menampilkan halaman statistik gaji per departemen (Tugas 1)
 */
include 'views/header.php';
?>
<h2>Halaman Statistik Gaji</h2>
<p style="margin-bottom: 2rem; color: #666;">
    Data statistik gaji ini dihitung menggunakan fungsi agregat PostgreSQL: <code>AVG()</code>, <code>MAX()</code>, <code>MIN()</code>, dan <code>GROUP BY</code>.
</p>

<?php if ($salary_stats->rowCount() > 0): ?>
<table class="data-table">
    <thead>
        <tr>
            <th>Departemen</th>
            <th>Jumlah Karyawan</th>
            <th>Gaji Rata-rata</th>
            <th>Gaji Tertinggi</th>
            <th>Gaji Terendah</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $salary_stats->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><strong><?php echo htmlspecialchars($row['department']); ?></strong></td>
            <td style="text-align: center;"><?php echo $row['total_employees']; ?></td>
            <td><strong>Rp <?php echo number_format($row['avg_salary'], 0, ',', '.'); ?></strong></td>
            <td style="color: #27ae60;">Rp <?php echo number_format($row['max_salary'], 0, ',', '.'); ?></td>
            <td style="color: #c0392b;">Rp <?php echo number_format($row['min_salary'], 0, ',', '.'); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
<div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 8px;">
    <p style="color: #666;">Tidak ada data statistik gaji.</p>
</div>
<?php endif; ?>

<?php include 'views/footer.php'; ?>
<?php
/**
 * FILE: views/department_summary.php
 * FUNGSI: Menampilkan hasil dari PostgreSQL Table Function get_department_summary() (Tugas 2)
 */
include 'views/header.php';

$summary_data = $dept_summary ? $dept_summary->fetchAll(PDO::FETCH_ASSOC) : [];
$total_dept = count($summary_data);
$total_karyawan = array_sum(array_column($summary_data, 'employee_count'));
$total_budget = array_sum(array_column($summary_data, 'total_budget'));
$avg_salary_total = $total_karyawan > 0 ? ($total_budget / $total_karyawan) : 0;
?>
<h2>Ringkasan Statistik Departemen (Table Function)</h2>
<p style="margin-bottom: 2rem; color: #666;">
    Halaman ini memanggil PostgreSQL <code>Table Function</code> bernama <code>get_department_summary()</code> untuk Tugas 2.
</p>

<!-- Cards Summary -->
<div class="dashboard-cards">
    <div class="card">
        <h3>Total Departemen</h3>
        <div class="number"><?php echo $total_dept; ?></div>
    </div>
    <div class="card">
        <h3>Total Karyawan</h3>
        <div class="number"><?php echo $total_karyawan; ?></div>
    </div>
    <div class="card">
        <h3>Total Budget Gaji</h3>
        <div class="number" style="font-size: 1.5rem;">
            Rp <?php echo number_format($total_budget, 0, ',', '.'); ?>
        </div>
    </div>
    <div class="card">
        <h3>Rata-rata Gaji Perusahaan</h3>
        <div class="number" style="font-size: 1.5rem;">
            Rp <?php echo number_format($avg_salary_total, 0, ',', '.'); ?>
        </div>
    </div>
</div>

<!-- Tabel Statistik Departemen -->
<table class="data-table">
    <thead>
        <tr>
            <th>Departemen</th>
            <th style="text-align: center;">Jumlah Karyawan (employee_count)</th>
            <th>Gaji Rata-rata (avg_salary)</th>
            <th>Total Budget (total_budget)</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($total_dept > 0): ?>
            <?php foreach ($summary_data as $row): ?>
            <tr>
                <td>
                    <strong><?php echo htmlspecialchars($row['department']); ?></strong>
                </td>
                <td style="text-align: center;">
                    <span style="padding: 0.25rem 0.75rem; background: #667eea; color: white; border-radius: 20px; font-weight: bold;">
                        <?php echo $row['employee_count']; ?> orang
                    </span>
                </td>
                <td>
                    <strong>Rp <?php echo number_format($row['avg_salary'], 0, ',', '.'); ?></strong>
                </td>
                <td>
                    <strong style="color: #27ae60;">
                        Rp <?php echo number_format($row['total_budget'], 0, ',', '.'); ?>
                    </strong>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align: center; padding: 2rem; color: #666;">
                    Tidak ada data ringkasan departemen.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div style="margin-top: 2rem; padding: 1.25rem; background: #e7f3ff; border-radius: 8px; border-left: 4px solid #0056b3;">
    <h4 style="margin-top: 0; margin-bottom: 0.5rem; color: #0056b3;">Informasi Tugas 2: PostgreSQL Table Function</h4>
    <p style="margin: 0; line-height: 1.6;">
        Fungsi ini dibuat di PostgreSQL dengan nama <code>get_department_summary()</code> yang mengembalikan kolom:
        <code>department</code>, <code>employee_count</code>, <code>avg_salary</code>, dan <code>total_budget</code>.
    </p>
</div>

<?php include 'views/footer.php'; ?>

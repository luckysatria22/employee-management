<?php
/**
 * FILE: views/tenure_stats.php
 * FUNGSI: Menampilkan halaman statistik masa kerja karyawan (Tugas 1)
 */
include 'views/header.php';

// Ambil semua data ringkasan ke array
$stats_rows = $tenure_stats->fetchAll(PDO::FETCH_ASSOC);
$total_all = array_sum(array_column($stats_rows, 'total_employees'));
?>
<h2>Halaman Statistik Masa Kerja</h2>
<p style="margin-bottom: 2rem; color: #666;">
    Data statistik masa kerja ini dikelompokkan menggunakan fungsi PostgreSQL: <code>COUNT()</code>, <code>CASE WHEN</code>, dan <code>GROUP BY</code>.
</p>

<!-- Cards Summary -->
<div class="dashboard-cards">
    <div class="card">
        <h3>Total Karyawan</h3>
        <div class="number"><?php echo $total_all; ?></div>
    </div>
    <?php foreach ($stats_rows as $stat): ?>
    <div class="card">
        <h3><?php echo htmlspecialchars($stat['experience_category']); ?></h3>
        <div class="number"><?php echo $stat['total_employees']; ?> <span style="font-size: 1rem; color: #666;">orang</span></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Tabel 1: Ringkasan Agregat Masa Kerja -->
<h3 style="margin-top: 2rem; margin-bottom: 0.5rem;">Ringkasan Kelompok Masa Kerja</h3>
<?php if (count($stats_rows) > 0): ?>
<table class="data-table">
    <thead>
        <tr>
            <th style="width: 25%;">Kategori Masa Kerja</th>
            <th style="width: 15%; text-align: center;">Jumlah Karyawan</th>
            <th style="width: 60%;">Daftar Karyawan di Kategori Ini</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stats_rows as $row): ?>
        <tr>
            <td>
                <strong><?php echo htmlspecialchars($row['experience_category']); ?></strong>
            </td>
            <td style="text-align: center;">
                <span style="padding: 0.25rem 0.75rem; background: #667eea; color: white; border-radius: 20px; font-weight: bold;">
                    <?php echo $row['total_employees']; ?> orang
                </span>
            </td>
            <td>
                <span style="color: #444; line-height: 1.8;">
                    <?php echo htmlspecialchars($row['employee_names'] ?? '-'); ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 8px;">
    <p style="color: #666;">Tidak ada data statistik masa kerja.</p>
</div>
<?php endif; ?>

<!-- Tabel 2: Rincian Setiap Karyawan & Masa Kerjanya -->
<h3 style="margin-top: 3rem; margin-bottom: 0.5rem;">Daftar Karyawan & Detail Masa Kerja</h3>
<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Lengkap</th>
            <th>Departemen</th>
            <th>Posisi</th>
            <th>Tanggal Masuk</th>
            <th>Masa Kerja</th>
            <th style="text-align: center;">Kategori</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($emp = $employees_tenure->fetch(PDO::FETCH_ASSOC)): ?>
            <?php
            // Format masa kerja (tahun dan bulan)
            $years = (int)$emp['tenure_years'];
            $months = (int)$emp['tenure_months'];
            $tenure_str = "";
            if ($years > 0 && $months > 0) {
                $tenure_str = "{$years} tahun {$months} bulan";
            } elseif ($years > 0) {
                $tenure_str = "{$years} tahun";
            } elseif ($months > 0) {
                $tenure_str = "{$months} bulan";
            } else {
                $tenure_str = "< 1 bulan";
            }

            // Warna badge berdasarkan kategori
            $badge_bg = '#6c757d'; // default
            if ($emp['experience_category'] === 'Junior') {
                $badge_bg = '#17a2b8'; // cyan / info
            } elseif ($emp['experience_category'] === 'Middle') {
                $badge_bg = '#28a745'; // green / success
            } elseif ($emp['experience_category'] === 'Senior') {
                $badge_bg = '#667eea'; // purple-blue / primary
            }
            ?>
        <tr>
            <td><?php echo htmlspecialchars($emp['id']); ?></td>
            <td><strong><?php echo htmlspecialchars($emp['full_name']); ?></strong></td>
            <td>
                <span style="padding: 0.2rem 0.5rem; background: #e9ecef; border-radius: 4px; font-size: 0.9rem;">
                    <?php echo htmlspecialchars($emp['department']); ?>
                </span>
            </td>
            <td><?php echo htmlspecialchars($emp['position']); ?></td>
            <td><?php echo date('d/m/Y', strtotime($emp['hire_date'])); ?></td>
            <td><strong><?php echo $tenure_str; ?></strong></td>
            <td style="text-align: center;">
                <span style="padding: 0.25rem 0.6rem; background: <?php echo $badge_bg; ?>; color: white; border-radius: 4px; font-size: 0.85rem; font-weight: bold;">
                    <?php echo htmlspecialchars($emp['experience_category']); ?>
                </span>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div style="margin-top: 2rem; padding: 1rem; background: #e7f3ff; border-radius: 5px;">
    <strong>Informasi Kategori Masa Kerja:</strong>
    <ul style="margin: 0.5rem 0 0 1.5rem;">
        <li><strong style="color: #17a2b8;">Junior:</strong> Masa kerja kurang dari 1 tahun (&lt; 1 tahun)</li>
        <li><strong style="color: #28a745;">Middle:</strong> Masa kerja antara 1 sampai 3 tahun (1 - 3 tahun)</li>
        <li><strong style="color: #667eea;">Senior:</strong> Masa kerja lebih dari 3 tahun (&gt; 3 tahun)</li>
    </ul>
</div>

<?php include 'views/footer.php'; ?>

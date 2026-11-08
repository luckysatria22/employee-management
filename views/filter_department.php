<?php
/**
 * FILE: views/filter_department.php
 * FUNGSI: Menampilkan hasil dari PostgreSQL Table Function get_employees_by_department
 */
include 'views/header.php';

$dept_name = $_GET['dept_name'] ?? 'ALL';
$employees_data = $result_employees ? $result_employees->fetchAll(PDO::FETCH_ASSOC) : [];
$total_count = count($employees_data);
?>
<h2>Filter Karyawan Berdasarkan Departemen</h2>
<p style="margin-bottom: 2rem; color: #666;">
    Halaman ini memanggil PostgreSQL <code>Table Function</code> bernama <code>get_employees_by_department(dept_name)</code>.
</p>

<!-- Form Filter -->
<form method="GET" action="index.php" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #e9ecef;">
    <input type="hidden" name="action" value="filter_department">
    <div style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div class="form-group" style="flex: 1; min-width: 250px; margin-bottom: 0;">
            <label for="dept_name" class="form-label">Nama Departemen</label>
            <select id="dept_name" name="dept_name" class="form-input">
                <option value="ALL" <?php echo ($dept_name === 'ALL' || empty($dept_name)) ? 'selected' : ''; ?>>-- Semua Departemen --</option>
                <option value="IT" <?php echo ($dept_name === 'IT') ? 'selected' : ''; ?>>IT</option>
                <option value="HR" <?php echo ($dept_name === 'HR') ? 'selected' : ''; ?>>HR</option>
                <option value="Finance" <?php echo ($dept_name === 'Finance') ? 'selected' : ''; ?>>Finance</option>
                <option value="Marketing" <?php echo ($dept_name === 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                <option value="Operations" <?php echo ($dept_name === 'Operations') ? 'selected' : ''; ?>>Operations</option>
                <option value="Sales" <?php echo ($dept_name === 'Sales') ? 'selected' : ''; ?>>Sales</option>
            </select>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary">Cari Karyawan</button>
            <a href="index.php?action=filter_department&dept_name=ALL" class="btn" style="background: #6c757d; color: white;">Reset / Tampilkan Semua</a>
        </div>
    </div>
</form>

<!-- Tabel Hasil -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h3 style="margin: 0;">
        <?php if ($dept_name === 'ALL' || empty($dept_name)): ?>
            Daftar Semua Karyawan (Semua Departemen)
        <?php else: ?>
            Hasil Pencarian untuk Departemen: <span style="color: #667eea;"><?php echo htmlspecialchars($dept_name); ?></span>
        <?php endif; ?>
    </h3>
    <span style="background: #e7f3ff; color: #0056b3; padding: 0.35rem 0.8rem; border-radius: 20px; font-weight: bold; font-size: 0.9rem;">
        Total: <?php echo $total_count; ?> Karyawan
    </span>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th style="width: 8%;">ID</th>
            <th style="width: 28%;">Nama Lengkap</th>
            <th style="width: 20%;">Departemen</th>
            <th style="width: 24%;">Posisi</th>
            <th style="width: 20%;">Gaji</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($total_count > 0): ?>
            <?php foreach ($employees_data as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></td>
                <td>
                    <span style="padding: 0.25rem 0.5rem; background: #e9ecef; border-radius: 4px; font-weight: 500;">
                        <?php echo htmlspecialchars($row['department']); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($row['position']); ?></td>
                <td><strong>Rp <?php echo number_format($row['salary'], 0, ',', '.'); ?></strong></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align: center; padding: 2.5rem; color: #666; background: #fdfdfd;">
                    <p style="font-size: 1.1rem; margin-bottom: 0.5rem;">Tidak ada karyawan ditemukan di departemen <strong><?php echo htmlspecialchars($dept_name); ?></strong>.</p>
                    <a href="index.php?action=filter_department&dept_name=ALL" class="btn btn-primary" style="font-size: 0.85rem;">Kembali ke Semua Karyawan</a>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div style="margin-top: 2rem; padding: 1rem; background: #e7f3ff; border-radius: 5px;">
    <strong>Informasi Table Function:</strong> 
    Data di atas diperoleh langsung dari pemanggilan PostgreSQL Table Function: 
    <code>get_employees_by_department('<?php echo htmlspecialchars($dept_name); ?>')</code>.
</div>

<?php include 'views/footer.php'; ?>

<?php $__env->startSection('title', 'Laporan Stok'); ?>

<?php $__env->startSection('content'); ?>
<?php use App\Helpers\InvenHelper; ?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-file-chart-column" style="color:#1e40af;margin-right:8px"></i>Laporan Stok Inventaris</h1>
        <div class="breadcrumb">Inventaris › Laporan Stok</div>
    </div>
    <button onclick="window.print()" class="btn btn-outline no-print">
        <i class="fas fa-print"></i> Cetak
    </button>
</div>


<div class="card no-print" style="margin-bottom:16px">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" style="display:flex;gap:15px;align-items:center;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:8px">
                <label style="font-size:13px;font-weight:600;margin:0">Tanggal Mulai:</label>
                <input type="date" name="tgl_mulai" value="<?php echo e($tgl_mulai); ?>" style="width:auto">
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                <label style="font-size:13px;font-weight:600;margin:0">Tanggal Selesai:</label>
                <input type="date" name="tgl_selesai" value="<?php echo e($tgl_selesai); ?>" style="width:auto">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Tampilkan</button>
        </form>
    </div>
</div>


<div class="stats-grid" style="margin-bottom:16px;grid-template-columns:repeat(auto-fit,minmax(170px,1fr))">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#166534"><i class="fas fa-arrow-down"></i></div>
        <div>
            <div class="label">Total Masuk</div>
            <div class="value" style="color:#166534"><?php echo e(number_format($grandMasuk)); ?></div>
            <div class="sub">unit diterima</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;color:#854d0e"><i class="fas fa-arrow-up"></i></div>
        <div>
            <div class="label">Total Terjual</div>
            <div class="value" style="color:#854d0e"><?php echo e(number_format($grandKeluar)); ?></div>
            <div class="sub">unit keluar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:#991b1b"><i class="fas fa-triangle-exclamation"></i></div>
        <div>
            <div class="label">Barang Rusak</div>
            <div class="value" style="color:#991b1b"><?php echo e(number_format($grandRusak)); ?></div>
            <div class="sub">unit rusak</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;color:#854d0e"><i class="fas fa-rotate-left"></i></div>
        <div>
            <div class="label">Total Return</div>
            <div class="value" style="color:#854d0e"><?php echo e(number_format($grandReturn)); ?></div>
            <div class="sub">unit dikembalikan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#166534"><i class="fas fa-sack-dollar"></i></div>
        <div>
            <div class="label">Omset Kotor</div>
            <div class="value" style="color:#166534;font-size:14px"><?php echo e(InvenHelper::rupiah($grandOmsetKotor)); ?></div>
            <div class="sub">sebelum return</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:#991b1b"><i class="fas fa-circle-minus"></i></div>
        <div>
            <div class="label">Nilai Return</div>
            <div class="value" style="color:#991b1b;font-size:14px"><?php echo e(InvenHelper::rupiah($grandNilaiReturn)); ?></div>
            <div class="sub">omset dikurangi</div>
        </div>
    </div>
    <div class="stat-card" style="border:2px solid #1e40af">
        <div class="stat-icon" style="background:#dbeafe;color:#1e40af"><i class="fas fa-sack-dollar"></i></div>
        <div>
            <div class="label" style="color:#1e40af;font-weight:800">Omset Bersih</div>
            <div class="value" style="color:#1e40af;font-size:14px"><?php echo e(InvenHelper::rupiah($grandOmset)); ?></div>
            <div class="sub">setelah dikurangi return</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#ede9fe;color:#6d28d9"><i class="fas fa-warehouse"></i></div>
        <div>
            <div class="label">Nilai Stok Tersisa</div>
            <div class="value" style="color:#6d28d9;font-size:14px"><?php echo e(InvenHelper::rupiah($grandNilaiStok)); ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-table"></i>
            Detail Per Produk —
            <?php echo e(\Carbon\Carbon::parse($tgl_mulai)->translatedFormat('d F Y')); ?> s/d <?php echo e(\Carbon\Carbon::parse($tgl_selesai)->translatedFormat('d F Y')); ?>

        </h3>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th style="text-align:center">Masuk</th>
                    <th style="text-align:center">Terjual</th>
                    <th style="text-align:center">Rusak</th>
                    <th style="text-align:center">Return</th>
                    <th style="text-align:center">Stok</th>
                    <th>Omset Kotor</th>
                    <th>Omset Bersih</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="color:#94a3b8"><?php echo e($i + 1); ?></td>
                    <td><span class="badge badge-primary"><?php echo e($r->kode_produk); ?></span></td>
                    <td style="font-weight:600"><?php echo e($r->nama_produk); ?></td>
                    <td><?php echo e($r->nama_kategori ?? '-'); ?></td>
                    <td style="text-align:center;color:#166534;font-weight:700">
                        <?php echo e($r->total_masuk > 0 ? '+' . $r->total_masuk : '-'); ?>

                    </td>
                    <td style="text-align:center;color:#dc2626;font-weight:700">
                        <?php echo e($r->total_keluar > 0 ? '-' . $r->total_keluar : '-'); ?>

                    </td>
                    <td style="text-align:center">
                        <?php if($r->total_barang_rusak > 0): ?>
                            <span style="color:#991b1b;font-weight:700">-<?php echo e($r->total_barang_rusak); ?></span>
                        <?php else: ?>
                            <span style="color:#94a3b8">-</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center">
                        <?php if($r->total_return > 0): ?>
                            <span style="color:#854d0e;font-weight:700">+<?php echo e($r->total_return); ?></span>
                            <div style="font-size:10px;color:#991b1b">-<?php echo e(InvenHelper::rupiah($r->nilai_return)); ?></div>
                        <?php else: ?>
                            <span style="color:#94a3b8">-</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center">
                        <span class="<?php echo e($r->stok_sekarang <= $r->stok_minimum ? 'stok-kritis' : 'stok-aman'); ?>">
                            <?php echo e($r->stok_sekarang); ?> <?php echo e($r->satuan); ?>

                        </span>
                    </td>
                    <td style="font-size:12px;color:#64748b">
                        <?php echo e($r->omset_kotor > 0 ? InvenHelper::rupiah($r->omset_kotor) : '-'); ?>

                    </td>
                    <td style="font-weight:700">
                        <?php if($r->omset > 0): ?>
                            <span style="color:#166534"><?php echo e(InvenHelper::rupiah($r->omset)); ?></span>
                        <?php elseif($r->omset < 0): ?>
                            <span style="color:#991b1b"><?php echo e(InvenHelper::rupiah($r->omset)); ?></span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($r->stok_sekarang == 0): ?>
                            <span class="badge badge-danger">Habis</span>
                        <?php elseif($r->stok_sekarang <= $r->stok_minimum): ?>
                            <span class="badge badge-warning">Kritis</span>
                        <?php else: ?>
                            <span class="badge badge-success">Aman</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr style="background:#f1f5f9;font-weight:700">
                    <td colspan="4" style="text-align:right;padding:12px 14px">TOTAL:</td>
                    <td style="text-align:center;color:#166534">+<?php echo e(number_format($grandMasuk)); ?></td>
                    <td style="text-align:center;color:#dc2626">-<?php echo e(number_format($grandKeluar)); ?></td>
                    <td style="text-align:center;color:#991b1b">
                        <?php echo e($grandRusak > 0 ? '-'.$grandRusak : '-'); ?>

                    </td>
                    <td style="text-align:center;color:#854d0e">
                        <?php echo e($grandReturn > 0 ? '+'.$grandReturn : '-'); ?>

                    </td>
                    <td></td>
                    <td style="font-size:12px;color:#64748b"><?php echo e(InvenHelper::rupiah($grandOmsetKotor)); ?></td>
                    <td style="color:#1e40af"><?php echo e(InvenHelper::rupiah($grandOmset)); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laravel\TBN\resources\views/inventaris/laporan.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Barang Keluar'); ?>

<?php $__env->startSection('content'); ?>
<?php use App\Helpers\InvenHelper; ?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-arrow-up" style="color:#f59e0b;margin-right:8px"></i>Barang Keluar</h1>
        <div class="breadcrumb">Inventaris › Barang Keluar</div>
    </div>
    <a href="<?php echo e(route('keluar.create')); ?>" class="btn btn-warning">
        <i class="fas fa-plus"></i> Catat Barang Keluar
    </a>
</div>


<div class="card" style="margin-bottom:16px">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <label style="font-size:13px;font-weight:600;margin:0">Filter Periode:</label>
            <select name="bln" style="width:auto">
                <?php for($m = 1; $m <= 12; $m++): ?>
                <option value="<?php echo e(str_pad($m,2,'0',STR_PAD_LEFT)); ?>" <?php echo e($bulan == str_pad($m,2,'0',STR_PAD_LEFT) ? 'selected' : ''); ?>>
                    <?php echo e(\Carbon\Carbon::create(null, $m)->translatedFormat('F')); ?>

                </option>
                <?php endfor; ?>
            </select>
            <input type="number" name="thn" value="<?php echo e($tahun); ?>" style="width:90px">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
        </form>
    </div>
</div>


<div class="stats-grid" style="margin-bottom:16px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;color:#854d0e"><i class="fas fa-receipt"></i></div>
        <div>
            <div class="label">Total Transaksi</div>
            <div class="value" style="color:#854d0e"><?php echo e($rows->count()); ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;color:#854d0e"><i class="fas fa-arrow-up"></i></div>
        <div>
            <div class="label">Total Unit Keluar</div>
            <div class="value" style="color:#854d0e"><?php echo e(number_format($totalUnit)); ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#166534"><i class="fas fa-sack-dollar"></i></div>
        <div>
            <div class="label">Total Pendapatan</div>
            <div class="value" style="color:#166534;font-size:16px"><?php echo e(InvenHelper::rupiah($totalNilai)); ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Daftar Barang Keluar — <?php echo e(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y')); ?></h3>
    </div>
    <div class="table-wrapper">
        <?php if($rows->isNotEmpty()): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Kode</th><th>Tanggal</th><th>Produk</th>
                    <th style="text-align:center">Jumlah</th><th>Harga Jual</th><th>Total</th>
                    <th>Pembeli</th><th>Keterangan</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="color:#94a3b8"><?php echo e($i + 1); ?></td>
                    <td><span class="badge badge-warning"><?php echo e($r->kode_transaksi); ?></span></td>
                    <td><?php echo e($r->tanggal->format('d/m/Y')); ?></td>
                    <td>
                        <div style="font-weight:600"><?php echo e($r->produk->nama_produk ?? '-'); ?></div>
                        <div style="font-size:11px;color:#94a3b8"><?php echo e($r->produk->kode_produk ?? ''); ?></div>
                    </td>
                    <td style="text-align:center;font-weight:700;color:#dc2626">-<?php echo e($r->jumlah); ?></td>
                    <td><?php echo e(InvenHelper::rupiah($r->harga_jual)); ?></td>
                    <td style="font-weight:600;color:#166534"><?php echo e(InvenHelper::rupiah($r->jumlah * $r->harga_jual)); ?></td>
                    <td><?php echo e($r->pembeli ?: '-'); ?></td>
                    <td style="color:#64748b"><?php echo e($r->keterangan ?: '-'); ?></td>
                    <td>
                        <form id="del-bk-<?php echo e($r->id); ?>" action="<?php echo e(route('keluar.destroy', $r->id)); ?>" method="POST" style="display:none">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        </form>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="confirmDelete('del-bk-<?php echo e($r->id); ?>', 'Hapus catatan ini? Stok akan dikembalikan.')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr style="background:#f8fafc;font-weight:700">
                    <td colspan="4" style="text-align:right;padding:12px 14px">Total:</td>
                    <td style="text-align:center;color:#dc2626">-<?php echo e(number_format($totalUnit)); ?></td>
                    <td></td>
                    <td style="color:#166534"><?php echo e(InvenHelper::rupiah($totalNilai)); ?></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-box"></i>
            <p>Belum ada catatan barang keluar pada periode ini.</p>
            <a href="<?php echo e(route('keluar.create')); ?>" class="btn btn-warning" style="margin-top:14px">
                <i class="fas fa-plus"></i> Catat Sekarang
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laravel\TBN\resources\views/inventaris/barang_keluar.blade.php ENDPATH**/ ?>
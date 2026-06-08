<?php $__env->startSection('title', 'Barang Rusak & Return'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1><i class="fas fa-triangle-exclamation" style="color:var(--danger)"></i> Barang Rusak & Return</h1>
        <div class="breadcrumb">Inventaris › Barang Rusak & Return</div>
    </div>
    <div class="btn-group">
        <a href="<?php echo e(route('rusak.create.rusak')); ?>" class="btn btn-danger">
            <i class="fas fa-triangle-exclamation"></i> Catat Rusak
        </a>
        <a href="<?php echo e(route('rusak.create.return')); ?>" class="btn btn-warning">
            <i class="fas fa-rotate-left"></i> Catat Return
        </a>
    </div>
</div>


<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr))">
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:var(--danger)"><i class="fas fa-triangle-exclamation"></i></div>
        <div>
            <div class="label">Barang Rusak</div>
            <div class="value" style="color:var(--danger)"><?php echo e(number_format($totalRusak)); ?></div>
            <div class="sub">unit bulan ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;color:#854d0e"><i class="fas fa-rotate-left"></i></div>
        <div>
            <div class="label">Return</div>
            <div class="value" style="color:#854d0e"><?php echo e(number_format($totalReturn)); ?></div>
            <div class="sub">unit bulan ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fee2e2;color:#991b1b"><i class="fas fa-chart-line"></i></div>
        <div>
            <div class="label">Omset Berkurang</div>
            <div class="value" style="color:#991b1b;font-size:15px"><?php echo e(\App\Helpers\InvenHelper::rupiah($totalNilaiReturn)); ?></div>
            <div class="sub">dari return bulan ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f3f4f6;color:#6b7280"><i class="fas fa-boxes-stacked"></i></div>
        <div>
            <div class="label">Total Dicatat</div>
            <div class="value"><?php echo e(number_format($totalUnit)); ?></div>
            <div class="sub">unit (rusak + return)</div>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success mb-4"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('danger')): ?>
    <div class="alert alert-danger mb-4"><i class="fas fa-exclamation-circle"></i> <?php echo e(session('danger')); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Daftar Catatan</h3>
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <select name="bln" class="form-control" style="width:auto">
                <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e(str_pad($m,2,'0',STR_PAD_LEFT)); ?>" <?php echo e($bulan == str_pad($m,2,'0',STR_PAD_LEFT) ? 'selected' : ''); ?>>
                        <?php echo e(\Carbon\Carbon::create()->month($m)->locale('id')->monthName); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <input type="number" name="thn" value="<?php echo e($tahun); ?>" class="form-control" style="width:85px" min="2020" max="2099">
            <select name="jenis" class="form-control" style="width:auto">
                <option value="" <?php echo e($jenis === '' ? 'selected' : ''); ?>>Semua</option>
                <option value="rusak"  <?php echo e($jenis === 'rusak'  ? 'selected' : ''); ?>>Rusak</option>
                <option value="return" <?php echo e($jenis === 'return' ? 'selected' : ''); ?>>Return</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
        </form>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Jenis</th>
                        <th style="text-align:center">Jumlah</th>
                        <th>Ref. Penjualan</th>
                        <th>Omset Berkurang</th>
                        <th>Pembeli / Ket.</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($i + 1); ?></td>
                        <td><code style="font-size:12px"><?php echo e($r->kode_transaksi); ?></code></td>
                        <td><?php echo e(\Carbon\Carbon::parse($r->tanggal)->format('d/m/Y')); ?></td>
                        <td>
                            <div style="font-weight:600"><?php echo e($r->produk->nama_produk ?? '-'); ?></div>
                            <div style="font-size:11px;color:var(--gray-500)"><?php echo e($r->produk->kode_produk ?? ''); ?></div>
                        </td>
                        <td>
                            <span class="badge <?php echo e($r->badgeJenis()); ?>">
                                <i class="fas <?php echo e($r->jenis === 'rusak' ? 'fa-triangle-exclamation' : 'fa-rotate-left'); ?>"></i>
                                <?php echo e($r->labelJenis()); ?>

                            </span>
                        </td>
                        <td style="text-align:center"><strong><?php echo e(number_format($r->jumlah)); ?></strong> <small><?php echo e($r->produk->satuan ?? ''); ?></small></td>
                        <td>
                            <?php if($r->jenis === 'return' && $r->barangKeluar): ?>
                                <div style="font-size:12px">
                                    <code><?php echo e($r->barangKeluar->kode_transaksi); ?></code><br>
                                    <span style="color:var(--gray-500)"><?php echo e(\Carbon\Carbon::parse($r->barangKeluar->tanggal)->format('d/m/Y')); ?></span>
                                </div>
                            <?php else: ?>
                                <span style="color:var(--gray-500)">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($r->jenis === 'return'): ?>
                                <span style="color:#991b1b;font-weight:700">
                                    −<?php echo e(\App\Helpers\InvenHelper::rupiah($r->nilaiReturn())); ?>

                                </span>
                            <?php else: ?>
                                <span style="color:var(--gray-500)">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="max-width:160px">
                            <?php if($r->sumber): ?><div style="font-size:13px"><?php echo e($r->sumber); ?></div><?php endif; ?>
                            <?php if($r->keterangan): ?><div style="font-size:11px;color:var(--gray-500)"><?php echo e(Str::limit($r->keterangan,40)); ?></div><?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo e(route('rusak.destroy', $r->id)); ?>"
                                  onsubmit="return confirm('Hapus catatan ini?\n\nStok akan dikembalikan ke kondisi semula.')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" style="text-align:center;padding:32px;color:var(--gray-500)">
                            <i class="fas fa-inbox" style="font-size:28px;display:block;margin-bottom:8px"></i>
                            Belum ada catatan untuk periode ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <?php if($rows->isNotEmpty()): ?>
                <tfoot>
                    <tr style="background:var(--gray-100);font-weight:700">
                        <td colspan="5" style="padding:11px 14px">TOTAL</td>
                        <td style="text-align:center"><?php echo e(number_format($totalUnit)); ?></td>
                        <td></td>
                        <td style="color:#991b1b">−<?php echo e(\App\Helpers\InvenHelper::rupiah($totalNilaiReturn)); ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<style>
.alert { padding:12px 16px; border-radius:8px; font-size:13px; display:flex; align-items:center; gap:8px; }
.alert-success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.alert-danger  { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.mb-4 { margin-bottom:16px; }
.form-control { padding:7px 10px; border:1.5px solid var(--gray-200); border-radius:7px; font-size:13px; outline:none; }
.form-control:focus { border-color:var(--primary); }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laravel\TBN\resources\views/inventaris/barang_rusak.blade.php ENDPATH**/ ?>
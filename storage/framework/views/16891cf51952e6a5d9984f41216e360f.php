<?php $__env->startSection('title', 'Data Produk'); ?>

<?php $__env->startSection('content'); ?>
<?php use App\Helpers\InvenHelper; ?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-box-open" style="color:#1e40af;margin-right:8px"></i>Data Produk</h1>
        <div class="breadcrumb">Produk › Daftar Produk</div>
    </div>
    <a href="<?php echo e(route('produk.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Daftar Produk (<?php echo e($produk->count()); ?> data)</h3>
        <form method="GET" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <select name="kat" onchange="this.form.submit()" style="width:auto;padding:7px 10px;font-size:13px">
                <option value="">Semua Kategori</option>
                <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($k->id); ?>" <?php echo e(request('kat') == $k->id ? 'selected' : ''); ?>><?php echo e($k->nama_kategori); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="q" placeholder="Cari produk..." value="<?php echo e(request('q')); ?>" style="width:220px">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
            <?php if(request('q') || request('kat')): ?>
            <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-outline btn-sm"><i class="fas fa-times"></i> Reset</a>
            <?php endif; ?>
        </form>
    </div>
    <div class="table-wrapper">
        <?php if($produk->isNotEmpty()): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Kode</th><th>Nama Produk</th><th>Kategori</th>
                    <th>Satuan</th><th>Harga Beli</th><th>Harga Jual</th>
                    <th style="text-align:center">Stok</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $produk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="color:#94a3b8"><?php echo e($i + 1); ?></td>
                    <td><span class="badge badge-primary"><?php echo e($p->kode_produk); ?></span></td>
                    <td>
                        <div style="font-weight:600"><?php echo e($p->nama_produk); ?></div>
                        <?php if($p->deskripsi): ?>
                        <div style="font-size:11px;color:#94a3b8"><?php echo e(Str::limit($p->deskripsi, 40)); ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($p->kategori->nama_kategori ?? '-'); ?></td>
                    <td><?php echo e($p->satuan); ?></td>
                    <td><?php echo e(InvenHelper::rupiah($p->harga_beli)); ?></td>
                    <td><?php echo e(InvenHelper::rupiah($p->harga_jual)); ?></td>
                    <td style="text-align:center">
                        <span class="<?php echo e($p->stok <= $p->stok_minimum ? 'stok-kritis' : 'stok-aman'); ?>"><?php echo e($p->stok); ?></span>
                        <div style="font-size:10px;color:#94a3b8">min: <?php echo e($p->stok_minimum); ?></div>
                    </td>
                    <td>
                        <?php if($p->stok == 0): ?>
                            <span class="badge badge-danger">Habis</span>
                        <?php elseif($p->stok <= $p->stok_minimum): ?>
                            <span class="badge badge-warning">Kritis</span>
                        <?php else: ?>
                            <span class="badge badge-success">Aman</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="<?php echo e(route('produk.edit', $p->id)); ?>" class="btn btn-outline btn-sm">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form id="del-produk-<?php echo e($p->id); ?>" action="<?php echo e(route('produk.destroy', $p->id)); ?>" method="POST" style="display:none">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            </form>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDelete('del-produk-<?php echo e($p->id); ?>', 'Yakin hapus produk \'<?php echo e(addslashes($p->nama_produk)); ?>\'?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>Belum ada produk<?php echo e(request('q') ? ' untuk pencarian "'.request('q').'"' : ''); ?>.</p>
            <a href="<?php echo e(route('produk.create')); ?>" class="btn btn-primary" style="margin-top:14px">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laravel\TBN\resources\views/produk/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Kategori Produk'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-tags" style="color:#1e40af;margin-right:8px"></i>Kategori Produk</h1>
        <div class="breadcrumb">Produk › Kategori</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start">
    
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-list"></i> Daftar Kategori</h3></div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th style="text-align:center">Jml Produk</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="color:#94a3b8"><?php echo e($i + 1); ?></td>
                        <td style="font-weight:600"><?php echo e($k->nama_kategori); ?></td>
                        <td style="color:#64748b"><?php echo e($k->deskripsi ?: '-'); ?></td>
                        <td style="text-align:center"><span class="badge badge-primary"><?php echo e($k->produk_count); ?></span></td>
                        <td>
                            <div class="btn-group">
                                <a href="<?php echo e(route('kategori.edit', $k->id)); ?>" class="btn btn-outline btn-sm">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form id="del-kat-<?php echo e($k->id); ?>" action="<?php echo e(route('kategori.destroy', $k->id)); ?>" method="POST" style="display:none">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                </form>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('del-kat-<?php echo e($k->id); ?>', 'Hapus kategori \'<?php echo e(addslashes($k->nama_kategori)); ?>\'?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3>
                <i class="fas fa-<?php echo e(isset($kategori) ? 'pen' : 'plus'); ?>"></i>
                <?php echo e(isset($kategori) ? 'Edit' : 'Tambah'); ?> Kategori
            </h3>
            <?php if(isset($kategori)): ?>
            <a href="<?php echo e(route('kategori.index')); ?>" class="btn btn-outline btn-sm"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if(isset($kategori)): ?>
            <form method="POST" action="<?php echo e(route('kategori.update', $kategori->id)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <?php else: ?>
            <form method="POST" action="<?php echo e(route('kategori.store')); ?>">
                <?php echo csrf_field(); ?>
            <?php endif; ?>
                <div class="form-group" style="margin-bottom:14px">
                    <label>Nama Kategori <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_kategori" required
                           value="<?php echo e(old('nama_kategori', $kategori->nama_kategori ?? '')); ?>"
                           placeholder="Contoh: Peralatan Dapur"
                           class="<?php echo e($errors->has('nama_kategori') ? 'is-invalid' : ''); ?>">
                    <?php $__errorArgs = ['nama_kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group" style="margin-bottom:16px">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Opsional"><?php echo e(old('deskripsi', $kategori->deskripsi ?? '')); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">
                    <i class="fas fa-save"></i> <?php echo e(isset($kategori) ? 'Simpan Perubahan' : 'Tambah Kategori'); ?>

                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laravel\TBN\resources\views/kategori/index.blade.php ENDPATH**/ ?>
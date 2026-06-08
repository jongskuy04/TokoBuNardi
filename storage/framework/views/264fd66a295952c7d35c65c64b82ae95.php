<?php $__env->startSection('title', 'Edit Produk'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-pen" style="color:#1e40af;margin-right:8px"></i>Edit Produk</h1>
        <div class="breadcrumb">Produk › Edit Produk</div>
    </div>
    <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-box"></i> Form Edit Produk</h3>
        <span class="badge badge-primary"><?php echo e($produk->kode_produk); ?></span>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('produk.update', $produk->id)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Kode Produk</label>
                    <input type="text" value="<?php echo e($produk->kode_produk); ?>" class="input-readonly" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Produk <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_produk" value="<?php echo e(old('nama_produk', $produk->nama_produk)); ?>"
                           class="<?php echo e($errors->has('nama_produk') ? 'is-invalid' : ''); ?>" required>
                    <?php $__errorArgs = ['nama_produk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id">
                        <option value="">-- Pilih Kategori --</option>
                        <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k->id); ?>" <?php echo e(old('kategori_id', $produk->kategori_id) == $k->id ? 'selected' : ''); ?>>
                            <?php echo e($k->nama_kategori); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Satuan</label>
                    <select name="satuan">
                        <?php $__currentLoopData = ['pcs','lusin','kg','gram','liter','box','pack','set','roll']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('satuan', $produk->satuan) === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga Beli (Rp)</label>
                    <input type="number" name="harga_beli" min="0" step="100" value="<?php echo e(old('harga_beli', $produk->harga_beli)); ?>">
                </div>
                <div class="form-group">
                    <label>Harga Jual (Rp) <span style="color:#ef4444">*</span></label>
                    <input type="number" name="harga_jual" min="0" step="100" value="<?php echo e(old('harga_jual', $produk->harga_jual)); ?>"
                           class="<?php echo e($errors->has('harga_jual') ? 'is-invalid' : ''); ?>" required>
                    <?php $__errorArgs = ['harga_jual'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label>Stok Saat Ini</label>
                    <input type="number" name="stok" min="0" value="<?php echo e(old('stok', $produk->stok)); ?>">
                    <small style="color:#64748b;font-size:11px">⚠️ Sebaiknya ubah stok via Barang Masuk/Keluar untuk akurasi laporan</small>
                </div>
                <div class="form-group">
                    <label>Stok Minimum</label>
                    <input type="number" name="stok_minimum" min="0" value="<?php echo e(old('stok_minimum', $produk->stok_minimum)); ?>">
                </div>
                <div class="form-group full">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3"><?php echo e(old('deskripsi', $produk->deskripsi)); ?></textarea>
                </div>
            </div>
            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laravel\TBN\resources\views/produk/edit.blade.php ENDPATH**/ ?>
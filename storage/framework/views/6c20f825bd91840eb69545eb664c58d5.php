<?php $__env->startSection('title', 'Tambah Produk'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-plus-circle" style="color:#1e40af;margin-right:8px"></i>Tambah Produk</h1>
        <div class="breadcrumb">Produk › Tambah Produk</div>
    </div>
    <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-box"></i> Form Tambah Produk</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('produk.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Kode Produk <span style="color:#ef4444">*</span></label>
                    <input type="text" name="kode_produk" value="<?php echo e(old('kode_produk', $kode)); ?>"
                           class="<?php echo e($errors->has('kode_produk') ? 'is-invalid' : ''); ?>" required>
                    <?php $__errorArgs = ['kode_produk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small style="color:#64748b;font-size:11px">Kode dibuat otomatis, bisa diubah</small>
                </div>
                <div class="form-group">
                    <label>Nama Produk <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_produk" placeholder="Contoh: Cobek Batu Ukuran Besar"
                           value="<?php echo e(old('nama_produk')); ?>"
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
                        <option value="<?php echo e($k->id); ?>" <?php echo e(old('kategori_id') == $k->id ? 'selected' : ''); ?>>
                            <?php echo e($k->nama_kategori); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Satuan</label>
                    <select name="satuan">
                        <?php $__currentLoopData = ['pcs','lusin','kg','gram','liter','box','pack','set','roll']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('satuan', 'pcs') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga Beli (Rp)</label>
                    <input type="number" name="harga_beli" min="0" step="100" value="<?php echo e(old('harga_beli', 0)); ?>">
                </div>
                <div class="form-group">
                    <label>Harga Jual (Rp) <span style="color:#ef4444">*</span></label>
                    <input type="number" name="harga_jual" min="0" step="100" value="<?php echo e(old('harga_jual', 0)); ?>"
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
                    <label>Stok Awal</label>
                    <input type="number" name="stok" min="0" value="<?php echo e(old('stok', 0)); ?>">
                </div>
                <div class="form-group">
                    <label>Stok Minimum (Batas Kritis)</label>
                    <input type="number" name="stok_minimum" min="0" value="<?php echo e(old('stok_minimum', 5)); ?>">
                </div>
                <div class="form-group full">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Deskripsi produk (opsional)"><?php echo e(old('deskripsi')); ?></textarea>
                </div>
            </div>
            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Produk</button>
                <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laravel\TBN\resources\views/produk/create.blade.php ENDPATH**/ ?>
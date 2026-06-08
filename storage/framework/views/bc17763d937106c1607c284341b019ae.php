<?php $__env->startSection('title', 'Catat Barang Rusak'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1><i class="fas fa-triangle-exclamation" style="color:var(--danger)"></i> Catat Barang Rusak</h1>
        <div class="breadcrumb">Inventaris › Rusak & Return › Tambah Rusak</div>
    </div>
    <a href="<?php echo e(route('rusak.index')); ?>" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="info-box mb-4">
    <i class="fas fa-circle-info"></i>
    <div>
        <strong>Barang Rusak</strong> — Stok akan <strong>berkurang</strong>. Gunakan ini untuk barang yang rusak/cacat dan tidak bisa dijual.
        Jika ingin mencatat <strong>return dari pembeli</strong>, gunakan menu
        <a href="<?php echo e(route('rusak.create.return')); ?>"><i class="fas fa-rotate-left"></i> Catat Return</a>.
    </div>
</div>

<div class="card" style="max-width:620px">
    <div class="card-header">
        <h3><i class="fas fa-file-pen"></i> Form Barang Rusak</h3>
    </div>
    <div class="card-body">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-circle"></i>
                <div><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><?php echo e($e); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('rusak.store')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="jenis" value="rusak">

            <div class="form-group">
                <label>Kode Transaksi</label>
                <input type="text" name="kode_transaksi" class="form-control <?php echo e($errors->has('kode_transaksi') ? 'is-invalid' : ''); ?>"
                       value="<?php echo e(old('kode_transaksi', $kode)); ?>" required>
                <?php $__errorArgs = ['kode_transaksi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label>Produk <span class="required">*</span></label>
                <select name="produk_id" class="form-control <?php echo e($errors->has('produk_id') ? 'is-invalid' : ''); ?>" required>
                    <option value="">-- Pilih Produk --</option>
                    <?php $__currentLoopData = $produkList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p->id); ?>" <?php echo e(old('produk_id') == $p->id ? 'selected' : ''); ?>>
                            <?php echo e($p->nama_produk); ?> — Stok: <?php echo e($p->stok); ?> <?php echo e($p->satuan); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['produk_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label>Jumlah Rusak <span class="required">*</span></label>
                <input type="number" name="jumlah" class="form-control <?php echo e($errors->has('jumlah') ? 'is-invalid' : ''); ?>"
                       value="<?php echo e(old('jumlah', 1)); ?>" min="1" required>
                <?php $__errorArgs = ['jumlah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label>Tanggal <span class="required">*</span></label>
                <input type="date" name="tanggal" class="form-control <?php echo e($errors->has('tanggal') ? 'is-invalid' : ''); ?>"
                       value="<?php echo e(old('tanggal', date('Y-m-d'))); ?>" required>
                <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label>Penyebab / Keterangan <small style="color:var(--gray-500)">(opsional)</small></label>
                <textarea name="keterangan" class="form-control" rows="3"
                          placeholder="Contoh: Cobek retak saat pengiriman"><?php echo e(old('keterangan')); ?></textarea>
            </div>

            <div style="display:flex;gap:10px;margin-top:6px">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?php echo e(route('rusak.index')); ?>" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

<style>
.form-group { margin-bottom:18px; }
label { display:block; font-size:13px; font-weight:600; color:var(--gray-700); margin-bottom:6px; }
.form-control { width:100%; padding:9px 12px; border:1.5px solid var(--gray-200); border-radius:8px; font-size:13.5px; color:var(--gray-900); outline:none; font-family:inherit; transition: border-color .2s; }
.form-control:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(30,64,175,.1); }
.form-control.is-invalid { border-color:var(--danger); }
.invalid-feedback { color:var(--danger); font-size:12px; margin-top:4px; }
.required { color:var(--danger); }
.alert { padding:12px 16px; border-radius:8px; font-size:13px; display:flex; align-items:flex-start; gap:8px; }
.alert-danger { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.mb-4 { margin-bottom:16px; }
.info-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:14px 16px; font-size:13px; color:#1e40af; display:flex; align-items:flex-start; gap:10px; }
.info-box a { color:#1e40af; font-weight:700; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laravel\TBN\resources\views/inventaris/tambah_rusak.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Catat Barang Masuk'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1><i class="fas fa-arrow-down" style="color:#10b981;margin-right:8px"></i>Catat Barang Masuk</h1>
        <div class="breadcrumb">Inventaris › Barang Masuk › Tambah</div>
    </div>
    <a href="<?php echo e(route('masuk.index')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-arrow-down"></i> Form Barang Masuk</h3></div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('masuk.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Kode Transaksi</label>
                        <input type="text" name="kode_transaksi" value="<?php echo e(old('kode_transaksi', $kode)); ?>" required>
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
                        <label>Tanggal <span style="color:#ef4444">*</span></label>
                        <input type="date" name="tanggal" value="<?php echo e(old('tanggal', date('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group full">
                        <label>Produk <span style="color:#ef4444">*</span></label>
                        <select name="produk_id" required onchange="updateStokInfo(this)"
                                class="<?php echo e($errors->has('produk_id') ? 'is-invalid' : ''); ?>">
                            <option value="">-- Pilih Produk --</option>
                            <?php $__currentLoopData = $produkList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>"
                                    data-stok="<?php echo e($p->stok); ?>"
                                    data-satuan="<?php echo e($p->satuan); ?>"
                                    <?php echo e(old('produk_id') == $p->id ? 'selected' : ''); ?>>
                                [<?php echo e($p->kode_produk); ?>] <?php echo e($p->nama_produk); ?> (Stok: <?php echo e($p->stok); ?> <?php echo e($p->satuan); ?>)
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
                        <div id="stokInfo" style="font-size:12px;color:#10b981;margin-top:4px"></div>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Masuk <span style="color:#ef4444">*</span></label>
                        <input type="number" name="jumlah" min="1" value="<?php echo e(old('jumlah')); ?>" placeholder="0" required
                               class="<?php echo e($errors->has('jumlah') ? 'is-invalid' : ''); ?>">
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
                        <label>Harga Beli per Unit (Rp)</label>
                        <input type="number" name="harga_beli" min="0" step="100" value="<?php echo e(old('harga_beli', 0)); ?>">
                    </div>
                    <div class="form-group full">
                        <label>Supplier / Pemasok</label>
                        <input type="text" name="supplier" placeholder="Nama supplier" value="<?php echo e(old('supplier')); ?>">
                    </div>
                    <div class="form-group full">
                        <label>Keterangan</label>
                        <textarea name="keterangan" rows="2" placeholder="Opsional"><?php echo e(old('keterangan')); ?></textarea>
                    </div>
                </div>
                <div style="margin-top:20px;display:flex;gap:10px">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Barang Masuk</button>
                    <a href="<?php echo e(route('masuk.index')); ?>" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3><i class="fas fa-circle-info"></i> Panduan</h3></div>
        <div class="card-body">
            <div style="font-size:13px;color:#475569;line-height:1.8">
                <p><i class="fas fa-circle-check" style="color:#10b981"></i> Pilih produk dari supplier</p>
                <p><i class="fas fa-circle-check" style="color:#10b981"></i> Isi jumlah unit yang diterima</p>
                <p><i class="fas fa-circle-check" style="color:#10b981"></i> Stok <strong>bertambah otomatis</strong></p>
                <p><i class="fas fa-circle-check" style="color:#10b981"></i> Harga beli diperbarui ke terbaru</p>
            </div>
            <div id="produkDetail" style="margin-top:16px;padding:12px;background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0;font-size:13px;color:#166534">
                Pilih produk untuk melihat stok saat ini
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function updateStokInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.dataset.stok !== undefined) {
        document.getElementById('produkDetail').innerHTML =
            '<strong>Stok Sekarang:</strong> ' + opt.dataset.stok + ' ' + opt.dataset.satuan;
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laravel\TBN\resources\views/inventaris/tambah_masuk.blade.php ENDPATH**/ ?>
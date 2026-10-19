<?php
// app/Views/partials/flash.php
use App\Core\Helper;

$success = Helper::flash('success');
$error   = Helper::flash('error');
?>
<?php if ($success || $error): ?>
    <div class="container-page pt-4">
        <?php if ($success): ?>
            <div class="uv-alert uv-alert--success mb-3" data-autohide role="status">
                <span class="uv-alert__icon">
                    <i data-lucide="check-circle-2" class="h-5 w-5"></i>
                </span>
                <div class="uv-alert__body">
                    <p class="uv-alert__title">Berhasil</p>
                    <p class="uv-alert__message"><?= Helper::e($success) ?></p>
                </div>
                <button type="button" class="uv-alert__close" data-dismiss aria-label="Tutup notifikasi">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="uv-alert uv-alert--error mb-3" data-autohide role="alert">
                <span class="uv-alert__icon">
                    <i data-lucide="alert-circle" class="h-5 w-5"></i>
                </span>
                <div class="uv-alert__body">
                    <p class="uv-alert__title">Terjadi kesalahan</p>
                    <p class="uv-alert__message"><?= Helper::e($error) ?></p>
                </div>
                <button type="button" class="uv-alert__close" data-dismiss aria-label="Tutup notifikasi">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

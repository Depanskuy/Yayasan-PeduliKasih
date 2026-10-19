<?php
// app/Views/partials/flash.php
use App\Core\Helper;

$success = Helper::flash('success');
$error   = Helper::flash('error');
?>
<?php if ($success || $error): ?>
    <div class="container-page pt-4">
        <?php if ($success): ?>
            <div class="alert-success mb-3" data-autohide>
                <i data-lucide="check-circle-2" class="mt-0.5 h-5 w-5 shrink-0"></i>
                <div class="flex-1"><?= Helper::e($success) ?></div>
                <button type="button" class="opacity-70 hover:opacity-100" data-dismiss>
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert-error mb-3" data-autohide>
                <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
                <div class="flex-1"><?= Helper::e($error) ?></div>
                <button type="button" class="opacity-70 hover:opacity-100" data-dismiss>
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

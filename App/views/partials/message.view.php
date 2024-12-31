<?php
use Framework\Session;

$success_message = Session::getFlashMessage('success_message');
$error_message = Session::getFlashMessage('error_message');
?>

<?php if (isset($success_message)): ?>
    <div class="message bg-green-100 p-3 my-3">
        <?= $success_message ?>
    </div>
<?php endif ?>

<?php if (isset($error_message)): ?>
    <div class="message bg-red-100 p-3 my-3">
        <?= $error_message ?>
    </div>
<?php endif ?>
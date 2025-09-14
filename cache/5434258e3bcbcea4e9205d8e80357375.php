<form action="<?= $login_path ?>" method="post">
<?php
if (isset($data['error'])) {
    echo $data['error'];
}
?>
<br>
<label for="">email</label><br>
<input type="email" name="email"><br>
<label for="">password</label><br>
<input type="password" name="password" id=""><br>
<input type="submit" value="Login"><br>
</form><?php /**PATH /project/workspace/theme/extrax/auth/login.blade.php ENDPATH**/ ?>
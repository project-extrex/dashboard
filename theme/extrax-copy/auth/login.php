<form action="<?= $login_path ?>" method="post">
<?php
if (isset($data['error'])) {
    echo $data['error'];
}
?>
<label for=""></label>
<input type="email" name="email">
<label for=""></label>
<input type="password" name="password" id="">
<input type="submit" value="Login">
</form>
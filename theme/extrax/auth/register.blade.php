<form action="" method="post">
    <div>
        <?= $data['error'] ?? '' ?>
    </div>
    <label for="username">username</label> <br>
    <input type="text" name="username" id=""> <br>
    <label for="firstname">firstname</label> <br>
    <input type="text" name="firstname"> <br>
    <label for="lastname">lastname</label> <br>
    <input type="text" name="lastname"> <br>
    <label for="email">email</label> <br>
    <input type="email" name="email"> <br>
    <label for="password">password</label> <br>
    <input type="password" name="password" id=""> <br>
    <label for="confirm"></label> <br>
    <input type="password" name="confirm_password"> <br>
    <input type="submit" value="Register">
</form>
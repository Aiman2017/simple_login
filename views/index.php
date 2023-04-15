<?php
    require_once '../app/bootstrap.php';
    if (!isLoggedIn()) {
        redirect('login');
    }

?>

<?php require_once 'template/header.php';?>
<div class="form-groups">
    <form method="post">
        <h1>EDIT PROFILE</h1>
        <div class="form-group">
            <label for="username">Name </label>
            <input type="text" class="form-control" id="username" name="name" placeholder="Enter name" value="<?= $name ?? ''?>">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email </label>
            <input type="text" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email"  value="<?= $email ?? ''?>">
        </div>
        <div class="form-group">
            <label for="phone">phone </label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone" value="<?= $phone ?? ''?>">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="text" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
        </div>
        <div class="form-group">
            <label for="confirm_password">confirm password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirmPassword" placeholder="Confirm password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>

</body>
</html>
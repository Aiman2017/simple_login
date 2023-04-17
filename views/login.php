<?php
require_once '../app/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $res = \Database\Database::readAll('login_system');
    $row = '';
    foreach ($res as $re => $v) {
        $row = $v;
    }
    $validate = new \validation\Validation();
    $errors = $validate->login($_POST);
    extract($_POST);
}

?>
<?php require_once "../views/template/header.php" ?>
<div class="form-groups">
    <form method="post"
    ">
    <h1>Login</h1>
    <?php if (!empty($errors['errors'])): ?>
        <div class="alert alert-danger" role="alert">
            <?= $errors['errors'] ?>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <label for="exampleInputEmail1">Email or Phone </label>
        <input type="text" class="form-control" id="exampleInputEmail1" name="login" placeholder="Enter email or phone"
               value="<?= $login ?? '' ?>">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
    </div>
    <div class="g-recaptcha" data-sitekey="6Lcqn5IlAAAAAHOKLlh2VqKl5EfZpJvKqFKSD8Hn"></div>
    <input type="hidden" value="<?= $row['id'] ?>">
    <button type="submit" class="btn btn-primary" value="Submit">Submit</button>
    <a href="signin.php">sign in</a>
    </form>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>

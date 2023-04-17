<?php
require_once '../app/bootstrap.php';
if (!isLoggedIn()) {
    redirect('login');
}
$id = $_GET['id'];
if (!$id) {
    redirect('login');
}
$error = [];
$con = \Database\Database::connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $validate = new \validation\Validation();
    $errors = $validate->validateName($_POST);
    $errors = $validate->validateEmail($_POST);
    $errors = $validate->validatePhone($_POST);
    $errors = $validate->validatePassword($_POST);
    if (empty($errors)) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = hash('sha1', $_POST['password']);

        $statement = $con->prepare('UPDATE login_system SET name = :name, email= :email, phone =:phone, password = :password  WHERE id =:id');
        $statement->bindValue(':name', $name);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':phone', $phone);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':id', $id);
        $check = $statement->execute();
        if ($check) {
            auth('USER');
            $name = '';
            $email = '';
            $phone = '';
        }
    }
}
?>

<?php require_once "../views/template/header.php" ?>
<div class="form-groups">
    <form method="post">
       <div class="d-inline-flex p-2 align-items-center ">
           <h1 class="mr-3">Edit profile</h1>
           <a href="../app/logout.php" class="btn btn-secondary">Logout</a>
       </div>
        <?php if (!empty($errors['checked']) && $errors['checked'] != '') : ?>
            <div class="alert alert-danger" role="alert">
                <?= $errors['checked'] ?>
            </div>
        <?php elseif (!empty($errors['confirmPassword'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= $errors['confirmPassword'] ?>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="username">Name </label>
            <input type="text" class="form-control" id="username" name="name" placeholder="Enter name"
                   value="<?= $name ?? '' ?>">
            <?php if (!empty($errors['errorName'])) : ?>
                <div style="color: red" class="red">
                    <?= $errors['errorName'] ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email </label>
            <input type="text" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email"
                   value="<?= $email ?? '' ?>">
            <?php if (!empty($errors['errorEmail'])) : ?>
                <div style="color: red" class="red">
                    <?= $errors['errorEmail'] ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="phone">phone </label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone"
                   value="<?= $phone ?? '' ?>">
            <?php if (!empty($errors['errorPhone'])) : ?>
                <div style="color: red" class="red">
                    <?= $errors['errorPhone'] ?>
                </div>
            <?php endif; ?>

        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="text" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
            <?php if (!empty($errors['errorPassword'])) : ?>
                <div style="color: red" class="red">
                    <?= $errors['errorPassword'] ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="confirm_password">confirm password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirmPassword"
                   placeholder="Confirm password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="login.php">login</a>

    </form>

</div>
</body>
</html>
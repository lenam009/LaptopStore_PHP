<?php
spl_autoload_register(function ($class) {
    require "../class/{$class}.php";
});
include'../init.php';

$result = "";

//NHẬN NHÓM QUYỀN
$rules = Rule::getAllRule($pdo);

//NHẬN INPUT
if (isset($_GET['email'])) {
    $user = User::getOneUserByEmail($pdo, $_GET['email']);
    if (empty($user)) {
        die("Email is not exists");
    }
}

//XÓA USER THEO YÊU CẦU NGƯỜI DÙNG
if (isset($_GET['action']) && $_GET['action'] == 'yes') {
    if ($user->deleteUser($pdo)) {
        if ($user->email == $_SESSION['user'])
            header('location: ../logout.php');
        else
            header('location: users.php');
    } else {
        $result = "This user is being used!!!";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //TRƯỜNG HỢP CÓ ĐỔI MẬT KHẨU
    if (!empty($_POST['confirmPassword'])) {
        if ($_POST['password'] != $_POST['confirmPassword']) {
            $result = "Password and re-password not match!!!";
        } else {
            $user->password = $_POST['password'];
            $user->idRule = $_POST['rule'];
            if ($user->updateUser($pdo)) {
                $result = "Update successful";
            } else {
                $result = "Update failed!!!";
            }
        }
    } //TRƯỜNG HỢP KO ĐỔI MẬT KHẨU
    else {
        $user->idRule = $_POST['rule'];
        if ($user->updateUserOnlyRole($pdo)) {
            $result = "Update successful";
        } else {
            $result = "Update failed!!!";
        }
    }
}

?>

<?php include '../admin/header.php' ?>


<form method="post" class="mt-2">
    <div class="w-75 m-auto">
        <div>
            <label for="email">Email (*)</label>
            <input id="email" class="form-control" disabled name="email" type="email" value="<?= $user->email ?>" />
        </div><br />
        <div>
            <label for="password">Password (*)</label>
            <input id="password" class="form-control" required name="password" type="password" value="<?= $user->password ?>" />
        </div><br />
        <div>
            <label for="repass">Re-password (*)</label>
            <input id="repass" class="form-control" name="confirmPassword" type="password" />
        </div><br />
        <div>
            <a>Role:</a>
            <?php foreach ($rules as $rule) : ?>
                <?php if ($user->idRule == $rule->idRule) : ?>
                    <input class="ml-3" type="radio" checked id="<?= $rule->idRule ?>" name="rule" value="<?= $rule->idRule ?>" />
                <?php else : ?>
                    <input class="ml-3" type="radio" id="<?= $rule->idRule ?>" name="rule" value="<?= $rule->idRule ?>" />
                <?php endif; ?>
                <label for="<?= $rule->idRule ?>"><?= $rule->nameRule ?></label>
            <?php endforeach; ?>
        </div><br />
        <div class="row p-0">
            <a class="btn btn-danger ml-3" href="../admin/user.php?email=<?= $user->email ?>&action=delete">Delete</a>
            <button class="btn btn-primary ml-2" type="submit" name="update" value="update">Update</button>
        </div>
        <div class="row m-2">
            <?php if (isset($_GET['action']) && $_GET['action'] == 'delete') : ?>
                <h3 class="text-danger">Are you sure delete?</h3>
                <a class="btn btn-danger ml-3" href="../admin/user.php?email=<?= $user->email ?>&action=yes">Yes</a>
                <a class="btn btn-primary ml-2" href="../admin/users.php">Go to page users</a>
            <?php endif; ?>
        </div>
        <div class="row text-center mt-3 p-0">
            <h4 class="text-danger ml-3"><?= $result ?></h4>
        </div>
    </div>
</form>


<?php include '../footer.php' ?>
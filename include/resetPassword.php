<?php
spl_autoload_register(function ($class) {
    require "../include/class/{$class}.php";
});
include'../include/init.php';

$result = "";

//CHECK COI CÓ $GET EMAIL
if (!isset($_GET['email'])) {
    die('Email invalid');
}

if (!isset($_GET['token'])) {
    die('Token invalid');
} else {
    $user = User::getOneUserByEmail($pdo, $_GET['email']);
    //CHECK COI EMAIL CÓ TỒN TẠI
    if (empty($user)) {
        die("Email not exists");
    }
    //CHECK COI TOKEN CÓ QUÁ HẠN
    //LẤY GIỜ ĐÃ KHỞI TẠO TOKEN
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $user->dateresetpass);
    //LẤY GIỜ HIỆN TẠI
    $dateCurrent = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', time()));
    //LẤY KHOẢNG CÁCH 2 DATE TIME 
    $diff = $dateCurrent->diff($date);
    //CHECK COI HOUR HOẶC DAY CỦA GIỮA 2 DATE TIME CÓ LỚN HƠN 0
    if ($diff->format('%H') > 0 || $diff->days > 0) {
        die('Link out of time');
    }
    //CHECK TOKEN CÓ KHỚP VỚI TOKEN ĐÃ TẠO
    if ($_GET['token'] != $user->token) {
        die("Token not match");
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $email = $_POST["email"];

    //CHECK PASSWORD VÀ RE-PASSWORD CÓ KHỚP
    if ($password != $confirmPassword) {
        $result = "Password and re-password not match!!!";
    }

    //RESET PASSWORD
    if (empty($result)) {
        if (User::updatePasswordByEmail($pdo, $password, $email)) {
            $result = "Reset password successful";
        } else {
            $result = "Reset password failed";
        }
    }
}

?>

<?php include '../include/header.php'; ?>

<div class="container-fluid">
    <div class="m-auto w-75">
        <table class="table mt-3 table-borderless">
            <form method="post">
                <div>
                    <label for="password">Password (*)</label>
                    <input id="password" class="form-control" required name="password" type="password" />
                </div><br />
                <div>
                    <label for="repass">Re-password (*)</label>
                    <input id="repass" class="form-control" required name="confirmPassword" type="password" />
                </div><br />
                <tr>
                    <td class="text-right border-0" colspan="2">
                        <input type="hidden" name="email" value="<?= $_GET['email'] ?>" />
                        <button class="btn btn-primary" name="submit">Submit</button>
                    </td>
                </tr>
                <tr>
                    <td class="border-0" colspan="2">
                        <h4 class="text-danger font-weight-bold"><?= $result ?></h4>
                    </td>
                </tr>
            </form>
        </table>
    </div>
</div>

<?php include '../include/footer.php'; ?>
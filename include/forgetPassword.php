<?php
spl_autoload_register(function ($class) {
    require "../include/class/{$class}.php";
});
require '../include/class/PHPMailer-master/src/Exception.php';
require '../include/class/PHPMailer-master/src/PHPMailer.php';
require '../include/class/PHPMailer-master/src/SMTP.php';

include '../include/init.php';

$result = "";
$token = rand(1000, 9999);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!preg_match("/^\\S+@\\S+\\.\\S+$/", $_POST['email']))
        $result = "Email invalid";
    else {
        //CHECK COI EMAIL CÓ TỒN TẠI
        $user = User::getOneUserByEmail($pdo, $_POST['email']);
        if (!empty($user)) {
            //GỬI LINK RESET PASSWORD TỚI NGƯỜI DÙNG QUA EMAIL
            Email::sendEmail($_POST['email'], $token);
            //UPDATE THỜI GIAN GỬI LINK VÀ TOKEN CHO NGƯỜI DÙNG
            User::updateDateResetPasswordAndToken($pdo, $_POST['email'], $token);
            $result = "Please check your email for reset password";
        } else
            $result = "Email incorrect!!!";
    }
}
?>

<?php include '../include/header.php'; ?>

<div class="container-fluid">
    <div class="m-auto w-75">
        <table class="table mt-3 table-borderless">
            <form method="post">
                <tr>
                    <td class="border-0"><label for="email">Email</label></td>
                    <td class="border-0"><input required class="form-control" type="email" name="email" /></td>
                </tr>
                <tr>
                    <td class="text-right border-0" colspan="2">
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
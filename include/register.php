<?php
spl_autoload_register(function ($class) {
    require "../include/class/{$class}.php";
});
include'../include/init.php';


$email = "";
$emailError = "";
$result = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["register"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];

        if ($password != $confirmPassword) {
            $result = "Password and re-password not match!!!";
        }

        if (!preg_match("/^\\S+@\\S+\\.\\S+$/", $email)) {
            $emailError = "Email invalid!!!";
        }

        if (empty($emailError) && empty($result)) {
            if (User::addUser($pdo, $email, $password, 2)) {
                $result = "Register successful";
            } else {
                $result = "Email is exists!!!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>


<?php include '../include/header.php' ?>

<div class="row" style="background-color: lightblue;">
    <div class="col-1">

    </div>
    <div class="col-5">
        <img src="../include/images/repersentLogin.png" width="700" />
    </div>
    <div class="col-5 bg-white" style="padding-top: 10%;">
        <form method="post">
            <div class="w-75 m-auto">
                <div>
                    <label for="email">Email (*)</label>
                    <input id="email" class="form-control" required name="email" type="email" value="<?= $email ?>" />
                    <span class="text-danger"><?= $emailError ?></span>
                </div><br />
                <div>
                    <label for="password">Password (*)</label>
                    <input id="password" class="form-control" required name="password" type="password" />
                </div><br />
                <div>
                    <label for="repass">Re-password (*)</label>
                    <input id="repass" class="form-control" required name="confirmPassword" type="password" />
                </div><br />
                <div class="">
                    <button class="btn text-white float-right" style="background-color: orange;" type="submit" name="register" value="register">Register</button>
                    <h4 class="text-danger mt-2"><?= $result ?></h4>
                </div>
            </div>
        </form>
    </div>
    <div class="col-1">

    </div>
</div>

<?php include '../include/footer.php' ?>
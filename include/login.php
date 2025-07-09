<?php
spl_autoload_register(function ($class) {
    require "../include/class/{$class}.php";
});
include'../include/init.php';


$email = "";
$emailError = "";
$password = "";
$result = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        //var_dump($password);

        if (!preg_match("/^\\S+@\\S+\\.\\S+$/", $email)) {
            $emailError = "Email invalid!";
        }

        if (empty($emailError)) {
            if (User::checkLogin($pdo, $email, $password)) {
                $_SESSION['user'] = $email;
                if (User::checkAdmin($email, $pdo)) {
                    header('location: admin/');
                } else {
                    header('location: index.php');
                }
            } else {
                $result = "Email or password incorrect!";
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
                    <input id="password" class="form-control" required name="password" type="password" value="<?= $password ?>" />
                </div><br />
                <div class="row p-0">
                    <div class="col-6">
                        <button class="btn btn-primary" type="submit" name="submit" value="submit">Login</button>
                    </div>
                    <div class="col-6 text-right pt-2">
                        <a href="../include/register.php" style="background-color: orange;" class="p-2 text-right rounded text-white border-0 ">Register</a><br />
                        <a href="../include/forgetPassword.php" class=" text-white btn btn-danger mt-4">Forget password</a>
                    </div>
                </div>
                <div>
                </div>
                <div class="row text-center mt-3 p-0">
                    <h4 class="text-danger ml-3"><?= $result ?></h4>
                </div>
            </div>
        </form>
    </div>
    <div class="col-1">

    </div>
</div>


<?php include '../include/footer.php' ?>
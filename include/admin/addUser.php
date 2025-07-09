<?php
spl_autoload_register(function ($class) {
    require "../class/{$class}.php";
});
include'../init.php';


$email = "";
$emailError = "";
$confirmPassword = "";
$password = "";
$result = "";
//NHẬN NHÓM QUYỀN
$rules = Rule::getAllRule($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    if ($password != $confirmPassword) {
        $result = "Password and re-password not match!";
    }

    if (!preg_match("/^\\S+@\\S+\\.\\S+$/", $email)) {
        $emailError = "Email invalid!";
    }

    if (!isset($_POST['rule'])) {
        $result = "You not choose role!!!";
    }

    if (empty($emailError) && empty($result)) {
        if (User::addUser($pdo, $email, $password, $_POST['rule'])) {
            $result = "Submit successful";
        } else {
            $result = "Email is exists";
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


<?php include '../admin/header.php' ?>

<div class="w-75 m-auto">
    <form method="post" class="mt-2">
        <div class="">
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
            <div>
                <a>Role: </a>
                <?php foreach ($rules as $rule) : ?>
                    <input class="ml-4" id="<?= $rule->idRule ?>" type="radio" name="rule" value="<?= $rule->idRule ?>" />
                    <label class="" for="<?= $rule->idRule ?>"><?= $rule->nameRule ?></label>
                <?php endforeach; ?>
            </div><br />
            <div class="">
                <button class="btn text-white btn-primary" type="submit" name="submit" value="submit">Submit</button>
                <h4 class="text-danger mt-2"><?= $result ?></h4>
            </div><br />
        </div>
    </form>
</div><br />

<?php include '../footer.php' ?>
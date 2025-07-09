<?php
if (isset($_SESSION['user']))
    if (User::checkAdmin($_SESSION['user'], $pdo)) {
        header('location: admin/');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../include/bootstrap/css/bootstrap.min.css">
    <style>
        .menu li {
            margin-left: 95px;
        }

        .card-hover:hover {
            box-shadow: 0px 0px 7px 1px grey inset;
            /* (h,v,độ mờ, bán kính mờ,màu,mờ bên trong khối) */
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="background-color: lightseagreen;">
        <div class="row">
            <nav class="navbar navbar-expand " style="height: 8%;">
                <a class="navbar-brand navbar " href="../include/"><img src="../include/images/logo.png" width="70" /></a>
                <ul class="navbar navbar-collapse nav menu  text-center font-weight-bold">
                    <li>
                        <form method="get" action="../include/index.php">
                            <div class="row">
                                <div class="col-10">
                                    <input type="text" placeholder="Import a key for search!" required name="search" class="form-control form-inline" />
                                </div>
                                <div class="col-2 border-0">
                                    <button type="submit" class="border-0 p-0 rounded-right" style="margin-left: -220%;cursor: pointer;"><a class=""><img src="../include/images/search1.png" class="rounded-right" width="40" height="39" /></a></button>
                                </div>
                            </div>
                        </form>
                    </li>
                    <?php if (!isset($_SESSION['user'])) : ?>
                        <li class="nav-item"><a href="../include/login.php" class="nav-link text-white"><span><img src="../include/images/login1.png" /></span><br />Login</a></li>
                        <li class="nav-item"><a href="../include/register.php" class="nav-link text-white"><span><img src="../include/images/register.png" /></span><br />Register</a></li>
                    <?php else : ?>
                        <li class="nav-item"><a href="../include/cart.php" class="nav-link text-white"><span><img src="../include/images/cart.png" /></span><br />Cart
                                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) != 0) : ?>
                                    <span>(<?= count($_SESSION['cart']) ?>)</span>
                                <?php endif ?></a>
                        </li>
                        <li class="nav-item"><a href="../include/purchaseHistory.php" class="nav-link text-white"><span><img src="../include/images/purchaseHistory.png" /></span><br />Purchase history</a></li>
                        <li class="nav-item"><a href="../include/logout.php" class="nav-link text-white"><span><img src="../include/images/logout.png" /></span><br />Logout</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
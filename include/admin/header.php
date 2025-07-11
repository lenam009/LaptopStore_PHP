<?php
if (!isset($_SESSION['user'])) {
    die('You must login');
}else{
 if (!User::checkAdmin($_SESSION['user'], $pdo)) {
       header('location: ../index.php');
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
    <style>
        .menu li {
            margin-left: 70pt;
        }

        .card-hover:hover {
            box-shadow: 0px 0px 10px 1px grey inset;
            /* (h,v,độ mờ, bán kính mờ) */
        }

        .card-hoverType:hover {
            box-shadow: 0px 0px 3px 2px yellow inset;
            /* (h,v,độ mờ, bán kính mờ,màu,mờ bên trong khối) */
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="background-color: lightseagreen;">
        <div class="row">
            <nav class="navbar nav navbar-expand " style="height: 8%;">
                <a class="navbar-brand nav navbar " href="../admin/"><img src="../images/logo.png" width="70" /></a>
                <ul class="navbar nav menu navbar-collapse text-center font-weight-bold">
                    <li>
                        <form method="get" action="../admin/index.php">
                            <div class="row">
                                <div class="col-10">
                                    <input type="text" placeholder="Import a key for search!" required name="search" class="form-control form-inline" />
                                </div>
                                <div class="col-2 border-0">
                                    <button type="submit" class="border-0 p-0 rounded-right"  style="margin-left: -220%;cursor: pointer;"><a class=""><img src="../images/search1.png" class="rounded-right" width="40" height="39" /></a></button>
                                </div>
                            </div>
                        </form>
                    </li>
                    <li class="nav-item"><a href="../admin/cartManage.php" class="nav-link text-white"><span><img src="../images/manageCart.png" /></span><br />Manage cart</a></a> </li>
                    <li class="nav-item"><a href="../admin/users.php" class="nav-link text-white"><span><img src="../images/user.png" /></span><br />Users</a></a> </li>
                    <li class="nav-item"><a href="../admin/newProduct.php" class="nav-link text-white"><span><img src="../images/newProduct.png" /></span><br />New product</a></li>
                    <li class="nav-item"><a href="../logout.php" class="nav-link text-white"><span><img src="../images/logout.png" /></span><br />Logout</a></li>
                </ul>
            </nav>
        </div>
    </div>
<!-- <head>
    <link rel="stylesheet" href="../include/bootstrap/css/bootstrap.min.css">
</head> -->

<?php
// spl_autoload_register(function ($class) {
//     require "../include/class/{$class}.php";
// });
// session_start();

// $db = new Database();
// $pdo = $db->getPDOConnect();

$productsType = TypeProduct::getAllProduct($pdo);

?>

<div class="row mt-1">
    <ul class="navbar nav flex-column text-center">
        <li class="nav-item bg-dark text-white p-2 " style="width: 110%;"><a>Producer</a></li>
        <?php foreach ($productsType as $product) : ?>
            <li class="nav-item card-hoverType  p-0 " style="width: 110%;"><a href="../include/index.php?idType=<?=$product->idTypeProduct ?>" class="bg-primary   nav-link text-white"><?= $product->nameTypeProduct ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>
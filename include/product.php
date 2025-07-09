<?php
spl_autoload_register(function ($class) {
    require "../include/class/{$class}.php";
});
include'../include/init.php';

$result = "";

//CHECK INPUT PRODUCT
if (isset($_GET['id'])) {
    $product = Product::getOneProductById($_GET['id'], $pdo);
    if (empty($product)) {
        die('Product not exists');
    } else {
        //NHẬN CATEGORY CỦA SẢN PHẨM ĐÓ
        $typeProduct = TypeProduct::getTypeProductByIdType($pdo, $product->idTypeProduct);
    }
} else {
    die('Product invalid');
}

//ADD TO CART
if (isset($_GET['action']) && $_GET['action'] == "addcart") {
    //KIỂM TRA COI CÓ SESSION CART (NẾU CHƯA CÓ THÌ KHỞI TẠO)
    $_SESSION['cart'] = $_SESSION['cart'] ?? array();
    //ADD SẢN PHẨM MÀ NGƯỜI DÙNG MUA VÀO SESSION
    if (Cart::addCartItemSession($product->idProduct, $pdo))
        $result = "Buy successful";
    else
        $result = "Buy failed";
}
?>

<?php include '../include/header.php' ?>

<div class="row m-2">
    <div class="col-5">
        <img src="../include/images/<?= $product->imageProduct ?>" width="500" height="420" />
    </div>
    <div class="col-7">
        <h4 class="font-wight-bold"><?= $product->nameProduct ?></h4>
        <a class="font-wight-bold"><?= $product->contentProduct ?></a><br /><br />
        <a class="font-weight-bold" style="font-size: large;">Producer : </a>
        <?php if (empty($typeProduct)) : ?>
            <a class="h6">None</a>
        <?php else : ?>
            <a class="h6"><?= $typeProduct->nameTypeProduct ?></a>
        <?php endif; ?><br /><br />
        <h4 class="text-danger"><?= number_format($product->priceProduct, 0, ',', '.') ?>&nbsp;VNĐ</h4>

        <div class="row mt-4 mb-4">
            <div class="col-12  ml-0  ">
                <h3 class="font-weight-bold">Data engine</h3>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td class="w-25">Screen</td>
                        <td><?= $product->screen ?></td>
                    </tr>
                    <tr>
                        <td>CPU</td>
                        <td><?= $product->CPU ?></td>
                    </tr>
                    <tr>
                        <td>RAM</td>
                        <td><?= $product->RAM ?></td>
                    </tr>
                    <tr>
                        <td>Hard Drive</td>
                        <td><?= $product->hardDrive ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php if (isset($_SESSION['user'])) : ?>
            <a class="btn btn-danger" href="../include/product.php?action=addcart&id=<?= $product->idProduct ?>"><img src="../include/images/buy.png" /> Buy Now</a><br /><br />
        <?php endif; ?>
        <a class="text-danger m-0  font-weight-bold" style="font-size: x-large;"><?= $result ?></a>
    </div>
</div>

<?php include '../include/footer.php' ?>
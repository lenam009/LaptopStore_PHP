<?php
spl_autoload_register(function ($class) {
    require "../class/{$class}.php";
});
include'../init.php';

$result = "";

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

//XÓA SẢN PHẨM
if (isset($_GET['action'])) {
    if ($_GET['action'] == "yes") {
        if ($product->removeProduct($pdo)) {
            header('location: index.php');
        } else {
            $result = "Product is being used, so can't delete!!!";
        }
    }
}
?>

<?php include '../admin/header.php' ?>

<div class="row m-2">
    <div class="col-5">
        <img src="../images/<?= $product->imageProduct ?>" width="500" height="420" />
    </div>
    <div class="col-7">
        <h4 class="font-wight-bold"><?= $product->nameProduct ?></h4>
        <a class="font-wight-bold"><?= $product->contentProduct ?></a><br /><br />
        <a class="font-weight-bold h5">Producer : </a>
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
        <?php if (isset($_GET['action']) && $_GET['action'] = 'delete' && empty($result)) : ?>
            <h3 class="font-weight-bold text-info">Are you sure delete this product?</h3>
            <a class="btn btn-danger" href="../admin/product.php?id=<?= $product->idProduct ?>&action=yes">Yes</a>
            <a class="btn btn-primary ml-5" href="../admin/product.php?id=<?= $product->idProduct ?>">Back</a>
        <?php else : ?>
            <a class="btn btn-danger" href="../admin/product.php?id=<?= $product->idProduct ?>&action=delete">Delete</a>
            <a class="btn btn-primary ml-5" href="../admin/updateProduct.php?id=<?= $product->idProduct ?>">Update</a>
        <?php endif; ?>
        <h3 class="font-weight-bold text-danger mt-2"><?= $result ?></h3>
    </div>
</div>

<?php include '../footer.php' ?>
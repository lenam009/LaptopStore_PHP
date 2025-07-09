<?php
spl_autoload_register(function ($class) {
    require "../class/{$class}.php";
});
include'../init.php';


//GÁN PAGE VỚI TRƯỜNG HOP PAGE < 1 && CHUA CÓ PAGE
$page = $_GET['page'] ?? 1;
$page = $page < 1 ? 1 : $page;
//PHÂN TRANG
$productEachPage = 3;
$limit = $productEachPage;
$offset = ($page - 1) * $limit;

//TAKE PRODUCT
//TRƯỜNG HỢP KEYWORD VÀ SORT
if (isset($_GET['search']) && isset($_GET['action'])) {
    $data = Product::getOneProductByWordBySort("%" . $_GET['search'] . "%", $pdo, $limit, $offset, $_GET['action']);
    $max = Product::getCountProductByWord("%" . $_GET['search'] . "%", $pdo);
}
//TRƯỜNG HỢP KEYWORD
elseif (isset($_GET['search'])) {
    $data = Product::getOneProductByWord("%" . $_GET['search'] . "%", $pdo, $limit, $offset);
    $max = Product::getCountProductByWord("%" . $_GET['search'] . "%", $pdo);
}
//TRƯỜNG HỢP CATEGORY VÀ SORT
else if (isset($_GET['idType']) && isset($_GET['action'])) {
    $data = Product::getOneProductByIdTypeBySort($_GET['idType'], $pdo, $limit, $offset, $_GET['action']);
    $max = Product::getCountByIdType($pdo, $_GET['idType']);
}
//TRƯỜNG HỢP CATEGORY
else if (isset($_GET['idType'])) {
    $data = Product::getOneProductByIdType($_GET['idType'], $pdo, $limit, $offset);
    $max = Product::getCountByIdType($pdo, $_GET['idType']);
}
//TRƯỜNG HỢP SORT
else if (isset($_GET['action'])) {
    $data = Product::getProductBySort($pdo, $_GET['action'], $limit, $offset);
    $max = Product::getCountAllProduct($pdo);
}
//TRƯỜNG HỢP KO CHỌN GÌ HẾT
else {
    $data = Product::getProductByPage($pdo, $limit, $offset);
    $max = Product::getCountAllProduct($pdo);
}

//CHECK PAGE LON HON PAGE QUY DINH
if ($page > ceil(($max) / $productEachPage) && ($max) != 0) {
    die("Product invalid");
}

?>

<?php include '../admin/header.php' ?>

<div class="container-fluid">
    <div class="mt-2 row">
        <div class="col-12 text-right">
            <?php if (isset($_GET['idType'])) : $idType = $_GET['idType'] ?>
                <a class="btn btn-secondary" href="../admin/index.php?action=decPrice&idType=<?= $idType ?>">Sort decrease price</a>
                <a class="btn btn-secondary" href="../admin/index.php?action=incPrice&idType=<?= $idType ?>">Sort increase price</a>
            <?php elseif (isset($_GET['search'])) : $search = $_GET['search'] ?>
                <a class="btn btn-secondary" href="../admin/index.php?action=decPrice&search=<?= $search ?>">Sort decrease price</a>
                <a class="btn btn-secondary" href="../admin/index.php?action=incPrice&search=<?= $search ?>">Sort increase price</a>
            <?php else : ?>
                <a class="btn btn-secondary" href="../admin/index.php?action=decPrice">Sort decrease price</a>
                <a class="btn btn-secondary" href="../admin/index.php?action=incPrice">Sort increase price</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="row m-2">
        <div class="col-11">
            <div class="row">
                <?php foreach ($data as $product) : ?>
                    <div class="col-4 text-center mt-2  p-1 ">
                        <div class="card text-center border border-dark" style="height: 100%;">
                            <div class="card-body card-hover">
                                <a href="../admin/product.php?id=<?= $product->idProduct ?>"><img src="../images/<?= $product->imageProduct ?>" width="300" height="180" /></a><br /><br />
                                <a class="small font-weight-bold"><?= $product->nameProduct ?></a><br /><br />
                                <a class="text-danger font-weight-bold"><?= number_format($product->priceProduct, 0, ',', '.')  ?> VNĐ</a>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-1 ">
            <?php include '../admin/typeProduct.php' ?>
        </div>
    </div>
    <div class="text-center col-12 mt-3">
        <?php for ($i = $page - 1; $i <= ceil($max / $limit); $i++) : ?>
            <?php if ($i > 0) : ?>
                <?php if ($i == $page) : ?>
                    <a class="btn btn-danger text-white"><?= $i ?></a>
                <?php else : ?>
                    <?php //TRƯỜNG HỢP KEYWORD VÀ SORT
                    if (isset($_GET['search']) && isset($_GET['action'])) : ?>
                        <a class="text-primary btn" href="../admin/index.php?page=<?= $i ?>&search=<?= $_GET['search'] ?>&action=<?= $_GET['action'] ?>"><?= $i ?></a>
                    <?php //TRƯỜNG HỢP KEYWORD
                    elseif (isset($_GET['search'])) : $search = $_GET['search'] ?>
                        <a class="text-primary btn" href="../admin/index.php?page=<?= $i ?>&search=<?= $_GET['search'] ?>"><?= $i ?></a>
                    <?php  //TRƯỜNG HỢP CATEGORY VÀ SORT
                    elseif (isset($_GET['action']) && isset($_GET['idType'])) : ?>
                        <a class="text-primary btn" href="../admin/index.php?page=<?= $i ?>&idType=<?= $_GET['idType'] ?>&action=<?= $_GET['action'] ?>"><?= $i ?></a>
                    <?php //TRƯỜNG HỢP CATEGORY
                    elseif (isset($_GET['idType'])) : ?>
                        <a class="text-primary btn" href="../admin/index.php?page=<?= $i ?>&idType=<?= $_GET['idType'] ?>"><?= $i ?></a>
                    <?php //TRƯỜNG HỢP SORT
                    elseif (isset($_GET['action'])) : ?>
                        <a class="text-primary btn" href="../admin/index.php?page=<?= $i ?>&action=<?= $_GET['action'] ?>"><?= $i ?></a>
                    <?php else : ?>
                        <a class="text-primary btn" href="../admin/index.php?page=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                    <?php //GIỚI HẠN LẠI SỐ PAGE ĐƯỢC CHỌN
                    if ($i == $page + 1) : ?>
                        <a class="text-primary btn">...</a>
                        <?php break; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</div>


<?php include '../footer.php'; ?>
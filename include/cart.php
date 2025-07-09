<?php
spl_autoload_register(function ($class) {
    require "../include/class/{$class}.php";
});
include'../include/init.php';

$result = "";
$emptyCartResult = "";

//KHAI BÁO BIẾN TỔNG TIỀN ĐỂ TÍNH TỔNG TIỀN
$money = 0;

//XÓA GIỎ HÀNG THEO YÊU CẦU NGƯỜI DÙNG
if (isset($_GET['action']) && $_GET['action'] == "yes") {
    unset($_SESSION['cart']);
}

//CẬP NHẬT LAI SỐ LƯỢNG HOẶC XÓA SẢN PHẨM THEO YÊU CẦU NGƯỜI DÙNG
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['submit'] == "delete") {
        Cart::delete($_POST['idProduct']);
        $result = "Delete successful";
    }
    if ($_POST['submit'] == "update") {
        Cart::update($_POST['idProduct'], $_POST['quantity']);
        $result = "Update successful";
    }
}

//CHECK COI GIỎ HÀNG CÓ RỖNG
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $emptyCartResult = "You not buy product";
}

//THỰC HIỆN THANH TOÁN
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'pay') {
        $idLastCart = Cart::addCartDB($pdo, $_SESSION['user'], "Wait for confirmation");
        foreach ($_SESSION['cart'] as $cartItem) {
            $cartItem->idCart = $idLastCart;
            $cartItem->addToCartItemDB($pdo);
        }
        Cart::updateMoneyByIdCart($pdo, $_GET['money'], $idLastCart);
        $emptyCartResult = "Payment successful ";
        unset($_SESSION['cart']);
    } 
}

?>

<?php include '../include/header.php' ?>

<div class="container-fluid">
    <?php if (!empty($emptyCartResult)) : ?>
        <h3 class="font-weight-bold text-center m-2" style="color: orange;"><?= $emptyCartResult ?></h3>
    <?php else : ?>
        <div class="w-100 m-auto pt-2">
            <? if (isset($_GET['action']) && $_GET['action'] == "delete") : ?>
                <a class="text-danger h4 ">Are you sure delete this cart?</a>
                <a class="btn btn-danger ml-2" href="../include/cart.php?action=yes">Yes</a>
            <?php else : ?>
                <a class="btn btn-primary font-weight-bold mt-2" href="../include/cart.php?action=delete">Delete cart</a><br />
            <?php endif; ?>
        </div>
        <div class=" w-100 m-auto">
            <table class="table mt-3 mb-3 text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($_SESSION['cart'] as $descCart) :
                        $product = Product::getOneProductById($descCart->idProduct, $pdo);
                        $money += $product->priceProduct * $descCart->quantity;
                    ?>
                        <form method="post">
                            <tr>
                                <td><?= $no ?></td>
                                <td><img class="card-img-top" src="../include/images/<?= $product->imageProduct ?>" height="150" /></td>
                                <td><a><?= $product->nameProduct ?></a></td>
                                <td><a><?= number_format($product->priceProduct,0,',','.') ?></a></td>
                                <td><input class="form-control w-50 m-auto" required type="number" min="1" name="quantity" value="<?= $descCart->quantity ?>" /></td>
                                <td class="text-right">
                                    <button name="submit" value="delete" type="submit" class="btn btn-danger">Delete</button>
                                    <button name="submit" value="update" type="submit" class="btn btn-primary">Update</button>
                                    <input type="hidden" name="idProduct" value="<?= $product->idProduct ?>" />
                                </td>
                            </tr>
                        </form>
                    <?php $no++;
                    endforeach; ?>
                    <tr>
                        <td colspan="6" class="text-right border-0 font-weight-bold h4 text-danger"><?= $result ?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right font-weight-bold"><a class="text-dark">Tổng tiền: <span class="text-danger"><?= number_format($money, 0, ',', '.') ?> VNĐ</span></a></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="border-0 text-right font-weight-bold"><a class="btn btn-primary" href="../include/cart.php?action=pay&money=<?= $money ?>">Payment</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../include/footer.php' ?>
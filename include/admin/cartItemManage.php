<?php
spl_autoload_register(function ($class) {
    require "../class/{$class}.php";
});
include'../init.php';

$result = "";

//CHECK COI ID CART CÓ TỒN TẠI
if (!isset($_GET['idCart'])) {
    die("Cart invalid");
}

//XÓA CART THEO YÊU CẦU NGƯỜI DÙNG
if (isset($_GET['action']) && $_GET['action'] == "yes") {
    if (Cart::deleteCartDB($pdo, $_GET['idCart'])) {
        header('location: cartManage.php');
    }
}

//CẬP NHẬT LAI SỐ LƯỢNG HOẶC XÓA SẢN PHẨM THEO YÊU CẦU NGƯỜI DÙNG
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['submit'] == "delete") {
        //UPDATE TỔNG TIỀN SAU KHI XÓA CART ITEM
        if (Cart::updateMoneyAfterDeleteCartItem($pdo, $_GET['idCart'], $_POST['idProduct'], $_POST['quantity'])) {
            CartItem::deleteCartItemByIdProduct($pdo, $_POST['idProduct'], $_GET['idCart']);
            $result = "Delete successful";
        } else {
            $result = "Delete failed";
        }
    }
    if ($_POST['submit'] == "update") {
        //UPDATE TỔNG TIỀN SAU KHI UPDATE CART ITEM
        if (Cart::updateMoneyAfterUpdateCartItem($pdo, $_GET['idCart'], $_POST['idProduct'], $_POST['quantity'])) {
            CartItem::updateCartItemByIdProduct($pdo, $_POST['idProduct'], $_POST['quantity'], $_GET['idCart']);
            $result = "Update successful";
        } else {
            $result = "Update failed";
        }
    }
}

//LẤY CART ITEM ĐỂ HIỂN THỊ
if (isset($_GET['idCart'])) {
    $cartItems = CartItem::getAllCartItemByIdCart($pdo, $_GET['idCart']);
}
?>

<?php include '../admin/header.php'; ?>

<div class="container-fluid">
    <div class="w-100 m-auto pt-2">
        <? if (isset($_GET['action']) && $_GET['action'] == "delete") : ?>
            <a class="text-danger h4">Are you sure delete this cart?</a>
            <a class="btn btn-danger ml-2" href="../admin/cartItemManage.php?action=yes&idCart=<?= $_GET['idCart'] ?>">Yes</a>
        <?php else : ?>
            <a class="btn btn-primary font-weight-bold mt-2" href="../admin/cartItemManage.php?action=delete&idCart=<?= $_GET['idCart'] ?>">Delete cart</a><br />
        <?php endif; ?>
    </div>

    <?php if (empty($cartItems)) : ?>
        <div class="row m-2">
            <div class="col-12 text-center">
                <h3 class="text-danger">This cart hasn't product </h3>
            </div>
        </div>
    <?php else : ?>
        <div class="w-100 m-auto">
            <table class="table mt-3 mb-3 text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $money = 0;
                    foreach ($cartItems as $cartItem) :
                        $product = Product::getOneProductById($cartItem->idProduct, $pdo);
                        $money += $product->priceProduct * $cartItem->quantity;
                    ?>
                        <form method="post">
                            <tr>
                                <td><img src="../images/<?= $product->imageProduct ?>" width="140" height="110" /></td>
                                <td><a><?= $product->nameProduct ?></a></td>
                                <td><a><?= $product->priceProduct ?></a></td>
                                <td><input class="form-control w-50 m-auto" required type="number" min="1" name="quantity" value="<?= $cartItem->quantity ?>" /></td>
                                <td class="text-right">
                                    <button name="submit" value="delete" type="submit" class="btn btn-danger">Delete</button>
                                    <button name="submit" value="update" type="submit" class="btn btn-primary">Update</button>
                                    <input type="hidden" name="idProduct" value="<?= $product->idProduct ?>" />
                                </td>
                            </tr>
                        </form>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="5" class="text-right border-0 font-weight-bold h4 text-danger"><?= $result ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right font-weight-bold"><a class="text-dark">Tổng tiền: <span class="text-danger"><?= number_format($money, 0, ',', '.') ?> VNĐ</span></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../footer.php'; ?>
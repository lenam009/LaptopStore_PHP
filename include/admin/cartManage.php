<?php
spl_autoload_register(function ($class) {
    require "../class/{$class}.php";
});
include'../init.php';

$result = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //UPDATE TÌNH TRẠNG CỦA CART THEO YÊU CẦU NGƯỜI DÙNG
    if ($_POST['submit'] == 'update') {
        if (Cart::updateCartForState($pdo, $_POST['idCart'], $_POST["state" . $_POST['idCart']])) {
            $result = "Update successful";
        } else {
            $result = "Update failed!!!";
        }
    }
}

//NHẬN DỮ LIỆU TỪ CART
$carts = Cart::getAllCart($pdo);
?>

<?php include '../admin/header.php' ?>

<div class="w-100 m-auto ">
    <table class="table text-center">
        <thead class="thead-dark">
            <tr>
                <th>No.</th>
                <th>User</th>
                <th>Date</th>
                <th>Money</th>
                <th>State</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($carts as $cart) : ?>
                <form method="post">
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $cart->email ?></td>
                        <td><?= $cart->date ?></td>
                        <td><?= number_format($cart->money, 0, ',', '.') ?> VNĐ</td>
                        <td>
                            <?php if ($cart->state == "Wait for confirmation") : ?>
                                <input type="radio" checked id="wait<?= $cart->idCart ?>" name="state<?= $cart->idCart ?>" value="Wait for confirmation" />
                            <?php else : ?>
                                <input type="radio" id="wait<?= $cart->idCart ?>" name="state<?= $cart->idCart ?>" value="Wait for confirmation" />
                            <?php endif; ?>
                            <label for="wait<?= $cart->idCart ?>">Wait for confirmation</label>

                            <?php if ($cart->state == "Delivering") : ?>
                                <input class="ml-3" checked type="radio" id="deliver<?= $cart->idCart ?>" name="state<?= $cart->idCart ?>" value="Delivering" />
                            <?php else : ?>
                                <input class="ml-3" type="radio" id="deliver<?= $cart->idCart ?>" name="state<?= $cart->idCart ?>" value="Delivering" />
                            <?php endif; ?>
                            <label for="deliver<?= $cart->idCart ?>">Delivering</label>

                            <?php if ($cart->state == "Payed") : ?>
                                <input class="ml-3" checked type="radio" id="payed<?= $cart->idCart ?>" name="state<?= $cart->idCart ?>" value="Payed" />
                            <?php else : ?>
                                <input class="ml-3" type="radio" id="payed<?= $cart->idCart ?>" name="state<?= $cart->idCart ?>" value="Payed" />
                            <?php endif; ?>
                            <label for="payed<?= $cart->idCart ?>">Payed</label>
                        </td>
                        <td>
                            <button name="submit" value="update" type="submit" class="btn btn-danger">Update</button>
                            <a href="../admin/cartItemManage.php?idCart=<?= $cart->idCart ?>" class="btn btn-primary">Edit</a>
                            <input type="hidden" name="idCart" value="<?= $cart->idCart ?>" />
                        </td>
                    </tr>
                </form>
            <?php $no++;
            endforeach; ?>
            <tr>
                <td colspan="6">
                    <h3 class="text-danger mr-4 text-right"><?= $result ?></h3>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include '../footer.php' ?>
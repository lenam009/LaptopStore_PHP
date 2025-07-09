<?php
spl_autoload_register(function ($class) {
    require "../include/class/{$class}.php";
});
include'../include/init.php';


$carts = Cart::getCartByEmail($pdo, $_SESSION['user']);

if (isset($_GET['action']) && $_GET['action'] == 'yes' && isset($_GET['idCart'])) {
    if (Cart::deleteCartDB($pdo, $_GET['idCart'])) {
        header('location: purchaseHistory.php');
    }
}

?>

<?php include '../include/header.php'; ?>

<?php if (empty($carts)) : ?>
    <h3 class=" text-center m-2 font-weight-bold" style="color: orange;">You not buy product</h3>
<?php else : ?>
    <div class="container-fluid">
        <div class="w-75 m-auto">
            <table class="table text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Date</th>
                        <th>State</th>
                        <th>Total price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($carts as $cart) : ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $cart->date ?></td>
                            <td><?= $cart->state ?></td>
                            <td><?= number_format($cart->money, 0, ',', '.') ?>&nbsp;VND</td>
                            <td>
                                <?php if (
                                    isset($_GET['action']) && $_GET['action'] == 'delete'
                                    && isset($_GET['idCart']) && $_GET['idCart'] == $cart->idCart
                                ) : ?>
                                    <a class="h5 text-danger">Are you sure delete?&nbsp;</a>
                                    <a class="btn btn-danger" href="../include/purchaseHistory.php?action=yes&idCart=<?= $cart->idCart ?>">Yes</a>
                                    <a class="btn btn-primary" href="../include/purchaseHistory.php">Back</a>
                                <?php elseif ($cart->state == "Wait for confirmation") : ?>
                                    <a class="btn btn-danger" href="../include/purchaseHistory.php?action=delete&idCart=<?= $cart->idCart ?>">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php $no++;
                    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
<?php include '../include/footer.php'; ?>
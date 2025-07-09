<?php
class CartItem
{
    public $idCart;
    public $idProduct;
    public $quantity;

    public function addToCartItemDB($pdo)
    {
        $sql = "INSERT INTO `cartitem`(`idProduct`, `idCart`, `quantity`) VALUES (:idProduct,:idCart,:quantity)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':idProduct', $this->idProduct, PDO::PARAM_INT);
        $stmt->bindParam(':idCart', $this->idCart, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $this->quantity, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            return false;
        }
    }

    public static function getAllCartItemByIdCart($pdo, $idCart)
    {
        $sql = "SELECT * FROM `cartitem` where idCart=:idCart";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idCart', $idCart, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'CartItem');
            return $stmt->fetchAll();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function getOneCartItemByIdCartIdProduct($pdo, $idCart, $idProduct)
    {
        $sql = "SELECT * FROM `cartitem` where idCart=:idCart and idProduct=:idProduct";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idCart', $idCart, PDO::PARAM_INT);
        $stmt->bindParam(':idProduct', $idProduct, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'CartItem');
            return $stmt->fetch();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function deleteCartItemByIdProduct($pdo, $idProduct, $idCart)
    {
        $sql = "DELETE FROM `cartitem` WHERE idProduct=:idProduct and idCart=:idCart";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":idProduct", $idProduct, PDO::PARAM_INT);
        $stmt->bindParam(":idCart", $idCart, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }


    public static function updateCartItemByIdProduct($pdo, $idProduct, $quantity, $idCart)
    {
        $sql = "UPDATE `cartitem` SET `quantity`=:quantity WHERE idProduct=:idProduct and idCart=:idCart";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":idProduct", $idProduct, PDO::PARAM_INT);
        $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
        $stmt->bindParam(":idCart", $idCart, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }
}

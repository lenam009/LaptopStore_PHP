<?php
class Cart
{
    public $idCart;
    public $email;
    public $date; //SELECT * FROM `cart` WHERE DAY(date)=2;
    public $money;
    public $state;

    public static function getAllCart($pdo)
    {
        $sql = "SELECT * FROM `cart` order by idCart desc";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Cart');
            return $stmt->fetchAll();
        } else {
            var_dump($stmt->errorInfo());
            return false;
        }
    }

    public static function addCartDB($pdo, $email, $state)
    {
        $sql = "INSERT INTO `cart`( `email`,`state`) VALUES (:email,:state)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":state", $state, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $id = $pdo->lastInsertId();
            return $id;
        } else {
            var_dump($stmt->errorInfo());
            return -1;
        }
    }

    public static function addCartItemSession($idProduct, $pdo)
    {
        $product = Product::getOneProductById($idProduct, $pdo);
        if (isset($product)) {
            $idCol = [];
            if (count($_SESSION['cart']) != 0) {
                $idCol = array_column($_SESSION['cart'], 'idProduct');
            }
            if (in_array($idProduct, $idCol)) {
                $_SESSION['cart'][$idProduct]->quantity += 1;
            } else {
                $item = new CartItem();
                $item->idProduct = $idProduct;
                $item->quantity = 1;
                $_SESSION['cart'][$idProduct] = $item;
            }
            return true;
        }
        return false;
    }

    public static function getOneCartByIdCart($pdo, $idCart)
    {
        $sql = "SELECT * FROM `cart` WHERE idCart=:idCart";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":idCart", $idCart, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Cart');
            return $stmt->fetch();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function updateMoneyByIdCart($pdo, $money, $idCart)
    {
        $sql = "UPDATE `cart` SET `money`=:money WHERE idCart=:idCart";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":money", $money, PDO::PARAM_INT);
        $stmt->bindParam(":idCart", $idCart, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function updateMoneyAfterDeleteCartItem($pdo, $idCart, $idProduct, $quantity)
    {
        $product = Product::getOneProductById($idProduct, $pdo);
        $cart = Cart::getOneCartByIdCart($pdo, $idCart);
        $money = $cart->money - ($product->priceProduct * $quantity);

        $sql = "UPDATE `cart` SET `money`=:money WHERE idCart=:idCart";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":money", $money, PDO::PARAM_INT);
        $stmt->bindParam(":idCart", $idCart, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            return false;
        }
    }

    public static function updateMoneyAfterUpdateCartItem($pdo, $idCart, $idProduct, $quantity)
    {
        $product = Product::getOneProductById($idProduct, $pdo);
        $cart = Cart::getOneCartByIdCart($pdo, $idCart);
        $cartItem = CartItem::getOneCartItemByIdCartIdProduct($pdo, $idCart, $idProduct);

        $money = $cart->money - ($product->priceProduct * $cartItem->quantity) + ($product->priceProduct * $quantity);


        $sql = "UPDATE `cart` SET `money`=:money WHERE idCart=:idCart";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":money", $money, PDO::PARAM_INT);
        $stmt->bindParam(":idCart", $idCart, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            return false;
        }
    }

    public static function deleteCartDB($pdo, $idCart)
    {
        $sql = "DELETE FROM `cart` WHERE idCart=:idCart";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":idCart", $idCart, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function updateCartForState($pdo, $idCart, $state)
    {
        $sql = "UPDATE `cart` SET `state`=:state WHERE idCart=:idCart";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":idCart", $idCart, PDO::PARAM_INT);
        $stmt->bindParam(":state", $state, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }


    public static function delete($idProduct)
    {
        unset($_SESSION['cart'][$idProduct]);
    }

    public static function update($idProduct, $quantity)
    {
        $_SESSION['cart'][$idProduct]->quantity = $quantity;
    }

    public static function getCartByEmail($pdo, $email)
    {
        $sql = "SELECT * FROM `cart` WHERE email=:email order by idCart desc";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Cart');
            return $stmt->fetchAll();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

  
}

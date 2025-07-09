<?php
class Product
{
    public $idProduct;
    public $idTypeProduct;
    public $nameProduct;
    public $imageProduct;
    public $contentProduct;
    public $priceProduct;
    public $screen;
    public $CPU;
    public $RAM;
    public $hardDrive;

    public static function getAllProduct($pdo)
    {
        $sql = "SELECT * FROM `product`";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            // $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // $data = array();
            // foreach ($products as $product) {
            //     $data[] = new Product($product['id']);
            // }
            // return $data;
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
            $data = $stmt->fetchAll();
            //var_dump($data);
            return $data;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }
    public static function getProductByPage($pdo, $limit, $offset)
    {
        $sql = "SELECT * FROM `product` order by idProduct desc limit :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
            return $stmt->fetchAll();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }
    public static function getOneProductByWordBySort($word, $pdo, $limit, $offset, $action)
    {
        if ($action == "incPrice") {
            $sql = "SELECT * FROM `product` WHERE nameProduct LIKE :word or contentProduct LIKE :word
        or screen LIKE :word or CPU LIKE :word or RAM LIKE :word or hardDrive LIKE :word order by priceProduct asc, idProduct desc limit :limit OFFSET :offset";
        } else {
            $sql = "SELECT * FROM `product` WHERE nameProduct LIKE :word or contentProduct LIKE :word
            or screen LIKE :word or CPU LIKE :word or RAM LIKE :word or hardDrive LIKE :word order by priceProduct desc, idProduct desc limit :limit OFFSET :offset";
        }
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":word", $word, PDO::PARAM_STR);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
            return $stmt->fetchAll();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }
    public static function getOneProductByWord($word, $pdo, $limit, $offset)
    {
        $sql = "SELECT * FROM `product` WHERE nameProduct LIKE :word or contentProduct LIKE :word
        or screen LIKE :word or CPU LIKE :word or RAM LIKE :word or hardDrive LIKE :word order by idProduct desc limit :limit OFFSET :offset ";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":word", $word, PDO::PARAM_STR);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
            return $stmt->fetchAll();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function getCountProductByWord($word, $pdo)
    {
        $sql = "SELECT * FROM `product` WHERE nameProduct LIKE :word or contentProduct LIKE :word
        or screen LIKE :word or CPU LIKE :word or RAM LIKE :word or hardDrive LIKE :word order by idProduct";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":word", $word, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
            return count($stmt->fetchAll());
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }
    public static function getOneProductById($idProduct, $pdo)
    {
        $sql = "SELECT * FROM `product` where idProduct=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $idProduct, PDO::PARAM_INT);
        if ($stmt->execute()) {
            // $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // return new Product($products[0]['id']);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
            return $stmt->fetch();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function getOneProductByIdType($idTypeProduct, $pdo, $limit, $offset)
    {
        $sql = "SELECT * FROM `product` WHERE idTypeProduct=:idType 
        ORDER by idProduct desc LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":idType", $idTypeProduct, PDO::PARAM_INT);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        if ($stmt->execute()) {
            // $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // return new Product($products[0]['id']);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
            $data = $stmt->fetchAll();
            //var_dump($data);
            return $data;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function getOneProductByIdTypeBySort($idTypeProduct, $pdo, $limit, $offset, $action)
    {
        if ($action == "incPrice") {
            $sql = "SELECT * FROM `product` WHERE idTypeProduct=:idType 
            ORDER by priceProduct asc, idProduct desc LIMIT :limit OFFSET :offset ";
        } else {
            $sql = "SELECT * FROM `product` WHERE idTypeProduct=:idType 
            ORDER by priceProduct desc, idProduct desc LIMIT :limit OFFSET :offset ";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":idType", $idTypeProduct, PDO::PARAM_INT);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
            return $stmt->fetchAll();
        } else {
            var_dump($stmt->errorInfo());
            return null;
        }
    }



    public function addProduct($pdo)
    {
        $sql = "INSERT INTO `product`(`nameProduct`, `imageProduct`, `contentProduct`, `priceProduct`, `screen`, `CPU`, `RAM`, `hardDrive`,`idTypeProduct`) VALUES 
        (:nameProduct,:imageProduct,:contentProduct,:priceProduct,:screen,:CPU,:RAM,:hardDrive,:idTypeProduct)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":nameProduct", $this->nameProduct, PDO::PARAM_STR);
        $stmt->bindParam(":imageProduct", $this->imageProduct, PDO::PARAM_STR);
        $stmt->bindParam(":contentProduct", $this->contentProduct, PDO::PARAM_STR);
        $stmt->bindParam(":priceProduct", $this->priceProduct, PDO::PARAM_INT);
        $stmt->bindParam(":screen", $this->screen, PDO::PARAM_STR);
        $stmt->bindParam(":CPU", $this->CPU, PDO::PARAM_STR);
        $stmt->bindParam(":RAM", $this->RAM, PDO::PARAM_STR);
        $stmt->bindParam(":hardDrive", $this->hardDrive, PDO::PARAM_STR);
        $stmt->bindParam(":idTypeProduct", $this->idTypeProduct, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $id = $pdo->lastInsertId();
            return $id;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }
    public function removeProduct($pdo)
    {

        $sql = "DELETE FROM `product` WHERE idProduct=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id", $this->idProduct, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            //var_dump($stmt->errorInfo());
            return false;
        }
    }
    public function updateProduct($pdo)
    {
        $sql = "UPDATE `product` SET `nameProduct`=:name,
        `imageProduct`=:image,`contentProduct`=:content,
        `priceProduct`=:price,`screen`=:screen,
        `CPU`=:CPU,`RAM`=:RAM,`hardDrive`=:hardDrive,`idTypeProduct`=:idType WHERE idProduct=:id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":name", $this->nameProduct, PDO::PARAM_STR);
        $stmt->bindParam(":image", $this->imageProduct, PDO::PARAM_STR);
        $stmt->bindParam(":content", $this->contentProduct, PDO::PARAM_STR);
        $stmt->bindParam(":price", $this->priceProduct, PDO::PARAM_INT);
        $stmt->bindParam(":idType", $this->idTypeProduct, PDO::PARAM_INT);
        $stmt->bindParam(":screen", $this->screen, PDO::PARAM_STR);
        $stmt->bindParam(":CPU", $this->CPU, PDO::PARAM_STR);
        $stmt->bindParam(":RAM", $this->RAM, PDO::PARAM_STR);
        $stmt->bindParam(":hardDrive", $this->hardDrive, PDO::PARAM_STR);
        $stmt->bindParam(":id", $this->idProduct, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            return false;
        }
    }
    public static function getProductBySort($pdo, $sort, $limit, $offset)
    {
        if ($sort == "decPrice") {
            $sql = "SELECT * FROM `product` ORDER BY priceProduct DESC, idProduct desc  limit :limit OFFSET :offset";
        } else {
            $sql = "SELECT * FROM `product` ORDER BY priceProduct ASC, idProduct desc  limit :limit OFFSET :offset";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
            $data = $stmt->fetchAll();
            return $data;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function getCountByIdType($pdo, $idTypeProduct)
    {
        $sql = "select count(*) as 'count' from product where idTypeProduct=:idType";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":idType", $idTypeProduct, PDO::PARAM_INT);
        if ($stmt->execute()) {
            // $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // return new Product($products[0]['id']);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $data = $stmt->fetch();
            //var_dump($data);
            return $data['count'];
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }
    public static function getCountAllProduct($pdo)
    {
        $data = Product::getAllProduct($pdo);
        return count($data);
    }
}

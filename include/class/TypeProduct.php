<?php
class TypeProduct
{
    public $idTypeProduct;
    public $nameTypeProduct;
    public $imageTypeProduct;

    public static function getAllProduct($pdo)
    {
        $sql = "SELECT * FROM `typeproduct`";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'TypeProduct');
            return $stmt->fetchAll();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function getTypeProductByIdProduct($idProduct, $pdo)
    {
        $sql = "SELECT t.* FROM `typeproduct` t JOIN pro_type p 
        on t.idTypeProduct=p.idTypePro where p.idPro=:idPro";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":idPro", $idProduct, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'TypeProduct');
            $data = $stmt->fetchAll();
            //var_dump($data);
            return $data;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function getTypeProductByIdType($pdo, $idType)
    {
        $sql = "SELECT * FROM `typeproduct` WHERE idTypeProduct=:idType";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":idType", $idType, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'TypeProduct');
            return $stmt->fetch();
        }
    }
}

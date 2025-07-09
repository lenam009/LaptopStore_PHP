<?php
class Pro_Type
{
    public $idPro;
    public $idTypePro;

    public static function addProType($pdo, $idProduct, $idType)
    {
        $sql = "INSERT INTO `pro_type`(`idPro`, `idTypePro`) VALUES (:idPro,:idType)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":idPro", $idProduct, PDO::PARAM_INT);
        $stmt->bindParam(":idType", $idType, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            return false;
        }
    }

    public static function deleteProTypeByIdProduct($pdo, $idProduct)
    {
        $sql = "DELETE FROM `pro_type` WHERE idPro=:idPro";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":idPro", $idProduct, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }
}

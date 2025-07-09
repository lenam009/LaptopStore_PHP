<?php
class Rule
{
    public $idRule;
    public $nameRule;

    public static function getAllRule($pdo)
    {
        $sql = "SELECT * FROM `rule` ";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Rule');
            return $stmt->fetchAll();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    
}

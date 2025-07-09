<?php

use User as GlobalUser;

class User
{
    public $email;
    public $password;
    public $idRule;
    public $token;
    public $dateresetpass;

    public static function getAllUsers($pdo)
    {
        $sql = "SELECT * FROM `user` order by email desc";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
            return $stmt->fetchAll();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    }

    public static function getOneUserByEmail($pdo, $email)
    {
        $sql = "SELECT * FROM `user` WHERE email=:email";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
            return $stmt->fetch();
        } else {
            var_dump($stmt->errorInfo());
            return null;
        }
    }

    public static function addUser($pdo, $email, $password, $rule)
    {
        $sql = "INSERT INTO `user`(`email`, `password`,`idRule`) VALUES (:email,:password,:rule)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":password", password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindParam(":rule", $rule, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            //var_dump($stmt->errorInfo());
            return false;
        }
    }

    public static function checkLogin($pdo, $email, $password)
    {
        $user = User::getOneUserByEmail($pdo, $email);
        if (empty($user))
            return false;
        return password_verify($password, $user->password);
    }

    public static function checkAdmin($email, $pdo)
    {
        $user = User::getOneUserByEmail($pdo, $email);
        return $user->idRule == 1;
        // if ($user->idRule == 1)
        //     return true;
        // return false;
        // $sql = "SELECT * FROM `user` WHERE email=:email and idRule=1";
        // $stmt = $pdo->prepare($sql);

        // $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        // if ($stmt->execute()) {
        //     $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
        //     $data = $stmt->fetchAll();
        //     return !empty($data);
        // } else {
        //     var_dump($stmt->errorInfo());
        //     exit;
        // }
    }

    public function deleteUser($pdo)
    {
        $sql = "DELETE FROM `user` WHERE email=:email";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            //var_dump($stmt->errorInfo());
            return false;
        }
    }


    public function updateUser($pdo)
    {
        $sql = "UPDATE `user` SET `password`=:password,`idRule`=:idRule WHERE email=:email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":password", password_hash($this->password, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->bindParam("idRule", $this->idRule, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUserOnlyRole($pdo)
    {
        $sql = "UPDATE `user` SET `idRule`=:idRule WHERE email=:email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->bindParam(":idRule", $this->idRule, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function updatePasswordByEmail($pdo, $password, $email)
    {
        $sql = "UPDATE `user` SET PASSWORD=:password where email=:email";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":password", password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function updateDateResetPasswordAndToken($pdo, $email, $token)
    {
        $sql = "UPDATE `user` SET dateresetpass=:date,token=:token where email=:email";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $stmt->bindParam(":date", date("Y-m-d H:i:s", time()), PDO::PARAM_STR);
        $stmt->bindParam(":token", $token, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

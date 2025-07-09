<?php
class Database
{
    protected $db_host;
    protected $db_name;
    protected $db_user;
    protected $db_pass;

    public function __construct($db_host, $db_name, $db_user, $db_pass)
    {
        $this->db_host = $db_host;
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
    }
    public function getPDOConnect()
    {
        $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8";

        try {
            $pdo = new PDO($dsn, $this->db_user, $this->db_pass);
            return $pdo;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            exit;
        }
    }
}

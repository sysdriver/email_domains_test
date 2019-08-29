<?php

class Db
{
    private $dbh;
    public $stmt;

    public function __construct($host,$username,$password,$database,$port=3306)
    {
        try {
            $dbh = new PDO('mysql:host=' . $host . ':' . $port . ';dbname=' . $database, $username, $password);
            $this->dbh = $dbh;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
        return $this;
    }

    public function bind($pos, $value, $type = null) {

        if( is_null($type) ) {
            switch( true ) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($pos, $value, $type);
        return $this;
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultset() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single($style = PDO::FETCH_BOTH) {
        $this->execute();
        return $this->stmt->fetch($style);
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function rowCount()
    {
        return $this->dbh->rowCount();
    }

}

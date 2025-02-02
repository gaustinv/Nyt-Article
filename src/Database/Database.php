<?php
namespace App\Database;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
    //  print_r(realpath('../../../db.sqlite')); exit();
        try {
            $this->connection = new PDO('sqlite:../../../database.sqlite');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Returns the singleton instance of the Database class.
     *
     * If no instance exists, a new one is created.
     *
     * @return Database The singleton instance of the Database class.
     */

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }


    public function getConnection() {
        return $this->connection;
    }
}

<?php
class Database {

public static $dataBaseConnection;

    public static function createConnection() {
        if (!isset(Database::$dataBaseConnection)) {
            Database::$dataBaseConnection = new mysqli("localhost", "root", "Gangulel123#", "campushub", "3306");

}
    }
    
public static function iud($query) {
        Database::createConnection();
        Database::$dataBaseConnection->query($query);
}

public static function search($query) {
        Database::createConnection();
        $resultset = Database::$dataBaseConnection->query($query);
        return $resultset;
        }

    }

?>

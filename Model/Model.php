<?php
abstract class Model {
    private static $db;

    private static function setdb() {
        try {
            self::$db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    protected function getDb() {
        if (self::$db == null) {
            self::setdb();
        }
        return self::$db;
    }
}
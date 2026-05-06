<?php
namespace Database;

use CodeIgniter\Database\Config;

class Connection {
    private static $db = null;

    /**
     * Retourne une instance de connexion à la base de données
     * Pattern Singleton pour éviter multiples connexions
     */
    public static function dbconnect()
    {
        if (self::$db === null) {
            self::$db = Config::connect();
        }
        return self::$db;
    }
}

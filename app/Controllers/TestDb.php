<?php

namespace App\Controllers;

use CodeIgniter\Database\Config;

class TestDb extends BaseController
{
    public function index()
    {
        $db = Config::connect();
        
        try {
            $result = $db->query("SELECT 1 as test")->getResult();
            return "✅ Connexion réussie !";
        } catch (\Exception $e) {
            return "❌ Erreur : " . $e->getMessage();
        }
    }
}
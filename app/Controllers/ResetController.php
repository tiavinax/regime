<?php

namespace App\Controllers;

class ResetController extends BaseController
{
    /**
     * Affiche la page de réinitialisation
     * URL: GET /admin/reset
     */
    public function index()
    {
        // Vérifier si l'utilisateur est admin
        if (!session()->has('user_id') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Accès réservé aux administrateurs');
        }
        
        return view('admin/reset');
    }
    
    /**
     * Teste si le fichier existe
     * URL: GET /admin/reset/test
     */
    public function testFile()
    {
        // Vérifier admin
        if (!session()->has('user_id') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }
        
        $sqlFile = ROOTPATH . 'script_SQL_reset.sql';
        
        echo "<h1>Test fichier SQL</h1>";
        echo "Chemin: " . $sqlFile . "<br>";
        echo "Existe: " . (file_exists($sqlFile) ? 'OUI' : 'NON') . "<br>";
        
        if (file_exists($sqlFile)) {
            $content = file_get_contents($sqlFile);
            echo "Taille: " . strlen($content) . " octets<br>";
            echo "Premiers caractères: " . htmlspecialchars(substr($content, 0, 200)) . "<br>";
        }
        
        die();
    }
    
    /**
     * Exécute la réinitialisation complète
     * URL: POST /admin/reset/execute
     */
    public function execute()
{
    // Vérifier que c'est une requête POST (insensible à la casse)
    if (strtolower($this->request->getMethod()) !== 'post') {
        return redirect()->to('/admin/reset')->with('error', 'Méthode non autorisée');
    }
    
    // Vérifier si l'utilisateur est admin
    if (!session()->has('user_id') || session()->get('role') !== 'admin') {
        return redirect()->to('/login')->with('error', 'Accès réservé aux administrateurs');
    }
    
    $sqlFile = ROOTPATH . 'script_SQL_reset.sql';
    
    if (!file_exists($sqlFile)) {
        return redirect()->back()->with('error', 'Fichier SQL non trouvé');
    }
    
    $db = \Config\Database::connect();
    
    try {
        $sql = file_get_contents($sqlFile);
        
        if (empty($sql)) {
            return redirect()->back()->with('error', 'Le fichier SQL est vide');
        }
        
        $queries = explode(';', $sql);
        $executed = 0;
        
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query) && strpos($query, '--') !== 0) {
                $db->query($query);
                $executed++;
            }
        }
        
        session()->destroy();
        
        return redirect()->to('/login')->with('success', '✅ Base de données réinitialisée avec succès !');
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
    }
}
}
<?php
// Fichier: public/create_admin.php
// Accédez à: http://localhost:8080/create_admin.php

require_once '../app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/Common.php';
require_once $paths->systemDirectory . '/Autoloader.php';

use CodeIgniter\Autoloader\Autoloader;
$autoloader = new Autoloader();
$autoloader->initialize($paths->appDirectory . 'Config/Autoload.php');
$autoloader->register();

use CodeIgniter\Database\Config;

$db = Config::connect();

// Mot de passe tsotra - tsy misy hash
$plainPassword = 'admin123';

echo "Mot de passe utilisé: " . $plainPassword . "<br><br>";

// Supprimer les anciens
$db->table('utilisateur')->where('email', 'jean.dupont@admin.com')->delete();
$db->table('utilisateur')->where('email', 'marie.martin@admin.com')->delete();
$db->table('utilisateur')->where('email', 'admin@exemple.com')->delete();

// Créer les admins avec mot de passe tsotra
$admins = [
    [
        'nom' => 'Administrateur',
        'email' => 'admin@exemple.com',
        'password' => $plainPassword,
        'genre' => 'homme',
        'date_naissance' => '1990-01-01',
        'role' => 'admin',
        'is_gold' => 0
    ],
    [
        'nom' => 'Jean Dupont',
        'email' => 'jean.dupont@admin.com',
        'password' => $plainPassword,
        'genre' => 'homme',
        'date_naissance' => '1985-03-15',
        'role' => 'admin',
        'is_gold' => 0
    ],
    [
        'nom' => 'Marie Martin',
        'email' => 'marie.martin@admin.com',
        'password' => $plainPassword,
        'genre' => 'femme',
        'date_naissance' => '1990-07-22',
        'role' => 'admin',
        'is_gold' => 0
    ]
];

foreach ($admins as $admin) {
    $db->table('utilisateur')->insert($admin);
    echo "✅ Créé: " . $admin['email'] . " (mot de passe: " . $plainPassword . ")<br>";
}

echo "<br><hr><br>";
echo "<strong>Tous les comptes admin:</strong><br>";

$results = $db->table('utilisateur')->where('role', 'admin')->get()->getResultArray();
foreach ($results as $user) {
    echo "📧 " . $user['email'] . " | Nom: " . $user['nom'] . " | Mot de passe: " . $user['password'] . "<br>";
}

echo "<br><a href='/admin/login'>Aller à la page de connexion admin</a>";
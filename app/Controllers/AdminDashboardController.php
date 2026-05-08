<?php

namespace App\Controllers;

class AdminDashboardController extends BaseController
{
    public function index()
    {
        // Vérifier si admin connecté
        if (!session()->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/login');
        }

        echo "<h1>Bienvenue dans l'administration</h1>";
        echo "<p>Connecté en tant que : " . session()->get('admin_nom') . "</p>";
        echo "<a href='/admin/logout'>Déconnexion</a>";
    }
}
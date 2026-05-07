<?php

namespace Config;

use App\Filters\AuthFilter;
use App\Filters\RoleFilter;
use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    // Déclaration des alias
    public array $aliases = [
        'csrf' => \CodeIgniter\Filters\CSRF::class,
        'auth' => AuthFilter::class,
        'role' => RoleFilter::class,
    ];
    // Filtres globaux (toutes les routes)
    public array $globals = [
        'before' => [
            'csrf',
        ],
    ];
    // Filtres par méthode HTTP (optionnel)
    public array $methods = [];
    // Filtres par route spécifique (optionnel — voir étape 4)
    public array $filters = [];
}

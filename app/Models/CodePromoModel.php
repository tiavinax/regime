<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePromoModel extends Model
{
    protected $table = 'codes_promo';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'code',
        'valeur',
        'type',
        'utilisations_max',
        'utilisations_actuelles',
        'date_expiration',
        'est_actif'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'date_creation';
    protected $updatedField = false;
}
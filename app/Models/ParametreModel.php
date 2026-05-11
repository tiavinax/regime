<?php

namespace App\Models;

use CodeIgniter\Model;

class ParametreModel extends Model
{
    protected $table = 'parametres';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'cle',
        'valeur',
        'description',
        'type'
    ];
    
    protected $useTimestamps = false;
    
    /**
     * Récupère la valeur d'un paramètre par sa clé
     */
    public function getValue($key, $default = null)
    {
        $param = $this->where('cle', $key)->first();
        return $param ? $param['valeur'] : $default;
    }
    
    /**
     * Met à jour ou crée un paramètre
     */
    public function setValue($key, $value)
    {
        $param = $this->where('cle', $key)->first();
        if ($param) {
            return $this->update($param['id'], ['valeur' => $value]);
        }
        return $this->insert(['cle' => $key, 'valeur' => $value]);
    }
}
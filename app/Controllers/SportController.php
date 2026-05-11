<?php

namespace App\Controllers;

use App\Models\ActiviteSportiveModel;

class SportController extends BaseController
{
    protected $activiteModel;

    public function __construct()
    {
        $this->activiteModel = new ActiviteSportiveModel();
    }

    /**
     * Affiche la liste des activités sportives
     */
    public function index()
    {
        $activites = $this->activiteModel->findAll();
        
        return view('sports/index', [
            'activites' => $activites
        ]);
    }

    /**
     * Affiche les détails d'une activité
     */
    public function show($id)
    {
        $activite = $this->activiteModel->find($id);
        
        if (!$activite) {
            return redirect()->to('/sports')->with('error', 'Activité non trouvée');
        }
        
        return view('sports/show', [
            'activite' => $activite
        ]);
    }
}
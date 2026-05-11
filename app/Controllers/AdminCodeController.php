<?php

namespace App\Controllers;

use App\Models\CodePromoModel;

class AdminCodeController extends BaseController
{
    protected $codeModel;

    public function __construct()
    {
        $this->codeModel = new CodePromoModel();
        helper('form');
    }

    private function checkAdmin()
    {
        if (!session()->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/login')->with('error', 'Veuillez vous connecter');
        }
        return null;
    }

    public function index()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $data = [
            'codes' => $this->codeModel->orderBy('id', 'DESC')->findAll(),
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/codes/index', $data);
    }

    public function create()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        return view('admin/codes/create', ['admin_nom' => session()->get('admin_nom')]);
    }

    public function store()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $rules = [
            'code' => 'required|min_length[3]|is_unique[codes_promo.code]',
            'valeur' => 'required|numeric|greater_than[0]',
            'type' => 'required|in_list[percentage,fixed]',
            'utilisations_max' => 'permit_empty|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->codeModel->insert([
            'code' => strtoupper($this->request->getPost('code')),
            'valeur' => $this->request->getPost('valeur'),
            'type' => $this->request->getPost('type'),
            'utilisations_max' => $this->request->getPost('utilisations_max') ?: null,
            'date_expiration' => $this->request->getPost('date_expiration') ?: null,
            'est_actif' => 1
        ]);

        return redirect()->to('/admin/codes')->with('success', 'Code promo créé');
    }

    public function edit($id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $code = $this->codeModel->find($id);
        if (!$code) {
            return redirect()->to('/admin/codes')->with('error', 'Code non trouvé');
        }

        $data = [
            'code' => $code,
            'admin_nom' => session()->get('admin_nom')
        ];
        return view('admin/codes/edit', $data);
    }

    public function update($id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $rules = [
            'code' => 'required|min_length[3]',
            'valeur' => 'required|numeric|greater_than[0]',
            'type' => 'required|in_list[percentage,fixed]',
            'est_actif' => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->codeModel->update($id, [
            'code' => strtoupper($this->request->getPost('code')),
            'valeur' => $this->request->getPost('valeur'),
            'type' => $this->request->getPost('type'),
            'utilisations_max' => $this->request->getPost('utilisations_max') ?: null,
            'date_expiration' => $this->request->getPost('date_expiration') ?: null,
            'est_actif' => $this->request->getPost('est_actif')
        ]);

        return redirect()->to('/admin/codes')->with('success', 'Code promo mis à jour');
    }

    public function delete($id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $this->codeModel->delete($id);
        return redirect()->to('/admin/codes')->with('success', 'Code promo supprimé');
    }

    public function toggle($id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $code = $this->codeModel->find($id);
        if ($code) {
            $newStatus = $code['est_actif'] ? 0 : 1;
            $this->codeModel->update($id, ['est_actif' => $newStatus]);
            return redirect()->back()->with('success', 'Statut modifié');
        }
        return redirect()->back()->with('error', 'Code non trouvé');
    }
}
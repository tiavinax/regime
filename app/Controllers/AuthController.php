<?php 
namespace App\Controllers;

class AuthController extends BaseController {

    public function form() {
        return view('login');
    }
}

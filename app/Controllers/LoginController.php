<?php

namespace App\Controllers;

class LoginController extends BaseController {
    
    public function form() {
        return view("login");
    }
}

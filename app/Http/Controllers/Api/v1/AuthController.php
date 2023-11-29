<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Service\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        $auth = new AuthService();
        return $auth->login($request);
    }

    public function register(Request $request) {
        $auth = new AuthService();
        return $auth->register($request);
    }

    public function logout() {
        $auth = new AuthService();
        return $auth->logout();
    }
}

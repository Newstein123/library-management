<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAccountController extends AccountController
{
    public function __construct() {
        parent::__construct('admin');
    }
}

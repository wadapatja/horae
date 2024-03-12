<?php

namespace App\Http\Controllers\web\register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index() {
        return view('pages/register/account');
    }
}

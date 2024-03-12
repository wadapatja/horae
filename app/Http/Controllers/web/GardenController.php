<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GardenController extends Controller
{
    public function index() {
        return view('pages/garden/map');
    }
}

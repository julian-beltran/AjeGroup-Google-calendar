<?php

namespace App\Http\Controllers\gestion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModeloGestionController extends Controller
{
    public function index()
    {
        return view ('modelo_gestion/dashboard_gestion');
    }
}

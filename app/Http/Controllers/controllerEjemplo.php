<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class controllerEjemplo extends Controller
{
    public function miMetodo()
    {
        return response()->json(['mensaje' => 'Hola desde la api']);
    }
}

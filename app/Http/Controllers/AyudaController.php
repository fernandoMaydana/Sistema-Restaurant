<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AyudaController extends Controller
{
    /**
     * Muestra la vista interactiva del Manual de Usuario y Centro de Ayuda.
     */
    public function index()
    {
        return view('ayuda.index');
    }
}

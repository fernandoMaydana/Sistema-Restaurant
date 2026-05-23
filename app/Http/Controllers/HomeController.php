<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
{
    $user = auth()->user();

    // Redirección según el rol de la base de datos
    if ($user->role == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role == 'cajero') {
        return redirect()->route('cajero.dashboard');
    } elseif ($user->role == 'mesero') {
        return redirect()->route('mesero.salon');
    }

    // Si por alguna razón no tiene rol, lo deslogueamos
    auth()->logout();
    return redirect('/login');
}
}

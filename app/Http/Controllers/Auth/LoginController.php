<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta ha sido deshabilitada. Contacta al administrador.'
            ]);
        }

        // Registrar inicio de sesión para meseros y cajeros
        if (in_array($user->role, ['mesero', 'cajero'])) {
            \App\Models\SesionTrabajo::create([
                'user_id' => $user->id,
                'fecha_entrada' => now(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user && in_array($user->role, ['mesero', 'cajero'])) {
            // Buscar la última sesión de trabajo abierta para este usuario
            $ultimaSesion = \App\Models\SesionTrabajo::where('user_id', $user->id)
                ->whereNull('fecha_salida')
                ->latest()
                ->first();
            if ($ultimaSesion) {
                $ultimaSesion->update([
                    'fecha_salida' => now()
                ]);
            }
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new \Illuminate\Http\JsonResponse([], 204)
            : redirect('/');
    }
}

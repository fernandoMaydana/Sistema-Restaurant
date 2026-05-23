<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,cajero,mesero'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado.');
    }

    public function edit(string $id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'role' => 'required|in:admin,cajero,mesero'
        ]);

        $data = $request->except(['password']);
        $data['is_active'] = $request->has('is_active');
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(string $id)
    {
        $usuario = User::findOrFail($id);
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')->with('error', 'No puedes eliminarte a ti mismo.');
        }
        $usuario->delete();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado.');
    }
}

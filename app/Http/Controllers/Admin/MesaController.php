<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index()
    {
        $mesas = \App\Models\Mesa::all();
        return view('admin.mesas.index', compact('mesas'));
    }

    public function create()
    {
        return view('admin.mesas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer|unique:mesas,numero',
            'capacidad' => 'required|integer|min:1',
            'es_para_llevar' => 'nullable|boolean'
        ]);
        
        $data = $request->all();
        $data['estado'] = 'libre';
        $data['es_para_llevar'] = $request->has('es_para_llevar');
        \App\Models\Mesa::create($data);

        return redirect()->route('admin.mesas.index')->with('success', 'Mesa creada.');
    }

    public function edit(string $id)
    {
        $mesa = \App\Models\Mesa::findOrFail($id);
        return view('admin.mesas.edit', compact('mesa'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'numero' => 'required|integer|unique:mesas,numero,' . $id,
            'capacidad' => 'required|integer|min:1',
            'estado' => 'required|in:libre,ocupada',
            'es_para_llevar' => 'nullable|boolean'
        ]);
        
        $mesa = \App\Models\Mesa::findOrFail($id);
        $data = $request->all();
        $data['es_para_llevar'] = $request->has('es_para_llevar');
        $mesa->update($data);

        return redirect()->route('admin.mesas.index')->with('success', 'Mesa actualizada.');
    }

    public function destroy(string $id)
    {
        $mesa = \App\Models\Mesa::findOrFail($id);
        if ($mesa->pedidos()->where('estado', '!=', 'pagado')->count() > 0) {
            return redirect()->route('admin.mesas.index')->with('error', 'No se puede eliminar una mesa con pedidos activos.');
        }
        $mesa->delete();
        return redirect()->route('admin.mesas.index')->with('success', 'Mesa eliminada.');
    }
}

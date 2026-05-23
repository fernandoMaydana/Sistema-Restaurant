<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = \App\Models\Categoria::all();
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255|unique:categorias,nombre']);
        \App\Models\Categoria::create($request->all());
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada con éxito.');
    }

    public function edit(string $id)
    {
        $categoria = \App\Models\Categoria::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string|max:255|unique:categorias,nombre,' . $id]);
        $categoria = \App\Models\Categoria::findOrFail($id);
        $categoria->update($request->all());
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(string $id)
    {
        $categoria = \App\Models\Categoria::findOrFail($id);
        if ($categoria->productos()->count() > 0) {
            return redirect()->route('admin.categorias.index')->with('error', 'No se puede eliminar porque tiene productos asociados.');
        }
        $categoria->delete();
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada.');
    }
}

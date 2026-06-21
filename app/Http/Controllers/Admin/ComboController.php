<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\ComboItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ComboController extends Controller
{
    public function index(Request $request)
    {
        $query = Combo::with('items.producto');

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        $combos = $query->get();
        return view('admin.combos.index', compact('combos'));
    }

    public function create()
    {
        $productos = Producto::where('disponible', true)->orderBy('nombre')->get();
        return view('admin.combos.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:fijo,condicionado',
            'precio_total' => 'nullable|numeric|min:0|required_if:tipo,fijo',
            'imagen' => 'nullable|image|max:2048',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['nombre', 'descripcion', 'tipo', 'precio_total']);
            $data['activo'] = $request->has('activo');

            if ($request->hasFile('imagen')) {
                $data['imagen'] = $request->file('imagen')->store('combos', 'public');
            }

            if ($data['tipo'] === 'condicionado') {
                $data['precio_total'] = null; // Se sumará según items
            }

            $combo = Combo::create($data);

            foreach ($request->input('items') as $itemData) {
                $esGratuito = isset($itemData['es_gratuito']) && ($itemData['es_gratuito'] == 1 || $itemData['es_gratuito'] == 'on' || $itemData['es_gratuito'] == true);
                
                ComboItem::create([
                    'combo_id' => $combo->id,
                    'producto_id' => $itemData['producto_id'],
                    'cantidad' => $itemData['cantidad'],
                    'es_gratuito' => $esGratuito,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.combos.index')->with('success', 'Combo creado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ocurrió un error al guardar el combo: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $combo = Combo::with('items.producto')->findOrFail($id);
        $productos = Producto::where('disponible', true)->orderBy('nombre')->get();
        return view('admin.combos.edit', compact('combo', 'productos'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:fijo,condicionado',
            'precio_total' => 'nullable|numeric|min:0|required_if:tipo,fijo',
            'imagen' => 'nullable|image|max:2048',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        $combo = Combo::findOrFail($id);

        DB::beginTransaction();
        try {
            $data = $request->only(['nombre', 'descripcion', 'tipo', 'precio_total']);
            $data['activo'] = $request->has('activo');

            if ($request->hasFile('imagen')) {
                if ($combo->imagen && Storage::disk('public')->exists($combo->imagen)) {
                    Storage::disk('public')->delete($combo->imagen);
                }
                $data['imagen'] = $request->file('imagen')->store('combos', 'public');
            }

            if ($data['tipo'] === 'condicionado') {
                $data['precio_total'] = null;
            }

            $combo->update($data);

            // Eliminar items antiguos y recrear
            $combo->items()->delete();

            foreach ($request->input('items') as $itemData) {
                $esGratuito = isset($itemData['es_gratuito']) && ($itemData['es_gratuito'] == 1 || $itemData['es_gratuito'] == 'on' || $itemData['es_gratuito'] == true);

                ComboItem::create([
                    'combo_id' => $combo->id,
                    'producto_id' => $itemData['producto_id'],
                    'cantidad' => $itemData['cantidad'],
                    'es_gratuito' => $esGratuito,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.combos.index')->with('success', 'Combo actualizado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el combo: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $combo = Combo::findOrFail($id);
        
        DB::beginTransaction();
        try {
            if ($combo->imagen && Storage::disk('public')->exists($combo->imagen)) {
                Storage::disk('public')->delete($combo->imagen);
            }
            
            $combo->delete();
            
            DB::commit();
            return redirect()->route('admin.combos.index')->with('success', 'Combo eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.combos.index')->with('error', 'No se pudo eliminar el combo: ' . $e->getMessage());
        }
    }

    public function toggle(string $id)
    {
        $combo = Combo::findOrFail($id);
        $combo->activo = !$combo->activo;
        $combo->save();

        return response()->json([
            'success' => true,
            'activo' => $combo->activo,
            'message' => 'Estado del combo actualizado.'
        ]);
    }
}

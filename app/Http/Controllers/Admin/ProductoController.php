<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Producto::with('categoria');
        
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        $productos = $query->get();
        $categorias = \App\Models\Categoria::all();
        
        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = \App\Models\Categoria::all();
        return view('admin.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id'    => 'required|exists:categorias,id',
            'nombre'          => 'required|string|max:255',
            'precio'          => 'required|numeric|min:0',
            'precio_nombre'   => 'nullable|string|max:50',
            'precio_2'        => 'nullable|numeric|min:0',
            'precio_2_nombre' => 'nullable|string|max:50',
            'precio_3'        => 'nullable|numeric|min:0',
            'precio_3_nombre' => 'nullable|string|max:50',
            'costo'           => 'nullable|numeric|min:0',
            'costo_2'         => 'nullable|numeric|min:0',
            'costo_3'         => 'nullable|numeric|min:0',
            'disponible'      => 'nullable',
            'imagen'          => 'nullable|image|max:2048',
            'usa_inventario'  => 'nullable',
            'stock'           => 'nullable|integer|min:0'
        ]);
        
        $data = $request->all();
        $data['disponible'] = $request->has('disponible');
        $data['usa_inventario'] = $request->has('usa_inventario');
        $data['stock'] = $request->input('stock', 0);

        if (!$request->has('toggle_precio_2')) {
            $data['precio_2'] = null;
            $data['precio_2_nombre'] = null;
            $data['costo_2'] = 0;
        }
        if (!$request->has('toggle_precio_3')) {
            $data['precio_3'] = null;
            $data['precio_3_nombre'] = null;
            $data['costo_3'] = 0;
        }
        
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        
        $producto = \App\Models\Producto::create($data);

        if ($producto->usa_inventario && $producto->stock > 0) {
            \App\Models\Compra::create([
                'producto_id' => $producto->id,
                'cantidad' => $producto->stock,
            ]);
        }
        
        return redirect()->route('admin.productos.index')->with('success', 'Producto creado.');
    }

    public function edit(string $id)
    {
        $producto = \App\Models\Producto::findOrFail($id);
        $categorias = \App\Models\Categoria::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'categoria_id'    => 'required|exists:categorias,id',
            'nombre'          => 'required|string|max:255',
            'precio'          => 'required|numeric|min:0',
            'precio_nombre'   => 'nullable|string|max:50',
            'precio_2'        => 'nullable|numeric|min:0',
            'precio_2_nombre' => 'nullable|string|max:50',
            'precio_3'        => 'nullable|numeric|min:0',
            'precio_3_nombre' => 'nullable|string|max:50',
            'costo'           => 'nullable|numeric|min:0',
            'costo_2'         => 'nullable|numeric|min:0',
            'costo_3'         => 'nullable|numeric|min:0',
            'disponible'      => 'nullable',
            'imagen'          => 'nullable|image|max:2048',
            'usa_inventario'  => 'nullable',
            'stock'           => 'nullable|integer|min:0'
        ]);
        
        $producto = \App\Models\Producto::findOrFail($id);
        $old_stock = $producto->stock;
        
        $data = $request->all();
        $data['disponible'] = $request->has('disponible');
        $data['usa_inventario'] = $request->has('usa_inventario');
        $data['stock'] = $request->input('stock', 0);

        if (!$request->has('toggle_precio_2')) {
            $data['precio_2'] = null;
            $data['precio_2_nombre'] = null;
            $data['costo_2'] = 0;
        }
        if (!$request->has('toggle_precio_3')) {
            $data['precio_3'] = null;
            $data['precio_3_nombre'] = null;
            $data['costo_3'] = 0;
        }
        
        if ($request->hasFile('imagen')) {
            if ($producto->imagen && \Illuminate\Support\Facades\Storage::disk('public')->exists($producto->imagen)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        
        $producto->update($data);

        if ($producto->usa_inventario && $producto->stock > $old_stock) {
            \App\Models\Compra::create([
                'producto_id' => $producto->id,
                'cantidad' => $producto->stock - $old_stock,
            ]);
        }
        
        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(string $id)
    {
        $producto = \App\Models\Producto::findOrFail($id);
        
        try {
            // Intentar eliminar el producto
            $producto->delete();
            
            // Si se eliminó correctamente, también borramos la imagen
            if ($producto->imagen && \Illuminate\Support\Facades\Storage::disk('public')->exists($producto->imagen)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($producto->imagen);
            }
            
            return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Error 1451 significa que hay una restricción de llave foránea (el producto está en algún pedido)
            if ($e->getCode() == "23000") {
                return redirect()->route('admin.productos.index')->with('error', 'No se puede eliminar el producto porque ya forma parte del historial de ventas de una o más mesas. Por favor, edita el producto y desmarca la opción "Disponible" para ocultarlo del menú.');
            }
            
            // Otro error de base de datos
            return redirect()->route('admin.productos.index')->with('error', 'Ocurrió un error al intentar eliminar el producto.');
        }
    }
}

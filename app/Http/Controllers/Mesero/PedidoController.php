<?php

namespace App\Http\Controllers\Mesero;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Pantalla de bienvenida con botón "Empezar Día".
     */
    public function bienvenida()
    {
        return view('mesero.bienvenida');
    }

    /**
     * Salón: cuadrícula visual de todas las mesas.
     */
    public function salon()
    {
        $mesas = Mesa::withCount(['pedidos as tiene_pedido_activo' => function ($q) {
            $q->whereIn('estado', ['abierto', 'cuenta_solicitada']);
        }])->with(['pedidos' => function ($q) {
            $q->whereIn('estado', ['abierto', 'cuenta_solicitada'])->with('detalles.producto')->latest();
        }])->orderBy('numero')->get();

        return view('mesero.salon', compact('mesas'));
    }

    /**
     * Vista de la mesa: muestra los productos disponibles y el detalle
     * del pedido activo (si existe). El mesero selecciona productos y registra.
     */
    public function verMesa($mesa_id)
    {
        $mesa = Mesa::findOrFail($mesa_id);

        // Pedido activo en esta mesa (puede no existir si está libre)
        $pedido = Pedido::with(['detalles.producto'])
            ->where('mesa_id', $mesa_id)
            ->whereIn('estado', ['abierto', 'cuenta_solicitada'])
            ->first();

        // Carta completa organizada por categorías (solo productos disponibles)
        $categorias = Categoria::with(['productos' => function ($q) {
            $q->where('disponible', true)->orderBy('id'); // Ordenados por creación (ID)
        }])->get()->filter(fn($c) => $c->productos->count() > 0);

        return view('mesero.mesa', compact('mesa', 'pedido', 'categorias'));
    }

    /**
     * Registra los productos seleccionados para la mesa.
     * Si no hay pedido activo, lo crea primero.
     */
    public function registrarItems(Request $request, $mesa_id)
    {
        $request->validate([
            'items'                        => 'required|array|min:1',
            'items.*.producto_id'          => 'required|exists:productos,id',
            'items.*.cantidad'             => 'required|integer|min:1',
            'items.*.precio_seleccionado'  => 'required|numeric|min:0',
            'items.*.notas'                => 'nullable|string|max:255',
        ]);

        $mesa = Mesa::findOrFail($mesa_id);

        DB::transaction(function () use ($request, $mesa) {
            // Buscar pedido activo o crear uno nuevo
            $pedido = Pedido::firstOrCreate(
                ['mesa_id' => $mesa->id, 'estado' => 'abierto'],
                [
                    'mesero_id' => auth()->id(),
                    'total'     => 0,
                ]
            );

            // Si la mesa estaba libre, marcarla como ocupada
            if ($mesa->estado === 'libre') {
                $mesa->update(['estado' => 'ocupada']);
            }

            // Agregar cada ítem al pedido
            foreach ($request->items as $item) {
                $producto = Producto::find($item['producto_id']);
                if ($producto && $producto->usa_inventario) {
                    if ($producto->stock < $item['cantidad']) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'items' => "No hay suficiente stock para: {$producto->nombre}. Disponible: {$producto->stock}."
                        ]);
                    }
                    $producto->decrement('stock', $item['cantidad']);
                }

                PedidoDetalle::create([
                    'pedido_id'       => $pedido->id,
                    'producto_id'     => $item['producto_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_seleccionado'],
                    'estado_comanda'  => 'pendiente',
                    'notas'           => $item['notas'] ?? null,
                ]);
            }

            // Recalcular total
            $total = PedidoDetalle::where('pedido_id', $pedido->id)
                ->selectRaw('SUM(cantidad * precio_unitario) as total')
                ->value('total');

            $pedido->update(['total' => $total ?? 0]);
        });

        return redirect()->route('mesero.salon')
            ->with('pedido_registrado', "✅ Pedido registrado para Mesa {$mesa->numero}.");
    }
}

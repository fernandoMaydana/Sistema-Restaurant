@extends('layouts.admin')

@section('title', 'Nuevo Producto')

@section('admin_content')
<div class="card shadow-sm col-lg-12">
    <div class="card-body">
        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label>Categoría</label>
                <select name="categoria_id" class="form-control" required>
                    <option value="">-- Seleccionar --</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Nombre del Producto</label>
                <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}">
            </div>

            <div class="mb-3">
                <label>Imagen del Producto (Opcional)</label>
                <input type="file" name="imagen" class="form-control" accept="image/*">
            </div>

            <hr class="mt-4 mb-4">
            <h5 class="mb-3 text-primary"><i class="bi bi-cash-coin me-2"></i>Precios y Rentabilidad</h5>

            <div class="row mb-3 align-items-end p-3 border rounded bg-light">
                <div class="col-md-2">
                    <label class="small fw-bold">Costo (P1)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">Bs</span>
                        <input type="text" inputmode="decimal" name="costo" id="costo" class="form-control" value="{{ old('costo', 0) }}" oninput="calcularGanancia(1)">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Nombre (Precio 1)</label>
                    <input type="text" name="precio_nombre" class="form-control" placeholder="Ej: Personal, Regular" value="{{ old('precio_nombre') }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Precio 1 *</label>
                    <div class="input-group">
                        <span class="input-group-text">Bs</span>
                        <input type="text" inputmode="decimal" name="precio" id="precio" class="form-control" required value="{{ old('precio') }}" oninput="calcularGanancia(1)">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-2 bg-white border rounded shadow-sm d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">Ganancia:</span>
                        <span class="fw-bold text-success fs-5" id="ganancia_neta">Bs0.00</span>
                        <span class="badge bg-primary" style="font-size: 0.9rem;" id="ganancia_margen">0%</span>
                    </div>
                </div>
            </div>

            <!-- Precio 2 Toggle -->
            <div class="mb-3 form-check form-switch p-3 border rounded bg-light-subtle">
                <input type="checkbox" class="form-check-input ms-0" id="toggle_precio_2" name="toggle_precio_2" value="1" {{ old('toggle_precio_2') ? 'checked' : '' }} onchange="togglePrecioFields(2)">
                <label class="form-check-label fw-bold ms-2" for="toggle_precio_2">Habilitar Precio Secundario (Precio 2)</label>
            </div>

            <!-- Precio 2 -->
            <div id="container_precio_2" class="row mb-3 align-items-end p-3 border rounded" style="display: none;">
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">Costo (P2)</label>
                    <div class="input-group">
                        <span class="input-group-text text-muted bg-white">Bs</span>
                        <input type="text" inputmode="decimal" name="costo_2" id="costo_2" class="form-control" value="{{ old('costo_2', 0) }}" oninput="calcularGanancia(2)">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Nombre (Precio 2)</label>
                    <input type="text" name="precio_2_nombre" class="form-control" placeholder="Ej: Mediana" value="{{ old('precio_2_nombre') }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Precio 2</label>
                    <div class="input-group">
                        <span class="input-group-text text-muted">Bs</span>
                        <input type="text" inputmode="decimal" name="precio_2" id="precio_2" class="form-control" value="{{ old('precio_2') }}" oninput="calcularGanancia(2)">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-2 bg-light border rounded d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">Ganancia:</span>
                        <span class="fw-bold text-success fs-5" id="ganancia_neta_2">Bs0.00</span>
                        <span class="badge bg-primary" style="font-size: 0.9rem;" id="ganancia_margen_2">0%</span>
                    </div>
                </div>
            </div>

            <!-- Precio 3 Toggle -->
            <div class="mb-3 form-check form-switch p-3 border rounded bg-light-subtle">
                <input type="checkbox" class="form-check-input ms-0" id="toggle_precio_3" name="toggle_precio_3" value="1" {{ old('toggle_precio_3') ? 'checked' : '' }} onchange="togglePrecioFields(3)">
                <label class="form-check-label fw-bold ms-2" for="toggle_precio_3">Habilitar Tercer Precio (Precio 3)</label>
            </div>

            <!-- Precio 3 -->
            <div id="container_precio_3" class="row mb-4 align-items-end p-3 border rounded" style="display: none;">
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">Costo (P3)</label>
                    <div class="input-group">
                        <span class="input-group-text text-muted bg-white">Bs</span>
                        <input type="text" inputmode="decimal" name="costo_3" id="costo_3" class="form-control" value="{{ old('costo_3', 0) }}" oninput="calcularGanancia(3)">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Nombre (Precio 3)</label>
                    <input type="text" name="precio_3_nombre" class="form-control" placeholder="Ej: Familiar" value="{{ old('precio_3_nombre') }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Precio 3</label>
                    <div class="input-group">
                        <span class="input-group-text text-muted">Bs</span>
                        <input type="text" inputmode="decimal" name="precio_3" id="precio_3" class="form-control" value="{{ old('precio_3') }}" oninput="calcularGanancia(3)">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-2 bg-light border rounded d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">Ganancia:</span>
                        <span class="fw-bold text-success fs-5" id="ganancia_neta_3">Bs0.00</span>
                        <span class="badge bg-primary" style="font-size: 0.9rem;" id="ganancia_margen_3">0%</span>
                    </div>
                </div>
            </div>
            
            <hr class="mb-4">

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1" {{ old('disponible', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="disponible">Producto Disponible</label>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="usa_inventario" name="usa_inventario" value="1" {{ old('usa_inventario') ? 'checked' : '' }} onchange="toggleStockField()">
                <label class="form-check-label" for="usa_inventario">Usar Control de Inventario</label>
            </div>

            <div class="mb-3" id="stock_container" style="display: {{ old('usa_inventario') ? 'block' : 'none' }};">
                <label>Stock Inicial</label>
                <input type="number" step="1" min="0" name="stock" class="form-control" value="{{ old('stock', 0) }}">
                <small class="text-muted">Cantidad de unidades disponibles para este producto.</small>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleStockField() {
        var isChecked = document.getElementById('usa_inventario').checked;
        document.getElementById('stock_container').style.display = isChecked ? 'block' : 'none';
    }
    
    function calcularGanancia(nivel) {
        var prefijo = nivel === 1 ? '' : '_' + nivel;
        var costoId = 'costo' + prefijo;
        var precioId = 'precio' + (nivel === 1 ? '' : '_' + nivel);
        
        var elCosto = document.getElementById(costoId);
        var elPrecio = document.getElementById(precioId);
        
        if (!elCosto || !elPrecio) return;

        var costoVal = elCosto.value || "0";
        var precioVal = elPrecio.value || "0";
        
        var costo = parseFloat(costoVal.toString().replace(/,/g, '.')) || 0;
        var precio = parseFloat(precioVal.toString().replace(/,/g, '.')) || 0;
        
        var ganancia = precio - costo;
        var margen = 0;
        
        if (costo > 0 && precio > 0) {
            margen = (ganancia / costo) * 100;
        } else if (costo === 0 && precio > 0) {
            margen = 100;
        }
        
        var elNeta = document.getElementById('ganancia_neta' + prefijo);
        var elMargen = document.getElementById('ganancia_margen' + prefijo);
        
        if (elNeta) elNeta.textContent = 'Bs' + ganancia.toFixed(2);
        if (elMargen) elMargen.textContent = margen.toFixed(1) + '%';
        
        if (ganancia < 0) {
            if(elNeta) { elNeta.classList.remove('text-success'); elNeta.classList.add('text-danger'); }
            if(elMargen) { elMargen.classList.remove('bg-primary'); elMargen.classList.add('bg-danger'); }
        } else {
            if(elNeta) { elNeta.classList.remove('text-danger'); elNeta.classList.add('text-success'); }
            if(elMargen) { elMargen.classList.remove('bg-danger'); elMargen.classList.add('bg-primary'); }
        }
    }

    function togglePrecioFields(nivel) {
        var isChecked = document.getElementById('toggle_precio_' + nivel).checked;
        var container = document.getElementById('container_precio_' + nivel);
        
        if (isChecked) {
            container.style.display = 'flex';
            container.querySelectorAll('input').forEach(function(el) {
                el.disabled = false;
            });
        } else {
            container.style.display = 'none';
            container.querySelectorAll('input').forEach(function(el) {
                el.disabled = true;
                if(el.type === 'text' || el.type === 'number') {
                    if (el.id.startsWith('costo_')) {
                        el.value = '0';
                    } else {
                        el.value = '';
                    }
                }
            });
            var elNeta = document.getElementById('ganancia_neta_' + nivel);
            var elMargen = document.getElementById('ganancia_margen_' + nivel);
            if (elNeta) elNeta.textContent = 'Bs0.00';
            if (elMargen) elMargen.textContent = '0%';
        }
    }

    // Inicializar valores al cargar
    toggleStockField();
    calcularGanancia(1);
    calcularGanancia(2);
    calcularGanancia(3);
    togglePrecioFields(2);
    togglePrecioFields(3);
    
    // Antes de enviar el formulario, cambiar comas por puntos para que Laravel lo procese correctamente
    var form = document.querySelector('form');
    if(form) {
        form.addEventListener('submit', function() {
            ['costo', 'precio', 'costo_2', 'precio_2', 'costo_3', 'precio_3'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el && el.value) {
                    el.value = el.value.replace(/,/g, '.');
                }
            });
        });
    }
</script>
@endsection

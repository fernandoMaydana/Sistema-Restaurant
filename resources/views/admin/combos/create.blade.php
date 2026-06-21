@extends('layouts.admin')

@section('title', 'Nuevo Combo / Promoción')

@section('admin_content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.combos.store') }}" method="POST" enctype="multipart/form-data" id="combo-form">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre del Combo <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Combo Almuerzo Familiar" value="{{ old('nombre') }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Imagen (Opcional)</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción / Términos</label>
                            <textarea name="descripcion" class="form-control" rows="2" placeholder="Ej: Incluye 2 platos de la casa y 1 refresco gratis. Válido fines de semana.">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tipo de Combo <span class="text-danger">*</span></label>
                            <select name="tipo" id="tipo" class="form-select" required>
                                <option value="fijo" {{ old('tipo') === 'fijo' ? 'selected' : '' }}>Precio Fijo Especial</option>
                                <option value="condicionado" {{ old('tipo') === 'condicionado' ? 'selected' : '' }}>Gratuito Condicionado (Suma normal)</option>
                            </select>
                            <div class="form-text">
                                <strong>Precio Fijo:</strong> Se cobra un monto único por todo el combo.<br>
                                <strong>Gratuito Condicionado:</strong> Se suman los precios normales de los platos, pero los marcados como "Gratis" se cobran a Bs 0.
                            </div>
                        </div>

                        <div class="col-md-4" id="precio-container">
                            <label class="form-label fw-bold">Precio Total Combo (Bs) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Bs</span>
                                <input type="number" name="precio_total" id="precio_total" class="form-control" step="0.01" min="0" placeholder="Ej: 45.00" value="{{ old('precio_total') }}">
                            </div>
                        </div>

                        <div class="col-md-4 d-flex align-items-center pt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1" checked>
                                <label class="form-check-label fw-bold" for="activo">Activo para Venta</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- SECCIÓN DETALLE PRODUCTOS -->
                    <h5 class="fw-bold mb-3"><i class="bi bi-list-stars text-primary"></i> Productos que componen el Combo</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="items-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 45%;">Producto</th>
                                    <th style="width: 15%;">Cantidad</th>
                                    <th style="width: 25%;">¿Es Gratis / Bonificado?</th>
                                    <th style="width: 15%; text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="items-tbody">
                                <!-- Filas dinámicas añadidas con JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button type="button" class="btn btn-outline-primary" id="btn-add-item">
                            <i class="bi bi-plus-circle"></i> Agregar Producto
                        </button>
                        <div class="text-end" id="price-summary-container">
                            <span class="text-muted">Precio Normal Sumado:</span>
                            <strong class="fs-5 text-dark ms-2" id="normal-total-price">Bs 0.00</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-4">
                        <a href="{{ route('admin.combos.index') }}" class="btn btn-light px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-5"><i class="bi bi-save"></i> Guardar Combo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo');
    const precioContainer = document.getElementById('precio-container');
    const precioInput = document.getElementById('precio_total');
    const btnAddItem = document.getElementById('btn-add-item');
    const itemsTbody = document.getElementById('items-tbody');
    const normalTotalPriceSpan = document.getElementById('normal-total-price');

    // Lista de productos para JS
    const productos = @json($productos);
    let itemIndex = 0;

    // Toggle de la visibilidad y obligatoriedad del precio total según el tipo de combo
    function togglePrecioField() {
        if (tipoSelect.value === 'fijo') {
            precioContainer.style.display = 'block';
            precioInput.required = true;
        } else {
            precioContainer.style.display = 'none';
            precioInput.required = false;
            precioInput.value = '';
        }
    }

    tipoSelect.addEventListener('change', togglePrecioField);
    togglePrecioField(); // Inicializar

    // Agregar fila de producto
    function addProductRow(productId = '', cantidad = 1, esGratuito = false) {
        const rowId = itemIndex++;
        
        let options = '<option value="" disabled selected>Seleccione un producto...</option>';
        productos.forEach(p => {
            const isSelected = p.id == productId ? 'selected' : '';
            options += `<option value="${p.id}" data-precio="${p.precio}" ${isSelected}>${p.nombre} (Bs ${parseFloat(p.precio).toFixed(2)})</option>`;
        });

        const html = `
            <tr id="row-${rowId}">
                <td>
                    <select name="items[${rowId}][producto_id]" class="form-select producto-select" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${rowId}][cantidad]" class="form-control cantidad-input" min="1" step="1" value="${cantidad}" required>
                </td>
                <td>
                    <div class="form-check form-switch pt-1">
                        <input class="form-check-input gratuito-checkbox" type="checkbox" name="items[${rowId}][es_gratuito]" value="1" ${esGratuito ? 'checked' : ''}>
                        <label class="form-check-label text-muted small">Sí, costo Bs 0</label>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row" data-row-id="${rowId}">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </td>
            </tr>
        `;
        
        itemsTbody.insertAdjacentHTML('beforeend', html);
        
        // Agregar eventos para recalcular el precio sumado
        const row = document.getElementById(`row-${rowId}`);
        row.querySelector('.producto-select').addEventListener('change', calculateTotalPrice);
        row.querySelector('.cantidad-input').addEventListener('input', calculateTotalPrice);
        row.querySelector('.gratuito-checkbox').addEventListener('change', calculateTotalPrice);
        row.querySelector('.remove-row').addEventListener('click', function() {
            row.remove();
            calculateTotalPrice();
        });

        calculateTotalPrice();
    }

    // Calcular la suma de precios normales de los ítems ingresados
    function calculateTotalPrice() {
        let total = 0;
        const rows = itemsTbody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const select = row.querySelector('.producto-select');
            const cantidadInput = row.querySelector('.cantidad-input');
            const gratuitoCheckbox = row.querySelector('.gratuito-checkbox');
            
            const selectedOption = select.options[select.selectedIndex];
            const cantidad = parseInt(cantidadInput.value) || 0;
            const esGratuito = gratuitoCheckbox.checked;
            
            if (selectedOption && selectedOption.value && !esGratuito) {
                const precio = parseFloat(selectedOption.dataset.precio) || 0;
                total += precio * cantidad;
            }
        });
        
        normalTotalPriceSpan.textContent = `Bs ${total.toFixed(2)}`;
    }

    // Inicializar con una fila vacía
    btnAddItem.addEventListener('click', () => addProductRow());
    
    // Si hay old inputs de Laravel validator (en caso de error back)
    @if(old('items'))
        @foreach(old('items') as $oldItem)
            addProductRow(
                "{{ $oldItem['producto_id'] ?? '' }}",
                "{{ $oldItem['cantidad'] ?? 1 }}",
                {{ isset($oldItem['es_gratuito']) ? 'true' : 'false' }}
            );
        @endforeach
    @else
        addProductRow();
    @endif
});
</script>
@endsection

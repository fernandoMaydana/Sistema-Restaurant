{{-- Partial para el contador de productos en la vista POS del cajero --}}
<div class="d-flex align-items-center gap-2">
    <button type="button" class="btn btn-sm btn-outline-secondary p-0 rounded-circle" style="width: 24px; height: 24px;" 
            onclick="cambiarCantNuevo('{{ $key }}', '{{ $prod->id }}', -1, '{{ $prod->nombre }} {{ isset($tipo) && $key == 'p_'.$prod->id.'_2' ? '('.$prod->precio_2_nombre.')' : '' }}', {{ $precio }})">
        <i class="bi bi-dash"></i>
    </button>
    
    <input type="number" id="qty-{{ $key }}" value="0" 
           class="form-control form-control-sm text-center fw-bold px-0 border-0 bg-transparent" 
           style="width: 30px;" readonly>

    <button type="button" class="btn btn-sm btn-primary p-0 rounded-circle" style="width: 24px; height: 24px;" 
            onclick="cambiarCantNuevo('{{ $key }}', '{{ $prod->id }}', 1, '{{ $prod->nombre }} {{ isset($tipo) && $key == 'p_'.$prod->id.'_2' ? '('.$prod->precio_2_nombre.')' : '' }}', {{ $precio }})">
        <i class="bi bi-plus"></i>
    </button>

    {{-- Inputs ocultos para enviar al servidor (se activan solo si cant > 0) --}}
    <input type="hidden" name="items[{{ $key }}][producto_id]" id="hid-pid-{{ $key }}" value="{{ $prod->id }}" disabled>
    <input type="hidden" name="items[{{ $key }}][cantidad]" id="hid-qty-{{ $key }}" value="0" disabled>
    <input type="hidden" name="items[{{ $key }}][precio_seleccionado]" id="hid-prc-{{ $key }}" value="{{ $precio }}" disabled>
</div>

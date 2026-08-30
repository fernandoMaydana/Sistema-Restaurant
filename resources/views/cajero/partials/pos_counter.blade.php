{{-- Partial para el contador de productos en la vista POS del cajero --}}
<div class="pos-stepper-wrapper d-inline-flex align-items-center rounded-pill p-1 shadow-sm border" id="stepper-{{ $key }}" style="background: rgba(148, 163, 184, 0.1);">
    <button type="button" class="btn btn-sm btn-link text-body p-0 rounded-circle d-flex align-items-center justify-content-center border-0 text-decoration-none pos-btn-dash" style="width: 32px; height: 32px; font-size: 1.05rem;" 
            onclick="cambiarCantNuevo('{{ $key }}', '{{ $prod->id }}', -1, '{{ addslashes($prod->nombre) }} {{ isset($tipo) && $key == 'p_'.$prod->id.'_2' ? '('.addslashes($prod->precio_2_nombre).')' : '' }}', {{ $precio }})">
        <i class="bi bi-dash-lg"></i>
    </button>
    
    <input type="number" id="qty-{{ $key }}" value="0" 
           class="form-control text-center fw-black px-0 border-0 bg-transparent pos-qty-input" 
           style="width: 34px; font-size: 1.05rem;" readonly>

    <button type="button" class="btn btn-primary btn-sm p-0 rounded-circle d-flex align-items-center justify-content-center border-0 shadow-sm pos-btn-plus" style="width: 32px; height: 32px; font-size: 1.05rem; background: #4361ee;" 
            onclick="cambiarCantNuevo('{{ $key }}', '{{ $prod->id }}', 1, '{{ addslashes($prod->nombre) }} {{ isset($tipo) && $key == 'p_'.$prod->id.'_2' ? '('.addslashes($prod->precio_2_nombre).')' : '' }}', {{ $precio }})">
        <i class="bi bi-plus-lg"></i>
    </button>

    {{-- Inputs ocultos para enviar al servidor (se activan solo si cant > 0) --}}
    <input type="hidden" name="items[{{ $key }}][producto_id]" id="hid-pid-{{ $key }}" value="{{ $prod->id }}" disabled>
    <input type="hidden" name="items[{{ $key }}][cantidad]" id="hid-qty-{{ $key }}" value="0" disabled>
    <input type="hidden" name="items[{{ $key }}][precio_seleccionado]" id="hid-prc-{{ $key }}" value="{{ $precio }}" disabled>
    <input type="hidden" name="items[{{ $key }}][notas]" id="hid-nota-{{ $key }}" value="" disabled>
</div>

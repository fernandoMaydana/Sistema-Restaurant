<div class="d-flex align-items-center gap-1">
    <button type="button"
            class="btn btn-outline-secondary p-0 rounded-circle"
            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; line-height: 1;"
            onclick="cambiarCant('{{ $key }}', '{{ $prod->id }}', -1, '{{ $prod->nombre }}{{ $key == 'p_'.$prod->id.'_2' ? ' ('.$prod->precio_2_nombre.')' : '' }}', {{ $precio }})">−</button>

    <input type="number"
           id="qty-{{ $key }}"
           value="0" min="0"
           class="form-control form-control-sm text-center fw-bold text-dark bg-transparent border-0 px-0"
           style="width: 32px; -moz-appearance: textfield;"
           oninput="actualizarDesdeInput('{{ $key }}', '{{ $prod->id }}', '{{ $prod->nombre }}{{ $key == 'p_'.$prod->id.'_2' ? ' ('.$prod->precio_2_nombre.')' : '' }}', {{ $precio }})"
           readonly>

    <input type="hidden" id="hid-pid-{{ $key }}" name="items[{{ $key }}][producto_id]" value="{{ $prod->id }}" disabled>
    <input type="hidden" id="hid-qty-{{ $key }}" name="items[{{ $key }}][cantidad]" value="0" disabled>
    <input type="hidden" id="hid-prc-{{ $key }}" name="items[{{ $key }}][precio_seleccionado]" value="{{ $precio }}" disabled>
    <input type="hidden" id="hid-nota-{{ $key }}" name="items[{{ $key }}][notas]" value="" disabled>

    <button type="button"
            class="btn btn-primary p-0 rounded-circle"
            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; line-height: 1;"
            onclick="cambiarCant('{{ $key }}', '{{ $prod->id }}', 1, '{{ $prod->nombre }}{{ $key == 'p_'.$prod->id.'_2' ? ' ('.$prod->precio_2_nombre.')' : '' }}', {{ $precio }})">+</button>
</div>

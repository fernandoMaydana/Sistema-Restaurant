@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 py-4">

    {{-- Encabezado Principal del Centro de Ayuda --}}
    <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 mb-4" style="background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px;">
                        <i class="bi bi-book-half fs-2"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-1">Centro de Ayuda y Manual del Sistema</h2>
                        <p class="mb-0 text-white-50">Guía interactiva paso a paso para el uso correcto de todas las funciones del restaurante.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                {{-- Buscador en Vivo de Instrucciones --}}
                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden bg-white">
                    <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="input-buscar-manual" class="form-control border-0 py-2 fs-6" placeholder="¿Qué necesitas hacer? (ej. crear usuario, descuento, cierre...)" onkeyup="filtrarManual()">
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs de Navegación por Módulo --}}
    <ul class="nav nav-pills mb-4 flex-nowrap overflow-auto pb-2 gap-2" id="ayudaTabs">
        <li class="nav-item">
            <button class="nav-link active fw-bold px-4 py-2.5 rounded-pill shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-admin">
                <i class="bi bi-shield-lock-fill me-2"></i>👑 Administrador
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 py-2.5 rounded-pill shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-caja">
                <i class="bi bi-cash-register me-2"></i>⚡ Caja y Salón
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 py-2.5 rounded-pill shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-mesero">
                <i class="bi bi-person-workspace me-2"></i>🍽️ Meseros
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 py-2.5 rounded-pill shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-cocina">
                <i class="bi bi-egg-fried me-2"></i>👨‍🍳 Cocina
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 py-2.5 rounded-pill shadow-sm" data-bs-toggle="pill" data-bs-target="#tab-faq">
                <i class="bi bi-patch-question-fill me-2"></i>❓ Preguntas Frecuentes
            </button>
        </li>
    </ul>

    {{-- Contenido del Manual --}}
    <div class="tab-content">

        {{-- 1. MÓDULO ADMINISTRADOR --}}
        <div class="tab-pane fade show active" id="tab-admin">
            <div class="row g-4">
                
                {{-- Crear y Gestionar Usuarios --}}
                <div class="col-md-6 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-primary-subtle text-primary p-2 rounded-3"><i class="bi bi-person-plus-fill fs-5"></i></span>
                                Cómo Crear y Gestionar Usuarios
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ol class="ps-3 mb-0 text-secondary lh-lg">
                                <li>Dirígete al menú principal y haz clic en <strong>"Usuarios"</strong>.</li>
                                <li>Haz clic en el botón azul <strong>"+ Nuevo Usuario"</strong>.</li>
                                <li>Ingresa el <strong>Nombre Completo</strong>, <strong>Correo Electrónico</strong> y asigna una <strong>Contraseña</strong>.</li>
                                <li>Selecciona el <strong>Rol adecuado</strong>:
                                    <ul>
                                        <li><strong class="text-danger">Administrador:</strong> Acceso total al sistema, reportes y configuración.</li>
                                        <li><strong class="text-primary">Cajero:</strong> Manejo de caja, cobro de facturas y control de salón.</li>
                                        <li><strong class="text-success">Mesero:</strong> Toma de pedidos desde mesas.</li>
                                        <li><strong class="text-warning text-dark">Cocinero:</strong> Visualización de comandas en pantalla de cocina.</li>
                                    </ul>
                                </li>
                                <li>Haz clic en <strong>"Guardar Usuario"</strong>.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Productos y Categorías --}}
                <div class="col-md-6 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-success-subtle text-success p-2 rounded-3"><i class="bi bi-box-seam-fill fs-5"></i></span>
                                Administración de Productos e Inventario
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ol class="ps-3 mb-0 text-secondary lh-lg">
                                <li><strong>Crear Categorías:</strong> Ingresa a <strong>"Categorías"</strong> para organizar tu menú (ej. *Bebidas, Platos Fuertes, Postres*).</li>
                                <li><strong>Nuevo Producto:</strong> Ve a <strong>"Productos" ➔ "+ Nuevo Producto"</strong>.</li>
                                <li>Asigna un <strong>Nombre</strong>, <strong>Categoría</strong>, <strong>Precio unitario (Bs)</strong> y sube una <strong>Imagen ilustrativa</strong>.</li>
                                <li><strong>Control de Stock:</strong> Si marcas la casilla <em>"Controlar Inventario"</em>, ingresa la cantidad disponible. El sistema descontará automáticamente el stock con cada venta.</li>
                                <li>Haz clic en <strong>"Guardar"</strong>.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Combos Promocionales --}}
                <div class="col-md-6 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-warning-subtle text-warning-emphasis p-2 rounded-3"><i class="bi bi-gift-fill fs-5"></i></span>
                                Crear Combos y Promociones
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ol class="ps-3 mb-0 text-secondary lh-lg">
                                <li>Ingresa a <strong>"Combos"</strong> en el panel de Administración.</li>
                                <li>Haz clic en <strong>"+ Crear Nuevo Combo"</strong>.</li>
                                <li>Asigna un <strong>Nombre de Promo</strong> (ej. *Combo Familiar 2x1*) y un <strong>Precio Combo (Bs)</strong>.</li>
                                <li>Selecciona los productos individuales que conforman el combo y la cantidad de cada uno.</li>
                                <li>Guarda el combo. Aparecerá destacado en la pestaña de combos para meseros y cajeros.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Reportes de Caja y Auditoría --}}
                <div class="col-md-6 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-info-subtle text-info p-2 rounded-3"><i class="bi bi-bar-chart-line-fill fs-5"></i></span>
                                Historial de Cajas y Reportes PDF
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ol class="ps-3 mb-0 text-secondary lh-lg">
                                <li>Ve a <strong>"Historial de Cajas"</strong> para auditar los cierres de turno realizados por los cajeros.</li>
                                <li>Verás la fecha de apertura/cierre, monto inicial, ventas totales en efectivo/QR/tarjeta, gastos y sobrantes/faltantes.</li>
                                <li>Haz clic en <strong>"Descargar PDF"</strong> para exportar el informe de cierre firmado digitalmente o impreso.</li>
                            </ol>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- 2. MÓDULO CAJA Y SALÓN --}}
        <div class="tab-pane fade" id="tab-caja">
            <div class="row g-4">
                
                {{-- Apertura y Cierre de Caja --}}
                <div class="col-md-6 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-success-subtle text-success p-2 rounded-3"><i class="bi bi-cash-stack fs-5"></i></span>
                                Apertura y Cierre de Caja (Turno)
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ol class="ps-3 mb-0 text-secondary lh-lg">
                                <li><strong>Apertura:</strong> Al iniciar sesión por primera vez en el día, ingresa el <strong>Monto Inicial en Efectivo (Bs)</strong> y haz clic en <strong>"Abrir Caja"</strong>.</li>
                                <li><strong>Durante el Turno:</strong> Puedes registrar salidas/compras pequeñas usando el botón <strong>"+ Registrar Gasto"</strong>.</li>
                                <li><strong>Cierre de Turno:</strong>
                                    <ul>
                                        <li>Haz clic en <strong>"Cerrar Caja"</strong> en el panel de inicio.</li>
                                        <li>Cuenta el efectivo físico existente en el cajón e ingrésalo.</li>
                                        <li>El sistema calculará automáticamente si el arqueo coincide o si hay sobrante/faltante.</li>
                                        <li>Presiona <strong>"Confirmar Cierre e Imprimir"</strong> para generar el reporte de caja.</li>
                                    </ul>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Operativa del Salón de Mesas --}}
                <div class="col-md-6 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-primary-subtle text-primary p-2 rounded-3"><i class="bi bi-grid-3x3-gap-fill fs-5"></i></span>
                                Salón de Mesas y Panel de Control
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ul class="ps-3 mb-0 text-secondary lh-lg">
                                <li><strong>1 Clic en Mesa:</strong> Selecciona la mesa y muestra el consumo en el panel derecho.</li>
                                <li><strong>Doble Clic en Mesa:</strong> Ingresa directamente a atender/agregar más ítems a la mesa.</li>
                                <li><strong>Buscador Instantáneo:</strong> Escribe un número de mesa o nombre de mesero en la barra superior para filtrar de inmediato.</li>
                                <li><strong>Panel de Control Permanente (Acciones Globales):</strong>
                                    <ul>
                                        <li><strong>Cambiar Mesa:</strong> Mueve el pedido activo de una mesa ocupada a una libre.</li>
                                        <li><strong>Unir Mesas:</strong> Fusiona los consumos de dos mesas en una sola cuenta.</li>
                                        <li><strong>Dividir Cuenta:</strong> Separa productos para que distintos clientes paguen por separado.</li>
                                        <li><strong>Aplicar Descuento:</strong> Registra un descuento por monto (Bs) o porcentaje (%).</li>
                                        <li><strong>Nota Especial:</strong> Agrega observaciones (ej. *Cliente VIP*, *Alergias*).</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Pedidos Para Llevar --}}
                <div class="col-md-6 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-warning-subtle text-warning-emphasis p-2 rounded-3"><i class="bi bi-bag-plus-fill fs-5"></i></span>
                                Pedidos Para Llevar (Flujo de Pago)
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ol class="ps-3 mb-0 text-secondary lh-lg">
                                <li>Haz clic en <strong>"Pedido Para Llevar"</strong> en la barra superior del Salón.</li>
                                <li>Selecciona los productos y platos solicitados.</li>
                                <li>Al finalizar, elije una de las **dos opciones de flujo**:
                                    <ul>
                                        <li><strong class="text-warning text-dark">💳 PAGAR EN ESTE MOMENTO:</strong> Para clientes presentes en caja. Imprime comanda a cocina y pasa directo al cobro/factura.</li>
                                        <li><strong class="text-primary">⏱️ PAGAR AL RECOGER DESPUÉS:</strong> Para pedidos por teléfono. Envía comanda a cocina y guarda el pedido en <em>"Pedidos Para Llevar Activos"</em> para cobrarlo cuando lleguen.</li>
                                    </ul>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Proceso de Cobro y Facturación SIAT --}}
                <div class="col-md-6 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-secondary-subtle text-dark p-2 rounded-3"><i class="bi bi-receipt-cutoff fs-5"></i></span>
                                Cobro, Métodos de Pago y Factura SIAT
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ol class="ps-3 mb-0 text-secondary lh-lg">
                                <li>En el panel lateral de la mesa u orden, haz clic en <strong>"Cobrar"</strong>.</li>
                                <li>Ingresa la información del cliente: <strong>NIT/CI</strong> y <strong>Razón Social</strong> (o selecciona <em>Consumidor Final</em>).</li>
                                <li>Selecciona el <strong>Método de Pago</strong>:
                                    <ul>
                                        <li><strong>Efectivo:</strong> Ingresa el dinero recibido y el sistema calcula el cambio exacto.</li>
                                        <li><strong>Pago QR / Tarjeta:</strong> Registra la transacción electrónica.</li>
                                    </ul>
                                </li>
                                <li>Haz clic en <strong>"Emitir Factura / Ticket"</strong>. El sistema enviará la factura firmada al SIN y la imprimirá en la ticketera térmica.</li>
                            </ol>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- 3. MÓDULO MESEROS --}}
        <div class="tab-pane fade" id="tab-mesero">
            <div class="row g-4">
                <div class="col-md-6 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-primary-subtle text-primary p-2 rounded-3"><i class="bi bi-tablet-fill fs-5"></i></span>
                                Toma de Pedidos en Mesas desde Móvil o Tablet
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ol class="ps-3 mb-0 text-secondary lh-lg">
                                <li>Ingresa con tus credenciales de mesero y presiona en la mesa que deseas atender.</li>
                                <li>Selecciona la categoría (Combos, Entradas, Fuertes, Bebidas).</li>
                                <li>Toca el botón <strong>"+ Agregar"</strong> en los productos elegidos por los comensales.</li>
                                <li>Si el cliente desea especificaciones (ej. *Sin cebolla*, *Término medio*), escríbelo en la casilla de <strong>Notas de Ítem</strong>.</li>
                                <li>Presiona <strong>"ENVIAR A COCINA"</strong>. El pedido se imprimirá automáticamente en la impresora térmica de cocina.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-info-subtle text-info p-2 rounded-3"><i class="bi bi-bell-fill fs-5"></i></span>
                                Solicitar Cuenta a Caja
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ol class="ps-3 mb-0 text-secondary lh-lg">
                                <li>Cuando la mesa termine de consumir y pida la cuenta, ingresa a la mesa.</li>
                                <li>Haz clic en el botón <strong>"Solicitar Pre-cuenta"</strong>.</li>
                                <li>El estado de la mesa cambiará a amarillo en el panel del cajero para avisarle que deben cobrar dicha mesa.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. MÓDULO COCINA --}}
        <div class="tab-pane fade" id="tab-cocina">
            <div class="row g-4">
                <div class="col-md-12 manual-card">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="bg-danger-subtle text-danger p-2 rounded-3"><i class="bi bi-fire fs-5"></i></span>
                                Pantalla de Gestión de Comandas en Cocina
                            </h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <ol class="ps-3 mb-0 text-secondary lh-lg">
                                <li>En el monitor de cocina se muestran los tickets de comandas en tiempo real organizados por antigüedad.</li>
                                <li>Cada comanda especifica el número de mesa/llevar, mesero, platos, cantidad y observaciones especiales.</li>
                                <li>Haz clic en <strong>"En Preparación"</strong> cuando comiences a cocinar los platos.</li>
                                <li>Haz clic en <strong>"Listo para Servir"</strong> cuando la orden esté preparada para ser despachada por el mesero.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. PREGUNTAS FRECUENTES (FAQ) --}}
        <div class="tab-pane fade" id="tab-faq">
            <div class="accordion accordion-flush" id="faqAccordion">

                <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden manual-card">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-bold fs-6 py-3 px-4 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-1">
                            ❓ ¿Qué hago si un cliente quiere pagar una parte en efectivo y otra con QR o Tarjeta?
                        </button>
                    </h2>
                    <div id="faq-1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body px-4 pb-4 text-secondary">
                            Puedes usar la función <strong>"Dividir Cuenta"</strong> ubicada en el Panel de Control del Salón de Mesas. Esto te permite separar la orden en dos cuentas parciales y cobrar una en efectivo y la otra mediante pago electrónico QR/Tarjeta.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden manual-card">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold fs-6 py-3 px-4 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-2">
                            ❓ ¿Cómo sé si mi ticketera térmica o impresora de factura está conectada correctamente?
                        </button>
                    </h2>
                    <div id="faq-2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body px-4 pb-4 text-secondary">
                            En el panel principal del Cajero (`/cajero`), revisa la tarjeta <strong>"Estado de Ticketera Física"</strong>. Si muestra el punto verde 🟢 <em>Conectada</em>, la impresora está lista. Si muestra un aviso rojo, haz clic en el botón <strong>"Recomprobar"</strong>.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden manual-card">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold fs-6 py-3 px-4 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-3">
                            ❓ ¿Cómo aplico un descuento especial a una mesa antes de cobrar?
                        </button>
                    </h2>
                    <div id="faq-3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body px-4 pb-4 text-secondary">
                            Haz clic en la mesa en el Salón, luego en el botón <strong>"Aplicar Descuento"</strong> del Panel de Control. Puedes elegir descuento por monto (ej. *10 Bs*) o porcentaje (ej. *15%*). El total se recalculará automáticamente.
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<script>
    function filtrarManual() {
        const input = document.getElementById('input-buscar-manual');
        if (!input) return;
        const q = input.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.manual-card');

        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            if (q === '' || text.includes(q)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endsection

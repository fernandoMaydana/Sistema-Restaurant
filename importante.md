Sistema Web para restaurant

Roles: administrador, cajero y mesero 

Flujo de trabajo:


El mesero toma la orden de la mesa esta se registra y pide a caja que se imprima una comanda para ir a cocina y que atiendan su pedido.
A la hora de pago el mesero puede solicitar la cuenta impresa a caja y llevarla a la mesa respectiva, el pago se realiza en caja se extiende una factura al cliente con lo detalles y la mesa se libera.


Historias de usuario 

Administrador 

El administrador es el que configura el sistema y revisa el dinero.

Como administrador, quiero poder crear, editar y eliminar categorias y platos con sus precios, para mantener la carta de un restaurant actualizada.

Como administrador, quiero registrar usuarios (meseros y cajeros) y darles contraseñas, para controlar quién entra al sistema y qué puede ver.

Como administrador, quiero definir cuántas mesas tiene el local y su capacidad, para adaptar el sistema al tamaño físico del restaurante.

Como administrador, quiero ver un reporte con el total de ventas del día y qué mesas ya pagaron, para poder hacer el cuadre de caja en la noche.

Cajero

Como cajero, quiero recibir una notificación o ver en pantalla las órdenes recién registradas por los meseros para imprimir el ticket (comanda) que se llevará a cocina.

Como cajero, quiero poder imprimir el detalle de consumo exacto de una mesa cuando el mesero lo solicite, para que se lo lleve al cliente a revisar.

 Como cajero, quiero procesar el pago final, registrar los datos del cliente y emitir la factura formal.

Como cajero, al confirmar el pago, quiero que el sistema cambia automáticamente el estado de la mesa a "Libre" para que los meseros puedan sentar a otra persona.


Mesero 

Como mesero, quiero ver una pantalla con todas las mesas y un indicador visual (ej. verde = libre, rojo = ocupada), para saber rápidamente el estado del salón.

Como mesero, quiero seleccionar una mesa y registrar pedidos agregando platos y bebidas, para llevar el control de lo que consumen los clientes.

Como mesero quiero poder agregar productos a una mesa que ya ha sido atendida.




Diseño de base de datos:
 
La base de datos inicial tiene 7 tablas principales 

1. users (El Personal)
Maneja los accesos y permisos.
id (Primary Key)
name (String)
email (String, Unique)
password (String)
role (Enum: 'admin', 'cajero', 'mesero') -> Define qué pantallas puede ver cada uno.
timestamps
2. mesas (El Salón)
id (Primary Key)
numero (Integer) -> Mesa 1, Mesa 2, etc.
capacidad (Integer)
estado (Enum: 'libre', 'ocupada') -> Para el indicador visual (verde/rojo) del mesero.
timestamps
3. categorias (Clasificación del Menú)
id (Primary Key)
nombre (String) -> Ej: Sopas, Platos Fuertes, Bebidas.
timestamps
4. productos (La Carta)
id (Primary Key)
categoria_id (Foreign Key -> categorias.id)
nombre (String)
precio (Decimal: 8, 2)
disponible (Boolean: true/false)
timestamps
5. pedidos (La Cuenta Activa)
Esta tabla es el "núcleo" del flujo. Une a la mesa con el mesero que la atiende.
id (Primary Key)
mesa_id (Foreign Key -> mesas.id)
mesero_id (Foreign Key -> users.id)
estado (Enum: 'abierto', 'cuenta_solicitada', 'pagado')
total (Decimal: 8, 2)
timestamps
6. pedido_detalles (Lo que consumen)
Aquí está la magia para tu historia de usuario: "Como mesero quiero poder agregar productos a una mesa que ya ha sido atendida". Como los clientes pueden seguir pidiendo más cervezas o postres después de la primera orden, necesitamos saber qué cosas ya se enviaron a cocina y qué cosas son nuevas.
id (Primary Key)
pedido_id (Foreign Key -> pedidos.id)
producto_id (Foreign Key -> productos.id)
cantidad (Integer)
precio_unitario (Decimal: 8, 2)
estado_comanda (Enum: 'pendiente', 'impreso') -> Cuando el mesero agrega algo, entra como 'pendiente'. Cuando el cajero imprime el ticket para cocina, esos items cambian a 'impreso'. Así no se duplican las órdenes en la cocina.
timestamps
7. facturas (El Cuadre de Caja)
Para que el cajero registre el pago y el administrador vea el dinero al final del día.
id (Primary Key)
pedido_id (Foreign Key -> pedidos.id)
cajero_id (Foreign Key -> users.id) -> Quién cobró ese dinero.
cliente_nombre (String, Nullable) -> Razón social.
cliente_nit_ci (String, Nullable) -> Documento para la factura.
monto_pagado (Decimal: 8, 2)
metodo_pago (Enum: 'efectivo', 'tarjeta', 'qr', 'transferencia')
timestamps






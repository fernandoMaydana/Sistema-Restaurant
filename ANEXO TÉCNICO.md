## **ANEXO TÉCNICO** 

## **INFORMACIÓN** 

## **Generalidades Esquema de Interoperabilidad** 

El Servicio de Impuestos Nacionales (SIN) priorizando la modernización, optimización e integración de procesos y aplicaciones impositivas, con la premisa de facilitar a los contribuyentes el cumplimiento de sus obligaciones tributarias, así como dotar a la Administración Tributaria de mecanismos eficientes que le permitan cumplir adecuadamente con sus fines, pone a disposición del contribuyente, el presente Anexo Técnico que documenta las distintas modalidades de facturación, incluye procesos de autorización, emisión, registro y envío de facturas manuales como electrónicas cumpliendo los requisitos legales y reglamentarios establecidos en la Resolución Normativa de Directorio N° 102100000011. 

## **Facturación Electrónica** 

Modalidad para la emisión de Facturas Digitales firmadas digitalmente, además del uso de Token propio o delegado a través de un Sistema Informático de Facturación autorizado por la Administración Tributaria y su posterior envío, registro y validación en los servidores de base de datos del SIN. 

## **Características:** 

- Uso de la Firma Digital. 

- Impresión de la Factura Digital de manera opcional. 

- Envío individual de la Factura firmada digitalmente en formato XML. 

- Envío agrupado en paquete por contingencia de las Facturas en formato XML firmadas digitalmente. 

- Envío masivo en forma de paquetes de las Facturas en formato XML firmadas digitalmente. 

## **Esquema de Interoperabilidad** 

**1.** El Sistema Informático de Facturación del emisor (previamente autorizado y que tenga CUIS vigente) solicita al SIN el código único de facturación diaria (CUFD), que le habilita la emisión de Facturas por un periodo de 24 horas. 

**2.** El SIN realiza verificaciones a la información del emisor y devuelve los códigos de Verificación y CUFD, además de la dirección de la sucursal o casa matriz. 

**3.** El Sistema Informático de Facturación del Contribuyente utiliza el CUFD, junto con los datos de emisión para generar el archivo XML (factura digital), que debe ser firmado digitalmente y enviado a través de los servicios correspondientes del SIN. 

**4.** El SIN recibe la solicitud de recepción y procede a validar la cabecera para devolver la siguiente información: 

   - **a)** Si la validación es correcta y es un proceso individual en línea, retorna código de recepción. 

   - **b)** Si la validación es correcta y es un proceso por paquete de contingencia o masivo, retorna el código de recepción. 

   - **c)** Si la validación presenta errores, retorna una lista de códigos y mensajes de error para que el emisor proceda a su corrección y posterior reenvío. 

**5.** El Sistema envía por correo u otra medio la representación gráfica y el XML al cliente, si este desea tener un respaldo de la emisión de la Factura Digital, el emisor podrá imprimir la Representación Gráfica. 

**6.** Cuando la emisión de la Factura Digital sea por paquete de contingencia o por emisión masiva, el SIN validará la información contenida en el paquete de manera individual, como resultado se tiene: 

   - **a)** Registrar y consolidar la Factura Digital para la emisión por contingencia o masiva en caso de no existir errores. 

   - **b)** En caso de existir errores, se observa el paquete, se registran las facturas correctas y se rechazan las que contengan los errores. En caso de que el tipo de documento sea NIT y el  numero de documento no sea valido o no haya sido validado previamente a través del método de verificación de NIT, el emisor podrá enviar el código de excepción para que la factura no sea rechazada. 

**7.** El SIN retorna los resultados del proceso de validación descritos en el punto 6. En caso de existir observaciones deberá subsanarlas y posteriormente reenviar la Factura Digital. 

## **Facturación Computarizada en Línea** 

Modalidad para la emisión de Facturas Digitales usando un Token propio o delegado en un Sistema Informático de Facturación autorizado por la Administración Tributaria y su posterior envío, registro y validación en los servidores de base de datos del SIN. 

## **Características:** 

- Impresión de la Factura Digital de manera opcional. 

- Envío individual de la Factura en formato XML. 

- Envío agrupado en paquete por contingencia de las Facturas en formato XML. 

- Envío masivo por paquete de las Facturas en formato XML. 

## **Esquema de Interoperabilidad** 

**1.** El Sistema Informático de Facturación del emisor (previamente autorizado y que tenga el CUIS vigente) solicita al SIN el código único de facturación diaria (CUFD), que le habilita la emisión de Facturas por un periodo de 24 horas. 

**2.** El SIN realiza verificaciones a la información del emisor y devuelve los códigos de Verificación y CUFD, además de la dirección de la sucursal o casa matriz. 

**3.** El Sistema Informático de Facturación del Contribuyente utiliza el CUFD. para emitir las Facturas en formato XML, obtención del hash del archivo y posteriormente realiza el envío del mismo al SIN. 

**4.** El SIN recibe la solicitud de recepción y procede a validar la cabecera de recepción para devolver la siguiente información: 

   - **a)** Si la validación esta correcta y es un proceso individual en línea, retorna código de recepción y el estado es recibido. 

   - **b)** Si la validación es correcta y es un proceso por paquete de contingencia o masivo, retorna el código de recepción. 

   - **c)** Si la validación presenta errores, retorna una lista de códigos y mensajes de error para que el emisor proceda a su corrección y posterior reenvío. 

**5.** Si el cliente desea tener un respaldo de la emisión la Factura Digital, el emisor podrá imprimir la Factura. 

**6.** Cuando la emisión de la Factura sea por paquete de contingencia o por emisión masiva, el SIN validará la información contenida en la Factura Digital, como resultado procederá a: 

- **a)** Registrar y consolidar la Factura Digital para la emisión por contingencia o masiva en caso de no existir errores. 

- **b)** En caso de existir errores, se observa el paquete, se registran las facturas correctas y se rechazan las que contengan los errores. En caso de que el tipo de documento sea NIT y el  numero de documento no sea valido o no haya sido validado previamente a través del método de verificación de NIT, el emisor podrá enviar el código de excepción para que la factura no sea rechazada **.** 

**7.** El emisor utilizará el código de recepción para realizar la validación de la Factura Digital enviada,solo cuando su emisión sea en paquete por contingencia o emisión masiva. 

**8.** El SIN retorna los resultados del proceso de validación descritos en el punto 6. En caso de existir observaciones deberá subsanarlos y posteriormente reenviar la Factura Digital. 

## **Portal Web en Línea** 

Modalidad de Facturación implementada por la Administración Tributaria en su Página Web para todos los contribuyentes previa suscripción y acceso con credenciales de acceso autorizadas para la emisión de facturas en linea. Las Facturas Digitales emitidas a través de esta modalidad, son validadas y registradas en la Base de Datos de la Administración Tributaria. 

**Nota:** Esta modalidad excepcionalmente podrá ser utilizada a manera de contingencia para las modalidades Computarizada y Electrónica en línea. 

## **Características:** 

- Impresión de la Factura de manera opcional. 

## **Esquema de Interoperabilidad** 

## **Tipos de Documentos Fiscales** 

Se consideran cuatro tipos de Documentos Fiscales: 

1. **Facturas con derecho a crédito fiscal.** Son aquellas Facturas que generan crédito fiscal para el Comprador y débito fiscal para el vendedor. 

2. **Facturas sin derecho a crédito fiscal.** Son aquellas Facturas que no generan crédito fiscal para el Comprador, ni débito fiscal para el vendedor. 

3. **Documento de ajuste.** Normativamente existe la Nota de Crédito - Débito como documento de ajuste del crédito y débito fiscal, cuando se realice una devolución parcial o total o rescisión de contrato, pudiendo emitirse hasta dieciocho (18) meses después de generada la factura original y la Nota de Conciliación para realizar ajustes en el Crédito y en el Débito Fiscal de los Sujetos Pasivos del IVA por transacciones facturadas en periodos anteriores no mayores a doce (12) meses. 

4. **Documento Equivalente.** Es el documento, que si bien no se constituye en una Factura o Nota Fiscal propiamente dicha, su emisión implica la realización de una operación gravada por el IVA, dando lugar al cómputo del Crédito Fiscal para el comprador. 

## **Documentos por Sector** 

En función del tipo de sector económico y a la clasificación normativa a la que pertenece, una Factura Electrónica debe ser construida y enviada a la Administración Tributaria utilizando los Servicios Web correspondientes. Las Facturas por Sector son las siguientes: 

|**Có**|**Descripción**||**Características**|**Características**|**Características**||**Tipo**|
|---|---|---|---|---|---|---|---|
|**dig**||||||**Factura/Documento**||
|**o**||||||||
|**1**|Factura de Compra|Habilitada||para|<br>transacciones por|Con|derecho a crédito|
||y Venta|bienes o servicios en general, incluyen||||fiscal||
|||línea<br>blanca,|||negra<br>y<br>cualquier|||
|||actividad que involucre un intercambio||||||
|||de estos.||||||
|**2**|Factura de Alquiler|Habilitado||para|alquiler de bienes|Con derecho a crédito||
||de Bienes|inmuebles|propios.|||fiscal||
||Inmuebles|||||||
|**3**|Factura Comercial|Habilitada||para|<br>transacciones<br>de|Sin derecho a crédito||
||de Exportación|exportación||de bienes, no se incluyen||fiscal||
|||minerales.||||||
|**4**|Factura de|Habilitada||para|<br>transacciones<br>de|Sin derecho a crédito||
||Comercial de|exportación||<br>de|<br>bienes<br>en<br>libre|fiscal||
||Exportación en|consignación.||||||
||Libre Consignación|||||||
|**5**|Factura de Venta en|Habilitada||para|<br>transacciones<br>en|Sin derecho a crédito||
||Zona Franca|zonas francas|||a concesionario o|fiscal||
|||usuario.||||||
|**6**|Factura de  Servicio|Habilitada||para|la exportación de|Sin derecho a crédito||
||Turístico y|servicios||turísticos<br>y<br>hospedaje,||fiscal||
||Hospedaje|alcanzados||por el Artículo 30 de la Ley||||
|||N° 292.||||||
|**7**|Factura de|Habilitada||para|comercialización de|Sin derecho a crédito||
||Seguridad|alimentos exentos de impuestos.||||fiscal||
||Alimentaria y|||||||
||Abastecimiento|||||||
|**8**|Factura  Tasa Cero|Habilitada|para los que se encuentren|||Sin derecho a crédito||
||Venta de Libros y|alcanzados||por el Régimen Tasa Cero||fiscal||
||Transporte|en el IVA.||Para la venta de libros||||
||Internacional de|nacionales||o|<br>importados<br>y|||
||Carga por Carretera|publicaciones|||oficiales.<br>Por<br>el|||
|||transporte|internacional de carga por|||||
|||carretera.||||||
|**9**|Facturas de|Habilitada||para|<br>transacciones<br>de|Sin derecho a crédito||
||Compra y Venta de|compra/venta de|||moneda extranjera.|fiscal||
||Moneda Extranjera|||||||
|**10**|Factura Dutty Free|Habilitada|para los que realicen ventas|||Sin derecho a crédito||
|||en tiendas|libres o Dutty Free.|||fiscal||



||||||
|---|---|---|---|---|
|**11**|Factura Sectores||Habilitada para la facturación de|Con derecho a crédito|
||Educativos||unidades<br>educativas<br>preescolares,|fiscal|
||||primaria, secundaria, de educación||
||||superior,<br>institutos<br>educativos,||
||||enseñanza de adultos y otros tipos de||
||||enseñanza.||
|**12**|Factura de||Habilitada<br>para<br>la<br>venta<br>de|Con derecho a crédito|
||Comercialización de||combustible<br>diésel<br>oíl,<br>venta<br>de|fiscal|
||Hidrocarburos||combustible<br>gasolina<br>especial y/o||
||||gasolina<br>Premium,<br>venta<br>de||
||||combustiblepara automotores.||
|**13**|Factura de||Habilitada para la distribución de agua,|Con derecho a crédito|
||Servicios Básicos||electricidad y Cooperativas Telefónicas|fiscal|
||||que dentro de sus operaciones utilicen||
||||otras tasas.||
|**14**|Factura Productos||Habilitada a los productos que estén|Con derecho a crédito|
||Alcanzados por el||alcanzados por el ICE, por ejemplo:|fiscal|
||ICE||cigarrillos,bebidas alcohólicasyotros.||
|**15**|Factura de||Habilitada para entidades de carácter|Con derecho a crédito|
||Entidades||financiero,<br>por<br>ejemplo:<br>bancos,|fiscal|
||Financieras||cooperativas y otros. No incluyen||
||||casas de cambio.||
|**16**|Factura de Hoteles||Habilitada<br>para<br>hoteles,<br>hostales,|Con derecho a crédito|
||||alojamientos y otros, cuando los|fiscal|
||||huéspedes sean de origen nacional o||
||||residentes en Bolivia.||
|**17**|Factura de||Habilitada para hospitales y clínicas,|Con derecho a crédito|
||Hospitales/Clínicas||deberá incluir información de los|fiscal|
||||pacientes y médicos cuando sea una||
||||intervenciónquirúrgica.||
|**18**|Factura de Juegos||Habilitada para las actividades que|Con derecho a crédito|
||de Azar||incluyan sorteos, concursos o juegos|fiscal|
||||de azar.||
|**19**|Factura|de|Habilitada para empresas dedicadas a la|Con derecho a crédito|
||Hidrocarburos||comercialización de hidrocarburos o sus|fiscal|
||Alcanzada IEHD||derivados en primera fase||
|**20**|Factura Comercial||Habilitada<br>para<br>transacciones<br>de|Sin derecho a crédito|
||de Exportación de||exportación de minerales.|fiscal|
||Minerales||||
|**21**|Factura de Venta de||Habilitada para la venta de minerales|Con derecho a crédito|
||Minerales||en el territorio nacional.|fiscal|
|**22**|Factura de||Habilitada<br>para<br>servicios<br>de|Con derecho a crédito|
||Telecomunicaciones||telecomunicaciones.|fiscal|
|**23**|Factura Prevalorada||Habilitada para actividades de cobro|Con derecho a crédito|
||||de tasa aeroportuaria y terrestre, y|fiscal|
||||para entradas a ferias.||
||||||



|||||||
|---|---|---|---|---|---|
|**24**|Nota de|Crédito -||Habilitada para realizar ajustes en el|Documento de Ajuste|
||Débito|||crédito y débito fiscal de los Sujetos||
|||||Pasivos o compradores.||
|**28**|Factura|Comercial||Habilitada para Contribuyentes que|Sin derecho a crédito|
||de Exportación||de|Exportan Servicios|fiscal|
||Servicios|||||
|**29**|Nota||de|Habilitada para realizar ajustes en el|Documento de Ajuste|
||Conciliación|||Crédito y en el Débito Fiscal de los||
|||||Sujetos<br>Pasivos<br>del<br>IVA<br>por||
|||||transacciones facturadas en periodos||
|||||anteriores no mayores a doce (12)||
|||||meses,<br>por servicios de energía||
|||||eléctrica, telecomunicaciones, agua||
|||||potable e hidrocarburos.||
|**30**|Boleto Aéreo|||Habilitada para el registro de pasajes|Documento|
|||||aéreos.|Equivalente|
|**31**|Factura||de|Habilitada para la recarga de Energía|Con derecho a crédito|
||Suministro||de|Eléctrica a Vehículos Eléctricos.|fiscal|
||Energía|||||
|**33**|Factura|Tasa Cero||Habilitada para la importación y|Sin derecho a crédito|
||IVA Ley N° 1613|||comercialización de bienes de capital y|fiscal|
|||||plantas industriales||
|**34**|Factura de Seguros|||Habilitada<br>para<br>transacciones|Con derecho a crédito|
|||||específicas del Sector Seguros|fiscal|
|**35**|Factura|Compra||Habilitada<br>para<br>transacciones por|Con derecho a crédito|
||Venta|||bienes o servicios en general, incluyen|fiscal|
||Bonificaciones|||línea<br>blanca,<br>negra<br>y<br>cualquier||
|||||actividad que involucre un intercambio||
|||||de estos. Permite descuento total en||
|||||algunos de losproductos en el detalle.||
|**36**|Factura|||Habilitada<br>para<br>actividades<br>de|Sin derecho a crédito|
||Prevalorada Sin|||realización de Espectáculos Públicos|fiscal|
||Derecho|Crédito||Eventuales,<br>Zona<br>Franza<br>e||
||Fiscal|||Importación y Venta de Libros||
|**37**|Factura||de|Habilitada para la venta de Gas|Con derecho a crédito|
||Comercialización||de|Natural Vehicular|fiscal|
||GNV|||||
|**38**|Factura|||Habilitada<br>para<br>todas<br>aquellas|Con Derecho a crédito|
||Hidrocarburos||No|actividades exentas del pago del IEHD|fiscal|
||Alcanzada IEHD|||||
|**39**|Factura||de|Habilitada para la comercialización de|Con Derecho a Crédito|
||Comercialización|||Gas Natural y Gas Licuado de Petróleo|Fiscal|
||De GNy|GLP||||
|**40**|Factura||de|Habilitada para la distribución de agua,|Sin Derecho a Crédito|
||Servicios|<br>Básicos||electricidad o cualquier servicio que se|Fiscal|
||Zona Franca|||considere<br>básico,<br>de<br>acuerdo<br>a||
|||||normativa vigente en Zona Franca||
|||||||



|||||||
|---|---|---|---|---|---|
|**41**|Factura|de Compra||Habilitada<br>para<br>transacciones por|Con Derecho a Crédito|
||Venta Tasas|||bienes o servicios en general, incluyen|Fiscal|
|||||línea<br>blanca,<br>negra<br>y<br>cualquier||
|||||actividad que involucre un intercambio||
|||||de estos, permite incluir tasas no||
|||||sujetas a Crédito Fiscal||
|**42**|Factura|<br>Alquiler||Habilitada para el alquiler de Bienes|Sin Derecho a Crédito|
||Zona Franca|||Inmuebles en zona Franca|Fiscal|
|**43**|Factura|<br>Comercial||Habilitada para transacciones de|Sin Derecho a Crédito|
||de|Exportación||exportación del sector Hidrocarburos|Fiscal|
||Hidrocarburos|||adecuado a legislativa vigente||
|**44**|Factura|Importación||Habilitada para empresas que|Con Derecho a Crédito|
||y Comercialización|||importen de forma directa lubricantes y|Fiscal|
||de Lubricantes|||que comercialicen los mismos al||
|||||consumidor final o distribuidor (No||
|||||utilizada para servicios, solo venta de||
|||||productos)||
|**45**|Factura|<br>Comercial||Habilitada para transacciones de|Sin Derecho a Crédito|
||de|Exportación||exportación de bienes, no se incluye a|Fiscal|
||Precio Venta|||la exportación de minerales.||
|**46**|Factura||Sector|Habilitada para la facturación de|Sin Derecho a Crédito|
||Educativo||Zona|unidades educativas preescolares,|Fiscal|
||Franca|||primaria, secundaria, de educación||
|||||superior, institutos educativos,||
|||||enseñanza de adultos y otros tipos de||
|||||enseñanza al interior de Zona Franca||
|**47**|Nota Crédito||Débito|Habilitada para realizar ajustes en el|Documento de Ajuste|
||Descuentos|||crédito y débito fiscal de los Sujetos||
|||||Pasivos o compradores a facturas||
|||||afectadas con un Descuento Adicional||
|**48**|Nota Crédito Débito|||Habilitada para realizar ajustes en el|Documento de Ajuste|
||ICE|||crédito y débito fiscal de los Sujetos||
|||||Pasivos o compradores a facturas||
|||||emitidas con ICE||
|**49**|Factura|||Habilitada para servicios de|Sin Derecho a Crédito|
||Telecomunicaciones|||telecomunicaciones en Zona Franca|Fiscal|
||Zona Franca|||||
|**50**|Factura|Hospitales/||Habilitada para hospitales y clínicas en|Sin Derecho a Crédito|
||Clínicas|Zona||Zona Franca, deberá incluir|Fiscal|
||Franca|||información de los pacientes y||
|||||||



||||médicos cuando sea una intervención|médicos cuando sea una intervención|médicos cuando sea una intervención|médicos cuando sea una intervención|
|---|---|---|---|---|---|---|
||||quirúrgica.||||
|**51**|Factura||Habilitada para empresas||dedicadas a<br>Con Derecho a Crédito||
||Engarrafadoras||la recarga o llenado de gas en|||Fiscal|
||||Garrafas y Contenedores||||
|**52**|Factura Venta||Habilitada para la Venta de Minerales|||<br>Sin Derecho a Crédito|
||Minerales Banco||al Banco Central y solo en la|||Fiscal|
||Central||modalidad electrónica||||
|**53**|Factura Importación||Habilitada para empresas||que|Con Derecho a Crédito|
||y Comercialización||importen de forma directa||lubricantes|y<br>Fiscal|
||de<br>Lubricantes<br>IEHD||que comercialicen los mismos al||||
||||consumidor final o distribuidor (No||||
||||utilizada para servicios, solo venta de||||
||||productos)||||
|**54**|Factura||Habilitada para ventas en el mercado|||Sin Derecho a Crédito|
||Compra-Venta|de|interno de productos de soya, cusi, totaí<br>Fiscal||||
||Insumos<br>para<br>la<br>Producción<br>de<br>Biodiésel y/o Diésel<br>Ecológico||y otras especies cultivados o silvestres,<br>y/o sus derivados, destinadas<br>exclusivamente a la producción de||||
||||biodiésel y/o diésel ecológico para las||||
||||plantas de Yacimientos Petrolíferos||||
||||Fiscales Bolivianos - YPFB||||
|**55**|Factura||Habilitada para la venta de||combustible||
||Comercialización|de|para automotores.||||
||Combustible||||||



## **Códigos de Autorización** 

Los Códigos de Autorización otorgados por el SIN o generados por el Sistema Informático de Facturación autorizan la emisión de Documentos Fiscales en función a parámetros establecidos. De acuerdo a su característica podrán o no ser consignados en los documentos fiscales autorizados por la Administración Tributaria, de acuerdo a la modalidad de facturación utilizada pueden ser: 

**CUIS** (Código Único de Inicio de Sistemas). Dato alfanumérico generado por la Administración Tributaria que identifica la relación entre el Sistema de Facturación, credenciales, contribuyente, sucursal y opcionalmente al punto de venta. Tiene una vigencia de  365 días calendario. Para su obtención se utiliza Token para validar la autenticidad del contribuyente. 

**CUFD** (Código Único de Facturación Diaria). Dato alfanumérico generado por la Administración Tributaria con la información del Sistema de Facturación, que permite al 

Sujeto Pasivo o Tercero Responsable la emisión de Documentos Fiscales Electrónicos durante 24 horas. Para su obtención se utiliza Token para validar la autenticidad del contribuyente. 

**CUF** (Código Único de Factura). Generado de forma automática al momento de la emisión de la Factura por el Sistema Informático de Facturación, en las Modalidades de Facturación en Línea,  permite la individualización de cada factura. 

**CAED** (Código Autorización para la Emisión de Documentos Fiscales). Generado por la Administración Tributaria para la emisión de Documentos Fiscales en las modalidades de facturación Manual y Prevalorada Preimpresa. 

**CAFC** (Código Autorización Facturas Contingencia). Generado por la Administración Tributaria para la impresión y posterior emisión de facturas de contingencia. Se lo obtiene cuando al efectuar la solicitud de impresión de facturas manuales de contingencia. 

**Número de Autorización.** Generado Automáticamente para la emisión de Facturas en la modalidad Computarizada SFV. 

**Nota.** En el caso del uso de Prevaloradas en Línea, el sistema informático de facturación deberá solicitar la autorización de emisión para este tipo de documento, considerando el periodo, los rangos de emisión y precios fijos para dichos documentos. Esta solicitud devolverá un código de autorización que deberá ser incluido en la solicitud de emisión. 

En los registros obligatorios a enviar a la Administración Tributaria, excepto en el Registro de Compras y Ventas o aplicativos SIAT o Mis facturas, donde se solicite el Numero de Autorización deberá registrarse el valor noventa y nueve (99) cuando las citadas facturas consignen Códigos de Autorización emitidas en la Modalidad de Facturación Electrónica en Línea, Computarizada en Línea o Portal Web en Línea o Modalidad Manual del Sistema de Facturación vigente. 

**==> picture [452 x 59] intentionally omitted <==**

## **FACTURACION EN LINEA** 

## **Factura Electrónica** 

Una Factura Electrónica es un documento digital de índole fiscal,  emitido a través de un Sistema Informático de Facturación autorizado por la Administración Tributaria, su existencia es digital y debe ser registrada y validada en la base de datos del Servicio de Impuestos Nacionales. 

A efecto de su emisión requiere: 

- Token de acceso que puede ser propio o delegado (en el caso de proveedor) y la Firma Digital del Sujeto Pasivo en la Modalidad de Facturación Electrónica en Línea; 

- Token de acceso delegado (en el caso de sistema proveedor) o propio, y la huella del archivo XML, en la Modalidad de Facturación Computarizada en Línea; 

- Credenciales de acceso otorgadas por la Administración Tributaria en la Modalidad de Facturación Portal Web en Línea. 

## **XML** 

Las facturas electrónicas se envían al SIN utilizando para ello XML que es un tipo de lenguaje de marcado o conjunto de códigos (denominados etiquetas) que definen la estructura y el significado de los datos, El ejemplo a continuación describe de manera general la estructura de una factura computarizada. 

<?xml version="1.0" encoding="UTF-8" standalone="yes"?> <facturaComputarizadaCompraVenta xsi:noNamespaceSchemaLocation="facturaComputarizadaCompraVenta.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <cabecera> <nitEmisor>1003579028</nitEmisor> 

<razonSocialEmisor>Carlos Loza</razonSocialEmisor> 

<municipio>La Paz</municipio> <telefono>78595684</telefono> 

<numeroFactura>1</numeroFactura> 

<cuf>44AAEC00DBD34C53C3E2CCE1A3FA7AF1E2A08606A667A75AC82F24C74</cuf> 

<cufd>BQUE+QytqQUDBKVUFOSVRPQkxVRFZNVFVJBMDAwMDAwM</cufd> <codigoSucursal>0</codigoSucursal> <direccion>AV. JORGE LOPEZ #123</direccion> 

<codigoPuntoVenta xsi:nil="true"/> <fechaEmision>2021-10-06T16:03:48.675</fechaEmision> 

<nombreRazonSocial>Mi razon social</nombreRazonSocial> 

<codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad> 

<numeroDocumento>5115889</numeroDocumento> 

<complemento xsi:nil="true"/> 

<codigoCliente>51158891</codigoCliente> <codigoMetodoPago>1</codigoMetodoPago> <numeroTarjeta xsi:nil="true"/> 

<montoTotal>99</montoTotal> 

<montoTotalSujetoIva>99</montoTotalSujetoIva> 

<codigoMoneda>1</codigoMoneda> <tipoCambio>1</tipoCambio> <montoTotalMoneda>99</montoTotalMoneda> <montoGiftCard xsi:nil="true"/> <descuentoAdicional>1</descuentoAdicional> <codigoExcepcion xsi:nil="true"/> <cafc xsi:nil="true"/> <leyenda>Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices. </leyenda> <usuario>pperez</usuario> <codigoDocumentoSector>1</codigoDocumentoSector> </cabecera> <detalle> <actividadEconomica>451010</actividadEconomica> <codigoProductoSin>49111</codigoProductoSin> <codigoProducto>JN-131231</codigoProducto> <descripcion>MI PRODUCTO O SERVICIO</descripcion> <cantidad>1</cantidad> <unidadMedida>1</unidadMedida> <precioUnitario>100</precioUnitario> <montoDescuento>0</montoDescuento> <subTotal>100</subTotal> <numeroSerie>124548</numeroSerie> <numeroImei xsi:nil="true"/> </detalle> </facturaComputarizadaCompraVenta> 

## **Servicios SOAP** 

Las facturas electrónicas son enviadas a la Administración Tributaria consumiendo servicios SOAP. Los servicios SOAP o simplemente conocidos como Web Services, basan su comunicación bajo el protocolo SOAP, que define cómo dos objetos en diferentes procesos pueden comunicarse intercambiando datos utilizando para ello XML. 

## **REQUERIMIENTOS** 

## **Sistema Informático de Facturación** 

Los Sistemas Informáticos de Facturación para interactuar con los servicios de la Administración Tributaria, deberán estar autorizados por el SIN y contar como mínimo con los siguientes componentes o funcionalidades: 

## **a) Emisor de Facturas Digitales:** 

Permite generar Facturas Digitales en formato XML para las modalidades Electrónica en Línea y Computarizada en Línea. 

Este componente debe poseer por lo menos la emisión individual y por contingencia, en función del giro del negocio puede tener la capacidad de emisión masiva: 

## **Emisión Individual** 

Este componente debe emitir una Factura Digital en base a la siguiente secuencia de pasos: 

- 1) Generar Archivo XML asociado a la Factura de acuerdo a su actividad económica. 

- 2) Firmar el archivo obtenido conforme estándar XMLDSig (sólo en el caso de la 

   - Modalidad Electrónica en Línea). 

- 3) Validar contra el XSD asociado. 

- 4) Comprimir el archivo XML en formato Gzip, mismo que debe ser enviado en la etiqueta archivo. 

- 5) Obtener el HASH (SHA256) del archivo compreso obtenido en el paso anterior, mismo que debe ser enviado en la etiqueta hashArchivo. Este valor es utilizado también como **Huella Digital** en la modalidad computarizada en Línea. 

## **Emisión de Paquetes por Contingencia** 

Cuando el Sistema Informático de Facturación autorizado tenga un evento de contingencia que obligue a la emisión de facturas fuera de línea (offline), almacenará las mismas en paquetes de máximo 500 Facturas. Posterior a la recuperación del evento de contingencia, el Sistema Informático deberá registrar el mismo a través del Servicio Web habilitado para el 

efecto y proceder al envío de los paquetes consumiendo para ello los servicios correspondientes. 

## **Emisión de Paquetes por emisión Masiva** 

La emisión masiva es utilizada por empresas que, por su giro de negocio, realizan procesos automatizados de emisión de Facturas Digitales en horarios extraordinarios, como entidades financieras, servicios de telecomunicaciones, luz, agua y otros. Por lo que el Sistema Informático de Facturación autorizado deberá generar paquetes de hasta 1000 Facturas y proceder al envío de los mismos a través de los servicios correspondientes. 

## **b) Gestor de  Facturas Digitales:** 

Su función principal es enviar y validar transacciones de registro como la anulación de las Facturas. En el apartado correspondiente a la implementación de Servicios de Facturación se muestra en detalle la implementación de los mismos, y el apartado de Archivos XML/XSD de Facturas contiene una descripción detallada de cada tipo de documento sector a ser gestionado. 

## **c) Sincronización de catálogos:** 

Funcionalidad que permite la descarga y actualización de los diferentes catálogos del Sistema de Facturación, códigos de productos y servicios, países, códigos de eventos significativos, códigos de mensajes de servicios y otros. La sincronización de catálogos se realizará de forma diaria. Para obtener mayor información, diríjase al siguiente enlace: implementación de Servicios de Facturación - Sincronización. 

**d) Sincronización de fecha y hora: (** Debe obligatoriamente efectuarse a Diario **)** Permite la sincronización de la fecha y hora de los Sistemas Informáticos de Facturación (Contribuyente) con la fecha y hora de la Administración Tributaria. Esta sincronización será utilizada para realizar los controles de plazos de envíos y registros en las diferentes casuísticas de emisión de Facturas Digitales. La sincronización puede ser realizada varias veces al día, recomendándose se la efectúe antes de la obtención del Código Único de Facturación Diaria - CUFD, a través del servicio web correspondiente. 

## **e) Registro de eventos significativos:** 

Funcionalidad que permite el registro de eventos significativos que se hubieren producido y que se detallan en la sección Contingencia. 

**f) Gestor de envío de documentos digitales e impresión:** 

Su función principal es gestionar la impresión, envío o publicación de la representación gráfica y el XML de la factura digital. Si bien la impresión no es obligatoria, cuando el sistema informático de facturación no tenga la capacidad de enviar la representación gráfica y el XML de la factura digital, el sistema informático deberá poder realizar la impresión física de la representación grafica y posterior envío de esta y el XML a través de algún medio tecnológico. Adicionalmente podría poner a disposición de los clientes tanto la representación como el XML a traves de un portal web u otro medio para que el cliente pueda consultar y obtener sus facturas digitales. 

## **ESQUEMAS DE CONEXIÓN** 

## **Esquema de Conexión** 

Los sistemas informáticos de facturación, deberán adoptar un esquema o forma de conexión para realizar el envío de sus facturas digitales a la Administración Tributaria. Esta interacción entre el Sistema Informático de Facturación autorizado y la Administración Tributaria se puede lograr consumiendo los servicios correspondientes puestos a disposición de los Contribuyentes por INTERNET, línea dedica segura MPLS o punto a punto por FIBRA ÓPTICA. 

## **Internet** 

Las características técnicas mínimas para el uso de INTERNET en cualquiera de las modalidades establecidas en la Resolución Normativa Vigente son las siguientes: 

- Ancho de Banda mínimo: El siguiente cuadro indica la velocidad mínima **recomendada** para la emisión y envío de Facturas Digitales: 

|**AREA**|**VELOCIDAD MÍNIMA**|
|---|---|
|URBANA|1 Mbps|
|RURAL|512 Kbps|



- Se sugiere que el servicio de Internet sea de uso exclusivo para el Sistema Informático de Facturación, para no afectar la comunicación con el SIN, adicionalmente para el envío de paquetes de Facturas Digitales, se deberá considerar un ancho de banda superior a 1 Mbps, si se desea enviar dichos paquetes por el canal de Internet. 

- Se hará uso del protocolo HTTPS con cifrado basado en SSL, para asegurar que la información no sea susceptible de ser interferida u obtenida de manera parcial o total. 

## **Fibra Óptica** 

Los Sujetos Pasivos que, por su giro de negocio, posean múltiples puntos de venta y gran emisión de Facturas Digitales de forma individual o masiva, se recomienda que utilicen un medio de comunicación punto a punto por FIBRA ÓPTICA. 

En el caso de actividades de Telecomunicaciones, Banca, Servicios Básicos (Agua, Luz), Hidrocarburos y otros sectores que vean la necesidad de contar con un punto de conexión directo a través de fibra óptica, deberán verificar con la Administración Tributaria la disponibilidad de puntos de acceso. 

Los Sujetos Pasivos que así lo deseen podrán utilizar la conexión punto a punto por FIBRA ÓPTICA de un tercero conectado a la Administración Tributaria siempre y cuando la calidad de servicio no se degrade  y su proveedor de canal lo haya declarado como cliente al momento de realizar la solicitud de conexión punto a punto. 

## **MPLS** 

En caso de imposibilidad física o logística para utilizar una conexión punto a punto por FIBRA ÓPTICA, los Contribuyentes pueden optar por una conexión segura privada mediante MPLS (que debe ser adquirido con las empresas de Telecomunicaciones). 

**Nota.-** En el caso de uso de conexión Punto a Punto o MPLS, estos deberán ser coordinados por el Sujeto Pasivo con la Administración Tributaria, antes de la Puesta en Producción de su Sistema Informático de Facturación. 

## Esquemas de Despliegue 

Algunos  esquemas de despliegue que pueden usarse son: **cliente-servidor** , se centran en la conexión de una red de clientes a uno o varios servidores; **distribuido** , poseen varios niveles de servidores y deben estar preparados para que su topología pueda ser modificada continuamente; **monolítico** , poseen solo un nivel de conexión y de ejecución es decir un solo servidor que a su vez puede actuar como cliente. 

## **Esquema Monolítico** 

En este tipo de despliegue el cliente también es el servidor del sistema, el cual se encarga del procesamiento y envío de las Facturas Digitales al Servicio de Impuestos Nacionales, en el caso de uso de la firma digital ésta deberá ser manejada por el mismo servidor ya sea a través de un HSM, token o software. Ejemplo un sistema de facturación de un pequeña tienda, que no tiene ni cajeros, ni sucursales. 

## **Esquema cliente-servidor (centralizado)** 

En este tipo de despliegue, el Sistema de Facturación posee un modelo centralizado, orientado a cliente-servidor, las sucursales o puntos de venta (sean fijos o móviles) deberán enviar la información de la Factura Digital a la “central” o casa matriz para que dicha información sea enviada al Servicio de Impuestos Nacionales.  La contingencia de sistemas podrá presentarse cuando la “central” no tenga comunicación con los servidores del Servicio de Impuestos Nacionales, entrando el sistema en modo fuera de línea para la emisión de Facturas Digitales, Para el manejo de la firma digital se admite el uso de token (previendo que por cada transacción deberá ser utilizado), HSM mediante un servidor encargado del firmado o Software. 

Se aclara que para cada sucursal o punto de venta se debe generar un CUIS y CUFD, de manera que se pueda identificar el origen de la emisión de la factura digital, por lo que el sistema informático de facturación debe tener la capacidad de manejar multiples CUIS y CUFD, adicionalmente se cuenta con un método para generación masiva de estos códigos 

## **Esquema distribuido (descentralizado)** 

En este tipo de despliegue el Sistema Informático de Facturación posee un modelo descentralizado, orientado a servidores distribuidos, cada sucursal o punto de venta (sean fijos o móviles) deberán enviar la información de la Factura Digital directamente al Servicio de Impuestos Nacionales. La contingencia de sistemas puede presentarse en cada sucursal o punto de venta, es decir cuando uno de estos no tenga comunicación con el SIN deberá emitir Facturas “fuera de línea”. En este caso el manejo de la firma digital deberá ser independiente por cada sucursal y punto de venta, es decir cada uno de estos deberá manejar la firma digital, ya sea a través de token, HSM o software, cada uno de estos puntos de venta deberá ser capaz de firmas las Facturas electrónicas para que sean enviado al SIN. 

## **SUCURSALES Y PUNTOS DE VENTA** 

## **Sucursales** 

Las sucursales son establecimientos secundarios donde se realiza alguna de las actividades económicas del Contribuyente, tienen una dirección física y están registradas en el Padrón Nacional de Contribuyentes. En el Sistema de Facturación, la emisión de las facturas se realiza por sucursal o casa matriz (sucursal 0). 

## **Puntos de Venta** 

Un punto de venta es un lugar, dispositivo o medio de venta que está asociado a una sucursal o casa matriz y que por su forma de trabajo puede ser fijo (en el caso de puntos de venta para ferias) o móvil (en el caso de camiones de repartidores o de distribución). Los Puntos de Venta no están registrados en el Padrón Nacional de Contribuyentes, pero para fines de facturación, deberán ser registrados a través del Sistema de Facturación y pueden ser: 

- Comisionistas de acuerdo a normativa vigente; 

- Ventanilla de Cobranza autorizada por la ASFI según normativa; 

- Puntos de Ventas Móviles para venta de bienes o prestación de servicios; 

- Puntos de Venta YPFB para la venta de combustible a precio internacional; 

- Puntos de Venta (Cajeros o similares) para la venta de otros bienes y/o prestación de servicios. 

- Puntos de Venta conjunta, habilitada para emisión conjunta de documentos fiscales. 

Para todos los Contribuyentes que estén bajo la modalidad de Facturación Electrónica en Línea y utilicen puntos de venta móviles, se asume que los mismos tienen la capacidad de firmar las Facturas Digitales emitidas o disponen de un servicio centralizado de que les permite firmar los Documentos Fiscales. 

**Consideraciones para Uso** : 

- Conforme normativa vigente los puntos de venta antes de poder ser utilizados, deben ser registrados en el Sistema de Facturación, utilizando para ello los Servicios Web disponibles o a través de la aplicación del Portal Web. 

- Aquellas empresas autorizadas que por su giro de negocio requieren acceder a zonas rurales y/o urbanas sin cobertura o servicio de Internet para la distribución de productos o servicios, deberán  registrar su solicitud a través de la opción que se habilitará en el Portal Web, dicha solicitud se considerará una declaración jurada y deberá contener la justificación del uso de esta excepcionalidad. 

## **Homologación de productos y servicios** 

## Catálogo de Productos 

La Homologación de productos y servicios permite al Contribuyente relacionar los códigos utilizados para sus productos y servicios con los códigos genéricos proporcionados por la Administración Tributaria. El proceso de homologación se muestra a continuación: 

1. Contribuyente a través de su Sistema Informático de Facturación descarga el listado de productos/servicios consumiendo el servicio Web correspondiente. 

2. El “Equipo de homologación” (personal determinado por el Sujeto Pasivo) busca un equivalente para  cada uno de sus productos o servicios en los códigos genéricos del listado que descargo al consumir los servicios de la Administración Tributaria y los relaciona. 

||**Ejemplo Proceso de Homologación**|**Ejemplo Proceso de Homologación**||
|---|---|---|---|
|||**Listado de Productos/Servicios:**||
|**Código**|**Descripción**|**Código de Actividad Económica**||
|**Producto/Servicio**||**(CAEB)**||
|1111|Semillas de trigo, para siembra|106110||
|1377|Nueces del Brasil con cáscara|463010||
|||||
|||||
|**Listado Productos Contribuyente:**||||
|**Código Interno**|**Descripción**|||
|12384E1|Nuez embolsada de 50gr|||
|4654D3|Nuez procesada con sal de 100gr|||
|**Homologación:**||||
|**Código Interno**|**Descripción**|**Código**||
|||**SIN**||
|12384E1|Nuez embolsada de 50gr|1377||
|4654D3|Nuez procesada con sal de 100gr|1377||
|||||



**Nota 1:** El código de producto del SIN deberá reflejarse en el XML de la factura, sin embargo el código visible en la representación gráfica es el "código interno" que utiliza la empresa para clasificar sus productos/servicios. 

En el proceso de homologación se deberá considerar si el origen del producto es importado o nacional para utilizar el producto adecuado, considerando que el código de producto en el SIN diferencia ambos conceptos. 

## **EMISION, ENVIO y ANULACION** 

## **Solicitud Token Delegado** 

Para poder realizar la solicitud del Token Delegado, debemos acceder al Portal SIAT con las credenciales de SIAT en linea 

Seleccionar la opción Sistema de Facturación si Ingresa con las credenciales de SIAT en linea V2 (Omitir si usa las credenciales de SIAT en linea V1) 

seleccione la opción Gestión de Autorización de Sistemas Informáticos de Facturación si desea obtener token para producción 

o seleccione la opción Gestión de Autorización de Sistemas Informáticos de Facturación (Piloto) si desea obtener token para piloto 

Seleccionar la opción Token Delegado en Producción 

o la opción Token Delegado Piloto 

Seleccionar la opción Generar Nuevo Ticket 

## Elegir el NIT correspondiente y la duración del mismo 

## Presione Solicitar para generar el Token correspondiente 

## **Renovación Token Delegado** 

Cuando el token obtenido caduca o esta por caducar,  ingresar a la misma opción e inactivar el TOKEN 

presionando sobre la X 

Una vez hecho esto simplemente obtener un nuevo token presionando el botón **Generar Nuevo Token** 

## **Nota:** 

Para el uso de este Token en los diferentes servicios de facturación, deberá incluir el mismo en el Header de la solicitud,  en Java 

headers.put("apikey", Arrays.asList("TokenApi " + pToken)); 

en la aplicación SoapUI: 

## **Emisión y Envío de Facturas** 

La emisión y envío de Facturas Digitales puede realizarse de manera individual (en tiempo real interactuando en línea con el SIN), por paquetes (en fuera de linea) o de forma masiva. Los pasos a seguir en cada caso se describen a continuación: 

## **Consideraciones antes de la Emisión** 

- Obtener Token Delegado que permite el consumo de los servicios requeridos, el mismo se puede obtener a través del Portal SIAT. 

- Obtener el CUIS (Código Único de Inicio de Sistemas) consumiendo para ello el servicio web correspondiente solo una vez al inicio o cuando se haya vencido la duración del mismo. 

- Obtener el CUFD (Código Único de Facturación Diario) de forma diaria consumiendo el servicio web correspondiente para poder emitir documentos fiscales. 

- Sincronización de catálogos (Actividades, sectores, productos,fecha hora, documento sector) diariamente consumiendo los servicios web correspondientes. 

**Nota:** Si un contribuyente posee varias sucursales y/o puntos de venta, la sincronización de catálogos, puede realizarse una sola vez con la Casa Matriz si el esquema de despliegue es centralizado caso contrario deberá realizar la sincronización por cada sucursal y/o punto de venta. 

Como buena práctica se recomienda periódicamente consumir el servicio Verifica Comunicación. Si como respuesta recibe un valor código de error  (Falso, -1, error de la serie 400 o 500) ingresar automáticamente a modo de facturación fuera de Linea. Asimismo, considere que este método existe en cada recurso disponible, por lo que su implementación debe hacerse por recurso. 

## **Emisión y envío Individual** 

1. Generar Archivo XML asociado al Documento Fiscal, de acuerdo a su actividad económica. 

2. Firmar el archivo obtenido conforme estándar XMLDSig (sólo en el caso de la Modalidad Electrónica en Línea). 

3. Validar contra el XSD asociado a objeto de comprobar que el XML está bien formado y se ajusta a una estructura definida. 

4. Comprimir el archivo XML en formato Gzip, mismo que debe ser enviado en la etiqueta archivo. 

5. Obtener el HASH (SHA 256) del archivo compreso obtenido en el paso anterior, mismo que debe ser enviado en la etiqueta hashArchivo. (también llamado Huella Digital). 

6. Envío Individual consumiendo el servicio de "Recepción de Factura", si no tuviera observaciones, devolverá el estado 908 (validado), en caso contrario devolverá 904 (observado), junto con el código de recepción del mismo, la lista de errores o advertencias (en caso de obtener 904), y la transacción con valor True o False cuando corresponda. 

## **Emisión y envío de Paquetes por Fuera de Linea** 

Se recurre a la emisión de Facturas fuera de línea (OFFLINE), cuando sucede algún evento significativos que impida la emisión de documentos fiscales en línea. En este caso las facturas se emiten individualmente y se agrupan en paquetes de hasta 500 documentos fiscales, para que luego de superada la contingencia se envíen los mismos a la Administración Tributaria a través de los servicios web correspondientes. El procedimiento a seguir es el siguiente: 

Primera Etapa (Mientras dure la contingencia, proceder a emitir las facturas de manera individual) 

1. Registar internamente el inicio del evento, junto con el motivo, para posteriormente 

2. Generar Archivo XML asociado al Documento Fiscal, de acuerdo a su actividad 

   - económica (utilizar modalidad fuera de linea). 

3. Firmar el archivo obtenido conforme estándar XMLDSig (sólo en el caso de la Modalidad Electrónica en Línea). 

4. Validar contra el XSD asociado a objeto de comprobar que el XML está bien formado y se ajusta a una estructura definida. 

5. Almacenar temporalmente de manera individual las Facturas generadas. 

Segunda Etapa (una vez superada la contingencia) 

1. Recuperar las Facturas almacenadas en formato XML durante la etapa anterior. 

2. Formar paquetes de hasta 500 Facturas. 

3. Comprimir con Gzip, el archivo resultante debe ser enviado utilizando para ello la etiqueta archivo. 

4. Obtener el HASH (SHA256) del archivo compreso obtenido en el paso anterior, mismo que debe ser enviado en la etiqueta hashArchivo. 

5. Envío de Paquetes de Facturas: 

- Consumir el servicio correspondiente para obtener un nuevo CUFD. 

- Registrar el evento significativo a través del servicio web correspondiente, indicando la fecha de inicio y fin del evento, así como el CUFD que fue usado para la emisión de facturas de contingencia. 

- Enviar los paquetes consumiendo el servicio "Recepción de Paquetes de facturas electrónicas o computarizadas". Si la transacción es exitosa, se devolverá el estado 901 (pendiente), el código de recepción del mismo y la transacción en True. 

- Validar la recepción consumiendo el servicio de "Validación de Paquetes de facturas electrónicas o computarizadas", mismo que devolverá el código de estado que puede ser 901 (pendiente), 904 (observada) o 908 (validado). En el caso de que existan observaciones se incluirá una lista de mensajes con códigos, descripciones, número de archivo y número de detalle de los errores y/o advertencias detectados en cada una de las facturas. 

**Nota:** Como buena practica, debe mantenerse un registro de facturas sin código de respuesta, una vez superada la contingencia las mismas se verifiquen consumiendo el servicio verificaciónEstadoFactura a objeto de identificar si tienen registro o no en el Servicio de Impuestos Nacionales y proceder a su anulación en caso de ser necesario. 

## **Emisión y envío de Paquetes Masivos** 

Se utiliza el envío masivo cuando por el giro de negocio de la empresa, se requiere de la generación de Facturas en grandes cantidades por lotes como es el caso de las entidades financieras, empresas de telecomunicaciones y de servicios básicos. Para poder utilizar la emisión de esta forma se debe registrar a través del Portal Web de la Administración Tributaria: 

- Periodicidad con la que se enviará: diario, semanal o mensual. 

- Tamaño de los paquetes: máximo 1000 Facturas. 

## Primera Etapa 

1. Generar Archivo XML asociado al Documento Fiscal, de acuerdo a su actividad económica (utilizar modalidad en linea). 

2. Firmar el archivo obtenido conforme estándar XMLDSig (sólo en el caso de la Modalidad Electrónica en Línea). 

3. Validar contra el XSD asociado a objeto de comprobar que el XML está bien formado y se ajusta a una estructura definida. 

4. Almacenar temporalmente de manera individual las Facturas generadas. 

## Segunda Etapa 

1. Recuperar las Facturas almacenadas en formato XML durante la etapa anterior. 

2. Formar paquetes de hasta 1000 Facturas. 

3. Comprimir con Gzip el archivo resultante debe ser enviado en la etiqueta archivo. 

4. Obtener el HASH (SHA256) del archivo compreso obtenido en el paso anterior, mismo que debe ser enviado en la etiqueta hashArchivo. 

5.  Envío de Paquetes de Facturas: 

- Enviar los paquetes consumiendo el servicio "Recepción Masiva Facturas electrónicas o computarizadas". Si la transacción es exitosa, se devolverá el estado 901 (pendiente), el código de recepción del mismo y la transacción en True. 

- Validar la recepción consumiendo el servicio de "Validación de Recepción Masiva de facturas electrónicas o computarizadas", mismo que devolverá el código de estado que puede ser 901 (pendiente), 904 (observada) o 908 (validado). En el caso de que existan observaciones se incluirá una lista de mensajes con códigos, descripciones, número de archivo y número de detalle de los errores y/o advertencias detectados en cada una de las facturas. 

## **Emisión y envío de Paquetes por Contingencia** 

La emisión de Facturas Manuales de Contingencia se produce cuando el sistema que genera las facturas no esta disponible debido a un evento significativo de tipo (corte de energía, falla de software o falla de hardware). En este caso y para no parar el negocio, se puede recurrir a la emisión de Facturas Manuales de Contingencia (previamente solicitadas e impresas a través de una imprenta autorizada).  Superada el evento de contingencia se puede proceder de la siguiente manera: 

## 0. Envío del evento: 

Se debe registrar el evento a través del servicio disponible para el efecto indicando: 

- fecha de inicio (hasta el minuto mínimamente) 

- fecha de fin (hasta el minuto mínimamente) 

- código de evento (5,6 o 7) 

- cufd del evento (debe corresponder a la fecha en la cual se tuvo el evento) 

- cufd del envío 

- descripción (descripción del evento ocurrido) 

## 1. Primera Etapa (Transcripción): 

   1. Generar Archivo XML transcribiendo la información contenida en la factura manual, con tipo de emisión "fuera de linea" (2), utilizar el CUFD que estaba vigente al ingresar en contingencia y registrado en el evento (completar todos los campos requeridos) 

   2. Firmar el archivo obtenido conforme estándar XMLDSig (sólo en el caso de la Modalidad Electrónica en Línea). 

   3. Validar contra el XSD asociado a objeto de comprobar que el XML está bien formado y se ajusta a una estructura definida. 

   4. Almacenar temporalmente de manera individual las Facturas generadas. 

2. Segunda Etapa (Armado de paquetes): 

   1. Recuperar las Facturas transcritas y en formato XML durante la etapa anterior. 

   2. Formar paquetes de hasta 500 Facturas. 

   3. Comprimir con Gzip, el archivo resultante debe ser enviado en la etiqueta archivo. 

   4. Obtener el HASH (SHA256) del archivo compreso obtenido en el paso anterior, mismo que debe ser enviado en la etiqueta hashArchivo. 

   5. Envío de Paquetes de Facturas: 

   - Consumir el servicio correspondiente para obtener un nuevo CUFD . 

   - Enviar los paquetes consumiendo el servicio "Recepción de Paquetes de facturas electrónicas o computarizadas", incluyendo el código de recepción del evento y el 

   - CAFC de las facturas transcritas. Si la transacción es exitosa, se devolverá el estado 901 (pendiente), el código de recepción del mismo y la transacción en True. 

- Validar la recepción consumiendo el servicio de "Validación de Paquetes de facturas electrónicas o computarizadas", mismo que devolverá el código de estado que puede ser 901 (pendiente), 904 (observada) o 908 (validado). En el caso de que existan observaciones se incluirá una lista de mensajes con códigos, descripciones, número de archivo y número de detalle de los errores y/o advertencias detectados en cada una de las facturas. 

## **Nota** . 

- Para el ambiente de pruebas (PILOTO) deberá solicitar CAFC para los documentos que esta autorizando, así como para las sucursales que probaran. 

- Los Códigos Especiales 99001 (Utilizado para consulados, embajadas, etc), el 99002 (Control Tributario) y el 99003 (Ventas Menores del Día) se deben enviar con el tipo de documento NIT y el código de Excepción en 1. 

- Si durante la emisión  se utiliza como tipo de documento C.I. o NIT el sistema emisor debe validar que el valor que se envía sea numérico. 

- El código de excepción debe enviarse por defecto con un valor de 0 (cero). Se envía con un valor de 1 (uno) solo si el Tipo de documento es un NIT pidiendo de esta manera al SIN no validar el mismo. Por otro lado, si la emisión es en fuera de linea y el tipo de documento NIT siempre enviar el código de excepción  con un valor de 1. 

- El tipo de emisión "CONTINGENCIA" que se obtiene al realizar la sincronización de catálogos es para uso exclusivo del SIN. 

- No se puede realizar emisión de facturas utilizando para ello actividades económicas de Importación 

## **Anulación de Documentos Fiscales** 

De acuerdo a normativa vigente, la anulación de documentos Fiscales emitidos en las modalidades electrónica en linea, computarizada en línea y Portal Web en línea se debe realizar de forma individual consumiendo el servicio proporcionado para tal efecto hasta el día nueve (9) del mes siguiente de su emisión. 

Se podrá realizar siempre y cuando el documento original este registrado en la Base de Datos de la Administración Tributaria como un documento válido  y no haya sido utilizado en la presentación de alguna Declaración Jurada. 

La anulación podrá ser realizada desde la misma sucursal en la cual se origino la transacción o desde otra sucursal habilitada. 

Toda anulación realizada debe ser  notificada al comprador a través del correo electrónico u otros medios electrónicos que garanticen la privacidad del mismo, informándole como mínimo el Código de Autorización, número de factura y  motivo de esta operación. 

## **Reversión de la Anulación de Documentos Fiscales** 

De acuerdo a normativa vigente, en caso de darse la anulación errónea de Documentos Fiscales, el Sujeto Pasivo del IVA podrá a través de su Sistema Informático de Facturación o la opción habilitada en la modalidad Portal Web en Línea según corresponda de acuerdo a la modalidad de facturacion utilizada, revertir por única vez la anulación y cambiar el estado de un Documento Fiscal a “VALIDO” hasta el día nueve (9) del mes siguiente de la emisión de la factura original. 

Durante la reversión, de no existir observaciones el sistema devolverá el estado 907 (Reversión Anulada Conforme), 981 (factura no disponible para reversión), 924 (Factura no Existe en la Base de Datos), 3011 (Sistema no supero las pruebas de autorización para utilizar la reversión) ó 3012 (Solicitud de Reversión fuera de plazo). 

La Reversión de la anulación podrá ser realizada desde la misma sucursal en la cual se origino la transacción o desde otra sucursal habilitada. 

Toda reversión debe ser notificada al comprador a través del correo electrónico u otros medios electrónicos que garanticen la privacidad del mismo informándole de esta operación. 

**Nota** :  Los Documentos Fiscales revertidos no podrán volver a ser anulados. 

## **Contingencia y Eventos Significativos** 

Los eventos significativos son hechos inherentes al Sistema informático de Facturación que intervienen en su funcionamiento o que podrían afectar la emisión de las Facturas Digitales. Deben ser registrados hasta 48 horas posteriores de finalizada la contingencia, a través del sistema autorizado por la Administración Tributaria y enviados automáticamente a través del servicio Web correspondiente. 

## **Tipos de Eventos Significativos que generan contingencia** 

EVENTO SIGNIFICATIVO DETALLE DE ACCIÓN 

1) Corte del servicio de Internet 

2) Inaccesibilidad al Servicio Web de la Administración Tributaria. 

**==> picture [289 x 76] intentionally omitted <==**

Emitir Documentos Fiscales digitales fuera de línea, conforme lo establecido en el Anexo Técnico de la presente Resolución. 

3) Ingreso a zonas sin Internet por despliegue de puntos de venta. 

4) Venta en Lugares sin internet. 

**==> picture [289 x 142] intentionally omitted <==**

5) Virus informático o falla de Emitir Facturas por Contingencia autorizadas por la software. Administración Tributaria, solicitadas con anterioridad por el Sujeto Pasivo del IVA o emitir Documentos Fiscales Digitales usando de manera transitoria y por contingencia la Modalidad de 

|6) Cambio de infraestructura de|Facturación Portal Web en línea conforme los aspectos técnicos||
|---|---|---|
|sistema o falla de hardware.|establecidos en el Anexo Técnico de la presente Resolución.||
|7) Corte de suministro de energía|Emitir Facturas por Contingencia autorizadas por la||
|eléctrica.|Administración Tributaria, solicitadas con anterioridad por el||
||Sujeto Pasivo del IVA.||



De producirse una contingencia, pero el sistema informático continua operativo, este deberá cambiar a la emisión de facturas fuera de línea, las facturas se emiten con el CUFD vigente hasta antes del corte. Las facturas emitidas se almacenan en paquetes que posteriormente serán enviados a la administración Tributaria, cuando la contingencia se haya superado. (Obtener un nuevo CUFD antes de registrar el evento significativo y enviar los paquetes, a fin de evitar posibles inconvenientes relacionados al tiempo de vigencia del CUFD durante el envío de los mismos de no hacerlo). 

En caso de que no pueda utilizarse el sistema informático por falla de hardware, software o por corte de energía eléctrica, se deberán emitir facturas manuales de contingencia previamente aprovisionadas, superada la contingencia estas deberán ser transcritas utilizando para ello el CUFD que estaba vigente al ingresar en contingencia y enviadas a la Administración Tributaria a través del mismo sistema informático de facturación. (Obtener un nuevo CUFD antes de registrar el evento significativo y enviar los paquetes, a fin de evitar posibles inconvenientes relacionados al tiempo de vigencia del CUFD durante el envío de los mismos de no hacerlo). 

**Nota:** Como buena practica, debe mantenerse un registro de facturas sin código de respuesta, a objeto de que una vez superada la contingencia se verifiquen las mismas consumiendo el servicio verificaciónEstadoFactura a objeto de identificar si fueron registradas o no en el Servicio de Impuestos Nacionales y de ser asi proceder a su anulación de ser necesario evitando duplicidades. 

SI el tipo de documento utilizado en la emisión de una factura en fuera de linea es el NIT, se debe enviar el código de excepción con valor uno. 

## **Ingreso a Contingencia** 

Se describen procedimientos y lineamientos que se recomienda seguir ante la presencia de un evento significativo 

## **Fuera de Línea** 

Si al consumir el servicio de verificar comunicación previa a la emisión de un Documento Fiscal recibimos como respuesta un Time Out, -1, Java Null Point o Http 500 y luego de intentar un par de veces más la respuesta es la misma, indica un problema de comunicación, por lo que para dar continuidad a nuestras operaciones debemos ingresar a Fuera de Linea. (Se recomienda permanecer en fuera de linea por un tiempo prudencial de 

acuerdo a las carácterísticas del negocio (no mayor a dos horas) antes de consumir nuevamente los servicos para verificar la comunicación y volver a la emisión de facturas en linea. Recuperada la comunicación obtener un nuevo CUFD, registrar el evento significativo y enviar los paquetes de facturas emitidas. 

## **Fuera de Línea Servicios** 

Si al consumir el servicio despues de haber verificado la comunicación  recibimos como respuesta un error 400 ó 404 Time Out y luego de intentar un par de veces más la respuesta es la misma, indica un problema de comunicación, por lo cual se debe ingresar a Fuera de Linea para poder seguir con la emisión de facturas y dar continuidad a las operaciones. Se recomienda permanecer en fuera de linea por un tiempo prudencial de acuerdo a las carácterísticas del negocio (no mayor a dos horas) antes de reintentar consumir nuevamente los servicos para verificar la comunicación y volver a la emisión de facturas en linea. Recuperada la comunicación obtener un nuevo CUFD, registrar el evento significativo y enviar los paquetes de facturas emitidas. 

## **Emisión de Facturas** 

Adicionalmente a lo anterior si recibimos como respuesta un Time Out, -1, Java Null Point o Http 500 durante la emisión de una factura una vez recuperada la comunicación obtener un nuevo CUFD y **verificar el estado de la factura emitida** consumiendo el servicio correspondiente. Si se encuentra registrada en los Servidores del SIN, proceder a su anulación luego registrar el evento significativo y enviar los paquetes de facturas emitidas. 

## **Anulación de Facturas** 

Si al consumir el servicio de anulación recibimos como respuesta un Time Out, -1, Java Null Point o Http 500 y luego de intentar un par de veces más la respuesta continua siendo la misma indica que el servicio específico que estamos requiriendo tiene algún problema por lo cual debemos esperar un tiempo prudencial para realizar nuevamente la anulación. Transcurrido un tiempo tiempo y antes de intentar la anulación nuevamente debemos verificar el estado de la factura Si esta figura en los Servidores del Servicio de Impuestos Nacionales como anulada simplemente completar la anulación de forma local,  pero si aparece como válida proceder con la anulación nuevamente. 

## **CUFD** 

Si al consumir el servicio de solicitud de CUFD  recibimos como respuesta un Time Out, -1, Java Null Point o Http 500 y luego de intentar un par de veces más la respuesta continua siendo la misma indica que el servicio específico que estamos requiriendo tiene algún problema por lo que para dar continuidad a nuestras operaciones debemos ingresar a Fuera de Linea y emitir facturas utilizando el último CUFD válido pues en casos como este la duración del CUFD se amplia hasta a 72 horas.  Se recomienda permanecer en fuera de linea por un tiempo prudencial de acuerdo a las carácterísticas del negocio (no mayor a dos horas) antes de reintentar consumir nuevamente los servicos para para obtener un nuevo CUFD. Si la respuesta es positiva, registrar el evento significativo y enviar los paquetes de facturas emitidas. 

## **Comprobante de Transacción** 

Considerando el giro del negocio y con el propósito de dar cumplimiento a lo dispuesto en el artículo 26 de la Resolución Normativa de Directorio N° 102100000011, referido al envío de la transacción en formato XML y de la Representación Gráfica del documento fiscal al correo electrónico del comprador o mediante otros medios electrónicos (WhatsApp, Instragram) que garanticen la privacidad de la información, se ha identificado que diversos contribuyentes optan por facilitar al comprador un comprobante de transacción que contenga los medios de acceso electrónico a dicha información. 

En este contexto, el **comprobante de transacción** deberá incorporar, de forma visible: 

Un **enlace web** que dirija al portal **del sistema de facturación Propio o Proveedor** , ó un **código QR (Quick Response)** que permita el acceso directo, ágil y seguro al enlace correspondiente al portal del **sistema de facturación Propio o Proveedor** , garantizando el cumplimiento de las disposiciones técnicas y de seguridad establecidas por la Administración Tributaria. 

El **código QR o el enlace web** deberán permitir al comprador consultar **en formato XML y la Representación Gráfica del documento fiscal** emitido, asegurando la disponibilidad, integridad y confidencialidad de la información, conforme a las especificaciones técnicas del **Sistema de Facturación en Línea** . 

## **Recomendaciones Técnicas de Dimensión y Legibilidad** 

- Se recomienda que el **código QR** no tenga un tamaño inferior a **3 x 3 cm** . 

- El código debe ser **claramente visible** y **ubicado en un área accesible** del comprobante impreso o digital. 

- La impresión o visualización del código debe garantizar una **lectura correcta** mediante dispositivos de **capacidad y calidad media** (como teléfonos móviles, tabletas u otros dispositivos con cámara estándar). 

- En caso de utilizar un **enlace web** , este debe presentarse de forma legible y funcional, evitando redireccionamientos innecesarios y asegurando su disponibilidad permanente. 

## **PROCESO DE AUTORIZACION Y RENOVACION DE SISTEMAS INFORMATICOS DE FACTURACION** 

## **Proceso de Autorización** 

Se realiza con la finalidad de permitir que un sistema Informático pueda interactuar con el Servicio de Impuestos Nacionales para la emisión de Facturas Digitales. Este proceso se logra superando exitosamente todas las etapas del proceso conocido como Autorización de Sistemas. 

## **Requisitos** 

Para poder realizar la solicitud de autorización se debe cumplir con los siguientes requisitos: 

- NIT del solicitante debe estar activo 

- Tener obligación tributaria IVA 

- No tener marcas de control o contravenciones tributarias 

## **Solicitud de Autorización de Sistemas Informáticos de Facturación** 

Si se cumplen los requisitos especificados en la normativa la solicitud se realiza ingresando a PRODUCCIÓN (https://siat.impuestos.gob.bo/launcher/) con las credenciales de SIAT en Linea. Considerar que si el inicio se realiza utilizando la autenticación SIAT en Linea v1 al ingresar buscar la opción "Gestión de Autorización de Sistemas (PILOTO)" o buscar la opción "Sistemas de Facturación" y luego "Gestión de Autorización de Sistemas (PILOTO)" si utiliza la autenticación SIAT en Linea v2. Una vez alli seleccionar la opción “Autorización de Sistemas Informáticos de Facturación”, luego "Seguimiento de Sistemas" y finalmente presionar en "Nuevo Sistema", donde se deberá ingresar los datos del Sistema Informático de Facturación como son: 

- Nombre Comercial. Ingresar el nombre con el cual se conocerá el sistema. 

- Tipo. Si será para uso propio o de proveedor (Si será utilizado por otras Contribuyentes). 

- Versión. Ingresar la versión del sistema 

- Marca de Proceso Masivo. Marca que determina que el Proveedor o Propietario realicen pruebas adicionales de envío de paquete de facturas por emisión Masiva, para la autorización del sistema. 

- Modalidad de Facturación. Ingresar la modalidad bajo la cual operará el sistema. 

- Tipo Documento Sector. Permite seleccionar los tipos de documentos que manejará el sistema, de acuerdo a la actividad que posee el Contribuyente en caso de ser sistema propio y las que desee ofertar en caso de sistema proveedor. 

Posteriormente, se deberá ingresar los Datos acerca de la(s) persona(s) de contacto: 

- Nombre Completo: Introducir el nombre completo de la persona de contacto. 

- Tipo Documento: Introducir el tipo de documento de identidad. 

- Número Documento: Introducir el número del documento de identidad seleccionado. 

- Complemento: Introducir el número de complemento en caso de contar con uno. 

- Correo Electrónico: Introducir un correo electrónico válido. 

- Celular: Introducir un número de celular valido. 

Finalizado el proceso de registro se genera un reporte con el detalle de la solicitud efectuada, que incluye el código de sistema asignado, parámetros constantes para el consumo de servicios y las direcciones a ser utilizadas para efectuar las diferentes pruebas documentadas en las Fases de Autorización. 

## **Consideraciones para Modalidad Facturación Electrónica en Línea** 

Para iniciar el proceso de autorización de sistemas que utilicen la modalidad de facturación electrónica en línea, los contribuyentes deberán contar con una firma digital, para ello podrán realizar la solicitud de una firma digital por software de prueba, que deberá ser solicitada a la administración tributaria, siguiendo el siguiente procedimiento: 

1. El contribuyente deberá generar el CSR de acuerdo a los campos definidos por la AGETIC. 

2. Una vez tenga el CSR generado, deberá enviarlo al SIN a través del correo siat.facturacion@impuestos.gob.bo 

3. El SIN a través de correo electrónico enviara el certificado publicado firmado tanto por el SIN, como por la AGETIC 

4. Ya con el certificado publico y privado, podrá firmar las facturas electrónicas. 

## **Etapas del Proceso de autorización** 

El proceso de autorización de sistemas consta de tres fases: 

**Fase 1:** Etapa donde se realizan una serie de pruebas mínimas necesarias para la emisión y envío de facturas al SIN 

**Fase 2** : Pruebas de Funcionalidad e Inspección, donde la Administración Tributaria realizará la verificación de las funcionalidades mínimas requeridas por un sistema informático de facturación para poder ser autorizado. Esta fase será coordinada entre la Administración Tributaria y el contribuyente, teniendo en cuenta que puede realizarse físicamente o virtualmente, en función de los criterios establecidos por el SIN. 

**Fase 3:** Tiene por objetivo garantizar y evaluar la implementación del Sistema Informático de Facturación autorizado, mediante la realización de  pruebas Funcionales y de Carga, estas pruebas no son contabilizadas o controladas por la Administración Tributaria, siendo responsabilidad del contribuyente a través de un sistema "propio" o "proveedor" que debe realizarlas. 

## **Nota** : 

Todos aquellos sistemas WEB y API deben realizar la autorización por separado. Autorizando por un lado el Sistema Web y por otro la API como tal. 

Los sistemas de tipo proveedor deben implementar todas las funcionalidades mínimas. 

## **Solicitud de Prórroga en el Proceso de Autorización** 

De acuerdo a normativa vigente, se otorga un plazo de 90 días a fin de poder autorizar un sistema propio o proveedor. Si al cabo del mismo, el desarrollo no esta terminado o no se superan todas las pruebas de autorización, se puede acceder a una prorroga de 60 días más a fin de poder completar esta tarea. Para obtener esta prorroga se debe: 

Ingresar  a PRODUCCIÓN (https://siat.impuestos.gob.bo/launcher/) con las credenciales de SIAT en Linea. Considerar que si el inicio se realiza utilizando la autenticación SIAT en Linea v1 al ingresar buscar la opción "Gestión de Autorización de Sistemas (PILOTO)" o buscar la opción "Sistemas de Facturación" y luego "Gestión de Autorización de Sistemas (PILOTO)" si utiliza la autenticación SIAT en Linea v2. 

- Ingresar a la opción Autorización de Sistemas. 

- Seleccionar la opción Solicitud de Prorroga. 

- Seleccionar el Sistema correspondiente. 

- Presione la opción de solicitud e introduzca el motivo de la misma. 

- Confirmar la solicitud. 

**Nota** : La solicitud de autorización sera cancelada automáticamente si después de 90 días y posterior prorroga de 60 días no se completan todas las pruebas establecidas. Debiendo iniciarse una nueva solicitud de autorización. 

## **Inhabilitación de Sistemas Informáticos Autorizados** 

La baja o inhabilitación de un Sistema Informático de Facturación Autorizado se puede realizar a través del portal Web de la Administración Tributaria si se cumple que: 

1. El Sistema de tipo "Sistema Proveedor" no esté asociado a otro Contribuyente. 

2. No este habilitado en ninguna sucursal o punto de venta. 

3. No se encuentre observado por la administración tributaria. 

Ingresando a PRODUCCIÓN (https://siat.impuestos.gob.bo/launcher/) con las credenciales 

de SIAT en Linea. Considerar que si el inicio se realiza utilizando la autenticación SIAT en Linea v1 al ingresar buscar la opción "Gestión de Autorización de Sistemas (PILOTO)" o buscar la opción "Sistemas de Facturación" y luego "Gestión de Autorización de Sistemas (PILOTO)" si utiliza la autenticación SIAT en Linea v2. 

1. Contribuyente solicita la inhabilitación del Sistema Autorizado. 

2. El SIN verifica si la solicitud efectuada cumple con los requisitos exigidos. 

3. El SIN confirma o rechaza la solicitud. 

## **Solicitud de Nueva Autorización** 

La RND 102100000011 Sistema de Facturación en su Artículo 20 (Proceso de Autorización de los Sistemas Informáticos de Facturación en el inciso B señala que “La Autorización del Sistema Informático de Facturación emitida por la Administración Tributaria tendrá una validez de tres (3) años computables desde su emisión de manera previa a la conclusión de este plazo, el Propietario o Proveedor deberá solicitar una nueva Autorización, sujetándose a las pruebas de funcionalidad que estén vigentes” 

## **Solicitud de Nueva Autorización** 

Para poder realizar la solicitud de nueva autorización, acceder al Portal SIAT con las credenciales de SIAT en linea 

Seleccionar la opción Sistema de Facturación si Ingresa con las credenciales de SIAT en linea V2 (Omitir si usa las credenciales de SIAT en linea V1) 

seleccione la opción Gestión de Autorización de Sistemas (PILOTO) 

Ingresar a la Opción Seguimiento de Sistemas Informáticos 

Donde se habilitara un botón que permitirá solicitar la nueva autorización 

Si el sistema es de tipo propio, complete la información requerida especificando todas las funcionalidades que utilizará en el sistema y presione el botón Solicitar. (Tomar en cuenta que emisiones con características diferentes a las elegidas serán observadas). 

En ambos se ajustaran las pruebas en entorno piloto, por lo que los solicitantes deberán completar nuevamente las mismas y presionar el botón Finalizar Pruebas que habilitara al Sistema para que se pueda programar la fecha y hora de la inspección. Si la inspección se supera el sistema será autorizado  por un plazo adicional de tres años, de no ser así y conforme a normativa vigente el código de sistema no podrá ser utilizado nuevamente. 

## **Consideraciones:** 

Solo podrán solicitar la renovación aquellos sistemas que se encuentren autorizados y en producción. 

Una vez realizada la solicitud de nueva autorización y finalizadas las nuevas pruebas en ambiente piloto, se dispondrá de 3 oportunidades para culminar la etapa de inspección. 

Aquellos sistemas que no realizaron la solicitud de nueva autorización y hayan alcanzado fecha de autorización. No podrán utilizar nuevamente el código de sistema asignado. 

## **Registro de Funcionalidades** 

Para poder realizar el registro de funcionalidades tiene que acceder al Portal SIAT con las credenciales de SIAT en linea 

Seleccionar la opción Sistema de Facturación si Ingresa con las credenciales de SIAT en linea V2 (Omitir si usa las credenciales de SIAT en linea V1) 

seleccione la opción Gestión de Autorización de Sistemas (PILOTO) 

## **SISTEMA PROPIO** 

Ingresar a la Opción Seguimiento de Sistemas Informáticos 

Donde se habilitara un botón que permitirá registrar las características del sistema 

## **CONTRIBUYENTES ASOCIADOS** 

Ingresar a la Confirmación de Asociación de Sistemas 

## **ASOCIACIÓN, CONFIRMACIÓN Y RENOVACIÓN DE SISTEMAS INFORMÁTICOS DE FACTURACIÓN** 

## **Asociación de Sistemas** 

Este procedimiento habilita a un contribuyente para poder utilizar el Sistema Autorizado de un proveedor y  efectuar la emisión de sus facturas con el mismo. El proceso consta de dos etapas: la Asociación y la confirmación. 

## **Asociación** 

El proveedor del sistema en el ambiente PILOTO registra al contribuyente al cual permitirá el uso de su sistema ingresando a PRODUCCIÓN (https://siat.impuestos.gob.bo/launcher/) con las credenciales de SIAT en Linea. Considerar que si el inicio se realiza utilizando la autenticación SIAT en Linea v1 al ingresar buscar la opción "Gestión de Autorización de Sistemas (PILOTO)" o buscar la opción "Sistemas de Facturación" y luego "Gestión de Autorización de Sistemas (PILOTO)" si utiliza la autenticación SIAT en Linea v2. 

Selecciona el menú Asociación de Sistemas (RND-102100000011) 

Elige la opción Asociación de Sistemas e ingresa la siguiente información 

- 

El NIT del contribuyente (deberá ser válido y estar activo). 

- Logín del usuario con el cual ingresa al sistema 

- El nombre del Sistema que está asociando. 

- Modalidad a la que se esta asociando 

- El tipo de servicio que utilizara: 

   - **Licencia:** cuando el Sistema Informático de Facturación es cedido a un tercero mediante el uso de licencias por ejemplo: eula, aluf, cluf, gnu, cddl u otras esta licencia queda reflejada en un acuerdo entre partes que debe tener un documento electrónico o físico de respaldo. 

   - **Alquiler:** cuando se cede no solamente el Sistema Informático de Facturación, si no también el Hardware necesario para su funcionamiento, temporalmente y por tiempo limitado. 

   - **Prestación de servicio:** cuando se haga uso de un Servicio Web de cualquier tipo. Por ejemplo SOAP o Rest, al cual el Sistema del Contribuyente enviará solamente la información de la Facturación necesaria para que el Sistema Proveedor genere y emita correctamente la factura digital. En este tipo de servicio se debe definir el tiempo de prestación de servicio. El contribuyente debe ceder mediante algún mecanismo su firma digital (para la modalidad de Facturación Electrónica en Línea) y un usuario de oficina virtual. 

   - **Facturación por terceros:** cuando la generación de la información de la factura y la emisión de la misma, es atendida solamente por el Sistema Proveedor, por ejemplo: Facturación de colegios, donde el colegio debe obtener y ceder un token delegado al tercero (Proveedor de Sistema). 

   - **Facturación Conjunta:** 

   - **Comisionistas:** 

- El o los sectores disponibles para el Contribuyente (solo podrá asociar sectores a los que esté autorizado el Contribuyente) 

- Correo electrónico del Contribuyente al cual se le enviara un mensaje para que confirme la asociación. 

## **Confirma Asociación** 

El contribuyente a través de la opción **Confirmación de Asociación** del Portal SIAT confirmará o rechazará el uso del sistema al cual su NIT fue asociado por el Proveedor, para ello debe ingresar al Portal SIAT 

Seleccionar el menú Sistema de Facturación Versión 2 

Elegir la opción Confirmación de la Asociación, que desplegara la vista donde podrá realizar pruebas piloto antes de aceptar o rechazar la asociación 

Se despliega una vista con todos los sistemas que asociaron su NIT. Solo se habilita  la opción Pruebas Piloto. Que le permiten decidir si el sistema es adecuado o no. Al seleccionar esta opción se genera un documento con las especificaciones para la realización de dichas pruebas 

Si esta conforme con las mismas, se acepta la asociación previa confirmación de la misma 

Si se acepta, se emite la autorización de asociación 

## **Inicio de Operaciones** 

Permite llevar el sistema autorizado de Piloto a Producción para poder realizar la emisión de Documentos Fiscales Electrónicos en un ambiente productivo, para ello seleccionamos del menú la opción de Inicio y Cierre de Operaciones, que nos despliega una ventana que muestra los sistemas asociados al NIT 

Al presionar sobre el botón de Inicio de Operaciones se nos despliega un formulario emergente 

} 

donde se deben registrar algunos datos como la fecha de inicio de operaciones, el tipo de servicio de Internet y la empresa que nos provee del mismo. Al hacer click en aceptar, se habilita el sistema en producción y se brindan las direcciones de los servicios productivos. 

Una vez hecho esto y ya en el ambiente productivo debemos proceder a Obtener un nuevo Token (diferente al utilizado en ambiente Piloto) que permita el consumo de los servicios requeridos. El mismo se obtiene a través del Portal SIAT y  puede tener una duración variable, Obtener el CUIS (Código Único de Inicio de Sistemas) consumiendo para ello el servicio web correspondiente solo 1 vez al inicio o cuando se haya vencido la duración del mismo. Obtener el CUFD (Código Único de Facturación Diario) de forma diaria consumiendo el servicio web correspondiente para poder emitir documentos fiscales. Sincronizar de catálogos (Actividades, sectores, productos,fecha hora, documento sector) diariamente consumiendo los servicios web correspondientes. Realizar la homologación de sus productos. Definir de ser necesario puntos de venta. 

## **SERVICIOS SOAP** 

## **Notifica Certificado Revocado** 

El Sistema Informático de Facturación del Sujeto Pasivo deberá informar de las firmas digitales revocadas o suspendidas a través de este Servicio Web, mismo que inhabilita el CUIS y el CUFD vigente, de manera automática no pudiendo realizar la emisión de Facturas Digitales a partir de ese momento, hasta que se tenga firma valida habilitada. 

El servicio implementado posee un objeto denominado notificaCertificadoRevocado el cual contiene la información descrita en el siguiente cuadro: 

## **Nombre Método:** solicitudNotificaRevocado 

|**Entrada**<br>**Tipo**<br>**Dato**<br>**Obligatorio**<br>**Descripción**<br>**Salida**<br>**Tipo Dato**|**Entrada**<br>**Tipo**<br>**Dato**<br>**Obligatorio**<br>**Descripción**<br>**Salida**<br>**Tipo Dato**|**Entrada**<br>**Tipo**<br>**Dato**<br>**Obligatorio**<br>**Descripción**<br>**Salida**<br>**Tipo Dato**|**Entrada**<br>**Tipo**<br>**Dato**<br>**Obligatorio**<br>**Descripción**<br>**Salida**<br>**Tipo Dato**|**Entrada**<br>**Tipo**<br>**Dato**<br>**Obligatorio**<br>**Descripción**<br>**Salida**<br>**Tipo Dato**|**Entrada**<br>**Tipo**<br>**Dato**<br>**Obligatorio**<br>**Descripción**<br>**Salida**<br>**Tipo Dato**|
|---|---|---|---|---|---|
|**codigoAmbie**<br>**nte**|Numérico|Si|Describe el tipo de<br>ambiente<br>utilizado,<br>los<br>valores<br>permitidos son:<br>Producción: 1<br>Pruebas y Piloto: 2|**transaccion**|Boolean|
|**codigoSistem**<br>**a**<br>Alfanumérico<br>Si<br>Código de Sistema<br>que le fue asignado<br>al<br>momento<br>de<br>realizar la solicitud<br>de autorización.<br>**codigosRes**<br>**puestas**<br>DTO[codigos<br>Respuesta]||||||
|**nit**|Numérico|Si|NIT perteneciente al<br>emisor<br>de<br>la<br>Factura.|||
|**cuis**<br>Alfanumérico<br>Si<br>Valor único para una<br>sucursal y/o punto<br>de venta que se<br>obtiene al realizar el<br>inicio<br>de<br>uso de<br>sistemas.<br> <br>||||||



|**codigoSucurs**<br>**al**|Numérico|Si|Valor que identifica a<br>la sucursal donde se<br>realiza la emisión de<br>la Factura:<br> Casa Matriz: 0<br> Sucursal: 1,2,..,n|||
|---|---|---|---|---|---|
|**fechaRevocac**<br>**ion**<br>Date<br>Si<br>Fecha de revocación<br>del Certificado<br> <br>||||||
|**razonRevocac**<br>**ion**<br>Alfanumérico<br>Si<br>Se envía el motivo<br>de la revocación.<br> <br>||||||
|**certificado**|Alfanumérico|Si|En este campo se<br>envía el certificado<br>que<br>haya<br>sido<br>revocado.|||



## **Nota:** 

Este servicio requiere el uso del Token Delegado 

**==> picture [452 x 44] intentionally omitted <==**

## **Solicitud del Código Único de Facturación Diaria - CUFD** 

Conforme a normativa vigente el proceso de obtención del Código Único de Facturación Diaria (CUFD) para el Sistema Informático de Facturación autorizado debe realizarse diariamente. Este código habilita el sistema del Sujeto Pasivo para la emisión de Facturas Digitales durante un periodo de vigencia de 24 horas. 

A continuación se describen los parámetros de entrada y salida a ser utilizados: 

||||||||
|---|---|---|---|---|---|---|
|**Nombre**|**solicitudCufd**||||||
|**Método**|||||||
|**Entrada**|**Tipo Dato**|<br>**Obligato**|**Descripció**||**Salida**|**Tipo Dato**|
|||**rio**|**n**||||
|**codigoAmbie**|Numérico|Si|Describe|el|**codigoCUFD**|Alfanumérico|
|**nte**|||tipo|de|||
||||ambiente||||
||||utilizado,||||
||||los valores||||
||||permitidos||||
||||son:||||
||||Producción:||||
||||1||||
||||Pruebas|y|||
||||Piloto: 2||||



||||||||||
|---|---|---|---|---|---|---|---|---|
||**codigoSistem**|Alfanuméri|Si|Código de|**fechaVigencia**|<br>Fecha|UTC||
||**a**|co||Sistema||Extendida|||
|||||que le fue|||||
|||||asignado al|||||
|||||momento|||||
|||||de realizar|||||
|||||la solicitud|||||
|||||de|||||
|||||autorización|||||
|||||.|||||
||**nit**|Numérico|Si|NIT|**transaccion**|Boolean|||
|||||pertenecien|||||
|||||te al emisor|||||
|||||de la|||||
|||||factura.|||||
||**codigoModali**|Numérico|Si|Modalidad|**codigosResp**|DTO[codigosResp|||
||**dad**|||utilizada por|**uestas**|uesta]|||
|||||el Sistema|||||
|||||Informático|||||
|||||de|||||
|||||Facturación|||||
|||||para<br>la|||||
|||||emisión de|||||
|||||facturas,|||||
|||||pudiendo|||||
|||||ser:|||||
|||||Electrónica|||||
|||||en Línea: 1|||||
|||||Computariz|||||
|||||ada<br>en|||||
|||||Línea: 2|||||
||**cuis**|Alfanuméri|Si|Valor único|**codigoContro**|Alfanumérico|||
|||co||para<br>una|**l**||||
|||||sucursal y/o|||||
|||||punto<br>de|||||
|||||venta<br>que|||||
|||||se obtiene|||||
|||||al realizar el|||||
|||||inicio<br>de|||||
||||||||||



||||uso|de|||
|---|---|---|---|---|---|---|
||||sistemas.||||
|**codigoSucurs**|Numérico|Si|Valor<br>que<br>||**direccion**|Alfanumérico|
|**al**|||identifica|la|||
||||sucursal||||
||||donde|se|||
||||realiza|la|||
||||emisión|de|||
||||la Factura:||||
||||Casa||||
||||Matriz: 0||||
||||Sucursal:||||
||||1,2,..,n||||
|**codigoPuntoV**|Numérico|No|Solo|se|||
|**enta**|||envía este||||
||||valor||||
||||cuando|se|||
||||desea||||
||||obtener|un|||
||||CUFD para||||
||||el punto|de|||
||||venta|(1,|||
||||2,..,n).||||
||||Caso||||
||||contrario||||
||||enviar 0.||||



## **Nota:** 

Este servicio requiere el uso del Token Delegado. 

Al obtener el CUFD podría recibir una alerta del sistema informando sobre  la proximidad del vencimiento del CUIS y la necesidad de su renovación. 

## **Solicitud CUFD Masivo** 

Conforme normativa vigente el proceso de obtención del código CUFD para el Sistema Informático de Facturación se realiza diariamente a través del consumo del Servicio Web correspondiente, que permite al Sujeto Pasivo la emisión de Facturas durante un periodo de vigencia de 24 horas. Debido a que el CUFD se obtiene por casa matriz, sucursal y punto de venta, este servicio permite la obtención de CUFD masivos. 

El servicio implementado contiene la información descrita en el siguiente cuadro: 

|||||||
|---|---|---|---|---|---|
|**Nombre**|solicitudCufdMasivo|||||
|**Método**||||||
|**Entrada**|**Tipo**|**Obliga**|**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**|**torio**||||



|**codigoAm**|Numéric|Si|Describe el tipo de ambiente|Describe el tipo de ambiente|**ListaCodig**|Lista||
|---|---|---|---|---|---|---|---|
|**biente**|o||utilizado, los valores permitidos||**oCufd**|[Alfanumérico||
||||son:|||]||
||||Producción: 1|||||
||||PruebasyPiloto: 2|||||
|**codigoMo**|Numéric|Si|Modalidad<br>utilizada<br>por|el|**fechaV**|Fecha|UTC|
|**dalidad**|o||Sistema<br>Informático|de|**igencia**|Extendida||
||||Facturación para la emisión|de||||
||||facturas, pudiendo ser:|||||
||||Electrónica en Línea: 1|||||
||||Computarizada en Línea: 2|||||
|**codigoSist**|Alfanum|Si|Código de Sistema que le|fue|**transaccio**|Boolean||
|**ema**|érico||asignado al momento de realizar||**n**|||
||||la solicitud de autorización.|||||
|**nit**|Numéric|Si|NIT perteneciente al emisor|de|**CodigosRe**|DTO||
||o||la factura||**spuestas**|[CodigosRes||
|||||||puesta]||
|**datosSolic**|||Etiqueta contenedora.|||||
|**itud**||||||||
|**codigoPun**|Numéric|No|Solo se envía el número|del||||
|**toVenta**|o||punto<br>de venta cuando|se||||
||||realizará la sincronización|de||||
||||fecha y hora para el mismo|(1,||||
||||2,..,n). Caso contrario enviar 0.|||||
|**codigoSuc**|Numéric|Si|Valor que identifica a la sucursal|||||
|**ursal**|o||donde se realiza la emisión de la|||||
||||factura:|||||
||||Casa Matriz: 0|||||
||||Sucursal: 1,2,..,n|||||
|**cuis**|Alfanum|Si|Valor único para una sucursal|||||
||érico||y/o punto de venta que|se||||
||||obtiene al realizar el inicio|de||||
||||uso de sistemas.|||||



## **Nota:** 

Este servicio requiere el uso del Token Delegado y limita el numero de solicitudes a mil. En caso de requerir más CUFDs deberá partir la solicitud para no exceder este limite. 

Al obtener los CUFDs podría recibir una alerta del sistema informando sobre  la proximidad del vencimiento del CUIS y la necesidad de su renovación. 

## **Solicitud del Código Único de Inicio de Sistemas - CUIS** 

Conforme a normativa vigente el proceso de obtención del CUIS para una sucursal o punto de venta debe realizarse mediante el Sistema Informático de Facturación autorizado, a través del Servicio Web disponible. 

El servicio implementado posee un objeto denominado SolicitudCuis el cual contiene la información descrita en el siguiente cuadro: 

||||||||
|---|---|---|---|---|---|---|
|**Nombre**|cuis||||||
|**Método**|||||||
|**Entrada**|**Tipo Dato**|<br>**Obligato**|**Descripción**||**Salida**|**Tipo Dato**|
|||**rio**|||||
|**codigoAmbie**|Numérico|Si|Describe el tipo||<br>**codigoCUIS**|Alfanumérico|
|**nte**|||de<br>ambiente||||
||||utilizado,|los|||
||||valores||||
||||permitidos son:||||
||||Producción: 1||||
||||Pruebas|y|||
||||Piloto: 2||||
|**codigoSistem**|Alfanumér|Si|Código|de|<br>**transaccion**|Boolean|
|**a**|ico||Sistema que|le|||
||||fue asignado al||||
||||momento|de|||
||||realizar|la|||
||||solicitud|de|||
||||autorización.||||
|**nit**|Numérico|Si|NIT||**CodigosResp**|DTO[CodigosResp|
||||perteneciente al||<br>**uestas**|uesta]|
||||emisor<br>de|la|||
||||Factura.||||
|**codigoModali**|Numérico|Si|Modalidad||**fechaVigencia**|Fecha UTC|
|**dad**|||utilizada por|el||extendida|
||||Sistema||||
||||Informático|de|||
||||Facturación||||
||||para la emisión||||
||||de<br>Facturas,||||
||||pudiendo ser:||||
||||Electrónica|en|||
||||Línea: 1||||
||||Computarizada||||
||||en Línea: 2||||



|**codigoSucurs**|Numérico|Si|Valor|que<br> <br>|
|---|---|---|---|---|
|**al**|||identifica|la|
||||sucursal donde||
||||se<br>realiza|<br>la|
||||emisión de|la|
||||Factura:||
||||Casa Matriz: 0||
||||Sucursal:||
||||1,2,..,n||
|**codigoPunto**|Numérico|No|Solo se envía<br> <br>||
|**Venta**|||cuando|la|
||||transacción|se|
||||realiza||
||||utilizando|un|
||||punto de venta.||
||||Caso contrario||
||||enviar 0.||



## **Notas:** 

Este servicio requiere el uso del Token Delegado. El Código Unico de Inicio de Sistemas (CUIS) puede ser renovado a partir del quinto día anterior a su vencimiento. El sistema alertara sobre la proximidad del vencimiento del CUIS cuando se obtenga un CUFD y la fecha de vigencia este próxima a su fin. 

Por otro lado, se debe tomar en cuenta que una vez renovado el CUIS y para poder seguir operando con normalidad, debe también obtenerse un nuevo CUFD. 

## **Solicitud CUIS Masivo** 

El CUIS es un valor generado por la Administración Tributaria y que identifica la relación entre el Sistema Informático de Facturación autorizado, credenciales, Contribuyente, sucursal y opcionalmente al punto de venta, es inalterable en su composición y deberá ser parte del envío de Facturas Digitales. 

Conforme a normativa vigente el proceso de obtención de los códigos CUIS para el Sistema Informático de Facturación debe realizarse a través del consumo de los Servicios Web disponibles. Específicamente este servicio permite obtener múltiples CUIS de manera simultánea y así evitar tener que generar los mismos uno por uno. 

El servicio implementado posee un objeto denominado SolicitudCuisMasivo el cual contiene la información descrita en el siguiente cuadro: 

## **Verifica NIT** 

Conforme a normativa vigente el proceso de verificación de NIT debe realizarse mediante el Sistema Informático de Facturación autorizado, a través del Servicio Web disponible. Esto método debería utilizarse para verificar los NIT de sus clientes antes del envío de la factura digital, se recomienda que se realice una verificación previa de sus clientes regulares o registrados, mientras que los eventuales o no registrados debería realizarse al momento de la emisión. 

El servicio implementado posee un objeto denominado SolicitudVerificarNit el cual contiene la información descrita en el siguiente cuadro: 

|||||||||
|---|---|---|---|---|---|---|---|
|**Nombre Método**|verificarNit|||||||
|**Entrada**|**Tipo Dato**|**Obligatori**|**Descripción**||**Salida**|**Tipo**||
|||**o**||||**Dato**||
|**codigoAmbiente**|Numérico|Si|Describe<br>el<br>tipo|de|**mensajes**|Lista||
||||ambiente utilizado, los|||||
||||valores permitidos son:|||||
||||Producción: 1|||||
||||PruebasyPiloto: 2|||||
|**codigoSistema**|Alfanuméric|Si|Código de Sistema que||**transacci**|Boolea||
||o||le<br>fue<br>asignado|al|**on**|n||
||||momento de realizar|la||||
||||solicitud|de||||
||||autorización.|||||
|**nit**|Numérico|Si|NIT<br>perteneciente|al||||
||||emisor de la Factura.|||||
|**codigoModalidad**|Numérico|Si|Modalidad utilizada por|||||
||||el Sistema Informático|||||
||||de Facturación para|la||||
||||emisión<br>de Facturas,|||||
||||pudiendo ser:|||||
||||Electrónica en Línea:|1||||
||||Computarizada|en||||
||||Línea: 2|||||
|**codigoSucursal**|Numérico|Si|Valor que identifica|la||||
||||sucursal<br>donde|se||||
||||realiza la emisión de la|||||
||||Factura:|||||
||||Casa Matriz: 0|||||
||||Sucursal: 1,2,..,n|||||
|**nitParaVerificacio**|Numérico|Si|Número de NIT que|se||||
|**n**|||requiere verificar|||||



## **Solicitud de Cierre de Operaciones** 

Conforme normativa vigente el proceso de cierre de operaciones para el Sistema Informático de Facturación podrá realizarse a través de los Servicios Web disponibles y permite realizar el cierre de operaciones de una sucursal o punto de venta, este proceso inhabilita el CUIS y el CUFD vigente, de manera automática no pudiendo realizar la emisión de Facturas Digitales a partir de ese momento. 

El servicio implementado posee un objeto denominado SolicitudOperaciones el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre**|cierreOperacionesSistema|cierreOperacionesSistema|cierreOperacionesSistema|||
|---|---|---|---|---|---|
|**Método**||||||
|**Entrada**|**Tipo**|**Obligat**|**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**|**orio**||||
|**codigoAmbi**|Numéric|Si|Describe el tipo de|**codigoSistema**|Alfanumérico|
|**ente**|o||ambiente utilizado, los|||
||||valores<br>permitidos|||
||||son:|||
||||Producción: 1|||
||||PruebasyPiloto: 2|||
|**codigoSiste**|Alfanum|Si|Código<br>de<br>Sistema|**transaccion**|Boolean|
|**ma**|érico||que le fue asignado al|||
||||momento de realizar la|||
||||solicitud<br>de|||
||||autorización.|||
|**nit**|Numéric|Si|NIT perteneciente al|**mensajes**|Lista|
||o||emisor de la Factura.|||
|**codigoModa**|Numéric|Si|Modalidad<br>utilizada|||
|**lidad**|o||por<br>el<br>Sistema de|||
||||Facturación<br>para la|||
||||emisión de Facturas,|||
||||pudiendo ser:|||
||||Electrónica en Línea:|||
||||1|||
||||Computarizada<br>en|||
||||Línea: 2|||
|**cuis**|Alfanum|Si|Valor único para una|||
||érico||sucursal y/o punto de|||
||||venta que se obtiene|||
||||al realizar el inicio de|||
||||uso de sistemas.|||
|**codigoSucu**|Numéric|Si|Valor que identifica la|||
|**rsal**|o||sucursal<br>donde<br>se|||
||||realiza la emisión de la|||
||||Factura:|||
||||Casa Matriz: 0|||
||||Sucursal: 1,2,..,n|||



**codigoPunt** Numéric No Solo se envía cuando **oVenta** o la transacción se realiza utilizando un punto de venta. Caso contrario enviar 0. 

## **Nota:** 

Este servicio requiere el uso del Token Delegado 

## **Proceso de cierre de operaciones** 

## **Cierre Punto de Venta** 

Este servicio permite al Sujeto Pasivo realizar el cierre definitivo de un punto de venta, solo podrá realizar esta operación si para el punto de venta no existe CUIS o CUFD activo. Una vez que el punto de venta se haya cerrado no podrá generarse nuevamente con el mismo correlativo. 

El servicio implementado contiene la información descrita en el siguiente cuadro: 

|**Nombre Método**|CierrePuntoVenta|CierrePuntoVenta||||
|---|---|---|---|---|---|
|**Entrada**|**Tipo Dato**|**Obligatori**|**Descripción**|**Salida**|**Tipo**|
|||**o**|||**Dato**|
|**codigoAmbient**|Numérico|Si|Describe<br>el<br>tipo<br>de|**transacci**|Boolea|
|**e**|||ambiente utilizado, los|**on**|n|
||||valores permitidos son:|||
||||Producción: 1|||
||||PruebasyPiloto: 2|||
|**codigoPuntoVe**|Numérico|Si|Solo se envía cuando la|**mensajes**|Lista|
|**nta**|||transacción<br>se<br>realiza|||
||||utilizando un punto de|||
||||venta.<br>Caso<br>contrario|||
||||enviar 0.|||



|**codigoSistema**|Alfanuméric|Si|Código de Sistema que|
|---|---|---|---|
||o||le<br>fue<br>asignado<br>el|
||||momento de realizar la|
||||solicitud de autorización.|
|**codigoSucursal**|Numérico|Si|Valor que identifica a la|
||||sucursal<br>donde<br>se|
||||realiza la emisión de la|
||||Factura:|
||||Casa Matriz: 0|
||||Sucursal: 1,2,..,n|
|**cuis**|Alfanuméric|Si|Valor único para una|
||o||sucursal y/o punto de|
||||venta que se obtiene al|
||||realizar el inicio de uso|
||||de sistemas.|
|**nit**|Numérico|Si|NIT<br>perteneciente<br>al|
||||emisor de la Factura.|



## **Nota:** 

Este servicio requiere el uso del Token Delegado 

## **Proceso de Cierre de Punto de Venta** 

## **Consulta de Envío de Eventos Significativos** 

El proceso de consulta de envío de eventos significativos permite informar al Contribuyente de los registros de contingencia enviados al SIN por el Sistema Informático de Facturación autorizado. 

El servicio implementado posee un objeto denominado SolicitudConsultaEvento el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre**|consultaEventoSignificativo|consultaEventoSignificativo|consultaEventoSignificativo|||
|---|---|---|---|---|---|
|**Método**||||||
|**Entrada**|**Tipo**|**Obliga**|**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**|**torio**||||
|**codigoAm**|Numéri|Si|Describe el tipo de ambiente|**listaEventos**|Array[codigo|
|**biente**|co||utilizado,<br>los<br>valores||Evento,|
||||permitidos son:||descripción,|
||||Producción: 1||fecha]|
||||PruebasyPiloto: 2|||
|**codigoSist**|Alfanum|Si|Código de Sistema que le fue|**transaccion**|Boolean|
|**ema**|érico||asignado<br>al<br>momento<br>de|||
||||realizar<br>la<br>solicitud<br>de|||
||||autorización.|||
|**nit**|Numéri|Si|NIT perteneciente al emisor de|**mensajes**|Lista|
||co||la Factura.|||
|**cuis**|Alfanum|Si|Valor único para una sucursal|||
||érico||y/o punto de venta que se|||
||||obtiene al realizar el inicio de|||
||||uso de sistemas.|||
|**cufd**|Alfanum||Valor diario otorgado por el|||
||érico||SIN .|||
|**codigoSuc**|Numéri|No|Valor que indentifica a la|||
|**ursal**|co||sucursal donde se realiza la|||
||||emisión de la Factura:|||
||||Casa Matriz: 0|||
||||Sucursal: 1,2,..,n|||
|**codigoPun**|Numéri|No|Solo<br>se envía cuando la|||
|**toVenta**|co||transacción<br>se<br>realiza|||
||||utilizando un punto de venta.|||
||||Caso contrario enviar 0.|||
|**fechaEven**|Date|Si|Fecha del evento significativo.|||
|**to**||||||



## **Nota:** 

Este servicio requiere el uso del Token Delegado 

## **Proceso de Consulta de Envíos de Eventos Significativos** 

## **Consulta Punto de Venta** 

Este servicio permite al Contribuyente realizar la consulta de puntos de venta asociados al Sujeto Pasivo. 

El servicio implementado contiene la información descrita en el siguiente cuadro: 

|**Nombre**|ConsultaPuntoVenta|ConsultaPuntoVenta|ConsultaPuntoVenta|||
|---|---|---|---|---|---|
|**Método**||||||
|**Entrada**|**Tipo**|**Obliga**|**Descripción**|**Salida**|**Tipo**|
||**Dato**|**torio**|||**Dat**|
||||||**o**|
|**codigoAm**|Numéri|Si|Describe el tipo de ambiente utilizado,|**transaccion**|Bool|
|**biente**|co||los valores permitidos son:||ean|
||||Producción: 1|||
||||PruebasyPiloto: 2|||
|**codigoSist**|Alfanum|Si|Código de Sistema que le fue asignado|**mensajes**|Lista|
|**ema**|érico||al momento de realizar la solicitud de|||
||||autorización.|||
|**codigoSuc**|Numéri|Si|Valor que identifica a la sucursal donde|**listaPuntosV**|Lista|
|**ursal**|co||se realiza la solicitud.|**entas**||
||||Casa Matriz: 0|||
||||Sucursal: 1,2,..,n|||
|**cuis**|Alfanum|Si|Valor único para una sucursal y/o punto|||
||érico||de venta que se obtiene al realizar el|||
||||inicio de uso de sistemas.|||
|**nit**|Numéri|Si|NIT perteneciente al emisor de la|||
||co||Factura.|||



## **Nota:** 

Este servicio requiere el uso del Token Delegado 

## **Proceso de Consulta de Punto de Venta** 

## **Registro de Evento Significativo** 

El proceso de registro de evento significativo permite informar al SIN de la contingencia del Sistema Informático de Facturación autorizado. 

El servicio implementado posee un objeto denominado SolicitudEventoSignificativo el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre**|registroEventoSignificativo|registroEventoSignificativo|registroEventoSignificativo||||
|---|---|---|---|---|---|---|
|**Método**|||||||
|**Entrada**|**Tipo**|**Obligat**|**Descripción**||**Salida**|**Tipo Dato**|
||**Dato**|**orio**|||||
|**codigoAmbi**|Numéric|Si|Describe el tipo de ambiente||<br>**codigoRec**|Alfanumérico|
|**ente**|o||utilizado,<br>los<br>valores||<br>**epcion**||
||||permitidos son:||||
||||Producción: 1||||
||||PruebasyPiloto: 2||||
|**codigoSiste**|Alfanum|Si|Código de Sistema que|le|<br>**transaccion**|Boolean|
|**ma**|érico||fue asignado al momento|de|||
||||realizar<br>la<br>solicitud|de|||
||||autorización.||||
|**nit**|Numéric|Si|NIT perteneciente al emisor||<br>**mensajes**|Lista|
||o||de la Factura.||||
|**cuis**|Alfanum|Si|Valor<br>único<br>para<br>una||||
||érico||sucursal y/o punto de venta||||
||||que se obtiene al realizar|el|||
||||inicio de uso de sistemas.||||
|**cufd**|Alfanum||Valor diario otorgado por|el|||
||érico||SIN.||||
|**codigoSucu**|Numéric|No|Valor que identifica a|la|||
|**rsal**|o||sucursal donde se realiza|la|||
||||emisión de la Factura:||||
||||Casa Matriz: 0||||
||||Sucursal: 1,2,..,n||||
|**codigoPunt**|Numéric|No|Solo se envía cuando|la|||
|**oVenta**|o||transacción<br>se<br>realiza||||
||||utilizando un punto de venta.||||
||||Caso contrario enviar 0.||||
|**codigoEven**|Numéric|Si|Paramétrica que identifica el||||
|**to**|o||tipo de evento.||||
|**descripcion**|Alfanum|Si|Descripción<br>del<br>evento||||
||érico||significativo.||||
|**fechaInicioE**|String|Si|El formato que debe tener||||
|**vento**|||es:"yyyy-MM-dd'T'HH:mm:ss||||
||||.SSS"||||
|**fechaFinEve**|String|Si|El formato que debe tener||||
|**nto**|||es:"yyyy-MM-dd'T'HH:mm:ss||||
||||.SSS"||||
|**cufdEvento**|Alfanum|Si|Valor del CUFD que se uso||||
||érico||en la contingencia.||||



## **Nota:** 

Este servicio requiere el uso del Token Delegado 

## **Proceso de Registro de Evento Significativo** 

## **Registro Punto de venta** 

Conforme normativa vigente los puntos de venta deben ser registrados en el Sistema de la Administración Tributaria, existe un Servicio Web disponible que permite realizar esto. 

El servicio implementado posee un objeto denominado SolicitudRegistroPuntoVenta el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre**|registroPuntoVenta|registroPuntoVenta||||
|---|---|---|---|---|---|
|**Método**||||||
|**Entrada**|**Tipo**|**Obliga**|**Descripción**|**Salida**|**Tipo**|
||**Dato**|**torio**|||**Dato**|
|**codigoAmbie**|Numéric|Si|Describe el tipo de ambiente|Describe el tipo de ambiente<br>**mensajes**|Lista|
|**nte**|o||utilizado, los valores permitidos|utilizado, los valores permitidos||
||||son:|||
||||Producción: 1|||
||||Pruebas y Piloto: 2|||
|**codigoModali**|Numéric|Si|Modalidad utilizada por el|**codigoPunto**|Numéric|
|**dad**|o||Sistema de Facturación para la|**Venta**|o|
||||emisión de Facturas, pudiendo|||
||||ser:|||
||||Electrónica en Línea: 1|||



||||Computarizada en Línea: 2|||
|---|---|---|---|---|---|
|**codigoSistem**|Alfanum|Si|Código de Sistema que le fue|Código de Sistema que le fue<br>**transaccion**|Boolean|
|**a**|érico||asignado al momento de realizar|asignado al momento de realizar||
||||la solicitud de autorización.|||
|**codigoSucurs**|Numéric|Si|Valor que identifica la sucursal|Valor que identifica la sucursal<br>||
|**al**|o||donde se realiza la emisión de la|donde se realiza la emisión de la||
||||Factura:|||
||||Casa Matriz: 0|||
||||Sucursal: 1,2,..,n|||
|**codigoTipoPu**||Si|Valor el tipo de Venta:|||
|**ntoVenta**|Numéric||1. Punto Venta Comisionista|||
||o|||||
||||2. Punto Venta Ventanilla de|2. Punto Venta Ventanilla de||
||||Cobranza|||
||||3. Punto de Venta Móviles|||
||||4. Punto de Venta YPFB|||
||||5. Punto de Venta Cajeros|||
||||6. Punto de Venta Conjunta|||
|**cuis**|Alfanum|Si|Valor único para una sucursal|Valor único para una sucursal<br>||
||érico||y/o punto de venta que se|y/o punto de venta que se||
||||obtiene al realizar el inicio de|obtiene al realizar el inicio de||
||||uso de sistemas.|||
|**descripcion**|Alfanum|Si|Descripción del punto de venta.|||
||érico|||||
|**nit**|Numéric|Si|NIT perteneciente al emisor de|NIT perteneciente al emisor de||
||o||la Factura.|||
|**nombrePunto**|Alfanum|Si|Nombre que le asignará a su|Nombre que le asignará a su||
|**Venta**|érico||punto de venta.|||



## **Nota:** 

Este servicio requiere el uso del Token Delegado 

## **Proceso de Registro de Punto de Venta** 

**==> picture [47 x 12] intentionally omitted <==**

**----- Start of picture text -----**<br>
Solicitud de registo de<br>**----- End of picture text -----**<br>


## **Registro Punto de venta Comisionista** 

Conforme normativa vigente los puntos de venta deben ser registrados en el Sistema de la Administración Tributaria, existe un Servicio Web disponible que permite realizar esto. 

El servicio implementado posee un objeto denominado SolicitudPuntoVentaComisionista el cual contiene la información descrita en el siguiente cuadro: 

|||||||
|---|---|---|---|---|---|
|**Nombre**|registroPuntoVentaComisionista|||||
|**Método**||||||
|**Entrada**|**Tipo**|**Obliga**|**Descripción**|**Salida**|**Tipo**|
||**Dato**|**torio**|||**Dato**|
|**codigoAmbie**|Numéric|Si|Describe el tipo de ambiente|**mensajes**|Lista|
|**nte**|o||utilizado, los valores permitidos|||
||||son:|||
||||Producción: 1|||
||||Pruebas y Piloto: 2|||
|**codigoModali**|Numéric|Si|Modalidad utilizada por el|**codigoPunto**|Numéric|
|**dad**|o||Sistema de Facturación para la|**Venta**|o|
||||emisión de Facturas, pudiendo|||
||||ser:|||
||||Electrónica en Línea: 1|||
||||Computarizada en Línea: 2|||
|**codigoSiste**|Alfanum|Si|Código de Sistema que le fue|**transaccion**|Boolean|
|**ma**|érico||asignado al momento de realizar|||
||||la solicitud de autorización.|||
|**codigoSucur**|Numéric|Si|Valor que identifica la sucursal|||
|**sal**|o||donde se realiza la emisión de la|||
||||Factura:|||
||||Casa Matriz: 0|||
||||Sucursal: 1,2,..,n|||
|**cuis**|Alfanum|Si|Valor único para una sucursal y/o|||
||érico||punto de venta que se obtiene al|||
||||realizar el inicio de uso de|||
||||sistemas.|||
|**descripcion**|Alfanum|Si|Descripción del punto de venta.|||
||érico|||||
|**fechaFin**||Si|Fecha de fin de contrato|||
|**fechaInicio**||Si|Fecha de inicio de contrato|||
|**nit**|Numéric|Si|NIT perteneciente al Comitente|||
||o|||||



|**nitComisioni**|Numéric|Si|NIT<br>perteneciente|al|
|---|---|---|---|---|
|**sta**|o||Comisionista||
|**nombrePunt**|Alfanum<br>|Si|Nombre que le asignará|a su|
|**oVenta**|érico||punto de venta.||
|**numeroCont**|Alfanum<br>|Si|Numero del contrato firmado con||
|**rato**|érico||el Comitente||



## **Nota:** 

Este servicio requiere el uso del Token Delegado 

## **Sincronización Códigos y Catálogos** 

Conforme a normativa vigente la sincronización de catálogos de facturación debe realizarse diariamente a través de los Servicios Web correspondientes. Es el proceso por el cual se descargan las diferentes tablas de paramétricas utilizados por el Sistema de Facturación (códigos de productos y servicios, países, códigos de eventos significativos, códigos de mensajes de servicios entre otros) a objeto de mantener actualizadas las tablas localmente. El consumo de estos servicios requiere de un Token Delegado 

## **Servicios SOAP / Parámetros Entrada y Salida** 

||Códig<br>os de<br>Activid<br>ades|Fe<br>ch<br>a y<br>Ho<br>ra|<br>Códigos<br>de<br>Activida<br>des<br>Docume<br>nto<br>Sector|<br>Códi<br>gos<br>de<br>Leye<br>ndas<br>Fact<br>uras|<br>Códi<br>gos<br>de<br>Men<br>sajes<br>Servi<br>cios|<br>Código<br>s de<br>Produc<br>tos y<br>Servici<br>os|Código<br>s de<br>Evento<br>s<br>Signific<br>ativos|Códig<br>os de<br>Motiv<br>os<br>Anula<br>ción|<br>Códi<br>gos<br>de<br>País<br>Orig<br>en|<br>Códig<br>os de<br>Tipo<br>Docu<br>mento<br>Identid<br>ad|<br>Códig<br>os de<br>Tipo<br>Docu<br>mento<br>Sector|<br> <br>Códi<br>gos<br>de<br>Tipo<br>Emi<br>sión|<br> <br>Códigos<br>de Tipo<br>Habitaci<br>ón|<br>Códi<br>gos<br>de<br>Tipo<br>Mét<br>odo<br>Pag<br>o|<br> <br> <br>Códi<br>gos<br>de<br>Tipo<br>Mon<br>eda|<br> <br>Códig<br>os de<br>Tipo<br>Punto<br>de<br>Venta|<br> <br>Código<br>s de<br>Tipo<br>Factur<br>a|Códi<br>gos<br>de<br>Unid<br>ad<br>de<br>Med<br>ida|
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
|**ENTRAD**<br>**A**|||||||||||||||||||
|codigoAm<br>biente|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|
|codigoSist<br>ema|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|
|nit|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|
|cuis|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|
|codigoSuc<br>ursal|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|
|codigoPu<br>ntoVenta|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|
|**SALIDA**|||||||||||||||||||
|Lista de<br>Códigos|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|
|Transacci<br>on|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|
|Lista de<br>Mensajes|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|**X**|
||||||||||||||||||||



## **Descripción Parámetros de Entrada** 

|ENTRADA|Tipo Dato|Obligatorio|Descripción|
|---|---|---|---|
|codigoAmbiente|Numérico|Si|Describe el tipo de ambiente utilizado, los valores permitidos son:<br>Producción: 1<br>Pruebas y Piloto: 2|
|codigoSistema|Alfanumérico|Si|Código de Sistema que le fue asignado el momento de realizar la<br>solicitud de autorización.|
|nit|Numérico|Si|NIT perteneciente al emisor de la Factura.|
|cuis|Alfanumérico|Si|Valor único para una sucursal y/o punto de venta que se obtiene al<br>realizar el inicio de uso de sistemas.|
|codigoSucursal|Numérico|Si|Valor que identifica a la sucursal donde se realiza la emisión de la<br>Factura:<br>Casa Matriz: 0<br>Sucursal: 1,2,..,n|
|codigoPuntoVenta|Numérico|No|Solo se envía el número del punto de venta cuando se realizará la<br>Sincronización para el mismo (1, 2,.., n). Caso contrario enviar 0.|



## **Descripción Parámetros Salida** 

|SALIDA|Tipo Dato|
|---|---|
|Lista de Códigos|Alfanumérico|
|Transaccion|Boolean|
|Lista de Mensajes|Alfanumérico|



## **FACTURACION ELECTRONICA EN LINEA** 

## **Recepción Factura Electrónica** 

Esta compuesta por una serie de Servicios Web habilitados para recibir facturas individuales emitidas bajo la modalidad Electrónica en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. Dichos servicios reciben la factura verificando que los parámetros enviados sean válidos, analizan si el archivo recibido es correcto, si la firma es válida y validan el XML contra el XSD. 

Si el documento recibido supera esta etapa, el servicio devuelve el código de recepción, caso contrario, se devuelve los códigos de errores y advertencia. 

El servicio implementado posee un objeto denominado SolicitudServicioRecepcionFactura el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método:** RecepcionFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo de|**codigoEs**|Numérico|
|**e**||||ambiente utilizado, los|**tado**||
|||||valores permitidos son:|||
|||||Producción: 1|||
|||||PruebasyPiloto: 2|||
|**codigoPuntoVe**|Numérico||No|Solo se envía cuando|**codigoRe**|Alfanumérico|
|**nta**||||la<br>transacción<br>se|**cepcion**||
|||||realiza<br>utilizando un|||
|||||punto de venta. Caso|||
|||||contrario enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema que|**codigosR**|DTO[codigosR|
|||||le<br>fue<br>asignado<br>al|**espuesta**|espuesta]|
|||||momento de realizar la|**s**||
|||||solicitud<br>de|||
|||||autorización.|||
|**codigoSucursal**|Numérico||Si|Valor que identifica a la||Boolean|
|||||sucursal<br>donde<br>se|**transacci**||
|||||realiza la emisión de la|||
|||||Factura:|**on**||
|||||Casa Matriz: 0|||
|||||Sucursal: 1,2,..,n|||
|**nit**|Numérico||Si|NIT perteneciente al||Alfanumérico|
|||||emisor de la Factura.|**codigoDesc**||
||||||**ripcion**||
|**codigoDocume**|Numérico||Si|Código que identifica el|||
|**ntoSector**||||sector de la Factura.|||
|**codigoEmision**|Numérico||Si|Describe si la emisión|||
|||||se realizó en línea. El|||
|||||valorpermitido es:|||



||||Online: 1|
|---|---|---|---|
|**codigoModalid**|Numérico|Si|Electrónica en línea: 1|
|**ad**||||
|**cufd**|Alfanumérico|Si|Valor diario otorgado|
||||por el SIN.|
|**cuis**|Alfanumérico|Si|Valor único para una|
||||sucursal y/o punto de|
||||venta que se obtiene al|
||||realizar el inicio de uso|
||||de sistemas.|
|**tipoFacturaDoc**|Numérico|Si|Código que identifica el|
|**umento**|||Tipo de Factura que se|
||||está enviando.|
|**archivo**|Alfanumérico|Si|Factura<br>que<br>es|
||||enviada<br>para<br>su|
||||validación.|
|**fechaEnvio**|TimeStamp|Si|Fecha y hora en la cual|
||||se envía la Factura.|
|**hashArchivo**|Alfanumérico|Si|Sha256 de la cadena|
||||Archivo que se envía.|



## **Proceso de Recepción de Factura Electrónica** 

## **Anulación Factura Electrónica** 

Está compuesta por una serie de Servicios Web habilitados para recibir solicitudes de anulación de facturas individuales emitidas bajo la modalidad Electrónica en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. 

Para la anulación de una Factura emitida en la modalidad Electrónica en Línea, la mencionada factura deberá estar previamente registrada y validada por la Administración Tributaria. 

Dichos servicios previa validación de los parámetros enviados, registran la solicitud devolviendo un código de estado cuando la misma fue correcta o un código de error y advertencia en caso contrario. 

El servicio implementado posee un objeto denominado SolicitudServicioAnulacionFactura el cual contiene la información descrita en el siguiente cuadro: 

||||||||
|---|---|---|---|---|---|---|
|**Nombre Método:**|AnulacionFactura||||||
|**Entrada**|**Tipo**|**Obligatorio**||**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**||||||
|**codigoAmbiente**|<br>Numérico||Si|Describe el tipo de|**codigosResp**|DTO|
|||||ambiente<br>utilizado,|**uesta**|[codigosR|
|||||los<br>valores||espuesta]|
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas y Piloto:|||
|||||2|||
|**codigoPuntoVe**|Numérico||No|Solo<br>se<br>envía||Numérico|
|**nta**||||cuando<br>la|**codigoEstado**||
|||||transacción<br>se|||
|||||realiza utilizando un|||
|||||punto<br>de<br>venta.|||
|||||Caso<br>contrario|||
|||||enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema|**transaccion**|Boolean|
|||||que le fue asignado|||
|||||al<br>momento<br>de|||
|||||realizar la solicitud|||
|||||de autorización.|||
|**codigoSucursal**|Numérico||Si|Valor que identifica a|**codigoDescri**|Alfanumér|
|||||la sucursal donde se|**pcion**|ico|
|||||realiza la emisión de|||
|||||la Factura:|||
|||||Casa Matriz: 0|||



||||Sucursal:1,2,...,n|
|---|---|---|---|
|**nit**|Numérico|Si|NIT perteneciente al|
||||emisor<br>de<br>la|
||||Factura.|
|**codigoDocumen**|Numérico|Si|Código<br>que<br> <br>|
|**toSector**|||identifica el sector|
||||de la Factura.|
|**codigoEmision**|Numérico|Si|Describe<br>si<br>la<br> <br>|
||||emisión se realizó|
||||en línea. El valor|
||||permitido es:|
||||Online: 1|
|**codigoModalida**|Numérico|Si|Electrónica en línea:|
|**d**|||1|
|**cufd**|Alfanumérico|Si|Valor diario otorgado<br> <br>|
||||por el SIN.|
|**cuis**|Alfanumérico|Si|Valor único para una<br> <br>|
||||sucursal y/o punto|
||||de venta que se|
||||obtiene al realizar el|
||||inicio<br>de uso de|
||||sistemas.|
|**tipoFacturaDoc**|Numérico|Si|Código<br>que<br> <br>|
|**umento**|||identifica el Tipo de|
||||Factura<br>o|
||||Documento<br>de|
||||Ajuste que se está|
||||enviando.|
|**codigoMotivo**|Numérico|Si|Paramétrica<br>que<br> <br>|
||||indica el motivo por|
||||el cual la Factura|
||||está siendo anulada.|
|**cuf**|Alfanumérico|Si|Código<br>único<br>de<br> <br>|
||||factura<br>que<br>está|
||||siendo anulado.|



## **Proceso de Recepción de Anulación de Factura Electrónica** 

## **Reversión Anulación Factura Electrónica** 

De acuerdo a RND Nº 102300000034 que indica “Asimismo, en caso de darse la anulación errónea de Documentos Fiscales, el Sujeto Pasivo del IVA a traves de su Sistema Informático de Facturación o la opción habilitada en la modalidad Portal Web en Línea, según corresponda de acuerdo a la modalidad de facturacion utilizada, podrá revertir por única vez la anulación y cambiar el estado de un Documento Fiscal a “VALIDO” hasta la fecha señalada en el párrafo precedente. Los Documentos Fiscales revertidos no podrán ser anulados”. Este servicio permite revertir el estado de las facturas digitales que fueron anuladas por error y por una sola vez. 

El servicio implementado posee un objeto denominado SolicitudServicioReversionAnulacionFactura el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre Método:**|**Nombre Método:**ReversionAnulacionFactura|**Nombre Método:**ReversionAnulacionFactura|**Nombre Método:**ReversionAnulacionFactura|||
|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**|||||
|**codigoAmbien**|Numérico|Numérico<br>Si|Describe el tipo de|**codigoEsta**|Numérico|
|**te**|||ambiente<br>utilizado,|**do**||
||||los<br>valores|||
||||permitidos son:|||
||||Producción: 1|||
||||Pruebas y Piloto:|||
||||2|||
|**codigoPuntoV**|Numérico|Numérico<br>No|Solo<br>se<br>envía||DTO|
|**enta**|||cuando<br>la|**codigosRes**|[codigosRespu|
||||transacción<br>se<br>realiza utilizando un|**puesta**|esta]|
||||punto<br>de<br>venta.|||
||||Caso<br>contrario|||
||||enviar 0.|||



||||||||
|---|---|---|---|---|---|---|
|**codigoSistem**|Alfanumérico|Si|Código de|Sistema|**transaccio**|Boolean|
|**a**|||que le fue|asignado|**n**||
||||al<br>momento<br>de||||
||||realizar la|solicitud|||
||||de autorización.||||
|**codigoSucurs**|Numérico|Si|Valor que identifica a|||Alfanumérico|
|**al**|||la sucursal|donde se|||
||||realiza la emisión de||**codigoDesc**||
||||la Factura:||**ripcion**||
||||Casa Matriz: 0||||
|||||Sucursal:|||
||||1,2,...,n||||
|**nit**|Numérico|Si|NIT perteneciente al||||
||||emisor|de<br>la|||
||||Factura.||||
|**codigoDocum**|Numérico|Si|Código que|identifica|||
|**entoSector**|||el<br>sector|de<br>la|||
||||Factura.||||
|**codigoEmisio**|Numérico|Si|Describe|si<br>la|||
|**n**|||emisión se realizó||||
||||en línea.|El valor|||
||||permitido es:||||
||||Online:|1|||
|**codigoModali**|Numérico|Si|Electrónica|en línea:|||
|**dad**|||1||||
|**cufd**|Alfanumérico|Si|Valor diario|otorgado|||
||||por el SIN.||||
|**cuis**|Alfanumérico|Si|Valor único|para una|||
||||sucursal y/o punto||||
||||de venta|que se|||
||||obtiene al realizar el||||
||||inicio<br>de|uso de|||
||||sistemas.||||
|**tipoFacturaDo**|Numérico|Si|Código que|identifica|||
|**cumento**|||el Tipo de Factura o||||
||||Documento|<br>de|||
||||||||



||||Ajuste|que se|está|
|---|---|---|---|---|---|
||||revirtiendo.|||
|**cuf**|Alfanumérico|Si|Código|<br>único|de<br> <br>|
||||factura|<br>que|está|
||||siendo|revertida.||



**Nota:** Todos los sistemas ya autorizados que deseen utilizar este servicio deberán completar las pruebas para el mismo en ambiente piloto. Superadas las mismas y al presionar el botón de finalizar pruebas serán habilitados automáticamente para usar el servicio en producción. 

Los sistemas en las etapas iniciales o en proceso de autorización deberán completar este set de pruebas obligatoriamente. 

Los sistemas que se hallen ya en proceso de inspección, deberán terminar el proceso de forma normal y cuando el sistema este en producción solicitar el nuevo servicio via correo a soporte.aplicaciones@impuestos.gob.bo. 

## **Recepción Paquete Facturas Electrónicas** 

Está compuesta por una serie de Servicios Web habilitados para recibir paquetes de hasta 500 facturas emitidas bajo la modalidad de Facturación Electrónica en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. Dichos servicios reciben el paquete verificando que los parámetros enviados sean válidos, analizan si el paquete recibido es correcto. 

Si el paquete recibido supera esta etapa, el servicio devuelve el código de recepción. Caso contrario, se devuelve los código de error o advertencia. 

El servicio implementado posee un objeto denominado SolicitudServicioRecepcionPaquete el cual contiene la información descrita en el siguiente cuadro: 

||||||||
|---|---|---|---|---|---|---|
|**Nombre Método:**RecepcionPaqueteFactura|||||||
|**Entrada**|**Tipo**|**Obligatorio**||**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**||||||
|**codigoAmbie**|Numérico||Si|Describe<br>el<br>tipo<br>de|**codigoEst**|Numérico|
|**nte**||||ambiente utilizado, los|**ado**||
|||||valores permitidos son:|||
|||||Producción: 1|||
|||||PruebasyPiloto: 2|||
|**codigoPunto**|Numérico||No|Solo se envía cuando la||Alfanumérico|
|**Venta**||||transacción se realiza|**codigoRec**||
|||||utilizando un punto de<br>venta.<br>Caso contrario|**epcion**||
|||||enviar 0.|||
|**codigoSiste**|Alfanumérico||Si|Código de Sistema que|**CodigosR**|DTO[codigos|
|**ma**||||le<br>fue<br>asignado<br>al|**espuestas**|Respuesta]|



||||||||
|---|---|---|---|---|---|---|
||||momento de realizar|la|||
||||solicitud de autorización.||||
|**codigoSucur**|Numérico|Si|Valor que identifica a|la|**transaccio**|Boolean|
|**sal**|||sucursal<br>donde|se|**n**||
||||realiza la emisión de|la|||
||||Factura:||||
||||Casa Matriz: 0||||
||||Sucursal: 1,2,...,n||||
|**nit**|Numérico|Si|NIT<br>perteneciente|al||Alfanumérico|
||||emisor de la Factura.||**codigoDescri**||
||||||**pcion**||
|**codigoDocu**|Numérico|Si|Código que identifica|el|||
|**mentoSector**|||sector de la  Factura.||||
|**codigoEmisi**|Numérico|Si|Describe si la emisión|se|||
|**on**|||realizó fuera de línea.|El|||
||||valor permitido es:||||
||||Offline : 2||||
|**codigoModali**|Numérico|Si|Electrónica en Línea: 1||||
|**dad**|||||||
|**cufd**|Alfanumérico|Si|Valor diario otorgado por||||
||||el SIN.||||
|**cuis**|Alfanumérico|Si|Valor único para una||||
||||sucursal y/o punto|de|||
||||venta que se obtiene|al|||
||||realizar el inicio de uso||||
||||de sistemas.||||
|**tipoFacturaD**|Numérico|Si|Código que identifica|el|||
|**ocumento**|||Tipo<br>de<br>Factura|o|||
||||Documento de Ajuste||||
||||que se está enviando.||||
|**archivo**|Alfanumérico|Si|Paquete<br>de<br>Facturas||||
||||que son enviadas para||||
||||su validación.||||
|**fechaEnvio**|TimeStamp|Si|Fecha y hora en la cual||||
||||se envía la Factura.||||
|**hashArchivo**|Alfanumérico|Si|Sha256 de la cadena||||
||||Archivo que se envía.||||
|**cafc**|Alfanumérico|No|Código de autorización||||
||||de emisión de facturas||||
||||manuales|de|||
||||contingencia.<br>Nulo|si|||
||||son facturas normales||||
||||||||



|**cantidadFact**|Numérico|Si|Cantidad de Facturas|
|---|---|---|---|
|**uras**|||enviadas en el paquete.|
|**codigoEvent**|Numérico|Si|Código que devolvió el|
|**o**|||método de registro de|
||||evento.|



## **Proceso de Recepción de paquetes de Facturas Electrónicas** 

## **Validación Recepción Paquete Facturas Electrónicas** 

Está compuesta por una serie de Servicios Web habilitados para verificar el estado de los paquetes de facturas emitidos bajo la modalidad Electrónica en Línea y enviadas al SIN, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. 

Dichos servicios previa validación de los parámetros enviados, verifican el estado en el cual se encuentra los paquetes de facturas. Si todas las facturas pasaron las validaciones y no se encontraron errores se devuelve un código de aceptación, caso contrario se devuelve otro de rechazo junto a una lista con el detalle de aquellas Facturas con problemas y los errores o advertencias detectadas en cada una de ellas. 

El servicio implementado posee un objeto denominado SolicitudServicioValidacionRecepcionPaquete el cual contiene la información descrita en el siguiente cuadro: 

## **Nombre Método:** validacionRecepcionPaqueteFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|
||**Dato**|||||



|||||||
|---|---|---|---|---|---|
|**codigoAmbient**|Numérico|Si|Describe el tipo de|**codigoEstad**|Numérico|
|**e**|||ambiente utilizado,|**o**||
||||los<br>valores|||
||||permitidos son:|||
||||Producción: 1|||
||||Pruebas y|||
||||Piloto: 2|||
|**codigoPuntoVe**|Numérico|No|Solo<br>se<br>envía||Alfanumérico|
|**nta**|||cuando<br>la|**codigoRecep**||
||||transacción<br>se<br>realiza<br>utilizando|**cion**||
||||un punto de venta.|||
||||Caso<br>contrario|||
||||enviar 0.|||
|**codigoSistema**|Alfanumérico|Si|Código de Sistema||Alfanumérico|
||||que<br>le<br>fue<br>asignado<br>al<br>momento<br>de|**codigoDescr**<br>**ipcion**||
||||realizar la solicitud|||
||||de autorización.|||
|**codigoSucursal**|Numérico|Si|Valor que identifica|**codigosRes**|DTO[codigos|
||||a la sucursal donde<br>se<br>realiza<br>la|**puestas**|Respuesta]|
||||emisión<br>de<br>la|||
||||Factura:|||
||||Casa Matriz: 0|||
||||Sucursal:|||
||||1,2,..,n|||
|**nit**|Numérico|Si|NIT perteneciente|**transaccion**|Boolean|
||||al emisor de la|||
||||Factura.|||
|**codigoDocume**|Numérico|Si|Código<br>que|||
|**ntoSector**|||identifica el sector|||
||||de la Factura.|||
|**codigoEmision**|Numérico|Si|Describe<br>si<br>la|||
||||emisión se realizó|||
||||fuera de línea. El|||
||||valor permitido es:|||
||||Offline: 2|||
|**codigoModalid**|Numérico|Si|Electrónica<br>en|||
|**ad**|||Línea: 1|||
|**cufd**|Alfanumérico|Si|Valor<br>diario|||
||||otorgado<br>por<br>el|||
||||SIN.|||
|||||||



|**cuis**|Alfanumérico|Si|Valor único para|
|---|---|---|---|
||||una  sucursal y/o|
||||punto de venta que|
||||se<br>obtiene<br>al|
||||realizar el inicio de|
||||uso de sistemas.|
|**tipoFacturaDoc**|Numérico|Si|Código<br>que|
|**umento**|||identifica el Tipo de|
||||Factura<br>o|
||||Documento<br>de|
||||Ajuste que se está|
||||enviando.|
|**codigoRecepci**|Alfanumérico|Si|Código Recepción|
|**on**|||enviado por el SIN.|



## **Proceso de Validación de Recepción de Paquetes de Facturas Electrónicas** 

## **Verifica Comunicación** 

Este servicio recibe a solicitud de verificación de comunicación, registra la misma y devuelve un código de comunicación exitosa 

|**Nombre Método:**verificarComunicacion|**Nombre Método:**verificarComunicacion|**Nombre Método:**verificarComunicacion||||
|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**|**Consideraciones**|**Salida**|**Tipo Dato**|
||**Dato**|||||
|ninguna|ninguno|ninguno<br>No|ninguna|**return =**|Numérico|
|||corresp||**926**||
|||onde||**(comuni**||
|||||**cación**||
|||||**exitosa)**||



## **Recepción Masiva Facturas Electrónicas** 

Está compuesta por una serie de Servicios Web habilitados para recibir facturas en forma masiva bajo la modalidad Electrónica en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. Dichos servicios reciben el paquete verificando que los parámetros enviados sean válidos, verificando si el paquete recibido es correcto. 

Si el paquete recibido supera esta etapa, el servicio devuelve el código de recepción. Caso contrario, se devuelve los códigos de error o advertencia. 

Se debe considerar lo siguiente: 

- Periodicidad con la que enviará: Diario, semanal o mensual. 

- Tamaño de los paquetes: máximo 1000. 

Estos puntos a ser considerados se registrarán a través de la opción habilitada en el portal web de la Administración Tributaria. 

El servicio implementado posee un objeto denominado SolicitudServicioRecepcionMasiva el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre Método:**recepcionMasivaFactura|**Nombre Método:**recepcionMasivaFactura|**Nombre Método:**recepcionMasivaFactura|**Nombre Método:**recepcionMasivaFactura||||
|---|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**||**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**||||||
|**codigoAmbien**|Numérico||Si|Describe el tipo|<br>**codigoEstado**|Numérico|
|**te**||||de<br>ambiente|||
|||||utilizado,<br>los|||
|||||valores permitidos|||
|||||son:|||
|||||Producción: 1|||
|||||Pruebas y|||
|||||Piloto: 2|||
|**codigoPuntoV**|Numérico||No|Solo<br>se<br>envía|||
|**enta**||||cuando<br>la<br>transacción<br>se|<br> <br>**codigoRecepci**|Alfanumérico|
|||||realiza utilizando|<br>**on**||
|||||un<br>punto<br>de|||
|||||venta.<br>Caso|||
|||||contrario enviar 0.|||
|**codigoSistema**|<br>Alfanumérico||Si|Código<br>de|<br>**codigosRespu**|DTO[codigos|
|||||Sistema<br>que le|<br>**estas**|Respuesta]|
|||||fue asignado al|||
|||||momento<br>de|||
|||||realizar la solicitud|||
|||||de autorización.|||



|||||||
|---|---|---|---|---|---|
|**codigoSucurs**|Numérico|Si|Valor<br>que|<br>**transaccion**|Boolean|
|**al**|||identifica<br>a<br>la|||
||||sucursal donde se|||
||||realiza la emisión|||
||||de la Factura:|||
||||Casa Matriz:|||
||||0|||
||||Sucursal:|||
||||1,2,..,n|||
|**nit**|Numérico|Si|NIT perteneciente||Alfanumérico|
||||al emisor de la|<br>**codigoDescripcion**||
||||Factura.|||
|**codigoDocum**|Numérico|Si|Código<br>que|||
|**entoSector**|||identifica el sector|||
||||de la Factura.1|||
|**codigoEmision**|<br>Numérico|Si|El valor permitido|||
||||es:|||
||||Masiva: 3|||
|**codigoModalid**|Numérico|Si|Electrónica<br>en|||
|**ad**|||Línea: 1|||
|**cufd**|Alfanumérico|Si|Valor<br>diario|||
||||otorgado por el|||
||||SIN.|||
|||||||
|**cuis**|Alfanumérico|Si|Valor único para|||
||||una sucursal y/o|||
||||punto<br>de venta|||
||||que se obtiene al|||
||||realizar el inicio|||
||||de<br>uso<br>de|||
||||sistemas.|||
|**tipoFacturaDo**|Numérico|Si|Código<br>que|||
|**cumento**|||identifica el Tipo|||
||||de<br>Factura<br>o|||
||||Documento<br>de|||
||||Ajuste que se está|||
||||enviando.|||
|||||||
|**archivo**|Alfanumérico|Si|Paquete<br>de|||
||||Facturas que son|||
||||enviados para su|||
||||validación.|||
|||||||



|**fechaEnvio**|TimeStamp|Si|Fecha y hora en|Fecha y hora en|Fecha y hora en|
|---|---|---|---|---|---|
||||la cual se envía la|la cual se envía la||
||||Factura.|||
|**hashArchivo**|Alfanumérico|Si|Sha256|de|la|
||||cadena|Archivo||
||||que se envía.|||
|**cantidadFactur**|Numérico|Si|Cantidad||de|
|**as**|||Facturas que son|Facturas que son|Facturas que son|
||||enviadas|dentro||
||||del paquete.|||



## **Proceso de Recepción Masiva de Facturas Electrónicas** 

## **Validación Recepción Masiva Facturas Electrónicas** 

Está compuesta por una serie de Servicios Web habilitados para verificar el estado de las facturas enviadas al SIN en forma masiva bajo la modalidad Electrónica en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. 

Dichos servicios previa validación de los parámetros enviados, verifican el estado en el cual se encuentra las facturas recibidas, si todas pasaron las validaciones y no se encontraron errores se devuelve un código de aceptación, caso contrario se devuelve otro de observación junto a una lista con el detalle de aquellos documentos con problemas y los errores o advertencias detectadas en cada uno de ellas. 

El servicio implementado posee un objeto denominado SolicitudServicioValidacionRecepcionMasiva el cual contiene la información descrita en el siguiente cuadro: 

## **Nombre Método:** validacionRecepcionMasivaFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo de|**codigoEstad**|Numérico|
|**e**||||ambiente utilizado,|**o**||
|||||los<br>valores|||
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas y Piloto:|||
|||||2|||
|**codigoPuntoVe**|Numérico||No|Solo<br>se<br>envía|||
|**nta**||||cuando<br>la|**codigoRecep**|Alfanumérico|
|||||transacción<br>se<br>realiza utilizando un|**cion**||
|||||punto<br>de<br>venta.|||
|||||Caso<br>contrario|||
|||||enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema||Alfanuméric|
|||||que le fue asignado<br>al<br>momento<br>de|**codigoDescr**|o|
|||||realizar la solicitud|**ipcion**||
|||||de autorización.|||
|**codigoSucursal**|Numérico||Si|Valor que identifica|**transaccion**|Boolean|
|||||a la sucursal donde|||
|||||se realiza la emisión|||
|||||de la Factura:|||
|||||Casa Matriz: 0|||
|||||Sucursal: 1,2,..,n|||
|**nit**|Numérico||Si|NIT perteneciente al|**codigosResp**|DTO[codigos|
|||||emisor<br>de<br>la|**uestas**|Respuesta]|
|||||Factura.|||
|**codigoDocume**|Numérico||Si|Código<br>que|||
|**ntoSector**||||identifica el sector|||
|||||de la Factura.|||
|**codigoEmision**|Numérico||Si|El  valor permitido|||
|||||es:|||
|||||Masiva: 3|||
|**codigoModalida**|Numérico||Si|Electrónica<br>en|||
|**d**||||Línea: 1|||
|**cufd**|Alfanumérico||Si|Valor<br>diario|||
|||||otorgado por el SIN.|||



|**cuis**|Alfanumérico|Si|Valor<br>único<br>para|
|---|---|---|---|
||||una<br>sucursal y/o|
||||punto de venta que|
||||se<br>obtiene<br>al|
||||realizar el inicio de|
||||uso de sistemas.|
|**tipoFacturaDoc**|Numérico|Si|Código<br>que|
|**umento**|||identifica el Tipo de|
||||Factura<br>o|
||||Documento<br>de|
||||Ajuste que se está|
||||enviando.|
|**codigoRecepci**|Alfanumérico|Si|Código<br>Recepción|
|**on**|||enviado por el SIN.|



## **Proceso de Validación de Recepción Masiva de Facturas Electrónicas** 

## **Verificación Estado de Factura Electrónica** 

Este servicio está habilitado para verificar el estado de las Facturas o Notas Crédito - Débito emitidas bajo la modalidad Electrónica en Línea y que fueron enviadas al SIN, cuando se desea saber el estado de la Factura o Nota de Crédito - Débito, podrá realizarse a través del Código Único de Factura (CUF). 

Este servicio, previa validación de los parámetros enviados, verifica el estado en el cual se encuentra la Factura o Nota Crédito - Débito. Si la misma paso todas las validaciones y no se encontraron errores, se devuelve un código de aceptación caso contrario se devuelve otro de observación junto a una lista con el detalle de los mismos. 

El servicio implementado posee un objeto denominado SolicitudServicioVerificaEstadoFactura el cual contiene la información descrita en el siguiente cuadro: 

## **Nombre Método:** verificacionEstadoFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**||**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|---|
||**Dato**|||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo|de|**codigoEstad**|Numérico|
|**e**||||ambiente<br>utilizado,||**o**||
|||||los<br>valores||||
|||||permitidos son:||||
|||||Producción: 1||||
|||||Pruebas y||||
|||||Piloto: 2||||
|**codigoPuntoVe**|Numérico||No|Solo<br>se<br>envía||**codigoRece**|Alfanumérico|
|**nta**||||cuando|la|**pcion**||
|||||transacción|se|||
|||||realiza utilizando|un|||
|||||punto<br>de<br>venta.||||
|||||Caso<br>contrario||||
|||||enviar 0.||||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema||||
|||||que le fue asignado||**codigosRes**|DTO[codigos|
|||||al<br>momento<br>de<br>realizar la solicitud||**puestas**|Respuesta]|
|||||de autorización||||
|**codigoSucursal**|Numérico||Si|Valor que identifica a||**transaccion**|Boolean|
|||||la sucursal donde|se|||
|||||realiza la emisión|de|||
|||||la Factura:||||
|||||Casa Matriz: 0||||
|||||Sucursal:||||
|||||1,2,..,n||||
|**nit**|Numérico||Si|NIT perteneciente|al|||
|||||emisor<br>de|la|||
|||||Factura.||||
|**codigoDocume**|Numérico||Si|Código que identifica||||
|**ntoSector**||||el sector de la||||
|||||Factura.||||
|**codigoEmision**|Numérico||Si|Describe si la||||
|||||emisión se realizó||||
|||||en línea. El valor||||
|||||permitido es:||||
|||||Online: 1||||
|**codigoModalid**|Numérico||Si|Electrónica en línea:||||
|**ad**||||1||||
|**cufd**|Alfanumérico||Si|Valor diario otorgado||||
|||||por el SIN.||||



|**cuis**|Alfanumérico|Si|Valor único para una|Valor único para una|Valor único para una|
|---|---|---|---|---|---|
||||sucursal y/o punto|||
||||de venta que se|de venta que se|de venta que se|
||||obtiene al realizar el|||
||||inicio<br>de|uso de|uso de|
||||sistemas.|||
|**tipoFacturaDoc**|Numérico|Si|Código que identifica|Código que identifica||
|**umento**|||el Tipo de Factura o|||
||||Documento|Documento|de|
||||Ajuste que se está|Ajuste que se está|Ajuste que se está|
||||enviando.|||
|**cuf**|Alfanumérico|Si|Código<br>Único||de|
||||Factura|a|ser|
||||validado.|||



## **Proceso de Verificación de Estado de Factura o Nota Crédito - Débito Electrónica por CUF** 

## **Recepción Anexo Electrolineras** 

Este servicio está habilitado para el envío del detalle de las recargas efectuadas con una tarjeta. El servicio implementado posee un objeto denominado SolicitudRecepcionSuministroAnexos el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre Método: recepcionAnexosSuministroEnergia**|**Nombre Método: recepcionAnexosSuministroEnergia**|**Nombre Método: recepcionAnexosSuministroEnergia**|**Nombre Método: recepcionAnexosSuministroEnergia**|||
|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**|||||



|||||||
|---|---|---|---|---|---|
|**codigoAmbient**|Numérico|Si|Describe el tipo|codigoEstad|Numérico|
|**e**|||de<br>ambiente|o||
||||utilizado,<br>los|||
||||valores|||
||||permitidos son:|||
||||Producción: 1|||
||||Pruebas y|||
||||Piloto: 2|||
|**codigoDocume**|Numérico|Si|Código<br>que|codigosRes|DTO[codigosRe|
|**ntoSector**|||identifica el sector<br>de la Factura.|puestas|spuesta]|
|**codigoEmision**|Numérico|Si|Describe<br>si la|transaccion|Boolean|
||||emisión<br>se|||
||||realizó en línea.|||
||||El<br>valor|||
||||permitido es:|||
||||Online: 1|||
|**codigoModalida**|Numérico|Si|Electrónica<br>en|||
|**d**|||línea: 1|||
|**codigoPuntoVe**|Numérico|No|Solo<br>se<br>envía|||
|**nta**|||cuando<br>la|||
||||transacción<br>se|||
||||realiza utilizando|||
||||un<br>punto<br>de|||
||||venta.<br>Caso|||
||||contrario enviar 0.|||
|**codigoSistema**|Alfanumérico|Si|Código<br>de|||
||||Sistema que le|||
||||fue asignado al|||
||||momento<br>de|||
||||realizar<br>la|||
||||solicitud<br>de|||
||||autorización.|||
|**codigoSucursal**|Numérico|Si|Valor<br>que|||
||||identifica<br>a<br>la|||
||||sucursal<br>donde|||
||||se<br>realiza<br>la|||
||||emisión<br>de<br>la|||
||||Factura:|||
||||Casa Matriz: 0|||
||||Sucursal:|||
||||1,2,..,n|||
|**cufd**|Alfanumérico||Valor<br>diario|||
||||otorgado por el|||
||||SIN|||
|cuis|Alfanumérico|Si|Valor único para|||
||||una sucursal y/o|||
||||punto de venta|||
||||que se obtiene al|||
|||||||



||||||
|---|---|---|---|---|
||||realizar el inicio||
||||de<br>uso|de|
||||sistemas.||
|**nit**|Numérico|Si|NIT perteneciente||
||||al emisor de la||
||||Factura.||
|**tipoFacturaDoc**|Numérico|Si|Código|que|
|**umento**|||identifica el sector||
||||de la Factura.||
|**cufFactSuminist**|Numérico|Si|Código|que|
|**ro**|||identifica<br>a|<br>la|
||||factura<br>emitida||
||||durante|la|
||||recarga||
|**fechaRecarga **|Fecha|Si|Fecha en la cual se realizo la recarga||
|**montoRecarga**|Numérico|Si|Monto de la recarga por el cual<br>||
||||se emitió la factura||
|**giftCard**|Numérico|Si|Identifica<br>a|<br>la<br> <br>|
||||tarjeta con la|cual|
||||se<br>realizo|la|
||||recarga||
||||||



## **FACTURACION COMPUTARIZADA EN LINEA** 

## **Recepción Factura** 

Esta compuesta por una serie de Servicios Web habilitados para recibir facturas individuales emitidas bajo las modalidades Electrónica y Computarizada en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. Dichos servicios reciben la factura verificando que los parámetros enviados sean válidos, analizan si el archivo recibido es correcto y validan el XML contra el XSD. 

Si el documento recibido supera esta etapa, el servicio devuelve el código de recepción, caso contrario, devuelve los códigos de error y advertencia. 

El servicio implementado posee un objeto denominado SolicitudServicioRecepcionFactura el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método:** RecepcionFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo de|**codigoEs**|Numérico|
|**e**||||ambiente utilizado, los|**tado**||
|||||valores permitidos son:|||
|||||Producción: 1|||
|||||PruebasyPiloto: 2|||
|**codigoPuntoVe**|Numérico||No|Solo se envía cuando|**codigoRe**|Alfanumérico|
|**nta**||||la<br>transacción<br>se|**cepcion**||
|||||realiza<br>utilizando un|||
|||||punto de venta. Caso|||
|||||contrario enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema que|**codigosR**|DTO[codigosRe|
|||||le<br>fue<br>asignado<br>al|**espuesta**|spuesta]|
|||||momento de realizar la|**s**||
|||||solicitud<br>de|||
|||||autorización.|||
|**codigoSucursal**|Numérico||Si|Valor que identifica a la||Boolean|
|||||sucursal<br>donde<br>se|**transacci**||
|||||realiza la emisión de la|||
|||||Factura:|**on**||
|||||Casa Matriz: 0|||
|||||Sucursal: 1,2,..,n|||
|**nit**|Numérico||Si|NIT perteneciente al||Alfanumérico|
|||||emisor de la Factura.|**codigoDesc**||
||||||**ripcion**||
|**codigoDocume**|Numérico||Si|Código que identifica el|||
|**ntoSector**||||sector de la Factura.|||
|**codigoEmision**|Numérico||Si|Describe si la emisión|||
|||||se realizó en línea. El|||
|||||valorpermitido es:|||



||||Online: 1|
|---|---|---|---|
|**codigoModalid**|Numérico|Si|Uno (1) Electrónica  y|
|**ad**|||dos (2) Computarizada|
||||en línea|
|**cufd**|Alfanumérico|Si|Valor diario otorgado<br> <br>|
||||por el SIN.|
|**cuis**|Alfanumérico|Si|Valor único para una<br> <br>|
||||sucursal y/o punto de|
||||venta que se obtiene al|
||||realizar el inicio de uso|
||||de sistemas.|
|**tipoFacturaDoc**|Numérico|Si|Código que identifica el<br> <br>|
|**umento**|||Tipo de Factura que se|
||||está enviando.|
|**archivo**|Alfanumérico|Si|Factura<br>que<br>es<br> <br>|
||||enviada<br>para<br>su|
||||validación.|
|**fechaEnvio**|TimeStamp|Si|Fecha y hora en la cual<br> <br>|
||||se envía la Factura.|
|**hashArchivo**|Alfanumérico|Si|Sha256 de la cadena|
||||Archivo que se envía.|



## **Anulación Factura** 

Está compuesta por una serie de Servicios Web habilitados para recibir solicitudes de anulación de facturas individuales emitidas bajo la modalidad Electrónica o Computarizada en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. 

Para la anulación de una Factura emitida en las modalidades Electrónica o  Computarizada en Línea, la misma deberá estar previamente registrada y validad por la Administración Tributaria. 

Dichos servicios previa validación de los parámetros enviados, registran la solicitud devolviendo un código de estado cuando la misma fue correcta o un código de error y advertencia en caso contrario. 

El servicio implementado posee un objeto denominado SolicitudServicioAnulacionFactura el cual contiene la información descrita en el siguiente cuadro: 

## **Nombre Método:** AnulacionFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbiente**|Numérico||Si|Describe el tipo de|**codigosRes**|DTO|
|||||ambiente<br>utilizado,|**puesta**|[codigosR|
|||||los<br>valores||espuesta]|
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas Piloto: 2|||
|**codigoPuntoVen**|Numérico||No|Solo<br>se<br>envía||Numérico|
|**ta**||||cuando<br>la|**codigoEstad**||
|||||transacción<br>se|||
|||||realiza utilizando un|**o**||
|||||punto<br>de<br>venta.|||
|||||Caso<br>contrario|||
|||||enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema|**transaccion**|Boolean|
|||||que le fue asignado|||
|||||al<br>momento<br>de|||
|||||realizar la solicitud|||
|||||de autorización.|||
|**codigoSucursal**|Numérico||Si|Valor que identifica a|**codigoDescripci**|Alfanumérico|
|||||la sucursal donde se|**on**||
|||||realiza la emisión de|||
|||||la Factura:|||
|||||Casa Matriz: 0|||
|||||Sucursal:1,2,...,n|||
|**nit**|Numérico||Si|NIT perteneciente al|||
|||||emisor<br>de<br>la|||
|||||Factura.|||
|**codigoDocumen**|Numérico||Si|Código que identifica|||
|**toSector**||||el<br>sector<br>de<br>la|||
|||||Factura.|||
|**codigoEmision**|Numérico||Si|Describe<br>si<br>la|||
|||||emisión se realizó|||
|||||en línea. El valor|||
|||||permitido es:|||
|||||Online: 1|||
|**codigoModalida**|Numérico||Si|Uno (1) Electrónica|||
|**d**||||y<br>dos<br>(2)|||



||||Computarizada|en|
|---|---|---|---|---|
||||línea||
|**cufd**|Alfanumérico|Si|Valor diario otorgado||
||||por el SIN.||
|**cuis**|Alfanumérico|Si|Valor único para una|Valor único para una|
||||sucursal y/o punto||
||||de venta que se|de venta que se|
||||obtiene al realizar el||
||||inicio<br>de<br>uso de|uso de|
||||sistemas.||
|**tipoFacturaDocu**|Numérico|Si|Código que identifica||
|**mento**|||el Tipo de Factura o||
||||Documento|de|
||||Ajuste que se está|Ajuste que se está|
||||enviando.||
|**codigoMotivo**|Numérico|Si|Paramétrica|que|
||||indica el motivo por|indica el motivo por|
||||el cual la Factura||
||||está siendo anulada.||
|**cuf**|Alfanumérico|Si|Código<br>único|de|
||||factura<br>que|está|
||||siendo anulado.||



## **Proceso de Recepción de Anulación de Factura Computarizada** 

## **Reversión Anulación Factura Computarizada** 

De acuerdo a RND Nº 102300000034 que indica “Asimismo, en caso de darse la anulación errónea de Documentos Fiscales, el Sujeto Pasivo del IVA a traves de su Sistema Informático de Facturación o la opción habilitada en la modalidad Portal Web en Línea, según corresponda de acuerdo a la modalidad de facturacion utilizada, podrá revertir por única vez la anulación y cambiar el estado de un Documento Fiscal a “VALIDO” hasta la fecha señalada en el párrafo precedente. Los Documentos Fiscales revertidos no podrán ser anulados”. Este servicio permite revertir el estado de las facturas digitales que fueron anuladas por error y por una sola vez. 

El servicio implementado posee un objeto denominado SolicitudServicioReversionAnulacionFactura el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método:** ReversionAnulacionFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbien**|Numérico||Si|Describe el tipo de|**codigoEstad**|Numérico|
|**te**||||ambiente utilizado,|**o**||
|||||los<br>valores|||
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas y|||
|||||Piloto: 2|||
|**codigoPuntoV**|Numérico||No|Solo<br>se<br>envía||DTO|
|**enta**||||cuando<br>la|**codigosRes**|[codigosRespu|
|||||transacción<br>se<br>realiza<br>utilizando|**puesta**|esta]|
|||||un punto de venta.|||
|||||Caso<br>contrario|||
|||||enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema|**transaccion**|Boolean|
|||||que<br>le<br>fue|||
|||||asignado<br>al|||
|||||momento<br>de|||
|||||realizar la solicitud|||
|||||de autorización.|||
|**codigoSucurs**|Numérico||Si|Valor que identifica||Alfanumérico|
|**al**||||a<br>la<br>sucursal|**codigoDescr**||
|||||donde se realiza la|**ipcion**||
|||||emisión<br>de<br>la|||
|||||Factura:|||
|||||Casa Matriz:|||
|||||0|||
|||||Sucursal:|||
|||||1,2,...,n|||



|**nit**|Numérico|Si|NIT perteneciente|
|---|---|---|---|
||||al emisor de la|
||||Factura.|
|**codigoDocum**|Numérico|Si|Código<br>que<br> <br>|
|**entoSector**|||identifica el sector|
||||de la Factura.|
|**codigoEmisio**|Numérico|Si|Describe<br>si<br>la<br> <br>|
|**n**|||emisión se realizó|
||||en línea. El valor|
||||permitido es:|
||||Online: 1|
|**codigoModalid**|Numérico|Si|Computarizada en|
|**ad**|||línea: 2|
|**cufd**|Alfanumérico|Si|Valor<br>diario<br> <br>|
||||otorgado<br>por<br>el|
||||SIN.|
|**cuis**|Alfanumérico|Si|Valor único para|
||||una sucursal y/o|
||||punto<br>de<br>venta|
||||que se obtiene al|
||||realizar el inicio de|
||||uso de sistemas.|
|**tipoFacturaDo**|Numérico|Si|Código<br>que<br> <br>|
|**cumento**|||identifica el Tipo|
||||de<br>Factura<br>o|
||||Documento<br>de|
||||Ajuste que se está|
||||revirtiendo.|
|**cuf**|Alfanumérico|Si|Código único de<br> <br>|
||||factura que está|
||||siendo revertida.|



**Nota:** Todos los sistemas ya autorizados que deseen utilizar este servicio deberán completar las pruebas para el mismo en ambiente piloto. Superadas las mismas y al presionar el botón de finalizar pruebas serán habilitados automáticamente para usar el servicio en producción. 

Los sistemas en las etapas iniciales o en proceso de autorización deberán completar este set de pruebas obligatoriamente. 

Los sistemas que se hallen ya en proceso de inspección, deberán terminar el proceso de forma normal y cuando el sistema este en producción solicitar el nuevo servicio via correo a soporte.aplicaciones@impuestos.gob.bo. 

## **Recepción Paquete Facturas** 

Está compuesta por una serie de Servicios Web habilitados para recibir paquetes de hasta 500 facturas emitidas bajo la modalidad Electrónica o Computarizada en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. Dichos servicios reciben el paquete verificando que los parámetros enviados sean válidos, analizan si el paquete recibido es correcto. 

Si el paquete recibido supera esta etapa, el servicio devuelve el código de recepción. Caso contrario, se devuelve un código de error o advertencia. 

El servicio implementado posee un objeto denominado SolicitudServicioRecepcionPaquete el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre Método:**RecepcionPaqueteFactura|**Nombre Método:**RecepcionPaqueteFactura|**Nombre Método:**RecepcionPaqueteFactura|**Nombre Método:**RecepcionPaqueteFactura|**Nombre Método:**RecepcionPaqueteFactura|||
|---|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**||**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**||||||
|**codigoAmbie**|Numérico||Si|Describe el tipo de|<br>**codigoEsta**|Numérico|
|**nte**||||ambiente utilizado,|<br>**do**||
|||||los<br>valores|||
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas y|||
|||||Piloto: 2|||
|**codigoPunto**|Numérico||No|Solo<br>se<br>envía||Alfanumérico|
|**Venta**||||cuando<br>la|<br>**codigoRece**||
|||||transacción<br>se<br>realiza utilizando un|<br> <br>**pcion**||
|||||punto<br>de<br>venta.|||
|||||Caso<br>contrario|||
|||||enviar 0.|||
|**codigoSiste**|Alfanumérico||Si|Código de Sistema|<br>**CodigosRes**|DTO[codigosRe|
|**ma**||||que le fue asignado|<br>**puestas**|spuesta]|
|||||al<br>momento<br>de|||
|||||realizar la solicitud|||
|||||de autorización.|||
|**codigoSucur**|Numérico||Si|Valor que identifica|<br>**transaccion**|Boolean|
|**sal**||||a la sucursal donde|||
|||||se<br>realiza<br>la|||
|||||emisión<br>de<br>la|||
|||||Factura:|||
|||||Casa Matriz: 0|||
|||||Sucursal:|||
|||||1,2,...,n|||
|**nit**|Numérico||Si|NIT<br>perteneciente||Alfanumérico|
|||||al<br>emisor<br>de la|<br>**codigoDescripc**||
|||||Factura.|**ion**||



|||||
|---|---|---|---|
|**codigoDocu**|Numérico|Si|Código<br>que|
|**mentoSector**|||identifica el sector|
||||de la  Factura.|
|**codigoEmisi**|Numérico|Si|Describe<br>si<br>la|
|**on**|||emisión se realizó|
||||fuera de línea. El|
||||valor permitido es:|
||||Offline : 2|
|**codigoModali**|Numérico|Si|Uno (1) Electrónica|
|**dad**|||y<br>dos<br>(2)|
||||Computarizada en|
||||línea|
|**cufd**|Alfanumérico|Si|Valor<br>diario<br> <br>|
||||otorgado<br>por<br>el|
||||SIN.|
|||||
|**cuis**|Alfanumérico|Si|Valor<br>único<br>para<br> <br>|
||||una<br>sucursal y/o|
||||punto de venta que|
||||se<br>obtiene<br>al|
||||realizar el inicio de|
||||uso de sistemas.|
|**tipoFacturaD**|Numérico|Si|Código<br>que<br> <br>|
|**ocumento**|||identifica el Tipo de|
||||Factura<br>o|
||||Documento<br>de|
||||Ajuste que se está|
||||enviando.|
|**archivo**|Alfanumérico|Si|Paquete<br>de|
||||Facturas que son|
||||enviadas para su|
||||validación.|
|**fechaEnvio**|TimeStamp|Si|Fecha y hora en la<br> <br>|
||||cual se envía la|
||||Factura.|
|**hashArchivo**|Alfanumérico|Si|Sha256<br>de<br>la<br> <br>|
||||cadena Archivo que|
||||se envía.|
|**cafc**|Alfanumérico|No|Código<br>de|
||||autorización<br>de|
||||emisión de facturas|
||||de<br>contingencia.|
|||||



||||Nulo<br>si<br>es|una|
|---|---|---|---|---|
||||factura normal||
|**cantidadFact**|Numérico|Si|Cantidad|de|
|**uras**|||Facturas enviadas||
||||en el paquete.||
|**codigoEvent**|Numérico|Si|Código|que|
|**o**|||devolvió el método||
||||de<br>registro|de|
||||evento.||



## **Proceso de Recepción de paquetes de Facturas Computarizadas** 

## **Validación Recepción Paquete Facturas** 

Está compuesta por una serie de Servicios Web habilitados para verificar el estado de los paquetes de facturas emitidos bajo la modalidad Electrónica o Computarizada en Línea y enviadas al SIN, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. 

Dichos servicios previa validación de los parámetros enviados, verifican el estado en el cual se encuentra los paquetes de facturas. Si todas las facturas pasaron las validaciones y no se encontraron errores se devuelve un código de aceptación, caso contrario se devuelve otro de rechazo junto a una lista con el detalle de aquellas Facturas con problemas y los errores o advertencias detectados en cada uno de ellos. 

El servicio implementado posee un objeto denominado SolicitudServicioValidacionRecepcionPaquete el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método:** validacionRecepcionPaqueteFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo|**codigoEstado**|Numérico|
|**e**||||de<br>ambiente|||
|||||utilizado,<br>los|||
|||||valores permitidos|||
|||||son:|||
|||||Producción: 1|||
|||||Pruebas y|||
|||||Piloto: 2|||
|**codigoPuntoVe**|Numérico||No|Solo<br>se<br>envía||Alfanumérico|
|**nta**||||cuando<br>la|**codigoDescrip**||
|||||transacción<br>se<br>realiza utilizando|**cion**||
|||||un<br>punto<br>de|||
|||||venta.<br>Caso|||
|||||contrario enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código<br>de||Alfanumérico|
|||||Sistema<br>que le<br>fue asignado al<br>momento<br>de|**codigoRecep**<br>**cion**||
|||||realizar la solicitud|||
|||||de autorización.|||
|**codigoSucursal**|Numérico||Si|Valor<br>que|**transaccion**|Boolean|
|||||identifica<br>a<br>la|||
|||||sucursal donde se|||
|||||realiza la emisión|||
|||||de la Factura:|||
|||||Casa Matriz: 0|||
|||||Sucursal:|||
|||||1,2,..,n|||
|**nit**|Numérico||Si|NIT perteneciente|**codigosRespu**|DTO[codigos|
|||||al emisor de la|**estas**|Respuesta]|
|||||Factura.|||
|**codigoDocume**|Numérico||Si|Código<br>que|||
|**ntoSector**||||identifica el sector|||
|||||de la Factura.|||
|**codigoEmision**|Numérico||Si|Describe<br>si<br>la|||
|||||emisión se realizó|||
|||||fuera de línea. El|||
|||||valor permitido es:|||
|||||Offline: 2|||
|**codigoModalid**|Numérico||Si|Uno<br>(1)|||
|**ad**||||Electrónica  y dos|||
|||||(2)|||
|||||Computarizada en|||
|||||línea|||



|**cufd**|Alfanumérico|Si|Valor|diario|
|---|---|---|---|---|
||||otorgado por el||
||||SIN.||
|**cuis**|Alfanumérico|Si|Valor único para|Valor único para|
||||una  sucursal y/o||
||||punto<br>de venta|de venta|
||||que se obtiene al||
||||realizar el inicio|realizar el inicio|
||||de<br>uso|de|
||||sistemas.||
|**tipoFacturaDoc**|Numérico|Si|Código|que|
|**umento**|||identifica el Tipo|identifica el Tipo|
||||de<br>Factura|Factura<br>o|
||||Documento|de|
||||Ajuste que se está||
||||enviando.||
|**codigoRecepci**|Alfanumérico|Si|Código Recepción||
|**on**|||enviado<br>por<br>el||
||||SIN.||



## **Proceso de Recepción de Paquetes de Facturas Computarizadas** 

## **Verifica Comunicación** 

Este servicio recibe la solicitud de verificación de comunicación, registra la misma y devuelve un código de comunicación exitosa 

|**Nombre Método:**verificarComunicacion|**Nombre Método:**verificarComunicacion|**Nombre Método:**verificarComunicacion|||||
|---|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**||||||
|**ninguna**|ninguno||No|ninguna|**return =**|Numérico|
||||corresp||**926**||
||||onde||**(comuni**||
||||||**cación**||
||||||**exitosa)**||



## **Proceso de Solicitud de Verificación de Comunicación** 

## **Recepción Masiva Facturas** 

Está compuesta por una serie de Servicios Web habilitados para recibir facturas en forma masiva bajo la modalidad Electrónica o Computarizada en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. Dichos servicios reciben el paquete verificando que los parámetros enviados sean válidos, verificando si el paquete recibido es correcto. 

Si el paquete recibido supera esta etapa, el servicio devuelve el código de recepción. Caso contrario, se devuelve los códigos de error o advertencia. 

Se debe considerar los siguiente: 

- Periodicidad con la que enviará: Diario, semanal o mensual. 

## - Tamaño de los paquetes: máximo 1000. 

Estos puntos a ser considerados se registrarán a través de la opción habilitada en el portal web de la Administración Tributaria. 

El servicio implementado posee un objeto denominado SolicitudServicioRecepcionMasiva el cual contiene la información descrita en el siguiente cuadro: 

|||||||||
|---|---|---|---|---|---|---|---|
|**Nombre Método:**|recepcionMasivaFactura|||||||
|**Entrada**|**Tipo**|**Obligatorio**||**Descripción**||**Salida**|**Tipo Dato**|
||**Dato**|||||||
|**codigoAmbien**|Numérico||Si|Describe el tipo de|<br>**codigoEstado**||Numérico|
|**te**||||ambiente utilizado,||||
|||||los<br>valores||||
|||||permitidos son:||||
|||||Producción: 1||||
|||||Pruebas y||||
|||||Piloto: 2||||
|**codigoPuntoV**|Numérico||No|Solo<br>se<br>envía|||Alfanumérico|
|**enta**||||cuando<br>la<br>transacción<br>se<br>realiza<br>utilizando|<br> <br> <br>**codigoRecepc**<br>**ion**|||
|||||un punto de venta.||||
|||||Caso<br>contrario||||
|||||enviar 0.||||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema|<br>**codigosRespu**||DTO[codigos|
|||||que<br>le<br>fue|<br>**estas**||Respuesta]|
|||||asignado<br>al||||
|||||momento<br>de||||
|||||realizar la solicitud||||
|||||de autorización.||||
|**codigoSucurs**|Numérico||Si|Valor que identifica|<br>**transaccion**||Boolean|
|**al**||||a<br>la<br>sucursal||||
|||||donde se realiza la||||
|||||emisión<br>de<br>la||||
|||||Factura:||||
|||||Casa Matriz: 0||||
|||||Sucursal:||||
|||||1,2,..,n||||
|**nit**|Numérico||Si|NIT perteneciente|||Alfanumérico|
|||||al emisor de la|<br>**codigoDescripcio**|||
||||||**n**|||
|||||Factura.||||
|**codigoDocum**|Numérico||Si|Código<br>que||||
|**entoSector**||||identifica el sector||||
|||||de la Factura.1||||
|**codigoEmisio**|Numérico||Si|El valor permitido||||
|**n**||||es:||||
|||||Masiva: 3||||
|**codigoModalid**|Numérico||Si|Uno<br>(1)||||
|**ad**||||Electrónicaydos||||



|||||||
|---|---|---|---|---|---|
|||||(2) Computarizada||
|||||en línea||
||**cufd**|Alfanumérico|Si|Valor<br>diario||
|||||otorgado<br>por<br>el||
|||||SIN.||
||**cuis**|Alfanumérico|Si|Valor único para||
|||||una sucursal y/o||
|||||punto<br>de<br>venta||
|||||que se obtiene al||
|||||realizar el inicio de||
|||||uso de sistemas.||
||**tipoFacturaDo**|Numérico|Si|Código<br>que||
||**cumento**|||identifica el Tipo||
|||||de<br>Factura<br>o||
|||||Documento<br>de||
|||||Ajuste que se está||
|||||enviando.||
||**archivo**|Alfanumérico|Si|Paquete<br>de||
|||||Facturas que son||
|||||enviados para su||
|||||validación.||
||**fechaEnvio**|TimeStamp|Si|Fecha y hora en la||
|||||cual se envía la||
|||||Factura.||
||**hashArchivo**|Alfanumérico|Si|Sha256<br>de<br>la||
|||||cadena<br>Archivo||
|||||que se envía.||
||**cantidadFactu**|Numérico|Si|Cantidad<br>de||
||**ras**|||Facturas que son||
|||||enviadas<br>dentro||
|||||del paquete.||
|||||||



## **Proceso de Recepción Masiva de Factura Computarizada** 

## **Validación Recepción Masiva Facturas** 

Está compuesta por una serie de Servicios Web habilitados para verificar el estado de las facturas enviadas al SIN en forma masiva bajo la modalidad Electrónica o Computarizada en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. 

Dichos servicios previa validación de los parámetros enviados, verifican el estado en el cual se encuentra las facturas recibidas, si todas pasaron las validaciones y no se encontraron errores se devuelve un código de aceptación, caso contrario se devuelve otro de observación junto a una lista con el detalle de aquellas facturas con problemas y los errores o advertencias detectadas en cada uno de ellas. 

El servicio implementado posee un objeto denominado SolicitudServicioValidacionRecepcionMasiva el cual contiene la información descrita en el siguiente cuadro: 

## **Nombre Método:** validacionRecepcionMasivaFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo de|Describe el tipo de<br>**codigoEsta**|Numérico|
|**e**||||ambiente<br>utilizado,|utilizado,<br>**do**||
|||||los<br>valores|valores||
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas y Piloto:|Pruebas y Piloto:||
|||||2|||



|||||||||
|---|---|---|---|---|---|---|---|
||**codigoPuntoVe**|Numérico|No|Solo<br>se<br>envía||Alfanumérico||
||**nta**|||cuando<br>la|<br>**codigoDesc**|||
|||||transacción<br>se<br>realiza utilizando un|<br> <br>**ripcion**|||
|||||punto de venta. Caso||||
|||||contrario enviar 0.||||
||**codigoSistema**|Alfanumérico|Si|Código de Sistema||Alfanumérico||
|||||que le fue asignado<br>al<br>momento<br>de|<br> <br>**codigoRec**|||
|||||realizar la solicitud|<br>**epcion**|||
|||||de autorización.||||
||**codigoSucursal**|<br>Numérico|Si|Valor que identifica a||Boolean||
|||||la sucursal donde se|<br>**transaccio**|||
|||||realiza la emisión de<br>la Factura:|<br>**n**|||
|||||Casa Matriz: 0||||
|||||Sucursal: 1,2,..,n||||
||**nit**|Numérico|Si|NIT perteneciente al|<br>**codigosRes**|DTO[codigos||
|||||emisor de la Factura.|<br>**puestas**|Respuesta]||
||**codigoDocume**|Numérico|Si|Código que identifica||||
||**ntoSector**|||el<br>sector<br>de<br>la||||
|||||Factura.||||
||**codigoEmision**|Numérico|Si|El<br>valor permitido||||
|||||es:||||
|||||Masiva: 3||||
||**codigoModalid**|Numérico|Si|Uno (1) Electrónica||||
||**ad**|||y<br>dos<br>(2)||||
|||||Computarizada<br>en||||
|||||línea||||
||**cufd**|Alfanumérico|Si|Valor diario otorgado||||
|||||por el SIN.||||
||**cuis**|Alfanumérico|Si|Valor único para una||||
|||||sucursal y/o punto de||||
|||||venta que se obtiene||||
|||||al realizar el inicio de||||
|||||uso de sistemas.||||
||**tipoFacturaDoc**|Numérico|Si|Código que identifica|<br>|||
||**umento**|||el Tipo de Factura  o||||
|||||Documento<br>de||||
|||||Ajuste que se está||||
|||||enviando.||||
||**codigoRecepci**|Alfanumérico|Si|Código<br>Recepción||||
||**on**|||enviado por el SIN.||||
|||||||||



## **Proceso de Validación de Factura Computarizada Masiva** 

## **Verificación Estado de Factura** 

Este servicio está habilitado para verificar el estado de las Facturas o Notas Crédito - Débito emitidas bajo la modalidad Computarizada en Línea y que fueron enviadas al SIN, cuando se desea saber el estado de la Factura o Nota de Crédito - Débito, podrá realizarse a través del Código Único de Factura (CUF). 

Este servicio, previa validación de los parámetros enviados, verifica el estado en el cual se encuentra la Factura o Nota Crédito - Débito. Si la misma paso todas las validaciones y no se encontraron errores, se devuelve un código de aceptación caso contrario se devuelve otro de observación junto a una lista con el detalle de los mismos. 

|El<br>servicio|implementado|posee|un|objeto|denominado|
|---|---|---|---|---|---|
|SolicitudServicioVerificaEstadoFactura el cual contiene la información descrita en el||||SolicitudServicioVerificaEstadoFactura el cual contiene la información descrita en el|SolicitudServicioVerificaEstadoFactura el cual contiene la información descrita en el|
|siguiente cuadro:||||||



|**Nombre Método:**|**Nombre Método:**verificacionEstadoFactura|**Nombre Método:**verificacionEstadoFactura|verificacionEstadoFactura||||
|---|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**|**Descripción**||**Salida**|**Tipo Dato**|
||**Dato**||||||
|**codigoAmbient**|Numérico|Numérico<br>Si|Describe el tipo de|Describe el tipo de|**codigoEstad**|Numérico|
|**e**|||ambiente<br>utilizado,||**o**||
||||los<br>valores||||
||||permitidos son:||||
||||Producción: 1|Producción: 1|||
||||Pruebas y||||
||||Piloto: 2||||
|**codigoPuntoVe**|Numérico|Numérico<br>No|Solo<br>se<br>envía||**codigoRece**|Alfanumérico|
|**nta**|||cuando|la|**pcion**||
||||transacción|se|||
||||realiza utilizando un|realiza utilizando un|||
||||punto<br>de<br>venta.||||



|||||||||
|---|---|---|---|---|---|---|---|
||||Caso|contrario||||
||||enviar 0.|||||
|**codigoSistema**|Alfanumérico|Si|Código de|Sistema||||
||||que le fue|asignado||**codigosRes**|DTO[codigos|
||||al<br>momento<br>de<br>realizar la solicitud|||**puestas**|Respuesta]|
||||de autorización|||||
|**codigoSucursal**|Numérico|Si|Valor que identifica a|||**transaccion**|Boolean|
||||la sucursal|donde|se|||
||||realiza la emisión||de|||
||||la Factura:|||||
||||Casa Matriz: 0|||||
||||Sucursal:|||||
||||1,2,..,n|||||
|**nit**|Numérico|Si|NIT perteneciente||al|||
||||emisor|de|la|||
||||Factura.|||||
|**codigoDocume**|Numérico|Si|Código que|identifica||||
|**ntoSector**|||el sector de|la||||
||||Factura.|||||
|**codigoEmision**|Numérico|Si|Describe si|la||||
||||emisión se realizó|||||
||||en línea. El|valor||||
||||permitido es:|||||
||||Online: 1|||||
|**codigoModalid**|Numérico|Si|dos(2)|||||
|**ad**|||Computarizada en|||||
||||Línea|||||
|**cufd**|Alfanumérico|Si|Valor diario|otorgado||||
||||por el SIN.|||||
|**cuis**|Alfanumérico|Si|Valor único|para una||||
||||sucursal y/o punto|||||
||||de venta|que|se|||
||||obtiene al realizar||el|||
||||inicio<br>de|uso|de|||
||||sistemas.|||||
|**tipoFacturaDoc**|Numérico|Si|Código que|identifica||||
|**umento**|||el Tipo de Factura o|||||
||||Documento||de|||
||||Ajuste que|se está||||
||||enviando.|||||
|||||||||



**cuf** Alfanumérico Si Código Único de Factura a ser validado. 

## **Proceso de Verificación de Estado de Factura o Nota Crédito - Débito Electrónica por CUF** 

## **Recepción Anexo Electrolineras** 

Este servicio está habilitado para el envío del detalle de las recargas efectuadas con una tarjeta. El servicio implementado posee un objeto denominado SolicitudRecepcionSuministroAnexos el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método: recepcionAnexosSuministroEnergia** 

|**Entrada**|**Tipo**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|
||**Dato**|||||
|**codigoAmbient**|Numérico|Numérico<br>Si|Describe el tipo|codigoEstad|Numérico|
|**e**|||de<br>ambiente|o||
||||utilizado,<br>los|||
||||valores|||
||||permitidos son:|||
||||Producción: 1|||
||||Pruebas y|||
||||Piloto: 2|||
|**codigoDocume**|Numérico|Numérico<br>Si|Código<br>que|codigosRes|DTO[codigosRes|
|**ntoSector**|||identifica el sector<br>de la Factura.|puestas|puesta]|
|**codigoEmision**|Numérico|Numérico<br>Si|Describe<br>si la|transaccion|Boolean|
||||emisión<br>se|||
||||realizó en línea.|||
||||El<br>valor|||
||||permitido es:|||



|||||
|---|---|---|---|
||||Online: 1|
|**codigoModalida**|Numérico|Si|Electrónica<br>en|
|**d**|||línea: 1|
|**codigoPuntoVe**|Numérico|No|Solo<br>se<br>envía|
|**nta**|||cuando<br>la|
||||transacción<br>se|
||||realiza utilizando|
||||un<br>punto<br>de|
||||venta.<br>Caso|
||||contrario enviar 0.|
|**codigoSistema**|Alfanumérico|Si|Código<br>de|
||||Sistema que le|
||||fue asignado al|
||||momento<br>de|
||||realizar<br>la|
||||solicitud<br>de|
||||autorización.|
|**codigoSucursal**|Numérico|Si|Valor<br>que<br> <br>|
||||identifica<br>a<br>la|
||||sucursal<br>donde|
||||se<br>realiza<br>la|
||||emisión<br>de<br>la|
||||Factura:|
||||Casa Matriz: 0|
||||Sucursal:|
||||1,2,..,n|
|**cufd**|Alfanumérico||Valor<br>diario|
||||otorgado por el|
||||SIN|
|cuis|Alfanumérico|Si|Valor único para|
||||una sucursal y/o|
||||punto de venta|
||||que se obtiene al|
||||realizar el inicio|
||||de<br>uso<br>de|
||||sistemas.|
|**nit**|Numérico|Si|NIT perteneciente|
||||al emisor de la|
||||Factura.|
|**tipoFacturaDoc**|Numérico|Si|Código<br>que|
|**umento**|||identifica el sector|
||||de la Factura.|
|**cufFactSuminist**|Numérico|Si|Código<br>que|
|**ro**|||identifica<br>a<br>la|
||||factura<br>emitida|
||||durante<br>la|
||||recarga|
|**fechaRecarga **|Fecha|Si|Fecha en la cual se realizo la recarga|
|||||



||||||
|---|---|---|---|---|
|**montoRecarga**|Numérico|Si|Monto de la recarga por el cual<br>||
||||se emitió la factura||
|**giftCard**|Numérico|Si|Identifica<br>a|<br>la<br>|
||||tarjeta con la|cual|
||||se<br>realizo|la|
||||recarga||
||||||



## **SERVICIO FACTURA COMPRA Y VENTA** 

## **Recepción Factura** 

Esta compuesta por una serie de Servicios Web habilitados para recibir facturas individuales emitidas bajo las modalidades Electrónica y Computarizada en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. Dichos servicios reciben la factura verificando que los parámetros enviados sean válidos, analizan si el archivo recibido es correcto y validan el XML contra el XSD. 

Si el documento recibido supera esta etapa, el servicio devuelve el código de recepción, caso contrario, devuelve los códigos de error y advertencia. 

El servicio implementado posee un objeto denominado SolicitudServicioRecepcionFactura el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre Método:**|RecepcionFactura|RecepcionFactura|||||
|---|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**||**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo de|**codigoEs**|Numérico|
|**e**||||ambiente utilizado, los|**tado**||
|||||valores permitidos son:|||
|||||Producción: 1|||
|||||PruebasyPiloto: 2|||
|**codigoPuntoVe**|Numérico||No|Solo se envía cuando|**codigoRe**|Alfanumérico|
|**nta**||||la<br>transacción<br>se|**cepcion**||
|||||realiza<br>utilizando un|||
|||||punto de venta. Caso|||
|||||contrario enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema que|**codigosR**|DTO[codigosR|
|||||le<br>fue<br>asignado<br>al|**espuesta**|espuesta]|
|||||momento de realizar la|**s**||
|||||solicitud<br>de|||
|||||autorización.|||
|**codigoSucursal**|Numérico||Si|Valor que identifica a la||Boolean|
|||||sucursal<br>donde<br>se|**transacci**||
|||||realiza la emisión de la|||
|||||Factura:|**on**||
|||||Casa Matriz: 0|||
|||||Sucursal: 1,2,..,n|||
|**nit**|Numérico||Si|NIT perteneciente al||Alfanumérico|
|||||emisor de la Factura.|**codigoDesc**||
||||||**ripcion**||
|**codigoDocume**|Numérico||Si|Código que identifica el|||
|**ntoSector**||||sector de la Factura.|||
|**codigoEmision**|Numérico||Si|Describe si la emisión|||
|||||se realizó en línea. El|||
|||||valor permitido es:|||
|||||Online: 1|||



|**codigoModalid**|Numérico|Si|Uno (1) Electrónica  y|
|---|---|---|---|
|**ad**|||dos (2) Computarizada|
||||en línea|
|**cufd**|Alfanumérico|Si|Valor diario otorgado<br> <br>|
||||por el SIN.|
|**cuis**|Alfanumérico|Si|Valor único para una<br> <br>|
||||sucursal y/o punto de|
||||venta que se obtiene al|
||||realizar el inicio de uso|
||||de sistemas.|
|**tipoFacturaDoc**|Numérico|Si|Código que identifica el<br> <br>|
|**umento**|||Tipo de Factura que se|
||||está enviando.|
|**archivo**|Alfanumérico|Si|Factura<br>que<br>es<br> <br>|
||||enviada<br>para<br>su|
||||validación.|
|**fechaEnvio**|TimeStamp|Si|Fecha y hora en la cual<br> <br>|
||||se envía la Factura.|
|**hashArchivo**|Alfanumérico|Si|Sha256 de la cadena<br>|
||||Archivo que se envía.|



## **Anulación Factura** 

Está compuesta por una serie de Servicios Web habilitados para recibir solicitudes de anulación de facturas individuales emitidas bajo la modalidad Electrónica o Computarizada en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. 

Para la anulación de una Factura emitida en las modalidades Electrónica o  Computarizada en Línea, la misma deberá estar previamente registrada y validad por la Administración Tributaria. 

Dichos servicios previa validación de los parámetros enviados, registran la solicitud devolviendo un código de estado cuando la misma fue correcta o un código de error y advertencia en caso contrario. 

El servicio implementado posee un objeto denominado SolicitudServicioAnulacionFactura el cual contiene la información descrita en el siguiente cuadro: 

## **Nombre Método:** AnulacionFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbiente**|Numérico||Si|Describe el tipo de|**codigosRes**|DTO|
|||||ambiente<br>utilizado,|**puesta**|[codigosR|
|||||los<br>valores||espuesta]|
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas Piloto: 2|||
|**codigoPuntoVen**|Numérico||No|Solo<br>se<br>envía||Numérico|
|**ta**||||cuando<br>la|**codigoEstad**||
|||||transacción<br>se|||
|||||realiza utilizando un|**o**||
|||||punto<br>de<br>venta.|||
|||||Caso<br>contrario|||
|||||enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema|**transaccion**|Boolean|
|||||que le fue asignado|||
|||||al<br>momento<br>de|||
|||||realizar la solicitud|||
|||||de autorización.|||
|**codigoSucursal**|Numérico||Si|Valor que identifica a|**codigoDescripci**|Alfanumérico|
|||||la sucursal donde se|**on**||
|||||realiza la emisión de|||
|||||la Factura:|||
|||||Casa Matriz: 0|||
|||||Sucursal:1,2,...,n|||
|**nit**|Numérico||Si|NIT perteneciente al|||
|||||emisor<br>de<br>la|||
|||||Factura.|||
|**codigoDocumen**|Numérico||Si|Código que identifica|||
|**toSector**||||el<br>sector<br>de<br>la|||
|||||Factura.|||
|**codigoEmision**|Numérico||Si|Describe<br>si<br>la|||
|||||emisión se realizó|||
|||||en línea. El valor|||
|||||permitido es:|||
|||||Online: 1|||
|**codigoModalida**|Numérico||Si|Uno (1) Electrónica|||
|**d**||||y<br>dos<br>(2)|||



||||Computarizada|en|
|---|---|---|---|---|
||||línea||
|**cufd**|Alfanumérico|Si|Valor diario otorgado||
||||por el SIN.||
|**cuis**|Alfanumérico|Si|Valor único para una|Valor único para una|
||||sucursal y/o punto||
||||de venta que se|de venta que se|
||||obtiene al realizar el||
||||inicio<br>de<br>uso de|uso de|
||||sistemas.||
|**tipoFacturaDocu**|Numérico|Si|Código que identifica||
|**mento**|||el Tipo de Factura o||
||||Documento|de|
||||Ajuste que se está|Ajuste que se está|
||||enviando.||
|**codigoMotivo**|Numérico|Si|Paramétrica|que|
||||indica el motivo por|indica el motivo por|
||||el cual la Factura||
||||está siendo anulada.||
|**cuf**|Alfanumérico|Si|Código<br>único|de|
||||factura<br>que|está|
||||siendo anulado.||



## **Proceso de Recepción de Anulación de Factura Computarizada** 

## **Recepción Paquete Facturas** 

Está compuesta por una serie de Servicios Web habilitados para recibir paquetes de hasta 500 facturas emitidas bajo la modalidad Electrónica o Computarizada en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. Dichos servicios reciben el paquete verificando que los parámetros enviados sean válidos, analizan si el paquete recibido es correcto. 

Si el paquete recibido supera esta etapa, el servicio devuelve el código de recepción. Caso contrario, se devuelve un código de error o advertencia. 

El servicio implementado posee un objeto denominado SolicitudServicioRecepcionPaquete el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre Método:**RecepcionPaqueteFactura|**Nombre Método:**RecepcionPaqueteFactura|**Nombre Método:**RecepcionPaqueteFactura|**Nombre Método:**RecepcionPaqueteFactura|**Nombre Método:**RecepcionPaqueteFactura|||
|---|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**||**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**||||||
|**codigoAmbie**|Numérico||Si|Describe el tipo de|**codigoEsta**|Numérico|
|**nte**||||ambiente utilizado,|**do**||
|||||los<br>valores|||
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas y|||
|||||Piloto: 2|||
|**codigoPunto**|Numérico||No|Solo<br>se<br>envía||Alfanumérico|
|**Venta**||||cuando<br>la|**codigoRece**||
|||||transacción<br>se<br>realiza utilizando un|**pcion**||
|||||punto<br>de<br>venta.|||
|||||Caso<br>contrario|||
|||||enviar 0.|||
|**codigoSiste**|Alfanumérico||Si|Código de Sistema|**CodigosRes**|DTO[codigosRe|
|**ma**||||que le fue asignado|**puestas**|spuesta]|
|||||al<br>momento<br>de|||
|||||realizar la solicitud|||
|||||de autorización.|||
|**codigoSucur**|Numérico||Si|Valor que identifica|**transaccion**|Boolean|
|**sal**||||a la sucursal donde|||
|||||se<br>realiza<br>la|||
|||||emisión<br>de<br>la|||
|||||Factura:|||
|||||Casa Matriz: 0|||
|||||Sucursal:|||
|||||1,2,...,n|||
|**nit**|Numérico||Si|NIT<br>perteneciente||Alfanumérico|
|||||al<br>emisor<br>de la|**codigoDescripc**||
|||||Factura.|**ion**||



|||||
|---|---|---|---|
|**codigoDocu**|Numérico|Si|Código<br>que|
|**mentoSector**|||identifica el sector|
||||de la  Factura.|
|**codigoEmisi**|Numérico|Si|Describe<br>si<br>la|
|**on**|||emisión se realizó|
||||fuera de línea. El|
||||valor permitido es:|
||||Offline : 2|
|**codigoModali**|Numérico|Si|Uno (1) Electrónica|
|**dad**|||y<br>dos<br>(2)|
||||Computarizada en|
||||línea|
|**cufd**|Alfanumérico|Si|Valor<br>diario<br> <br>|
||||otorgado<br>por<br>el|
||||SIN.|
|**cuis**|Alfanumérico|Si|Valor<br>único<br>para<br> <br>|
||||una<br>sucursal y/o|
||||punto de venta que|
||||se<br>obtiene<br>al|
||||realizar el inicio de|
||||uso de sistemas.|
|**tipoFacturaD**|Numérico|Si|Código<br>que<br> <br>|
|**ocumento**|||identifica el Tipo de|
||||Factura<br>o|
||||Documento<br>de|
||||Ajuste que se está|
||||enviando.|
|**archivo**|Alfanumérico|Si|Paquete<br>de|
||||Facturas que son|
||||enviadas para su|
||||validación.|
|**fechaEnvio**|TimeStamp|Si|Fecha y hora en la<br> <br>|
||||cual se envía la|
||||Factura.|
|**hashArchivo**|Alfanumérico|Si|Sha256<br>de<br>la<br> <br>|
||||cadena Archivo que|
||||se envía.|
|**cafc**|Alfanumérico|No|Código<br>de|
||||autorización<br>de|
||||emisión de facturas|
||||de<br>contingencia.|
|||||



||||Nulo<br>si<br>es|una|
|---|---|---|---|---|
||||factura normal||
|**cantidadFact**|Numérico|Si|Cantidad|de|
|**uras**|||Facturas enviadas||
||||en el paquete.||
|**codigoEvent**|Numérico|Si|Código|que|
|**o**|||devolvió el método||
||||de<br>registro|de|
||||evento.||



## **Proceso de Recepción de paquetes de Facturas Computarizadas** 

## **Validación Recepción Paquete Facturas** 

Está compuesta por una serie de Servicios Web habilitados para verificar el estado de los paquetes de facturas emitidos bajo la modalidad Electrónica o Computarizada en Línea y enviadas al SIN, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. 

Dichos servicios previa validación de los parámetros enviados, verifican el estado en el cual se encuentra los paquetes de facturas. Si todas las facturas pasaron las validaciones y no se encontraron errores se devuelve un código de aceptación, caso contrario se devuelve otro de rechazo junto a una lista con el detalle de aquellas Facturas con problemas y los errores o advertencias detectados en cada uno de ellos. 

El servicio implementado posee un objeto denominado SolicitudServicioValidacionRecepcionPaquete el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método:** validacionRecepcionPaqueteFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo|**codigoEstado**|Numérico|
|**e**||||de<br>ambiente|||
|||||utilizado,<br>los|||
|||||valores permitidos|||
|||||son:|||
|||||Producción: 1|||
|||||Pruebas y|||
|||||Piloto: 2|||
|**codigoPuntoVe**|Numérico||No|Solo<br>se<br>envía||Alfanumérico|
|**nta**||||cuando<br>la|**codigoDescrip**||
|||||transacción<br>se<br>realiza utilizando|**cion**||
|||||un<br>punto<br>de|||
|||||venta.<br>Caso|||
|||||contrario enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código<br>de||Alfanumérico|
|||||Sistema<br>que le<br>fue asignado al<br>momento<br>de|**codigoRecep**<br>**cion**||
|||||realizar la solicitud|||
|||||de autorización.|||
|**codigoSucursal**|Numérico||Si|Valor<br>que|**transaccion**|Boolean|
|||||identifica<br>a<br>la|||
|||||sucursal donde se|||
|||||realiza la emisión|||
|||||de la Factura:|||
|||||Casa Matriz: 0|||
|||||Sucursal:|||
|||||1,2,..,n|||
|**nit**|Numérico||Si|NIT perteneciente|**codigosRespu**|DTO[codigos|
|||||al emisor de la|**estas**|Respuesta]|
|||||Factura.|||
|**codigoDocume**|Numérico||Si|Código<br>que|||
|**ntoSector**||||identifica el sector|||
|||||de la Factura.|||
|**codigoEmision**|Numérico||Si|Describe<br>si<br>la|||
|||||emisión se realizó|||
|||||fuera de línea. El|||
|||||valor permitido es:|||
|||||Offline: 2|||
|**codigoModalid**|Numérico||Si|Uno<br>(1)|||
|**ad**||||Electrónica  y dos|||
|||||(2)|||
|||||Computarizada en|||
|||||línea|||



|**cufd**|Alfanumérico|Si|Valor|diario|
|---|---|---|---|---|
||||otorgado por el||
||||SIN.||
|**cuis**|Alfanumérico|Si|Valor único para|Valor único para|
||||una  sucursal y/o||
||||punto<br>de venta|de venta|
||||que se obtiene al||
||||realizar el inicio|realizar el inicio|
||||de<br>uso|de|
||||sistemas.||
|**tipoFacturaDoc**|Numérico|Si|Código|que|
|**umento**|||identifica el Tipo|identifica el Tipo|
||||de<br>Factura|Factura<br>o|
||||Documento|de|
||||Ajuste que se está||
||||enviando.||
|**codigoRecepci**|Alfanumérico|Si|Código Recepción||
|**on**|||enviado<br>por<br>el||
||||SIN.||



## **Proceso de Recepción de Paquetes de Facturas Computarizadas** 

## **Recepción Masiva Facturas** 

Está compuesta por una serie de Servicios Web habilitados para recibir facturas en forma masiva bajo la modalidad Electrónica o Computarizada en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. Dichos servicios reciben el paquete verificando que los parámetros enviados sean válidos, verificando si el paquete recibido es correcto. 

Si el paquete recibido supera esta etapa, el servicio devuelve el código de recepción. Caso contrario, se devuelve los códigos de error o advertencia. 

Se debe considerar los siguiente: 

- Periodicidad con la que enviará: Diario, semanal o mensual. 

- Tamaño de los paquetes: máximo 1000. 

Estos puntos a ser considerados se registrarán a través de la opción habilitada en el portal web de la Administración Tributaria. 

El servicio implementado posee un objeto denominado SolicitudServicioRecepcionMasiva el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método:** recepcionMasivaFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbien**|Numérico||Si|Describe el tipo de|**codigoEstado**|Numérico|
|**te**||||ambiente utilizado,|||
|||||los<br>valores|||
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas y|||
|||||Piloto: 2|||
|**codigoPuntoV**|Numérico||No|Solo<br>se<br>envía||Alfanumérico|
|**enta**||||cuando<br>la<br>transacción<br>se<br>realiza<br>utilizando|**codigoRecepc**<br>**ion**||
|||||un punto de venta.|||
|||||Caso<br>contrario|||
|||||enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema|**codigosRespu**|DTO[codigos|
|||||que<br>le<br>fue|**estas**|Respuesta]|
|||||asignado<br>al|||
|||||momento<br>de|||
|||||realizar la solicitud|||
|||||de autorización.|||



|||||||
|---|---|---|---|---|---|
|**codigoSucurs**|Numérico|Si|Valor que identifica|**transaccion**|Boolean|
|**al**|||a<br>la<br>sucursal|||
||||donde se realiza la|||
||||emisión<br>de<br>la|||
||||Factura:|||
||||Casa Matriz: 0|||
||||Sucursal:|||
||||1,2,..,n|||
|**nit**|Numérico|Si|NIT perteneciente||Alfanumérico|
||||al emisor de la|**codigoDescripcio**||
||||Factura.|**n**||
|**codigoDocum**|Numérico|Si|Código<br>que|||
|**entoSector**|||identifica el sector|||
||||de la Factura.1|||
|**codigoEmisio**|Numérico|Si|El valor permitido|||
|**n**|||es:|||
||||Masiva: 3|||
|**codigoModalid**|Numérico|Si|Uno<br>(1)|||
|**ad**|||Electrónica  y dos|||
||||(2) Computarizada|||
||||en línea|||
|**cufd**|Alfanumérico|Si|Valor<br>diario|||
||||otorgado<br>por<br>el|||
||||SIN.|||
|**cuis**|Alfanumérico|Si|Valor único para|||
||||una sucursal y/o|||
||||punto<br>de<br>venta|||
||||que se obtiene al|||
||||realizar el inicio de|||
||||uso de sistemas.|||
|**tipoFacturaDo**|Numérico|Si|Código<br>que|||
|**cumento**|||identifica el Tipo|||
||||de<br>Factura<br>o|||
||||Documento<br>de|||
||||Ajuste que se está|||
||||enviando.|||
|**archivo**|Alfanumérico|Si|Paquete<br>de|||
||||Facturas que son|||
||||enviados para su|||
||||validación.|||
|||||||



|**fechaEnvio**|TimeStamp|Si|Fecha y hora en la|Fecha y hora en la|
|---|---|---|---|---|
||||cual se envía la|cual se envía la|
||||Factura.||
|**hashArchivo**|Alfanumérico|Si|Sha256|de<br>la|
||||cadena|Archivo|
||||que se envía.||
|**cantidadFactu**|Numérico|Si|Cantidad|Cantidad<br>de|
|**ras**|||Facturas que son|Facturas que son|
||||enviadas|enviadas<br>dentro|
||||del paquete.||



## **Proceso de Recepción Masiva de Factura Computarizada** 

## **Validación Recepción Masiva Facturas** 

Está compuesta por una serie de Servicios Web habilitados para verificar el estado de las facturas enviadas al SIN en forma masiva bajo la modalidad Electrónica o Computarizada en Línea, los mismos se hallan publicados de forma diferenciada por tipo de documentos sector. 

Dichos servicios previa validación de los parámetros enviados, verifican el estado en el cual se encuentra las facturas recibidas, si todas pasaron las validaciones y no se encontraron errores se devuelve un código de aceptación, caso contrario se devuelve otro de observación junto a una lista con el detalle de aquellas facturas con problemas y los errores o advertencias detectadas en cada uno de ellas. 

El servicio implementado posee un objeto denominado SolicitudServicioValidacionRecepcionMasiva el cual contiene la información descrita en el siguiente cuadro: 

## **Nombre Método:** validacionRecepcionMasivaFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo de|**codigoEsta**|Numérico|
|**e**||||ambiente<br>utilizado,|**do**||
|||||los<br>valores|||
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas y Piloto:|||
|||||2|||
|**codigoPuntoVe**|Numérico||No|Solo<br>se<br>envía||Alfanumérico|
|**nta**||||cuando<br>la|**codigoDesc**||
|||||transacción<br>se<br>realiza utilizando un|**ripcion**||
|||||punto de venta. Caso|||
|||||contrario enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema||Alfanumérico|
|||||que le fue asignado<br>al<br>momento<br>de|**codigoRec**||
|||||realizar la solicitud|**epcion**||
|||||de autorización.|||
|**codigoSucursal**|Numérico||Si|Valor que identifica a||Boolean|
|||||la sucursal donde se|**transaccio**||
|||||realiza la emisión de|||
|||||la Factura:|**n**||
|||||Casa Matriz: 0|||
|||||Sucursal: 1,2,..,n|||
|**nit**|Numérico||Si|NIT perteneciente al|**codigosRes**|DTO[codigos|
|||||emisor de la Factura.|**puestas**|Respuesta]|
|**codigoDocume**|Numérico||Si|Código que identifica|||
|**ntoSector**||||el<br>sector<br>de<br>la|||
|||||Factura.|||
|**codigoEmision**|Numérico||Si|El<br>valor permitido|||
|||||es:|||
|||||Masiva: 3|||
|**codigoModalid**|Numérico||Si|Uno (1) Electrónica|||
|**ad**||||y<br>dos<br>(2)|||
|||||Computarizada<br>en|||
|||||línea|||
|**cufd**|Alfanumérico||Si|Valor diario otorgado|||
|||||por el SIN.|||



|**cuis**|Alfanumérico|Si|Valor único para una|
|---|---|---|---|
||||sucursal y/o punto de|
||||venta que se obtiene|
||||al realizar el inicio de|
||||uso de sistemas.|
|**tipoFacturaDoc**|Numérico|Si|Código que identifica|
|**umento**|||el Tipo de Factura  o|
||||Documento<br>de|
||||Ajuste que se está|
||||enviando.|
|**codigoRecepci**|Alfanumérico|Si|Código<br>Recepción|
|**on**|||enviado por el SIN.|



## **Proceso de Validación de Factura Computarizada Masiva** 

## **Verificación Estado de Factura** 

Este servicio está habilitado para verificar el estado de las Facturas o Notas Crédito - Débito emitidas bajo la modalidad Computarizada en Línea y que fueron enviadas al SIN, cuando se desea saber el estado de la Factura o Nota de Crédito - Débito, podrá realizarse a través del Código Único de Factura (CUF). 

Este servicio, previa validación de los parámetros enviados, verifica el estado en el cual se encuentra la Factura o Nota Crédito - Débito. Si la misma paso todas las validaciones y no se encontraron errores, se devuelve un código de aceptación caso contrario se devuelve otro de observación junto a una lista con el detalle de los mismos. 

El servicio implementado posee un objeto denominado SolicitudServicioVerificaEstadoFactura el cual contiene la información descrita en el siguiente cuadro: 

## **Nombre Método:** verificacionEstadoFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**||**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|---|
||**Dato**|||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo|de|**codigoEstad**|Numérico|
|**e**||||ambiente<br>utilizado,||**o**||
|||||los<br>valores||||
|||||permitidos son:||||
|||||Producción: 1||||
|||||Pruebas y||||
|||||Piloto: 2||||
|**codigoPuntoVe**|Numérico||No|Solo<br>se<br>envía||**codigoRece**|Alfanumérico|
|**nta**||||cuando|la|**pcion**||
|||||transacción|se|||
|||||realiza utilizando|un|||
|||||punto<br>de<br>venta.||||
|||||Caso<br>contrario||||
|||||enviar 0.||||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema||||
|||||que le fue asignado||**codigosRes**|DTO[codigos|
|||||al<br>momento<br>de<br>realizar la solicitud||**puestas**|Respuesta]|
|||||de autorización||||
|**codigoSucursal**|Numérico||Si|Valor que identifica a||**transaccion**|Boolean|
|||||la sucursal donde|se|||
|||||realiza la emisión|de|||
|||||la Factura:||||
|||||Casa Matriz: 0||||
|||||Sucursal:||||
|||||1,2,..,n||||
|**nit**|Numérico||Si|NIT perteneciente|al|||
|||||emisor<br>de|la|||
|||||Factura.||||
|**codigoDocume**|Numérico||Si|Código que identifica||||
|**ntoSector**||||el sector de la||||
|||||Factura.||||
|**codigoEmision**|Numérico||Si|Describe si la||||
|||||emisión se realizó||||
|||||en línea. El valor||||
|||||permitido es:||||
|||||Online: 1||||
|**codigoModalid**|Numérico||Si|dos(2)||||
|**ad**||||Computarizada en||||
|||||Línea||||



|**cufd**|Alfanumérico|Si|Valor diario otorgado|Valor diario otorgado|Valor diario otorgado|
|---|---|---|---|---|---|
||||por el SIN.|||
|**cuis**|Alfanumérico|Si|Valor único para una|Valor único para una|Valor único para una|
||||sucursal y/o punto|||
||||de venta que se|de venta que se|de venta que se|
||||obtiene al realizar el|||
||||inicio<br>de|uso de|uso de|
||||sistemas.|||
|**tipoFacturaDoc**|Numérico|Si|Código que identifica|Código que identifica||
|**umento**|||el Tipo de Factura o|||
||||Documento|Documento|de|
||||Ajuste que se está|Ajuste que se está|Ajuste que se está|
||||enviando.|||
|**cuf**|Alfanumérico|Si|Código<br>Único||de|
||||Factura|a|ser|
||||validado.|||



**Proceso de Verificación de Estado de Factura o Nota Crédito - Débito Electrónica por CUF** 

## **Recepción Anexos** 

Este servicio está habilitado para el envío de los números de Serie e Imei realizados en una compra. El servicio implementado posee un objeto denominado SolicitudRecepcionAnexos el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre Método:**|RecepcionFactura|RecepcionFactura|||||
|---|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**||**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo|codigoEstad|Numérico|
|**e**||||de<br>ambiente|o||
|||||utilizado,<br>los|||
|||||valores|||
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas y|||
|||||Piloto: 2|||
|**codigoDocume**|Numérico||Si|Código<br>que|codigosRes|DTO[codigosRes|
|**ntoSector**||||identifica el sector<br>de la Factura.|puestas|puesta]|
|**codigoEmision**|Numérico||Si|Describe<br>si la|transaccion|Boolean|
|||||emisión<br>se|||
|||||realizó en línea.|||
|||||El<br>valor|||
|||||permitido es:|||
|||||Online: 1|||
|**codigoModalida**|Numérico||Si|Electrónica<br>en|||
|**d**||||línea: 1|||
|**codigoPuntoVe**|Numérico||No|Solo<br>se<br>envía|||
|**nta**||||cuando<br>la|||
|||||transacción<br>se|||
|||||realiza utilizando|||
|||||un<br>punto<br>de|||
|||||venta.<br>Caso|||
|||||contrario enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código<br>de|||
|||||Sistema que le|||
|||||fue asignado al|||
|||||momento<br>de|||
|||||realizar<br>la|||
|||||solicitud<br>de|||
|||||autorización.|||
|**codigoSucursal**|Numérico||Si|Valor<br>que|||
|||||identifica<br>a<br>la|||
|||||sucursal<br>donde|||
|||||se<br>realiza<br>la|||
|||||emisión<br>de<br>la|||
|||||Factura:|||
|||||Casa Matriz: 0|||



|||||
|---|---|---|---|
||||Sucursal:|
||||1,2,..,n|
|**cufd**|Alfanumérico||Valor<br>diario|
||||otorgado por el|
||||SIN|
|cuis|Alfanumérico|Si|Valor único para|
||||una sucursal y/o|
||||punto de venta|
||||que se obtiene al|
||||realizar el inicio|
||||de<br>uso<br>de|
||||sistemas.|
|**nit**|Numérico|Si|NIT perteneciente|
||||al emisor de la|
||||Factura.|
|**tipoFacturaDoc**|Numérico|Si|Código<br>que|
|**umento**|||identifica el sector|
||||de la Factura.|
|**codigo**|Alfanumérico|Si||
||||Numero de Serie|
||||o Emei|
|**codigoProducto**|Alfanumérico|Si|Código de|
||||producto|
||||Contribuyente|
|codigoProductoS|Numérico|Si|Código<br>de<br> <br>|
|in|||Producto SIN.|
|**tipoCodigo**|Numérico|Si|Identifica<br>si<br>se<br> <br>|
||||trata de un Nro de|
||||Serie o de un|
||||Emei|
||||Nro. Serie = 1|
||||Emei = 2|
|cuf|Numérico|Si|Código<br>que<br>|
||||identifica el Tipo|
||||de Factura que se|
||||está enviando.|
|||||



## **Reversión Anulación Factura Compra Venta** 

De acuerdo a RND Nº 102300000034 que indica “Asimismo, en caso de darse la anulación errónea de Documentos Fiscales, el Sujeto Pasivo del IVA a traves de su Sistema Informático de Facturación o la opción habilitada en la modalidad Portal Web en Línea, según corresponda de acuerdo a la modalidad de facturacion utilizada, podrá revertir por única vez la anulación y cambiar el estado de un Documento Fiscal a “VALIDO” hasta la fecha señalada en el párrafo precedente. Los Documentos Fiscales revertidos no podrán ser anulados”. Este servicio permite revertir el estado de las facturas digitales que fueron anuladas por error y por una sola vez. 

El servicio implementado posee un objeto denominado SolicitudServicioReversionAnulacionFactura el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método:** ReversionAnulacionFactura 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|
||**Dato**||||||
|**codigoAmbien**|Numérico||Si|Describe el tipo de|**codigoEstad**|Numérico|
|**te**||||ambiente utilizado,|**o**||
|||||los<br>valores|||
|||||permitidos son:|||
|||||Producción: 1|||
|||||Pruebas y|||
|||||Piloto: 2|||
|**codigoPuntoV**|Numérico||No|Solo<br>se<br>envía||DTO|
|**enta**||||cuando<br>la|**codigosRes**|[codigosRespu|
|||||transacción<br>se<br>realiza<br>utilizando|**puesta**|esta]|
|||||un punto de venta.|||
|||||Caso<br>contrario|||
|||||enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema|**transaccion**|Boolean|
|||||que<br>le<br>fue|||
|||||asignado<br>al|||
|||||momento<br>de|||
|||||realizar la solicitud|||
|||||de autorización.|||
|**codigoSucurs**|Numérico||Si|Valor que identifica||Alfanumérico|
|**al**||||a<br>la<br>sucursal|**codigoDescr**||
|||||donde se realiza la|**ipcion**||
|||||emisión<br>de<br>la|||
|||||Factura:|||
|||||Casa Matriz:|||
|||||0|||
|||||Sucursal:|||
|||||1,2,...,n|||



|**nit**|Numérico|Si|NIT perteneciente|
|---|---|---|---|
||||al emisor de la|
||||Factura.|
|**codigoDocum**|Numérico|Si|Código<br>que<br> <br>|
|**entoSector**|||identifica el sector|
||||de la Factura.|
|**codigoEmisio**|Numérico|Si|Describe<br>si<br>la<br> <br>|
|**n**|||emisión se realizó|
||||en línea. El valor|
||||permitido es:|
||||Online: 1|
|**codigoModalid**|Numérico|Si|Computarizada en|
|**ad**|||línea: 2|
|**cufd**|Alfanumérico|Si|Valor<br>diario<br> <br>|
||||otorgado<br>por<br>el|
||||SIN.|
|**cuis**|Alfanumérico|Si|Valor único para|
||||una sucursal y/o|
||||punto<br>de<br>venta|
||||que se obtiene al|
||||realizar el inicio de|
||||uso de sistemas.|
|**tipoFacturaDo**|Numérico|Si|Código<br>que<br> <br>|
|**cumento**|||identifica el Tipo|
||||de<br>Factura<br>o|
||||Documento<br>de|
||||Ajuste que se está|
||||revirtiendo.|
|**cuf**|Alfanumérico|Si|Código único de<br> <br>|
||||factura que está|
||||siendo revertida.|



**Nota:** Para poder utilizar este servicio, todos los sistemas autorizados deberán completar las pruebas del mismo en ambiente piloto. Superadas las mismas y al presionar el botón de finalizar pruebas serán habilitados automáticamente para usar el servicio en producción. 

Los sistemas en las etapas iniciales o en proceso de autorización deberán completar este set de pruebas adicionalmente. 

Los sistemas que se hallen ya en proceso de inspección, deberán terminar el proceso de forma normal y cuando el sistema este en producción solicitar el nuevo servicio via correo a soporte.aplicaciones@impuestos.gob.bo. 

## **DOCUMENTOS DE AJUSTE** 

## **Recepción de Documentos de Ajuste** 

Este servicio permite recibir de manera individual los Documentos de Ajuste que incluyen las Notas Crédito - Débito y las Notas de Conciliación de las modalidades Electrónicas y Computarizada en Línea, de manera que verifica y registra devolviendo un código de éxito cuando la validación es correcta, de lo contrario se emite los códigos de errores y advertencias cuando existe incoherencia en los datos de entrada o del archivo recibido. 

El servicio implementado posee un objeto denominado SolicitudServicioRecepcionDocumentoAjuste el cual contiene la información descrita en el siguiente cuadro: 

|**Nombre Método;**|recepcionDocumentoAjuste|recepcionDocumentoAjuste|recepcionDocumentoAjuste|recepcionDocumentoAjuste|||
|---|---|---|---|---|---|---|
|**Entrada**|**Tipo**|**Obligatorio**||**Descripción**|**Salida**|**Tipo Dato**|
||**Dato**||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo de|**codigoEs**|Numérico|
|**e**||||ambiente utilizado, los|**tado**||
|||||valores permitidos son:|||
|||||Producción: 1|||
|||||PruebasyPiloto: 2|||
|**codigoPuntoVe**|Numérico||No|Solo se envía cuando|**codigoRe**|Alfanumérico|
|**nta**||||la<br>transacción<br>se|**cepcion**||
|||||realiza<br>utilizando un|||
|||||punto de venta. Caso|||
|||||contrario enviar 0.|||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema que|**todigosR**|DTO[codigosRe|
|||||le<br>fue<br>asignado<br>al|**espuesta**|spuesta]|
|||||momento de realizar la|**s**||
|||||solicitud<br>de|||
|||||autorización.|||
|**codigoSucursal**|Numérico||Si|Valor que identifica a la||Boolean|
|||||sucursal<br>donde<br>se|**transacci**||
|||||realiza la emisión de la|||
|||||Factura:|**on**||
|||||Casa Matriz: 0|||
|||||Sucursal: 1,2,..,n|||
|**nit**|Numérico||Si|NIT perteneciente al|||
|||||emisor de la Factura.|||
|**codigoDocume**|Numérico||Si|Código que identifica el|||
|**ntoSector**||||sector de la Factura.|||
|**codigoEmision**|Numérico||Si|Describe si la emisión|||
|||||se realizó en línea. El|||
|||||valor permitido es:|||
|||||Online: 1|||
|**codigoModalid**|Numérico||Si|Uno (1) Electrónica en|||
|**ad**||||Linea 0 dos (2) para|||
|||||Computarizada en|||
|||||línea|||



|**cufd**|Alfanumérico|Si|Valor diario otorgado<br> <br>|
|---|---|---|---|
||||por el SIN.|
|**cuis**|Alfanumérico|Si|Valor único para una<br> <br>|
||||sucursal y/o punto de|
||||venta que se obtiene al|
||||realizar el inicio de uso|
||||de sistemas.|
|**tipoFacturaDoc**|Numérico|Si|Código que identifica el<br> <br>|
|**umento**|||Tipo de Factura que se|
||||está enviando.|
|**archivo**|Alfanumérico|Si|Factura<br>que<br>es<br> <br>|
||||enviada<br>para<br>su|
||||validación.|
|**fechaEnvio**|TimeStamp|Si|Fecha y hora en la cual<br> <br>|
||||se envía la Factura.|
|**hashArchivo**|Alfanumérico|Si|Sha256 de la cadena|
||||Archivo que se envía.|



## **Anulación de Documento de Ajuste** 

Este servicio permite recibir solicitudes de anulación de Documentos de Ajuste (Notas Crédito - Débito y Notas de Conciliación) de las modalidades Electrónica en Línea y Computarizada en Línea, verifica los datos enviados y registra la solicitud devolviendo un código de recepción cuando la validación es correcta, de lo contrario se emite códigos de error y advertencia cuando existe incoherencia en los datos de entrada. 

El servicio implementado posee un objeto denominado SolicitudServicioAnulacionDocumentoAjuste el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método:** anulacionDocumentoAjuste 

|**Entrada**|**Tipo**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|
||**Dato**|||||
|**codigoAmbiente**|Numérico|Si|Describe el tipo de|**codigosRes**|DTO|
||||ambiente<br>utilizado,|**puesta**|[codigosR|
||||los<br>valores||espuesta]|
||||permitidos son:|||
||||Producción: 1|||
||||PruebasyPiloto: 2|||



|||||||||
|---|---|---|---|---|---|---|---|
||**codigoPuntoVen**|Numérico|No|Solo<br>se<br>envía||Numérico||
||**ta**|||cuando<br>la|**codigoEstad**|||
|||||transacción<br>se||||
|||||realiza utilizando un|**o**|||
|||||punto<br>de<br>venta.||||
|||||Caso<br>contrario||||
|||||enviar 0.||||
||**codigoSistema**|Alfanumérico|Si|Código de Sistema|**transaccion**|Boolean||
|||||que le fue asignado||||
|||||al<br>momento<br>de||||
|||||realizar la solicitud||||
|||||de autorización.||||
||**codigoSucursal**|Numérico|Si|Valor que identifica a||||
|||||la sucursal donde se||||
|||||realiza la emisión de||||
|||||la Factura:||||
|||||Casa Matriz: 0||||
|||||Sucursal:1,2,...,n||||
||**nit**|Numérico|Si|NIT perteneciente al||||
|||||emisor de la Factura.||||
||**codigoDocumen**|Numérico|Si|Código que identifica||||
||**toSector**|||el<br>sector<br>de<br>la||||
|||||Factura.||||
||**codigoEmision**|Numérico|Si|Describe<br>si<br>la||||
|||||emisión se realizó en||||
|||||línea.<br>El<br>valor||||
|||||permitido es:||||
|||||Online: 1||||
||**codigoModalida**|Numérico|Si|Uno (1) Electrónica||||
||**d**|||en Linea 0 dos (2)||||
|||||para Computarizada||||
|||||en línea||||
||**cufd**|Alfanumérico|Si|Valor diario otorgado||||
|||||por el SIN.||||
||**cuis**|Alfanumérico|Si|Valor único para una||||
|||||sucursal y/o punto||||
|||||de<br>venta que se||||
|||||obtiene al realizar el||||
|||||inicio<br>de<br>uso<br>de||||
|||||sistemas.||||
|||||||||



|**tipoFacturaDocu**|Numérico|Si|Código que identifica|Código que identifica|
|---|---|---|---|---|
|**mento**|||el Tipo de Factura o||
||||Documento|de|
||||Ajuste que se está|Ajuste que se está|
||||enviando.||
|**codigoMotivo**|Numérico|Si|Paramétrica|que|
||||indica el motivo por|indica el motivo por|
||||el cual la Factura||
||||está siendo anulada.||
|**cuf**|Alfanumérico|Si|Código<br>único|de|
||||factura<br>que|está|
||||siendo anulado.||



## **Proceso de Recepción de Anulación de Notas Crédito - Débito Computarizada** 

## **Verificación Estado de los Documentos de Ajuste** 

Este servicio está habilitado para verificar el estado de los Documentos de Ajuste emitidos bajo las modalidades Electrónica  y Computarizada en Línea y que fueron enviadas al SIN. 

Este servicio, previa validación de los parámetros enviados, verifica el estado en el cual se encuentra el Documento de Ajuste. Si paso todas las validaciones y no se encontraron errores, se devuelve un código de aceptación caso contrario se devuelve otro de observación junto a una lista con el detalle de los mismos. 

El servicio implementado posee un objeto denominado SolicitudServicioVerificacionEstadoDocumentoAjuste el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método:** verificacionEstadoDocumentoAjuste 

|**Entrada**|**Tipo**|**Obligatorio**|**Obligatorio**|**Descripción**||**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|---|---|
||**Dato**|||||||
|**codigoAmbient**|Numérico||Si|Describe el tipo|de|**codigoEstad**|Numérico|
|**e**||||ambiente<br>utilizado,||**o**||
|||||los<br>valores||||
|||||permitidos son:||||
|||||Producción: 1||||
|||||Pruebas y||||
|||||Piloto: 2||||
|**codigoPuntoVe**|Numérico||No|Solo<br>se<br>envía||**codigoRece**|Alfanumérico|
|**nta**||||cuando|la|**pcion**||
|||||transacción|se|||
|||||realiza utilizando|un|||
|||||punto<br>de<br>venta.||||
|||||Caso<br>contrario||||
|||||enviar 0.||||
|**codigoSistema**|Alfanumérico||Si|Código de Sistema||||
|||||que le fue asignado||**codigosRes**|DTO[codigos|
|||||al<br>momento<br>de<br>realizar la solicitud||**puestas**|Respuesta]|
|||||de autorización||||
|**codigoSucursal**|Numérico||Si|Valor que identifica a||**transaccion**|Boolean|
|||||la sucursal donde|se|||
|||||realiza la emisión|de|||
|||||la Factura:||||
|||||Casa Matriz: 0||||
|||||Sucursal:||||
|||||1,2,..,n||||
|**nit**|Numérico||Si|NIT perteneciente|al|||
|||||emisor<br>de|la|||
|||||Factura.||||
|**codigoDocume**|Numérico||Si|Código que identifica||||
|**ntoSector**||||el sector de la||||
|||||Factura.||||
|**codigoEmision**|Numérico||Si|Describe si la||||
|||||emisión se realizó||||
|||||en línea. El valor||||
|||||permitido es:||||
|||||Online: 1||||
|**codigoModalid**|Numérico||Si|Computarizada en||||
|**ad**||||línea: 2||||
|**cufd**|Alfanumérico||Si|Valor diario otorgado||||
|||||por el SIN.||||
|**cuis**|Alfanumérico||Si|Valor único para una||||
|||||sucursal y/o punto||||
|||||de venta que|se|||



||||obtiene al realizar el|obtiene al realizar el|obtiene al realizar el|
|---|---|---|---|---|---|
||||inicio<br>de|uso de|uso de|
||||sistemas.|||
|**tipoFacturaDoc**|Numérico|Si|Código que identifica|Código que identifica||
|**umento**|||el Tipo de Factura o|||
||||Documento|Documento|de|
||||Ajuste que se está|Ajuste que se está|Ajuste que se está|
||||enviando.|||
|**cuf**|Alfanumérico|Si|Código<br>Único||de|
||||Factura|a|ser|
||||validado.|||



**Proceso de Verificación de Estado de Factura o Nota Crédito - Débito Electrónica por CUF** 

## **Verifica Comunicación** 

Este servicio recibe la solicitud de verificación de comunicación, registra la misma y devuelve un código de comunicación exitosa. 

## **Nombre Método:** verificarComunicacion 

|**Entrada**|**Tipo**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|
||**Dato**|||||
|**ninguna**|ninguno|No|ninguna|**return =**|Numérico|
|||corresp||**926**||
|||onde||**(comuni**||
|||||**cación**||
|||||**exitosa)**||



## **Proceso de Solicitud de Verificación de Comunicación** 

## **Reversión de Anulación Documento Ajuste** 

De acuerdo a RND Nº 102300000034 que indica “Asimismo, en caso de darse la anulación errónea de Documentos Fiscales, el Sujeto Pasivo del IVA a traves de su Sistema Informático de Facturación o la opción habilitada en la modalidad Portal Web en Línea, según corresponda de acuerdo a la modalidad de facturacion utilizada, podrá revertir por única vez la anulación y cambiar el estado de un Documento Fiscal a “VALIDO” hasta la fecha señalada en el párrafo precedente. Los Documentos Fiscales revertidos no podrán ser anulados”. Este servicio permite revertir el estado de los documentos fiscales que fueron anuladas por error y por una sola vez. 

El servicio implementado posee un objeto denominado SolicitudServicioReversionAnulacionDocumentoAjuste el cual contiene la información descrita en el siguiente cuadro: 

**Nombre Método:** reversionAnulacionDocumentoAjuste 

|**Entrada**|**Tipo**|**Obligatorio**|**Descripción**|**Salida**|**Tipo Dato**|
|---|---|---|---|---|---|
||**Dato**|||||
|**codigoAmbien**|Numérico|Si|Describe el tipo de|**codigoEsta**|Numérico|
|**te**|||ambiente utilizado,|**do**||
||||los<br>valores|||
||||permitidos son:|||
||||Producción: 1|||
||||Pruebas y Piloto:|||
||||2|||
|**codigoPuntoV**|Numérico|No|Solo<br>se<br>envía||DTO|
|**enta**|||cuando<br>la|**codigosRe**|[codigosRespu|
||||transacción<br>se<br>realiza utilizando un|**spuesta**|esta]|
||||punto<br>de<br>venta.|||
||||Caso<br>contrario|||
||||enviar 0.|||



|||||||
|---|---|---|---|---|---|
|**codigoSistema**|Alfanumérico|Si|Código de Sistema|**transaccio**|Boolean|
||||que le fue asignado|**n**||
||||al<br>momento<br>de|||
||||realizar la solicitud|||
||||de autorización.|||
|**codigoSucurs**|Numérico|Si|Valor que identifica||Alfanumérico|
|**al**|||a la sucursal donde|**descripcion**||
||||se realiza la emisión|||
||||de la Factura:|||
||||Casa Matriz: 0|||
||||Sucursal:|||
||||1,2,...,n|||
|**nit**|Numérico|Si|NIT perteneciente al|||
||||emisor<br>de<br>la|||
||||Factura.|||
|**codigoDocum**|Numérico|Si|Código<br>que|||
|**entoSector**|||identifica el sector|||
||||de la Factura.|||
|**codigoEmision**|Numérico|Si|Describe<br>si<br>la|||
||||emisión se realizó|||
||||en línea. El valor|||
||||permitido es:|||
||||Online: 1|||
|**codigoModalid**|Numérico|Si|Computarizada<br>en|||
|**ad**|||línea: 2|||
|**cufd**|Alfanumérico|Si|Valor<br>diario|||
||||otorgado por el SIN.|||
|**cuis**|Alfanumérico|Si|Valor<br>único<br>para|||
||||una<br>sucursal<br>y/o|||
||||punto de venta que|||
||||se<br>obtiene<br>al|||
||||realizar el inicio de|||
||||uso de sistemas.|||
|**tipoFacturaDo**|Numérico|Si|Código<br>que|||
|**cumento**|||identifica el Tipo de|||
||||Factura<br>o|||
||||Documento<br>de|||
|||||||



||||Ajuste|que se|está|
|---|---|---|---|---|---|
||||revirtiendo.|||
|**cuf**|Alfanumérico|Si|Código|<br>único|<br>de<br> <br>|
||||factura|<br>que|está|
||||siendo|revertida.||



**Nota:** Todos los sistemas ya autorizados que deseen utilizar este servicio deberán completar las pruebas para el mismo en ambiente piloto. Superadas las mismas y al presionar el botón de finalizar pruebas serán habilitados automáticamente para usar el servicio en producción. 

Los sistemas en las etapas iniciales o en proceso de autorización deberán completar este set de pruebas obligatoriamente. 

Los sistemas que se hallen ya en proceso de inspección, deberán terminar el proceso de forma normal y cuando el sistema este en producción solicitar el nuevo servicio via correo a soporte.aplicaciones@impuestos.gob.bo. 

## **CODIGOS ERROR SIAT** 

Algunos de los codigos de error utilizados por el SIAT son: 

CÓDIGO DESCRIPCIÓN 

- 123 Código Único De Facturación Diaria (Cufd) Fuera De Tolerancia 

- 901 Recepción Pendiente 

- 902 Recepción Rechazada 

- 903 Recepción Procesada 

- 904 Recepción Observada 

- 905 Anulación Confirmada 

- 906 Anulación Rechazada 

- 907 Reversión De Anulación Confirmada 908 Recepción Validada 

- 909 Reversión De Anulación Rechazada 910 El Parámetro Ambiente Es Invalido 911 El Parámetro Código De Sistema Es Invalido 912 El Sistema No Esta Asociado Al Contribuyente 913 Código Único De Inicio De Sistema (Cuis) Invalido 

|||||
|---|---|---|---|
||914|Código Único De Facturación Diaria (Cufd) Invalido||
||915|El Parámetro Tipo Factura Documento Es Invalido||
||916|El Parámetro Tipo De Emisión Es Invalido||
||917|El Parámetro Modalidad Es Invalido||
||918|El Parámetro Sucursal Es Invalido||
||919|El Parámetro NIT Es Invalido||
||920|El Parámetro Archivo Es Invalido||
||921|El Firmado Del XML Es Incorrecto||
||922|La Firma Del XML No Corresponde Al Contribuyente||
||923|El Parámetro Código De Recepción Es Invalido||
||924|La Factura o Nota, No Existe En La Base De Datos Del Sin||
||925|El Parámetro Motivo De Anulación Es Invalido||
||926|Comunicación Exitosa||
||927|El Certificado De La Firma Es Invalido||
||928|El Certificado Se Encuentra Revocado||
||929|El Código Único De Inicio De Sistema (Cuis) No Esta Vigente||
||930|El Código Único De Inicio De Sistema (Cuis) No Corresponde A La Sucursal/Punto||
|||Venta||
||931|El Parámetro Código Documento Sector Es Invalido||
||932|El Parámetro Código Documento Sector No Corresponde Al Servicio||
||933|El Punto De Venta Es Inexistente o Invalido||
||934|La Solicitud De Anulación De La Factura o Nota De Crédito-Débito Se Encuentra Fuera||
|||De Plazo||
||935|El Parámetro Fecha De Envío Es Invalido||
||936|La Factura o Nota De Crédito-Débito Ya Se Encuentra Anulada||
||937|El NIT No Tiene Asociado La Modalidad De Facturación||
||938|El NIT Presenta Marcas De Control||
|||||



|||||
|---|---|---|---|
||939|La Factura o Nota De Crédito - Débito No Cumple Con El Formato Del Xsd||
|||Especificado||
||940|El NIT No Tiene Habilitado El Documento Sector||
||941|La Factura o Nota De Crédito - Débito No Se Encuentra Disponible Para Ser Anulada||
||942|El Código De Recepción De Evento Significativo No Se Encuentra En La Base De||
|||Datos Del Sin||
||943|El Formato De La Fecha De Envío Es Incorrecto||
||944|El Código De Recepción No Se Encuentra En La Base De Datos Del Sin||
||945|El Estado De Recepción De La Anulación Es Incorrecta||
||946|El Código Único De Factura (Cuf) No Existe En Base De Datos Del Sin||
||947|El Parámetro Tipo De Punto De Venta Es Invalido||
||948|El Parámetro Nombre De Punto De Venta No Puede Ser Vacío||
||949|El Parámetro Descripción De Punto De Venta No Puede Ser Vacío||
||950|El Parámetro Código De Evento Significativo No Puede Ser Vacío||
||951|El Parámetro Descripción De Evento Significativo No Puede Ser Vacío||
||952|El Código Único De Factura (Cuf) Ya Se Encuentra Registrado En La Base De Datos||
|||Del Sin||
||953|El Código Único De Facturación Diaria (Cufd) No Se Encuentra Vigente||
||954|La Cantidad De Facturas En El Paquete Emitido Por Contingencia Ha Excedido El||
|||Máximo Permitido||
||955|No Existe Registro Para Autorizar El Proceso Masivo||
||956|La Cantidad De Facturas En El Paquete Emitido Masivamente Ha Excedido El Máximo||
|||Permitido||
||957|No Existe Registro De Evento Significativo En La Base De Datos Del Sin||
||958|El Usuario No Se Encuentra Autorizado Para Consumir Este Servicio||
||959|El Código Único De Inicio De Sistema (Cuis) No Se Encuentra Asociado Al Sistema||
||960|El Parámetro Fin De Evento Es Requerido||
||961|El NIT Tiene Marca De Domicilio Inexistente||
||962|El NIT Tiene Bloqueo De Dosificación Originado En Fiscalización||
|||||



|||||
|---|---|---|---|
||963|El NIT Tiene Bloqueo De Dosificación Originado En Jurídica||
||964|El NIT No Cumple Con Obligatoriedad De Presentación De DDJJ||
||965|El Contribuyente No Cuenta Con Firma Vigente Registrada||
||966|No Se Puede Recuperar Los Datos Del Contribuyente||
||967|Tiempo De Espera Agotado Para Conexión A Base De Datos||
||968|La Anulación De La Factura o Nota De Crédito - Débito Ya Se Encuentra Revertida||
||969|El Parámetro Hash Es Invalido||
||970|El Cuis En La Base De Datos Se Encuentra Vigente, No Puede Solicitar Otro||
||971|El Tamaño Del Archivo Excede El Tamaño Permitido De 100 Mb||
||972|La Cantidad De Facturas Enviada En El Paquete Es Mayor A La Definida En La||
|||Normativa||
||973|El Código Único De Inicio De Sistema (Cuis) No Se Encuentra Vigente||
||974|El Rango De Fechas Del Evento Significativo Para Registrar Es Inválido||
||975|El Sistema No Se Encuentra Autorizado O Se Encuentra Observado||
||976|El Código Del Evento Es Incorrecto||
||977|No Existen Actividades Asociadas Al NIT||
||978|Reversión De La Factura o Nota De Crédito/Débito Confirmada||
||979|El Cuis No Se Encuentra Asociado Al Sistema O A La Sucursal||
||980|Existe Un Cuis Vigente Para La Sucursal O Punto De Venta||
||981|Rango De Fechas De Evento Significativo Invalido||
||982|No Existe Puntos De Venta Asociados||
||983|La Fecha De Envío Del Paquete Esta Fuera De Plazo||
||984|El Evento Significativo No Corresponde Al Cufd Del Evento Registrado||
||985|La Cantidad De Facturas Es Diferente A La Declarada||
||986|NIT Activo||
||987|NIT Inactivo||
||988|Código Único De Inicio De Sistema (Cuis) Fuera De Tolerancia||
||989|Token Invalido||
|||||



|||||
|---|---|---|---|
||990|El Cliente No Tiene Actividades Relacionadas Al Sector Que Intenta Asociar||
||991|Error En Base De Datos||
||992|Error Servicio Padrón||
||993|La Fecha De Envío Esta Fuera De Plazo||
||994|NIT Inexistente||
||995|Servicio No Disponible||
||996|Rango De Fechas Invalido||
||997|El Nombre Excede El Limite De Caracteres Permitidos||
||998|La Descripción Excede El Limite De Caracteres Permitidos||
||999|Error En La Ejecución Del Servicio||
||1000|El Cuf Enviado Ya Existe En La Base De Datos Del Sin||
||1001|El NIT Enviado En El XML Es Inexistente O No Corresponde Al Cufd||
||1002|El Código Único De Factura (Cuf) Enviado En El XML Es Invalido||
||1003|El Código Único De Facturación Diaria (Cufd) Enviado En El XML Es Invalido||
||1004|La Sucursal Enviada En El XML No Corresponde A Los Datos Del Cufd||
||1005|La Factura o Nota De Crédito-Débito No Puede Ser Emitida Al Mismo Emisor||
||1006|El Cufd Enviado No Corresponde Al Evento Asociado Al Paquete Enviado||
||1007|La Dirección Enviada En El XML No Corresponde A La Registrada En Padrón||
||1008|El Punto De Venta Enviado En El XML Es Inexistente O Invalido||
||1009|La Fecha De Emisión Enviada En El XML No Es Valida Para Emisión En Linea||
||1010|La Factura No Puede Ser Enviada Con Numero De CI/NIT/CEX o Para Montos||
|||Mayores A 3000||
||1011|El Complemento Solo Puede Ser Enviado Cuando El Tipo De Documento Es Carnet De||
|||Identidad||
||1012|El Numero De Tarjeta Solo Puede Ser Enviado Cuando El Método De Pago Sea Con||
|||Tarjeta||
||1013|El Calculo Del Monto Total Es Erróneo||
||1014|El Calculo Del Monto Total Moneda Es Erróneo||
|||||



|||||
|---|---|---|---|
||1015|El Calculo Del Importe Base Para Crédito Fiscal Es Erróneo||
||1016|El Código De Actividad Económica No Esta Habilitada Para El Contribuyente||
||1017|El Código De Producto No Esta Relacionado A Ninguna Actividad Económica Del||
|||Contribuyente||
||1018|El Calculo Del Subtotal Es Erróneo||
||1019|El Calculo De Ice Especifico Es Erróneo||
||1020|El Calculo De Ice Porcentual Es Erróneo||
||1021|El Monto Ice Especifico Es Erróneo||
||1022|El Monto Ice Porcentual Es Erróneo||
||1023|El Código Nandina Enviado En La Factura Es Erróneo||
||1024|La Sumatoria De Lo Detalles Es Errónea||
||1025|El Monto Sujeto A Crédito Fiscal Ley 317 Es Erróneo||
||1026|El Monto Total Sujeto Al Impuesto Del Juego (IJ) Es Erróneo||
||1027|El Monto De Diferencia De Cambios Es Erróneo||
||1028|El Monto De IVA Enviado Es Erróneo||
||1029|El Monto Total Devuelto Enviado Es Erróneo||
||1030|El Monto Total Original Enviado Es Erróneo||
||1031|El Monto Efectivo De Crédito O Débito Devuelto Enviado Es Erróneo||
||1032|El Monto Total De Impuesto A La Participación En Juego (IPJ) Es Erróneo||
||1033|El Monto Devuelto Es Mayor Al Monto Original||
||1034|La Fecha De Emisión Es Menor Al Periodo Anterior||
||1035|Formato De Fecha Incorrecta||
||1036|Nominatividad Incorrecta Para Nombre/Razón Social||
||1037|El Numero Documento De Tipo NIT No Es Valido||
||1038|NIT Conjunto No Valido||
||1039|Fecha Emisión Para Envío Masivo Incorrecto||
||1040|Fecha De Emisión No Se Encuentra En El Rango De Contingencia||
||1041|La Fecha De Emisión No Se Encuentra Dentro Del Plazo Establecido En Norma||
|||||



|||||
|---|---|---|---|
||1042|El NIT Del Medico Enviado No Es Valido||
||1043|El Monto Conciliado Enviado Es Erróneo||
||1044|El Monto Total Conciliado Enviado Es Erróneo||
||1045|Valor De Cafc No Valido Para La Factura||
||1046|Fecha Emisión Para El Cafc Enviado Incorrecto||
||1047|Numero Factura Para El Cafc Enviado Incorrecto||
||1048|Factura De La Nota Crédito Débito No Encontrada||
||1049|Detalle De La Nota Diferente Al Detalle De La Factura Original||
||1050|Monto Gift Card No Corresponde Al Método De Pago||
||1051|Fecha De Factura Incorrecta||
||1052|El Calculo Del Monto IEHD Es Erróneo||
||1053|La Actividad De La Nota De Crédito Débito No Se Encuentra Autorizada Para Este||
|||Plazo||
||1054|El Monto Descuento Crédito Débito Es Erróneo||
||1055|El Monto Tarifa Es Erróneo||
||1056|El Tipo De Cambio Es Erróneo||
||1057|El Monto Total Moneda Es Erróneo||
||1058|El Monto Total Sujeto Iva Es Erróneo||
||1059|La Razón Social Es Errónea||
||1060|El Monto Detalle Es Erróneo||
||1061|Factura De La Nota Crédito Débito No Es Valida Para Realizar La Devolución||
||2000|Advertencia: El Numero Factura Enviado Tiene Error De Correlatividad||
||2001|Advertencia: La Fecha De Emisión Enviada No Se Encuentra Dentro Del Rango Del||
|||Evento De Contingencia Asociado||
||2002|Advertencia: La Fecha De Emisión Enviada No Es Valida Para La Emisión Masiva||
||2003|Advertencia: La Factura No Puede Ser Enviada Con Numero De CI/NIT/CEX o Para||
|||Montos Mayores A 3000||
||2004|Advertencia: El Complemento Solo Puede Ser Enviado Cuando El Tipo De Documento||
|||Es Carnet De Identidad||
|||||



|||||
|---|---|---|---|
||2005|Advertencia: El NIT Del Cliente Enviado En El Campo Numero De Documento No Es||
|||Valido||
||2006|Advertencia: El Numero De Tarjeta Solo Puede Ser Enviado Cuando El Método De||
|||Pago Sea Con Tarjeta||
||2007|Advertencia: El Calculo Del Monto Total Es Erróneo||
||2008|Advertencia: El Calculo Del Monto Total Moneda Es Erróneo||
||2009|Advertencia: El Calculo Del Importe Base Para Crédito Fiscal Es Erróneo||
||2010|Advertencia: El Código De Actividad Económica No Esta Habilitada Para El||
|||Contribuyente||
||2011|Advertencia: El Código De Producto No Esta Relacionado A Ningún Actividad||
|||Económica Del Contribuyente||
||2012|Advertencia: El Calculo Del Subtotal Es Erróneo||
||2013|Advertencia: La Factura o Nota De Crédito-Débito No Puede Ser Emitida Al Mismo||
|||Emisor||
||2014|Advertencia: La Dirección Enviada En El XML No Corresponde A La Registrada En||
|||Padrón||
||2015|Advertencia: El Calculo De Ice Especifico Es Erróneo||
||2016|Advertencia: El Calculo De Ice Porcentual Es Erróneo||
||2017|Advertencia: El Monto Ice Especifico Es Erróneo||
||2018|Advertencia: El Monto Ice Porcentual Es Erróneo||
||2019|Advertencia: El Código Nandina Enviado En La Factura Es Erróneo||
||3000|El NIT No Tiene Contrato Vigente||
||3001|La Categoría De Contrato No Corresponde Al Sector||
||3002|La Solicitud Excede El Limite De Cufd Masivo Permitido||
||3003|Marca: No Tiene Formularios 200 Y 210 Vigentes||
||3004|Marca: Domicilio Inexistente||
||3005|Marca: Bloqueo De Dosificación Originados En Fiscalización||
||3006|Marca: Bloqueo De Dosificación Originados En Jurídica||
||3007|Marca: No Cumple Con Obligatoriedad De Presentación De DDJJ||
|||||



|||||
|---|---|---|---|
||3008|Advertencia: El Cuis Esta A Punto De Caducar, Genere Un Nuevo Cuis Por Favor||
||3009|El Tamaño Del Archivo Es Mayor A La Definida En Norma||
||3010|La Factura Ya Se Encuentra Utilizada o Consolidada||



## **ARCHIVOS XML/XSD DE FACTURAS ELECTRÓNICAS** 

## **XML y XSD - Factura de Compra y Venta** 

Habilitada para transacciones por bienes o servicios en general, incluyen línea blanca, negra y cualquier actividad que involucre un intercambio de estos. 

||||**Descarga XML/XSD**||
|---|---|---|---|---|
||||**Formato Gráfico**||
|**Nombre Campo**|**Tipo**|**Obligat**|**Descripción**||
||**Dato**|**orio**|||
|||**CABECERA**|||
|**nitEmisor**|Numéric|Si|Número de NIT registrado en el Padrón Nacional||
||o||de Contribuyentes que corresponde a la persona o||
||||empresaque emite la factura.||
|**razonSocialEmisor**|Alfanum|Si|Razón Social o nombre registrado en el Padrón||
||érico||Nacional de Contribuyentes de la persona o||
||||empresaque emite la factura.||
|**municipio**|Alfanum|Si|Nombre del departamento o municipio que se||
||érico||refleja en la Factura.||
|**telefono**|Alfanum|No|Número de teléfono que se refleja en la Factura.||
||érico||||
|**numeroFactura**|Numéric|Si|Numeración propia que se le asigna a la Factura.||
||o||||
|**cuf**|Alfanum|Si|Código único de facturación (CUF) debe ser||
||érico||generado por el emisor siguiendo el algoritmo||
||||indicado.||
|**cufd**|Alfanum|Si|Código único de facturación diario (CUFD), valor||
||érico||único que se obtiene al consumir el servicio web||
||||correspondiente.||
|**codigoSucursal**|Numéric|Si|Código de la sucursal registrada en el Padrón y en||
||o||la cual se está emitiendo la factura.||
|**direccion**|Alfanum|Si|Dirección de la sucursal registrada en el Padrón||
||érico||Nacional de Contribuyentes.||
|**codigoPuntoVenta**|Numéric|No|Código del punto de Venta creado mediante un||
||o||servicio web y en el cual se emite la factura.||
|**fechaEmision**|Fecha|Si|Fecha y hora en la cual se emite la factura.||
||||Expresada<br>en<br>formato<br>UTC Extendido, por||
||||ejemplo: “2020-02-15T08:40:12.215”.||
|**nombreRazonSocial**|Alfanum|No|Razón Social o nombre de la persona u empresa a||
||érico||la cual se emite la factura.||
|**codigoTipoDocumento**|Numéric|Si|Valor de la paramétrica que identifica el Tipo de||
|**Identidad**|o||Documento utilizado para la emisión de la factura.||
||||Puede contener valores del 1 al 5.||



|||||
|---|---|---|---|
|**numeroDocumento**|Alfanum|Si|Número que corresponde al Tipo de Documento|
||érico||Identidad utilizado y al cual se realizará la|
||||facturación.|
|**complemento**|Alfanum|No|Valor que otorga el SEGIP en casos de cédulas de|
||érico||identidad con número duplicado, En otro caso|
||||enviar un valornuloagregando en la Etiqueta|
||||xsi:nil=”true”.|
|**codigoCliente**|Alfanum|Si|Código de identificación único del cliente, deberá|
||érico||ser asignado por el sistema de facturación del|
||||contribuyente.|
|**codigoMetodoPago**|Numéric|Si|Valor de la paramétrica que identifica el método de|
||o||pago utilizado para realizar la compra. Por ejemplo|
||||1 que representa a un pago en efectivo. (Utilizar el|
||||tipo de pago Otros  (5) solo si el método utilizado|
||||no esta disponible)|
|**numeroTarjeta**|Numéric|No|Cuando el método de pago es 2 (Tarjeta), debe|
||o||enviarse este valor pero ofuscado con los primeros|
||||y últimos 4 dígitos en claro y ceros al medio. Ej:|
||||4797000000007896, en otro caso, debe enviarse|
||||un valor nulo.|
|**montoTotal**|Numéric|Si|Monto total por el cual se realiza el hecho|
||o||generador.|
|**montoTotalSujetoIva**|Numéric|Si|Monto base para el cálculo del crédito fiscal.|
||o|||
|**montoGiftCard**|Numéric|No|Monto a ser cancelado con una Gift Card|
||o|||
|**descuentoAdicional**||No|Monto Adicional al descuento por item|
||Numéric|||
||o|||
|**codigoExcepcion**||No|Por defecto, enviar este campo con un valor de|
||Numéric||cero (0) o nulo. Solo cuando se desee autorizar al|
||o||SIN el registro de una factura emitida a un NIT|
||||inválido se debe enviar el valor de uno (1) en el|
||||mismo .|
|**cafc**||No|Código de Autorización de Facturas por|
||Alfanum||Contingencia|
||érico|||
|**codigoMoneda**|Numéric|Si|Valor de la paramétrica que identifica la moneda en|
||o||la cual se realiza la transacción.|
|**tipoCambio**|Numéric|Si|Tipo de cambio de acuerdo a la moneda en la que|
||o||se realiza el hecho generador, si el código de|
||||moneda es boliviano deberá ser igual a 1.|
|**montoTotalMoneda**|Numéric|Si|Es el Monto Total expresado en el tipo de moneda,|
||o||si el código de moneda es boliviano deberá ser|
||||igual al monto total.|
|**leyenda**|Alfanum|Si|Leyenda asociada a la actividad económica.|
||érico|||
|||||



|**usuario**|Alfanum|Si|Identifica al usuario que emite la factura, deberá|
|---|---|---|---|
||érico||ser descriptivo. Por ejemplo JPEREZ|
|**codigoDocumentoSect**|Numéric|Si|Valor de la paramétrica que identifica el tipo de|
|**or**|o||factura que se está emitiendo. Para este tipo de|
||||factura este valor es 1.|
||||**DETALLE**|
|**actividadEconomica**|Alfanum|Si|Actividad económica registrada en el Padrón|
||érico||Nacional de Contribuyentes relacionada al NIT.|
|**codigoProductoSin**|Numéric|Si|Homologado a los códigos de productos genéricos|
||o||enviados por el SIN a través del servicio de|
||||sincronización.|
|**codigoProducto**|Alfanum|Si|Código que otorga el contribuyente a su servicio o|
||érico||producto.|
|**descripcion**|Alfanum|Si|Descripción que otorga el contribuyente a su|
||érico||servicio oproducto.|
|**cantidad**|Numéric|Si|Cantidad del producto o servicio otorgado. En caso|
||o||de servicio este valor debe ser 1.|
|**unidadMedida**|Numéric|Si|Valor de la paramétrica que identifica la unidad de|
||o||medida.|
|**precioUnitario**|Numéric|Si|Precio que otorga el contribuyente a su servicio o|
||o||producto.|
|**montoDescuento**|Numéric|No|<br>Monto de descuento sobre el producto o servicio|
||o||específico,  Si no aplica deberá ser nulo.|
|**subTotal**|Numéric|Si|El subtotal es igual a la (cantidad * precio unitario)|
||o||– descuento.|
|**numeroSerie**|Alfanum|No|<br>Número de serie correspondiente al producto|
||érico||vendido de línea blanca o negra. Nulo en otro|
||||caso.|
|**numeroImei**|Alfanum|No|<br>Número de Imei del celular vendido. Nulo en otro|
||érico||caso.|



**Nota.** - En el caso de venta de servicios, se debe considerar en cantidad consignar 1, en unidad de medida consignar 58 (unidad servicio), el precio del servicio consignarlo en precio unitario para fines de cálculo. 

Si los números de serie o Imei son pocos incluirlos en el detalle de la factura caso contrario enviar los mismos consumiendo el servicio  Recepción Archivos Anexos correspondiente, el cual permite el registro simultaneo de varios numeros de Serie o Imei. 

Si corresponde, incluir en el campo Descripción de la factura el número de la Guía de Tránsito utilizada para el transporte de los productos. 

En caso de utilizar la modalidad electrónica en linea, no se olvide de incluir en el XSD la dirección donde se encuentra el SignatureSchema. 

## **Firma Digital** 

Es un mecanismo criptográfico que permite al receptor de un mensaje firmado digitalmente identificar a la entidad originadora de dicho mensaje (autenticación de origen y no repudio), y confirmar que el mensaje no ha sido alterado desde que fue firmado por el originador (integridad). 

## **Garantías de la Firma Digital** 

1. **Autenticidad:** La firma digital ayuda a garantizar que la persona que firma es quien dice ser. 

2. **No Repudio:** El signatario no puede negar la firma digital ya que solo él posee el certificado digital y la clave privada. 

3. **Integridad:** La firma digital ayuda a verificar que el contenido no se ha cambiado o manipulado desde que se firmó el documento. 

## **Beneficios** 

1. Reduce el uso de papel. 

2. Disminuye costos operativos. 

3. Agiliza y simplifica la entrega de facturas. 

4. Permite implementar trámites en línea. 

## **¿Cómo obtienes un certificado digital?** 

Para obtener un certificado digital válido a nivel nacional puede acudir a una entidad certificada autorizada o una Agencia de Registro y realizar el trámite ante ella: 

- Agencia de Gobierno Electrónico y Tecnologías de Información y Comunicación 

- (AGETIC). 

- Certificaciones Digitales Digicert SRL. 

## **Proceso de Firmado** 

A efectos de poder firmar un documento, es necesario disponer de una llave pública y una privada; tener implementado algoritmos de conversión a Base 64, canonicalización, SHA256 y RSA Sha256 V2 y seguir los siguientes pasos: 

1. Aplicar el algoritmo de canonicalización al documento XML, es decir realizar un procesamiento que permita obtener su forma canónica o se normalice el documento original. 

2. Aplicara al resultado el algoritmo sha256 a objeto de obtener el HASH. 

3. Obtener una cadena aplicando al anterior HASH el algoritmo Base64. 

4. Adicionar las etiquetas de signature al XML. 

5. Agregar a la etiqueta Digest Value el valor obtenido en el paso 4. 

6. Tomar la sección de la firma y obtener un HASH del mismo aplicando el algoritmo SHA256. 

7. Encriptar el HASH obtenido utilizando el algoritmo RSA SHA256 con la llave privada. 

8. Aplicar a la cadena resultante el algoritmo Base64 para obtener una cadena. 

9. Adicionar a la etiqueta de Signature Value la cadena anterior. 

10. Finalmente colocar en la etiqueta X509 Certificate la llave publica. 

11. Devolver el XML firmado. 

## **Validaciones de la Firma Digital** 

La Administración Tributaria validará de forma inmediata la vigencia de la Firma Digital utilizada en el firmado de Facturas digitales u otros documentos fiscales digitales, independientemente de su forma de envío. 

La validación de revocación se hará en función de la información que las Entidades Certificadoras vayan actualizando su Lista de Certificados Revocados (CRL), por lo que no será necesariamente inmediata, las Facturas o Notas de Crédito - Débito emitidas con firmas revocadas o no vigentes serán observadas para procesos posteriores. 

## **Generación CSR** 

Es un documento electrónico firmado digitalmente por una Entidad Certificadora Autorizada que vincula los datos de verificación de firma de un signatario y confirma su identidad. El certificado digital es válido únicamente dentro del período de vigencia, indicado en el mismo. (Ley No 164). 

Un Certificado Digital consta de una pareja de claves criptográficas, una pública y una privada, creadas con un algoritmo matemático, de forma que aquello que se cifra con una de las claves sólo se puede descifrar con su clave pareja. 

El usuario titular del certificado mantiene bajo su poder la clave privada, ya que si ésta es sustraída, el sustractor podría suplantar la identidad del titular en la red. En este caso el titular debe revocar el certificado lo antes posible, igual que se anula una tarjeta de crédito sustraída. 

La clave pública forma parte de un documento digital junto a los datos del usuario titular, esta clave será firmada por una Entidad Certificadora Autorizada (AGETIC, DIGICERT) que son las entidades de confianza que asegura que la clave pública se corresponde con los datos del titular. 

La Firma Digital puede ser por: 

1. **Token:** Es el tipo más simple para el uso de la firma digital, consiste en un USB de PKI (Infraestructura de clave pública), que podría ser utilizado cuando se tenga baja emisión de Facturas o Notas Crédito - Débito electrónicas. Para cada Factura emitida, el Contribuyente debe autenticarse para firmarlo con su llave Privada. 

2. **Software:** Es el tipo de almacenamiento donde las firmas digitales son creadas y administradas por un software algún equipo (PC o laptop) del propio Contribuyente. De acuerdo a Resolución Administrativa Regulatoria de la ATT 357/2020 y R.M. 253/18 esta permitido el uso de la firma digital por software 

debido a que se tiene altos volúmenes de transaccionalidad y a la inmediatez de la emisión de la factura. Se debe extremar medidas de seguridad para proteger de accesos no autorizados al lugar donde se halla almacenada la firma digital. 

3. **HSM:** Este tipo de firmado se realiza a través de un hardware especializado (Módulo de Seguridad de Hardware) de manejo y administración de firmas; podría ser utilizado por los Contribuyentes que posean varias sucursales o puntos de venta, esto implicaría varias firmas digitales que son creadas y alojadas en el HSM. 

## **Especificación CSR válida en Bolivia** 

Conforme normativa vigente el CSR (Certificate Signing Request) es el formato con el cual hacemos la solicitud de un certificado digital ante la entidad certificadora. En Bolivia ésta solicitud debe tener el siguiente estándar: 

|||
|---|---|
|**Nombre**<br>**Suscriptor**|**OID**<br>**Descripción OID**|
|**(subject)**|2.5.4.3<br>CN = Nombres y Apellidos del representante<br>legal autorizado para representar a la persona<br>jurídica en determinadas atribuciones;|
||2.5.4.10<br>O = Razón Social de la empresa o Institución a la<br>que representa lapersonajurídica;|
||2.5.4.11<br>OU = Unidad Organizacional de laque depende;|
||2.5.4.12<br>title = Cargo del representante legal;|
||2.5.4.6<br>C = estándar de acuerdo a ISO 3166{BO};|
||2.5.4.46<br>dnQualifier = Tipo de documento{CI/CE};|
||1.3.6.1.1.1.1.0<br>uidNumber = Nro. De documento{numeral};|
||0.9.2342.19200300.100.1.1 uid = número de complemento{alfanumérico} ;|
||2.5.4.5<br>serialNumber = Número de NIT{numeral} ;|
||1.2.840.113549.1.9.1<br>emailAddress=Dirección de correo;|
|||



## **Extensiones** 

- La extensión del archivo CSR es de tipo: .pem o .csr. 

- De parte de la entidad Certificadora obtendremos el Certificado con extensión .cer o .pem. 

- Así también, la llave privada (private Key) tendrá la extensión .pem. 

## **Generación de CSR para Token** 

En nuestro país AGETIC y DIGICERT son responsables de la emisión de certificados digitales y venta de dispositivos TOKEN por ello en su sitio encontrará toda la información relacionada a la creación costos y otros. 

Puedes revisar la guía de instalación y generación en el sistema JACOBITUS - FIDO, proporcionado por la Agencia de Gobierno Electrónico y Tecnologías de Información y 

Comunicación (AGETIC) o ver el video para la generación de CSR proporcionado por DIGICERT. 

## **Firmado de documentos XML** 

## **Requisitos:** 

|**Lenguaje de Programación**|JAVA|
|---|---|
|**IDE**|Eclipse|
|**Acceso a Internet**|Solo para descarga de librerías|
|||



## **Pasos para la configuración:** 

· **Dependencias:** Para el firmado de documentos XML necesitamos las siguientes dependencias: 

## **POM** 

<?xml version="1.0" encoding="UTF-8"?> 

<project xmlns="http://maven.apache.org/POM/4.0.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://maven.apache.org/POM/4.0.0 http://maven.apache.org/xsd/maven-4.0.0.xsd"> 

<modelVersion>4.0.0</modelVersion> 

<groupId>bo.sin</groupId> <artifactId>TestFirma</artifactId> 

<version>1.0-SNAPSHOT</version> 

<packaging>jar</packaging> 

<dependencies> 

<dependency> 

<groupId>junit</groupId> 

<artifactId>junit</artifactId> 

<version>4.12</version> 

<scope>test</scope> 

</dependency> 

<dependency> 

<groupId>org.hamcrest</groupId> 

<artifactId>hamcrest-core</artifactId> 

<version>1.3</version> 

<scope>test</scope> 

</dependency> 

<dependency> 

<groupId>org.bouncycastle</groupId> <artifactId>bcprov-jdk15on</artifactId> 

<version>1.56</version> 

</dependency> 

<dependency> 

<groupId>org.bouncycastle</groupId> <artifactId>bcpkix-jdk15on</artifactId> 

<version>1.56</version> 

</dependency> 

<dependency> 

<groupId>org.apache.santuario</groupId> 

<artifactId>xmlsec</artifactId> 

<version>2.0.5</version> 

<type>jar</type> 

</dependency> 

<dependency> 

<groupId>commons-io</groupId> 

<artifactId>commons-io</artifactId> 

<version>2.5</version> 

<scope>test</scope> 

<type>jar</type> 

</dependency> 

</dependencies> 

<properties> 

<project.build.sourceEncoding>UTF-8</project.build.sourceEncoding> 

<maven.compiler.source>1.8</maven.compiler.source> 

<maven.compiler.target>1.8</maven.compiler.target> 

</properties> <build> 

<resources> 

<resource> 

<directory>src/main/resources</directory> 

</resource> </resources> </build> 

</project> 

## **Implementación de código:** 

La clase principal tiene la siguiente forma: 

Firmador. Java 

/* 

* To change this license header, choose License Headers in Project Properties. 

* To change this template file, choose Tools | Templates 

* and open the template in the editor. 

*/ 

package bo.sin.testfirma; /** * 

* @author marcelo.romero 

*/ import java.io.BufferedReader; import java.io.ByteArrayInputStream; import java.io.ByteArrayOutputStream; import java.io.FileInputStream; import java.io.FileReader; import java.io.IOException; import java.security.GeneralSecurityException; import java.security.KeyFactory; import java.security.PrivateKey; import java.security.PublicKey; import java.security.Security; import java.security.cert.CertificateException; import java.security.cert.CertificateFactory; import java.security.cert.X509Certificate; import java.security.interfaces.RSAPrivateKey; 

import java.security.interfaces.RSAPublicKey; import java.security.spec.PKCS8EncodedKeySpec; import java.security.spec.X509EncodedKeySpec; import javax.xml.parsers.DocumentBuilder; import javax.xml.parsers.DocumentBuilderFactory; import javax.xml.parsers.ParserConfigurationException; import org.apache.commons.codec.binary.Base64; import org.apache.xml.security.Init; import org.apache.xml.security.algorithms.MessageDigestAlgorithm; import org.apache.xml.security.exceptions.XMLSecurityException; import org.apache.xml.security.signature.XMLSignature; import org.apache.xml.security.transforms.Transforms; import org.apache.xml.security.utils.Constants; import org.apache.xml.security.utils.ElementProxy; import org.apache.xml.security.utils.XMLUtils; import org.bouncycastle.jce.provider.BouncyCastleProvider; import org.w3c.dom.Document; import org.w3c.dom.Element; import org.xml.sax.SAXException; /** 

* 

* @author */ public class Firmador { 

// http://stackoverflow.com/questions/7224626/how-to-sign-string-with-private-key private static Firmador instancia; private String ALG = "SHA1withRSA"; static { Init.init(); Security.addProvider(new BouncyCastleProvider()); } /** * Obtener un firmador por defecto. * 

* @return un Firmador. 

*/ 

public static Firmador getInstance() { 

if (instancia == null) { 

instancia = new Firmador(); 

} return instancia; } private Firmador() { 

} 

//// Todo: Colocar en un solo directorio la llave privada con la publica 

/** 

* Esta funcion añade una firma a un documento XML. 

* 

* @param datos Documento a firmar <i>XML</i>. 

* @param priv Clave privada. 

* @param cert Certificado del firmante. 

* @return Retorna el documento con una firma. 

* @throws ParserConfigurationException 

* @throws IOException 

* @throws SAXException 

* @throws XMLSecurityException 

*/ 

public static byte[] firmarDsig(byte[] datos, PrivateKey priv, X509Certificate... cert) throws ParserConfigurationException, IOException, SAXException, XMLSecurityException { 

ElementProxy.setDefaultPrefix(Constants.SignatureSpecNS, ""); 

Document documento = leerXML(datos); 

Element root = (Element) documento.getFirstChild(); 

documento.setXmlStandalone(false); 

XMLSignature signature = new XMLSignature(documento, null, XMLSignature.ALGO_ID_SIGNATURE_RSA_SHA256); 

root.appendChild(signature.getElement()); 

Transforms transforms = new Transforms(documento); 

transforms.addTransform(Transforms.TRANSFORM_ENVELOPED_SIGNATURE); 

transforms.addTransform(Transforms.TRANSFORM_C14N_WITH_COMMENTS); 

signature.addDocument("", transforms, MessageDigestAlgorithm.ALGO_ID_DIGEST_SHA256); 

if (cert != null) { 

signature.addKeyInfo(cert[0]); 

} 

signature.sign(priv); 

ByteArrayOutputStream baos = new ByteArrayOutputStream(); 

XMLUtils.outputDOMc14nWithComments(documento, baos); 

return baos.toString().getBytes(); 

} 

public static Document leerXML(byte datos[]) throws ParserConfigurationException, IOException, SAXException { 

DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance(); 

DocumentBuilder builder; 

factory.setNamespaceAware(true); 

builder = factory.newDocumentBuilder(); 

return builder.parse(new ByteArrayInputStream(datos)); 

} 

private static String getKey(String filename) throws IOException { 

// Read key from file 

String strKeyPEM = ""; 

BufferedReader br = new BufferedReader(new FileReader(filename)); 

String line; 

while ((line = br.readLine()) != null) { 

strKeyPEM += line + "\n"; 

} 

br.close(); 

return strKeyPEM; 

} 

public static RSAPrivateKey getPrivateKey(String filename) throws IOException, GeneralSecurityException { 

String privateKeyPEM = getKey(filename); 

return getPrivateKeyFromString(privateKeyPEM); 

} 

public static RSAPrivateKey getPrivateKeyFromString(String key) throws IOException, GeneralSecurityException { 

String privateKeyPEM = key; 

privateKeyPEM = privateKeyPEM.replace("-----BEGIN PRIVATE KEY-----\n", ""); 

privateKeyPEM = privateKeyPEM.replace("-----END PRIVATE KEY-----", ""); 

byte[] encoded = Base64.decodeBase64(privateKeyPEM); 

KeyFactory kf = KeyFactory.getInstance("RSA"); 

PKCS8EncodedKeySpec keySpec = new PKCS8EncodedKeySpec(encoded); 

RSAPrivateKey privKey = (RSAPrivateKey) kf.generatePrivate(keySpec); 

return privKey; 

} 

public static RSAPublicKey getPublicKey(String filename) throws IOException, GeneralSecurityException { 

String publicKeyPEM = getKey(filename); 

return getPublicKeyFromString(publicKeyPEM); 

} 

public static RSAPublicKey getPublicKeyFromString(String key) throws IOException, GeneralSecurityException { 

String publicKeyPEM = key; 

publicKeyPEM = publicKeyPEM.replace("-----BEGIN PUBLIC KEY-----\n", ""); 

publicKeyPEM = publicKeyPEM.replace("-----END PUBLIC KEY-----", ""); 

byte[] encoded = Base64.decodeBase64(publicKeyPEM); 

KeyFactory kf = KeyFactory.getInstance("RSA"); 

RSAPublicKey pubKey = (RSAPublicKey) kf.generatePublic(new X509EncodedKeySpec(encoded)); 

return pubKey; 

} 

public static X509Certificate getX509Certificate(String filename) throws IOException, CertificateException 

{ 

CertificateFactory fact = CertificateFactory.getInstance("X.509"); 

FileInputStream is = new FileInputStream (filename); 

X509Certificate cer = (X509Certificate) fact.generateCertificate(is); 

PublicKey key = cer.getPublicKey(); 

return cer; } 

} 

Para probar el código se tiene el siguiente Test: 

TEST 

@Test 

public void firmarXML() throws URISyntaxException, ParserConfigurationException, XMLSecurityException, org.xml.sax.SAXException { 

String xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><facturaElectronicaEstandar> AQUI VA LA FACTURA XML </fin>"; 

byte[] datos = xml.getBytes(StandardCharsets.UTF_8); 

try { 

- String path = new 

File(Firmador.class.getProtectionDomain().getCodeSource().getLocation().toURI()).getPath(); 

PrivateKey privateKey = Firmador.getPrivateKey(path + "/vladimir_private.pem"); 

X509Certificate cert =  Firmador.getX509Certificate(path + "/vladimir.cer"); 

byte[] xmlFirmado = Firmador.firmarDsig(datos, privateKey, cert); 

String respuesta = new String(xmlFirmado); 

System.out.println("facturaFirmada: "+respuesta); 

} catch (IOException | GeneralSecurityException ex) { 

Logger.getLogger(FirmadorUnitTest.class.getName()).log(Level.SEVERE, null, ex); 

} 

} 

En la línea remarcada en verde se debe agregar la Factura Digital en formato XML. En la línea remarcada en amarillo se configuran las rutas donde se encuentran el certificado digital y la llave privada. 

En la línea remarcada en celeste se imprime el resultado que es la Factura electrónica firmada. 

**==> picture [452 x 83] intentionally omitted <==**


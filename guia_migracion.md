# 🚀 Guía de Migración del Sistema de Restaurante a la PC del Local

Esta guía te ayudará a trasladar el sistema de restaurante desde tu laptop de desarrollo a la computadora local del restaurante, asegurando que los datos de la base de datos se transfieran correctamente y las funciones de impresión queden operativas.

---

## 📋 Resumen del Proceso

El traslado se divide en 4 fases principales:
1. **Exportar datos** desde la laptop de desarrollo.
2. **Instalar el entorno de ejecución** en la PC del local.
3. **Descargar el código (por Git/GitHub)** y configurarlo.
4. **Restaurar la base de datos** y configurar la impresora térmica.

---

## 🛠️ Fase 1: Preparación y Exportación (En la Laptop)

Antes de ir al local, necesitamos guardar los datos actuales de la base de datos.

### 1. Exportar la Base de Datos (`restaurante_db`)
1. Abre tu navegador e ingresa a **phpMyAdmin** (usualmente `http://localhost/phpmyadmin`).
2. En la barra lateral izquierda, haz clic en la base de datos **`restaurante_db`**.
3. Haz clic en la pestaña **Exportar** (en la barra superior).
4. Selecciona el método de exportación **Rápido** y formato **SQL**.
5. Presiona el botón **Exportar** (o **Ir**). Se descargará un archivo llamado `restaurante_db.sql`.
6. Guarda este archivo `restaurante_db.sql` en un pendrive (memoria USB) para llevarlo a la PC del local.

### 2. Guardar y Subir los Cambios al Repositorio (GitHub)
Asegúrate de que todo tu código esté subido a GitHub. Abre la terminal en el proyecto de tu laptop y ejecuta:
```bash
git add .
git commit -m "feat: preparación para despliegue en PC local"
git push origin main
```
*(Reemplaza `main` por la rama que estés usando si es diferente).*

---

## 💻 Fase 2: Instalación de Requisitos (En la PC del Local)

Para que el sistema funcione en la computadora del local, necesitamos instalar el software necesario. Recomendamos usar **Laragon** en lugar de XAMPP, ya que es más rápido, moderno y autogestiona las herramientas necesarias en Windows de forma mucho más limpia.

### Opción Recomendada: Laragon (Súper fácil para Windows)
1. Descarga **Laragon Full** (con PHP 8.2 o superior, MySQL y Apache) desde su sitio web oficial: [https://laragon.org/download/](https://laragon.org/download/).
2. Instálalo siguiendo el asistente (se instalará por defecto en `C:\laragon`).

### Herramientas adicionales necesarias:
Si no vienen preinstaladas con Laragon, o si prefieres instalar todo por separado, descarga e instala lo siguiente en la PC del local:

1. **Git para Windows**:
   - Descarga desde [https://git-scm.com/](https://git-scm.com/).
   - Durante la instalación, deja todas las opciones por defecto.
2. **Node.js (Versión LTS)**:
   - Descarga desde [https://nodejs.org/](https://nodejs.org/).
   - Requerido para compilar el diseño y los assets del frontend.
3. **Composer (Manejador de Dependencias PHP)**:
   - Descarga desde [https://getcomposer.org/download/](https://getcomposer.org/download/).
   - Durante la instalación, te pedirá ubicar el archivo `php.exe`. Si usas Laragon, este se encuentra en: `C:\laragon\bin\php\php-8.x.x\php.exe`.

---

## 📥 Fase 3: Descarga y Configuración del Proyecto (En la PC del Local)

### 1. Clonar el repositorio de GitHub
1. Abre la terminal de Git (**Git Bash**) o la consola en la PC del local.
2. Navega a la carpeta de servidor web. 
   - Si instalaste **Laragon**: ve a `C:\laragon\www\`
   - Si instalaste **XAMPP**: ve a `C:\xampp\htdocs\`
   ```bash
   cd C:\laragon\www
   ```
3. Clona tu repositorio usando la URL de GitHub:
   ```bash
   git clone https://github.com/TU_USUARIO/TU_REPOSITORIO.git sistema-restaurante
   ```
   *(Reemplaza la URL por la tuya)*
4. Entra a la carpeta del proyecto:
   ```bash
   cd sistema-restaurante
   ```

### 2. Configurar el archivo de entorno (`.env`)
1. Crea una copia del archivo `.env.example` y llámala `.env`:
   ```bash
   copy .env.example .env
   ```
2. Abre el archivo `.env` con un editor de texto (como Notepad++ o VS Code) y edita los datos de la base de datos si es necesario. Por defecto, en Laragon el usuario es `root` y no tiene contraseña:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=restaurante_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 3. Instalar Dependencias del Sistema
Ejecuta los siguientes comandos uno por uno en la carpeta del proyecto en la PC del local:

1. Instalar dependencias de PHP:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
2. Generar la clave única de la aplicación:
   ```bash
   php artisan key:generate
   ```
3. Instalar dependencias de JavaScript y compilar el diseño:
   ```bash
   npm install
   npm run build
   ```

---

## 🗄️ Fase 4: Migración de la Base de Datos (En la PC del Local)

Ahora pasaremos los datos que exportaste desde la laptop a la nueva computadora.

1. Abre **Laragon** (o XAMPP) y haz clic en **"Iniciar Todo"** (Start All).
2. Abre el gestor de base de datos. Puedes ir a `http://localhost/phpmyadmin` en el navegador.
3. Haz clic en **Nueva** en la barra lateral izquierda para crear una nueva base de datos.
4. Escribe el nombre exacto: **`restaurante_db`** y selecciona el cotejamiento `utf8mb4_unicode_ci`. Haz clic en **Crear**.
5. Selecciona la base de datos recién creada (`restaurante_db`).
6. Haz clic en la pestaña **Importar** (en la barra superior).
7. Haz clic en **Seleccionar archivo** y busca el archivo `restaurante_db.sql` que trajiste en el pendrive.
8. Desplázate hacia abajo y haz clic en **Importar** (o **Ir**).
9. ¡Listo! Todas tus tablas, platos, categorías y usuarios registrados ahora están en la PC del local.

---

## 🖨️ Fase 5: Configurar la Impresora Térmica (Muy Importante)

El sistema utiliza la librería `escpos-php` para mandar comandas y facturas directo a la ticketera física. Sigue estos pasos para que funcione:

1. Conecta la impresora térmica por USB a la PC del local y enciéndela.
2. Instala los drivers oficiales correspondientes de tu impresora (ej. Epson, Xprinter, etc.).
3. Imprime una página de prueba desde Windows para verificar que el driver funciona.
4. **Compartir la Impresora en Red local**:
   - Ve a *Panel de Control > Dispositivos e Impresoras*.
   - Haz clic derecho sobre tu impresora térmica y selecciona **Propiedades de la impresora**.
   - Ve a la pestaña **Compartir**.
   - Marca la casilla **"Compartir esta impresora"** y asígnale un nombre corto y sin espacios, por ejemplo: **`EPSON_TM`**.
   - Haz clic en *Aplicar* y *Aceptar*.
5. **Configurar el archivo `.env`**:
   - Asegúrate de que el nombre asignado en el paso anterior coincida exactamente con la variable en tu archivo `.env`:
     ```env
     PRINTER_NAME=EPSON_TM
     ```

> **IMPORTANTE**: Si la impresora está conectada localmente a la misma PC del cajero que corre el sistema, Windows buscará la impresora con ese nombre compartido (`EPSON_TM`) y mandará las comandas directamente sin problemas.

---

## 🚀 Fase 6: Puesta en Marcha

Una vez configurado todo, puedes iniciar el sistema de dos formas:

### Opción A (Rápida con Laravel Serve):
Abre una consola en la carpeta del proyecto en la PC del local y ejecuta:
```bash
php artisan serve --host=0.0.0.0
```
*El parámetro `--host=0.0.0.0` permite que otros dispositivos en el local (como los teléfonos o tablets de los meseros) se conecten al sistema usando la dirección IP de la PC del cajero (ej: `http://192.168.1.100:8000`).*

### Opción B (Profesional con Laragon VirtualHosts):
Laragon crea automáticamente enlaces locales limpios.
- Si tu carpeta en `C:\laragon\www` se llama `sistema-restaurante`, Laragon creará un dominio local: **`http://sistema-restaurante.test`**.
- Solo debes abrir ese enlace en tu navegador.
- Si los meseros necesitan entrar desde sus teléfonos, deben estar conectados al mismo Wi-Fi que la PC del local y escribir la IP de la computadora en su navegador: `http://192.168.1.XX/sistema-restaurante/public/` (o configurar el archivo de Hosts/VirtualHost de Apache en Laragon para escuchar peticiones externas).

---

## ⚠️ Checklist de Verificación Rápida

- [ ] ¿Laragon/XAMPP está iniciado (Apache y MySQL activos)?
- [ ] ¿El archivo `.env` tiene las credenciales correctas y `APP_DEBUG=false` para producción?
- [ ] ¿Importaste correctamente el archivo `restaurante_db.sql`?
- [ ] ¿La impresora térmica está encendida, con drivers e instalada con el nombre compartido `EPSON_TM`?
- [ ] ¿Ejecutaste `npm run build` para compilar los estilos y JS de la aplicación?

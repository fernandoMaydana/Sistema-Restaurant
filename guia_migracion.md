# 🚀 Guía de Migración del Sistema de Restaurante a la PC del Local (Con XAMPP)

Esta guía te ayudará a trasladar el sistema de restaurante desde tu laptop de desarrollo a la computadora local del restaurante, asegurando que los datos de la base de datos se transfieran correctamente y las funciones de impresión queden operativas, utilizando **XAMPP** como servidor local.

---

## 📋 Resumen del Proceso

El traslado se divide en 4 fases principales:
1. **Exportar datos** desde la laptop de desarrollo.
2. **Instalar XAMPP y los requisitos** en la PC del local.
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
git commit -m "feat: actualización de la guía de migración para XAMPP"
git push origin main
```

---

## 💻 Fase 2: Instalación de Requisitos (En la PC del Local)

Para que el sistema funcione en la computadora del local usando **XAMPP**, debes descargar e instalar las siguientes herramientas en orden:

### 1. XAMPP (Servidor Web y Base de Datos)
- **Descargar:** Entra a [https://www.apachefriends.org/es/index.html](https://www.apachefriends.org/es/index.html) y descarga **XAMPP para Windows** con una versión de PHP 8.2 o superior (obligatorio).
- **Instalación:** Ejecuta el instalador. Asegúrate de instalar al menos los módulos **Apache** y **MySQL**. Deja la ruta por defecto (`C:\xampp`).

### 2. Git para Windows (Para descargar el código)
- **Descargar:** Entra a [https://git-scm.com/](https://git-scm.com/).
- **Instalación:** Ejecuta el instalador y deja todas las opciones por defecto. Esto te dará la herramienta **Git Bash**.

### 3. Node.js (Versión LTS)
- **Descargar:** Entra a [https://nodejs.org/](https://nodejs.org/).
- **Instalación:** Instala la versión recomendada (LTS). Es necesario para compilar el diseño y estilos visuales del sistema.

### 4. Composer (Manejador de Dependencias de Laravel)
- **Descargar:** Entra a [https://getcomposer.org/download/](https://getcomposer.org/download/) y descarga **Composer-Setup.exe**.
- **Instalación:**
  1. Durante la instalación, te pedirá la ruta de `php.exe`. 
  2. Selecciónala en la carpeta de XAMPP: **`C:\xampp\php\php.exe`**.
  3. Sigue los pasos hasta finalizar.

---

## 📥 Fase 3: Descarga y Configuración del Proyecto (En la PC del Local)

### 1. Clonar el repositorio de GitHub
1. Abre la terminal **Git Bash** en la PC del local (la puedes buscar en el menú de inicio).
2. Entra a la carpeta de servidores de XAMPP (`htdocs`):
   ```bash
   cd /c/xampp/htdocs
   ```
3. Clona tu repositorio usando la URL de tu GitHub:
   ```bash
   git clone https://github.com/fernandoMaydana/Sistema-Restaurant.git sistema-restaurante
   ```
4. Entra a la carpeta del proyecto que se acaba de crear:
   ```bash
   cd sistema-restaurante
   ```

### 2. Configurar el archivo de entorno (`.env`)
1. Crea tu archivo de variables de entorno copiando el archivo de ejemplo:
   ```bash
   copy .env.example .env
   ```
2. Abre el archivo `.env` recién creado con el Bloc de notas o un editor como VS Code y configura tu base de datos (por defecto en XAMPP el usuario es `root` y la contraseña está vacía):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=restaurante_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 3. Instalar Dependencias del Sistema
Ejecuta los siguientes comandos uno por uno dentro de la carpeta `/c/xampp/htdocs/sistema-restaurante` en la terminal:

1. Instalar dependencias de PHP:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
2. Generar la clave de seguridad de la aplicación:
   ```bash
   php artisan key:generate
   ```
3. Instalar dependencias de JavaScript y compilar los estilos visuales:
   ```bash
   npm install
   npm run build
   ```

---

## 🗄️ Fase 4: Migración de la Base de Datos (En la PC del Local)

1. Abre el panel de control de **XAMPP** y haz clic en **Start** al lado de **Apache** y **MySQL** para activarlos.
2. Abre tu navegador e ingresa a **`http://localhost/phpmyadmin`**.
3. Haz clic en **Nueva** en el menú de la izquierda para crear la base de datos.
4. Escribe el nombre exacto: **`restaurante_db`** y selecciona el cotejamiento `utf8mb4_unicode_ci`. Luego haz clic en **Crear**.
5. Selecciona la base de datos `restaurante_db` que acabas de crear.
6. Ve a la pestaña **Importar** en el menú superior.
7. Haz clic en **Seleccionar archivo** y busca el archivo `restaurante_db.sql` de tu pendrive.
8. Ve al final de la página y presiona el botón **Importar** (o **Ir**).

---

## 🖨️ Fase 5: Configurar la Impresora Térmica (Muy Importante)

El sistema se conecta automáticamente a la ticketera local mediante la variable `PRINTER_NAME` en el `.env`.

1. Conecta la impresora por USB al PC del local y enciéndela.
2. Instala los drivers oficiales de la impresora.
3. **Compartir la Impresora en Windows**:
   - Ve a *Panel de Control > Dispositivos e Impresoras*.
   - Haz clic derecho sobre la impresora y selecciona **Propiedades de la impresora**.
   - En la pestaña **Compartir**, marca la casilla **"Compartir esta impresora"** y ponle un nombre sin espacios, por ejemplo: **`EPSON_TM`**.
   - Haz clic en *Aplicar* y *Aceptar*.
4. **Verificar el archivo `.env`**:
   - Asegúrate de que el nombre asignado coincida en tu archivo `.env`:
     ```env
     PRINTER_NAME=EPSON_TM
     ```

---

## 🚀 Fase 6: Puesta en Marcha

Una vez configurado todo, puedes iniciar el sistema abriendo una terminal en la carpeta del proyecto en la PC del local y ejecutando:
```bash
php artisan serve --host=0.0.0.0
```
*El host `0.0.0.0` es clave para que los meseros puedan ingresar desde sus teléfonos ingresando la dirección IP de la PC del local (por ejemplo: `http://192.168.1.100:8000`).*

---

## ⚠️ Checklist de Verificación Rápida

- [ ] ¿Apache y MySQL están iniciados en el Panel de XAMPP?
- [ ] ¿El archivo `.env` tiene configurada la base de datos correcta y la impresora?
- [ ] ¿Importaste el archivo `restaurante_db.sql` en phpMyAdmin?
- [ ] ¿La impresora térmica está encendida y compartida con el nombre `EPSON_TM`?
- [ ] ¿Ejecutaste `npm run build` para que se vean todos los estilos premium de la aplicación?

---

## 🛠️ Solución de Problemas Comunes

### 1. Las imágenes no se muestran o no se guardan (Error 404 / Enlace roto)
En Laravel, las imágenes se guardan en una carpeta protegida (`storage/app/public`) y se necesita crear un "puente" o acceso directo hacia la carpeta pública. Si no lo has creado en la PC del local, no verás las imágenes.
* **Solución:** Abre la terminal de Git Bash en la carpeta del proyecto en la PC del local y ejecuta:
  ```bash
  php artisan storage:link
  ```
  Esto creará el acceso directo necesario automáticamente.

### 2. Error al intentar subir imágenes (Límites de tamaño)
Hay dos lugares que controlan el peso máximo de las imágenes que puedes subir:

* **El validador de Laravel (2 MB):**
  En el controlador [ProductoController.php](file:///c:/Users/Fernando/OneDrive/Desktop/htdocs/sistema-restaurante/app/Http/Controllers/Admin/ProductoController.php#L49) hay una regla que valida `'imagen' => 'nullable|image|max:2048'`. Esto limita las imágenes a un máximo de **2 Megabytes**. Si tomas una foto con un celular moderno, esta pesará más de 2MB y Laravel rechazará la subida mostrando un error de validación.
  * **Solución:** Puedes reducir la foto antes de subirla o cambiar el valor `2048` por uno mayor (ej. `10240` para 10MB) en el código.

* **La configuración de PHP en XAMPP (Por defecto 2 MB):**
  Si al subir una foto la página se queda en blanco, da un error 500 o expira la sesión, es por el límite de XAMPP.
  * **Solución:**
    1. Abre el Panel de Control de **XAMPP**.
    2. Haz clic en el botón **Config** en la línea de **Apache** y selecciona **php.ini**.
    3. Busca la línea `upload_max_filesize=2M` y cámbiala a `upload_max_filesize=40M`.
    4. Busca la línea `post_max_size=8M` y cámbiala a `post_max_size=40M`.
    5. Guarda el archivo, cierra y **reinicia Apache** (clic en *Stop* y luego en *Start* en el panel de XAMPP).

---

## 🔄 Cómo Actualizar el Sistema en la PC del Local

Cuando realices mejoras, crees nuevas funciones o arregles errores en tu laptop, sigue estos pasos para pasar esas actualizaciones a la computadora del local sin perder la información de ventas que ya se registró allá.

### Paso 1: Subir los cambios a GitHub (Desde tu Laptop)
Una vez que termines de programar y probar los cambios en tu laptop, súbelos a GitHub:
```bash
git add .
git commit -m "feat: descripción del cambio o mejora"
git push origin main
```

### Paso 2: Descargar la actualización (En la PC del Local)
1. Abre la terminal de **Git Bash** en la carpeta del proyecto (`C:\xampp\htdocs\sistema-restaurante`).
2. Descarga la última versión de tu código:
   ```bash
   git pull origin main
   ```

### Paso 3: Actualizar la Base de Datos (Si hay nuevas tablas o columnas)
Si tu actualización incluyó cambios en la base de datos (nuevas tablas, columnas o configuraciones de tablas), ejecuta el siguiente comando en la terminal:
```bash
php artisan migrate
```
> **IMPORTANTE:** Este comando es totalmente seguro. Actualizará la estructura de tu base de datos **sin borrar** los datos, platos, mesas o ventas registradas en el local. ¡Nunca uses `migrate:fresh` en el local porque eso sí borraría la base de datos!

### Paso 4: Recompilar los diseños y dependencias (Si es necesario)
Si agregaste nuevas librerías de PHP o JavaScript, o modificaste estilos visuales (CSS/Blade), ejecuta:
```bash
# Si instalaste nuevas librerías de PHP
composer install --no-dev --optimize-autoloader

# Si instalaste librerías de JS o cambiaste el diseño visual
npm install
npm run build
```

### Paso 5: Limpiar caché de Laravel
Para asegurarte de que el sistema cargue los nuevos archivos inmediatamente y no use archivos antiguos guardados en memoria:
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

# 🚀 Guía de Despliegue con PostgreSQL en la PC del Local (Cliente)

Esta guía está diseñada para llevar a cabo la **instalación limpia desde cero** por primera vez en la PC servidor del restaurante, y también detalla el procedimiento para aplicar **actualizaciones** en el futuro sin riesgo de perder los datos de ventas y configuraciones del cliente.

---

## 📂 PARTE 1: Instalación Limpia Desde Cero (Primera Vez)

Sigue estos pasos en orden secuencial para instalar y levantar el sistema por primera vez en la computadora servidor del local.

### 1. Instalar PostgreSQL en Windows
1. **Descargar:** Entra a [EnterpriseDB (EDB) Downloads](https://www.enterprisedb.com/downloads/postgres-postgresql-downloads) y baja la versión para Windows x86-64 (se recomienda **PostgreSQL 15 o 16**).
2. **Instalación:** 
   - Ejecuta el instalador. Deja la ruta por defecto (`C:\Program Files\PostgreSQL\<version>`).
   - Marca todos los componentes (PostgreSQL Server, pgAdmin 4, Stack Builder, Command Line Tools).
   - **Contraseña:** Define la contraseña del superusuario `postgres` (se recomienda usar `123789456` para coincidir con la configuración del proyecto).
   - **Puerto:** Deja el puerto por defecto `5432`.
   - Continúa e instala. Desmarca *Stack Builder* al finalizar.
3. **Crear la Base de Datos:**
   - Abre **pgAdmin 4** desde el menú de inicio de Windows.
   - Despliega *Servers* (introduce la contraseña de postgres).
   - Haz clic derecho sobre **Databases** -> **Create** -> **Database...**
   - Escribe el nombre exacto: `sistema_restaurante` y haz clic en **Save**.

### 2. Habilitar PostgreSQL en PHP (XAMPP)
Por defecto, XAMPP tiene PostgreSQL desactivado. Debes activarlo:
1. Abre el Panel de Control de **XAMPP**.
2. Al lado de **Apache**, haz clic en el botón **Config** -> **php.ini**.
3. Busca en el archivo las siguientes dos líneas y **quítales el punto y coma (`;`) inicial**:
   ```ini
   extension=pdo_pgsql
   extension=pgsql
   ```
4. Guarda el archivo, cierra y **reinicia Apache** (clic en *Stop* y luego en *Start* en XAMPP).

### 3. Descargar el Código del Sistema
1. Abre **Git Bash** en la PC del local del cliente.
2. Navega al directorio público de XAMPP:
   ```bash
   cd /c/xampp/htdocs
   ```
3. **¿Qué hacer si ya existe la carpeta `sistema-restaurante`?**
   Si ya hay una versión anterior en el local, **no la borres directamente**. Es mejor renombrarla para tener un respaldo de seguridad (por si necesitas rescatar el archivo `.env` anterior, imágenes subidas o configuraciones):
   * **Renombrarla desde Git Bash:**
     ```bash
     mv sistema-restaurante sistema-restaurante-viejo
     ```
   * *O bien, cámbiale el nombre desde el Explorador de Archivos de Windows a algo como `sistema-restaurante-copia`.*
4. Clona el repositorio limpio de Git:
   ```bash
   git clone https://github.com/fernandoMaydana/Sistema-Restaurant.git sistema-restaurante
   ```
5. Entra a la carpeta del proyecto nuevo:
   ```bash
   cd sistema-restaurante
   ```

### 4. Configurar Entorno (.env) y Dependencias
1. Genera el archivo de variables de entorno copiando la plantilla:
   ```bash
   cp .env.example .env
   ```
2. Abre el archivo `.env` e ingresa los datos de conexión a tu PostgreSQL:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=sistema_restaurante
   DB_USERNAME=postgres
   DB_PASSWORD=123789456  # Coloca la contraseña que definiste en el paso 1
   ```
3. Instala las dependencias de PHP y genera la llave de cifrado de Laravel:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   ```
4. Instala y compila el frontend del sistema (esencial para los diseños y estilos premium):
   ```bash
   npm install
   npm run build
   ```

### 5. Configurar la Base de Datos Inicial (Elegir un Método)

* **Método A: Comenzar completamente vacío (Migraciones y Semilla)**
  Usa esto si quieres que el sistema empiece limpio y genere los usuarios por defecto:
  ```bash
  php artisan migrate --seed
  ```
  *🔑 Cuentas por defecto:*
  - **Administrador:** `admin@restaurant.com` / `admin123`
  - **Cajero:** `caja@restaurant.com` / `caja123`
  - **Mesero:** `mesero@restaurant.com` / `mesero123`

* **Método B: Restaurar desde un Respaldo (Backup) pre-configurado de tu PC**
  Usa esto si ya cargaste categorías, platos, precios y usuarios en tu laptop y quieres clonarlos tal cual.
  1. **En tu laptop:** Clic derecho sobre `sistema_restaurante` en pgAdmin 4 -> **Backup...** -> Escribe `respaldo_inicial.backup` y selecciona el formato **Custom**. (O por consola: `pg_dump -U postgres -d sistema_restaurante -F c -f respaldo_inicial.backup`). Guardalo en un pendrive.
  2. **Copiar fotos de platos (¡Muy Importante!):** Copia el contenido de la carpeta `storage/app/public/` de tu laptop y pégalo en la misma carpeta `storage/app/public/` del proyecto en la PC del local.
  3. **En la PC del local:** Clic derecho sobre la BD vacía `sistema_restaurante` en pgAdmin 4 -> **Restore...** -> Selecciona el archivo `respaldo_inicial.backup` y ejecuta la restauración. (O por consola: `pg_restore -U postgres -d sistema_restaurante -v respaldo_inicial.backup`).

### 6. Enlaces y Configuración de Impresora
1. Crea el enlace simbólico para poder visualizar las imágenes:
   ```bash
   php artisan storage:link
   ```
2. Comparte la ticketera térmica en Windows (*Propiedades de la impresora > Compartir > Compartir esta impresora*) con el nombre de recurso `EPSON_TM`.
3. Verifica que en tu `.env` del local coincida el nombre de la impresora compartida:
   ```env
   PRINTER_NAME=EPSON_TM
   ```
4. Configura el script de inicio automático: Abre [iniciar_sistema.vbs](file:///c:/Users/Fernando/OneDrive/Desktop/htdocs/sistema-restaurante/iniciar_sistema.vbs) y cambia la dirección IP en la línea 15 por la IP de red local fija de la PC servidor del local (ej: `http://192.168.0.7:8000`).

---

## 🔄 PARTE 2: Actualización del Sistema (En el Futuro)

Sigue estos pasos cuando hagas mejoras, corrijas errores o agregues nuevas funciones en tu laptop y desees pasarlas a la PC servidor del local **sin riesgo de borrar** las ventas, facturas o platos reales que el cliente ya haya registrado.

### 1. Subir cambios a GitHub (En tu Laptop)
Una vez que pruebes que tus cambios funcionan bien localmente:
```bash
git add .
git commit -m "feat: descripción de las mejoras aplicadas"
git push origin main
```

### 2. Descargar la Actualización (En la PC del Local)
1. Abre **Git Bash** en la carpeta del proyecto (`C:\xampp\htdocs\sistema-restaurante`).
2. Descarga la última versión de tu código:
   ```bash
   git pull origin main
   ```

### 3. Actualizar la Base de Datos de Forma Segura
Si tus cambios incluyen nuevas tablas o columnas en la base de datos, ejecuta:
```bash
php artisan migrate
```
> [!CAUTION]
> **¡ADVERTENCIA DE SEGURIDAD!** 
> Para actualizar, **NUNCA** utilices `php artisan migrate:fresh` ni restaures respaldos viejos sobre la base de datos del local. 
> El comando `php artisan migrate` es totalmente seguro: modificará e insertará las nuevas tablas y columnas sin alterar ni borrar las ventas, platos y reportes existentes. `migrate:fresh` destruirá toda la información del cliente.

### 4. Recompilar Diseños y Dependencias (Si es necesario)
Si instalaste nuevas librerías PHP/JS o modificaste código visual en archivos Blade o CSS:
```bash
# Si hay nuevas librerías de PHP
composer install --no-dev --optimize-autoloader

# Si hay nuevas dependencias de JS o cambios visuales
npm install
npm run build
```

### 5. Limpieza de Caché de Laravel
Para obligar a Laravel a leer los nuevos archivos de configuración y vistas inmediatamente, borrando la memoria caché anterior:
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

---

## 🚀 Puesta en Marcha

Para iniciar el sistema en el local del cliente, simplemente haz doble clic sobre el archivo `iniciar_sistema.vbs`. Este script levantará el servidor de Laravel en segundo plano (`php artisan serve --host 0.0.0.0`) y abrirá automáticamente el navegador en la IP local configurada para que los meseros puedan ingresar desde sus teléfonos.

Para detener el servidor, haz doble clic sobre el archivo `detener_sistema.bat`.

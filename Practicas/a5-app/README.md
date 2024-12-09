
# **Documentación del Proyecto de Gestión de Archivos**

## **Descripción General**
Este proyecto es una aplicación web desarrollada en Laravel que permite la gestión y compartición de archivos entre usuarios. Las funcionalidades incluyen subir archivos, compartirlos, eliminarlos, restaurarlos y votar por ellos (likes y dislikes). La aplicación cuenta con vistas categorizadas, un sistema de roles de usuario y un panel de administración.

---

## **Características Principales**
1. **Gestión de Archivos**:
   - Subida de archivos con opción de privacidad (público o privado).
   - Descarga de archivos disponibles.
   - Eliminación temporal (soft delete) y restauración de archivos.
   - Compartición de archivos entre usuarios autenticados.

2. **Sistema de Votación**:
   - Votación positiva ("like") o negativa ("dislike") en archivos públicos.
   - Control de votos únicos por usuario por archivo.

3. **Sistema de Roles**:
   - Roles de usuario (usuario estándar y administrador).
   - Acceso limitado a funcionalidades administrativas según el rol.

4. **Interfaz Categorizada**:
   - Navegación por "Archivos de la comunidad", "Mis archivos", "Archivos compartidos" y "Archivos eliminados".
   - Búsqueda de archivos por nombre.

5. **Panel de Administración**:
   - Gestión de usuarios y archivos (crear, editar, eliminar).

---

## **Rutas Disponibles**
### **Rutas Públicas**
- **`GET /`**: Página principal con lista de archivos públicos.
- **`GET /login`**: Formulario de inicio de sesión.
- **`POST /login`**: Procesa credenciales de inicio de sesión.
- **`GET /register`**: Formulario de registro de usuario.
- **`POST /register`**: Registra un nuevo usuario.

### **Rutas Protegidas (Autenticación Requerida)**
#### **Gestión de Archivos**
- **`POST /upload`**: Subida de archivos.
- **`GET /download/{file}`**: Descarga un archivo.
- **`GET /delete/{file}`**: Elimina temporalmente un archivo.
- **`GET /restore/{id}`**: Restaura un archivo eliminado.
- **`GET /force-delete/{id}`**: Elimina permanentemente un archivo.

#### **Votación en Archivos**
- **`POST /fichero/{id}/like`**: Añade un voto positivo.
- **`POST /fichero/{id}/dislike`**: Añade un voto negativo.

#### **Archivos Compartidos**
- **`POST /compartir/{id}`**: Comparte un archivo.
- **`DELETE /eliminar-compartido/{id}`**: Elimina un archivo compartido.

### **Rutas Administrativas**
- **`GET /admin/dashboard`**: Acceso al panel administrativo.
- **`DELETE /admin/file/{id}`**: Elimina un archivo de forma permanente.
- **`DELETE /admin/user/{id}`**: Elimina un usuario.
- **`PUT /admin/user/{id}`**: Actualiza los datos de un usuario.

---

## **Vistas y Funcionalidades**

### **Vista: Welcome**
- Lista de archivos públicos (archivos de la comunidad).
- Botones para descargar, votar y eliminar.
- Fila adicional para mostrar votos positivos y negativos.

### **Vista: Mis Archivos**
- Archivos subidos por el usuario autenticado.
- Funcionalidades: Descargar, eliminar y compartir.

### **Vista: Archivos Compartidos**
- Archivos que han sido compartidos con el usuario.
- Funcionalidades: Descargar y eliminar compartición.

### **Vista: Archivos Eliminados**
- Archivos eliminados temporalmente.
- Funcionalidades: Restaurar y eliminar permanentemente.

### **Panel Administrativo**
- Gestión completa de usuarios y archivos.
- Acceso limitado a administradores.

---

## **Base de Datos**
### **Estructura de Tablas**
#### **Tabla `users`**
- `id`: Identificador único.
- `name`: Nombre del usuario.
- `email`: Correo electrónico.
- `password`: Contraseña (encriptada).
- `role_id`: Rol del usuario.
- `timestamps`: Fechas de creación y actualización.

#### **Tabla `ficheros`**
- `id`: Identificador único.
- `name`: Nombre del archivo.
- `path`: Ruta del archivo.
- `user_id`: Propietario.
- `privado`: Visibilidad (0 = público, 1 = privado).
- `timestamps`: Fechas de creación y actualización.

#### **Tabla `votes`**
- `id`: Identificador único.
- `fichero_id`: Relación con un archivo.
- `user_id`: Relación con un usuario.
- `like`: Tipo de voto (1 = like, 0 = dislike).
- `timestamps`: Fechas de creación y actualización.

#### **Tabla `shared_files`**
- `id`: Identificador único.
- `fichero_id`: Relación con un archivo compartido.
- `user_id`: Usuario con quien se compartió.
- `timestamps`: Fechas de creación y actualización.

---

## **Configuración del Proyecto**

### **Instalación**
1. Clonar el repositorio:
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. Instalar dependencias:
   ```bash
   composer install
   npm install
   ```

3. Configurar `.env`:
   - Configurar base de datos.
   - Establecer claves de encriptación.

4. Migrar la base de datos:
   ```bash
   php artisan migrate
   ```

5. Ejecutar el servidor:
   ```bash
   php artisan serve
   ```

### **Dependencias Principales**
- Laravel Framework
- MySQL
- Blade (Motor de plantillas)
- Bootstrap (Diseño Frontend)
- Docker (Entorno de desarrollo)

---

## **Pruebas y Debugging**
- Ejecutar pruebas:
  ```bash
  php artisan test
  ```
- Ver registros de errores:
  ```bash
  tail -f storage/logs/laravel.log
  ```

---

## **Futuras Mejoras**
1. Implementar notificaciones al compartir archivos.
2. Crear un sistema de comentarios para los archivos.
3. Ampliar el sistema de roles con permisos más específicos.
4. Optimizar el diseño responsivo para dispositivos móviles.

---

## **Autores**
- **Iñaki Borrego Bau** (Desarrollador Principal)
- **Framework Utilizado**: Laravel

---


# Documentación del Proyecto Laravel

Este proyecto Laravel gestiona usuarios, archivos y estadísticas de descargas. Está estructurado con rutas, vistas, migraciones y funcionalidades personalizadas. A continuación, se describe cada componente con detalles técnicos y decisiones de diseño.

---

## **Rutas (`routes/web.php`)**

### 1. `/` (GET)
- **Funcionalidad**: Muestra la página principal (`welcome.blade.php`) con una lista de archivos disponibles, permitiendo buscar por nombre.
- **Tecnologías utilizadas**:
  - **Eloquent ORM**: Para consultar la base de datos de archivos con filtros dinámicos.
  - **Blade Templates**: Renderiza la vista `welcome` con los datos de los archivos.
- **Por qué se eligió**: Eloquent simplifica las consultas y permite un código limpio con su funcionalidad `when()`.

---

### 2. `/login` (GET/POST)
- **GET**:
  - Muestra el formulario de inicio de sesión.
  - Utiliza la vista `login.blade.php`.
- **POST**:
  - Valida las credenciales del usuario y autentica su sesión usando `Auth::attempt`.
- **Tecnologías utilizadas**:
  - **Middleware de autenticación**: Laravel gestiona sesiones y cookies.
  - **Form Request Validation**: Simplifica la validación de credenciales.
- **Por qué se eligió**: Laravel Breeze fue la base inicial para la gestión de usuarios.

---

### 3. `/logout` (GET)
- **Funcionalidad**: Finaliza la sesión del usuario y regenera el token CSRF.
- **Tecnologías utilizadas**:
  - **Auth Facade**: Permite cerrar sesión de forma segura.
- **Por qué se eligió**: Laravel gestiona la seguridad de las sesiones automáticamente.

---

### 4. `/register` (GET/POST)
- **GET**:
  - Muestra el formulario de registro.
- **POST**:
  - Registra un nuevo usuario en la base de datos.
  - Cifra la contraseña con `Hash::make`.
- **Tecnologías utilizadas**:
  - **Eloquent**: Facilita la creación de nuevos usuarios.
  - **Request Validation**: Garantiza la validez de los datos de entrada.
- **Por qué se eligió**: Laravel proporciona herramientas nativas para la gestión de usuarios.

---

### 5. `/subir-archivos` (GET/POST)
- **GET**:
  - Muestra la vista `upload.blade.php` con un formulario para subir archivos.
- **POST**:
  - Sube archivos al directorio `public/storage` y los asocia al usuario autenticado.
  - Sanitiza el nombre del archivo usando expresiones regulares.
- **Tecnologías utilizadas**:
  - **Storage Facade**: Gestiona la subida de archivos al sistema de almacenamiento.
  - **Blade Templates**: Renderiza la vista.
- **Por qué se eligió**: Laravel simplifica la gestión de archivos con el sistema de almacenamiento.

---

### 6. `/download/{file}` (GET)
- **Funcionalidad**:
  - Incrementa el contador de descargas en la tabla `ficheroes`.
  - Registra cada descarga en la tabla `descargas` con la fecha.
  - Descarga el archivo solicitado.
- **Tecnologías utilizadas**:
  - **Eloquent**: Incrementa el contador.
  - **DB Facade**: Inserta datos en la tabla `descargas`.
  - **Storage Facade**: Devuelve el archivo para descarga.
- **Por qué se eligió**: Separar el registro de descargas en otra tabla permite analizar estadísticas fácilmente.

---

### 7. `/delete/{file}` (GET)
- **Funcionalidad**:
  - Elimina un archivo del sistema y su registro en la base de datos.
- **Tecnologías utilizadas**:
  - **Storage Facade**: Borra el archivo físico.
  - **Eloquent**: Elimina el registro asociado en la tabla.
- **Por qué se eligió**: Garantiza que no queden archivos huérfanos en el sistema.

---

### 8. `/archivo/{id}` (GET)
- **Funcionalidad**:
  - Muestra detalles del archivo, como el propietario, tamaño, estadísticas de descargas y un gráfico.
  - Genera un QR para descargar el archivo.
- **Tecnologías utilizadas**:
  - **Chart.js**: Renderiza el gráfico de descargas por día.
  - **QrCode**: Genera el QR dinámicamente.
  - **Eloquent**: Consulta el archivo y las estadísticas asociadas.
- **Por qué se eligió**: Estas herramientas simplifican la presentación de datos de forma visual y atractiva.

---

### 9. `/qr/download/{id}` (GET)
- **Funcionalidad**: Genera un QR para descargar el archivo directamente.
- **Tecnologías utilizadas**:
  - **QrCode Package**: Genera el código QR.
- **Por qué se eligió**: Ofrece una forma rápida de compartir el enlace de descarga.

---

### 10. `/ver-pdf/{id}` (GET)
- **Funcionalidad**:
  - Muestra un archivo PDF incrustado en la vista.
- **Tecnologías utilizadas**:
  - **Storage Facade**: Gestiona la ruta del archivo.
  - **Response Helper**: Devuelve el archivo como respuesta.
- **Por qué se eligió**: Garantiza que el PDF sea visualizado directamente sin necesidad de descargarlo.

---

## **Vistas**
1. **`welcome.blade.php`**: Muestra la lista de archivos con un buscador dinámico.
2. **`login.blade.php`**: Formulario de inicio de sesión.
3. **`register.blade.php`**: Formulario de registro de nuevos usuarios.
4. **`upload.blade.php`**: Formulario para subir archivos.
5. **`archivo.blade.php`**: Vista detallada de un archivo con estadísticas de descargas.

---

## **Migraciones**
1. **`create_users_table`**: Define la tabla de usuarios con campos para nombre, correo y contraseña.
2. **`create_ficheroes_table`**: Define la tabla de archivos, incluyendo campos como nombre, tamaño, ruta y contador de descargas.
3. **`add_privado_to_ficheroes_table`**: Añade un campo para distinguir archivos privados de públicos.
4. **`create_descargas_table`**: Registra cada descarga con el ID del archivo y la fecha.

---

## **Conclusión**
Este proyecto combina funcionalidades avanzadas de Laravel con herramientas adicionales como Chart.js y QrCode para gestionar archivos y estadísticas de forma eficiente y visual. El diseño modular permite futuras extensiones, como filtros avanzados o integración con APIs externas.

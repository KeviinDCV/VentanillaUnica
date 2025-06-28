# UniRadic - Sistema de Ventanilla Única de Radicación

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12.x-red.svg" alt="Laravel Version">
    <img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP Version">
    <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
    <img src="https://img.shields.io/badge/Status-Production-brightgreen.svg" alt="Status">
</p>

## 📋 Descripción

**UniRadic** es un sistema integral de gestión documental diseñado específicamente para instituciones hospitalarias. Permite la radicación, seguimiento y gestión de documentos de entrada, internos y de salida, cumpliendo con los estándares de gestión documental del sector salud en Colombia.

### 🎯 Características Principales

- **Radicación Múltiple**: Gestión de documentos de entrada, internos y salida
- **Gestión de Remitentes**: Base de datos completa de personas naturales y jurídicas
- **TRD (Tabla de Retención Documental)**: Clasificación documental según normativas
- **Control de Tiempos**: Seguimiento de fechas límite y documentos vencidos
- **Roles y Permisos**: Sistema de autenticación con roles diferenciados
- **Reportes y Estadísticas**: Generación de informes y exportación de datos
- **Interfaz Responsiva**: Diseño adaptable para dispositivos móviles y escritorio

## 🏗️ Arquitectura del Sistema

### Stack Tecnológico

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade Templates + Alpine.js + TailwindCSS
- **Base de Datos**: SQLite (configurable para MySQL/PostgreSQL)
- **Autenticación**: Laravel Breeze
- **Build Tools**: Vite
- **Estilos**: TailwindCSS con componentes personalizados

### Estructura de Módulos

```
app/
├── Http/Controllers/
│   ├── Admin/                 # Controladores de administración
│   ├── Auth/                  # Autenticación
│   ├── DashboardController    # Panel principal
│   ├── GestionController      # Gestión de documentos
│   ├── RadicacionController   # Radicación principal
│   ├── RadicacionEntradaController
│   ├── RadicacionInternaController
│   ├── RadicacionSalidaController
│   └── SistemaController      # Configuración del sistema
├── Models/
│   ├── Radicado              # Modelo principal de documentos
│   ├── Remitente             # Gestión de remitentes
│   ├── Dependencia           # Unidades organizacionales
│   ├── Trd                   # Tabla de Retención Documental
│   ├── Ciudad/Departamento   # Ubicaciones geográficas
│   └── User                  # Usuarios del sistema
└── Middleware/
    ├── CheckRole             # Control de roles
    ├── SessionTimeout        # Timeout de sesiones
    └── SuspenderSistema      # Suspensión del sistema
```

## 🚀 Instalación

### Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js 18+ y npm
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <repository-url> uniradic
   cd uniradic
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Node.js**
   ```bash
   npm install
   ```

4. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar base de datos**
   ```bash
   # Para SQLite (por defecto)
   touch database/database.sqlite
   
   # O configurar MySQL/PostgreSQL en .env
   ```

6. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

7. **Ejecutar seeders (opcional)**
   ```bash
   php artisan db:seed
   ```

8. **Compilar assets**
   ```bash
   npm run build
   ```

9. **Iniciar servidor de desarrollo**
   ```bash
   php artisan serve
   ```

## ⚙️ Configuración

### Variables de Entorno Principales

```env
# Aplicación
APP_NAME="UniRadic"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost
APP_TIMEZONE=America/Bogota

# Base de Datos
DB_CONNECTION=sqlite
# DB_DATABASE=/path/to/database.sqlite

# Sesiones
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database
```

### Configuración de Roles

El sistema maneja dos roles principales:

- **Administrador**: Acceso completo al sistema
- **Ventanilla**: Acceso limitado a radicación y consulta

## 📊 Funcionalidades del Sistema

### 1. Radicación de Documentos

#### Documentos de Entrada
- Registro de documentos externos dirigidos a la institución
- Asignación automática de número de radicado
- Gestión de remitentes (personas naturales/jurídicas)
- Control de fechas límite de respuesta

#### Documentos Internos
- Comunicaciones entre dependencias internas
- Referenciación a radicados existentes
- Seguimiento de trámites internos

#### Documentos de Salida
- Respuestas y comunicaciones oficiales
- Vinculación con radicados de entrada
- Control de destinatarios

### 2. Gestión de Remitentes

```php
// Ejemplo de estructura de remitente
$remitente = [
    'tipo_persona' => 'natural|juridica',
    'tipo_documento' => 'CC|CE|NIT|PP',
    'numero_documento' => '12345678',
    'nombres' => 'Juan Carlos',
    'apellidos' => 'Pérez García',
    'telefono' => '3001234567',
    'email' => 'juan.perez@email.com',
    'direccion' => 'Calle 123 #45-67',
    'ciudad_id' => 1,
    'departamento_id' => 1
];
```

### 3. TRD (Tabla de Retención Documental)

Sistema de clasificación documental que incluye:
- Unidades administrativas
- Series documentales
- Subseries documentales
- Tiempos de retención
- Disposición final

### 4. Sistema de Consultas

Filtros avanzados por:
- Número de radicado
- Fechas (desde/hasta)
- Estado del documento
- Dependencia destino
- Tipo de documento
- Remitente
- Documentos vencidos

### 5. Reportes y Estadísticas

- Exportación a CSV
- Estadísticas en tiempo real
- Indicadores de gestión
- Reportes por dependencia
- Control de vencimientos

## 🔐 Seguridad

### Medidas Implementadas

- **Autenticación robusta** con Laravel Breeze
- **Control de sesiones** con timeout automático
- **Protección CSRF** en todos los formularios
- **Rate limiting** en intentos de login
- **Headers de seguridad** personalizados
- **Validación de entrada** en todos los endpoints
- **Logs de auditoría** para acciones críticas

### Middleware de Seguridad

```php
// Middleware aplicados globalmente
'web' => [
    SecurityHeaders::class,
    SuspenderSistema::class,
],

// Middleware para rutas autenticadas
'auth' => [
    SessionTimeout::class,
],
```

## 📱 Interfaz de Usuario

### Características de la UI

- **Diseño responsivo** con TailwindCSS
- **Navegación intuitiva** con sidebar colapsable
- **Componentes reutilizables** con Alpine.js
- **Feedback visual** para acciones del usuario
- **Modo oscuro** (opcional)
- **Accesibilidad** mejorada

### Componentes Principales

- Dashboard con métricas en tiempo real
- Formularios modales para radicación
- Tablas con paginación y filtros
- Sistema de notificaciones
- Breadcrumbs de navegación

## 🔧 Desarrollo

### Comandos Útiles

```bash
# Desarrollo con hot reload
composer run dev

# Ejecutar tests
composer run test

# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Generar documentación de API
php artisan route:list
```

### Estructura de Base de Datos

#### Tablas Principales

- `radicados`: Registro principal de documentos
- `remitentes`: Base de datos de remitentes
- `dependencias`: Unidades organizacionales
- `trd`: Tabla de Retención Documental
- `documentos`: Archivos adjuntos
- `users`: Usuarios del sistema

#### Relaciones Clave

```sql
radicados
├── remitente_id → remitentes.id
├── trd_id → trd.id
├── dependencia_destino_id → dependencias.id
├── dependencia_origen_id → dependencias.id
└── usuario_radica_id → users.id
```

## 📈 Monitoreo y Mantenimiento

### Logs del Sistema

```bash
# Ubicación de logs
storage/logs/laravel.log

# Monitoreo en tiempo real
php artisan pail
```

### Backup y Restauración

```bash
# Backup de base de datos
php artisan backup:run

# Restauración
php artisan migrate:fresh --seed
```

### Optimización

```bash
# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

## 🤝 Contribución

### Estándares de Código

- **PSR-12** para PHP
- **ESLint** para JavaScript
- **Prettier** para formateo
- **PHPStan** para análisis estático

### Flujo de Trabajo

1. Fork del repositorio
2. Crear rama feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit de cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear Pull Request

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 🆘 Soporte


### Contacto

- **Desarrollador**: Kevin David Ch E
- **Institución**: E.S.E Hospital San Agustín Puerto Merizalde
- **Email**: Keviindavid00@gmail.com

---

<p align="center">
    Desarrollado con ❤️ para el sector salud colombiano
</p>
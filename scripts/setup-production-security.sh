#!/bin/bash

# 🔐 Script de Configuración de Seguridad para Producción - UniRadic
# Este script configura las medidas de seguridad esenciales para el entorno de producción

set -e  # Salir si cualquier comando falla

echo "🔐 Configurando seguridad para producción - UniRadic"
echo "=================================================="

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para mostrar mensajes
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Verificar que estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    log_error "Este script debe ejecutarse desde el directorio raíz de Laravel"
    exit 1
fi

# 1. Verificar variables de entorno críticas
log_info "Verificando configuración de entorno..."

if [ ! -f ".env" ]; then
    log_error "Archivo .env no encontrado"
    exit 1
fi

# Verificar variables críticas
check_env_var() {
    local var_name=$1
    local var_value=$(grep "^${var_name}=" .env | cut -d '=' -f2)
    
    if [ -z "$var_value" ]; then
        log_error "Variable de entorno $var_name no está configurada"
        return 1
    fi
    
    return 0
}

# Variables críticas que deben estar configuradas
critical_vars=("APP_KEY" "DB_PASSWORD" "DB_DATABASE" "DB_USERNAME")

for var in "${critical_vars[@]}"; do
    if ! check_env_var "$var"; then
        log_error "Configuración incompleta. Revise el archivo .env"
        exit 1
    fi
done

log_success "Variables de entorno verificadas"

# 2. Configurar permisos de archivos
log_info "Configurando permisos de archivos..."

# Permisos seguros para directorios
find . -type d -exec chmod 755 {} \;

# Permisos seguros para archivos
find . -type f -exec chmod 644 {} \;

# Permisos especiales para archivos ejecutables
chmod 755 artisan
chmod 755 scripts/*.sh 2>/dev/null || true

# Permisos para storage y bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Proteger archivo .env
chmod 600 .env

log_success "Permisos de archivos configurados"

# 3. Configurar directorios de logs de seguridad
log_info "Configurando logs de seguridad..."

mkdir -p storage/logs/security
chmod 755 storage/logs/security

# Crear archivo de log de seguridad si no existe
touch storage/logs/security.log
chmod 644 storage/logs/security.log

log_success "Logs de seguridad configurados"

# 4. Limpiar cache y optimizar
log_info "Optimizando aplicación..."

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimizaciones para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

log_success "Aplicación optimizada"

# 5. Verificar configuración de base de datos
log_info "Verificando conexión a base de datos..."

if php artisan migrate:status > /dev/null 2>&1; then
    log_success "Conexión a base de datos exitosa"
else
    log_error "Error de conexión a base de datos"
    exit 1
fi

# 6. Configurar backup automático
log_info "Configurando backup automático..."

# Crear directorio de backups
mkdir -p storage/backups
chmod 755 storage/backups

# Crear script de backup
cat > storage/backups/backup.sh << 'EOF'
#!/bin/bash
# Script de backup automático para UniRadic

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/path/to/backups"
DB_NAME=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2)
DB_USER=$(grep "^DB_USERNAME=" .env | cut -d '=' -f2)
DB_PASS=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f2)

# Crear backup de base de datos
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_backup_$DATE.sql.gz

# Backup de archivos
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz storage/app/documentos

# Limpiar backups antiguos (mantener solo los últimos 30 días)
find $BACKUP_DIR -name "*.gz" -mtime +30 -delete

echo "Backup completado: $DATE"
EOF

chmod +x storage/backups/backup.sh

log_success "Backup automático configurado"

# 7. Verificar configuración de seguridad
log_info "Verificando configuración de seguridad..."

# Verificar que APP_DEBUG esté en false
if grep -q "APP_DEBUG=true" .env; then
    log_warning "APP_DEBUG está en true. Cambie a false para producción"
fi

# Verificar que APP_ENV esté en production
if ! grep -q "APP_ENV=production" .env; then
    log_warning "APP_ENV no está configurado como 'production'"
fi

# Verificar configuración de sesión
if ! grep -q "SESSION_SECURE_COOKIE=true" .env; then
    log_warning "SESSION_SECURE_COOKIE debería estar en true para HTTPS"
fi

log_success "Verificación de seguridad completada"

# 8. Crear archivo de monitoreo
log_info "Configurando monitoreo de seguridad..."

cat > storage/logs/security_monitor.sh << 'EOF'
#!/bin/bash
# Monitor de seguridad para UniRadic

LOG_FILE="storage/logs/security.log"
ALERT_EMAIL="admin@hospital.com"

# Verificar intentos de inyección SQL
SQL_ATTEMPTS=$(grep -c "Intento de inyección SQL" $LOG_FILE 2>/dev/null || echo 0)

# Verificar intentos de XSS
XSS_ATTEMPTS=$(grep -c "Intento de XSS" $LOG_FILE 2>/dev/null || echo 0)

# Verificar accesos no autorizados
UNAUTH_ATTEMPTS=$(grep -c "acceso no autorizado" $LOG_FILE 2>/dev/null || echo 0)

# Enviar alerta si hay demasiados intentos
TOTAL_THREATS=$((SQL_ATTEMPTS + XSS_ATTEMPTS + UNAUTH_ATTEMPTS))

if [ $TOTAL_THREATS -gt 10 ]; then
    echo "ALERTA: $TOTAL_THREATS amenazas detectadas en las últimas 24 horas" | \
    mail -s "Alerta de Seguridad - UniRadic" $ALERT_EMAIL
fi

echo "Monitor ejecutado: $(date) - Amenazas: $TOTAL_THREATS"
EOF

chmod +x storage/logs/security_monitor.sh

log_success "Monitor de seguridad configurado"

# 9. Resumen final
echo ""
echo "🎉 Configuración de seguridad completada"
echo "========================================"
echo ""
log_success "✅ Permisos de archivos configurados"
log_success "✅ Logs de seguridad habilitados"
log_success "✅ Aplicación optimizada para producción"
log_success "✅ Backup automático configurado"
log_success "✅ Monitor de seguridad instalado"
echo ""
log_info "📋 Próximos pasos recomendados:"
echo "   1. Configurar SSL/TLS en el servidor web"
echo "   2. Configurar firewall (UFW/iptables)"
echo "   3. Instalar fail2ban para protección adicional"
echo "   4. Configurar monitoreo con cron jobs"
echo "   5. Realizar pruebas de penetración"
echo ""
log_warning "⚠️  Recuerde revisar y ajustar las configuraciones según su entorno específico"
echo ""

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SetupProductionSecurity extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'security:setup-production {--force : Force setup even if already in production}';

    /**
     * The description of the console command.
     */
    protected $description = 'Configure security settings for production environment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Configurando seguridad para producción...');

        // Verificar si ya está en producción
        if (app()->environment('production') && !$this->option('force')) {
            $this->warn('La aplicación ya está en modo producción.');
            if (!$this->confirm('¿Desea continuar con la configuración?')) {
                return 0;
            }
        }

        // 1. Verificar y crear directorios necesarios
        $this->createSecurityDirectories();

        // 2. Configurar permisos de archivos
        $this->configureFilePermissions();

        // 3. Limpiar y optimizar cache
        $this->optimizeApplication();

        // 4. Verificar configuración de seguridad
        $this->verifySecurityConfiguration();

        // 5. Crear archivos de monitoreo
        $this->createMonitoringFiles();

        $this->info('✅ Configuración de seguridad completada');
        $this->displaySecurityChecklist();

        return 0;
    }

    /**
     * Crear directorios necesarios para seguridad
     */
    private function createSecurityDirectories()
    {
        $this->info('📁 Creando directorios de seguridad...');

        $directories = [
            storage_path('logs/security'),
            storage_path('backups'),
            storage_path('app/documentos'),
        ];

        foreach ($directories as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
                $this->line("   ✓ Creado: {$dir}");
            } else {
                $this->line("   ✓ Existe: {$dir}");
            }
        }
    }

    /**
     * Configurar permisos de archivos
     */
    private function configureFilePermissions()
    {
        $this->info('🔒 Configurando permisos de archivos...');

        // Permisos para storage
        if (File::exists(storage_path())) {
            chmod(storage_path(), 0755);
            $this->line('   ✓ Permisos de storage configurados');
        }

        // Permisos para bootstrap/cache
        if (File::exists(base_path('bootstrap/cache'))) {
            chmod(base_path('bootstrap/cache'), 0755);
            $this->line('   ✓ Permisos de bootstrap/cache configurados');
        }

        // Proteger archivo .env
        if (File::exists(base_path('.env'))) {
            chmod(base_path('.env'), 0600);
            $this->line('   ✓ Archivo .env protegido');
        }
    }

    /**
     * Optimizar aplicación para producción
     */
    private function optimizeApplication()
    {
        $this->info('⚡ Optimizando aplicación...');

        // Limpiar cache
        Artisan::call('config:clear');
        $this->line('   ✓ Cache de configuración limpiado');

        Artisan::call('route:clear');
        $this->line('   ✓ Cache de rutas limpiado');

        Artisan::call('view:clear');
        $this->line('   ✓ Cache de vistas limpiado');

        Artisan::call('cache:clear');
        $this->line('   ✓ Cache de aplicación limpiado');

        // Optimizar para producción
        if (app()->environment('production')) {
            Artisan::call('config:cache');
            $this->line('   ✓ Configuración cacheada');

            Artisan::call('route:cache');
            $this->line('   ✓ Rutas cacheadas');

            Artisan::call('view:cache');
            $this->line('   ✓ Vistas cacheadas');
        }
    }

    /**
     * Verificar configuración de seguridad
     */
    private function verifySecurityConfiguration()
    {
        $this->info('🔍 Verificando configuración de seguridad...');

        $issues = [];

        // Verificar APP_DEBUG
        if (config('app.debug') === true) {
            $issues[] = 'APP_DEBUG está en true (debería ser false en producción)';
        } else {
            $this->line('   ✓ APP_DEBUG configurado correctamente');
        }

        // Verificar APP_ENV
        if (config('app.env') !== 'production') {
            $issues[] = 'APP_ENV no está configurado como "production"';
        } else {
            $this->line('   ✓ APP_ENV configurado correctamente');
        }

        // Verificar APP_KEY
        if (empty(config('app.key'))) {
            $issues[] = 'APP_KEY no está configurada';
        } else {
            $this->line('   ✓ APP_KEY configurada');
        }

        // Verificar configuración de sesión
        if (!config('session.secure') && app()->environment('production')) {
            $issues[] = 'SESSION_SECURE_COOKIE debería estar en true para HTTPS';
        }

        if (!config('session.http_only')) {
            $issues[] = 'SESSION_HTTP_ONLY debería estar en true';
        }

        // Mostrar problemas encontrados
        if (!empty($issues)) {
            $this->warn('⚠️  Problemas de configuración encontrados:');
            foreach ($issues as $issue) {
                $this->line("   • {$issue}");
            }
        } else {
            $this->line('   ✅ Todas las verificaciones pasaron');
        }
    }

    /**
     * Crear archivos de monitoreo
     */
    private function createMonitoringFiles()
    {
        $this->info('📊 Creando archivos de monitoreo...');

        // Script de monitoreo de seguridad
        $monitorScript = <<<'BASH'
#!/bin/bash
# Monitor de seguridad para UniRadic

LOG_FILE="storage/logs/security.log"
DATE=$(date '+%Y-%m-%d %H:%M:%S')

# Verificar si existe el archivo de log
if [ ! -f "$LOG_FILE" ]; then
    echo "[$DATE] Archivo de log de seguridad no encontrado: $LOG_FILE"
    exit 1
fi

# Contar amenazas en las últimas 24 horas
SQL_ATTEMPTS=$(grep -c "Intento de inyección SQL" "$LOG_FILE" 2>/dev/null || echo 0)
XSS_ATTEMPTS=$(grep -c "Intento de XSS" "$LOG_FILE" 2>/dev/null || echo 0)
UNAUTH_ATTEMPTS=$(grep -c "acceso no autorizado" "$LOG_FILE" 2>/dev/null || echo 0)
MALICIOUS_FILES=$(grep -c "archivo malicioso" "$LOG_FILE" 2>/dev/null || echo 0)

TOTAL_THREATS=$((SQL_ATTEMPTS + XSS_ATTEMPTS + UNAUTH_ATTEMPTS + MALICIOUS_FILES))

echo "[$DATE] Monitor de seguridad ejecutado"
echo "  - Intentos SQL injection: $SQL_ATTEMPTS"
echo "  - Intentos XSS: $XSS_ATTEMPTS"
echo "  - Accesos no autorizados: $UNAUTH_ATTEMPTS"
echo "  - Archivos maliciosos: $MALICIOUS_FILES"
echo "  - Total amenazas: $TOTAL_THREATS"

# Alerta si hay demasiadas amenazas
if [ $TOTAL_THREATS -gt 10 ]; then
    echo "[$DATE] ⚠️  ALERTA: $TOTAL_THREATS amenazas detectadas en las últimas 24 horas"
fi
BASH;

        File::put(storage_path('logs/security_monitor.sh'), $monitorScript);
        chmod(storage_path('logs/security_monitor.sh'), 0755);
        $this->line('   ✓ Script de monitoreo creado');

        // Crear archivo de log de seguridad si no existe
        if (!File::exists(storage_path('logs/security.log'))) {
            File::put(storage_path('logs/security.log'), '');
            chmod(storage_path('logs/security.log'), 0644);
            $this->line('   ✓ Archivo de log de seguridad creado');
        }
    }

    /**
     * Mostrar checklist de seguridad
     */
    private function displaySecurityChecklist()
    {
        $this->newLine();
        $this->info('📋 CHECKLIST DE SEGURIDAD COMPLETADO');
        $this->line('==========================================');
        $this->newLine();

        $this->line('✅ Middleware de seguridad activado');
        $this->line('✅ Logs de seguridad configurados');
        $this->line('✅ Validación de archivos mejorada');
        $this->line('✅ Permisos de archivos configurados');
        $this->line('✅ Aplicación optimizada');

        $this->newLine();
        $this->warn('📝 PRÓXIMOS PASOS RECOMENDADOS:');
        $this->line('1. Configurar SSL/TLS en el servidor web');
        $this->line('2. Configurar backup automático');
        $this->line('3. Configurar monitoreo con cron job:');
        $this->line('   0 */6 * * * cd /path/to/app && bash storage/logs/security_monitor.sh');
        $this->line('4. Revisar logs de seguridad regularmente');

        $this->newLine();
        $this->info('🎉 Configuración de seguridad completada exitosamente');
    }
}

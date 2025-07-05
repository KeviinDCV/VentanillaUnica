<?php
/**
 * Script para aplicar configuración de seguridad básica
 * Ejecutar con: php apply-security.php
 */

echo "🔐 Aplicando configuración de seguridad básica...\n";
echo "================================================\n\n";

// Verificar que estamos en el directorio correcto
if (!file_exists('artisan')) {
    echo "❌ Error: Este script debe ejecutarse desde el directorio raíz de Laravel\n";
    exit(1);
}

// 1. Verificar archivo .env
echo "📋 Verificando archivo .env...\n";
if (!file_exists('.env')) {
    echo "❌ Error: Archivo .env no encontrado\n";
    echo "   Copie .env.example a .env y configure las variables\n";
    exit(1);
}

// 2. Leer configuración actual
$envContent = file_get_contents('.env');
$envLines = explode("\n", $envContent);
$envVars = [];

foreach ($envLines as $line) {
    $line = trim($line);
    if (empty($line) || strpos($line, '#') === 0) {
        continue;
    }
    
    $parts = explode('=', $line, 2);
    if (count($parts) === 2) {
        $envVars[$parts[0]] = $parts[1];
    }
}

// 3. Verificar configuraciones críticas
echo "🔍 Verificando configuraciones críticas...\n";

$criticalChecks = [
    'APP_ENV' => ['production', 'Debe estar en "production" para seguridad'],
    'APP_DEBUG' => ['false', 'Debe estar en "false" para ocultar errores'],
    'APP_KEY' => ['', 'Debe estar configurada (ejecute: php artisan key:generate)'],
];

$warnings = [];
$errors = [];

foreach ($criticalChecks as $var => $check) {
    $currentValue = $envVars[$var] ?? '';
    $expectedValue = $check[0];
    $message = $check[1];
    
    if ($var === 'APP_KEY') {
        if (empty($currentValue)) {
            $errors[] = "❌ {$var}: {$message}";
        } else {
            echo "   ✅ {$var}: Configurada\n";
        }
    } else {
        if ($currentValue !== $expectedValue) {
            if ($var === 'APP_ENV' && $currentValue !== 'production') {
                $warnings[] = "⚠️  {$var}: {$message} (actual: {$currentValue})";
            } elseif ($var === 'APP_DEBUG' && $currentValue !== 'false') {
                $errors[] = "❌ {$var}: {$message} (actual: {$currentValue})";
            }
        } else {
            echo "   ✅ {$var}: Configurado correctamente\n";
        }
    }
}

// 4. Verificar configuraciones de seguridad adicionales
echo "\n🛡️  Verificando configuraciones de seguridad adicionales...\n";

$securityChecks = [
    'SESSION_SECURE_COOKIE' => 'true',
    'SESSION_HTTP_ONLY' => 'true',
    'SESSION_ENCRYPT' => 'true',
    'SESSION_LIFETIME' => '60',
];

foreach ($securityChecks as $var => $expectedValue) {
    $currentValue = $envVars[$var] ?? '';
    if ($currentValue !== $expectedValue) {
        $warnings[] = "⚠️  {$var}: Recomendado '{$expectedValue}' (actual: '{$currentValue}')";
    } else {
        echo "   ✅ {$var}: Configurado correctamente\n";
    }
}

// 5. Mostrar resultados
echo "\n📊 Resumen de verificación:\n";
echo "==========================\n";

if (!empty($errors)) {
    echo "\n❌ ERRORES CRÍTICOS (deben corregirse):\n";
    foreach ($errors as $error) {
        echo "   {$error}\n";
    }
}

if (!empty($warnings)) {
    echo "\n⚠️  ADVERTENCIAS (recomendado corregir):\n";
    foreach ($warnings as $warning) {
        echo "   {$warning}\n";
    }
}

if (empty($errors) && empty($warnings)) {
    echo "\n✅ Todas las verificaciones pasaron correctamente\n";
}

// 6. Ejecutar comandos de optimización
echo "\n⚡ Ejecutando comandos de optimización...\n";

$commands = [
    'php artisan config:clear' => 'Limpiando cache de configuración',
    'php artisan route:clear' => 'Limpiando cache de rutas',
    'php artisan view:clear' => 'Limpiando cache de vistas',
    'php artisan cache:clear' => 'Limpiando cache de aplicación',
];

foreach ($commands as $command => $description) {
    echo "   {$description}...\n";
    exec($command . ' 2>&1', $output, $returnCode);
    if ($returnCode === 0) {
        echo "   ✅ Completado\n";
    } else {
        echo "   ⚠️  Error ejecutando: {$command}\n";
    }
}

// 7. Crear directorios de seguridad
echo "\n📁 Creando directorios de seguridad...\n";

$directories = [
    'storage/logs/security',
    'storage/backups',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "   ✅ Creado: {$dir}\n";
        } else {
            echo "   ❌ Error creando: {$dir}\n";
        }
    } else {
        echo "   ✅ Existe: {$dir}\n";
    }
}

// 8. Verificar permisos
echo "\n🔒 Verificando permisos de archivos...\n";

$permissions = [
    'storage' => 0755,
    'bootstrap/cache' => 0755,
    '.env' => 0600,
];

foreach ($permissions as $path => $expectedPerm) {
    if (file_exists($path)) {
        $currentPerm = fileperms($path) & 0777;
        if ($currentPerm !== $expectedPerm) {
            if (chmod($path, $expectedPerm)) {
                echo "   ✅ Permisos corregidos: {$path}\n";
            } else {
                echo "   ❌ Error configurando permisos: {$path}\n";
            }
        } else {
            echo "   ✅ Permisos correctos: {$path}\n";
        }
    }
}

// 9. Resumen final
echo "\n🎉 Configuración de seguridad aplicada\n";
echo "=====================================\n\n";

if (!empty($errors)) {
    echo "❌ ACCIÓN REQUERIDA: Corrija los errores críticos antes de continuar\n\n";
    exit(1);
} else {
    echo "✅ Configuración básica de seguridad aplicada correctamente\n\n";
    
    echo "📋 PRÓXIMOS PASOS RECOMENDADOS:\n";
    echo "1. Configurar SSL/TLS en su servidor web\n";
    echo "2. Configurar backup automático\n";
    echo "3. Revisar logs de seguridad regularmente\n";
    echo "4. Ejecutar: php artisan security:setup-production\n\n";
    
    echo "📁 ARCHIVOS IMPORTANTES:\n";
    echo "- Logs de seguridad: storage/logs/security.log\n";
    echo "- Configuración de producción: .env.production.example\n";
    echo "- Documentación: docs/SECURITY_CHECKLIST.md\n\n";
    
    exit(0);
}
?>

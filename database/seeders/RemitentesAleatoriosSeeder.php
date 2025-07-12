<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Remitente;
use App\Models\Departamento;
use App\Models\Ciudad;
use Faker\Factory as Faker;

class RemitentesAleatoriosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando creación de remitentes aleatorios...');

        // Verificar que existan departamentos y ciudades
        $departamentos = Departamento::activo()->with('ciudades')->get();
        
        if ($departamentos->isEmpty()) {
            $this->command->error('❌ No hay departamentos disponibles. Ejecuta DepartamentoCiudadSeeder primero.');
            return;
        }

        $this->command->info("📊 Departamentos disponibles: {$departamentos->count()}");
        $totalCiudades = $departamentos->sum(function($depto) {
            return $depto->ciudades->count();
        });
        $this->command->info("📊 Ciudades disponibles: {$totalCiudades}");

        // Configurar Faker en español
        $faker = Faker::create('es_ES');

        // Definir todas las posibles combinaciones
        $tiposDocumento = ['CC', 'CE', 'TI', 'PP', 'NIT', 'OTRO'];
        
        // Nombres realistas colombianos
        $nombres = [
            'Juan Carlos', 'María Elena', 'Carlos Alberto', 'Ana Sofía', 'Luis Fernando', 'Carmen Rosa',
            'José Miguel', 'Claudia Patricia', 'Roberto Antonio', 'Luz Marina', 'Diego Alejandro', 'Sandra Milena',
            'Andrés Felipe', 'Paola Andrea', 'Ricardo Enrique', 'Gloria Esperanza', 'Fernando José', 'Mónica Alejandra',
            'Gustavo Adolfo', 'Liliana Patricia', 'Héctor Fabián', 'Yolanda María', 'Óscar Iván', 'Beatriz Elena',
            'Jairo Alberto', 'Esperanza del Carmen', 'Álvaro Hernán', 'Rosa María', 'Édgar Mauricio', 'Amparo',
            'Germán Darío', 'Stella Maris', 'Rubén Darío', 'Marta Lucía', 'Javier Orlando', 'Nubia Esperanza',
            'Hernando', 'Blanca Cecilia', 'Arturo', 'Gladys', 'Emilio', 'Consuelo', 'Ramiro', 'Olga Lucía'
        ];

        $apellidos = [
            'García', 'Rodríguez', 'González', 'Hernández', 'López', 'Martínez', 'Pérez', 'Sánchez',
            'Ramírez', 'Torres', 'Flores', 'Rivera', 'Gómez', 'Díaz', 'Cruz', 'Morales',
            'Ortiz', 'Gutiérrez', 'Chávez', 'Ruiz', 'Jiménez', 'Vargas', 'Castillo', 'Herrera',
            'Mendoza', 'Medina', 'Aguilar', 'Guerrero', 'Rojas', 'Muñoz', 'Delgado', 'Castro',
            'Ortega', 'Rubio', 'Marín', 'Soto', 'Contreras', 'Silva', 'Sepúlveda', 'Esquivel'
        ];

        // Entidades/organizaciones típicas
        $entidades = [
            'Alcaldía Municipal', 'Gobernación Departamental', 'Ministerio de Salud', 'ICBF',
            'Policía Nacional', 'Bomberos Voluntarios', 'Cruz Roja Colombiana', 'Defensoría del Pueblo',
            'Procuraduría General', 'Contraloría General', 'Universidad Nacional', 'SENA',
            'EPS Sanitas', 'EPS Sura', 'EPS Compensar', 'EPS Famisanar', 'Nueva EPS', 'Medimás',
            'Cámara de Comercio', 'DIAN', 'Registraduría Nacional', 'Migración Colombia',
            'Ministerio de Educación', 'Secretaría de Educación', 'Secretaría de Salud', 'INVIMA',
            'DANE', 'IDEAM', 'ANLA', 'Superintendencia de Salud', 'Ministerio del Trabajo',
            'Colpensiones', 'ISS', 'Positiva ARL', 'Sura ARL', 'Colmena ARL',
            null, null, null, null, null // Para que algunos no tengan entidad
        ];

        // Dominios de email comunes en Colombia
        $dominiosEmail = [
            'gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com', 'yahoo.es',
            'une.net.co', 'etb.net.co', 'telecom.com.co', 'cable.net.co'
        ];

        // Prefijos telefónicos de Colombia
        $prefijosCelular = ['300', '301', '302', '303', '304', '305', '310', '311', '312', '313', '314', '315', '316', '317', '318', '319', '320', '321', '322', '323', '324', '350', '351'];

        // Cantidad de remitentes a crear
        $cantidadRemitentes = 100;
        $this->command->info("🎯 Creando {$cantidadRemitentes} remitentes aleatorios...");

        $barra = $this->command->getOutput()->createProgressBar($cantidadRemitentes);
        $barra->start();

        for ($i = 1; $i <= $cantidadRemitentes; $i++) {
            // Seleccionar departamento y ciudad aleatoriamente
            $departamento = $departamentos->random();
            $ciudad = $departamento->ciudades->random();

            // Generar datos aleatorios
            $tipoDocumento = $faker->randomElement($tiposDocumento);
            $numeroDocumento = $this->generarNumeroDocumento($tipoDocumento, $faker);
            
            $nombre = $faker->randomElement($nombres);
            $apellido1 = $faker->randomElement($apellidos);
            $apellido2 = $faker->randomElement($apellidos);
            $nombreCompleto = "{$nombre} {$apellido1} {$apellido2}";

            // Email (70% probabilidad de tener email)
            $email = null;
            if ($faker->boolean(70)) {
                $nombreEmail = strtolower(str_replace(' ', '.', $nombre . '.' . $apellido1));
                $nombreEmail = $this->limpiarTextoParaEmail($nombreEmail);
                $dominio = $faker->randomElement($dominiosEmail);
                $email = "{$nombreEmail}@{$dominio}";
            }

            // Teléfono (80% probabilidad de tener teléfono)
            $telefono = null;
            if ($faker->boolean(80)) {
                $prefijo = $faker->randomElement($prefijosCelular);
                $numero = $faker->numberBetween(1000000, 9999999);
                $telefono = "{$prefijo}{$numero}";
            }

            // Dirección (60% probabilidad de tener dirección)
            $direccion = null;
            if ($faker->boolean(60)) {
                $tipoVia = $faker->randomElement(['Calle', 'Carrera', 'Avenida', 'Diagonal', 'Transversal']);
                $numero1 = $faker->numberBetween(1, 200);
                $numero2 = $faker->numberBetween(1, 99);
                $numero3 = $faker->numberBetween(1, 99);
                $direccion = "{$tipoVia} {$numero1} #{$numero2}-{$numero3}";
                
                // Agregar complemento ocasionalmente
                if ($faker->boolean(30)) {
                    $complementos = ['Apto 101', 'Casa 2', 'Local 3', 'Oficina 205', 'Torre A', 'Bloque B'];
                    $direccion .= ' ' . $faker->randomElement($complementos);
                }
            }

            // Entidad (40% probabilidad de tener entidad)
            $entidad = null;
            if ($faker->boolean(40)) {
                $entidad = $faker->randomElement($entidades);
            }

            // Crear remitente
            Remitente::create([
                'tipo' => 'registrado', // Todos son registrados según los requerimientos
                'tipo_documento' => $tipoDocumento,
                'numero_documento' => $numeroDocumento,
                'nombre_completo' => $nombreCompleto,
                'telefono' => $telefono,
                'email' => $email,
                'direccion' => $direccion,
                'ciudad' => $ciudad->nombre,
                'departamento' => $departamento->nombre,
                'entidad' => $entidad,
                'observaciones' => $faker->boolean(10) ? $faker->sentence() : null,
            ]);

            $barra->advance();
        }

        $barra->finish();
        $this->command->newLine();
        $this->command->info('✅ Remitentes aleatorios creados exitosamente!');
        
        // Mostrar estadísticas
        $this->mostrarEstadisticas();
    }

    /**
     * Generar número de documento según el tipo
     */
    private function generarNumeroDocumento($tipoDocumento, $faker)
    {
        switch ($tipoDocumento) {
            case 'CC':
                return $faker->numberBetween(10000000, 99999999); // 8-9 dígitos
            case 'CE':
                return $faker->numberBetween(100000, 9999999); // 6-7 dígitos
            case 'TI':
                return $faker->numberBetween(1000000000, 9999999999); // 10 dígitos
            case 'PP':
                return strtoupper($faker->bothify('??######')); // 2 letras + 6 números
            case 'NIT':
                return $faker->numberBetween(100000000, 999999999) . '-' . $faker->numberBetween(0, 9); // NIT con dígito verificador
            case 'OTRO':
                return $faker->bothify('###-###-###'); // Formato genérico
            default:
                return $faker->numberBetween(10000000, 99999999);
        }
    }

    /**
     * Limpiar texto para usar en email
     */
    private function limpiarTextoParaEmail($texto)
    {
        // Reemplazar caracteres especiales
        $texto = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $texto);
        // Remover caracteres no válidos para email
        $texto = preg_replace('/[^a-z0-9.]/', '', $texto);
        return $texto;
    }

    /**
     * Mostrar estadísticas de los remitentes creados
     */
    private function mostrarEstadisticas()
    {
        $total = Remitente::count();
        $conEmail = Remitente::whereNotNull('email')->count();
        $conTelefono = Remitente::whereNotNull('telefono')->count();
        $conDireccion = Remitente::whereNotNull('direccion')->count();
        $conEntidad = Remitente::whereNotNull('entidad')->count();

        $this->command->info("\n📈 Estadísticas de remitentes:");
        $this->command->info("   - Total remitentes: {$total}");
        $this->command->info("   - Con email: {$conEmail} (" . round(($conEmail/$total)*100, 1) . "%)");
        $this->command->info("   - Con teléfono: {$conTelefono} (" . round(($conTelefono/$total)*100, 1) . "%)");
        $this->command->info("   - Con dirección: {$conDireccion} (" . round(($conDireccion/$total)*100, 1) . "%)");
        $this->command->info("   - Con entidad: {$conEntidad} (" . round(($conEntidad/$total)*100, 1) . "%)");

        // Estadísticas por tipo de documento
        $this->command->info("\n📋 Por tipo de documento:");
        foreach (['CC', 'CE', 'TI', 'PP', 'NIT', 'OTRO'] as $tipo) {
            $cantidad = Remitente::where('tipo_documento', $tipo)->count();
            $this->command->info("   - {$tipo}: {$cantidad}");
        }
    }
}

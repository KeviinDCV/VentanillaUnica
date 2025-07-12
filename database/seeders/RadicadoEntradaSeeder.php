<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Radicado;
use App\Models\Remitente;
use App\Models\Dependencia;
use App\Models\Subserie;
use App\Models\TipoSolicitud;
use App\Models\User;
use App\Models\Documento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RadicadoEntradaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando creación de radicados de entrada aleatorios...');

        // Obtener datos necesarios
        $dependencias = Dependencia::activas()->get();
        $subseries = Subserie::with(['serie.unidadAdministrativa'])->get();
        $tiposSolicitud = TipoSolicitud::activos()->get();
        $usuarios = User::where('role', 'ventanilla')->get();

        if ($dependencias->isEmpty() || $tiposSolicitud->isEmpty() || $usuarios->isEmpty()) {
            $this->command->error('❌ Faltan datos básicos (dependencias, tipos de solicitud o usuarios ventanilla)');
            return;
        }

        $this->command->info("📊 Datos disponibles:");
        $this->command->info("   - Dependencias: {$dependencias->count()}");
        $this->command->info("   - Subseries: {$subseries->count()}");
        $this->command->info("   - Tipos de solicitud: {$tiposSolicitud->count()}");
        $this->command->info("   - Usuarios ventanilla: {$usuarios->count()}");

        // Crear 20 radicados de entrada con variaciones
        $cantidadRadicados = 20;
        $this->command->info("🎯 Creando {$cantidadRadicados} radicados de entrada...");

        for ($i = 1; $i <= $cantidadRadicados; $i++) {
            $this->crearRadicadoEntrada($i, $dependencias, $subseries, $tiposSolicitud, $usuarios);
        }

        $this->command->info('✅ Radicados de entrada creados exitosamente!');
    }

    private function crearRadicadoEntrada($numero, $dependencias, $subseries, $tiposSolicitud, $usuarios)
    {
        try {
            // 1. Crear remitente (50% anónimo, 50% registrado)
            $tipoRemitente = rand(1, 2) === 1 ? 'anonimo' : 'registrado';
            $remitente = $this->crearRemitente($tipoRemitente);

            // 2. Generar número de radicado
            $numeroRadicado = Radicado::generarNumeroRadicado('entrada');

            // 3. Seleccionar datos aleatorios
            $dependenciaDestino = $dependencias->random();
            $tipoSolicitud = $tiposSolicitud->random();
            $usuario = $usuarios->random();

            // 4. TRD opcional (70% con TRD, 30% sin TRD)
            $subserie = rand(1, 10) <= 7 ? $subseries->random() : null;

            // 5. Datos del documento
            $mediosRecepcion = ['fisico', 'email', 'web', 'telefono', 'fax', 'otro'];
            $mediosRespuesta = ['fisico', 'email', 'telefono', 'presencial', 'no_requiere'];
            $tiposAnexo = ['original', 'copia', 'ninguno'];

            // 6. Fecha aleatoria (últimos 30 días)
            $fecha = Carbon::now()->subDays(rand(0, 30));

            // 7. Crear radicado
            $radicado = Radicado::create([
                'numero_radicado' => $numeroRadicado,
                'tipo' => 'entrada',
                'fecha_radicado' => $fecha->toDateString(),
                'hora_radicado' => $fecha->setTime(rand(8, 17), rand(0, 59), rand(0, 59))->toTimeString(),
                'remitente_id' => $remitente->id,
                'subserie_id' => $subserie?->id,
                'dependencia_destino_id' => $dependenciaDestino->id,
                'usuario_radica_id' => $usuario->id,
                'medio_recepcion' => $mediosRecepcion[array_rand($mediosRecepcion)],
                'tipo_comunicacion' => $tipoSolicitud->codigo,
                'numero_folios' => rand(1, 15),
                'numero_anexos' => rand(0, 5), // 0 a 5 anexos
                'observaciones' => $this->generarObservaciones(),
                'medio_respuesta' => $mediosRespuesta[array_rand($mediosRespuesta)],
                'tipo_anexo' => $tiposAnexo[array_rand($tiposAnexo)],
                'fecha_limite_respuesta' => rand(1, 3) === 1 ? $fecha->copy()->addDays(rand(5, 30))->toDateString() : null,
                'estado' => $this->generarEstadoAleatorio(),
            ]);

            // 8. Crear documento simulado
            $this->crearDocumentoSimulado($radicado, $numeroRadicado);

            $trdInfo = $subserie ? "{$subserie->serie->unidadAdministrativa->codigo}.{$subserie->serie->numero_serie}.{$subserie->numero_subserie}" : 'Sin TRD';
            $this->command->info("   ✓ #{$numero}: {$numeroRadicado} - {$remitente->nombre_completo} ({$tipoRemitente}) - TRD: {$trdInfo}");

        } catch (\Exception $e) {
            $this->command->error("   ❌ Error creando radicado #{$numero}: " . $e->getMessage());
        }
    }

    private function crearRemitente($tipo)
    {
        $nombres = [
            'Juan Carlos Rodríguez', 'María Elena Gómez', 'Carlos Alberto Martínez', 'Ana Sofía López',
            'Luis Fernando Torres', 'Carmen Rosa Díaz', 'José Miguel Herrera', 'Claudia Patricia Ruiz',
            'Roberto Antonio Silva', 'Luz Marina Vargas', 'Diego Alejandro Castro', 'Sandra Milena Ortiz',
            'Andrés Felipe Morales', 'Paola Andrea Jiménez', 'Ricardo Enrique Peña', 'Gloria Esperanza Ramos'
        ];

        $entidades = [
            'Alcaldía Municipal', 'Gobernación Departamental', 'Ministerio de Salud', 'ICBF',
            'Policía Nacional', 'Bomberos Voluntarios', 'Cruz Roja', 'Defensoría del Pueblo',
            'Procuraduría General', 'Contraloría General', 'Universidad Nacional', 'SENA'
        ];

        $ciudades = [
            'Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena', 'Bucaramanga',
            'Pereira', 'Manizales', 'Ibagué', 'Neiva', 'Villavicencio', 'Pasto'
        ];

        $departamentos = [
            'Cundinamarca', 'Antioquia', 'Valle del Cauca', 'Atlántico', 'Bolívar', 'Santander',
            'Risaralda', 'Caldas', 'Tolima', 'Huila', 'Meta', 'Nariño'
        ];

        $data = [
            'tipo' => $tipo,
            'nombre_completo' => $nombres[array_rand($nombres)],
            'telefono' => '3' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT),
            'email' => strtolower(str_replace(' ', '.', $nombres[array_rand($nombres)])) . '@email.com',
            'direccion' => 'Calle ' . rand(1, 100) . ' # ' . rand(1, 50) . '-' . rand(1, 99),
            'ciudad' => $ciudades[array_rand($ciudades)],
            'departamento' => $departamentos[array_rand($departamentos)],
        ];

        if ($tipo === 'registrado') {
            $tiposDocumento = ['CC', 'CE', 'TI', 'PP', 'NIT'];
            $data['tipo_documento'] = $tiposDocumento[array_rand($tiposDocumento)];
            $data['numero_documento'] = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);

            // 30% de probabilidad de tener entidad
            if (rand(1, 10) <= 3) {
                $data['entidad'] = $entidades[array_rand($entidades)];
            }
        }

        return Remitente::create($data);
    }

    private function generarObservaciones()
    {
        $observaciones = [
            'Solicitud urgente para revisión',
            'Requiere respuesta prioritaria',
            'Documento con información confidencial',
            'Seguimiento a solicitud anterior',
            'Petición de información pública',
            'Queja sobre servicio prestado',
            'Sugerencia para mejora del servicio',
            'Reclamo por demora en respuesta',
            'Solicitud de certificación',
            'Petición de reunión',
            null, // 20% sin observaciones
            null,
            null,
            null
        ];

        return $observaciones[array_rand($observaciones)];
    }

    private function generarEstadoAleatorio()
    {
        $estados = [
            'pendiente' => 40,    // 40%
            'en_proceso' => 30,   // 30%
            'respondido' => 25,   // 25%
            'archivado' => 5      // 5%
        ];

        $random = rand(1, 100);
        $acumulado = 0;

        foreach ($estados as $estado => $porcentaje) {
            $acumulado += $porcentaje;
            if ($random <= $acumulado) {
                return $estado;
            }
        }

        return 'pendiente';
    }

    private function crearDocumentoSimulado($radicado, $numeroRadicado)
    {
        // Crear un archivo PDF simulado
        $nombreArchivo = $numeroRadicado . '_documento_entrada.pdf';
        $rutaArchivo = 'documentos/entrada/' . $nombreArchivo;

        // Contenido simulado de PDF
        $contenidoSimulado = "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n/Pages 2 0 R\n>>\nendobj\n2 0 obj\n<<\n/Type /Pages\n/Kids [3 0 R]\n/Count 1\n>>\nendobj\n3 0 obj\n<<\n/Type /Page\n/Parent 2 0 R\n/MediaBox [0 0 612 792]\n>>\nendobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \ntrailer\n<<\n/Size 4\n/Root 1 0 R\n>>\nstartxref\n174\n%%EOF";

        // Guardar archivo simulado
        Storage::disk('public')->put($rutaArchivo, $contenidoSimulado);

        // Crear registro en base de datos
        Documento::create([
            'radicado_id' => $radicado->id,
            'nombre_archivo' => 'Documento_' . $numeroRadicado . '.pdf',
            'ruta_archivo' => $rutaArchivo,
            'tipo_mime' => 'application/pdf',
            'tamaño_archivo' => strlen($contenidoSimulado),
            'hash_archivo' => hash('sha256', $contenidoSimulado),
            'descripcion' => 'Documento principal del radicado de entrada',
            'es_principal' => true,
        ]);
    }
}

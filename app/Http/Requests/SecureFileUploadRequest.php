<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class SecureFileUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'documento' => [
                'required',
                'file',
                File::types(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])
                    ->max(10 * 1024) // 10MB máximo
                    ->rules([
                        'mimes:pdf,doc,docx,jpg,jpeg,png',
                        'max:10240', // 10MB en KB
                        function ($attribute, $value, $fail) {
                            // Validación adicional de seguridad
                            $this->validateFileContent($attribute, $value, $fail);
                        }
                    ])
            ]
        ];
    }

    /**
     * Validación adicional del contenido del archivo
     */
    private function validateFileContent($attribute, $file, $fail): void
    {
        if (!$file || !$file->isValid()) {
            $fail('El archivo no es válido.');
            return;
        }

        // Verificar que el MIME type real coincida con la extensión
        $realMimeType = mime_content_type($file->getRealPath());
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
            'image/jpg'
        ];

        if (!in_array($realMimeType, $allowedMimes)) {
            $fail('El tipo de archivo no está permitido. Tipo detectado: ' . $realMimeType);
            return;
        }

        // Verificar tamaño del archivo
        if ($file->getSize() > 10485760) { // 10MB en bytes
            $fail('El archivo es demasiado grande. Máximo permitido: 10MB');
            return;
        }

        // Verificar que no sea un archivo ejecutable
        $dangerousExtensions = [
            'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'jar',
            'php', 'asp', 'aspx', 'jsp', 'pl', 'py', 'rb', 'sh', 'cgi'
        ];

        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, $dangerousExtensions)) {
            $fail('Tipo de archivo no permitido por seguridad.');
            return;
        }

        // Escanear contenido en busca de patrones maliciosos
        $this->scanForMaliciousContent($file, $fail);
    }

    /**
     * Escanear archivo en busca de contenido malicioso
     */
    private function scanForMaliciousContent($file, $fail): void
    {
        try {
            // Leer los primeros 8KB del archivo para análisis
            $handle = fopen($file->getRealPath(), 'rb');
            if (!$handle) {
                $fail('No se pudo analizar el archivo.');
                return;
            }

            $content = fread($handle, 8192);
            fclose($handle);

            // Patrones maliciosos comunes
            $maliciousPatterns = [
                '/<%.*?%>/i',                    // PHP tags
                '/<\?.*?\?>/i',                  // PHP short tags
                '/<script.*?>/i',                // JavaScript
                '/javascript:/i',                // JavaScript protocol
                '/vbscript:/i',                  // VBScript
                '/data:.*base64/i',              // Base64 data URLs
                '/eval\(/i',                     // Eval functions
                '/exec\(/i',                     // Exec functions
                '/system\(/i',                   // System calls
                '/shell_exec/i',                 // Shell execution
                '/passthru/i',                   // Passthru
                '/file_get_contents/i',          // File operations
                '/file_put_contents/i',          // File operations
                '/fopen/i',                      // File operations
                '/fwrite/i',                     // File operations
                '/include/i',                    // Include statements
                '/require/i',                    // Require statements
                '/\$_GET/i',                     // Superglobals
                '/\$_POST/i',                    // Superglobals
                '/\$_REQUEST/i',                 // Superglobals
                '/\$_SESSION/i',                 // Superglobals
                '/\$_COOKIE/i',                  // Superglobals
                '/\$_SERVER/i',                  // Superglobals
                '/base64_decode/i',              // Base64 decode
                '/gzinflate/i',                  // Compression functions
                '/str_rot13/i',                  // Obfuscation
                '/create_function/i',            // Dynamic functions
                '/call_user_func/i',             // Dynamic calls
                '/preg_replace.*\/e/i',          // Dangerous regex
            ];

            foreach ($maliciousPatterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $fail('El archivo contiene contenido potencialmente malicioso.');
                    
                    // Log del intento de subida maliciosa
                    \Log::channel('security')->error('Intento de subida de archivo malicioso', [
                        'ip' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'user_id' => auth()->id(),
                        'filename' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'pattern_matched' => $pattern,
                        'timestamp' => now()->toISOString()
                    ]);
                    
                    return;
                }
            }

        } catch (\Exception $e) {
            \Log::channel('security')->warning('Error al escanear archivo', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'user_id' => auth()->id()
            ]);
            
            $fail('Error al validar el archivo. Inténtelo nuevamente.');
        }
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'documento.required' => 'Debe seleccionar un archivo.',
            'documento.file' => 'El archivo seleccionado no es válido.',
            'documento.mimes' => 'El archivo debe ser de tipo: PDF, DOC, DOCX, JPG, JPEG o PNG.',
            'documento.max' => 'El archivo no puede ser mayor a 10MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'documento' => 'documento',
        ];
    }
}

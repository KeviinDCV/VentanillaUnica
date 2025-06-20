<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Formatear fecha y hora para Colombia
     */
    public static function formatDateTime($date = null, $format = 'd/m/Y H:i:s'): string
    {
        if ($date === null) {
            $date = now();
        }
        
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        // Asegurar que esté en zona horaria de Colombia
        $date = $date->setTimezone('America/Bogota');
        
        return $date->format($format);
    }
    
    /**
     * Formatear solo fecha para Colombia
     */
    public static function formatDate($date = null, $format = 'd/m/Y'): string
    {
        return self::formatDateTime($date, $format);
    }
    
    /**
     * Formatear solo hora para Colombia
     */
    public static function formatTime($date = null, $format = 'H:i'): string
    {
        return self::formatDateTime($date, $format);
    }
    
    /**
     * Obtener fecha y hora actual de Colombia
     */
    public static function nowColombia(): Carbon
    {
        return Carbon::now('America/Bogota');
    }
    
    /**
     * Formatear fecha en español colombiano
     */
    public static function formatSpanish($date = null, $format = 'l, d \d\e F \d\e Y \a \l\a\s H:i'): string
    {
        if ($date === null) {
            $date = now();
        }
        
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        // Asegurar que esté en zona horaria de Colombia
        $date = $date->setTimezone('America/Bogota');
        
        // Configurar en español
        $date->locale('es');
        
        return $date->format($format);
    }
    
    /**
     * Obtener nombre del día en español
     */
    public static function getDayName($date = null): string
    {
        if ($date === null) {
            $date = now();
        }
        
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        $date = $date->setTimezone('America/Bogota');
        $date->locale('es');
        
        return $date->dayName;
    }
    
    /**
     * Obtener nombre del mes en español
     */
    public static function getMonthName($date = null): string
    {
        if ($date === null) {
            $date = now();
        }
        
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        $date = $date->setTimezone('America/Bogota');
        $date->locale('es');
        
        return $date->monthName;
    }
    
    /**
     * Formatear fecha relativa en español (hace 2 horas, etc.)
     */
    public static function diffForHumans($date): string
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        $date = $date->setTimezone('America/Bogota');
        $date->locale('es');
        
        return $date->diffForHumans();
    }
    
    /**
     * Verificar si es hoy
     */
    public static function isToday($date): bool
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        $date = $date->setTimezone('America/Bogota');
        $today = Carbon::today('America/Bogota');
        
        return $date->isSameDay($today);
    }
    
    /**
     * Formatear para mostrar en dashboard
     */
    public static function formatForDashboard($date = null): string
    {
        if ($date === null) {
            $date = now();
        }
        
        return self::formatDateTime($date, 'd/m/Y H:i');
    }
    
    /**
     * Formatear hora actual para dashboard
     */
    public static function currentTimeForDashboard(): string
    {
        return self::formatTime(null, 'H:i');
    }
    
    /**
     * Formatear fecha completa para dashboard
     */
    public static function currentDateTimeForDashboard(): string
    {
        return self::formatDateTime(null, 'd/m/Y H:i:s');
    }
}

<?php
class Sanitize {
    // Limpia texto genérico
    public static function cleanString($string) {
        return filter_var(trim($string));
    }

    // Valida un correo electrónico
    public static function validateEmail($email) {
        return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
    }
    
    // Valida una fecha (YYYY-MM-DD)
    public static function validateDate($date) {
        $format = 'Y-m-d';
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    // Valida contraseñas (longitud mínima de 4 caracteres por ejemplo)
    public static function validatePassword($password) {
        return strlen($password) >= 4;
    }
}
?>
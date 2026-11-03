<?php
// Mensajes que se pueden mostrar según el valor de ?msg= en la URL: código => [tipo, texto]
class Mensajes
{
    const LISTA = [
        // Sesión y usuarios
        'no_session'          => ['warning', 'Debe iniciar sesión para continuar.'],
        'logout'              => ['info',    'Sesión cerrada correctamente.'],
        'registered'          => ['success', 'Usuario registrado correctamente. Ya puede iniciar sesión.'],
        'updated'             => ['success', 'Perfil actualizado correctamente.'],
        'invalid_credentials' => ['danger',  'Credenciales inválidas'],
        'invalid_username'    => ['danger',  'El usuario debe tener entre 3 y 50 caracteres.'],
        'password_short'      => ['danger',  'La contraseña debe tener al menos 6 caracteres.'],
        'password_mismatch'   => ['danger',  'Las contraseñas no coinciden.'],
        'invalid_province'    => ['danger',  'Seleccione una provincia válida.'],
        'username_taken'      => ['danger',  'Ese nombre de usuario ya está en uso.'],
        // Cursos
        'course_created'      => ['success', 'Curso creado correctamente.'],
        'course_updated'      => ['success', 'Curso actualizado correctamente.'],
        'course_deleted'      => ['success', 'Curso eliminado correctamente.'],
        'course_not_found'    => ['danger',  'El curso solicitado no existe.'],
        'invalid_name'        => ['danger',  'El nombre debe tener entre 3 y 100 caracteres.'],
        'invalid_code'        => ['danger',  'El código solo puede tener letras, números y guiones (de 2 a 20 caracteres).'],
        'invalid_career'      => ['danger',  'Seleccione una carrera válida.'],
        'invalid_credits'     => ['danger',  'Los créditos deben ser un número entre 1 y 10.'],
        'invalid_description' => ['danger',  'La descripción es obligatoria (máximo 1000 caracteres).'],
        'code_taken'          => ['danger',  'Ya existe un curso con ese código.'],
        // Generales
        'invalid_input'       => ['danger',  'Complete todos los campos obligatorios.'],
        'server_error'        => ['danger',  'Ocurrió un error en el servidor. Intente de nuevo.'],
    ];

    // Devuelve [tipo, texto] si ?msg= es un código conocido; si no, null
    public static function desdeUrl()
    {
        $codigo = $_GET['msg'] ?? '';
        if (is_string($codigo) && isset(self::LISTA[$codigo])) {
            return self::LISTA[$codigo];
        }
        return null;
    }
}
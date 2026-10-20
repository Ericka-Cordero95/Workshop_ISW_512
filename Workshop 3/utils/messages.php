<?php
// Mensajes que se pueden mostrar según el valor de ?msg= en la URL: código => [tipo, texto]
const MENSAJES = [
    'course_created'      => ['success', 'Curso creado correctamente.'],
    'course_updated'      => ['success', 'Curso actualizado correctamente.'],
    'course_deleted'      => ['success', 'Curso eliminado correctamente.'],
    'course_not_found'    => ['danger',  'El curso solicitado no existe.'],
    'invalid_input'       => ['danger',  'Complete todos los campos del curso.'],
    'invalid_name'        => ['danger',  'El nombre debe tener entre 3 y 100 caracteres.'],
    'invalid_code'        => ['danger',  'El código solo puede tener letras, números y guiones (de 2 a 20 caracteres).'],
    'invalid_career'      => ['danger',  'Seleccione una carrera válida.'],
    'invalid_credits'     => ['danger',  'Los créditos deben ser un número entre 1 y 10.'],
    'invalid_description' => ['danger',  'La descripción es obligatoria (máximo 1000 caracteres).'],
    'code_taken'          => ['danger',  'Ya existe un curso con ese código.'],
    'server_error'        => ['danger',  'Ocurrió un error en el servidor. Intente de nuevo.'],
];

// Muestra una alerta de Bootstrap si ?msg= es un código conocido
function renderAlerta()
{
    $codigo = $_GET['msg'] ?? '';
    if (is_string($codigo) && isset(MENSAJES[$codigo])) {
        echo '<div class="alert alert-' . MENSAJES[$codigo][0] . '" role="alert">'
            . htmlspecialchars(MENSAJES[$codigo][1]) . '</div>';
    }
}

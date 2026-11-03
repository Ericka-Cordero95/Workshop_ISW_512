<?php
// Escapa un texto antes de mostrarlo en HTML (previene XSS)
function e($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}
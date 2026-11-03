<?php
// Menú de navegación para las vistas con sesión iniciada.
// El controlador envía $paginaActiva ('perfil' o 'cursos').
$paginaActiva = $paginaActiva ?? '';
?>
<ul class="nav nav-tabs" id="navId">
  <li class="nav-item">
    <a href="/index.php?c=perfil&a=ver" class="nav-link <?php echo $paginaActiva === 'perfil' ? 'active' : ''; ?>">Perfil</a>
  </li>
  <li class="nav-item">
    <a href="/index.php?c=cursos&a=listar" class="nav-link <?php echo $paginaActiva === 'cursos' ? 'active' : ''; ?>">Cursos</a>
  </li>
  <li class="nav-item">
    <form action="/index.php?c=auth&a=logout" method="POST" class="m-0">
      <button type="submit" class="nav-link btn btn-link">Cerrar sesión</button>
    </form>
  </li>
</ul>

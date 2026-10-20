<?php
// Menú de navegación para las páginas con sesión iniciada.
// Cada página define $paginaActiva ('perfil' o 'cursos') antes de incluir este archivo.
$paginaActiva = $paginaActiva ?? '';
?>
<ul class="nav nav-tabs" id="navId">
  <li class="nav-item">
    <a href="/pages/perfil.php" class="nav-link <?php echo $paginaActiva === 'perfil' ? 'active' : ''; ?>">Perfil</a>
  </li>
  <li class="nav-item">
    <a href="/pages/cursos.php" class="nav-link <?php echo $paginaActiva === 'cursos' ? 'active' : ''; ?>">Cursos</a>
  </li>
  <li class="nav-item">
    <form action="/actions/logout.php" method="POST" class="m-0">
      <button type="submit" class="nav-link btn btn-link">Cerrar sesión</button>
    </form>
  </li>
</ul>
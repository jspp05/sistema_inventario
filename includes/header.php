<?php $pagina = $pagina ?? ''; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de inventario</title>
  <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
<header class="navbar">
  <div class="container nav-inner">
    <strong>Sistema de inventario</strong>
    <nav>
      <a href="index.php" class="<?= $pagina === 'inicio' ? 'activo' : '' ?>">Inicio</a>
      <a href="productos.php" class="<?= $pagina === 'productos' ? 'activo' : '' ?>">Productos</a>
      <a href="registrar.php" class="<?= $pagina === 'registrar' ? 'activo' : '' ?>">Registrar</a>
    </nav>
  </div>
</header>
<main class="container">

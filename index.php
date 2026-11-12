<?php
require __DIR__ . '/config/conexion.php';
$pagina = 'inicio';

$total      = (int) $pdo->query('SELECT COUNT(*) FROM productos')->fetchColumn();
$activos    = (int) $pdo->query("SELECT COUNT(*) FROM productos WHERE estado = 'Activo'")->fetchColumn();
$categorias = (int) $pdo->query('SELECT COUNT(DISTINCT categoria_id) FROM productos')->fetchColumn();

include __DIR__ . '/includes/header.php';
?>
<h1>Inventario de productos</h1>
<p class="sub">Consulta las existencias, revisa el estado de cada producto y registra nuevas entradas.</p>

<section class="cards">
  <div class="card"><span>Productos registrados</span><strong><?= $total ?></strong></div>
  <div class="card"><span>Productos activos</span><strong><?= $activos ?></strong></div>
  <div class="card"><span>Categorías</span><strong><?= $categorias ?></strong></div>
</section>

<div class="acciones">
  <a class="btn btn-verde" href="registrar.php">Registrar producto</a>
  <a class="btn btn-gris" href="productos.php">Ver productos</a>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>

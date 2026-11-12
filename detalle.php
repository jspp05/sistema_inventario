<?php
require __DIR__ . '/config/conexion.php';
$pagina = 'productos';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$producto = null;

if ($id) {
    $st = $pdo->prepare('SELECT p.id, p.nombre, p.precio, p.estado, p.cantidad, c.nombre AS categoria
                         FROM productos p INNER JOIN categorias c ON c.id = p.categoria_id
                         WHERE p.id = ?');
    $st->execute([$id]);
    $producto = $st->fetch();
}

include __DIR__ . '/includes/header.php';
?>
<?php if (!$producto): ?>
  <h1>Producto no encontrado</h1>
  <p class="sub">El identificador indicado no existe o no es válido.</p>
  <a class="btn btn-gris" href="productos.php">Volver al listado</a>
<?php else:
  $clase = $producto['estado'] === 'Activo' ? 'estado-activo' : 'estado-inactivo'; ?>
  <h1>Detalle del producto</h1>
  <div class="panel detalle">
    <dl>
      <dt>ID</dt><dd><?= (int) $producto['id'] ?></dd>
      <dt>Nombre</dt><dd><?= htmlspecialchars($producto['nombre']) ?></dd>
      <dt>Precio</dt><dd>$<?= number_format($producto['precio'], 2, ',', '.') ?></dd>
      <dt>Categoría</dt><dd><?= htmlspecialchars($producto['categoria']) ?></dd>
      <dt>Estado</dt><dd><span class="<?= $clase ?>"><?= htmlspecialchars($producto['estado']) ?></span></dd>
      <dt>Cantidad</dt><dd><?= (int) $producto['cantidad'] ?></dd>
    </dl>
    <div class="acciones">
      <a class="btn btn-verde" href="registrar.php?id=<?= (int) $producto['id'] ?>">Editar</a>
      <a class="btn btn-gris" href="productos.php">Volver</a>
    </div>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>

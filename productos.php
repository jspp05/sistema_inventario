<?php
require __DIR__ . '/config/conexion.php';
$pagina = 'productos';

$sql = 'SELECT p.id, p.nombre, p.precio, p.estado, p.cantidad, c.nombre AS categoria
        FROM productos p
        INNER JOIN categorias c ON c.id = p.categoria_id
        ORDER BY p.id';
$productos = $pdo->query($sql)->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="titulo-fila">
  <div>
    <h1>Listado de productos</h1>
    <p class="sub">Productos disponibles en el inventario.</p>
  </div>
  <a class="btn btn-verde" href="registrar.php">Nuevo producto</a>
</div>

<!-- Req. 1 (búsqueda) y Req. 3 (filtro por estado) -->
<div class="panel controles">
  <input type="text" id="buscador" placeholder="Buscar por nombre o categoría...">
  <button type="button" id="btn-limpiar" class="btn btn-gris">Limpiar</button>
  <label for="filtro-estado">Estado:</label>
  <select id="filtro-estado">
    <option value="todos">Todos</option>
    <option value="activo">Activos</option>
    <option value="inactivo">Inactivos</option>
  </select>
</div>

<p id="contador" class="contador">Mostrando <?= count($productos) ?> producto(s)</p>

<div class="panel tabla-wrap">
  <table id="tabla-productos">
    <thead>
      <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Categoría</th><th>Estado</th><th>Cantidad</th><th>Acciones</th></tr>
    </thead>
    <tbody>
    <?php foreach ($productos as $p):
        $claseEstado = strtolower($p['estado']) === 'activo' ? 'estado-activo' : 'estado-inactivo'; ?>
      <tr data-nombre="<?= htmlspecialchars(mb_strtolower($p['nombre'])) ?>"
          data-categoria="<?= htmlspecialchars(mb_strtolower($p['categoria'])) ?>"
          data-estado="<?= strtolower($p['estado']) ?>">
        <td><?= (int) $p['id'] ?></td>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td>$<?= number_format($p['precio'], 2, ',', '.') ?></td>
        <td><?= htmlspecialchars($p['categoria']) ?></td>
        <td><span class="<?= $claseEstado ?>"><?= htmlspecialchars($p['estado']) ?></span></td>
        <td><?= (int) $p['cantidad'] ?></td>
        <td>
          <a href="detalle.php?id=<?= (int) $p['id'] ?>">Ver</a> |
          <a class="editar" href="registrar.php?id=<?= (int) $p['id'] ?>">Editar</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p id="sin-resultados" class="oculto">No se encontraron productos con ese criterio.</p>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>

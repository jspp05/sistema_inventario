<?php
require __DIR__ . '/config/conexion.php';
$pagina = 'registrar';

$errores = [];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$producto = ['nombre' => '', 'precio' => '', 'cantidad' => '', 'estado' => 'Activo', 'categoria_id' => 1];

// Modo edición: cargar datos del producto
if ($id && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $st = $pdo->prepare('SELECT nombre, precio, cantidad, estado, categoria_id FROM productos WHERE id = ?');
    $st->execute([$id]);
    $encontrado = $st->fetch();
    if ($encontrado) {
        $producto = $encontrado;
    } else {
        $errores[] = 'El producto que intentas editar no existe.';
        $id = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto['nombre']       = trim($_POST['nombre'] ?? '');
    $producto['precio']       = $_POST['precio'] ?? '';
    $producto['cantidad']     = $_POST['cantidad'] ?? '';
    $producto['estado']       = $_POST['estado'] ?? 'Activo';
    $producto['categoria_id'] = (int) ($_POST['categoria_id'] ?? 0);

    // Validaciones del lado del servidor
    if ($producto['nombre'] === '') $errores[] = 'El nombre es obligatorio.';
    if (!is_numeric($producto['precio']) || $producto['precio'] <= 0) $errores[] = 'El precio debe ser un número positivo.';
    if (filter_var($producto['cantidad'], FILTER_VALIDATE_INT) === false || $producto['cantidad'] < 0) $errores[] = 'La cantidad no puede ser negativa.';
    if (!in_array($producto['estado'], ['Activo', 'Inactivo'], true)) $errores[] = 'Estado no válido.';

    if (!$errores) {
        $datos = [$producto['nombre'], $producto['precio'], $producto['estado'], $producto['cantidad'], $producto['categoria_id']];
        try {
            if ($id) {
                $datos[] = $id;
                $pdo->prepare('UPDATE productos SET nombre=?, precio=?, estado=?, cantidad=?, categoria_id=? WHERE id=?')->execute($datos);
            } else {
                $pdo->prepare('INSERT INTO productos (nombre, precio, estado, cantidad, categoria_id) VALUES (?,?,?,?,?)')->execute($datos);
            }
            header('Location: productos.php');
            exit;
        } catch (PDOException $e) {
            $errores[] = 'No se pudo guardar el producto. Verifica los datos.';
        }
    }
}

$categorias = $pdo->query('SELECT id, nombre FROM categorias ORDER BY id')->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<h1><?= $id ? 'Editar producto' : 'Registrar producto' ?></h1>
<p class="sub">Completa o modifica los datos del inventario.</p>

<?php if ($errores): ?>
  <div class="alerta"><?php foreach ($errores as $e) echo '<p>' . htmlspecialchars($e) . '</p>'; ?></div>
<?php endif; ?>
<div id="mensaje-error" class="alerta oculto"></div>

<form id="form-producto" class="panel formulario" method="post" action="registrar.php<?= $id ? '?id=' . (int) $id : '' ?>" novalidate>
  <input type="hidden" name="id" value="<?= (int) $id ?>">

  <label for="nombre">Nombre del producto</label>
  <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>">

  <label for="precio">Precio (número positivo)</label>
  <input type="number" id="precio" name="precio" step="0.01" value="<?= htmlspecialchars($producto['precio']) ?>">

  <label for="cantidad">Cantidad disponible (no negativa)</label>
  <input type="number" id="cantidad" name="cantidad" step="1" value="<?= htmlspecialchars($producto['cantidad']) ?>">

  <label for="estado">Estado</label>
  <select id="estado" name="estado">
    <option value="Activo"   <?= $producto['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
    <option value="Inactivo" <?= $producto['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
  </select>

  <label for="categoria_id">Categoría</label>
  <select id="categoria_id" name="categoria_id">
    <?php foreach ($categorias as $c): ?>
      <option value="<?= (int) $c['id'] ?>" <?= (int) $producto['categoria_id'] === (int) $c['id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <div class="acciones">
    <button type="submit" id="btn-guardar" class="btn btn-verde">Guardar</button>
    <a href="productos.php" class="btn btn-gris">Cancelar</a>
  </div>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>

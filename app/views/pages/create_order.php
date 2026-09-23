 <main class="main">
  <?php use classes\Producto;
  use classes\Proveedor;

  include __DIR__ . '/header.php'; ?>
  <section class="container">
    
    <?php
    $mensaje = $global_msg ?? '';
    
    // Obtener listas para los selectores
    $proveedores = Proveedor::getProveedores();
    $productosList = Producto::getProductos();
    ?>

    <section class="toolbar d-flex gap-2 mb-3">
      <a href="index.php?page=orders" class="btn secondary text-decoration-none d-flex align-center px-3">&laquo; Volver a Pedidos</a>
    </section>

    <h3 class="mt-5 mb-3">Realizar Nuevo Pedido</h3>
    <?php echo $mensaje; ?>
    
    <section class="form">
      <form method="POST" action="php/actions/order_actions.php">
        <input type="hidden" name="action" value="create_order">
        <section class="row">
          <section class="flex-2">
            <label>Proveedor *</label>
            <select name="id_proveedor" required>
                <option value="">-- Seleccionar Proveedor --</option>
                <?php foreach($proveedores as $prov): ?>
                    <option value="<?= htmlspecialchars($prov->getIdProveedor()) ?>">
                        <?= htmlspecialchars($prov->getNombre()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
          </section>
          <section class="flex-1">
            <label>Fecha del Pedido</label>
            <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" required>
          </section>
          <section class="flex-1">
            <label>Estado</label>
            <select name="estado">
                <option value="Pendiente" selected>Pendiente</option>
                <option value="Recibido">Recibido</option>
                <option value="Cancelado">Cancelado</option>
            </select>
          </section>
        </section>

        <h4 class="mt-4 mb-3 border-bottom-light pb-2">Añadir Productos al Pedido</h4>
        <section id="productos-container">
            <!-- Fila de producto base -->
            <section class="row producto-row mb-2">
              <section class="flex-2">
                <select name="productos[]">
                    <option value="">-- Seleccionar Producto --</option>
                    <?php foreach($productosList as $prod): ?>
                        <option value="<?= htmlspecialchars($prod->getIdProducto()) ?>">
                            <?= htmlspecialchars($prod->getNombre()) ?> (Stock: <?= htmlspecialchars($prod->getStock()) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
              </section>
              <section class="flex-1">
                <input type="number" name="cantidades[]" placeholder="Cantidad" min="0">
              </section>
              <section class="flex-0 align-self-center">
                <button type="button" class="btn secondary" onclick="this.parentElement.parentElement.remove()">X</button>
              </section>
            </section>
        </section>
        
        <section class="mt-2">
            <button type="button" class="btn secondary" onclick="addProductoRow()">+ Añadir otro producto</button>
        </section>

        <section class="row mt-4">
          <button type="submit" class="btn">Guardar Pedido</button>
        </section>
      </form>
    </section>

    <footer class="footer">© 2026 Flores Nuria · Creación de pedidos</footer>
  </section>
</main>

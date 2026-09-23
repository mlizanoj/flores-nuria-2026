<main class="main">
  <?php use classes\Cliente;
  use classes\Producto;

  include __DIR__ . '/header.php'; ?>
  <section class="container">
    
    <?php
    $mensaje = $global_msg ?? '';
    ?>

    <section class="toolbar d-flex gap-2 mb-3">
      <a href="index.php?page=payments" class="btn secondary text-decoration-none d-flex align-center px-3">&laquo; Volver a Pagos</a>
    </section>

    <h3 class="mt-5 mb-3">Crear Nuevo Pago</h3>
    <?php echo $mensaje; ?>
    
    <?php
    $productos = Producto::getProductos();
    $clientes = Cliente::getClientes();
    ?>

    <section class="form">
      <form method="POST" action="php/actions/payment_action.php">
        <input type="hidden" name="action" value="create_payment">
        <section class="row">

          <section class="flex-2">
            <label>Producto *</label>
            <input type="text" id="search-create-prod" placeholder="Buscar producto..." onkeyup="filterProducts('create')" class="flex-1 px-3-py-1 border-base rounded-sm" style="max-width: 300px;">
            <select name="pedido_id" id="select-create-prod" required>
              <option value="">-- Seleccionar Producto --</option>
              <?php foreach($productos as $p): ?>
                <option value="<?= htmlspecialchars($p->getIdProducto(), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($p->getNombre(), ENT_QUOTES, 'UTF-8'); ?> - <?= $p->getStock(); ?></option>
              <?php endforeach; ?>
            </select>
          </section>

          <section class="flex-2">
            <label>Cliente</label>
            <input type="text" id="search-filter-client" placeholder="Nombre del cliente" onkeyup="filterSelect('client')" class="flex-1 px-3-py-1 border-base rounded-sm">
              <select name="cliente_id" id="select-filter-client" required>
                  <option value="">-- Seleccionar Cliente --</option>
                  <?php foreach($clientes as $c): ?>
                    <option value="<?= htmlspecialchars($c->getIdCliente(), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($c->getNombre(), ENT_QUOTES, 'UTF-8'); ?> - <?= htmlspecialchars($c->getCorreo() ?? ""); ?></option>
                  <?php endforeach; ?>
              </select>
          </section>
          <section class="flex-1">
            <label>Monto (€) *</label>
            <input type="number" step="0.01" name="monto" min="0.01" required>
          </section>
          <section class="flex-1">
            <label>Método de Pago *</label>
            <select name="metodo" required>
              <option value="">-- Seleccionar --</option>
              <option value="efectivo">Efectivo</option>
              <option value="tarjeta">Tarjeta</option>
              <option value="transferencia">Transferencia</option>
              <option value="otros">Otros</option>
            </select>
          </section>
        </section>

        <section class="row mt-4">
          <section class="flex-1">
            <label>Fecha</label>
            <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>">
          </section>
        </section>

        <section class="row mt-4">
          <button type="submit" class="btn">Guardar Pago</button>
        </section>
      </form>
    </section>

    <footer class="footer">© 2026 Flores Nuria · Creación de pagos</footer>
  </section>
</main>

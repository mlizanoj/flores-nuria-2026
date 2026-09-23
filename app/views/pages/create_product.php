<main class="main">
  <?php include __DIR__ . '/header.php'; ?>
  <section class="container">
    
    <?php
    $mensaje = $global_msg ?? '';
    ?>

    <section class="toolbar d-flex gap-2 mb-3">
      <a href="index.php?page=products" class="btn secondary text-decoration-none d-flex align-center px-3">&laquo; Volver a Productos</a>
    </section>

    <h3 class="mt-5 mb-3">Crear Nuevo Producto</h3>
    <?php echo $mensaje; ?>
    
    <section class="form">
      <form method="POST" action="php/actions/product_actions.php">
        <input type="hidden" name="action" value="create_product">
        <section class="row">
          <section class="flex-2">
            <label>Nombre del Producto *</label>
            <input type="text" name="nombre" placeholder="Ej: Rosa roja" required>
          </section>
          <section class="flex-1">
            <label>Precio Base (€) *</label>
            <input type="number" step="0.01" name="precio" min="0.01" required>
          </section>
          <section class="flex-1">
            <label>IVA (%)</label>
            <input type="number" step="0.01" name="iva" min="0" max="100" value="21.00" required>
          </section>
          <section class="flex-1">
            <label>Stock Inicial</label>
            <input type="number" name="stock" min="0" value="0">
          </section>
        </section>

        <section class="row mt-4">
          <button type="submit" class="btn">Guardar Producto</button>
        </section>
      </form>
    </section>

    <footer class="footer">© 2026 Flores Nuria · Creación de productos</footer>
  </section>
</main>

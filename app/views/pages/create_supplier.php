<main class="main">
  <?php include __DIR__ . '/header.php'; ?>
  <section class="container">
    
    <?php
    $mensaje = $global_msg ?? '';
    ?>

    <section class="toolbar d-flex gap-2 mb-3">
      <a href="index.php?page=suppliers" class="btn secondary text-decoration-none d-flex align-center px-3">&laquo; Volver a Proveedores</a>
    </section>

    <h3 class="mt-5 mb-3">Crear Nuevo Proveedor</h3>
    <?php echo $mensaje; ?>
    
    <section class="form">
      <form method="POST" action="php/actions/supplier_actions.php">
        <input type="hidden" name="action" value="create_supplier">
        <section class="row">
          <section class="flex-2">
            <label>Nombre del Proveedor *</label>
            <input type="text" name="nombre" placeholder="Ej: Viveros el Sol" required>
          </section>
          <section class="flex-2">
            <label>Dirección</label>
            <input type="text" name="direccion" placeholder="Ej: Calle Principal 123">
          </section>
        </section>

        <section class="row mt-4">
          <section class="flex-1">
            <label>Teléfono</label>
            <input type="number" name="telefono" placeholder="Ej: 600123456" maxlength="9" minlength="9">
          </section>
          <section class="flex-1">
            <label>Correo Electrónico</label>
            <input type="email" name="correo" placeholder="Ej: [EMAIL_ADDRESS]">
          </section>
        </section>

        <section class="row mt-4">
          <button type="submit" class="btn">Guardar Proveedor</button>
        </section>
      </form>
    </section>

    <footer class="footer">© 2026 Flores Nuria · Creación de proveedores</footer>
  </section>
</main>

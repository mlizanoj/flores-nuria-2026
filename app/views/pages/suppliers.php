<main class="main">
  <?php

  use classes\Proveedor;

  include __DIR__ . '/header.php';
  $msg = $global_msg ?? '';
  ?>
  <section class="container">
    <?php echo $msg; ?>
    <section class="toolbar d-flex gap-2 mb-3">
      <form method="GET" action="index.php" class="d-flex gap-2 m-0">
        <input type="hidden" name="page" value="suppliers">
        <input type="text" name="search" placeholder="Buscar proveedor..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" class="px-3-py-1 border-base rounded-sm outline-none">
        <button type="submit" class="btn">Buscar</button>
      </form>
      <a href="index.php?page=suppliers" class="btn pill green text-decoration-none d-flex align-center px-3">Mostrar Todo</a>
      <a href="index.php?page=create_supplier" class="btn secondary text-decoration-none d-flex align-center px-3">Nuevo proveedor</a>
    </section>

    <section class="table-wrap">
      <table class="table">
        <thead>
          <tr><th>Nombre</th><th>Dirección</th><th>Teléfono</th><th>Correo</th><th></th></tr>
        </thead>
        <tbody>
          <?php
          $busqueda = trim($_GET['search'] ?? '');
          if ($busqueda !== '') {
              $proveedores = Proveedor::buscarProveedores($busqueda);
          } else {
              $proveedores = Proveedor::getProveedores();
          }
          
          foreach ($proveedores as $proveedor) {
            $id = htmlspecialchars($proveedor->getIdProveedor());
            $nombre = htmlspecialchars($proveedor->getNombre());
            $direccion = htmlspecialchars($proveedor->getDireccion());
            $telefono = htmlspecialchars($proveedor->getTelefono());
            $correo = htmlspecialchars($proveedor->getCorreo());
            
            $jsNombre = htmlspecialchars(json_encode($proveedor->getNombre()));
            $jsDir = htmlspecialchars(json_encode($proveedor->getDireccion()));
            $jsTel = htmlspecialchars(json_encode($proveedor->getTelefono()));
            $jsCor = htmlspecialchars(json_encode($proveedor->getCorreo()));
            
            echo "<tr>";
            echo "<td>{$nombre}</td>";
            echo "<td>{$direccion}</td>";
            echo "<td>{$telefono}</td>";
            echo "<td>{$correo}</td>";
            echo "<td class=\"controller\">
                    <button type=\"button\" class=\"btn-edit\" onclick='editSupplier({$id}, {$jsNombre}, {$jsDir}, {$jsTel}, {$jsCor})'>✎</button>
                    <button type=\"button\" class=\"btn-delete\" onclick='deleteSupplier({$id})'>🗑</button>
                  </td>";
            echo "</tr>";
          }
          
          if (empty($proveedores)) {
              echo "<tr><td colspan='5' class='text-center'>No hay proveedores registrados.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </section>

    <h3 id="edit-title" class="mt-5 d-none">Editar proveedor</h3>
    <section class="form">
      <form id="form-edit-supplier" method="POST" action="php/actions/supplier_actions.php" class="d-none">
        <input type="hidden" name="action" value="update_supplier">
        <input type="hidden" name="idProveedor" id="edit-id">
        
        <section class="row">
          <section class="flex-2">
            <label>Nombre del Proveedor *</label>
            <input type="text" name="nombre" id="edit-nombre" required>
          </section>
          <section class="flex-2">
            <label>Dirección</label>
            <input type="text" name="direccion" id="edit-direccion">
          </section>
        </section>

        <section class="row mt-4">
          <section class="flex-1">
            <label>Teléfono</label>
            <input type="tel" name="telefono" id="edit-telefono">
          </section>
          <section class="flex-1">
            <label>Correo Electrónico</label>
            <input type="email" name="correo" id="edit-correo">
          </section>
        </section>

        <section class="row mt-4">
          <button type="submit" class="btn pill orange">Guardar cambios</button>
          <button type="button" class="btn secondary" onclick="closeEditForm()">Cancelar</button>
        </section>
      </form>
    </section>

    <footer class="footer">© 2026 Flores Nuria · Administración de proveedores</footer>
  </section>
</main>

<form id="form-delete-supplier" method="POST" action="php/actions/supplier_actions.php" class="d-none">
    <input type="hidden" name="action" value="delete_supplier">
    <input type="hidden" name="idProveedor" id="delete-id">
</form>

<main class="main">
  <?php

  use classes\Accesorio;
  use classes\Flor;
  use classes\Planta;
  use classes\Producto;

  include __DIR__ . '/header.php';
  $msg = $global_msg ?? '';
  ?>
  <section class="container">
    <?php echo $msg; ?>
    <section class="toolbar d-flex gap-2 mb-3">
      <form method="GET" action="index.php" class="d-flex gap-2 m-0">
        <input type="hidden" name="page" value="products">
        <input type="text" name="search" placeholder="Buscar producto..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" class="px-3-py-1 border-base rounded-sm outline-none">
        <button type="submit" class="btn">Buscar</button>
      </form>
        <form method="GET" action="index.php" class="d-flex gap-2 m-0">
            <select name="category" class="px-3 py-1 rounded-sm">
                <option value="" selected="selected">Seleccione categoria...</option>
                <?php $categorias = Producto::getCategorias();
                foreach ($categorias as $categoria) { ?>
                    <option value="<?= $categoria ?>"><?= $categoria ?></option>
                <?php } ?>
            </select>
            <button class="btn secondary">Familias</button>
        </form>

      <a href="index.php?page=products" class="btn pill green text-decoration-none d-flex align-center px-3">Mostrar Todo</a>
      <a href="index.php?page=create_product" class="btn secondary text-decoration-none d-flex align-center px-3">Nuevo producto</a>
    </section>

    <section class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Oferta</th>
            <th>Precio Base</th>
            <th>IVA</th>
            <th>Precio Final</th>
            <th>Stock</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $busqueda = trim($_GET['search'] ?? '');
          $categoria = trim($_GET['category'] ?? '');
          if ($busqueda !== '') {
              $productos = Producto::buscarProductos($busqueda);
          } elseif ($categoria !== '') {
              switch ($categoria) {
                  case 'flor':
                      $productos = Flor::buscarProductos($categoria);
                      break;
                  case 'planta':
                      $productos =  Planta::buscarProductos($categoria);
                      break;
                  case 'accesorio':
                      $productos = Accesorio::buscarProductos($categoria);
                      break;
              }
              $productos = Producto::buscarProductos($categoria);
          } else{
              $productos = Producto::getProductos();
          }
          
          foreach ($productos as $producto) {
            $id = htmlspecialchars($producto->getIdProducto());
            $nombre = htmlspecialchars($producto->getNombre());
            $stock = htmlspecialchars($producto->getStock());
            $precio_raw = (float)$producto->getPrecio();
            $iva = (float)$producto->getIva();
            $precio_final = (float)$producto->getPrecioConIva();
            
            $ofertaInfo = '';
            $ofertasProd = $producto->getOferta();
            if (!empty($ofertasProd)) {
                $ofertaActiva = $ofertasProd[0];
                $fechaFin = $ofertaActiva->getFechaFin();
                if (empty($fechaFin) || strtotime($fechaFin) >= strtotime(date('Y-m-d'))) {
                    $ofertaInfo = '<span class="pill green px-3-py-1 rounded-sm text-sm fw-bold">Oferta: -' . $ofertaActiva->getDescuento() . '%</span>';
                }
            }
            
            // Pasamos los datos puros a JS, por eso usamos json_encode para escapar strings seguros
            $jsNombre = htmlspecialchars(json_encode($producto->getNombre()));
            
            echo "<tr>";
            echo "<td>{$nombre}</td>";
            ?>
            <td><?php if(isset($ofertaActiva) && $ofertaActiva->getActiva()){ echo $ofertaInfo; } else{ echo "Sin oferta activa"; } ?> </td>
          <?php
            echo "<td>" . number_format($precio_raw, 2) . " €</td>";
            echo "<td>" . number_format($iva, 2) . " %</td>";
            echo "<td>" . number_format($precio_final, 2) . " €</td>";
            echo "<td>{$stock}</td>";
            echo "<td>" . ($stock > 0 ? 'Disponible' : 'Agotado') . "</td>";
            echo "<td class=\"controller\">
                    <button type=\"button\" class=\"btn-edit\" onclick='editProduct({$id}, {$jsNombre}, {$precio_raw}, {$iva}, {$stock})'>✎</button>
                    <button type=\"button\" class=\"btn-delete\" onclick='deleteProduct({$id})'>🗑</button>
                  </td>";
            echo "</tr>";
          }
          
          if (empty($productos)) {
              echo "<tr><td colspan='8' class='text-center'>No hay productos registrados.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </section>

    <h3 id="edit-title" class="mt-5 d-none">Editar producto</h3>
    <section class="form">
      <form id="form-edit-product" method="POST" action="php/actions/product_actions.php" class="d-none">
        <input type="hidden" name="action" value="update_product">
        <input type="hidden" name="idProducto" id="edit-id">
        
        <section class="row">
          <section class="flex-2">
            <label>Nombre del Producto *</label>
            <input type="text" name="nombre" id="edit-nombre" required>
          </section>
          <section class="flex-1">
            <label>Precio Base (€) *</label>
            <input type="number" step="0.01" name="precio" id="edit-precio" required>
          </section>
          <section class="flex-1">
            <label>IVA (%) *</label>
            <input type="number" step="0.01" name="iva" id="edit-iva" required>
          </section>
          <section class="flex-1">
            <label>Cantidad Stock</label>
            <input type="number" name="stock" id="edit-stock">
          </section>
        </section>
        <section class="row mt-4">
          <section class="flex-1">
            <label>Desvincular Oferta</label>
            <div class="mt-2">
                <input type="checkbox" name="quitar_oferta" value="1" id="edit-quitar-oferta">
                <label for="edit-quitar-oferta" class="d-inline fw-normal">Quitar oferta actual del producto</label>
            </div>
          </section>
        </section>

        <section class="row mt-4">
          <button type="submit" class="btn pill orange">Guardar cambios</button>
          <button type="button" class="btn secondary" onclick="closeEditForm()">Cancelar</button>
        </section>
      </form>
    </section>

    <footer class="footer">© 2026 Flores Nuria · Administración de productos</footer>
  </section>
</main>

<form id="form-delete-product" method="POST" action="php/actions/product_actions.php" class="d-none">
    <input type="hidden" name="action" value="delete_product">
    <input type="hidden" name="idProducto" id="delete-id">
</form>

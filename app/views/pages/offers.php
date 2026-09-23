<main class="main">
  <?php

  use classes\Oferta;
  use classes\Producto;

  include __DIR__ . '/header.php';
  $msg = $global_msg ?? '';
  ?>
  <section class="container">
    <?php echo $msg; ?>
    <section class="toolbar d-flex gap-2 mb-3">
      <form method="GET" action="index.php" class="d-flex gap-2 m-0">
        <input type="hidden" name="page" value="offers">
        <input type="text" name="search" placeholder="Buscar oferta..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" class="px-3-py-1 border-base rounded-sm outline-none">
        <button type="submit" class="btn">Buscar</button>
      </form>
      <a href="index.php?page=offers" class="btn pill green text-decoration-none d-flex align-center px-3">Mostrar Todo</a>
      <button type="button" class="btn secondary d-flex align-center px-3" onclick="document.getElementById('form-create-offer').style.display='block'; document.getElementById('form-edit-offer').style.display='none';">Nueva oferta</button>
    </section>

    <!-- FORMULARIO CREAR OFERTA -->
    <form id="form-create-offer" method="POST" action="php/actions/offer_actions.php" class="form mb-3 d-none">
        <input type="hidden" name="action" value="create_offer">
        <div class="row">
          <section class="flex-1">
            <label>Nombre de la Oferta *</label>
            <input type="text" name="nombre" placeholder="Ej: Black Friday" required>
          </section>
          <section class="flex-1">
            <label>Descuento (%) *</label>
            <input type="number" step="0.01" name="descuento" placeholder="Ej: 15" min="0" max="100" required>
          </section>
          <section class="flex-1">
            <label>Fecha Fin (Opcional)</label>
            <input type="date" name="fechaFin">
          </section>
          <section class="flex-1">
            <label>Estado *</label>
            <select name="activa" required>
              <option value="1" selected>Activa</option>
              <option value="0">Inactiva</option>
            </select>
          </section>
        </div>
        <div class="row mt-3">
          <section class="flex-1">
            <label>Productos *</label>
            <div class="d-flex gap-2 mb-2 align-center">
                <input type="text" id="search-create-prod" placeholder="Buscar producto..." onkeyup="filterProducts('create')" class="flex-1 px-3-py-1 border-base rounded-sm" style="max-width: 300px;">
                <select id="select-create-prod" class="flex-2 px-3-py-1 border-base rounded-sm">
                  <?php
                    $productos = Producto::getProductos();
                    foreach($productos as $p){
                        echo "<option value='{$p->getIdProducto()}'>{$p->getNombre()} (" . number_format($p->getPrecioConIva(), 2) . " €)</option>";
                    }
                  ?>
                </select>
                <button type="button" class="btn primary px-3-py-1 fw-bold rounded-sm m-0" onclick="addProductToOffer('create')">+</button>
                <button type="button" class="btn secondary px-3-py-1 rounded-sm m-0" onclick="addAllProductsToOffer('create')" title="Añadir todos los productos">Añadir todos</button>
            </div>
            <div id="container-create-prod" class="border-light rounded-sm p-2 bg-light min-h-80 max-h-150 overflow-y-auto">
                <!-- Elementos añadidos aparecerán aquí -->
                <p id="empty-create-prod" class="text-muted text-md m-0">Añade productos usando el botón +</p>
            </div>
          </section>
        </div>
        <div class="row mt-4">
          <button type="submit" class="btn pill green">Crear Oferta</button>
          <button type="button" class="btn btn-gray" onclick="document.getElementById('form-create-offer').style.display='none';">Cancelar</button>
        </div>
    </form>

    <!-- FORMULARIO EDITAR OFERTA -->
    <h3 id="edit-title" class="mt-5 d-none">Editar oferta</h3>
    <form id="form-edit-offer" method="POST" action="php/actions/offer_actions.php" class="form mb-3 d-none">
        <input type="hidden" name="action" value="update_offer">
        <input type="hidden" name="idOferta" id="edit-id-oferta">
        
        <div class="row">
          <section class="flex-1">
            <label>Nombre Oferta *</label>
            <input type="text" name="nombre" id="edit-nombre-oferta" required>
          </section>
          <section class="flex-1">
            <label>Descuento (%) *</label>
            <input type="number" step="0.01" name="descuento" id="edit-descuento-oferta" min="0" max="100" required>
          </section>
          <section class="flex-1">
            <label>Fecha Fin (Opcional)</label>
            <input type="date" name="fechaFin" id="edit-fechafin-oferta">
          </section>
          <section class="flex-1">
            <label>Estado *</label>
            <select name="activa" id="edit-activa-oferta" required>
              <option value="1">Activa</option>
              <option value="0">Inactiva</option>
            </select>
          </section>
        </div>
        <div class="row mt-3">
          <section class="flex-1">
            <label>Productos *</label>
            <div class="d-flex gap-2 mb-2 align-center">
                <input type="text" id="search-edit-prod" placeholder="Buscar producto..." onkeyup="filterProducts('edit')" class="flex-1 px-3-py-1 border-base rounded-sm" style="max-width: 300px;">
                <select id="select-edit-prod" class="flex-2 px-3-py-1 border-base rounded-sm">
                  <?php
                    foreach($productos as $p){
                        echo "<option value='{$p->getIdProducto()}'>{$p->getNombre()} (" . number_format($p->getPrecioConIva(), 2) . " €)</option>";
                    }
                  ?>
                </select>
                <button type="button" class="btn primary px-3-py-1 fw-bold rounded-sm m-0" onclick="addProductToOffer('edit')">+</button>
                <button type="button" class="btn secondary px-3-py-1 rounded-sm m-0" onclick="addAllProductsToOffer('edit')" title="Añadir todos los productos">Añadir todos</button>
            </div>
            <div id="container-edit-prod" class="border-light rounded-sm p-2 bg-light min-h-80 max-h-150 overflow-y-auto">
                <!-- Elementos añadidos aparecerán aquí -->
                <p id="empty-edit-prod" class="text-muted text-md m-0">Añade productos usando el botón +</p>
            </div>
          </section>
        </div>
        <div class="row mt-4">
          <button type="submit" class="btn pill orange">Guardar cambios</button>
          <button type="button" class="btn btn-gray" onclick="document.getElementById('form-edit-offer').style.display='none'; document.getElementById('edit-title').style.display='none';">Cancelar</button>
        </div>
    </form>

    <!-- LISTA DE OFERTAS -->
    <section class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descuento</th>
            <th>Producto</th>
            <th>Fecha Fin</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $ofertas = Oferta::getOfertas();
          $ofertaCnt = 0;
          foreach ($ofertas as $oferta) {
            $ofertaCnt++;
            $id = $oferta->getIdOferta();
            $nombre = htmlspecialchars($oferta->getNombre());
            $descuento = number_format($oferta->getDescuento(), 2) . ' %';
            $producto = htmlspecialchars($oferta->producto_nombre);
            $fechaFin = $oferta->getFechaFin();
            
            $activaDb = $oferta->getActiva();
            $isCaducada = (!empty($fechaFin) && strtotime($fechaFin) < strtotime(date('Y-m-d')));
            
            if (!$activaDb) {
                $estadoStr = '<span class="status-badge status-inactive">Inactiva</span>';
            } elseif ($isCaducada) {
                $estadoStr = '<span class="status-badge status-expired">Caducada</span>';
            } else {
                $estadoStr = '<span class="status-badge status-active">Activa</span>';
            }
            
            $jsNombre = htmlspecialchars(json_encode($oferta->getNombre()));
            $jsFecha = htmlspecialchars(json_encode($fechaFin ?? ''));
            $jsProductosIds = htmlspecialchars(json_encode($oferta->getProductosIds()));
            $jsActiva = $activaDb ? 'true' : 'false';
            
            echo "<tr>";
            echo "<td>{$nombre}</td>";
            echo "<td>{$descuento}</td>";
            echo "<td>{$producto}</td>";
            echo "<td>" . ($fechaFin ? date('d/m/Y', strtotime($fechaFin)) : 'Sin límite') . "</td>";
            echo "<td>
                    <div class='d-flex align-center gap-2'>
                        <label class='switch'>
                            <input type='checkbox' " . ($activaDb ? 'checked' : '') . " onchange='toggleOfferStatus({$id})'>
                            <span class='slider round'></span>
                        </label>
                        {$estadoStr}
                    </div>
                  </td>";
            echo "<td class=\"controller\">
                    <button type=\"button\" class=\"btn-edit\" onclick='editOffer({$id}, {$jsNombre}, {$oferta->getDescuento()}, {$jsProductosIds}, {$jsFecha}, {$jsActiva})'>✎</button>
                    <button type=\"button\" class=\"btn-delete\" onclick='deleteOffer({$id})'>🗑</button>
                  </td>";
            echo "</tr>";
          }
          if ($ofertaCnt === 0) {
              echo "<tr><td colspan='7' class='text-center'>No hay ofertas registradas.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </section>

    <footer class="footer">© 2026 Flores Nuria · Administración de Ofertas</footer>
  </section>
</main>

<form id="form-delete-offer" method="POST" action="php/actions/offer_actions.php" class="d-none">
    <input type="hidden" name="action" value="delete_offer">
    <input type="hidden" name="idOferta" id="delete-id-oferta">
</form>

<form id="form-toggle-offer" method="POST" action="php/actions/offer_actions.php" class="d-none">
    <input type="hidden" name="action" value="toggle_offer">
    <input type="hidden" name="idOferta" id="toggle-id-oferta">
</form>

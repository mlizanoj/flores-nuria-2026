<main class="main">
  <?php

  use classes\Pedido;
  use classes\Producto;

  include __DIR__ . '/header.php';
  $msg = $global_msg ?? '';
  ?>
  <section class="container">
    <?php echo $msg; ?>
    <section class="toolbar d-flex gap-2 mb-3">
      <form method="GET" action="index.php" class="d-flex gap-2 m-0">
        <input type="hidden" name="page" value="orders">
        <input type="text" name="search" placeholder="Buscar por estado o proveedor..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" class="px-3-py-1 border-base rounded-sm outline-none">
        <button type="submit" class="btn">Buscar</button>
      </form>
      <a href="index.php?page=orders" class="btn pill green text-decoration-none d-flex align-center px-3">Mostrar Todo</a>
      <a href="index.php?page=create_order" class="btn secondary text-decoration-none d-flex align-center px-3">Realizar pedido</a>
    </section>

    <section class="table-wrap">
      <table class="table">
        <thead>
          <tr>
              <th>ID</th>
              <th>Proveedor (ID)</th>
              <th>Fecha</th>
              <th>Estado</th>
              <th>Productos</th>
              <th>Acciones Rápidas</th>
              <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $busqueda = trim($_GET['search'] ?? '');
          if ($busqueda !== '') {
              $pedidos = Pedido::buscarPedidos($busqueda);
          } else {
              $pedidos = Pedido::getPedidos();
          }
          
          // Crear mapa de productos para mostrar nombres en lugar de IDs
          $allProducts = Producto::getProductos();
          $prodMap = [];
          foreach($allProducts as $p) {
              $prodMap[$p->getIdProducto()] = $p->getNombre();
          }
          
          foreach ($pedidos as $pedido) {
            $id = htmlspecialchars($pedido->getIdPedido());
            $proveedorId = htmlspecialchars($pedido->getIdProveedor());
            $fecha = htmlspecialchars($pedido->getFechaCreacion());
            $estado = htmlspecialchars($pedido->getEstado());
            
            $bolsa = $pedido->getBolsaCompra();
            $prodsArr = $bolsa ? $bolsa->getProductos() : [];
            $numProds = count($prodsArr);
            
            $orderItems = [];
            foreach($prodsArr as $item) {
                $pId = $item[0];
                $pQty = $item[1];
                $pName = $prodMap[$pId] ?? 'Producto Desconocido';
                $orderItems[] = ['nombre' => $pName, 'cantidad' => $pQty];
            }
            
            $jsOrderItems = htmlspecialchars(json_encode($orderItems));
            $jsFecha = htmlspecialchars(json_encode($pedido->getFechaCreacion()));
            $jsEstado = htmlspecialchars(json_encode($pedido->getEstado()));
            
            // Colores por estado
            $estadoStr = $estado;
            if (strtolower($estado) === 'recibido') $estadoStr = "<span class='text-success fw-bold'>$estado</span>";
            if (strtolower($estado) === 'cancelado') $estadoStr = "<span class='text-danger fw-bold'>$estado</span>";
            if (strtolower($estado) === 'pendiente') $estadoStr = "<span class='text-warning fw-bold'>$estado</span>";
            
            echo "<tr>";
            echo "<td>{$proveedorId}</td>";
            echo "<td>{$fecha}</td>";
            echo "<td>{$estadoStr}</td>";
            echo "<td>{$numProds} tipos</td>";
            echo "<td class='d-flex gap-1'>
                    <button type=\"button\" class=\"btn secondary btn-sm\" onclick='changeStatus({$id}, \"Pendiente\")'>Pendiente</button>
                    <button type=\"button\" class=\"btn btn-sm btn-success\" onclick='changeStatus({$id}, \"Recibido\")'>Recibido</button>
                    <button type=\"button\" class=\"btn btn-sm btn-danger\" onclick='changeStatus({$id}, \"Cancelado\")'>Cancelado</button>
                  </td>";
            echo "<td class=\"controller\">
                    <button type=\"button\" class=\"btn-view\" onclick='viewOrder({$id}, {$jsOrderItems})'>👁</button>
                    <button type=\"button\" class=\"btn-edit\" onclick='editOrder({$id}, {$proveedorId}, {$jsFecha}, {$jsEstado})'>✎</button>
                    <button type=\"button\" class=\"btn-delete\" onclick='deleteOrder({$id})'>🗑</button>
                  </td>";
            echo "</tr>";
          }
          
          if (empty($pedidos)) {
              echo "<tr><td colspan='7' class='text-center'>No hay pedidos registrados.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </section>

    <h3 id="view-title" class="mt-5 d-none">Detalles del Pedido #<span id="view-id"></span></h3>
    <section id="view-panel" class="form d-none bg-f9 p-4 rounded-md">
        <h4 class="m-0 border-bottom-gray pb-2">Productos incluidos</h4>
        <ul id="view-items-list" class="list-style-none p-0 m-0"></ul>
        <button type="button" class="btn secondary mt-3" onclick="closeViewPanel()">Cerrar detalles</button>
    </section>

    <h3 id="edit-title" class="mt-5 d-none">Editar Pedido</h3>
    <section class="form">
      <form id="form-edit-order" method="POST" action="php/actions/order_actions.php" class="d-none">
        <input type="hidden" name="action" value="update_order">
        <input type="hidden" name="idPedido" id="edit-id">
        
        <section class="row">
          <section class="flex-1">
            <label>ID Proveedor *</label>
            <input type="number" name="id_proveedor" id="edit-proveedor" required>
          </section>
          <section class="flex-1">
            <label>Fecha</label>
            <input type="date" name="fecha" id="edit-fecha">
          </section>
          <section class="flex-1">
            <label>Estado</label>
            <select name="estado" id="edit-estado">
              <option value="Pendiente">Pendiente</option>
              <option value="Recibido">Recibido</option>
              <option value="Cancelado">Cancelado</option>
            </select>
          </section>
        </section>

        <section class="row mt-4">
          <button type="submit" class="btn pill orange">Guardar cambios</button>
          <button type="button" class="btn secondary" onclick="closeEditForm()">Cancelar</button>
        </section>
      </form>
    </section>

    <footer class="footer">© 2026 Flores Nuria · Administración de pedidos</footer>
  </section>
</main>

<form id="form-delete-order" method="POST" action="php/actions/order_actions.php" class="d-none">
    <input type="hidden" name="action" value="delete_order">
    <input type="hidden" name="idPedido" id="delete-id">
</form>

<form id="form-status-order" method="POST" action="php/actions/order_actions.php" class="d-none">
    <input type="hidden" name="action" value="change_status">
    <input type="hidden" name="idPedido" id="status-id">
    <input type="hidden" name="estado" id="status-val">
</form>

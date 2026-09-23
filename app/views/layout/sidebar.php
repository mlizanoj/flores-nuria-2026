<?php
$currentPage = $page ?? ($_GET['page'] ?? 'dashboard');
function sidebarActive(string $slug, string $current): string {
  return $slug === $current ? 'active' : '';
}
?>
<aside class="sidebar">
  <section class="logo">
    <span class="brand"><img src="img/logo.png"></span>
  </section>
  <nav class="nav" aria-label="navegación principal">
    <h4>Navegación</h4>
    <ul>
      <li class="<?= sidebarActive('dashboard', $currentPage) ?>"><a href="?page=dashboard">Dashboard</a></li>
      <li class="<?= sidebarActive('reports', $currentPage) ?>"><a href="?page=reports">Informes</a></li>
       <li class="<?= sidebarActive('payments', $currentPage) ?>"><a href="?page=payments">Cobros/Pagos</a></li>
      <!-- <li class="<?= sidebarActive('schedule', $currentPage) ?>"><a href="?page=schedule">Agenda</a></li> -->
      <li class="<?= sidebarActive('products', $currentPage) ?>"><a href="?page=products">Productos</a></li>
      <li class="<?= sidebarActive('offers', $currentPage) ?>"><a href="?page=offers">Ofertas</a></li>
      <li class="<?= sidebarActive('orders', $currentPage) ?>"><a href="?page=orders">Pedidos</a></li>
      <!-- <li class="<?= sidebarActive('customers', $currentPage) ?>"><a href="?page=customers">Clientes</a></li> -->
      <li class="<?= sidebarActive('supplie rs', $currentPage) ?>"><a href="?page=suppliers">Proveedores</a></li>
      <!-- <li class="<?= sidebarActive('employees', $currentPage) ?>"><a href="?page=employees">Empleados</a></li> -->
    </ul>
    <!-- <h4>Ventas</h4>
    <ul>
      <li class="<?= sidebarActive('invoices', $currentPage) ?>"><a href="?page=invoices">Facturas emitidas</a></li>
      <li class="<?= sidebarActive('deliveries', $currentPage) ?>"><a href="?page=deliveries">Albaranes emitidos</a></li>
      <li class="<?= sidebarActive('budgets', $currentPage) ?>"><a href="?page=budgets">Presupuestos emitidos</a></li>
    </ul> -->
  </nav>
</aside>

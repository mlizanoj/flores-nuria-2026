<?php
$pageLabelMap = [
  'dashboard' => 'Dashboard',
  'products' => 'Productos',
  'employees' => 'Empleados',
  'reports' => 'Informes',
  'payments' => 'Cobros/Pagos',
  'schedule' => 'Agenda',
  'customers' => 'Clientes',
  'suppliers' => 'Proveedores',
  'invoices' => 'Facturas emitidas',
  'deliveries' => 'Albaranes emitidos',
  'budgets' => 'Presupuestos emitidos',
  'login' => 'Acceder',
  'register' => 'Registrarse',
  'create-employee' => 'Crear Empleado',
  'create-product' => 'Crear Producto',
  'create-customer' => 'Crear Cliente',
  'create-supplier' => 'Crear Proveedor',
  'create-invoice' => 'Crear Factura',
  'create-delivery' => 'Crear Albaran',
  'create-budget' => 'Crear Presupuesto',
  'offers' => 'Ofertas',
  'orders' => 'Pedidos emitidos',

];
$currentPage = $page ?? ($_GET['page'] ?? 'dashboard');
$currentPageLabel = $pageLabelMap[$currentPage] ?? 'Dashboard';
$breadcrumbItems = ['Dashboard'];
if($currentPage !== 'dashboard'){
  $breadcrumbItems[] = $currentPageLabel;
}

$userInitial = 'U';
if (isset($_SESSION['empleado_nombre']) && !empty($_SESSION['empleado_nombre'])) {
  $userInitial = mb_strtoupper(mb_substr($_SESSION['empleado_nombre'], 0, 1, 'UTF-8'));
}
?>
<header class="header">
  <section class="left">
    <section class="logo">
      <button class="menu-toggle" type="button">☰</button>
    </section>
    <nav class="breadcrumbs" aria-label="breadcrumb">
      <ol>
        <?php foreach($breadcrumbItems as $item): ?>
          <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
      </ol>
    </nav>
  </section>
  <section class="right user-menu-container">
    <button class="user-circle" id="userMenuBtn" type="button" aria-haspopup="true" aria-expanded="false"><?= htmlspecialchars($userInitial, ENT_QUOTES, 'UTF-8') ?></button>
    <div class="user-dropdown" id="userDropdown" role="menu">
      <div class="user-dropdown-info">
        <strong><?= htmlspecialchars($_SESSION['empleado_nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?></strong>
        <span class="user-dropdown-role"><?= htmlspecialchars($_SESSION['empleado_puesto'] ?? 'Personal', ENT_QUOTES, 'UTF-8') ?></span>
      </div>
      <div class="dropdown-divider"></div>
      <a href="php/actions/auth_actions.php?action=logout" role="menuitem"><span class="icon">🚪</span> Cerrar Sesión</a>
    </div>
  </section>
</header>

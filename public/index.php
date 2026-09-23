<?php

use classes\Empleado;

require_once 'autoloader.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!Empleado::checkSession()) {
    include __DIR__ . '/public/login.php';
    exit;
}

$global_msg = '';
if (isset($_SESSION['msg'])) {
    $global_msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

// Página principal: ensamblado visual. Sin lógica de negocio.
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Floristería - Panel</title>
  <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/logo_fondo_blanco.jpg">
  <script src="js/menu.js?v=<?php echo time(); ?>" defer></script>
</head>
<body>
  <section class="app">
    <?php
      $page = $_GET['page'] ?? 'dashboard';
      // __DIR__ es la ruta absoluta del directorio actual, lo que evita problemas attm:D
      include __DIR__ . '/public/sidebar.php';
//      Paginas permitidas para evitar fallos
      if(in_array($page, ['dashboard', 'products', 'create_product', 'orders', 'create_order', 'tikets', 'create_tiket', 'payments', 'create_payment', 'reports','suppliers', 'offers'])) {
        include __DIR__ . '/public/' . $page . '.php';
      } else {
        include __DIR__ . '/public/dashboard.php';
      }
    ?>
  </section>
</body>
</html>

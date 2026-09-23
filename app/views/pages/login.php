<?php

use classes\Empleado;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../php/clases.php';

// If already logged in, redirect to dashboard
if (Empleado::checkSession()) {
    header("Location: index.php");
    exit;
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Iniciar Sesión - Flores Nuria</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="icon" href="img/logo_fondo_blanco.jpg">
</head>
<body>
  <section class="login-wrapper">
    <article class="login-card">
      <img src="img/logo.png" alt="Flores Nuria Logo" class="login-logo">
      <h1 class="login-title">Flores Nuria</h1>
      <p class="login-subtitle">Gestión de Administración</p>
      
      <?php if (!empty($error)): ?>
        <div class="login-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      
      <form action="php/actions/auth_actions.php" method="POST" class="login-form">
        <input type="hidden" name="action" value="login">
        <div class="form-group">
          <label for="correo">Correo Electrónico</label>
          <input type="email" id="correo" name="correo" placeholder="correo@ejemplo.com" required autocomplete="email">
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
        </div>
        <button type="submit" class="login-btn">Iniciar Sesión</button>
      </form>
    </article>
  </section>
</body>
</html>

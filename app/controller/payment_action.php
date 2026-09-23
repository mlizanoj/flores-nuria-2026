<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'create_payment') {

        // 1. Recoger y sanear los datos del formulario (respetando tus nombres de campos)
        $pedido_id = filter_input(INPUT_POST, 'pedido_id', FILTER_VALIDATE_INT);
        $cliente   = filter_input(INPUT_POST, 'cliente', FILTER_SANITIZE_STRING) ?? '';
        $monto     = filter_input(INPUT_POST, 'monto', FILTER_VALIDATE_FLOAT);
        $metodo    = filter_input(INPUT_POST, 'metodo', FILTER_SANITIZE_STRING);
        $fecha     = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);

        // 2. Validar que los campos obligatorios (*) no estén vacíos
        if ($pedido_id && $monto && $metodo && $fecha) {

            try {
                // 3. Instanciar tu objeto y guardar en la base de datos
                // Esto dependerá de cómo tengas estructurada tu clase, por ejemplo:
                /*
                $nuevoPago = new Pago();
                $nuevoPago->setPedidoId($pedido_id);
                $nuevoPago->setCliente($cliente);
                $nuevoPago->setMonto($monto);
                $nuevoPago->setMetodo($metodo);
                $nuevoPago->setFecha($fecha);

                $guardado = $nuevoPago->save(); // Método que ejecuta el INSERT SQL
                */

                $guardado = true;
                if ($guardado) {
                    $_SESSION['global_msg'] = "<div class='alert success'>Pago registrado correctamente.</div>";
                    header("Location: ../../index.php?page=payments");
                    exit;
                } else {
                    $_SESSION['global_msg'] = "<div class='alert error'>Error al registrar el pago.</div>";
                }

            } catch (Exception $e) {
                $_SESSION['global_msg'] = "<div class='alert error'>Error del servidor: " . $e->getMessage() . "</div>";
            }

        } else {
            $_SESSION['global_msg'] = "<div class='alert error'>Por favor, completa todos los campos obligatorios (*).</div>";
        }

        header("Location: ../../index.php?page=create_payment");
        exit;
    }
} else {
    header("Location: ../../index.php");
    exit;
}
?>
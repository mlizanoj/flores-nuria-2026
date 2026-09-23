<?php

use classes\BolsaCompra;
use classes\Pedido;

session_start();
require_once __DIR__ . '/../clases.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create_order') {
        $proveedor = $_POST['id_proveedor'] ?? '';
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        $estado = $_POST['estado'] ?? 'Pendiente';
        $prods = $_POST['productos'] ?? [];
        $cants = $_POST['cantidades'] ?? [];
        
        if (!empty($proveedor)) {
            $bolsa = new BolsaCompra();
            for ($i = 0; $i < count($prods); $i++) {
                if (!empty($prods[$i]) && !empty($cants[$i]) && $cants[$i] > 0) {
                    $bolsa->addProducto($prods[$i], $cants[$i]);
                }
            }
            
            $nuevoPedido = new Pedido(null, $estado, $bolsa, $fecha, 0, $proveedor);
            if ($nuevoPedido->IngresarPedido()) {
                $_SESSION['msg'] = "<div class='color-green mb-3'>Pedido creado exitosamente.</div>";
            } else {
                $_SESSION['msg'] = "<div class='color-red mb-3'>Error al guardar el pedido.</div>";
            }
        } else {
            $_SESSION['msg'] = "<div class='color-red mb-3'>El proveedor es obligatorio.</div>";
        }
        header("Location: ../../index.php?page=orders");
        exit;
    } 
    elseif ($action === 'update_order') {
        $idEdit = $_POST['idPedido'] ?? '';
        $provEdit = $_POST['id_proveedor'] ?? '';
        $fechaEdit = $_POST['fecha'] ?? '';
        $estEdit = $_POST['estado'] ?? '';
        if (!empty($idEdit) && !empty($provEdit)) {
            $bolsaVacia = new BolsaCompra(); // Evita errores
            $pedActualizar = new Pedido($idEdit, $estEdit, $bolsaVacia, $fechaEdit, 0, $provEdit);
            if ($pedActualizar->ActualizarPedido()) {
                $_SESSION['msg'] = "<div class='color-green mb-3'>Pedido actualizado correctamente.</div>";
            } else {
                $_SESSION['msg'] = "<div class='color-red mb-3'>No se realizaron cambios o hubo un error.</div>";
            }
        }
        header("Location: ../../index.php?page=orders");
        exit;
    } 
    elseif ($action === 'delete_order') {
        $idDel = $_POST['idPedido'] ?? '';
        if (!empty($idDel)) {
            $pedEliminar = new Pedido($idDel, '', null, '', 0, 0);
            if ($pedEliminar->EliminarPedido()) {
                $_SESSION['msg'] = "<div class='color-green mb-3'>Pedido eliminado correctamente.</div>";
            } else {
                $_SESSION['msg'] = "<div class='color-red mb-3'>Error al eliminar pedido.</div>";
            }
        }
        header("Location: ../../index.php?page=orders");
        exit;
    }
    elseif ($action === 'change_status') {
        $idStatus = $_POST['idPedido'] ?? '';
        $nuevoEstado = $_POST['estado'] ?? '';
        if (!empty($idStatus) && !empty($nuevoEstado)) {
            $ped = new Pedido($idStatus, $nuevoEstado, null, null, null, null);
            if ($ped->ModificarEstado()) {
                $_SESSION['msg'] = "<div class='color-green mb-3'>Estado del pedido actualizado.</div>";
            } else {
                $_SESSION['msg'] = "<div class='color-red mb-3'>Error al cambiar el estado.</div>";
            }
        }
        header("Location: ../../index.php?page=orders");
        exit;
    }
}
header("Location: ../../index.php");
exit;

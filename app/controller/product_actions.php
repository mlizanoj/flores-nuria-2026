<?php

use classes\BD;
use classes\Producto;

session_start();
require_once __DIR__ . '/../clases.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create_product') {
        $nombre = $_POST['nombre'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $stock = $_POST['stock'] ?? '';
        $iva = $_POST['iva'] ?? 21.00;
        
        if (!empty($nombre) && is_numeric($precio) && is_numeric($stock) && is_numeric($iva)) {
            $nuevoProducto = new Producto(null, $nombre, $precio, $stock, null, $iva);
            if ($nuevoProducto->IngresarProducto()) {
                $_SESSION['msg'] = "<div class='color-green mb-3'>Producto creado correctamente.</div>";
            } else {
                $_SESSION['msg'] = "<div class='color-red mb-3'>Error al guardar el producto.</div>";
            }
        } else {
            $_SESSION['msg'] = "<div class='color-red mb-3'>Datos inválidos. Verifica el formulario.</div>";
        }
        header("Location: ../../index.php?page=products");
        exit;
    } 
    elseif ($action === 'update_product') {
        $idEdit = $_POST['idProducto'] ?? '';
        $nombreEdit = $_POST['nombre'] ?? '';
        $precioEdit = $_POST['precio'] ?? '';
        $stockEdit = $_POST['stock'] ?? '';
        $ivaEdit = $_POST['iva'] ?? 21.00;
        $quitarOferta = $_POST['quitar_oferta'] ?? '0';
        
        if (!empty($idEdit) && !empty($nombreEdit) && is_numeric($precioEdit) && is_numeric($stockEdit) && is_numeric($ivaEdit)) {
            $prodActualizar = new Producto($idEdit, $nombreEdit, $precioEdit, $stockEdit, null, $ivaEdit);
            if ($prodActualizar->ActualizarProducto()) {
                if ($quitarOferta === '1') {
                    $conn = BD::FloresNuria();
                    $stmtDel = $conn->prepare("DELETE FROM oferta_producto WHERE id_producto = ?");
                    $stmtDel->execute([$idEdit]);
                }
                $_SESSION['msg'] = "<div class='color-green mb-3'>Producto actualizado correctamente.</div>";
            } else {
                $_SESSION['msg'] = "<div class='color-red mb-3'>No se realizaron cambios o hubo un error.</div>";
            }
        }
        header("Location: ../../index.php?page=products");
        exit;
    } 
    elseif ($action === 'delete_product') {
        $idDel = $_POST['idProducto'] ?? '';
        if (!empty($idDel)) {
            $prodEliminar = new Producto($idDel, '', 0, 0, null);
            if ($prodEliminar->EliminarProducto()) {
                $_SESSION['msg'] = "<div class='color-green mb-3'>Producto eliminado correctamente.</div>";
            } else {
                $_SESSION['msg'] = "<div class='color-red mb-3'>Error al eliminar producto.</div>";
            }
        }
        header("Location: ../../index.php?page=products");
        exit;
    }
}
header("Location: ../../index.php");
exit;

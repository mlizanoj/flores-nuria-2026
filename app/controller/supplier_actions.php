<?php

use classes\Proveedor;

session_start();
require_once __DIR__ . '/../clases.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create_supplier') {
        $nombre = $_POST['nombre'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $correo = $_POST['correo'] ?? '';
        
        if (!empty($nombre)) {
            $nuevoProveedor = new Proveedor(null, $nombre, $direccion, $telefono, $correo);
            if ($nuevoProveedor->IngresarProveedor()) {
                $_SESSION['msg'] = "<div class='color-green mb-3'>Proveedor creado correctamente.</div>";
            } else {
                $_SESSION['msg'] = "<div class='color-red mb-3'>Error al guardar el proveedor.</div>";
            }
        } else {
            $_SESSION['msg'] = "<div class='color-red mb-3'>El nombre es obligatorio.</div>";
        }
        header("Location: ../../index.php?page=suppliers");
        exit;
    } 
    elseif ($action === 'update_supplier') {
        $idEdit = $_POST['idProveedor'] ?? '';
        $nombreEdit = $_POST['nombre'] ?? '';
        $dirEdit = $_POST['direccion'] ?? '';
        $telEdit = $_POST['telefono'] ?? '';
        $corEdit = $_POST['correo'] ?? '';
        
        if (!empty($idEdit) && !empty($nombreEdit)) {
            $provActualizar = new Proveedor($idEdit, $nombreEdit, $dirEdit, $telEdit, $corEdit);
            if ($provActualizar->ActualizarProveedor()) {
                $_SESSION['msg'] = "<div class='color-green mb-3'>Proveedor actualizado correctamente.</div>";
            } else {
                $_SESSION['msg'] = "<div class='color-red mb-3'>No se realizaron cambios o hubo un error.</div>";
            }
        }
        header("Location: ../../index.php?page=suppliers");
        exit;
    } 
    elseif ($action === 'delete_supplier') {
        $idDel = $_POST['idProveedor'] ?? '';
        if (!empty($idDel)) {
            $provEliminar = new Proveedor($idDel, '', '', '', '');
            if ($provEliminar->EliminarProveedor()) {
                $_SESSION['msg'] = "<div class='color-green mb-3'>Proveedor eliminado correctamente.</div>";
            } else {
                $_SESSION['msg'] = "<div class='color-red mb-3'>Error al eliminar proveedor.</div>";
            }
        }
        header("Location: ../../index.php?page=suppliers");
        exit;
    }
}
header("Location: ../../index.php");
exit;

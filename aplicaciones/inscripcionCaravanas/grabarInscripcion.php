<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorInscripcionCaravana.php';

    $usuario = $_SESSION['usuario'];
    $a = htmlspecialchars($_POST['a'], ENT_NOQUOTES, 'UTF-8');
    $b = htmlspecialchars($_POST['b'], ENT_NOQUOTES, 'UTF-8');
    $c = htmlspecialchars($_POST['c'], ENT_NOQUOTES, 'UTF-8');
    $d = htmlspecialchars($_POST['d'], ENT_NOQUOTES, 'UTF-8');
    $e = htmlspecialchars($_POST['e'], ENT_NOQUOTES, 'UTF-8');
    $f = htmlspecialchars($_POST['f'], ENT_NOQUOTES, 'UTF-8');
    $g = htmlspecialchars($_POST['g'], ENT_NOQUOTES, 'UTF-8');
    $h = htmlspecialchars($_POST['h'], ENT_NOQUOTES, 'UTF-8');
    $i = htmlspecialchars($_POST['i'], ENT_NOQUOTES, 'UTF-8');
    $j = htmlspecialchars($_POST['j'], ENT_NOQUOTES, 'UTF-8');
    $k = htmlspecialchars($_POST['k'], ENT_NOQUOTES, 'UTF-8');
    $l = htmlspecialchars($_POST['l'], ENT_NOQUOTES, 'UTF-8');
    $m = htmlspecialchars($_POST['m'], ENT_NOQUOTES, 'UTF-8');

    try {
        $conexion = new Conexion();
        $cic      = new ControladorInscripcionCaravana();
        $registro = $cic->guardarInscripcion($conexion, $usuario, $a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m);
        echo 'El registro ' . $registro . ' ha sido actualizado.';
    } catch (Exception $e) {
        echo 'Ocurrió un error al actualizar el registro. ' . $e->getMessage();
    }
?>


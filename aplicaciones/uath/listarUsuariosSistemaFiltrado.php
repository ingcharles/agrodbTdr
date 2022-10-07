<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorEmpleados.php';


$conexion = new Conexion();
$ca = new ControladorAplicaciones('uath');
$identificador = htmlspecialchars($_POST['identificador'], ENT_NOQUOTES, 'UTF-8');
$nombres = htmlspecialchars($_POST['nombres'], ENT_NOQUOTES, 'UTF-8');
$apellidos = htmlspecialchars($_POST['apellidos'], ENT_NOQUOTES, 'UTF-8');

$ce = new ControladorEmpleados();
try{
    $usuarios = $ce->obtenerUsuariosPorFiltro($conexion, $identificador, $nombres, $apellidos);
} catch (Exception $ex){
    echo $conexion->mensajeError;
}
echo '<table>
        <thead>
            <tr><th>#</th><th>Identificación</th><th>Nombre</th><th>Estado</th></tr>
        </thead>';
$contador = 1;
while($usuario = pg_fetch_assoc($usuarios)){
    echo '<tr id="' . $usuario['identificador'] . '" class="item" data-rutaAplicacion="uath" data-opcion="abrirUsuarioSistema" ondragstart="drag(event)" draggable="true" data-destino="detalleItem">
        <td>'.($contador++).'</td>
        <td>'.$usuario['identificador'].'</td>
        <td>'.$usuario['apellido'].', '.$usuario['nombre'].'</td>
        <td>'.($usuario['estado_empleado']==''?'Actualizar catastro':$usuario['estado_empleado']).'</td>
        </tr>';
}
echo '</table>';
<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$opcion = $_POST['opcion'];
$idTipoProducto = $_POST['tipoProducto'];
$idSubtipoProducto = $_POST['subtipoProducto'];

switch ($opcion) {
    
    case 'tipoProducto':       
        
        echo '<select id="subtipoProducto" name="subtipoProducto" required>
				<option value="">Seleccione...</option>';
                $qSubtipoProducto = $cc->listarSubTipoProductoXtipoProducto($conexion, $idTipoProducto);
                while ($fila = pg_fetch_assoc($qSubtipoProducto)){
                    echo '<option value="'.$fila['id_subtipo_producto']. '">'. $fila['nombre'] .'</option>';
                }        
        echo '</select>';
        
    break;
    
    case 'subtipoProducto':
        
        $qProducto = $cc->listarProductoXsubTipoProducto($conexion, $idSubtipoProducto);
        echo '<select id="producto" name="producto" required>
				<option value="">Seleccione...</option>';
                $qProducto = $cc->listarProductoXsubTipoProducto($conexion, $idSubtipoProducto);
                while ($fila = pg_fetch_assoc($qProducto)){
                    echo '<option value="'.$fila['id_producto']. '">'. $fila['nombre_comun'] .'</option>';
                }
        echo '</select>';
        
    break;
     
}


?>

<script type="text/javascript">

$(document).ready(function(){
    distribuirLineas();
    
});

$("#subtipoProducto").change(function(event){
	event.preventDefault();
	event.stopImmediatePropagation();
    $('#reporteGeneralOperadores').attr('data-opcion','accionesReporteGeneralOperadores');
    $('#reporteGeneralOperadores').attr('data-destino','resultadoProducto');
    $('#opcion').val('subtipoProducto');
    abrir($("#reporteGeneralOperadores"),event,false);
});

</script>
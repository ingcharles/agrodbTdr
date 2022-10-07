<?php
//echo "holaaaaaaaa<<<<<<";
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$controladorCatalogos = new ControladorAdministrarCatalogos();
$idCatalogo = $_POST['cbSubCatalogo'];
$idItem = $_POST['idItem'];

$opcion= htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');


switch ($opcion){
    case'subcatalogo':
        $resultado= $controladorCatalogos->listarItems($conexion, $idCatalogo, '1');        
        
        echo '<label>Seleccione uno o varios Productos</label>
            
					<div class="seleccionTemporal">
						<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
				    	<label >Seleccionar todos </label>
					</div>
            
				<hr>
			 <div id="contenedorProducto"><table style="border-collapse: initial;"><tr>';
        $agregarDiv = 0;
        $cantidadLinea = 0;
        while ($fila = pg_fetch_assoc($resultado)){
            
            echo '<td style="text-align:left;"><input id="'.$fila['id_item'].'" type="checkbox" name="producto[]" class="productoActivar" data-resetear="no" value="'.$fila['id_item'].'" />
			 	<label for="'.$fila['id_item'].'">'.$fila['nombre'].'</label></td>';
            $agregarDiv++;
            
            if(($agregarDiv % 3) == 0){
                echo '</tr><tr>';
                $cantidadLinea++;
            }
            
            if($cantidadLinea == 9){
                echo '<script type="text/javascript">$("#contenedorProducto").css({"height": "250px", "overflow": "auto"}); </script>';
            }
        }
        echo '</tr></table></div>
             <button type="button" id="agregarItem" class="mas">Agregar Item</button>';
    break;
}

?>

<script type="text/javascript">

$("#agregarItem").click(function(event){
	event.preventDefault();	
	$("#frmItem").attr("data-opcion","asociarItemsCatalogo");	
	ejecutarJson($("#frmItem"));
	//return false;
});

</script>
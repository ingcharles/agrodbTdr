<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAplicaciones.php';


$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$identificadorOperador = $_SESSION['usuario'];

$qProveedoresOrganico = $cro->listarOperacionesOperadorXAreaTematicaOperacionXCodigoTipoOperacion($conexion, $identificadorOperador, " in ('declararProveedor')", 400, 0, 'AI', " in ('COM', 'PRC')");
$qProveedores = $cro->listarProveedoresOperador($conexion, $identificadorOperador);

?>

<header>													
    <nav>
    	<?php 
            $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);

			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
			}
		?>
		</nav>
	</header>
    
    <h2>Registrar Proveedores Comercio Exterior</h2>

	<table id="interior">
		<thead>
			<tr>
				<th colspan="2">Proveedores Nacionales</th>
			</tr>
			<tr>
				<th>#</th>
				<th>Código Proveedor</th>
				<th>Producto</th>
				<th>País</th>
			</tr>
		</thead>
	</table>
	
	<table id="exterior">
		<thead>
			<tr>
				<th colspan="2">Comercio Exterior</th>
			</tr>
			<tr>
				<th>#</th>
				<th>Código Proveedor</th>
				<th>Mi Operación</th>
				<th>Producto</th>
				<th>País</th>
			</tr>
		</thead>
	</table>
			
    <?php    
    
    $contador = 0;
    while($proveedores = pg_fetch_assoc($qProveedores)){
        if($proveedores['operacion_operador']){
            $categoria = 'exterior';
        }else{
            $categoria = 'interior';
        }
        
        $contenido =	'<tr
    						id="'.$proveedores['id_proveedor'].'"
    						class="item"
    						data-rutaAplicacion="registroOperador"
    						data-opcion="abrirProveedor"
    						ondragstart="drag(event)"
    						draggable="true"
    						data-destino="detalleItem">
    					<td>'.++$contador.'</td>
    					<td style="white-space:nowrap;"><b>'.$proveedores['codigo_proveedor'].'</b></td>';
    
        if($categoria == 'exterior'){
            $contenido .=	'<td>'.$proveedores['nombre_operacion'].'</td>';
        }
        
        $contenido .= '<td>'.$proveedores['nombre_producto'].'</td>
    					<td>'.$proveedores['nombre_pais'].'</td>
    				</tr>';
        ?>
    			<script type="text/javascript">
    					var contenido = <?php echo json_encode($contenido);?>;
    					var categoria = <?php echo json_encode($categoria);?>;
    					$("#"+categoria+"").append(contenido);
    			</script>
    			<?php 		
    			
    }

if(pg_num_rows($qProveedoresOrganico)>0){
    
?>

    <h2>Registrar Proveedores Orgánicos</h2>
    <table id="organicos">
        <thead>
            <tr>
            	<th colspan="2">Proveedores Orgánicos</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Provincia</th>
                <th>Tipo operación</th>
                <th>Nombre del área</th>
                 <th>Estado</th>
            </tr>
        </thead>
        </table>
    <?php
    
    while($fila = pg_fetch_assoc($qProveedoresOrganico)){
        
        $estado = "";
        
        switch($fila['estado']){
            
            case 'registrado':
                $estado = "Registrado";
                $estilo = "";
            break;
            case 'subsanacionProducto':
                $estado = "Subsanacion";
                $estilo = 'notificacionFilaSubsanacion';
            break;
            case 'declararProveedor':
                $estado = "Declarar Proveedor";
                $estilo = 'notificacionFilaCargarRendimiento';
            break;
        }        
        
        $categoria = 'organicos';
        
        $nombreArea = $cro->buscarNombreAreaPorSitioPorTipoOperacion($conexion, $fila['id_tipo_operacion'], $identificadorOperador, $fila['id_sitio'], $fila['id_operacion']);
        
        $contenido =	'<tr
							id="'.$fila['id_operacion'].'"
							class="item '.$estilo.'"
							data-rutaAplicacion="registroOperador"
							data-opcion="declararProveedorOrganico"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
						<td>'.++$contador.'</td>
						<td style="white-space:nowrap;">'.(strlen($fila['provincia'])>14?(substr($cro->reemplazarCaracteres($fila['provincia']),0,14).'...'):(strlen($fila['provincia'])>0?$fila['provincia']:'')).'</td>
						<td style="white-space:nowrap;">'.(strlen($fila['nombre_tipo_operacion'])>30?(substr($cro->reemplazarCaracteres($fila['nombre_tipo_operacion']),0,30).'...'):(strlen($fila['nombre_tipo_operacion'])>0?$fila['nombre_tipo_operacion']:'')).'</td>
                        <td style="white-space:nowrap;">'.(strlen($nombreArea)>42?(substr($cro->reemplazarCaracteres($nombreArea),0,42).'...'):(strlen($nombreArea)>0?$nombreArea:'')).'</td>
                        <td style="white-space:nowrap;">'.$estado.'</b></td>';
        ?>
        <script type="text/javascript">
				var contenido = <?php echo json_encode($contenido);?>;
				var categoria = <?php echo json_encode($categoria);?>;
				$("#"+categoria+"").append(contenido);
		</script>
    
    <?php
    }
} ?> 
 
<script>
$(document).ready(function(){	

	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
    
    $("#listadoItems").removeClass("comunes");
    $("#listadoItems").addClass("lista");
    $("#exterior tbody tr").length == 0 ? $("#exterior").remove():"";
    $("#interior tbody tr").length == 0 ? $("#interior").remove():"";
	
});
</script>
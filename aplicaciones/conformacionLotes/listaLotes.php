<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cl = new ControladorLotes();
$cac = new ControladorAdministrarCaracteristicas();
$usuario=$_SESSION['usuario'];


$itemsFiltrados[] = array();

$res = $cl->listarLotes($conexion, $_POST['numeroLote'], $_POST['codigoLoteFiltro'], $_POST['fechaConformacion'], $usuario,$_POST['productosfiltro']);

while($fila = pg_fetch_assoc($res)){
	
	$fecha = strtotime($fila['fecha_conformacion']);
	$formato = date('Y-m-d',$fecha);

	$itemsFiltrados[] = array('<tr
				id="'.$fila['id_lote'].'"
				class="item"
				data-rutaAplicacion="conformacionLotes"
				data-opcion="abrirLote"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$fila['numero_lote'].'</b></td>       			
				<td>'.$fila['codigo_lote'].'</td>
				<td>'.$formato.'</td>
				<td>'.$fila['producto'].'</td>				
				<td>'.$fila['cantidad'].'</td>
			</tr>');
}
/*
if(pg_num_rows($res)==0){
	echo '<script type="text/javascript">$("#mensajeError").html("No existen registros con esos parámetros de búsqueda").addClass("alerta");</script>';
}*/

?>
<header>
	<h1>Conformar Lote</h1>	
		<nav>
		<form id="filtrar" data-rutaAplicacion="conformacionLotes" data-opcion="listaLotes" data-destino="listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
				<table class="filtro" style='width: 400px;'>
					<tbody>
					<tr>
						<th colspan="3">Buscar lote:</th>
					</tr>
					<tr>
					<td>Producto:</td>
					<td>					
						<select id="productosfiltro" name="productosfiltro" style="width:100%">
						<option value="">Seleccione....</option>
						<?php 
						$res= $cl->listarProductosTrazabilidadTodos($conexion);
						
						while ($produFila = pg_fetch_assoc($res)){
						    echo '<option value="' . $produFila['id_producto'] . '">' . $produFila['nombre_comun'].'</option>';
						}
						?>					
						</select>
					</td>
					</tr>
					<tr>
					<td>*Lote Nro:</td>
					<td> <input id="numeroLote" type="text" name="numeroLote" style="width:100%">	</td>
					</tr>
					<tr>
						<td>*Código Lote:</td>
						<td> <input id="codigoLoteFiltro" type="text" name="codigoLoteFiltro" style="width:100%">	</td>
					</tr>
					<tr>
						<td>*Fecha conformación:</td>
						<td> <input id="fechaConformacion" type="text" name="fechaConformacion" maxlength="10" style="width:100%">	</td>
					</tr>					
					<tr>
						<td colspan="4"> <button id='buscar'>Buscar</button></td>						
					</tr>
					<tr>
						<td colspan="4" style='text-align:center' id="mensajeError"></td>
						
					</tr>
					</tbody>
					</table>
				</form>
</nav>
</header>
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
 
 <div id="paginacion" class="normal">
 </div>

<?php 

echo '
    <table id="tablaItems">
    	<thead>
    		<tr>
    			<th>#</th>
    			<th>Lote Nro.</th>
    			<th>Cód. Lote</th>
    			<th>Fecha de conformación</th>
    			<th>Producto</th>			
    			<th>Cantidad</th>				
    		</tr>
    	</thead>
    	<tbody>
    	</tbody>
    </table>
';
    
?>

 


<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
					
	});

	$("#fechaConformacion").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});
	
	$("#filtrar").submit(function(event){
		event.preventDefault();
		$(".alerta").removeClass("alerta");
		var error = false;

		if($("#numeroLote").val()==""  && $("#codigoLote").val()==""  && $("#fechaConformacion").val()==""){	
			 error = true;	
			 $("#mensajeError").html("Por favor ingrese al menos un campo para realizar la consulta").addClass('alerta');	
		}
		
		if(!error){	
			abrir($('#filtrar'),event, false);
		}		

	});

	$("#_eliminar").click(function(e){	
		if($("#cantidadItemsSeleccionados").text()<1){		
			return false;
		}
		
	});

</script>


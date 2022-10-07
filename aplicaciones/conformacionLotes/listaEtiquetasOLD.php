<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorLotes.php';

$conexion = new Conexion();
//$cu = new ControladorUsuarios();
$cl = new ControladorLotes();

$itemsFiltrados[] = array();
$usuario=$_SESSION['usuario'];

$res = $cl->listarLotesEtiquetados($conexion, $_POST['loteNro'], $_POST['codigoLote'], $_POST['fechaConformacion'], $_POST['fechaEtiquetado'], $usuario,$_POST['productosfiltro']);

while($fila = pg_fetch_assoc($res)){
	
	$fecha = strtotime($fila['fecha_conformacion']);
	$formato = date('Y-m-d',$fecha);

	$itemsFiltrados[] = array('<tr
				id="'.$fila['id_lote'].'"
				class="item"
				data-rutaAplicacion="conformacionLotes"
				data-opcion="abrirLoteEtiquetado"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$fila['numero_lote'].'</b></td>
				<td>'.$fila['codigo_lote'].'</td>
				<td>'.$formato.'</td>
				<td>'.date('Y-m-d',strtotime($fila['fecha_etiquetado'])).'</td>				
				<td>'.$fila['cantidad'].'</td>
			</tr>');
}

?>
<header>
	<h1>Etiquetar Lote</h1>	
		<nav>
		<form id="filtrar" data-rutaAplicacion="conformacionLotes" data-opcion="listaEtiquetas" data-destino="listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
				<table class="filtro" style='width: 400px;'>
					<tbody>
					<tr>
						<th colspan="3">Buscar Lote:</th>
					</tr>
					<tr>
					<td>Producto:</td>
					<td>					
						<select id="productosfiltro" name="productosfiltro" style="width:76%">
						<option value="">Seleccione....</option>
						<?php 
						$val=0;
						$tipo = $cl->obtenerCodigoTipoOperacion($conexion,"SV","ACO");
						$tipofila =pg_fetch_assoc($tipo);
						$productos = $cl->listarProductosTrazabilidad($conexion,$usuario);
						while ($produFila = pg_fetch_assoc($productos)){
							if($produFila['total']=='2' || ($produFila['total']=='1' && $produFila['tipo']==$tipofila['id_tipo_operacion']) ){
								echo '<option value="' . $produFila['id_producto'] . '">' . $produFila['nombre_comun'].'</option>';	
							}
						}		 			
						?>					
						</select>
					</td>
					</tr>
					<tr>
					<td>*Lote Nro:</td>
					<td> <input id="loteNro" type="text" name="loteNro"></td>
					</tr>
					<tr>
						<td>*Código Lote:</td>
						<td> <input id="codigoLote" type="text" name="codigoLote"></td>
					</tr>
					<tr>
						<td>*Fecha conformación:</td>
						<td> <input id="fechaConformacion" type="text" name="fechaConformacion" maxlength="10">	</td>
					</tr>
					<tr>
						<td>*Fecha Etiquetado:</td>
						<td> <input id="fechaEtiquetado" type="text" name="fechaEtiquetado" maxlength="10">	</td>
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

 
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Lote Nro.</th>
			<th>Código Lote</th>
			<th>Fecha de conformación</th>
			<th>Fecha de etiquetado</th>			
			<th>Cantidad</th>
							
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
					
	});

	$( "#fechaConformacion" ).datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});
	
	$( "#fechaEtiquetado" ).datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	$("#filtrar").submit(function(event){
		event.preventDefault();
		$(".alerta").removeClass("alerta");
		var error = false;
		
		if($("#loteNro").val()==""  && $("#codigoLote").val()==""  && $("#fechaConformacion").val()=="" && $("#fechaEtiquetado").val()==""){	
			 error = true;	
			 $("#mensajeError").html("Por favor ingrese al menos un campo para realizar la consulta").addClass('alerta');	
		}
		
		if(!error){	
			abrir($('#filtrar'),event, false);
		}
		
	});

</script>


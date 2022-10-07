<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorLotes.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cl = new ControladorLotes();
$usuario=$_SESSION['usuario'];


$itemsFiltrados[] = array();

$res = $cl->listarRegistros($conexion, $_POST['identificadorProveedor'], $_POST['nombreProveedor'], $_POST['fechaIngreso'],$_POST['estadoRegistro'],$usuario,$_POST['productosfiltro']);
	
	while($fila = pg_fetch_assoc($res)){
		
		$fecha = strtotime($fila['fecha_ingreso']);		
		$formato = date('Y-m-d',$fecha);
	
		$itemsFiltrados[] = array('<tr
				id="'.$fila['id_registro'].'"
				class="item"
				data-rutaAplicacion="conformacionLotes"
				data-opcion="abrirProductoProveedor"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td ><b>'.$fila['codigo_registro'].'</b></td>
       			<td >'.$formato.'</td>
				<td>'.$fila['nombre_proveedor'].'</td>
				<td>'.$fila['nombre_producto'].'</td>
				<td>'.$fila['cantidad'].'</td>
				<td>'.$fila['estado'].'</td>
			</tr>');
	}	
	
?>
<header>
	<h1>Registrar Ingresos</h1>	
		<nav>
		<form id="filtrar" data-rutaAplicacion="conformacionLotes" data-opcion="listaProductoProveedor" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
				<table class="filtro" style='width: 400px;'>
					<tbody>
					<tr>
						<th colspan="3">Buscar Proveedor:</th>
					</tr>
						<tr>
						<td>* Identificación Proveedor:</td>
						<td> <input id="identificadorProveedor" type="text" name="identificadorProveedor"
						 maxlength="13"></td>
					</tr>
					<tr>
						<td>* Nombre Proveedor:</td>
						<td> <input id="nombreProveedor" type="text" name="nombreProveedor">	</td>
					</tr>
					<tr>
					<td>Producto:</td>
					<td>					
						<select id="productosfiltro" name="productosfiltro" style="width:86%">
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
						<td>Fecha ingreso:</td>
						<td> <input id="fechaIngreso" type="text" name="fechaIngreso"></td>
					</tr>
					<tr>
						<td>Estado:</td>
						<td> 
						<select id="estadoRegistro" name="estadoRegistro" style="width:86%">
							<option value="">Seleccione</option>
							<option value="1">Disponible</option>
							<option value="2">Utilizado</option>
						</select>
						</td>
					</tr>					
					<tr>						
						<td colspan="3"> <button id='buscar'>Buscar</button></td>
					</tr>
					<tr>
						<td colspan="4" style='text-align:center' id="mensajeError"></td>
					</tr>
					</tbody>
					</table>
				</form>
				<!-- <td id="mensajeError"></td> -->
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
 
<table id="tablaItems" class="listaRegistros">
	<thead>
		<tr>
			<th>#</th>
			<th>Código Ingreso</th>
			<th>Fecha Ingreso</th>
			<th>Nombre Proveedor</th>
			<th>Producto</th>
			<th>Cantidad</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);		

		$("#identificadorProveedor").numeric();

	});

	$("#fechaIngreso").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	
	$("#filtrar").submit(function(event){
		event.preventDefault();		
		var error = false;		
		if ($("#nombreProveedor").val().length <= 2 && $("#identificadorProveedor").val()=="" && $("#fechaIngreso").val()=="" && $("#estadoRegistro").val()=="") {
			error = true;
	    	$("#mensajeError").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
	    }	

		if($("#identificadorProveedor").val()==""  && $("#nombreProveedor").val()==""  && $("#fechaIngreso").val()=="" && $("#estadoRegistro").val()=="" ){	
			 error = true;	
			 $("#mensajeError").html("Por favor ingrese al menos un campo para realizar la consulta").addClass('alerta');	
		}

		if ($("#identificadorProveedor").val().length >= 1 && $("#identificadorProveedor").val().length < 10 ){
			error = true;	
			$("#mensajeError").html("El número de cédula esta incompleto").addClass('alerta');
		}


		if ($("#identificadorProveedor").val().length > 10 && $("#identificadorProveedor").val().length < 13 ){
			error = true;	
			$("#mensajeError").html("El número de ruc esta incompleto").addClass('alerta');
		}		
		
		if(!error){	
			abrir($('#filtrar'),event, false);
		}		
	});

	$("#_eliminar").click(function(e){
		
		if($("#cantidadItemsSeleccionados").text()<1){
			$("#mensajeError").html("Por favor seleccione un registro a eliminar.").addClass('alerta');			
			return false;
		}
		
	});



</script>


<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

$conexion = new Conexion();
$ce = new ControladorMercanciasSinValorComercial();

$solicitante=$_POST['solicitante'];
$numeroSolicitud=$_POST['numeroSolicitud'];
$fecha=$_POST['fecha'];

$itemsFiltrados[] = array();

$res = $ce->listaSolicitudes($conexion, "Importacion",$solicitante,$numeroSolicitud,$fecha);

while($fila = pg_fetch_assoc($res)){

	$itemsFiltrados[] = array('<tr
				id="'.$fila['id_solicitud'].'"
				class="item"
				data-rutaAplicacion="mercanciasSinValorComercial"
				data-opcion="abrirImportacion"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td style="white-space:nowrap;"><b>'.$fila['id_solicitud'].'</b></td>
				<td>'.$fila['solicitante'].'</td>
				<td>'.$fila['pais_origen_destino'].'</td>
				<td>'.$fila['estado'].'</td>
			</tr>');
}
?>
<header>
	<h1>Importación</h1>
		<nav>
		<form id="filtrar" data-rutaAplicacion="mercanciasSinValorComercial" data-opcion="listaImportacion" data-destino="listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
				<table class="filtro" style='width: 400px;'>
					<tbody>
					<tr>
						<th colspan="3">Buscar Solicitud:</th>
					</tr>
						<tr>
						<td>* Identificación Solicitante:</td>
						<td> <input id="solicitante" type="text" name="solicitante"></td>
					</tr>
					<tr>
						<td>* Número de Solicitud:</td>
						<td> <input id="numeroSolicitud" type="text" name="numeroSolicitud" ></td>
					</tr>
					<tr>
						<td>Fecha:</td>
						<td> <input id="fecha" type="text" name="fecha" maxlength="10" ></td>
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
			<th>Nro. Solicitud</th>
			<th>Solicitante</th>
			<th>País</th>
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
		if($("#estado").html()!="Producto actualizado." && $("#estado").html()!="Producto Eliminado."){
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');
		}
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);

		$("#solicitante").attr('maxlength','16');
		$("#numeroSolicitud").attr('maxlength','16');
	});
	

	$("#fecha").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	$("#numeroSolicitud").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
         if ((event.which < 48 || event.which > 57)) {
             if(event.which != 8)
             event.preventDefault();
         }
    });

	$("#filtrar").submit(function(event){
		event.preventDefault();
		$(".alerta").removeClass("alerta");
		var error = false;

		if($("#solicitante").val()==""  && $("#numeroSolicitud").val()==""  && $("#fecha").val()==""){
			 error = true;
			 $("#mensajeError").html("Por favor ingrese al menos un campo que contiene (*) para realizar la consulta").addClass('alerta');
		}

		if(!error){	
			abrir($('#filtrar'),event, false);
		}
	});

</script>
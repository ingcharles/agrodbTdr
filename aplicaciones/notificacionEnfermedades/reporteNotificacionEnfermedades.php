<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEnfermedades.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();
$cne = new ControladorNotificacionEnfermedades();

$identificador=$_SESSION['usuario'];

?>

<header>
	<nav>
		<form id="filtrarReporteEnfermedades"
			action="aplicaciones/notificacionEnfermedades/reporteImprimirNotificacionEnfermedades.php"
			data-rutaAplicacion='notificacionEnfermedades' target="_self"
			method="post">
			<input type="hidden" name="opcion" id="opcion" />
			<table class="filtro" style='width: 500px;'>
				<tbody>
					<tr>
						<th colspan="5" style="text-align: center;"><p>Reporte de
								Enfermedades Zoonósicas
							
							<p>
						
						</th>
					</tr>
					<tr>
						<th>Animal:</th>
						<th colspan="4"><select id="producto" name="producto"
							style='width: 420px;'>
								<?php 
								echo '<option value="TODOS">TODOS</option>';

								$producto = $cne->ObtenerProducto($conexion, $identificador);
								while ($fila = pg_fetch_assoc($producto)){
							    		echo '<option value="'.$fila['id_producto']. '">'. $fila['nombre_comun'] .'</option>';
							    		$idProducto=$fila['id_producto'];
							    	}
							    	?>

						</select>
						</th>
					</tr>
					<tr>
						<th>Tipo Enfermedad:</th>

						<th colspan="4">
							<div id="divEsconderTipoEnfermedad">
								<select id="esconderTipoEnfermedad" disabled="disabled"
									style='width: 420px;'>
									<option value="TODOS">TODOS</option>
								</select>
							</div>
							<div id="resultadoProducto" data-linea="4"></div>
						</th>
					<tr>
						<th>Enfermedad:</th>
						<th colspan="4">
							<div id="divEsconderEnfermedad">
								<select id="esconderEnfermedad" disabled="disabled"
									style='width: 420px;'>
									<option value="">TODOS</option>
								</select>
							</div>
							<div id="resultadoTipoEnfermedad" data-linea="4"></div>
						</th>
					</tr>
					<tr>
						<th>Fecha inicio:</th>
						<th><input id="fechaInicio" type="text" name="fechaInicio" placeholder="día/mes/año"></th>
						<th>Fecha fin:</th>
						<th><input id="fechaFin" type="text" name="fechaFin" placeholder="día/mes/año"></th>
					</tr>
					<tr>
						<td colspan="5" style='text-align: center'>
							<button type="submit" class="guardar" onclick="chequearCampos()">Generar reporte Excel</button>
						</td>
					</tr>
					<tr>
						<td colspan="5" id="estado1" align="center"></td>
					</tr>
				</tbody>
			</table>
		</form>
	</nav>
</header>


<script>

	$(document).ready(function(){//inicio ready

		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');
		
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		}).datepicker('setDate', 'today');
		
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		}).datepicker('setDate', 'today');
		
	});

	 function chequearCampos(form){
		 $(".alertaCombo").removeClass("alertaCombo");
			var error = false;
		
			if($("#fechaInicio").val()==0 || $("#fechaInicio").val()==null){
				error = true;
				$("#fechaInicio").addClass("alertaCombo");
			}

			if($("#fechaFin").val()==0 || $("#fechaFin").val()==null ){
				error = true;
				$("#fechaFin").addClass("alertaCombo");
			}

			if (error){
				event.preventDefault();
				$("#estado1").html("Por favor llene todos los campos para obtener datos.").addClass('alerta');
			}else{
				$("#estado1").html("").removeClass('alerta');                      
			}
			
	 }

	$("#producto").change(function(){
		$('#filtrarReporteEnfermedades').attr('data-opcion','accionesReporteNotificacionEnfermedades');
	 	$('#filtrarReporteEnfermedades').attr('data-destino','resultadoProducto');
	 	$('#opcion').val('producto');	
	 	$('#divEsconderTipoEnfermedad').hide();
	 	abrir($("#filtrarReporteEnfermedades"),event,false);	
	 });
	
</script>

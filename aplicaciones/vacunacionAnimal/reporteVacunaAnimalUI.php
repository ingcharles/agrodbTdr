<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorCatalogos.php';
$conexion = new Conexion();
$va = new ControladorVacunacionAnimal();
$cc = new ControladorCatalogos();

$tecnicoDistribuidor=$va->listarTecnicosDistribuidores($conexion);
$tecnicoVacunador=$va->listarTecnicosVacunadores($conexion);

while($fila = pg_fetch_assoc($tecnicoDistribuidor)){
	$aTecnicoDistribuidor[]= array(identificador_distribuidor=>$fila['identificador'], nombre_distribuidor=>$fila['nombres'], provincia=>$fila['provincia']);
}
	
while($filas = pg_fetch_assoc($tecnicoVacunador)){
	$aTecnicoVacunador[]= array(identificador_vacunador=>$filas['identificador'], nombre_vacunador=>$filas['nombres'], provincia=>$filas['provincia']);
}
	
?>
<header>
	<nav>
		<form id="filtrarVacunacionAnimal" data-rutaAplicacion='vacunacionAnimal' action="aplicaciones/vacunacionAnimal/reporteImprimirVacunaAnimalUI.php" target="_self" method="post">
			<table class="filtro" style='width: 100%;'>
				<tbody>
					<tr>
						<th colspan="4">Reporte certificado de vacunación usuarios internos</th>					
					</tr>
					<tr>
						<th>Provincia</th>
						<th colspan="3">
							<select id="provincia" name="provincia" style='width: 100%;'>
								<option value="0" >Seleccione...</option>
								<?php 
									echo '<option value="TODOS">TODOS</option>';
									$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
									foreach ($provincias as $provincia){
										echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
									}
								?>
							</select> 			
						</th>
					</tr>
					<tr>
						<th>Distribuidor</th>
						<th colspan="3">
							<select id="distribuidor" name="distribuidor"  disabled="disabled" style='width: 100%;'>
								<option value="0">Seleccione....</option>
							</select>
						</th>
					</tr>
					<tr>
						<th>Vacunador</th>
						<th colspan="3">
							<select id="vacunador" name="vacunador" disabled="disabled" style='width: 100%;'>
								<option value="0">Seleccione...</option>
							</select>
						</th>
					</tr>
					<tr>
						<th>Estado</th>
						<th colspan="3">
							<select id="cmbEstado" name="cmbEstado" style="width: 100%;">
								<option value="0">Seleccione...</option>
								<option value="activo">Activo</option>
								<option value="anulado">Anulado</option>
								<option value="1">Todos</option>													
							</select>
						</th>
					</tr>
					<tr>
						<th>Fecha inicio</th>
						<th><input id="fechaInicio" type="text" name="fechaInicio"></th>
						<th>Fecha fin</th>
						<th><input id="fechaFin" type="text" name="fechaFin"></th>					
					</tr>	
					<tr>
						<td colspan="4" style='text-align:center'><button type="submit" class="guardar"  >Generar reporte Excel</button></td>
					</tr>
					<tr>
						<td colspan="4"  align="center" id="estado" ></td>
					</tr>
				</tbody>
			</table>
		</form>	
	</nav>
</header>
<script>

	var array_distribuidor= <?php echo json_encode($aTecnicoDistribuidor); ?>;
	var array_vacunador= <?php echo json_encode($aTecnicoVacunador); ?>;				
							
	$(document).ready(function(){
		distribuirLineas();
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		fecha = fechaActual();
		$("#fechaInicio").val(fecha);
		$("#fechaFin").val(fecha);

	});

	$("#provincia").change(function(event){
		sDistribuidor="";
		sDistribuidor += '<option value="0" >Selecione...</option><option value="TODOS">TODOS</option>';
		if (($('#provincia').val()=='TODOS') ){
			for(var i=0;i<array_distribuidor.length;i++){
				sDistribuidor += '<option value="'+array_distribuidor[i]['identificador_distribuidor']+'"> '
				+ array_distribuidor[i]['nombre_distribuidor']+' - '
				+ array_distribuidor[i]['identificador_distribuidor']+' </option>';
			}
		}else{
			for(var i=0;i<array_distribuidor.length;i++){
				if ($("#provincia").val()==array_distribuidor[i]['provincia']){
					sDistribuidor += '<option value="'+array_distribuidor[i]['identificador_distribuidor']+'"> '
					+ array_distribuidor[i]['nombre_distribuidor']+' - '
					+ array_distribuidor[i]['identificador_distribuidor']+'</option>';
				}   	
			}
		}
		$('#distribuidor').html(sDistribuidor);
		$("#distribuidor").removeAttr("disabled");
	});

	$("#distribuidor").change(function(event){
		sVacunador="";
		sVacunador += '<option value="0" >Selecione...</option><option value="TODOS">TODOS</option>';
		if (($('#provincia').val()=='TODOS') ){
			if (($('#distribuidor').val()=='TODOS') ){
				$('#vacunador').attr('disabled',true);
				$('#vacunador').html(sVacunador);
				$("#vacunador").removeClass("alertaCombo");
				
			}else{
				for(var i=0;i<array_vacunador.length;i++){
					sVacunador += '<option value="'+array_vacunador[i]['identificador_vacunador']+'"> '
					+ array_vacunador[i]['nombre_vacunador']+' - '
					+ array_vacunador[i]['identificador_vacunador']+' </option>';
				}
				$('#vacunador').html(sVacunador);
				$("#vacunador").removeAttr("disabled");		
			}
		}else{
			if (($('#distribuidor').val()=='TODOS') ){
				$('#vacunador').attr('disabled',true);
				$('#vacunador').html(sVacunador);
				$("#vacunador").removeClass("alertaCombo");
			}else{	
				for(var i=0;i<array_vacunador.length;i++){
					if ($("#provincia").val()==array_vacunador[i]['provincia']){
						sVacunador += '<option value="'+array_vacunador[i]['identificador_vacunador']+'"> '
						+ array_vacunador[i]['nombre_vacunador']+' - '
						+ array_vacunador[i]['identificador_vacunador']+'</option>';
					}   	
				}
				$('#vacunador').html(sVacunador);
				$("#vacunador").removeAttr("disabled");
			}
		}
	});

	function fechaActual() {
	  	var date = new Date();
	  	var year = date.getFullYear();
	 	var month = (1 + date.getMonth()).toString();
	 	month = month.length > 1 ? month : '0' + month;
	  	var day = date.getDate().toString();
	  	day = day.length > 1 ? day : '0' + day;
	 	return  day + '/' + month + '/' +  year;
	}	

	$("#filtrarVacunacionAnimal").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($("#provincia").val()==0){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}
		
		if($("#distribuidor").val()==0 || $("#distribuidor").val()==null){
			error = true;
			$("#distribuidor").addClass("alertaCombo");
		}
	
		if($("#distribuidor").val()!='TODOS'){
			if($("#vacunador").val()==0 || $("#vacunador").val()==null){
				error = true;
				$("#vacunador").addClass("alertaCombo");
			}
		}

		if($("#cmbEstado").val()==0){
			error = true;
			$("#cmbEstado").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor seleccione todos los campos para obtener datos.").addClass('alerta');
			event.preventDefault();
		}else{                
			$("#estado").html("").removeClass('alerta');   
			ejecutarJson(form);  	
		}
	});
</script>
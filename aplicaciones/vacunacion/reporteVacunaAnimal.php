<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorCatalogos.php';
$conexion = new Conexion();
$va = new ControladorVacunacionAnimal();
$cc = new ControladorCatalogos();

$qEmpresas= $va->listaEmpresas($conexion);
while($fila = pg_fetch_assoc($qEmpresas)){
	$aEmpresa[]= array(id_empresa=>$fila['id_empresa'], nombre_empresa=>$fila['nombre_empresa'], tipo_empresa=>$fila['tipo_empresa']);
}

$qDistribuidores=$va->listaDistribuidoresEmpresas($conexion);
while($filaD = pg_fetch_assoc($qDistribuidores)){
	$aDistribuidores[]= array(identificador_distribuidor=>$filaD['identificador'], nombre_distribuidor=>$filaD['nombres'], id_empresa=>$filaD['id_empresa']);
}

$qVacunadores=$va->listaVacunadoresEmpresa($conexion);
while($filas = pg_fetch_assoc($qVacunadores)){
	$aVacunadores[]= array(identificador_vacunador=>$filas['identificador'], nombre_vacunador=>$filas['nombres'], id_empresa=>$filas['id_empresa']);
}
?>
<header>
	<nav>
		<form id="filtrarVacunacionAnimal" data-rutaAplicacion='vacunacionAnimal' action="aplicaciones/vacunacionAnimal/reporteImprimirVacunaAnimal.php" target="_self" method="post">
			<input type="hidden" name="opcion" id="opcion" value="0" />
			<table class="filtro" style='width:100%;'>
				<tbody>
					<tr>
						<th colspan="4">Reporte certificado de vacunación usuarios externos</th>					
					</tr>
					<tr>
						<th>Provincia</th>
						<th colspan="3">
							<select id="provincia" name="provincia" style='width: 100%;'>
								<option value="" >Seleccione...</option>
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
						<th>Operador Vacunación</th>
						<th colspan="3">
							<select id="operadoraVacunacion" name="operadoraVacunacion" disabled="disabled" style='width:100%;'>
							<option value="">Seleccione...</option>
							</select>	
						</th>
					</tr>
					<tr>
						<th>Distribuidor</th>
						<th colspan="3">
							<select id="distribuidor" name="distribuidor" disabled="disabled" style='width:100%;'>
							<option value="">Seleccione...</option>
							</select>	
						</th>
					</tr>
					<tr>
						<th>Vacunador</th>
						<th colspan="3">
							<select id="vacunador" name="vacunador" disabled="disabled" style='width:100%;'>
							<option value="">Seleccione...</option>
							</select>	
						</th>
					</tr>
					<tr>
						<th>Estado</th>
						<th colspan="3">
							<select id="cmbEstado" name="cmbEstado" style='width:100%;'>
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
						<td colspan="4" style='text-align:center'><button type="submit" class="guardar" >Generar reporte Excel</button></td>
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
								
	var array_empresas= <?php echo json_encode($aEmpresa); ?>;
	var array_distribuidores= <?php echo json_encode($aDistribuidores); ?>;	
	var array_vacunadores= <?php echo json_encode($aVacunadores); ?>;						
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
		sEmpresa="";
		sEmpresa += '<option value="0">Seleccione...</option>';
		sEmpresa += '<option value="traspatio">OPERADORES TRASPATIO</option>';
		sEmpresa += '<option value="autoservicio">OPERADORES AUTOSERVICIO</option>';

		for(var i=0;i<array_empresas.length;i++){
				sEmpresa += '<option value="'+array_empresas[i]['id_empresa']+'"> '
				+ array_empresas[i]['nombre_empresa']+'</option>';  	
		}
		$('#operadoraVacunacion').html(sEmpresa);
		$("#operadoraVacunacion").removeAttr("disabled");
	});

	$("#operadoraVacunacion").change(function(event){
		sDistribuidores="";
		sDistribuidores += '<option value="0" >Seleccione...</option>';
		sVacunadores="";
		sVacunadores += '<option value="0" >Seleccione...</option>';
		
		if($("#operadoraVacunacion").val()!="traspatio" && $("#operadoraVacunacion").val()!="autoservicio" ){
			sDistribuidores += '<option value="TODOS">TODOS</option>';
			for(var i=0;i<array_distribuidores.length;i++){
				if ($("#operadoraVacunacion").val()==array_distribuidores[i]['id_empresa']){
					sDistribuidores += '<option value="'+array_distribuidores[i]['identificador_distribuidor']+'"> '
					+ array_distribuidores[i]['nombre_distribuidor']+'</option>';
				}   	
			}
			$("#distribuidor").removeAttr("disabled");
			sVacunadores += '<option value="TODOS">TODOS</option>';
			for(var i=0;i<array_vacunadores.length;i++){
				if ($("#operadoraVacunacion").val()==array_vacunadores[i]['id_empresa']){
					sVacunadores += '<option value="'+array_vacunadores[i]['identificador_vacunador']+'"> '
					+ array_vacunadores[i]['nombre_vacunador']+'</option>';
				}   	
			}
			$("#vacunador").removeAttr("disabled");
		}else{
			$("#distribuidor").attr("disabled",true);
			$("#vacunador").attr("disabled",true);
			$("#distribuidor").removeClass("alertaCombo");
			$("#vacunador").removeClass("alertaCombo");
		}
			$('#distribuidor').html(sDistribuidores);
			$('#vacunador').html(sVacunadores);
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

	function reporteVacunacion(){
		chequearOperadorVacunacion(this);
		
	}

	$("#filtrarVacunacionAnimal").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($("#provincia").val()==0){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}
		
		if($("#operadoraVacunacion").val()==0){
			error = true;
			$("#operadoraVacunacion").addClass("alertaCombo");
		}

		if($("#operadoraVacunacion").val()!="traspatio" && $("#operadoraVacunacion").val()!="autoservicio"){
			if($("#distribuidor").val()==0){
				error = true;
				$("#distribuidor").addClass("alertaCombo");
			}
			
			if($("#vacunador").val()==0){
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
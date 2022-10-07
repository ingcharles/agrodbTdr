<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorCatalogos.php';
$conexion = new Conexion();
$va = new ControladorVacunacion();
$cc = new ControladorCatalogos();

set_time_limit(3000);

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
<h1>Reporte de certificados de vacunación usuarios internos</h1>
	<nav>
		<form id="filtrarVacunacion" data-rutaAplicacion='vacunacion' action="aplicaciones/vacunacion/reporteImprimirVacunacionUsuarioInterno.php" target="_self" method="post">
			<table class="filtro" style='width: 100%;'>
				<tbody>
					<tr>
						<th colspan="4">Filtros para el reporte de certificados de vacunación usuarios internos</th>					
					</tr>
					<tr>
						<td align="left">Provincia:</td>
						<td colspan="3">
							<select id="provincia" name="provincia" style='width: 100%;'>
								<option value="0" >Seleccione...</option>
								<?php 
									echo '<option value="todos">Todos</option>';
									$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
									foreach ($provincias as $provincia){
										echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
									}
								?>
							</select> 			
						</td>
					</tr>
					<tr>
						<td align="left">Distribuidor:</td>
						<td colspan="3">
							<select id="distribuidor" name="distribuidor" style='width: 100%;'>
								<option value="0">Seleccione...</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="left">Vacunador:</td>
						<td colspan="3">
							<select id="vacunador" name="vacunador" style='width: 100%;'>
								<option value="0">Seleccione...</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="left">Estado:</td>
						<td colspan="3">
							<select id="cmbEstado" name="cmbEstado" style="width: 100%;">
								<option value="0">Seleccione...</option>
								<option value="todos">Todos</option>	
								<option value="vigente">Vigente</option>
								<option value="anulado">Anulado</option>
								<option value="caducado">Caducado</option>		
																				
							</select>
						</td>
					</tr>
					<tr>
						<td align="left">Fecha Inicio:</td>
						<td><input id="fechaInicio" type="text" readonly="readonly" name="fechaInicio"></td>
						<td align="left">Fecha Fin:</td>
						<td><input id="fechaFin" type="text" readonly="readonly" name="fechaFin"></td>					
					</tr>	
					<tr>
						<td colspan="4" style='text-align:center'><button class="guardar" >Generar Reporte</button></td>
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
		var myDate = new Date();
		days= ('0' + myDate.getDate()).slice(-2);
		months= ('0' + (myDate.getMonth())).slice(-2);
		monthsI= ('0' + (myDate.getMonth()-3)).slice(-2);
		years=myDate.getFullYear();
		
		$("#fechaInicio").val($.datepicker.formatDate('dd/mm/yy', new Date(years, monthsI,days)));
		$("#fechaFin").val($.datepicker.formatDate('dd/mm/yy', new Date(years, months,days)));
		     
		$("#fechaInicio").datepicker({ 
			changeMonth: true,
		 	changeYear: true,
		    onSelect: function(selectedDate){ 
		    	var year = selectedDate.substr(6,4);
				var month= ('0' + (parseInt(selectedDate.substr(3,2))+2)).slice(-2);
		        var days = selectedDate.substr(0,2);
		        $("#fechaFin").datepicker("option", "minDate", selectedDate);
		        $("#fechaFin").datepicker("option", "maxDate", new Date(year, month,days));
		        $("#fechaFin").datepicker("setDate", new Date(year, month,days)); 
		    }
		});
		
		$("#fechaFin").datepicker({
			changeMonth: true,
		    changeYear: true,
		    maxDate:"0"
		});	
		
	});
	
	$("#provincia").change(function(event){
		sDistribuidor="";
		sDistribuidor += '<option value="0" >Seleccione...</option><option value="todos">Todos</option>';
		if (($('#provincia').val()=='todos') ){
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
		sVacunador += '<option value="0" >Seleccione...</option><option value="todos">Todos</option>';
		if (($('#provincia').val()=='todos') ){
			if (($('#distribuidor').val()=='todos') ){
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
			if (($('#distribuidor').val()=='todos') ){
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

	$("#filtrarVacunacion").submit(function(event){
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
	
		if($("#distribuidor").val()!='todos'){
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
			$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');
			event.preventDefault();
		}else{                
			$("#estado").html("").removeClass('alerta');   
			ejecutarJson(form);  	
		}
	});

</script>
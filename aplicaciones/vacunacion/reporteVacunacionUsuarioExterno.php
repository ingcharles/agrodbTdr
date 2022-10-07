<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorCatalogos.php';
$conexion = new Conexion();
$va = new ControladorVacunacion();
$cc = new ControladorCatalogos();

$qDistribuidores=$va->listarDistribuidoresEmpresa($conexion);
while($filaD = pg_fetch_assoc($qDistribuidores)){
	if($filaD['estado']=='activo')
	$aDistribuidores[]= array(identificador_distribuidor=>$filaD['identificador'], nombre_distribuidor=>$filaD['nombres'], id_empresa=>$filaD['id_empresa']);
}

$qVacunadores=$va->listarVacunadoresEmpresa($conexion);
while($filas = pg_fetch_assoc($qVacunadores)){
	if($filas['estado']=='activo')
	$aVacunadores[]= array(identificador_vacunador=>$filas['identificador'], nombre_vacunador=>$filas['nombres'], id_empresa=>$filas['id_empresa']);
}

$identificadorUsuario=$_SESSION['usuario'];
$filaTipoUsuario=pg_fetch_assoc($va->obtenerTipoUsuario($conexion, $identificadorUsuario));


switch ($filaTipoUsuario['codificacion_perfil']){
	case 'PFL_USUAR_INT':
		$qEmpresas= $va->listaEmpresas($conexion);
		while($fila = pg_fetch_assoc($qEmpresas)){
			$aEmpresa[]= array(identificador_empresa=>$fila['identificador'],id_empresa=>$fila['id_empresa'], nombre_empresa=>$fila['nombre_empresa']);
		}
	break;

	case 'PFL_USUAR_EXT':
	    
		//$qResultadoEmpleadoEmpresa=$va->consultarRelacionEmpleadoEmpresa($conexion, $identificadorUsuario);
	    $qResultadoEmpleadoEmpresa=$va->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorVacunacion')", "('OPT')");
		if(pg_num_rows($qResultadoEmpleadoEmpresa)!=0){
			$qEmpresas= $va->listaEmpresas($conexion,pg_fetch_result($qResultadoEmpleadoEmpresa, 0, 'identificador_empresa'));
			while($fila = pg_fetch_assoc($qEmpresas)){
				$aEmpresa[]= array(identificador_empresa=>$fila['identificador'],id_empresa=>$fila['id_empresa'], nombre_empresa=>$fila['nombre_empresa']);
			}
		}else{
		    $qOperacionesUsuario = $va->obtenerOperacionesUsuario($conexion, $identificadorUsuario, "('OPT', 'OPI')");
		    $operacionesUsuario = pg_fetch_assoc($qOperacionesUsuario);
		    
		    $codigoTipoOperacionUsuario = $operacionesUsuario['codigo_tipo_operacion'];
		    
		    if(stristr($codigoTipoOperacionUsuario, 'OPT') == true /*|| stristr($codigoTipoOperacionUsuario, 'OPI') == true*/){
		        $qEmpresas= $va->listaEmpresas($conexion, $identificadorUsuario);
		        while($fila = pg_fetch_assoc($qEmpresas)){
		            $aEmpresa[]= array(identificador_empresa=>$fila['identificador'],id_empresa=>$fila['id_empresa'], nombre_empresa=>$fila['nombre_empresa']);
		        }
		    }
		}
		
	break;

	
}

?>
<header>
<h1>Reporte de certificados de vacunación usuarios externos</h1>
	<nav>
		<form id="filtrarVacunacion" data-rutaAplicacion='vacunacion' action="aplicaciones/vacunacion/reporteImprimirVacunacionUsuarioExterno.php" target="_self" method="post">
			<input type="hidden" name="opcion" id="opcion" value="0" />
			<input type="hidden" name="identificacionOperadora" id="identificacionOperadora"  />
			<table class="filtro" style='width:100%;'>
				<tbody>
					<tr>
						<th colspan="4">Filtros para el reporte de certificados de vacunación usuarios externos</th>					
					</tr>
					<tr>
						<td align="left">Provincia:</td>
						<td colspan="3">
							<select id="provincia" name="provincia" style='width:99%;' >
								<option value="" >Seleccione...</option>
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
						<td align="left">Operador Vacunación:</td>
						<td colspan="3">
							<select id="operadoraVacunacion" name="operadoraVacunacion" style='width:99%;' >
								<option value="0" >Seleccione...</option>
								<?php 
									if($filaTipoUsuario['codificacion_perfil']=='PFL_USUAR_INT'){
										echo '<option data-operadora="OPT" value="OPT">OPERADORES TRASPATIO</option>';
										echo '<option data-operadora="OPI" value="OPI">OPERADORES AUTOSERVICIO</option>';
									}
										
									foreach ($aEmpresa as $provincia){
										echo '<option data-operadora="' . $provincia['identificador_empresa'] . '" value="' . $provincia['id_empresa'] . '">' . $provincia['nombre_empresa'] . '</option>';
									}
									
								?>
							</select> 
						</td>
					</tr>
					
					<tr>
						<td align="left">Distribuidor:</td>
						<td colspan="3">
							<select id="distribuidor" name="distribuidor"  style='width:99%;' >
							<option value="">Seleccione...</option>
							</select>	
						</td>
					</tr>
					
					<tr>
						<td align="left">Vacunador:</td>
						<td colspan="3">
							<select id="vacunador" name="vacunador"  style='width:99%;'>
							<option value="">Seleccione...</option>
							</select>	
						</td>
					</tr>
					
					<tr>
						<td align="left">Estado:</td>
						<td colspan="3">
							<select id="cmbEstado" name="cmbEstado" style='width:99%;'>
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
						<td ><input id="fechaInicio" type="text" name="fechaInicio" readonly="readonly" style='width:98%;' ></td>
						<td align="left">Fecha Fin:</td>
						<td><input id="fechaFin" type="text" name="fechaFin" readonly="readonly" style='width:98%;' ></td>					
					</tr>
					<tr>
						<td colspan="4" style='text-align:center'><button class="guardar" >Generar Reporte</button></td>
					</tr>
					<tr>
						<td colspan="4"  align="center" id="estadoError" ></td>
					</tr>
					
					
				</tbody>
			</table>
		</form>	
	</nav>
</header>
<script>
	var arrayDistribuidores= <?php echo json_encode($aDistribuidores); ?>;	
	var arrayVacunadores= <?php echo json_encode($aVacunadores); ?>;						

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
		$("#operadoraVacunacion").removeAttr("disabled");
	});

	$("#operadoraVacunacion").change(function(event){
		$("#identificacionOperadora").val($("#operadoraVacunacion option:selected").attr('data-operadora'));
		sDistribuidores="";
		sDistribuidores += '<option value="0" >Seleccione...</option>';
		sVacunadores="";
		sVacunadores += '<option value="0" >Seleccione...</option>';
		
		if($("#operadoraVacunacion").val()!="OPT" && $("#operadoraVacunacion").val()!="OPI" ){
			sDistribuidores += '<option value="todos">Todos</option>';
			for(var i=0;i<arrayDistribuidores.length;i++){
				if ($("#operadoraVacunacion").val()==arrayDistribuidores[i]['id_empresa']){
					sDistribuidores += '<option value="'+arrayDistribuidores[i]['identificador_distribuidor']+'"> '
					+ arrayDistribuidores[i]['nombre_distribuidor']+'</option>';
				}   	
			}
			$("#distribuidor").removeAttr("disabled");
			sVacunadores += '<option value="todos">Todos</option>';
			for(var i=0;i<arrayVacunadores.length;i++){
				if ($("#operadoraVacunacion").val()==arrayVacunadores[i]['id_empresa']){
					sVacunadores += '<option value="'+arrayVacunadores[i]['identificador_vacunador']+'"> '
					+ arrayVacunadores[i]['nombre_vacunador']+'</option>';
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

	$("#filtrarVacunacion").submit(function(event){
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

		if($("#operadoraVacunacion").val()!="OPT" && $("#operadoraVacunacion").val()!="OPI"){
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
			$("#estadoError").html("Ingresar información en campos obligatorios.").addClass('alerta');
			event.preventDefault();
		}else{ 
			$("#estadoError").html("").removeClass('alerta');       
			ejecutarJson(form);      	
		}
	});
</script>
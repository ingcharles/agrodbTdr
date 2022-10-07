<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
$conexion = new Conexion();
$ma = new ControladorMovilizacionAnimal();
$va=new ControladorVacunacionAnimal();

/*$usuario= $va->seleccionarProvinciaUsuarioMovilizacion($conexion,$_SESSION['usuario']);
while ($fila = pg_fetch_assoc($usuario)){
	 $provincia=$fila['provincias'];
}
$usuario="";
$perfilUsuario= $va->PerfilUsuario($conexion, $_SESSION['usuario']);
while ($fila = pg_fetch_assoc($perfilUsuario)){
	if( $fila['central']==105  ){
	 $usuario="central";	
	}}
	*/
$empresa = $ma-> listaOperadoresEmpresas($conexion);



?>
<header>
		<nav>
			<form id="filtrarReporteMovilizacion" action="aplicaciones/movilizacionAnimal/reporteImprimirMovilizacionAnimal.php" target="_self" method="post">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			
			<input type="hidden" id="autoservicio" name="autoservicio" value="<?php echo $autoservicio;?>" />
			<table class="filtro" style='width: 500px;'>
				<tbody>
				<tr>
					<th colspan="5">Reporte certificado de movilización</th>					
				</tr>
				<tr>
					<th>Provincia: </th>
					<th colspan="4">
						<select id="provincia" name="provincia" style='width: 420px;'>
					<option value="" >Seleccionar.......</option>
					
					<?php 
					echo '<option value="TODOS">TODOS</option>';
						$provincias = $va->listarSitiosLocalizacionUsuarioPlantaCentral($conexion,$_SESSION['nombreProvincia'],$_SESSION['nombreLocalizacion'],null);
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
						}
					?>
				</select> 			
				<input type="hidden" id="codigoProvincia" name="codigoProvincia"/>
				</th>
				</tr>
				<tr>
					<th>Operador: </th>
					<th colspan="4">
						<select id="operador" name="operador" disabled="disabled" style='width: 420px;' >
						<option value="" >Seleccionar.......</option>
						</select>	
					</th>
				</tr>								
				<tr>
					<th>Fecha inicio:</th>
					<th><input id="fechaInicio" type="text" name="fechaInicio"></th>
					<th>Fecha fin:</th>
					<th><input id="fechaFin" type="text" name="fechaFin"></th>					
				</tr>				
				<tr>
					<th>Estado:</th>
					<th colspan="4">
						<select id="cmbEstado" name="cmbEstado" style='width: 170px;'>
							<option value="0">Seleccionar...</option>
							<option value="activo">Activo</option>
							<option value="anulado">Anulado</option>
							<option value="1">Todos</option>													
						</select>
					</th>
				</tr>	
				<tr>
					<td colspan="5" style='text-align:center'> 
							<button type="submit" class="guardar" onclick="chequearCamposSitio()">Generar reporte Excel</button>						
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

var array_empresas = <?php echo json_encode($empresa); ?>;

	$(document).ready(function(){//inicio ready
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui el reporte para revisarlo.</div>');
		//listaProvincias();
	//	listaEmpresas();
		
		fecha = fechaActual();
		$("#fechaInicio").val(fecha);
		$("#fechaFin").val(fecha);
	});//fin ready



	
	  $("#provincia").change(function(event){
		  sEmpresa ='0';
		  sEmpresa = '<option value="" >Seleccionar.......</option>';
		  if (($('#provincia').val()=='TODOS') ){
				sEmpresa += '<option value="1">AGROCALIDAD TRASPATIO</option>';
				 for(var i=0;i<array_empresas.length;i++){
					    	sEmpresa += '<option value="'+array_empresas[i]['identificador_empresa']+'">'+array_empresas[i]['empresa']+'</option>';
				   	}
			}
		    for(var i=0;i<array_empresas.length;i++){
		    	 if ($("#provincia").val()==array_empresas[i]['provincia']){
			    	sEmpresa += '<option value="'+array_empresas[i]['identificador_empresa']+'">'+array_empresas[i]['empresa']+'</option>';

					}
		   	}
		    $('#operador').html(sEmpresa);
		    $("#operador").removeAttr("disabled");
		    $("#codigoProvincia").val($("#provincia").val());
		  //  $("#canton1").val($("#canton").val());
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


	// $("#filtrarReporteMovilizacion").submit(function(event){
			
			
			
			
	//	});

	 

	 function chequearCamposSitio(form){
		 $(".alertaCombo").removeClass("alertaCombo");
			var error = false;
			if($("#operador").val()==0 || $("#operador").val()==null  ){
				error = true;
				$("#operador").addClass("alertaCombo");
			}

			if($("#cmbEstado").val()==0 || $("#cmbEstado").val()=="" ){
				error = true;
				$("#cmbEstado").addClass("alertaCombo");
			}

			if($("#provincia").val()==0 || $("#provincia").val()=="" ){
				error = true;
				$("#provincia").addClass("alertaCombo");
			}
			
			if (error){
				$("#estado1").html("Por favor llene todos los campos para obtener datos.").addClass('alerta');
				event.preventDefault();
			}else{
				$("#estado1").html("").removeClass('alerta');
			}
			
	 }
		
</script>
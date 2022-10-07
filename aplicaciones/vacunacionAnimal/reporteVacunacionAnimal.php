<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorCatalogos.php';
$conexion = new Conexion();
$va = new ControladorVacunacionAnimal();
$cc = new ControladorCatalogos();
$usuarioProvincia= $va->seleccionarProvinciaUsuarioM($conexion,$_SESSION['usuario']);

while ($filas = pg_fetch_assoc($usuarioProvincia)){
	  $provincia=$filas['provincias'];
}


$usuarioProvincia="";
$perfilUsuario= $va->PerfilUsuario($conexion, $_SESSION['usuario']);
while ($fila = pg_fetch_assoc($perfilUsuario)){
	if( $fila['central']==105  ){
 $usuarioProvincia="central";	
	}}

$operador_reporte = $va-> listaOperadorReporte($conexion, $_SESSION['usuario']);
//$autoservicio = "0";
$valor_tipo_operacion = "";

if (is_array($operador_reporte))
{
foreach ($operador_reporte as $fila){
	$valor_tipo_operacion = $fila['valor_tipo_operacion'];
	//$autoservicio = $fila['estado'];
}}
$autoservicio = 0;
$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
?>
<header>
		<nav>
			<form id="filtrarVacunacionAnimal1" action="aplicaciones/vacunacionAnimal/reporteImprimirVacunaAnimal.php" target="_self" method="post">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<input type="hidden" id="autoservicio" name="autoservicio" value="<?php echo $autoservicio;?>" />
			<input type="hidden" id="valor_tipo_operacion" name="valor_tipo_operacion" value="<?php echo $valor_tipo_operacion;?>" />
		
			<table class="filtro" style='width: 500px;'>
				<tbody>
				<tr>
					<th colspan="5">Reporte certificado de vacunación</th>					
				</tr>
				<tr>
				
					<th>Provincia: </th>
					<th colspan="4">
						<select id="provincia" name="provincia">
					<option value="" >Provincia........</option>
					
					
					
					<?php 
					//OJO despues trabajar por provincia y perfiles
					//if ($usuarioProvincia=='central'){
						//}
					echo '<option value=1>TODOS</option>';
					
						$provincias = $va->listarSitiosLocalizacionn($conexion,$provincia,$usuarioProvincia);
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
						}
						
					?>
				</select> 			
				
					
				</th>
				</tr>
				<tr>
					<th>Operador: </th>
					<th colspan="4">
						<select id="operador" name="operador" disabled="disabled" >
						
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
					<th>
						<select id="cmbEstado" name="cmbEstado">
							<option value="0">Seleccionar...</option>
							<option value="activo">Activo</option>
							<option value="anulado">Anulado</option>
							<option value="1">Todos</option>													
						</select>
					</th>
				</tr>	
				<tr>
					<td colspan="5" style='text-align:center'> 
						
							<button type="submit" class="guardar" onclick="reporteVacunacion()" >Generar reporte Excel</button>
												
					</td>
				</tr>
				<tr>
					<td colspan="5" id="estado1" align="center"></td>
				</tr>
				</tbody>
				</table>
			</form>
		</nav>
		<nav>
			<form  id="filtrarVacunacionAnimal" action="aplicaciones/vacunacionAnimal/reporteImprimirCatastros.php" target="_self" method="post">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			
			<input  type="hidden" id="id_sitio" name="id_sitio" value="0" >
			
			<table class="filtro" style='width: 500px;'>
				<tbody>
					<tr>
						<th colspan="5">Reporte Catastros</th>					
					</tr>
					<tr>
						<th>Sitio:</th>
						<th colspan="3">
							<select id="tipoBusquedaSitios" name="tipoBusquedaSitios"  >
								<option value="0">Seleccionar...</option>
								<?php
								$usuarioProvincia= $va->seleccionarProvinciaUsuarioM($conexion,$_SESSION['usuario']);
									
								while ($filas = pg_fetch_assoc($usuarioProvincia)){
									echo $provincia=$filas['provincias'];
								}
								$sitios = $va-> listaSitioProvinciaCatastro($conexion, $provincia, $_SESSION['usuario']);
								while ($fila = pg_fetch_assoc($sitios)){
					        	echo '<option value="'. $fila['id_sitio'].'">'.$fila['identificador_operador'].'- '.substr($fila['granja'],0, 20).'- '.substr($fila['provincia'],0, 17).'</option>';		     
 								}
 								
								?>			
				   			 </select>	
				  	</tr>
					<tr>
						<th>Fecha inicio:</th>
						<th><input id="fechaInicios" type="text" name="fechaInicios"></th>
						<th>Fecha fin:</th>
						<th><input id="fechaFins" type="text" name="fechaFins"></th>					
					</tr>
					</tbody>
					<tr>
						<td colspan="5" style='text-align:center'> 
							<button type="submit" class="guardar" onclick="reporteCatastro()">Generar reporte Excel</button>
						</td>
					</tr>
					<tr>
						<td colspan="5" id="estado" align="center"></td>
					</tr>
					
				</table>
				</form>
			</nav>
			
			
			<nav>
			<form  id="filtrarSitioProduccion" action="aplicaciones/vacunacionAnimal/reporteImprimirSitiosProduccion.php" target="_self" method="post">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			
			<input  type="hidden" id="nombreParroquia" name="nombreParroquia" value="0" >
			
			<table class="filtro" >
				<tbody>
					<tr>
						<th colspan="4">Reporte Sitios de Produccion</th>					
					</tr>
					<tr>
					
					<th>Provincia:</th>
						<th colspan="3"  width="413px;" ><select id="provinciaOperador" name="provinciaOperador" >
					<option value="">Provincia........................................</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
						}
					?>
				</select>
					</tr>
				<tr>
						<th>Cantón</th>		
						<th colspan="3"><select id="cantonOperador" name="cantonOperador" disabled="disabled">
						<option value="">Cantón...........................................</option>
						</select>
						</tr>
				<tr>
					<th >Parroquia</th>
						<th colspan="3"><select id="parroquiaOperador" name="parroquiaOperador" disabled="disabled">
						<option value="">Parroquia........................................</option>
						
						</select>
					</th>
							</tr>

					<tr>
						<th>Fecha inicio:</th>
						<th ><input id="fechaInicioOperador" type="text" name="fechaInicioOperador"></th>
						<th >Fecha fin:</th>
						<th ><input id="fechaFinOperador" type="text" name="fechaFinOperador"></th>					
					</tr> 
					</tbody>
					<tr>
						<td colspan="4" style='text-align:center'> 
							<button type="submit" class="guardarOperador" onclick="reporteSitiosProduccion()" >Generar reporte Excel</button>
						</td>
					</tr>
					<tr>
						<td colspan="4" id="estado2" align="center"></td>
					</tr>
					
				</table>
				</form>
			</nav>
</header>
<script>

var array_empresas= <?php echo json_encode($operador_reporte); ?>;
							
	$(document).ready(function(){
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#fechaInicios").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#fechaFins").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#fechaInicioOperador").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#fechaFinOperador").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui el reporte para revisarlo.</div>');
		//listaReporte();
		
		fecha = fechaActual();
		$("#fechaInicio").val(fecha);
		$("#fechaFin").val(fecha);
		$("#fechaInicios").val(fecha);
		$("#fechaFins").val(fecha);
		$("#fechaInicioOperador").val(fecha);
		$("#fechaFinOperador").val(fecha);
		
	});

	  $("#provincia").change(function(event){
	
		  sEmpresa ='0';
		  sEmpresa = '<option value="" disabled="disabled" >Operador Empresa...</option>';
		  if (($('#provincia').val()=='1') ){
				sEmpresa += '<option value="1">AGROCALIDAD TRASPATIO</option>';
				 for(var i=0;i<array_empresas.length;i++){
						sEmpresa += '<option value="'+array_empresas[i]['identificador_empresa']+'"> '
						+ array_empresas[i]['nombre_operador_reporte']
						+'</option>'; 	}
			}

		  for(var i=0;i<array_empresas.length;i++){
		    	 if ($("#provincia").val()==array_empresas[i]['nombre_provincia']){
			    	sEmpresa += '<option value="'+array_empresas[i]['identificador_empresa']+'"> '
					+ array_empresas[i]['nombre_operador_reporte']
					+'</option>';

					}
		   	}
		    $('#operador').html(sEmpresa);
		    $("#operador").removeAttr("disabled");
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
</script>

<script type="text/javascript">

$("#tipoBusquedaSitios").change(function(){         
	if($("#tipoBusquedaSitios").val()!='0'){
		$("#id_sitio").val($("#tipoBusquedaSitios option:selected").val());
	}
}); 


//***************//Acciones de botton
$("#btn_guardar").click(function(event){		
	
		if($("#estado").html() == ''){		
			 dia = calcularEdad($('#fecha_nacimiento').val());
			 $('#numeroDias').val(dia);			
			 event.preventDefault();		
			 $('#reportevacunacionAnimal').attr('data-opcion','accionesCatastroAnimal');
			 $('#reportevacunacionAnimal').attr('data-destino','res_sitio');
		     $('#opcion').val('2');		     	
			 abrir($("#reportevacunacionAnimal"),event,false); //Se ejecuta ajax, busqueda de sitio	
			 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		
	}				 		 		
});	

function reporteCatastro(){
	chequearCatastro();

}


function reporteVacunacion(){
	
	chequearOperadorVacunacion();
}

function chequearCatastro(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#tipoBusquedaSitios").val()==0){
		error = true;
		$("#tipoBusquedaSitios").addClass("alertaCombo");
	}			
	if($("#id_sitio").val()==0){
		error = true;
		$("#id_sitio").addClass("alertaCombo");
	}				
	if (error == true){
		$("#estado").html("Por favor llene todos los campos para obtener datos.").addClass('alerta');
	}else{                   
		$("#estado").html("").removeClass('alerta');			      	
	}//
}//inicio



function chequearOperadorVacunacion(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#provincia").val()==0){
		error = true;
		$("#provincia").addClass("alertaCombo");
	}
	
	if($("#operador").val()==0 || $("#operador").val()==null){
		error = true;
		$("#operador").addClass("alertaCombo");
	}
	if($("#cmbEstado").val()==0){
		error = true;
		$("#cmbEstado").addClass("alertaCombo");
	}
	
	if (error == true){
		$("#estado1").html("Por favor llene todos los campos para obtener datos.").addClass('alerta');
	}else{                   
		$("#estado1").html("").removeClass('alerta');			      	
	}//
}


function reporteSitiosProduccion(){
	
	chequearSitiosProduccion();
}


function chequearSitiosProduccion(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#provinciaOperador").val()==0 || $("#provinciaOperador").val()==""){
		error = true;
		$("#provinciaOperador").addClass("alertaCombo");
	}
	
	if($("#cantonOperador").val()==0 || $("#cantonOperador").val()==""){
		error = true;
		$("#cantonOperador").addClass("alertaCombo");
	}
	if($("#parroquiaOperador").val()==0 || $("#parroquiaOperador").val()==""){
		error = true;
		$("#parroquiaOperador").addClass("alertaCombo");
	}
	
	if (error == true){
		$("#estado2").html("Por favor llene todos los campos para obtener datos.").addClass('alerta');
	}else{                   
		$("#estado2").html("").removeClass('alerta');			      	
	}//
}

//inicio	
</script>
<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;

$("#provinciaOperador").change(function(event){
	scanton ='0';
	scanton = '<option value="">Cantón...........................................</option>';
    for(var i=0;i<array_canton.length;i++){
	    if ($("#provinciaOperador").val()==array_canton[i]['padre']){
	    	scanton += '<option data-latitud="'+array_canton[i]['latitud']+'"data-longitud="'+array_canton[i]['longitud']+'"data-zona="'+array_canton[i]['zona']+'" value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
		}
   	}
    $('#cantonOperador').html(scanton);
    $("#cantonOperador").removeAttr("disabled");

});

$("#cantonOperador").change(function(){
	sparroquia ='0';
	sparroquia = '<option value="">Parroquia........................................</option>';
    for(var i=0;i<array_parroquia.length;i++){
	    if ($("#cantonOperador").val()==array_parroquia[i]['padre']){
	    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
		    } 
    	}
    $('#parroquiaOperador').html(sparroquia);
	$("#parroquiaOperador").removeAttr("disabled");
});
$("#parroquiaOperador").change(function(){
	$("#nombreParroquia").val($("#parroquiaOperador option:selected").text())
});

</script>



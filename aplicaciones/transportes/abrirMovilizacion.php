<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorEmpleados.php';

$conexion = new Conexion();
$cv = new controladorVehiculos();
$ca = new ControladorAreas();
$cu = new ControladorUsuarios();
$ce = new ControladorEmpleados();

$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");

$vehiculo = $cv->obtenerDatosVehiculos($conexion, $_SESSION['nombreLocalizacion'],'Otro');

//Identificador Usuario Administrador o Apoyo de Transportes
if($_SESSION['usuario'] != '' && $_SESSION['usuario']!=$vehiculo['identificador_registro']){
	$identificadorUsuarioRegistro = $_SESSION['usuario'];
}else if($_SESSION['usuario'] != '' && $_SESSION['usuario']==$vehiculo['identificador_registro']){
	$identificadorUsuarioRegistro = $vehiculo['identificador_registro'];
}else{
	$identificadorUsuarioRegistro = '';
}

if($identificadorUsuarioRegistro != ''){
	$idArea= $ca->areaUsuario($conexion, $identificadorUsuarioRegistro);
	$identificadorResponsable = $ca->buscarResponsableSubproceso($conexion, pg_fetch_result($idArea, 0, 'id_area'));
	$idAreaPadre = $ca->buscarPadreSubprocesos($conexion, pg_fetch_result($idArea, 0, 'id_area'));
}

$ocupantes = $cv->abrirMovilizacionOcupantes($conexion, $_POST['id']);
$recorrido = $cv->abrirMovilizacionRutas($conexion,$_POST['id']);
$fec = $cv->abrirMovilizacionFechas($conexion,$_POST['id']);
$fechas = pg_fetch_assoc($fec);


$res = $cv->abrirMovilizacion($conexion, $_POST['id']);
$movilizacion = pg_fetch_assoc($res);

$veh = $cv->abrirVehiculo($conexion, $movilizacion['placa']);
$vehiculoAsignado = pg_fetch_assoc($veh);

$per = $ce->obtenerDatosPersonales($conexion, $movilizacion['conductor']);
$persona = pg_fetch_assoc($per);

$usuario = $cu->obtenerUsuariosXarea($conexion);

while($fila = pg_fetch_assoc($usuario)){
	$responsable[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

$administrador = $cv->buscarAdministrador($conexion, $_SESSION['idLocalizacion']);
$autoridad = $cu->BuscarAutoridadInstitucion($conexion);

$jefeTransportes = pg_fetch_result($administrador, 0, 'nombres_completos');

$valorInicio = strtotime($fechas['fecha_desde']);
$valorFin =  strtotime($fechas['fecha_hasta']);

//$cargo = 'Jefe de Transportes';
$salvoconducto = 0;

//Buscar area padre de la del administrador para validación

while( $valorInicio <= $valorFin ){
	if(date("w", $valorInicio) == 0 || date("w", $valorInicio) == 6){
		$salvoconducto = 1;
		
		if(pg_fetch_result($idArea, 0, 'id_area') == 'GA' || pg_fetch_result($idArea, 0, 'id_area') == 'DGAF' || pg_fetch_result($idArea, 0, 'id_area') == 'GDMA'){
			$responsableSalvoconducto = pg_fetch_result($autoridad, 0, 'nombres_completos');
			$cargo = 'Director Ejecutivo';
			break;
		}else if(pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTS' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTP'  || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTT' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') ==  'OTSD' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTG' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') ==  'OTCA' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTEO'){
			$autoridad = $cu->buscarResponsableArea($conexion, pg_fetch_result($idAreaPadre, 0, 'id_area_padre'));
			$responsableSalvoconducto = pg_fetch_result($autoridad, 0, 'nombres_completos');
			$cargo = 'Director Distrital Tipo A';
			break;
		}else if(pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTC' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTE' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTO'  || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTCO' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTCH' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') ==  'OTM' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTLR' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTSE' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') ==  'OTMS' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTL'){
			$autoridad = $cu->buscarResponsableArea($conexion, pg_fetch_result($idAreaPadre, 0, 'id_area_padre'));
			$responsableSalvoconducto = pg_fetch_result($autoridad, 0, 'nombres_completos');
			$cargo = 'Director Ditrital Tipo B';
			break;
		}else if(pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTI' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTN' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTPA' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTB' || pg_fetch_result($idAreaPadre, 0, 'id_area_padre') == 'OTZC'){
			$autoridad = $cu->buscarResponsableArea($conexion, pg_fetch_result($idAreaPadre, 0, 'id_area_padre'));
			$responsableSalvoconducto = pg_fetch_result($autoridad, 0, 'nombres_completos');
			$cargo = 'Jefe de Servicio de Sanidad Agropecuario';
			break;
		}
	}
	$valorInicio =  strtotime('+1 day',$valorInicio);
}



$rutaArchivo = $movilizacion['ruta_archivo'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>
	<header>
		<img src='aplicaciones/general/img/encabezado.png'>
		<h1>Orden de movilización</h1><div class="numero"><?php echo ' N° '.$movilizacion['id_movilizacion'];?></div>
	</header>
	
	<div id="estado"></div>
		
	<fieldset id="detalleMovilizacion">
		<legend>Detalle</legend>
		
		
		<?php
		echo'
		<div data-linea="0">
			
				<label>ID: </label>'. $movilizacion['id_movilizacion'].'
					
			</div>	
		<div data-linea="1">
		
			<label>Tipo: </label> '.$movilizacion['tipo_movilizacion'].'
		
		</div><div data-linea="2">
		
			<label>Descripción: </label> '.$movilizacion['descripcion'].'
		
		</div><div data-linea="3">
			<label>Ocupantes: </label>';
				while($fila = pg_fetch_assoc($ocupantes)){
					$varOcupantes .= $fila['nombres_completos'].', ';
				}
				
				echo trim($varOcupantes, ", ");
		
		echo'</div><div data-linea="4">
		
			<label>Otros ocupantes: </label> '.($movilizacion['observacion_ocupante']!=''?$movilizacion['observacion_ocupante']:'No aplica').'
		</div><div data-linea="5"><label>Recorrido: </label> ';


			while($fila = pg_fetch_assoc($recorrido)){
				$varRecorrido .= $fila['localizacion'].', ';
			}

		echo trim($varRecorrido, ", ");
		
		echo'</div><div data-linea="6">
			
				<label>Observación de ruta: </label> '.($movilizacion['observacion_ruta']!=''?$movilizacion['observacion_ruta']:'Sin novedad').'
			
			</div><div data-linea="7">
		
				<label>Recorrido valido: </label> <label>Desde el </label>'.$fechas['fecha_desde'].'<label> Hasta el </label>'.$fechas['fecha_hasta'].'
			
			</div><div data-linea="8">
					
				<label>Fecha de solicitud: </label> '.date('j/n/Y (G:i)',strtotime($movilizacion['fecha_solicitud'])).'
			
			</div>';
		
		if($movilizacion['estado'] == 3){
			echo '	<div data-linea="9">
						<label>Orden: </label><a download="'.$movilizacion['id_movilizacion'].'.pdf" href="'.$movilizacion['ruta_archivo'].'" target= "_blank">'.$movilizacion['id_movilizacion'].'</a>
					</div>';
		}

		?>
	</fieldset>
		
<form id="datosMovilizacion" data-rutaAplicacion="transportes" data-opcion="actualizarMovilizacion" data-accionEnExito="ACTUALIZAR" >

	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	<input type='hidden' id='jefeTransportes' name='jefeTransportes' value="<?php echo $jefeTransportes;?>" />
	<input type="hidden" name="cargo" id="cargo" value="<?php echo $cargo;?>"/>
	<input type="hidden" name="salvoconducto" id="salvoconducto" value="<?php echo $salvoconducto;?>"/>
	<input type="hidden" name="responsableSalvoconducto" id="responsableSalvoconducto" value="<?php echo $responsableSalvoconducto;?>"/>
	
	<fieldset id="datosVehiculo">
		<legend>Datos vehículo</legend>
		
			<input type="hidden" name="id_movilizacion" value="<?php echo $movilizacion['id_movilizacion'];?>"/>
			<input type="hidden" name="km_actual" value="<?php echo $movilizacion['kilometraje_actual'];?>"/>
			<input type="hidden" name="id_vehiculo" id="id_vehiculo"/>
			
		
	<div data-linea="1">
		
		<label>Vehículo</label>
		<select id="vehiculo" name="vehiculo" >
					<option value="">Vehículo....</option>
					<?php 
						while($fila = pg_fetch_assoc($vehiculo)){
							echo '<option value="' . $fila['placa'] . '"  data-idVehiculo="'. $fila['id_vehiculo'].'" data-kilometrajeActual="Kilometraje actual: '. $fila['kilometraje_actual'].'"  data-kilometrajeInicial="Kilometraje inicial: '. $fila['kilometraje_inicial'].'">' . $fila['marca'] .' '.$fila['modelo'] .' -> '.$fila['placa']. '</option>';					
						}
					?>
		</select>
	
	
	</div><div data-linea="2">	
					
		<label>Kilometraje</label>
		<input type="number" step="1" name="km_inicial"  id="km_inicial" placeholder="Ej: 12345" required="required"/>
	
	</div><div data-linea="2">
	

	<div class="kilometraje"></div>
	
	</div>

	</fieldset>
	
	<fieldset>
		<legend>Conductor</legend>
		
		<div data-linea="3">	
	
		<label>Área</label>
				<select id="area" name="area" >
					<option value="">Áreas....</option>
					<?php 
						while($fila = pg_fetch_assoc($area)){
								echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" >' . $fila['nombre'] . '</option>';
							}
					?>
				</select>
				
				<input type="hidden" id="categoriaArea" name="categoriaArea" />
				
	</div><div data-linea="3">
			
		<div id="dSubOcupante"></div>
	</div>
	
	</fieldset>
	
	<button type="submit" class="guardar">Actualizar movilización</button>
	
</form>


<form id="imprimirMovilizacion" data-rutaAplicacion="transportes" data-opcion="imprimirMovilizacion" data-accionEnExito="ACTUALIZAR" >

	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	<input type="hidden" name="id_movilizacion" value="<?php echo $movilizacion['id_movilizacion'];?>"/>
	
	<!-- insertar div del jasper -->
	<?php 
		if($movilizacion['estado'] == 2){
				echo '<embed id="visor" src='.$rutaArchivo.' width="540" height="450">';
		}
	?>
		
	<p class="nota">Por favor presione el botón "Finalizar etapa".</p>
	
	<button type="submit" class="guardar">Finalizar etapa</button>


<!-- Table firmas -->
	
</form>



<form id="finalizarMovilizacion" data-rutaAplicacion="transportes" data-opcion="finalizarMovilizacion"  data-accionEnExito="ACTUALIZAR">
	
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<br/>	
	<fieldset>
		<legend>Información de cierre de movilizacón</legend>
			
			<input type="hidden" name="id_movilizacion" value="<?php echo $movilizacion['id_movilizacion'];?>"/>
			<input type="hidden" name="placa" value="<?php echo $movilizacion['placa'];?>"/>
			<input type="hidden" id="km_actual" name="km_actual" value="<?php echo $movilizacion['kilometraje_inicial'];?>"/>
		
			
		<div data-linea="1">
			<label>Kilometraje actual</label>
							<input type="text" id="km_inicial" name="km_inicial" value="<?php echo $movilizacion['kilometraje_inicial'];?>" readonly="readonly"/>
		</div><div data-linea="1">	
			<label>Kilometraje final</label>
				<input type="number" step="1" name="km_final" id="km_final" placeholder="Ej: 12345" required="required"/> 
		</div><div data-linea="2" id="razonIncrementoKm">	
			<label>Razón incremento kilometraje</label>
				<input type="text" name="razonKilometraje" id="razonKilometraje" /> 
		</div><div data-linea="3">
			
			<label>Observación</label>
				<input type="text" name="observacion"/> 
				
		</div>

	</fieldset>
	
			
	<button id="detalle" type="submit" class="guardar" >Finalizar movilización</button>
	
</form>



</body>

<script type="text/javascript">

var array_responsable= <?php echo json_encode($responsable); ?>;

$("#area").change(function(event){
	$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
	$("#datosMovilizacion").attr('data-opcion', 'combosOcupante');
    $("#datosMovilizacion").attr('data-destino', 'dSubOcupante');
    abrir($("#datosMovilizacion"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
    
    $('#ocupante').html(socupante);
    $('#ocupante').removeAttr("disabled");
 });

$("#datosMovilizacion").submit(function(event){

	$("#datosMovilizacion").attr('data-opcion', 'actualizarMovilizacion');
    $("#datosMovilizacion").removeAttr('data-destino');
    
    event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#vehiculo").val()==""){
		error = true;
		$("#vehiculo").addClass("alertaCombo");
	}

	if($("#area").val()==""){
		error = true;
		$("#area").addClass("alertaCombo");
	}

	if($("#ocupante").val()=="" || $("#ocupante").val()=="Otro"){
		error = true;
		$("#ocupante").addClass("alertaCombo");
		$("#estado").addClass("Debe seleccionar a un funcionario de Agrocalidad");
	}

	if(!error){
		var km = $("#vehiculo  option:selected").attr("data-kilometrajeActual");
		var km_str = km.split(" ");  

		if($("#km_inicial").val() >= Number(km_str[2])){
			ejecutarJson($(this));
		}else{
			$("#estado").html("El kilometraje ingresado en inferior al actual, por favor verifique y vuelva a intentar.").addClass("alerta");
		}
	}
	
});

$("#imprimirMovilizacion").submit(function(event){
	//window.print();
	event.preventDefault();
	ejecutarJson($(this));
});

$("#km_final").change(function(event){
	if((Number($("#km_final").val())-Number($("#km_actual").val())) >= Number(1000)){
		$("#razonIncrementoKm").show();
		$("#razonKilometraje").attr('required','required');
		$("#estado").html("El kilometraje ingresado supera los 1000 kms de recorrido, por favor verifique el valor o ingrese el motivo del mismo.").addClass("alerta");
	}else{
		$("#razonIncrementoKm").hide();
		$("#razonKilometraje").removeAttr("required");
	}
});

$("#finalizarMovilizacion").submit(function(event){
	event.preventDefault();
	
	if(Number($("#km_final").val()) > Number($("#km_actual").val())){
		if((Number($("#km_final").val())-Number($("#km_actual").val())) < Number(9)){
			$("#estado").html("Por favor verifique el valor del kilometraje recorrido o comuníquese con soporte para rectificar los kilometrajes erróneos.").addClass("alerta");
			$("#km_final").addClass("alertaCombo");
		}else{
			ejecutarJson($(this));
		}
	}else{
		$("#estado").html("El kilometraje ingresado en inferior al actual, por favor verifique y vuelva a intentar.").addClass("alerta");
	}

});

if (<?php echo $movilizacion['estado'];?>=="2"){
	$("#datosMovilizacion").hide();
	$("#finalizarMovilizacion").hide();
	$("#detalleMovilizacion").hide();
}

if (<?php echo $movilizacion['estado'];?>=="3"){
	$("#datosMovilizacion").hide();
	$("#imprimirMovilizacion").hide();
}

if (<?php echo $movilizacion['estado'];?>=="1"){
	$("#finalizarMovilizacion").hide();
	$("#imprimirMovilizacion").hide();
}

$("#vehiculo").change(function(){
	$('#id_vehiculo').val($("#vehiculo  option:selected").attr("data-idVehiculo"));
	$("#datosVehiculo div.kilometraje").html($("#vehiculo  option:selected").attr("data-kilometrajeActual"));
	$("#datosVehiculo div.notificacion").html("");

	var inicialKm = $("#vehiculo  option:selected").attr("data-kilometrajeInicial");
	var kmInicial = inicialKm.split(" ");  

	var actualKm = $("#vehiculo  option:selected").attr("data-kilometrajeActual");
	var kmActual = actualKm.split(" ");  

	$("#km_inicial").val(Number(kmActual[2]));

	if( Number(kmActual[2]) >= Number(kmInicial[2]) + 4800 ){
		var km = Number(kmActual[2]) - Number(kmInicial[2]);  
		$("#estado").html('El vehiculo a recorrido: '+km+' kilometros, esta proximo a mantenimiento.').addClass("alerta");
	}
});


$(document).ready(function(){
	distribuirLineas();
	$("#razonIncrementoKm").hide();
});

</script>

</html>


<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cai = new ControladorAccidentesIndicentes();

$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');

//poner en todos los combos style="width:100%"

$identificador=$_SESSION['identificador'];
$solicitud=$_POST['id'];

$valores_accidentes=pg_fetch_array($cai->listarDatosAccidente($conexion,'', '','', '', $prioridad=NULL,$solicitud));

$datos_registro=pg_fetch_array($cai->buscarRegistroAccidente($conexion,$solicitud));
$datos_circunstancias=pg_fetch_array($cai->buscarCircunstanciasAccidente($conexion,$solicitud));
$datos_ficha=pg_fetch_array($cai->buscarFichaAccidente($conexion,$solicitud));


$consulta=$cai->buscarDatosServidor($conexion,$valores_accidentes['identificador_accidentado']);
$valores_datos=pg_fetch_array($consulta);

$parroquia=pg_fetch_result($cai->obtenerNombreLocalizacion ($conexion, $valores_datos['id_localizacion_parroquia']),0,'nombre');
$provincia=pg_fetch_result($cai->obtenerNombreLocalizacion ($conexion, $valores_datos['id_localizacion_provincia']),0,'nombre');
$canton=pg_fetch_result($cai->obtenerNombreLocalizacion ($conexion, $valores_datos['id_localizacion_canton']),0,'nombre');


$parroquiaAccidente=pg_fetch_result($cai->obtenerNombreLocalizacion ($conexion, $datos_registro['id_localizacion_parroquia']),0,'nombre');
$provinciaAccidente=pg_fetch_result($cai->obtenerNombreLocalizacion ($conexion, $datos_registro['id_localizacion_provincia']),0,'nombre');
$cantonAccidente=pg_fetch_result($cai->obtenerNombreLocalizacion ($conexion, $datos_registro['id_localizacion_ciudad']),0,'nombre');

$nombre_puesto=pg_fetch_result($cai->obtenerNombrePuesto ($conexion, $valores_datos['identificador']),0,'nombre_puesto');
$datos = array(
		'nombre'=>$valores_datos['nombre'].' '.$valores_datos['apellido'],
		'fechaNacimiento'=>$valores_datos['fecha_nacimiento'],
		'edad'=>$valores_datos['edad'],
		'genero'=>$valores_datos['genero'],
		'estadoCivil'=>$valores_datos['estado_civil'],
		'tieneDiscapacidad'=>$valores_datos['tiene_discapacidad'],
		'domicilio'=>$valores_datos['domicilio'],
		'convencional'=>$valores_datos['convencional'],
		'parroquia'=>$parroquia,
		'provincia'=>$provincia,
		'ciudad'=>$canton,
		'nombrePuesto'=>$nombre_puesto,
		'celular'=>$valores_datos['celular'],
		'referencia'=>$valores_datos['referencia_domicilio']
);

?>
<header>
	<h1>
		<?php echo ucfirst($valores_accidentes['tipo_sso']);?>
	</h1>
</header>

<form id="datosRegistroActualizar"
	data-rutaAplicacion="investigacionAccidentesIncidentes"
	data-opcion="actualizarRegistroSso" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion"
		value="<?php echo $solicitud;?>" /> <input type="hidden" id="tipoSso"
		name="tipoSso" value="<?php echo $valores_accidentes['tipo_sso'];?>" />
	<div id="estado"></div>

	<div class="pestania">
		<fieldset>
			<legend>Identificación de Persona Accidentada</legend>

			<div data-linea="1">
				<label>Identificación:</label> <input type="text"
					value="<?php echo $valores_accidentes['identificador_accidentado'];?>"
					id="identificadorUsuario" name="identificadorUsuario"
					onchange="buscarDatos(id);  return false;" />
				<div id="estadoBusque"></div>

			</div>
			<div data-linea="2">
				<label>Nombre:</label> <input type="text" id="nombreServidor"
					value="<?php echo $datos['nombre'];?>" name="nombreServidor"
					value="<?php echo $datos['nombre'];?>" readonly />
			</div>
			<div data-linea="3">
				<label>Fecha Nacimiento:</label> <input type="text"
					id="fechaNacimiento" name="fechaNacimiento"
					value="<?php echo $datos['fechaNacimiento'];?>" readonly />
			</div>
			<div data-linea="3">
				<label>Edad:</label> <input type="text" id="edad" name="edad"
					value="<?php echo $datos['edad'];?>" readonly />
			</div>
			<div data-linea="5">
				<label>Género:</label> <input type="text" id="genero" name="genero"
					value="<?php echo $datos['genero'];?>" readonly />
			</div>
			<div data-linea="5">
				<label>Estado Civil:</label> <input type="text" id="estadoCivil"
					name="estadoCivil" value="<?php echo $datos['estadoCivil'];?>"
					readonly />
			</div>
			<div data-linea="7">
				<label>Pertenece a un Grupo Vulnerable?:</label> <input type="text"
					id="tieneDiscapacidad" name="tieneDiscapacidad"
					value="<?php echo $datos['tieneDiscapacidad'];?>" readonly />
			</div>
			<div data-linea="8">
				<label>Dirección Domiciliaria:</label> <input type="text"
					id="domicilio" name="domicilio"
					value="<?php echo $datos['domicilio'];?>" readonly />
			</div>
			<div data-linea="9">
				<label>Referencia:</label> <input type="text" id="referencia"
					name="referencia" value="<?php echo $datos['referencia'];?>" />
			</div>
			<div data-linea="10">
				<label>Provincia:</label> <input type="text" id="provincia"
					name="provincia" value="<?php echo $datos['provincia'];?>" readonly />
			</div>
			<div data-linea="10">
				<label>Ciudad:</label> <input type="text" id="ciudad" name="ciudad"
					readonly value="<?php echo $datos['ciudad'];?>" />
			</div>
			<div data-linea="10">
				<label>Sector:</label> <input type="text" id="sector" name="sector"
					readonly value="<?php echo $datos['parroquia'];?>" />
			</div>
			<div data-linea="13">
				<label>Teléfono 1:</label> <input type="text" id="convencional"
					readonly name="convencional"
					value="<?php echo $datos['convencional'];?>" />
			</div>
			<div data-linea="13">
				<label>Teléfono 2:</label> <input type="text" id="celular" name=""
					value="<?php echo $datos['celular'];?>" readonly />
			</div>
			<div data-linea="15">
				<label>Escolaridad:</label> <input type="text" id="escolaridad"
					name="escolaridad"
					value="<?php echo $valores_accidentes['escolaridad'];?>" readonly />
			</div>
			<div data-linea="15">
				<label>Profesión:</label> <input type="text" id="profesion"
					name="profesion"
					value="<?php echo $valores_accidentes['profesion'];?>" />
			</div>
			<div data-linea="17">
				<label>Ocupación:</label> <input type="text" id="nombrePuesto"
					name="nombrePuesto" value="<?php echo $datos['nombrePuesto'];?>"
					readonly />
			</div>
			<div data-linea="18">
				<label>Horario de Trabajo:</label> <input type="text"
					id="horarioTrab" name="horarioTrab"
					value="<?php echo $valores_accidentes['horario_trabajo'];?>" />
			</div>
			<div data-linea="18">
				<label>Tiempo Puesto Trabajo:</label> <input type="text"
					id="horarioTrab" name="horarioTrab"
					value="<?php echo $valores_accidentes['tiempo_puesto'];?>" />
			</div>

		</fieldset>
		<fieldset>
			<legend>Información del Accidente</legend>
			<div data-linea="1">
				<label>Día de la Semana:</label> <input type="text" id="diaSemana"
					name="diaSemana" value="<?php echo $datos_registro['dia'];?>" />
			</div>
			<div data-linea="2">
				<label>Fecha del Accidente:</label> <input type="text"
					id="fechaSuceso" name="fechaSuceso"
					value="<?php echo $datos_registro['fecha_accidente']?>"
					data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly />

			</div>
			<div data-linea="2">
				<label>Hora:</label> <input id="horaAccidente" name="horaAccidente"
					class="menores"
					value="<?php echo $datos_registro['hora_accidente']?>" type="text"
					placeholder="10:30" data-inputmask="'mask': '99:99'" />

			</div>
			<div data-linea="4">
				<label>Tipo de Accidente:</label> <input type="text"
					id="tipoAccidente" name="tipoAccidente"
					value="<?php echo $datos_registro['tipo_accidente'];?>" />
			</div>
			<div data-linea="4">
				<label>Lugar del Accidente:</label> <input type="text"
					id="lugarAccidente" name="lugarAccidente"
					value="<?php echo $datos_registro['lugar_accidente'];?>" />
			</div>
			<div data-linea="6">
				<label>Dirección:</label> <input type="text" id="direccion"
					name="direccion" value="<?php echo $datos_registro['direccion']?>" />
			</div>
			<div data-linea="7">
				<label>Referencia:</label> <input type="text"
					id="referenciaAccidente" name="referenciaAccidente"
					value="<?php echo $datos_registro['referencia']?>" />
			</div>
			<div data-linea="8">
				<label>Provincia:</label> <input type="text"
					id="provinciaAccidente" name="provinciaAccidente"
					value="<?php echo $provincia;?>" />
			</div>
			<div data-linea="8">
				<label>Ciudad:</label> <input type="text" id="cantonAccidente"
					name="cantonAccidente" value="<?php echo $cantonAccidente;?>" />
			</div>
			<div data-linea="8">
				<label>Sector:</label> <input type="text" id="parroquiaAccidente"
					name="parroquiaAccidente" value="<?php echo $parroquiaAccidente;?>" />
			</div>
		</fieldset>
	</div>

	<div class="pestania">
		<fieldset>
			<legend>Descripción y Circunstancias del Accidente</legend>
			<div data-linea="1">
				<label>Describir que hacia el Trabajador y como se Lesionó:</label><br>
			</div>
			<div data-linea="2">
				<textarea
					style="width: 150px; height: 60px; display: inline-block; vertical-align: middle;"
					id="describirAccidente" name="describirAccidente"><?php echo trim($datos_circunstancias['describir_accidentado']);?></textarea>
			</div>
			<div data-linea="3">
				<label>¿Era su Trabajo Habitual?:</label> <input type="text"
					id="nombreMedico" name="nombreMedico"
					value="<?php echo $datos_circunstancias['trabajo_habitual'];?>" />
			</div>
			<div data-linea="4">
				<label>¿Ha sido Accidente de Trabajo?:</label> <input type="text"
					id="nombreMedico" name="nombreMedico"
					value="<?php echo $datos_circunstancias['accidente_trabajo'];?>" />
			</div>
			<div data-linea="5">
				<label>Partes Lesionadas del Cuerpo:</label> <input type="text"
					id="partesLesionadas" name="partesLesionadas"
					value="<?php echo $datos_circunstancias['partes_lesionadas']?>" />
			</div>
			<div data-linea="6">
				<label>Persona que lo Atendió Inmediatamente:</label> <input
					type="text" id="personaAtendio" name="personaAtendio"
					value="<?php echo $datos_circunstancias['persona_atendio']?>" />
			</div>
			<div data-linea="7">
				<label>El Accidentado fue Trasladado a:</label> <input type="text"
					id="trasladoAccidente" name="trasladoAccidente"
					value="<?php echo $datos_circunstancias['traslado_accidentado']?>" />
			</div>
		</fieldset>
		<fieldset>
			<legend>Información de Testigos</legend>
			<div data-linea="1">
				<label>Nombre:</label> <input type="text" id="nombreTestigo"
					name="nombreTestigo"
					value="<?php echo $datos_circunstancias['nombre_testigo']?>" />
			</div>
			<div data-linea="2">
				<label>Dirección Domiciliaria:</label> <input type="text"
					id="direccionTestigo" name="direccionTestigo"
					value="<?php echo $datos_circunstancias['direccion_testigo']?>" />
			</div>
			<div data-linea="3">
				<label>Teléfono:</label> <input type="text" name="telefonoTestigo"
					id="telefonoTestigo"
					value="<?php echo $datos_circunstancias['telefono_testigo']?>"
					placeholder="Ej. (09) 9988-8899"
					data-inputmask="'mask': '(09) 9999-9999'"
					data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" title="(09) 9988-8899"
					size="15" />
			</div>
		</fieldset>
		<fieldset>
			<legend>Datos que debe llenar el Médico que Atendió al Accidentado</legend>
			<div data-linea="1">
				<label>Lugar de Atención:</label> <input type="text"
					id="lugarAtencion" name="lugarAtencion"
					value="<?php echo $datos_ficha['lugar_atencion'];?>" />
			</div>
			<div data-linea="2">
				<label>Fecha de Atención:</label> <input type="text"
					id="fechaAtencion" name="fechaAtencion"
					value="<?php echo $datos_ficha['fecha_atencion'];?>"
					data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly />

			</div>
			<div data-linea="2">
				<label>Hora:</label> <input id="horaAtencion" name="horaAtencion"
					class="menores" value="<?php echo $datos_ficha['hora_atencion'];?>"
					type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" />
			</div>

			<div data-linea="4">
				<label>Presenta Síntomas de:</label> <input type="text"
					id="nombreMedico" name="nombreMedico"
					value="<?php echo $datos_ficha['sintomas'];?>" />
			</div>
			<div data-linea="4">
				<label>Otros Datos:</label> <input type="text" id="nombreMedico"
					name="nombreMedico"
					value="<?php echo $datos_ficha['otros_datos'];?>" />
			</div>
			<div data-linea="6">
				<label>Descripción de Lesiones:</label>
			</div>
			<div data-linea="7">
				<textarea style="width: 152px; height: 60px;"
					id="descripcionLesiones" name="descripcionLesiones"><?php echo $datos_ficha['descripcion_lesiones'];?></textarea>
			</div>
			<div data-linea="8">
				<label>¿Se Trasladó a un Centro de Salud?:</label> <input type="text"
					id="nombreMedico" name="nombreMedico"
					value="<?php echo $datos_ficha['traslado_centro_salud'];?>" />
			</div>
			<div data-linea="9">
				<label>Nombre del Médico que Atiende:</label> <input type="text"
					id="nombreMedico" name="nombreMedico"
					value="<?php echo $datos_ficha['nombre_medico'];?>" />
			</div>
		
			<div data-linea="10">
				<label>Tiempo de Reposo:</label> 
			</div>
			<div data-linea="10">
				<label>Desde:</label> <input type="text" id="fechaReposoDesde" name="fechaReposoDesde"
					value="<?php echo $datos_ficha['reposo_desde'];?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly/>
			</div>
			<div data-linea="10">
				<label>Hasta:</label> <input type="text" id="fechaReposoHasta" name="fechaReposoHasta"
					value="<?php echo $datos_ficha['reposo_hasta'];?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly/>
			</div>

		</fieldset>
		
		<fieldset>
		<legend>Resultado</legend>
		<div data-linea="1">
			<label>Observación: </label><input type="text" readonly value="<?php echo $valores_accidentes['observacion'];?>" /> 
		</div>
		</fieldset>
</form>

<script type="text/javascript">

	$(document).ready(function(){
		$('input[type="text"], textarea').attr('readonly','readonly'); 
		$("#observacion").attr('readonly',false);
		$("#identificador").attr('readonly',false);
		$("#solicitud").attr('readonly',false);
		$("#estadoSolicitud").attr('readonly',false);
		$("#fechaBusque").attr('readonly',false);
		distribuirLineas();
		construirAnimacion($(".pestania"));
	});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}
$("#datosRegistroActualizar").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#resultado").val()==""){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}

	});
</script>

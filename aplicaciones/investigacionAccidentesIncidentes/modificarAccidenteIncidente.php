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
$codParroquiaAccidente=$datos_registro['id_localizacion_parroquia'];
$codProvinciaAccidente=$datos_registro['id_localizacion_provincia'];
$codCantonAccidente=$datos_registro['id_localizacion_ciudad'];


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

<form id="datosRegistro"
	data-rutaAplicacion="investigacionAccidentesIncidentes"
	data-opcion="actualizarAccidenteIncidente" data-accionEnExito="ACTUALIZAR">
   <input type="hidden" id="solicitud" value="<?php echo $solicitud;?>" name="solicitud"
					 />
	<div id="estado"></div>

	<div class="pestania">
		<fieldset>
			<legend>Identificación de Persona Accidentada</legend>

			<div data-linea="1">
				<label>* Identificación:</label> <input type="text"
					value="<?php echo $valores_accidentes['identificador_accidentado'];?>"
					id="identificadorUsuario" name="identificadorUsuario"
					onchange="buscarDatos(id);  return false;" />
				<input type="hidden" id="opcion" name="opcion"
					value="<?php echo $valores_accidentes['tipo_sso'];?>" />	
				<div id="estadoBusque"></div>

			</div>
			<div data-linea="2">
				<label>* Nombre:</label> <input type="text" id="nombreServidor"
					value="<?php echo $datos['nombre'];?>" name="nombreServidor"
					readonly />
			</div>
			<div data-linea="3">
				<label>* Fecha Nacimiento:</label> <input type="text"
					id="fechaNacimiento" name="fechaNacimiento"
					value="<?php echo $datos['fechaNacimiento'];?>" readonly />
			</div>
			<div data-linea="3">
				<label>* Edad:</label> <input type="text" id="edad" name="edad"
					value="<?php echo $datos['edad'];?>" readonly />
			</div>
			<div data-linea="5">
				<label>* Género:</label> <input type="text" id="genero" name="genero"
					value="<?php echo $datos['genero'];?>" readonly />
			</div>
			<div data-linea="5">
				<label>* Estado Civil:</label> <input type="text" id="estadoCivil"
					name="estadoCivil" value="<?php echo $datos['estadoCivil'];?>"
					readonly />
			</div>
			<div data-linea="7">
				<label>* Pertenece a un Grupo Vulnerable?:</label> <input type="text"
					id="tieneDiscapacidad" name="tieneDiscapacidad"
					value="<?php echo $datos['tieneDiscapacidad'];?>" readonly />
			</div>
			<div data-linea="8">
				<label>* Dirección Domiciliaria:</label> <input type="text"
					id="domicilio" name="domicilio"
					value="<?php echo $datos['domicilio'];?>" readonly />
			</div>

			<div data-linea="10">
				<label>* Provincia:</label> <input type="text" id="provincia"
					name="provincia" value="<?php echo $datos['provincia'];?>" readonly />
			</div>
			<div data-linea="10">
				<label>* Ciudad:</label> <input type="text" id="ciudad" name="ciudad"
					readonly value="<?php echo $datos['ciudad'];?>" />
			</div>
			<div data-linea="10">
				<label>* Sector:</label> <input type="text" id="sector" name="sector"
					readonly value="<?php echo $datos['parroquia'];?>" />
			</div>
			<div data-linea="13">
				<label>* Teléfono 1:</label> <input type="text" id="convencional"
					readonly name="convencional"
					value="<?php echo $datos['convencional'];?>" />
			</div>
			<div data-linea="13">
				<label>Teléfono 2:</label> <input type="text" id="celular" name=""
					value="<?php echo $datos['celular'];?>" readonly />
			</div>
			<div data-linea="15">
				<label>* Escolaridad:</label> <select name="escolaridad"
					id="escolaridad">
					<option value="">Seleccione...</option>
					<option value="No aplica">No aplica</option>
					<option value="Elemental">Elemental</option>
					<option value="Básica">Básica</option>
					<option value="Bachillerato">Bachillerato</option>
					<option value="Superior">Superior</option>
					<option value="Cuarto nivel">Cuarto nivel</option>
				</select>
			</div>
			<div data-linea="15">
				<label>* Profesión:</label> <input type="text" id="profesion"
					name="profesion" maxlength="16"
					value="<?php echo $valores_accidentes['profesion'];?>" />
			</div>
			<div data-linea="17">
				<label>* Ocupación:</label> <input type="text" id="nombrePuesto"
					name="nombrePuesto" value="<?php echo $datos['nombrePuesto'];?>"
					readonly />
			</div>
			<div data-linea="18">
				<label>* Horario de Trabajo:</label> <input type="text"
					id="horarioTrab" name="horarioTrab"
					value="<?php echo $valores_accidentes['horario_trabajo'];?>" />
			</div>
			<div data-linea="18">
				<label>* Tiempo Puesto Trabajo:</label> <select
					name="tiempoPuesto" id="tiempoPuesto">
					<option value="">Seleccione...</option>
					<option value="0 – 6 meses">0 – 6 meses</option>
					<option value="7 – 11 meses">7 – 11 meses</option>
					<option value="1 – 2 años">1 – 2 años</option>
					<option value="3 – 5 años">3 – 5 años</option>
					<option value="6 – 10 años">6 – 10 años</option>
					<option value="11 – 15 años">11 – 15 años</option>
					<option value="más de 15 años">más de 15 años</option>

				</select>
			</div>

		</fieldset>
		<fieldset>
			<legend>Información del Accidente</legend>
			<div data-linea="1">
				<label>* Día de la Semana:</label> <select name="diaSemana"
					id="diaSemana">
					<option value="">Seleccione...</option>
					<option value="Lunes">Lunes</option>
					<option value="Martes">Martes</option>
					<option value="Miércoles">Miércoles</option>
					<option value="Jueves">Jueves</option>
					<option value="Viernes">Viernes</option>
					<option value="Sábado">Sábado</option>
					<option value="Domingo">Domingo</option>

				</select>
			</div>
			<div data-linea="2">
				<label>* Fecha del Accidente:</label> <input type="text"
					id="fechaSuceso" name="fechaSuceso"
					value="<?php echo $datos_registro['fecha_accidente']?>"
					data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly />

			</div>
			<div data-linea="2">
				<label>* Hora:</label> <input id="horaAccidente" name="horaAccidente"
					class="menores"
					value="<?php echo $datos_registro['hora_accidente']?>" type="text"
					placeholder="10:30" data-inputmask="'mask': '99:99'" />

			</div>
			<div data-linea="4">
				<label>* Tipo de Accidente:</label> <select name="tipoAccidente"
					id="tipoAccidente">
					<option value="">Seleccione...
					</option>
					<option value="Fallecimiento">Fallecimiento</option>
					<option value="Incapacidad">Incapacidad</option>
				</select>
			</div>
			<div data-linea="4">
				<label>* Lugar del Accidente:</label> <select name="lugarAccidente"
					id="lugarAccidente">
					<option value="">Seleccione...</option>
					<option value="En el centro o lugar de trabajo habitual">En el
						centro o lugar de trabajo habitual</option>
					<option value="En desplazamiento en su jornada laboral">En
						desplazamiento en su jornada laboral</option>
					<option value="En otro centro o lugar de trabajo">En otro centro o
						lugar de trabajo</option>
					<option value="Al ir o volver del trabajo in itínere">Al ir o
						volver del trabajo in itínere</option>
					<option value="En comisión de servicios">En comisión de servicios</option>
				</select>
			</div>
			<div data-linea="6">
				<label>* Dirección:</label> <input type="text" id="direccion" maxlength="64"
					name="direccion" value="<?php echo $datos_registro['direccion']?>" />
			</div>
			<div data-linea="7">
				<label>* Referencia:</label> <input type="text" maxlength="64"
					id="referenciaAccidente" name="referenciaAccidente"
					value="<?php echo $datos_registro['referencia']?>" />
			</div>
			<div data-linea="8">
				<label>* Provincia:</label> <select id="provinciaAccidente"
					name="provinciaAccidente">
					<option value="<?php echo $codProvinciaAccidente;?>">
						<?php echo $provinciaAccidente;?>
					</option>
					<?php 	
					$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
					foreach ($provincias as $provincia){
						if($provinciaAccidente != $provincia['nombre'])
						echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
					}
					?>
				</select> <input type="hidden" id="nombreProvincia"
					name="nombreProvincia" />
			</div>
			<div data-linea="8">
				<label>* Ciudad:</label> <select id="cantonAccidente"
					name="cantonAccidente">
				</select> <input type="hidden" id="nombreCanton" name="nombreCanton" />
			</div>
			<div data-linea="8">
				<label>* Sector:</label> <select id="parroquiaAccidente"
					name="parroquiaAccidente">
				</select> <input type="hidden" id="nombreParroquia"
					name="nombreParroquia" />
			</div>
		</fieldset>
	</div>

	<div class="pestania">
		<fieldset>
			<legend>Descripción y Circunstancias del Accidente</legend>
			<div data-linea="1">
				<label>* Describir que hacia el Trabajador y como se Lesionó:</label><br>
			</div>
			<div data-linea="2">
				<textarea
					style="width: 150px; height: 60px; display: inline-block; vertical-align: middle;"
					id="describirAccidente" name="describirAccidente" maxlength="255"><?php echo trim($datos_circunstancias['describir_accidentado']);?></textarea>
			</div>
			<div data-linea="3">
				<label>* ¿Era su Trabajo Habitual?:</label> <select
					name="trabajoHabitual" id="trabajoHabitual">
					<option value="">Seleccione...</option>
					<option value="SI">Si</option>
					<option value="NO">No</option>
				</select>
			</div>
			<div data-linea="4">
				<label>* ¿Ha Sido Accidente de Trabajo?:</label> <select
					name="accidenteTrabajo" id="accidenteTrabajo">
					<option	value="">Seleccione...
					</option>
					<option value="SI">Si</option>
					<option value="NO">No</option>
				</select>
			</div>
			<div data-linea="5">
				<label>* Partes Lesionadas del Cuerpo:</label> <input type="text"
					id="partesLesionadas" name="partesLesionadas" maxlength="64"
					value="<?php echo $datos_circunstancias['partes_lesionadas']?>" />
			</div>
			<div data-linea="6">
				<label>* Persona que lo Atendió Inmediatamente:</label> <input
					type="text" id="personaAtendio" name="personaAtendio" maxlength="64"
					value="<?php echo $datos_circunstancias['persona_atendio']?>" />
			</div>
			<div data-linea="7">
				<label>* El Accidentado fue Trasladado a:</label> <input type="text"
					id="trasladoAccidente" name="trasladoAccidente" maxlength="64"
					value="<?php echo $datos_circunstancias['traslado_accidentado']?>" />
			</div>
		</fieldset>
		<fieldset>
			<legend>Información de Testigos</legend>
			<div data-linea="1">
				<label>Nombre:</label> <input type="text" id="nombreTestigo"
					name="nombreTestigo" maxlength="64"
					value="<?php echo $datos_circunstancias['nombre_testigo']?>" />
			</div>
			<div data-linea="2">
				<label>Dirección Domiciliaria:</label> <input type="text"
					id="direccionTestigo" name="direccionTestigo" maxlength="128"
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
					id="lugarAtencion" name="lugarAtencion" maxlength="64"
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
				<label>Presenta Síntomas de:</label> <select name="presentaSintomas"
					id="presentaSintomas">
					<option value="">Seleccione...</option>
					<option value="Intoxicación por alcohol">Intoxicación por alcohol</option>
					<option value="Intoxicación por otras drogas">Intoxicación por
						otras drogas</option>
				</select>
			</div>
			<div data-linea="4">
				<label>Otros Datos:</label> <select name="otrosDatos"
					id="otrosDatos">
					<option value="">Seleccione...</option>
					<option value="Hubo riña">Hubo riña</option>
					<option value="Hay sospecha de simulación">Hay sospecha de
						simulación</option>
				</select>
			</div>
			<div data-linea="6">
				<label>Descripción de Lesiones:</label>
			</div>
			<div data-linea="7">
				<textarea style="width: 152px; height: 60px;"
					id="descripcionLesiones" name="descripcionLesiones"><?php echo $datos_ficha['descripcion_lesiones'];?></textarea>

			</div>
			<div data-linea="8">
				<label>¿Se Trasladó a un Centro de Salud?:</label> <select
					name="trasladoCentroSalud" id="trasladoCentroSalud">
					<option value="">Seleccione...</option>
					<option value="SI">Si</option>
					<option value="NO">No</option>
				</select>
			</div>
			<div data-linea="9">
				<label>Nombre del Médico que Atiende:</label> <input type="text"
					id="nombreMedico" name="nombreMedico" maxlength="64"
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
		<button id="guardarForm" type="submit" class="guardar">Guardar</button>
	</div>

</form>

<script type="text/javascript">

	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_oficina= <?php echo json_encode($oficinas); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;
	var cantonActual=<?php echo json_encode($cantonAccidente); ?>;
	var parroquiaActual=<?php echo json_encode($parroquiaAccidente); ?>;
	var codCantonActual=<?php echo json_encode($codCantonAccidente); ?>;
	var codParroquiaActual=<?php echo json_encode($codParroquiaAccidente); ?>;
  
	$(document).ready(function(){
		
		$("#identificadorUsuario").attr('readonly',true);
		
		scanton = '<option value="'+codCantonActual+'">'+cantonActual+'</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provinciaAccidente").val()==array_canton[i]['padre']){
			    if(cantonActual != array_canton[i]['nombre'])
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			    }
	   		}
	    $('#cantonAccidente').html(scanton);
		soficina = '<option value="'+codParroquiaActual+'">'+parroquiaActual+'</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	if(parroquiaActual != array_parroquia[i]['nombre'])
		    	soficina += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#parroquiaAccidente').html(soficina);
	    $("#fechaReposoHasta").attr('disabled','disabled');

		construirValidador();
		distribuirLineas();
		construirAnimacion($(".pestania"));

		cargarValorDefecto("escolaridad","<?php echo $valores_accidentes['escolaridad'];?>");
		cargarValorDefecto("tiempoPuesto","<?php echo $valores_accidentes['tiempo_puesto']?>");
		cargarValorDefecto("diaSemana","<?php echo $datos_registro['dia']?>");
		cargarValorDefecto("lugarAccidente","<?php echo $datos_registro['lugar_accidente']?>");
		cargarValorDefecto("tipoAccidente","<?php echo $datos_registro['tipo_accidente']?>");
		cargarValorDefecto("trabajoHabitual","<?php echo $datos_circunstancias['trabajo_habitual']?>");
		cargarValorDefecto("accidenteTrabajo","<?php echo $datos_circunstancias['accidente_trabajo']?>");
		cargarValorDefecto("presentaSintomas","<?php echo $datos_ficha['sintomas'];?>");
		cargarValorDefecto("otrosDatos","<?php echo $datos_ficha['otros_datos'];?>");
		cargarValorDefecto("trasladoCentroSalud","<?php echo $datos_ficha['traslado_centro_salud'];?>");
	  	
	});

	$("#fechaSuceso").datepicker({
		changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    maxDate: 0
	    
	    
	  });		
	$("#fechaAtencion").datepicker({
		changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    
	  });	
function buscarDatos(id){
	        var valor = $('#identificadorUsuario').val();

	        if(valor.length>=9){
	          // $('#estadoBusqued').html('Cargando datos del servidor...');
	           var consulta = $.ajax({
	              type:'POST',
	              url:'aplicaciones/investigacionAccidentesIncidentes/buscarDatosUsuario.php',
	              data:{identificador:valor},
	              dataType:'JSON'
	           });
	           consulta.done(function(data){
	              if(data.error!==undefined){
	                 $('#estadoBusque').html(data.error).addClass("alerta");
	                 $('#nombreServidor').val('');
	                 $('#fechaNacimiento').val('');
	                 $('#edad').val('');
	                 $('#genero').val('');
	                 $('#estadoCivil').val('');
	                 $('#tieneDiscapacidad').val('');
	                 $('#domicilio').val('');
	                 $('#convencional').val('');
	                 $('#sector').val('');
	                 $('#provincia').val('');
	                 $('#ciudad').val('');
	                 $('#nombrePuesto').val('');
	                 $('#celular').val('');
	                 return false;
	              } else {
		              
	                 if(data.nombre!==undefined){$('#nombreServidor').val(data.nombre);}
	                 if(data.fechaNacimiento!==undefined){$('#fechaNacimiento').val(data.fechaNacimiento);}
	                 if(data.edad!==undefined){$('#edad').val(calcularEdad(data.fechaNacimiento));}
	                 if(data.genero!==undefined){$('#genero').val(data.genero);}
	                 if(data.estadoCivil!==undefined){$('#estadoCivil').val(data.estadoCivil);}
	                 if(data.tieneDiscapacidad!==undefined){$('#tieneDiscapacidad').val(data.tieneDiscapacidad);}
	                 if(data.domicilio!==undefined){$('#domicilio').val(data.domicilio);}
	                 if(data.convencional!==undefined){$('#convencional').val(data.convencional);}
	                 if(data.parroquia!==undefined){$('#sector').val(data.parroquia);}
	                 if(data.provincia!==undefined){$('#provincia').val(data.provincia);}
	                 if(data.ciudad!==undefined){$('#ciudad').val(data.ciudad);}
	                 if(data.nombrePuesto!==undefined){$('#nombrePuesto').val(data.nombrePuesto);}
	                 if(data.celular!==undefined){$('#celular').val(data.celular);}
	                // $('#estadoBusque').html('');
	                 return true;
	              }
	           });
	           consulta.fail(function(){
	              $('#estadoBusque').html('Ha habido un error contactando al servidor.').addClass("alerta");
	              return false;
	           });     
	        } else {
	           $('#estadoBusque').html('La longitud debe ser mayor a 9 caracteres...').addClass("alerta");;
	           return false;
	        }
	 }
function calcularEdad(fecha)
{
    var values=fecha.split("-");
    var dia = values[2];
    var mes = values[1];
    var ano = values[0];

    // cogemos los valores actuales
    var fecha_hoy = new Date();
    var ahora_ano = fecha_hoy.getYear();
    var ahora_mes = fecha_hoy.getMonth();
    var ahora_dia = fecha_hoy.getDate();
    
    // realizamos el calculo
    var edad = (ahora_ano + 1900) - ano;
    if ( ahora_mes < (mes - 1))
    {
        edad--;
    }
    if (((mes - 1) == ahora_mes) && (ahora_dia < dia))
    {
        edad--;
    }
    if (edad > 1900)
    {
        edad -= 1900;
    }
   return edad; 
      
}


$("#provinciaAccidente").change(function(){
	$('#nombreProvincia').val($("#provinciaAccidente option:selected").text());
	  	scanton = '<option value="">Seleccione...</option>';
  for(var i=0;i<array_canton.length;i++){
	    if ($("#provinciaAccidente").val()==array_canton[i]['padre']){
	    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
		    }
 		}
  $('#cantonAccidente').html(scanton);
  $("#cantonAccidente").removeAttr("disabled");
});

$("#cantonAccidente").change(function(){

  	 $('#nombreCanton').val($("#cantonAccidente option:selected").text());
		soficina ='0';
		soficina = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#cantonAccidente").val()==array_parroquia[i]['padre']){
		    	soficina += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#parroquiaAccidente').html(soficina);
		$("#parroquiaAccidente").removeAttr("disabled");
	});
	
$('button.subirArchivo').click(function (event) {	
	
    var boton = $(this);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");

    if (extension[extension.length - 1].toUpperCase() == 'PDF') {

    	if($("#fecha_inicio").val() !=""){
        	
    		subirArchivo(
	                archivo
	                , $("#numero_contrato").val()+'_'+$("#fecha_inicio").val().replace(/[_\W]+/g, "-")
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new carga(estado, archivo, boton)
	            );
            
		}else{
			 alert("Debe seleccionar una fecha de ingreso!");
			 archivo.val("");
		} 
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }
});

$("#horaAtencion").change(function(){

	$("#horaAtencion").removeClass('alertaCombo');
		
		var horaNueva = $("#horaAtencion").val().replace(/\_/g, "0");
		$("#horaAtencion").val(horaNueva);
		
		var hora = $("#horaAtencion").val().substring(0,2);
		var minuto = $("#horaAtencion").val().substring(3,5);
		
		if(parseInt(hora)>=1 && parseInt(hora)<25){
			if(parseInt(minuto)>=0 && parseInt(minuto)<60){
				if(parseInt(hora)==24){
					minuto = '00';
					$("#horaAtencion").val('24:00');
				}
			}else{
				$("#horaAtencion").addClass('alertaCombo');
				$("#estado").html("Los minutos ingresados están incorrecto, por favor actualice la información").addClass('alerta');
			}
		}else{
			$("#horaAtencion").addClass('alertaCombo');
			$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
		}

	});

$("#horaAccidente").change(function(){

	$("#horaAccidente").removeClass('alertaCombo');
		
		var horaNueva = $("#horaAccidente").val().replace(/\_/g, "0");
		$("#horaAccidente").val(horaNueva);
		
		var hora = $("#horaAccidente").val().substring(0,2);
		var minuto = $("#horaAccidente").val().substring(3,5);
		
		if(parseInt(hora)>=1 && parseInt(hora)<25){
			if(parseInt(minuto)>=0 && parseInt(minuto)<60){
				if(parseInt(hora)==24){
					minuto = '00';
					$("#horaAccidente").val('24:00');
				}
			}else{
				$("#horaAccidente").addClass('alertaCombo');
				$("#estado").html("Los minutos ingresados están incorrecto, por favor actualice la información").addClass('alerta');
			}
		}else{
			$("#horaAccidente").addClass('alertaCombo');
			$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
		}

	});
function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}
$("#datosRegistro").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#opcion").val()==""){
	    	error = true;
			$("#estadoOpcion").html("Seleccione una opcion...").addClass('alerta');
		}
		if($("#identificadorUsuario").val()==""){
			error = true;
			$("#identificadorUsuario").addClass("alertaCombo");
		}
		
		if($("#escolaridad").val()==""){
			error = true;
			$("#escolaridad").addClass("alertaCombo");
		}
		if($("#profesion").val()==""){
			error = true;
			$("#profesion").addClass("alertaCombo");
		}
		if($("#horarioTrab").val()==""){
			error = true;
			$("#horarioTrab").addClass("alertaCombo");
		}
		if($("#tiempoPuesto").val()==""){
			error = true;
			$("#tiempoPuesto").addClass("alertaCombo");
		}
		
		if($("#diaSemana").val()==""){
			error = true;
			$("#diaSemana").addClass("alertaCombo");
		}
		if($("#fechaSuceso").val()==""){
			error = true;
			$("#fechaSuceso").addClass("alertaCombo");
		}
		if($("#horaAccidente").val()==""){
			error = true;
			$("#horaAccidente").addClass("alertaCombo");
		}
		if($("#tipoAccidente").val()==""){
			error = true;
			$("#tipoAccidente").addClass("alertaCombo");
		}
		if($("#lugarAccidente").val()==""){
			error = true;
			$("#lugarAccidente").addClass("alertaCombo");
		}
		if($("#direccion").val()==""){
			error = true;
			$("#direccion").addClass("alertaCombo");
		}
		if($("#referenciaAccidente").val()==""){
			error = true;
			$("#referenciaAccidente").addClass("alertaCombo");
		}
		if($("#provinciaAccidente").val()==""){
			error = true;
			$("#provinciaAccidente").addClass("alertaCombo");
		}
		if($("#cantonAccidente").val()==""){
			error = true;
			$("#cantonAccidente").addClass("alertaCombo");
		}
		if($("#parroquiaAccidente").val()==""){
			error = true;
			$("#parroquiaAccidente").addClass("alertaCombo");
		}
		if($("#trabajoHabitual").val()==""){
			error = true;
			$("#trabajoHabitual").addClass("alertaCombo");
		}
		if($("#accidenteTrabajo").val()==""){
			error = true;
			$("#accidenteTrabajo").addClass("alertaCombo");
		}
		if($("#partesLesionadas").val()==""){
			error = true;
			$("#partesLesionadas").addClass("alertaCombo");
		}
		if($("#trasladoAccidente").val()==""){
			error = true;
			$("#trasladoAccidente").addClass("alertaCombo");
		}
		if($("#personaAtendio").val()==""){
			error = true;
			$("#personaAtendio").addClass("alertaCombo");
		}
		if($("#describirAccidente").val()==""){
			error = true;
			$("#describirAccidente").addClass("alertaCombo");
		}
		if($("#describirAccidente").val().length > 256){
			error = true;
			$("#describirAccidente").addClass("alertaCombo");
			$("#estado").text('Cantidad de caracteres es mayor a la permitida ...').addClass("alerta");
		}
		if (!error){
			$("#fechaReposoHasta").removeAttr('disabled','disabled');
			ejecutarJson($(this));
		}else{
			$("#estado").html("Todos los campos con ( * ) son obligatorios...!").addClass('alerta');
			}

	});
function agregarOpcion(id){
	$("#opcion").val($("#"+id).val());
}
$("#fechaReposoHasta").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
  });
  
$("#fechaReposoDesde").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    
    onSelect: function(dateText, inst) {
    	$("#fechaReposoHasta").removeAttr('disabled','disabled');
	  	$('#fechaReposoHasta').datepicker('option', 'minDate', $("#fechaReposoDesde" ).val()); 
	  	$('#fechaReposoHasta').datepicker('option', 'maxDate', "+2Y");
	  	$('#fechaReposoHasta').val($("#fechaReposoDesde" ).val()); 
	   
    }
});
</script>

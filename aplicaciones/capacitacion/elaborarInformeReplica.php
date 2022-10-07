<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$ce = new ControladorCapacitacion();
$cc = new ControladorCatalogos();
$cu = new ControladorUsuarios();
$ca = new ControladorAreas();

$idRequerimiento=$_POST['id'];
$identificador=$_SESSION['usuario'];

$resCapacitacion = $ce->obtenerRequerimientos($conexion,'','','',$idRequerimiento,'','','','');
$capacitacion = pg_fetch_assoc($resCapacitacion);

$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$res = $cc->listarLocalizacion($conexion, 'PAIS');
$area = $ca->listarAreas($conexion);

?>
<header>
	<h1>Visualizar informe de capacitación</h1>
</header>

<form id="finalizarRequerimiento" data-rutaAplicacion="capacitacion" data-opcion="finalizarRequerimientoForm">
	<input type="hidden" id="opcion" name="opcion" value="Actualizar" />
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" />
	<input type="hidden" id="rutaCompleta" name="rutaCompleta" value="" /> 
	<input type="hidden" id="identificadorFuncionario" name="identificadorFuncionario" value=""/> 
	<div id="estado"></div>
	
	<fieldset>
		<legend>Información Empleado</legend>

		<div data-linea="1">
			<label>Tipo de evento</label> 
			<select name="tipoEvento" id="tipoEvento" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<?php 							  	
					$tipoEvento = $cc->listarTiposCapacitacion($conexion);
					while($fila = pg_fetch_assoc($tipoEvento)){
						echo '<option value="' . $fila['codigo'] . '">' . $fila['nombre'].' </option>';
					}
				?>
			</select>
		</div>
		<div data-linea="1">
			<label>Tipo de certificado</label> 
			<select name="tipoCertificado"	id="tipoCertificado" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="Asistencia">Asistencia</option>
				<option value="Aprobacion">Aprobación</option>
			</select>
		</div>
		<div data-linea="2">
			<label>Nombre del evento</label>
			<input type="text"	name="nombre_evento" id="nombre_evento" readonly="readonly"	value="<?php echo $capacitacion['nombre_evento']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
		</div>
		<div data-linea="3">
			<label></label>
		</div>
		<div data-linea="4">
			<label>Empresa capacitadora</label> <input type="text" id="empresaCapacitadora" name="empresaCapacitadora" class="desabilitado" value="<?php echo $capacitacion['empresa_capacitadora']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly="readonly" />
		</div>
		<div data-linea="5">
			<label>Fecha inicio</label> 
			<input type="text" id="fechaInicio"	name="fechaInicio" required="required" class="desabilitado" value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_inicio']));?>" readonly="readonly" />
		</div>
		<div data-linea="5">
			<label>Fecha fin</label>
			<input type="text" id="fechaFin" name="fechaFin" required="required" class="desabilitado" value="<?php echo date('d/m/Y',strtotime( $capacitacion['fecha_fin']));?>" readonly="readonly" />
		</div>
		<div data-linea="6">
			<label>Capacitación Interna</label>
			<select name="capacitacionInterna" id="capacitacionInterna" disabled="disabled">
				<option value="">Seleccione....</option>
				<option value="SI">SI</option>
				<option value="NO">NO</option>
			</select>
		</div>
		<div data-linea="6">
			<label>Horas</label>
			<input type="text" id="horas" name="horas" size="4" value="<?php echo $capacitacion['horas']?>" data-inputmask="'mask': '9[99]'" data-er="[0-9]{1,2}" title="99" readonly="readonly" />
		</div>
		<div data-linea="7">
			<label>Es evento pagado?</label> 
			<select name="eventoPagado"	id="eventoPagado" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="SI">Si</option>
				<option value="NO">No</option>
			</select>
		</div>
		<div data-linea="7">
			<label id="etiquetaCosto">Costo total</label>
			<input type="text"	id="costoUnitario" name="costoUnitario" readonly="readonly" value="<?php echo $capacitacion['costo_unitario']?>" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
		</div>
	
	</fieldset>
	<fieldset>
		<legend>Lugar del evento</legend>
		<div data-linea="1">
			<label>Localidad</label>
			<select name="localizacion"	id="localizacion" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="Nacional">Nacional</option>
				<option value="Internacional">Internacional</option>
			</select>
		</div>
		<div data-linea="1">
			<label>País</label>
			<select name="pais" id="pais" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<?php
					while($pais = pg_fetch_assoc($res)){
						echo '<option value="'.$pais['nombre'].'">'.$pais['nombre'].'</option>';
					}
				?>
			</select>
		</div>
		<div data-linea="2">
			<label id="etiquetaProvincia">Provincia</label>
			<select	id="provincia" name="provincia" disabled="disabled" class="desabilitado">
				<option value="">Provincia....</option>
				<?php 	
					$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
					foreach ($provincias as $provincia){
						echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
					}
				?>
			</select>
		</div>
		<div data-linea="2">
			<label id="etiquetaCanton">Canton</label> <select id="canton"
				name="canton" disabled="disabled" class="desabilitado">
			</select>
		</div>
		<div data-linea="3">
			<label id="etiquetaCiudad">Ciudad</label>
			<input type="text"	id="ciudad" name="ciudad" value="<?php echo $capacitacion['ciudad']?>" readonly="readonly" class="desabilitado" />
		</div>
	</fieldset>
	<fieldset>
		<legend>Justificación del evento</legend>
		<div data-linea="1">
			<label>Descripción</label>
		</div>
		<div data-linea="2">
			<textarea rows="4" id="justificacion" readonly="readonly" name="justificacion" class="desabilitado" style="resize:none"><?php echo $capacitacion['justificacion']?></textarea>
		</div>
	</fieldset>
	<fieldset>
		<legend>Aprobación del requerimiento</legend>
		<div data-linea="1">
			<label>Estado del requerimiento</label>
			<input type="text" value="<?php
					switch ($capacitacion['estado_requerimiento']){
						case 0: $tituloEstado="Solicitud rechazada por el director"; break;
						case 1: $tituloEstado="Solicitud rechazada por talento humano"; break;
						case 6: $tituloEstado="Solicitud ingresada"; break;
						case 8: $tituloEstado="Solicitud devuelta por talento humano"; break;
						case 11: $tituloEstado="Solicitud aprobada por el director"; break;
						case 12: $tituloEstado="Solicitud aprobada para certificación financiera"; break;
						case 13: $tituloEstado="Solicitud con certificación financiera"; break;
						case 14: $tituloEstado="Solicitud por asignar replicación"; break;
						case 17: $tituloEstado="Solicitud para entrega de formato replica"; break;
					}
			
		echo $tituloEstado;?>" readonly="readonly" class="desabilitado" />
		</div>
		<div data-linea="2">
			<label>Observación del director</label>
		</div>
		<div data-linea="3">
			<textarea rows="3" id="observacion" name="observacion"	readonly="readonly" class="desabilitado" style="resize:none"><?php echo $capacitacion['observacion'];?></textarea>
		</div>
		<div data-linea="4">
			<label>Observación talento humano</label>
		</div>		
		<div data-linea="5">
			<textarea rows="3" id="observacionTH" name="observacionTH"	readonly="readonly" style="resize:none"><?php echo $capacitacion['observacion_talento_humano'];?></textarea>
		</div>
	</fieldset>
	<fieldset id="fCertificacionFinanciera">
		<legend>Certificación financiera</legend>

		<div data-linea="1">
			<label>Nombre de la partida:</label> <input type="text"
				id="nombre_certificacion" name="nombre_certificacion"
				value="<?php echo $capacitacion['nombre_certificacion']?>"
				readonly="readonly" />
		</div>
		<div data-linea="2">
			<label>fecha de la partida:</label> <input type="text"
				id="fecha_partida" name="fecha_partida"
				value="<?php echo $capacitacion['fecha_partida']?>"
				readonly="readonly" />
		</div>
		<div data-linea="2">
			<label>No. certificación:</label> <input type="text"
				id="numero_certificacion" name="numero_certificacion"
				value="<?php echo $capacitacion['numero_certificacion']?>"
				readonly="readonly" />
		</div>
		<div data-linea="5">
			<label>Archivo certificación</label>
			<?php echo ($capacitacion['archivo']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$capacitacion['archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
		</div>
		<div id="status"></div>
	</fieldset>
	<fieldset>
		<legend>Información para el informe talento humano</legend>
		<div data-linea="1">
			<label>Objetivo del curso</label>
		</div>
		<div data-linea="2">
			<textarea rows="3" id="objetivoCurso" name="objetivoCurso"	readonly="readonly" style="resize:none"> <?php echo $capacitacion['objetivo_curso'];?></textarea>
		</div>
		<div data-linea="3">
			<label>Justificación de recursos humanos</label>
		</div>
		<div data-linea="4">
			<textarea rows="3" id="justificacionTH" name="justificacionTH" readonly="readonly" style="resize:none"> <?php echo $capacitacion['justificacion_th']?> </textarea>
		</div>
		<div data-linea="5">
			<label>Informe talento humano</label>
			<?php echo ($capacitacion['ruta_informe']==''? '<span class="alerta">No se ha generado el informe</span>':'<a href='.$capacitacion['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe generado</a>')?>
		</div>
	</fieldset> 
	<?php
		$resFuncionarios=$ce->obtenerFuncionarios($conexion,$idRequerimiento);
		$contadorFuncionarios=0;
		while($filaFuncionario = pg_fetch_assoc($resFuncionarios)){
		    $contadorFuncionarios++;
		    $nombre=$filaFuncionario['nombre'];
		    $apellido=$filaFuncionario['apellido'];
		    echo '<fieldset><legend>funcionarios replicados por '.$apellido.' '.$nombre.'</legend>';
			echo'<table style="width:100%">
			<tbody id="ocupante_'.$filaFuncionario['identificador'].'">';
		    $obtenerFuncionariosReplicados= $ce->obtenerFuncionariosReplicados($conexion,$idRequerimiento,$filaFuncionario['identificador']);
		    $bandera=false;
		    $contador=0;
		    $acumulado=0;
		    $find = array('/[\-\:\ ]+/', '/&lt;{^&gt;*&gt;/');
			$fecha=preg_replace($find, '', date('Y-m-d h:i:sa'));
  			while($fila = pg_fetch_assoc($obtenerFuncionariosReplicados)){
				$contador++;
				echo '<tr><td><label>'.$fila['apellido'].' '.$fila['nombre'].'</label></td>
				<td><label>Calificación: ';
				if($fila['calificacion']!=0){
					$calificacion=$fila['calificacion']+'/20';
					$acumulado+=$calificacion;
				}else{
					$calificacion="<label class='alerta'>Aun no califica</label>";
					$bandera=true;
				}
				echo $calificacion.'</label></td></tr>';
			}
			echo '<tr><td></td><td><label>Total:'.round($acumulado/$contador, 2).'</label></td></tr>';
			echo '</tbody>
			</table>
			</fieldset>';
			if($filaFuncionario['archivo_firmado']!=""){
				echo '<fieldset id='.$filaFuncionario['identificador'].' class="documento_firmado">
				<legend>Informe de réplica</legend>
				<div data-linea="1">
				<label>Archivo de réplica entregado por: '.$apellido.' '.$nombre.'. </label>';
				echo $filaFuncionario['archivo_firmado']==''? '<span class="alerta">No se ha cargado ningún informe</span>':'<a href='.$filaFuncionario['archivo_firmado'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Documento Cargado</a></div>
				</fieldset>';
			}else{
				echo '<fieldset id='.$filaFuncionario['identificador'].'>
				<legend>Informe de revisión de '.$apellido.' '.$nombre.'</legend>
				<div data-linea="1">
				<input type="file" class="archivo" name="informe" accept="application/pdf"/>
				<input type="hidden" class="rutaArchivo" name="archivo" value="0"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo ';
				echo ini_get('upload_max_filesize');
				echo 'B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/capacitacion/generados" >Subir archivo</button>
				<input type="hidden" id="fecha" name="fecha" value="'.$fecha.'"/>
				</div>
				<div data-linea="2" id="reporte_generado_'.$filaFuncionario['identificador'].'"></div>					
				</fieldset>';
			}
		}
	?>
	<input type="hidden" id="contadorFuncionarios" name="contadorFuncionarios" value="<?php echo $contadorFuncionarios;?>" />
			
	<p>
		<button id="actualizar" type="submit" class="guardar">Finalizar el	proceso</button>
	</p>
</form>


<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var usuario = <?php echo json_encode($identificador);?>;
var idSolicitud = <?php echo json_encode($idRequerimiento);?>

$(document).ready(function(){
 	construirValidador();
	distribuirLineas();
	cargarValorDefecto("tipoEvento","<?php echo $capacitacion['tipo_evento']?>");
	cargarValorDefecto("tipoCertificado","<?php echo $capacitacion['tipo_certificado']?>");
	cargarValorDefecto("eventoPagado","<?php echo $capacitacion['evento_pagado']?>");
	cargarValorDefecto("localizacion","<?php echo $capacitacion['localizacion']?>");
	cargarValorDefecto("pais","<?php echo $capacitacion['pais']?>");
	localizacion();
	eventoPagado();
	cargarValorDefecto("provincia","<?php echo $capacitacion['provincia']?>");
	llenarCanton();
	cargarValorDefecto("canton","<?php echo $capacitacion['canton']?>");
	cargarValorDefecto("capacitacionInterna","<?php echo $capacitacion['capacitacion_interna']?>");

	if($("#eventoPagado").val()=='NO'){
		$("#fCertificacionFinanciera").hide();
	}
	
 });
 
$('button.subirArchivo').click(function (event) {
	
    var boton = $(this);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");
       
    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
        subirArchivo(
            archivo
            , usuario + "_" + idSolicitud+"_"+$('#fecha').val().replace(/ /g,'')
            , boton.attr("data-rutaCarga")
            , rutaArchivo
            , new carga(estado, archivo, boton)
        );
        $(this).parent().parent().addClass("documento_firmado");
      	$("#identificadorFuncionario").val($(this).parent().parent().attr('id'));
        $("#rutaCompleta").val(boton.attr("data-rutaCarga")+"/"+usuario + "_" + idSolicitud+"_"+$('#fecha').val().replace(/ /g,'')+'.pdf');
    	
        $("#finalizarRequerimiento").attr('data-opcion', 'finalizarReporteReplica');
        $("#finalizarRequerimiento").attr('data-destino', 'reporte_generado_'+$(this).parent().parent().attr('id'));
        $("#finalizarRequerimiento").removeAttr('data-accionEnExito');
        abrir($("#finalizarRequerimiento"), event, false);
        habilitar();
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }
});

$("#provincia").change(function(){
	llenarCanton();
});

function llenarCanton() {
	$('#nombreProvincia').val($("#provincia option:selected").text());
 	scanton = '<option value="">Canton...</option>';
    for(var i=0;i<array_canton.length;i++){
	    if ($("#provincia").val()==array_canton[i]['padre']){
	    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
		    }
   		}
    $('#canton').html(scanton);
}

$("#eventoPagado").change(function(){
	eventoPagado();
});

function eventoPagado(){
	$("#costoUnitario").removeAttr("disabled");
	if($("#eventoPagado option:selected").val()=="NO"){
		$("#etiquetaCosto").hide();
		$("#costoUnitario").hide();
	}else{
		$("#etiquetaCosto").show();
		$("#costoUnitario").show();
	}
}

$("#localizacion").change(function(){
		localizacion();
});

function localizacion(){
	if($("#localizacion option:selected").val()=="Nacional"){
		$("#etiquetaProvincia").show();
		$("#provincia").show();
		$("#etiquetaCanton").show();
		$("#canton").show();
		$("#pais option[value=Ecuador]").attr('selected','selected');
		$("#pais").attr("disabled","disabled");
		$("#etiquetaCiudad").hide();
		$("#ciudad").hide();
	}else{
		$("#etiquetaProvincia").hide();
		$("#provincia").hide();
		$("#etiquetaCanton").hide();
		$("#canton").hide();
		$("#etiquetaCiudad").show();
		$("#ciudad").show();
	}
}



$("#finalizarRequerimiento").submit(function(event){
	 event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if( ($('.documento_firmado').length)<($('#contadorFuncionarios').val())){
		 $('#estado').html('Debe subir todos los informes para poder finalizar el proceso').addClass('alerta');
		 error=true;
	}
	if (!error){
	    $("#finalizarRequerimiento").attr('data-opcion', 'finalizarRequerimientoForm');
	    $('modificarRequerimiento.desabilitado').prop('disabled', false);
	    ejecutarJson(this); 	
	    if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente'){
			$('#_actualizar').click(); 
	    }
	}
});
</script>

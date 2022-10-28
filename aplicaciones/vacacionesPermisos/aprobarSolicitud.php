<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorAreas.php';
$conexion = new Conexion();
$cv = new ControladorVacaciones();
$cc = new ControladorCatastro();
$ca = new ControladorAreas();
$subtipos = $cv->obtenerSubTipoPermiso($conexion);
$identificador = $_SESSION['usuario'];
while ($fila = pg_fetch_assoc($subtipos)){
	$resTipos[] = array(id_subtipo_permiso=>$fila['id_subtipo_permiso'],nombre=>$fila['descripcion_subtipo'],
			minutos=>$fila['minutos_permitidos'],id_tipo_permiso=>$fila['id_tipo_permiso'],
			requiere_adjunto=>$fila['requiere_adjunto'],presentacion_reintegro=>$fila['presentacion_despues_reintegro'],
			detalle_permiso=>$fila['detalle_permiso'], codigo=>$fila['codigo']);
}
$id_subtipo=$_POST['id'];
$filaSolicitud = pg_fetch_assoc($cv->obtenerPermisoSolicitado($conexion,$id_subtipo));
//Verificar saldo disponible para el funcionario que solicita el permiso
$qSaldos =  $cv->consultarSaldoFuncionario($conexion,$filaSolicitud['identificador']);
if(pg_num_rows($qSaldos) > 0){
	$saldos = pg_fetch_result($cv->consultarSaldoFuncionario($conexion,$filaSolicitud['identificador']), 0, 'minutos_disponibles');
}else{	
	$saldos = 0;
}
//Tiempo disponible funcionario solicitante
$saldoDisponible = pg_fetch_result($cv->consultarSaldoFuncionario($conexion,$filaSolicitud['identificador']), 0, 'minutos_disponibles');
$dias=floor(intval($saldoDisponible)/480);
$horas=floor((intval($saldoDisponible)-$dias*480)/60);
$minutos=(intval($saldoDisponible)-$dias*480)-$horas*60;
$diasDisponibles = $dias .' días, '.$horas.' horas, '.$minutos.' minutos';
?>
<header>
	<h1>Solicitudes por Revisar</h1>
</header>
<form id="aprobarSolicitud" data-rutaAplicacion="vacacionesPermisos" data-opcion="gestionAprobaciones" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" value="Actualizar" /> 
	<input type="hidden" id="id_solicitud_permiso" name="id_solicitud_permiso" value="<?php echo $id_subtipo;?>" />
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $filaSolicitud['identificador'];?>" />
	<input type="hidden" id="tiempoSolicitado" name="tiempoSolicitado" value="<?php echo $filaSolicitud['minutos_utilizados']; ?>" /> 
	<input type="hidden" id="disponibilidad" name="disponibilidad" value="<?php echo $saldos; ?>" />
	<input type="hidden" id="codigoPermiso" name="codigoPermiso" value="<?php echo $filaSolicitud['codigo']; ?>" />  
	<div id="estado"></div>
	<fieldset>
		<legend>Tipo de Solicitud</legend>
		<div data-linea="1">
			<label>Permiso por: </label>
			<?php echo $filaSolicitud['descripcion_subtipo'];?>
		</div>
		<div data-linea="3" id="comisionLocal">
			<label>Destino Comisión Local</label> 
				<?php echo $filaSolicitud['destino_comision'];?>
		</div>
		<div data-linea="4" id="comisionProvincial">
			<label>Destino Comisión Provincial</label>  
				<?php echo $filaSolicitud['destino_comision'];?>
		</div>
		<div data-linea="2">
			<?php echo $filaSolicitud['detalle_permiso'];?>
		</div>
	</fieldset>
	<fieldset id="detalle">
		<legend>Fechas y Tiempo requerido</legend>
		<div data-linea="4">
			<label id="etiquetaFechaSuceso">Fecha de suceso: </label>
				<?php echo date('j/n/Y',strtotime($filaSolicitud['fecha_suceso']));?>
		</div>
		<hr id="separador">
		<div data-linea="5">
			<label>Fecha de salida</label> 
				<?php echo date('j/n/Y',strtotime($filaSolicitud['fecha_inicio']));?>
		</div>
		<div data-linea="5">
			<label>Hora de salida:</label> 
				<?php echo date('H:i',strtotime($filaSolicitud['fecha_inicio']));?>
		</div>
		<hr>
		<div data-linea="6">
			<label>Fecha de retorno</label> 
				<?php echo date('j/n/Y',strtotime($filaSolicitud['fecha_fin']));?>
		</div>
		<div data-linea="6">
			<label>Hora de retorno:</label> 
				<?php echo date('H:i',strtotime($filaSolicitud['fecha_fin']));?>
		</div>
	</fieldset>
	<fieldset id="adjuntos">
		<legend>Documento para Justificación</legend>
		<div data-linea="1">
			<?php echo ($filaSolicitud['ruta_archivo']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$filaSolicitud['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
		</div>
	</fieldset>
	<?php 
	$consult=$cv->obtenerEncargadoPuestoArea($conexion,$filaSolicitud['identificador'],'','','',$id_subtipo,'');
	if(pg_num_rows($consult)){
               $row = pg_fetch_assoc($consult);
               $datos= pg_fetch_assoc($cc->filtroObtenerDatosFuncionario($conexion, $row['identificador_encargado']));
				echo '<fieldset> 
						<legend>Funcionario encargado</legend>';
				echo '<div data-linea="1"> 
						<label>Funcionario:  </label>';
				echo $datos['nombre'].' '.$datos['apellido'];
				echo ' </div>';
				echo '<div data-linea="2">
						<label>Memorando designación:  </label>';
						echo ($row['ruta_subrogacion']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$row['ruta_subrogacion'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>');
				echo ' </div>';
				echo '</fieldset>';
		}	   
	 ?>
	<fieldset>
		<legend>Revisión del Jefe Inmediato</legend>
		<div data-linea="1">
			<label>La solicitud ha sido </label> 
			<select
				id="estado_solicitud" name="estado_solicitud">
				<option value="">Seleccione....</option>
				<option value="Aprobado">Aprobada</option>
				<option value="Rechazado">Rechazada</option>
			</select>
		</div>
		<div data-linea="2">
			<label>Observaciones</label> 
			<input type="text" id="observaciones" name="observaciones" />
		</div>
	</fieldset>
	<p>
		<button id="actualizar" type="submit" class="guardar"
		<?php echo $filaSolicitud['estado']=='Aprobado'?' disabled:disabled':''; ?>>Guardar</button>
	</p>
</form>
<script type="text/javascript">
var array_subTipos= <?php echo json_encode($resTipos); ?>;
var tiempoDisponible = true;
var disponibilidad = $("#disponibilidad").val();
var tiempoSolicitado = $("#tiempoSolicitado").val();
var diasDisponibles= <?php echo json_encode($diasDisponibles); ?>;
$(document).ready(function(){
	distribuirLineas();
	construirValidador();
	$("#comisionLocal").hide();
	$("#comisionProvincial").hide();
	if($("#codigoPermiso").val() == 'PE-PIV' || $("#codigoPermiso").val() == 'VA-VA' || $("#codigoPermiso").val() == 'PE-PIVF'){
		if(parseInt(disponibilidad) >= parseInt(tiempoSolicitado)){
			$('#estado').html('El funcionario dispone de '+diasDisponibles+' para este permiso.').addClass('exito');
			tiempoDisponible = true;
		}else{
			$('#estado').html('El funcionario no dispone de tiempo suficiente para este permiso, por favor rechace la solicitud pidiendo que realice una modificación en el tiempo. Tiempo disponible: '+diasDisponibles).addClass('alerta');
			alert('El funcionario no dispone de tiempo suficiente para este permiso, por favor rechace la solicitud indicando que realice una modificación en el tiempo. Tiempo disponible: '+diasDisponibles);
			tiempoDisponible = false;
		}
	}
	if($("#codigoPermiso").val()=="PE-CE" || $("#codigoPermiso").val()=="PE-CL" || $("#codigoPermiso").val()=="VA-VA" || $("#codigoPermiso").val()=="PE-RN" || $("#codigoPermiso").val()=="PE-PIV" || $("#codigoPermiso").val()=="PE-PIVF"){
		$("#adjuntos").hide();
	}else{
		$("#adjuntos").show();
	}
	//Comisiones Locales
	if($("#codigoPermiso").val()=="PE-CL"){
		$("#comisionLocal").show();
	}
	//Comisiones Provinciales
	if($("#codigoPermiso").val()=="PE-CP"){
		$("#comisionProvincial").show();
	}
 });
$('button.subirArchivo').click(function (event) {
    var boton = $(this);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");
       
    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
        subirArchivo(archivo, usuario, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)); 
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }        
});
function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
}
function chequearCampos(form){
	 $(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#estado_solicitud").val()==""){
			error = true;
			$("#estado_solicitud").addClass("alertaCombo");
		}
		//Aprobado
		if($("#estado_solicitud").val()=="Aprobado"){
			if($("#codigoPermiso").val() == 'PE-PIV' || $("#codigoPermiso").val() == 'VA-VA'){
				if(tiempoDisponible == false){
					error = true;
					$("#estado_solicitud").addClass("alertaCombo");
					alert('El funcionario no dispone de tiempo suficiente para este permiso, por favor rechace la solicitud pidiendo que realice una modificación en el tiempo.');
					$('#estado').html('El funcionario no dispone de tiempo suficiente para este permiso, por favor rechace la solicitud indicando al funcionario que realice una modificación en el tiempo requerido.').addClass('alerta');
				}
			}			
		}
		if($("#observaciones").val()==""){
			error = true;
			$("#observaciones").addClass("alertaCombo");
		}
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
 }
 $("#aprobarSolicitud").submit(function(event){
	event.preventDefault();
	chequearCampos(this);	 
	event.stopPropagation();
 });
</script>

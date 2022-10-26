<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cv = new ControladorVacaciones();
$cc = new ControladorCatalogos();

$identificador = $_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
}

$subtipos = $cv->obtenerSubTipoPermiso($conexion);

while ($fila = pg_fetch_assoc($subtipos)){
	$resTipos[] = array(id_subtipo_permiso=>$fila['id_subtipo_permiso'],nombre=>$fila['descripcion_subtipo'],
			minutos=>$fila['minutos_permitidos'],id_tipo_permiso=>$fila['id_tipo_permiso'],
			requiere_adjunto=>$fila['requiere_adjunto'],presentacion_reintegro=>$fila['presentacion_despues_reintegro'],
			detalle_permiso=>$fila['detalle_permiso'], codigo=>$fila['codigo']);
}

$id_subtipo=$_POST['id'];
$filaSolicitud = pg_fetch_assoc($cv->obtenerPermisoSolicitado($conexion,$id_subtipo));

?>
<header>
	<h1>Solicitud de permisos o vacaciones</h1>
</header>

<div id="resultadoIni">

	<fieldset>
		<legend>Tipo de solicitud</legend>		
		<div id="motivoRechazo" data-linea="99">
			<label><?php echo $filaSolicitud['estado'];?></label> 
				<?php echo ' -> '.$filaSolicitud['observacion'];?>
		</div>
		
		<div data-linea="1">
			<label>Tipo de solicitud</label> 
				<select name="tipoSolicitud" id="tipoSolicitud" disabled="disabled">
					<option value="">Seleccione....</option>
						<?php 	
							$tipoPermiso = $cv->obtenerTipoPermiso($conexion);
							while($fila = pg_fetch_assoc($tipoPermiso)){
								echo '<option value="' . $fila['id_permiso'] . '">' . $fila['descripcion_permiso'].' </option>';
							}
						?>
			</select>
		</div>
		<div data-linea="2">
			<label>Subtipo de solicitud</label> 
				<select name="subTipoSolicitud" id="subTipoSolicitud" disabled="disabled">
					<option value="">Seleccione un tipo permiso...</option>
					<?php 	
						$subTipoPermiso = $cv->obtenerSubTipoPermiso($conexion,null,$filaSolicitud['sub_tipo']);
							while($fila = pg_fetch_assoc($subTipoPermiso)){
								$opcionTipoPermiso=$fila['codigo'];
								echo '<option value="' . $fila['id_subtipo_permiso'] . '" data-minutos="'.$fila['minutos_permitidos'].'" data-detalle="'.$fila['detalle_permiso'].'" data-codigo="'.$fila['codigo'].'">' . $fila['descripcion_subtipo'].' </option>';
							}
					?>
			</select>
		</div>
		
		<div data-linea="3" id="comisionLocal">
			<label>Destino Comisión Local</label> 
				<input type="text" id="lugarComisionLocal" name="lugarComisionLocal" value="<?php echo $filaSolicitud['destino_comision'];?>" disabled="disabled"/>
		</div>
		<div data-linea="3" id="comisionExterior">
			<label>Destino Comisión al Exterior</label> 
				<input type="text" id="lugarComisionLocal" name="lugarComisionLocal" value="<?php echo $filaSolicitud['destino_comision'];?>" disabled="disabled"/>
		</div>
		
		<div data-linea="4" id="comisionProvincial">
			<label>Destino Comisión Provincial</label> 
				<select name="lugarComisionProvincial" id="lugarComisionProvincial" disabled="disabled">
					<option value="">Seleccione....</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							if($provincia['nombre'] == $filaSolicitud['destino_comision']){
								echo '<option value="' . $provincia['nombre'] . '" selected="selected">' . $provincia['nombre'] . '</option>';
							}else{
								echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
							}
						}
					?>
			</select>
		</div>		
		<div data-linea="5" id="descripcionSolicitud">
			<?php echo $filaSolicitud['detalle_permiso'];?>
		</div>
	</fieldset>

	<fieldset id="detalle">
		<legend>Fechas y Tiempo requerido</legend>
				<?php echo $opcionTipoPermiso; if($opcionTipoPermiso != 'PE-DA'){?>
		<div data-linea="4">
			<label id="etiquetaFechaSuceso">Fecha de suceso:</label>
				<input type="text" id="fechaSuceso" name="fechaSuceso" value="<?php echo date('Y-n-j',strtotime($filaSolicitud['fecha_suceso']));?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" disabled="disabled" />
		</div>
		<hr id="separador">
		<div data-linea="5">
			<label>Fecha de salida</label> 
				<input type="text" id="fechaSalida" name="fechaSalida" value="<?php echo date('Y-n-j',strtotime($filaSolicitud['fecha_inicio']));?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" disabled="disabled" />
		</div>

		<div data-linea="5">
			<label>Hora de salida:</label> 
				<input id="horaSalida" name="horaSalida" class="menores" value="<?php echo date('H:i',strtotime($filaSolicitud['fecha_inicio']));?>"type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" disabled="disabled"/>
		</div>		
		<hr>		
		<div data-linea="6">
			<label>Fecha de retorno</label> 
				<input type="text" id="fechaRetorno" name="fechaRetorno" value="<?php echo date('Y-n-j',strtotime($filaSolicitud['fecha_fin']));?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" disabled="disabled"/>
		</div>
		
		<div data-linea="6">
			<label>Hora de retorno:</label> 
				<input id="horaRetorno" name="horaRetorno" class="menores" value="<?php echo date('H:i',strtotime($filaSolicitud['fecha_fin']));?>" type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" disabled="disabled"/>
		</div>
				<?php }else { $tiempoDescontado=$cv->devolverFormatoDiasDisponibles($filaSolicitud['minutos_utilizados']);	?>
					<div data-linea="4">
			<label id="etiquetaFechaSuceso">Tiempo descontado:</label>
				<input type="text" value="<?php echo $tiempoDescontado;  ?>" disabled="disabled" />
		</div>
										
			<?php	}?>
	</fieldset>


<fieldset id="adjuntos">
		<legend>Documento para Justificación</legend>

			<div data-linea="1">
				<label>Certificado del justificativo:</label>
				<?php echo ($filaSolicitud['ruta_archivo']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$filaSolicitud['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Certificado cargado</a>')?>
			</div>
	</fieldset>
	
	<fieldset id="accionPersonal">
		<legend>Acción de Personal</legend>

			<div data-linea="1">
				<label>Acción de Personal:</label>
				<?php echo ($filaSolicitud['ruta_informe']==''? '<span class="alerta">No ha generado ningún archivo aún</span>':'<a href='.$filaSolicitud['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Acción de personal creada</a>')?>
			</div>
	</fieldset>
	</div>
<div id="resultadoFin"></div>
<script type="text/javascript">
var array_subTipos= <?php echo json_encode($resTipos); ?>;
var usuario = <?php echo json_encode($usuario); ?>;
var opcionTipoPermiso= <?php echo json_encode($filaSolicitud['piv_tipo']); ?>;
var opcionTipoPermisoBan=true;

$(document).ready(function(){
	distribuirLineas();
	construirValidador();

	$("#accionPersonal").hide();

	if(usuario == '0'){
		$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		$("#modificarPer").attr("disabled", "disabled");
	}

	var estado= <?php echo json_encode($filaSolicitud['estado']); ?>;
	var observacion= <?php echo json_encode($filaSolicitud['observacion']); ?>;
	var archivoCertificado= <?php echo json_encode($filaSolicitud['ruta_archivo']); ?>;

	cargarValorDefecto("tipoSolicitud","<?php echo $filaSolicitud['id_permiso']?>");
	cargarValorDefecto("subTipoSolicitud","<?php echo $filaSolicitud['sub_tipo']?>");

	$("#motivoRechazo").hide();
	
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-ER" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-AM" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF"){
		   $("#etiquetaFechaSuceso").hide();
	       $("#fechaSuceso").hide();
	       $("#separador").hide();
	}else{
		$("#etiquetaFechaSuceso").show();
	    $("#fechaSuceso").show();
	    $("#separador").show();
	}
	
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="VA-VA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-RN" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIV" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-PIVF" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-DA" || $("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL"){
		$("#adjuntos").hide();
	}else{
		$("#adjuntos").show();
	}

	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CL"){
		$("#comisionLocal").show();
	}else{
		$("#comisionLocal").hide();
	}
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CE"){
		$("#comisionExterior").show();
	}else{
		$("#comisionExterior").hide();
	}
	if($("#subTipoSolicitud option:selected").attr('data-codigo')=="PE-CP"){
		$("#comisionProvincial").show();
	}else{
		$("#comisionProvincial").hide();
	}

	if((estado == "Rechazado") && (observacion!='')) {
		$("#motivoRechazo").show();
	}else{
		$("#motivoRechazo").hide();
	}
	
	if((estado == "Aprobado") && (archivoCertificado=="0")) {
		$("#cargaArchivo").hide();
	}

	if(estado == "InformeGenerado") {
		$("#accionPersonal").show();
	}
 });

//-----------------------------------------------------------------------------

</script>
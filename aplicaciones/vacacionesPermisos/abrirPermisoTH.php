<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';

$conexion = new Conexion();
$cv = new ControladorVacaciones();

$subtipos = $cv->obtenerSubTipoPermiso($conexion,null,null);

while ($fila = pg_fetch_assoc($subtipos)){
	$resTipos[] = array(id_subtipo_permiso=>$fila['id_subtipo_permiso'],nombre=>$fila['descripcion_subtipo'],
			minutos=>$fila['minutos_permitidos'],id_tipo_permiso=>$fila['id_tipo_permiso'],
			requiere_adjunto=>$fila['requiere_adjunto'],presentacion_reintegro=>$fila['presentacion_despues_reintegro'],
			detalle_permiso=>$fila['detalle_permiso'], codigo=>$fila['codigo']);
}

$idPermiso=$_POST['id'];

$filaSolicitud = pg_fetch_assoc($cv->obtenerPermisoSolicitado($conexion,$idPermiso));

$saldoFuncionario = $cv->consultarSaldoFuncionario($conexion,$filaSolicitud['identificador']);

if(pg_num_rows($saldoFuncionario) > 0){
	$saldos = pg_fetch_result($saldoFuncionario, 0, 'minutos_disponibles');
}else{
	$saldos =0;
}



$rutaArchivo = $filaSolicitud['ruta_informe'];
?>
<header>
	<h1>Generar acción de personal</h1>
</header>

<form id="generarAccionPersonalForm" data-rutaAplicacion="vacacionesPermisos" data-opcion="generarAccionPersonal" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" value="Actualizar" />
	<input type="hidden" id="disponibilidad" name="disponibilidad" value="<?php echo $saldos; ?>"/>
     <input type="hidden" id="identificadorTH" name="identificadorTH" value="<?php echo $_SESSION['usuario']; ?>" />
     <input type="hidden" id="id_registro" name="id_registro" value="<?php echo $idPermiso; ?>" />
     <input type="hidden" id="identificadorFuncionario" name="identificadorFuncionario" value="<?php echo $filaSolicitud['identificador']; ?>" />
     <input type="hidden" id="codigoPermiso" name="codigoPermiso" value="<?php echo $filaSolicitud['codigo']; ?>" />
     

	<div id="estado"></div>
	
	
	<div id="reporte">
		<!-- insertar div del jasper -->
		<?php 
			if($filaSolicitud['estado'] == 'InformeGenerado'){
					echo '<embed id="visor" src='.$rutaArchivo.' width="540" height="490">';
			}
		?>
	</div>
		
	<div id="informacion">
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
			
			<hr id="separador">
			
			<div data-linea="5">
				<label id="etiquetaFechaSuceso">Fecha de suceso: </label>
					<?php echo date('j/n/Y',strtotime($filaSolicitud['fecha_suceso']));?>
			</div>
			<hr id="separador">
			<div data-linea="6">
				<label>Fecha de salida</label> 
					<?php echo date('j/n/Y',strtotime($filaSolicitud['fecha_inicio']));?>
			</div>
	
			<div data-linea="6">
				<label>Hora de salida:</label> 
					<?php echo date('H:i',strtotime($filaSolicitud['fecha_inicio']));?>
			</div>
			
			
			<hr>
			
			<div data-linea="7">
				<label>Fecha de retorno</label> 
					<?php echo date('j/n/Y',strtotime($filaSolicitud['fecha_fin']));?>
			</div>
			
			<div data-linea="7">
				<label>Hora de retorno:</label> 
					<?php echo date('H:i',strtotime($filaSolicitud['fecha_fin']));?>
			</div>
			
			<hr id="separador">
		
			<div data-linea="8">
				<?php echo ($filaSolicitud['ruta_archivo']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$filaSolicitud['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
			</div>
	
		</fieldset>
		
		
			<?php 
			$bandera=1;
			if (empty($filaSolicitud['ruta_informe'])){
				$bandera=0;
				
			}
			
			?>
		<button id="btnGenerarAccion" type="submit" class="guardar" <?php echo ($bandera!=0? ' disabled=disabled':'') ?> >Generar Acción de Personal</button>
		
	</div>
	
</form>

<script type="text/javascript">
var array_subTipos= <?php echo json_encode($resTipos); ?>;
var estadoSolicitud= <?php echo json_encode($filaSolicitud['estado']); ?>;

$(document).ready(function(){
	distribuirLineas();
	construirValidador();

	$("#comisionLocal").hide();
	$("#comisionProvincial").hide();
	
	cargarValorDefecto("tipoSolicitud","<?php echo $filaSolicitud['id_permiso']?>");
	cargarValorDefecto("subTipoSolicitud","<?php echo $filaSolicitud['sub_tipo']?>");
	$("#descripcionSolicitud").html(($("#subTipoSolicitud option:selected").attr("data-detalle")));

	if(estadoSolicitud == 'InformeGenerado'){
		$("#reporte").show();
		$("#informacion").hide();
	}else{
		$("#reporte").hide();
		$("#informacion").show();
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

/* $("#generarAccionPersonalForm").submit(function(event){
	 event.preventDefault();
	 ejecutarJson($(this));

		$('select.desabilitado').removeAttr('disabled');
 	$('input.desabilitado').removeAttr('disabled');
		$('textarea.desabilitado').removeAttr('disabled');
		$('.desabilitado').removeAttr('disabled');
		$('.desabilitado').attr('disabled',false);
	 event.preventDefault();
	 $('generarAccionPersonalForm.desabilitado').prop('disabled', false);
	// chequearCampos(this);	 	
	
});*/

 $("#btnGenerarAccion").click(function (event) {
	   event.preventDefault();
	   ejecutarJson($("#generarAccionPersonalForm"));

	   var resultado = $("#estado").html();

	   if(resultado == 'Los datos han sido actualizados satisfactoriamente.'){
			 $('#generarAccionPersonalForm').attr('data-opcion','mostrarDocumentoPDF');
			 $('#generarAccionPersonalForm').attr('data-destino','detalleItem');

			 abrir($("#generarAccionPersonalForm"),event,false);
	   }
});
 
</script>
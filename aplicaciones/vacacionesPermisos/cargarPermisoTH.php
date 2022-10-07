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
$saldoFuncionarioNuevo = $cv->consultarSaldoFuncionarioNuevo($conexion,$filaSolicitud['identificador']);

if(pg_num_rows($saldoFuncionario) > 0){
	$saldos = pg_fetch_result($saldoFuncionario, 0, 'minutos_disponibles');
}else{
	$saldos =0;
}

if(pg_num_rows($saldoFuncionarioNuevo) > 0){
	$saldos = $saldos + pg_fetch_result($saldoFuncionarioNuevo, 0, 'minutos_disponibles');
}



$rutaArchivo = $filaSolicitud['ruta_informe'];
?>
<header>
	<h1>Generar acción de personal</h1>
</header>

<form id="guardarAccionManualForm" data-rutaAplicacion="vacacionesPermisos" data-opcion="actualizarEstadoAccionManual" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
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
		if(!$filaSolicitud['firma_manual']){
			if($filaSolicitud['estado'] == 'InformeGenerado'){
					echo '<embed id="visor" src='.$rutaArchivo.' width="540" height="490">';
			}
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
		
			<fieldset id="adjuntos">
		<legend>Acción de personal</legend>

			<div data-linea="1">
				<label>Acción de personal manual:</label>
				<?php echo ($rutaArchivo=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaArchivo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Certificado cargado</a>')?>
			</div>
			<div data-linea="8">
			<input type="file" class="archivo" name="informe"
				accept="application/pdf" /> 
				<input type="hidden" class="rutaArchivo" name="archivo" value="0" />
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo;
				<?php echo ini_get('upload_max_filesize');?>
				B)
			</div>
			<button type="button" name="boton"  class="adjunto"
				data-rutaCarga="aplicaciones/vacacionesPermisos/accionPersonal">Subir
				archivo</button>
		</div>
		
	</fieldset>
	
		<button id="btnGuardarAccion" type="submit" class="guardar" disabled=disabled>Guardar Acción Manual</button>
		
	</div>
	
</form>

<script type="text/javascript">
var nombreArchivo= <?php echo json_encode($rutaArchivo); ?>;

$(document).ready(function(){
	distribuirLineas();
	construirValidador();

	$("#comisionLocal").hide();
	$("#comisionProvincial").hide();
	
	//Comisiones Locales
	if($("#codigoPermiso").val()=="PE-CL"){
		$("#comisionLocal").show();
	}

	//Comisiones Provinciales
	if($("#codigoPermiso").val()=="PE-CP"){
		$("#comisionProvincial").show();
	}
 });

$('button.adjunto').click(function (event) {
	var nombre = nombreArchivo.split('/'); 
	nombre = nombre[3].split('.');
    var boton = $(this);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");
    numero = Math.floor(Math.random()*100000000);
    
    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
    	$(this).attr('disabled','disabled');
    	$("#btnGuardarAccion").removeAttr('disabled','disabled');
        subirArchivo(archivo, nombre[0], boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)); 
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("0");
    }        
});

 $("#btnGuardarAccion").click(function (event) {
	   event.preventDefault();
	   ejecutarJson($("#guardarAccionManualForm"));

	   var resultado = $("#estado").html();

	   if(resultado == 'Los datos han sido actualizados satisfactoriamente.'){
			 $('#guardarAccionManualForm').attr('data-opcion','mostrarDocumentoPDF');
			 $('#guardarAccionManualForm').attr('data-destino','detalleItem');

			 abrir($("#guardarAccionManualForm"),event,false);
	   }
});
 
</script>
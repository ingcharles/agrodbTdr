<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorAplicacionesPerfiles.php';

	$conexion = new Conexion();;
	$cc = new ControladorCatalogos();
	$cap= new ControladorAplicacionesPerfiles();
	$idAplicacion=$_POST['elementos'];

	if($idAplicacion!=''){
	$res=$cap->obtenerDatosAplicacion($conexion, $idAplicacion);
	$datos = pg_fetch_assoc($res);
	$qOpciones=$cap->buscarOpcionesAplicacion($conexion, $idAplicacion);
	$uso=pg_num_rows($qOpciones);
	}
?>
<header>
	<h1>Eliminar Aplicación</h1>
</header>
<form id='notificarAplicacion' data-rutaAplicacion='asignarAplicacionPerfil' data-opcion='eliminarAplicacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	<input type="hidden" name="idAplicacion" value="<?php echo $idAplicacion;?>" />	

	<div id="eliminar">
		<p style="text-align: center">
			<button type="submit" id="eliminarAplicacion" name="eliminarAplicacion" >Eliminar Aplicación</button>
		</p>
	</div>

	<fieldset>
		<legend>Información Aplicación</legend>
		<div data-linea="1">
			<label>Nombre Aplicación:</label>
				<?php echo $datos['nombre'];?>
		</div>
		<div data-linea="2" id="notificar">
			<label ><b>Notificacion:</b> </label><span class='alerta' >La aplicación no puede ser eliminada porque esta en uso.</span>
		</div>
	</fieldset>
</form>		
	
<script type="text/javascript">
								
	$(document).ready(function(){
		distribuirLineas();

		if(<?php echo json_encode($idAplicacion); ?> == '')
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		
		var vista=<?php echo json_encode($uso); ?>;
		if( vista== 0){
			$("#notificar").hide();
		}else{
			$("#notificar").show();
			$("#eliminar").hide();
		}
	});
	
	$("#notificarAplicacion").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));	
		if($("#estado").html("El registro a sido eliminado satisfactoriamente."))
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
	});
</script>
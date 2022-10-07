<?php
//session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorSolicitudes.php';
require_once '../../clases/ControladorDocumentos.php';

$conexion = new Conexion();
$cs = new ControladorSolicitudes();
$cd = new ControladorDocumentos();

$conexion->verificarSesion();

$res = $cs->abrirSolicitud($conexion, $_POST['id'], $_SESSION['usuario']); //filtrado por cedula

$cs->actualizarNotificacion($conexion, $_POST['id'], $_SESSION['usuario']);

$qCreadorDocumento = $cd->obtenerCreadorDocumento($conexion, $_POST['id']);
$creadorDocumento = pg_fetch_assoc($qCreadorDocumento);

$fila = pg_fetch_assoc($res);
$rutaAplicacion = '';
switch ($fila['tipo']){
	case 'documento': $rutaAplicacion='documentos';
}
/*$res2 = $ca-> listarRevisores($conexion, $documento['id_documento']);*/
$res2 = $cs-> listarRevisores($conexion, $_POST['id']);
$lista = '';
while ($revisores = pg_fetch_assoc($res2)){ //res2
	if($revisores['identificador_delegador'] ==''){
		$lista.="'".$revisores['identificador']."',";
	}
}
//$lista=substr($lista, 0,count($lista)-2);
$lista.="'".$_SESSION['usuario']."'";
$lista.=",'".$creadorDocumento['identificador']."'";

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Solictud seleccionada</h1>
	</header>
	<div id="detalleSolicitud"></div>
	<form id="revisores" data-rutaAplicacion="solicitudes" data-opcion="estadoRevisores" data-accionEnExito="ACTUALIZAR">
	
	<input id="usuarioIdentificador" name="usuarioIdentificador" type="hidden" >
	
	<fieldset id="solicitud">
		<legend>Estado de revisión</legend>
		
		<div data-linea="1">
			<label>Estado actual: </label>
			<?php echo $fila['estado'] . '<input type="hidden" id="'.$fila['id_solicitud'].'" data-rutaAplicacion="'.$rutaAplicacion.'" data-opcion="mostrarDetalleSolicitud" data-destino="detalleSolicitud"/>';?>
		</div>
		
		<div data-linea="2">
			<label>Nuevo estado: </label>
			<select id="nuevoEstado" name="nuevoEstado">
				<option value="Aprobado">Aprobar</option>
				<option value="Rechazado">Rechazar</option>
				<option value="Delegado">Solicitar revisión a</option>
			</select>
			<input name="id_solicitud" value="<?php echo $_POST['id']?>" type="hidden">
		</div>
		
		<div id="reasignarA" data-linea="2">
		
				<select id="revisor" name="revisor">
					<?php 
						$cu = new ControladorUsuarios();
						$res = $cu->obtenerUsuariosActivos($conexion,$lista);
						echo '<option value="">Seleccione....</option>';
						while($fila = pg_fetch_assoc($res)){
							echo '<option  
										value="' . $fila['identificador'] . '">' . $fila['apellido'] . ", " . $fila['nombre'] . '</option>';
						}
					?>
				</select>
		</div>
		
		<div id="nombreUsuario"></div>
		
		<div data-linea="3">	
			<label>Comentario: </label>
		</div>
		
		<div data-linea="4">
			<textarea name="comentario"></textarea>
		</div>
		<div>
			<button id="guardar" type="submit" class="guardar">Guardar</button>
		</div>
		<div id="estado"></div>
	</fieldset>
	</form>
	
	

</body>
<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
		$("#reasignarA").hide();
		//alert($("#ultimoAcceso").val());
		abrir($("#solicitud input:hidden"),null,false);
	});

	$("#nuevoEstado").change(function(){
		if ($("#nuevoEstado").val()=="Delegado")
			$("#reasignarA").show();
		else
			$("#reasignarA").hide();
	});


	$("#revisores").submit(function(event){
		$("#nombreUsuario").append("<input name='iNombreUsuario' value='"+$("#revisor  option:selected").text()+"' type='hidden'>");
	    event.preventDefault();

	    $(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#nuevoEstado option:selected").val() == 'Delegado'){
			if($("#revisor").val()==""){
				error = true;
				$("#revisor").addClass("alertaCombo");
			}
		}

		if (!error){
			ejecutarJson($(this));	
		}else{
			$("#estado").html('Por favor debe agregar un revisor').addClass('alerta');		
		}
	    
	});
	
</script>
</html>

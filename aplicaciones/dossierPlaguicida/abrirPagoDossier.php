<?php
session_start();

	require_once '../../clases/Conexion.php';
	
	require_once '../../clases/ControladorDossierPecuario.php';

	$idUsuario= $_SESSION['usuario'];
	$id_solicitud = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$id_fase = $_POST['opcion'];
	$id_tramite = $_POST['nombreOpcion'];
	$identificador=$idUsuario;

	$conexion = new Conexion();
	$cp=new ControladorDossierPecuario();
	
	$datosGenerales=array();
	$listaPlagas=array();


	//Busca el protocolo
	if($id_solicitud!=null){
		$datosGenerales=$cp->obtenerSolicitud($conexion, $id_solicitud);
		$identificador=$datosGenerales['identificador'];
	}




?>

<header>
	<h1>TEMPORAL: Registro de pago</h1>
</header>

<div id="estado"></div>


<div class="pestania" id="P1" style="display: block;">
	<form id="frmFinalizarProtocolo" data-rutaaplicacion="dossierPlaguicida" data-opcion="guardarPagoDossier" data-accionenexito='ACTUALIZAR'>
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
		<input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
		<input type="hidden" id="id_fase" name="id_fase" value="<?php echo $id_fase;?>" />
		<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $id_tramite;?>" />


		<fieldset>
			<legend>Esto es solo de prueba: lo hace el sistema GUIA</legend>


		</fieldset>
		<button type="submit" class="guardar">Registrar Pago</button>

	</form>
</div>

<script type="text/javascript">

	$("document").ready(function(){

		construirAnimacion(".pestania");
		
		distribuirLineas();

	});

	

	
	$("#frmFinalizarProtocolo").submit(function(event){

		event.preventDefault();

		
		var error = false;
		

		if (!error){
			ejecutarJson($(this), new exitoAsignacion());
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
		
		
	});

	function exitoAsignacion() {
		this.ejecutar = function () {
			mostrarMensaje("Tramite ha sido asignado", "EXITO");
			$('#asignar').html('Tramite ha sido asignado');
		}
	}

</script>

<style type="text/css">
	
	.detalleFloatContenido {
	float: left;
	width: 99%;
	}
	.detalleFloatContenido .detalleIzquierdo {
	float: left;
	width: 49%;
	margin-left: 2px;
	}
	.detalleFloatContenido .detalleDerecho {
	float: left;
	width: 49%;
	margin-left: 2px;
	}

	ul {
     list-style-type:square;
	 } 

</style>

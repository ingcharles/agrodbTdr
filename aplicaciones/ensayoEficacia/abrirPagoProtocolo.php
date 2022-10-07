<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';


	
	$idUsuario= $_SESSION['usuario'];
	$idProtocolo = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$id_fase = $_POST['opcion'];
	$id_tramite = $_POST['nombreOpcion'];
	$identificador=$idUsuario;

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	

	$datosGenerales=array();
	$listaPlagas=array();

	
	//Busca el protocolo
	if($idProtocolo!=null){
		$datosGenerales=$ce->obtenerProtocolo($conexion, $idProtocolo);
		$identificador=$datosGenerales['identificador'];
	}




?>

<header>
	<h1>TEMPORAL: Registro de pago</h1>
</header>

<div id="estado"></div>


<div class="pestania" id="P1" style="display: block;">
	<form id="frmFinalizarProtocolo" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarPagoProtocolo" data-accionEnExito = 'ACTUALIZAR'>
		<input type="hidden"  id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>"/>
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
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
		
		
	});

	

</script>

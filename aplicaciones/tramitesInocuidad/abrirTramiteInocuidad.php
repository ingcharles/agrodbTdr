<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorTramitesInocuidad.php';

$conexion = new Conexion();

$cc = new ControladorCatalogos();
$cti = new ControladorTramitesInocuidad();
$cr = new ControladorRegistroOperador();

$idSolicitud = $_POST['id'];
$identificadorUsuario = $_SESSION['usuario'];

$tramite = pg_fetch_assoc($cti->obtenerTramiteInocuidad($conexion, $idSolicitud));
$operador = pg_fetch_assoc($cr->buscarOperador($conexion, $tramite['identificador_operador']));
$producto = pg_fetch_assoc($cc ->obtenerTipoSubtipoXProductos($conexion, $tramite['id_producto']));

?>

<header>
	<h1>Tramite</h1>
</header>

	<div id="estado"></div>
	
	

	
	<fieldset>
			<legend>Datos del operador</legend>
			
			<div data-linea="1">
				<label>Identificación: </label> <?php echo $operador['identificador']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo ($operador['razon_social']==''?$operador['apellido_representante'].' '.$operador['nombre_representante']:$operador['razon_social']); ?> 
			</div>
			
	</fieldset>
	
	<fieldset>
			<legend>Datos del producto</legend>
			
			<div data-linea="1">
				<label>Tipo producto: </label> <?php echo $producto['nombre_tipo']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Subtipo producto: </label> <?php echo $producto['nombre_subtipo']; ?> 
			</div>
			
			<div data-linea="3">
				<label>Producto: </label> <?php echo $tramite['nombre_producto']; ?> 
			</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Datos generales</legend>
			<div data-linea="1">
				<label>Tipo tramite: </label> <?php echo $tramite['nombre_tipo_tramite'];?> 
			</div>
			
			<div data-linea="2">
				<label>Observación: </label> <?php echo ($tramite['observacion']==''?'Sin observación':$tramite['observacion']); ?> 
			</div>
			
	</fieldset>

	
	
	<form id="evaluarTramiteResponsble" data-rutaAplicacion="tramitesInocuidad" data-opcion="evaluarTramiteResponsable" data-accionEnExito="ACTUALIZAR">
	
		<input type="hidden" name="inspector" value="<?php echo $identificadorUsuario;?>"/> 
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="estado" value="finalizado"/>
	
		<button type="submit" class="guardar">Finalizar tramite</button>	
				
	</form>
	  

<script type="text/javascript">

var estado= <?php echo json_encode($tramite['estado']); ?>;

	$(document).ready(function(){
		distribuirLineas();

		$("#evaluarTramiteResponsble").hide();

		if(estado == "porEntregar"){
			$("#evaluarTramiteResponsble").show();
		}
		
	});

	$("#evaluarTramiteResponsble").submit(function(event){
		event.preventDefault();
		ejecutarJson(this);
	});

</script>

<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();

if($_POST['id'] == '_nuevo'){
	$identificador = $_SESSION['usuario'];
}else{
	$identificador = $_POST['id'];
}

$sitios = $cr->listarSitios($conexion, $identificador);

?>

<header>
	<h1>Nueva operación</h1>
</header>

<form id='nuevaSolicitud' data-rutaAplicacion='registroOperador' data-opcion='guardarNuevaOperacion' data-destino="detalleItem">
	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador;?>" />
	<input type="hidden" id="opcion" name="opcion" />
	<!-- input type="hidden" id="areaProducto" name="areaProducto" /-->
	<input type="hidden" id="idFlujo" name="idFlujo" />
	
	<div id="estado"></div>
	
	<fieldset>
		<legend>Registro de Operador</legend>
		
			<div data-linea="1">			
				<label>Área temática</label> 
				<select id="areaProducto" name="areaProducto">
					<option value="">Seleccione....</option>
					<option value="SV">Sanidad vegetal</option>
					<option value="SA">Sanidad animal</option>
					<option value="CGRIA">Registros de insumos agropecuarios</option>
					<option value="IAP">Registros de insumos agrícolas</option>
					<option value="IAV">Registros de insumos pecuarios</option>
					<option value="IAF">Registros de insumos fertilizantes</option>
					<!-- option value="IAPA">Registro de insumos para plantas de autoconsumo</option-->
					<option value="AI">Inocuidad de los alimentos</option>
					<option value="LT">Laboratorios</option>
				</select>				
			</div>
			
			<div data-linea="2">
				<label>Sitio</label>
				<select id="sitio" name="sitio" required>
					<option value="">Seleccione....</option>
					<?php 
						while ($fila = pg_fetch_assoc($sitios)){
							echo '<option value="'.$fila['id_sitio'].'">'.$fila['nombre_lugar'].'</option>';
						 }
					?>
				</select>
			
			</div>
					
			<div data-linea="3">
				<div id="dOperaciones"></div>
			</div>
			
			<div id="area" data-linea="4"></div>
			
			<button type="submit" class="mas">Agregar operación</button>
			
	</fieldset>
	
	<p class="nota">Por favor revise que la información ingresada sea correcta. Una vez enviada no podrá ser modificada.</p>
 	
</form> 

	<fieldset>
		<legend>Operaciones agregadas</legend>
				 <div>
					<table id="operaciones">
					</table>
				</div>
	</fieldset>
	
	<button id="enviarSolicitud" type="button" class="guardar">Enviar solicitud</button>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
		acciones("#nuevaSolicitud","#operaciones");
	});

	$("#areaProducto").change(function(event){
		$("#sitio").val("");

		if($("#tipoOperacion").length != 0){
			$("#dOperaciones").html('');
			$("#area").html('');
			$("#dTipoProducto").html('');
			$("#dSubTipoProducto").html('');
			$("#dProducto").html('');
	 	 }
	});

	$("#sitio").change(function(event){
		event.preventDefault();

		if($("#tipoOperacion").length != 0){
			$("#area").html('');
			$("#dTipoProducto").html('');
			$("#dSubTipoProducto").html('');
			$("#dProducto").html('');
	 	 }

		$("#estado").html("").removeClass("alerta");
		$(".alertaCombo").removeClass("alertaCombo");
		
 		$("#nuevaSolicitud").attr('data-destino','dOperaciones');
 		$("#nuevaSolicitud").attr('data-opcion', 'combosOperador');
 		$("#opcion").val('operaciones');

 		
 		if($("#areaProducto").val() == ''){
 			$("#areaProducto").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione un área temática.").addClass("alerta");
		}else{
			event.stopImmediatePropagation();
 	 		abrir($("#nuevaSolicitud"),event,false);
		}
	 });

	$("#enviarSolicitud").click(function (event) {
		
		if($("#operaciones >tbody >tr").length != 0){
			$("#_actualizar").click();
		}else{
			$("#estado").html("Por favor ingrese por lo menos una operación").addClass("alerta");
		}
			
	});	
</script>
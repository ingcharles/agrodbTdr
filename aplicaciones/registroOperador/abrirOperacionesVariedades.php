<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$idOperacion =  htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
$nombreTipoOperacion=$_POST['opcion'];

//list($idOperacion, $nombreTipoOperacion) = explode("-", $data);

$qrep=$cro->buscarListaOperacionesEstadoCargarIA($conexion, $idOperacion);
$datos = pg_fetch_assoc($qrep);

$variedad= $cc->listarVariedadesxProducto($conexion, $idOperacion);

$variedadxOperacion= $cro->listarVariedadesOperaciones($conexion, $idOperacion);

?>
<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1 style="font-size: 24px;">Seleccionar variedad de Productos</h1>
</header>
<div id="estado"></div>

<form id='abrirListaOperacionesVariedades' data-rutaAplicacion='registroOperador' data-opcion='abrirListaOperacionesVariedades'	data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	
	<fieldset>
		<legend>Información de la Operación y Producto</legend>
			<input type="hidden" name="idRaza" value="" /> 
			<input type="hidden" id="estados" value="" disabled="disabled" />
			<div data-linea="1">
				<label>Tipo de producto: </label>
				<?php echo $datos['nombretipoproducto'];?>
				<br />
			</div>
			<div data-linea="1">
				<label>Subtipo de producto: </label>
				<?php echo $datos['nombresubtipoproducto'];?>
				<br />
			</div>
			<div data-linea="2">
				<label>Producto: </label>
				<?php echo $datos['nombre_comun'];?>
				<br />
			</div>
			<div data-linea="2">
				<label>Operación: </label>
				<?php echo $datos['nombretipooperacion'];?>
				<br />
			</div>
	</fieldset>
</form>

<form id="nuevoOperacionVariedad" data-rutaAplicacion="registroOperador" data-opcion="guardarOperacionesVariedades">
	
	<input type="hidden" id="idOperacion" name="idOperacion" value="<?php echo $datos['id_operacion'];?>"/> 
	<input type="hidden" id="idVariedad" name="idVariedad"/> 
	<input type="hidden" id="nombreVariedad" name="nombreVariedad"/> 
	<input type="hidden" id="nombreTipoOperacion" name="nombreTipoOperacion" value="<?php echo $nombreTipoOperacion;?>"/> 

	<fieldset id="variedades">
		<legend>Variedad de Productos</legend>
			<div data-linea="1">
				<label>Variedad</label> 
				<select id="variedadProducto" name="variedadProducto">
					<option value="0">Seleccione...</option>
					<?php 
						while ($fila = pg_fetch_assoc($variedad)){
				    		echo '<option value="'.$fila['id_variedad'].'">'.$fila['nombre'].'</option>';
				   		}
			    	?>
				</select>
			<button type="submit" class="mas">Añadir variedad</button>
		</div>
	</fieldset>
</form>


<fieldset>
	<legend>Añadir Variedades</legend>
	<table id="codigoVO">
		<?php 
			while ($fila = pg_fetch_assoc($variedadxOperacion)){
					echo $cro->imprimirOperacionVariedad($idOperacion, $fila['id_variedad'], $fila['nombretipooperacion'], $fila['nombrevariedad']);
			}
		?>
	</table>
</fieldset>

<form id="nuevoEstadoCargarIA" data-rutaAplicacion="registroOperador" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="idOperacionIA" value="<?php echo $datos['id_operacion'];?>"/>
	<input type="hidden" name="idTipoOperacionIA" value="<?php echo $datos['id_tipo_operacion'];?>"/>
	<input type="hidden" name="idFlujo" value="<?php echo $datos['id_flujo_operacion'];?>"/>
	<input type="hidden" name="areaProducto" value="<?php echo $datos['id_area'];?>"/>
		
	<button id="enviarSolicitud" type="submit" class="guardar">Enviar solicitud</button> 
</form>

<script type="text/javascript"> 

$(document).ready(function(){
	distribuirLineas();
	acciones("#nuevoOperacionVariedad","#codigoVO");

});
			
$("#variedadProducto").change(function(){	
	$("#idVariedad").val($("#variedadProducto option:selected").val());
	$("#nombreVariedad").val($("#variedadProducto option:selected").text());
});


$("#nuevoOperacionVariedad").submit(function(event){
    event.preventDefault();
    $(".alertaCombo").removeClass("alertaCombo");
  	var error = false;

		if($("#variedadProducto").val()=="0"){	
			error = true;		
			$("#variedadProducto").addClass("alertaCombo");
			
		}

		if (error){
			$("#estado").html("Por favor seleccione una variedad.").addClass('alerta');
		}
});

$("#nuevoEstadoCargarIA").submit(function(event){
	var error = false;
	   	 if($("#codigoVO >tbody >tr").length != 0){
	   		event.preventDefault();
	   		$('#nuevoEstadoCargarIA').attr('data-opcion','guardarNuevoEstadoCargarIA');
	   	   	ejecutarJson($(this));  
	   	   		
	   	}else{

	   		if($("#variedadProducto").val()=="0"){	
				error = true;		
				$("#variedadProducto").addClass("alertaCombo");
			}
	   		if (error){
	  		event.preventDefault();
	   		$("#estado").html("Por favor ingrese por lo menos una variedad").addClass("alerta");
	   		}

	  	}
	});



</script>

















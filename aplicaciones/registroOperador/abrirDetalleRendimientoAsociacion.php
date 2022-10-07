<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$usuario = $_SESSION['usuario'];


$identificadorMiembroAsociacion = $_POST['identificadorMiembroAsociacion'];
$idMiembroAsociacion = $_POST['idMiembroAsociacion'];
$idDetalleMiembro = $_POST['idDetalleMiembro'];

$idTipoOperacion = $_POST['idTipoOperacion'];
$nombreTipoOperacion = $_POST['nombreTipoOperacion'];

$idProducto = $_POST['idProducto'];
$nombreProducto = $_POST['nombreProducto'];


$idSitio = $_POST['idSitio'];
$nombreSitio = $_POST['nombreSitio'];

$idArea = $_POST['idArea'];
$nombreArea = $_POST['nombreArea'];

$rendimiento = $_POST['rendimiento'];

$operaciones = $cro->obtenerOperacionesXOPerador($conexion, $usuario);

?>

<header>
<h1>Detalle miembros de asociacion</h1>
</header>

<div id="estado"></div>

<form id="regresar" data-rutaAplicacion="registroOperador" data-opcion="abrirRendimientoAsociacion" data-destino="detalleItem" >
	<div id="datosRegresar">
		<input type="hidden" name="id" id="id" value="<?php echo $idMiembroAsociacion.'@'.$idSitio;?>" />
	</div>
	<button class="regresar">Regresar a Miembro Asociación</button>
</form>

<form id="detalleRendimientoAsociacion" data-rutaAplicacion="registroOperador" data-opcion="actualizarDetalleRendimientoAsociacion"  data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" value="operaciones">
	<input type="hidden" id="idArea" name="idArea" value="<?php echo $idArea;?>"/>
	<input type="hidden" id="identificadorMiembroAsociacion" name="identificadorMiembroAsociacion" value="<?php echo $identificadorMiembroAsociacion;?>" />
	<input type="hidden" name="idMiembroAsociacion" value="<?php echo $idMiembroAsociacion;?>"/>
	<input type="hidden" name="idDetalleMiembro" value="<?php echo $idDetalleMiembro;?>"/>
	<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $idProducto?>"/>
	<input type="hidden" id="nombreSitioArea" name="nombreSitioArea"/>
	<input type="hidden" id="nombreOperacionProducto" name="nombreOperacionProducto"/>
	<input type="hidden" id="datosSAOPAterior" name="datosSAOPAterior" value="<?php echo $nombreSitio.'@'.$nombreArea.'@'.$nombreTipoOperacion.'@'.$nombreProducto;?>"/>
	<div id="resultadoDetalle">
		<input type="hidden" id="idSitioAterior" name="idSitioAterior" value="<?php echo $idSitio;?>"/>
		<input type="hidden" id="idProductoAnterior" name="idProductoAnterior" value="<?php echo $idProducto;?>"/>
		<input type="hidden" id="rendimientoAnterior" name="rendimientoAnterior" value="<?php echo $rendimiento;?>"/>
	</div>
	
	<fieldset>
		<legend>Detalle</legend>
		
		
		<div data-linea="1">
			<label for="operacion">Operación y producto:</label>
			<select id="operacion" name="operacion" disabled="disabled">
				<option value="<?php echo $idTipoOperacion ?>" data-producto="<?php echo $idProducto ?>" selected="selected"><?php echo $nombreTipoOperacion . ' - ' . $nombreProducto?></option>
					<?php 
						while ($fila = pg_fetch_assoc($operaciones)){
							echo '<option value="'.$fila['id_tipo_operacion'].'" data-producto="'.$fila['id_producto'].'">'.$fila['nombre'].' - '.$fila['nombre_comun'].'</option>';
						 }
					?>
			</select>
		</div>
		
		<div id="resultadoOperacion" data-linea="5">
			<label for="sitio">Sitio y Área:</label>
			<select id="sitio" name="sitio" disabled="disabled">
				<option value="<?php echo $idSitio ?>" data-idArea="<?php echo $idArea ?>" ><?php echo $nombreSitio." - ". $nombreArea?></option>
				</select>
		</div>
			
		<div data-linea="3" id='Irendimiento'>
			<label>Rendimiento:</label>
			<input type="text" id=rendimiento name="rendimiento" value="<?php echo $rendimiento?>" maxlength="13" disabled="disabled"/>
		</div>				
		
		<div data-linea="4">
			<button id="modificar" type="button" class="editar">Editar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</div>
	</fieldset>
	
</form>
<script>
						
	$('document').ready(function(){

		construirValidador();
		distribuirLineas();
	
	});	

	var tuma="";	
	
	$("#operacion").change(function(event){
		$('#detalleRendimientoAsociacion').attr('data-opcion','accionesRendimientoAsociacion');
		$('#detalleRendimientoAsociacion').attr('data-destino','resultadoOperacion');
		$('#idProducto').val($("#operacion option:selected").attr('data-producto'));
		$('#opcion').val('operaciones');
		abrir($("#detalleRendimientoAsociacion"),event,false);	
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#detalleRendimientoAsociacion").submit(function(event){

		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#rendimiento").val()==""){
			error = true;
			$("#rendimiento").addClass("alertaCombo");
		}
	
		if (!error){

			var datosDetalle = $("#sitio option:selected").val()+'-'+$("#identificadorMiembroAsociacion").val();
			
			var data ="opcion="+'cargarDetalle'+'&datosDetalle='+datosDetalle;
		    $.ajax({        
		        type: "POST",
		        data: data,        
		        url: "aplicaciones/registroOperador/cargarDatosAnteriores.php",
		        success: function(data) {   
				tuma=data;
						if(data=="true"){		
				        	
				        	$('#detalleRendimientoAsociacion').attr('data-opcion','actualizarDetalleRendimientoAsociacion');
							ejecutarJson('#detalleRendimientoAsociacion');

							$("#resultadoDetalle").html('<input type="hidden" id="idSitioAterior" name="idSitioAterior" value="'+$("#sitio option:selected").val()+'" />'+ 
								     '<input type="hidden" id="idProductoAnterior" name="idProductoAnterior" value="'+$("#operacion option:selected").attr('data-producto')+'" />'+
									 '<input type="hidden" id="rendimientoAnterior" name="rendimientoAnterior" value="'+$("#rendimiento").val()+'" />');
						
			        	}else{       	
							$("#estado").html("El sitio, área, producto y operación ya han sido asignados a un miembro de la asociación.").addClass("alerta");								
						}
        		}
		    });
		
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}

	});

	$("#regresar").submit(function(event){
		event.preventDefault();		
		$('#regresar').attr('data-opcion','abrirRendimientoAsociacion');
		$('#regresar').attr('data-destino','detalleItem');

		var miembro = <?php echo json_encode($idMiembroAsociacion); ?>;
		if(tuma=="true")
		$('#id').val(miembro+'@'+$("#sitio option:selected").val());
						
		abrir($("#regresar"),event,false);
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

</script>
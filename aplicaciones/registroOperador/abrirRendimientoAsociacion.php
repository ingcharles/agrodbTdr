<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$usuario = $_SESSION['usuario'];

$data =  htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
list($idMiembroAsociacion, $idSitio, $nombreSitio) = explode("@", $data);

$qMiembroAsociacion = $cro->obtenerDatosMiembroAsociacionXIdMiembro($conexion, $idMiembroAsociacion);
$miembroAsociacion = pg_fetch_assoc($qMiembroAsociacion);

$qDetalle = $cro->obtenerDetalleMiembroXIdentificadorXSitio($conexion, $idMiembroAsociacion, $idSitio);

?>

<header>
<h1>Miembros de asociación</h1>
</header>

<div id="estado"></div>

<form id="actualizarMiembroAsociacion" data-rutaAplicacion="registroOperador" data-opcion="actualizarRendimientoAsociacion" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" value="<?php echo $idMiembroAsociacion;?>" id="identificador" name="identificador" />	
		<div id="datosMiembro">
			<input type="hidden" value="<?php echo $miembroAsociacion['identificador_miembro_asociacion'];?>" id="identificadorMiembroAnterior" name="identificadorMiembroAnterior" />
			<input type="hidden" value="<?php echo $miembroAsociacion['nombre_miembro_asociacion'];?>" id="nombreMiembroAnterior" name="nombreMiembroAnterior" />
			<input type="hidden" value="<?php echo $miembroAsociacion['apellido_miembro_asociacion'];?>" id="apellidoMiembroAnterior" name="apellidoMiembroAnterior" />
			<input type="hidden" value="<?php echo $miembroAsociacion['codigo_magap'];?>" id="codigoMagapAnterior" name="codigoMagapAnterior" />
		</div>
			
		<fieldset>
			<legend>Información general</legend>
			<div data-linea="1">
				<label for="codigoMiembro" class="codigoMiembro">Codigo: </label><?php echo $miembroAsociacion['codigo_miembro_asociacion'];?>
			</div>
			<div data-linea="1">
				<label for="identificacionMiembro">Identificación:</label>
				<input value="<?php echo $miembroAsociacion['identificador_miembro_asociacion'];?>" name="identificacionMiembro" type="text" id="identificacionMiembro" placeholder="identificacionMiembro" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div>
			<div data-linea="2">
				<label for="nombreMiembro">Nombres:</label>
				<input value="<?php echo $miembroAsociacion['nombre_miembro_asociacion'];?>" name="nombreMiembro" type="text" id="nombreMiembro" placeholder="nombreMiembro" maxlength="250" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div>
			<div data-linea="3">
				<label for="apellidoMiembro">Apellidos:</label>
				<input value="<?php echo $miembroAsociacion['apellido_miembro_asociacion'];?>" name="apellidoMiembro" type="text" id="apellidoMiembro" placeholder="apellidoMiembro" maxlength="250" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div>
			<?php if($miembroAsociacion['codigo_magap']!=0 || $miembroAsociacion['codigo_magap']!=''){?>
			<div data-linea="4">
				<label for="codigoMagap">Código Magap:</label> 
				<input value="<?php echo $miembroAsociacion['codigo_magap'];?>" name="codigoMagap" type="text" id="codigoMagap" placeholder="Nombres" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div><?php }?>
			<div>
				<button id="modificar" type="button" class="editar">Editar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</div>
		</fieldset>
</form>

<form id="nuevo">
</form>

<fieldset>	
	<legend>Áreas y operaciones del sitio <?php echo $nombreSitio?></legend>
		<table id="detalleMiembroAsociacion">
			<?php 
				while ($detalle = pg_fetch_assoc($qDetalle)){					
					echo $cro->imprimirLineaDetalleMiembroAsociacion($detalle['id_tipo_operacion'], $detalle['nombre_tipo_operacion'], $detalle['id_operacion'], $detalle['id_producto'], $detalle['nombre_producto'], $detalle['id_sitio'], $detalle['nombre_lugar'], $detalle['id_area'], $detalle['nombre_area'], $detalle['rendimiento'], $idMiembroAsociacion, $detalle['id_detalle_miembro_asociacion'], $detalle['identificador_miembro_asociacion']);
				}
			?>
		</table>
</fieldset>
<script type="text/javascript">

	$('document').ready(function(){
		
		acciones("#nuevo","#detalleMiembroAsociacion");
		$("#identificacionMiembro").numeric();
		distribuirLineas();

	});

	
	$(".iconoE").click(function(event){
		
		if ($('#detalleMiembroAsociacion >tbody >tr').length <= 1){
			$("#imprimirDetalle").attr('data-accionEnExito','ACTUALIZAR');
		}	

	});

	
	$("#modificar").click(function(){
		
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		
	});

	
	$("#actualizarMiembroAsociacion").submit(function(event){

		event.preventDefault();
	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if (!error){
			
			var datosDetalle = $("#identificacionMiembro").val()+'-'+$("#nombreMiembro").val()+'-'+$("#apellidoMiembro").val()+'-'+$("#codigoMagap").val();
			
			var data ="opcion="+'cargarDatosMiembro'+'&datosDetalle='+datosDetalle;
		    $.ajax({        
		        type: "POST",
		        data: data,        
		        url: "aplicaciones/registroOperador/cargarDatosAnteriores.php",
		        success: function(data) {   
		        	$("#datosMiembro").html(data);
		        }
		    });

			ejecutarJson($(this));
			
		}else{
			
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
			
		}
		
	});

</script>
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';


$conexion = new Conexion();
$cp = new ControladorProtocolos();

$idProtocoloAreaAsignado = $_POST['idProtocoloAreaAsignado'];
$qProtocoloAreaAsignado = $cp->obtenerProtocoloAreaAsignadoXId($conexion, $idProtocoloAreaAsignado);
$protocoloAreaAsignado = pg_fetch_assoc($qProtocoloAreaAsignado);

$qObtenerAreasProtocolos = $cp->obtenerAreasProtocolos($conexion, $protocoloAreaAsignado['id_protocolo_area']);
$listaAreasProtocolos = pg_fetch_assoc($qObtenerAreasProtocolos);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Abrir protocolos asignados</h1>
	</header>
	<div id="estado"></div>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="regresar" data-rutaAplicacion="inspeccionesDeProtocolo" data-opcion="abrirInspeccionesProtocolo" data-destino="detalleItem">
					<input type="hidden" name="id" value="<?php echo $protocoloAreaAsignado['id_protocolo_area'];?>"/>
					<button class="regresar">Volver</button>
				</form>
	
				<form id="actualizarProtocoloAreaAsignado" data-rutaAplicacion="inspeccionesDeProtocolo" data-opcion="actualizarProtocoloAreaAsignado" >
					<input type="hidden" id="idProtocoloAreaAsignado" name="idProtocoloAreaAsignado" value="<?php echo $idProtocoloAreaAsignado;?>">
										
					<fieldset>
						<legend>Datos generales</legend>

                    	<div data-linea="1">
                    		<label>Razon social:</label>
                    		<input type="text" id="razonSocial" name="razonSocial" value="<?php echo $listaAreasProtocolos ['nombre_operador']; ?>" disabled />
                    	</div>
                    	
                    	<div data-linea="2">
                    		<label>Sitio:</label>
                    		<input type="text" id="sitio" name="sitio" value="<?php echo $listaAreasProtocolos ['codigo_sitio'] . ' - ' . $listaAreasProtocolos ['nombre_sitio']; ?>" disabled />
                    	</div>
                    
                    	<div data-linea="3">
                    		<label>Área:</label> 
                    		<input type="text" id="area" name="area" value="<?php echo $listaAreasProtocolos ['codigo_area'] . ' - ' . $listaAreasProtocolos ['nombre_area']; ?>" disabled />
                    	</div>
                    
                    	<div data-linea="4">
                    		<label>Operacion:</label> 
                    		<input type="text" id="operacion" name="operacion" value="<?php echo $listaAreasProtocolos ['nombre_tipo_operacion']; ?>" disabled />
                    	</div>					

					</fieldset>
				
				
				<fieldset>
						<legend>Resultado de inspección</legend>

                    	<div data-linea="1">
                    		<label>Protocolo:</label> <input type="text" id="nombreProtocolo" name="nombreProtocolo" value="<?php echo $protocoloAreaAsignado ['nombre_protocolo']; ?>" disabled />
                    	</div>
                    	
                    	<div data-linea="2">
                			<label>Estado:</label> <select id="estadoProtocoloAsignado" name="estadoProtocoloAsignado" required disabled="disabled">
                				<option value="">Seleccione...</option>
                				<option value="aprobado">Aprobado</option>
                				<option value="desaprobado">Desaprobado</option>
                				<option value="implementacion">En implementación</option>
                			</select>
                		</div>
                    	<button id="modificar" type="button" class="editar">Modificar</button>
						<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
                </fieldset>
		</form>	
			</td>
		</tr>
	</table>
</body>

<script type="text/javascript">

	$('document').ready(function(){
		cargarValorDefecto("estadoProtocoloAsignado","<?php echo $protocoloAreaAsignado['estado_protocolo_asignado'];?>");
		acciones(null, null);
		distribuirLineas();
	});

	$("#modificar").click(function(){
		$("#estadoProtocoloAsignado").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});
	
	$("#actualizarProtocoloAreaAsignado").submit(function(event){
		event.preventDefault();		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#estadoProtocoloAsignado").val() == ""){
			error = true;
			$("#estadoProtocoloAsignado").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}else{
			ejecutarJson($(this));
		}		
	});
	
</script>
</html>
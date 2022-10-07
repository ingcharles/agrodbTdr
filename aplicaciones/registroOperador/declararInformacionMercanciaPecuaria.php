<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$idOperacion = $_POST['id'];
$identificadorOperador= $_SESSION['usuario'];

$qPaises = $cc->listarLocalizacion($conexion, "PAIS");

$qOperacion = $cr->abrirOperacionXid($conexion, $idOperacion);
$operacion = pg_fetch_assoc($qOperacion);

$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
$idHistorialOperacion = $operacion['id_historial_operacion'];

?>

<header>
	<h1>Declarar Información Mercancías Pecuarias</h1>
</header>

<form id="declararInformacionCentroAcopio" data-rutaAplicacion="registroOperador" data-opcion="agregarRegistroMercanciaPecuaria" data-destino="detalleItem">

	<input type="hidden" id="identificadorOperador" name="identificadorOperador" value="<?php echo $identificadorOperador;?>" />
	<input type="hidden" id="idOperadorTipoOperacion" name="idOperadorTipoOperacion" value="<?php echo $idOperadorTipoOperacion;?>" />

	<fieldset>
		<legend>Información de Mercancías Pecuarias</legend>

		<div data-linea="1">
			<label for="idProducto">Producto: </label> 
			<select id="idProducto" name="idProducto" required="required">
				<option value="">Seleccione...</option>
				<?php
					$qProductos = $cr->obtenerProductoPorIdOperadorTipoOperacionIdHistorialOperacion($conexion, $identificadorOperador, $idOperadorTipoOperacion, $idHistorialOperacion);
					while ($fila = pg_fetch_assoc($qProductos)){
						echo '<option value="'.$fila['id_producto'].'" data-idOperacion= "'.$fila['id_operacion'].'">'.$fila['nombre_producto'].'</option>';
					}
				?>
			</select>
			<input type="hidden" id="idOperacion" name="idOperacion" />
			<input type="hidden" id="nombreProducto" name="nombreProducto" />
		</div>

		<div data-linea="2">
			<label for="idPaisDestino">País de destino: </label> 
			<select id="idPaisDestino" name="idPaisDestino" required="required">
				<option value="">Seleccione...</option>
				<?php 
					while($paises = pg_fetch_assoc($qPaises)){
						echo '<option value="'. $paises['id_localizacion'] .'">'. $paises['nombre'] .'</option>';
					}
				?>
			</select>
			<input type="hidden" id="nombrePais" name="nombrePais" />
		</div>

		<div data-linea="3">
			<label for="usoDestino">Uso destinado: </label>
			<select id="usoDestino" name="usoDestino" required="required">
				<option value="">Seleccione...</option>
				<option value="Consumo">Consumo</option>
				<option value="Comercio">Comercio</option>
				<option value="Industrialización">Industrialización</option>
				<option value="Otro">Otro</option>
			</select>
		</div>
		<button type="submit" class="mas">Agregar registro</button>
	</fieldset>

	<p class="nota">Por favor revise que la información ingresada sea correcta. Una vez enviada no podrá ser modificada.</p>
</form>

<fieldset>
	<legend>Productos a exportar</legend>

	<div data-linea="5">
		<table id="productosExportar" style="width: 100%">
			<thead>
				<tr>
					<th>Producto</th>
					<th>País de destino</th>
					<th>Uso destinado</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$qProductos = $cr->obtenerRegistroMercanciasPecuaria($conexion, $identificadorOperador, $idOperadorTipoOperacion);

					while ($fila = pg_fetch_assoc($qProductos)){
						echo $cr->imprimirLineaMercanciasPecuaria($fila['id_centro_pecuario'], $fila['nombre_producto'], $fila['nombre_pais'], $fila['uso']);
					}
				?>
			</tbody>
		</table>
	</div>
</fieldset>

<form id="enviarProductosOperador" data-rutaAplicacion="registroOperador" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idOperadorTipoOperacion" name="idOperadorTipoOperacion" value=" <?php echo $idOperadorTipoOperacion;?>" />
	<input type="hidden" id="idHistorialOperacion" name="idHistorialOperacion" value=" <?php echo $idHistorialOperacion;?>" />
	<input type="hidden" id="operacionInicial" name="operacionInicial" value="<?php echo $idOperacion;?>" />

	<div>
		<input id="aceptarCondicion" name="aceptarCondicion" type="checkbox"/>
			Declaro que la información consignada es verdadera, me comprometo a cumplir con las disposiciones dadas por la Agencia y a mantener actualizada la información proporcionada.
	</div>
	<br/>
	<button type="submit" class="guardar" id="enviarSolicitud">Enviar solicitud</button>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
		acciones("#declararInformacionCentroAcopio","#productosExportar");
		$('#enviarSolicitud').attr('disabled','disabled');
	});

	$("#idProducto").change(function(event){
		$("#idOperacion").val($("#idProducto option:selected").attr("data-idOperacion"));
		$("#nombreProducto").val($("#idProducto option:selected").text());
	});

	$("#idPaisDestino").change(function(event){
		$("#nombrePais").val($("#idPaisDestino option:selected").text());
	});

	$("#enviarProductosOperador").submit(function(event){
		event.preventDefault();
		if($("#productosExportar >tbody >tr").length !=0){
			$("#enviarProductosOperador").attr('data-opcion', 'actualizarEstadoOperacionMercanciaPecuaria');
			$("#enviarProductosOperador").attr('data-destino', 'detalleItem');
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor ingrese por lo menos un registro").addClass("alerta");
		}
	});

	$("#aceptarCondicion").click(function(e){
		if($('#aceptarCondicion').is(':checked')){
			$('#enviarSolicitud').removeAttr('disabled');
		}else{
			$('#enviarSolicitud').attr('disabled','disabled');
		}
	});
</script>
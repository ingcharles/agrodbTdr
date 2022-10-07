<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';


$conexion = new Conexion();
$cr = new ControladorRegistroOperador();

$operaciones=$_POST['elementos'];

$registros = $cr->obtenerOperadorProductoOperacion($conexion,$operaciones);

while($fila = pg_fetch_assoc($registros)){
	$operadores[] = array(identificador => $fila['identificador'], nombreOperador => $fila['nombre_operador'], nombreTipoOperacion => $fila['nombre_tipo_operacion'],
			nombreProducto => $fila['nombre_producto'], estado => $fila['estado'], idVue => $fila['id_vue'], nombreSubtipo => $fila['nombre_subtipo'],
			nombreTipo => $fila['nombre_tipo']);
}

?>

<header>
	<h1>Grupo de operaciones</h1>
</header>

<div id="estado"></div>

<p>Las <b>operaciones</b> a ser enviadas son: </p>

<fieldset>
	<legend><?php echo 'Operador: '.$operadores[0]['nombreOperador']?></legend>

<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Operación</th>
			<th>Tipo producto</th>
			<th>Subtipo producto</th>
			<th>Producto</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
	

	<?php 
	
	$contador = 0;
	foreach ($operadores as $operador){
		echo '<tr id="'.$operador['identificador'].'">
					<td>'.++$contador.'</td>
					<td><b>'.$operador['nombreTipoOperacion'].'</b></td>
					<td>'.$operador['nombreTipo'].'</td>
					<td>'.$operador['nombreSubtipo'].'</td>
					<td>'.$operador['nombreProducto'].'</td>
				</tr>';
	}
	?>
	
	</table>
	
</fieldset>

<form id='enviarTemporal' data-rutaAplicacion='registroOperador' data-opcion='enviarOperacionTemporal' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<input type="hidden" id="idOperaciones" name="idOperaciones" value="<?php echo $operaciones;?>" />
	
	<button type="submit" class="guardar">Enviar</button>

</form>

<script type="text/javascript">

	var array_operaciones= <?php echo json_encode($operaciones); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_operaciones == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una o varias operaciones temporales y  a continuación presione el boton "Enviar temporales".</div>');
		}
		
	});

	$("#enviarTemporal").submit(function(event){
	 	event.preventDefault();
		ejecutarJson($(this));
	});
	
	
</script>

</html>

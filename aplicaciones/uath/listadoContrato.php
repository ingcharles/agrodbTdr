<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastro.php';

//require_once('../../FirePHPCore/FirePHP.class.php');
//ob_start();
$conexion = new Conexion();
?>

<header>
	<h1>Contratos</h1>
	<!--<nav>

		<a href="#" id="_nuevo" data-destino="detalleItem"
			data-opcion="nuevoFamiliarContacto" data-rutaAplicacion="uath">Nuevo</a>
		a href="#" id="_actualizar" data-destino="detalleItem" data-opcion=""
			data-rutaaplicacion="uath">Actualizar</a
		<a href="#" id="_seleccionar" data-destino="detalleItem" data-opcion="" data-rutaaplicacion="uath"><div id="cantidadItemsSeleccionados">0</div>Seleccionar</a>
		<a href="#" id="_eliminar" data-destino="detalleItem" data-opcion="eliminarFamiliarContacto" data-rutaaplicacion="uath">Eliminar</a>
	</nav> -->
</header>
<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Tipo Contrato</th>
			<th>Numero Contrato</th>
			<th>Fecha Inicio</th>
			<th>Fecha Fin</th>

		</tr>
	</thead>
	<?php 
		$cd = new ControladorCatastro();
		$res = $cd->obtenerDatosContratoUsuario($conexion, $_SESSION['usuario'], 'Total');
		$contador = 0;
		while($contrato = pg_fetch_assoc($res)){
			echo '<tr 	id="'.$contrato['id_datos_contrato'].'"
						class="item"
						data-rutaAplicacion="uath"
						data-opcion="verContrato" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem"
						>
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$contrato['tipo_contrato'].'</b></td>
					<td>'.$contrato['numero_contrato'].'</td>
					<td>'.$contrato['fecha_inicio'].'</td>
					<td>'.$contrato['fecha_fin'].'</td>
					
				</tr>';
		
		}
		
		
	?>
	</table>



<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");

	});
	</script>

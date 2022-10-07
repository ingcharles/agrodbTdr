<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

	$conexion = new Conexion();
	$cc = new ControladorCatastro();
	$identificador=$_SESSION['usuario'];
?>	
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Listado de Roles</th>
				</tr>
			</thead>
			<?php 
				 	$res = $cc->obtenerRolPagos($conexion, $identificador,'NO','',$_POST['id']);
				 	$contador = 0;
				 	while($roles = pg_fetch_assoc($res))
				 	{
				 		$nombreArchivo= str_ireplace('_', ' ', $roles['nombre_archivo']);
				 		$nombreArchivo= str_ireplace('.pdf', '', $nombreArchivo);
				 	
				 		echo '<tr id="'.$roles['ruta_archivo'].'"
				 		class="item"
				 		data-rutaAplicacion="uath"
				 		data-opcion="abrirRolPagos"
				 		ondragstart="drag(event)"
				 		draggable="true"
				 		data-destino="detalleItem"
				 		>
				 		<td>'.++$contador.'</td>
				 		<td style="white-space:nowrap;"><b>'.$nombreArchivo.'</b></td>
				 		</tr>';
				 	}
				 
			?>
		</table>
  <script type="text/javascript">
	$(document).ready(function(){
		//$("#listadoItems").removeClass("lista");
		//$("#listadoItems").addClass("comunes");
		
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");	
		$("#detalleItem").html('<div class="mensajeInicial"></div>');
	});

	$('#_actualizar').click(function(event){
		event.preventDefault();
		abrir($('#_actualizar'),event, false);
	});	
 </script>

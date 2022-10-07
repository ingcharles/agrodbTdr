<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

	$conexion = new Conexion();
	//$identificador='1709164949'; //$_SESSION['usuario'];
	$identificador=$_SESSION['usuario'];

	?>
		<header>
			<h1>Rol Pagos</h1>
			<nav>
				<a id="_actualizar" data-rutaaplicacion="general" data-opcion="listarRolPagos" data-destino="listadoItems" href="#">Actualizar</a>
			</nav>
		</header>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Listado de Roles</th>
				</tr>
			</thead>
			<?php 
				$cc = new ControladorCatastro();
				$res = $cc->obtenerRolPagos($conexion, $identificador);
				$contador = 0;
				
				//$rutaArchivo='aplicaciones/uath/archivosRolPagos/pdf/1709164949/1709164949_ROL_MENSUAL_AGOSTO_2016.pdf';
				while($roles = pg_fetch_assoc($res))
					{	
					$nombreArchivo= str_ireplace('_', ' ', $roles['nombre_archivo']);		
					$nombreArchivo= str_ireplace('.pdf', '', $nombreArchivo);
						
					echo '<tr 	id="'.$roles['ruta_archivo'].'"
								class="item"
								data-rutaAplicacion="general"
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
  <script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");	
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	});

	$('#_actualizar').click(function(event){
		event.preventDefault();
		abrir($('#_actualizar'),event, false);
	});	
	
 </script>

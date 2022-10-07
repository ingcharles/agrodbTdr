<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastro.php';
//require_once('../../FirePHPCore/FirePHP.class.php'); borrado
//ob_start(); borrado
$conexion = new Conexion();
//$firephp = FirePHP::getInstance(true); borrado	
//$identificador=$_POST['identificador'];
//$firephp->warn($identificador);

	$identificador=$_SESSION['usuario'];

	?>
	
		<header>
		
			<h1>Experiencia Laboral</h1>
			
			<nav>
				<a id="_nuevo" data-rutaaplicacion="uath" data-opcion="nuevosExperienciaLaboral" data-destino="detalleItem" href="#">Nuevo</a>
				<a id="_actualizar" data-rutaaplicacion="uath" data-opcion="listaExperienciaLaboral" data-destino="listadoItems" href="#">Actualizar</a>
				<a id="_eliminar" data-rutaaplicacion="uath" data-opcion="eliminarExperienciaLaboral" data-destino="detalleItem" href="#">Eliminar</a>
			</nav>
		</header>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Institucion</th>
					<th>Unidad Administrativa</th>
					<th>Puesto</th>
					<th>Estado</th>
					<th>Observaciones</th>
	
				</tr>
			</thead>
			<?php 
				$cd = new ControladorCatastro();
				$res = $cd->obtenerExperienciaLaboral($conexion, $identificador);
				$contador = 0;
				while($experiencia = pg_fetch_assoc($res))
				{
					
					echo '<tr 	id="'.$experiencia['id_experiencia_laboral'].'"
								class="item"
								data-rutaAplicacion="uath"
								data-opcion="modificarExperienciaLaboral" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem">
							<td>'.++$contador.'</td>
							<td>'.$experiencia['institucion'].'</td>
							<td>'.$experiencia['unidad_administrativa'].'</td>
							<td>'.$experiencia['puesto'].'</td>
							<td>'.$experiencia['estado'].'</td>
							<td>'.$experiencia['observaciones_rrhh'].'</td>
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

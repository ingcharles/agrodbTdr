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
		
			<h1>Datos Capacitaciones</h1>
			
			
			<nav>
				<a id="_nuevo" data-rutaaplicacion="uath" data-opcion="nuevosDatosCapacitacion" data-destino="detalleItem" href="#">Nuevo</a>
				<a id="_actualizar" data-rutaaplicacion="uath" data-opcion="listaDatosCapacitacion" data-destino="listadoItems" href="#">Actualizar</a>
				<a id="_eliminar" data-rutaaplicacion="uath" data-opcion="eliminarDatosCapacitacion" data-destino="detalleItem" href="#">Eliminar</a>
			</nav>
		</header>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Título</th>
					<th>País</th>
					<th>Estado</th>
					<th>Observaciones</th>
					
	
				</tr>
			</thead>
			<?php 
				$cd = new ControladorCatastro();
				$res = $cd->obtenerDatosCapacitacion($conexion, $identificador);
				$contador = 0;
				while($academico = pg_fetch_assoc($res))
				{
					
					echo '<tr 	id="'.$academico['id_datos_capacitacion'].'"
								class="item"
								data-rutaAplicacion="uath"
								data-opcion="modificarDatosCapacitacion" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem"
								>
							<td>'.++$contador.'</td>
							<td>'.$academico['titulo_capacitacion'].'</td>
							<td>'.$academico['pais'].'</td>
							<td>'.$academico['estado'].'</td>
							<td>'.$academico['observaciones'].'</td>
						</tr>';

				}
				
				
			?>
		</table>

<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		
		//if($('#identificador').val()=='')
		//{
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		//}
	});

	$('#_actualizar').click(function(event){
		event.preventDefault();
		abrir($('#_actualizar'),event, false);
	});



	
	
	
	</script>


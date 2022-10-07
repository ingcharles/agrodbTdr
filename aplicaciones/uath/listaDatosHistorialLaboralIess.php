<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
$conexion = new Conexion();

	$identificador=$_SESSION['usuario'];

	?>
	
		<header>
		
			<h1>Historial Laboral IESS</h1>
			
			<nav>
				<a id="_nuevo" data-rutaaplicacion="uath" data-opcion="agregarHistorialLaboral" data-destino="detalleItem" href="#">Nuevo</a>
				<a id="_actualizar" data-rutaaplicacion="uath" data-opcion="listaDatosHistorialLaboralIess" data-destino="listadoItems" href="#">Actualizar</a>
				
			</nav>
		</header>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Fecha de registro</th>
					<th>Estado</th>
					<th>Observaciones</th>
	
				</tr>
			</thead>
			<?php 
			$cd = new ControladorCatastro();
			$res = $cd->obtenerDatosHistorialLaboralIess($conexion, $identificador,'');
			$contador = 0;
			while($historial = pg_fetch_assoc($res))
			{
					
				echo '<tr 	id="'.$historial['id_datos_historial_laboral'].'"
								class="item"
								data-rutaAplicacion="uath"
								data-opcion="modificarDatosHistorialLaboralIess"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="detalleItem"
								>
							<td>'.++$contador.'</td>
							<td>'.$historial['fecha'].'</td>
				
							<td>'.$historial['estado'].'</td>
							<td>'.$historial['observacion'].'</td>
						</tr>';
			
			}
				
			$conexion->desconectar();
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


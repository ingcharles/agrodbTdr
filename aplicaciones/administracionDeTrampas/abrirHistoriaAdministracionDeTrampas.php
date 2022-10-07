<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministracionDeTrampas.php';
	
$conexion = new Conexion();	
$cat = new ControladorAdministracionDeTrampas();

$idAdministracionTrampa = $_POST['id'];

$qHistoriaTrampa = $cat->obtenerHistoriaAdministracionTrampaPorIdAdministracion($conexion, $idAdministracionTrampa);

?>

<header>
	<h1>Historia Administración de Trampas</h1>
</header>

<div id="estado"></div>
	
	<form id="abrirHistoriaAdministracionDeTrampas" data-rutaAplicacion="administracionDeTrampas" >

		<fieldset>
			<legend>Datos Generales trampa <?php echo $historiaTrampa['codigo_trampa']; ?></legend>
			<table style="width: 100%;">
			<thead>
				<tr>
				<th>#</th> 
				<th>Estado trampa</th>
				<th>Fecha de modificación</th>
				<th>Identificador técnico</th>
				<th>Observación</th>
			</tr>
			</thead>
						<?php
						$contador = 0;						
						while ($historiaTrampa = pg_fetch_assoc($qHistoriaTrampa)){

							switch ($historiaTrampa['estado_trampa']){
								
								case "inactivo":
									$estadoTrampa = "Inactiva";									
									break;
								case "activo":
									$estadoTrampa = "Activa";
									break;
								case "eliminado":
									$estadoTrampa = "Eliminada";
									break;
							}

							echo  '<tr>
								<td style="text-align: center;">'.++$contador.'</td>
								<td style="text-align: center;">'. $estadoTrampa.'</td>
								<td style="text-align: center;">'.$historiaTrampa['fecha_modificacion'].'</td>
								<td style="text-align: center;">'.$historiaTrampa['identificador_tecnico'].'</td>
								<td>'.$historiaTrampa['observacion'].'</td>
							</tr>';
						}
						?>
				</table>
		</fieldset>
		
	</form>
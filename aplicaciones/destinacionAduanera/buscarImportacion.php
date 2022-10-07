<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportaciones.php';

$conexion = new Conexion();
$ci = new ControladorImportaciones();

$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
$idImportacion = htmlspecialchars ($_POST['permisoImportacion'],ENT_NOQUOTES,'UTF-8');

$error = 0;

	$qImportacion = $ci -> abrirImportacion($conexion, $identificador, $idImportacion);

		echo '	<fieldset id="informacionExportador">
					<legend>Información del Exportador</legend>
						<div data-linea="1">			
							<label>Nombre exportador</label> '
							. $qImportacion[0]['nombreExportador'] . 
				 		'</div> <br />
					
						<div data-linea="2">
							<label>País origen</label> '
							. $qImportacion[0]['direccionExportador'] .
						'</div> <br />
						
						<div data-linea="3">
							<label>Dirección</label> '	
							. $qImportacion[0]['paisExportacion'] .
						'</div> 
				</fieldset>';
		
		echo '	<fieldset id="informacionArribo">
					<legend>Información de Arribo</legend>
						<div data-linea="1">
							<label>Puerto destino</label> '
							. $qImportacion[0]['puertoDestino'] .
						'</div> <br />
			
						<div data-linea="2">
							<label>Transporte</label> '
						. $qImportacion[0]['tipoTransporte'] .
						'</div> <br />
				</fieldset>';
				
		echo '  <fieldset id="informacionProductos">
					<legend>Detalle de productos</legend>
							 <div>
								<table>
									<thead>
										<tr>
											<th></th>
											<th>Producto</th>
											<th>Unidades</th>
											<th>Peso</th>
										<tr>
									</thead> 
									
									<tbody id="productos">';
			$i = 1;
				foreach($qImportacion as $fila){
					echo '	<tr>
								<td>'
									.$i.
								'</td>
								<td>'
									.$fila['nombreProducto'].
								'</td>
								<td>'
									.$fila['unidad'].
								'</td>
								<td>'
									.$fila['peso'].' kg.
								</td>
							</tr>';
					$i++;
				}
		
		echo						'</tbody>
									
								</table>
							</div>
			
				</fieldset>';
?>

<script type="text/javascript">
	
</script>
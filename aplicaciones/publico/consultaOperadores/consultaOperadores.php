<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link href="estilos/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>

<header>

<h1>Operadores</h1>
		
</header>


<?php

require_once '../../../clases/Conexion.php';

$conexion = new Conexion();

//$tmp = $conexion->ejecutarConsulta("select * from g_operadores.areas");

$tmp = ($conexion->ejecutarConsulta("
		SELECT row_to_json (operador)
		FROM (
			SELECT
				o1.* ,
				(
					SELECT array_to_json(array_agg(row_to_json(s2)))
					FROM (
							SELECT 
									s1.* , (
										SELECT array_to_json(array_agg(row_to_json(a2)))
										from (
											select 
												* 
											from 
												g_operadores.areas a1
											where
												a1.id_sitio = s1.id_sitio ) a2 ) areas
							FROM 
									g_operadores.sitios s1 
							WHERE 
									s1.identificador_operador=o1.identificador) s2 ) sitios
			FROM 
				g_operadores.operadores o1 
			) as operador"));


//print_r(($tmp));
//print_r(($tmp[row_to_json]));
//print_r(json_decode($tmp[row_to_json]));



while($tmp2 = pg_fetch_assoc($tmp)){
	$operador = (array)(json_decode($tmp2[row_to_json]));
	
	?> 
		<table class="operador">
			<tr>
				<td colspan="4"><?php  echo $operador['razon_social'];?></td>
			</tr>
			<tr>
				<th colspan="4" class="tituloSeccion">Datos Generales del operador</th>
			</tr>
			<tr>
				<th>RUC/CI</th><td><?php echo $operador['identificador'];?></td>
				<th>Persona</th><td><?php echo $operador['tipo_operador'];?></td>
			</tr>
			<tr>
				<th>Representante</th><td><?php  echo $operador['apellido_representante'] . ', ' . $operador['nombre_representante'];?></td>
				<th>Técnico</th><td><?php  echo $operador['apellido_tecnico'] . ', ' . $operador['nombre_tecnico'];?></td>
			</tr>
			<tr>
				<th>Dirección</th><td colspan="3"><?php  echo $operador['provincia'] . ' - ' . $operador['canton']. ' (' . $operador['parroquia'] . '), ' . $operador['direccion'];?></td>
			</tr>
			<tr>			
				<th>Teléfono</th><td><?php  echo $operador['telefono_uno'] . '<br/>' . $operador['telefono_dos'] . '<br/>FAX: ' . $operador['fax'] ;?></td>
				<th>Celular</th><td><?php  echo $operador['celular_uno'] . ' <br/> ' . $operador['celular_dos'];?></td>
			</tr>
			<tr>
				<th>Correo</th><td colspan="3"><?php  echo $operador['correo'] ;?></td>
			</tr>	
			<tr>
				<th colspan="4" class="tituloSeccion">Sitios registrados</th>
			</tr>
			<tr>
				<td colspan="4">
				<table class="sitios">
							
								
				<?php 
					$sitios = $operador['sitios'];
					if (!empty($sitios)){
						foreach($sitios as $sitioTmp){
							$sitio = (array)$sitioTmp;
							?>
							
								<tr>
									<th class="tituloSitio" colspan="4"> <?php echo $sitio['codigo'] . ': ' . $sitio['nombre_lugar'] . ' (' . $sitio['superficie_total'] . ' m&sup2;)';?>	</th>
								</tr>
								<tr>
									<th>Dirección</th><td colspan="3"><?php  echo $sitio['provincia'] . ' - ' . $sitio['canton']. ' (' . $sitio['parroquia'] . '), ' . $sitio['direccion'];?><div class="referencia">Refencia: <?php echo $sitio['referencia'];?></div></td>
								</tr>
								<tr>
									<th>Latitud</th><td> <?php echo $sitio['latitud']?>	</td>
									<th>Longitud</th><td> <?php echo $sitio['longitud']?>	</td>
								</tr>
								<tr>
									<th>Teléfono</th><td> <?php echo $sitio['telefono']?>	</td>
									<th>Estado</th><td><span class="estado"> <?php echo $sitio['estado']?></span>	</td>
								</tr>
								<tr>
								<td colspan="4">
									<table class='areas'>
										<tr>
											<th>Área</th>
											<th>Superficie</th>
											<th>Observación</th>
											<th>Estado</th>
										</tr>	
									
										<?php 
											$areas = $sitio['areas'];
											if(!empty($areas)){
												foreach($areas as $areaTmp){
													$area = (array)$areaTmp;
													echo '<tr><td>' . $area['tipo_area'] .': '. $area['nombre_area'] . '</td>'.
															'<td>' . $area['superficie_utilizada'] . '</td>' .
															'<td>' . $area['observacion'] . '</td>' .
															'<td>' . $area['estado'] . '</td></tr>';
												}
											} else {
												echo '<span class="alerta">No se han registrados áreas.</span>';
											}
											?>
									</table>
								</td>
								</tr>
							<?php 
						}
					} else{
							echo '<span class="alerta">No se han registrados sitios.</span>';
					}
				?>				
				
								
						</table>
				</tr>
			<tr>
			<tr>
				<th colspan="4" class="tituloSeccion">Operaciones registradas</th>
			</tr>
		</table>
	
	
	
		
	<?php 
	
	/*echo 
			'<section class="operador">'.
				'<div class="datos_generales">'.
					'<span id="razon">' . $operador['razon_social'] . '(' . $operador['identificador'] . ')</span>' .
				'</div>'.
			'<section/>';*/
	
}
?>


</body>
</html>
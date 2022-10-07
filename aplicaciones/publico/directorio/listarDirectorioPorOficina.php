<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorAreas.php';
require_once '../../../clases/ControladorDirectorio.php';

$conexion = new Conexion();
$ca = new ControladorAreas();
$cd = new ControladorDirectorio();

$area = $_POST['area'];
$categoriaArea = $_POST['categoriaArea'];
$apellido = $_POST['apellido'];

if($area != null){
	
	if($categoriaArea == '3' || $categoriaArea == '1'){
		$areaSubproceso = "'".$area."',";
	}else{
			
		$qAreasSubProcesos = $ca->buscarAreasSubprocesos($conexion, $area);
			
		$areaSubproceso = "'".$area."',";
	
		while($fila = pg_fetch_assoc($qAreasSubProcesos)){
			$areaSubproceso .= "'".$fila['id_area']."',";
		}
	}
	
	$areaSubproceso = "(".rtrim($areaSubproceso,',').")";
	
}

$funcionarios = $cd->obtenerFuncionariosPorArea($conexion, $areaSubproceso, $apellido);

echo '<h1>Listado</h1><table>';

echo '<thead><tr>'.
		'<th>Oficina</th>'.
		'<th>Área</th>'.
		'<th>Funcionario</th>'.
		'<th>Teléfono</th>'.
		'<th>Extensión</th>'.
			
			
		'</tr></thead><tbody>';

while ($funcionario = pg_fetch_assoc($funcionarios)){
	echo '<tr>'.
			'<td>'.$funcionario['oficina'] . '</td>'.
			'<td>'.$funcionario['area'] . '</td>'.
			'<td>'.$funcionario['apellido'] .
			', '.$funcionario['nombre'] . '</td>'.
			'<td>'.$funcionario['telefono'] . '</td>'.
			'<td>'.$funcionario['extension'] . '</td>'.
			
			
		'</tr>';
}
echo '</tbody></table>';
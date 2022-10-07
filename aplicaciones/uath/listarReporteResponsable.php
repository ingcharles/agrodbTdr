<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatastro.php';	
	$conexion = new Conexion();
	$cc = new ControladorCatastro();
	
	$identificador = $_POST['identificador'];
	$apellido = $_POST['apellido'];
	$nombre = $_POST['nombre'];
	$puesto = $_POST['puesto'];
	
	if(htmlspecialchars ($_POST['seleccionCategoria'],ENT_NOQUOTES,'UTF-8') <> '')
		$area = htmlspecialchars ($_POST['seleccionCategoria'],ENT_NOQUOTES,'UTF-8');
	if(htmlspecialchars ($_POST['seleccionCategoria2'],ENT_NOQUOTES,'UTF-8') <> '')
		$area = htmlspecialchars ($_POST['seleccionCategoria2'],ENT_NOQUOTES,'UTF-8');
	if(htmlspecialchars ($_POST['seleccionCategoria3'],ENT_NOQUOTES,'UTF-8') <> '')
		$area = htmlspecialchars ($_POST['seleccionCategoria3'],ENT_NOQUOTES,'UTF-8');
	if(htmlspecialchars ($_POST['seleccionCategoria4'],ENT_NOQUOTES,'UTF-8') <> '')
		$area = htmlspecialchars ($_POST['seleccionCategoria4'],ENT_NOQUOTES,'UTF-8');
	if(htmlspecialchars ($_POST['seleccionCategoria5'],ENT_NOQUOTES,'UTF-8') <> '')
		$area = htmlspecialchars ($_POST['seleccionCategoria5'],ENT_NOQUOTES,'UTF-8');
	//$listaReporte = $cc->filtroObtenerFuncionarios($conexion, $identificador, $apellido, $nombre, 'Activo', $area);
	$listaReporte = $cc->filtroObtenerEncargo($conexion, $identificador, $apellido, $nombre, 'Aprobado', '','unico','',$puesto,$area);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<div id="paginacion" class="normal">
 </div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th style="width: 24%;">Funcionario</th>
			<th>Área</th>
			<th>Puesto</th>
			<th>Designación</th>			
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
</body>	
<?php 
		$contador = 0; 
		$itemsFiltrados[] = array();
		while($fila = pg_fetch_assoc($listaReporte)){
			$nombre='';
				
				$prioridad = pg_fetch_result($cc->verificarResponsable($conexion,$fila['identificador_responsable'], $fila['area'],''), 0, 'prioridad');
				if($prioridad==1 or $prioridad==3)$nombre='Responsable';
				else $nombre='Subrogante';		
				$bandera=0;			
				if(pg_num_rows($cc->verificarResponsable($conexion,$fila['identificador_responsable'], $fila['area'])))
					$bandera=1;
				if(pg_num_rows($cc->verificarResponsablePuesto($conexion,$fila['identificador_responsable'], $fila['area'])))
					$bandera=2;
			
			$descripcion= $fila['area'].'<br>'.$fila['nombrearea'];
			if($fila['designacion']=='Encargado')$nombre='Titular';
			
			$identicar=$fila['identificador_responsable'].'.'.$fila['area'].'.'.$nombre;		
			$itemsFiltrados[] = array('<tr 
						id="'.$identicar.'"
						class="item"
						data-rutaAplicacion="uath"
						data-opcion="abrirReporteResponsable" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="width: 24%;"><b>'.$fila['nombre'].'</b></td>
					<td>'.$descripcion.'</td>
					<td>'.$fila['nombre_puesto'].'</td>				
					<td>'.$nombre.'</td>					
				</tr>');
			}
?>			

<script type="text/javascript"> 

	$(document).ready(function(){
		$("#detalleItem").html('');		
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});
	
</script>
</html>

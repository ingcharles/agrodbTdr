<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatastro.php';	
	$conexion = new Conexion();
	$cc = new ControladorCatastro();
	
	$identificador = $_POST['identificador'];
	$apellido = $_POST['apellidoUsuario'];
	$nombre = $_POST['nombreUsuario'];
	$responsable = $_POST['responsable'];
	$area = $_POST['area'];	
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
	$listaReporte = $cc->filtroObtenerFuncionarios($conexion, $identificador, $apellido, $nombre, $responsable, $area);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<table>
	<thead>
		<tr>
			<th>#</th>
			<th style="width: 24%;">Funcionario</th>
			<th>Área</th>
			<th>Responsable Área</th>
			
		</tr>
	</thead>
	
<?php 

		$contador = 0; 
		while($fila = pg_fetch_assoc($listaReporte)){
			$nombre='';
			if($responsable <> '')$nombre='Responsable';
			if(pg_num_rows($cc->verificarResponsable($conexion,$fila['identificador'], $fila['area']))){
				
				$prioridad = pg_fetch_result($cc->verificarResponsable($conexion,$fila['identificador'], $fila['area']), 0, 'prioridad');
				if($prioridad==1)$nombre='Responsable';
				else $nombre='Subrogante';								
			}
			$descripcion= $fila['area'].'<br>'.$fila['nombrearea'];
			$identicar=$fila['identificador'].'.'.$fila['area'].'.'.$nombre;
			echo '<tr 
						id="'.$identicar.'"
						class="item"
						data-rutaAplicacion="uath"
						data-opcion="abrirEstructuraFuncionario" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="width: 24%;"><b>'.$fila['nombre'].'</b></td>
					<td>'.$descripcion.'</td>				
					<td>'.$nombre.'</td>					
				</tr>';
			}
?>			
</table>
</body>

<script type="text/javascript"> 

	$(document).ready(function(){
		$("#detalleItem").html('');		
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
	});
	
</script>
</html>

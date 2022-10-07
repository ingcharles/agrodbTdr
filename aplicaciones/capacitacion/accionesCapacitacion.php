<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$ca = new ControladorAreas();
$ce = new ControladorCapacitacion();

$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcionFuncionario'],ENT_NOQUOTES,'UTF-8');
$funcionarioReplica = htmlspecialchars ($_POST['identificadorReplicador'],ENT_NOQUOTES,'UTF-8');
$categoriaArea = htmlspecialchars ($_POST['categoriaArea'],ENT_NOQUOTES,'UTF-8');


switch ($opcion){
	case 'funcionario':
		
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
		
		$funcionarios = $ca->obtenerFuncionariosXareasCapacitacion($conexion, $areaSubproceso);
		
		echo '<label>Funcionarios</label>
				<select id="ocupante" name="ocupante" required>
				<option value="" selected="selected" >Seleccione....</option><option value="Todos">Todos</option>';
				while ($resultado = pg_fetch_assoc($funcionarios)){
					echo '<option value="'.$resultado['identificador'].'" data-bloqueo="'.$resultado['bloqueo'].'">'.$resultado['apellido'].' '.$resultado['nombre'].'</option>';
				}
		echo '</select>';
	break;
	
	case 'funcionarioReplica':
		
		$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
		$qAreasSubProcesos = $ca->buscarAreasSubprocesos($conexion, $area);
			
		while($fila = pg_fetch_assoc($qAreasSubProcesos)){
			$areaSubproceso .= "'".$fila['id_area']."',";
		}
	
		$areaSubproceso = "(".rtrim($areaSubproceso,',').")";
	
		$funcionarios = $ca->obtenerFuncionariosXareasCapacitacion($conexion, $areaSubproceso);
	
		echo '<label>Funcionarios</label>
				<select codigo="'.$funcionarioReplica.'" class="listadoFuncionarios" id="ocupante_'.$funcionarioReplica.'" name="ocupante" required>
				<option value="" selected="selected" >Seleccione....</option><option value="Todos">Todos</option>';
		while ($resultado = pg_fetch_assoc($funcionarios)){
			echo '<option value="'.$resultado['identificador'].'" data-bloqueo="'.$resultado['bloqueo'].'">'.$resultado['apellido'].', '.$resultado['nombre'].'</option>';
		}
		echo '</select>';
	break;

	default:
		echo 'Tipo desconocido';
	break;
}


?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$("#ocupante").change(function(){
		if($("#ocupante").val()!=0){
			$("#nombreFuncionario").val($("#ocupante option:selected").text());
		}
	});

	
	$('.listadoFuncionarios').change(function(event){
		event.stopImmediatePropagation();
		$("#identificadorReplicado_"+$(this).attr("codigo")).val($(this).val());			 
		$("#nombreReplicado_"+$(this).attr("codigo")).val($('option:selected',this).text());			 
	});
</script>

<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';

$conexion = new Conexion();
$cv = new ControladorVacaciones();

 $idExcelDesc=$_POST['id'];

$contador = 0;
$itemsFiltrados[] = array();


//$res = $ca->obtenerRolPagosXmesExcel($conexion, $idExcelDesc);

$res = pg_fetch_assoc($cv->buscarExcelDescuentos($conexion, null,null,$idExcelDesc));



switch ($res['mes_descuento']){
	case 'Enero':
		$fechaSalida = $res['anio_descuento'].'-01-01';
		$fechaRetorno =$res['anio_descuento'].'-01-30';
		break;
	case 'Febrero':
		$fechaSalida = $res['anio_descuento'].'-02-01';
		$fechaRetorno =$res['anio_descuento'].'-02-28';
		break;
	case 'Marzo':
		$fechaSalida = $res['anio_descuento'].'-03-01';
		$fechaRetorno =$res['anio_descuento'].'-03-30';
		break;
	case 'Abril':
		$fechaSalida = $res['anio_descuento'].'-04-01';
		$fechaRetorno =$res['anio_descuento'].'-04-30';
		break;
	case 'Mayo':
		$fechaSalida = $res['anio_descuento'].'-05-01';
		$fechaRetorno =$res['anio_descuento'].'-05-30';
		break;
	case 'Junio':
		$fechaSalida = $res['anio_descuento'].'-06-01';
		$fechaRetorno =$res['anio_descuento'].'-06-30';
		break;
	case 'Julio':
		$fechaSalida = $res['anio_descuento'].'-07-01';
		$fechaRetorno =$res['anio_descuento'].'-07-30';
		break;
	case 'Agosto':
		$fechaSalida = $res['anio_descuento'].'-08-01';
		$fechaRetorno =$res['anio_descuento'].'-08-30';
		break;
	case 'Septiembre':
		$fechaSalida = $res['anio_descuento'].'-09-01';
		$fechaRetorno =$res['anio_descuento'].'-09-30';
		break;
	case 'Octubre':
		$fechaSalida = $res['anio_descuento'].'-10-01';
		$fechaRetorno =$res['anio_descuento'].'-10-30';
		break;
	case 'Noviembre':
		$fechaSalida = $res['anio_descuento'].'-11-01';
		$fechaRetorno =$res['anio_descuento'].'-11-30';
		break;
	case 'Diciembre':
		$fechaSalida = $res['anio_descuento'].'-12-01';
		$fechaRetorno =$res['anio_descuento'].'-12-30';
		break;
   }
		$fechaSalida = new DateTime($fechaSalida);
		date_time_set($fechaSalida,'08','00');
			
		$fechaRetorno = new DateTime($fechaRetorno);
		date_time_set($fechaRetorno,'17','00');
			
		$fechaSalida=date_format($fechaSalida, 'Y-m-d H:i:s');
		$fechaRetorno=date_format($fechaRetorno, 'Y-m-d H:i:s');
		
		$res=$cv->obtenerFuncionariosDescuento($conexion,$fechaSalida, $fechaRetorno, $idExcelDesc);		


while($fila = pg_fetch_assoc($res)){
	$minutos_descontados=$cv->devolverFormatoDiasDisponibles($fila['minutos_utilizados']);
	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_funcionario_rol_pago'].'"
		class=""
		data-rutaAplicacion="uath"
		data-opcion=""
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
		<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
		<td>'.$fila['nombre_completo'].'</td>
		<td>'.$fila['mes_descuento'].'</td>
		<td>'.$fila['anio_descuento'].'</td>	
		<td>'.$minutos_descontados.'</td>	
		</tr>');
}

?>

 <div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Funcionarios</th>	
			<th>Mes</th>
			<th>Año</th>
			<th>Descuento</th>
			
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);			
	});

</script>
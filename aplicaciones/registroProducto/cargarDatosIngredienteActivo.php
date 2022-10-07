<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$incremento = $_POST['incremento'];
$datoIncremento = $_POST['datoIncremento'];

$conexion = new Conexion();
$cr = new ControladorRequisitos();

try{
		
	try {

		$res = $cr->listarTipoIngredienteActivo($conexion, $incremento, $datoIncremento);
		
		$items = array();
		
	while($fila = pg_fetch_assoc($res)){

			switch ($fila['id_area']){
				case 'IAP':
					$categoria = 'Plaguicida';
					$datosAdicionales = '<td>'.$fila['cas'].'</td>
										 <td>'.$fila['formula_quimica'].'</td>
										 <td>'.$fila['grupo_quimico'].'</td>';
				break;
				case 'IAV':
					$categoria = 'Veterinario';
					$datosAdicionales = '';
				break;
				case 'IAF':
					$categoria = 'Fertilizante';
					$datosAdicionales = '';
				break;
				case 'IAPA':
					$categoria = 'PlantasAutoconsumo';
					$datosAdicionales = '';
				break;
			}
				
			$contenido = '<tr 
								id="'.$fila['id_ingrediente_activo'].'"
								class="item"
								data-rutaAplicacion="registroProducto"
								data-opcion="abrirIngredienteActivo" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem">
							<td>'.++$contador.'</td>
							<td>'.$fila['ingrediente_activo'].'</td>
							<td>'.$fila['ingrediente_quimico'].'</td>
							'.$datosAdicionales.'
					</tr>';
				
		$items[] = array(contenido => $contenido, categoria => $categoria);
				
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $items;
			
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error al ejecutar sentencia';
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
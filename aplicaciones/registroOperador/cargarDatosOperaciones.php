<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$incremento = $_POST['incremento'];
$datoIncremento = $_POST['datoIncremento'];
$identificadorOperador = $_POST['identificadorOperador'];

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();

try{
		
	try {

		$res = $cr->listarOperacionesOperador($conexion, $identificadorOperador, " not in ('eliminado')", $incremento,$datoIncremento);
		
		$items = array();
		
		while($fila = pg_fetch_assoc($res)){
	
		switch ($fila['estado']){
	
			case 'registrado':
				$categoria = 'registrado';
				$estado = 'aprobada';
				$clase = 'circulo_verde';
				break;
					
			case 'rechazado':
				$categoria = 'registrado';
				$estado = 'rechazada';
				$clase = 'circulo_rojo';
				break;
					
			case 'cancelado':
				$categoria = 'registrado';
				$estado = 'Cancelado';
				$clase = 'circulo_rojo';
				break;
					
			case 'anulado':
				$categoria = 'registrado';
				$estado = 'Anulado';
				$clase = 'circulo_rojo';
				break;
					
			case 'noHabilitado':
				$categoria = 'registrado';
				$estado = 'No habilitado';
				$clase = 'circulo_rojo';
				break;
					
			case 'registradoObservacion':
				$categoria = 'registrado';
				$estado = 'aprobada con observación';
				$clase = 'circulo_amarillo';
				break;
					
			case 'inactivo':
				$categoria = 'registrado';
				$estado = 'Inactivo';
				$clase = 'circulo_rojo';
				break;
					
			case 'inspeccion':
				$categoria = 'inspeccion';
				$estado = 'por asignar';
				$clase = '';
				break;
					
			case 'asignadoInspeccion':
				$categoria = 'inspeccion';
				$estado = 'asignado';
				$clase = '';
				break;
					
			case 'pago':
				$categoria = 'pago';
				$estado = 'por asignar valor';
				$clase = '';
				break;
					
			case 'representanteTecnico':
				$categoria = 'representanteTecnico';
				$estado = 'Repre. técnico';
				$clase = '';
				break;
					
			case 'cargarAdjunto':
				$categoria = 'cargarAdjunto';
				$estado = 'Adjunto';
				$clase = '';
				break;
					
			case 'subsanacion':
			    $categoria = 'subsanacion';
			    $estado = 'Adjunto';
			    $clase = '';
			    break;
			    
			case 'subsanacionRepresentanteTecnico':
			    $categoria = 'subsanacion';
			    $estado = 'Repre. técnico';
			    $clase = '';
			    break;
			    
			case 'subsanacionProducto':
			    $categoria = 'subsanacion';
			    $estado = 'Por cargar productos';
			    $clase = '';
			    break;
					
			case 'cargarIA':
			case 'declararICentroAcopio':
			case 'declararDVehiculo':
			case 'declararIMercanciaPecuaria':
			case 'declararIColmenar':
				$categoria = 'cargarIA';
				$estado = 'Inf. Adicional';
				$clase = '';
				break;
					
			case 'verificacion':
				$categoria = 'verificacion';
				$estado = 'Por pagar';
				$clase = '';
				break;
				
			case 'cargarProducto':
				$categoria = 'cargarProducto';
				$estado = 'Cargar productos';
				$clase = '';
				break;
				
			case 'documental':
				$categoria = 'documental';
				$estado = 'Por revisión documental';
				$clase = '';
			break;
				
			case 'porCaducar':
			    $categoria = 'porCaducar';
			    $estado = 'Por caducar';
			    $clase = '';
		    break;
			
			case 'cargarRendimiento':
			    $categoria = 'cargarRendimiento';
			    $estado = 'Cargar rendimiento';
			    $clase = '';
			break;
			
			case 'declararProveedor':
			    $categoria = 'declararProveedor';
			    $estado = 'Declarar proveedor';
			    $clase = '';
            break;
			    
			default:
				$categoria = 'ninguna';
				$estado = 'ninguna';
				$clase = '';
		}
		
		
		
		$nombreArea = $cr->buscarNombreAreaPorSitioPorTipoOperacion($conexion, $fila['id_tipo_operacion'], $identificadorOperador, $fila['id_sitio'], $fila['id_operacion']);

		$codigoSitio = $fila['id_sitio'].'-'.$categoria;
		$nombreSitio = $fila['nombre_lugar'];
		$contenido = '<article
			id="'.$fila['id_operacion'].'"
			class="item"
			data-rutaAplicacion="registroOperador"
			data-opcion="abrirOperacion"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
			<span><small> # '.$fila['id_tipo_operacion'].'-'.$fila['id_sitio'].' </small></span>
						<span><small>'.(strlen($fila['provincia'])>15?(substr($cr->reemplazarCaracteres($fila['provincia']),0,15).'...'):(strlen($fila['provincia'])>0?$fila['provincia']:'')).'</small></span><br />
						<span><small>'.(strlen($fila['nombre_tipo_operacion'])>30?(substr($cr->reemplazarCaracteres($fila['nombre_tipo_operacion']),0,30).'...'):(strlen($fila['nombre_tipo_operacion'])>0?$fila['nombre_tipo_operacion']:'')).'<b> en </b> '.
							(strlen($nombreArea)>42?(substr($cr->reemplazarCaracteres($nombreArea),0,42).'...'):(strlen($nombreArea)>0?$nombreArea:'')).'</small></span>
					<aside class= "estadoOperador"><small> Estado: '.$estado.'<span><div class= "'.$clase.'"></div></span></small></aside>
						</article>';
				
		$items[] = array('contenido' => $contenido, 'categoria' => $categoria, 'subcategoria' => $codigoSitio, 'nombreSitio' => $nombreSitio);
				
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
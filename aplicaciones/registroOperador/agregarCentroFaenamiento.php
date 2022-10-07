<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$mensaje['contenido'] = '';


try{
		$area = $_POST['area'];
		$idOperacion = $_POST['idOperacion'];
		$idArea = $_POST['idArea'];

	try {
	    $conexion = new Conexion();
	    
	    $datos = explode('-', $area);
	    $area= $datos[0];
	    $idCentroFaenamiento = $datos[1];
		$cr = new ControladorRegistroOperador();
		$arrayParametros = array('id_centro_faenamiento' => $idCentroFaenamiento, 'id_operacion' => $idOperacion,'id_area' =>$idArea);
		$res = $cr->consultarCentroFaenamienTransporte($conexion,$arrayParametros);
	   if(pg_num_rows($res) > 0){
		    $mensaje['estado'] = 'error';
		    $mensaje['mensaje'] = 'Ya existe el centro de faenamiento..!!';
		   
		}else{
			$idCentro = 0;
		    $res = $cr->guardarCentroFaenamienTransporte($conexion,$arrayParametros);
			$verificarCentros = $cr->consultarCentroFaenamienTransporte($conexion,$arrayParametros);
			if(pg_num_rows($verificarCentros) > 0 ){
				$arrayParametros = array('id_operacion' => $idOperacion,'id_area' =>$idArea);
				$res = $cr->consultarCentroFaenamienTransporte($conexion,$arrayParametros);
				$html = '<table id="listadoCF" style="width:100%">
								<thead>
									<tr>
										<th>#</th>
										<th>RUC</th>
										<th>Razón social</th>
										<th>Provincia</th>
										<th>Sitio</th>
										<th>Área</th>
										<th></th>
									<tr>
								</thead>
								<tbody id="areas">';				
				$contadorProducto = 0;
				while ($fila = pg_fetch_assoc($res)) {
						$arrayParametros = array('id_centro_faenamiento' => $fila['id_centro_faenamiento']);
						$dato = pg_fetch_assoc($cr->buscarCentroFaenamientoXid($conexion,$arrayParametros));
						$html .= '<tr><td>'.++$contadorProducto.'</td>
							<td>'.$dato['identificador_operador'].'</td>
							<td>'.$dato['razon_social'].'</td>
							<td>'.$dato['provincia'].'</td>
							<td>'.$dato['nombre_lugar'].'</td>
							<td>'.$dato['nombre_area'].'</td>
							<td><button type="button" class="menos" onclick="eliminarCF('.$fila['id_centros_faenamiento_transporte'].'); return false; ">Quitar</button></td></tr>';
				}
				$html .= '</tbody>
						</table>';
				$mensaje['estado'] = 'EXITO';
				$mensaje['mensaje'] = 'Registro agregado ..!!';
				$mensaje['contenido'] = $html;
		}else{
			echo("falloooo");
		}
	}
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>

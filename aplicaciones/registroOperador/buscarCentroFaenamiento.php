<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$mensaje['contenido'] = '';


try{
		$ruc = $_POST['ruc'];
		$razonSocial = $_POST['razonSocial'];
		$provincia = $_POST['provincia'];
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
        
		$arrayParametros = array('identificador_operador' => $ruc, 'provincia' => $provincia,'id_area_tipo_operacion' =>'AI', 'codigo' => 'FAE');
		
		$res = $cr -> buscarCentroFaenamiento($conexion, $arrayParametros);
		
		if(pg_num_rows($res) > 0){
		    $combo .= '<option value="" >Seleccione...</option>';
		    while ($fila = pg_fetch_assoc($res)) {
		           $combo .= '<option value="' . $fila['id_sitio'] . '">' . $fila['nombre_lugar'] . '</option>';
		    }
		    $mensaje['estado'] = 'EXITO';
		    $mensaje['mensaje'] = '';
		    $mensaje['contenido'] = $combo;
		}else{
		    $mensaje['estado'] = 'error';
		    $mensaje['mensaje'] = 'No existe el centro de faenamiento buscado...!!';
		    $mensaje['contenido'] = '<option value="" >Seleccione...</option>';
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

<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAuditoria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	//$tipo = htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
	$producto = htmlspecialchars ($_POST['producto'],ENT_NOQUOTES,'UTF-8');
	$pais = htmlspecialchars ($_POST['pais'],ENT_NOQUOTES,'UTF-8');
	$identificadorCreacionRequisitoComercio = $_SESSION['usuario'];
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		$tipo = pg_fetch_result($cc->obtenerAreaProductos($conexion, $producto), 0, 'id_area');
		$nombreProducto = pg_fetch_result($cc->obtenerNombreProducto($conexion, $producto), 0, 'nombre_comun');
		$nombrePais = pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $pais), 0, 'nombre');
		$categoriaPais = pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $pais), 0, 'categoria');
		
		if(pg_num_rows($cr->buscarProductoPais($conexion, $producto, $pais))==0){
			//Ingresar registro original del usuario (individual o grupo)
			$idRequisitoComercio = pg_fetch_row($cr -> guardarRequisitoComercio($conexion, $tipo, $producto, $nombreProducto, $pais, $nombrePais, $identificadorCreacionRequisitoComercio));
			
			//Revisar si el elemento ingresado es un grupo
			if($categoriaPais == 5){
				//Obtener el listado de localizaciones del grupo
				$listaPaisesGrupo = $cc->obtenerLocalizacionesGrupo($conexion, $pais);
				
				while($paisesGrupo = pg_fetch_assoc($listaPaisesGrupo)){
					if(pg_num_rows($cr->buscarProductoPais($conexion, $producto, $paisesGrupo['id_localizacion']))==0){
						$nombrePaisGrupo = pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $paisesGrupo['id_localizacion']), 0, 'nombre');
						$idRequisitoComercioGrupo = pg_fetch_row($cr -> guardarRequisitoComercio($conexion, $tipo, $producto, $nombreProducto, $paisesGrupo['id_localizacion'], $nombrePaisGrupo, $identificadorCreacionRequisitoComercio));
						
						/**AUDITORIA***/
						
						$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
						$qTransaccion = $ca ->guardarTransaccion($conexion,$idRequisitoComercioGrupo[0] , pg_fetch_result($qLog, 0, 'id_log'));
						$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha creado el requisito de comercialización con el producto '.$nombreProducto.' código '.$producto.' al país '.$nombrePaisGrupo.' código '. $paisesGrupo['id_localizacion']);
						
						/**FIN AUDITORIA***/
						
						$lineasImprimirGrupo .= $cr->imprimirLineaProductoPais($idRequisitoComercioGrupo[0], $nombrePaisGrupo, $paisesGrupo['id_localizacion'], $nombreProducto);
					}
				}
			}
			
			/**AUDITORIA***/
				
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion,$idRequisitoComercio[0] , pg_fetch_result($qLog, 0, 'id_log'));
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha creado el requisito de comercialización con el producto '.$nombreProducto.' código '.$producto.' al país '.$nombrePais.' código '.$pais);
				
			/**FIN AUDITORIA***/
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirLineaProductoPais($idRequisitoComercio[0], $nombrePais, $pais, $nombreProducto) . $lineasImprimirGrupo;
		}else{
			$mensaje['mensaje'] = 'El producto y país elegidos ya han sido registrados.';
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
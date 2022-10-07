<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorAuditoria.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$idRequisitoComercio = htmlspecialchars ($_POST['idRequisitoComercio'],ENT_NOQUOTES,'UTF-8');
	$tipoRequisito = htmlspecialchars ($_POST['tipoRequisito'],ENT_NOQUOTES,'UTF-8');
	$requisito = htmlspecialchars ($_POST['requisito'],ENT_NOQUOTES,'UTF-8');
	$nombreRequisito = htmlspecialchars ($_POST['nombreRequisito'],ENT_NOQUOTES,'UTF-8');
	$identificadorCreacionRequisitoAsignado = $_SESSION['usuario'];
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$ca = new ControladorAuditoria();
		$cc = new ControladorCatalogos();
		
		if(pg_num_rows($cr->buscarPaisRequisito($conexion, $idRequisitoComercio, $requisito, $tipoRequisito)) == 0){
			
			$requisitoAsignado = pg_fetch_row($cr->guardarNuevoRequisitoAsignado($conexion, $idRequisitoComercio, $requisito, $tipoRequisito, 'activo', $identificadorCreacionRequisitoAsignado));
			
			//Países hijos
			
			$datosRequisitoComercio = pg_fetch_assoc($cr->abrirRequisitosComercio($conexion, $idRequisitoComercio));
			$pais = pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $datosRequisitoComercio['id_localizacion']), 0, 'id_localizacion');
			$categoriaPais = pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $datosRequisitoComercio['id_localizacion']), 0, 'categoria');
			
			//Revisar si el elemento ingresado es un grupo
			if($categoriaPais == 5){
				//Obtener el listado de localizaciones del grupo
				$listaPaisesGrupo = $cc->obtenerLocalizacionesGrupo($conexion, $pais);
				
				while($paisesGrupo = pg_fetch_assoc($listaPaisesGrupo)){
					//Obtener los requisitos de comercio de todos los paises con el id localizacion y su producto para actualizar los datos
					$idRequisitoComercioGrupo = pg_fetch_result($cr->buscarRequisitosComercioXPaisProducto($conexion, $paisesGrupo['id_localizacion'], $datosRequisitoComercio['id_producto']), 0, 'id_requisito_comercio');
					
					if(pg_num_rows($cr->buscarPaisRequisito($conexion, $idRequisitoComercioGrupo, $requisito, $tipoRequisito)) == 0){
						
						$requisitoAsignadoGrupo = pg_fetch_row($cr->guardarNuevoRequisitoAsignado($conexion, $idRequisitoComercioGrupo, $requisito, $tipoRequisito, 'activo', $identificadorCreacionRequisitoAsignado));
						
						/*AUDITORIA*/
						
						$qTransaccion = $ca -> buscarTransaccion($conexion, $idRequisitoComercioGrupo, $_SESSION['idAplicacion']);
						$transaccion = pg_fetch_assoc($qTransaccion);
						
						if($transaccion['id_transaccion'] == ''){
							$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
							$qTransaccion = $ca ->guardarTransaccion($conexion, $idRequisitoComercioGrupo, pg_fetch_result($qLog, 0, 'id_log'));
						}
						
						$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asignado el requisito de '.$tipoRequisito.' '.$nombreRequisito.' con código '.$requisito);
						
						/*FIN AUDITORIA*/
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = $cr->imprimirLineaRequisito($idRequisitoComercio, $requisito, $nombreRequisito, $tipoRequisito, 'activo');
					}
				}
			}
			
			
			/*AUDITORIA*/
				
			$qTransaccion = $ca -> buscarTransaccion($conexion, $idRequisitoComercio, $_SESSION['idAplicacion']);
			$transaccion = pg_fetch_assoc($qTransaccion);
				
			if($transaccion['id_transaccion'] == ''){
				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
				$qTransaccion = $ca ->guardarTransaccion($conexion, $idRequisitoComercio, pg_fetch_result($qLog, 0, 'id_log'));
			}
				
			$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asignado el requisito de '.$tipoRequisito.' '.$nombreRequisito.' con código '.$requisito);
			
			/*FIN AUDITORIA*/
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirLineaRequisito($idRequisitoComercio, $requisito, $nombreRequisito, $tipoRequisito, 'activo');
		}else{
			$mensaje['mensaje'] = 'El requisito elegido ya ha sido asignado.';
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
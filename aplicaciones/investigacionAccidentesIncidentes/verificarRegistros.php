<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';
require_once '../../clases/ControladorVacaciones.php';

$conexion = new Conexion();
$cai = new ControladorAccidentesIndicentes();
$cv = new ControladorVacaciones();

try {
	switch ($_POST['opcion']) {
		case 1:
					$identificador = $_POST['identificadorRegistro'];
					
					$idArea=$cv->devolverJefeImnediato($conexion, $identificador);
					if($idArea['idarea'] == 'DGATH' )
						$consulta=$cai->listarDatosAccidente($conexion,'', $_POST['estadoSolicitud'],$id_area_padre=NULL,
								$tipo_Sso=NULL,'',$_POST['solicitud'],$_POST['identificador'],$_POST['fechaBusque']);
					
					else $consulta=$cai->listarDatosAccidente($conexion,'', 'Subsanar',$idArea['idArea'],
							$tipo_Sso=NULL, 1,$_POST['solicitud'], $_POST['identificador'],$_POST['fechaBusque']);
					
					
					if(pg_num_rows($consulta) != 0){
						$return = array(
								'confirmar'=>'ok'
									);
					}else{
					
						$return = array('error'=>'No existen registros..!!!');
					}
		break;
		case 2:
					$res =$cai->reporteAccidenteIncidente($conexion,$_POST['zona'],$_POST['identificador'],$_POST['estadoSolicitud'],$_POST['fechaDesde'],$_POST['fechaHasta']);
					
					if(pg_num_rows($res) != 0){
						$return = array(
								'confirmar'=>'ok'
						);
					}else{
					
						$return = array('error'=>'No existen registros..!!!');
					}
		break;
		case 3: 
					if($_POST['idArea'] == 'DGATH' ){
						$consulta=$cai->listarDatosAccidente($conexion,'', $_POST['estadoSolicitud'],'',
								$tipo_Sso=NULL,$_POST['nivel'],$_POST['solicitud'], $_POST['identificador'],$_POST['fechaBusque']);
					}else {
						$consulta=$cai->listarDatosAccidente($conexion,'','',$_POST['idArea'],
								$tipo_Sso=NULL, $_POST['nivel'],$_POST['solicitud'], $_POST['identificador'],$_POST['fechaBusque'],'');
					}
					
					if(pg_num_rows($consulta) != 0){
						$return = array('confirmar'=>'ok');
					}else{
						$return = array('error'=>'No existen registros..!!!');
					}
					
		break;
				default:
					$return = array('error'=>'Error desconocido..!!!');
		break;
	}

} catch (Exception $e) {
	$return = array('error'=>'No existe ningun registro..!!!');
} finally {
	$conexion->desconectar();
	die(json_encode($return));
}

?>
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierFertilizante.php';

$ce = new ControladorEnsayoEficacia();
$cf=new ControladorDossierFertilizante();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{

	$opcionActualizar=$_POST['opcionActualizar'];
	$id_solicitud=$_POST['id_solicitud'];
	$esGuardar=true;
	try {
		$conexion = new Conexion();
		switch($opcionActualizar){

			case "guardarTipoClasificacion":
				$clasificacion=htmlspecialchars ($_POST['clasificacion'],ENT_NOQUOTES,'UTF-8');
				$subTipoProducto=htmlspecialchars ($_POST['id_subtipo_producto'],ENT_NOQUOTES,'UTF-8');
				$resultado=$cf->agregarClasificacion($conexion,$id_solicitud,$clasificacion,$subTipoProducto);

				if($resultado['tipo']=='insert'){

					$mensaje['mensaje'] = $cf->imprimirLineaTipoClasificacion($resultado['resultado']['id_solicitud_clasificacion'],$resultado['resultado']['nombre'],$resultado['resultado']['codigo'],$resultado['resultado']['sub_tipo_producto']);
				}
				else
					$mensaje['mensaje']='Registro a sido actualizado';
				break;

			case "eliminarTipoClasificacion":
				$id_solicitud_clasificacion=$_POST['id_solicitud_clasificacion'];
				$cf->eliminarClasificacion($conexion,$id_solicitud_clasificacion);
				$mensaje['mensaje'] = $id_solicitud_clasificacion;
				break;

			case "guardarCultivo":
				$idCultivo=htmlspecialchars ($_POST['cultivoNomCien'],ENT_NOQUOTES,'UTF-8');
				$resultado=$cf->agregarCultivo($conexion,$id_solicitud,$idCultivo);

				if($resultado['tipo']=='insert'){
					$nombre=$resultado['resultado']['nombre_comun'].' (<i>'.$resultado['resultado']['nombre_cientifico'].'</i>)';
					$mensaje['mensaje'] = $cf->imprimirLineaCultivo($resultado['resultado']['id_solicitud_cultivo'],$nombre);
				}
				else
					$mensaje['mensaje']='Registro a sido actualizado';
				break;

			case "eliminarCultivo":
				$id_solicitud_cultivo=$_POST['id_solicitud_cultivo'];
				$cf->eliminarCultivo($conexion,$id_solicitud_cultivo);
				$mensaje['mensaje'] = $id_solicitud_cultivo;
				break;

			

		}
		if($esGuardar){
			$mensaje['estado'] = 'exito';

		}
		$conexion->desconectar();

		echo json_encode($mensaje);

	}
	catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}
catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}

?>


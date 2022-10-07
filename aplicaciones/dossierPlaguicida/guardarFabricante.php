<?php
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorDossierPlaguicida.php';

$cg = new ControladorDossierPlaguicida();
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$paso=$_POST['paso_opcion'];
	$esGuardar=true;
	try {
		$conexion = new Conexion();
		switch($paso){
			case "guardar":
				$id_solicitud=htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');				
				$tipo_fabricante=htmlspecialchars ($_POST['tipo_fabricante'],ENT_NOQUOTES,'UTF-8');
				$nombre=htmlspecialchars ($_POST['fabricante_nombre'],ENT_NOQUOTES,'UTF-8');
				$id_pais=htmlspecialchars ($_POST['fabricante_pais'],ENT_NOQUOTES,'UTF-8');
				$direccion=htmlspecialchars ($_POST['fabricante_direccion'],ENT_NOQUOTES,'UTF-8');
				$representante_legal=htmlspecialchars ($_POST['fabricante_representante'],ENT_NOQUOTES,'UTF-8');
				$correo=htmlspecialchars ($_POST['fabricante_correo'],ENT_NOQUOTES,'UTF-8');
				$telefono=htmlspecialchars ($_POST['fabricante_telefono'],ENT_NOQUOTES,'UTF-8');
				$carta=htmlspecialchars ($_POST['fabricante_carta'],ENT_NOQUOTES,'UTF-8');
				
				$resultado = $cg->actualizarFabricante($conexion,$id_solicitud,$tipo_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono,$carta);

				if($resultado['tipo']=='insert'){
					$item=$cg->obtenerFabricante($conexion,$resultado['id_solicitud_fabricante']);
					$mensaje['mensaje'] = $cg->imprimirLineaFabricante($item);
				}
				else
					$mensaje['mensaje']='Registro a sido actualizado';
				break;
			case "guardarManufacturador":
				$id_solicitud_fabricante = htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');				
				$nombre=htmlspecialchars ($_POST['manufacturador_nombre'],ENT_NOQUOTES,'UTF-8');
				$id_pais = htmlspecialchars ($_POST['manufacturador_pais'],ENT_NOQUOTES,'UTF-8');
				$direccion = htmlspecialchars ($_POST['manufacturador_direccion'],ENT_NOQUOTES,'UTF-8');
				$representante_legal = htmlspecialchars ($_POST['manufacturador_representante'],ENT_NOQUOTES,'UTF-8');
				$correo = htmlspecialchars ($_POST['manufacturador_correo'],ENT_NOQUOTES,'UTF-8');
				$telefono = htmlspecialchars ($_POST['manufacturador_telefono'],ENT_NOQUOTES,'UTF-8');
				$resultado = $cg->agregarManufacturador($conexion,$id_solicitud_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono);

				if($resultado['tipo']=='insert'){
					$item=$cg->obtenerManufacturador($conexion,$resultado['id_solicitud_manufacturador']);
					$mensaje['mensaje'] = $cg->imprimirLineaManufacturador($item);
				}
				else
					$mensaje['mensaje']='Registro a sido actualizado';
				break;
			
			case "borrar":	//Elimina items de las tablas
				$id_solicitud_fabricante=htmlspecialchars ($_POST['id_solicitud_fabricante'],ENT_NOQUOTES,'UTF-8');				
				$cg->eliminarFabricante($conexion,$id_solicitud_fabricante);
				$mensaje['mensaje'] = $id_solicitud_fabricante;
				break;
				
			case "borrarManufacturador":	//Elimina items de las tablas				
				$id_solicitud_manufacturador=htmlspecialchars ($_POST['id_solicitud_manufacturador'],ENT_NOQUOTES,'UTF-8');				
				$cg->eliminarManufacturador($conexion,$id_solicitud_manufacturador);
				$mensaje['mensaje'] = $id_solicitud_manufacturador;
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


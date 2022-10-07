<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$ruta = 'programasControlOficial';

try{
	$conexion = new Conexion();
	$cpco = new ControladorProgramasControlOficial();
	
	$identificador = $_SESSION['usuario'];
	
	$idCatastroPredioEquidos = htmlspecialchars ($_POST['idCatastroPredioEquidos'],ENT_NOQUOTES,'UTF-8');
	
	$idEspecie = htmlspecialchars ($_POST['especie'],ENT_NOQUOTES,'UTF-8');
	$nombreEspecie = htmlspecialchars ($_POST['nombreEspecie'],ENT_NOQUOTES,'UTF-8');
	$idRaza = htmlspecialchars ($_POST['raza'],ENT_NOQUOTES,'UTF-8');
	$nombreRaza = htmlspecialchars ($_POST['nombreRaza'],ENT_NOQUOTES,'UTF-8');
	$idCategoria = htmlspecialchars ($_POST['categoria'],ENT_NOQUOTES,'UTF-8');
	$nombreCategoria = htmlspecialchars ($_POST['nombreCategoria'],ENT_NOQUOTES,'UTF-8');
	$numeroAnimales = htmlspecialchars ($_POST['numeroAnimales'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$res = $cpco->buscarEspeciePredioEquidos($conexion, $idCatastroPredioEquidos, 
														$nombreEspecie, $nombreRaza, $nombreCategoria);
		if(pg_num_rows($res) == 0){
			$conexion->ejecutarConsulta("begin;");
			
			$idEspeciePredioEquidos = pg_fetch_result($cpco->nuevaEspeciePredioEquidos($conexion, $idCatastroPredioEquidos, 
													$identificador, $idEspecie, $nombreEspecie, $idRaza, $nombreRaza, 
													$idCategoria, $nombreCategoria, $numeroAnimales), 
													0, 'id_catastro_predio_equidos_especie');
			$conexion->ejecutarConsulta("commit;");
			
		}else{
			$especie = pg_fetch_assoc($res);
			
			$numeroAnimales = pg_fetch_result($cpco->actualizarEspeciePredioEquidos($conexion, $especie['id_catastro_predio_equidos_especie'],
			                                             $numeroAnimales), 0, 'numero_animales');
			
			$conexion->ejecutarConsulta("commit;");
			
			$especiePredioEquidosConsulta = $cpco->listarEspeciePredioEquidos($conexion, $idCatastroPredioEquidos);

		}
		
		$especiePredioEquidosConsulta = $cpco->listarEspeciePredioEquidos($conexion, $idCatastroPredioEquidos);
		
		while ($especie = pg_fetch_assoc($especiePredioEquidosConsulta)){
		    $lista .=  $cpco->imprimirLineaEspeciePredioEquidosConsulta($especie['id_catastro_predio_equidos_especie'], $idCatastroPredioEquidos,
		        $especie['nombre_especie'], $especie['nombre_raza'], $especie['nombre_categoria'],
		        $especie['numero_animales'], $ruta);
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $lista;
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$conexion->desconectar();
	}
		
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$conexion->desconectar();
}
?>
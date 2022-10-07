<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';


$idLote = htmlspecialchars($_POST['idLote'], ENT_NOQUOTES, 'UTF-8');
$codigoLote = htmlspecialchars($_POST['codigoLotes'], ENT_NOQUOTES, 'UTF-8');
$codigoComprobacion = htmlspecialchars($_POST['codigoComprobacion'], ENT_NOQUOTES, 'UTF-8');
$cantidad = htmlspecialchars($_POST['cantidadLoteh'], ENT_NOQUOTES, 'UTF-8');
$idTipoLote = htmlspecialchars($_POST['loteTipo'], ENT_NOQUOTES, 'UTF-8');
$tipo = htmlspecialchars($_POST['nLoteTipo'], ENT_NOQUOTES, 'UTF-8');
$nPais = htmlspecialchars($_POST['idPaisDestino'], ENT_NOQUOTES, 'UTF-8');
$idPais = htmlspecialchars($_POST['paisDestino'], ENT_NOQUOTES, 'UTF-8');
$tipoProdu = htmlspecialchars($_POST['tipoProducto'], ENT_NOQUOTES, 'UTF-8');
$descri = htmlspecialchars($_POST['descripcionLote'], ENT_NOQUOTES, 'UTF-8');
$idRegistro = $_POST["idRegistro"]; 
$operador= $_POST['idUsuario'];
$idProducto= htmlspecialchars($_POST['idProducto2'], ENT_NOQUOTES, 'UTF-8');
$nProducto = htmlspecialchars ($_POST['nProducto'],ENT_NOQUOTES,'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cl = new ControladorLotes();
	
	try{
		
		$valor=0;
		
		if(stristr($nProducto, 'cacao') === FALSE) {
			
		} else{
			
			if($codigoLote==$codigoComprobacion){
				$valor=0;
			} else{
				$valor=pg_num_rows($cl->comprobarCodigo($conexion,$operador,$codigoLote,$idProducto));				
			}
		}
			
		if($valor==0){
			$cl->actualizarLote($conexion,$idLote,$codigoLote,$cantidad,$idTipoLote,$tipo,$idPais,$nPais,$tipoProdu,$descri);
			
			$registro = $cl->ObtenerRegistrosConformados($conexion, $idLote,$operador);
			while($fila=pg_fetch_assoc($registro)){
				$cl->estadoRegistro($conexion, 1, $fila['id_registro']);
			}
			
			$cl->eliminarDetalleLote($conexion,$idLote);
			
			for($i=0; $i< count($idRegistro);$i++){
				$cl->guardarDetalleLote($conexion, $idLote, $idRegistro[$i]);
				$cl->estadoRegistro($conexion, 2, $idRegistro[$i]);
			}	
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";			
		
		} else {
			$mensaje['mensaje'] = "El Código de Lote (".$codigoLote.") ya se encuentra registrado";
		}

	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}



?>


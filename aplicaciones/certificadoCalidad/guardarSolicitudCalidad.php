<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificadoCalidad.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	//Datos generales
	
	$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
	$razonSocial = htmlspecialchars ($_POST['razonSocial'],ENT_NOQUOTES,'UTF-8');
	$nombreImportador = htmlspecialchars ($_POST['nombreImportador'],ENT_NOQUOTES,'UTF-8');
	$direccionImportador = htmlspecialchars ($_POST['direccionImportador'],ENT_NOQUOTES,'UTF-8');	
	$fechaEmbarque = htmlspecialchars ($_POST['fechaEmbarque'],ENT_NOQUOTES,'UTF-8');
	$numeroTransporte = htmlspecialchars ($_POST['numeroTransporte'],ENT_NOQUOTES,'UTF-8');
	$medioTransporte = htmlspecialchars ($_POST['medioTransporte'],ENT_NOQUOTES,'UTF-8');
	$idPaisEmbarque = htmlspecialchars ($_POST['paisEmbarque'],ENT_NOQUOTES,'UTF-8');
	$nombrePaisEmbarque = htmlspecialchars ($_POST['nombrePaisEmbarque'],ENT_NOQUOTES,'UTF-8');
	$idPuertoEmbarque = htmlspecialchars ($_POST['puertoEmbarque'],ENT_NOQUOTES,'UTF-8');
	$nombrePuertoEmbarque = htmlspecialchars ($_POST['nombrePuertoEmbarque'],ENT_NOQUOTES,'UTF-8');
	$idPaisDestino = htmlspecialchars ($_POST['paisDestino'],ENT_NOQUOTES,'UTF-8');
	$nombrePaisDestino = htmlspecialchars ($_POST['nombrePaisDestino'],ENT_NOQUOTES,'UTF-8');
	$idPuertoDestino = htmlspecialchars ($_POST['puertoDestino'],ENT_NOQUOTES,'UTF-8');
	$nombrePuertoDestino = htmlspecialchars ($_POST['nombrePuertoDestino'],ENT_NOQUOTES,'UTF-8');
	
	//Datos de lugar inspección
	$idProvincia = $_POST['idProvincia'];	
	$nombreProvincia = $_POST['nombreProvincia'];	
	$idAreaInspeccion = $_POST['idLugarInspeccion'];	
	$nombreLugarInspeccion = $_POST['nombreLugarInspeccion'];	
	$fechaHoraInspeccion = $_POST['fechaHoraInspeccion'];
	
	//Datos producto inspeccion
	$numeroLote = $_POST['iNumeroLote'];
	$idLoteInspeccion = $_POST['idLoteInspeccion'];
	$idProducto = $_POST['iProducto'];
	$nombreProducto = $_POST['iNombreProducto'];
	$valorFob = $_POST['iValorFob'];
	$pesoNeto = $_POST['iPesoNeto'];
	$unidadNeto = $_POST['iUnidadNeto'];
	$pesoBruto = $_POST['iPesoBruto'];
	$unidadBruto = $_POST['iUnidadBruto'];
	$idVariedad = $_POST['iVariedad'];
	$nombreVariedad = $_POST['iNombreVariedad'];
	$idCalidad = $_POST['iCalidad'];
	$nombreCalidad = $_POST['iNombreCalidad'];
	
	$estado = 'enviado';
		
	try {
		$conexion = new Conexion();
		$cc = new ControladorCertificadoCalidad();
		
		$idCertificado = pg_fetch_assoc($cc->buscarTipoCertificado($conexion, 'CERT_CALIDAD', 'SV'));
		
		
		$idCertificadoCalidad = $cc->guardarCertificadoCalidad($conexion, $idCertificado['id_certificado'], $identificadorOperador, $razonSocial, $nombreImportador, $direccionImportador, 
																$fechaEmbarque, $numeroTransporte, $idPuertoEmbarque, $nombrePuertoEmbarque, $medioTransporte, 
																$idPaisDestino, $nombrePaisDestino, $idPuertoDestino, $nombrePuertoDestino, $idPaisEmbarque, $nombrePaisEmbarque);
		
		for ($i = 0; $i < count ($idProvincia); $i++) {
			
			$idLugarInspeccion = $cc->guardarLugarInspeccion($conexion, pg_fetch_result($idCertificadoCalidad, 0, 'id_certificado_calidad'), $fechaHoraInspeccion[$i], 
															$idProvincia[$i], $nombreProvincia[$i], $idAreaInspeccion[$i], $nombreLugarInspeccion[$i]);
			
			for ($j = 0; $j < count ($idLoteInspeccion); $j++) {
				if($idProvincia[$i].$idAreaInspeccion[$i] == $idLoteInspeccion[$j]){
					$cc->guardarLotesInspeccion($conexion, pg_fetch_result($idLugarInspeccion, 0, 'id_lugar_inspeccion'), $numeroLote[$j], $idProducto[$j], 
																		   $nombreProducto[$j], $pesoBruto[$j], $unidadBruto[$j], $pesoNeto[$j], $unidadNeto[$j], 
																		   $idVariedad[$j], $nombreVariedad[$j], ($idCalidad[$j]!=''?$idCalidad[$j]:0), $nombreCalidad[$j], $valorFob[$j], $estado);
				}
				
			}
			
		}
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Se ha ingresado la información correctamente";
		

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


		
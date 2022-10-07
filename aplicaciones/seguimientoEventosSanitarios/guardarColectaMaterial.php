<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

$ruta = 'seguimientoEventosSanitarios';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
	
	$identificador = $_SESSION['usuario'];
	
	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	
	$colectaMaterial = htmlspecialchars ($_POST['colectaMaterial'],ENT_NOQUOTES,'UTF-8');
	
	$numeroVisita = htmlspecialchars ($_POST['numeroVisita'],ENT_NOQUOTES,'UTF-8');
	$razonesMuestra = htmlspecialchars ($_POST['razonesMuestra'],ENT_NOQUOTES,'UTF-8');		
	$laboratorioMuestra = htmlspecialchars ($_POST['laboratorioMuestra'],ENT_NOQUOTES,'UTF-8');	
	$nombreLaboratorioMuestra = htmlspecialchars ($_POST['nombreLaboratorioMuestra'],ENT_NOQUOTES,'UTF-8');
	
	$anexo = htmlspecialchars ($_POST['archivoAnexo'],ENT_NOQUOTES,'UTF-8');
	
	$especieMuestra = $_POST['arrayEspecieMuestra'];
	$nombreEspecieMuestra = $_POST['arrayNombreEspecieMuestra'];
	$pruebasMuestra = $_POST['arrayPruebasMuestra'];
	$nombrePruebasMuestra = $_POST['arrayNombrePruebasMuestra'];
	$tipoMuestra = $_POST['arrayTipoMuestra'];
	$nombreTipoMuestra = $_POST['arrayNombreTipoMuestra'];
	$numeroMuestras = $_POST['arrayNumeroMuestras'];
	$fechaColectaMuestra = $_POST['arrayFechaColectaMuestra'];
	$horaColectaMuestra = $_POST['arrayHoraColectaMuestra'];
	$fechaEnvioMuestra = $_POST['arrayFechaEnvioMuestra'];
	$horaEnvioMuestra = $_POST['arrayHoraEnvioMuestra'];
	
	try{
		if(($identificador != null) || ($identificador != '')){
			
			$conexion->ejecutarConsulta("begin;");
			
			if($colectaMaterial == 'Si'){
				$muestrasExistentes = pg_num_rows($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, $numeroVisita));
				
				if($muestrasExistentes == 0){
					$idMuestra = pg_fetch_result($cpco->nuevaMuestra(	$conexion, $idEventoSanitario, $numeroVisita, $colectaMaterial, 
															$razonesMuestra,  
															$laboratorioMuestra, $nombreLaboratorioMuestra,
															$identificador, $anexo), 0, 'id_muestras');
					
					for ($i = 0; $i < count ($especieMuestra); $i++) {
						$cpco->nuevaDetalleMuestras(	$conexion, $idMuestra, $idEventoSanitario, $especieMuestra[$i], $nombreEspecieMuestra[$i],
								$pruebasMuestra[$i], $nombrePruebasMuestra[$i], $tipoMuestra[$i], $nombreTipoMuestra[$i], $numeroMuestras[$i],
								$fechaColectaMuestra[$i], $horaColectaMuestra[$i], $fechaEnvioMuestra[$i], $horaEnvioMuestra[$i], $identificador, $numeroVisita);
					}
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Se han guardado los datos';
					
				}else{
					$muestra = pg_fetch_assoc($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, $numeroVisita));
					
					if($muestra['colecta_material'] == 'Si'){
						//update
						/*$idMuestra = pg_fetch_result($cpco->actualizarMuestra(	$conexion, $idEventoSanitario, $numeroVisita, $colectaMaterial,
								$razonesMuestra, $pruebasMuestra,
								$laboratorioMuestra, $nombreLaboratorioMuestra,
								$identificador), 0, 'id_muestras');*/
							
						
						for ($i = 0; $i < count ($especieMuestra); $i++) {
							
							$detalle = pg_num_rows($cpco->buscarMuestrasDetalle($conexion, $idEventoSanitario, $especieMuestra[$i], $pruebasMuestra[$i], $tipoMuestra[$i], $numeroVisita));
							
							if($detalle == 0){
								$cpco->nuevaDetalleMuestras(	$conexion, $muestra['id_muestras'], $idEventoSanitario, $especieMuestra[$i], $nombreEspecieMuestra[$i],
										$pruebasMuestra[$i], $nombrePruebasMuestra[$i], $tipoMuestra[$i], $nombreTipoMuestra[$i], $numeroMuestras[$i],
										$fechaColectaMuestra[$i], $horaColectaMuestra[$i], $fechaEnvioMuestra[$i], $horaEnvioMuestra[$i], $identificador, $numeroVisita);
								
								$mensaje['estado'] = 'exito';
								$mensaje['mensaje'] = 'Se han guardado los datos del detalle de muestras';
							}else{
								$mensaje['estado'] = 'error';
								$mensaje['mensaje'] = 'Ya existe la información ingresada.';
							}
						}
						
						
					}else{
						
						//borrar registro y poner nuevos datos
						/*$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'No puede agregar más detalles de muestras ';*/
						
						$idMuestra = pg_fetch_result($cpco->actualizarMuestra(	$conexion, $idEventoSanitario, $numeroVisita, $colectaMaterial,
								$razonesMuestra, $pruebasMuestra,
								$laboratorioMuestra, $nombreLaboratorioMuestra,
								$identificador), 0, 'id_muestras');
							
						
						for ($i = 0; $i < count ($especieMuestra); $i++) {
								
							$detalle = pg_num_rows($cpco->buscarMuestrasDetalle($conexion, $idEventoSanitario, $especieMuestra[$i], $pruebasMuestra[$i], $tipoMuestra[$i], $numeroVisita));
								
								$cpco->nuevaDetalleMuestras(	$conexion, $muestra['id_muestras'], $idEventoSanitario, $especieMuestra[$i], $nombreEspecieMuestra[$i],
										$pruebasMuestra[$i], $nombrePruebasMuestra[$i], $tipoMuestra[$i], $nombreTipoMuestra[$i], $numeroMuestras[$i],
										$fechaColectaMuestra[$i], $horaColectaMuestra[$i], $fechaEnvioMuestra[$i], $horaEnvioMuestra[$i], $identificador, $numeroVisita);
							
						}
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Se han guardado los datos del detalle de muestras';
					}
				}
			}else{
				$muestrasExistentes = pg_num_rows($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, $numeroVisita));
				$detalleMuestrasExistentes = pg_num_rows($cpco->listarMuestrasDetallePorVisita($conexion, $idEventoSanitario, $numeroVisita));
				
				//
				$pruebasMuestra = 'No Aplica';
				$laboratorioMuestra = 0;
				$nombreLaboratorioMuestra = 'No Aplica';
				$anexo = '';
				
				$especieMuestra[0] = 0;
				$nombreEspecieMuestra[0] = 'No Aplica';
				$pruebasMuestra[0] = 0;
				$nombrePruebasMuestra[0] = 'No Aplica';
				$tipoMuestra[0] = 0;
				$nombreTipoMuestra[0] = 'No Aplica';
				$numeroMuestras[0] = 0;
				$fechaColectaMuestra[0] = 'now()';
				$horaColectaMuestra[0] = '00:00';
				$fechaEnvioMuestra[0] = 'now()';
				$horaEnvioMuestra[0] = '00:00';
				
				if($muestrasExistentes == 0){
					$idMuestra = pg_fetch_result($cpco->nuevaMuestra($conexion, $idEventoSanitario, $numeroVisita, $colectaMaterial,
							$razonesMuestra, 
							$laboratorioMuestra, $nombreLaboratorioMuestra,
							$identificador, $anexo), 0, 'id_muestras');
						
					for ($i = 0; $i < count (1); $i++) {
						$cpco->nuevaDetalleMuestras(	$conexion, $idMuestra, $idEventoSanitario, $especieMuestra[$i], $nombreEspecieMuestra[$i],
								$pruebasMuestra[$i], $nombrePruebasMuestra[$i], $tipoMuestra[$i], $nombreTipoMuestra[$i], $numeroMuestras[$i],
								$fechaColectaMuestra[$i], $horaColectaMuestra[$i], $fechaEnvioMuestra[$i], $horaEnvioMuestra[$i], $identificador, $numeroVisita);
					}
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Se han guardado los datos';
				}else{
					$muestra = pg_fetch_assoc($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, $numeroVisita));
							
					if($detalleMuestrasExistentes == 0){
						for ($i = 0; $i < count (1); $i++) {
							$cpco->nuevaDetalleMuestras(	$conexion, $muestra['id_muestras'], $idEventoSanitario, $especieMuestra[$i], $nombreEspecieMuestra[$i],
									$pruebasMuestra[$i], $nombrePruebasMuestra[$i], $tipoMuestra[$i], $nombreTipoMuestra[$i], $numeroMuestras[$i],
									$fechaColectaMuestra[$i], $horaColectaMuestra[$i], $fechaEnvioMuestra[$i], $horaEnvioMuestra[$i], $identificador, $numeroVisita);
						}
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Se han guardado los datos del detalle de muestras';
					}else{
						
						$muestra = pg_fetch_assoc($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, $numeroVisita));
							
						$cpco->actualizarMuestra(	$conexion, $idEventoSanitario, $numeroVisita, $colectaMaterial,
								$razonesMuestra, $pruebasMuestra,
								$laboratorioMuestra, $nombreLaboratorioMuestra,
								$identificador);
						
						for ($i = 0; $i < count (1); $i++) {
							$cpco->nuevaDetalleMuestras(	$conexion, $muestra['id_muestras'], $idEventoSanitario, $especieMuestra[$i], $nombreEspecieMuestra[$i],
									$pruebasMuestra[$i], $nombrePruebasMuestra[$i], $tipoMuestra[$i], $nombreTipoMuestra[$i], $numeroMuestras[$i],
									$fechaColectaMuestra[$i], $horaColectaMuestra[$i], $fechaEnvioMuestra[$i], $horaEnvioMuestra[$i], $identificador, $numeroVisita);
						}
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Se han guardado los datos del detalle de muestras';
					}
				}
			}
			
			$conexion->ejecutarConsulta("commit;");
			
			$conexion->desconectar();			
	
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Su sesión ha expirado, por favor ingrese nuevamente al sistema para continuar.';
		
			$conexion->desconectar();
		}
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
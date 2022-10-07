<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/Constantes.php';
require_once '../general/phpqrcode/qrlib.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$archivo= $_POST['archivo'];
$opcion=$_POST['opcion'];

try{

	$identificador = $_POST['identificadorEmpleado'];
	$apellido= strtoupper ($_POST['apellidoEmpleado']);
	$nombre = strtoupper ($_POST['nombreEmpleado']); 
	$tipo_documento= $_POST['tipoDocumento'];
	$nacionalidad= $_POST['nacionalidad'];
	$genero=$_POST['genero'];
	$estado_civil=$_POST['estadoCivil'];
	$cedula_militar=$_POST['cedulaMilitar'];
	$fecha_nacimiento=$_POST['fechaNacimiento'];
	$edad=$_POST['edad'];
	$tipo_sangre=$_POST['tipoSangre'];
	$identificacion_etnica=$_POST['identificacionEtnica'];
	$nacionalidad_indigena=$_POST['nacionalidadIndigena'];
	$fotografia=$archivo;
	$extension=$_POST['extension'];
	$domicilio=$_POST['domicilio'];
	$convencional=$_POST['convencional'];
	$celular=$_POST['celular'];
	$mail_personal=$_POST['mailPersonal'];
	$mail_institucional=$_POST['mailInstitucional'];
	$discapacidad_empleado = $_POST['discapacidad_empleado'];
	$carnet_conadis_empleado = $_POST['carnet_conadis_empleado'];
	$representante_discapacitado = $_POST['representante_discapacitado'];
	$carnet_conadis_familiar = $_POST['carnet_conadis_familiar'];
	$provincia = $_POST['provincia'];
	$canton = $_POST['canton'];
	$parroquia=$_POST['parroquia'];
	$enfermedad_catastrofica=$_POST['enfermedad_catastrofica'];
	$nombre_enfermedad_catastrofica=$_POST['nombre_enfermedad_catastrofica'];
		
		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				$constgi = new Constantes();				
				$jru = new ControladorReportes();
				
				$rutaPerfilPublico = 'aplicaciones/uath/archivosPerfilPublico/' . md5($identificador) . '.pdf';
				$rutaPerfilPublicoPdf = $constgi::RUTA_DOMINIO . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/uath/archivosPerfilPublico/' . md5($identificador) . '.pdf';
				
				//----Generador de QR------//				
				$nombreQr= md5($identificador).'.png';
				$rutaQr = $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/uath/qrPerfilPublico/' . $nombreQr;
				$rutaQrPerfilPublico = 'aplicaciones/uath/qrPerfilPublico/' . $nombreQr;
				
				$tamanio = 3;
				$level = 'H';
				$frameSize = 3;
				$contenido = $rutaPerfilPublicoPdf;
				
				QRcode::png($contenido, $rutaQr, $level, $tamanio, $frameSize);					
				//----Generador de QR------//
				
				if(strcmp($opcion,"Guardar")==0){
					$cu=new ControladorUsuarios();
					$cu->crearUsuario($conexion, $identificador, md5($identificador));
					$cu->activarCuenta($conexion, $identificador, md5($identificador));
					$cu->crearPerfilUsuario($conexion, $identificador, 'Usuario interno');
					$cc->crearFichaEmpleado($conexion,$identificador,$apellido,$nombre,$tipo_documento,$nacionalidad,$genero,$estado_civil,$cedula_militar,$fecha_nacimiento,$edad,
							$tipo_sangre,$identificacion_etnica,$nacionalidad_indigena,$fotografia,$extension,$domicilio,$convencional,$celular,$mail_personal,$mail_institucional,
							$discapacidad_empleado,$carnet_conadis_empleado,$representante_discapacitado,$carnet_conadis_familiar,$provincia,$canton,$parroquia,
					        $enfermedad_catastrofica,$nombre_enfermedad_catastrofica, $rutaPerfilPublico, $rutaQrPerfilPublico);
				}
				if(strcmp($opcion,"Actualizar")==0){
					$cc->actualizarFichaEmpleado($conexion,$identificador,$apellido,$nombre,$tipo_documento,$nacionalidad,$genero,$estado_civil,$cedula_militar,$fecha_nacimiento,$edad,
							$tipo_sangre,$identificacion_etnica,$nacionalidad_indigena,$fotografia,$extension,$domicilio,$convencional,$celular,$mail_personal,$mail_institucional,
							$discapacidad_empleado,$carnet_conadis_empleado,$representante_discapacitado,$carnet_conadis_familiar,$provincia,$canton,$parroquia,
					        $enfermedad_catastrofica,$nombre_enfermedad_catastrofica, $rutaPerfilPublico, $rutaQrPerfilPublico);
				}								
								
				$ReporteJasper= '/aplicaciones/uath/reportes/perfilPublico.jrxml';
				$salidaReporte= '/aplicaciones/uath/archivosPerfilPublico/' . md5($identificador) . '.pdf';
					
				$qPerfilPublico = $cc->obtenerDatosPerfilPublicoPorIdentificador($conexion, $identificador);
				$perfilPublico = pg_fetch_assoc($qPerfilPublico);
				$rutaFoto = $perfilPublico['fotografia'];
				
				if(trim($rutaFoto) == "" || $rutaFoto == null){				    
				    $rutaFotografia = $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/uath/fotos/foto.png';
				}else{		    
				    $validarRutaFotografia = $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/' . $rutaFoto;				   
				    
				    if (file_exists($validarRutaFotografia)) {
				        $rutaFotografia = $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/' . $rutaFoto;
				    }else{
				        $rutaFotografia = $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/uath/fotos/foto.png';
				    }
				}
				
				$parameters['parametrosReporte'] = array(
				    'identificador' => $identificador,
				    'rutaFotografia' => $rutaFotografia
				);
				
				$jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $salidaReporte, 'perfilPublico');
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
				$conexion->desconectar();
				echo json_encode($mensaje);
				
				
			} catch (Exception $ex){
					pg_close($conexion);
					$error=$ex->getMessage();
					$mensaje['estado'] = 'error';
					$suma_cod_error;
					$error_code=0;
					$suma_cod_error= $error_code + (stristr($error, 'duplicate key')!=FALSE)?1:0;
					$error_code= $error_code + $suma_cod_error;
					$suma_cod_error= $error_code + (stristr($error, 'numero_contrato')!=FALSE)?2:0;
					$error_code= $error_code + $suma_cod_error;
					
					switch($error_code){
						case 0:		$mensaje['mensaje'] = 'No se puede ejecutar la sentencia';
							break;	
						case 3:		$mensaje['mensaje'] = 'Error: Ya existe un contrato con el mismo número';
							break;
					}
					echo json_encode($mensaje);
			}

} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>

<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorEmpleados.php';
require_once '../../clases/ControladorReportes.php';
require_once '../general/phpqrcode/qrlib.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$nombre = strtoupper($_POST['nombre']);
	$apellido = strtoupper($_POST['apellido']);
	$sexo = $_POST['sexo'];
	$estadoCivil = $_POST['estadoCivil'];
	$nacimiento = $_POST['nacimiento'];
	$sangre = $_POST['sangre'];
	$nacionalidad = $_POST['nacionalidad'];
	$etnia = $_POST['etnia'];
	$indigena = $_POST['indigena'];
	$domicilio = $_POST['domicilio'];
	$convencional = $_POST['convencional'];
	$celular = $_POST['celular'];
	$mailPersonal = $_POST['mailPersonal'];
	$mailInstitucional = $_POST['mailInstitucional'];
	$discapacidad_empleado = $_POST['discapacidad_empleado'];
	$carnet_conadis_empleado = $_POST['carnet_conadis_empleado'];
	$representante_discapacitado = $_POST['representante_discapacitado'];
	$carnet_conadis_familiar = $_POST['carnet_conadis_familiar'];
	$provincia = $_POST['provincia'];
	$canton = $_POST['canton'];
	$parroquia = $_POST['parroquia'];
	$enfermedad_catastrofica=$_POST['enfermedad_catastrofica'];
	$nombre_enfermedad_catastrofica=$_POST['nombre_enfermedad_catastrofica'];
	$edad=$_POST['edad'];
	$extension_magap=$_POST['extension'];
	$tipo_documento=$_POST['tipoDocumento'];
	//*************nuevos campos***************************
	$provinciaNacimiento=$_POST['provinciaNacimiento'];
	$cantonNacimiento=$_POST['cantonNacimiento'];
	$parroquiaNacimiento=$_POST['parroquiaNacimiento'];
	$jornadaLaboral=$_POST['jornadaLaboral'];
	$religion=$_POST['religion'];
	$orientacionSexual=$_POST['orientacionSexual'];
	$lateralidad=$_POST['lateralidad'];
	//**********nuevo campo*******************
	$libretaMilitar=$_POST['libretaMilitar'];
	$telefonoInstitucional=$_POST['telefonoInstitucional'];
	$identificador = $_SESSION['usuario'];
	
	try {
		$conexion = new Conexion();
		$cc = new ControladorCatastro();
		$ce = new ControladorEmpleados();
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
		
		$ce->actualizarDatosPersonales($conexion, $identificador, $nombre, $apellido, $sexo, $estadoCivil, $nacimiento,$sangre,$nacionalidad,$etnia,$indigena,$domicilio,$convencional,$celular,$mailPersonal,$mailInstitucional,
		    $discapacidad_empleado,$carnet_conadis_empleado,$representante_discapacitado,$carnet_conadis_familiar,$provincia,$canton,$parroquia,$edad,$enfermedad_catastrofica,$nombre_enfermedad_catastrofica,$extension_magap,$tipo_documento,
		    $provinciaNacimiento,$cantonNacimiento,$parroquiaNacimiento,$jornadaLaboral,$religion,$orientacionSexual,$lateralidad,$libretaMilitar,$telefonoInstitucional, $rutaPerfilPublico, $rutaQrPerfilPublico);
		
		$jru = new ControladorReportes();
		
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
		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		
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
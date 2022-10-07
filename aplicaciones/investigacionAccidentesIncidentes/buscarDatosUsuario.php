<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';
try {

	$conexion = new Conexion();
	$cai = new ControladorAccidentesIndicentes();
	$consulta=$cai->buscarDatosServidor($conexion,$_POST["identificador"]);
	
	if(pg_num_rows($consulta) != 0){
		$valores_datos=pg_fetch_array($consulta);
		$parroquia=pg_fetch_result($cai->obtenerNombreLocalizacion ($conexion, $valores_datos['id_localizacion_parroquia']),0,'nombre');
		$provincia=pg_fetch_result($cai->obtenerNombreLocalizacion ($conexion, $valores_datos['id_localizacion_provincia']),0,'nombre');
		$canton=pg_fetch_result($cai->obtenerNombreLocalizacion ($conexion, $valores_datos['id_localizacion_canton']),0,'nombre');
		$nombre_puesto=pg_fetch_result($cai->obtenerNombrePuesto ($conexion, $valores_datos['identificador']),0,'nombre_puesto');
		
		$return = array(
				'nombre'=>$valores_datos['nombre'].' '.$valores_datos['apellido'],
				'fechaNacimiento'=>$valores_datos['fecha_nacimiento'],
				'edad'=>$valores_datos['edad'],
				'genero'=>$valores_datos['genero'],
				'estadoCivil'=>$valores_datos['estado_civil'],
				'tieneDiscapacidad'=>$valores_datos['tiene_discapacidad'],
				'domicilio'=>$valores_datos['domicilio'],
				'convencional'=>$valores_datos['convencional'],
				'parroquia'=>$parroquia,
				'provincia'=>$provincia,
				'ciudad'=>$canton,
				'nombrePuesto'=>$nombre_puesto,
				'celular'=>$valores_datos['celular']	
				
				);
	}else{
	
		$return = array('error'=>'No existe ningun registro..!!!');
	}
	
} catch (Exception $e) {
	$return = array('error'=>'Error Desconocido..!!!');
} finally {
	$conexion->desconectar();
	die(json_encode($return));
}
?>
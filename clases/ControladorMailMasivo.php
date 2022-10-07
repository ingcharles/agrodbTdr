<?php
class ControladorMailMasivo{
	
	public function guardarMailMasivo ($conexion,$identificador,$correo,$nombreRequerimiento,$operacion,$area,$estado){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO mail_masivo(
	             identificador, correo, nombre_requerimiento,operacion, area, fecha_registro, estado_correo)
	    VALUES ('$identificador','$correo','$nombreRequerimiento', '$operacion','$area',now(),'$estado');");
		return $res;
	}
	
}
?>
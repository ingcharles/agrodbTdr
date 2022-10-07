<?php

/************************** CLASE BASE *****************************/

abstract class ControladorCRC{
	
	private $mensajeDeError;
	
	public abstract function validarCRC($variable);
	
	protected function setMensaje($mensaje){
		$this->mensajeDeError = $mensaje;
	}
	public function getMensaje(){
		return $this->mensajeDeError;
	}
}

/************************** INSTANCIAS *****************************/

class InicioDeSesionCRC extends ControladorCRC{
	public function validarCRC($variable){
		if($variable){
			return true;
		}
		parent::setMensaje('El inicio de sesion no ha podido validar CRC de transacción');
		return false;
		
	}
}

class TransaccionCRC extends ControladorCRC{
	public function validarCRC($variable){
		if($variable){
			return true;
		}
		parent::setMensaje('El CRC para transacción no es válido');
		return false;

	}
}
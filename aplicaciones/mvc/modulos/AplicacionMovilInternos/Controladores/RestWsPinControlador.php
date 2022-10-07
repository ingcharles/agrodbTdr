<?php
/**
 * Controlador Alertas
 *
 * Este archivo controla la lógica del negocio del modelo: AlertasModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-09-07
 * @uses AplicacionesControlador
 * @package AplicacionMovilInternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilInternos\Controladores;

use Agrodb\AplicacionMovilInternos\Modelos\PinUsuarioLogicaNegocio;
use Agrodb\AplicacionMovilInternos\Modelos\PinUsuarioModelo;

class RestWsPinControlador extends BaseControlador{

	private $lNegocioUsuarioPin = null;

	private $modeloUsuarioPin = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioUsuarioPin = new PinUsuarioLogicaNegocio();
		$this->modeloUsuarioPin = new PinUsuarioModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}
	
	
	/**
	 * Método que genera un pin de ingreso
	 */
	public function generarPin($identificador, $tipo){
		
		$arrayParametros = array('identificador' => $identificador, 'tipo' => $tipo);

		$estado = "exito";
		$mensaje = "Pin enviado a su correo electrónico personal registrado en el Sistema GUIA";
		$cuerpo =[];
		$correoUsuario ='';

		
		$correo = $this->lNegocioUsuarioPin->obtenerCorreo($arrayParametros);

		$correo->buffer();

		if (isset($correo->current()->tipo_empleado)){			

			if($correo->current()->tipo_empleado == 'Interno'){

				if (isset($correo->current()->mail_institucional)){
	
					if($correo->current()->mail_institucional != '') {
						$correoUsuario = $correo->current()->mail_institucional;
						$mensaje = "Pin enviado a su correo electrónico institucional";
					} else{
						$correoUsuario = $correo->current()->mail_personal;				
					}
	
				} else{
					$correoUsuario = $correo->current()->mail_personal;			
				}
			}	else{

				if (isset($correo->current()->mail_personal)){
					$correoUsuario = $correo->current()->mail_personal;
				}
			}
		} else{
			if (isset($correo->current()->mail_personal)){
				$correoUsuario = $correo->current()->mail_personal;
			}
		}
				
		if ($correoUsuario == '' ){
			$estado='error';
			$mensaje = "No tiene registrado ningún correo electrónico en el Sistema GUIA";
		} else{			
			$this->lNegocioUsuarioPin->guardarPin($arrayParametros);
			$pin=$this->obtenerPin($identificador);
			$pin->buffer();

			$arrayParametros['correo'] = $correoUsuario;
			$arrayParametros['id_pin'] = $pin->current()->id_pin;
			$arrayParametros['pin'] = $pin->current()->pin;

			$this->lNegocioUsuarioPin->enviarCorreo($arrayParametros);
			
			$cuerpo = $pin->toArray();
		}		

		echo json_encode(array("estado"=>$estado, "mensaje" => $mensaje, "cuerpo" => $cuerpo));
	}


	/**
	 * Método para obtener el pin de ingreso del usuario
	 */
	private function obtenerPin($identificador){
			
		$res = $this->lNegocioUsuarioPin->obtenerdarPin(array("identificador" => $identificador));

		return $res;
	}
}
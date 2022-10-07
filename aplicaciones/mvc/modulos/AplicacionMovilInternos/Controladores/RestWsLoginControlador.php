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

use Agrodb\Usuarios\Modelos\UsuariosLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosModelo;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\AplicacionMovilInternos\Modelos\PinUsuarioLogicaNegocio;
use Agrodb\Token\Modelos\TokenLogicaNegocio;

class RestWsLoginControlador extends BaseControlador
{

	private $lNegocioUsuario = null;
	private $lNegocioPerfil = null;
	private $modeloUsuario = null;
	private $lNegocioToken = null;
	private $lNegocioPin = null;

	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->lNegocioUsuario = new UsuariosLogicaNegocio();
		$this->lNegocioPerfil = new UsuariosPerfilesLogicaNegocio();
		$this->modeloUsuario = new UsuariosModelo();
		$this->lNegocioToken = new TokenLogicaNegocio();
		$this->lNegociolNegocioPinToken = new TokenLogicaNegocio();
		$this->lNegocioPin = new PinUsuarioLogicaNegocio();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'
		));
	}


	/**
	 * Método para obtener los datos de ingreso de un usuario
	 */
	public function login($identificador, $clave, $tipo)
	{

		$parametros = $_POST;

		$estado = "exito";
		$mensaje = "Usuario y contraseña correctos";
		$token = null;
		$cuerpo = [];

		$esUsuarioInterno = false;
		$esClaveCorrecta = false;

		if ($tipo == 'usuario' || $tipo == 'pin') {

			$arrayParametros = array('identificador' => $identificador, 'clave' => $clave);

			$esUsuarioInterno = $this->usuarioInterno($identificador);

			if ($esUsuarioInterno) {
				$usuario = $this->lNegocioUsuario->buscarUsuarioInterno($arrayParametros);
			} else {
				$usuario = $this->lNegocioUsuario->buscarUsuarioExterno($arrayParametros);
			}

			$usuario->buffer();

			if (isset($usuario->current()->identificador)) {

				if ($tipo == 'usuario') {
					$esClaveCorrecta = $this->validarUsuario($usuario, $clave);
					if ($esClaveCorrecta) {
						$cuerpo = $usuario->toArray();
						$token = $this->lNegocioToken->auth(RUTA_KEY_AGROSERVICIOS);
					} else {
						$estado = "error";
						$mensaje = "El usuario o la contraseña son incorrectos";
					}
				} else {
					$pin = $this->lNegocioPin->validarPin(array('identificador' => $identificador, 'pin' => $clave));								
					if(isset($pin->current()->identificador)){
						$cuerpo = $usuario->toArray();
						$mensaje = "El pin ingresado es correcto";
						$token = $this->lNegocioToken->auth(RUTA_KEY_AGROSERVICIOS);
					} else{
						$estado = "error";
						$mensaje = "El pin ingresado es incorrecto o está caducado";
					}
				}
			} else {
				$estado = "error";
				$mensaje = "El usuario o la contraseña son incorrectos";
			}

			echo json_encode(array("estado" => $estado, "mensaje" => $mensaje, "cuerpo" => $cuerpo, "token" => $token));
		} else if ($tipo == 'biometrico') {

			if (isset($parametros['publickey'])){
				if ($this->lNegocioToken->validarPublicKey(PRIVATE_KEY_AGROSERVICIOS, $parametros['publickey'])) {
					$token = $this->lNegocioToken->auth(RUTA_KEY_AGROSERVICIOS);
					$estado = "exito";
					$mensaje = "Usuario validado con éxito";
				} else {
					$estado = "error";
					$mensaje = "Credenciales inválidas";
				}
			} else{
				$estado = "error";
				$mensaje = "Credenciales inválidas";
			}

			echo json_encode(array("estado" => $estado, "mensaje" => $mensaje, "cuerpo" => $cuerpo, "token" => $token));
		}
	}

	/**
	 * Método para obtener perfil de usuario interno
	 */
	private function usuarioInterno($identificador)
	{

		$arrayParametros = array('identificador' => $identificador);

		$perfil = $this->lNegocioPerfil->buscarPerfinInterno($arrayParametros);

		if (isset($perfil->current()->id_perfil)) {
			return true;
		}
	}

	/**
	 * Método que valida la clave de un usuario
	 */
	private function validarUsuario($resultado, $clave)
	{

		if ($resultado->current()->clave == md5($clave)) {
			return true;
		}
	}


	/**
	 * Método que valida el pin del usuario
	 */
	private function validarPin($resultado, $clave)
	{

		if ($resultado->current()->clave == md5($clave)) {
			return true;
		}
	}
}

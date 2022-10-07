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

use Agrodb\AplicacionMovilInternos\Modelos\AplicacionesLogicaNegocio;
use Agrodb\AplicacionMovilInternos\Modelos\AplicacionesModelo;

class RestWsAplicacionesControlador extends BaseControlador
{

	private $lNegocioAplicaciones = null;

	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->lNegocioAplicaciones = new AplicacionesLogicaNegocio();
		$this->modeloAplicaciones = new AplicacionesModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'
		));
	}


	/**
	 * Método de obtención de las aplicaciones del usuario
	 */
	public function obtenerAplicaciones($identificador)
	{
		$arrayParametros = array('identificador' => $identificador);
		$this->lNegocioAplicaciones->obtenerAplicacionesPorUsuario($arrayParametros);
	}
}

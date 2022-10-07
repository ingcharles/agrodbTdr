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

use Agrodb\AplicacionMovilInternos\Modelos\AreasLogicaNegocio;
use Agrodb\AplicacionMovilInternos\Modelos\AreasModelo;

class RestWsAreasControlador extends BaseControlador{

	private $lNegocioAreas = null;

	private $modeloAreas = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioAreas = new AreasLogicaNegocio();
		$this->modeloAreas = new AreasModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}
	
	
	/**
	 * Método de obtención de las áreas de las aplicaciones del usuario
	 */
	public function obtenerAreas($identificador){
		$arrayParametros = array('identificador' => $identificador);
		$this->lNegocioAreas->obtenerAreasAplicacion($arrayParametros);
	}
}
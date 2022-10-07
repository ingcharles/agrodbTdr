<?php
/**
 *
 * @author AGROCALIDAD
 * @date   2020-09-07
 * @uses AplicacionesControlador
 * @package AplicacionMovilInternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilInternos\Controladores;

use Agrodb\Catalogos\Modelos\TiposEnvaseLogicaNegocio;

class RestWsEnvasesControlador extends BaseControlador{

	private $lNegocioEnvases = null;
	
	/**
	 * Constructor
	 */
	function __construct(){

		$this->lNegocioEnvases = new TiposEnvaseLogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));

		set_error_handler(array(
			$this,
			'manejadorExcepcionesSincatch'));
	}
	
	
	/**
	 * Método para obtener el catálogo de tipos de envases para tránsito
	 * 
	 * @param String $idArea
	 */
	public function tiposEnvases($idArea){
		$this->lNegocioEnvases->obtenerCatalogoEnvases($idArea);	
	}
}


	

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

use Agrodb\Catalogos\Modelos\PuertosLogicaNegocio;

class RestWsPuertosControlador extends BaseControlador{

	private $lNegocioPuertos = null;
	
	/**
	 * Constructor
	 */
	function __construct(){

		$this->lNegocioPuertos = new PuertosLogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));

		set_error_handler(array(
			$this,
			'manejadorExcepcionesSincatch'));
	}
	
	
	/**
	 * Método para obtener el catálogo de los puertos de entra y salida del país
	 */
	public function catalogoPuertos(){
		$this->lNegocioPuertos->obtenerCatalagoPuertosIngresoSalida();	
	}
}


	

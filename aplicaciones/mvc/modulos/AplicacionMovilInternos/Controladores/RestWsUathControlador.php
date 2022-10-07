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

use Agrodb\GUath\Modelos\DatosContratoLogicaNegocio;

class RestWsUathControlador extends BaseControlador{

	private $lNegocioUath = null;
	
	/**
	 * Constructor
	 */
	function __construct(){

		$this->lNegocioUath = new DatosContratoLogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));

		set_error_handler(array(
			$this,
			'manejadorExcepcionesSincatch'));
	}
	
	
	/**
	 * Método para obtener la provincia donde el funcionario tiene activo su contrato
	 */
	public function ubicacionContrato($identificador){
		$this->lNegocioUath->buscarProvinciaContratoUsuario($identificador);	
	}
}


	

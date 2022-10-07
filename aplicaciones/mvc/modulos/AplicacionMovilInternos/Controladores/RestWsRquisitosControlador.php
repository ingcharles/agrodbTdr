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

use Agrodb\RequisitosComercializacion\Modelos\RequisitosComercializacionLogicaNegocio;

class RestWsRquisitosControlador extends BaseControlador{

	private $lNegocioEnvases = null;
	
	/**
	 * Constructor
	 */
	function __construct(){

		$this->lNegocioEnvases = new RequisitosComercializacionLogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));

		set_error_handler(array(
			$this,
			'manejadorExcepcionesSincatch'));
	}
	
	
	/**
	 * Método para obtener el catálogo de paises de origen para tránsito internacional con requisitos comerciales.
	 * 
	 * @param String $idArea
	 */
	public function paisOrigenProcendenciaTransito(){
		$this->lNegocioEnvases->obtenerCatalogoPaisesOrigenProcedenciaTransito();	
	}

	/**
	 * Método para obtener el catálogo de productos con requisitos comerciales para tránsito internacional
	 * 
	 * @param String $idArea
	 */
	public function productosTransito(){
		$this->lNegocioEnvases->obtenerProductoaRequisitosTransito();	
	}
}


	

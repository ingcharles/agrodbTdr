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

use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionModelo;

class RestWsLocalizacionControlador extends BaseControlador{

	private $lNegocioLocalizacion = null;
	private $modeloLocalizacion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
		$this->modeloLocalizacion = new LocalizacionModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}
	
	
	/**
	 * Método de obtención de cantones
	 */
	public function obtenerCantones($provincia){
		
		$localizacion = $this->lNegocioLocalizacion->buscarCantones($provincia);
		echo json_encode($localizacion->toArray());
	}

	/**
	 * Método  para obtener el catálogo de cantones y parroquias por provincia
	 * @param int provincia
	 * identificador de la provincia
	 */
	public function catalogoLocalizacionProvincia($provincia=null){
		
		$this->lNegocioLocalizacion->BuscarCatalogoCantonParroquiaPorProvincia($provincia);

	}

	/**
	 * Método  para obtener la lista de localizaciones por categoria
	 * @param int categoria
	 * 
	 */
	public function localizacionCategoria($categoria){
		
		$this->lNegocioLocalizacion->buscarLocalizacionPorCategoria($categoria);

	}


	
}
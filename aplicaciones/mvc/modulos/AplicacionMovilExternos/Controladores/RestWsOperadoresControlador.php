<?php
/**
 * Controlador Noticias
 *
 * Este archivo controla la lógica del negocio del modelo: Operadores y operaciones
 *
 * @author AGROCALIDAD
 * @date   2019-06-06
 * @uses RestWsOperadoresControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;

class RestWsOperadoresControlador extends BaseControlador{

	private $lNegocioLocalizacion = null;
	private $lNegocioOperaciones = null;
	private $lNegocioOperadores = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
		$this->lNegocioOperaciones = new OperacionesLogicaNegocio();
		$this->lNegocioOperadores = new OperadoresLogicaNegocio();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de obtención de localización, País y Provincia
	 */
	public function obtenerLozalizacion($categoria){
		
		$arrayParametros = array('categoria' => $categoria);
		
		if($arrayParametros['categoria'] == 0){
			$localizaciones = $this->lNegocioLocalizacion->buscarPaises();
		}else if($arrayParametros['categoria'] == 1){
			$localizaciones = $this->lNegocioLocalizacion->buscarProvinciasEc();
		}
		
		$arrayLocalizacion = array();
		
		foreach ($localizaciones as $localizacion){
			$arrayLocalizacion[] = array('id'=> $localizacion->id_localizacion, 'nombre'=> $localizacion->nombre);
		}

		echo json_encode($arrayLocalizacion);
	}
	
	/**
	 * Método de obtención de tipos de operaciones
	 */
	public function obtenerTipoOperaciones($identificadorOperador, $nombreProvincia){
		
		$identificadorOperador = trim($identificadorOperador);
		
		$arrayParametros = array('identificador_operador' => $identificadorOperador,
								 'nombre_provincia' => $nombreProvincia);
		
		$tipoOperaciones = $this->lNegocioOperaciones->obtenerTipoOperacionesPorIdentificadorProvincia($arrayParametros);
		
		echo json_encode($tipoOperaciones);
	}
	
	/**
	 * Método de obtención de operadores
	 */
	public function obtenerOperadores($razonSocial, $nombreProvincia){
		
		$razonSocial = $this->quitarTildes(trim($razonSocial));
		
		$arrayParametros = array('razon_social' => $razonSocial,
			'nombre_provincia' => $nombreProvincia);
		
		$operadores = $this->lNegocioOperadores->obtenerOperadoresPorRazonSocialProvincia($arrayParametros);
		
		echo json_encode($operadores->toArray());
	}
}

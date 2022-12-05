<?php
/**
 * Controlador Registro Trampeo
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

use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\DatosVehiculosLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\CentrosAcopioLogicaNegocio;

class RestWsInspeccionOperacionesControlador extends BaseControlador{

	private $lNegocioOperaciones = null;
	private $lNegocioDatosVehiculos = null;
	private $lNegocioCentrosAcopio = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioOperaciones = new OperacionesLogicaNegocio();
		$this->lNegocioDatosVehiculos = new DatosVehiculosLogicaNegocio();
		$this->lNegocioCentrosAcopio = new CentrosAcopioLogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));

		set_error_handler(array(
			$this,
			'manejadorExcepcionesSincatch'));
	}
	
	
	/**
	 * Método de obtención de las provincias
	 */
	public function obtenerOperacionesRegistoOperador($provincia,$idAreaTematica,$codigoTipoOperacion,$estado){
	    // $arrayParametros = array('provincia'=>259, //'idAreaTematica' => 'AI', 'codigoTipoOperacion' => //'ACO', 'estado' => 'inspeccion');
	    
		$arrayParametros = array('provincia'=>$provincia, 'idAreaTematica' => $idAreaTematica, 'codigoTipoOperacion' => $codigoTipoOperacion, 'estado' => $estado);
		//print_r($arrayParametros);
	    $this->lNegocioOperaciones->obtenerOperacionesRegistroOperadorPorEstado($arrayParametros);
	}

	
	/**
	 * Método que guarda las inspecciones de medios de transporte de leches
	 */
	public function guardarInspeccionMedioTransporteAI(){

        //$certificadoBpa = (array) json_decode(file_get_contents('php://input'));
        //print_r($certificadoBpa);
        $arrayInspeccion = (array) json_decode(file_get_contents('php://input'));
		
	    $this->lNegocioDatosVehiculos->guardarDatosInspeccionMedioTransporteAI($arrayInspeccion);
		
	}
	
	/**
	 * Método que guarda las inspecciones de centros de acopio de leche
	 */
	public function guardarInspeccionCentroAcopioAI(){
	    
	    $arrayInspeccion = (array) json_decode(file_get_contents('php://input'));
	  
	    
	    $this->lNegocioCentrosAcopio->guardarDatosInspeccionCentroAcopioAI($arrayInspeccion);
	    
	}

}


	

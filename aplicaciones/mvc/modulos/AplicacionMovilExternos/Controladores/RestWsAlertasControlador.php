<?php
/**
 * Controlador Alertas
 *
 * Este archivo controla la lógica del negocio del modelo: AlertasModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2019-06-06
 * @uses AlertasControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\AplicacionMovilExternos\Modelos\AlertasLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\AlertasModelo;

class RestWsAlertasControlador extends BaseControlador{

	private $lNegocioAlertas = null;

	private $modeloAlertas = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioAlertas = new AlertasLogicaNegocio();
		$this->modeloAlertas = new AlertasModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}
	
	
	/**
	 * Método de obtención de alertas basado en una carga offset, fectch
	 */
	public function obtenerAlertas($incremento){
		$arrayParametros = array('incremento' => $incremento);
		$alertas = $this->lNegocioAlertas->obtenerAlertasOffset($arrayParametros);
		echo json_encode($alertas->toArray());
	}
}

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

use Agrodb\FormulariosInspeccion\Modelos\Controlf02LogicaNegocio;

class RestWsTransitoSalidaControlador extends BaseControlador{

	private $lNegocioTransitoIngreso = null;
	
	/**
	 * Constructor
	 */
	function __construct(){

		$this->lNegocioTransitoIngreso = new Controlf02LogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));

		set_error_handler(array(
			$this,
			'manejadorExcepcionesSincatch'));
	}
	
	
	/**
	 * Método para obtener los registros de verificación de tránsito de ingreso
	 * 
	 */
	public function registrosIngreso(){
		$this->lNegocioTransitoIngreso->obtenerTransitoIngreso();	
	}

	/**
	 * Método para obtener los registros de verificación de tránsito de ingreso
	 * 
	 */
	public function salida(){

		$transitoSalida = (array) json_decode(file_get_contents('php://input'));

		$arrayDatos = [];

		foreach($transitoSalida["salida"] as $registro) {
			$campos = array(
				'id_ingreso' => $registro->id_ingreso,
				'estado_precinto' => $registro->estado_precinto,
				'tipo_verificacion' => $registro->tipo_verificacion,
				'estado' => 'Salida',
				'fecha_salida' => $registro->fecha_salida,
				'usuario_id_salida' => $registro->usuario_id_salida,
				'usuario_salida' => $registro->usuario_salida,
				'tablet_id_salida' => $registro->tablet_id_salida,
				'tablet_version_base_salida' => $registro->tablet_version_base_salida,		
			);

			$arrayDatos[] = $campos;
		}

		$this->lNegocioTransitoIngreso->actualizarSalida($arrayDatos);	
	}
}
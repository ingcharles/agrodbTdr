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

class RestWsTransitoIngresoControlador extends BaseControlador{

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
	public function ingresos(){

		$ingresos = (array) json_decode(file_get_contents('php://input'));

		$arrayIngresos = [];
		$arrayProductos = [];		

		foreach($ingresos["ingreso"] as $registro) {
			$campos = array(
				'id' => $registro->id,
				'nombre_razon_social' => $registro->nombre_razon_social,
				'ruc_ci' => $registro->ruc_ci,
				'id_pais_origen' => $registro->id_pais_origen,
				'pais_origen' => $registro->pais_origen,
				'id_pais_procedencia' => $registro->id_pais_procedencia,
				'pais_procedencia' => $registro->pais_procedencia,
				'id_pais_destino' => $registro->id_pais_destino,
				'pais_destino' => $registro->pais_destino,
				'id_punto_ingreso' => $registro->id_punto_ingreso,
				'punto_ingreso' => $registro->punto_ingreso,
				'id_punto_salida' => $registro->id_punto_salida,
				'punto_salida' => $registro->punto_salida,
				'placa_vehiculo' => $registro->placa_vehiculo,
				'dda' => $registro->dda,
				'precinto_sticker' => $registro->precinto_sticker,
				'usuario_ingreso' => $registro->usuario_ingreso,
				'usuario_id_ingreso' => $registro->usuario_id_ingreso,
				'fecha_ingreso' => $registro->fecha_ingreso,
				'id_tablet' => $registro->id_tablet,
				'tablet_version_base_ingreso' => $registro->tablet_version_base_ingreso,
			);

			$arrayIngresos[] = $campos;
		}


		foreach($ingresos["productos"] as $registro) {
			$campos = array(
				'id' => $registro->id,
				'partida_arancelaria' => $registro->partida_arancelaria,
				'descripcion_producto' => $registro->descripcion_producto,
				'subtipo' => $registro->subtipo,
				'cantidad' => $registro->cantidad,
				'tipo_envase' => $registro->tipo_envase,
				'id_padre' => $registro->control_f02_id,
				'id_tablet' => $registro->id_tablet,
			);

			$arrayProductos[] = $campos;
		}
		
		$this->lNegocioTransitoIngreso->guardarIngresos($arrayIngresos,$arrayProductos);
	}
}
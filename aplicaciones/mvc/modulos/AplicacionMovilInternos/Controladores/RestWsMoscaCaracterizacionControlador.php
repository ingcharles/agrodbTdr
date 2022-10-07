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

use Agrodb\FormulariosInspeccion\Modelos\Moscaf02LogicaNegocio;

class RestWsMoscaCaracterizacionControlador extends BaseControlador{

	private $lNegocioMoscaf02 = null;
	

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioMoscaf02 = new Moscaf02LogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}
	

	/**
	 * Método que guarda las inspecciones de trampas de mosca de la fruta
	 */
	public function guardarCaracterizacion(){

		$muestreo = (array) json_decode(file_get_contents('php://input'));

		$arraycaracterizacion = [];

		foreach($muestreo["detalle"] as $registro){
			$campos = array (
				"id_tablet" => $registro->id_tablet,
				"nombre_asociacion_productor" => $registro->nombre_asociacion_productor,
				"identificador" => $registro->identificador,
				"telefono" => $registro->telefono,
				"codigo_provincia" => $registro->codigo_provincia,
				"provincia" => $registro->provincia,
				"codigo_canton" => $registro->codigo_canton,
				"canton" => $registro->canton,
				"codigo_parroquia" => $registro->codigo_parroquia,
				"parroquia" => $registro->parroquia,
				"sitio" => $registro->sitio,
				"especie" => $registro->especie,
				"variedad" => $registro->variedad,
				"area_produccion_estimada" => $registro->area_produccion_estimada,
				"coordenada_x" => $registro->coordenada_x,
				"coordenada_y" => $registro->coordenada_y,
				"coordenada_z" => $registro->coordenada_z,
				"observaciones" => $registro->observaciones,
				"fecha_inspeccion" => $registro->fecha_inspeccion,
				"usuario_id" => $registro->usuario_id,
				"usuario" => $registro->usuario,
				"tablet_id" => $registro->tablet_id,
				"tablet_version_base" => $registro->tablet_version_base,
				"imagen" => $registro->imagen,
			);

			$arraycaracterizacion[] = $campos;

		}

		$this->lNegocioMoscaf02->guardarCaracterizacion($arraycaracterizacion);
		
	}

}


	

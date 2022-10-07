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

use Agrodb\FormulariosInspeccion\Modelos\Moscaf03LogicaNegocio;

class RestWsMoscaMuestreoControlador extends BaseControlador{

	private $lNegocioMoscaf03 = null;
	

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioMoscaf03 = new Moscaf03LogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}
	

	/**
	 * Método que guarda las inspecciones de muestras de mosca de la fruta
	 */
	public function guardarMuestreo(){

		$muestreo = (array) json_decode(file_get_contents('php://input'));

		$arrayMuestreo = [];
		$arrayOrdenes = [];

		foreach($muestreo["detalle"] as $registro){
			$campos = array (
				"id" => $registro->id,
				"id_tablet" => $registro->id_tablet,
				"codigo_provincia" => $registro->codigo_provincia,
				"nombre_provincia" => $registro->nombre_provincia,
				"codigo_canton" => $registro->codigo_canton,
				"nombre_canton" => $registro->nombre_canton,
				"codigo_parroquia" => $registro->codigo_parroquia,
				"nombre_parroquia" => $registro->nombre_parroquia,
				"semana" => $registro->semana,
				"coordenada_x" => $registro->coordenada_x,
				"coordenada_y" => $registro->coordenada_y,
				"coordenada_z" => $registro->coordenada_z,
				"fecha_inspeccion" => $registro->fecha_inspeccion,
				"usuario_id" => $registro->usuario_id,
				"usuario" => $registro->usuario,
				"tablet_id" => $registro->tablet_id,
				"tablet_version_base" => $registro->tablet_version_base,
				"sitio" => $registro->sitio,
				"envio_muestra" => $registro->envio_muestra,
				"imagen" => $registro->imagen,
				"id_cabecera_padre" => $registro->imagen,
			);

			$arrayMuestreo[] = $campos;

		}

		foreach($muestreo["ordenes"] as $registro){
			$campos = array(
				"id_padre" => $registro->id_muestreo,
				"id_tablet" => $registro->id_tablet,
				"aplicacion_producto_quimico" => $registro->aplicacion_producto_quimico,
				"codigo_muestra" => $registro->codigo_muestra,
				"descripcion_sintomas" => $registro->descripcion_sintomas,
				"especie_vegetal" => $registro->especie_vegetal,
				"sitio_muestreo" => $registro->sitio_muestreo,
				"numero_frutos_colectados" => $registro->numero_frutos_colectados,
				"id_caracterizacion" => $registro->id_muestreo,
			);

			$arrayOrdenes[] = $campos;

		}

		$this->lNegocioMoscaf03->guardarMuestreo($arrayMuestreo,$arrayOrdenes);
		
	}

}


	

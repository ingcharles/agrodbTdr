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

use Agrodb\Catalogos\Modelos\PuertosLogicaNegocio;
use Agrodb\FormulariosInspeccion\Modelos\Controlf03LogicaNegocio;

class RestWsEmbalajeControlador extends BaseControlador{

	private $lNegocioPuertos = null;
	private $lNegocioControlf03 = null;
	
	/**
	 * Constructor
	 */
	function __construct(){

		$this->lNegocioPuertos = new PuertosLogicaNegocio();
		$this->lNegocioControlf03 = new Controlf03LogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));

		set_error_handler(array(
			$this,
			'manejadorExcepcionesSincatch'));
	}
	
	
	/**
	 * Método para obtener el catálogo de los puertos de entra y salida del país
	 */
	public function catalogoPuertos(){
		$this->lNegocioPuertos->obtenerCatalagoPuertosIngresoSalida();	
	}

	/**
	 * Método para guardar los registros de Inspección de embalaje de madera
	 */
	public function embalajeMadera(){
		$embalaje = (array) json_decode(file_get_contents('php://input'));

		$arrayCabecera = [];
		$arrayOrdenes = [];

		foreach($embalaje["embalaje"] as $registro) {
			$campos = array(
				'id' => $registro->id,
				'id_punto_control' => $registro->id_punto_control,
				'punto_control' => $registro->punto_control,
				'area_inspeccion' => $registro->area_inspeccion,
				'identidad_embalaje' => $registro->identidad_embalaje,
				'id_pais_origen' => $registro->id_pais_origen,
				'pais_origen' => $registro->pais_origen,
				'numero_embalajes' => $registro->numero_embalajes,
				'numero_unidades' => $registro->numero_unidades,
				'marca_autorizada' => $registro->marca_autorizada,
				'marca_autorizada_descripcion' => $registro->marca_autorizada_descripcion,
				'marca_legible' => $registro->marca_legible,
				'marca_legible_descripcion' => $registro->marca_legible_descripcion,
				'ausencia_dano_insectos' => $registro->ausencia_dano_insectos,
				'ausencia_dano_insectos_descripcion' => $registro->ausencia_dano_insectos_descripcion,
				'ausencia_insectos_vivos' => $registro->ausencia_insectos_vivos,
				'ausencia_insectos_vivos_descripcion' => $registro->ausencia_insectos_vivos_descripcion,
				'ausencia_corteza' => $registro->ausencia_corteza,
				'ausencia_corteza_descripcion' => $registro->ausencia_corteza_descripcion,
				'razon_social' => $registro->razon_social,
				'manifesto' => $registro->manifesto,
				'producto' => $registro->producto,
				'envio_muestra' => $registro->envio_muestra,
				'observaciones' => $registro->observaciones,
				'dicatamen_final' => $registro->dictamen_final,
				'usuario' => $registro->usuario,
				'usuario_id' => $registro->usuario_id,
				'fecha_creacion' => $registro->fecha_creacion,
				'id_tablet' => $registro->id_tablet,
				'tablet_id' => $registro->tablet_id,
				'tablet_version_base' => $registro->tablet_version_base,	
			);

			$arrayCabecera[] = $campos;
		}

		foreach ($embalaje["ordenes"] as $orden) {
            $campos = array(
                'id_padre' => $orden->control_f03_id,
                'id_tablet' => $orden->id_tablet,
                'actividad_origen' => $orden->actividad_origen,
                'analisis' => $orden->analisis,
                'codigo_muestra' => $orden->codigo_muestra,
                'conservacion' => $orden->conservacion,
                'tipo_muestra' => $orden->tipo_muestra,
                'descripcion_sintomas' => $orden->descripcion_sintomas,
                'fase_fenologica' => $orden->fase_fenologica,
                'nombre_producto' => $orden->nombre_producto,
                'peso_muestra' => $orden->peso_muestra,
                'prediagnostico' => $orden->prediagnostico,
                'tipo_cliente' => $orden->tipo_cliente,
            );

			$arrayOrdenes[] = $campos;
           
        }

		$this->lNegocioControlf03->guardarEmbalaje($arrayCabecera,$arrayOrdenes);

	}
}
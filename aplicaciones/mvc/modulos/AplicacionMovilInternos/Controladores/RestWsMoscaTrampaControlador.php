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

use Agrodb\FormulariosInspeccion\Modelos\Moscaf01LogicaNegocio;

class RestWsMoscaTrampaControlador extends BaseControlador{

	private $lNegocioAdministracionTrampas = null;
	

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioAdministracionTrampas = new Moscaf01LogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}
	
	
	/**
	 * Método de obtención de las provincias
	 */
	public function rutasTrampas($provincia){
		$arrayParametos = array('provincia'=>$provincia);		
		$this->lNegocioAdministracionTrampas->obtenerRutasTrampas($arrayParametos);
	}

	/**
	 * Método que guarda las inspecciones de trampas de vigilancia
	 */
	public function guardarTrampas(){

		$trampas = (array) json_decode(file_get_contents('php://input'));

		$arrayCabecera = [];
		$arrayTrampas= [];
		$arrayOrdenes = [];

		foreach($trampas["inspeccion"] as $inspeccion) {
			$campos = array(
				"id" => $inspeccion->id,
				"id_tablet" => $inspeccion->id_tablet,
				"fecha_inspeccion" => $inspeccion->fecha_inspeccion,
				"usuario_id" => $inspeccion->usuario_id,
				"usuario" => $inspeccion->usuario,
				"tablet_id" => $inspeccion->tablet_id,
				"tablet_version_base" => $inspeccion->tablet_version_base,			
			);

			$arrayCabecera[] = $campos;
		}

		foreach($trampas["detalle"] as $trampa){	
			$campos = array(		
				'id' => $trampa->id,
				"id_padre" => $trampa->id_padre,
				"id_tablet" => $trampa->id_tablet,
				"id_provincia" => $trampa->id_provincia,
				"nombre_provincia" => $trampa->nombre_provincia,
				"id_canton" => $trampa->id_canton,
				"nombre_canton" => $trampa->nombre_canton,
				"id_parroquia" => $trampa->id_parroquia,
				"nombre_parroquia" => $trampa->nombre_parroquia,
				"id_lugar_instalacion" => $trampa->id_lugar_instalacion,
				"nombre_lugar_instalacion" => $trampa->nombre_lugar_instalacion,
				"numero_lugar_instalacion" => $trampa->numero_lugar_instalacion,
				"id_tipo_atrayente" => $trampa->id_tipo_atrayente,
				"nombre_tipo_atrayente" => $trampa->nombre_tipo_atrayente,
				"tipo_trampa" => $trampa->tipo_trampa,
				"codigo_trampa" => $trampa->codigo_trampa,
				"semana" => $trampa->semana,
				"coordenada_x" => $trampa->coordenada_x,
				"coordenada_y" => $trampa->coordenada_y,
				"coordenada_z" => $trampa->coordenada_z,
				"fecha_instalacion" => $trampa->fecha_instalacion,
				"estado_trampa" => $trampa->estado_trampa,
				"exposicion" => $trampa->exposicion,
				"condicion" => $trampa->condicion,
				"cambio_trampa" => $trampa->cambio_trampa,
				"cambio_plug" => $trampa->cambio_plug,
				"especie_principal" => $trampa->especie_principal,
				"estado_fenologico_principal" => $trampa->estado_fenologico_principal,
				"especie_colindante" => $trampa->especie_colindante,
				"estado_fenologico_colindante" => $trampa->estado_fenologico_colindante,
				"numero_especimenes" => $trampa->numero_especimenes,
				"observaciones" => $trampa->observaciones,
				"envio_muestra" => $trampa->envio_muestra,
				"estado_registro" => $trampa->estado_registro,
				"fecha_inspeccion" => $trampa->fecha_inspeccion,
				"usuario_id" => $trampa->usuario_id,
				"usuario" => $trampa->usuario,
				"tablet_id" => $trampa->tablet_id,
				"tablet_version_base" => $trampa->tablet_version_base,			
			);

			$arrayTrampas[]= $campos;
		}

		foreach($trampas['ordenes'] as $orden){	

			$campos = array(	
				'id_padre' => $orden->id_padre,
				'id_tablet' => $orden->id_tablet,
				'analisis' => $orden->analisis,
				'codigo_muestra' => $orden->codigo_muestra,
				'tipo_muestra' => $orden->tipo_muestra,
				'codigo_trampa_padre' => $orden->codigo_trampa_padre,
			);

			$arrayOrdenes[]= $campos;

		}


		$this->lNegocioAdministracionTrampas->guardarTrampasMosca($arrayCabecera,$arrayTrampas,$arrayOrdenes);
		
	}


	// private function actualizarEstado($codigo_trampa, $identificadorTecnico){

	// 	$administracionTrampa = $this->lNegocioAdministracionTrampas->obtenerAdministracionTrampaPorCodigoTrampa(array('codigoTrampa' => $codigo_trampa));
		
	// 	$administracionTrampa->buffer();

	// 	$this->lNegocioAdministracionTrampas->actualizarEstadoAdminstracionTrampa(array("idTrampa" => $administracionTrampa->current()->id_administracion_trampa));
		
	// 	$this->lNegocioAdministracionTrampas->guardarNuevoHistoriaAdminintracionTrampas($administracionTrampa->toArray(), $identificadorTecnico);
		
	// }

}


	

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

use Agrodb\AdministracionTrampas\Modelos\AdministracionTrampasLogicaNegocio;
use Agrodb\FormulariosInspeccion\Modelos\Vigilanciaf01LogicaNegocio;

class RestWsTrampeoVigilanciaControlador extends BaseControlador{

	private $lNegocioAdministracionTrampas = null;
	private $lNegocioVigilanciaf01 = null;
	

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioAdministracionTrampas = new AdministracionTrampasLogicaNegocio();
		$this->lNegocioVigilanciaf01 = new Vigilanciaf01LogicaNegocio();
		
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
	public function obtenerRutasTrampasVigilancia($provincia){
		$arrayParametos = array('provincia'=>$provincia);
		$this->lNegocioAdministracionTrampas->obtenerRutasTrampasVigilancia($arrayParametos);	
	}

	/**
	 * Método que guarda las inspecciones de trampas de vigilancia
	 */
	public function guardarTrampasOld(){

		$trampas = (array) json_decode(file_get_contents('php://input'));
		
		$fecha = date("Y-m-d H:i:s");		

		foreach($trampas["inspeccion"] as $inspeccion) {
			$campos = array("id_tablet" => $inspeccion->id_tablet,
			"fecha_inspeccion" => $inspeccion->fecha_inspeccion,
			"usuario_id" => $inspeccion->usuario_id,
			"usuario" => $inspeccion->usuario,
			"tablet_id" => $inspeccion->tablet_id,
			"tablet_version_base" => $inspeccion->tablet_version_base,
			"fecha_ingreso_guia" => $fecha,
			);

			$idInspeccion = $this->lNegocioAdministracionTrampas->guardarTrampaPadreRegistro($campos);
			
		
			foreach($trampas["detalle"] as $trampa){

				if ($trampa->id_padre == $inspeccion->id){
					$campos = array(
					'id_padre' => $idInspeccion->current()->id ,
					'id_tablet' => $trampa->id_tablet,
					'fecha_instalacion' => $trampa->fecha_instalacion,
					'codigo_trampa' => $trampa->codigo_trampa,
					'tipo_trampa' => $trampa->tipo_trampa,
					'id_provincia' => $trampa->id_provincia,
					'nombre_provincia' => $trampa->nombre_provincia,
					'id_canton' => $trampa->id_canton,
					'nombre_canton' => $trampa->nombre_canton,
					'id_parroquia' => $trampa->id_parroquia,
					'nombre_parroquia' => $trampa->nombre_parroquia,
					'estado_trampa' => $trampa->estado_trampa,
					'coordenada_x' => $trampa->coordenada_x,
					'coordenada_y' => $trampa->coordenada_y,
					'coordenada_z' => $trampa->coordenada_z,
					'id_lugar_instalacion' => $trampa->id_lugar_instalacion,
					'nombre_lugar_instalacion' => $trampa->nombre_lugar_instalacion,
					'numero_lugar_instalacion' => $trampa->numero_lugar_instalacion,
					'fecha_inspeccion' => $trampa->fecha_inspeccion,
					'semana' => $trampa->semana,
					'usuario_id' => $trampa->usuario_id,
					'usuario' => $trampa->usuario,
					'propiedad_finca' => $trampa->propiedad_finca,
					'condicion_trampa' => $trampa->condicion_trampa,
					'especie' => $trampa->especie,
					'procedencia' => $trampa->procedencia,
					'condicion_cultivo' => $trampa->condicion_cultivo,
					'etapa_cultivo' => $trampa->etapa_cultivo,
					'exposicion' => $trampa->exposicion,
					'cambio_feromona' => $trampa->cambio_feromona,
					'cambio_papel' => $trampa->cambio_papel,
					'cambio_aceite' => $trampa->cambio_aceite,
					'cambio_trampa' => $trampa->cambio_trampa,
					'numero_especimenes' => $trampa->numero_especimenes,
					'diagnostico_visual' => $trampa->diagnostico_visual,
					'fase_plaga' => $trampa->fase_plaga,
					'observaciones' => $trampa->observaciones,
					'envio_muestra' => $trampa->envio_muestra,
					'tablet_id' => $trampa->tablet_id,
					'tablet_version_base' => $trampa->tablet_version_base,
					'foto' => $trampa->foto,
					);

					$res = $this->lNegocioAdministracionTrampas->guardarTrampas($campos);

					if($trampa->estado_trampa=='inactivo'){						
						$this->actualizarEstado($trampa->codigo_trampa, $trampa->usuario_id);
					}

				}

			}

			foreach($trampas['ordenes'] as $orden){

				if ($orden->id_padre == $inspeccion->id){

					$campos = array(
					'id_padre' => $idInspeccion->current()->id ,
					'id_tablet' => $orden->id_tablet,
					'actividad_origen' =>$orden->actividad_origen,
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
					'aplicacion_producto_quimico' => $orden->aplicacion_producto_quimico,
					'codigo_trampa' => $orden->codigo_trampa
					);

					$res = $this->lNegocioAdministracionTrampas->guardarOrdenLaboratorio($campos);
				}

			}

		}
		
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
				'id' => $inspeccion->id,
				'id_tablet' => $inspeccion->id_tablet,
				'fecha_inspeccion' => $inspeccion->fecha_inspeccion,
				'usuario_id' => $inspeccion->usuario_id,
				'usuario' => $inspeccion->usuario,
				'tablet_id' => $inspeccion->tablet_id,
				'tablet_version_base' => $inspeccion->tablet_version_base,
			);

			$arrayCabecera[] = $campos;
		}			
		
		foreach($trampas["detalle"] as $trampa){

			$campos = array(
				'id' => $trampa->id,
				'id_padre' => $trampa->id_padre,
				'id_tablet' => $trampa->id_tablet,
				'fecha_instalacion' => $trampa->fecha_instalacion,
				'codigo_trampa' => $trampa->codigo_trampa,
				'tipo_trampa' => $trampa->tipo_trampa,
				'id_provincia' => $trampa->id_provincia,
				'nombre_provincia' => $trampa->nombre_provincia,
				'id_canton' => $trampa->id_canton,
				'nombre_canton' => $trampa->nombre_canton,
				'id_parroquia' => $trampa->id_parroquia,
				'nombre_parroquia' => $trampa->nombre_parroquia,
				'estado_trampa' => $trampa->estado_trampa,
				'coordenada_x' => $trampa->coordenada_x,
				'coordenada_y' => $trampa->coordenada_y,
				'coordenada_z' => $trampa->coordenada_z,
				'id_lugar_instalacion' => $trampa->id_lugar_instalacion,
				'nombre_lugar_instalacion' => $trampa->nombre_lugar_instalacion,
				'numero_lugar_instalacion' => $trampa->numero_lugar_instalacion,
				'fecha_inspeccion' => $trampa->fecha_inspeccion,
				'semana' => $trampa->semana,
				'usuario_id' => $trampa->usuario_id,
				'usuario' => $trampa->usuario,
				'propiedad_finca' => $trampa->propiedad_finca,
				'condicion_trampa' => $trampa->condicion_trampa,
				'especie' => $trampa->especie,
				'procedencia' => $trampa->procedencia,
				'condicion_cultivo' => $trampa->condicion_cultivo,
				'etapa_cultivo' => $trampa->etapa_cultivo,
				'exposicion' => $trampa->exposicion,
				'cambio_feromona' => $trampa->cambio_feromona,
				'cambio_papel' => $trampa->cambio_papel,
				'cambio_aceite' => $trampa->cambio_aceite,
				'cambio_trampa' => $trampa->cambio_trampa,
				'numero_especimenes' => $trampa->numero_especimenes,
				'diagnostico_visual' => $trampa->diagnostico_visual,
				'fase_plaga' => $trampa->fase_plaga,
				'observaciones' => $trampa->observaciones,
				'envio_muestra' => $trampa->envio_muestra,
				'tablet_id' => $trampa->tablet_id,
				'tablet_version_base' => $trampa->tablet_version_base,
				'foto' => $trampa->foto,
			);

			$arrayTrampas[] = $campos;
		}

		foreach($trampas['ordenes'] as $orden){

			$campos = array(				
				'id_padre' => $orden->id_padre,
				'id_tablet' => $orden->id_tablet,
				'actividad_origen' =>$orden->actividad_origen,
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
				'aplicacion_producto_quimico' => $orden->aplicacion_producto_quimico,
				'codigo_trampa_padre' => $orden->codigo_trampa
			);

			$arrayOrdenes[] = $campos;
		}

		$this->lNegocioVigilanciaf01->guardarTrampas($arrayCabecera,$arrayTrampas,$arrayOrdenes);
		
	}


	private function actualizarEstado($codigo_trampa, $identificadorTecnico){

		$administracionTrampa = $this->lNegocioAdministracionTrampas->obtenerAdministracionTrampaPorCodigoTrampa(array('codigoTrampa' => $codigo_trampa));
		
		$administracionTrampa->buffer();

		$this->lNegocioAdministracionTrampas->actualizarEstadoAdminstracionTrampa(array("idTrampa" => $administracionTrampa->current()->id_administracion_trampa));
		
		$this->lNegocioAdministracionTrampas->guardarNuevoHistoriaAdminintracionTrampas($administracionTrampa->toArray(), $identificadorTecnico);
		
	}

}


	

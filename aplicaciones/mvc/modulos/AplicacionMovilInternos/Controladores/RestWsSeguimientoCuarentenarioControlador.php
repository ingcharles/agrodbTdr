<?php
/**
 *
 * @author AGROCALIDAD
 * @date   2022/02/02
 * @uses RestWsSeguimientoCuarentenarioControlador
 * @package AplicacionMovilInternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilInternos\Controladores;

use Agrodb\SeguimientoCuarentenario\Modelos\SeguimientosCuarentenariosLogicaNegocio;
use Agrodb\FormulariosInspeccion\Modelos\Controlf04LogicaNegocio;

class RestWsSeguimientoCuarentenarioControlador extends BaseControlador{

	private $lNegocioCuarentenario = null;
	private $lNegocioControlf04 = null;
	
	/**
	 * Constructor
	 */
	function __construct(){

		$this->lNegocioCuarentenario = new SeguimientosCuarentenariosLogicaNegocio();
		$this->lNegocioControlf04 = new Controlf04LogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));

		set_error_handler(array(
			$this,
			'manejadorExcepcionesSincatch'));
	}
	
	
	/**
	 * Método para obtener las solicitudes de seguimiento cuarentenario
	 */
	public function solicitudes(){
		$this->lNegocioCuarentenario->obtenerSolicitudesSeguimientoCuarentenario();	
	}

	/**
	 * Método para guardar los registros de Inspección de embalaje de madera
	 */
	public function seguimientoCuarentenario(){
		$seguimiento = (array) json_decode(file_get_contents('php://input'));

		$arrayCabecera = [];
		$arrayOrdenes = [];

		foreach($seguimiento["seguimientos"] as $registro){
			$campos = array( 
				'id' => $registro->id,
				'id_seguimiento_cuarentenario' => $registro->id_seguimiento_cuarentenario,
				'ruc_operador' => $registro->ruc_operador,
				'razon_social' => $registro->razon_social,
				'codigo_pais_origen' => $registro->codigo_pais_origen,
				'pais_origen' => $registro->pais_origen,
				'producto' => $registro->producto,
				'subtipo' => $registro->subtipo,
				'peso' => $registro->peso,
				'numero_plantas_ingreso' => $registro->numero_plantas_ingreso,
				'codigo_provincia' => $registro->codigo_provincia,
				'provincia' => $registro->provincia,
				'codigo_canton' => $registro->codigo_canton,
				'canton' => $registro->canton,
				'codigo_parroquia' => $registro->codigo_parroquia,
				'parroquia' => $registro->parroquia,
				'nombre_scpe' => $registro->nombre_scpe,
				'tipo_operacion' => $registro->tipo_operacion,
				'tipo_cuarentena_condicion_produccion' => $registro->tipo_cuarentena_condicion_produccion,
				'fase_seguimiento' => $registro->fase_seguimiento,
				'codigo_lote' => $registro->codigo_lote,
				'numero_seguimientos_planificados' => $registro->numero_seguimientos_planificados,
				'cantidad_total' => $registro->cantidad_total,
				'cantidad_vigilada' => $registro->cantidad_vigilada,
				'actividad' => $registro->actividad,
				'etapa_cultivo' => $registro->etapa_cultivo,
				'registro_monitoreo_plagas' => $registro->registro_monitoreo_plagas,
				'ausencia_plagas' => $registro->ausencia_plagas,
				'cantidad_afectada' => $registro->cantidad_afectada,
				'porcentaje_incidencia' => $registro->porcentaje_incidencia,
				'porcentaje_severidad' => $registro->porcentaje_severidad,
				'fase_desarrollo_plaga' => $registro->fase_desarrollo_plaga,
				'organo_afectado' => $registro->organo_afectado,
				'distribucion_plaga' => $registro->distribucion_plaga,
				'poblacion' => $registro->poblacion,
				'descripcion_sintomas' => $registro->descripcion_sintomas,
				'envio_muestra' => $registro->envio_muestra,
				'resultado_inspeccion' => $registro->resultado_inspeccion,
				'numero_plantas_inspeccion' => $registro->numero_plantas_inspeccion,
				'observaciones' => $registro->observaciones,
				'usuario' => $registro->usuario,
				'usuario_id' => $registro->usuario_id,
				'fecha_creacion' => $registro->fecha_creacion,
				'id_tablet' => $registro->id_tablet,
				'tablet_id' => $registro->tablet_id,
				'tablet_version_base' => $registro->tablet_version_base,
			);

			$arrayCabecera[] = $campos;

		}

		foreach($seguimiento["ordenes"] as $registro){
			$campos = array( 
				'id' => $registro->id,
				'id_padre' => $registro->id_padre,
				'id_tablet' => $registro->id_tablet,
				'actividad_origen' => $registro->actividad_origen,
				'analisis' => $registro->analisis,
				'aplicacion_producto_quimico' => $registro->aplicacion_producto_quimico,
				'codigo_muestra' => $registro->codigo_muestra,
				'conservacion' => $registro->conservacion,
				'tipo_muestra' => $registro->tipo_muestra,
				'descripcion_sintomas' => $registro->descripcion_sintomas,
				'fase_fenologica' => $registro->fase_fenologica,
				'nombre_producto' => $registro->nombre_producto,
				'prediagnostico' => $registro->prediagnostico
			);

			$arrayOrdenes[] = $campos;

		}		

		$this->lNegocioControlf04->guardarSeguimiento($arrayCabecera,$arrayOrdenes);

	}
}


	

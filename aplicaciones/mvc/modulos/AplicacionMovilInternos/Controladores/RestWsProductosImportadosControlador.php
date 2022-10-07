<?php
/**
 * Controlador Alertas
 *
 * Este archivo controla la lógica del negocio del modelo: AlertasModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2022-07-07
 * @uses AplicacionesControlador
 * @package AplicacionMovilInternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilInternos\Controladores;

use Agrodb\FormulariosInspeccion\Modelos\Controlf01LogicaNegocio;

class RestWsProductosImportadosControlador extends BaseControlador{

	private $lNegocioControlF01 = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioControlF01 = new Controlf01LogicaNegocio();

		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
		
		set_error_handler(array(
			$this,
			'manejadorExcepcionesSincatch'));
	}
	
	
	/**
	 * Método de obtención de los DDA en estado inspección
	 */
	public function productosImportados($provincia, $tipoCertificado){
		$arrayParametros = array('provincia' => $provincia, 'tipo_certificado' => $tipoCertificado);
		$this->lNegocioControlF01->obtenerProductosImportados($arrayParametros);
	}

	/**
	 * Método para guardar los registros de Inspección de productos importados
	 */
	public function inspeccionPoductosImportados(){
		$inspeccion = (array) json_decode(file_get_contents('php://input'));

		$arrayCabecera = [];
		$arrayLotes = [];
		$arrayOrdenes = [];
		$arrayProductos = [];

		foreach($inspeccion["inspecciones"] as $registro){
			$campos = array (
				'id' => $registro->id,
				'id_tablet' => $registro->id_tablet,
				'dda' => $registro->dda,
				'pfi' => $registro->pfi,
				'dictamen_final' => $registro->dictamen_final,
				'observaciones' => $registro->observaciones,
				'envio_muestra' => $registro->envio_muestra,
				'usuario_id' => $registro->usuario_id,
				'usuario' => $registro->usuario,
				'fecha_inspeccion' => $registro->fecha_creacion,
				'tablet_id' => $registro->tablet_id,
				'tablet_version_base' => $registro->tablet_version,
				'pregunta01' => $registro->pregunta01,
				'pregunta02' => $registro->pregunta02,
				'pregunta03' => $registro->pregunta03,
				'pregunta04' => $registro->pregunta04,
				'pregunta05' => $registro->pregunta05,
				'pregunta06' => $registro->pregunta06,
				'pregunta07' => $registro->pregunta07,
				'pregunta08' => $registro->pregunta08,
				'pregunta09' => $registro->pregunta09,
				'pregunta10' => $registro->pregunta10,
				'pregunta11' => $registro->pregunta11,
				'categoria_riesgo' => $registro->categoria_riesgo,
				'seguimiento_cuarentenario' => $registro->seguimiento_cuarentenario,
				'provincia' => $registro->provincia,
				'peso_ingreso' => $registro->peso_ingreso,
				'numero_embalajes_envio' => $registro->numero_embalajes_envio,
				'numero_embalajes_inspeccionados' => $registro->numero_embalajes_inspeccionados,
			);

			$arrayCabecera[] = $campos;

		}

		foreach($inspeccion["lotes"] as $registro){
			$campos = array(	
				'id_tablet' => $registro->id_tablet,
				'id_padre' => $registro->control_f01_id,
				'descripcion' => $registro->descripcion,
				'numero_cajas' => $registro->numero_cajas,
				'cajas_muestra' => $registro->cajas_muestra,
				'porcentaje_inspeccion' => $registro->porcentaje_inspeccion,
				'ausencia_suelo' => $registro->ausencia_suelo,
				'ausencia_contaminantes' => $registro->ausencia_contaminantes,
				'ausencia_sintomas' => $registro->ausencia_sintomas,
				'ausencia_plagas' => $registro->ausencia_plagas,
				'dictamen' => $registro->dictamen,
				'control_f01_id' => $registro->control_f01_id,				
			);

			$arrayLotes[] = $campos;

		}

		foreach($inspeccion["ordenes"] as $registro){
			$campos = array(				
				'id_padre' => $registro->control_f01_id,
				'id_tablet' => $registro->id_tablet,
				'actividad_origen' => $registro->actividad_origen,
				'analisis' => $registro->analisis,
				'codigo_muestra' => $registro->codigo_muestra,
				'conservacion' => $registro->conservacion,
				'tipo_muestra' => $registro->tipo_muestra,
				'descripcion_sintomas' => $registro->descripcion_sintomas,
				'fase_fenologica' => $registro->fase_fenologica,
				'nombre_producto' => $registro->nombre_producto,
				'peso_muestra' => $registro->peso_muestra,
				'prediagnostico' => $registro->prediagnostico,
				'tipo_cliente' => $registro->tipo_cliente,			
			);

			$arrayOrdenes[] = $campos;

		}

		foreach($inspeccion["productos"] as $registro){
			$campos = array(
				'id_padre' => $registro->control_f01_id,			
				'id_tablet' => $registro->id_tablet,
				'nombre' => $registro->nombre,
				'cantidad_declarada' => $registro->cantidad_declarada,
				'cantidad_ingresada' => $registro->cantidad_ingresada,
				'unidad' => $registro->unidad,
				'subtipo' => $registro->subtipo,				
			);

			$arrayProductos[] = $campos;

		}

		$this->lNegocioControlF01->guardarInspeccion($inspeccion,$arrayCabecera,$arrayLotes,$arrayOrdenes,$arrayProductos);
	}
}
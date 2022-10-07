<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores
 *
 * @property AGROCALIDAD
 * @author Carlos Anchundia
 * @date      2021-03-24
 * @uses BaseControlador
 * @package PlagasLaboratorio
 * @subpackage Controladores
 */
namespace Agrodb\PlagasLaboratorio\Controladores;

session_start();

use Agrodb\Core\Comun;

class BaseControlador extends Comun{

	public $itemsFiltrados = array();

	public $codigoJS = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::usuarioActivo();
		// Si se requiere agregar código concatenar la nueva cadena con ejemplo $this->codigoJS.=alert('hola');
		$this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
	}

	public function crearTabla(){
		$tabla = "//No existen datos para mostrar...";
		if (count($this->itemsFiltrados) > 0){
			$tabla = '$(document).ready(function() {
			construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			$("#listadoItems").removeClass("comunes");
			});';
		}

		return $tabla;
	}
	
	public function imprimirLineaPlaga($arrayDatos){
		
		$linea = null;
		
		if(isset($arrayDatos)){
			
			foreach ($arrayDatos as $fila){
				
				$linea .= '<tr id="RP' . $fila['id_plaga'] . '">' .
				'<td width="100%">' . $fila['nombre_cientifico'] . '</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="' . URL_MVC_FOLDER . 'PlagasLaboratorio\plagas" data-opcion="abrir" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="id_plaga" value="' . $fila['id_plaga'] . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
			}
		}
		
		return $linea;
	}
	
	public function imprimirLineaDetallePlaga($arrayDatos){
		
		$linea = null;
		
		if(isset($arrayDatos)){
			
			foreach ($arrayDatos as $fila){
				
				$linea .= '<tr>
								<td>' . $fila['identificado_por'] . '</td>' .
								'<td>' . $fila['nombre_provincia'] . '</td>' .
								'<td>' . $fila['numero_reporte'] . '</td>' .
								'<td>' . $fila['fecha_ingreso'] . '</td>' .
					'</tr>';
			}
		}
		
		return $linea;
	}
}

<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores
 *
 * @property AGROCALIDAD
 * @author Carlos Anchundia
 * @date      2021-07-06
 * @uses BaseControlador
 * @package Importaciones
 * @subpackage Controladores
 */
namespace Agrodb\RevisionSolicitudesVue\Controladores;

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

	/**
	 * Construye el código HTML para desplegar panel de busqueda
	 */
	public function cargarPanelBusquedaSolicitud(){
		
		$this->panelBusqueda = '<table class="filtro" style="width: 400px;">
                        				<tbody>
											<tr>
												<th colspan="2">Buscar:</th>
											</tr>
                        					<tr >
                        						<td>Número solicitud:</td>
                        						<td>
                        							<input id="id_vue" type="text" name="id_vue" style="width: 100%">
                        						</td>
                        					</tr>
                                            <tr></tr>
                        					<tr>
                        						<td colspan="3">
                        							<button id="btnFiltrar">Buscar</button>
                        						</td>
                        					</tr>
                        				</tbody>
                        			</table>';
	}
}

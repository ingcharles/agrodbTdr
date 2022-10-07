<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores
 *
 * @property AGROCALIDAD
 * @author Carlos Anchundia
 * @date      2020-06-09
 * @uses BaseControlador
 * @package JornadaLaboral
 * @subpackage Controladores
 */
namespace Agrodb\JornadaLaboral\Controladores;

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
	
	/*
	 * Panel de busqueda de registros de horrio de funcionarios.
	 * */
	
	public function cargarPanelBusqueda() {
		
		$this->panelBusquedaUsuario= '<table class="filtro" style="width: 450px;">
                            				<tbody>
                                                <tr>
                                                    <th colspan="2">Buscar:</th>
                                                </tr>
                            					<tr  style="width: 100%;">
                            						<td >Cédula: </td>
                            						<td>
                            							<input id="identificador" type="text" name="identificador" class="validacion" style="width: 100%" >
                            						</td>
                            					</tr>
                                                <tr  style="width: 100%;">
                            						<td style="width: 30%;">Estado: </td>
													<td>
														<select id="estado_registro" name="estado_registro" class="validacion" style="width: 100%;">
															'.$this->comboActivoInactivo('') .'
														</select>
													</td>
                            					</tr>
                                                <tr  style="width: 100%;">
                            						<td >Apellidos: </td>
                            						<td>
                            							<input id="apellido" type="text" name="apellido" class="validacion" style="width: 100%" >
                            						</td>
                            					</tr>
                                                <tr  style="width: 100%;">
                            						<td >Nombres: </td>
                            						<td>
                            							<input id="nombre" type="text" name="nombre" class="validacion" style="width: 100%" >
                            						</td>
                            					</tr>
                                                <tr  style="width: 100%;">
                            						<td >Área pertenece: </td>
                            						<td>
                            							<select id="area" name="area" class="validacion" style="width: 100%;">
															'.$this->obtenerAreasDireccionesTecnicas('') .'
														</select>
                            						</td>
                            					</tr>
                            					<tr>
                            						<td colspan="3" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
		;
	}
}

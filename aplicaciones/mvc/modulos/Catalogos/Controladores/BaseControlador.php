<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores
 *
 * @property AGROCALIDAD
 * @author Carlos Anchundia
 * @date      2021-06-23
 * @uses BaseControlador
 * @package AdministracionCatalogosRIA
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

session_start();

use Agrodb\Core\Comun;

class BaseControlador extends Comun{

	public $itemsFiltrados = array();

	public $codigoJS = null;
	
	public $fecha = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::usuarioActivo();
		// Si se requiere agregar código concatenar la nueva cadena con ejemplo $this->codigoJS.=alert('hola');
		$this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
		
		$this->fecha = str_replace(' ', '', date('Y-m-d - H-i-s'));
		
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
	 * Combo de dos estados ACTIVO/INACTIVO
	 *
	 * @param type $respuesta
	 * @return string
	 */
	public function comboActivoInactivoAC($opcion = null){
		$combo = "";
		if ($opcion == "activo"){
			$combo .= '<option value="activo" selected="selected">activo</option>';
			$combo .= '<option value="inactivo">inactivo</option>';
		}else if ($opcion == "Inactivo"){
			$combo .= '<option value="activo" >activo</option>';
			$combo .= '<option value="inactivo" selected="selected">inactivo</option>';
		}else{
			$combo .= '<option value="" selected="selected">Seleccionar...</option>';
			$combo .= '<option value="activo" >activo</option>';
			$combo .= '<option value="inactivo">inactivo</option>';
		}
		return $combo;
	}
	
	public function imprimirLineaRegistroSubTipoProducto($arrayDatos){
		
		$linea = null;
		
		if(isset($arrayDatos)){
			
			foreach ($arrayDatos as $fila){
				
				$linea .= '<tr id="ST' . $fila['id_subtipo_producto'] . '">' .
					'<td width="100%">' . $fila['nombre'] . '</td>' .
					'<td>' .
					'<form class="abrir" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/SubtipoProductos" data-opcion="editar" data-destino="detalleItem" data-accionEnExito="NADA" >' .
					'<input type="hidden" name="id_subtipo_producto" value="' . $fila['id_subtipo_producto'] . '" >' .
					'<button class="icono" type="submit" ></button>' .
					'</form>' .
					'</td>' .
					'</tr>';
			}
		}
		
		return $linea;
	}
	
	public function imprimirLineaRegistroProducto($arrayDatos){
		
		$linea = null;
		
		if(isset($arrayDatos)){
			
			foreach ($arrayDatos as $fila){
				
				$linea .= '<tr id="P' . $fila['id_producto'] . '">' .
					'<td width="100%">' . $fila['nombre_comun'] . '</td>' .
					'<td>' .
					'<form class="abrir" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/Productos" data-opcion="editar" data-destino="detalleItem" data-accionEnExito="NADA" >' .
					'<input type="hidden" name="id_producto" value="' . $fila['id_producto'] . '" >' .
					'<button class="icono" type="submit" ></button>' .
					'</form>' .
					'</td>' .
					'</tr>';
			}
		}
		
		return $linea;
	}
	
	public function imprimirLineaRegistroParametro($arrayDatos){
		
		$linea = null;
		
		if(isset($arrayDatos)){
			
			foreach ($arrayDatos as $fila){
				
				$linea .= '<tr id="Pa' . $fila['id_parametro'] . '">' .
					'<td width="100%">' . $fila['descripcion'] . '</td>' .
					'<td>' .
					'<form class="abrir" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/Parametros" data-opcion="editar" data-destino="detalleItem" data-accionEnExito="NADA" >' .
					'<input type="hidden" name="id_parametro" value="' . $fila['id_parametro'] . '" >' .
					'<button class="icono" type="submit" ></button>' .
					'</form>' .
					'</td>' .
					'</tr>';
			}
		}
		
		return $linea;
	}
	
	public function imprimirLineaRegistroMetodo($arrayDatos){
		
		$linea = null;
		
		if(isset($arrayDatos)){
			
			foreach ($arrayDatos as $fila){
				
				$linea .= '<tr id="M' . $fila['id_metodo'] . '">' .
					'<td width="100%">' . $fila['descripcion'] . '</td>' .
					'<td>' .
					'<form class="abrir" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/Metodos" data-opcion="editar" data-destino="detalleItem" data-accionEnExito="NADA" >' .
					'<input type="hidden" name="id_metodo" value="' . $fila['id_metodo'] . '" >' .
					'<button class="icono" type="submit" ></button>' .
					'</form>' .
					'</td>' .
					'</tr>';
			}
		}
		
		return $linea;
	}
	
	public function imprimirLineaRegistroRango($arrayDatos){
		
		$linea = null;
		
		if(isset($arrayDatos)){
			
			foreach ($arrayDatos as $fila){
				
				$linea .= '<tr id="R' . $fila['id_rango'] . '">' .
					'<td width="100%">' . $fila['descripcion'] . '</td>' .
					'<td>' .
					'<form class="abrir" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/Rangos" data-opcion="editar" data-destino="detalleItem" data-accionEnExito="NADA" >' .
					'<input type="hidden" name="id_rango" value="' . $fila['id_rango'] . '" >' .
					'<button class="icono" type="submit" ></button>' .
					'</form>' .
					'</td>' .
					'</tr>';
			}
		}
		
		return $linea;
	}
}

<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores
 *
 * @property AGROCALIDAD
 * @author Carlos Anchundia
 * @date      2021-07-13
 * @uses BaseControlador
 * @package ProveedoresExterior
 * @subpackage Controladores
 */
namespace Agrodb\ProveedoresExterior\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\ProductosProveedorModelo;
use Agrodb\ProveedoresExterior\Modelos\ProveedorExteriorModelo;
use Agrodb\ProveedoresExterior\Modelos\ProveedorExteriorLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\DocumentosAdjuntosLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\ProductosProveedorLogicaNegocio;

class BaseControlador extends Comun{

	public $itemsFiltrados = array();

	public $codigoJS = null;

	public $informacionSocilitante = null;

	public $documentos = null;

	public $productosProveedorExterior = null;

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
	 * Metodo para obtener y construir los datos del operador
	 */
	public function construirDatosOperador($identificadorOperador, $nombreProvinciaOperador){
		$arrayParametros = array(
			'identificador' => $identificadorOperador);

		$operadores = new OperadoresLogicaNegocio();

		$datosOperador = $operadores->obtenerDatosOperadores($arrayParametros);

		if (isset($datosOperador->current()->identificador)){

			$this->informacionSocilitante = '<div data-linea="1">
                                    			 <label for="identificador_operador">RUC/RISE: </label>' . $datosOperador->current()->identificador . '
                                        		</div>
                                    			     
                                        		<div data-linea="1">
                                        			<label for="razon_social_operador">Razon social: </label>' . $datosOperador->current()->nombre_operador . '
                                        		</div>
                                        			    
                                        		<div data-linea="2">
                                        			<label for="direccion_operador">Direccion: </label>' . $datosOperador->current()->direccion . '
                                        		</div>
                                        			    
                                        		<div data-linea="3">
                                        			<label for="nombre_provincia_operador">Provincia: </label>' . $nombreProvinciaOperador . '
                                        		</div>
                                        			    
                                        		<div data-linea="4">
                                        			<label for="telefono_operador">Teléfono: </label>' . $datosOperador->current()->telefono . '
                                        		</div>
                                        			    
                                        		<div data-linea="4">
                                        			<label for="celular_operador">Celular: </label>' . $datosOperador->current()->celular . '
                                        		</div>
                                        			    
                                        		<div data-linea="5">
                                        			<label for="correo_electronico_operador">Correo: </label>' . $datosOperador->current()->correo . '
                                        		</div>
                                        			    
                                        		<div data-linea="6">
                                        			<label for="representante_legal_operador">Representante legal: </label>' . $datosOperador->current()->representante_legal . '
                                        		</div>';

			$this->informacionSocilitante;
		}
	}

	/**
	 * Método para desplegar los documentos adjuntos cargados por el operador en el formulario
	 */
	public function desplegarDocumentosAdjuntos($arrayParametros){
		$documentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();

		$query = "id_proveedor_exterior = '" . $arrayParametros['id_proveedor_exterior'] . "' and estado_adjunto = 'Activo' ORDER BY id_documento_adjunto ASC";
		$arrayDocumentos = $documentosAdjuntos->buscarLista($query);

		$this->documentos = '<table style="width: 100%;">
								<tr>
										<td><label>#</label></td>
										<td><label>Nombre</label></td>
										<td><label>Enlace</label></td>
									</tr>';
		$i = 1;
		foreach ($arrayDocumentos as $documento){

			if ($documento['ruta_adjunto'] != "0"){

				$this->documentos .= '<tr>
						  			<td>' . $i . '</td>
									<td>' . $documento['tipo_adjunto'] . '</td>
									<td>
										<a href="' . $documento['ruta_adjunto'] . '" target="_blank">Archivo</a>
									</td>
						 		</tr>';
				$i ++;
			}
		}

		$this->documentos .= '</table>';
	}

	/**
	 * Método para listar los tipos de productos registrados para el proveedor
	 */
	public function construirDetalleProductosProveedor($arrayParametros, $procesoModificacion = false){
		$productosProveedor = new ProductosProveedorLogicaNegocio();
		$qProductosProveedor = $productosProveedor->buscarLista($arrayParametros);

		$td = "";
		$i = 1;

		foreach ($qProductosProveedor as $filaProductosProveedor){

			if ($procesoModificacion){
				$td = '<td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDetalleProductosProveedor(' . $filaProductosProveedor['id_producto_proveedor'] . '); return false;"/></td>';
			}

			$this->productosProveedorExterior .= '<tr id="fila' . $filaProductosProveedor['id_producto_proveedor'] . '">
                                            <td>' . $i ++ . '</td>
                                            <td>' . $filaProductosProveedor['nombre_subtipo_producto'] . '</td>' . $td . '</tr>';
		}

		$this->productosProveedorExterior;
	}
}

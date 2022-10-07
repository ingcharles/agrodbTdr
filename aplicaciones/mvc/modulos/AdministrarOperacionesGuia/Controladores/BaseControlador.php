<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores
 *
 * @property AGROCALIDAD
 * @author Carlos Anchundia
 * @date      2020-09-18
 * @uses BaseControlador
 * @package AdministrarOperacionesGuia
 * @subpackage Controladores
 */
namespace Agrodb\AdministrarOperacionesGuia\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
use Agrodb\Usuarios\Modelos\PerfilesLogicaNegocio;

class BaseControlador extends Comun{

	public $itemsFiltrados = array();

	public $codigoJS = null;

	public $panelBusqueda = null;

	public $perfilUsuario = null;

	public $estadoOperacion = null;

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

	public function filtroOperaciones(){
		// 1791782062001
		$tipoOperacion = '<option value="">Seleccione...</option>';
		if (in_array('PFL_TEC_PC', $this->perfilUsuario)){
			$provincia = ' <tr  style="width: 100%;">
    						<td >Provincia: </td>
    						<td colspan="3" >
    							<select style="width:185px;" id="provincia" name= "provincia">
                                <option value="">Seleccione...</option>
                    				' . $this->comboProvinciasEc() . '
                    			</select>
    						</td>
    					</tr>';
		}else{
			$arrayParametros = array(
				'identificador_operador' => $_SESSION['usuario'],
				'estado' => "in ('registrado','noHabilitado')",
				'provincia' => $_SESSION['nombreProvincia'],
				'id_area' => 'AI');
			$tipoOperacion = $this->comboTipoOperacion($arrayParametros);
			$provincia = '';
		}
		$this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar por:</th>
	                                                </tr>
	                                                <tr  style="width: 100%;">
	                            						<td >RUC / CI:</td>
	                            						<td colspan="3">
	                            							<input id="identificadorFiltro" type="text" name="identificadorFiltro" value="" >
	                            						</td>
	        
	                            					</tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Razón Social: </td>
	                            						<td colspan="3">
	                            							<input id="razonSocial" type="text" name="razonSocial" value="" >
	                            						</td>
	                            					</tr>
	                                                ' . $provincia . '
	                                                <tr  style="width: 100%;">
	                            						<td >Tipo operación: </td>
	                            						<td colspan="3">
                                                            <select style="width:185px;" id="tipoOperacion" name= "tipoOperacion">
                                                                     ' . $tipoOperacion . '
                    			                             </select>
	                            						</td>

	                            					</tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}

	/**
	 * *
	 * cargar perfiles de usuario
	 */
	public function perfilUsuario($codPerfil = null){
		$lNegocioPerfiles = new PerfilesLogicaNegocio();
		$consulta = $lNegocioPerfiles->verificarPerfil($_SESSION['usuario'], 'PRG_ADM_OPR_GUIA', $codPerfil);
		foreach ($consulta as $value){
			$this->perfilUsuario[] = $value->codificacion_perfil;
		}
	}

	function reemplazarCaracteres($cadena){
		$cadena = str_replace('á', 'a', $cadena);
		$cadena = str_replace('é', 'e', $cadena);
		$cadena = str_replace('í', 'i', $cadena);
		$cadena = str_replace('ó', 'o', $cadena);
		$cadena = str_replace('ú', 'u', $cadena);
		$cadena = str_replace('ñ', 'n', $cadena);

		$cadena = str_replace('Á', 'A', $cadena);
		$cadena = str_replace('É', 'E', $cadena);
		$cadena = str_replace('Í', 'I', $cadena);
		$cadena = str_replace('Ó', 'O', $cadena);
		$cadena = str_replace('Ú', 'U', $cadena);
		$cadena = str_replace('Ñ', 'N', $cadena);

		return $cadena;
	}

	/**
	 * **
	 * datos generales del exportador
	 */
	public function datosGenerales($identificador){
		$lNegocioOperaciones = new OperacionesLogicaNegocio();

		$operador = $lNegocioOperaciones->obtenerOperador($identificador);
		$html = '';
		foreach ($operador as $fila){
			$item = (array) (json_decode($fila['row_to_json']));
			$html = '<fieldset>
                	    <legend>
                	    Datos generales
                	    </legend>
                	    <div data-linea="1">
                	    <h2>Razón social: ' . $item['razon_social'] . '</h2>
                    </div>
                    <div data-linea="3">
                        <label>RUC/CI:</label>
                        <span>' . $item['identificador'] . '</span>
                        <span>(Persona ' . $item['tipo_operador'] . ')</span>
                    </div>
                    <div data-linea="5">
                        <label>Representante legal: </label>
                        <span>' . $item['apellido_representante'] . ', ' . $item['nombre_representante'] . '</span>
                    </div>
                            
                    <div data-linea="7">
                        <label>Dirección (según RUC): </label>
                        <span>' . $item['provincia'] . ' - ' . $item['canton'] . ' (' . $item['parroquia'] . '), ' . $item['direccion'] . '</span>
                    </div>
                    <hr/>
                    <div data-linea="9">
                        <label>Teléfonos:</label>
                        <span>' . '[TF1]: <u>' . $item['telefono_uno'] . '</u>' . ' | [TF2]: <u>' . $item['telefono_dos'] . '</u>' . ' | [FAX]: <u>' . $item['fax'] . '</u>' . ' | [CL1]: <u>' . $item['celular_uno'] . '</u>' . ' | [CL2]: <u>' . $item['celular_dos'] . '</u>
                        </span>
                    </div>
                    <hr/>
                    <div data-linea="11">
                        <label>Correo electrónico:</label>
                        <span>' . $item['correo'] . '</span>
                    </div>
                    <hr/>
                    <div data-linea="13">
                        <label>Registro de orquídeas:</label>
                        <span>' . $item['registro_orquideas'] . '</span>
                    </div>
                    <div data-linea="13">
                        <label>Registro de madera:</label>
                        <span>' . $item['registro_madera'] . '</span>
                    </div>
                    <div data-linea="13">
                        <label>Código GS1:</label>
                        <span>' . $item['gs1'] . '</span>
                    </div>
                    <hr/>
                    <div data-linea="14">
                        <label>Representante técnico: </label>
                        <span>' . $item['apellido_tecnico'] . ', ' . $item['nombre_tecnico'] . '</span>
                    </div>';

			if (isset($item['ruta_poa'])){

				$html .= '<hr/>
                    			<div data-linea="15">
                	        		<label>Certificado POA: </label>
                	        		<span><a href=' . $item['ruta_poa'] . ' target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar certificado de Registro de Operador Orgánico</a></span>
                	    		</div>';
			}
			$html .= '</fieldset>';
		}
		return $html;
	}

	public function sitiosAreas($identificadorOperador, $idOperacion){
		$lNegocioOperaciones = new OperacionesLogicaNegocio();
		$arrayParametros = array(
			'identificadorOperador' => $identificadorOperador,
			'idOperacion' => $idOperacion);
		$operador = $lNegocioOperaciones->abrirOperacion($arrayParametros);
		$html = '';
		foreach ($operador as $item){
			$html .= '
        	<fieldset>
        	<legend>Datos del sitio y área</legend>
        	<div data-linea="5">
                        <input type="hidden" name="id_tipo_operacion" id="id_tipo_operacion" value="' . $item['id_tipo_operacion'] . '">
        	            <label>Nombre del sitio: </label> ' . $item['sitio'] . '</div>
        				<div data-linea="5">
        					<label>Provincia: </label> ' . $item['provincia'] . '</div>
        				<div data-linea="6">
        					<label>Cantón: </label> ' . $item['canton'] . '</div>
        				<div data-linea="6">
        					<label>Parroquia: </label> ' . $item['parroquia'] . '</div>
        				<div data-linea="7">
        					<label>Dirección: </label> ' . $item['direccion'] . '</div>
        				<div data-linea="8">
        					<label>Referencia: </label> ' . $item['referencia'] . '</div>
        				<hr>
        				<div data-linea=9>
        					<label>Nombre del área: </label> ' . $item['area'] . '</div>
        				<div data-linea=9>
        					<label>Código del área: </label>' . $item['codificacion_area'] . '</div>
        				<div data-linea=10>
        					<label>Tipo de área: </label>' . $item['tipo_area'] . '</div>
        					    
        				<div data-linea=10>
        					<label>Superficie utilizada: </label>' . $item['superficie_utilizada'] . '</div>';
		}
		$html .= '</fieldset>';
		return $html;
	}

	/**
	 */
	public function medioTransporte($identificadorOperador, $idOperacion){
		$lNegocioOperaciones = new OperacionesLogicaNegocio();
		$arrayParametros = array(
			'identificadorOperador' => $identificadorOperador,
			'idOperacion' => $idOperacion);
		$operador = $lNegocioOperaciones->abrirOperacion($arrayParametros);
		$html = '';
		foreach ($operador as $item){
			$arrayParametros = array(
				'id_area' => $item['id_area'],
				'id_tipo_operacion' => $item['id_tipo_operacion'],
				'id_operador_tipo_operacion' => $item['id_operador_tipo_operacion'],
				'estado' => 'activo');
			$transporte = $lNegocioOperaciones->listarDatosVehiculoXIdAreaXidTipoOperacion($arrayParametros);
		}

		$html = '';
		foreach ($transporte as $item){
			$marca = (isset($item['marca']) && $item['marca']!='') ? ($item['marca']) : ("N/A");
			$modelo = (isset($item['modelo']) && $item['modelo']!='') ? ($item['modelo']) : ("N/A");
			$clase = (isset($item['clase']) && $item['clase']!='') ? $item['clase'] : ("N/A");
			$color = (isset($item['colorvehiculo']) && $item['colorvehiculo']!='') ? ($item['colorvehiculo']) : ("N/A");
			$tipo = (isset($item['tipovehiculo']) && $item['tipovehiculo']!='') ? ($item['tipovehiculo']) : ("N/A");
			$placa = (isset($item['placa_vehiculo']) && $item['placa_vehiculo']!='') ? ($item['placa_vehiculo']) : ("N/A");
			$anio = (isset($item['anio_vehiculo']) && $item['anio_vehiculo']!='') ? ($item['anio_vehiculo']) : ("N/A");
			$html .= '
        	<fieldset>
        	<legend>Datos del medio de transporte</legend>
        	            <div data-linea="5">
        	            <label>*Marca: </label> ' . $marca . '</div>
        				<div data-linea="5">
        					<label>*Modelo: </label> ' . $modelo  . '</div>
        				<div data-linea="6">
        					<label>*Clase: </label> ' . $clase . '</div>
        				<div data-linea="6">
        					<label>*Color: </label> ' . $color . '</div>
        				<div data-linea="7">
        					<label>*Tipo: </label> ' . $tipo . '</div>
        				<div data-linea="7">
        					<label>*Placa: </label> ' . $placa . '</div>
        				<div data-linea="8">
        					<label>*Año: </label> ' . $anio . '</div>
        				<div data-linea="8">
        					<label>*Capacidad instalada: </label>' . $item['capacidad_vehiculo'] . '</div>
        				<div data-linea="9">
        					<label>*Unidad: </label>' . $item['codigo_unidad_medida'] . '</div>';
		}
		$html .= '</fieldset>';
		return $html;
	}

	public function datosOperacion($identificadorOperador, $idOperacion){
		$lNegocioOperaciones = new OperacionesLogicaNegocio();
		$arrayParametros = array(
			'identificadorOperador' => $identificadorOperador,
			'idOperacion' => $idOperacion);
		$operador = $lNegocioOperaciones->abrirOperacion($arrayParametros);
		$html = '';
		foreach ($operador as $item){
			$arrayParametros = array(
				'id_operador_tipo_operacion' => $item['id_operador_tipo_operacion'],
				'id_historial_operacion' => $item['id_historial_operacion'],
				'estado' => $item['estado']);
			$productos = $lNegocioOperaciones->obtenerProductosPorIdOperadorTipoOperacionHistorico($arrayParametros);
			$this->estadoOperacion = $item['estado'];
			$html = ' <legend>Datos de la operación </legend>	';

			if ($productos->count() != 0){
				$html .= '
        			<table style="width: 100%">
        			<thead>
        				<tr>
        					<th>#</th>
        					<th>Tipo producto</th>
        					<th>Subtipo producto</th>
        					<th>Producto</th>
        					<th>Código</th> 
                            <th></th>
        				</tr>
        			</thead>
        			<tbody>
        			<?php
                    ';
				$contadorProducto = 0;
				foreach ($productos as $fila){
					$html .= '<tr><td>' . ++ $contadorProducto . '</td>
                              <td>' . $fila['nombre_tipo'] . '</td>
                              <td>' . $fila['nombre_subtipo'] . '</td>
                              <td>' . $fila['nombre_comun'] . '</td>
    						  <td>' . $fila['id_operacion'] . '</td>
                              ';
					// if($fila['estado'] == 'registrado'){
					// $html .= '<td><input type="checkbox" checked id="'.$fila['id_operacion'].'" value="'.$fila['id_operacion'].'" name="check[]" onclick="limpiarResultado(id);"/> </td></tr>';
					// }else{
					$html .= '<td><input type="checkbox"   id="' . $fila['id_operacion'] . '" value="' . $fila['id_operacion'] . '" name="check[]" onclick="limpiarResultado(id);"/> </td></tr>';

					// }
				}

				$html .= '</tbody>
    		</table><hr>';
				$html .= '
                   <div data-linea="2">
                    <span>Seleccionar:  </span>
        		    <input  name="resultado[]" type="radio"  id="total"   value="total" onclick="verificarOpcion(id);"><span> Todo</span>&nbsp;&nbsp;&nbsp;&nbsp;
        			<input  name="resultado[]" type="radio"  id="parcial" value="parcial" onclick="verificarOpcion(id);"><span> Parcial</span>&nbsp;&nbsp;&nbsp;&nbsp;
		          </div>	
                ';
			}
		}
		return $html;
	}

	public function comboTipoOperacion($arrayParametros){
		$lNegocioOperaciones = new OperacionesLogicaNegocio();
		$combo = $lNegocioOperaciones->obtenerTipoOperacionesOperador($arrayParametros);
		$opcionesHtml = '<option value="">Seleccione...</option>';
		foreach ($combo as $item){
			$opcionesHtml .= '<option value="' . $item->codigo . '">' . $item->operaciones_registradas . '</option>';
		}
		return $opcionesHtml;
	}

	public function comboResultado($dato = null){
		$arrayDatos = array(
			'Habilitado' => 'registrado',
			'Inhabilitado' => 'noHabilitado');

		$opcionesHtml = '<option value="">Seleccione...</option>';
		foreach ($arrayDatos as $item => $valor){
			if ($dato != $valor){
				$opcionesHtml .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $opcionesHtml;
	}
}

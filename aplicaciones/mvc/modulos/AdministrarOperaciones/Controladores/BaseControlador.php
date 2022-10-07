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
namespace Agrodb\AdministrarOperaciones\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
use Agrodb\Usuarios\Modelos\PerfilesLogicaNegocio;

class BaseControlador extends Comun{

	public $itemsFiltrados = array();

	public $codigoJS = null;

	public $panelBusqueda = null;

	public $perfilUsuario = null;

	

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

	
}

<?php

 /**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2020-09-18
 * @uses      BaseControlador
 * @package   InspeccionMusaceas
 * @subpackage Controladores
 */
namespace Agrodb\InspeccionMusaceas\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\InspeccionMusaceas\Modelos\SolicitudInspeccionLogicaNegocio;
use Agrodb\InspeccionMusaceas\Modelos\DetalleSolicitudInspeccionLogicaNegocio;
 
 
class BaseControlador extends Comun
{
	public $itemsFiltrados = array();
	public $codigoJS = null;
	public $panelBusqueda = null;
	public $perfilUsuario = array();

	/**
	* Constructor
	*/
	function __construct() {
	    if(PHP_SAPI!=='cli'){
	        parent::usuarioActivo();
	    }
		//Si se requiere agregar código concatenar la nueva cadena con  ejemplo $this->codigoJS.=alert('hola');
		$this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
	}
	public function crearTabla() {
		$tabla = "//No existen datos para mostrar...";
		if (count($this->itemsFiltrados) > 0) {
			$tabla = '$(document).ready(function() {
			construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			$("#listadoItems").removeClass("comunes");
			});';
		}

	return $tabla;
	}
	/**
	 * combo productos
	 */
	public function comboProductos($producto=null){
	    $combo = '<option value="">Seleccionar....</option>';
	    $arrayProductos = array('banano','orito','plátano','banano morado');
	    foreach ($arrayProductos as $item) {
	        if ($producto == $item)
	        {
	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    
	    return $combo;
	}
	/**
	 * combo productos
	 */
	public function comboTipoProduccion($tipoProduccion=null){
	    $combo = '<option value="">Seleccionar....</option>';
	    $arrayProductos = array('Convencional','Orgánico');
	    foreach ($arrayProductos as $item) {
	        if ($tipoProduccion == $item)
	        {
	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    
	    return $combo;
	}
	/**
	 * combo productos
	 */
	public function comboInspeccion($lugar=null){
	    $combo = '<option value="">Seleccionar....</option>';
	    $arrayProductos = array('Puerto','Acopio','Lugar de producción');
	    foreach ($arrayProductos as $item) {
	        if ($lugar == $item)
	        {
	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    
	    return $combo;
	}
	/****
	 * datos generales del exportador
	 */
	public function datosGenerales($identificador){
	    
	    $lNegocioSolicitudInspeccion = new SolicitudInspeccionLogicaNegocio();
	    
	    $operador = $lNegocioSolicitudInspeccion->obtenerOperador($identificador);
	    $html='';
	    foreach ($operador as $fila) {
	        $item = (array)(json_decode($fila['row_to_json']));
	        $html = '<fieldset>
                	    <legend>
                	    Datos generales
                	    </legend>
                	    <div data-linea="1">
                	    <h2>Razón social: '. $item['razon_social'].'</h2>
                    </div>
                    <div data-linea="3">
                        <label>RUC/CI:</label>
                        <span>'. $item['identificador'] .'</span>
                        <span>(Persona '. $item['tipo_operador'] .')</span>
                    </div>
                    <div data-linea="5">
                        <label>Representante legal: </label>
                        <span>'. $item['apellido_representante'] . ', ' . $item['nombre_representante'] .'</span>
                    </div>
                            
                    <div data-linea="7">
                        <label>Dirección (según RUC): </label>
                        <span>'. $item['provincia'] . ' - ' . $item['canton'] . ' (' . $item['parroquia'] . '), ' . $item['direccion'] .'</span>
                    </div>
                    <hr/>
                    <div data-linea="9">
                        <label>Teléfonos:</label>
                        <span>'. '[TF1]: <u>' . $item['telefono_uno'] . '</u>' .
                        ' | [TF2]: <u>' . $item['telefono_dos'] . '</u>' .
                        ' | [FAX]: <u>' . $item['fax'] . '</u>' .
                        ' | [CL1]: <u>' . $item['celular_uno'] . '</u>' .
                        ' | [CL2]: <u>' . $item['celular_dos'] . '</u>
                        </span>
                    </div>
                    <hr/>
                    <div data-linea="11">
                        <label>Correo electrónico:</label>
                        <span>'. $item['correo'] .'</span>
                    </div>
                    <hr/>
                    <div data-linea="13">
                        <label>Registro de orquídeas:</label>
                        <span>'. $item['registro_orquideas'] .'</span>
                    </div>
                    <div data-linea="13">
                        <label>Registro de madera:</label>
                        <span>'. $item['registro_madera'] .'</span>
                    </div>
                    <div data-linea="13">
                        <label>Código GS1:</label>
                        <span>'. $item['gs1'] .'</span>
                    </div>
                    <hr/>
                    <div data-linea="14">
                        <label>Representante técnico: </label>
                        <span>'. $item['apellido_tecnico'] . ', ' . $item['nombre_tecnico'] .'</span>
                    </div>';
	        
	        if (isset($item['ruta_poa'])){
	            
	            $html .= '<hr/>
                    			<div data-linea="15">
                	        		<label>Certificado POA: </label>
                	        		<span><a href='.$item['ruta_poa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar certificado de Registro de Operador Orgánico</a></span>
                	    		</div>';
	        }
	        $html .= '</fieldset>';
	    }
	    return $html;
	}
	/**
	 * Consulta los paises
	 *          *
	 * @param Integer $idLocalizacion
	 * @return string
	 */
	public function cargarPaises($idLocalizacion = null)
	{
	    $lNegocioSolicitudInspeccion = new SolicitudInspeccionLogicaNegocio();
	    $paises = "";
	    $combo = $lNegocioSolicitudInspeccion->obtenerPaises();
	    $paises = '<option value="">Seleccionar....</option>';
	    foreach ($combo as $item)
	    {
	        if ($idLocalizacion == $item['id_localizacion'])
	        {
	            $paises .= '<option value="' . $item->id_localizacion . '" selected>' . $item->nombre . '</option>';
	        } else
	        {
	            $paises .= '<option value="' . $item->id_localizacion . '">' . $item->nombre . '</option>';
	        }
	    }
	    return $paises;
	}
	/**
	 * Cargar los puertos
	 *          *
	 * @param Integer $idPuerto
	 * @return string
	 */
	public function cargarPuertos($idPais=null, $idPuerto=null)
	{
	    $lNegocioSolicitudInspeccion = new SolicitudInspeccionLogicaNegocio();
	    $puertos = "";
	    $combo = $lNegocioSolicitudInspeccion->obtenerPuertos($idPais);
	    $puertos = '<option value="">Seleccionar....</option>';
	    foreach ($combo as $item)
	    {
	        if ($idPuerto == $item['id_puerto'])
	        {
	            $puertos .= '<option value="' . $item->id_puerto . '" selected>' . $item->nombre_puerto . '</option>';
	        } else
	        {
	            $puertos .= '<option value="' . $item->id_puerto . '">' . $item->nombre_puerto . '</option>';
	        }
	    }
	    return $puertos;
	}
	
	
	//**************crear html de lista de productores**************************
	public function listarProductores($idSolicitudInspeccion, $opt=null){
	    $lNegocioDetalleSolicitudInspeccion = new DetalleSolicitudInspeccionLogicaNegocio();
	    $html=$datos='';
	    $resultado =  $lNegocioDetalleSolicitudInspeccion->buscarLista("id_solicitud_inspeccion=".$idSolicitudInspeccion." order by 1");
	    foreach ($resultado as $item) {
	        $datos .= '<tr>';
	        $datos .= '<td>'.$item->razon_social.'</td>';
	        $datos .= '<td>'.$item->provincia.'</td>';
	        $datos .= '<td>'.$item->area.'</td>';
	        $datos .= '<td>'.$item->codigo_mag.'</td>';
	        $datos .= '<td>'.$item->num_cajas.' </td>';
	        if($opt != null){
	        $datos .= '<td><input type="checkbox" id="'.$item->id_detalle_solicitud_inspeccion.'" value="'.$item->id_detalle_solicitud_inspeccion.'" name="check[]" onclick="limpiarResultado(id);"/> </td>';
	        }$datos .= '<tr>';
	    }
	    $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Razón Social</th>
                        <th>Provincia</th>
						<th>Área (Finca)</th>
						<th>Código</th>
                        <th>Cajas</th>
                        <th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>
           ';
	    return $html;
	}
	public function filtroSolicitud($opt){
	    switch ($opt) {
	        case 1:
	            $this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar solicitud:</th>
	                                                </tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Número de solicitud: </td>
	                            						<td colspan="3">
	                            							<input id="numeroSolicitud" type="text" name="numeroSolicitud" value="" >
	                            						</td>
	                
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Estado Solicitud: </td>
	                            						<td colspan="3">
                                                            <select style="width: 100%;" id="estadoSolicitud" name= "estadoSolicitud" >
        		                                              <option value=""></option>
        		                                              <option value="Enviada">Enviada</option>
        		                                              <option value="Atendida">Atendida</option>
        		                                              <option value="Consumida">Consumida</option>
                                                              <option value="Dada de baja">Dada de baja</option>
	                
        	                                                </select>
	                            						</td>
	                
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha: </td>
	                            						<td colspan="3">
	                            							<input id="fecha" type="text" name="fecha" value="" readonly>
	                            						</td>
	                
	                            					</tr>
	                
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	        break;
	        case 2:
	            $this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar solicitud:</th>
	                                                </tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Número de solicitud: </td>
	                            						<td colspan="3">
	                            							<input id="numeroSolicitud" type="text" name="numeroSolicitud" value="" >
	                            						</td>
	                
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >RUC/CI Exportador: </td>
	                            						<td colspan="3">
	                            							<input id="identificador" type="text" name="identificador" value="" >
	                            						</td>
	                
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha: </td>
	                            						<td colspan="3">
	                            							<input id="fecha" type="text" name="fecha" value="" readonly>
	                            						</td>
	                
	                            					</tr>
	                
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	            break;
	        case 3:
	            $this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar solicitud:</th>
	                                                </tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Número de solicitud: </td>
	                            						<td colspan="3">
	                            							<input id="numeroSolicitud" type="text" name="numeroSolicitud" value="" >
	                            						</td>
	                
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >RUC/CI Exportador: </td>
	                            						<td colspan="3">
	                            							<input id="identificador" type="text" name="identificador" value="" >
	                            						</td>
	                
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha: </td>
	                            						<td colspan="3">
	                            							<input id="fecha" type="text" name="fecha" value="" readonly>
	                            						</td>
	                
	                            					</tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Estado: </td>
	                            						<td colspan="3">
	                            							<select style="width: 100%;" id="estadoSolicitud" name= "estadoSolicitud" >
        		                                              <option value=""></option>
        		                                              <option value="Enviada">Enviada</option>
        		                                              <option value="Atendida">Atendida</option>
        		                                              <option value="Consumida">Consumida</option>
                                                              <option value="Dada de baja">Dada de baja</option>
	                
        	                                                </select>
	                            						</td>
	                
	                            					</tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	        
	        default:
	            ;
	        break;
	    }
    }
    
    /***
     * cargar perfiles de usuario
     * @param unknown $codPerfil
     */
    public function perfilUsuario($codPerfil=null){
        $lNegocioSolicitudInspeccion = new SolicitudInspeccionLogicaNegocio();
        $consulta = $lNegocioSolicitudInspeccion->verificarPerfil($_SESSION['usuario'],$codPerfil);
        foreach ($consulta as $value) {
            $this->perfilUsuario[]=$value->codificacion_perfil;
        }
    }
    /**
     * validar perfil
     */
    public function verificarPerfilUsuario($perfil,$perfilPermitido){
        $existe= false;
        foreach ($perfilPermitido as $value) {
            if (in_array($value, $perfil,true)) {
                $existe = true;
                break;
            }
        }
        return $existe;
    }
}

<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2018-11-21
 * @uses      BaseControlador
 * @package   CentrosFaenamiento
 * @subpackage Controladores
 */
namespace Agrodb\CentrosFaenamiento\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\Core\Constantes;
use Agrodb\CentrosFaenamiento\Modelos\CentrosFaenamientoLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\Usuarios\Modelos\PerfilesLogicaNegocio;

class BaseControlador extends Comun
{

    public $itemsFiltrados = array();

    public $codigoJS = null;
    
    public $perfilUsuario = array();

    /**
     * Constructor
     */
    function __construct()
    {
        parent::usuarioActivo();
        // Si se requiere agregar código concatenar la nueva cadena con ejemplo $this->codigoJS.=alert('hola');
        $this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
    }

    public function crearTabla()
    {
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
     * Construye el código HTML para desplegar panel de busqueda
     */
    public function cargarPanelCentroFaenamiento()
    {
        $this->panelBusqueda = '<table class="filtro" style="width: 400px;">
                        				<tbody>
                                            <tr>
                                                <th colspan="2">Buscar centro de faenamiento:</th>
                                            </tr>
                        					<tr >
                        						<td>Ruc centro de faenamiento:</td>
                        						<td>
                        							<input id="identificadorFiltro" type="text" name="identificadorFiltro" style="width: 100%" required="true" class="camposRequeridos">
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
        $this->panelBusquedaPC = '<table class="filtro" style="width: 400px;">
                        				<tbody>
                                            <tr>
                                                <th colspan="2">Buscar centro de faenamiento:</th>
                                            </tr>
                        					<tr >
                        						<td>Provincia:</td>
                        						<td><select id="provincia" name="provincia"><option value="">Seleccione...</option>'.$this->comboProvinciasEc().'</select></td>
                        					</tr>
                                            <tr >
                        						<td>Ruc centro de faenamiento:</td>
                        						<td>
                        							<input id="identificadorFiltro" type="text" name="identificadorFiltro" style="width: 100%" required="true" class="camposRequeridos">
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

        $this->panelBusquedaCentro = '
            <table class="filtro" style="width: 400px;">
				<tbody>
					<tr>
						<th colspan="4">Buscar auxiliar:</th>
					</tr>
					<tr>
                    <td ><input name="tipo" type="radio" id="busqueda1" value="ruc"></td >
                    <td style="text-align:left;"> RUC </td>
                    <td ><input name="tipo" type="radio" id="busqueda2" value="ci" checked></td >
                    <td style="text-align:left;"> CI </td>
                </tr>
					<tr >
						<td>RUC / CI auxiliar:</td>
						<td colspan="3">
							<input id="identificadorFiltro" type="text" name="identificadorFiltro" style="width: 100%">
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<button id="btnFiltrar">Buscar</button>
						</td>
					</tr>
				</tbody>
			</table>';
        
        $this->panelBusquedaAsignarCF = '
            <table class="filtro" style="width: 400px;">
				<tbody>
					<tr>
						<th colspan="4">Buscar veterinario / auxiliar:</th>
					</tr>
					<tr>
                    <td ><input name="tipo" type="radio" id="busqueda1" value="ruc"></td >
                    <td style="text-align:left;"> RUC </td>
                    <td ><input name="tipo" type="radio" id="busqueda2" value="ci" checked></td >
                    <td style="text-align:left;"> CI </td>
                </tr>
					<tr >
						<td>RUC / CI veterinario-auxiliar:</td>
						<td colspan="3">
							<input id="identificadorFiltro" type="text" name="identificadorFiltro" style="width: 100%">
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<button id="btnFiltrar">Buscar</button>
						</td>
					</tr>
				</tbody>
			</table>';
    }

    /**
     * Construye el código HTML para limpiar detalle
     */
    public function limpiarDetalle()
    {}

    /**
     * Combo de datos centros de faenamiento
     *
     * @param type $respuesta
     * @return string
     */
    public function comboCentrosFaenamientoAdministracion($respuesta = null)
    {
        $combo = "";
        $opt = array(
            'Habilitado',
            'Activo',
            'Clausurado temporalmente',
            'Clausurado definitivamente',
            'Cerrado temporalmente',
            'Cerrado definitivamente'
        );
        $combo .= '<option value="" >Seleccione....</option>';
        foreach ($opt as $value) {
            if (ucfirst($respuesta) == ucfirst($value)) {
                $combo .= '<option value="' . $value . '" selected>' . $value . '</option>';
            } else {
                $combo .= '<option value="' . $value . '">' . $value . '</option>';
            }
        }
        return $combo;
    }

    /**
     * Combo de datos centros de faenamiento
     *
     * @param type $respuesta
     * @return string
     */
    public function comboResultadoTipoInspector($respuesta = null)
    {
        $combo = "";
        $opt = array(
            'Registrado',
            'No habilitado'
        );
        $combo .= '<option value="" >Seleccione....</option>';
        foreach ($opt as $value) {
            if (ucfirst($respuesta) == ucfirst($value)) {
                $combo .= '<option value="' . $value . '" selected>' . $value . '</option>';
            } else {
                $combo .= '<option value="' . $value . '">' . $value . '</option>';
            }
        }
        return $combo;
    }

    /**
     * Combo de datos centros de faenamiento
     *
     * @param type $respuesta
     * @return string
     */
    public function comboTipoInspector($respuesta = null)
    {
        $combo = "";
        $opt = array(
            Constantes::tipo_inspector()->AUXILIAR
        );
        $combo .= '<option value="" >Seleccione....</option>';
        foreach ($opt as $value) {
            if (ucfirst($respuesta) == ucfirst($value)) {
                $combo .= '<option value="' . $value . '" selected>' . $value . '</option>';
            } else {
                $combo .= '<option value="' . $value . '">' . $value . '</option>';
            }
        }
        return $combo;
    }
    /**
     * Combo de tipo centro de faenamiento
     *
     * @param type $respuesta
     * @return string
     */
    public function comboTipoCentroFaenamiento($tipo = null)
    {
        $combo = "";
        $opt = array(
            'IND – INDUSTRIAL',
            'SIND – SEMIINDUSTRIAL',
            'ART – ARTESANAL'
        );
        $combo .= '<option value="" >Seleccione....</option>';
        foreach ($opt as $value) {
            if (ucfirst($tipo) == ucfirst($value)) {
                $combo .= '<option value="' . $value . '" selected>' . $value . '</option>';
            } else {
                $combo .= '<option value="' . $value . '">' . $value . '</option>';
            }
        }
        return $combo;
    }
    /**
     * Combo de tipo centro de faenamiento
     *
     * @param type $respuesta
     * @return string
     */
    public function comboTipoHabilitacion($tipo = null)
    {
        $combo = "";
        $opt = array(
            'Nacional',
            'Internacional',
            'Cantonal',
            'Intercantonal',
            'Interprovincial'
        );
        $combo .= '<option value="" >Seleccione....</option>';
        foreach ($opt as $value) {
            if (ucfirst($tipo) == ucfirst($value)) {
                $combo .= '<option value="' . $value . '" selected>' . $value . '</option>';
            } else {
                $combo .= '<option value="' . $value . '">' . $value . '</option>';
            }
        }
        return $combo;
    }
    /***
     * cargar perfiles de usuario
     */
    public function perfilUsuario($codPerfil=null){
        $lNegocioPerfiles = new PerfilesLogicaNegocio();
        $consulta = $lNegocioPerfiles->verificarPerfil($_SESSION['usuario'],'PRG_CENT_FAENAMI',$codPerfil);
        foreach ($consulta as $value) {
            $this->perfilUsuario[]=$value->codificacion_perfil;
        }
    }
}

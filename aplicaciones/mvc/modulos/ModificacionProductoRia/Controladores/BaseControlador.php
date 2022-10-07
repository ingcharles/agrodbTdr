<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2022-07-13
 * @uses      BaseControlador
 * @package   ModificacionProductoRia
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;

session_start();

use Agrodb\Core\Comun;


class BaseControlador extends Comun
{
    public $itemsFiltrados = array();
    public $codigoJS = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::usuarioActivo();
        //Si se requiere agregar código concatenar la nueva cadena con  ejemplo $this->codigoJS.=alert('hola');
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

    public function datosGeneralesOperador($operador)
    {
        $datos = '<fieldset>
                    <legend>
                        Datos de titular
                    </legend>
                    <div data-linea="1">
                        <label>RUC/CI: </label>' . $operador->getIdentificador() . '
                    </div>
                    <div data-linea="2">
                        <label>Razón social: </label>' . $operador->getRazonSocial() . '
                    </div>
                    <div data-linea="3">
                        <label>Representante legal: </label>' . $operador->getNombreRepresentante() . ' ' . $operador->getApellidoRepresentante() . '
                    </div>
                    <div data-linea="4">
                        <label>Dirección: </label>' . $operador->getDireccion() . '
                    </div>
                    <div data-linea="5">
                        <label>Teléfono(s): </label>' . trim($operador->getTelefonoUno() . ' / ' . $operador->getTelefonoDos(), '/') . '
                    </div>
                    <div data-linea="6">
                        <label>Correo electrónico: </label>' . $operador->getCorreo() . '
                    </div>
                    <div data-linea="7">
                        <label>Representante legal: </label>' . $operador->getNombreTecnico() . ' ' . $operador->getApellidoTecnico() . '
                    </div>
                </fieldset>';

        return $datos;
    }

    public function datosGeneralesProducto($producto)
    {

        switch ($producto['id_area']) {
            case 'IAV':
                $area = 'Pecuaria';
                break;
            case 'IAP':
                $area = 'Agrícola';
                break;
            case 'IAF':
                $area = 'Fertilizante';
                break;
        }

        $datos = '<fieldset>
                    <legend>
                        Producto a modificar
                    </legend>
                    <div data-linea="1">
                        <label>Área temática: </label>' . $area . '
                    </div>
                    <div data-linea="2">
                        <label>Tipo de producto: </label>' . $producto['tipo'] . '
                    </div>
                    <div data-linea="3">
                        <label>Subtipo de producto legal: </label>' . $producto['subtipo'] . '
                    </div>
                    <div data-linea="4">
                        <label>Producto: </label>' . $producto['producto'] . '
                    </div>
                    <div data-linea="5">
                        <label>Número registro: </label>' . $producto['numero_registro'] . '
                    </div>
                </fieldset>';

        return $datos;
    }

    public function datosResultadoRevisionProducto($solicitudModificacion, $datosEmpleado)
    {
        $datos = '<fieldset>
                    <legend>
                        Datos de revisión técnica
                    </legend>
                    
                    <label>Técnico revisor </label>
                    
                    <div data-linea="1">
                        <label>Nombre: </label>' . $datosEmpleado->getNombre().' '.$datosEmpleado->getApellido() . '
                    </div>
                    <div data-linea="2">
                        <label>Correo: </label>' . $datosEmpleado->getMailInstitucional() . '
                    </div>
                    
                    <label>Observaciones emitidas </label>
                    
                    <div data-linea="3">
                        <label>Documento: </label>' . ($solicitudModificacion->getRutaRevisor() == '0' ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$solicitudModificacion->getRutaRevisor().' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                    </div>
                    <div data-linea="4">
                        <label>Observación: </label>' . $solicitudModificacion->getObservacionRevisor() . '
                    </div>
                    <div data-linea="5">
                        <label>Certificado de producto: </label>' . ($solicitudModificacion->getRutaCertificado() == '0' ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$solicitudModificacion->getRutaCertificado().' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                    </div>
                    
                </fieldset>';

        return $datos;
    }

    public function datosGeneralesModificacionProducto($tipoModificacion)
    {
        $numero = 0;
        $datos = '<fieldset>
                    <legend>
                        Modificaciones requeridas
                    </legend>
                    <ol>';
        foreach ($tipoModificacion as $modificacion) {
            $datos .= '<li>' . ++$numero . '.- ' . $modificacion->tipo_modificacion . '</li>';
        }

        $datos .= '</ol>
                </fieldset>';

        return $datos;
    }


    /**
     * Construye el código HTML para desplegar panel de búsqueda
     */
    public function cargarPanelBusquedaSolicitud()
    {

        $this->panelBusqueda = '<table id="fBusqueda" class="filtro" style="width: 400px;">
                        				<tbody>
											<tr>
												<th colspan="2">Buscar:</th>
											</tr>
											<tr>                            
                                                <td colspan="2">
                                                    <input name="tipo" type="radio" id="razon_social" value="razonSocial">
                                                    <label id="l_razon">Razón social</label>
                                                    <input name="tipo" type="radio" id="identificador" value="identificador">
                                                    <label id="l_id">RUC/CI</label>
                                                </td>                                    
                                            </tr>
                        					<tr>
                        						<td>Razón/RUC</td>
                        						<td>
                        							<input id="busqueda" type="text" name="busqueda" style="width: 100%" class="validacion">
                        						</td>
                        					</tr>
                        					<tr>
                        						<td>Provincia</td>
                        						<td>
                        							<select id="id_provincia" name="id_provincia" style="width: 100%">
                        							    <option value="">Seleccione...</option>
                        							    ' . $this->comboProvinciasEc() . '
                                                    </select>
                                                    <input type="hidden" id="nombre_provincia" name="nombre_provincia">
                        						</td>
                        					</tr>
                        					<tr>
                        						<td>Tipo solicitud</td>
                        						<td>
                        							<select id="tipo_solicitud" name="tipo_solicitud" style="width: 100%" class="validacion">
                        							    <option value="">Seleccione...</option>
                        							    <option value="IAP">Agrícola</option>
                        							    <option value="IAF">Fertilizante</option>
                        							    <option value="IAV">Pecuario</option>
                                                    </select>
                        						</td>
                        					</tr>
                        					<tr>
                        						<td colspan="2">
                        							<button id="btnFiltrar">Buscar</button>
                        						</td>
                        					</tr>
                        				</tbody>
                        			</table>';
    }

    public function datosFinancieroModificacionProducto($datosFinanciero)
    {
        $datos = '<fieldset>
                    <legend>Información de pago</legend>
                        <div data-linea="1">
						<label>Monto a pagar:</label> <span class="alerta">$ '.$datosFinanciero['total_pagar'].'</span>
                        </br><a href="'.$datosFinanciero['orden_pago'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar orden de pago: </a>
					</div>
                </fieldset>';

        return $datos;
    }
}

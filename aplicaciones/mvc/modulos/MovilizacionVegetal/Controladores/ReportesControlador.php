<?php
/**
 * Controlador Ventanillas
 *
 * Este archivo controla la lógica del negocio del modelo:  VentanillasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-02-13
 * @uses    VentanillasControlador
 * @package SeguimientoDocumental
 * @subpackage Controladores
 */
namespace Agrodb\MovilizacionVegetal\Controladores;

use Agrodb\MovilizacionVegetal\Modelos\MovilizacionLogicaNegocio;
use Agrodb\MovilizacionVegetal\Modelos\MovilizacionModelo;
use Agrodb\MovilizacionVegetal\Modelos\DetalleMovilizacionLogicaNegocio;
use Agrodb\MovilizacionVegetal\Modelos\DetalleMovilizacionModelo;
use Agrodb\MovilizacionVegetal\Modelos\FiscalizacionLogicaNegocio;
use Agrodb\MovilizacionVegetal\Modelos\FiscalizacionModelo;
use Agrodb\Catalogos\Modelos\TipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoProductosModelo;
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\SubtipoProductosModelo;
use Agrodb\Catalogos\Modelos\TiposOperacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\TiposOperacionModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ReportesControlador extends BaseControlador
{

    private $lNegocioMovilizacion = null;
    private $modeloMovilizacion = null;
    
    private $lNegocioDetalleMovilizacion = null;
    private $modeloDetalleMovilizacion = null;
    
    private $lNegocioFiscalizacion = null;
    private $modeloFiscalizacion = null;
    
    private $lNegocioTipoProductos = null;
    private $modeloTipoProductos = null;
    
    private $lNegocioSubtipoProductos = null;
    private $modeloSubtipoProductos = null;
    
    private $lNegocioTiposOperacion = null;
    private $modeloTiposOperacion = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;
    private $ruta = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioMovilizacion = new MovilizacionLogicaNegocio();
        $this->modeloMovilizacion = new MovilizacionModelo();
        
        $this->lNegocioDetalleMovilizacion = new DetalleMovilizacionLogicaNegocio();
        $this->modeloDetalleMovilizacion = new DetalleMovilizacionModelo();
        
        $this->lNegocioFiscalizacion = new FiscalizacionLogicaNegocio();
        $this->modeloFiscalizacion = new FiscalizacionModelo();
        
        $this->lNegocioTipoProductos = new TipoProductosLogicaNegocio();
        $this->modeloTipoProductos = new TipoProductosModelo();
        
        $this->lNegocioSubtipoProductos = new SubtipoProductosLogicaNegocio();
        $this->modeloSubtipoProductos = new SubtipoProductosModelo();
        
        $this->lNegocioTiposOperacion = new TiposOperacionLogicaNegocio();
        $this->modeloTiposOperacion = new TiposOperacionModelo();
        
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $this->cargarPanelReportes();
        
        require APP . 'MovilizacionVegetal/vistas/listaOpcionesReportes.php';
    }
    
    /**
     * Método de inicio del controlador para reportes
     */
    public function listarReporteMovilizacion()
    {
        $this->cargarPanelReportes();
        
        require APP . 'MovilizacionVegetal/vistas/listaMovilizacionReporteVista.php';
    }
    
    /**
     * Método de inicio del controlador para reportes
     */
    public function listarReporteFiscalizacion()
    {
        $this->cargarPanelReportes();
        
        require APP . 'MovilizacionVegetal/vistas/listaFiscalizacionReporteVista.php';
    }

    /**
     * Construye el código HTML para desplegar panel de busqueda para los reportes
     */
    public function cargarPanelReportes()
    {
        $this->panelBusquedaMovilizacionReporte = '
                                                    <form id="filtrar" action="aplicaciones/mvc/MovilizacionVegetal/Movilizacion/exportarMovilizacionesExcel" target="_blank" method="post">
                                                        <table class="filtro" style="width: 450px;">
                                                            <tbody>
                                                                <tr>
                                                                    <th colspan="4">Filtro para el reporte de movilización de productos S. Vegetal</th>
                                                                </tr>
                                            					<tr  style="width: 100%;">
                                            						<td >Provincia: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idProvinciaFiltro" name="idProvinciaFiltro" style="width: 100%;" required>
                                                                            <option value="">Seleccione....</option>
                                                                            <option value="Todas">Todas</option>' .
                                                                            $this->comboProvinciasEc() .
                                                                       '</select>
                                                                        <input type="hidden" id="provinciaFiltro" name="provinciaFiltro" />
                                            						</td>
                                            					</tr>                                                                
                                                                <tr  style="width: 100%;">
                                            						<td >Tipo de Producto: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idTipoProductoFiltro" name="idTipoProductoFiltro" style="width: 100%;" >
                                                                            <option value="">Seleccione....</option>' .
                                                                            $this->comboTipoProductoReporte('SV') .
                                                                       '</select>
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Subtipo de Producto: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idSubtipoProductoFiltro" name="idSubtipoProductoFiltro" style="width: 100%;" >
                                                                            <option value="">Seleccionar....</option>' .
                                                                       '</select>
                                            						</td>
                                            					</tr>                                                                
                                                                <tr  style="width: 100%;">
                                            						<td >Estado: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="estadoFiltro" name="estadoFiltro" style="width: 100%;" >
                                                                            <option value="">Seleccione....</option>' .
                                                                            $this->comboEstadosMovilizacion() .
                                                                       '</select>
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Fecha Inicio: </td>
                                            						<td>
                                            							<input id="fechaInicio" type="text" name="fechaInicio" required readonly="readonly">
                                            						</td>
                                                                
                                            						<td >Fecha Fin: </td>
                                            						<td>
                                            							<input id="fechaFin" type="text" name="fechaFin" required readonly="readonly">
                                            						</td>
                                            					</tr>
                                                                
                                                                <tr></tr>
                                            					<tr>
                                            						<td colspan="3">
                                            							<button type="submit">Generar Reporte</button>
                                            						</td>
                                                                            
                                            					</tr>
                                            				</tbody>
                                            			</table>
                                                    </form>';
                                                                        
        $this->panelBusquedaFiscalizacionReporte = '
                                                    <form id="filtrar" action="aplicaciones/mvc/MovilizacionVegetal/Fiscalizacion/exportarFiscalizacionesExcel" target="_blank" method="post">
                                                        <table class="filtro" style="width: 450px;">
                                                            <tbody>
                                                                <tr>
                                                                    <th colspan="4">Filtro para el reporte de fiscalizaciones de permisos de movilización</th>
                                                                </tr>
                                            					<tr  style="width: 100%;">
                                            						<td >Provincia: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idProvinciaFiltro" name="idProvinciaFiltro" style="width: 100%;" required>
                                                                            <option value="">Seleccione....</option>
                                                                            <option value="Todas">Todas</option>' .
                                                                            $this->comboProvinciasEc() .
                                                                       '</select>
                                                                        <input type="hidden" id="provinciaFiltro" name="provinciaFiltro" />
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Cantón: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idCantonFiltro" name="idCantonFiltro" style="width: 100%;" >
                                                                            <option value="">Seleccionar....</option>' .
                                                                       '</select>
                                                                        <input type="hidden" id="cantonFiltro" name="cantonFiltro" />
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Parroquia: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="idParroquiaFiltro" name="idParroquiaFiltro" style="width: 100%;" >
                                                                            <option value="">Seleccionar....</option>' .
                                                                        '</select>
                                                                         <input type="hidden" id="parroquiaFiltro" name="parroquiaFiltro" />
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Resultado Fiscalización: </td>
                                            						<td style="width: 100%;" colspan="3">
                                                                        <select id="estadoFiltro" name="estadoFiltro" style="width: 100%;" >
                                                                            <option value="">Seleccione....</option>' .
                                                                            $this->comboEstadosFiscalizacion() .
                                                                       '</select>
                                            						</td>
                                            					</tr>
                                                                <tr  style="width: 100%;">
                                            						<td >Fecha Inicio: </td>
                                            						<td>
                                            							<input id="fechaInicio" type="text" name="fechaInicio" required readonly="readonly">
                                            						</td>
                                                                
                                            						<td >Fecha Fin: </td>
                                            						<td>
                                            							<input id="fechaFin" type="text" name="fechaFin" required readonly="readonly">
                                            						</td>
                                            					</tr>
                                                                            
                                                                <tr></tr>
                                            					<tr>
                                            						<td colspan="3">
                                            							<button type="submit">Generar Reporte</button>
                                            						</td>
                                                                            
                                            					</tr>
                                            				</tbody>
                                            			</table>
                                                    </form>';
    }
    
    /**
     * Combo de estados para trámites
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboEstadosMovilizacion($opcion = null)
    {
        $combo = "";
        
        if ($opcion == "Vigente") {
        	$combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Vigente" selected="selected">Vigente</option>';
            $combo .= '<option value="Caducado">Caducado</option>';
            $combo .= '<option value="Anulado">Anulado</option>';
        } else if ($opcion == "Caducado") {
        	$combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Vigente" >Vigente</option>';
            $combo .= '<option value="Caducado">Caducado</option>';
            $combo .= '<option value="Anulado">Anulado</option>';
        } else if ($opcion == "Anulado") {
            $combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Vigente" >Vigente</option>';
            $combo .= '<option value="Caducado">Caducado</option>';
            $combo .= '<option value="Anulado" selected="selected">Anulado</option>';
        }else {
        	$combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Vigente">Vigente</option>';
            $combo .= '<option value="Caducado">Caducado</option>';
            $combo .= '<option value="Anulado">Anulado</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de estados para trámites
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboEstadosFiscalizacion($opcion = null)
    {
        $combo = "";
        
        if ($opcion == "Positivo") {
            $combo .= '<option value="Positivo" selected="selected">Positivo</option>';
            $combo .= '<option value="Negativo">Negativo</option>';
        } else if ($opcion == "Negativo") {
            $combo .= '<option value="Positivo" >Positivo</option>';
            $combo .= '<option value="Negativo">Negativo</option>';
        } else {
            $combo .= '<option value="Positivo">Positivo</option>';
            $combo .= '<option value="Negativo">Negativo</option>';
        }
        
        return $combo;
    }
    
    /**
     * Construye el combo para desplegar la lista de Tipos de Productos
     */
    public function comboTipoProductoReporte($area, $idTipoProducto=null)
    {
        $combo = "";
        $tipoProducto = $this->lNegocioTipoProductos->buscarTipoProductoXArea($area);
        
        foreach ($tipoProducto as $item)
        {
            if ($idTipoProducto == $item['id_tipo_producto'])
            {
                $combo .= '<option value="' . $item->id_tipo_producto . '" selected >' . $item->nombre . '</option>';
            } else
            {
                $combo .= '<option value="' . $item->id_tipo_producto . '" >' . $item->nombre . '</option>';
            }
        }
        return $combo;
    }
    
    /**
     * Construye el combo para desplegar la lista de Subtipos de Productos
     */
    public function comboSubtipoProductoXTipo($idTipoProducto)
    {
        $combo = '<option value="">Seleccione....</option>';
        
        $subtipoProducto = $this->lNegocioSubtipoProductos->buscarSubtipoProductoXArea($idTipoProducto);
        
        foreach ($subtipoProducto as $item)
        {
            $combo .= '<option value="' . $item->id_subtipo_producto . '" >' . $item->nombre . '</option>';
        }
        
        echo $combo;
        exit();
    }
    
    /**
     * Construye el combo para desplegar la lista de Operaciones por área
     */
    public function comboOperaciones($area)
    {
        $combo = "";
        $operaciones = $this->lNegocioTiposOperacion->buscarOperacionesXArea($area);
        
        foreach ($operaciones as $item)
        {
            $combo .= '<option value="' . $item->id_tipo_operacion . '" >' . $item->nombre . '</option>';
        }
        
        return $combo;
    }
}
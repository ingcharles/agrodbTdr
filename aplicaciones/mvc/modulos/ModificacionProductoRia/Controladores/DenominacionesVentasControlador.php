<?php
/**
 * Controlador DenominacionesVentas
 *
 * Este archivo controla la lógica del negocio del modelo:  DenominacionesVentasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-13
 * @uses    DenominacionesVentasControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;

use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\DenominacionesVentasLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\DenominacionesVentasModelo;

class DenominacionesVentasControlador extends BaseControlador
{

    private $lNegocioDenominacionesVentas = null;
    private $modeloDenominacionesVentas = null;
    private $accion = null;

    private $lNegocioProductosInocuidad = null;
    private $rutaFecha = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDenominacionesVentas = new DenominacionesVentasLogicaNegocio();
        $this->modeloDenominacionesVentas = new DenominacionesVentasModelo();
        $this->lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();

        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloDenominacionesVentas = $this->lNegocioDenominacionesVentas->buscarDenominacionesVentas();
        $this->tablaHtmlDenominacionesVentas($modeloDenominacionesVentas);
        require APP . 'ModificacionProductoRia/vistas/listaDenominacionesVentasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo DenominacionesVentas";
        require APP . 'ModificacionProductoRia/vistas/formularioDenominacionesVentasVista.php';
    }

    /**
     * Método para registrar en la base de datos -DenominacionesVentas
     */
    public function guardar()
    {
        $this->lNegocioDenominacionesVentas->guardar($_POST);
    }

    /**
     *Obtenemos los datos del registro seleccionado para editar - Tabla: DenominacionesVentas
     */
    public function editar()
    {
        $this->accion = "Editar DenominacionesVentas";
        $this->modeloDenominacionesVentas = $this->lNegocioDenominacionesVentas->buscar($_POST["id"]);
        require APP . 'ModificacionProductoRia/vistas/formularioDenominacionesVentasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DenominacionesVentas
     */
    public function borrar()
    {
        $this->lNegocioDenominacionesVentas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DenominacionesVentas
     */
    public function tablaHtmlDenominacionesVentas($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_denominacion_venta'] . '"
                        class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\denominacionesventas"
                        data-opcion="editar" ondragstart="drag(event)" draggable="true"
                        data-destino="detalleItem">
                        <td>' . ++$contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['id_denominacion_venta'] . '</b></td>
                        <td>' . $fila['id_detalle_solicitud_producto'] . '</td>
                        <td>' . $fila['id_tabla_origen'] . '</td>
                        <td>' . $fila['id_declaracion_venta'] . '</td>
                    </tr>');
            }
        }
    }

    public function modificarDenominacionVentaProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSolicitudProducto)
    {
        $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
        $filaDenominacionVenta = '';
        $ingresoDatos = '';
        $banderaAcciones = false;
        $idProducto = $parametros['id_producto'];

        $denominacionVentaProductoActual = $this->lNegocioProductosInocuidad->buscar($idProducto);

        switch ($estadoSolicitudProducto) {

            case 'Creado':
            case 'subsanacion':
                $banderaAcciones = true;
                $ingresoDatos = '<div data-linea="1">
                                    <label>Declaración de venta:</label>
                                    <select name="id_declaracion_venta" id="id_declaracion_venta" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion">
                                        <option value="">Seleccionar....</option>' . $this->comboDeclaracionVenta($denominacionVentaProductoActual->getIdDeclaracionVenta()) . '
                                    </select>
                                    <input type="hidden" name="declaracion_venta" id="declaracion_venta" />
                                </div>
                                <hr/>
                                <div data-linea="2">
                                    <label>Documento de respaldo:</label>
                                </div>
                                <div data-linea="3">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . '" name="ruta_documento_respaldo" value="0"/>
                                    <input type="file" class="archivo" id="v' . $tipoModificacion . '" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . '</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="4">
                        			<button type="button" class="mas" id="agregarDenominacionVenta" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
                break;

        }

        $qDatosDenominacionVenta = $this->lNegocioDenominacionesVentas->buscarLista(array(
            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
        ));

        foreach ($qDatosDenominacionVenta as $datosDenominacionVenta) {

            $idDenominacionVenta = $datosDenominacionVenta['id_denominacion_venta'];
            $declaracionVenta = $datosDenominacionVenta['declaracion_venta'];

            $filaDenominacionVenta .= '
                <tr id="fila' . $idDenominacionVenta . '">
                    <td>' . $declaracionVenta . '</td>
                    <td>' . $tiempoAtencion . ' días</td>';
            if ($banderaAcciones) {
                $filaDenominacionVenta .= '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarDenominacionVenta(' . $idDenominacionVenta . '); return false;"/>
                        </td>';
            }
            $filaDenominacionVenta .= '</tr>';
        }

        $modificarDenominacionVenta = '';

        if($rutaDocumentoRespaldo){
            $modificarDenominacionVenta .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }

        $modificarDenominacionVenta .= '
        <fieldset  id="fDenominacionVentaProducto">
            <legend>Modificar denominación de venta</legend>
            ' . $ingresoDatos . '
            <table id="tDenominacionVentaProducto" style="width: 100%">
                <thead>
                    <tr>
                        <th>Denominación de venta</th>
                        <th>Tiempo de atención</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>' . $filaDenominacionVenta . '</tbody>
            </table>
        </fieldset>';

        return $modificarDenominacionVenta;
    }

    /**
     * Método para listar denominaciones de venta de producto agregada
     */
    public function generarFilaDenominacionVentaProducto($idDenominacionVentaProducto, $datosDenominacionVenta, $tiempoAtencion)
    {
        $this->listaDetalles = '
                        <tr id="fila' . $idDenominacionVentaProducto . '">
                            <td>' . $datosDenominacionVenta['declaracion_venta'] . '</td>
                            <td>' . $tiempoAtencion . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDenominacionVenta(' . $idDenominacionVentaProducto . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }
}

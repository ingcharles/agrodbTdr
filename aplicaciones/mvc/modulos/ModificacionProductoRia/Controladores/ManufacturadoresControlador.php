<?php
/**
 * Controlador Manufacturadores
 *
 * Este archivo controla la lógica del negocio del modelo:  ManufacturadoresModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-13
 * @uses    ManufacturadoresControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;

use Agrodb\Catalogos\Modelos\FabricanteFormuladorLogicaNegocio;
use Agrodb\Catalogos\Modelos\ManufacturadorLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\ManufacturadoresLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\ManufacturadoresModelo;

class ManufacturadoresControlador extends BaseControlador
{

    private $lNegocioManufacturadores = null;
    private $modeloManufacturadores = null;

    private $lNegocioFabricanteFormulador = null;
    private $lNegocioManufacturadoresActual = null;
    private $accion = null;
    private $rutaFecha = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioManufacturadores = new ManufacturadoresLogicaNegocio();
        $this->modeloManufacturadores = new ManufacturadoresModelo();
        $this->lNegocioFabricanteFormulador = new FabricanteFormuladorLogicaNegocio();
        $this->lNegocioManufacturadoresActual = new ManufacturadorLogicaNegocio();

        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloManufacturadores = $this->lNegocioManufacturadores->buscarManufacturadores();
        $this->tablaHtmlManufacturadores($modeloManufacturadores);
        require APP . 'ModificacionProductoRia/vistas/listaManufacturadoresVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Manufacturadores";
        require APP . 'ModificacionProductoRia/vistas/formularioManufacturadoresVista.php';
    }

    /**
     * Método para registrar en la base de datos -Manufacturadores
     */
    public function guardar()
    {
        $this->lNegocioManufacturadores->guardar($_POST);
    }

    /**
     *Obtenemos los datos del registro seleccionado para editar - Tabla: Manufacturadores
     */
    public function editar()
    {
        $this->accion = "Editar Manufacturadores";
        $this->modeloManufacturadores = $this->lNegocioManufacturadores->buscar($_POST["id"]);
        require APP . 'ModificacionProductoRia/vistas/formularioManufacturadoresVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Manufacturadores
     */
    public function borrar()
    {
        $this->lNegocioManufacturadores->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Manufacturadores
     */
    public function tablaHtmlManufacturadores($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_manufacturador'] . '"
                        class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\manufacturadores"
                        data-opcion="editar" ondragstart="drag(event)" draggable="true"
                        data-destino="detalleItem">
                        <td>' . ++$contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['id_manufacturador'] . '</b></td>
                        <td>'. $fila['id_detalle_solicitud_producto'] . '</td>
                        <td>' . $fila['id_fabricante_formulador'] . '</td>
                        <td>' . $fila['manufacturador'] . '</td>
                    </tr>');
            }
        }
    }

    public function modificarManufacturadorProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
    {
        $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
        $filaManufacturador = '';
        $ingresoDatos = '';
        $banderaAcciones = false;
       
        switch ($estadoSoliciudProducto) {

            case 'Creado':
            case 'subsanacion':
                $opcionFabricantesFormuladores = '';
                $datos = [
                    'id_producto' => $parametros['id_producto'],
                    'estado' => 'activo'
                ];

                $fabricantesFormuladores = $this->lNegocioFabricanteFormulador->buscarLista($datos);
                foreach ($fabricantesFormuladores as $fabricanteFormulador){
                    $opcionFabricantesFormuladores .= '<option value="' . $fabricanteFormulador->id_fabricante_formulador . '">' . $fabricanteFormulador->tipo . ' - ' . $fabricanteFormulador->nombre . ' - '.$fabricanteFormulador->pais_origen.'</option>';
                }

                $banderaAcciones = true;
                $ingresoDatos = '<div data-linea="1">
                                    <label>Fabricante/Formulador: </label>									
                                    <select id="id_fabricante_formulador" name="id_fabricante_formulador" class="validacion" required>
                                        <option value="">Seleccione....</option>'
                                        . $opcionFabricantesFormuladores .
                                    '</select>
                                    <input type="hidden" name="fabricante_formulador" id="fabricante_formulador" />
                                </div>
                                <div data-linea="2">
                                    <label>Manufacturador: </label>
                                        <input type="text" name="manufacturador" id="manufacturador" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion" required/>
                                </div>
                                <div data-linea="3">
                                    <label>País origen: </label>
                                    <select id="id_pais_origen" name="id_pais_origen" class="validacion" required>
                                        <option value="">Seleccione....</option>'
                                        . $this->comboPaises() .
                                        '</select>
                                    <input type="hidden" name="pais_origen" id="pais_origen" />
                                </div>
                                <hr/>
                                <div data-linea="4">
                                    <label>Documento de respaldo:</label>
                                </div>
                                <div data-linea="5">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . '" name="ruta_documento_respaldo" value="0"/>
                                    <input type="file" class="archivo validacion" id="v' . $tipoModificacion . '" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . ')</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="6">
                        			<button type="button" class="mas" id="agregarManufacturador" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
                break;
        }
        
        $arrayConsulta = [
            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
        ];
        
        switch ($estadoSoliciudProducto) {
            case 'Creado':
                $arrayConsulta += ['id_producto' => $parametros['id_producto']];
                break;
        }

        $qDatosManufacturador = $this->lNegocioManufacturadores->buscarManufacturadorOrigenDestino($arrayConsulta);

        foreach ($qDatosManufacturador as $datosManufacturador) {

            $idManufacturadorOrigen = $datosManufacturador['id_manufacturador_origen'];
            $idManufacturador = $datosManufacturador['id_manufacturador'];
            $manufacturador = $datosManufacturador['manufacturador'];
            $paisOrigen = $datosManufacturador['pais_origen'];
            $estado = $datosManufacturador['estado'];
            $fabricanteFormulador = $datosManufacturador['fabricante_formulador'];

            $filaManufacturador .=
                '<tr id="fila' . ($idManufacturadorOrigen ? $idManufacturadorOrigen : $idManufacturador) . '">
                    <td>' . $fabricanteFormulador . '</td>
                    <td>' . $manufacturador . '</td>
                    <td>' . $paisOrigen . '</td>';
            if ($banderaAcciones) {
                if (!$idManufacturadorOrigen) {
                    $filaManufacturador .=
                        '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarManufacturador(' . $idManufacturador . '); return false;"/>
                    </td>';
                } else {
                    $filaManufacturador .= '<td class="' . $estado . '">
                            <button type="button" name="eliminar" class="icono" onclick="fn_cambiarEstadoManufacturador(' . $idManufacturadorOrigen . '); return false;"/>
                        </td>';
                }

            }else{
                $filaManufacturador .= '<td>' . $estado .' </td>';
            }
            $filaManufacturador .= '</tr>';
        }

        $modificarManufacturador = '';

        if($rutaDocumentoRespaldo){
            $modificarManufacturador .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }

        $modificarManufacturador .= '
            <fieldset  id="fManufacturadorProducto">
                <legend>Modificar manufacturador</legend>
                ' . $ingresoDatos . '
                <table id="tManufacturadorProducto" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Fabricante/Formulador</th>
                            <th>Manufacturador</th>
                            <th>País origen</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>' . $filaManufacturador . '</tbody>
                </table>
            </fieldset>';

        return $modificarManufacturador;
    }

    /**
     * Método para generar fila de registro de manufacturador
     */
    public function generarManufacturadorProducto($idManufacturadorProducto, $datosManufacturador, $tiempoAtencion)
    {
        $this->listaDetalles = '
                        <tr id="fila' . $idManufacturadorProducto . '">
                            <td>' . $datosManufacturador['fabricante_formulador'] . '</td>
                            <td>' . $datosManufacturador['manufacturador'] . '</td>
                            <td>' . $datosManufacturador['pais_origen'] . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarManufacturador(' . $idManufacturadorProducto . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

    /**
     * Método para guardar registro de cambio de estado
     */
    public function guardarEstadoManufacturador()
    {

        $datos = [
            'id_detalle_solicitud_producto' => $_POST['id_detalle_solicitud_producto'],
            'id_tabla_origen' => $_POST['id_tabla_origen']
        ];

        $datosManufacturador = $this->lNegocioManufacturadores->buscarLista($datos);

        if (!count($datosManufacturador)) {

            $manufacturador = $this->lNegocioManufacturadoresActual->buscar($_POST['id_tabla_origen']);

            $_POST['id_fabricante_formulador'] = $manufacturador->getIdFabricanteFormulador();
            $_POST['manufacturador'] = $manufacturador->getManufacturador();
            $_POST['estado'] = $manufacturador->getEstado() === 'activo' ? 'inactivo' : 'activo';
            $_POST['id_pais_origen'] = $manufacturador->getIdPaisOrigen();
            $_POST['pais_origen'] = $manufacturador->getPaisOrigen();
        } else {
            $_POST['estado'] = $datosManufacturador->current()->estado === 'activo' ? 'inactivo' : 'activo';
            $_POST['id_manufacturador'] = $datosManufacturador->current()->id_manufacturador;
        }

        $this->guardar();

        echo json_encode(array(
            'estado' => 'EXITO',
            'resultado' => 'Datos actualizados con éxito'
        ));
    }
}

<?php
/**
 * Controlador FabricantesFormuladores
 *
 * Este archivo controla la lógica del negocio del modelo:  FabricantesFormuladoresModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-13
 * @uses    FabricantesFormuladoresControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;

use Agrodb\Catalogos\Modelos\FabricanteFormuladorLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\FabricantesFormuladoresLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\FabricantesFormuladoresModelo;

class FabricantesFormuladoresControlador extends BaseControlador
{

    private $lNegocioFabricantesFormuladores = null;
    private $modeloFabricantesFormuladores = null;

    private $lNegocioFabricanteFormuladorActual = null;

    private $accion = null;
    private $rutaFecha = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioFabricantesFormuladores = new FabricantesFormuladoresLogicaNegocio();
        $this->modeloFabricantesFormuladores = new FabricantesFormuladoresModelo();

        $this->lNegocioFabricanteFormuladorActual = new FabricanteFormuladorLogicaNegocio();

        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');

        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloFabricantesFormuladores = $this->lNegocioFabricantesFormuladores->buscarFabricantesFormuladores();
        $this->tablaHtmlFabricantesFormuladores($modeloFabricantesFormuladores);
        require APP . 'ModificacionProductoRia/vistas/listaFabricantesFormuladoresVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo FabricantesFormuladores";
        require APP . 'ModificacionProductoRia/vistas/formularioFabricantesFormuladoresVista.php';
    }

    /**
     * Método para registrar en la base de datos -FabricantesFormuladores
     */
    public function guardar()
    {
        $this->lNegocioFabricantesFormuladores->guardar($_POST);
    }

    /**
     *Obtenemos los datos del registro seleccionado para editar - Tabla: FabricantesFormuladores
     */
    public function editar()
    {
        $this->accion = "Editar FabricantesFormuladores";
        $this->modeloFabricantesFormuladores = $this->lNegocioFabricantesFormuladores->buscar($_POST["id"]);
        require APP . 'ModificacionProductoRia/vistas/formularioFabricantesFormuladoresVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - FabricantesFormuladores
     */
    public function borrar()
    {
        $this->lNegocioFabricantesFormuladores->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - FabricantesFormuladores
     */
    public function tablaHtmlFabricantesFormuladores($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_fabricante_formulador'] . '"
		                class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\fabricantesformuladores"
		                data-opcion="editar" ondragstart="drag(event)" draggable="true"
		                data-destino="detalleItem">
		                <td>' . ++$contador . '</td>
		                <td style="white - space:nowrap; "><b>' . $fila['id_fabricante_formulador'] . '</b></td>
                        <td>' . $fila['id_detalle_solicitud_producto'] . '</td>
                        <td>' . $fila['tipo'] . '</td>
                        <td>' . $fila['nombre'] . '</td>
                    </tr>'
                );
            }
        }
    }

    public function modificarFabricanteFormuladorProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
    {
        $idArea = $parametros['id_area'];
        $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
        $filaFabricanteFormulador = '';
        $ingresoDatos = '';
        $banderaAcciones = false;

        $arrayConsulta = [
            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
        ];

        switch ($estadoSoliciudProducto) {

            case 'Creado':
            case 'subsanacion':

                switch ($idArea) {
                    case 'IAV':
                    case 'IAP':
                        $arrayConsulta += [
                            'id_producto' => $parametros['id_producto']
                        ];
                        break;
                }

                $banderaAcciones = true;
                $ingresoDatos = '<div data-linea="1">
                                    <label>Tipo: </label>									
                                    <select id="tipo" name="tipo" class="validacion" required>
                                        <option value="">Seleccione....</option>
                                        <option value="Fabricante">Fabricante</option>
                                        <option value="Formulador">Formulador</option>
                                    </select>
                                </div>
                                <div data-linea="2">
                                    <label>Nombre: </label>
                                        <input type="text" name="nombre_fabricante_formulador" id="nombre_fabricante_formulador" data-tiempoatencion="' . $tiempoAtencion . ' días" class="validacion" required/>
                                </div>
                                <div data-linea="3">
                                    <label>País origen: </label>
                                    <select id="id_pais_origen" name="id_pais_origen" class="validacion" required>
                                        <option value="">Seleccione....</option>' . $this->comboPaises() . '
                                    </select>
                                    <input type="hidden" name="nombre_pais_origen" id="nombre_pais_origen" />
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
                        			<button type="button" class="mas" id="agregarFabricanteFormulador" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
                break;
        }
        
        $qDatosFabricanteFormulador = $this->lNegocioFabricantesFormuladores->buscarFabricanteFormuladorOrigenDestino($arrayConsulta);

        foreach ($qDatosFabricanteFormulador as $datosFabricanteFormulador) {

            $idFabricanteFormuladorOrigen = $datosFabricanteFormulador['id_fabricante_formulador_origen'];
            $idFabricanteFormulador = $datosFabricanteFormulador['id_fabricante_formulador'];
            $tipo = $datosFabricanteFormulador['tipo'];
            $nombre = $datosFabricanteFormulador['nombre'];
            $nombrePais = $datosFabricanteFormulador['nombre_pais_origen'];
            $estado = $datosFabricanteFormulador['estado'];

            $filaFabricanteFormulador .=
                '<tr id="fila' . ($idFabricanteFormuladorOrigen ? $idFabricanteFormuladorOrigen : $idFabricanteFormulador) . '">
                    <td>' . $tipo . '</td>
                    <td>' . $nombre . '</td>
                    <td>' . $nombrePais . '</td>';
            if ($banderaAcciones) {
                if (!$idFabricanteFormuladorOrigen) {
                    $filaFabricanteFormulador .=
                        '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarFabricanteFormulador(' . $idFabricanteFormulador . '); return false;"/>
                    </td>';
                } else {
                    $filaFabricanteFormulador .= '<td class="' . $estado . '">
                            <button type="button" name="eliminar" class="icono" onclick="fn_cambiarEstadoFabricanteFormulador(' . $idFabricanteFormuladorOrigen . '); return false;"/>
                        </td>';
                }

            }else{
                $filaFabricanteFormulador .= '<td>' . $estado . '</td>';
            }
            $filaFabricanteFormulador .= '</tr>';
        }

        $modificarFabricanteFormulador = '';

        if($rutaDocumentoRespaldo){
            $modificarFabricanteFormulador .= '
            <fieldset>
                <legend>Documento adjunto</legend>
                <div data-linea="1">
                    <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                </div>
            </fieldset>';
        }

        $modificarFabricanteFormulador .= '
            <fieldset  id="fFabricanteFormuladorProducto">
                <legend>Modificar fabricante/formulador</legend>
                ' . $ingresoDatos . '
                <table id="tFabricanteFormuladorProducto" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>País</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>' . $filaFabricanteFormulador . '</tbody>
                </table>
            </fieldset>';

        return $modificarFabricanteFormulador;
    }

    /**
     * Método para listar titularidad de producto agregada
     */
    public function generarFilaFabricanteFormuladorProducto($idFabricanteFormuladorProducto, $datosFabricanteProducto, $tiempoAtencion)
    {
        $this->listaDetalles = '
                        <tr id="fila' . $idFabricanteFormuladorProducto . '">
                            <td>' . $datosFabricanteProducto['tipo'] . '</td>
                            <td>' . $datosFabricanteProducto['nombre'] . '</td>
                            <td>' . $datosFabricanteProducto['nombre_pais_origen'] . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarFabricanteFormulador(' . $idFabricanteFormuladorProducto . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

    /**
     * Método para guardar registro de cambio de estado
     */
    public function guardarEstadoFabricanteFormulador()
    {

        $datos = [
            'id_detalle_solicitud_producto' => $_POST['id_detalle_solicitud_producto'],
            'id_tabla_origen' => $_POST['id_tabla_origen']
        ];

        $datosFabricanteFormulador = $this->lNegocioFabricantesFormuladores->buscarLista($datos);

        if (!count($datosFabricanteFormulador)) {

            $fabricanteFormulador = $this->lNegocioFabricanteFormuladorActual->buscar($_POST['id_tabla_origen']);

            $_POST['tipo'] = $fabricanteFormulador->getTipo();
            $_POST['nombre'] = $fabricanteFormulador->getNombre();
            $_POST['estado'] = $fabricanteFormulador->getEstado() === 'activo' ? 'inactivo' : 'activo';
            $_POST['id_pais_origen'] = $fabricanteFormulador->getIdPaisOrigen();
            $_POST['nombre_pais_origen'] = $fabricanteFormulador->getPaisOrigen();
        } else {
            $_POST['estado'] = $datosFabricanteFormulador->current()->estado === 'activo' ? 'inactivo' : 'activo';
            $_POST['id_fabricante_formulador'] = $datosFabricanteFormulador->current()->id_fabricante_formulador;
        }

        $this->guardar();

        echo json_encode(array(
            'estado' => 'EXITO',
            'resultado' => 'Datos actualizados con éxito'
        ));
    }
}

<?php
/**
 * Controlador Usos
 *
 * Este archivo controla la lógica del negocio del modelo:  UsosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-13
 * @uses    UsosControlador
 * @package ModificacionProductoRia
 * @subpackage Controladores
 */

namespace Agrodb\ModificacionProductoRia\Controladores;

use Agrodb\Catalogos\Modelos\ProductoInocuidadUsoLogicaNegocio;
use Agrodb\Catalogos\Modelos\UsosProductosPlaguicidasLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\UsosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\UsosModelo;

class UsosControlador extends BaseControlador
{

    private $lNegocioUsos = null;
    private $modeloUsos = null;
    private $accion = null;
    private $rutaFecha = null;
    private $lNegocioUsosProductosPlaguicidasActual = null;
    private $lNegocioProductoInocuidadUsoActual = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioUsos = new UsosLogicaNegocio();
        $this->modeloUsos = new UsosModelo();

        $this->lNegocioUsosProductosPlaguicidasActual = new UsosProductosPlaguicidasLogicaNegocio();
        $this->lNegocioProductoInocuidadUsoActual = new ProductoInocuidadUsoLogicaNegocio();

        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');

        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloUsos = $this->lNegocioUsos->buscarUsos();
        $this->tablaHtmlUsos($modeloUsos);
        require APP . 'ModificacionProductoRia/vistas/listaUsosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Usos";
        require APP . 'ModificacionProductoRia/vistas/formularioUsosVista.php';
    }

    /**
     * Método para registrar en la base de datos -Usos
     */
    public function guardar()
    {
        $this->lNegocioUsos->guardar($_POST);
    }

    /**
     *Obtenemos los datos del registro seleccionado para editar - Tabla: Usos
     */
    public function editar()
    {
        $this->accion = "Editar Usos";
        $this->modeloUsos = $this->lNegocioUsos->buscar($_POST["id"]);
        require APP . 'ModificacionProductoRia/vistas/formularioUsosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Usos
     */
    public function borrar()
    {
        $this->lNegocioUsos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Usos
     */
    public function tablaHtmlUsos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_uso'] . '"
                        class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'ModificacionProductoRia\usos"
                        data-opcion="editar" ondragstart="drag(event)" draggable="true"
                        data-destino="detalleItem">
                        <td>' . ++$contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['id_uso'] . '</b></td>
                        <td>' . $fila['id_detalle_solicitud_producto'] . '</td>
                        <td>' . $fila['id_cultivo'] . '</td>
                        <td>' . $fila['nombre_cultivo'] . '</td>
                    </tr>'
                );
            }
        }
    }

    public function modificarUsoProducto($parametros, $tiempoAtencion, $idDetalleSolicitudProducto, $estadoSoliciudProducto)
    {
        $idArea = $parametros['id_area'];
        $tipoModificacion = $parametros['tipo_modificacion'];
        $rutaDocumentoRespaldo = $parametros['ruta_documento_respaldo'];
        $filaUso = '';
        $ingresoDatosAgricola = '';
        $ingresoDatosPecuario = '';
        $ingresoDatosFertilizantes = '';
        $banderaAcciones = false;

        switch ($estadoSoliciudProducto) {

            case 'Creado':
            case 'subsanacion':

                $banderaAcciones = true;
                $ingresoDatosAgricola = '
                                <div data-linea="1">
						            <label>Cultivo Nombre Científico: </label>
                                    <select id="id_cultivo" name="id_cultivo" required class="validacion">
                                        <option value="">Cultivo....</option>'
                                        . $this->comboCultivos($idArea) .
                                    '</select>
						            <input type="hidden" name="nombre_cientifico_cultivo" id="nombre_cientifico_cultivo" />
					            </div>
                                <div data-linea="1">
                                    <label>Cultivo Nombre Común: </label>
                                    <input type="text" name="nombre_cultivo" id="nombre_cultivo" readonly="readonly" required="required" data-tiempoatencion="' . $tiempoAtencion . ' días"/>
                                </div>
                                <div data-linea="2">
                                    <label>Plaga Nombre Científico: </label>
                                    <select id="id_plaga" name="id_plaga" required class="validacion">
                                        <option value="">Plaga....</option>'
                                        . $this->comboUsos($idArea) .
                                    '</select>
                                    <input type="hidden" name="nombre_cientifico_plaga" id="nombre_cientifico_plaga" />
                                    <input type="hidden" name="id_area" id="id_area" value="'.$idArea.'" />
                                    <input type="hidden" name="tiempo_atencion" id="tiempo_atencion" value="'.$tiempoAtencion.'" />
                                </div>
                                <div data-linea="2">
                                    <label>Plaga Nombre Común: </label>
                                    <input type="text" name="nombre_plaga" id="nombre_plaga" readonly="readonly" required="required"/>
                                </div>
                                <div data-linea="3">
                                    <label>Dosis: </label>
                                    <input type="text" name="dosis" id="dosis" required="required" class="validacion"/>
                                </div>
                                <div data-linea="3">
                                    <select id="unidad_dosis" name="unidad_dosis" required class="validacion">
                                        <option value="">Unidad....</option>'
                                        . $this->comboUnidadesMedida() .
                                    '</select>
                                </div>
                                <div data-linea="4">
                                    <label>Período de carencia: </label>
                                    <input type="text" name="periodo_carencia" id="periodo_carencia" required="required" class="validacion"/>
                                </div>
                                <div data-linea="5">
                                    <label>Gasto de agua: </label>
                                    <input type="text" name="gasto_agua" id="gasto_agua" />
                                </div>
                                <div data-linea="5">
                                    <select id="unidad_gasto_agua" name="unidad_gasto_agua" >
                                        <option value="" selected="selected">Unidad....</option>'
                                        . $this->comboUnidadesMedida() .
                                    '</select>
                                </div>
                                <hr/>
                                <div data-linea="6">
                                    <label>Documento de respaldo:</label>
                                </div>
                                <div data-linea="7">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . '" name="ruta_documento_respaldo" value="0"/>
                                    <input type="file" class="archivo validacion" id="v' . $tipoModificacion . '" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . ')</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="8">
                        			<button type="button" class="mas" id="agregarUso" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';

                $ingresoDatosPecuario = '
                                <div data-linea="9">
                                    <label>Aplicado a: </label>
                                    <select id="aplicado_a" name="aplicado_a" required class="validacion">
							            <option value="">Seleccione....</option>
                                        <option value="Especie">Especie</option>
                                        <option value="Instalacion">Instalación</option>
						            </select>
                                </div>
                                <div data-linea="10">
                                    <label>Uso</label>
                                    <select id="id_uso_producto" name="id_uso_producto" required class="validacion">
                                        <option value="">Uso....</option>'
                                        . $this->comboUsos($idArea) .
                                        '</select>
                                    <input type="hidden" name="nombre_uso" id="nombre_uso" data-tiempoatencion="' . $tiempoAtencion . ' días"/>
                                    <input type="hidden" name="id_area" id="id_area" value="'.$idArea.'" />
                                    <input type="hidden" name="tiempo_atencion" id="tiempo_atencion" value="'.$tiempoAtencion.'" />
                                </div>
                                <div data-linea="11" class="UsoEspecie" style="display: none">
                                    <label>Especie</label>
                                    <select id="id_especie" name="id_especie" class="validacion">
                                        <option value="">Especie....</option>'
                                        . $this->comboEspecies() .
                                    '</select>
                                    <input type="hidden" name="nombre_especie_tipo" id="nombre_especie_tipo" />
                                </div>
                                <div data-linea="12" class="UsoEspecie" style="display: none">
                                    <label>Nombre Especie</label>
                                    <input type="text" id="nombre_especie" name="nombre_especie" />
                                </div>
                                <div data-linea="13" class="UsoInstalacion" style="display: none">
                                    <label>Instalación</label>
						            <input type="text" id="instalacion" name="instalacion" class="validacion"/>
                                </div>
                                <hr/>
                                <div data-linea="14">
                                    <label>Documento de respaldo:</label>
                                </div>
                                <div data-linea="15">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . '" name="ruta_documento_respaldo" value="0"/>
                                    <input type="file" class="archivo validacion" id="v' . $tipoModificacion . '" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . ')</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="16">
                        			<button type="button" class="mas" id="agregarUso" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';

                $ingresoDatosFertilizantes = '
                                <div data-linea="17">
                                    <label>Aplicado a: </label>
                                    <select id="aplicado_a" name="aplicado_a" required class="validacion">
							            <option value="">Seleccione....</option>
                                        <option value="Instalacion">Instalación</option>
                                        <option value="Producto">Producto</option>
						            </select>
                                </div>
                                <div data-linea="18">
                                    <label>Uso</label>
                                    <select id="id_uso_producto" name="id_uso_producto" required class="validacion">
                                        <option value="">Uso....</option>
                                        '. $this->comboUsos($idArea) .'
                                    </select>
                                    <input type="hidden" name="nombre_uso" id="nombre_uso" data-tiempoatencion="' . $tiempoAtencion . ' días"/>
                                    <input type="hidden" name="id_area" id="id_area" value="'.$idArea.'" />
                                    <input type="hidden" name="tiempo_atencion" id="tiempo_atencion" value="'.$tiempoAtencion.'" />
                                </div>
                                <div data-linea="19" class="UsoInstalacion" style="display: none">	
                                    <label>Instalación</label>
						            <input type="text" id="instalacion"  class="validacion"/>
                                </div>
                                <div data-linea="20" class="UsoProducto" style="display: none">	
                                    <label>Producto</label>
						            <input type="text" id="instalacion_producto"  class="validacion"/>
                                </div>
                                <hr/>
                                <div data-linea="21">
                                    <label>Documento de respaldo:</label>
                                </div>
                                <div data-linea="22">
                                    <input type="hidden" class="rutaArchivo" id="r' . $tipoModificacion . '" name="ruta_documento_respaldo" value="0"/>
                                    <input type="file" class="archivo validacion" id="v' . $tipoModificacion . '" accept="application/pdf" />
                                    <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . ')</div>
                                    <button type="button" class="subirArchivo adjunto" data-rutaCarga="' . MODI_PROD_RIA_URL . $this->rutaFecha . '">Subir archivo</button>
                                </div>
                                <hr/>
                                <div data-linea="23">
                        			<button type="button" class="mas" id="agregarUso" data-tipomodificacion="' . $tipoModificacion . '">Agregar</button>
                        		</div>';
                break;
        }

        $arrayConsulta = [
            'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto,
        ];

        switch ($idArea){
            case 'IAP':

                switch ($estadoSoliciudProducto) {
                    case 'Creado':
                       $arrayConsulta += ['id_producto' => $parametros['id_producto']];
                        break;
                }

                $qDatosUso = $this->lNegocioUsos->buscarUsoOrigenDestinoPlaguicida($arrayConsulta);

                foreach ($qDatosUso as $datosUso) {

                    $idUsoOrigen = $datosUso['id_uso_origen'];
                    $idUso = $datosUso['id_uso'];
                    $nombreCultivo = $datosUso['nombre_cultivo'];
                    $nombreCientificoCultivo = $datosUso['nombre_cientifico_cultivo'];
                    $nombrePlaga = $datosUso['nombre_plaga'];
                    $nombreCintificoPlaga = $datosUso['nombre_cientifico_plaga'];
                    $dosis = $datosUso['dosis'];
                    $unidadDosis = $datosUso['unidad_dosis'];
                    $periodoCarencia = $datosUso['periodo_carencia'];
                    $gastoAgua = $datosUso['gasto_agua'];
                    $unidadGastosAgua = $datosUso['unidad_gasto_agua'];
                    $estado = ($datosUso['estado'] ? $datosUso['estado'] :'activo');

                    $filaUso .=
                        '<tr id="fila' . ($idUsoOrigen ? $idUsoOrigen : $idUso) . '">
                        <td>' . $nombreCultivo . '</td>
                        <td>' . $nombreCientificoCultivo . '</td>
                        <td>' . $nombrePlaga . '</td>
                        <td>' . $nombreCintificoPlaga . '</td>
                        <td>' . $periodoCarencia . '</td>
                        <td>' . $gastoAgua .' '.$unidadGastosAgua.'</td>
                        <td>' . $dosis .' '.$unidadDosis.'</td>';
                    if ($banderaAcciones) {
                        if (!$idUsoOrigen) {
                            $filaUso .=
                                '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarUso(' . $idUso . '); return false;"/>
                    </td>';
                        } else {
                            $filaUso .= '<td class="' . $estado . '">
                            <button type="button" name="eliminar" class="icono" onclick="fn_cambiarEstadoUso(' . $idUsoOrigen . '); return false;"/>
                        </td>';
                        }

                    }else{
                        $filaUso .= '<td>' . $estado .' </td>';
                    }
                    $filaUso .= '</tr>';
                }

                $modificarUso = '';

                if($rutaDocumentoRespaldo){
                    $modificarUso .= '
                    <fieldset>
                        <legend>Documento adjunto</legend>
                        <div data-linea="1">
                            <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                        </div>
                    </fieldset>';
                }

                $modificarUso .= '
                    <fieldset  id="fUsoProducto">
                        <legend>Uso autorizado</legend>
                        ' . $ingresoDatosAgricola . '
                        <table id="tUsoProducto" style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="2">Cultivo</th>
                                    <th colspan="2">Plaga</th>
                                    <th colspan="3"></th>
                                    <th colspan="1"></th>
                                </tr>
                                <tr>
                                    <th>Nombre común</th>
                                    <th>Nombre científico</th>
                                    <th>Nombre común</th>
                                    <th>Nombre científico</th>
                                    <th>Período de carencia</th>
                                    <th>Gasto de agua</th>
                                    <th>Dosis</th>
    						        <th></th>
                                </tr>
                            </thead>
                            <tbody>' . $filaUso . '</tbody>
                        </table>
                    </fieldset>';
                break;
            case 'IAV':

                switch ($estadoSoliciudProducto) {
                    case 'Creado':
                        $arrayConsulta += ['id_producto' => $parametros['id_producto']];
                        break;
                }

                $qDatosUso = $this->lNegocioUsos->buscarUsoOrigenDestinoVeterinarioFertilizantes($arrayConsulta);

                foreach ($qDatosUso as $datosUso) {

                    $idUsoOrigen = $datosUso['id_uso_origen'];
                    $idUso = $datosUso['id_uso'];
                    $nombreUso = $datosUso['nombre_uso'];
                    $nombreEspecieTipo = $datosUso['nombre_especie_tipo'];
                    $nombreEspecie = $datosUso['nombre_especie'];
                    $aplicadoA = $datosUso['aplicado_a'];
                    $instalacion = $datosUso['instalacion'];
                    $estado = ($datosUso['estado'] ? $datosUso['estado'] :'activo');

                    $filaUso .=
                        '<tr id="fila' . ($idUsoOrigen ? $idUsoOrigen : $idUso) . '">
                        <td>' . $nombreUso . '</td>
                        <td>' . $aplicadoA . '</td>
                        <td>' . ($nombreEspecieTipo === null ? $instalacion : $nombreEspecieTipo.' '.$nombreEspecie) . '</td>';
                    if ($banderaAcciones) {
                        if (!$idUsoOrigen) {
                            $filaUso .=
                                '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarUso(' . $idUso . '); return false;"/>
                    </td>';
                        } else {
                            $filaUso .= '<td class="' . $estado . '">
                            <button type="button" name="eliminar" class="icono" onclick="fn_cambiarEstadoUso(' . $idUsoOrigen . '); return false;"/>
                        </td>';
                        }

                    }else{
                        $filaUso .= '<td>' . $estado .' </td>';
                    }
                    $filaUso .= '</tr>';
                }

                $modificarUso = '';

                if($rutaDocumentoRespaldo){
                    $modificarUso .= '
                    <fieldset>
                        <legend>Documento adjunto</legend>
                        <div data-linea="1">
                            <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                        </div>
                    </fieldset>';
                }

                $modificarUso .= '
                    <fieldset  id="fUsoProducto">
                        <legend>Uso autorizado</legend>
                        ' . $ingresoDatosPecuario. '
                        <table id="tUsoProducto" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Uso</th>
                                    <th>Aplicado a</th>
                                    <th>Instalación/Especie</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>' . $filaUso . '</tbody>
                        </table>
                    </fieldset>';
                break;
            case 'IAF':
                
                $qDatosUso = $this->lNegocioUsos->buscarUsoOrigenDestinoVeterinarioFertilizantes($arrayConsulta);

                foreach ($qDatosUso as $datosUso) {

                    $idUsoOrigen = $datosUso['id_uso_origen'];
                    $idUso = $datosUso['id_uso'];
                    $nombreUso = $datosUso['nombre_uso'];
                    $aplicadoA = $datosUso['aplicado_a'];
                    $instalacion = $datosUso['instalacion'];
                    $estado = ($datosUso['estado'] ? $datosUso['estado'] :'activo');

                    $filaUso .=
                        '<tr id="fila' . ($idUsoOrigen ? $idUsoOrigen : $idUso) . '">
                        <td>' . $nombreUso . '</td>
                        <td>' . $aplicadoA . '</td>
                        <td>' . $instalacion . '</td>';
                    if ($banderaAcciones) {
                        if (!$idUsoOrigen) {
                            $filaUso .=
                                '<td class="borrar">
                        <button type="button" name="eliminar" class="icono" onclick="fn_eliminarUso(' . $idUso . '); return false;"/>
                    </td>';
                        } else {
                            $filaUso .= '<td class="' . $estado . '">
                            <button type="button" name="eliminar" class="icono" onclick="fn_cambiarEstadoUso(' . $idUsoOrigen . '); return false;"/>
                        </td>';
                        }

                    }else{
                        $filaUso .= '<td>' . $estado .' </td>';
                    }
                    $filaUso .= '</tr>';
                }

                $modificarUso = '';

                if($rutaDocumentoRespaldo){
                    $modificarUso .= '
                    <fieldset>
                        <legend>Documento adjunto</legend>
                        <div data-linea="1">
                            <label>Certificado de producto: </label>' . ($rutaDocumentoRespaldo === 0 ? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$rutaDocumentoRespaldo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>') . '
                        </div>
                    </fieldset>';
                }

                $modificarUso .= '
                    <fieldset  id="fUsoProducto">
                        <legend>Uso autorizado</legend>
                        ' . $ingresoDatosFertilizantes. '
                        <table id="tUsoProducto" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Uso</th>
                                    <th>Aplicado a</th>
                                    <th>Instalación/Producto</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>' . $filaUso . '</tbody>
                        </table>
                    </fieldset>';
                break;
        }

        return $modificarUso;
    }

    /**
     * Método para listar titularidad de producto agregada
     */
    public function generarFilaUsoProductoVeterinario($idUsoProducto, $datosUsoProducto, $tiempoAtencion)
    {
        $this->listaDetalles = '
                        <tr id="fila' . $idUsoProducto . '">
                            <td>' . $datosUsoProducto['nombre_uso'] . '</td>
                        <td>' . $datosUsoProducto['aplicado_a'] . '</td>
                        <td>' . ($datosUsoProducto['nombre_especie_tipo'] === '' ? $datosUsoProducto['instalacion'] : $datosUsoProducto['nombre_especie_tipo'].' '.$datosUsoProducto['nombre_especie']) . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarUso(' . $idUsoProducto . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

    /**
     * Método para listar titularidad de producto agregada
     */
    public function generarFilaUsoProductoFertilizante($idUsoProducto, $datosUsoProducto, $tiempoAtencion)
    {
        $this->listaDetalles = '
                        <tr id="fila' . $idUsoProducto . '">
                            <td>' . $datosUsoProducto['nombre_uso'] . '</td>
                        <td>' . $datosUsoProducto['aplicado_a'] . '</td>
                        <td>' . $datosUsoProducto['instalacion'] . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarUso(' . $idUsoProducto . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

    /**
     * Método para listar titularidad de producto agregada
     */
    public function generarFilaUsoProductoPlaguicida($idUsoProducto, $datosUsoProducto, $tiempoAtencion)
    {
        $this->listaDetalles = '
                        <tr id="fila' . $idUsoProducto . '">
                            <td>' . $datosUsoProducto['nombre_cultivo'] . '</td>
                            <td>' . $datosUsoProducto['nombre_cientifico_cultivo'] . '</td>
                            <td>' . $datosUsoProducto['nombre_plaga'] . '</td>
                            <td>' . $datosUsoProducto['nombre_cientifico_plaga'] . '</td>
                            <td>' . $datosUsoProducto['periodo_carencia'] . '</td>
                            <td>' . $datosUsoProducto['gasto_agua'].' '. $datosUsoProducto['unidad_gasto_agua'].'</td>
                            <td>' . $datosUsoProducto['dosis'].' '. $datosUsoProducto['unidad_dosis'].'</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarUso(' . $idUsoProducto . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

    /**
     * Método para guardar registro de cambio de estado
     */
    public function guardarEstadoUso()
    {
        $datos = [
            'id_detalle_solicitud_producto' => $_POST['id_detalle_solicitud_producto'],
            'id_tabla_origen' => $_POST['id_tabla_origen']
        ];

        $idArea = $_POST['id_area'];

        $datosUso = $this->lNegocioUsos->buscarLista($datos);

        if (!count($datosUso)) {

            switch ($idArea){
                case 'IAP':
                    $uso = $this->lNegocioUsosProductosPlaguicidasActual->buscar($_POST['id_tabla_origen']);

                    $_POST['id_cultivo'] = $uso->getIdCultivo();
                    $_POST['nombre_cultivo'] = $uso->getCultivoNombreComun();
                    $_POST['nombre_cientifico_cultivo'] = $uso->getCultivoNombreCientifico();
                    $_POST['id_plaga'] = $uso->getIdPlaga();
                    $_POST['nombre_plaga'] = $uso->getPlagaNombreComun();
                    $_POST['nombre_cientifico_plaga'] = $uso->getPlagaNombreCientifico();
                    $_POST['dosis'] = $uso->getDosis();
                    $_POST['unidad_dosis'] = $uso->getUnidadDosis();
                    $_POST['periodo_carencia'] = $uso->getPeriodoCarencia();
                    $_POST['gasto_agua'] = $uso->getGastoAgua();
                    $_POST['unidad_gasto_agua'] = $uso->getUnidadGastoAgua();
                    $_POST['estado'] = 'inactivo';
                    break;
                case 'IAV':
                    $uso = $this->lNegocioProductoInocuidadUsoActual->buscar($_POST['id_tabla_origen']);

                    $_POST['id_uso_producto'] = $uso->getIdUso();
                    $_POST['id_especie'] = $uso->getIdEspecie();
                    $_POST['nombre_especie'] = $uso->getNombreEspecie();
                    $_POST['aplicado_a'] = $uso->getAplicadoA();
                    $_POST['instalacion'] = $uso->getInstalacion();
                    $_POST['estado'] = 'inactivo';
                    break;
                case 'IAF':
                    break;
            }
        } else {
            $_POST['estado'] = $datosUso->current()->estado === 'activo' ? 'inactivo' : 'activo';
            $_POST['id_uso'] = $datosUso->current()->id_uso;
        }

        $this->guardar();

        echo json_encode(array(
            'estado' => 'EXITO',
            'resultado' => 'Datos actualizados con éxito'
        ));
    }
}

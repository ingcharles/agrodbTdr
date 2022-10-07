<?php
/**
 * Controlador Revisiones
 *
 * Este archivo controla la lógica del negocio para el proceso de revisión y Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-06
 * @uses    RevisionesControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\SolicitudLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\SolicitudModelo;
use Agrodb\DossierPecuario\Modelos\SecuenciaRevisionLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\SecuenciaRevisionModelo;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class AdministracionSolicitudesControlador extends BaseControlador
{

    private $lNegocioSolicitud = null;

    private $modeloSolicitud = null;

    private $lNegocioSecuenciaRevision = null;

    private $modeloSecuenciaRevision = null;

    private $lNegocioUsuariosPerfiles = null;

    private $modeloUsuariosPerfiles = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->lNegocioSolicitud = new SolicitudLogicaNegocio();
        $this->modeloSolicitud = new SolicitudModelo();

        $this->lNegocioSecuenciaRevision = new SecuenciaRevisionLogicaNegocio();
        $this->modeloSecuenciaRevision = new SecuenciaRevisionModelo();

        $this->lNegocioUsuariosPerfiles = new UsuariosPerfilesLogicaNegocio();
        $this->modeloUsuariosPerfiles = new UsuariosPerfilesModelo();

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
        $this->cargarPanelAdministracion();

        require APP . 'DossierPecuario/vistas/listaAdministracionSolicitudesVista.php';
    }

    /**
     * Método de inicio del controlador
     */
    public function indexTecnico()
    {
        $this->cargarPanelAdministracion();

        require APP . 'DossierPecuario/vistas/listaRevisionSolicitudesVista.php';
    }

    /**
     * Método de inicio del reporte
     */
    public function indexReporte()
    {
        $this->cargarPanelAdministracion();

        require APP . 'DossierPecuario/vistas/listaReporteSolicitudesVista.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de - Dossier Pecuario
     */
    public function tablaHtmlRevisiones($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_solicitud'] . '"
                                                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario/Solicitud"
                                                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                                                		  data-destino="detalleItem">
                                                		  <td>' . ++ $contador . '</td>
                                                <td>' . $fila['tipo_certificado'] . '</td>
                                                <td style="white - space:nowrap; "><b>' . $fila['codigo_certificado'] . '</b></td>
                                                <td>' . $fila['nombre_pais_destino'] . '</td>
                                                <td>' . $fila['nombre_pais_destino'] . '</td>
                                                <td>' . $fila['estado_certificado'] . '</td>
                                            </tr>'
            );
        }
    }

    /**
     * Construye el código HTML para desplegar la lista de - CertificadoFitosanitario
     */
    public function tablaHtmlDossierPecuarioRevisiones($tabla, $tipoUsuario)
    {
        $contador = 0;
        $controlador = "";
        $opcion = "";

        foreach ($tabla as $fila) {

            switch ($fila['estado_solicitud']) {

                case 'Recibido':
                    {
                        $opcion = "asignarTecnico";
                        break;
                    }

                case 'EnTramite':
                    {
                        if ($tipoUsuario == 'administrador') {
                            $opcion = "asignarTecnico";
                        } else {
                            $opcion = "abrirTecnico";
                        }
                        break;
                    }

                case 'Aprobado':
                case 'Rechazado':
                case 'Subsancion':
                case 'Modificado':
                    {
                        $opcion = "abrir";
                        break;
                    }

                default:
                    {
                        $opcion = "abrir";
                        break;
                    }
            }

            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_solicitud'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario/solicitud"
                		  data-opcion="' . $opcion . '" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                	<td>' . ++ $contador . '</td>
                	<td style="white - space:nowrap; "><b>' . $fila['id_expediente'] . '</b></td>
                    <td>' . $fila['codigo_producto_final'] . '</td>
                    <td>' . $fila['nombre_producto'] . '</td>
                    <td>' . $fila['tipo_solicitud'] . '</td>
                    <td>' . ($fila['estado_solicitud'] == 'Recibido' ? $fila['provincia'] : (isset($fila['id_provincia_revision']) ? $fila['nombre'] : 'Sin asignar')) . '</td>
                    <td>' . $fila['estado_solicitud'] . '</td>
                </tr>'
            );
        }
    }

    /**
     * Construye el código HTML para desplegar panel de busqueda para las solicitudes
     */
    public function cargarPanelAdministracion()
    {
        $perfilTecnico = $this->lNegocioUsuariosPerfiles->buscarUsuariosXAplicacionPerfil($_SESSION['usuario'], 'PFL_ADM_DOS_PEC');

        $this->panelBusquedaAdministrador = '<table class="filtro">
                                            <tbody>
                                                <tr></tr>
                                                <tr>
                            						<td>Número de trámite: </td>
                            						<td colspan=3>
                                                        <input type="text" id="numeroTramiteFiltro" name="numeroTramiteFiltro" style="width: 100%;" />
                            						</td>
                                                </tr>
                            					<tr>
                            						<td>Estado: </td>
                            						<td colspan=3>
                                                        <select id="estadoFiltro" name="estadoFiltro" style="width: 100%;" required>
                                                            <option value="">Seleccione....</option>' . $this->comboEstadosDossierPecuario() . '</select>
                            						</td>
                                                </tr>
                                                <tr class="provincia">
                            						<td>Provincia: </td>
                            						<td colspan=3>
                                                        <select id="idProvinciaFiltro" name="idProvinciaFiltro" style="width: 100%;" disabled="disabled">
                                                            <option value="">Seleccione....</option>' . $this->comboProvinciasEC(null) . '</select>

                                                        <input type="hidden" id="provinciaFiltro" name="provinciaFiltro" style="width: 100%;" />
                            						</td>
                                                </tr>
                                                <tr class="tecnico">
                                                    <td>Técnico: </td>
                            						<td>
                            							<select id="identificadorTecnicoFiltro" name="identificadorTecnicoFiltro" style="width: 100%;" disabled="disabled">
                                                            <option value="">Seleccione....</option>
                                                        </select>
                            						</td>
                            					</tr>
                                                <tr></tr>
                            					<tr>
                            						<td colspan="3">
                            							<button type="button" id="btnFiltrar">Buscar</button>
                            						</td>                                                            
                            					</tr>
                            				</tbody>
                            			</table>';

        $this->panelBusquedaTecnico = '<table class="filtro">
                                            <tbody>
                                                <tr></tr>
                                                <tr>
                            						<td>Número de trámite: </td>
                            						<td colspan=3>
                                                        <input type="text" id="numeroTramiteFiltro" name="numeroTramiteFiltro" style="width: 100%;" />
                            						</td>
                                                </tr>
                            					<tr>
                            						<td>Estado: </td>
                            						<td colspan=3>
                                                        <select id="estadoFiltro" name="estadoFiltro" style="width: 100%;" required>
                                                            <option value="">Seleccione....</option>' . $this->comboEstadosDossierPecuarioTecnico("EnTramite") . '</select>
                            						</td>
                                                </tr>
                                                <tr class="provincia">
                            						<td>Provincia: </td>
                            						<td colspan=3>
                                                        <input type="text" id="provinciaFiltro" name="provinciaFiltro" value="' . $_SESSION['nombreProvincia'] . '" style="width: 100%;" readonly="readonly" />
                                                        <input type="hidden" id="idProvinciaFiltro" name="idProvinciaFiltro" value="' . $_SESSION['idProvincia'] . '" style="width: 100%;" />
                            						</td>
                                                </tr>
                                                <tr class="tecnico">
                                                    <td>Técnico: </td>
                            						<td>
                            							<input type="text" id="tecnicoFiltro" name="tecnicoFiltro" value="' . $_SESSION['datosUsuario'] . '" style="width: 100%;" readonly="readonly" />
                                                        <input type="hidden" id="identificadorTecnicoFiltro" name="identificadorTecnicoFiltro" value="' . $_SESSION['usuario'] . '" style="width: 100%;" />
                            						</td>
                            					</tr>
                                                <tr></tr>
                            					<tr>
                            						<td colspan="3">
                            							<button type="button" id="btnFiltrar">Buscar</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';

        $this->panelBusquedaReporte = '<form id="filtrar" action="aplicaciones/mvc/DossierPecuario/Solicitud/exportarReporteSolicitudSecuenciaRevisionExcel" target="_blank" method="post">
                                            <table class="filtro" >
                                                <tbody>
                                                    <tr></tr>
                                                    <tr>
                                						<td>Número de trámite: </td>
                                						<td colspan=3>
                                                            <input type="text" id="numeroTramiteFiltro" name="numeroTramiteFiltro" style="width: 100%;" />
                                						</td>
                                                    </tr>
                                					<tr>
                                						<td>RUC/RISE: </td>
                                						<td colspan=3>
                                                            <input type="text" id="identificadorFiltro" name="identificadorFiltro" style="width: 100%;" />
                                						</td>
                                                    </tr>
                                                    <tr>
                                						<td>Fecha Inicio: </td>
                                						<td colspan=3>
                                                            <input type="text" id="fechaInicioFiltro" name="fechaInicioFiltro" style="width: 100%;" required="required"/>
                                						</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Fecha Fin: </td>
                                						<td>
                                							<input type="text" id="fechaFinFiltro" name="fechaFinFiltro" style="width: 100%;" required="required"/>
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
     * Combo de fases de revisión
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboEstadosDossierPecuario($opcion = null)
    {
        $combo = "";
        if ($opcion == "Recibido") {
            $combo .= '<option value="Recibido" selected="selected">Recibido</option>';
            $combo .= '<option value="EnTramite">En trámite</option>';
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Subsanacion">Subsanacion</option>';
            $combo .= '<option value="Modificado">Modificado</option>';
        } else if ($opcion == "EnTramite") {
            $combo .= '<option value="Recibido">Recibido</option>';
            $combo .= '<option value="EnTramite" selected="selected">En trámite</option>';
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Subsanacion">Subsanacion</option>';
            $combo .= '<option value="Modificado">Modificado</option>';
        } else if ($opcion == "Aprobado") {
            $combo .= '<option value="Recibido">Recibido</option>';
            $combo .= '<option value="EnTramite">En trámite</option>';
            $combo .= '<option value="Aprobado" selected="selected">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Subsanacion">Subsanacion</option>';
            $combo .= '<option value="Modificado">Modificado</option>';
        } else if ($opcion == "Rechazado") {
            $combo .= '<option value="Recibido">Recibido</option>';
            $combo .= '<option value="EnTramite">En trámite</option>';
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado" selected="selected">Rechazado</option>';
            $combo .= '<option value="Subsanacion">Subsanacion</option>';
            $combo .= '<option value="Modificado">Modificado</option>';
        } else if ($opcion == "Subsanacion") {
            $combo .= '<option value="Recibido">Recibido</option>';
            $combo .= '<option value="EnTramite">En trámite</option>';
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Subsanacion" selected="selected">Subsanacion</option>';
            $combo .= '<option value="Modificado">Modificado</option>';
        } else if ($opcion == "Modificado") {
            $combo .= '<option value="Recibido">Recibido</option>';
            $combo .= '<option value="EnTramite">En trámite</option>';
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Subsanacion">Subsanacion</option>';
            $combo .= '<option value="Modificado" selected="selected">Modificado</option>';
        } else {
            $combo .= '<option value="Recibido">Recibido</option>';
            $combo .= '<option value="EnTramite">En trámite</option>';
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Subsanacion">Subsanacion</option>';
            $combo .= '<option value="Modificado">Modificado</option>';
        }
        return $combo;
    }

    /**
     * Combo de fases de revisión
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboEstadosDossierPecuarioTecnico($opcion = null)
    {
        $combo = "";
        if ($opcion == "EnTramite") {
            $combo .= '<option value="EnTramite" selected="selected">En trámite</option>';
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Modificado">Modificado</option>';
        } else if ($opcion == "Aprobado") {
            $combo .= '<option value="EnTramite">En trámite</option>';
            $combo .= '<option value="Aprobado" selected="selected">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Modificado">Modificado</option>';
        } else if ($opcion == "Rechazado") {
            $combo .= '<option value="EnTramite">En trámite</option>';
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado" selected="selected">Rechazado</option>';
            $combo .= '<option value="Modificado">Modificado</option>';
        } else if ($opcion == "Modificado") {
            $combo .= '<option value="EnTramite">En trámite</option>';
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Modificado" selected="selected">Modificado</option>';
        } else {
            $combo .= '<option value="EnTramite">En trámite</option>';
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Modificado">Modificado</option>';
        }
        return $combo;
    }

    /**
     * Consulta los técnicos habilitados por provincia
     * y construye el combo
     */
    public function comboTecnicosXProvincia()
    {
        $idProvincia = $_POST['idProvincia'];

        $usuarios = "";
        $combo = $this->lNegocioUsuariosPerfiles->buscarUsuariosIEXPerfilProvincia('PFL_TEC_DOS_PEC', $idProvincia);

        if ($combo != null) {
            foreach ($combo as $item) {
                $usuarios .= '<option value="' . $item->identificador . '">' . $item->usuario . '</option>';
            }
        }

        echo $usuarios;
        exit();
    }

    /**
     * Método para listar las solicitudes registradas
     */
    public function listarSolicitudesAdministracionFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        $idExpediente = $_POST['numeroTramiteFiltro'];
        $estadoSolicitud = $_POST["estadoFiltro"];
        $idProvincia = $_POST['idProvinciaFiltro'];
        $identificadorTecnico = $_POST['identificadorTecnicoFiltro'];
        $filtro = $_POST['filtro'];

        $arrayParametros = array(
            'id_expediente' => $idExpediente,
            'estado_solicitud' => $estadoSolicitud,
            'id_provincia_revision' => $idProvincia,
            'identificador_tecnico' => $identificadorTecnico,
            'filtro' => $filtro
        );

        $solicitudes = $this->lNegocioSolicitud->buscarSolicitudesFiltradas($arrayParametros);

        $this->tablaHtmlDossierPecuarioRevisiones($solicitudes, $filtro);

        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);

        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
}
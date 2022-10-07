<?php

/**
 * Controlador ResultadoAnalisis
 *
 * Este archivo controla la lógica del negocio del modelo:  ResultadoAnalisisModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ResultadoAnalisisControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\ResultadoAnalisisLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ResultadoAnalisisModelo;
use Agrodb\Laboratorios\Modelos\CamposResultadosInformesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\TipoAnalisisLogicaNegocio;
use Agrodb\Laboratorios\Modelos\RecepcionMuestrasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\OrdenesTrabajosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ServiciosLogicaNegocio;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class ResultadoAnalisisControlador extends FormularioDinamicoResultados
{

    private $lNegocioResultadoAnalisis = null;
    private $modeloResultadoAnalisis = null;
    private $lNCamposResultadosInformes = null;
    private $lNegocioTipoAnalisis = null;
    private $accion = null;
    private $itemsMuestras = null;
    private $verAccion = false;
    private $idOrdenTrabajo = null;
    public $arrayTitulo = array();
    public $permisoAcreditacion = 0;
    public $servicioAcreditado = 0;
    public $fCodigoMuestra = null;     //filtro codigo de la muestra en el formulario
    public $fAnalisisMuestra = null;     //filtro nombre del analisis de la muestra en el formulario
    public $respuestaHtml = null;
    private $idLaboratorio;             //id del laboratorio

    /**
     * Constructor
     */

    function __construct()
    {
        parent::__construct();
        $this->lNegocioResultadoAnalisis = new ResultadoAnalisisLogicaNegocio();
        $this->modeloResultadoAnalisis = new ResultadoAnalisisModelo();
        $this->lNCamposResultadosInformes = new CamposResultadosInformesLogicaNegocio();
        $this->lNegocioTipoAnalisis = new TipoAnalisisLogicaNegocio();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloResultadoAnalisis = $this->lNegocioResultadoAnalisis->buscarResultadoAnalisis();
        $this->tablaHtmlResultadoAnalisis($modeloResultadoAnalisis);
        require APP . 'Laboratorios/vistas/listaResultadoAnalisisVista.php';
    }

    /**
     * Busca los campos de resultado de acuerdo a la muestra seleccionada
     * Solamente para VERTICAL(modal)
     */
    public function verCamposResultado()
    {
        $rama = $_POST['rama'];
        $idRecepcionMuestras = $_POST['idRecepcionMuestras'];

        $estado = '';
        $mensaje = '';
        $lista = "";
        $camposResultadoModal = '';

        $resultado = $this->lNCamposResultadosInformes->buscarCamposResultado($rama, '0');
        if (count($resultado) > 0)
        {
            $estado = 'EXITO';
            $fila = $resultado->current();
            $idServicio = $fila->id_servicio;
            $camposResultadoModal = $this->camposParaResultado($idServicio, $idRecepcionMuestras);
            $lista = $this->obtenerResultadosGuardados($_POST['idRecepcionMuestras'], $_POST['idServicio'], TRUE);
        } else
        {
            $estado = 'ERROR';
            $mensaje = Constantes::NO_FORMULARIO_RESULTADO;
        }

        //buscar datos de la muestra
        $lNRecepcionMuestras = new RecepcionMuestrasLogicaNegocio();
        $buscaMuestra = $lNRecepcionMuestras->buscarMuestras(array('id_recepcion_muestras' => $idRecepcionMuestras));
        $filaMuestra = $buscaMuestra->current();
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'codigo' => $filaMuestra->codigo_lab_muestra,
            'analisis' => $filaMuestra->rama_nombre,
            'formulario' => $camposResultadoModal,
            'lista' => $lista
        ));
    }

    /**
     * Busca los campos de resultado de acuerdo a la muestra seleccionada
     * Solamente para VERTICAL(modal)
     */
    public function verCamposResultadoEditar()
    {
        $camposResultadoModal = "";
        $rama = $_POST['rama'];
        $idRecepcionMuestras = $_POST['idRecepcionMuestras'];
        $numResultado = $_POST['numResultado'];
        $resultado = $this->lNCamposResultadosInformes->buscarCamposResultado($rama, '0');
        $fila = $resultado->current();
        $idServicio = $fila->id_servicio;
        $camposResultadoModal .= $this->camposParaResultado($idServicio, $idRecepcionMuestras, $numResultado);
        echo $camposResultadoModal;
    }

    /**
     * Construye la tabla de los resultados guardados
     * Usado para Analizar muestras y Validar información
     * @param type $idRecepcionMuestra
     * @param type $idServicio
     * @return type
     */
    public function obtenerResultadosGuardados($idRecepcionMuestra, $idServicio, $verAcciones = FALSE, $estadoAnalisis = NULL)
    {
        $lNResultado = new ResultadoAnalisisLogicaNegocio();
        $html = "";

        $buscaResultados = $lNResultado->buscarResultadosPorMuestra($idRecepcionMuestra, $idServicio, $estadoAnalisis);
        if (count($buscaResultados) > 0)
        {
            $titulos = array();
            $cuerpoTabla = "";
            foreach ($buscaResultados as $fila)
            {
                $tr = "<tr><td>$fila->num_resultado</td>";
                $tr .= "<td>$fila->estado_ra</td>";
                if ($fila->estado_ra == 'ACTIVO' & $verAcciones == TRUE)
                {
                    //formar botones para las acciones de editar y anular
                    $tr .= "<td><button type='button' name='editar' id='editar' class='fas fa-edit' title='Editar este análisis' onClick='fn_editar($fila->num_resultado)'/></td>";
                    $tr .= "<td><button type='button' name='anular' id='anular' class='fas fa-minus' title='Anular este análisis' onClick='fn_anular($fila->num_resultado)'/></td>";
                }

                $camposJson = json_decode($fila->campos, TRUE);
                if ($camposJson !== null)
                {
                    foreach ($camposJson as $campo)
                    {
                        $nombreCampo = $campo['nombre'];
                        $titulos[$nombreCampo] = $nombreCampo;
                        $valor = "";
                        if (in_array($campo['tipo_campo'], array('COMBOBOX', 'CHECKLIST')))
                        {
                            foreach ($campo['opciones'] as $fila)
                            {
                                if ($fila['valor'] == 'check')
                                {
                                    $valor .= $fila['nombre'] . "</br>";
                                }
                            }
                        } else
                        {
                            $valor = $campo['valor'];
                        }
                        $tr .= "<td>$valor</td>";
                    }
                }
                $tr .= "</tr>";
                $cuerpoTabla .= $tr;
            }
            $cabeceraTabla = "<thead class='thead-light'><tr>"
                    . "<th>Num.</th>"
                    . "<th>Estado</th>";
            if ($verAcciones == TRUE)
            {
                $cabeceraTabla .= "<th>Editar</th><th>Anular</th>";
            }
            $cabeceraTabla .= "<th>" . implode("</th><th>", $titulos) . "</th></tr></thead>";
            $html = "<table class='table'>$cabeceraTabla $cuerpoTabla</table>";
        }
        return $html;
    }

    /**
     * Anular un resultado de análisis
     */
    public function anularAnalisis()
    {
        $this->lNegocioResultadoAnalisis->anularAnalisis($_POST['idRecepcionMuestras'], $_POST['numResultado']);

        $resultado = $this->lNCamposResultadosInformes->buscarCamposResultado($_POST['rama'], '0');
        $fila = $resultado->current();

        $camposResultadoModal = $this->camposParaResultado($fila->id_servicio, $_POST['idRecepcionMuestras']);
        $lista = $this->obtenerResultadosGuardados($_POST['idRecepcionMuestras'], $_POST['idServicio'], TRUE);
        echo json_encode(array('formulario' => $camposResultadoModal, 'lista' => $lista));
    }

    /**
     * Para mostrar la tabla de los resultados ingresados para Validad Informacion
     * en el menú Validar información
     */
    public function verResultadosIngresados()
    {
        $rama = $_POST['rama'];
        $resultado = $this->lNCamposResultadosInformes->buscarCamposResultado($rama, '0');
        $fila = $resultado->current();
        $idServicio = $fila->id_servicio;
        $lista = $this->obtenerResultadosGuardados($_POST['idRecepcionMuestras'], $_POST['idServicio'], FALSE, 'ACTIVO');
        echo $lista;
    }

    /**
     * Para mostrar el formulario
     */
    public function verMuestrasIdoneas()
    {
        $this->idOrdenTrabajo = $_POST['id'];
        require APP . 'Laboratorios/vistas/formularioResultadoAnalisisVista.php';
    }

    /**
     * Listar las muestras por órden de trabajo
     */
    public function listarDatos($idOrdenTrabajo)
    {
        //id del laboratorio
        $lNegocioOrdenTrabajo = new OrdenesTrabajosLogicaNegocio();
        $buscaOrden = $lNegocioOrdenTrabajo->buscarLista(array('id_orden_trabajo' => $idOrdenTrabajo));
        $orden = $buscaOrden->current();
        $this->idLaboratorio = $orden->id_laboratorio;

        $codigo = $_POST['fCodigoMuestra'];
        $analisis = $_POST['fAnalisisMuestra'];

        //buscar permiso
        $this->permisoAcreditacion = $this->obtenerPermisoLaboratorio($_POST['idLaboratoriosProvincia'], 'acreditacion');

        $lNegocioRecepcionMuestras = new RecepcionMuestrasLogicaNegocio();
        $buscaServiciosRM = $lNegocioRecepcionMuestras->buscarRMServicios($idOrdenTrabajo);
        foreach ($buscaServiciosRM as $fila)
        {
            $arrayParametros = array(
                'es_idonea' => 'SI',
                'codigo' => $codigo,
                'analisis' => $analisis,
                'estado_actual' => array('IDONEA', 'ANALIZADA', 'NO APROBADO'),
                'id_servicio' => $fila->id_servicio,
                'derivada' => 'NO');
            if ($fila->despliegue == 'VERTICAL')
            {
                $resultado = $this->lNegocioResultadoAnalisis->buscarMuestrasIdoneas($idOrdenTrabajo, $arrayParametros);
            } else
            {
                $resultado = $this->lNegocioResultadoAnalisis->buscarMuestrasIdoneasResultados($idOrdenTrabajo, $arrayParametros);
            }
            $this->tablaHtmlMuestrasIdoneas($resultado, $_POST['idLaboratoriosProvincia']);
            echo $this->itemsMuestras;
        }
    }

    /**
     * Construye el código HTML para desplegar la lista de muestras
     */
    public function tablaHtmlMuestrasIdoneas($tabla, $idLaboratoriosProvincia)
    {
        $html = "";
        $idServicio = "";
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $this->idOrdenTrabajo = $fila->id_orden_trabajo;
                $idServicio = $fila->id_servicio;
                $camposResultado = "";
                $boton = "";

                $campoAux = "";
                if ($fila->despliegue == 'HORIZONTAL')
                {
                    $numResultado = "";
                    if ($fila->num_resultado > 0)
                    {
                        $numResultado = $fila->num_resultado;
                    }
                    $result = $this->formarCampos($fila->id_recepcion_muestras, $fila->id_servicio, $fila->campos, $numResultado);
                    $camposResultado = $result[0];
                    $this->arrayTitulo = $result[1];
                    $this->verAccion = TRUE;    //para mostrar boton guardar al final
                    //campo auxiliar para rescuentos de reactivos en caso horizontal
                    $campoAux = $fila->id_recepcion_muestras . "-" . $fila->id_servicio . "-" . $idLaboratoriosProvincia . "-" . $numResultado;
                    $campoAux = "<input type='hidden' name='campoAux[]' value='$campoAux'/>";
                } else
                {
                    $this->arrayTitulo = array();
                    $this->verAccion = FALSE;
                    $boton = '<td style="text-align:center"><button type="button" id="btnResultado" onclick="fn_camposResultado(' . $fila->id_recepcion_muestras . ',' . $fila->id_servicio . ',' . "'" . $fila->rama . "'" . ')" class="far fa-window-restore"> </button></td>';
                }

                //acreditacion
                $acreditacion = "";
                if ($this->permisoAcreditacion)
                {
                    //buscamos si el servicio es acreditado
                    $objServicio = new ServiciosLogicaNegocio();
                    $datosServicio = $objServicio->buscar($fila->id_servicio);

                    if (trim($datosServicio->getAcreditacion()) != 'NO')
                    {
                        $acreditacion = '<td style="text-align:center"><select onchange="fn_actualizarAcreditado(' . $fila->id_recepcion_muestras . ',this)">
                                    ' . $this->crearComboSINO($fila->acreditado) . '</select></td>';
                        $this->servicioAcreditado = 1;
                    } else
                    {
                        $this->servicioAcreditado = 0;
                    }
                }

                $html .= '<tr">
		  <td>' . $fila->numero_muestra . $campoAux . '</td>
                  <td style="text-align:center">' . $this->botonDatosMuestra($fila->id_recepcion_muestras) . '</td>
		  <td>' . $fila->codigo_lab_muestra . '</b></td>
                  <td>' . $fila->codigo_usu_muestra . '</td>
                  <td>' . $fila->rama_nombre . '</td>
                  <td>' . $fila->fecha_inicio_analisis . '</td>'
                        . $acreditacion .
                        '<td style="text-align:center">' . $fila->estado_actual . '</td>'
                        . $camposResultado
                        . $boton .
                        '</tr>';
            }
            $this->formarTabla($html, $idServicio);
        } else
        {
            $html = "<tr><td colspan='5'>No existen datos para mostrar</td></tr>";
            $this->formarTabla($html, $idServicio);
        }
    }

    /**
     * Horizontal
     * @param type $items
     */
    public function formarTabla($items, $idServicio = 0)
    {
        $html = ' <form id="formMuestra' . $idServicio . '">';
        $html.='<button id="sbm' . $idServicio . '" style="display: none"/>';
        $html .= "<table style='width: 100%'>
            <thead>
                <tr>
                    <th>#</th>
                    <th title='Opci&oacute;n para ver datos de la muestra en ventana modal'>Datos</br>Muestra</th>
                    <th>C&oacute;digo</th>
                    <th>" . $this->obtenerAtributoLaboratorio($this->idLaboratorio, 'm_cod_campo') . "</th>
                    <th>An&aacute;lisis</th>
                    <th>Fecha inicio An&aacute;lisis</th>";
        if ($this->servicioAcreditado)
        {
            $html .= "<th>Acreditado</th>";
        }
        $html .= "<th>Estado</th>";

        //boton para ingresar analisis en VERTICAL
        if ($this->verAccion == FALSE)
        {
            $html .= "<th>Ingresar</br>An&aacute;lisis</th>";
        }
        //titulos si es horizontal
        if ($this->arrayTitulo)
        {
            foreach ($this->arrayTitulo as $val)
            {
                $html .= "<th>$val</th>";
            }
        }
        $html .= "</tr>
            </thead>
            <tbody>$items</tbody>
        </table>";
        if ($this->verAccion == TRUE)
        {

            $html .= '<button type="button"  onclick="fn_guardarAnalisis(' . $idServicio . ')" class="far fa-save"> Guardar</button>';
            $html .= '<small id="msg' . $idServicio . '"> </small>';
        }
        $html.='<input type="hidden" name="identificador" id="identificador" value="' . $this->usuarioActivo() . '">
        <input type="hidden" id="id_orden_trabajo" name="id_orden_trabajo" value="' . $this->idOrdenTrabajo . '"/>';
        $html.='</form>';
        $this->itemsMuestras = $html;
    }

    /**
     * Método para registrar en la base de datos -ResultadoAnalisis
     */
    public function guardarTipoVertical()
    {
        $_POST['tipo_informe'] = Constantes::tipo_informe()->PRINCIPAL;
        $_POST['identificador'] = parent::usuarioActivo();
        $respuesta = $this->lNegocioResultadoAnalisis->guardarTipoVertical($_POST);
        $this->construirRespuesta($respuesta);

        $resultado = $this->lNCamposResultadosInformes->buscarCamposResultado($_POST['rama'], '0');
        $fila = $resultado->current();

        $camposResultadoModal = $this->camposParaResultado($fila->id_servicio, $_POST['idRecepcionMuestras']);
        $lista = $this->obtenerResultadosGuardados($_POST['idRecepcionMuestras'], $_POST['idServicio'], TRUE);
        echo json_encode(array(
            "estado" => "EXITO",
            "mensaje" => Constantes::GUARDADO_CON_EXITO,
            'formulario' => $camposResultadoModal,
            'lista' => $lista,
            'respuesta' => $this->respuestaHtml));
    }

    /**
     * Custruir html de la respuesta de los descuentos automaticos de los reactivos en un analisis
     * @param type $respuesta
     * @return string
     */
    public function construirRespuesta($respuesta)
    {
        $html = "";
        foreach ($respuesta as $filaRespuesta)
        {
            $data = json_decode($filaRespuesta, TRUE);
            $arrayError = array();
            $arrayExito = array();
            foreach ($data as $fila)
            {
                if ($fila['RESULT'] == 'ERROR')
                {
                    $datos = $fila['DATO'];
                    foreach ($datos as $filaDato)
                    {
                        if ($filaDato['MSG'] == 'NO_EXISTE_RECETA')
                        {
                            $texto = Constantes::NO_EXISTE_RECETA;
                            $arrayError[] = "<i class='fas fa-circle'></i>$texto</br>";
                        } else
                        {
                            $texto = str_replace('{REACTIVO}', $filaDato['REACTIVO'], Constantes::NO_EXISTE_EN_LABORATORIO);
                            $texto = str_replace('{CANTIDAD}', $filaDato['CANTIDAD'], $texto);
                            $arrayError[] = "<i class='fas fa-circle'></i>$texto</br>";
                        }
                    }
                } else if ($fila['RESULT'] == 'EXITO')
                {
                    $datos = $fila['DATO'];
                    foreach ($datos as $filaDato)
                    {
                        $texto = str_replace('{REACTIVO}', $filaDato['REACTIVO'], Constantes::DESCUENTO_EXITOSO);
                        $texto = str_replace('{CANTIDAD}', $filaDato['CANTIDAD'], $texto);
                        $arrayExito[] = "<i class='fas fa-circle'></i>$texto</br>";
                    }
                }
            }
            if (count($arrayError) > 0)
            {
                $html .= "<div class='alert alert-warning' role='alert' style='text-align:left'>" . implode("", $arrayError) . "</div>";
            }
            if (count($arrayExito) > 0)
            {
                $html .= "<div class='alert alert-success' role='alert' style='text-align:left'>" . implode("", $arrayExito) . "</div>";
            }
        }
        $this->respuestaHtml = $html;
    }

    /**
     * Método para registrar en la base de datos -ResultadoAnalisis
     */
    public function guardarTipoHorizontal()
    {
        $_POST['tipo_informe'] = Constantes::tipo_informe()->PRINCIPAL;
        $_POST['identificador'] = parent::usuarioActivo();
        $respuesta = $this->lNegocioResultadoAnalisis->guardarTipoHorizontal($_POST);
        $this->construirRespuesta($respuesta);
        echo Constantes::GUARDADO_CON_EXITO;
        exit();
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ResultadoAnalisis
     */
    public function editar()
    {
        $this->accion = "Editar ResultadoAnalisis";
        $this->modeloResultadoAnalisis = $this->lNegocioResultadoAnalisis->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioResultadoAnalisisVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ResultadoAnalisis
     */
    public function borrar()
    {
        $this->lNegocioResultadoAnalisis->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ResultadoAnalisis
     */
    public function tablaHtmlResultadoAnalisis($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_resultado_analisis'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/ResultadoAnalisis"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_resultado_analisis'] . '</b></td>
                  <td>' . $fila['id_parametros_servicio'] . '</td>
                  <td>' . $fila['id_laboratorio'] . '</td>
                  <td>' . $fila['id_informe_analisis'] . '</td>
                </tr>');
        }
    }

    /**
     * Para actualizar el campo g_laboratorios.recepcion_muestras.acreditado
     * @param type $idRecepcionMuestras
     */
    public function actualizarAcreditado($idRecepcionMuestras)
    {
        $lNegocioRecepcionMuestras = new RecepcionMuestrasLogicaNegocio();
        $lNegocioRecepcionMuestras->actualizarAcreditado($idRecepcionMuestras, $_POST['acreditado']);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

}

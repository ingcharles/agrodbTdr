<?php

/**
 * Controlador Solicitudes
 *
 * Este archivo controla la lógica del negocio del modelo:  SolicitudesModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     SolicitudesControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;


use Agrodb\Laboratorios\Modelos\BandejaresponsabletecnicoLogicaNegocio;
use Agrodb\Laboratorios\Modelos\OrdenesTrabajosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\OrdenesTrabajosModelo;
use Agrodb\Laboratorios\Modelos\TipoAnalisisLogicaNegocio;
use Agrodb\Laboratorios\Modelos\RecepcionMuestrasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ResultadoAnalisisLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ArchivoInformeAnalisisLogicaNegocio;
use Agrodb\Laboratorios\Modelos\MarbetesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\SolicitudesLogicaNegocio;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;
class BandejaResponsableTecnicoControlador extends FormularioDinamico
{
    private $lNegocioRecepcionMuestras = null;
    private $lNegocioResultadoAnalisis = null;
    public $itemsOrdenesTrabajo = array();
    public $itemsMuestras = array();
    private $bandejaRT = null;
    private $lNegocioOrdenTrabajo = null;
    private $lNegocioTipoAnalisis = null;
    private $estados;   //para el filtro de estado de órdenes de trabajo
    public $idSolicitud;
    public $idLaboratorio;
    public $idOrdenTrabajo;
    public $datosInforme = "";
    public $modeloOrdenTrabao = null;
    public $idoneaEnProceso = false;   //Permitir declarar idonea una muestra durante el proceso de an&aacute;lisis 
    public $modeloSolicitudes;
    private $modeloLaboratorios;
    public $itemsMuestrasAlmacenadas;

    /*
     * Constructor
     */

    function __construct()
    {
        parent::__construct();
        $this->estados = array(
            Constantes::estado_OT()->REGISTRADA,
            Constantes::estado_OT()->ACTIVA,
            Constantes::estado_OT()->EN_PROCESO,
            Constantes::estado_OT()->FINALIZADA
        );
        $this->lNegocioResultadoAnalisis = new ResultadoAnalisisLogicaNegocio();
        $this->bandejaRT = new BandejaresponsabletecnicoLogicaNegocio();
        $this->lNegocioOrdenTrabajo = new OrdenesTrabajosLogicaNegocio();
        $this->lNegocioTipoAnalisis = new TipoAnalisisLogicaNegocio();
        $this->lNegocioRecepcionMuestras = new RecepcionMuestrasLogicaNegocio();

        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /*     * ***************** ORDENES DE TRABAJO ********* */

    /**
     * Método de inicio del controlador
     * ORDENES DE TRABAJO
     */
    public function index()
    {
        parent::laboratorioUsuario();
        require APP . 'Laboratorios/vistas/listaBandejaRTVista.php';
    }

    /**
     * Búsqueda por filtro de las ordenes de trabajo
     * ORDENES DE TRABAJO
     */
    public function listarDatos()
    {
        $estado = $_POST['estado_orden'];
        if (empty($_POST['estado_orden']))
        {
            $estado = $this->estados;
        }
        $arrayParametros = array();
        if ($estado == Constantes::estado_OT()->EN_PROCESO)
        {

            $enProceso = array(Constantes::estado_OT()->EN_PROCESO, Constantes::estado_OT()->EN_ANALISIS, Constantes::estado_OT()->EN_APROBACION, Constantes::estado_OT()->FIRMADO);
            $arrayParametros = array(
                'identificador' => parent::usuarioActivo(),
                'estado_orden' => $enProceso);
        } else
        {
            $arrayParametros = array(
                'identificador' => parent::usuarioActivo(),
                'estado_orden' => $estado);
        }
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        if (!empty($_POST['codigo_ot']))
        {
            $arrayParametros['codigo_ot'] = $_POST['codigo_ot'];
        }
        //$OrdenesTrabajo = $this->bandejaRT->buscarBandejaOT($arrayParametros);
        $OrdenesTrabajo = $this->bandejaRT->buscarOrdenesT($arrayParametros);
        $this->tablaHtmlOrdenesT($OrdenesTrabajo);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Construye el código HTML para desplegar las órdenes de trabajo
     * ORDENES DE TRABAJO
     */
    public function tablaHtmlOrdenesT($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $botonPdf = '';
                if ($fila->estado_orden != 'REGISTRADA')
                {

                    $botonPdf = "<button class=\"bntGrid fas fa-file-pdf\" onclick=\"fn_verPdf(" . $fila->id_orden_trabajo . ")\"/>";
                }
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_orden_trabajo . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/BandejaResponsableTecnico"
		  data-opcion="verMuestras" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->codigo_ot . '</b></td>
                    <td>' . $fila->cliente . '</td>
                    <td style="text-align:center">' . $this->botonDatosSolicitud($fila->id_solicitud) . '</td>
                    <td style="text-align:center">' . $botonPdf . '</td>
                    <td style="text-align:center">' . $this->botonInformes($fila->id_solicitud, $fila->id_orden_trabajo) . '</td>
                    <td>' . $fila->fecha_activacion . '</td>
                    <td>' . $fila->estado_orden . '</td>
                </tr>'
                );
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * Del menú Ordenes de trabajo, al dar doble clic no debe desplegarse nada.
     * Se permite seleccionar con un clic (color amarillo) y luego clic en los botones superiores
     * Informes, Generar etiquetas, Muestras almacenadas
     */
    public function verMuestras()
    {
          if(isset($idOrdenTrabajo)){                         
        $this->modeloOrdenTrabajo = new OrdenesTrabajosModelo();
        $this->modeloOrdenTrabajo = $this->lNegocioOrdenTrabajo->buscar($idOrdenTrabajo);
        $arrayParametros = array(
            'id_orden_trabajo' => $idOrdenTrabajo);
        $recepcionMuestras = $this->lNegocioRecepcionMuestras->buscarMuestras($arrayParametros);
        $this->tablaHtmlVerMuestras($recepcionMuestras, $this->modeloOrdenTrabajo->getEstado());
        require APP . 'Laboratorios/vistas/listaMuestrasVista.php';
       
        }else{
             echo "<script> mostrarMensaje('".Constantes::INF_OT_RT."', 'fallo'); </script>";
             exit();
        }
    }

    /**
     * VERIFICAR MUESTRAS
     * Construye el código HTML para desplegar la lista de muestras
     */
    public function tablaHtmlVerMuestras($tabla, $estadoOT)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            $numMuestra = 0;
            $dato = "";
            foreach ($tabla as $fila)
            {
                $numMuestra = $fila['numero_muestra'];
                $html = "<tr>$dato</tr>"; //la primera vez va vacio y como va como array entonces no toma en cuenta

                $this->itemsMuestras[] = array($html);
                $dato = '<td>' . ++$contador . '</td>';
                $dato .= '<td style="text-align:center">' . $this->botonDatosMuestra($fila->id_recepcion_muestras) . '</td>';
                $dato .= '<td><b>' . $fila->codigo_usu_muestra . '</b></td>';
                $dato .= '<td>' . $fila->rama_nombre . '</td>';
                $dato .= '<td>' . $fila->es_idonea . ' </td>';
                $dato .= '<td>' . $fila->fecha_verificada . '</td>';
                $dato .= '<td>' . $fila->observacion_recepcion . '</td>';
                $dato .= '<td>' . $fila->observacion_verificacion . '</td>';
                $dato .= '<td>' . $fila->observacion_analisis . '</td>';
                $dato .= '<td>' . $fila->observacion_aprobacion . '</td>';
                $dato .= '<td>' . $fila->estado_actual . '</td>';
                $dato .= '</tr>';
            }
            $this->itemsMuestras[] = array($dato);
        } else
        {
            $this->itemsMuestras[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * Para ver las muestras almacenadas
     */
    public function muestras($idLaboratoriosProvincia)
    {
        $this->accion = "Almacenar muestras";
        $idOrdenTrabajo = $_POST['elementos'];
        if ($idOrdenTrabajo !== '')
        {
            $this->idOrdenTrabajo = $idOrdenTrabajo;
        }
        $arrayParametros = array(
            'id_laboratorios_provincia' => $idLaboratoriosProvincia,
            'id_orden_trabajo' => $idOrdenTrabajo,
            'estado_actual' => array(Constantes::estado_MU()->ALMACENADA, Constantes::estado_MU()->DESECHADA));
        $muestras = $this->lNegocioRecepcionMuestras->buscarMuestras($arrayParametros);
        $this->tablaHtmlMuestrasAlmacenadas($muestras);
        require APP . 'Laboratorios/vistas/formularioMuestrasAlmacenadasVista.php';
    }

    /**
     * Actualizar datos del formulario Almacenar muestras
     */
    public function actualizar()
    {
        $this->accion = "Muestras almacenadas";
        $lNRecepcionMustra = new RecepcionMuestrasLogicaNegocio();
        $lNRecepcionMustra->guardarDatosRM($_POST);
        $arrayParametros = array(
            'id_orden_trabajo' => $_POST['id_orden_trabajo'],
            'id_laboratorios_provincia' => $_POST['id_laboratorios_provincia'],
            'estado_actual' => array(Constantes::estado_MU()->ALMACENADA, Constantes::estado_MU()->DESECHADA));
        $muestras = $this->lNegocioRecepcionMuestras->buscarMuestras($arrayParametros);
        $this->tablaHtmlMuestrasAlmacenadas($muestras);
        echo json_encode(array('datos' => $this->itemsMuestrasAlmacenadas, 'mensaje' => Constantes::GUARDADO_CON_EXITO));
    }

    /**
     * Construye el código HTML para desplegar la lista de muestras
     */
    public function tablaHtmlMuestrasAlmacenadas($tabla)
    {
        $contador = 0;
        $html = "";
        foreach ($tabla as $fila)
        {
            $class = "";
            if ($fila->estado_actual == 'ALMACENADA')
            {
                $class = $this->alertaFechas($fila->fecha_fin_almacenamiento);
            }

            $botonDesechar = "<select id='estado_actual_$fila->id_recepcion_muestras' onchange='fn_actualizarDatos($fila->id_recepcion_muestras)'>";
            if ($fila->estado_actual == 'ALMACENADA')
            {
                $botonDesechar.= "<option value='ALMACENADA' selected>NO</option>"
                        . "<option value='DESECHADA'>SI</option>";
            } else
            {
                $botonDesechar.= "<option value='ALMACENADA'>NO</option>"
                        . "<option value='DESECHADA' selected>SI</option>";
            }
            $html.= '<tr class=' . $class . '>
		  <td>' . ++$contador . '</td>';
            $html.= "<td>$fila->codigo_lab_muestra</td>";
            $html.= '<td>' . $fila->codigo_usu_muestra . '</td>
                  <td>' . $fila->rama_nombre . '</td>
                  <td>' . $fila->fecha_fin_analisis . '</td>
                  <td>' . $fila->estado_actual . '</td>
                  <td><input id="fecha_fin_almacenamiento_' . $fila->id_recepcion_muestras . '" type="date" value="' . $fila->fecha_fin_almacenamiento . '" size="6" onchange="fn_actualizarDatos(' . $fila->id_recepcion_muestras . ')"/></td>'
                    . "<td>$botonDesechar</td>" .
                    '</tr>';
        }
        $this->itemsMuestrasAlmacenadas = $html;
    }

    /**
     * Despliega al vista para la lista de muestras almacenadas
     */
    public function almacenarMuestra()
    {
        require APP . 'Laboratorios/vistas/formularioMuestrasAlmacenadasVista.php';
    }

    /**
     * Despliega al vista para la lista de informes de la orden de trabajo
     */
    public function informes()
    {
        $this->accion = "Informes";
        require APP . 'Laboratorios/vistas/formularioInformesVista.php';
    }

    /*     * ***************** VERIFICAR MUESTRAS ********* */

    /**
     * VERIFICAR MUESTRAS
     * Despliega las ordenes de trabajo ACTIVAS para realizar la verificación
     */
    public function verificarMuestras()
    {
        parent::laboratorioUsuario();
        require APP . 'Laboratorios/vistas/listaBandejaRTVerificarVista.php';
    }

    /**
     * VERIFICAR MUESTRAS
     * Muestra la lista de órdenes de trabajo
     * Por defecto solo ACTIVAS, si busca por código debe mostras de todas maneras
     */
    public function listarDatosVerificar()
    {
        //Si busca por codigo entonces se muestra la orden de cualquier estado y de cualquier laboratorio
        $arrayParametros = array();
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        if (!empty($_POST['codigo_ot']))
        {
            $arrayParametros['codigo_ot'] = $_POST['codigo_ot'];
        } else  //mostrar solo los ACTIVOS y del laboratorio que corresponde o que seleccionó
        {
            $arrayParametros['estado_orden'] = Constantes::estado_OT()->ACTIVA;
        }
        $OrdenesTrabajo = $this->bandejaRT->buscarOrdenesT($arrayParametros);
        $this->tablaHtmlOTVerificar($OrdenesTrabajo, $_POST['id_laboratorios_provincia']);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Construye el código HTML para desplegar las órdenes de trabajo
     */
    public function tablaHtmlOTVerificar($tabla, $idLaboratoriosProvincia)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $botonPdf = '';

                $botonPdf = "<button class=\"bntGrid fas fa-file-pdf\" onclick=\"fn_verPdf(" . $fila->id_orden_trabajo . ")\"/>";

                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_solicitud . "-" . $fila->id_laboratorio . "-" . $fila->id_orden_trabajo . "-" . $idLaboratoriosProvincia . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/BandejaResponsableTecnico"
		  data-opcion="verMuestrasVerificar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->codigo_ot . '</b></td>
                    <td>' . $fila->cliente . '</td>
                    <td style="text-align:center">' . $this->botonDatosSolicitud($fila->id_solicitud) . '</td>
                    <td style="text-align:center">' . $botonPdf . '</td>
                    <td style="text-align:center">' . $fila->fecha_activacion . '</td>
                    <td style="text-align:center">' . $fila->estado_orden . '</td>
                </tr>'
                );
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * VERIFICAR MUESTRAS
     * Busca las muestras de un análisis
     */
    public function verMuestrasVerificar()
    {
        $ids = explode('-', $_POST['id']);
        $idSolicitud = $ids[0]; //separo y obtengo id_solicitud
        $idLaboratorio = $ids[1]; //separo y obtengo id_laboratorio
        $idOrdenTrabajo = $ids[2]; //separo y obtengo id_orden_trabajo
        $idLaboratoriosProvincia = $ids[3]; //separo y obtengo id_laboratorios_provincia
        //buscar los atributos del laboratorio;
        $lNLaboratorios = new \Agrodb\Laboratorios\Modelos\LaboratoriosLogicaNegocio();
        $this->modeloLaboratorios = new \Agrodb\Laboratorios\Modelos\LaboratoriosModelo();
        $this->modeloLaboratorios = $lNLaboratorios->buscar($idLaboratorio);

        $lNSolicitudes = new SolicitudesLogicaNegocio();
        $this->modeloSolicitudes = $lNSolicitudes->buscar($idSolicitud);

        $this->idLaboratorio = $idLaboratorio;
        $this->idOrdenTrabajo = $idOrdenTrabajo;

        $this->idoneaEnProceso = $this->obtenerPermisoLaboratorio($idLaboratoriosProvincia, 'idoneaEnProceso');

        $this->modeloOrdenTrabajo = new OrdenesTrabajosModelo();
        $this->modeloOrdenTrabajo = $this->lNegocioOrdenTrabajo->buscar($idOrdenTrabajo);

        //buscar si la orden de trabajo está en marbetes
        $lNmarbetes = new MarbetesLogicaNegocio();
        $buscaMarbetes = $lNmarbetes->buscarLista(array('id_orden_trabajo' => $this->modeloOrdenTrabajo->getIdOrdenTrabajo()));
        if (count($buscaMarbetes) > 0)
        {
            $this->tablaHtmlVerificarMarbetes($buscaMarbetes);
            require APP . 'Laboratorios/vistas/formularioBandejaRTMarbetesVista.php';
        } else
        {
            $arrayParametros = array(
                'id_orden_trabajo' => $idOrdenTrabajo,
                'es_aceptada' => 'SI',
                'estado_actual' => array('RECIBIDA', 'IDONEA', 'PENDIENTE'));
            $recepcionMuestras = $this->lNegocioRecepcionMuestras->buscarMuestras($arrayParametros);
            $this->tablaHtmlVerificarMuestras($recepcionMuestras, $this->modeloOrdenTrabajo->getEstado());
            require APP . 'Laboratorios/vistas/formularioBandejaRTVista.php';
        }
    }

    /**
     * VERIFICAR MUESTRAS
     * Construye el código HTML para desplegar la lista de marbetes
     */
    public function tablaHtmlVerificarMarbetes($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            $numMuestra = 0;
            $cont = 0;
            $dato = "";
            foreach ($tabla as $fila)
            {
                $op = '<input name="idRecepcionMuestras[' . $fila->id_recepcion_muestras . ']" type="hidden" value="' . $fila->id_recepcion_muestras . '">';
                $this->itemsMuestras[] = array(
                    '<tr">
                  <td>' . ++$contador . $op . '</td>
		  <td>' . $fila->numero_lote . '</td>
		  <td>' . $fila->cantidad . '</b></td>
                  <td><input type="date" name="fecha_impresion[' . $fila->id_marbete . ']" value="' . $fila->fecha_impresion . '" placeholder="" required /></td>
                  <td><input type="text" name="inicio_serie[' . $fila->id_marbete . ']" value="' . $fila->inicio_serie . '" placeholder="" required maxlength="32"/></td>
                  <td><input type="text" name="fin_serie[' . $fila->id_marbete . ']" value="' . $fila->fin_serie . '" placeholder="" required maxlength="32"/></td>
                  <td>' . $fila->estado . '</td>
                </tr>');
            }
            $this->itemsMuestras[] = array($dato);
        } else
        {
            $this->itemsMuestras[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * VERIFICAR MUESTRAS
     * Construye el código HTML para desplegar la lista de muestras
     */
    public function tablaHtmlVerificarMuestras($tabla, $estadoOT)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            $numMuestra = 0;
            $cont = 0;
            $dato = "";
            foreach ($tabla as $fila)
            {
                $numMuestra = $fila['numero_muestra'];
                $html = "<tr>$dato</tr>"; //la primera vez va vacio y como va como array entonces no toma en cuenta

                $es = "";
                $op = "";
                $mi = "";
                if ($estadoOT == 'ACTIVA')
                {
                    //escritura
                    $op = '<input name="idRecepcionMuestras[' . $fila->id_recepcion_muestras . ']" type="hidden" value="' . $fila->id_recepcion_muestras . '">';
                    $mi = '<select name="esIdonea[' . $fila->id_recepcion_muestras . ']" required onchange="fn_verificarSI()" class="esIdonea cls_selectAllCmbByClass" ' . $es . '>
                                    <option value="">Seleccionar....</option>' . $this->crearComboSINO($fila->es_idonea) . '</select>';
                } else if ($estadoOT === 'EN PROCESO' & $this->idoneaEnProceso)
                {
                    //escritura
                    $op = '<input name="idRecepcionMuestras[' . $fila->id_recepcion_muestras . ']" type="hidden" value="' . $fila->id_recepcion_muestras . '">';
                    $mi = '<select name="esIdonea[' . $fila->id_recepcion_muestras . ']" required onchange="fn_verificarSI()" class="esIdonea cls_selectAllCmbByClass" ' . $es . '>
                                    <option value="">Seleccionar....</option>' . $this->crearComboSINO($fila->es_idonea) . '</select>';
                } else
                {
                    //lectura
                    $es = "style='background:transparent;border:0' readonly";
                    $mi = $fila->es_idonea;
                }

                $this->itemsMuestras[] = array($html);
                $dato = '<td>' . ++$contador . $op . '</td>';
                $dato .= '<td style="text-align:center">' . $this->botonDatosMuestra($fila->id_recepcion_muestras) . '</td>';
                $dato .= '<td><b>' . $fila->codigo_usu_muestra . '</b></td>';
                $dato .= '<td>' . $fila->rama_nombre . '</td>';
                $dato .= '<td>' . $mi . ' </td>';
                $dato .= '<td>' . $fila->fecha_verificada . '</td>';
                $dato .= '<td><textarea rows="2" cols="30" name="observacionVerificacion[' . $fila->id_recepcion_muestras . ']"
                      placeholder="Observaci&oacute;n verificaci&oacute;n">' . $fila->observacion_verificacion . '</textarea>';
                $dato .= '<td>' . $fila->estado_actual . '</td>';
                $dato .= '</tr>';
            }
            $this->itemsMuestras[] = array($dato);
        } else
        {
            $this->itemsMuestras[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * VERIFICAR MUESTRAS
     * Método para registrar la verificación de idoneidad de las muestras
     */
    public function guardarMuestras()
    {
        $lNRecepcionMustra = new RecepcionMuestrasLogicaNegocio();
        $_POST['identificador'] = parent::usuarioActivo();
        $_POST['urlArchivo'] = URL_DIR_NO_IDONEAS . $_POST['archivo'];
        $lNRecepcionMustra->guardarRT($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * VERIFICAR MUESTRAS
     * Método para registrar la verificación de idoneidad de las muestras
     */
    public function guardarVerificacionMarbetes()
    {
        $lNRecepcionMustra = new RecepcionMuestrasLogicaNegocio();
        $_POST['identificador'] = parent::usuarioActivo();
        $lNRecepcionMustra->guardarVerificacionMarbetes($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /*     * ***************** ANALIZAR MUESTRAS ********* */

    /**
     * ANALIZAR MUESTRAS
     * Despliega las ordenes de trabajo EN PROCESO para realizar el análisis (veritical/horizontal)
     */
    public function analizarMuestras()
    {
        require APP . 'Laboratorios/vistas/listaBandejaRTAnalizarVista.php';
    }

    /**
     * ANALIZAR MUESTRAS
     * Muestra la lista de órdenes de trabajo
     * Por defecto solo EN PROCESO, si busca por código debe mostras de todas maneras
     */
    public function listarDatosAnalizar()
    {
        //Si busca por codigo entonces se muestra la orden de cualquier estado y de cualquier laboratorio
        $arrayParametros = array();
        if (!empty($_POST['codigo_ot']))
        {
            $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
            $arrayParametros['codigo_ot'] = $_POST['codigo_ot'];
            $OrdenesTrabajo = $this->bandejaRT->buscarOrdenesT($arrayParametros);
        } else  //mostrar solo los EN PROCESO y del laboratorio que corresponde o que seleccionó
        {
            $OrdenesTrabajo = $this->bandejaRT->buscarOrdenesAnalizar($_POST['id_laboratorios_provincia']);
        }
        $this->tablaHtmOTMuestrasIdoneas($OrdenesTrabajo);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * ANALIZAR MUESTRAS
     * Construye el código HTML para desplegar las órdenes de trabajo
     */
    public function tablaHtmOTMuestrasIdoneas($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $botonPdf = "<button class=\"bntGrid fas fa-file-pdf\" onclick=\"fn_verPdf(" . $fila->id_orden_trabajo . ")\"/>";
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_orden_trabajo . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/ResultadoAnalisis"
		  data-opcion="verMuestrasIdoneas" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->codigo_ot . '</b></td>
                    <td>' . $fila->cliente . '</td>
                    <td style="text-align:center">' . $this->botonDatosSolicitud($fila->id_solicitud) . '</td>
                    <td style="text-align:center">' . $botonPdf . '</td>
                    <td style="text-align:center">' . $fila->fecha_activacion . '</td>
                    <td style="text-align:center">' . $fila->estado_orden . '</td>
                </tr>'
                );
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /*     * ***************** VALIDAR MUESTRAS ********* */

    /**
     * VALIDAR MUESTRAS
     * Muestras las órdenes de trabajo que están en estado EN PROCESO
     */
    public function validarInformacion()
    {
        require APP . 'Laboratorios/vistas/listaBandejaRTValidarVista.php';
    }

    /**
     * VALIDAR MUESTRAS
     * Muestra la lista de órdenes de trabajo
     * Por defecto solo EN PROCESO, si busca por código debe mostras de todas maneras
     */
    public function listarDatosValidar()
    {
        //Si busca por codigo entonces se muestra la orden de cualquier estado y de cualquier laboratorio
        $arrayParametros = array();
        if (!empty($_POST['codigo_ot']))
        {
            $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
            $arrayParametros['codigo_ot'] = $_POST['codigo_ot'];
            $OrdenesTrabajo = $this->bandejaRT->buscarOrdenesT($arrayParametros);
        } else  //mostrar solo los EN PROCESO y del laboratorio que corresponde o que seleccionó
        {
            $OrdenesTrabajo = $this->bandejaRT->buscarOrdenesValidar($_POST['id_laboratorios_provincia']);
        }

        $this->tablaHtmOTValidar($OrdenesTrabajo);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * VALIDAR MUESTRAS
     * Ordenes de trabajo para validar la información
     * @param type $tabla
     */
    public function tablaHtmOTValidar($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $boton = '<button type="button" id="btnResultado" onclick="fn_verPdf(' . $fila->id_orden_trabajo . ')" class="fas fa-file-pdf"> </button>';

                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_orden_trabajo . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/BandejaResponsableTecnico"
		  data-opcion="verMuestrasAnalizadas" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->codigo_ot . '</b></td>
                    <td>' . $fila->cliente . '</td>
                    <td style="text-align:center">' . $this->botonDatosSolicitud($fila->id_solicitud) . '</td>
                    <td style="text-align:center">' . $boton . '</td>
                    <td style="text-align:center">' . $fila->fecha_activacion . '</td>
                    <td style="text-align:center">' . $fila->estado_orden . '</td>
                </tr>'
                );
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * VALIDAR MUESTRAS
     * Para mostrar el formulario de validación
     */
    public function verMuestrasAnalizadas()
    {
        $this->idOrdenTrabajo = $_POST['id'];
        //id del laboratorio
        $lNegocioOrdenTrabajo = new OrdenesTrabajosLogicaNegocio();
        $buscaOrden = $lNegocioOrdenTrabajo->buscarLista(array('id_orden_trabajo' => $this->idOrdenTrabajo));
        $orden = $buscaOrden->current();
        $this->idLaboratorio = $orden->id_laboratorio;
        require APP . 'Laboratorios/vistas/formularioValidacionAnalisisVista.php';
    }

    /**
     * Muestra la lista de muestras en estado ANALIZADA
     * @param type $param
     */
    public function listarDatosValidacion($idOrdenTrabajo)
    {
        $codigo = $_POST['codigo'];
        $analisis = $_POST['analisis'];

        $arrayParametros = array('codigo' => $codigo, 'analisis' => $analisis, 'estado_actual' => array('ANALIZADA'));
        $resultado = $this->lNegocioResultadoAnalisis->buscarMuestrasIdoneas($idOrdenTrabajo, $arrayParametros);
        $this->tablaHtmlMuestrasIdoneas($resultado);
        echo json_encode($this->itemsMuestras);
    }

    /**
     * VALIDAR MUESTRAS
     * Construye el código HTML para desplegar la lista de muestras
     */
    public function tablaHtmlMuestrasIdoneas($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $this->idOrdenTrabajo = $fila->id_orden_trabajo;
                $boton = '<td><button type="button" id="btnResultado" onclick="fn_camposResultado(' . $fila->id_recepcion_muestras . ',' . $fila->id_servicio . ',' . "'" . $fila->rama . "'" . ')" class="far fa-window-restore"> </button></td>';

                $aprobado = ($fila->estado_aprobacion == 'APROBADO') ? 'selected' : "";
                $noAprobado = ($fila->estado_aprobacion == 'NO APROBADO') ? 'selected' : "";
                $campoEstadoAprobacion = "<select name='estado_aprobacion[$fila->id_recepcion_muestras]' class='cls_selectAllCmbByClass'>"
                        . "<option value=''>Seleccione...</option>"
                        . "<option value='APROBADO' $aprobado>SI</option>"
                        . "<option value='NO APROBADO' $noAprobado>NO</option>"
                        . "</select>";
                $campoObservacionAprobacion = '<textarea rows="2" cols="30" name="observacion_aprobacion[' . $fila->id_recepcion_muestras . ']"
                      placeholder="Observaci&oacute;n validaci&oacute;n">' . $fila->observacion_aprobacion . '</textarea>';
                $nuevoAnalisis = ($fila->nuevo_analisis == 'SI') ? 'checked' : "";
                $campoNuevoAnalisis = "<input type='checkbox' name='nuevo_analisis[$fila->id_recepcion_muestras]' $nuevoAnalisis/>";
                $campoContadorNoAprobado = "<input type='hidden' name='contador_no_aprobado[$fila->id_recepcion_muestras]' value='$fila->contador_no_aprobado'/>";
                $this->itemsMuestras[] = array(
                    '<tr">
		  <td>' . $fila->numero_muestra . $campoContadorNoAprobado .'</td>
                  <td>' . $this->botonDatosMuestra($fila->id_recepcion_muestras) . '</td>
		  <td>' . $fila->codigo_lab_muestra . '</b></td>
                  <td>' . $fila->codigo_usu_muestra . '</td>
                  <td>' . $fila->rama_nombre . '</td>
                  <td>' . $campoEstadoAprobacion . '</td>
                  <td>' . $campoObservacionAprobacion . '</td>
                  <td>' . $campoNuevoAnalisis . '</td>'
                    . $boton .
                    '<td>' . $fila->estado_actual . '</td>
                </tr>');
            }
        } else
        {
            $this->itemsMuestras[] = array("<tr><td colspan='6'>No existen datos para mostrar. Es posible que no exista muestras pendientes por aprobar.</td></tr>");
        }
    }

    /**
     * VALIDAR MUESTRAS
     * Método para registrar la validación de la muestras
     */
    public function guardarValidacion()
    {
        $lNRecepcionMustra = new RecepcionMuestrasLogicaNegocio();
        $lNRecepcionMustra->guardarValidacion($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Permite unir y consolidar informes
     */
    public function consolidarInformes()
    {
        require APP . 'Laboratorios/vistas/listaConsolidarInformesVista.php';
    }

    /**
     * Permite firmar electronicamente el informe
     */
    public function legalizarInformes()
    {
        require APP . 'Laboratorios/vistas/listaLegalizarInformesVista.php';
    }

    /**
     * Permite enviar los informes al cliente
     */
    public function enviarInformes()
    {
        require APP . 'Laboratorios/vistas/listaEnviarInformesVista.php';
    }

    /**
     * Muestra las lista de clientes según el laboratorio
     */
    public function buscarClientes()
    {
        $idLaboratoriosProvincia = $_POST['id_laboratorios_provincia'];
        $informe = new ArchivoInformeAnalisisLogicaNegocio();
        $buscaDatos = $informe->buscarClientesInforme($idLaboratoriosProvincia,Constantes::estado_OT()->EN_APROBACION);
        $array = array();
        foreach ($buscaDatos as $fila)
        {
            $array[] = array("id" => $fila->id_archivo_informe_analisis, "text" => $fila->nombre_informe);
        }
        echo \Zend\Json\Json::encode($array);
        
    }
    
     /**
     * Muestra las lista de clientes según el laboratorio
     */
    public function buscarClientesModificar($anio,$mes)
    {
        $idLaboratoriosProvincia = $_POST['id_laboratorios_provincia'];
        $informe = new ArchivoInformeAnalisisLogicaNegocio();
        $buscaDatos = $informe->buscarClientesInformeModificar($idLaboratoriosProvincia,$anio,$mes);
        $array = array();
        foreach ($buscaDatos as $fila)
        {
            $array[] = array("id" => $fila->id_archivo_informe_analisis, "text" => $fila->nombre_informe);
        }
        echo \Zend\Json\Json::encode($array);
        
    }

    /**
     * Muestra las lista de clientes según el laboratorio
     */
    public function buscarClientesFirma()
    {
        $idLaboratoriosProvincia = $_POST['id_laboratorios_provincia'];
        $informe = new ArchivoInformeAnalisisLogicaNegocio();
        $buscaDatos = $informe->buscarClientesInforme($idLaboratoriosProvincia, Constantes::estado_OT()->EN_APROBACION);
        $array = array();
        foreach ($buscaDatos as $fila)
        {
            $array[] = array("id" => $fila->id_archivo_informe_analisis, "text" => $fila->nombre_informe);
        }
        echo json_encode($array);
    }

    /**
     * Permite modificar  los informes
     */
    public function modificarInforme()
    {
        require APP . 'Laboratorios/vistas/listaModificarInformesVista.php';
    }

    /*     * ***************** DERIVACION DE MUESTRAS ********* */

    /**
     * DERIVACION DE MUESTRAS
     * Despliega las ordenes de trabajo con estado EN PROCESO
     */
    public function derivar()
    {
        require APP . 'Laboratorios/vistas/listaBandejaRTDerivarVista.php';
    }

    /**
     * DERIVACION DE MUESTRAS
     */
    public function listarDatosDerivar()
    {
        //Si busca por codigo entonces se muestra la orden de cualquier estado y de cualquier laboratorio
        $arrayParametros = array();
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];

        if ($this->obtenerPermisoLaboratorio($_POST['id_laboratorios_provincia'], Constantes::permisos_laboratorio()->DERIVACION))
        {
            if (!empty($_POST['codigo_ot']))
            {
                $arrayParametros['codigo_ot'] = $_POST['codigo_ot'];
            } else  //mostrar solo los EN PROCESO y del laboratorio que corresponde o que selecciono
            {
                $arrayParametros['estado_orden'] = array(Constantes::estado_OT()->EN_PROCESO, Constantes::estado_OT()->EN_ANALISIS, Constantes::estado_OT()->EN_APROBACION);
            }
            $OrdenesTrabajo = $this->bandejaRT->buscarOrdenesT($arrayParametros);
            $this->tablaHtmlOrdenesTDerivar($OrdenesTrabajo);
            echo json_encode($this->itemsFiltrados);
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>" . Constantes::INF_PERMISO_LABORATORIO . "</td></tr>");
            echo json_encode($this->itemsFiltrados);
        }
    }

    /**
     * DERIVACION DE MUESTRAS
     * Construye el código HTML para desplegar las órdenes de trabajo
     */
    public function tablaHtmlOrdenesTDerivar($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $botonPdf = '';
                if ($fila->estado_orden == 'EN PROCESO')
                {

                    $botonPdf = "<button class=\"bntGrid fas fa-file-pdf\" onclick=\"fn_verPdf(" . $fila->id_orden_trabajo . ")\"/>";
                }
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_solicitud . "-" . $fila->id_laboratorio . "-" . $fila->id_orden_trabajo . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/BandejaResponsableTecnico"
		  data-opcion="verMuestrasParaDerivar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->codigo_ot . '</b></td>
                    <td>' . $fila->cliente . '</td>
                    <td>' . $botonPdf . '</td>
                    <td></td>
                    <td>' . $fila->fecha_activacion . '</td>
                    <td>' . $fila->estado_orden . '</td>
                </tr>'
                );
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existe Ordenes de Trabajo EN PROCESO</td></tr>");
        }
    }

    /**
     * DERIVACION DE MUESTRAS
     * Para mostrar el formulario
     * Puede derivar si el laboratorio tiene permiso g_laboratorios.laboratorios.atributos
     */
    public function verMuestrasParaDerivar()
    {
        $ids = explode('-', $_POST['id']);
        $idSolicitud = $ids[0]; //separo y obtengo id_solicitud
        $idLaboratorio = $ids[1]; //separo y obtengo id_laboratorio
        $idOrdenTrabajo = $ids[2]; //separo y obtengo id_orden_trabajo si existe

        $lNSolicitudes = new SolicitudesLogicaNegocio();
        $this->modeloSolicitudes = $lNSolicitudes->buscar($idSolicitud);

        $arrayParametros = array(
            'id_orden_trabajo' => $idOrdenTrabajo,
            'es_idonea' => 'SI',
            'estado_actual' => array('IDONEA'));
        $recepcionMuestras = $this->lNegocioRecepcionMuestras->buscarMuestras($arrayParametros);
        $this->tablaHtmlMuestrasDerivar($recepcionMuestras);

        $this->idLaboratorio = $idLaboratorio;
        $this->idOrdenTrabajo = $idOrdenTrabajo;
        require APP . 'Laboratorios/vistas/formularioBandejaRTDerivarVista.php';
    }

    /**
     * DERIVACION DE MUESTRAS
     * Construye el código HTML para desplegar la lista de muestras
     */
    public function tablaHtmlMuestrasDerivar($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            $dato = "";
            foreach ($tabla as $fila)
            {
                if ($fila->derivada == 'SI')
                {
                    $op = "muestra en derivaci&oacute;n";
                } else
                {
                    $op = '<input type="checkbox" name="muestras[]" value="' . $fila->id_recepcion_muestras . '"/>';
                }

                $dato = '<td>' . ++$contador . '</td>';
                $dato .= '<td>' . $op . '</td>';
                $dato .= '<td><b>' . $fila->codigo_lab_muestra . '</b></td>';
                $dato .= '<td><b>' . $fila->codigo_usu_muestra . '</b></td>';
                $dato .= '<td>' . $fila->rama_nombre . '</td>';
                $dato .= '<td>' . $fila->es_idonea . ' </td>';
                $dato .= '<td>' . $fila->fecha_verificada . '</td>';
                $dato .= '<td>' . $this->botonDatosMuestra($fila->id_recepcion_muestras) . '</td>';
                $dato .= '<td>' . $fila->estado_actual . '</td>';
                $dato .= '</tr>';
                $this->itemsMuestras[] = array($dato);
            }
        } else
        {
            $this->itemsMuestras[] = array("<tr><td colspan='5'>No existe muestras idoneas para derivar</td></tr>");
        }
    }

    /**
     * DERIVACION DE MUESTRAS
     */
    public function guardarDerivacion()
    {
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /*     * ***************** CONFIRMACION DE ANALISIS ********* */

    /**
     * CONFIRMACION DE ANALISIS
     * Despliega las ordenes de trabajo ACTIVAS para realizar la verificación
     */
    public function confirmacion()
    {
        require APP . 'Laboratorios/vistas/listaBandejaRTConfirmacionVista.php';
    }

    /**
     * CONFIRMACION DE ANALISIS
     */
    public function listarDatosConfirmar()
    {
        //Si busca por codigo entonces se muestra la orden de cualquier estado y de cualquier laboratorio
        $arrayParametros = array();
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        if ($this->obtenerPermisoLaboratorio($_POST['id_laboratorios_provincia'], Constantes::permisos_laboratorio()->CONFIRMACION))
        {
            if (!empty($_POST['codigo_ot']))
            {
                $arrayParametros['codigo_ot'] = $_POST['codigo_ot'];
            } else  //mostrar solo los EN PROCESO y del laboratorio que corresponde o que selecciono
            {
                $arrayParametros['estado_orden'] = Constantes::estado_OT()->EN_APROBACION;
            }
            $OrdenesTrabajo = $this->bandejaRT->buscarOrdenesT($arrayParametros);
            $this->tablaHtmlOrdenesTConfirmar($OrdenesTrabajo);
            echo json_encode($this->itemsFiltrados);
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>" . Constantes::INF_PERMISO_LABORATORIO . "</td></tr>");
            echo json_encode($this->itemsFiltrados);
        }
    }

    /**
     * CONFIRMACION DE ANALISIS
     * Construye el código HTML para desplegar las órdenes de trabajo
     */
    public function tablaHtmlOrdenesTConfirmar($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $botonPdf = '';
                if ($fila->estado_orden == 'EN PROCESO')
                {

                    $botonPdf = "<button class=\"bntGrid fas fa-file-pdf\" onclick=\"fn_verPdf(" . $fila->id_orden_trabajo . ")\"/>";
                }
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_solicitud . "-" . $fila->id_laboratorio . "-" . $fila->id_orden_trabajo . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/BandejaResponsableTecnico"
		  data-opcion="verMuestrasParaConfirmar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->codigo_ot . '</b></td>
                    <td>' . $fila->cliente . '</td>
                    <td>' . $botonPdf . '</td>
                    <td></td>
                    <td>' . $fila->fecha_activacion . '</td>
                    <td>' . $fila->estado_orden . '</td>
                </tr>'
                );
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existe Ordenes de Trabajo EN APROBACION</td></tr>");
        }
    }

    /**
     * CONFIRMACION DE ANALISIS
     * Para mostrar el formulario
     */
    public function verMuestrasParaConfirmar()
    {
        $ids = explode('-', $_POST['id']);
        $idSolicitud = $ids[0]; //separo y obtengo id_solicitud
        $idLaboratorio = $ids[1]; //separo y obtengo id_laboratorio
        $idOrdenTrabajo = $ids[2]; //separo y obtengo id_orden_trabajo si existe

        $lNSolicitudes = new SolicitudesLogicaNegocio();
        $this->modeloSolicitudes = $lNSolicitudes->buscar($idSolicitud);

        $arrayParametros = array(
            'id_orden_trabajo' => $idOrdenTrabajo,
            'es_idonea' => 'SI',
            'nuevo_analisis' => 'SI',
            'estado_aprobacion' => 'APROBADO');
        $recepcionMuestras = $this->lNegocioRecepcionMuestras->buscarMuestras($arrayParametros);
        $this->tablaHtmlMuestrasConfirmar($recepcionMuestras);

        $this->idLaboratorio = $idLaboratorio;
        $this->idOrdenTrabajo = $idOrdenTrabajo;
        require APP . 'Laboratorios/vistas/formularioBandejaRTConfirmacionVista.php';
    }

    /**
     * CONFIRMACION DE ANALISIS
     * Construye el código HTML para desplegar la lista de muestras
     */
    public function tablaHtmlMuestrasConfirmar($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            $numMuestra = 0;
            $cont = 0;
            $dato = "";
            foreach ($tabla as $fila)
            {
                if ($fila->por_confirmar == 'SI')
                {
                    $op = "muestra enviada a confirmar";
                } else
                {
                    $op = '<input type="checkbox" name="muestras[]" value="' . $fila->id_recepcion_muestras . '"/>';
                }
                $dato = '<td>' . ++$contador . '</td>';
                $dato .= '<td>' . $op . '</td>';
                $dato .= '<td><b>' . $fila->codigo_lab_muestra . '</b></td>';
                $dato .= '<td><b>' . $fila->codigo_usu_muestra . '</b></td>';
                $dato .= '<td>' . $fila->rama_nombre . '</td>';
                $dato .= '<td>' . $fila->es_idonea . ' </td>';
                $dato .= '<td>' . $fila->fecha_verificada . '</td>';
                $dato .= '<td>' . $this->botonDatosMuestra($fila->id_recepcion_muestras) . '</td>';
                $dato .= '<td>' . $fila->estado_actual . '</td>';
                $dato .= '<td>' . $fila->estado_aprobacion . '</td>';
                $dato .= '</tr>';
                $this->itemsMuestras[] = array($dato);
            }
        } else
        {
            $this->itemsMuestras[] = array("<tr><td colspan='5'>No existe muestras APROBADAS y que requieran confirmaci&oacute;n</td></tr>");
        }
    }

    /*     * *********** *///
    /**
     * Formulario para configurar e imprimir las etiquetas
     */

    public function etiquetas()
    {
        $this->idOrdenTrabajo = $_POST['elementos'];
        $this->accion = "Seleccinar el tipo de campos disponibles para generar las etiquetas";
        require APP . 'Laboratorios/vistas/formularioEtiquetasVista.php';
    }

    public function basedatos()
    {
        parent::laboratorioUsuario();
        require APP . 'Laboratorios/vistas/listaBandejaRTbasedatosVista.php';
    }

}

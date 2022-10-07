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

use Agrodb\Core\Constantes;
use Agrodb\Laboratorios\Modelos\BandejaRecepcionLogicaNegocio;
use Agrodb\Laboratorios\Modelos\OrdenesTrabajosModelo;
use Agrodb\Laboratorios\Modelos\OrdenesTrabajosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\TipoAnalisisLogicaNegocio;
use Agrodb\Laboratorios\Modelos\PagosModelo;
use Agrodb\Laboratorios\Modelos\PagosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\SolicitudesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\SolicitudesModelo;
use Agrodb\Laboratorios\Modelos\RecepcionMuestrasLogicaNegocio;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\Core\Mensajes;

class BandejaRecepcionControlador extends BaseControlador
{

    private $lNegocioRecepcionMuestras = null;
    public $itemsOrdenesTrabajo = array();  //items de ordenes de trabajo
    public $itemsMuestras = array();        //items de muestras
    private $bandejaRecepcion = null;       //logica de negocio bandeja recepcion
    private $lNegocioOrdenTrabajo = null;
    private $lNegocioTipoAnalisis = null;
    private $modeloPagos = null;
    private $modeloSolicitudes = null;
    private $modeloOrdenTrabajo = null;
    private $estados;       //para el filtro de estado
    public $totalPago;      //para total de pago
    public $idSolicitud;
    public $idLaboratorio;
    public $idOrdenTrabajo;
    public $idDetalleSolicitud;
    private $datosMemo;     //tabla html de los datos del memo
    private $saldo;         //saldo de numero de analisis del memo
    private $mostrarDetalleMemo = false;
    private $rutArcExo = null;  //ruta del archivo de exoneracion
    public $existePago;         //Si/NO si existe o no el pago de la solicitud
    public $datosSolicitud = array();   //para datos de la solicitud
    public $datosUsuario;       //para datos del usuario

    /*
     * Constructor
     */

    function __construct()
    {
        parent::__construct();
        $this->rutArcExo = URL_DIR_FILES . '/exoneracion'; //ruta del archivo de exoneracion
        //estados para la bandeja de recepción
        $this->estados = array(
            Constantes::estado_SO()->ENVIADA,
            Constantes::estado_SO()->RECIBIDA,
            Constantes::estado_SO()->EN_PROCESO,
            Constantes::estado_SO()->FINALIZADA,
        );
        $this->bandejaRecepcion = new BandejaRecepcionLogicaNegocio();
        $this->lNegocioOrdenTrabajo = new OrdenesTrabajosLogicaNegocio();
        $this->lNegocioTipoAnalisis = new TipoAnalisisLogicaNegocio();
        $this->lNegocioRecepcionMuestras = new RecepcionMuestrasLogicaNegocio();

        $this->modeloPagos = new PagosModelo();

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
        parent::laboratorioUsuario();
        require APP . 'Laboratorios/vistas/listaBandejaRecepcionVista.php';
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        if (!empty($_POST['estado']))
        {
            $estado = $_POST['estado'];
        } else
        {
            $estado = $this->estados;
        }
        $arrayParametros = array(
            'estado' => $estado,
            'codigo' => $_POST['codigo'],
            'cliente' => $_POST['cliente'],
            'identificador' => $this->identificador,
            'perfil' => Constantes::perfil()->recaudador);
        $modeloSolicitudes = $this->bandejaRecepcion->buscarSolicitudes($arrayParametros);
        $this->tablaHtmlSolicitudes($modeloSolicitudes);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Busca las ordenes de trabajo de la solicitud selecionada
     */
    public function verOrdenesTrabajo()
    {
        $idSolicitud = $_POST['id'];
        $this->modeloSolicitudes = new SolicitudesModelo();
        $modeloOrdenesT = $this->lNegocioOrdenTrabajo->buscarOrdenesTrabajos($idSolicitud);
        $this->tablaHtmlOrdenesT($modeloOrdenesT);
        require APP . 'Laboratorios/vistas/formularioBandejaRecepcionVista.php';
    }

    /**
     * Busca las ordenes de trabajo de la solicitud selecionada
     */
    public function verOrdenesTrabajoJson()
    {
        $idSolicitud = $_POST['id'];
        $this->modeloSolicitudes = new SolicitudesModelo();
        $modeloOrdenesT = $this->lNegocioOrdenTrabajo->buscarOrdenesTrabajos($idSolicitud);
        $this->tablaHtmlOrdenesT($modeloOrdenesT);
        echo \Zend\Json\Json::encode($this->itemsOrdenesTrabajo);
    }

    /**
     * Busca las muestras de un análisis
     */
    public function verMuestras()
    {
        $ids = explode('-', $_POST['id']);
        $idSolicitud = $ids[0]; //separo y obtengo id_solicitud
        $idLaboratorio = $ids[1]; //separo y obtengo id_laboratorio
        $idOrdenTrabajo = $ids[2]; //separo y obtengo id_orden_trabajo si existe
        $modeloOrdenesT = $this->lNegocioOrdenTrabajo->buscarOrdenesTrabajos($idSolicitud);
        $this->tablaHtmlOrdenesT($modeloOrdenesT);

        $arrayParametros = array(
            'id_solicitud' => $idSolicitud,
            'id_laboratorio' => $idLaboratorio);
        $tipoAnalisisMuestras = $this->lNegocioTipoAnalisis->tipoAnalisisMuestras($arrayParametros);
        $this->tablaHtmlMuestras($tipoAnalisisMuestras);
        $this->idSolicitud = $idSolicitud;
        $this->idLaboratorio = $idLaboratorio;
        $this->idOrdenTrabajo = $idOrdenTrabajo;

        $lNSolicitudes = new SolicitudesLogicaNegocio();
        $this->modeloSolicitudes = $lNSolicitudes->buscar($idSolicitud);

        $this->buscarPago();

        //buscar datos de la orden de trabajo
        $this->modeloOrdenTrabajo = new OrdenesTrabajosModelo();
        if ($idOrdenTrabajo != "")
        {
            $this->modeloOrdenTrabajo = $this->lNegocioOrdenTrabajo->buscar($idOrdenTrabajo);
        }
        require APP . 'Laboratorios/vistas/formularioBandejaRecepcionVista.php';
    }

    /**
     * Registra la recepción de las muestras
     */
    public function guardarMuestras()
    {
        //Guardar las muestras
        $lNRecepcionMuestra = new RecepcionMuestrasLogicaNegocio();
        $_POST['identificador'] = parent::usuarioActivo();
        $_POST['estado'] = Constantes::estado_OT()->REGISTRADA;
        $this->idOrdenTrabajo = $lNRecepcionMuestra->guardar($_POST);

        // $modeloOrdenesT = $this->lNegocioOrdenTrabajo->buscarOrdenesTrabajos($_POST['idSolicitud']);
        // $this->tablaHtmlOrdenesT($modeloOrdenesT);
        // require APP . 'Laboratorios/vistas/formularioBandejaRecepcionVista.php';
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO_RECEPCION);
    }

    /**
     * Activa la orden de trabajo
     * Si no está registrado la orden de pago o no existe una orden activa entonces debe mostras la vista correspondiente
     * @param type $param
     */
    public function activarOrden()
    {
        $this->idOrdenTrabajo = $_POST['idOrdenTrabajo'];
        $this->idSolicitud = $_POST['idSolicitud'];
        $this->idLaboratorio = $_POST['idLaboratorio'];
        $datos = array(
            'fecha_activacion' => date('Y-m-d'),
            'id_orden_trabajo' => $_POST['idOrdenTrabajo'],
            'estado' => Constantes::estado_OT()->ACTIVA
        );
        $this->lNegocioOrdenTrabajo->guardar($datos);

        $this->modeloSolicitudes = new SolicitudesModelo();
        $lNSolicitudes = new SolicitudesLogicaNegocio();
        $this->modeloSolicitudes = $lNSolicitudes->buscar($_POST['idSolicitud']);

        $this->buscarPago();

        $modeloOrdenesT = $this->lNegocioOrdenTrabajo->buscarOrdenesTrabajos($_POST['idSolicitud']);
        $this->tablaHtmlOrdenesT($modeloOrdenesT);
        // require APP . 'Laboratorios/vistas/formularioBandejaRecepcionVista.php';
        Mensajes::exito(Constantes::ACTIVADA_CON_EXITO_RECEPCION);
    }

    /**
     * Para buscar si la orden de trabajo tiene registrado un pago
     */
    public function buscarPago()
    {
        //si el pago no está registrado
        $lnPagos = new PagosLogicaNegocio();
        $buscaRegistroPago = $lnPagos->buscarLista(array('id_solicitud' => $this->idSolicitud));
        $fila = $buscaRegistroPago->current();
        $this->existePago = 'SI';
        if (empty($fila->id_pagos))
        {
            $this->existePago = 'NO';
        }
    }

    /**
     * Ver formulario para el registro de pago
     */
    public function verFormularioRegistrarPago()
    {
        $this->idOrdenTrabajo = $_POST['idOrdenTrabajo'];
        $this->idSolicitud = $_POST['idSolicitud'];
        $this->idLaboratorio = $_POST['idLaboratorio'];
        $this->modeloSolicitudes = new SolicitudesModelo();
        $lNSolicitudes = new SolicitudesLogicaNegocio();
        $this->modeloSolicitudes = $lNSolicitudes->buscar($_POST['idSolicitud']);
        //DEBE REGISTRAR EL PAGO
        //obtener el total de pago segun la orden de trabajo
        $this->totalPago = $lNSolicitudes->buscarTotalSolicitud($this->idSolicitud);
        require APP . 'Laboratorios/vistas/bandejaRecepcionPagoVista.php';
    }

    /**
     * Construye tabla con el detalle del consumo del número de muestras en las solicitudes
     * @param type $memo
     */
    public function buscarDatosMemo($memo)
    {
        $lNSolicitudes = new SolicitudesLogicaNegocio();
        $buscaDatosMemo = $lNSolicitudes->buscarDatosMemo($memo);
        $contador = 0;
        $tabla = "";
        $total = 0;
        $numMuestras = 0;
        foreach ($buscaDatosMemo as $fila)
        {
            $this->mostrarDetalleMemo = true;
            $tabla.= '<tr>
		<td>' . ++$contador . '</td>
		<td style="text-align: center"><b>' . $fila->codigo . '</b></td>
                <td style="text-align: center">' . $fila->fecha_registro . '</td>
                <td style="text-align: center">' . $fila->oficio_exoneracion . '</td>
                <td style="text-align: right">' . $fila->num_muestras_exoneradas . '</td>
                <td style="text-align: right">' . $fila->num_muestras . '</td>
                </tr>';
            $total = $total + $fila->num_muestras;
        }
        $this->saldo = $this->saldo - $total;
        $this->datosMemo = $tabla;
    }

    /**
     * Para activar la orden de trabajo aceptando la Exoneración de pago
     * @param type $param
     */
    public function aceptarExoneracion()
    {
        $datos = array(
            'fecha_activacion' => date('Y-m-d'),
            'id_orden_trabajo' => $_POST['idOrdenTrabajo'],
            'estado' => Constantes::estado_OT()->ACTIVA
        );
        $this->lNegocioOrdenTrabajo->guardar($datos);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Solicitudes
     */
    public function tablaHtmlSolicitudes($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $memo = "N/A";
                if ($fila->exoneracion == 'SI')
                {
                    $memo = '<button class="fas fa-search" onclick="fn_abrirVistaMemo(' . $fila->id_solicitud . ')"/>';
                }
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_solicitud . '"
                    class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/BandejaRecepcion"
                    data-opcion="verOrdenesTrabajo" ondragstart="drag(event)" draggable="true"
                    data-destino="detalleItem">
                        <td>' . ++$contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila->codigo . '</b></td>
                        <td>' . $fila->cliente . '</td>
                        <td>' . $fila->tipo_solicitud . '</td>
                        <td>' . $memo . '</td>
                        <td style="text-align:center">' . $this->botonDatosSolicitud($fila->id_solicitud) . '</td>
                        <td>' . $fila->fecha_envio . '</td>
                        <td>' . round($fila->total_solicitud * ("1." . Constantes::IVA), 2) . '</td>
                        <td>' . $fila->estado . '</td>
                    </tr>'
                );
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='7'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * Función para abrir el memo
     */
    public function verMemo()
    {
        $solicitud = new SolicitudesModelo();
        $lNSolicitudes = new SolicitudesLogicaNegocio();
        $solicitud = $lNSolicitudes->buscar($_POST['idSolicitud']);
        if ($solicitud->getNomArchivoOficio() != null)
        {
            $this->urlPdf = $this->rutArcExo . "/" . $solicitud->getNomArchivoOficio();
            require APP . 'Laboratorios/vistas/visorPDF.php';
        } else
        {
            echo Mensajes::fallo(Constantes::INF_NO_MEMO);
        }
    }

    /**
     * Construye el código HTML para desplegar las órdenes de trabajo
     */
    public function tablaHtmlOrdenesT($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $boton = '';
            if (in_array($fila->estado_orden, array(Constantes::estado_OT()->ACTIVA, Constantes::estado_OT()->EN_PROCESO, Constantes::estado_OT()->FINALIZADA)))
            {
                $boton = '<a class="fas fa-file-pdf" href="' . URL . 'laboratorios/BandejaInformes/descargarOt/' . $fila->id_orden_trabajo . '" target="_blank"></a>';
            }

            $this->itemsOrdenesTrabajo[] = array(
                '<tr id="' . $fila->id_solicitud . "-" . $fila->id_laboratorio . "-" . $fila->id_orden_trabajo . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/BandejaRecepcion"
		  data-opcion="verMuestras" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->codigo_ot . '</b></td>
                    <td>' . $fila->laboratorio . '</td>
                    <td>' . $fila->fecha_activacion . '</td>
                    <td>' . $fila->estado_orden . '</td>
                    <td>' . round($fila->total_orden * ("1." . Constantes::IVA), 2) . '</td>
                    <td>' . $boton . '</td>
                </tr>'
            );
        }
    }

    /**
     * Construye el código HTML para desplegar la lista de muestras
     */
    public function tablaHtmlMuestras($tabla)
    {
        $contador = 0;
        $dato = "";
        foreach ($tabla as $fila)
        {
            $numMuestra = $fila->numero_muestra;
            $html = "<tr>$dato</tr>"; //la primera vez va vacio y como va como array entonces no toma en cuenta
            $this->itemsMuestras[] = array($html);
            $dato = '<td>' . ++$contador
                    . '<input name="numMuestra[' . $fila->id_servicio . '-' . $numMuestra . ']" type="hidden" value="' . $numMuestra . '" readonly style="background: transparent; border:0; width:50px">'
                    . '<input name="idServicio[' . $fila->id_servicio . '-' . $numMuestra . ']" type="hidden" value="' . $fila->id_servicio . '">'
                    . '<input name="idDetalleSolicitud[' . $fila->id_servicio . '-' . $numMuestra . ']" type="hidden" value="' . $fila->id_detalle_solicitud . '">'
                    . '<input name="idRecepcionMuestras[' . $fila->id_servicio . '-' . $numMuestra . ']" type="hidden" value="' . $fila->id_recepcion_muestras . '">'
                    . '</td>';
            $dato.= '<td style="white - space:nowrap; "><b><input type="text" name="codigoUsuMuestra[' . $fila->id_servicio . '-' . $numMuestra . ']" value="' . $fila->codigo_usu_muestra . '" style="background:transparent;border:0" readonly/> </b></td>';
            $dato.= '<td>' . $fila->rama_nombre . '</td>';
            $dato.= '<td><input type="text" name="conservacionMuestra[' . $fila->id_servicio . '-' . $numMuestra . ']" value="' . $fila->conservacion_muestra . '" placeholder="Cómo llega la muestra a la recepción" maxlength="128"/> </td>';
            $dato.= '<td> <select name="esAceptada[' . $fila->id_servicio . '-' . $numMuestra . ']" class="cls_selectAllCmbByClass esAceptada" required onchange="fn_verificarSI()">
                                    <option value="">Seleccione</option>' . $this->crearComboSINO($fila->es_aceptada) . '</select></td>';
            $dato.= '<td><input type="text" name="observacionRecepcion[' . $fila->id_servicio . '-' . $numMuestra . ']" value="' . $fila->observacion_recepcion . '" placeholder="Escribir observación"/> </td>';
            $dato.= '</tr>';
        }
        $this->itemsMuestras[] = array($dato);
    }

    /**
     * Despliega el formulario para el reingreso de muestras
     * @param type $param
     */
    public function rMuestras()
    {
        $idSolicitud = $_POST['elementos'];
        $modeloOrdenesT = $this->lNegocioOrdenTrabajo->buscarOrdenesTrabajos($idSolicitud);
        $this->tablaHtmlOrdenesTReingreso($modeloOrdenesT);
        require APP . 'Laboratorios/vistas/formularioBandejaRecepReingresoMuesVista.php';
    }

    /**
     * Construye el código HTML para desplegar las órdenes de trabajo
     */
    public function tablaHtmlOrdenesTReingreso($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $boton = '';
            if ($fila->id_orden_trabajo != null && !empty($fila->id_orden_trabajo))
            {
                $boton = '<a class="fas fa-file-pdf" href="' . URL . 'laboratorios/BandejaInformes/descargarOt/' . $fila->id_orden_trabajo . '" target="_blank"></a>';
            }

            $this->itemsOrdenesTrabajo[] = array(
                '<tr id="' . $fila->id_solicitud . "-" . $fila->id_laboratorio . "-" . $fila->id_orden_trabajo . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/BandejaRecepcion"
		  data-opcion="verMuestrasReingreso" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->codigo_ot . '</b></td>
                    <td>' . $fila->laboratorio . '</td>
                    <td>' . $fila->fecha_activacion . '</td>
                    <td>' . $fila->estado_orden . '</td>
                    <td>' . round($fila->total_orden, 2) . '</td>
                    <td>' . $boton . '</td>
                </tr>'
            );
        }
    }

    /**
     * Busca las muestras de un análisis
     */
    public function verMuestrasReingreso()
    {
        $ids = explode('-', $_POST['id']);
        $idSolicitud = $ids[0]; //separo y obtengo id_solicitud
        $idLaboratorio = $ids[1]; //separo y obtengo id_laboratorio
        $idOrdenTrabajo = $ids[2]; //separo y obtengo id_orden_trabajo si existe
        $modeloOrdenesT = $this->lNegocioOrdenTrabajo->buscarOrdenesTrabajos($idSolicitud);
        $this->tablaHtmlOrdenesTReingreso($modeloOrdenesT);

        if ($idOrdenTrabajo == "")
        {
            $this->itemsMuestras[] = array('<tr><td colspan="7" style="text-align: center">La orden de trabajo debe estar ACTIVA</td></tr>');
        } else
        {
            $arrayParametros = array(
                'id_orden_trabajo' => $idOrdenTrabajo,
                'estado_actual' => array('PENDIENTE'));
            $recepcionMuestras = $this->lNegocioRecepcionMuestras->buscarMuestras($arrayParametros);
            $this->tablaHtmlMuestrasReingreso($recepcionMuestras);
        }

        $this->idSolicitud = $idSolicitud;
        $this->idLaboratorio = $idLaboratorio;
        $this->idOrdenTrabajo = $idOrdenTrabajo;

        //buscar datos de la orden de trabajo
        $this->modeloOrdenTrabajo = new OrdenesTrabajosModelo();
        if ($idOrdenTrabajo != "")
        {
            $this->modeloOrdenTrabajo = $this->lNegocioOrdenTrabajo->buscar($idOrdenTrabajo);
        }
        require APP . 'Laboratorios/vistas/formularioBandejaRecepReingresoMuesVista.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de muestras
     */
    public function tablaHtmlMuestrasReingreso($tabla)
    {
        $contador = 0;
        $numMuestra = 0;
        $dato = "";
        foreach ($tabla as $fila)
        {
            if ($numMuestra <> $fila['numero_muestra'])
            {
                $numMuestra = $fila['numero_muestra'];
                $html = "<tr>$dato</tr>"; //la primera vez va vacio y como va como array entonces no toma en cuenta
                $this->itemsMuestras[] = array($html);
                $dato = '<td>' . ++$contador
                        . '<input name="idRecepcionMuestras[' . $fila->id_recepcion_muestras . ']" type="hidden" value="' . $fila->id_recepcion_muestras . '">'
                        . '</td>';
                $dato.= '<td style="white - space:nowrap; "><b>' . $fila->codigo_usu_muestra . '</b></td>';
                $dato.= '<td>' . $fila->nombre . '</td>';
                $dato.= '<td><input type="text" name="conservacionMuestra[' . $fila->id_recepcion_muestras . ']" value="' . $fila->conservacion_muestra . '" placeholder="Cómo llega la muestra a la recepción" maxlength="128"/> </td>';
                $dato.= '<td><input type="text" name="observacionRecepcion[' . $fila->id_recepcion_muestras . ']" value="' . $fila->observacion_recepcion . '" placeholder="Escribir observación"/> </td>';
                $dato.= '<td><input type="date" name="fechaToma[' . $fila->id_recepcion_muestras . ']" value="' . $fila->fecha_toma . '" placeholder="" maxlength="13" required/> </td>';
                $dato.= '<td><input type="text" name="responsableToma[' . $fila->id_recepcion_muestras . ']" value="' . $fila->responsable_toma . '" placeholder="" maxlength="128" required/> </td>';

                $dato.= '</tr>';
            }
        }
        $this->itemsMuestras[] = array($dato);
    }

    /**
     * Registra la recepción de las muestras
     */
    public function guardarMuestrasReingreso()
    {
        //Guardar las muestras
        $lNRecepcionMuestra = new RecepcionMuestrasLogicaNegocio();
        $_POST['identificador'] = parent::usuarioActivo();
        $_POST['estado'] = Constantes::estado_OT()->REGISTRADA;
        $lNRecepcionMuestra->guardarReingreso($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Verificar el depósito del banco seleccionado
     * Solo se verifica el número de transacción y el banco ya que puede ser que la fecha de depósito esté registrado con otro valor
     * @param type $param
     */
    public function verficarDeposito()
    {
        $estado = "EXITO";
        $mensaje = "";
        $lNPagos = new PagosLogicaNegocio();
        $buscaRegistro = $lNPagos->verficarDeposito($_POST['banco'], $_POST['num_deposito']);
        $fila = $buscaRegistro->current();
        $a = $fila->existe;
        if ($fila->existe == TRUE)
        {
            $estado = "ERROR";
            $mensaje = Constantes::ERROR_TRANSACCION_REGISTRADA;
        }
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje));
    }

    /**
     * Permite abrir la ventana para notificar al cliente de forma manual
     */
    public function notificar()
    {
        $idSolicitud = $_POST['elementos'];
        if ($idSolicitud !== '')
        {
            //buscar datos de la solicitud
            $this->accion = "Notificar al cliente";
            $lNsolcitudes = new SolicitudesLogicaNegocio();
            $this->modeloSolicitudes = $lNsolcitudes->buscar($idSolicitud);
            //buscar datos del usuario
            $lNFichaEmpleado = new FichaEmpleadoLogicaNegocio();
            $buscaUsuario = $lNFichaEmpleado->buscarDatosUsuario($this->modeloSolicitudes->getUsuarioGuia());
            $this->datosUsuario = $buscaUsuario->current();
            require APP . 'Laboratorios/vistas/formularioNotificarClienteVista.php';
        }
    }

    /**
     * Permite enviar notificaciones al cliente de forma manual
     */
    public function enviarNotificacionClienteManual()
    {
        $lNRecepcionMuestra = new RecepcionMuestrasLogicaNegocio();
        $_POST['identificador'] = parent::usuarioActivo();
        $lNRecepcionMuestra->enviarNotificacionClienteManual($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Para visualizar las ordenes de trabajo
     */
    public function ordenTrabajo()
    {
        $idSolicitud = $_POST['elementos'];
        $this->modeloSolicitudes = new SolicitudesModelo();
        $modeloOrdenesT = $this->lNegocioOrdenTrabajo->buscarOrdenesTrabajos($idSolicitud);
        $this->tablaHtmlOrdenTrabajo($modeloOrdenesT);
        require APP . 'Laboratorios/vistas/ordenTrabajoVista.php';
    }

    /**
     * Construye el código HTML para desplegar las órdenes de trabajo
     */
    public function tablaHtmlOrdenTrabajo($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $boton = '';
            if ($fila->id_orden_trabajo != null && !empty($fila->id_orden_trabajo))
            {
                $boton = '<a class="fas fa-file-pdf" href="' . URL . 'laboratorios/BandejaInformes/descargarOt/' . $fila->id_orden_trabajo . '" target="_blank"></a>';
            }

            $this->itemsOrdenesTrabajo[] = array(
                '<tr>
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->codigo_ot . '</b></td>
                    <td>' . $fila->laboratorio . '</td>
                    <td>' . $fila->fecha_activacion . '</td>
                    <td>' . $fila->estado_orden . '</td>
                    <td>' . round($fila->total_orden * ("1." . Constantes::IVA), 2) . '</td>
                    <td>' . $boton . '</td>
                </tr>'
            );
        }
    }

}

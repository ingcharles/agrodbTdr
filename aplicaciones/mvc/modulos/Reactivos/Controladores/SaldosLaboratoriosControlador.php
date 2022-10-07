<?php

/**
 * Controlador SaldosLaboratorios
 *
 * Este archivo controla la lógica del negocio del modelo:  SaldosLaboratoriosModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     SaldosLaboratoriosControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\SaldosLaboratoriosLogicaNegocio;
use Agrodb\Reactivos\Modelos\SaldosLaboratoriosModelo;
use Agrodb\Reactivos\Modelos\SolicitudCabeceraModelo;
use Agrodb\Reactivos\Modelos\SolicitudCabeceraLogicaNegocio;
use Agrodb\Reactivos\Modelos\ReactivosLaboratoriosLogicaNegocio;
use Agrodb\Reactivos\Modelos\SolicitudRequerimientoLogicaNegocio;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class SaldosLaboratoriosControlador extends BaseControlador
{

    private $lNegocioSaldosLaboratorios = null;
    private $lNegocioSolicitudRequerimiento = null;
    private $lNegocioReactivosLaboratorios = null;
    private $modeloSaldosLaboratorios = null;
    private $lNegocioSolicitudCabecera = null;
    private $accion = null;
    private $itemsSaldosRequeridos = null;
    private $idSolicitudCabecera = null;
    private $arrayTotales;
    private $tipo = null;
    private $codBaja = null;
    private $idReactivoLaboratorio = null;
    private $itemsSaldosLote = null;
    public $modeloSolicitudCabecera = null;
    private $opcion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioReactivosLaboratorios = new ReactivosLaboratoriosLogicaNegocio();
        $this->lNegocioSaldosLaboratorios = new SaldosLaboratoriosLogicaNegocio();
        $this->modeloSaldosLaboratorios = new SaldosLaboratoriosModelo();
        $this->lNegocioSolicitudCabecera = new SolicitudCabeceraLogicaNegocio();
        $this->lNegocioSolicitudRequerimiento = new SolicitudRequerimientoLogicaNegocio();
        $this->codBaja = Constantes::catalogos_rea()->COD_BAJA;
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Reactivos/vistas/listaSaldosLaboratoriosVista.php';
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatosConsolidar()
    {
        $arrayParametros = array();
        if (!empty($_POST['nombre']))
        {
            $arrayParametros['nombre'] = $_POST['nombre'];
        }
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        //guardar en sesion el id_laboratorios_provincia
        $_SESSION['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        $buscarSaldosPorLaboratorio = $this->lNegocioSaldosLaboratorios->buscarSaldosPorLaboratorio($arrayParametros);
        $this->tablaHtmlSaldosLaboratorios($buscarSaldosPorLaboratorio);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Registra los datos tipo CONSOLIDADO
     */
    public function guardarConsolidado()
    {
        $_POST['id_laboratorios_provincia'] = $_SESSION['id_laboratorios_provincia'];
        $_POST['motivo'] = 'CONSOLIDADO';
        $_POST['identificador'] = parent::usuarioActivo();
        $this->lNegocioSaldosLaboratorios->guardarConsolidado($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Registrar el ingreso de reactivo al laboratorio
     */
    public function ingreso()
    {
        $this->opcion = "Ingreso de reactivos";
        $arrayParametros = array(
            'id_laboratorios_provincia' => $this->laboratoriosProvincia(), //todos los laboratorios del usuario
            'estado' => array('SOLICITADO', 'EN PROCESO'));
        $buscaSolicitudRequerimiento = $this->lNegocioSolicitudCabecera->buscarSolicitudesTodas($arrayParametros);
        $this->tablaHtmlSolicitudes($buscaSolicitudRequerimiento);
        require APP . 'Reactivos/vistas/listaSolicitudCabeceraVista.php';
    }

    /**
     * Para buscar los totales solicitados de la tabla solicitud_requerimiento
     */
    public function buscarTotales()
    {
        //buscar los totales solicitados de la tabla solicitud_requerimiento
        $buscaSolictudesRequerimiento = $this->lNegocioSolicitudRequerimiento->buscarLista(array('id_solicitud_cabecera' => $this->idSolicitudCabecera));
        $array = array();
        foreach ($buscaSolictudesRequerimiento as $row)
        {
            $array[] = array('id' => $row->id_solicitud_requerimiento, 'cant_solicitada' => $row->cantidad_solicitada);
        }
        $this->arrayTotales = json_encode($array);
    }

    /**
     * Muestra formulario para ingresar las cantidades recibidad por lotes
     * @param type $tipo
     */
    public function ingresoCantidad($tipo)
    {
        //datos de la solicitud
        $lNSolicitudCabecera = new SolicitudCabeceraLogicaNegocio();
        $this->modeloSolicitudCabecera = new SolicitudCabeceraModelo();
        $this->modeloSolicitudCabecera = $lNSolicitudCabecera->buscar($_POST['id']);

        $this->buscarTotales();
        $datos = $this->lNegocioSaldosLaboratorios->inicializarIngreso($_POST['id']);

        if ($tipo == 'SOLICITUD A BODEGA')
        {
            $formulario = 'formularioSaldosLaboratoriosVista';
            $this->tablaHtmlIngresoRequerimiento($datos);
        } else
        {
            $this->tablaHtmlIngresoRequerimientoLaboratorio($datos);
            $formulario = 'formularioIngresoReactivosLaboratorioVista';
        }
        require APP . 'Reactivos/vistas/' . $formulario . '.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de - SaldosLaboratorios
     */
    public function tablaHtmlReactivosSolucion($tabla)
    {
        if (count($tabla) > 0)
        {
            $this->itemsSaldosRequeridos = "";
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsSaldosRequeridos.= '<tr id="' . $fila->id_reactivo_laboratorio . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/SaldosLaboratorios"
		  data-opcion="verLotesConsolidar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>
                    <td>' . $fila->cantidad_solicitada . '</td>
                    <td>' . $fila->unidad_medida . '</td>
                </tr>';
            }
        } else
        {
            $this->itemsSaldosRequeridos.="<tr><td colspan='5'>No existen datos para mostrar</td></tr>";
        }
    }

    /**
     * Registra las salidas
     */
    public function guardarSaldosSolucion()
    {
        $_POST["tipo_ingreso"] = 'EGRESO';
        $_POST["motivo"] = 'SOLUCION';
        $this->lNegocioSaldosLaboratorios->guardarSaldosSolucion($_POST);
        echo Constantes::GUARDADO_CON_EXITO;
    }

    /**
     * Crear un nuevo registro para registrar un nuevo lote
     */
    public function nuevoLote($idSaldoLaboratorio, $idSolicitudCabecera)
    {
        //Guardar los datos hasta el momento
        $_POST['estado'] = Constantes::estado_SOLREA()->EN_PROCESO;
        $this->lNegocioSaldosLaboratorios->guardar($_POST);
        //datos de la solicitud
        $lNSolicitudCabecera = new SolicitudCabeceraLogicaNegocio();
        $this->modeloSolicitudCabecera = new SolicitudCabeceraModelo();
        $this->modeloSolicitudCabecera = $lNSolicitudCabecera->buscar($idSolicitudCabecera);
        $this->buscarTotales();
        $datos = $this->lNegocioSaldosLaboratorios->crearNuevoLote($idSaldoLaboratorio, $idSolicitudCabecera);
        $this->tablaHtmlIngresoRequerimiento($datos);
        require APP . 'Reactivos/vistas/formularioSaldosLaboratoriosVista.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de - SaldosLaboratorios
     */
    public function tablaHtmlSaldosLaboratorios($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_reactivo_laboratorio . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/SaldosLaboratorios"
		  data-opcion="verLotesConsolidar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>
                    <td>' . $fila->unidad . '</td>
                    <td>' . $fila->total_ingreso . '</td>
                    <td>' . $fila->total_egreso . '</td>
                    <td>' . $fila->saldo . '</td>
                </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * Para mostrar el formulario de registro de salida de reactivo
     */
    public function verLotesConsolidar()
    {
        $this->accion = "Registrar Consolidar saldos";
        $this->idReactivoLaboratorio = $_POST["id"];
        //obtener los saldos por lote del reactivo seleccionado
        $arrayParametros = array(
            'id_laboratorio' => parent::laboratorioUsuario(),
            'id_reactivo_laboratorio' => $_POST["id"]
        );
        $buscaSaldosPorLote = $this->lNegocioSaldosLaboratorios->buscarSaldosPorLote($arrayParametros);
        $this->tablaHtmlConsolidar($buscaSaldosPorLote);
        $this->modeloSaldosLaboratorios = $this->lNegocioSaldosLaboratorios->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioConsolidarSaldosVista.php';
    }

    /**
     * Forma la tabla del formulario de registro de salida de reactivo
     * @param type $tabla
     */
    public function tablaHtmlConsolidar($tabla)
    {
        $contador = 0;
        $this->itemsSaldosLote = "";
        foreach ($tabla as $fila)
        {
            $this->itemsSaldosLote.=
                    "<tr id = $fila->id_reactivo_laboratorio-$fila->lote class='item'"
                    . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Reactivos/SaldosLaboratorios'"
                    . 'data-opcion="verKardexLaboratorios"'
                    . 'data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td>' . $fila->nombre . '</td>
                    <td>' . $fila->unidad . '</td>
                    <td>' . $fila->lote . '</td>
                    <td>' . $fila->total_ingreso . '</td>
                    <td>' . $fila->total_egreso . '</td>
                    <td>' . $fila->saldo . '</td>
                    <td>' . $fila->fecha_caducidad . '</td>
                    <td><button type ="button" class="far fa-window-restore" onclick="fn_verModalConsolidar(' . $fila->id_reactivo_laboratorio . ',' . "'$fila->lote'" . ')" ></button></td>
                    </tr>';
        }
    }

    /*     * ***************************** */
    /*     * ***** SALIDA DE REACTIVOS *** /
      /*     * ***************************** */

    /**
     * Registrar manualmente la salida de reactivo del laborarorio o dar de baja.
     */
    public function salida()
    {
        require APP . 'Reactivos/vistas/listaSalidaReactivosLaboratoriosVista.php';
    }

    /**
     * Mustras la lista de reactivos del laboratorio
     */
    public function listarDatos()
    {
        $arrayParametros = array();
        if (!empty($_POST['nombre']))
        {
            $arrayParametros['nombre'] = $_POST['nombre'];
        }
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        $arrayParametros['tipo'] = 'REACTIVO';
        $buscarSaldosPorLaboratorio = $this->lNegocioSaldosLaboratorios->buscarSaldosPorLaboratorio($arrayParametros);
        $this->tablaHtmlReactivosLaboratorios($buscarSaldosPorLaboratorio);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Guarda la salida de un reactivo
     */
    public function guardarSalidaReactivo()
    {
        $_POST['tipo_ingreso'] = 'EGRESO';
        $_POST['motivo'] = 'SALIDA MANUAL';
        $this->lNegocioSaldosLaboratorios->guardarSalidaReactivo($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo SaldosLaboratorios";
        require APP . 'Reactivos/vistas/formularioSaldosLaboratoriosVista.php';
    }

    /**
     * Método para registrar en la base de datos -SaldosLaboratorios
     */
    public function guardar()
    {
        $_POST['estado'] = Constantes::estado_SOLREA()->INGRESADO;
        $this->lNegocioSaldosLaboratorios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: SaldosLaboratorios
     */
    public function editar()
    {
        $this->accion = "Editar SaldosLaboratorios";
        $this->modeloSaldosLaboratorios = $this->lNegocioSaldosLaboratorios->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioSaldosLaboratoriosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - SaldosLaboratorios
     */
    public function borrar($idSaldoLaboratorio, $idSolicitudCabecera)
    {
        //datos de la solicitud
        $lNSolicitudCabecera = new SolicitudCabeceraLogicaNegocio();
        $this->modeloSolicitudCabecera = new SolicitudCabeceraModelo();
        $this->modeloSolicitudCabecera = $lNSolicitudCabecera->buscar($idSolicitudCabecera);
        $this->lNegocioSaldosLaboratorios->borrar($idSaldoLaboratorio);
        $this->buscarTotales();
        $datos = $this->lNegocioSaldosLaboratorios->inicializarIngreso($idSolicitudCabecera);
        $this->tablaHtmlIngresoRequerimiento($datos);
        echo $this->itemsSaldosRequeridos;
    }

    /**
     * Construye el código HTML para desplegar la lista de solicitudes a bodega
     */
    public function tablaHtmlSolicitudes($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $boton = '<a class="far fa-file-excel fa-2x" href="' . URL . 'Laboratorios/BandejaInformes/reactivos/' . $fila->id_solicitud_cabecera . '" target="_blank"></a>';
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_solicitud_cabecera . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/SaldosLaboratorios"
		  data-opcion="ingresoCantidad/' . $fila->tipo . '" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
                      <td>' . $fila->laboratorio_solicita . '</td>
                      <td>' . $fila->tipo . '</td>
                        <td>' . $fila->nombre_origen . ' - ' . $fila->provincia_origen . '</td>
                  <td>' . $fila->fecha_solicitud . '</td>
                      <td >' . $fila->observacion . '</td>
                  <td >' . $fila->estado . '</td>
                      <td style="text-align:center">' . $boton . '</td>
                   </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='7'>No existe solicitudes a bodega pendientes por ingresar al laboratorio</td></tr>");
        }
    }

    /**
     * Construye el código HTML para desplegar la lista de - ReactivosLaboratorios
     */
    public function tablaHtmlReactivosLaboratorios($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_reactivo_laboratorio . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/SaldosLaboratorios"
		  data-opcion="verLotes" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>
                    <td style="text-align:center">' . $fila->unidad . '</td>
                    <td style="text-align:right">' . $fila->total_ingreso . '</td>
                    <td style="text-align:right">' . $fila->total_egreso . '</td>
                    <td style="text-align:right">' . $fila->saldo . '</td>
                </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * Para mostrar el formulario de registro de salida de reactivo
     */
    public function verLotes()
    {
        $this->accion = "Registrar salida del reactivo";
        $this->idReactivoLaboratorio = $_POST["id"];

        //obtener los saldos por lote del reactivo seleccionado
        $arrayParametros = array(
            'id_reactivo_laboratorio' => $_POST["id"]
        );
        $buscaSaldosPorLote = $this->lNegocioSaldosLaboratorios->buscarSaldosPorLote($arrayParametros);
        $this->tablaHtmlSalida($buscaSaldosPorLote);
        $this->modeloSaldosLaboratorios = $this->lNegocioSaldosLaboratorios->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioSalidaReactivosLaboratoriosVista.php';
    }

    /**
     * Para editar los saldos
     */
    public function editarSaldo()
    {
        $arrayParametros = array(
            'id_laboratorio' => parent::laboratorioUsuario(),
            'id_reactivo_laboratorio' => $_POST["id_reactivo_laboratorio"],
            'lote' => $_POST["lote"]
        );
        $buscaSaldosPorLote = $this->lNegocioSaldosLaboratorios->buscarSaldosPorLote($arrayParametros);
        $this->tablaHtmlLote($buscaSaldosPorLote);
        echo $this->itemsSaldosLote;
    }

    /**
     * Forma la tabla del formulario de registro de salida de reactivo
     * @param type $tabla
     */
    public function tablaHtmlSalida($tabla)
    {
        $this->itemsSaldosLote = "";
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsSaldosLote.=
                        "<tr id = $fila->id_reactivo_laboratorio-$fila->lote class='item'"
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Reactivos/SaldosLaboratorios'"
                        . 'data-opcion="verKardexLaboratorios"'
                        . 'data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td>' . $fila->nombre . '</td>
                    <td>' . $fila->unidad . '</td>
                    <td>' . $fila->lote . '</td>
                    <td>' . $fila->total_ingreso . '</td>
                    <td>' . $fila->total_egreso . '</td>
                    <td>' . $fila->saldo . '</td>
                    <td>' . $fila->fecha_caducidad . '</td>
                    <td><button type ="button" class="far fa-window-restore" onclick="fn_verModalRegistroSalida(' . $fila->id_reactivo_laboratorio . ',' . "'$fila->lote'" . ')" ></button></td>
                    </tr>';
            }
        } else
        {
            $this->itemsSaldosLote.= "<tr><td colspan='5'>No existen datos para mostrar</td></tr>";
        }
    }

    /**
     * Forma la tabla del formulario de registro de salida de reactivo
     * @param type $tabla
     */
    public function tablaHtmlLote($tabla)
    {
        $this->itemsSaldosLote = "";
        foreach ($tabla as $fila)
        {
            $this->itemsSaldosLote.=
                    '<tr>
		  <td><input type="hidden" name="id_reactivo_laboratorio" value="' . $fila->id_reactivo_laboratorio . '"/>'
                    . '<input type="hidden" name="lote" value="' . $fila->lote . '"/>' . $fila->nombre . '</td>
                  <td>' . $fila->unidad . '</td>
                  <td>' . $fila->lote . '</td>
                  <td>' . $fila->total_ingreso . '</td>
                  <td>' . $fila->total_egreso . '</td>
                  <td>' . $fila->saldo . '</td>
                  <td>' . $fila->fecha_caducidad . '</td>
                    </tr>';
        }
    }

    /**
     * Desplegar la vistra para ver el kardex segun el reactivo y lote
     */
    public function verKardexLaboratorios()
    {
        $ids = explode('-', $_POST['id']);
        $idReactivoLaboratorio = $ids[0];
        $lote = $ids[1];

        $this->accion = "K&aacute;rdex";

        $arrayParametros = array(
            'id_reactivo_laboratorio' => $idReactivoLaboratorio,
            'lote' => $lote
        );
        $buscaSaldosPorLote = $this->lNegocioSaldosLaboratorios->buscarSaldosPorLote($arrayParametros);
        $this->tablaHtmlLote($buscaSaldosPorLote);

        $arrayParametrosK = array('id_reactivo_laboratorio' => $idReactivoLaboratorio, 'lote' => $lote);
        $bucarDatos = $this->lNegocioSaldosLaboratorios->buscarKardexLaboratorios($arrayParametrosK);
        $this->tablaHtmlKardexLaboratorio($bucarDatos);
        require APP . 'Reactivos/vistas/listaKardexLaboratorioVista.php';
    }

    /**
     * Construye el código HTML para desplegar la lista
     */
    public function tablaHtmlKardexLaboratorio($tabla)
    {
        $html = "";
        $contador = 0;
        if (count($tabla) > 0)
        {
            $saldo = 0;
            foreach ($tabla as $fila)
            {
                if ($fila->tipo_ingreso == 'INGRESO')
                {
                    $saldo = $saldo + $fila->cantidad;
                } else
                {
                    $saldo = $saldo - $fila->cantidad;
                }
                $html.="<tr>"
                        . "<td>" . ++$contador . "</td>"
                        . "<td>" . $fila->tipo_ingreso . "</td>"
                        . "<td>" . $fila->motivo . "</td>"
                        . "<td style='text-align: right'>" . $fila->cantidad . "</td>"
                        . "<td style='text-align: right'>" . $saldo . "</td>"
                        . "<td style='text-align: center'>" . $fila->fecha_registro . "</td>"
                        . "<td>" . $fila->razon_salida . "</td>"
                        . "<td>" . $fila->codigo_lab_muestra . "</td>"
                        . "<td>" . $fila->num_resultado_analisis . "</td>"
                        . "</tr>";
            }
        } else
        {
            $html = "<tr colspan='5'><td colspan='5'>No tiene asignado los reactivos</td></tr>";
        }
        $this->listaKardexLaboratorios = $html;
    }

    /**
     * 
     * @param type $tabla
     */
    public function tablaHtmlIngresoRequerimiento($tabla)
    {
        $contador = 0;
        $this->itemsSaldosRequeridos = "";
        foreach ($tabla as $fila)
        {
            $campoIdSaldosLaboratorio = '<input type="hidden" id="id_saldo_laboratorio" name="id_saldo_laboratorio[' . $fila->id_saldo_laboratorio . ']" value="' . $fila->id_saldo_laboratorio . '"/>';
            $eliminar = '';
            $cantidad = $fila->cantidad_solicitada . ' ' . $fila->unidad;
            //solo se elimina las crea ha creado como nuevo lote
            if (in_array($this->modeloSolicitudCabecera->getEstado(), array('SOLICITADO', 'EN PROCESO')) & $fila->nuevo == 'SI')
            {
                $cantidad = '';
                $eliminar = '<button type ="button" class="icono" onclick="eliminarSaldoLaboratorio(' . $fila->id_saldo_laboratorio . ')"></button>';
            }
            $btnAgregarLote = "";
            if (in_array($this->modeloSolicitudCabecera->getEstado(), array('SOLICITADO', 'EN PROCESO')) & $fila->nuevo == 'NO')
            {
                $btnAgregarLote = '<button type ="submit" class="fas fa-plus-circle" onclick="agregar_lote(' . $fila->id_saldo_laboratorio . ')" title="Agregar registro"></button>';
            }
            $certificado = "No Existe";
            if (!empty($fila->nombre_archivo))
            {
                $certificado = $this->descargaPdf(URL_DIR_REA_CERTIFICADOS . '/' . $fila->nombre_archivo);
            }
            $this->itemsSaldosRequeridos.=
                    '<tr>
                    <td>' . $btnAgregarLote . '</td>
                    <td>' . ++$contador . $campoIdSaldosLaboratorio . '</td>
                    <td>' . $fila->nombre . '</td>
                    <td>' . $certificado . '</td>
                    <td>' . $cantidad . '</td>
                    <td>' . $fila->unidad . '</td>
                    <td><input id="lote" name="lote[' . $fila->id_saldo_laboratorio . ']" type="text" value="' . $fila->lote . '" size="10" maxlength="8" required style="text-transform:uppercase;"/></td>
                    <td><input type="number" class="agrupa_' . $fila->id_solicitud_requerimiento . '" id="cantidad" name="cantidad[' . $fila->id_saldo_laboratorio . ']" value="' . $fila->cantidad . '" step="0.000001" value="0.00" placeholder="0.00" min="0.000001" max="' . $fila->cantidad_solicitada . '" lang="en" style="width:80px" required/></td>
                    <td><input id="fecha_caducidad" name="fecha_caducidad[' . $fila->id_saldo_laboratorio . ']" type="date" value="' . $fila->fecha_caducidad . '" size="6" required/></td>
                    <td><input id="ubicacion" name="ubicacion[' . $fila->id_saldo_laboratorio . ']" type="text" value="' . $fila->ubicacion . '" size="20" maxlength="256" required style="text-transform:uppercase;"/></td>
                    <td style="text-align: center" class="borrar">' . $eliminar . '</td>
                </tr>';
        }
    }

    /**
     * 
     * @param type $tabla
     */
    public function tablaHtmlIngresoRequerimientoLaboratorio($tabla)
    {
        $contador = 0;
        $this->itemsSaldosRequeridos = "";
        foreach ($tabla as $fila)
        {
            $campoIdSaldosLaboratorio = '<input type="hidden" id="id_saldo_laboratorio" name="id_saldo_laboratorio[' . $fila->id_saldo_laboratorio . ']" value="' . $fila->id_saldo_laboratorio . '"/>';
            $eliminar = '';
            $cantidad = $fila->cantidad_solicitada . ' ' . $fila->unidad;

            $certificado = "No Existe";
            if (!empty($fila->nombre_archivo))
            {
                $certificado = $this->descargaPdf(URL_DIR_REA_CERTIFICADOS . '/' . $fila->nombre_archivo);
            }
            $this->itemsSaldosRequeridos.=
                    '<tr>
                    <td>' . ++$contador . $campoIdSaldosLaboratorio . '</td>
                    <td>' . $fila->nombre . '</td>
                    <td>' . $certificado . '</td>
                    <td>' . $cantidad . '</td>
                    <td>' . $fila->unidad . '</td>
                    <td><input id="lote" name="lote[' . $fila->id_saldo_laboratorio . ']" type="text" value="' . $fila->lote . '" size="10" maxlength="8" required style="text-transform:uppercase;"/></td>
                    <td><input type="number" class="agrupa_' . $fila->id_solicitud_requerimiento . '" id="cantidad" name="cantidad[' . $fila->id_saldo_laboratorio . ']" value="' . $fila->cantidad . '" step="0.000001" value="0.00" placeholder="0.00" min="0.000001" max="' . $fila->cantidad_solicitada . '" lang="en" style="width:80px" required/></td>
                    <td><input id="fecha_caducidad" name="fecha_caducidad[' . $fila->id_saldo_laboratorio . ']" type="date" value="' . $fila->fecha_caducidad . '" size="6" required/></td>
                    <td><input id="ubicacion" name="ubicacion[' . $fila->id_saldo_laboratorio . ']" type="text" value="' . $fila->ubicacion . '" size="20" maxlength="256" required style="text-transform:uppercase;"/></td>
                    <td style="text-align: center" class="borrar">' . $eliminar . '</td>
                </tr>';
        }
    }

}

<?php

/**
 * Controlador SolicitudRequerimiento
 *
 * Este archivo controla la lógica del negocio del modelo:  SolicitudRequerimientoModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     SolicitudRequerimientoControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\SolicitudRequerimientoLogicaNegocio;
use Agrodb\Reactivos\Modelos\SolicitudRequerimientoModelo;
use Agrodb\Reactivos\Modelos\SolicitudCabeceraLogicaNegocio;
use Agrodb\Reactivos\Modelos\SolicitudCabeceraModelo;
use Agrodb\Reactivos\Modelos\ReactivosLaboratoriosLogicaNegocio;
use Agrodb\Reactivos\Modelos\ReactivosBodegaLogicaNegocio;
use Agrodb\Reactivos\Modelos\SaldosLaboratoriosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\LaboratoriosProvinciaModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class SolicitudLaboratorioControlador extends BaseControlador
{

    private $lNegocioSolicitudRequerimiento = null;
    private $lNegocioReactivosLaboratorios = null;
    private $modeloSolicitudRequerimiento = null;
    private $lNegocioSolicitudCabecera = null;
    private $modeloSolicitudCabecera = null;
    private $modeloLaboratoriosProvincia = null;
    private $lNreactivosLaboratorios = null;
    private $lNegocioReactivosBodega = null;
    private $accion = null;
    private $itemsRequeridos;
    private $itemsReactivos = array();
    private $idSolicitudCabecera;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioSaldosLaboratorios = new SaldosLaboratoriosLogicaNegocio();
        $this->lNegocioReactivosLaboratorios = new ReactivosLaboratoriosLogicaNegocio();
        $this->lNegocioSolicitudRequerimiento = new SolicitudRequerimientoLogicaNegocio();
        $this->modeloSolicitudRequerimiento = new SolicitudRequerimientoModelo();
        $this->lNreactivosLaboratorios = new ReactivosLaboratoriosLogicaNegocio();
        $this->lNegocioSolicitudCabecera = new SolicitudCabeceraLogicaNegocio();
        $this->modeloSolicitudCabecera = new SolicitudCabeceraModelo();
        $this->lNegocioReactivosBodega = new ReactivosBodegaLogicaNegocio();
        $this->modeloLaboratoriosProvincia = new LaboratoriosProvinciaModelo();
        parent::__construct();

        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {

    }

    /**
     * Muestra en el panel izquierdo la lista de reactivos del laboratorio para agregar a la solicitud
     * al panel derecho
     */
    public function nuevo()
    {
        require APP . 'Reactivos/vistas/listaReactivosLaboratoriosOtrosVista.php';
    }

    /**
     * Muestra la lista de reactivos del laboratorio
     */
    public function listarReactivosLaboratorio()
    {
        $arrayParametros = array();
        if (!empty($_POST['nombre']))
        {
            $arrayParametros['nombre'] = $_POST['nombre'];
        }
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        $arrayParametros['tipo'] = array('REACTIVO', 'SOLUCION');
        $buscarSaldosPorLaboratorio = $this->lNegocioSaldosLaboratorios->buscarSaldosReactivosLaboratorios($arrayParametros);
        $this->tablaHtmlReactivosLaboratorio($buscarSaldosPorLaboratorio);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ReactivosLaboratorios
     */
    public function tablaHtmlReactivosLaboratorio($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_reactivo_laboratorio . '"
		  class="" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/SolicitudLaboratorio"
		  data-opcion="agregar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>'
                    . '<td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>'
                    . "<td style='text-align:center'>$fila->tipo</td>"
                    . "<td style='text-align:center'>$fila->unidad_medida</td>"
                    . "<td style='text-align:right'>$fila->total_ingreso</td>"
                    . "<td style='text-align:right'>$fila->total_egreso</td>"
                    . "<td style='text-align:right'>$fila->saldo</td>"
                    . "<td style='text-align:center'>$fila->estado_registro</td>"
                    . "<td style='text-align:center'>" . "<button class='' value='>' onclick='fn_agregarReaLabASolicitud(" . $fila->id_reactivo_laboratorio . ")' title='Agregar a la lista'>&gt;&gt;</button>" . "</td>"
                    . '</tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='7'>No existe reactivos disponibles en el laboratorio</td></tr>");
        }
    }

    public function verFormularioDetalle()
    {
        $idSolicitudCabecera = $_POST['id'];
        //datos de la solicitud
        $this->modeloSolicitudCabecera = $this->lNegocioSolicitudCabecera->buscar($idSolicitudCabecera);
        //recuperamos todos los reactivos utilizados
        $this->tablaHtmlSolicitudRequerimiento($idSolicitudCabecera);
        require APP . 'Reactivos/vistas/formularioSolicitudLaboratorioVista.php';
    }

    /**
     * Creamos una solicitud para el laboratorio de forma automática y la mantenemos activa 
     * hasta que sea envida, por lo que el usuario podrá seguir agregando reactivos.
     */
    public function agregar()
    {
        $idSolicitudCabecera = $_POST['idSolicitudCabecera'];
        $idReactivoLaboratorio = $_POST['idReactivoLaboratorio'];
        $idLaboratoriosProvincia = $_POST['idLaboratoriosProvincia'];   //laboratorio que solicita
        $idLaboratoriosProvinciaOrigen = $_POST['idLaboratoriosProvinciaOrigen']; //laboratorio a quien solicita
        //crear la solicitud
        if (empty($idSolicitudCabecera))
        {
            $this->accion = "Nueva Solicitud de Requerimiento a Laboratorio";
            //Creamos la solicitud
            $datosSolicitud = array(
                "fecha_solicitud" => date(DATE_FORMAT),
                "codigo" => "TEMP",
                "estado" => "ACTIVO",
                "id_laboratorios_provincia" => $idLaboratoriosProvincia,
                "id_laboratorios_provincia_origen" => $idLaboratoriosProvinciaOrigen,
                "tipo" => 'SOLICITUD A LABORATORIO');
            $idSolicitudCabecera = $this->lNegocioSolicitudCabecera->guardar($datosSolicitud);
        } else
        {
            //Recuperamos la solicitud activa
            $this->accion = "Editando la Solicitud de Reactivos";
        }

        $datosRequerimiento = array(
            "id_reactivo_laboratorio" => $idReactivoLaboratorio,
            "id_solicitud_cabecera" => $idSolicitudCabecera);
        $resultadoRR = $this->modeloSolicitudRequerimiento->buscarLista($datosRequerimiento);
        //Guardamos el detalle de la solicitud
        $filaRR = $resultadoRR->current();
        if (empty($filaRR->id_reactivo_laboratorio))
        {
            $this->lNegocioSolicitudRequerimiento->guardar($datosRequerimiento);
        }
        
        $this->modeloSolicitudCabecera = $this->modeloSolicitudCabecera->buscar($idSolicitudCabecera);
        
        //recuperamos todos los reactivos utilizados
        $this->tablaHtmlSolicitudRequerimiento($idSolicitudCabecera);
        require APP . 'Reactivos/vistas/formularioSolicitudLaboratorioVista.php';
    }

    /**
     * Método para registrar en la base de datos -SolicitudRequerimiento
     */
    public function guardar()
    {
        $this->lNegocioSolicitudRequerimiento->actualizarCantidad($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: SolicitudRequerimiento
     */
    public function editar($estado)
    {
        $idSolicitudCabecera = $_POST["id"];

        $this->itemsRequeridos = "";
        $this->modeloSolicitudCabecera = $this->lNegocioSolicitudCabecera->buscar($idSolicitudCabecera);

        $lNLaboratoriosProvincia = new \Agrodb\Laboratorios\Modelos\LaboratoriosProvinciaLogicaNegocio();
        $this->modeloLaboratoriosProvincia = $lNLaboratoriosProvincia->buscar($this->modeloSolicitudCabecera->getIdLaboratoriosProvinciaOrigen());

        //reactivos del laboratorio
        $arrayParametros['id_laboratorios_provincia'] = $this->modeloSolicitudCabecera->getIdLaboratoriosProvincia();
        $arrayParametros['tipo'] = array('REACTIVO', 'SOLUCION');
        $buscarSaldosPorLaboratorio = $this->lNegocioSaldosLaboratorios->buscarSaldosReactivosLaboratorios($arrayParametros);
        $this->tablaHtmlReactivosLaboratorio($buscarSaldosPorLaboratorio);

        $this->tablaHtmlSolicitudRequerimiento($idSolicitudCabecera, $estado);
        if ($estado == 'ACTIVO')
        {
            $this->accion = "Editar Solicitud";
            require APP . 'Reactivos/vistas/listaReactivosLaboratoriosOtrosVista.php';
        } else
        {
            $this->accion = "Detalle de la Solicitud";
            require APP . 'Reactivos/vistas/lecturaSolicitudLaboratorioVista.php';
        }
    }

    /**
     * Método para borrar un registro en la base de datos - SolicitudRequerimiento
     */
    public function borrar($idSolicitudRequerimiento, $idSolicitudCabecera)
    {
        $this->lNegocioSolicitudRequerimiento->borrar($idSolicitudRequerimiento);
        $this->tablaHtmlSolicitudRequerimiento($idSolicitudCabecera);
        $this->modeloSolicitudCabecera = $this->modeloSolicitudCabecera->buscar($idSolicitudCabecera);
        echo $this->itemsRequeridos;
    }

    /**
     * Construye el código HTML para desplegar la lista de - SolicitudRequerimiento
     */
    public function tablaHtmlSolicitudRequerimiento($idSolicitudCabecera, $estado = "ACTIVO")
    {
        //buscar los reactivos del laboratorio
        $tabla = $this->lNegocioSaldosLaboratorios->buscarReactivosLabSolicitados($idSolicitudCabecera);
        $contador = 0;
        $this->itemsRequeridos = "";
        foreach ($tabla as $fila)
        {
            $cantidad = "";
            $eliminar = "";
            //Unicamente se puede editar las solicitudes con estado ACTIVO
            if ($estado != 'ACTIVO')
            {
                $cantidad = $fila->cantidad_solicitada;
                $eliminar = "---";
            } else
            {
                $cantidad = '<input type="number" id="cantidad" name="cantidad[' . $fila->id_solicitud_requerimiento . ']" value="' . $fila->cantidad_solicitada . '" step="0.01" value="0.00" placeholder="0.00" min="0.01" lang="en" size="10" required/>';
                $eliminar = '<button type ="button"  class="icono" onclick="eliminarRequerimiento(' . $fila->id_solicitud_requerimiento . ')"></button>';
            }

            $this->itemsRequeridos.=
                    '<tr>
		  <td>' . ++$contador . '</td>
                      <td>' . $fila->nombre . '</td>
                  <td>' . $fila->tipo . '</td>'
                    . '<td>' . $fila->unidad_medida . '</td>'
                    . '<td>' . $fila->saldo . '</td>'
                    . "<td style='text-align: center'>$cantidad</td>"
                    . "<td style='text-align: center' class='borrar'>$eliminar</td>"
                    . '</tr>';
        }
    }

    /**
     * Construye el código HTML para desplegar la lista de - SolicitudRequerimiento
     */
    public function tablaHtmlSolicitudCabecera($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $boton = '<a class="far fa-file-excel fa-2x" href="' . URL . 'Laboratorios/BandejaInformes/reactivos/' . $fila->id_solicitud_cabecera . '" target="_blank"></a>';
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_solicitud_cabecera . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/SolicitudRequerimiento"
		  data-opcion="editar/' . $fila->estado . '" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
                      <td>' . $fila->nombre_laboratorio . '</td>
                       <td>' . $fila->provincia_bodega . '</td>
                        <td>' . $fila->nombre_bodega . '</td>
                  <td>' . $fila->fecha_solicitud . '</td>
                  <td >' . $fila->observacion . '</td>
                      <td >' . $fila->estado . '</td>
                          <td style="text-align:center">' . $boton . '</td>
                   </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen solicitudes creadas</td></tr>");
        }
    }

    /**
     * Construye el código HTML para desplegar la lista de - SolicitudRequerimiento
     */
    public function tablaHtmlSaldos($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_reactivo_laboratorio'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/SolicitudRequerimiento"
		  data-opcion="" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['nombre'] . '</b></td>
                  <td>' . $fila['saldo_bodega'] . '</td>
                  <td style="color:red">' . $fila['saldo_laboratorio'] . '</td>
                  <td>' . $fila['unidad'] . '</td>
                      
                   </tr>');
        }
    }

}

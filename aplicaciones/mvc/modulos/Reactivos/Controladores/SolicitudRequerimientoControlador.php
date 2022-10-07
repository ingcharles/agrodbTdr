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
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class SolicitudRequerimientoControlador extends BaseControlador
{

    private $lNegocioSolicitudRequerimiento = null;
    private $lNegocioReactivosLaboratorios = null;
    private $modeloSolicitudRequerimiento = null;
    private $lNegocioSolicitudCabecera = null;
    private $modeloSolicitudCabecera = null;
    private $lNreactivosLaboratorios = null;
    private $lNegocioReactivosBodega = null;
    private $accion = null;
    private $itemsRequeridos;
    private $itemsReactivos = array();
    private $idSolicitudCabecera;
    private $opcion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioReactivosLaboratorios = new ReactivosLaboratoriosLogicaNegocio();
        $this->lNegocioSolicitudRequerimiento = new SolicitudRequerimientoLogicaNegocio();
        $this->modeloSolicitudRequerimiento = new SolicitudRequerimientoModelo();
        $this->lNreactivosLaboratorios = new ReactivosLaboratoriosLogicaNegocio();
        $this->lNegocioSolicitudCabecera = new SolicitudCabeceraLogicaNegocio();
        $this->modeloSolicitudCabecera = new SolicitudCabeceraModelo();
        $this->lNegocioReactivosBodega = new ReactivosBodegaLogicaNegocio();
        parent::__construct();

        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        if (isset($_POST['opcion']))
        {
            $_SESSION['opcion'] = $_POST['opcion'];
        } else if (isset($_SESSION['opcion']))
        {
            $_POST['opcion'] = $_SESSION['opcion'];
        }

        $this->opcion = "Solicitudes";
        //buscar todas la solicitudes de todos los laboratorios
        $arrayParametros = array(
            'id_laboratorios_provincia' => $this->laboratoriosProvincia()   //todos los laboratorios del usuario
        );
        $buscaSolicitudRequerimiento = $this->lNegocioSolicitudCabecera->buscarSolicitudesTodas($arrayParametros);
        $this->tablaHtmlSolicitudCabecera($buscaSolicitudRequerimiento);
        require APP . 'Reactivos/vistas/listaSolicitudCabeceraVista.php';
    }

    /**
     * Construye la tabla con los datos del reactivo
     * @param type $filtro
     */
    public function buscarReactivo($filtro)
    {
        if (empty($filtro))
        {
            exit();
        }
        $contador = 0;
        $reactivosBodega = new \Agrodb\Reactivos\Modelos\ReactivosBodegaLogicaNegocio();
        $resultado = $reactivosBodega->buscarReactivosSaldos(1, $filtro);
        $html = '<ul id="listaReactivo">';

        $html = '<table id="tablaItems" >
        <thead><tr>
            <th>#</th>
            <th>Reactivos</th>
            <th>Saldo Bodega</th>
            <th>Saldo Laboratorio</th>
            <th>Unidad</th>
            <th>Cantidad</th>
            <th>Solicitar</th>
        </tr></thead><tbody>';
        $filas = "";
        foreach ($resultado as $fila)
        {
            $filas.='<tr><td>' . ++$contador . '</td>';
            $filas.='<td>' . $fila['nombre'] . '</td>';
            $filas.='<td>' . $fila['saldo_bodega'] . '</td>';
            $filas.='<td>' . $fila['saldo_laboratorio'] . '</td>';
            $filas.='<td>' . $fila['unidad'] . '</td>';
            $filas.='<td><input type ="number" id="cantidad' . $fila['id_reactivo_bodega'] . '" size="12"/></td>';
            $filas.='<td><input type="button" id="boton" value="Agregar" onClick="fn_agregar(' . $fila['id_reactivo_bodega'] . ')"></td></tr>';
        }
        $html.= $filas . '</tbody></table>';
        echo $html;
    }

    /**
     * Muestra en el panel izquierdo la lista de reactivos del laboratorio para agregar a la solicitud
     * al panel derecho
     */
    public function nuevo()
    {
        $this->accion = "Nueva solicitud";
        require APP . 'Reactivos/vistas/formularioSolicitudRequerimientoVista.php';
    }

    /**
     * Muestra las lista de reactivos bodega según la bodega
     */
    public function listarReactivosBodega()
    {
        //Buscamos los reactivos según la bodega seleccionada
        $this->tablaHtmlReactivosBodega($_POST['id_bodega'], $_POST['id_laboratorios_provincia'], $_POST['id_laboratorio'], $_POST['nombre']);
        //Buscamos si existe una solicitud activa
        $arrayParametros = array(
            'id_bodega' => $_POST['id_bodega'],
            'id_laboratorios_provincia' => $_POST['id_laboratorios_provincia'],
            'estado' => 'ACTIVO');
        $buscaSolicitudRequerimiento = $this->lNegocioSolicitudCabecera->buscarSolicitudes($arrayParametros);
        $fila = $buscaSolicitudRequerimiento->current();
        $this->idSolicitudCabecera = 0;
        $this->itemsRequeridos = "";
        if (!empty($fila->id_solicitud_cabecera))
        {
            $this->accion = "Solicitud de Requerimiento Pendiente";
            $this->idSolicitudCabecera = $fila->id_solicitud_cabecera;
            $this->tablaHtmlSolicitudRequerimiento($fila->id_solicitud_cabecera);
        } else
        {
            $this->accion = "Nueva Solicitud de Requerimiento";
        }
        echo json_encode(array('itemsReactivos' => $this->itemsReactivos, 'itemsRequeridos' => $this->itemsRequeridos));
    }

    /**
     * Creamos una solicitud para el laboratorio de forma automática y la mantenemos activa 
     * hasta que sea envida, por lo que el usuario podrá seguir agregando reactivos.
     */
    public function agregar()
    {
        $idSolicitudCabecera = null;
        //Buscamos si existe una solicitud activa de este laboratorio
        $arrayParametros = array(
            'id_bodega' => $_POST['id_bodega'],
            'id_laboratorios_provincia' => $_POST['id_laboratorios_provincia'],
            'estado' => 'ACTIVO');
        $buscaSolicitudRequerimiento = $this->lNegocioSolicitudCabecera->buscarSolicitudes($arrayParametros);
        $fila = $buscaSolicitudRequerimiento->current();
        if (empty($fila->id_solicitud_cabecera))
        {
            $this->accion = "Nueva Solicitud de Requerimiento";
            //Creamos la solicitud
            $datosSolicitud = array(
                "fecha_solicitud" => date(DATE_FORMAT),
                "codigo" => "TEMP",
                "id_laboratorio" => $_POST['id_laboratorio'],
                "estado" => "ACTIVO",
                "id_laboratorios_provincia" => $_POST['id_laboratorios_provincia'],
                "id_bodega" => $_POST['id_bodega']);
            $idSolicitudCabecera = $this->lNegocioSolicitudCabecera->guardar($datosSolicitud);
        } else
        {
            //Recuperamos la solicitud activa
            $this->accion = "Editando la Solicitud de Reactivos";
            $idSolicitudCabecera = $fila->id_solicitud_cabecera;
        }
        //verificanos si el reactivo solicitado ya existe en el laboratorio
        $resultadoRL = $this->lNreactivosLaboratorios->buscarLista(array("id_laboratorio" => $_POST['id_laboratorio'], "id_reactivo_bodega" => $_POST["id"]));
        $filaRL = $resultadoRL->current();
        $idReactivoLaboratorio = null;
        if (empty($filaRL->id_reactivo_laboratorio))
        {
            //buscar datos del reactivo bodega
            $buscaReactivoBodega = $this->lNegocioReactivosBodega->buscar($_POST["id"]);
            //Registramos el reactivo solicitado en laboratorio en caso de no existir
            $datosReactivos = array(
                "id_laboratorio" => $_POST['id_laboratorio'],
                "id_reactivo_bodega" => $_POST["id"],
                "nombre" => $buscaReactivoBodega->getNombre(),
                "unidad_medida" => $buscaReactivoBodega->getUnidad(),
                "id_laboratorios_provincia" => $_POST['id_laboratorios_provincia']);
            $idReactivoLaboratorio = $this->lNreactivosLaboratorios->guardar($datosReactivos);
        } else
        {
            $idReactivoLaboratorio = $filaRL->id_reactivo_laboratorio;
        }
        $datosRequerimiento = array("id_reactivo_laboratorio" => $idReactivoLaboratorio, "id_solicitud_cabecera" => $idSolicitudCabecera);
        $resultadoRR = $this->modeloSolicitudRequerimiento->buscarLista($datosRequerimiento);
        //Guardamos el detalle de la solicitud
        $filaRR = $resultadoRR->current();
        if (empty($filaRR->id_reactivo_laboratorio))
        {
            $this->lNegocioSolicitudRequerimiento->guardar($datosRequerimiento);
        }
        //recuperamos todos los reactivos utilizados
        $this->tablaHtmlSolicitudRequerimiento($idSolicitudCabecera);
        //Buscamos los reactivos según la bodega
        $this->tablaHtmlReactivosBodega($_POST['id_bodega'], $_POST['id_laboratorios_provincia'], $_POST['id_laboratorio'], $_POST['nombre']);

        $this->modeloSolicitudCabecera = $this->modeloSolicitudCabecera->buscar($idSolicitudCabecera);
        require APP . 'Reactivos/vistas/formularioSolicitudRequerimientoVista.php';
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
     * Actualizar registros 
     */
    public function listaActualizar()
    {
        //buscar todas la solicitudes de todos los laboratorios
        $arrayParametros = array(
            'id_laboratorios_provincia' => $this->laboratoriosProvincia()   //todos los laboratorios del usuario
        );
        $buscaSolicitudRequerimiento = $this->lNegocioSolicitudCabecera->buscarSolicitudesTodas($arrayParametros);
        $this->tablaHtmlSolicitudCabecera($buscaSolicitudRequerimiento);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: SolicitudRequerimiento
     */
    public function editar($estado)
    {
        $idSolicitudCabecera = $_POST["id"];
        $datosSolicitud = $this->lNegocioSolicitudCabecera->buscar($idSolicitudCabecera);

        $this->tablaHtmlReactivosBodega($datosSolicitud->getIdBodega(), $datosSolicitud->getIdLaboratoriosProvincia(), $datosSolicitud->getIdLaboratorio());   //tabla de reactivos de la bodega

        $this->itemsRequeridos = "";
        $this->modeloSolicitudCabecera = $this->modeloSolicitudCabecera->buscar($idSolicitudCabecera);
        $this->tablaHtmlSolicitudRequerimiento($idSolicitudCabecera, $estado);
        if ($estado == 'ACTIVO')
        {
            $this->accion = "Editar Solicitud";
            require APP . 'Reactivos/vistas/formularioSolicitudRequerimientoVista.php';
        } else
        {
            $this->accion = "Detalle de la Solicitud";
            require APP . 'Reactivos/vistas/lecturaSolicitudRequerimientoVista.php';
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
        $tabla = $this->lNegocioSolicitudRequerimiento->buscarSolicitudRequerimiento($idSolicitudCabecera);
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
                $eliminar = '<button type ="button" class="icono" onclick="eliminarRequerimiento(' . $fila->id_solicitud_requerimiento . ')"></button>';
            }

            $this->itemsRequeridos.=
                    '<tr>
		  <td>' . ++$contador . '</td>
                      <td>' . $fila->codigo_bodega . '</td>
                  <td>' . $fila->nombre . '</td>'
                    . '<td style="text-align: right">' . ($fila->cantidad - $fila->egresos) . '</td>'
                    . '<td style="text-align: center">' . $fila->unidad . '</td>'
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

                if ($fila->tipo == 'SOLICITUD A BODEGA')
                {
                    $opcion = 'data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/SolicitudRequerimiento"
		  data-opcion="editar/' . $fila->estado . '" data-destino="detalleItem"';
                } else
                {
                    if ($fila->estado == 'ACTIVO')
                    {
                        $opcion = 'data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/SolicitudLaboratorio"
		  data-opcion="editar/' . $fila->estado . '" data-destino="listadoItems"';
                    } else
                    {
                        $opcion = 'data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/SolicitudLaboratorio"
		  data-opcion="editar/' . $fila->estado . '" data-destino="detalleItem"';
                    }
                }
                $boton = '<a class="far fa-file-excel fa-2x" href="' . URL . 'Laboratorios/BandejaInformes/reactivos/' . $fila->id_solicitud_cabecera . '" target="_blank"></a>';
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_solicitud_cabecera . '"
                    class="item"' . $opcion . ' ondragstart="drag(event)" draggable="true">
                    <td>' . ++$contador . '</td>
                    <td>' . $fila->laboratorio_solicita . '</td>
                    <td>' . $fila->tipo . '</td>
                    <td>' . $fila->nombre_origen . ' - ' . $fila->provincia_origen . '</td>
                    <td>' . $fila->fecha_solicitud . '</td>
                    <td>' . $fila->observacion . '</td>
                    <td>' . $fila->estado . '</td>
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

    /**
     * Construye el código HTML para desplegar la lista de Reactivos según la bodega seleccionada
     */
    public function tablaHtmlReactivosBodega($idBodega, $idLaboratoriosProvincia, $idLaboratorio, $nombre = null)
    {
        $contador = 0;
        $arrayParametros = array(
            'id_bodega' => $idBodega,
            'nombre' => $nombre);
        $tabla = $this->lNegocioReactivosLaboratorios->buscarReactivosBodegaSaldos($arrayParametros);
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $this->itemsReactivos[] = array(
                    '<tr>
                    <td>' . ++$contador . '</td>
                    <td>' . $fila->provincia_bodega . '</td>
                    <td>' . $fila->nombre_bodega . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>
                    <td>' . $fila->cantidad . '</td>
                    <td>' . $fila->egresos . '</td>
                    <td>' . ($fila->cantidad - $fila->egresos) . '</td>
                    <td>' . $fila->unidad . '</td>
                    <td>' . $fila->estado . '</td>'
                    . "<td>" . "<button class='' value='>' onclick='fn_agregarReaASolicitud(" . $fila->id_reactivo_bodega . "," . $idBodega . "," . $idLaboratoriosProvincia . "," . $idLaboratorio . ")' title='Agregar a la lista'>&gt;&gt;</button>" . "</td>"
                    . '</tr>');
            }
        } else
        {
            $this->itemsReactivos[] = array("<tr><td colspan='5'>No existen reactivos en la Bodega seleccionada.</br> El guardalmacén debe ingresar los reactivos a la bodega.</td></tr>");
        }
    }

    //------------------------
    //------ GUARDALMACEN ----
    //------------------------
    /**
     * Buscar las solicitudes de los laboratorios
     */
    public function verSolicitudesLaboratorios()
    {
        $this->opcion = "Solicitudes de Laboratorios";
        //buscar todas la solicitudes de todos los laboratorios
        $arrayParametros = array(
            'id_bodega' => $this->usuarioBodegas(),
            'estado' => array('SOLICITADO', 'INGRESADO'));
        $buscaSolicitudRequerimiento = $this->lNegocioSolicitudCabecera->buscarSolicitudes($arrayParametros);
        $this->tablaHtmlSolicitudCabecera($buscaSolicitudRequerimiento);
        require APP . 'Reactivos/vistas/listaSolicitudCabeceraVista.php';
    }

}

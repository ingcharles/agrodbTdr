<?php

/**
 * Controlador ReactivosLaboratorios
 *
 * Este archivo controla la lógica del negocio del modelo:  ReactivosSolucionModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ReactivosSolucionControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\ReactivosLaboratoriosLogicaNegocio;
use Agrodb\Reactivos\Modelos\ReactivosSolucionLogicaNegocio;
use Agrodb\Reactivos\Modelos\ReactivosLaboratoriosModelo;
use Agrodb\Reactivos\Modelos\ReactivosSolucionModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class ReactivosSolucionControlador extends BaseControlador
{

    private $lNegocioReactivosLaboratorios = null;
    private $lNegocioReactivosSolucion = null;
    private $modeloReactivosLaboratorios = null;
    private $modeloReactivosSolucion = null;
    private $accion = null;
    private $codEstadoReactivo = null;
    private $listaReactivosLaboratorios = null; //para el combo de reactivos del laboratorio
    private $listaReactivosSolucion;    //tabla html que contiene la lista de reactivos de la solucion

    /**
     * 
     * 
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioReactivosLaboratorios = new ReactivosLaboratoriosLogicaNegocio();
        $this->lNegocioReactivosSolucion = new ReactivosSolucionLogicaNegocio();
        $this->modeloReactivosLaboratorios = new ReactivosLaboratoriosModelo();
        $this->modeloReactivosSolucion = new ReactivosSolucionModelo();
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
        $this->codEstadoReactivo = Constantes::catalogos_rea()->COD_ESTADO;
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $this->accion = "Reactivos de la Soluci&oacute;n";

        //datos de la solucion
        $solucion = $this->lNegocioReactivosLaboratorios->buscar($_POST['id_reactivo_laboratorio']);
        //buscar los reactivos del 
        $buscaReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscarListaReactivos($solucion->getIdLaboratoriosProvincia());
        $array = array();
        foreach ($buscaReactivosLaboratorios as $fila)
        {
            $array[] = array("id" => $fila->id_reactivo_laboratorio, "text" => $fila->nombre);
        }
        $this->listaReactivosLaboratorios = json_encode($array);

        //Buscar los reactivos de la solucion
        $this->modeloReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscar($_POST['id_reactivo_laboratorio']);

        $this->tablaHtmlReactivosSolucion($_POST['id_reactivo_laboratorio']);

        require APP . 'Reactivos/vistas/formularioReactivosSolucionVista.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de - ActividadUso
     */
    public function tablaHtmlReactivosSolucion($idSolucion)
    {
        $buscaReactivosSolucion = $this->lNegocioReactivosSolucion->buscarReactivosSolucion($idSolucion);
        $html = "";
        $contador = 0;
        if (count($buscaReactivosSolucion) > 0)
        {
            foreach ($buscaReactivosSolucion as $fila)
            {
                $html.="<tr id = $idSolucion-$fila->id_reactivo_solucion class='item'"
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Reactivos/ReactivosSolucion'"
                        . 'data-opcion="editar"'
                        . 'data-destino="detalleItem">'
                        . "<td>" . ++$contador . "</td>"
                        . "<td>" . $fila->nombre . "</td>"
                        . "<td style='text-align: center'>" . $fila->unidad_medida . "</td>"
                        . "<td style='text-align: right'>" . $fila->cantidad_requerida . "</td>"
                        . "<td style='text-align: center'>" . $fila->estado_registro . "</td>"
                        . "<td>" . $fila->observacion . "</td>"
                        . "</tr>";
            }
        } else
        {
            $html = "<tr colspan='5'><td colspan='5'>No tiene asignado los reactivos</td></tr>";
        }
        $this->listaReactivosSolucion = $html;
    }

    /**
     * Método para registrar en la base de datos -ReactivosSolucion
     */
    public function guardar()
    {
        $this->lNegocioReactivosSolucion->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar
     */
    public function editar()
    {
        $ids = explode('-', $_POST['id']);
        $idSolucion = $ids[0];
        $idReactivoSolucion = $ids[1];

        //datos de la solucion
        $solucion = $this->lNegocioReactivosLaboratorios->buscar($idSolucion);
        //buscar los reactivos del 
        $buscaReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscarListaReactivos($solucion->getIdLaboratoriosProvincia());
        $array = array();
        foreach ($buscaReactivosLaboratorios as $fila)
        {
            $array[] = array("id" => $fila->id_reactivo_laboratorio, "text" => $fila->nombre);
        }
        $this->listaReactivosLaboratorios = json_encode($array);

        //Buscar los reactivos de la solucion
        $this->modeloReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscar($idSolucion);

        $this->accion = "Editar Reactivo de la Soluci&oacute;n";

        $this->modeloReactivosSolucion = $this->lNegocioReactivosSolucion->buscar($idReactivoSolucion);
        $this->tablaHtmlReactivosSolucion($idSolucion);
        require APP . 'Reactivos/vistas/formularioReactivosSolucionVista.php';
    }

    /**
     * Método para buscar los datos del reactivo
     */
    public function obtenerDatosReactivoLaboratorio($idReactivoLaboratorio)
    {
        $resultado = $this->lNegocioReactivosLaboratorios->buscarReactivos(parent::laboratorioUsuario(), $idReactivoLaboratorio);
        $fila = $resultado->current();
        echo json_encode(array(
            "id" => $fila->id_reactivo_laboratorio,
            "nombre" => $fila->nombre,
            "unidad" => $fila->unidad
        ));
    }

    /**
     * Método para borrar un registro en la base de datos - ReactivosLaboratorios
     */
    public function borrar()
    {
        $this->lNegocioReactivosLaboratorios->borrar($_POST['elementos']);
    }

    /*     * ******************************* */
    /*     * ******** INGRESAR SOLUCION *********** */
    /*     * ******************************* */
    /*     * Para la opcion Ingresar Solucion */

    /**
     * INGRESAR SOLUCION
     */
    public function ingresarSoluciones()
    {
        require APP . 'Reactivos/vistas/listaIngresoReactivosSolucionVista.php';
    }

    /**
     * INGRESAR SOLUCION
     * Muestra la lista de soluciones
     */
    public function listarSoluciones()
    {
        $arrayParametros = array();
        if (!empty($_POST['nombre']))
        {
            $arrayParametros['nombre'] = $_POST['nombre'];
        }
        $arrayParametros['id_laboratorios_provincia'] = $_POST['id_laboratorios_provincia'];
        $arrayParametros['tipo'] = 'SOLUCION';
        $modeloReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscarReactivosLaboratorios($arrayParametros);
        $this->tablaHtmlReactivosLaboratoriosSoluciones($modeloReactivosLaboratorios);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * INGRESAR SOLUCION
     * Construye el código HTML para desplegar la lista de Reactivos tipo Solucion del Laboratorio
     */
    public function tablaHtmlReactivosLaboratoriosSoluciones($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_reactivo_laboratorio . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Reactivos/ReactivosSolucion"
		  data-opcion="registrarSaldosSolucion" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila->nombre . '</b></td>
                  <td style="text-align: center">' . $fila->unidad_medida . '</td>
                  <td style="text-align: right">' . $fila->volumen_final . '</td>
                  <td style="text-align: center">' . $fila->estado_registro . '</td>
                  <td style="text-align: center">' . $fila->total_reactivos_solucion . '</td>
                 </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

    /**
     * INGRESAR SOLUCION
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ReactivosLaboratorios
     */
    public function registrarSaldosSolucion()
    {
        $this->accion = "Registrar ingreso de Soluci&oacute;n";
        $buscaReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscarReactivos(parent::laboratorioUsuario());
        $array = array();
        foreach ($buscaReactivosLaboratorios as $fila)
        {
            $array[] = array("id" => $fila->id_reactivo_laboratorio, "text" => $fila->nombre);
        }
        $this->listaReactivosLaboratorios = json_encode($array);
        $this->modeloReactivosLaboratorios = $this->lNegocioReactivosLaboratorios->buscar($_POST["id"]);

        $this->tablaHtmlReactivosSolucionLista($_POST["id"]);

        require APP . 'Reactivos/vistas/formularioIngresoReactivosSolucionVista.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de - ActividadUso
     */
    public function tablaHtmlReactivosSolucionLista($idSolucion)
    {
        $lNReactivosSolucion = new ReactivosSolucionLogicaNegocio();
        $buscaReactivosSolucion = $lNReactivosSolucion->buscarReactivosSolucion($idSolucion);
        $html = "";
        $contador = 0;
        if (count($buscaReactivosSolucion) > 0)
        {
            foreach ($buscaReactivosSolucion as $fila)
            {
                $html.="<tr>"
                        . "<td>" . ++$contador . "</td>"
                        . "<td>" . $fila->nombre . "</td>"
                        . "<td style='text-align: center'>" . $fila->unidad_medida . "</td>"
                        . "<td style='text-align: right'>" . $fila->cantidad_requerida . "</td>"
                        . "<td style='text-align: center'>" . $fila->estado_registro . "</td>"
                        . "<td>" . $fila->observacion . "</td>"
                        . "</tr>";
            }
        } else
        {
            $html = "<tr colspan='5'><td colspan='5'>No tiene asignado los reactivos</td></tr>";
        }
        $this->listaReactivosSolucion = $html;
    }

    /**
     * Funcion que permite descontar los reactivos requeridos para la solucion
     */
    public function guardarSaldosLaboratorios()
    {
        $this->lNegocioReactivosSolucion->guardarSaldosLaboratorios($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

}

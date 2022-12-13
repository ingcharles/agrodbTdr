<?php
/**
 * Controlador DatosVehiculos
 *
 * Este archivo controla la lógica del negocio del modelo:  DatosVehiculosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-04-05
 * @uses    DatosVehiculosControlador
 * @package RegistroOperador
 * @subpackage Controladores
 */
namespace Agrodb\RegistroOperador\Controladores;

use Agrodb\RegistroOperador\Modelos\DatosVehiculosLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\DatosVehiculosModelo;
use Agrodb\RevisionFormularios\Modelos\GruposSolicitudesLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\InspeccionLogicaNegocio;

class DatosVehiculosControlador extends BaseControlador
{

    private $lNegocioDatosVehiculos = null;
    private $modeloDatosVehiculos = null;
    private $lNegocioGruposSolicitudes = null;
    private $lNegocioInspeccion = null;
    
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDatosVehiculos = new DatosVehiculosLogicaNegocio();
        $this->modeloDatosVehiculos = new DatosVehiculosModelo();
        $this->lNegocioGruposSolicitudes = new GruposSolicitudesLogicaNegocio();
        $this->lNegocioInspeccion = new InspeccionLogicaNegocio();
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
        $modeloDatosVehiculos = $this->lNegocioDatosVehiculos->buscarDatosVehiculos();
        $this->tablaHtmlDatosVehiculos($modeloDatosVehiculos);
        require APP . 'RegistroOperador/vistas/listaDatosVehiculosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo DatosVehiculos";
        require APP . 'RegistroOperador/vistas/formularioDatosVehiculosVista.php';
    }

    /**
     * Método para registrar en la base de datos -DatosVehiculos
     */
    public function guardar()
    {
        $this->lNegocioDatosVehiculos->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DatosVehiculos
     */
    public function editar()
    {
        $this->accion = "Editar DatosVehiculos";
        $this->modeloDatosVehiculos = $this->lNegocioDatosVehiculos->buscar($_POST["id"]);
        require APP . 'RegistroOperador/vistas/formularioDatosVehiculosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DatosVehiculos
     */
    public function borrar()
    {
        $this->lNegocioDatosVehiculos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DatosVehiculos
     */
    public function tablaHtmlDatosVehiculos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_dato_vehiculo'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroOperador\datosvehiculos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_dato_vehiculo'] . '</b></td>
<td>' . $fila['id_area'] . '</td>
<td>' . $fila['id_tipo_operacion'] . '</td>
<td>' . $fila['id_marca_vehiculo'] . '</td>
</tr>'
                );
            }
        }
    }

    /*
     * Funcion de proceso de generación de checklist en inspecciones de aplicativo móvil
     */
    public function paGenerarChecklistMedioTransporteAplicativoMovil()
    {
        $fecha = date("Y-m-d h:m:s");
        $estadoChecklist = "generado";

        echo "\n" . 'Proceso Automático de solicitudes de medio trasnporte' . $fecha . "\n" . "\n";

        echo "\n" . 'Inicio generación de checklist inspección' . "\n" . "\n";

        //$solicitudes = $this->lNegocioDatosVehiculos->obtenerSolicitudesPorGenerarChecklist();
        $arrayParametros = array('origen_inspeccion' => 'aplicativoMovil', 'estado_checklist' => 'generar', 'estado_dato_vehiculo' => 'activo');
        
        $solicitudes = $this->lNegocioDatosVehiculos->buscarLista($arrayParametros);
        
        foreach ($solicitudes as $fila) {

            // Se genera el archivo .pdf del checklist
            $idSolicitud = $fila['id_dato_vehiculo'];
            $nombreArchivo = md5(rand() . $idSolicitud);
            $rutaChecklist = $this->lNegocioDatosVehiculos->generarChecklistInspeccionMedioTransporte($idSolicitud, $nombreArchivo);

            $arrayActualizarRutaChecklist = array(
                'id_dato_vehiculo' => $idSolicitud,
                'estado_checklist' => $estadoChecklist
            );

            $this->lNegocioDatosVehiculos->guardar($arrayActualizarRutaChecklist);
            
            $arrayActualizarRutaChecklist = array(
                'id_operador_tipo_operacion' => $fila['id_operador_tipo_operacion'],
                'estado' => 'Técnico'
            );
            
            $qInspeccion = $this->lNegocioGruposSolicitudes->obtenerMaximoInspeccionGrupoSolicitud($arrayActualizarRutaChecklist);
            $idInspeccion = $qInspeccion->current()->id_inspeccion;
            
            if(isset($idInspeccion)){
                $this->lNegocioInspeccion->guardar(array('id_inspeccion' => $idInspeccion, 'ruta_archivo' => $rutaChecklist));
                $this->lNegocioDatosVehiculos->enviarCorreoInspeccionMdt($idSolicitud, $rutaChecklist, $fila['id_operador_tipo_operacion']);
            }
            echo 'Se generó el checklist del medio de transporte ' . $fila['id_dato_vehiculo'] . "\n";
        }

        echo "\n" . 'Fin de generación de medio de transporte' . "\n" . "\n";
    }
}

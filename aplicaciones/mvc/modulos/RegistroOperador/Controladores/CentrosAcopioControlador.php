<?php
/**
 * Controlador CentrosAcopio
 *
 * Este archivo controla la lógica del negocio del modelo:  CentrosAcopioModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-04-05
 * @uses    CentrosAcopioControlador
 * @package RegistroOperador
 * @subpackage Controladores
 */
namespace Agrodb\RegistroOperador\Controladores;

use Agrodb\RegistroOperador\Modelos\CentrosAcopioLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\CentrosAcopioModelo;
use Agrodb\RevisionFormularios\Modelos\GruposSolicitudesLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\InspeccionLogicaNegocio;

class CentrosAcopioControlador extends BaseControlador
{

    private $lNegocioCentrosAcopio = null;
    private $modeloCentrosAcopio = null;
    private $lNegocioGruposSolicitudes = null;
    private $lNegocioInspeccion = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCentrosAcopio = new CentrosAcopioLogicaNegocio();
        $this->modeloCentrosAcopio = new CentrosAcopioModelo();
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
        $modeloCentrosAcopio = $this->lNegocioCentrosAcopio->buscarCentrosAcopio();
        $this->tablaHtmlCentrosAcopio($modeloCentrosAcopio);
        require APP . 'RegistroOperador/vistas/listaCentrosAcopioVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo CentrosAcopio";
        require APP . 'RegistroOperador/vistas/formularioCentrosAcopioVista.php';
    }

    /**
     * Método para registrar en la base de datos -CentrosAcopio
     */
    public function guardar()
    {
        $this->lNegocioCentrosAcopio->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: CentrosAcopio
     */
    public function editar()
    {
        $this->accion = "Editar CentrosAcopio";
        $this->modeloCentrosAcopio = $this->lNegocioCentrosAcopio->buscar($_POST["id"]);
        require APP . 'RegistroOperador/vistas/formularioCentrosAcopioVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - CentrosAcopio
     */
    public function borrar()
    {
        $this->lNegocioCentrosAcopio->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - CentrosAcopio
     */
    public function tablaHtmlCentrosAcopio($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_centro_acopio'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroOperador\centrosacopio"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_centro_acopio'] . '</b></td>
<td>' . $fila['id_area'] . '</td>
<td>' . $fila['id_tipo_operacion'] . '</td>
<td>' . $fila['capacidad_instalada'] . '</td>
</tr>'
                );
            }
        }
    }
    
    /*
     * Funcion de proceso de generación de checklist en inspecciones de aplicativo móvil
     */
    public function paGenerarChecklistCentroAcopioAplicativoMovil()
    {
        $fecha = date("Y-m-d h:m:s");
        $estadoChecklist = "generado";
        
        echo "\n" . 'Proceso Automático de solicitudes de centro de acopio ' . $fecha . "\n" . "\n";
        
        echo "\n" . 'Inicio generación de checklist inspección' . "\n" . "\n";
        
        $arrayParametros = array('origen_inspeccion' => 'aplicativoMovil', 'estado_checklist'=>'generar', 'estado_centro_acopio' => 'activo');
        
        $solicitudes = $this->lNegocioCentrosAcopio->buscarLista($arrayParametros);
        
        foreach ($solicitudes as $fila) {
 
            // Se genera el archivo .pdf del checklist
            $idSolicitud = $fila['id_centro_acopio'];
            $nombreArchivo = md5(rand() . $idSolicitud);
            $rutaChecklist = $this->lNegocioCentrosAcopio->generarChecklistInspeccionCentroAcopio($fila['id_centro_acopio'], $nombreArchivo);
            
            $arrayActualizarEstadoChecklist = array(
                'id_centro_acopio' => $idSolicitud,
                'estado_checklist' => $estadoChecklist
            );
            
            $this->lNegocioCentrosAcopio->guardar($arrayActualizarEstadoChecklist);
            
            $arrayActualizarRutaChecklist = array(
                'id_operador_tipo_operacion' => $fila['id_operador_tipo_operacion'],
                'estado' => 'Técnico'
            );
            
            $qInspeccion = $this->lNegocioGruposSolicitudes->obtenerMaximoInspeccionGrupoSolicitud($arrayActualizarRutaChecklist);
            $idInspeccion = $qInspeccion->current()->id_inspeccion;
            
            if(isset($idInspeccion)){
                $this->lNegocioInspeccion->guardar(array('id_inspeccion' => $idInspeccion, 'ruta_archivo' => $rutaChecklist));
                $this->lNegocioCentrosAcopio->enviarCorreoInspeccionAco($idSolicitud, $rutaChecklist, $fila['id_operador_tipo_operacion']);
            }
            
            echo 'Se generó el checklist del centro de acopio ' . $fila['id_centro_acopio'] . "\n";
        }
        
        echo "\n" . 'Fin de generación de medio de centro de acopio' . "\n" . "\n";
    }
        
}

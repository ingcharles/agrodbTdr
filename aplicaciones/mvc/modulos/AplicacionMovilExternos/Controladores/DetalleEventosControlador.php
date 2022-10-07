<?php
/**
 * Controlador DetalleEventos
 *
 * Este archivo controla la lógica del negocio del modelo:  DetalleEventosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-12-24
 * @uses    DetalleEventosControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\AplicacionMovilExternos\Modelos\DetalleEventosLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\DetalleEventosModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class DetalleEventosControlador extends BaseControlador
{

    private $lNegocioDetalleEventos = null;
    private $modeloDetalleEventos = null;

    private $accion = null;
    private $archivo = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDetalleEventos = new DetalleEventosLogicaNegocio();
        $this->modeloDetalleEventos = new DetalleEventosModelo();
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
        $modeloDetalleEventos = $this->lNegocioDetalleEventos->buscarDetalleEventos();
        $this->tablaHtmlDetalleEventos($modeloDetalleEventos);
        
        require APP . 'AplicacionMovilExternos/vistas/listaDetalleEventosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo DetalleEventos";
        
        require APP . 'AplicacionMovilExternos/vistas/formularioDetalleEventosVista.php';
    }

    /**
     * Método para registrar en la base de datos -DetalleEventos
     */
    public function guardar()
    {
        $arrayParametros = array(   'id_evento' =>  $_POST['id_evento_campania'],
                                    'nombre_evento' =>  $_POST['nombre_campania'],
                                    'evento' =>  $_POST['evento_campania'],
                                    'ruta_imagen' =>  $_POST['ruta_imagen_campania'],
                                    'ruta_recurso' => $_POST['ruta_recurso_campania']
                                 );
            
        $this->lNegocioDetalleEventos->guardar($arrayParametros);      
        
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }
    
    /**
     * Método para actualizar estado del registro -DetalleEventos
     */
    public function actualizarEstado()
    {
        
        $arrayParametros = array(   'id_detalle_evento' =>  $_POST['id_detalle_evento'],
                                    'estado' =>  ($_POST['estado']=='activo'?'inactivo':'activo')
                                );
        
        $this->lNegocioDetalleEventos->guardar($arrayParametros);
        
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DetalleEventos
     */
    public function editar()
    {
        $this->accion = "Editar DetalleEventos";
        
        $this->modeloDetalleEventos = $this->lNegocioDetalleEventos->buscar($_POST["id"]);
        require APP . 'AplicacionMovilExternos/vistas/formularioDetalleEventosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DetalleEventos
     */
    public function borrar()
    {
        $this->modeloDetalleEventos = $this->lNegocioDetalleEventos->buscar($_POST["elementos"]);
        
        $this->lNegocioDetalleEventos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DetalleEventos
     */
    public function tablaHtmlDetalleEventos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_detalle_evento'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'AplicacionMovilExternos/detalleEventos"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		    <td>' . ++ $contador . '</td>
            		    <td style="white - space:nowrap; "><b>' . $fila['id_detalle_evento'] . '</b></td>
                        <td>' . $fila['id_evento'] . '</td>
                        <td>' . $fila['nombre_evento'] . '</td>
                        <td>' . $fila['evento'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Método para listar los eventos registrados
     */
    public function construirDetalleEventos($idEvento)
    {
        $query = "id_evento = $idEvento";
        
        $listaDetalles = $this->lNegocioDetalleEventos->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .=
            '<tr>
                        <td>' . $i++. '</td>
                        <td>' . ($fila['nombre_evento'] != '' ? $fila['nombre_evento'] : ''). '</td>
                        <td>' . ($fila['evento'] != '' ? $fila['evento'] : '') . '</td>
                        <td>' . ($fila['ruta_imagen'] != '' ? '<a href="'.$fila['ruta_imagen'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Imagen</a>' : '<span class="alerta">No hay imagen</span>'). '</td>
                        <td>' . ($fila['ruta_recurso'] != '' ? '<a href="'.$fila['ruta_recurso'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Enlace</a>' : '<span class="alerta">No hay enlace</span>'). '</td>
                        <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" onclick="fn_eliminarDetalle(' . $fila['id_detalle_evento'] . '); return false;"/></td>
                        <td class="'.strtolower($fila['estado']).'"><button type="button" name="actualizar" id="actualizar" class="icono" onclick="fn_actualizarEstadoDetalle('.'\'' . $fila['estado'] . '\','. $fila['id_detalle_evento'] . '); return false;"/></td>
                    </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}
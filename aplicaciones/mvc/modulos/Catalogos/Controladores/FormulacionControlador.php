<?php
/**
 * Controlador Formulacion
 *
 * Este archivo controla la lógica del negocio del modelo:  FormulacionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-23
 * @uses    FormulacionControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\FormulacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\FormulacionModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class FormulacionControlador extends BaseControlador
{

    private $lNegocioFormulacion = null;
    private $modeloFormulacion = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioFormulacion = new FormulacionLogicaNegocio();
        $this->modeloFormulacion = new FormulacionModelo();
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
        
    }
    
    /**
     * Método de inicio del controlador
     */
    public function listarAdministracionFormulacion()
    {
        $this->cargarPanelFormulacion();
        
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'Formulacion/nuevo',
                'ruta' => URL_MVC_FOLDER . 'Catalogos',
                'descripcion' => 'Nuevo'
            ),
            array(
                'estilo' => '_actualizar',
                'pagina' => '',
                'ruta' => '',
                'descripcion' => 'Actualizar'
            ),
            array(
                'estilo' => '_seleccionar',
                'pagina' => '',
                'ruta' => '',
                'descripcion' => 'Seleccionar'
            )
        );
        
        $this->listaBotones = $this->crearAccionBotonesListadoItems($opciones);
        
        require APP . 'Catalogos/vistas/listaFormulacionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->formulario = "nuevo";
        
        $this->accion = "Nueva Forma Física, Farmacéutica, Cosmética (Formulación)";
        require APP . 'Catalogos/vistas/formularioFormulacionVista.php';
    }

    /**
     * Método para registrar en la base de datos -Formulacion
     */
    public function guardar()
    {
        $this->lNegocioFormulacion->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Formulacion
     */
    public function editar()
    {
        $this->accion = "Editar Forma Física, Farmacéutica, Cosmética (Formulación)";
        $this->modeloFormulacion = $this->lNegocioFormulacion->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioFormulacionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Formulacion
     */
    public function borrar()
    {
        $this->lNegocioFormulacion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Formulacion
     */
    public function tablaHtmlFormulacion($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_formulacion'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/Formulacion"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['formulacion'] . '</b></td>
                    <td>' . $fila['id_area'] . '</td>
                    <td>' . $fila['estado_formulacion'] . '</td>
                </tr>'
            );
        }
    }
    
    public function actualizarFormulacion()
    {
        $modeloFormulacion = $this->lNegocioFormulacion->buscarFormulacion();
        $this->tablaHtmlFormulacion($modeloFormulacion);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Usos por área
     */
    public function cargarPanelFormulacion()
    {
        
        $this->panelBusquedaFormulacion = '<table class="filtro" style="width: 100%;">
            
                                                <tbody>
                                					<tr  style="width: 100%;">
                                						<td >*Área: </td>
                                						<td >
                                                            <select id="idArea" name="idArea" required style="width: 100%">' .
                                                            $this->comboAreasRegistroInsumosAgropecuarios() .
                                                            '</select>
                                						</td>
                                                    </tr>
                                                                
                                                    <tr  style="width: 100%;">
                                						<td >*Estado: </td>
                                						<td >
                                                            <select id="estadoIA" name="estadoIA" required style="width: 100%">' .
                                                            $this->comboActivoInactivo('Activo') .
                                                            '</select>
                                						</td>
                                                    </tr>
                                					<tr>
                                						<td colspan="2" style="text-align: end;">
                                							<button id="btnFiltrar">Filtrar</button>
                                						</td>
                                					</tr>
                                				</tbody>
                                			</table>';
    }
    
    /**
     * Método para listar las formulaciones filtradas
     */
    public function listarFormulacionesFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idArea = $_POST["idArea"];
        $estadoIA = $_POST["estadoIA"];
        
        $arrayParametros = array(
            'id_area' => $idArea,
            'estado_formulacion' => $estadoIA
        );
        
        $modeloUso = $this->lNegocioFormulacion->buscarFormulacionesXFiltro($arrayParametros);
        
        $this->tablaHtmlFormulacion($modeloUso);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
}
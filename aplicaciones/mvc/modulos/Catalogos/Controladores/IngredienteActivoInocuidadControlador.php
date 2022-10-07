<?php
/**
 * Controlador IngredienteActivoInocuidad
 *
 * Este archivo controla la lógica del negocio del modelo:  IngredienteActivoInocuidadModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    IngredienteActivoInocuidadControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\IngredienteActivoInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\IngredienteActivoInocuidadModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class IngredienteActivoInocuidadControlador extends BaseControlador
{

    private $lNegocioIngredienteActivoInocuidad = null;
    private $modeloIngredienteActivoInocuidad = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioIngredienteActivoInocuidad = new IngredienteActivoInocuidadLogicaNegocio();
        $this->modeloIngredienteActivoInocuidad = new IngredienteActivoInocuidadModelo();
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
    public function listarAdministracionIngredienteActivo()
    {
        $this->cargarPanelIngredienteActivo();
                
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'IngredienteActivoInocuidad/nuevo',
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
        
        /*$modeloIngredienteActivoInocuidad = $this->lNegocioIngredienteActivoInocuidad->buscarIngredienteActivoInocuidad();
        $this->tablaHtmlIngredienteActivoInocuidad($modeloIngredienteActivoInocuidad);*/
        require APP . 'Catalogos/vistas/listaIngredienteActivoInocuidadVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->formulario = "nuevo";
        
        $this->accion = "Nuevo Nombre de Componente / Ingrediente Activo";
        require APP . 'Catalogos/vistas/formularioIngredienteActivoInocuidadVista.php';
    }

    /**
     * Método para registrar en la base de datos -IngredienteActivoInocuidad
     */
    public function guardar()
    {
        $this->lNegocioIngredienteActivoInocuidad->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: IngredienteActivoInocuidad
     */
    public function editar()
    {
        $this->formulario = "abrir";
        
        $this->accion = "Editar Nombre de Componente / Ingrediente Activo";
        $this->modeloIngredienteActivoInocuidad = $this->lNegocioIngredienteActivoInocuidad->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioIngredienteActivoInocuidadVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - IngredienteActivoInocuidad
     */
    public function borrar()
    {
        $this->lNegocioIngredienteActivoInocuidad->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - IngredienteActivoInocuidad
     */
    public function tablaHtmlIngredienteActivoInocuidad($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_ingrediente_activo'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/IngredienteActivoInocuidad"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
        		    <td style="white - space:nowrap; "><b>' . $fila['id_area'] . '</b></td>
                    <td>' . $fila['ingrediente_activo'] . '</td>
                    <td>' . $fila['estado_ingrediente_activo'] . '</td>
                </tr>'
            );
        }
    }
    
    public function actualizarIngredienteActivo()
    {
        $modeloIngredienteActivo = $this->lNegocioIngredienteActivoInocuidad->buscarIngredienteActivoInocuidad();
        $this->tablaHtmlIngredienteActivoInocuidad($modeloIngredienteActivo);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Ingredientes activos por área
     */
    public function cargarPanelIngredienteActivo()
    {
        
        $this->panelBusquedaIngredienteActivo = '<table class="filtro" style="width: 100%;">

                                                <tbody>                                            
                                					<tr  style="width: 100%;">
                                						<td >*Área: </td>
                                						<td >
                                                            <select id="idArea" name="idArea" required style="width: 100%">' .
                                                            $this->comboAreasRegistroInsumosPecuarios() .
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
     * Método para listar los ingredientes activos filtrados
     */
    public function listarIngredientesActivosFiltrados()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idArea = $_POST["idArea"];
        $estadoIA = $_POST["estadoIA"];
        
        $arrayParametros = array(
            'id_area' => $idArea,
            'estado_ingrediente_activo' => $estadoIA
            );
        
        $modeloIngredienteActivo = $this->lNegocioIngredienteActivoInocuidad->buscarIngredienteActivoXFiltro($arrayParametros);
        
        $this->tablaHtmlIngredienteActivoInocuidad($modeloIngredienteActivo);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
}
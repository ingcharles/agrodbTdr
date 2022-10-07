<?php
/**
 * Controlador Usos
 *
 * Este archivo controla la lógica del negocio del modelo:  UsosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    UsosControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\UsosLogicaNegocio;
use Agrodb\Catalogos\Modelos\UsosModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class UsosControlador extends BaseControlador
{

    private $lNegocioUsos = null;
    private $modeloUsos = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioUsos = new UsosLogicaNegocio();
        $this->modeloUsos = new UsosModelo();
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
    public function listarAdministracionUsos()
    {
        $this->cargarPanelUsos();
        
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'Usos/nuevo',
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
        
        /*$modeloUsos = $this->lNegocioUsos->buscarUsos();
        $this->tablaHtmlUsos($modeloUsos);*/
        require APP . 'Catalogos/vistas/listaUsosVista.php';
    }
    

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->formulario = "nuevo";
        
        $this->accion = "Nuevo Uso";
        require APP . 'Catalogos/vistas/formularioUsosVista.php';
    }

    /**
     * Método para registrar en la base de datos -Usos
     */
    public function guardar()
    {
        $this->lNegocioUsos->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Usos
     */
    public function editar()
    {
        $this->formulario = "abrir";
        
        $this->accion = "Editar Uso";
        $this->modeloUsos = $this->lNegocioUsos->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioUsosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Usos
     */
    public function borrar()
    {
        $this->lNegocioUsos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Usos
     */
    public function tablaHtmlUsos($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_uso'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/usos"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_area'] . '</b></td>
                    <td>' . $fila['nombre_uso'] . '</td>
                    <td>' . $fila['nombre_comun_uso'] . '</td>
                    <td>' . $fila['estado_uso'] . '</td>
                </tr>'
            );
        }
    }
    
    public function actualizarUso()
    {
        $modeloUso = $this->lNegocioUsos->buscarUsos();
        $this->tablaHtmlUsos($modeloUso);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Usos por área
     */
    public function cargarPanelUsos()
    {
        
        $this->panelBusquedaUsos = '<table class="filtro" style="width: 100%;">
            
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
    public function listarUsosFiltrados()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idArea = $_POST["idArea"];
        $estadoIA = $_POST["estadoIA"];
        
        $arrayParametros = array(
            'id_area' => $idArea,
            'estado_uso' => $estadoIA
        );
        
        $modeloUso = $this->lNegocioUsos->buscarUsosXFiltro($arrayParametros);
        
        $this->tablaHtmlUsos($modeloUso);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
}
<?php
/**
 * Controlador UsuariosVentanilla
 *
 * Este archivo controla la lógica del negocio del modelo:  UsuariosVentanillaModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-02-13
 * @uses    UsuariosVentanillaControlador
 * @package SeguimientoDocumental
 * @subpackage Controladores
 */
namespace Agrodb\SeguimientoDocumental\Controladores;

use Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaModelo;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\GUath\Modelos\FichaEmpleadoModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class UsuariosVentanillaControlador extends BaseControlador
{

    private $lNegocioUsuariosVentanilla = null;
    private $modeloUsuariosVentanilla = null;
    
    private $lNegocioFichaEmpleado = null;
    private $modeloFichaEmpleado = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioUsuariosVentanilla = new UsuariosVentanillaLogicaNegocio();
        $this->modeloUsuariosVentanilla = new UsuariosVentanillaModelo();
        
        $this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();
        $this->modeloFichaEmpleado = new FichaEmpleadoModelo();
        
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
        require APP . 'SeguimientoDocumental/vistas/listaOpcionesAdministracion.php';
    }

    /**
     * Método de inicio del controlador
     */
    public function listarAdministracionUsuariosVentana()
    {
        $this->cargarPanelUsuariosVentanilla();

        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'usuariosVentanilla/nuevo',
                'ruta' => URL_MVC_FOLDER . 'SeguimientoDocumental',
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
        require APP . 'SeguimientoDocumental/vistas/listaUsuariosVentanillaVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Usuario Ventanilla";
        $this->formulario = 'nuevo';
        require APP . 'SeguimientoDocumental/vistas/formularioUsuariosVentanillaVista.php';
    }

    /**
     * Método para registrar en la base de datos -UsuariosVentanilla
     */
    public function guardar()
    {
    	$this->lNegocioUsuariosVentanilla->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: UsuariosVentanilla
     */
    public function editar()
    {
        $this->accion = "Editar UsuariosVentanilla";
        $this->formulario = 'abrir';
        $this->modeloUsuariosVentanilla = $this->lNegocioUsuariosVentanilla->buscar($_POST["id"]);
        
        require APP . 'SeguimientoDocumental/vistas/formularioUsuariosVentanillaVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - UsuariosVentanilla
     */
    public function borrar()
    {
        $this->lNegocioUsuariosVentanilla->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - UsuariosVentanilla
     */
    public function tablaHtmlUsuariosVentanilla($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_usuario_ventanilla'] . '"
                        		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'SeguimientoDocumental\usuariosVentanilla"
                        		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                        		  data-destino="detalleItem">
                        		  <td>' . ++ $contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['ventanilla'] . '</b></td>
                        <td>' . $fila['apellido'] . ' ' . $fila['nombre'] . '</td>
                        <td>' . $fila['perfil'] . '</td>
                        <td>' . $fila['estado_usuarios_ventanilla'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Construye el combo para desplegar la lista de Ventanilla
     */
    public function comboVentanillasSeguimientoDocumental($idVentanilla=null)
    {
        $comboVentanilla = "";
        $ventanilla = $this->lNegocioUsuariosVentanilla->buscarVentanillasEstado("Activo");
        
        foreach ($ventanilla as $item)
        {
            if ($idVentanilla == $item['id_ventanilla'])
            {
                $comboVentanilla .= '<option value="' . $item->id_ventanilla . '" data-unidad="'. $item->unidad_destino .'" selected >' . $item->nombre . '</option>';
            } else
            {
                $comboVentanilla .= '<option value="' . $item->id_ventanilla . '" data-unidad="'. $item->unidad_destino .'">' . $item->nombre . '</option>';
            }
        }
        return $comboVentanilla;
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Usuarios por Ventanilla
     */
    public function cargarPanelUsuariosVentanilla()
    {
        $this->panelBusquedaUsuariosVentanilla = '<table class="filtro" style="width: 400px;">
                                    				<tbody>
                                                        <tr>
                                                            <th colspan="2">Buscar:</th>
                                                        </tr>
                                    					<tr >
                                    						<td >Ventanilla: </td>
                                    						<td style="width: 100%;">
                                                                <select id="idVentanilla" name="idVentanilla" required style="width: 100%;">'. 
                                                                    $this->comboVentanillasSeguimientoDocumental() . 
                                                                '</select>
                                    						</td>
                                    					</tr>
                                                        <tr></tr>
                                    					<tr>
                                    						<td colspan="3">
                                    							<button id="btnFiltrar">Filtrar lista</button>
                                    						</td>
            
                                    					</tr>
                                    				</tbody>
                                    			</table>';
        
    }
    
    /**
     * Método para listar los usuarios por ventanilla
     * */
    public function listarUsuariosVentanilla()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idVentanilla = $_POST["idVentanilla"];
        $arrayParametros = array('id_ventanilla' => $idVentanilla);
        $usuarios = $this->lNegocioUsuariosVentanilla->buscarUsuariosVentanillaDatos($arrayParametros);
        
        $this->tablaHtmlUsuariosVentanilla($usuarios);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);

        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    /**
     * Método para el nombre del usuario
     * */
    public function obtenerNombreUsuarioTecnico($identificador)
    {
        $validacion = "Fallo";
    	$nombre="El número de cédula no existe.";
        
        $usuario = $this->datosFuncionario($identificador);

		$datos = $usuario->apellido .' '. $usuario->nombre;
		
		if(strlen(trim($datos)) > 0){
			$nombre = $datos;
			$validacion = "Exito";
		}

		echo json_encode(array('nombre' => $nombre, 'validacion' => $validacion));
    }
}

<?php
/**
 * Controlador Noticias
 *
 * Este archivo controla la lógica del negocio del modelo:  NoticiasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-12-24
 * @uses    NoticiasControlador
 * @package AplicacionWeb
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\AplicacionMovilExternos\Modelos\NoticiasLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\NoticiasModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class NoticiasControlador extends BaseControlador
{

    private $lNegocioNoticias = null;
    private $modeloNoticias = null;

    private $accion = null;    

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioNoticias = new NoticiasLogicaNegocio();
        $this->modeloNoticias = new NoticiasModelo();
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

        $fecha = date("Y-m-d");		
		$modeloNoticias = $this->lNegocioNoticias->buscarLista("fecha_noticia>='$fecha'");
        $this->tablaHtmlNoticias($modeloNoticias);
        $this->cargarPanelNoticias();
        require APP . 'AplicacionMovilExternos/vistas/listaNoticiasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Noticia";
        $this->formulario = 'nuevo';
        
        require APP . 'AplicacionMovilExternos/vistas/formularioNoticiasVista.php';
    }

    /**
     * Método para registrar en la base de datos -Noticias
     */
    public function guardar()
    {
        $this->lNegocioNoticias->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Noticias
     */
    public function editar()
    {
        $this->accion = "Editar Noticias";
        $this->formulario = 'abrir';
        
        $this->modeloNoticias = $this->lNegocioNoticias->buscar($_POST["id"]);
        require APP . 'AplicacionMovilExternos/vistas/formularioNoticiasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Noticias
     */
    public function borrar()
    {
        $this->lNegocioNoticias->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Noticias
     */
    public function tablaHtmlNoticias($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_noticia'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'AplicacionMovilExternos/Noticias"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                		<td>' . ++ $contador . '</td>
                		<td style="white - space:nowrap; "><b>' . $fila['titulo'] . '</b></td>
                        <td>' . $fila['fecha_noticia'] . '</td>
                        <td>' . $fila['fuente'] . '</td>
                        <td>' . $fila['estado'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Noticias
     */
    public function cargarPanelNoticias()
    {
        
        $this->panelBusquedaNoticias = '<table class="filtro" style="width: 450px;">
                                                <tbody  style="width: 100%;">
                                                    <tr>
                                                        <th >Consultar noticias:</th>
                                                    </tr>
            
                                					<tr  style="width: 100%;">
                                						<td >Título: </td>
                                						<td colspan=3 >
                                							<input id="tituloNoticia" type="text" name="tituloNoticia" style="width: 100%" >
                                						</td>
                                					</tr>

                                                    <tr  style="width: 100%;">
                                						<td >*Fecha Inicio: </td>
                                						<td>
                                							<input id="fechaInicio" type="text" name="fechaInicio" style="width: 100%" readonly="readonly">
                                						</td>
                                					</tr>
                                                    <tr  style="width: 100%;">
                                						<td >*Fecha Fin: </td>
                                						<td>
                                							<input id="fechaFin" type="text" name="fechaFin" style="width: 100%" readonly="readonly">
                                						</td>
                                					</tr>
                                							    
                                                    <tr  style="width: 100%;">
                                						<td >*Estado: </td>
                                						<td colspan=3>
                                                            <select id="estadoNoticia" name="estadoNoticia" style="width: 100%;" required>' . $this->comboEstado("activo") . '</select>
                                						</td>
                                                    </tr>
                                                                
                                					<tr>
                                						<td colspan="2" style="text-align: end;">
                                							<button id="btnFiltrar">Consultar</button>
                                						</td>
                                					</tr>
                                				</tbody>
                                			</table>';
    }
    
    /**
     * Método para listar las noticias registradas
     */
    public function listarNoticiasFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $tituloNoticia = $_POST["tituloNoticia"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoNoticia = $_POST["estadoNoticia"];
        
        $arrayParametros = array(
            'titulo' => $tituloNoticia,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $estadoNoticia
        );
        $noticias = $this->lNegocioNoticias->buscarNoticiasXFiltro($arrayParametros);
        
        $this->tablaHtmlNoticias($noticias);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }


    /**
     * Combo de dos estados ACTIVO/INACTIVO
     * @param type $respuesta
     * @return string
     */
    public function comboEstado($opcion)
    {
        $combo = "";
        if ($opcion == "activo")
        {
            $combo .= '<option value="activo" selected="selected">Activo</option>';
            $combo .= '<option value="inactivo">Inactivo</option>';
        } else if ($opcion == "inactivo")
        {
            $combo .= '<option value="activo" >Activo</option>';
            $combo .= '<option value="inactivo" selected="selected">Inactivo</option>';
        } else
        {
            $combo .= '<option value="" selected="selected">Seleccionar...</option>';
            $combo .= '<option value="activo" >Activo</option>';
            $combo .= '<option value="inactivo">Inactivo</option>';
        }
        return $combo;
    }
}
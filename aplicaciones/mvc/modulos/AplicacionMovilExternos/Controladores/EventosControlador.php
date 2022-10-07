<?php
/**
 * Controlador Eventos
 *
 * Este archivo controla la lógica del negocio del modelo:  EventosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-12-24
 * @uses    EventosControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\AplicacionMovilExternos\Modelos\EventosLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\EventosModelo;

use Agrodb\AplicacionMovilExternos\Modelos\DetalleEventosModelo;
use Agrodb\AplicacionMovilExternos\Modelos\DetalleEventosLogicaNegocio;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class EventosControlador extends BaseControlador
{

    private $lNegocioEventos = null;
    private $modeloEventos = null;

    private $lNegocioDetalleEventos = null;
    private $modeloDetalleEventos = null;

    private $accion = null;    
    private $documentos = null;
    private $rutaFecha = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioEventos = new EventosLogicaNegocio();
        $this->modeloEventos = new EventosModelo();
        
        $this->lNegocioDetalleEventos = new DetalleEventosLogicaNegocio();
        $this->modeloDetalleEventos = new DetalleEventosModelo();
        
        $this->rutaFecha = date('Y').'/'.date('m').'/'.date('d');
        
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
        $fecha = date('Y-m-d');
        $modeloEventos = $this->lNegocioEventos->buscarLista("fecha_evento>='".$fecha."'");       
        $this->tablaHtmlEventos($modeloEventos);
        
        require APP . 'AplicacionMovilExternos/vistas/listaEventosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Evento";
        $this->formulario = 'nuevo';
        
        require APP . 'AplicacionMovilExternos/vistas/formularioEventosVista.php';
    }

    /**
     * Método para registrar en la base de datos -Eventos
     */
    public function guardar()
    {
        if ($_POST["id_evento"] === ''){
            $estado = 'exito';
            $mensaje = 'Certificado generado con éxito';
            $contenido = '';
            
            $contenido = $this->lNegocioEventos->guardar($_POST);
            
            echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
           
        }else{
            $_POST["id_evento"] = $this->lNegocioEventos->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
        
        
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Eventos
     */
    public function editar()
    {
        $this->accion = "Editar Evento";
        $this->formulario = 'abrir';
        
        $this->modeloEventos = $this->lNegocioEventos->buscar($_POST["id"]);
        $this->modeloDetalleEventos = $this->lNegocioDetalleEventos->buscar($_POST["id"]);
        
        require APP . 'AplicacionMovilExternos/vistas/formularioDetalleEventosVista.php';
        //require APP . 'AplicacionMovilExternos/vistas/formularioEventosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Eventos
     */
    public function borrar()
    {
        $this->lNegocioEventos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Eventos
     */
    public function tablaHtmlEventos($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_evento'] . '"
        			  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'AplicacionMovilExternos/Eventos"
        			  data-opcion="editar" ondragstart="drag(event)" draggable="true"
        			  data-destino="detalleItem">
			        <td>' . ++ $contador . '</td>
    				<td style="white - space:nowrap; "><b>' . $fila['nombre_evento'] . '</b></td>
    				<td>' . $fila['fecha_evento'] . '</td>
    				<td>' . $fila['estado'] . '</td>
				</tr>'
            );
        }
    }

    /**
     * Construye el código HTML para desplegar panel de busqueda para Eventos
     */
    public function crearPanelBusqueda()
    {
        
        $panelBusquedaNoticias = '<table class="filtro" style="width: 450px;">
                                                <tbody  style="width: 100%;">
                                                    <tr>
                                                        <th >Consultar denuncias:</th>
                                                    </tr>
            
                                					<tr  style="width: 100%;">
                                						<td >Nombre: </td>
                                						<td colspan=3 >
                                							<input id="nombreEvento" type="text" name="nombreEvento" style="width: 100%" >
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
                                                            <select id="estadoEvento" name="estadoEvento" style="width: 100%;" required>' . $this->comboEstado("activo") . '</select>
                                						</td>
                                                    </tr>
                                                                
                                					<tr>
                                						<td colspan="2" style="text-align: end;">
                                							<button id="btnFiltrar">Consultar</button>
                                						</td>
                                					</tr>
                                				</tbody>
											</table>';
		return $panelBusquedaNoticias ;
    }
    
    /**
     * Método para listar las noticias registradas
     */
    public function listarEventosFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $nombreEvento = $_POST["descripcion"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoEvento = $_POST["estado"];
        
        $arrayParametros = array(
            'nombre' => $nombreEvento,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $estadoEvento
        );
		$eventos = $this->lNegocioEventos->buscarEventoXFiltro($arrayParametros);
        
        $this->tablaHtmlEventos($eventos);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }

    /**
	 * Construye combo de estado Nueva o Atendida
	 *
	 * @return string
	 */
	public function comboEstado($estado)
	{

		$combo = "";

		if ($estado == 'activo') {
			$combo .= '<option value="activo" selected>Activo</option>';
			$combo .= '<option value="inactivo" >Inactivo</option>';
		} else {
			$combo .= '<option value="activo" >Activo</option>';
			$combo .= '<option value="inactivo" selected>Inactivo</option>';
		}

		return $combo;
	}
}
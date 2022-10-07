<?php
/**
 * Controlador Alertas
 *
 * Este archivo controla la lógica del negocio del modelo:  AlertasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-12-24
 * @uses    AlertasControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\AplicacionMovilExternos\Modelos\AlertasLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\AlertasModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class AlertasControlador extends BaseControlador
{

    private $lNegocioAlertas = null;
    private $modeloAlertas = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioAlertas = new AlertasLogicaNegocio();
        $this->modeloAlertas = new AlertasModelo();
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
        $modeloAlertas = $this->lNegocioAlertas->buscarLista("fecha_alerta>='".$fecha."'");
        $this->tablaHtmlAlertas($modeloAlertas);
        
        require APP . 'AplicacionMovilExternos/vistas/listaAlertasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Alerta";
        $this->formulario = 'nuevo';
        
        require APP . 'AplicacionMovilExternos/vistas/formularioAlertasVista.php';
    }

    /**
     * Método para registrar en la base de datos -Alertas
     */
    public function guardar()
    {
        $this->lNegocioAlertas->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Alertas
     */
    public function editar()
    {
        $this->accion = "Editar Alertas";
        
        $this->modeloAlertas = $this->lNegocioAlertas->buscar($_POST["id"]);
        require APP . 'AplicacionMovilExternos/vistas/formularioAlertasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Alertas
     */
    public function borrar()
    {
        $this->lNegocioAlertas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Alertas
     */
    public function tablaHtmlAlertas($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_alerta'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'AplicacionMovilExternos/Alertas"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    	<td>' . ++ $contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['titulo'] . '</b></td>
                        <td style="white - space:nowrap; "><b>' . $fila['alerta'] . '</b></td>
                        <td>' . $fila['fecha_alerta']. '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['estado'] . '</b></td>
                    </tr>'
                );
            }
        }
    }

    /**
     * Construye el código HTML para desplegar panel de busqueda para Denuncias
     */
    public function crearPanelBusqueda()
    {
        
        $panelBusquedaNoticias = '<table class="filtro" style="width: 450px;">
                                                <tbody  style="width: 100%;">
                                                    <tr>
                                                        <th >Consultar alertas:</th>
                                                    </tr>
            
                                					<tr  style="width: 100%;">
                                						<td >Título: </td>
                                						<td colspan=3 >
                                							<input id="tituloAlerta" type="text" name="tituloAlerta" style="width: 100%" >
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
                                                            <select id="estadoAlerta" name="estadoAlerta" style="width: 100%;" required>' . $this->comboEstado("activo") . '</select>
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
    public function listarAlertasFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $tituloAlerta = $_POST["tituloAlerta"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoDenuncia= $_POST["estado"];
        
        $arrayParametros = array(
            'titulo' => $tituloAlerta,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $estadoDenuncia
        );
		$alertas = $this->lNegocioAlertas->buscarAlertaXFiltro($arrayParametros);
        
        $this->tablaHtmlAlertas($alertas);
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
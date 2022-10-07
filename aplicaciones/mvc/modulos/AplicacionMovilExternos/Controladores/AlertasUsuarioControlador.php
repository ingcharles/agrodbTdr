<?php
 /**
 * Controlador AlertasUsuario
 *
 * Este archivo controla la lógica del negocio del modelo:  AlertasUsuarioModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-09-07
 * @uses    AlertasUsuarioControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
 namespace Agrodb\AplicacionMovilExternos\Controladores;
 use Agrodb\AplicacionMovilExternos\Modelos\AlertasUsuarioLogicaNegocio;
 use Agrodb\AplicacionMovilExternos\Modelos\AlertasUsuarioModelo;
 use Agrodb\AplicacionMovilExternos\Modelos\TiposAlertaLogicaNegocio;
 
class AlertasUsuarioControlador extends BaseControlador 
{

		 private $lNegocioAlertasUsuario = null;
		 private $lNegocioTipoAlerta = null;
		 private $modeloAlertasUsuario = null;
		 private $accion = null;
		 private $tipoAlerta = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioAlertasUsuario = new AlertasUsuarioLogicaNegocio();
		 $this->lNegocioTipoAlerta = new TiposAlertaLogicaNegocio();
		 $this->modeloAlertasUsuario = new AlertasUsuarioModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $fecha = date("Y-m-d");
		 $modeloAlertasUsuario = $this->lNegocioAlertasUsuario->buscarLista("fecha_registro>='$fecha'");
		 //$modeloAlertasUsuario = $this->lNegocioAlertasUsuario->buscarAlertasUsuario();

		 $this->tablaHtmlAlertasUsuario($modeloAlertasUsuario);
		 require APP . 'AplicacionMovilExternos/vistas/listaAlertasUsuarioVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo AlertasUsuario"; 
		 require APP . 'AplicacionMovilExternos/vistas/formularioAlertasUsuarioVista.php';
		}	/**
		* Método para registrar en la base de datos -AlertasUsuario
		*/
		public function guardar()
		{
		  $this->lNegocioAlertasUsuario->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: AlertasUsuario
		*/
		public function editar()
		{
		 $this->accion = "Editar AlertasUsuario"; 
		 $this->modeloAlertasUsuario = $this->lNegocioAlertasUsuario->buscar($_POST["id"]);
		 $this->tipoAlerta = $this->tipoAlerta($this->modeloAlertasUsuario->getIdTipoAlerta());
		 require APP . 'AplicacionMovilExternos/vistas/formularioAlertasUsuarioVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - AlertasUsuario
		*/
		public function borrar()
		{
		  $this->lNegocioAlertasUsuario->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - AlertasUsuario
		*/
		 public function tablaHtmlAlertasUsuario($tabla) 
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
			  $fecha = date_create($fila['fecha_registro']);
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_alerta'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'AplicacionMovilExternos\AlertasUsuario"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . date_format($fecha,"Y-m-d")  . '</b></td>
		<td>'
				. $fila['descripcion'] . '</td>
		<td>' . $fila['nombre_usuario']
				. '</td>
		<td>' . $fila['lugar'] . '</td>
		<td>' . $fila['estado'] . '</td>
		</tr>');
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
                                                        <th >Consultar denuncias:</th>
                                                    </tr>
            
                                					<tr  style="width: 100%;">
                                						<td >Descripción: </td>
                                						<td colspan=3 >
                                							<input id="descripcionDenuncia" type="text" name="descripcionDenuncia" style="width: 100%" >
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
                                                            <select id="estadoDenuncia" name="estadoDenuncia" style="width: 100%;" required>' . $this->comboEstado("Nueva") . '</select>
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
     * Método para listar las alertas por filtro
     */
    public function listarAlertasFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $descripcionDenuncia = $_POST["descripcion"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        $estadoDenuncia= $_POST["estado"];
        
        $arrayParametros = array(
            'descripcion' => $descripcionDenuncia,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $estadoDenuncia
        );
		$denuncias = $this->lNegocioAlertasUsuario->buscarAlertaXFiltro($arrayParametros);
        
        $this->tablaHtmlAlertasUsuario($denuncias);
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

		if ($estado == 'Nueva') {
			$combo .= '<option value="Nueva" selected>Nueva</option>';
			$combo .= '<option value="Atendida" >Atendida</option>';
		} else {
			$combo .= '<option value="Nueva" >Nueva</option>';
			$combo .= '<option value="Atendida" selected>Atendida</option>';
		}

		return $combo;
	}

	/**
	 * Obtiene decripción del tipo de alerta
	 *
	 * @return string
	 */
	public function tipoAlerta($idAlerta)
	{

		$resultado = $this->lNegocioTipoAlerta->buscarLista(array ("id_tipo_alerta = $idAlerta"));

		return $resultado->current()->descripcion;
	}

	/**
	 * Actualiza estado de alerta (Atención)
	 *
	 * @return string
	 */
	public function actualizar()
	{

		$resultado = $this->lNegocioAlertasUsuario->guardarEstado(array ("id_alerta" => $_POST['id_alerta'], "estado" => $_POST['estado'], "observacion" => $_POST['observacion']));


	}

}

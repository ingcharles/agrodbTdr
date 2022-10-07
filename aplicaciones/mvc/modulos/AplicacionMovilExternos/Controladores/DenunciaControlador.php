<?php

/**
 * Controlador Denuncia
 *
 * Este archivo controla la lógica del negocio del modelo:  DenunciaModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-05-22
 * @uses    DenunciaControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */

namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\AplicacionMovilExternos\Modelos\DenunciaLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\DenunciaModelo;

class DenunciaControlador extends BaseControlador
{

	private $lNegocioDenuncia = null;
	private $modeloDenuncia = null;
	private $accion = null;
	private $motivoDenuncia = null;
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->lNegocioDenuncia = new DenunciaLogicaNegocio();
		$this->modeloDenuncia = new DenunciaModelo();
		set_exception_handler(array($this, 'manejadorExcepciones'));
	}
	/**
	 * Método de inicio del controlador
	 */
	public function index()
	{
		$fecha = date("Y-m-d");		
		$modeloDenuncia = $this->lNegocioDenuncia->buscarLista("fecha_registro>='$fecha'");
		
		$this->tablaHtmlDenuncia($modeloDenuncia);
		require APP . 'AplicacionMovilExternos/vistas/listaDenunciaVista.php';
	}
	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo()
	{
		$this->accion = "Nuevo Denuncia";
		require APP . 'AplicacionMovilExternos/vistas/formularioDenunciaVista.php';
	}
	/**
	 * Método para registrar en la base de datos -Denuncia
	 */
	public function guardar()
	{
		$this->lNegocioDenuncia->guardar($_POST);
	}
	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: Denuncia
	 */
	public function editar()
	{
		$this->accion = "Editar Denuncia";
		$this->modeloDenuncia = $this->lNegocioDenuncia->buscar($_POST["id"]);
		$this->motivoDenuncia = $this->motivoDenuncia($this->modeloDenuncia->getIdMotivo());
		require APP . 'AplicacionMovilExternos/vistas/formularioDenunciaVista.php';
	}
	/**
	 * Método para borrar un registro en la base de datos - Denuncia
	 */
	public function borrar()
	{
		$this->lNegocioDenuncia->borrar($_POST['elementos']);
	}
	/**
	 * Construye el código HTML para desplegar la lista de - Denuncia
	 */
	public function tablaHtmlDenuncia($tabla)
	{
		$contador = 0;
		foreach ($tabla as $fila) {
			$this->itemsFiltrados[] = array(
				'<tr id="' . $fila['id_denuncia'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'AplicacionMovilExternos\Denuncia"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . date('Y-m-d',strtotime($fila['fecha_registro'])) . '</b></td>
		<td>'
					. $fila['descripcion'] . '</td>
		<td>' . $fila['nombre_denunciante']
					. '</td>
		<td>' . $fila['lugar'] . '</td>
		<td>' . $fila['estado'] . '</td>
		</tr>'
			);
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
     * Método para listar las noticias registradas
     */
    public function listarDenunciasFiltradas()
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
		$denuncias = $this->lNegocioDenuncia->buscarDenunciaXFiltro($arrayParametros);
        
        $this->tablaHtmlDenuncia($denuncias);
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
	 * Obtiene decripción del motivo de denuncia
	 *
	 * @return string
	 */
	public function motivoDenuncia($idDenuncia)
	{

		$resultado = $this->lNegocioDenuncia->buscaraMotivo(array ("id_denuncia" => $idDenuncia));

		return $resultado->current()->descripcion;
	}

	/**
	 * Actualiza estado de denuncia
	 *
	 * @return string
	 */
	public function actualizar()
	{

		$resultado = $this->lNegocioDenuncia->guardarEstado(array ("id_denuncia" => $_POST['id_denuncia'], "estado" => $_POST['estado'], "observacion" => $_POST['observacion']));


	}
}

<?php
 /**
 * Controlador PartidasArancelarias
 *
 * Este archivo controla la lógica del negocio del modelo:  PartidasArancelariasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    PartidasArancelariasControlador
 * @package Catalogos
 * @subpackage Controladores
 */
 namespace Agrodb\Catalogos\Controladores;
 use Agrodb\Catalogos\Modelos\PartidasArancelariasLogicaNegocio;
 use Agrodb\Catalogos\Modelos\PartidasArancelariasModelo;
 
class PartidasArancelariasControlador extends BaseControlador 
{

		 private $lNegocioPartidasArancelarias = null;
		 private $modeloPartidasArancelarias = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioPartidasArancelarias = new PartidasArancelariasLogicaNegocio();
		 $this->modeloPartidasArancelarias = new PartidasArancelariasModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloPartidasArancelarias = $this->lNegocioPartidasArancelarias->buscarPartidasArancelarias();
		 $this->tablaHtmlPartidasArancelarias($modeloPartidasArancelarias);
		 require APP . 'Catalogos/vistas/listaPartidasArancelariasVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo PartidasArancelarias"; 
		 require APP . 'Catalogos/vistas/formularioPartidasArancelariasVista.php';
		}	/**
		* Método para registrar en la base de datos -PartidasArancelarias
		*/
		public function guardar()
		{
		  $this->lNegocioPartidasArancelarias->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: PartidasArancelarias
		*/
		public function editar()
		{
		 $this->accion = "Editar PartidasArancelarias"; 
		 $this->modeloPartidasArancelarias = $this->lNegocioPartidasArancelarias->buscar($_POST["id"]);
		 require APP . 'Catalogos/vistas/formularioPartidasArancelariasVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - PartidasArancelarias
		*/
		public function borrar()
		{
		  $this->lNegocioPartidasArancelarias->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - PartidasArancelarias
		*/
		 public function tablaHtmlPartidasArancelarias($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_partida_arancelaria'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'Catalogos\partidasarancelarias"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_partida_arancelaria'] . '</b></td>
<td>'
		  . $fila['fecha_creacion'] . '</td>
<td>' . $fila['id_producto']
		  . '</td>
<td>' . $fila['partida_arancelaria'] . '</td>
</tr>');
		}
		}
	}

}

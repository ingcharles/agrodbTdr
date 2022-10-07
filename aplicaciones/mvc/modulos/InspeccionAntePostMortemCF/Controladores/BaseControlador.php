<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores
 *
 * @property AGROCALIDAD
 * @author Carlos Anchundia
 * @date      2019-05-27
 * @uses BaseControlador
 * @package InspeccionAntePostMortemCF
 * @subpackage Controladores
 */
namespace Agrodb\InspeccionAntePostMortemCF\Controladores;

session_start();
use Agrodb\Programas\Modelos\AccionesLogicaNegocio;
use Agrodb\Core\Comun;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class BaseControlador extends Comun{

	public $itemsFiltrados = array();

	public $codigoJS = null;

	/**
	 * Constructor
	 */
	function __construct(){
		if(PHP_SAPI!=='cli'){
			parent::usuarioActivo();
		}
		// Si se requiere agregar código concatenar la nueva cadena con ejemplo $this->codigoJS.=alert('hola');
		$this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
	}

	public function crearTabla(){
		$tabla = "//No existen datos para mostrar...";
		if (count($this->itemsFiltrados) > 0){
			$tabla = '$(document).ready(function() {
			construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			$("#listadoItems").removeClass("comunes");
			});';
		}

		return $tabla;
	}

	function articleComun($arrayParametros, $opt){
		switch ($opt) {

			case 1:
				$contenido = '<article
								id="' . $arrayParametros['id'] . '"
								class="item"
								data-rutaAplicacion="' . $arrayParametros['rutaAplicacion'] . '"
								data-opcion="' . $arrayParametros['opcion'] . '"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="' . $arrayParametros['destino'] . '"
								<span><small> ' . $arrayParametros['texto1'] . ' </small></span>
					 			<span class="ordinal">' . $arrayParametros['contador'] . '</span>
								<aside ></aside>
								</article>';
			break;

			case 2:
				$contenido = '<article
								id="' . $arrayParametros['id'] . '"
								class="item"
								data-rutaAplicacion="' . $arrayParametros['rutaAplicacion'] . '"
								data-opcion="' . $arrayParametros['opcion'] . '"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="' . $arrayParametros['destino'] . '"
								<span><small> ' . $arrayParametros['texto1'] . ' </small></span>
								<span><small>' . $arrayParametros['texto2'] . '</small></span>
					 			<span class="ordinal">' . $arrayParametros['contador'] . '</span>
								<aside ><small> Estado: <br> ' . $arrayParametros['estado'] . '<span><div class= "' . $this->claseEstado($arrayParametros['estado']) . '"></div></span></small></aside>
								</article>';
			break;
			case 3:
				$contenido = '<article
								id="' . $arrayParametros['id'] . '"
								class="item"
								data-rutaAplicacion="' . $arrayParametros['rutaAplicacion'] . '"
								data-opcion="' . $arrayParametros['opcion'] . '"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="' . $arrayParametros['destino'] . '"
								<span> ' . $arrayParametros['texto1'] . ' </span>
								<span class="ordinal">' . $arrayParametros['contador'] . '</span>
								<aside > Pendientes: ' . $arrayParametros['numero'] . '</aside>
								</article>';
			break;
			case 4:
				$contenido = '<article
								id="' . $arrayParametros['id'] . '"
								class="item"
								data-rutaAplicacion="' . $arrayParametros['rutaAplicacion'] . '"
								data-opcion="' . $arrayParametros['opcion'] . '"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="' . $arrayParametros['destino'] . '"
								<span><small> ' . $arrayParametros['texto1'] . ' </small></span>
								<span><small>' . $arrayParametros['texto2'] . '</small></span>
					 			<span class="ordinal">' . $arrayParametros['contador'] . '</span>
								<aside ><small> Estado: <br> ' . $arrayParametros['estado'] . '<span></span></small></aside>
								</article>';
			break;
			case 5:
				$contenido = '<article
								id="' . $arrayParametros['id'] . '"
								class="item"
								data-rutaAplicacion="' . $arrayParametros['rutaAplicacion'] . '"
								data-opcion="' . $arrayParametros['opcion'] . '"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="' . $arrayParametros['destino'] . '"
								<span><h3> ' . $arrayParametros['texto1'] . ' </h3></span>
								<span><small>' . $arrayParametros['texto2'] . '</small></span>
					 			<span class="ordinal">' . $arrayParametros['contador'] . '</span>
								<aside > Aprobados: ' . $arrayParametros['estado'] . '<span></span></aside>
								</article>';
				break;
		}

		return $contenido;
	}

	function claseEstado($estado){
		switch ($estado) {

			case 'Sin pendientes':
				$clase = 'circulo_verde';
			break;

			case 'Con pendientes':
				$clase = 'circulo_rojo';
			break;
			case 'Por revisar':
				$clase = 'circulo_rojo';
			break;

			case 'registradoObservacion':
				$clase = 'circulo_amarillo';
			break;

			default:
				$clase = '';
		}
		return $clase;
	}

	/**
	 *
	 * @see \Agrodb\Core\Comun::crearAccionBotones()
	 */
	public function crearAccionBotonesCF($arrayParametros){
		if (! isset($_POST["opcion"])){
			Mensajes::fallo(Constantes::ERROR_MENU);
			throw new \Exception('Verifique que el controlador tenga implementada el método para esta acción');
		}
		if (! isset($_SESSION['usuario'])){
			Mensajes::fallo(Constantes::ERROR_USUARIO_INACTIVO);
			throw new \Exception('No se puede verificar las acciones del perfil de usuario, sesión del usuario a finalizado');
		}
		$acciones = new AccionesLogicaNegocio();
		$resultado = $acciones->obtenerAccionesPermitidas($_POST["opcion"], $_SESSION['usuario']);

		$botones = "";
		// $itemsFiltrados[] = array();
		$ruta = 'detalleItem';
		$contador = 0;
		foreach ($resultado as $fila){
			if ($fila['estilo'] == '_nuevo' || $fila['estilo'] == '_actualizar'){
				if ($fila['estilo'] == '_actualizar'){
					$ruta = 'listadoItems';
				}else{
					$ruta = 'detalleItem';
				}
				$estilo = $fila['estilo'] . 'Cf';
				$botones .= '<a  href="#" id="' . $arrayParametros['id_centro_faenamiento'] . '-' . $arrayParametros['opcion'] . '-' . $contador . '"class="' . $estilo . '" data-destino="' . $ruta . '" data-opcion="' . $fila['pagina'] . '"data-rutaAplicacion="' . $fila['ruta'] . '"
			>' . (($fila['estilo'] == '_seleccionar') ? '<div id="cantidadItemsSeleccionados">0</div>' : '') . $fila['descripcion'] . '</a>';

				$contador ++;
			}elseif ($fila['estilo'] == '_seleccionar'){
				$botones .= '<a  href="#" id="' . $fila['estilo'] . '" data-destino="' . $ruta . '" data-opcion="' . $fila['pagina'] . '"data-rutaAplicacion="' . $fila['ruta'] . '"
			>' . (($fila['estilo'] == '_seleccionar') ? '<div id="cantidadItemsSeleccionados">0</div>' : '') . $fila['descripcion'] . '</a>';
			}
		}
		return $botones . '<div id="estado"></div>';
	}

	/**
	 *
	 * @see combos de opcion si y no
	 */
	public function comboSiNo($campo = null){
		$arrayCombo = array(
			"Si" => "Si",
			"No" => "No");

		$combo = '<option value="">Seleccione...</option>';

		foreach ($arrayCombo as $item){
			if ($campo == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}

	/**
	 *
	 * @see combos de opcion parcial y total
	 */
	public function comboParcialTotal($campo = null){
		$arrayCombo = array(
			"Parcial" => "Parcial",
			"Total" => "Total");

		$combo = '<option value="">Seleccione...</option>';

		foreach ($arrayCombo as $item){
			if ($campo == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}

	/**
	 *
	 * @see combos de opcion parcial y total
	 */
	public function comboDestino($campo = null){
		$arrayCombo = array(
			"Incineración" => "Incineración",
			"Rendering" => "Rendering",
			"Descomposición controlada (abono)" => "Descomposición controlada (abono)",
			"Entrega a gestor ambiental autorizado" => "Entrega a gestor ambiental autorizado");

		$combo = '<option value="">Seleccione...</option>';

		foreach ($arrayCombo as $item){
			if ($campo == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}

	/**
	 *
	 * @see combos de opcion enfermedad
	 */
	public function comboEnfermedad($campo = null){
		$arrayCombo = array(
			"Enfermedad vesicular" => "Enfermedad vesicular",
			"Tuberculosis" => "Tuberculosis",
			"Paratuberculosis" => "Paratuberculosis",
			"Leucosis" => "Leucosis",
			"Brucelosis" => "Brucelosis",
			"Metritis" => "Metritis",
			"Distomatosis" => "Distomatosis",
			"Hidatidosis" => "Hidatidosis",
			"Leptospirosis" => "Leptospirosis",
			"Cetosis" => "Cetosis",
			"Acidosis Ruminal" => "Acidosis Ruminal"
		);

		$combo = '<option value="">Seleccione...</option>';

		foreach ($arrayCombo as $item){
			if ($campo == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}
	/**
	 *
	 * @see combos de opcion localizacion
	 */
	public function comboLocalizacion($campo = null){
		$arrayCombo = array(
			"Lengua" => "Lengua",
			"Patas" => "Patas",
			"Ubre" => "Ubre",
			"Pulmón" => "Pulmón",
			"Hígado" => "Hígado",
			"Intestino" => "Intestino",
			"Ganglios" => "Ganglios",
			"S. Reproductivo" => "S. Reproductivo",
			"Articulaciones" => "Articulaciones",
			"Útero" => "Útero",
			"Mucosas" => "Mucosas",
			"Riñón" => "Riñón",
			"Rumen" => "Rumen",
			"Contenido Ruminal" => "Contenido Ruminal",
			"General" => "General",
			"Otros" => "Otros"
		);
		
		$combo = '<option value="">Seleccione...</option>';
		
		foreach ($arrayCombo as $item){
			if ($campo == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}
	/**
	 *
	 * @see combos de opcion organo decomisado
	 */
	public function comboOrganoDecomisado($campo = null){
		$arrayCombo = array(
			"Lengua" => "Lengua",
			"Patas" => "Patas",
			"Ubre" => "Ubre",
			"Pulmón" => "Pulmón",
			"Hígado" => "Hígado",
			"Intestino" => "Intestino",
			"Ganglios" => "Ganglios",
			"Aparato Reproductivo" => "Aparato Reproductivo",
			"Riñón" => "Riñón",
			"Estómago" => "Estómago",
			"Corazón" => "Corazón",
			"Carcasa" => "Carcasa",
			"Otros" => "Otros"
		);
		
		$combo = '<option value="">Seleccione...</option>';
		
		foreach ($arrayCombo as $item){
			if ($campo == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}
	/**
	 *
	 * @see combos de opcion razon decomisado
	 */
	public function comboRazonDecomisado($campo = null){
		$arrayCombo = array(
			"Enfermedad infecciosa (virus, bacterias,parásitos)" => "Enfermedad infecciosa (virus, bacterias,parásitos)",
			"Alteración de las propiedades organolépticas" => "Alteración de las propiedades organolépticas",
			"Contaminación" => "Contaminación",
			"Otras" => "Otras"
		);
		
		$combo = '<option value="">Seleccione...</option>';
		
		foreach ($arrayCombo as $item){
			if ($campo == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}
}

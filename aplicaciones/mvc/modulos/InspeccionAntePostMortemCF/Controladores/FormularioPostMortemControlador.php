<?php
/**
 * Controlador FormularioPostMortem
 *
 * Este archivo controla la lógica del negocio del modelo: FormularioPostMortemModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2019-05-27
 * @uses FormularioPostMortemControlador
 * @package InspeccionAntePostMortemCF
 * @subpackage Controladores
 */
namespace Agrodb\InspeccionAntePostMortemCF\Controladores;

use Agrodb\InspeccionAntePostMortemCF\Modelos\FormularioPostMortemLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\FormularioPostMortemModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\FormularioAnteMortemLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\FormularioAnteMortemModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetalleAnteAvesLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetalleAnteAvesModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetalleAnteAnimalesLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetalleAnteAnimalesModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetallePostAvesLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetallePostAvesModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetallePostAnimalesLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetallePostAnimalesModelo;

class FormularioPostMortemControlador extends BaseControlador{

	private $lNegocioFormularioPostMortem = null;

	private $modeloFormularioPostMortem = null;

	private $lNegocioFormularioAnteMortem = null;

	private $modeloFormularioAnteMortem = null;

	private $lNegocioDetalleAnteAves = null;

	private $modeloDetalleAnteAves = null;

	private $lNegocioDetalleAnteAnimales = null;

	private $modeloDetalleAnteAnimales = null;

	private $lNegocioDetallePostAves = null;

	private $modeloDetallePostAves = null;

	private $lNegocioDetallePostAnimales = null;

	private $modeloDetallePostAnimales = null;

	private $accion = null;

	private $article = null;

	private $botones = null;

	private $nombreCF = null;

	private $provincia = null;

	private $canton = null;

	private $parroquia = null;

	private $razonSocial = null;

	private $nombreMedico = null;

	private $comboEspecie = null;

	private $comboProducto = null;

	private $fechaInicial = null;

	private $idCentroFaenamiento = null;

	private $idFormularioAnteMortem = null;

	private $idFormularioPostMortem = null;

	private $detalleFormulario = null;

	private $hallazgosDiagnosticados = null;

	private $resultadoOrgano = null;

	private $resultadoDecomisoParcial = null;

	private $resultadoDecomisoTotal = null;

	private $arrayDetalleFormulario = null;

	private $idOperadorTipoOperacion = null;

	private $estadoRegistro = null;

	private $comboDestino = null;

	private $idFormularioEditar = null;

	private $arrayHallazgos = null;

	private $arrayResultadoOrgano = null;

	private $arrayResultadoDecomisoParcial = null;

	private $arrayResultadoDecomisoTotal = null;

	private $perfilUsuario = null;

	private $urlExcel = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioFormularioPostMortem = new FormularioPostMortemLogicaNegocio();
		$this->modeloFormularioPostMortem = new FormularioPostMortemModelo();
		$this->lNegocioFormularioAnteMortem = new FormularioAnteMortemLogicaNegocio();
		$this->modeloFormularioAnteMortem = new FormularioAnteMortemModelo();
		$this->lNegocioDetalleAnteAves = new DetalleAnteAvesLogicaNegocio();
		$this->modeloDetalleAnteAves = new DetalleAnteAvesModelo();
		$this->lNegocioDetalleAnteAnimales = new DetalleAnteAnimalesLogicaNegocio();
		$this->modeloDetalleAnteAnimales = new DetalleAnteAnimalesModelo();
		$this->lNegocioDetallePostAves = new DetallePostAvesLogicaNegocio();
		$this->modeloDetallePostAves = new DetallePostAvesModelo();
		$this->lNegocioDetallePostAnimales = new DetallePostAnimalesLogicaNegocio();
		$this->modeloDetallePostAnimales = new DetallePostAnimalesModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$this->articleCentroFaenamiento();
		require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioPostMortemCfVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo FormularioPostMortem";
		require APP . 'InspeccionAntePostMortemCF/vistas/formularioFormularioPostMortemVista.php';
	}

	/**
	 * Método para registrar en la base de datos -FormularioPostMortem
	 */
	public function guardar(){
		$this->lNegocioFormularioPostMortem->guardar($_POST);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: FormularioPostMortem
	 */
	public function editar(){
		$this->perfilUsuario();
		$this->urlPdf = '';
		$this->accion = "Nuevo Formulario Post Mortem";
		$variable = explode('-', $_POST["id"]);
		$this->idFormularioEditar = $_POST["id"];
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[1],
			'opcion' => $variable[2]);
		$consulta = $this->lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
		$this->provincia = $consulta->current()->provincia;
		$this->canton = $consulta->current()->canton;
		$this->parroquia = $consulta->current()->parroquia;
		$this->razonSocial = $consulta->current()->razon_social;
		//$datos = $this->lNegocioFormularioAnteMortem->buscarDatosOperador($_SESSION['usuario']);
		
		$identifi = $this->lNegocioFormularioAnteMortem->buscarIdentificadorMedicoVeterinario($arrayParametros);
		$datos =  $this->lNegocioFormularioAnteMortem->buscarDatosOperador($identifi->current()->identificador_operador);
		
		$this->nombreMedico = $datos->current()->nombre_medico;
		$this->fechaInicial = $this->lNegocioFormularioAnteMortem->fechaInicalAves(date('Y-m-d'));
		$this->idFormularioAnteMortem = $variable[0];
		$this->idCentroFaenamiento = $variable[1];

		if ($consulta->current()->especie == 'Avícola'){
			$this->modeloDetallePostAves = $this->lNegocioDetallePostAves->buscar($variable[4]);
			$this->modeloDetalleAnteAves = $this->lNegocioDetalleAnteAves->buscar($variable[3]);
			$this->modeloDetallePostAves->setFechaFormulario($this->lNegocioFormularioAnteMortem->fechaInicalAves($this->modeloDetallePostAves->getFechaFormulario()));
			if ($this->modeloDetallePostAves->getIdFormularioPostMortem() != null){
				$this->modeloFormularioPostMortem = $this->lNegocioFormularioPostMortem->buscar($this->modeloDetallePostAves->getIdFormularioPostMortem());
				if ($this->modeloFormularioPostMortem->getEstado() == 'Aprobado_PM'){
					$this->accion = "Ver Formulario Post Mortem";
				}else{
					$this->accion = "Editar Formulario Post Mortem";
				}
			}
			$this->idFormularioPostMortem = $this->modeloDetallePostAves->getIdFormularioPostMortem();
			$this->modeloDetallePostAves->setFechaFormulario($this->lNegocioFormularioAnteMortem->formatearFecha($this->modeloDetallePostAves->getFechaFormulario()));

			require APP . 'InspeccionAntePostMortemCF/vistas/formularioDetallePostAvesVista.php';
		}else{
			$this->modeloDetallePostAnimales = $this->lNegocioDetallePostAnimales->buscar($variable[4]);
			$this->modeloDetalleAnteAnimales = $this->lNegocioDetalleAnteAnimales->buscar($variable[3]);
			$this->modeloDetallePostAnimales->setFechaFormulario($this->lNegocioFormularioAnteMortem->formatearFecha($this->modeloDetallePostAnimales->getFechaFormulario()));
			if ($this->modeloDetallePostAnimales->getIdFormularioPostMortem() != null){
				$this->modeloFormularioPostMortem = $this->lNegocioFormularioPostMortem->buscar($this->modeloDetallePostAnimales->getIdFormularioPostMortem());
				if ($this->modeloFormularioPostMortem->getEstado() == 'Aprobado_PM'){
					$this->accion = "Ver Formulario Post Mortem";
				}else{
					$this->accion = "Editar Formulario Post Mortem";
				}
			}

			$this->hallazgosDiagnosticados = $this->generarDetalleHallazgosPost($this->modeloDetallePostAnimales->getIdDetallePostAnimales());
			$this->resultadoOrgano = $this->generarResultadoOrgano($this->modeloDetallePostAnimales->getIdDetallePostAnimales());
			$this->resultadoDecomisoParcial = $this->generarResultadoDecomisoParcial($this->modeloDetallePostAnimales->getIdDetallePostAnimales());
			$this->resultadoDecomisoTotal = $this->generarResultadoDecomisoTotal($this->modeloDetallePostAnimales->getIdDetallePostAnimales());
			require APP . 'InspeccionAntePostMortemCF/vistas/formularioDetallePostAnimalesVista.php';
		}
	}

	/**
	 * Método para generar los detalles en de hallazgos del examen post mortem
	 */
	public function generarDetalleHallazgosPost($idDetallePostAnimales){
		$html = '';
		$datos = array();

		if ($idDetallePostAnimales != null){

			$consulta = $this->lNegocioFormularioPostMortem->buscarDetalleHallazgosPost($idDetallePostAnimales);
			foreach ($consulta as $item){
				$datos[] = array(
					"enfermedad" => $item['enfermedad'],
					"localizacion" => $item['localizacion'],
					"numAnimalAfec" => $item['num_animales_afectados'],
					"tipo" => "hallazgos",
					"presencia" => "",
					"id_examen_post_hallazgos" => $item['identificador']);
				$html .= "<tr id=" . $item['identificador'] . "-ha><td>" . $item['enfermedad'] . "</td><td>" . $item['localizacion'] . "</td><td>" . $item['num_animales_afectados'] . "</td></tr>";
			}
			$consulta = $this->lNegocioFormularioPostMortem->buscarDetalleEndoparasitosPost($idDetallePostAnimales);
			foreach ($consulta as $item){
				$datos[] = array(
					"enfermedad" => $item['enfermedad'],
					"localizacion" => $item['localizacion'],
					"numAnimalAfec" => $item['num_animales_afectados'],
					"tipo" => "endoparasitos",
					"presencia" => $item['endoparasitos_presencia'],
					"id_examen_post_endoparasitos" => $item['identificador']);
				$html .= "<tr id=" . $item['identificador'] . "-en><td>" . $item['enfermedad'] . "</td><td>" . $item['localizacion'] . "</td><td>" . $item['num_animales_afectados'] . "</td></tr>";
			}
			$consulta = $this->lNegocioFormularioPostMortem->buscarDetalleEctoparasitosPost($idDetallePostAnimales);
			foreach ($consulta as $item){
				$datos[] = array(
					"enfermedad" => $item['enfermedad'],
					"localizacion" => $item['localizacion'],
					"numAnimalAfec" => $item['num_animales_afectados'],
					"tipo" => "ectoparasitos",
					"presencia" => $item['ectoparasitos_presencia'],
					"id_examen_post_ectoparasitos" => $item['identificador']);
				$html .= "<tr id=" . $item['identificador'] . "-ec><td>" . $item['enfermedad'] . "</td><td>" . $item['localizacion'] . "</td><td>" . $item['num_animales_afectados'] . "</td></tr>";
			}
		}

		$this->arrayHallazgos = $datos;
		return $html;
	}

	/**
	 * Método para generar los detalles en de resultados decomiso organos
	 */
	public function generarResultadoOrgano($idDetallePostAnimales){
		$html = '';
		$datos = array();
		if ($idDetallePostAnimales != null){
			$consulta = $this->lNegocioFormularioPostMortem->buscarResultadoOrgano($idDetallePostAnimales);
			foreach ($consulta as $item){
				$datos[] = array(
					"numOrganoDecomiso" => $item['num_organos_decomisados'],
					"organo" => $item['organo_decomisado'],
					"razonDecomiso" => $item['razon_decomiso'],
					"id_resultado_organos" => $item['id_resultado_organos']);

				$html .= "<tr id=" . $item['id_resultado_organos'] . "><td>" . $item['num_organos_decomisados'] . "</td><td>" . $item['organo_decomisado'] . "</td><td>" . $item['razon_decomiso'] . "</td></tr>";
			}
		}

		$this->arrayResultadoOrgano = $datos;
		return $html;
	}

	/**
	 * Método para generar los detalles en de resultados decomiso parciales
	 */
	public function generarResultadoDecomisoParcial($idDetallePostAnimales){
		$html = '';
		$datos = array();
		if ($idDetallePostAnimales != null){
			$consulta = $this->lNegocioFormularioPostMortem->buscarResultadoDecomisoParcial($idDetallePostAnimales);
			foreach ($consulta as $item){
				$datos[] = array(
					"razonDecomiso" => $item['razon_decomiso'],
					"numCanalesDecomisadas" => $item['num_canales_decomisadas'],
					"pesoCarneAprobada" => $item['peso_carne_aprobada'],
					"pesoCarneDecomisada" => $item['peso_carne_decomisada'],
					"id_resultado_decomiso_parcial" => $item['id_resultado_decomiso_parcial']);
				$html .= "<tr id=" . $item['id_resultado_decomiso_parcial'] . "><td>" . $item['razon_decomiso'] . "</td><td>" . $item['num_canales_decomisadas'] . "</td><td>" . $item['peso_carne_aprobada'] . "</td><td>" . $item['peso_carne_decomisada'] . "</td></tr>";
			}
		}
		$this->arrayResultadoDecomisoParcial = $datos;
		return $html;
	}

	/**
	 * Método para generar los detalles en de resultados decomiso total
	 */
	public function generarResultadoDecomisoTotal($idDetallePostAnimales){
		$html = '';
		$datos = array();
		if ($idDetallePostAnimales != null){
			$consulta = $this->lNegocioFormularioPostMortem->buscarResultadoDecomisoTotal($idDetallePostAnimales);
			foreach ($consulta as $item){
				$datos[] = array(
					"razonDecomiso" => $item['razon_decomiso'],
					"numCanalesDecomisadas" => $item['num_canales_decomisadas'],
					"pesoCarneDecomisada" => $item['peso_carne_decomisada'],
					"id_resultado_decomiso_total" => $item['id_resultado_decomiso_total']);
				$html .= "<tr id=" . $item['id_resultado_decomiso_total'] . "><td>" . $item['razon_decomiso'] . "</td><td>" . $item['num_canales_decomisadas'] . "</td><td>" . $item['peso_carne_decomisada'] . "</td></tr>";
			}
		}
		$this->arrayResultadoDecomisoTotal = $datos;
		return $html;
	}

	/**
	 * Método para generar los detalles en de endoparasitos del examen post mortem
	 */
	public function generarDetalleEndoparasitosPost($idDetallePostAnimales){
		$html = '';
		if ($idDetallePostAnimales != null){
			$consulta = $this->lNegocioFormularioPostMortem->buscarDetalleEndoparasitosPost($idDetallePostAnimales);
			foreach ($consulta as $item){
				$html .= "<tr id=" . $item['id_examen_post_hallazgos'] . "ha><td>" . $item['enfermedad'] . "</td><td>" . $item['localizacion'] . "</td><td>" . $item['num_animales_afectados'] . "</td></tr>";
			}
		}
		return $html;
	}

	/**
	 * Método para listar
	 */
	public function listar(){
		$this->perfilUsuario();
		$variable = explode('-', $_POST["id"]);
		$_POST["opcion"] = $variable[1];
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[0],
			'opcion' => $variable[1]);
		$consulta = $this->lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
		$this->nombreCF = $consulta->current()->razon_social;
		$this->detalleFormulario = 'Formularios pendientes por meses';
		$this->articleDetalleFormulariosCentroFaenamiento($arrayParametros);
		require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioPostMortemVista.php';
	}

	/**
	 * Método para devolver el la codificacion del perfi
	 */
	public function perfilUsuario(){
		$consulta = $this->lNegocioFormularioAnteMortem->verificarPerfil($_SESSION['usuario']);
		$this->perfilUsuario = $consulta->current()->codificacion_perfil;
	}

	/**
	 * Método para detalle de informacion
	 */
	public function listarFormularios(){
		$variable = explode('-', $_POST["id"]);
		$_POST["opcion"] = $variable[1];
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[0],
			'opcion' => $variable[1]);
		$consulta = $this->lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
		$this->nombreCF = $consulta->current()->razon_social;
		$this->detalleFormulario = 'Formularios pendientes';
		$this->articleFormulariosCentroFaenamiento($arrayParametros);
		require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioPostMortemVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - FormularioPostMortem
	 */
	public function borrar(){
		$this->lNegocioFormularioPostMortem->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Formulario de aves y animales
	 */
	public function tablaHtmlFormularioPostMortem($tabla, $arrayParametros, $especie){
		$contador = $idDetallePost = 0;
		foreach ($tabla as $fila){

			if ($especie == 'Avícola'){
				$consulta = $this->lNegocioFormularioPostMortem->obtenerInfoDetallePostAves($fila['id_detalle']);
				if ($consulta->count()){
					$estado = $consulta->current()->estado;
					$idDetallePost = $consulta->current()->id_detalle_post_aves;
				}else{
					$estado = $fila['estado'];
				}
			}else{
				$consulta = $this->lNegocioFormularioPostMortem->obtenerInfoDetallePostAnimales($fila['id_detalle']);
				if ($consulta->count()){
					$estado = $consulta->current()->estado;
					$idDetallePost = $consulta->current()->id_detalle_post_animales;
				}else{
					$estado = $fila['estado'];
				}
			}

			$this->itemsFiltrados[] = array(
				'<tr id="' . $fila['id_formulario_ante_mortem'] . '-' . $arrayParametros['id_centro_faenamiento'] . '-' . $arrayParametros['opcion'] . '-' . $fila['id_detalle'] . '-' . $idDetallePost . '"
		  class="item"
          data-rutaAplicacion="' . URL_MVC_FOLDER . 'InspeccionAntePostMortemCF\formularioPostMortem"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $estado . '</b></td>
			<td>' . $fila['fecha_creacion'] . '</td>
			<td>' . $fila['num_csmi'] . '</td>
			<td>' . $fila['codigo_formulario'] . '</td>
            <td>' . $fila['tipo'] . '</td>
			</tr>');
		}
	}

	/**
	 * Método para crear los articulos de centros de faenamiento
	 */
	public function articleCentroFaenamiento(){
		$arrayParametros = array(
			'identificador_operador' => $_SESSION["usuario"]);
		$consulta = $this->lNegocioFormularioAnteMortem->buscarCfAsignados($arrayParametros);
		$contador = 0;
		foreach ($consulta as $fila){
			$arrayIndex = "identificador='" . $_SESSION['usuario'] . "' and id_centro_faenamiento=" . $fila['id_centro_faenamiento'] . " order by 1";
			$lista = $this->lNegocioFormularioAnteMortem->buscarLista($arrayIndex);
			$estado = '';
			foreach ($lista as $item){
				if ($item['estado'] == 'Aprobado_AM' || $item['estado'] == 'Por revisar'){
					$estado = 'Con pendientes';
					break;
				}else{
					$estado = 'Sin pendientes';
				}
			}
			$arrayParametros = array(
				'id' => $fila['id_centro_faenamiento'] . '-' . $_POST["opcion"],
				'rutaAplicacion' => URL_MVC_FOLDER . 'InspeccionAntePostMortemCF',
				'opcion' => 'formularioPostMortem/listar',
				'destino' => 'listadoItems',
				'contador' => ++ $contador,
				'estado' => $estado,
				'texto1' => $fila['razon_social'],
				'texto2' => '');
			if ($estado == ''){
				$this->article .= $this->articleComun($arrayParametros, 1);
			}else{
				$this->article .= $this->articleComun($arrayParametros, 2);
			}
		}
	}

	/**
	 * Método para crear los articulos por meses de los formularios del centro de faenamiento
	 */
	public function articleMesesFormulariosCentroFaenamiento($arrayParametros){
		$arrayConsulta = array(
			'identificador_operador' => $_SESSION["usuario"],
			'id_centro_faenamiento' => $arrayParametros['id_centro_faenamiento']);
		$lista = $this->lNegocioFormularioAnteMortem->buscarFormulariosCfXMes($arrayConsulta);

		$contador = 0;
		foreach ($lista as $fila){
			$arrayDetalle = array(
				'id' => $arrayParametros['id_centro_faenamiento'] . '-' . $arrayParametros['opcion'] . '-' . $fila['mes'],
				'rutaAplicacion' => URL_MVC_FOLDER . 'InspeccionAntePostMortemCF',
				'opcion' => 'formularioPostMortem/detalleListar',
				'destino' => 'listadoItems',
				'contador' => ++ $contador,
				'estado' => 'Pendientes',
				'texto1' => $this->lNegocioFormularioAnteMortem->mesEnLetras($fila['mes']),
				'numero' => $fila['cantidad']);
			$this->article .= $this->articleComun($arrayDetalle, 3);
		}
	}

	/**
	 * Método para crear los articulos de los formularios del centro de faenamiento
	 * *
	 */
	public function articleFormulariosCentroFaenamiento($arrayParametros){
		$this->perfilUsuario();
		$variable = explode('-', $_POST["id"]);
		$_POST["opcion"] = $variable[1];
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[0],
			'opcion' => $variable[1],
			'mes' => $variable[2],
			'identificador_operador' => $_SESSION["usuario"],
			'estado' => "'Aprobado_AM'");
		$consulta = $this->lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
		$this->nombreCF = $consulta->current()->razon_social;
		$this->detalleFormulario = 'Formularios pendientes';
		if ($this->perfilUsuario == 'PFL_APM_CF_OP'){
			$modeloDetalleAnteMortem = $this->lNegocioFormularioAnteMortem->buscarFormulariosCf($arrayParametros);
		}else{
			$modeloDetalleAnteMortem = $this->lNegocioFormularioAnteMortem->buscarFormulariosCfAux($arrayParametros);
		}
		$contador = 0;
		foreach ($modeloDetalleAnteMortem as $fila){
			$arrayDetalle = array(
				'id' => $arrayParametros['id_centro_faenamiento'] . '-' . $arrayParametros['opcion'] . '-' . $arrayParametros['mes'] . '-' . $fila['id_formulario_ante_mortem'],
				'rutaAplicacion' => URL_MVC_FOLDER . 'InspeccionAntePostMortemCF',
				'opcion' => 'formularioPostMortem/detalleListar',
				'destino' => 'listadoItems',
				'contador' => ++ $contador,
				'estado' => $fila['estado'],
				'texto1' => 'Formulario Ante-Mortem',
				'texto2' => $fila['codigo_formulario'],
				'numero' => '');
			$this->article .= $this->articleComun($arrayDetalle, 4);
		}
	}

	/**
	 * Método para crear los articulos por meses de los formularios del centro de faenamiento
	 */
	public function articleDetalleFormulariosCentroFaenamiento($arrayParametros){
		$arrayConsulta = array(
			'identificador_operador' => $_SESSION["usuario"],
			'id_centro_faenamiento' => $arrayParametros['id_centro_faenamiento'],
			'estado' => "'Aprobado_AM'");
		if ($this->perfilUsuario == 'PFL_APM_CF_OP'){
			$lista = $this->lNegocioFormularioAnteMortem->buscarFormulariosCfXMes($arrayConsulta);
		}else{
			$lista = $this->lNegocioFormularioAnteMortem->buscarFormulariosCfXMesAux($arrayConsulta);
		}
		$contador = 0;
		foreach ($lista as $fila){
			$arrayDetalle = array(
				'id' => $arrayParametros['id_centro_faenamiento'] . '-' . $arrayParametros['opcion'] . '-' . $fila['mes'],
				'rutaAplicacion' => URL_MVC_FOLDER . 'InspeccionAntePostMortemCF',
				'opcion' => 'formularioPostMortem/listarFormularios',
				'destino' => 'listadoItems',
				'contador' => ++ $contador,
				'estado' => 'Pendientes',
				'texto1' => $this->lNegocioFormularioAnteMortem->mesEnLetras($fila['mes']),
				'numero' => $fila['cantidad']);
			$this->article .= $this->articleComun($arrayDetalle, 3);
		}
	}

	/**
	 * Método para desplegar el detalle del formulario
	 */
	public function detalleListar(){
		$variable = explode('-', $_POST["id"]);
		$_POST["opcion"] = $variable[1];
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[0],
			'id_formulario_ante_mortem' => $variable[3],
			'opcion' => $variable[1],
			'mes' => $variable[2],
			'identificador_operador' => $_SESSION["usuario"]);
		$consulta = $this->lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
		$this->nombreCF = $consulta->current()->razon_social;
		$this->detalleFormulario = 'Formularios registrados';
		$modeloDetalleAnteMortem = $this->lNegocioFormularioAnteMortem->buscarFormulariosCf($arrayParametros);

		if ($modeloDetalleAnteMortem->current()->especie == 'Avícola'){
			$consulta = $this->lNegocioFormularioAnteMortem->buscarAMDetalleFormularioAves($variable[3]);
			$this->tablaHtmlFormularioPostMortem($consulta, $arrayParametros, $modeloDetalleAnteMortem->current()->especie);
			require APP . 'InspeccionAntePostMortemCF/vistas/listaDetalleAnteAvesPostVista.php';
		}else{
			$consulta = $this->lNegocioFormularioAnteMortem->buscarAMDetalleFormularioAnimales($variable[3]);
			$this->tablaHtmlFormularioPostMortem($consulta, $arrayParametros, $modeloDetalleAnteMortem->current()->especie);
			require APP . 'InspeccionAntePostMortemCF/vistas/listaDetalleAnteAvesPostVista.php';
		}
	}

	/**
	 * Método para enviar a revision los formularios de animales
	 */
	public function enviarRevisionAnimales(){
		$respuesta = $this->lNegocioFormularioAnteMortem->guardar($_POST);
		if ($respuesta){
			echo json_encode(array(
				'estado' => 'EXITO',
				'mensaje' => 'Enviado a revisión'));
		}else{
			echo json_encode(array(
				'estado' => 'FALLO',
				'mensaje' => 'Error al enviar a revisión...!!'));
		}
	}

	/**
	 * Método para aprobar el formulario de animales
	 */
	public function aprobarFormularioAnimales(){
		$respuesta = $this->lNegocioFormularioAnteMortem->guardar($_POST);
		if ($respuesta){
			echo json_encode(array(
				'estado' => 'EXITO',
				'mensaje' => 'Formulario Aprobado'));
		}else{
			echo json_encode(array(
				'estado' => 'FALLO',
				'mensaje' => 'Error al aprobar el formulario...!!'));
		}
	}

	/**
	 * Método para enviar a revision los formularios de aves
	 */
	public function enviarRevisionAves(){
		$respuesta = $this->lNegocioFormularioPostMortem->guardar($_POST);
		if ($respuesta){
			echo json_encode(array(
				'estado' => 'EXITO',
				'mensaje' => 'Enviado a revisión'));
		}else{
			echo json_encode(array(
				'estado' => 'FALLO',
				'mensaje' => 'Error al enviar a revisión...!!'));
		}
	}

	/**
	 * Método para aprobar el formulario de animales
	 */
	public function aprobarFormularioAves(){
		$respuesta = $this->lNegocioFormularioPostMortem->guardar($_POST);
		if ($respuesta){
			echo json_encode(array(
				'estado' => 'EXITO',
				'mensaje' => 'Formulario Aprobado'));
		}else{
			echo json_encode(array(
				'estado' => 'FALLO',
				'mensaje' => 'Error al aprobar el formulario...!!'));
		}
	}

	/**
	 * visualizar certificado
	 */
	public function generarFormularioAnimales(){
		$this->modeloFormularioPostMortem = $this->lNegocioFormularioPostMortem->buscar($_POST['id_formulario_post_mortem']);
		$nombreArchivo = $this->modeloFormularioPostMortem->getIdFormularioPostMortem() . '_' . $this->modeloFormularioPostMortem->getCodigoFormulario() . '_' . $this->modeloFormularioPostMortem->getIdentificador();
		$arrayDatos = array(
			'titulo' => 'FORMULARIO DE INSPECCIÓN POST-MORTEM EN CENTROS DE FAENAMIENTO - RUMIANTES Y MONOGÁSTRICOS',
			'subtitulo' => 'COORDINACIÓN GENERAL DE INOCUIDAD DE ALIMENTOS',
			'seccionA' => 'A. IDENTIFICACIÓN DEL CENTRO DE FAENAMIENTO',
			'seccionB' => 'B. INSPECCIÓN POSTMORTEM',
			'seccionC' => 'C. FIRMAS DE RESPONSABILIDAD',
			'seccionFirma' => 'MÉDICO VETERINARIO OFICIAL O AUTORIZADO',
			'nombreArchivo' => $nombreArchivo,
			'id_formulario_post_mortem' => $_POST['id_formulario_post_mortem'],
			'idFormularioDetalle' => $_POST["idFormularioDetalle"],
			'fechaCreacion' => $this->modeloFormularioPostMortem->getFechaCreacion());
		
		$this->lNegocioFormularioPostMortem->crearExcel($arrayDatos);
		// $this->lNegocioFormularioAnteMortem->generarFormularioAnimales($arrayDatos);
		$this->urlExcel = INSP_FORM_AP_CF . "reportes/formulariosPM/" . $nombreArchivo . ".xlsx";
		
		$estado = 'EXITO';
		$mensaje = 'Formulario generado con exito';

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'ruta' => $this->urlExcel));
	}

	/**
	 * crear formulario de post mortem aves
	 */
	public function generarFormularioAves(){
		$this->modeloFormularioPostMortem = $this->lNegocioFormularioPostMortem->buscar($_POST['id_formulario_post_mortem']);
		$nombreArchivo = $this->modeloFormularioPostMortem->getIdFormularioPostMortem() . '_' . $this->modeloFormularioPostMortem->getCodigoFormulario() . '_' . $this->modeloFormularioPostMortem->getIdentificador();
		$arrayDatos = array(
			'titulo' => 'FORMULARIO DE INSPECCIÓN POST-MORTEM EN CENTROS DE FAENAMIENTO DE AVES',
			'subtitulo' => 'COORDINACIÓN GENERAL DE INOCUIDAD DE ALIMENTOS',
			'seccionA' => 'A. IDENTIFICACIÓN DEL CENTRO DE FAENAMIENTO',
			'seccionB' => 'B. INSPECCIÓN POSTMORTEM',
			'seccionC' => 'C. FIRMAS DE RESPONSABILIDAD',
			'seccionFirma' => 'MÉDICO VETERINARIO OFICIAL O AUTORIZADO',
			'id_formulario_post_mortem' => $_POST['id_formulario_post_mortem'],
			'nombreArchivo' => $nombreArchivo,
			'idFormularioDetalle' => $_POST["idFormularioDetalle"],
			'fechaCreacion' => $this->modeloFormularioPostMortem->getFechaCreacion());
		$this->lNegocioFormularioPostMortem->generarFormularioAves($arrayDatos);
		$this->urlPdf = INSP_FORM_AP_CF . "reportes/formulariosPM/" . $arrayDatos['nombreArchivo'] . ".pdf";
		$estado = 'EXITO';
		$mensaje = 'Formulario generado con exito';

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'ruta' => $this->urlPdf));
	}

	/**
	 * Método para agregar detalle de formulario de post aves
	 */
	public function agregarFormularioPostAves(){
		$valor = $this->lNegocioFormularioPostMortem->guardarDetallePostAves($_POST);
		$campos = explode('-', $valor);
		if ($campos[0]){
			echo json_encode(array(
				'estado' => 'EXITO',
				'mensaje' => 'Formulario ' . $campos[1] . ' correctamente',
				'contenido' => $campos[1],
				'id' => $campos[0],
				'idDetalle' => $campos[2]));
		}else{
			echo json_encode(array(
				'estado' => 'FALLO',
				'mensaje' => 'Error al guardar el formulario...!!'));
		}
	}

	/**
	 * Método para agregar detalle de formulario de post animales
	 */
	public function agregarFormularioPostAnimales(){
		$valor = $this->lNegocioFormularioPostMortem->guardarDetallePostAnimales($_POST);
		$campos = explode('-', $valor);
		if ($campos[0]){
			echo json_encode(array(
				'estado' => 'EXITO',
				'mensaje' => 'Formulario ' . $campos[1] . ' correctamente',
				'contenido' => '',
				'id' => $campos[0],
				'idDetalle' => $campos[2]));
		}else{
			echo json_encode(array(
				'estado' => 'FALLO',
				'mensaje' => 'Error al guardar el formulario...!!'));
		}
	}

	/**
	 * Método para agregar hallazgos en post mortem
	 */
	public function agregarHallazgos(){
		$html = "";
		$count = 0;
		foreach ($_POST['hallazgos'] as $item){
			$html .= "<tr id=" . $count . "><td>" . $item['enfermedad'] . "</td><td>" . $item['localizacion'] . "</td><td>" . $item['numAnimalAfec'] . "</td></tr>";
			$count ++;
		}
		echo json_encode(array(
			'estado' => 'EXITO',
			'mensaje' => 'Elemento agregado correctamente',
			'contenido' => $html));
	}

	// <td><button class='bEliminar icono' onclick='eliminarTr(this,1); return false; '></button></td>

	/**
	 * Método para agregar hallazgos en post mortem
	 */
	public function agregarOrganos(){
		$html = "";
		$count = 0;
		foreach ($_POST['organos'] as $item){
			$html .= "<tr id=" . $count . "><td>" . $item['numOrganoDecomiso'] . "</td><td>" . $item['organo'] . "</td><td>" . $item['razonDecomiso'] . "</td></tr>";
			$count ++;
		}
		echo json_encode(array(
			'estado' => 'EXITO',
			'mensaje' => 'Elemento agregado correctamente',
			'contenido' => $html));
	}

	/**
	 * Método para agregar hallazgos en post mortem
	 */
	public function agregarCanalesParcial(){
		$html = "";
		$count = 0;
		foreach ($_POST['decomisoParcial'] as $item){
			$html .= "<tr id=" . $count . "><td>" . $item['razonDecomiso'] . "</td><td>" . $item['numCanalesDecomisadas'] . "</td><td>" . $item['pesoCarneAprobada'] . "</td><td>" . $item['pesoCarneDecomisada'] . "</td></tr>";
			$count ++;
		}
		echo json_encode(array(
			'estado' => 'EXITO',
			'mensaje' => 'Elemento agregado correctamente',
			'contenido' => $html));
	}

	/**
	 * Método para agregar hallazgos en post mortem
	 */
	public function agregarCanalesTotal(){
		$html = "";
		$count = 0;
		foreach ($_POST['decomisoTotal'] as $item){
			$html .= "<tr id=" . $count . "><td>" . $item['razonDecomiso'] . "</td><td>" . $item['numCanalesDecomisadas'] . "</td><td>" . $item['pesoCarneDecomisada'] . "</td></tr>";
			$count ++;
		}
		echo json_encode(array(
			'estado' => 'EXITO',
			'mensaje' => 'Elemento agregado correctamente',
			'contenido' => $html));
	}
}

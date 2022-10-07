<?php
/**
 * Controlador FormularioAnteMortem
 *
 * Este archivo controla la lógica del negocio del modelo: FormularioAnteMortemModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2019-05-27
 * @uses FormularioAnteMortemControlador
 * @package InspeccionAntePostMortemCF
 * @subpackage Controladores
 */
namespace Agrodb\InspeccionAntePostMortemCF\Controladores;

use Agrodb\InspeccionAntePostMortemCF\Modelos\FormularioAnteMortemLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\FormularioAnteMortemModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetalleAnteAvesLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetalleAnteAvesModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetalleAnteAnimalesLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\DetalleAnteAnimalesModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAvesMuertasLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAvesMuertasModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAvesCaractLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAvesCaractModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAvesSistematicosLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAvesSistematicosModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAvesExternasLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAvesExternasModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAnimalesMuertosLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAnimalesMuertosModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAnimalesClinicosLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAnimalesClinicosModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAnimalesLocomocionLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\HallazgosAnimalesLocomocionModelo;
use Agrodb\InspeccionAntePostMortemCF\Modelos\FormularioPostMortemLogicaNegocio;

class FormularioAnteMortemControlador extends BaseControlador{

	private $lNegocioFormularioAnteMortem = null;

	private $modeloFormularioAnteMortem = null;

	private $lNegocioDetalleAnteAves = null;

	private $modeloDetalleAnteAves = null;

	private $lNegocioDetalleAnteAnimales = null;

	private $modeloDetalleAnteAnimales = null;

	// ************agregar modelo de hallazgos aves**
	private $lNegocioHallazgosAvesMuertas = null;

	private $modeloHallazgosAvesMuertas = null;

	private $lNegocioHallazgosAvesCaract = null;

	private $modeloHallazgosAvesCaract = null;

	private $lNegocioHallazgosSistematicos = null;

	private $modeloHallazgosSistematicos = null;

	private $lNegocioHallazgosAvesExternas = null;

	private $modeloHallazgosAvesExternas = null;

	// ************agregar modelo de hallazgos animales**
	private $lNegocioHallazgosAnimalesMuertos = null;

	private $modeloHallazgosAnimalesMuertos = null;

	private $lNegocioHallazgosAnimalesClinicos = null;

	private $modeloHallazgosAnimalesClinicos = null;

	private $lNegocioHallazgosAnimalesLocomocion = null;

	private $modeloHallazgosAnimalesLocomocion = null;

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

	private $agregarFormularioAntetAves = null;

	private $detalleFormulario = null;

	private $datosDetalleFormulario = null;

	private $arrayDetalleFormulario = null;

	private $idOperadorTipoOperacion = null;

	private $estadoRegistro = null;

	private $idFormularioEditar = null;

	private $perfilUsuario = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioFormularioAnteMortem = new FormularioAnteMortemLogicaNegocio();
		$this->modeloFormularioAnteMortem = new FormularioAnteMortemModelo();

		$this->lNegocioDetalleAnteAves = new DetalleAnteAvesLogicaNegocio();
		$this->modeloDetalleAnteAves = new DetalleAnteAvesModelo();
		$this->lNegocioDetalleAnteAnimales = new DetalleAnteAnimalesLogicaNegocio();
		$this->modeloDetalleAnteAnimales = new DetalleAnteAnimalesModelo();
		// ****************aves**************
		$this->lNegocioHallazgosAvesMuertas = new HallazgosAvesMuertasLogicaNegocio();
		$this->modeloHallazgosAvesMuertas = new HallazgosAvesMuertasModelo();
		$this->lNegocioHallazgosAvesCaract = new HallazgosAvesCaractLogicaNegocio();
		$this->modeloHallazgosAvesCaract = new HallazgosAvesCaractModelo();
		$this->lNegocioHallazgosSistematicos = new HallazgosAvesSistematicosLogicaNegocio();
		$this->modeloHallazgosSistematicos = new HallazgosAvesSistematicosModelo();
		$this->lNegocioHallazgosAvesExternas = new HallazgosAvesExternasLogicaNegocio();
		$this->modeloHallazgosAvesExternas = new HallazgosAvesExternasModelo();
		// *******************animales*****
		$this->lNegocioHallazgosAnimalesMuertos = new HallazgosAnimalesMuertosLogicaNegocio();
		$this->modeloHallazgosAnimalesMuertos = new HallazgosAnimalesMuertosModelo();
		$this->lNegocioHallazgosAnimalesClinicos = new HallazgosAnimalesClinicosLogicaNegocio();
		$this->modeloHallazgosAnimalesClinicos = new HallazgosAnimalesClinicosModelo();
		$this->lNegocioHallazgosAnimalesLocomocion = new HallazgosAnimalesLocomocionLogicaNegocio();
		$this->modeloHallazgosAnimalesLocomocion = new HallazgosAnimalesLocomocionModelo();

		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$this->articleCentroFaenamiento();
		require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioAnteMortemCfVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function listar(){
		$variable = explode('-', $_POST["id"]);
		$_POST["opcion"] = $variable[1];
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[0],
			'opcion' => $variable[1]);
		$consulta = $this->lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
		$this->nombreCF = $consulta->current()->razon_social;
		$this->botones = $this->crearAccionBotonesCF($arrayParametros);
		$this->detalleFormulario = 'Formularios pendientes';
		$this->articleMesesFormulariosCentroFaenamiento($arrayParametros);
		require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioAnteMortemVista.php';
	}

	/**
	 * Método para desplegar el detalle del formulario
	 */
	public function detalleListar(){
		$variable = explode('-', $_POST["id"]);
		$_POST["opcion"] = $variable[1];
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[0],
			'opcion' => $variable[1],
			'mes' => $variable[2],
			'identificador_operador' => $_SESSION["usuario"]);
		$consulta = $this->lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
		$this->nombreCF = $consulta->current()->razon_social;
		$this->detalleFormulario = 'Registro de formularios';
		$this->botones = $this->crearAccionBotonesCF($arrayParametros);
		$this->perfilUsuario();
		if($this->perfilUsuario == 'PFL_APM_CF_OP'){
		    $modeloDetalleAnteMortem = $this->lNegocioFormularioAnteMortem->buscarFormulariosCf($arrayParametros);
		}else{
			$modeloDetalleAnteMortem = $this->lNegocioFormularioAnteMortem->buscarFormulariosCfAux($arrayParametros);
		}
		$this->tablaHtmlDetalleFormularioAnteMortem($modeloDetalleAnteMortem, $arrayParametros);
		require APP . 'InspeccionAntePostMortemCF/vistas/listaDetalleAnteAvesVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->perfilUsuario();
		$variable = explode('-', $_POST["id"]);
		$_POST["opcion"] = $variable[1];
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[0],
			'opcion' => $variable[1]);
		$consulta = $this->lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
		$this->provincia = $consulta->current()->provincia;
		$this->canton = $consulta->current()->canton;
		$this->parroquia = $consulta->current()->parroquia;
		$this->razonSocial = $consulta->current()->razon_social;
		//$datos = $this->lNegocioFormularioAnteMortem->buscarDatosOperador($_SESSION['usuario']);
		
		$identifi = $this->lNegocioFormularioAnteMortem->buscarIdentificadorMedicoVeterinario($arrayParametros);
		$datos =  $this->lNegocioFormularioAnteMortem->buscarDatosOperador($identifi->current()->identificador_operador);
		
		$this->nombreMedico = $datos->current()->nombre_medico;
		$identifi = $this->lNegocioFormularioAnteMortem->buscarIdentificadorMedicoVeterinario($arrayParametros);
		$datos =  $this->lNegocioFormularioAnteMortem->buscarDatosOperador($identifi->current()->identificador_operador);
		$this->accion = "Nuevo Formulario de Inspección en Centros de Faenamiento";
		$this->fechaInicial = $this->lNegocioFormularioAnteMortem->fechaInicalAves(date('Y-m-d'));
		$this->idFormularioAnteMortem = '';
		$this->idCentroFaenamiento = $variable[0];
		$this->idOperadorTipoOperacion = $consulta->current()->id_operador_tipo_operacion;
		$this->urlPdf = '';
		if ($consulta->current()->especie == 'Avícola'){
			$this->comboEspecie = $this->comboProductos($this->idOperadorTipoOperacion, $consulta->current()->especie);
			require APP . 'InspeccionAntePostMortemCF/vistas/formularioDetalleAnteAvesVista.php';
		}else{
			$this->comboEspecie = $this->comboEspecie($consulta->current()->especie);
			require APP . 'InspeccionAntePostMortemCF/vistas/formularioDetalleAnteAnimalesVista.php';
		}
	}

	/**
	 * Método para registrar en la base de datos -FormularioAnteMortem
	 */
	public function guardar(){
		$this->lNegocioFormularioAnteMortem->guardar($_POST);
	}
	/**
	 * Método para devolver el la codificacion del perfil
	 */
	public function perfilUsuario(){
		$consulta = $this->lNegocioFormularioAnteMortem->verificarPerfil($_SESSION['usuario']);
		$this->perfilUsuario = $consulta->current()->codificacion_perfil;
	}
	
	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: FormularioAnteMortem
	 */
	public function editar(){
		$this->perfilUsuario();
		$this->urlPdf = '';
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
		
		$identifi = $this->lNegocioFormularioAnteMortem->buscarIdentificadorMedicoVeterinario($arrayParametros);
		$datos =  $this->lNegocioFormularioAnteMortem->buscarDatosOperador($identifi->current()->identificador_operador);
		
		//$datos = $this->lNegocioFormularioAnteMortem->buscarDatosOperador($_SESSION['usuario']);
		$this->nombreMedico = $datos->current()->nombre_medico;
		$this->fechaInicial = $this->lNegocioFormularioAnteMortem->fechaInicalAves(date('Y-m-d'));
		$this->idFormularioAnteMortem = $variable[0];
		$this->modeloFormularioAnteMortem = $this->lNegocioFormularioAnteMortem->buscar($this->idFormularioAnteMortem);

		if ($this->modeloFormularioAnteMortem->getEstado() == 'Aprobado_AM'){
			$this->accion = "Ver Formulario de Inspección en Centros de Faenamiento";
		}else{
			$this->accion = "Editar Formulario de Inspección en Centros de Faenamiento";
		}
		$this->idOperadorTipoOperacion = $consulta->current()->id_operador_tipo_operacion;
		$this->idCentroFaenamiento = $variable[1];
		if ($consulta->current()->especie == 'Avícola'){
			$this->comboEspecie = $this->comboProductos($consulta->current()->id_operador_tipo_operacion, $consulta->current()->especie);
			$this->datosDetalleFormulario = $this->generarDetalleFormularioAves($variable[0]);
			require APP . 'InspeccionAntePostMortemCF/vistas/formularioDetalleAnteAvesVista.php';
		}else{
			$this->comboEspecie = $this->comboEspecie($consulta->current()->especie);
			$this->datosDetalleFormulario = $this->generarDetalleFormularioAnimales($variable[0]);
			require APP . 'InspeccionAntePostMortemCF/vistas/formularioDetalleAnteAnimalesVista.php';
		}
	}

	/**
	 * Método para generar los detalles en formulario de aves
	 */
	public function generarDetalleFormularioAves($idFormularioAnteMortem){
		$consulta = $this->lNegocioFormularioAnteMortem->buscarDetalleFormularioAves($idFormularioAnteMortem);
		$count = 0;
		$html = '';
		foreach ($consulta as $item){
			$html .= "<tr id=" . $item['id_detalle_ante_aves'] . "><td>" . ++ $count . "</td><td>" . $item['num_csmi'] . "</td><td>" . $item['fecha_formulario'] . "</td><td>" . $item['tipo_ave'] . "</td><td><button id=" . $item['id_detalle_ante_aves'] . " class='bPrevisualizar icono' onclick='btnPrevisualizar(id); return false; '></button></td></tr>";
		}
		return $html;
	}

	/**
	 * Método para generar los detalles en formulario de animales
	 */
	public function generarDetalleFormularioAnimales($idFormularioAnteMortem){
		$consulta = $this->lNegocioFormularioAnteMortem->buscarDetalleFormularioAnimales($idFormularioAnteMortem);
		$count = 0;
		$html = '';
		foreach ($consulta as $item){
			$html .= "<tr id=" . $item['id_detalle_ante_animales'] . "><td>" . ++ $count . "</td><td>" . $item['fecha_formulario'] . "</td><td>" . $item['especie'] . "</td><td><button id=" . $item['id_detalle_ante_animales'] . " class='bPrevisualizar icono' onclick='btnPrevisualizar(id); return false; '></button></td></tr>";
		}
		return $html;
	}

	/**
	 * Método para borrar un registro en la base de datos - FormularioAnteMortem
	 */
	public function borrar(){
		$this->lNegocioFormularioAnteMortem->borrar($_POST['elementos']);
	}

	/**
	 * Método para crear los articulos de centros de faenamiento
	 */
	public function articleCentroFaenamiento(){
		$arrayParametros = array(
			'identificador_operador' => $_SESSION["usuario"]);
		$consulta = $this->lNegocioFormularioAnteMortem->buscarCfAsignados($arrayParametros);
		$contador = 0;
		foreach ($consulta AS $fila){
			$arrayIndex = " id_centro_faenamiento=" . $fila['id_centro_faenamiento'] . " order by 1";
			$lista = $this->lNegocioFormularioAnteMortem->buscarLista($arrayIndex);
			$estado = '';
			foreach ($lista as $item){
				if ($item['estado'] == 'Registrado' || $item['estado'] == 'Por revisar'){
					$estado = 'Con pendientes';
					break;
				}else{
					$estado = 'Sin pendientes';
				}
			}
			$arrayParametros = array(
				'id' => $fila['id_centro_faenamiento'] . '-' . $_POST["opcion"],
				'rutaAplicacion' => URL_MVC_FOLDER . 'InspeccionAntePostMortemCF',
				'opcion' => 'formularioAnteMortem/listar',
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
	public function aprobarFormularioAves(){
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
	 * Método para crear los articulos por meses de los formularios del centro de faenamiento
	 */
	public function articleMesesFormulariosCentroFaenamiento($arrayParametros){
		$this->perfilUsuario();
		$arrayConsulta = array(
			'identificador_operador' => $_SESSION["usuario"],
			'id_centro_faenamiento' => $arrayParametros['id_centro_faenamiento']);
		if($this->perfilUsuario == 'PFL_APM_CF_OP'){
			$lista = $this->lNegocioFormularioAnteMortem->buscarFormulariosCfXMes($arrayConsulta);
		}else{
			$lista = $this->lNegocioFormularioAnteMortem->buscarFormulariosCfXMesAux($arrayConsulta);
		}
		$contador = 0;
		foreach ($lista as $fila){
			$arrayDetalle = array(
				'id' => $arrayParametros['id_centro_faenamiento'] . '-' . $arrayParametros['opcion'] . '-' . $fila['mes'],
				'rutaAplicacion' => URL_MVC_FOLDER . 'InspeccionAntePostMortemCF',
				'opcion' => 'formularioAnteMortem/detalleListar',
				'destino' => 'listadoItems',
				'contador' => ++ $contador,
				'estado' => 'Pendientes',
				'texto1' => $this->lNegocioFormularioAnteMortem->mesEnLetras($fila['mes']),
				'numero' => $fila['cantidad']);
			$this->article .= $this->articleComun($arrayDetalle, 3);
		}
	}

	/**
	 * Método para agregar detalle de formulario de aves
	 */
	public function agregarFormularioAves(){
		$valor = $this->lNegocioFormularioAnteMortem->guardarDetalleAves($_POST);
		if ($valor){
			$contenido = $this->generarDetalleFormularioAves($valor);
			echo json_encode(array(
				'estado' => 'EXITO',
				'mensaje' => 'Detalle agregado correctamente',
				'contenido' => $contenido,
				'id' => $valor));
		}else{
			echo json_encode(array(
				'estado' => 'FALLO',
				'mensaje' => 'Error al guardar el detalle...!!'));
		}
	}

	/**
	 * Método para actualizar detalles en formularios de aves
	 */
	public function actualizarDetalleFormularioAves(){
		$valor = $this->lNegocioDetalleAnteAves->actualizarDatos($_POST);
		if ($valor){
			$contenido = $this->generarDetalleFormularioAves($_POST['id_formulario_ante_mortem']);
			echo json_encode(array(
				'estado' => 'EXITO',
				'mensaje' => 'Datos actualizados correctamente',
				'contenido' => $contenido));
		}else{
			echo json_encode(array(
				'estado' => 'FALLO',
				'mensaje' => 'Error al actualizar el detalle...!!'));
		}
	}

	/**
	 * Muestra el modal de previsualizacion de detalle de formulario de aves
	 */
	public function detalleFormularioAvesPrevisualizar(){
		$this->perfilUsuario();
		$arrayIndex = "id_detalle_ante_aves=" . $_POST['id_detalle_ante_aves'] . " order by 1";
		$consulta = $this->lNegocioDetalleAnteAves->buscarLista($arrayIndex);
		$this->arrayDetalleFormulario = $consulta->current();
		$this->modeloDetalleAnteAves->setOptions((array) $consulta->current());
		$especie = $this->lNegocioDetalleAnteAves->buscarEspecieXDetalleFormularioAves($_POST['id_detalle_ante_aves']);
		$especie = $especie->current();
		if ($this->arrayDetalleFormulario->hallazgos == 'Si'){
			if ($this->arrayDetalleFormulario->id_hallazgos_aves_muertas != ''){
				$this->modeloHallazgosAvesMuertas = $this->lNegocioHallazgosAvesMuertas->buscar($this->arrayDetalleFormulario->id_hallazgos_aves_muertas);
			}
			if ($this->arrayDetalleFormulario->id_hallazgos_aves_caract != ''){
				$this->modeloHallazgosAvesCaract = $this->lNegocioHallazgosAvesCaract->buscar($this->arrayDetalleFormulario->id_hallazgos_aves_caract);
			}
			if ($this->arrayDetalleFormulario->id_hallazgos_aves_sistematicos != ''){
				$this->modeloHallazgosSistematicos = $this->lNegocioHallazgosSistematicos->buscar($this->arrayDetalleFormulario->id_hallazgos_aves_sistematicos);
			}
			if ($this->arrayDetalleFormulario->id_hallazgos_aves_externas != ''){
				$this->modeloHallazgosAvesExternas = $this->lNegocioHallazgosAvesExternas->buscar($this->arrayDetalleFormulario->id_hallazgos_aves_externas);
			}
		}
		$this->estadoRegistro = $_POST['estadoRegistro'];
		$this->comboEspecie = $this->comboProductos($especie->id_operador_tipo_operacion, $especie->especie, $this->arrayDetalleFormulario->tipo_ave);
		$this->arrayDetalleFormulario->fecha_formulario = $this->lNegocioFormularioAnteMortem->formatearFecha($this->arrayDetalleFormulario->fecha_formulario);
		$this->fechaInicial = $this->lNegocioFormularioAnteMortem->fechaInicalAves(date('Y-m-d'));
		require APP . 'InspeccionAntePostMortemCF/vistas/formularioDetalleRegistroAves.php';
	}

	/**
	 * Construye el código HTML para desplegar la lista de - FormularioAnteMortem
	 */
	public function tablaHtmlDetalleFormularioAnteMortem($tabla, $arrayParametros){
		$contador = 0;
		foreach ($tabla as $fila){
			$this->itemsFiltrados[] = array(
				'<tr id="' . $fila['id_formulario_ante_mortem'] . '-' . $arrayParametros['id_centro_faenamiento'] . '-' . $arrayParametros['opcion'] . '"
		  class="item" 
          data-rutaAplicacion="' . URL_MVC_FOLDER . 'InspeccionAntePostMortemCF\formularioAnteMortem"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['estado'] . '</b></td>
			<td>' . $fila['fecha_creacion'] . '</td>
			<td>' . $fila['codigo_formulario'] . '</td>
			</tr>');
		}
	}

	/**
	 * Método para crear combo de especie
	 */
	public function comboEspecie($especie, $seleccion = null){
		$variable = explode(',', $especie);

		$listEspecie = '<option value="">Seleccione...</option>';

		foreach ($variable as $item){
			if ($seleccion == $item){
				$listEspecie .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$listEspecie .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $listEspecie;
	}

	/**
	 * Método para crear combo de productos
	 */
	public function comboProductos($idOperadorTipoOperacion, $especie, $seleccion = null){
		$consulta = $this->lNegocioFormularioAnteMortem->buscarDatosProductos($idOperadorTipoOperacion, $especie);

		$listProducto = '<option value="">Seleccione...</option>';

		foreach ($consulta as $item){
			if ($seleccion == $item['nombre_producto']){
				$listProducto .= '<option value="' . $item['nombre_producto'] . '" selected>' . $item['nombre_producto'] . '</option>';
			}else{
				$listProducto .= '<option value="' . $item['nombre_producto'] . '">' . $item['nombre_producto'] . '</option>';
			}
		}
		return $listProducto;
	}

	/**
	 * Construye el código HTML para buscar productos segun especie
	 */
	public function buscarProductosXespecie(){
		echo $this->comboProductos($_POST['idOperadorTipoOperacion'], $_POST['especie']);
	}

	/**
	 * Método para agregar detalle en formularios de animales
	 */
	public function agregarFormularioAnimales(){
		$valor = $this->lNegocioFormularioAnteMortem->guardarDetalleAnimales($_POST);
		if ($valor != ''){
			$contenido = $this->generarDetalleFormularioAnimales($valor);
			echo json_encode(array(
				'estado' => 'EXITO',
				'mensaje' => 'Detalle agregado correctamente',
				'contenido' => $contenido,
				'id' => $valor));
		}else{
			echo json_encode(array(
				'estado' => 'FALLO',
				'mensaje' => 'Error al guardar el detalle...!!'));
		}
	}

	/**
	 * Método para actualizar detalles en formularios de aves
	 */
	public function actualizarDetalleFormularioAnimales(){
		$valor = $this->lNegocioDetalleAnteAnimales->actualizarDatos($_POST);
		if ($valor){
			$contenido = $this->generarDetalleFormularioAnimales($_POST['id_formulario_ante_mortem']);
			echo json_encode(array(
				'estado' => 'EXITO',
				'mensaje' => 'Datos actualizados correctamente',
				'contenido' => $contenido));
		}else{
			echo json_encode(array(
				'estado' => 'FALLO',
				'mensaje' => 'Error al actualizar el detalle...!!'));
		}
	}

	/**
	 * Muestra el modal de previsualizacion de detalle de formulario de aves
	 */
	public function detalleFormularioAnimalesPrevisualizar(){
		$arrayIndex = "id_detalle_ante_animales=" . $_POST['id_detalle_ante_animales'] . " order by 1";
		$consulta = $this->lNegocioDetalleAnteAnimales->buscarLista($arrayIndex);
		$this->modeloDetalleAnteAnimales->setOptions((array) $consulta->current());
		$especie = $this->lNegocioDetalleAnteAnimales->buscarEspecieXDetalleFormularioAnimales($_POST['id_detalle_ante_animales']);
		$especie = $especie->current();
		if ($this->modeloDetalleAnteAnimales->getHallazgos() == 'Si'){
			if ($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesMuertos() != ''){
				$this->modeloHallazgosAnimalesMuertos = $this->lNegocioHallazgosAnimalesMuertos->buscar($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesMuertos());
			}
			if ($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesClinicos() != ''){
				$this->modeloHallazgosAnimalesClinicos = $this->lNegocioHallazgosAnimalesClinicos->buscar($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesClinicos());
			}
			if ($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesLocomocion() != ''){
				$arrayLocomocion = "id_hallazgos_animales_locomocion=" . $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesLocomocion() . "";
				$consulta = $this->lNegocioHallazgosAnimalesLocomocion->buscarLista($arrayLocomocion);
				$this->modeloHallazgosAnimalesLocomocion->setNumAnimalesCogera($consulta->current()->num_animales_cojera);
				$this->modeloHallazgosAnimalesLocomocion->setNumAnimalesAmbulatorios($consulta->current()->num_animales_ambulatorios);
			}
		}
		$this->estadoRegistro = $_POST['estadoRegistro'];
		$this->comboEspecie = $this->comboEspecie($especie->especie, $this->modeloDetalleAnteAnimales->getEspecie());
		$this->comboProducto = $this->comboProductos($especie->id_operador_tipo_operacion, $this->modeloDetalleAnteAnimales->getEspecie(), $this->modeloDetalleAnteAnimales->getCategoriaEtaria());
		$this->modeloDetalleAnteAnimales->setFechaFormulario($this->lNegocioFormularioAnteMortem->formatearFecha($this->modeloDetalleAnteAnimales->getFechaFormulario()));
		$this->fechaInicial = $this->lNegocioFormularioAnteMortem->fechaInicalAves(date('Y-m-d'));
		require APP . 'InspeccionAntePostMortemCF/vistas/formularioDetalleRegistroAnimales.php';
	}

	/**
	 * generar formulario de post animales 
	 */
	public function generarFormularioAnimales(){
		$this->modeloFormularioAnteMortem = $this->lNegocioFormularioAnteMortem->buscar($_POST['id_formulario_ante_mortem']);
		$nombreArchivo = $this->modeloFormularioAnteMortem->getIdFormularioAnteMortem() . '_' . $this->modeloFormularioAnteMortem->getCodigoFormulario() . '_' . $this->modeloFormularioAnteMortem->getIdentificador();
		$arrayDatos = array(
			'titulo' => 'FORMULARIO DE INSPECCIÓN ANTE-MORTEM EN CENTROS DE FAENAMIENTO - RUMIANTES Y MONOGÁSTRICOS',
			'subtitulo' => 'COORDINACIÓN GENERAL DE INOCUIDAD DE ALIMENTOS',
			'seccionA' => 'A. IDENTIFICACIÓN DEL CENTRO DE FAENAMIENTO',
			'seccionB' => 'B. INSPECCIÓN ANTEMORTEM',
			'seccionC' => 'C. FIRMAS DE RESPONSABILIDAD',
			'seccionFirma' => 'MÉDICO VETERINARIO OFICIAL O AUTORIZADO',
			'id_formulario_ante_mortem' => $_POST['id_formulario_ante_mortem'],
			'nombreArchivo' => $nombreArchivo,
			'idFormularioDetalle' => $_POST["idFormularioDetalle"],
			'fechaCreacion' => $this->modeloFormularioAnteMortem->getFechaCreacion());

		$this->lNegocioFormularioAnteMortem->generarFormularioAnimales($arrayDatos);
		$this->urlPdf = INSP_FORM_AP_CF . "reportes/formulariosAM/" . $nombreArchivo . ".pdf";
		$_POST['ruta_archivo']=$this->urlPdf;
		$this->lNegocioFormularioAnteMortem->guardar($_POST);
		$estado = 'EXITO';
		$mensaje = 'Formulario generado con exito';
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'ruta' => $this->urlPdf));
	}

	/**
	 * crear formulario de ante mortem aves
	 */
	public function generarFormularioAves(){
		$this->modeloFormularioAnteMortem = $this->lNegocioFormularioAnteMortem->buscar($_POST['id_formulario_ante_mortem']);
		$nombreArchivo = $this->modeloFormularioAnteMortem->getIdFormularioAnteMortem() . '_' . $this->modeloFormularioAnteMortem->getCodigoFormulario() . '_' . $this->modeloFormularioAnteMortem->getIdentificador();
		$arrayDatos = array(
			'titulo' => 'FORMULARIO DE INSPECCIÓN ANTE-MORTEM EN CENTROS DE FAENAMIENTO DE AVES',
			'subtitulo' => 'COORDINACIÓN GENERAL DE INOCUIDAD DE ALIMENTOS',
			'seccionA' => 'A. IDENTIFICACIÓN DEL CENTRO DE FAENAMIENTO',
			'seccionB' => 'B. INSPECCIÓN ANTEMORTEM',
			'seccionC' => 'C. FIRMAS DE RESPONSABILIDAD',
			'seccionFirma' => 'MÉDICO VETERINARIO OFICIAL O AUTORIZADO',
			'id_formulario_ante_mortem' => $_POST['id_formulario_ante_mortem'],
			'nombreArchivo' => $nombreArchivo,
			'idFormularioDetalle' => $_POST["idFormularioDetalle"],
			'fechaCreacion' => $this->modeloFormularioAnteMortem->getFechaCreacion());
		$this->lNegocioFormularioAnteMortem->generarFormularioAves($arrayDatos);
		$this->urlPdf = INSP_FORM_AP_CF . "reportes/formulariosAM/" . $nombreArchivo . ".pdf";
		$_POST['ruta_archivo']=$this->urlPdf;
		$this->lNegocioFormularioAnteMortem->guardar($_POST);
		$estado = 'EXITO';
		$mensaje = 'Formulario generado con exito';

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'ruta' => $this->urlPdf));
	}
	
	/**
	 * proceso automatico para aprobar formulario ante mortem
	 */
	public function procesoAutomatico(){
		echo "\n" . 'Proceso Automatico de aprobación de formularios ante mortem' . "\n" . "\n";
		$arrayIndex = "estado in ('Registrado','Por revisar') order by 1";
		$modeloAnteMortem = $this->lNegocioFormularioAnteMortem->buscarLista($arrayIndex);
		$arrayGuardar = array();
		$arrayEmail = array();
		foreach ($modeloAnteMortem as $fila){
			$arrayGuardar = array(
				'id_formulario_ante_mortem' => $fila['id_formulario_ante_mortem'],
				'estado' => 'Aprobado_AM');
			$this->lNegocioFormularioAnteMortem->guardar($arrayGuardar);
			
			$arrayParametros = array(
				'id_centro_faenamiento' => $fila['id_centro_faenamiento']);
			$consulta = $this->lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
			$arrayEmail = array(
				'id_formulario_ante_mortem' => $fila['id_formulario_ante_mortem'],
				'centroFaenamiento' => $consulta->current()->razon_social,
				'codigoFormulario' => $fila['codigo_formulario'],
				'fechaFormulario' => $fila['fecha_creacion'],
				'especie' => $fila['especie'],
				'identificador' => $fila['identificador']
			);
			
			$this->lNegocioFormularioAnteMortem->notificarEmail($arrayEmail);
			echo $fila['identificador'] . '->formulario ante mortem aprobado automaticamente especie(' .$fila['especie']. ') id_formulario (' .$fila['id_formulario_ante_mortem']. ') '."\n";
		}
		echo "\n";
		
		echo "\n" . 'Proceso Automatico de aprobación de formularios post mortem' . "\n" . "\n";
		$arrayIndex = "estado in ('Registrado') order by 1";
		$lnFormularioPostMortem = new FormularioPostMortemLogicaNegocio();
		$modeloAnteMortem = $lnFormularioPostMortem->buscarLista($arrayIndex);
		foreach ($modeloAnteMortem as $fila){
			$arrayGuardar = array(
				'id_formulario_post_mortem' => $fila['id_formulario_post_mortem'],
				'estado' => 'Aprobado_PM');
			$lnFormularioPostMortem->guardar($arrayGuardar);
			echo $fila['identificador'] . '->formulario post mortem aprobado automaticamente id formulario(' .$fila['id_formulario_post_mortem']. ')'."\n";
		}
		echo "\n";
	}
}

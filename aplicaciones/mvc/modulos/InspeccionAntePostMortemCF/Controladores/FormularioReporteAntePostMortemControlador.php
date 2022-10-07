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
use Agrodb\InspeccionAntePostMortemCF\Modelos\FormularioReporteAntePostMortemLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\FormularioPostMortemLogicaNegocio;
use Agrodb\InspeccionAntePostMortemCF\Modelos\FormularioPostMortemModelo;

class FormularioReporteAntePostMortemControlador extends BaseControlador{

	private $lNegocioFormularioReporteAntePostMortem = null;

	private $lNegocioFormularioAnteMortem = null;

	private $modeloFormularioAnteMortem = null;

	private $article = null;

	private $panelBusqueda = null;

	private $idProceso = null;

	private $fechaInicial = null;

	private $fechaFinal = null;

	private $url = null;

	private $opcion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioFormularioReporteAntePostMortem = new FormularioReporteAntePostMortemLogicaNegocio();

		$this->lNegocioFormularioAnteMortem = new FormularioAnteMortemLogicaNegocio();
		$this->modeloFormularioAnteMortem = new FormularioAnteMortemModelo();

		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		// $this->articleCentroFaenamiento();
		require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioReporteAntePostMortemVista.php';
	}

	/**
	 * Método de inicio del controlador aprobados
	 */
	public function aprobados(){
		$this->articleAnios();
		require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioReporteAntePostMortemVista.php';
	}

	/**
	 * Método de inicio del controlador mensual
	 */
	public function mensual(){
		$this->filtroBusqueda(3);
		$this->perfilUsuario();
		if ($this->perfilUsuario == 'PFL_APM_CF_OP' || $this->perfilUsuario == 'PFL_APM_CF_TP'){
			$this->filtroBusqueda(4);
			$this->opcion = 0;
		}else{
			$this->filtroBusqueda(3);
			$this->opcion = 1;
		}
		require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioReporteAntePostMortemMensualVista.php';
	}

	/**
	 * Método de visulizar archivos del controlador
	 */
	public function visualizarInfo(){
		$this->accion = 'Reporte Formularios';
		$seleccionar = explode('-', $_POST["id"]);

		if ($seleccionar[1] == 'A'){
			$this->modeloFormularioAnteMorte = $this->lNegocioFormularioAnteMortem->buscar($seleccionar[0]);

			if ($this->modeloFormularioAnteMorte->getRutaArchivo() == ''){
				if ($this->modeloFormularioAnteMorte->getEspecie() == 'Avícola'){
					
					$idFormulario = '0-' . $this->modeloFormularioAnteMorte->getIdCentroFaenamiento();
					$nombreArchivo = $this->modeloFormularioAnteMorte->getIdFormularioAnteMortem() . '_' . $this->modeloFormularioAnteMorte->getCodigoFormulario() . '_' . $this->modeloFormularioAnteMorte->getIdentificador();
					$arrayDatos = array(
						'titulo' => 'FORMULARIO DE INSPECCIÓN ANTE-MORTEM EN CENTROS DE FAENAMIENTO DE AVES',
						'subtitulo' => 'COORDINACIÓN GENERAL DE INOCUIDAD DE ALIMENTOS',
						'seccionA' => 'A. IDENTIFICACIÓN DEL CENTRO DE FAENAMIENTO',
						'seccionB' => 'B. INSPECCIÓN ANTEMORTEM',
						'seccionC' => 'C. FIRMAS DE RESPONSABILIDAD',
						'seccionFirma' => 'MÉDICO VETERINARIO OFICIAL O AUTORIZADO',
						'id_formulario_ante_mortem' => $seleccionar[0],
						'nombreArchivo' => $nombreArchivo,
						'idFormularioDetalle' => $idFormulario,
						'fechaCreacion' => $this->modeloFormularioAnteMorte->getFechaCreacion());
					$this->lNegocioFormularioAnteMortem->generarFormularioAves($arrayDatos);
					$this->url = INSP_FORM_AP_CF . "reportes/formulariosAM/" . $nombreArchivo . ".pdf";
				}else{
					$idFormulario = '0-' . $this->modeloFormularioAnteMorte->getIdCentroFaenamiento();
					$nombreArchivo = $this->modeloFormularioAnteMorte->getIdFormularioAnteMortem() . '_' . $this->modeloFormularioAnteMorte->getCodigoFormulario() . '_' . $this->modeloFormularioAnteMorte->getIdentificador();
					$arrayDatos = array(
						'titulo' => 'FORMULARIO DE INSPECCIÓN ANTE-MORTEM EN CENTROS DE FAENAMIENTO - RUMIANTES Y MONOGÁSTRICOS',
						'subtitulo' => 'COORDINACIÓN GENERAL DE INOCUIDAD DE ALIMENTOS',
						'seccionA' => 'A. IDENTIFICACIÓN DEL CENTRO DE FAENAMIENTO',
						'seccionB' => 'B. INSPECCIÓN ANTEMORTEM',
						'seccionC' => 'C. FIRMAS DE RESPONSABILIDAD',
						'seccionFirma' => 'MÉDICO VETERINARIO OFICIAL O AUTORIZADO',
						'id_formulario_ante_mortem' => $seleccionar[0],
						'nombreArchivo' => $nombreArchivo,
						'idFormularioDetalle' => $idFormulario,
						'fechaCreacion' => $this->modeloFormularioAnteMorte->getFechaCreacion());

					$this->lNegocioFormularioAnteMortem->generarFormularioAnimales($arrayDatos);
					$this->url = INSP_FORM_AP_CF . "reportes/formulariosAM/" . $nombreArchivo . ".pdf";
				}

				$_POST['id_formulario_ante_mortem'] = $seleccionar[0];
				$_POST['ruta_archivo'] = $this->url;
				$this->lNegocioFormularioAnteMortem->guardar($_POST);
			}else{
				$this->url = $this->modeloFormularioAnteMorte->getRutaArchivo();
			}
		}else{

			$lnFormularioPostMortem = new FormularioPostMortemLogicaNegocio();
			$modeloFormularioPostMortem = new FormularioPostMortemModelo();
			$modeloFormularioPostMortem = $lnFormularioPostMortem->buscar($seleccionar[0]);
			if ($modeloFormularioPostMortem->getRutaArchivo() == ''){
				if ($seleccionar[2] == 'Avícola'){
					$idFormulario = '0-' . $seleccionar[3];
					$nombreArchivo = $modeloFormularioPostMortem->getIdFormularioPostMortem() . '_' . $modeloFormularioPostMortem->getCodigoFormulario() . '_' . $modeloFormularioPostMortem->getIdentificador();
					$arrayDatos = array(
						'titulo' => 'FORMULARIO DE INSPECCIÓN POST-MORTEM EN CENTROS DE FAENAMIENTO DE AVES',
						'subtitulo' => 'COORDINACIÓN GENERAL DE INOCUIDAD DE ALIMENTOS',
						'seccionA' => 'A. IDENTIFICACIÓN DEL CENTRO DE FAENAMIENTO',
						'seccionB' => 'B. INSPECCIÓN POSTMORTEM',
						'seccionC' => 'C. FIRMAS DE RESPONSABILIDAD',
						'seccionFirma' => 'MÉDICO VETERINARIO OFICIAL O AUTORIZADO',
						'id_formulario_post_mortem' => $modeloFormularioPostMortem->getIdFormularioPostMortem(),
						'nombreArchivo' => $nombreArchivo,
						'idFormularioDetalle' => $idFormulario,
						'fechaCreacion' => $modeloFormularioPostMortem->getFechaCreacion());
					$lnFormularioPostMortem->generarFormularioAves($arrayDatos);
					$this->url = INSP_FORM_AP_CF . "reportes/formulariosPM/" . $arrayDatos['nombreArchivo'] . ".pdf";
				}else{
					$idFormulario = '0-' . $seleccionar[3];
					$nombreArchivo = $modeloFormularioPostMortem->getIdFormularioPostMortem() . '_' . $modeloFormularioPostMortem->getCodigoFormulario() . '_' . $modeloFormularioPostMortem->getIdentificador();
					$arrayDatos = array(
						'titulo' => 'FORMULARIO DE INSPECCIÓN POST-MORTEM EN CENTROS DE FAENAMIENTO - RUMIANTES Y MONOGÁSTRICOS',
						'subtitulo' => 'COORDINACIÓN GENERAL DE INOCUIDAD DE ALIMENTOS',
						'seccionA' => 'A. IDENTIFICACIÓN DEL CENTRO DE FAENAMIENTO',
						'seccionB' => 'B. INSPECCIÓN POSTMORTEM',
						'seccionC' => 'C. FIRMAS DE RESPONSABILIDAD',
						'seccionFirma' => 'MÉDICO VETERINARIO OFICIAL O AUTORIZADO',
						'nombreArchivo' => $nombreArchivo,
						'id_formulario_post_mortem' => $modeloFormularioPostMortem->getIdFormularioPostMortem(),
						'idFormularioDetalle' => $idFormulario,
						'fechaCreacion' => $modeloFormularioPostMortem->getFechaCreacion()
					);
					$lnFormularioPostMortem->crearExcel($arrayDatos);
					$this->url = INSP_FORM_AP_CF . "reportes/formulariosPM/" . $nombreArchivo . ".xlsx";
				}
				$_POST['id_formulario_post_mortem'] = $seleccionar[0];
				$_POST['ruta_archivo'] = $this->url;
				$lnFormularioPostMortem->guardar($_POST);
			}else{
				$this->url = $modeloFormularioPostMortem->getRutaArchivo();
			}
		}
		require APP . 'InspeccionAntePostMortemCF/vistas/formularioVisualizacionAntePostMortemVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function listar(){
		$this->idProceso = $_POST["id"];
		$variable = explode('-', $_POST["id"]);
		$_POST["opcion"] = $variable[1];
		$arrayParametros = array(
			'mes' => $variable[0],
			'anio' => $variable[1],
			'estado' => 'Aprobado_AM',
			'opcion' => $variable[2],
			'identificador_operador' => $_SESSION["usuario"]);
		$this->perfilUsuario();
		if ($this->perfilUsuario == 'PFL_APM_CF_OP'){
			$this->filtroBusqueda(2);
			$this->opcion = 0;
		}else{
			unset($arrayParametros["identificador_operador"]);
			$this->filtroBusqueda(1);
			$this->opcion = 1;
		}
		$consulta = $this->lNegocioFormularioAnteMortem->buscarDetalleFormulariosXAnio($arrayParametros);
		$this->fechaInicial = $variable[1] . '-' . $variable[0] . '-01';
		$this->fechaFinal = $variable[1] . '-' . $variable[0] . '-' . $this->getUltimoDiaMes($variable[1], $variable[0]);
		$this->detalleFormulario = 'Lista de formularios aprobados';
		$this->tablaHtmlFormularioReporte($consulta);
		require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioFiltroAntePostMortemVista.php';
	}

	/**
	 * Método para buscra formulario mensual
	 */
	public function generarReporteMensual(){
		$arrayParametros = array(
			'provincia' => $_POST["provincia"],
			'rucFaenamiento' => $_POST["rucFaenamiento"],
			'fechaInicio' => $_POST["fechaInicio"],
			'fechaFin' => $_POST["fechaFin"],
			'identificador_operador' => $_SESSION["usuario"]);
		$this->perfilUsuario();
		if ($this->perfilUsuario == 'PFL_APM_CF_OP'){
		}else{
			unset($arrayParametros["identificador_operador"]);
		}

		// print_r($arrayParametros);
		// $consulta = $this->lNegocioFormularioAnteMortem->buscarDetalleFormulariosXAnio($arrayParametros);

		// require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioFiltroAntePostMortemVista.php';
	}

	/**
	 * Método para devolver el dia del mes
	 */
	function getUltimoDiaMes($elAnio, $elMes){
		return date("d", (mktime(0, 0, 0, $elMes + 1, 1, $elAnio) - 1));
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function filtrarInformacion(){
		$variable = explode('-', $_POST["idProceso"]);
		$_POST["opcion"] = $variable[1];
		$arrayParametros = array(
			'filtro' => 'si',
			'mes' => $variable[0],
			'anio' => $variable[1],
			'estado' => 'Aprobado_AM',
			'opcion' => $variable[2],
			'identificador_operador' => $_SESSION["usuario"],
			'fecha' => ($_POST['fecha'] == '') ? "null" : date("d", strtotime($_POST['fecha'])),
			'provinvia' => ($_POST['provincia'] == 'Seleccionar...') ? "null" : "'" . $_POST['provincia'] . "'",
			'cFaenamiento' => ($_POST['cFaenamiento'] == '') ? "null" : "'%" . $_POST['cFaenamiento'] . "%'",
			'csmi' => ($_POST['csmi'] == '') ? "null" : $_POST['csmi'],
			'codFormulario' => ($_POST['codFormulario'] == '') ? "null" : "'" . $_POST['codFormulario'] . "'",
			'especie' => ($_POST['tipo_especie'] == '') ? "null" : "'%" . $_POST['tipo_especie'] . "%'");
		$this->perfilUsuario();
		if ($this->perfilUsuario == 'PFL_APM_CF_OP'){
			$this->filtroBusqueda(2);
		}else{
			unset($arrayParametros["identificador_operador"]);
			$this->filtroBusqueda(1);
		}
		
		$consulta = $this->lNegocioFormularioAnteMortem->buscarDetalleFormulariosXAnio($arrayParametros);
		$this->detalleFormulario = 'Lista de formularios aprobados';
		$this->tablaHtmlFormularioReporte($consulta);
		echo \Zend\Json\Json::encode($this->itemsFiltrados);
		exit();
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
		if ($this->perfilUsuario == 'PFL_APM_CF_OP'){
			$modeloDetalleAnteMortem = $this->lNegocioFormularioAnteMortem->buscarFormulariosCf($arrayParametros);
		}else{
			$modeloDetalleAnteMortem = $this->lNegocioFormularioAnteMortem->buscarFormulariosCfAux($arrayParametros);
		}
		$this->tablaHtmlDetalleFormularioAnteMortem($modeloDetalleAnteMortem, $arrayParametros);
		require APP . 'InspeccionAntePostMortemCF/vistas/listaDetalleAnteAvesVista.php';
	}

	/**
	 * Método para desplegar el detalle del formulario por meses
	 */
	public function listarMeses(){
		$variable = explode('-', $_POST["id"]);
		$_POST["opcion"] = $variable[1];
		$arrayParametros = array(
			'anio' => $variable[0],
			'identificador_operador' => $_SESSION["usuario"],
			'estado' => 'Aprobado_AM');
		$this->perfilUsuario();
		if ($this->perfilUsuario != 'PFL_APM_CF_OP'){
			unset($arrayParametros["identificador_operador"]);
		}
		$consulta = $this->lNegocioFormularioAnteMortem->buscarFormulariosCfXMeses($arrayParametros);
		$this->detalleFormulario = 'Registro de formularios';
		$this->articleMeses($consulta, $variable[0]);
		require APP . 'InspeccionAntePostMortemCF/vistas/listaFormularioReporteAntePostMortemVista.php';
	}

	/**
	 * Método para devolver la codificacion del perfil
	 */
	public function perfilUsuario(){
		$consulta = $this->lNegocioFormularioAnteMortem->verificarPerfil($_SESSION['usuario']);
		$this->perfilUsuario = $consulta->current()->codificacion_perfil;
	}

	/**
	 * Método para crear los articulos por años
	 */
	public function articleAnios(){
		$arrayParametros = array(
			'identificador_operador' => $_SESSION["usuario"],
			'estado' => 'Aprobado_AM',
			'provincia' => $_SESSION['nombreProvincia']);
		$this->perfilUsuario();
		if ($this->perfilUsuario != 'PFL_APM_CF_OP'){
			unset($arrayParametros["identificador_operador"]);
		}
		if ($this->perfilUsuario != 'PFL_APM_CF_TP'){
			unset($arrayParametros["provincia"]);
		}

		$consulta = $this->lNegocioFormularioAnteMortem->buscarFormulariosCfXAnio($arrayParametros);
		$contador = 0;
		foreach ($consulta as $fila){
			$arrayParametros = array(
				'id' => $fila['anio'] . '-' . $_POST["opcion"],
				'rutaAplicacion' => URL_MVC_FOLDER . 'InspeccionAntePostMortemCF',
				'opcion' => 'formularioReporteAntePostMortem/listarMeses',
				'destino' => 'listadoItems',
				'contador' => ++ $contador,
				'estado' => $fila['cantidad'],
				'texto1' => $fila['anio'],
				'texto2' => '');

			$this->article .= $this->articleComun($arrayParametros, 5);
		}
	}

	/**
	 * Método para crear los articulos por años
	 */
	public function articleMeses($consulta, $anio){
		$contador = 0;
		foreach ($consulta as $fila){
			$arrayParametros = array(
				'id' => $fila['mes'] . '-' . $anio . '-' . $_POST["opcion"],
				'rutaAplicacion' => URL_MVC_FOLDER . 'InspeccionAntePostMortemCF',
				'opcion' => 'formularioReporteAntePostMortem/listar',
				'destino' => 'listadoItems',
				'contador' => ++ $contador,
				'estado' => $fila['cantidad'],
				'texto1' => $this->lNegocioFormularioAnteMortem->mesEnLetras($fila['mes']),
				'texto2' => '');
			$this->article .= $this->articleComun($arrayParametros, 5);
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
	 * Construye el código HTML para desplegar la lista de - FormularioAnteMortem
	 */
	public function tablaHtmlFormularioReporte($arrayParametros){
		$contador = 0;
		foreach ($arrayParametros as $fila){
			$this->itemsFiltrados[] = array(
				'<tr id="' . $fila['id_formulario_ante_mortem'] . '-A"
		  class="item"
          data-rutaAplicacion="' . URL_MVC_FOLDER . 'InspeccionAntePostMortemCF\formularioReporteAntePostMortem"
		  data-opcion="visualizarInfo" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['razon_social'] . '</b></td>
			<td>' . $this->lNegocioFormularioAnteMortem->formatearFecha($fila['fecha_formulario']) . '</td>
			<td>' . $fila['codigo_formulario'] . '</td>
			<td>' . $fila['num_csmi'] . '</td>
			<td>' . $fila['tipo_especie'] . '</td>
			<td>' . $fila['estado'] . '</td>
			</tr>');

			$sql = $this->lNegocioFormularioAnteMortem->buscarDetalleFormulariosPostMortemXAnio($fila['id_formulario_ante_mortem']);
			foreach ($sql as $filaX){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $filaX['id_formulario_post_mortem'] . '-P-' .$filaX['especie']. '-'.$fila['id_centro_faenamiento'].'"
		  class="item"
          data-rutaAplicacion="' . URL_MVC_FOLDER . 'InspeccionAntePostMortemCF\formularioReporteAntePostMortem"
		  data-opcion="visualizarInfo" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['razon_social'] . '</b></td>
			<td>' . $this->lNegocioFormularioAnteMortem->formatearFecha($fila['fecha_formulario']) . '</td>
			<td>' . $filaX['codigo_formulario'] . '</td>
			<td>' . $filaX['num_csmi'] . '</td>
			<td>' . $filaX['tipo_especie'] . '</td>
			<td>' . $filaX['estado'] . '</td>
			</tr>');
			}
		}
	}

	/**
	 * generar reporte mensual
	 */
	public function generarFormularioMensual(){
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

		return $this->urlPdf;
	}

	/**
	 * Construye el código HTML para buscar productos segun especie
	 */
	public function buscarProductosXespecie(){
		echo $this->comboProductos($_POST['idOperadorTipoOperacion'], $_POST['especie']);
	}

	public function filtroBusqueda($opt){
		switch ($opt) {
			case 1:
				$this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Búsqueda Formulario:</th>
	                                                </tr>
													<tr  style="width: 100%;">
	                            						<td >Provincia: </td>
	                            						<td>
	                            							<select id="id_provincia" name="id_provincia">
																<option value="">Seleccionar...</option>
																' . $this->comboProvinciasEc() . '
															</select>
	                            						</td>
														<td >C. de Faenamiento: </td>
	                            						<td>
	                            							<input id="cFaenamiento" type="text" name="cFaenamiento"  value="" maxlength="512">
	                            						</td>
	                            					</tr>
	                            					<tr  style="width: 100%;">
	                            						<td >Fecha: </td>
	                            						<td>
	                            							<input id="fecha" type="text" name="fecha"  value="" readonly>
	                            						</td>
														<td >Nro. GUIA (CSMI): </td>
	                            						<td>
	                            							<input id="csmi" type="text" name="csmi"  value="" maxlength="8">
	                            						</td>
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Cód Formul: </td>
	                            						<td>
	                            							<input id="codFormulario" type="text" name="codFormulario"  value="" maxlength="21">
	                            						</td>
														<td >Tipo Ave / Especie: </td>
	                            						<td>
	                            							<input id="tipo_especie" type="text" name="tipo_especie"  value="" maxlength="64">
	                            						</td>
	                            					</tr>
																	
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
			break;
			case 2:
				$this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Búsqueda Formulario:</th>
	                                                </tr>
													<tr  style="width: 100%;">
	                            						<td >C. de Faenamiento: </td>
	                            						<td>
	                            							<input id="cFaenamiento" type="text" name="cFaenamiento"  value="" maxlength="512">
	                            						</td>
	                            					
	                            						<td >Fecha: </td>
	                            						<td>
	                            							<input id="fecha" type="text" name="fecha"  value="" readonly>
	                            						</td>
                                                    </tr>
	                            					<tr  style="width: 100%;">
														<td >Nro. GUIA (CSMI): </td>
	                            						<td>
	                            							<input id="csmi" type="text" name="csmi"  value="" maxlength="8">
	                            						</td>
	                            						<td >Cód Formul: </td>
	                            						<td>
	                            							<input id="codFormulario" type="text" name="codFormulario"  value="" maxlength="21">
	                            						</td>
                                                    </tr>
                                                    <tr  style="width: 100%;">
														<td >Tipo Ave / Especie: </td>
	                            						<td>
	                            							<input id="tipo_especie" type="text" name="tipo_especie"  value="" maxlength="64" >
	                            						</td>
	                            					</tr>
																	
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
			break;
			case 3:
				$this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Búsqueda Mensual:</th>
	                                                </tr>
													<tr  style="width: 100%;">
	                            						<td >Provincia: </td>
	                            						<td>
	                            							<select id="id_provincia" name="id_provincia">
																<option value="">Seleccionar...</option>
																' . $this->comboProvinciasEc() . '
															</select>
	                            						</td>
														<td >RUC C. de Faenamiento: </td>
	                            						<td>
	                            							<input id="rucFaenamiento" type="text" name="rucFaenamiento"  value="" maxlength="13">
	                            						</td>
	                            					</tr>
	                            					<tr  style="width: 100%;">
	                            						<td >Fecha inicio: </td>
	                            						<td>
	                            							<input id="fechaInicio" type="text" name="fechaInicio"  value="" readonly>
	                            						</td>
														<td >Fecha fin: </td>
	                            						<td>
	                            							<input id="fechaFin" type="text" name="fechaFin"  value="" readonly>
	                            						</td>
	                            					</tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
			break;
			case 4:
				$this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Búsqueda Mensual:</th>
	                                                </tr>
													<tr  style="width: 100%;">
														<td >RUC C. de Faenamiento: </td>
	                            						<td>
	                            							<input id="rucFaenamiento" type="text" name="rucFaenamiento"  value="" maxlength="13">
	                            						</td>
	                            					</tr>
	                            					<tr  style="width: 100%;">
	                            						<td >Fecha inicio: </td>
	                            						<td>
	                            							<input id="fechaInicio" type="text" name="fechaInicio"  value="" readonly>
	                            						</td>
														<td >Fecha fin: </td>
	                            						<td>
	                            							<input id="fechaFin" type="text" name="fechaFin"  value="" readonly>
	                            						</td>
	                            					</tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
			break;
			default:
				;
			break;
		}
	}
}

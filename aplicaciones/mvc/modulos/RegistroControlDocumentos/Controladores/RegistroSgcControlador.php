<?php
/**
 * Controlador RegistroSgc
 *
 * Este archivo controla la lógica del negocio del modelo: RegistroSgcModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-10-18
 * @uses RegistroSgcControlador
 * @package RegistroControlDocumentos
 * @subpackage Controladores
 */
namespace Agrodb\RegistroControlDocumentos\Controladores;

use Agrodb\RegistroControlDocumentos\Modelos\RegistroSgcLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\RegistroSgcModelo;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleRegistroSgcLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleRegistroSgcModelo;
use Agrodb\RegistroControlDocumentos\Modelos\DocumentoAdjuntoLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DocumentoAdjuntoModelo;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleDestinatarioLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleDestinatarioModelo;
use Agrodb\RegistroControlDocumentos\Modelos\TecnicoLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\TecnicoModelo;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleSocializacionLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleSocializacionModelo;

class RegistroSgcControlador extends BaseControlador{

	private $lNegocioRegistroSgc = null;

	private $modeloRegistroSgc = null;

	private $lNegocioDetalleRegistroSgc = null;

	private $modeloDetalleRegistroSgc = null;

	private $lNegocioDocumentoAdjunto = null;

	private $modeloDocumentoAdjunto = null;

	private $lNegocioDetalleDestinatario = null;

	private $modeloDetalleDestinatario = null;

	private $lNegocioTecnico = null;

	private $modeloTecnico = null;

	private $lNegocioDetalleSocializacion = null;

	private $modeloDetalleSocializacion = null;

	private $accion = null;

	private $rutaArchivo = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioRegistroSgc = new RegistroSgcLogicaNegocio();
		$this->modeloRegistroSgc = new RegistroSgcModelo();

		$this->lNegocioDetalleRegistroSgc = new DetalleRegistroSgcLogicaNegocio();
		$this->modeloDetalleRegistroSgc = new DetalleRegistroSgcModelo();

		$this->lNegocioDocumentoAdjunto = new DocumentoAdjuntoLogicaNegocio();
		$this->modeloDocumentoAdjunto = new DocumentoAdjuntoModelo();

		$this->lNegocioDetalleDestinatario = new DetalleDestinatarioLogicaNegocio();
		$this->modeloDetalleDestinatario = new DetalleDestinatarioModelo();

		$this->lNegocioTecnico = new TecnicoLogicaNegocio();
		$this->modeloTecnico = new TecnicoModelo();

		$this->lNegocioDetalleSocializacion = new DetalleSocializacionLogicaNegocio();
		$this->modeloDetalleSocializacion = new DetalleSocializacionModelo();

		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloRegistroSgc = $this->lNegocioRegistroSgc->buscarRegistroSgc();
		$this->tablaHtmlRegistroSgc($modeloRegistroSgc);
		require APP . 'RegistroControlDocumentos/vistas/listaRegistroSgcVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$verificar = $this->lNegocioRegistroSgc->buscarLista("estado ='temporal' and identificador_registro='" . $_SESSION['usuario'] . "'");
		if ($verificar->count()){
			foreach ($verificar as $value){
				$this->lNegocioDetalleRegistroSgc->borrarPorParametro('id_registro_sgc', $value['id_registro_sgc']);
				$this->lNegocioDetalleDestinatario->borrarPorParametro('id_registro_sgc', $value['id_registro_sgc']);
				$this->lNegocioRegistroSgc->borrar($value['id_registro_sgc']);
			}
		}
		$this->accion = "Nuevo Registro";
		require APP . 'RegistroControlDocumentos/vistas/formularioRegistroSgcVista.php';
	}

	public function registrosDocumentos(){
		$modeloRegistroSgc = $this->lNegocioRegistroSgc->buscarLista("estado not in('temporal')");
		$this->tablaHtmlRegistroSgc($modeloRegistroSgc);
		$this->filtroRegistroDocumentos();
		require APP . 'RegistroControlDocumentos/vistas/listaRegistrosDocumentosVista.php';
	}

	public function revisarRegistrosDocumentos(){
		$modeloRegistroSgc = $this->lNegocioRegistroSgc->filtrarRevisarRegistros();
		$this->tablaHtmlRevisarRegistroSgc($modeloRegistroSgc);
		$this->filtroRevisarRegistroDocuementos();
		require APP . 'RegistroControlDocumentos/vistas/listaRevisarRegistrosDocumentosVista.php';
	}

	public function reporteRegistrosSgc(){
		$this->filtroReporteRegistrosSgc();
		require APP . 'RegistroControlDocumentos/vistas/listaReporteRegistrosSgcVista.php';
	}

	public function reportesDocumentosSgc(){
		$this->filtroReportesDocumentosSgc();
		require APP . 'RegistroControlDocumentos/vistas/listaReportesDocumentosSgcVista.php';
	}

	public function asignarTecnicoSocializar(){
		$arrayParametros = array(
			'identificador' => $_SESSION['usuario'],
			'estado_socializacion' => 'pendiente');
		$modeloRegistroSgc = $this->lNegocioRegistroSgc->filtrarRevisarRegistros($arrayParametros);
		$this->tablaHtmlAsignarTecnicoSocializar($modeloRegistroSgc);
		require APP . 'RegistroControlDocumentos/vistas/listaAsignarTecnicoSocializarVista.php';
	}

	public function revisarDocumentosSocializar(){
		$arrayParametros = array(
			'identificador' => $_SESSION['usuario']);
		$modeloRegistroSgc = $this->lNegocioRegistroSgc->filtrarRevisarRegistrosSocializar($arrayParametros);
		$this->tablaHtmlRevisarRegistroSocializar($modeloRegistroSgc);
		require APP . 'RegistroControlDocumentos/vistas/listaRevisarRegistrosDocumentosSocializarVista.php';
	}

	public function registrosPlanifiJuridi(){
		$modeloRegistroSgc = $this->lNegocioRegistroSgc->buscarLista("estado not in('temporal')");
		$this->tablaHtmlRegistroSgcPlanificacion($modeloRegistroSgc);
		$this->filtroRegistroDocumentos();
		require APP . 'RegistroControlDocumentos/vistas/listaRegistrosDocumentosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -RegistroSgc
	 */
	public function guardar(){
		$_POST['identificador_registro'] = $_SESSION['usuario'];
		$_POST['estado'] = 'creado';
		if ($_POST['socializar'] == 'Si'){
			$_POST['estado'] = 'No atendido';
		}else{
			unset($_POST['fecha_vigencia']);
			unset($_POST['fecha_notificacion']);
		}
		$this->lNegocioRegistroSgc->guardar($_POST);
	}

	/**
	 * Método para registrar en la base de datos -RegistroSgc
	 */
	public function guardarAsignarTecnico(){
		$verificar = $this->lNegocioDetalleSocializacion->buscarLista("id_detalle_destinatario=" . $_POST['id_detalle_destinatario'] . " and identifcador_asignante='" . $_SESSION['usuario'] . "'");
		if ($verificar->count() > 0){
			$_POST['estado'] = 'socializar';
			$this->lNegocioRegistroSgc->guardar($_POST);
			$datos = array(
				'id_detalle_destinatario' => $_POST['id_detalle_destinatario'],
				'estado_socializacion' => 'registrado');
			$this->lNegocioDetalleDestinatario->guardar($datos);
		}
	}

	/**
	 * Método para registrar en la base de datos -RegistroSgc
	 */
	public function guardarRevisarSocializar(){/**/
		$this->modeloDetalleSocializacion = $this->lNegocioDetalleSocializacion->buscar($_POST['id_detalle_socializacion']);
		
		if ($this->modeloDetalleSocializacion->getEstadoSocializar() == 'temporal'){
			$datos = $this->lNegocioDetalleDestinatario->buscarLista("id_registro_sgc=" . $_POST['id_registro_sgc'] . " and identificador='" . $this->modeloDetalleSocializacion->getIdentifcadorAsignante() . "'");
						
			if ($datos->count() > 0){
				$valores = array(
					'estado' => 'Atendido',
					'id_detalle_destinatario' => $datos->current()->id_detalle_destinatario);
				$this->lNegocioDetalleDestinatario->guardar($valores);
				$_POST['estado_socializar'] = 'Atendido';
				$this->lNegocioDetalleSocializacion->guardar($_POST);
				
				//Busca si todos los registros del mismo documento se encuentran ya atendidos para actualizar la cabecera del documento
				$registrosPendientes = $this->lNegocioDetalleSocializacion->buscarSocializacionesPendientes($_POST);
				
				if ($registrosPendientes->count() == 0){				
    				$_POST['estado'] = 'Atendido';
    				$this->lNegocioRegistroSgc->guardar($_POST);
				}
			}
		}
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: RegistroSgc
	 */
	public function editar(){
		$this->accion = "Editar Registro";
		$this->modeloRegistroSgc = $this->lNegocioRegistroSgc->buscar($_POST["id"]);
		require APP . 'RegistroControlDocumentos/vistas/formularioRegistroSgcVista.php';
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: RegistroSgc
	 */
	public function revisar(){
		$this->accion = "Revisión registro socializado";
		$id = explode('-', $_POST["id"]);
		$this->modeloRegistroSgc = $this->lNegocioRegistroSgc->buscar($id[0]);
		$socializar = $this->lNegocioDetalleSocializacion->buscarLista("id_detalle_destinatario=" . $id[1]);
		if ($socializar->count()){
			$this->modeloDetalleSocializacion->setOptions((array) $socializar->current());
			$this->modeloTecnico = $this->lNegocioTecnico->buscar($this->modeloDetalleSocializacion->getIdTecnico());
		}
		require APP . 'RegistroControlDocumentos/vistas/formularioRevisarRegistroSgcVista.php';
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: RegistroSgc
	 */
	public function revisarPlanificacionJuridico(){
		$this->accion = "Visualización registro";
		$this->modeloRegistroSgc = $this->lNegocioRegistroSgc->buscar($_POST["id"]);
		require APP . 'RegistroControlDocumentos/vistas/formularioRevisarRegistroSgcVista.php';
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: RegistroSgc
	 */
	public function asignar(){
		$this->accion = "Asignación técnico";
		$this->modeloRegistroSgc = $this->lNegocioRegistroSgc->buscar($_POST["id"]);
		$verificar = $this->lNegocioDetalleDestinatario->buscarLista("id_registro_sgc =" . $_POST["id"] . " and identificador='" . $_SESSION['usuario'] . "'");
		$this->modeloDetalleDestinatario->setOptions((array) $verificar->current());
		/*
		 * $arrayParametros = array('id_registro_sgc' => $_POST["id"], 'identificador_asignante' => $_SESSION['usuario']);
		 * $verificar = $this->lNegocioDetalleSocializacion->buscarDetalleSocializacionDestinatarrio($arrayParametros);
		 * if($verificar->count()){
		 * $this->lNegocioDetalleDestinatario->buscar($verificar->current()->id_detalle_destinatario);
		 * }
		 */
		require APP . 'RegistroControlDocumentos/vistas/formularioAsignarTecnicoSocializarVista.php';
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: RegistroSgc
	 */
	public function socializar(){
		$this->accion = "Registro a socializar";
		$id = explode('-', $_POST["id"]);
		$this->modeloRegistroSgc = $this->lNegocioRegistroSgc->buscar($id[0]);

		$socializar = $this->lNegocioDetalleSocializacion->buscarLista("id_detalle_destinatario=" . $id[1]);
		$this->modeloDetalleSocializacion->setOptions((array) $socializar->current());
		$this->modeloTecnico = $this->lNegocioTecnico->buscar($this->modeloDetalleSocializacion->getIdTecnico());
		require APP . 'RegistroControlDocumentos/vistas/formularioRevisarDocumentosSocializarVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - RegistroSgc
	 */
	public function borrar(){
		$this->lNegocioRegistroSgc->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - RegistroSgc
	 */
	public function tablaHtmlRegistroSgc($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$nombre = $this->lNegocioRegistroSgc->buscarNombreArea($fila['coordinacion']);
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_registro_sgc'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroControlDocumentos\registroSgc"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['numero_glpi'] . '</b></td>
<td>' . $fila['numero_memorando'] . '</td>
<td>' . $fila['fecha_aprobacion'] . '</td>
<td>' . $nombre->current()->nombre . '</td>
<td>' . $fila['formato'] . '</td>
</tr>');
			}
		}
	}

	/**
	 * Construye el código HTML para desplegar la lista de - RegistroSgc
	 */
	public function tablaHtmlRegistroSgcPlanificacion($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$nombre = $this->lNegocioRegistroSgc->buscarNombreArea($fila['coordinacion']);
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_registro_sgc'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroControlDocumentos\registroSgc"
		  data-opcion="revisarPlanificacionJuridico" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['numero_glpi'] . '</b></td>
<td>' . $fila['numero_memorando'] . '</td>
<td>' . $fila['fecha_aprobacion'] . '</td>
<td>' . $nombre->current()->nombre . '</td>
<td>' . $fila['formato'] . '</td>
</tr>');
			}
		}
	}

	/**
	 * Construye el código HTML para desplegar la lista de - RegistroSgc
	 */
	public function tablaHtmlRevisarRegistroSgc($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$nombre = $this->lNegocioRegistroSgc->buscarNombreArea($fila['coordinacion']);
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_registro_sgc'] . '-' . $fila['id_detalle_destinatario'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroControlDocumentos\registroSgc"
		  data-opcion="revisar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['numero_memorando'] . '</b></td>
<td>' . $fila['fecha_notificacion'] . '</td>
<td>' . $fila['nombre_area'] . '</td>
<td>' . $nombre->current()->nombre . '</td>
<td>' . $fila['fecha_vigencia'] . '</td>
<td>' . $fila['estado'] . '</td>
</tr>');
			}
		}
	}

	/**
	 * Construye el código HTML para desplegar la lista de - RegistroSgc
	 */
	public function tablaHtmlAsignarTecnicoSocializar($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$nombre = $this->lNegocioRegistroSgc->buscarNombreArea($fila['coordinacion']);
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_registro_sgc'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroControlDocumentos\registroSgc"
		  data-opcion="asignar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['numero_memorando'] . '</b></td>
<td>' . $fila['fecha_notificacion'] . '</td>
<td>' . $fila['nombre_area'] . '</td>
<td>' . $nombre->current()->nombre . '</td>
<td>' . $fila['fecha_vigencia'] . '</td>
</tr>');
			}
		}
	}

	/**
	 * Construye el código HTML para desplegar la lista de - RegistroSgc
	 */
	public function tablaHtmlRevisarRegistroSocializar($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$nombre = $this->lNegocioRegistroSgc->buscarNombreArea($fila['coordinacion']);
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_registro_sgc'] . '-' . $fila['id_detalle_destinatario'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroControlDocumentos\registroSgc"
		  data-opcion="socializar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['numero_memorando'] . '</b></td>
<td>' . $fila['fecha_notificacion'] . '</td>
<td>' . $fila['nombre_area'] . '</td>
<td>' . $nombre->current()->nombre . '</td>
<td>' . $fila['fecha_vigencia'] . '</td>
</tr>');
			}
		}
	}

	public function comboCoordinacion(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';

		// $consulta = $this->lNegocioRegistroSgc->buscarDivisionEstruc($_POST['coordinacion']);

		$contenido = $this->comboAreas($_POST['coordinacion']);

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	public function guardarEnlace(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';

		$_POST['identificador_registro'] = $_SESSION['usuario'];
		$id = $this->lNegocioRegistroSgc->guardarEnlace($_POST);

		if ($id != 0){
			$contenido = $id;
			$lista = $this->listarEnlaces($id);
		}else{
			$estado = 'FALLO';
			$mensaje = 'Error al guardar el registro !!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'lista' => $lista,
			'contenido' => $contenido));
	}

	public function guardarDestinatario(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';

		$_POST['identificador_registro'] = $_SESSION['usuario'];
		$id = $this->lNegocioRegistroSgc->guardarDestinatario($_POST);
		if ($id != 0){
			$contenido = $id;
			if ($_POST['accion'] == 'Nuevo Registro'){
				$lista = $this->listarDestinatariosRegistrados($id);
			}else{
				$lista = $this->listarDestinatariosRegistrados($id, 'No');
			}
		}else{
			$estado = 'FALLO';
			$mensaje = 'Error al guardar el registro !!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'lista' => $lista,
			'contenido' => $contenido));
	}

	public function eliminarEnlace(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';

		if (isset($_POST['id'])){
			$this->lNegocioDetalleRegistroSgc->borrar($_POST['id']);
			$lista = $this->listarEnlaces($_POST['id_registro_sgc']);
		}else{
			$estado = 'FALLO';
			$mensaje = 'No se encontro registro !!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'lista' => $lista,
			'contenido' => $contenido));
	}

	/**
	 * guardar archivo adjunto
	 */
	public function agregarDocumentosAdjuntos(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';
		try{
			$nuevo_nombre_archivo = 'Anexo 1';
			if (! empty($_REQUEST['id_registro_sgc']) && $_REQUEST['id_registro_sgc'] != 'null'){
				$consulta = $this->lNegocioDocumentoAdjunto->buscarLista("id_registro_sgc=" . $_REQUEST['id_registro_sgc'] . " and estado='creado' order by 1");
				if ($consulta->count() > 0){
					$numero = $consulta->count() + 1;
					$nuevo_nombre_archivo = 'Anexo ' . $numero;
				}
			}

			$nombre_archivo = $_FILES['archivo']['name'];
			$tipo_archivo = $_FILES['archivo']['type'];
			$tamano_archivo = $_FILES['archivo']['size'];
			$tmpArchivo = $_FILES['archivo']['tmp_name'];
			$rutaCarpeta = REG_CTR_DOC_SGC . $_REQUEST['id_registro_sgc'];
			$extension = explode(".", $nombre_archivo);
			$numero_aleatorio = rand(1,1000);

			if ($tamano_archivo != '0'){
				if (strtoupper(end($extension)) == 'PDF' && $tipo_archivo == 'application/pdf'){
					if (! file_exists('../../' . $rutaCarpeta)){
						mkdir('../../' . $rutaCarpeta, 0777, true);
					}
					$nuevo_nombre = $_REQUEST['id_registro_sgc'] . '_' . $numero_aleatorio . '.' . end($extension);
					$nombreDocumento = str_replace(" ", "_", strtolower($nuevo_nombre));
					$ruta = $rutaCarpeta . '/' . $nombreDocumento;
					move_uploaded_file($tmpArchivo, '../../' . $ruta);

					if (! empty($_REQUEST['id_registro_sgc']) && $_REQUEST['id_registro_sgc'] != 'null'){
						$id = $_REQUEST['id_registro_sgc'];
					}else{
						unset($_REQUEST["id_registro_sgc"]);
						$_REQUEST['identificador_registro'] = $_SESSION['usuario'];
						$id = $this->lNegocioRegistroSgc->guardar($_REQUEST);
					}

					if ($id){
						$arrayAdjunto = array(
							'ruta_archivo' => $ruta,
							'nombre_archivo' => $nuevo_nombre_archivo,
							'id_registro_sgc' => $id);
						$this->lNegocioDocumentoAdjunto->guardar($arrayAdjunto);
						$mensaje = 'Documento agregado correctamente';
						$lista = $this->listarDocumentos($id);
						$contenido = $id;
					}else{
						$estado = 'FALLO';
						$mensaje = 'Error al guardar el Documento..!!';
						$lista = $ruta;
					}
				}else{
					$estado = 'FALLO';
					$mensaje = 'No se cargó archivo. Extención incorrecta';
				}
			}else{
				$estado = 'FALLO';
				$mensaje = 'El documento supera el tamaño permitido';
			}
		}catch (\Exception $ex){
			$estado = 'FALLO';
			$mensaje = 'No se cargó documento';
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'lista' => $lista));
	}

	public function buscarDestinatario(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';
		$contenido = $this->listarDestinatarios($_POST['destinatario']);
		$lista = $this->listarDestinatariosRegistrados($_POST['id_registro_sgc']);
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'lista' => $lista,
			'contenido' => $contenido));
	}

	public function verificarDestinatario(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		if ($_POST['id_registro_sgc'] != ''){
			$verificar = $this->lNegocioDetalleDestinatario->buscarLista("identificador='" . $_POST['identificador'] . "' and id_registro_sgc=" . $_POST['id_registro_sgc']);
			if ($verificar->count() > 0){
				$estado = 'FALLO';
				$mensaje = 'Funcionario ya registrado !!';
			}
		}
		// $contenido = $this->listarDestinatarios($_POST['destinatario']);

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	public function eliminarDestinatario(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';

		if (isset($_POST['id'])){
			$this->lNegocioDetalleDestinatario->borrar($_POST['id']);
			$lista = $this->listarDestinatariosRegistrados($_POST['id_registro_sgc']);
		}else{
			$estado = 'FALLO';
			$mensaje = 'No se encontro registro !!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'lista' => $lista,
			'contenido' => $contenido));
	}

	public function guardarTecnico(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';
		$arrayParametros = array(
			'id_registro_sgc' => $_POST['id_registro_sgc'],
			'identificador_asignante' => $_SESSION['usuario'],
			'id_detalle_destinatario=' . $_POST['id_detalle_destinatario']);
		$verificar = $this->lNegocioDetalleSocializacion->buscarDetalleSocializacionDestinatarrio($arrayParametros);
		if ($verificar->count() <= 0){
			// $this->modeloTecnico = $this->lNegocioTecnico->buscar($_POST['id_tecnico']);

			$arrayParametros = array(
				'identificador' => $_POST['identificador'],
				'nombre' => $_POST['tecnico'],
				'id_registro_sgc' => $_POST['id_registro_sgc']);
			$idTecnico = $this->lNegocioTecnico->guardar($arrayParametros);
			$catastro = $this->lNegocioRegistroSgc->filtrarInfoTecnicoCatastro($arrayParametros);

			if ($catastro->count() > 0){
				$_POST['identifcador_asignante'] = $_SESSION['usuario'];
				$_POST['provincia'] = $catastro->current()->provincia;
				$_POST['oficina'] = $catastro->current()->oficina;
				$_POST['coordinacion'] = $catastro->current()->coordinacion;
				$_POST['direccion'] = $catastro->current()->direccion;
				$_POST['id_tecnico'] = $idTecnico;
				$id = $this->lNegocioDetalleSocializacion->guardar($_POST);
				if ($id != 0){
					$contenido = $id;
					$lista = $this->listarTecnicoRegistrado($_POST['id_detalle_destinatario']);
				}else{
					$estado = 'FALLO';
					$mensaje = 'Error al guardar el registro !!';
				}
			}else{
				$estado = 'FALLO';
				$mensaje = 'Error al verificar datos en el catastro !!';
			}
		}else{
			$estado = 'FALLO';
			$mensaje = 'Ya existe un tecnico registrado !!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'lista' => $lista,
			'contenido' => $contenido));
	}

	public function eliminarTecnico(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';

		if (isset($_POST['id'])){
			$consultar = $this->lNegocioDetalleSocializacion->buscar($_POST['id']);
			$this->lNegocioDetalleSocializacion->borrar($_POST['id']);
			$this->lNegocioTecnico->borrar($consultar->getIdTecnico());
			$lista = $this->listarTecnicoRegistrado($_POST['id_registro_sgc']);
		}else{
			$estado = 'FALLO';
			$mensaje = 'No se encontro registro !!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'lista' => $lista,
			'contenido' => $contenido));
	}

	/**
	 * guardar archivo adjunto
	 */
	public function agregarDocumentoSocializar(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$lista = '';
		try{
			$nuevo_nombre_archivo = 'Evidencia 1';

			$nombre_archivo = $_FILES['archivo']['name'];
			$tipo_archivo = $_FILES['archivo']['type'];
			$tamano_archivo = $_FILES['archivo']['size'];
			$tmpArchivo = $_FILES['archivo']['tmp_name'];
			$rutaCarpeta = REG_CTR_DOC_SGC . $_REQUEST['id_registro_sgc'];
			$extension = explode(".", $nombre_archivo);
			$numero_aleatorio = rand(1,1000);

			if ($tamano_archivo != '0'){
				if (strtoupper(end($extension)) == 'PDF' && $tipo_archivo == 'application/pdf'){
					if (! file_exists('../../' . $rutaCarpeta)){
						mkdir('../../' . $rutaCarpeta, 0777, true);
					}
					$nuevo_nombre = $_REQUEST['id_registro_sgc'] . '_' . $numero_aleatorio . '_' . $_SESSION['usuario'] . '.' . end($extension);
					$nombreDocumento = str_replace(" ", "_", strtolower($nuevo_nombre));
					$ruta = $rutaCarpeta . '/' . $nombreDocumento;
					move_uploaded_file($tmpArchivo, '../../' . $ruta);

					$id = $_REQUEST['id_detalle_socializacion'];
					if ($id){
						$arrayAdjunto = array(
							'id_detalle_socializacion' => $id,
							'nombre_socializar' => $nuevo_nombre_archivo,
							'documento_socializar' => $ruta,
							'fecha_socializacion' => date('Y-m-d'),
							'estado_socializar' => 'temporal');
						$this->lNegocioDetalleSocializacion->guardar($arrayAdjunto);
						$mensaje = 'Documento agregado correctamente';
						$lista = $this->listarDocumentoSocializar($_REQUEST['id_detalle_destinatario']);
						$contenido = $id;
					}else{
						$estado = 'FALLO';
						$mensaje = 'Error al guardar el Documento..!!';
						$lista = $ruta;
					}
				}else{
					$estado = 'FALLO';
					$mensaje = 'No se cargó archivo. Extención incorrecta';
				}
			}else{
				$estado = 'FALLO';
				$mensaje = 'El documento supera el tamaño permitido';
			}
		}catch (\Exception $ex){
			$estado = 'FALLO';
			$mensaje = 'No se cargó documento';
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'lista' => $lista));
	}

	public function filtrarInformacion(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$modeloRegistrosSgc = array();

		if ($_POST['fecha_aprobacion_desde'] != '' && $_POST['fecha_aprobacion_hasta'] != ''){
			$arrayParametros = array(
				'numero_memorando_busq' => $_POST['numero_memorando_busq'],
				'numero_glpi_busq' => $_POST['numero_glpi_busq'],
				'fecha_aprobacion_desde' => $_POST['fecha_aprobacion_desde'],
				'fecha_aprobacion_hasta' => $_POST['fecha_aprobacion_hasta'],
				'fecha_notificacion_desde' => $_POST['fecha_notificacion_desde'],
				'fecha_notificacion_hasta' => $_POST['fecha_notificacion_hasta'],
				'coordinacion_busq' => $_POST['coordinacion_busq'],
				'formato_busq' => $_POST['formato_busq'],
				'estado_registro_busq' => $_POST['estado_registro_busq']);
			$modeloRegistrosSgc = $this->lNegocioRegistroSgc->filtrarRegistroSgc($arrayParametros);
		}else{
			$estado = 'FALLO';
			$mensaje = 'Debe ingresar los campos obligatorios..!!';
		}
		$this->tablaHtmlRegistroSgc($modeloRegistrosSgc);
		$contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	public function filtrarInformacionRevisar(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$modeloRegistrosSgc = array();

		if ($_POST['fecha_aprobacion_desde'] != '' && $_POST['fecha_aprobacion_hasta'] != ''){
			$arrayParametros = array(
				'numero_memorando_busq' => $_POST['numero_memorando_busq'],
				'numero_glpi_busq' => $_POST['numero_glpi_busq'],
				'fecha_aprobacion_desde' => $_POST['fecha_aprobacion_desde'],
				'fecha_aprobacion_hasta' => $_POST['fecha_aprobacion_hasta'],
				'fecha_notificacion_desde' => $_POST['fecha_notificacion_desde'],
				'fecha_notificacion_hasta' => $_POST['fecha_notificacion_hasta'],
				'coordinacion_busq' => $_POST['coordinacion_busq'],
				'formato_busq' => $_POST['formato_busq'],
				'estadoSocializar' => $_POST['estadoSocializar']);
			$modeloRegistrosSgc = $this->lNegocioRegistroSgc->filtrarRevisarRegistros($arrayParametros);
		}else{
			$estado = 'FALLO';
			$mensaje = 'Debe ingresar los campos obligatorios..!!';
		}
		$this->tablaHtmlRevisarRegistroSgc($modeloRegistrosSgc);
		$contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	// *********************crear excel************************************************************************
	public function generarReporteRegistrosDocumentos(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$rutaArch = '';
		if (isset($_POST['fecha_aprobacion_desde']) && isset($_POST['fecha_aprobacion_hasta'])){
			$arrayParametros = array(
				'fecha_aprobacion_desde' => $_POST['fecha_aprobacion_desde'],
				'fecha_aprobacion_hasta' => $_POST['fecha_aprobacion_hasta'],
				'coordinacion_busq' => $_POST['coordinacion_busq'],
				'coordinacion_dest_busq' => $_POST['coordinacion_dest_busq'],
				'formato_busq' => $_POST['formato_busq'],
				'estadoSocializar' => $_POST['estadoSocializar']);
			$modeloRegistrosSgc = $this->lNegocioRegistroSgc->filtrarRevisarRegistros($arrayParametros);
			// $arrayParametros = array('fecha_desde' =>$_POST['fecha_desde'],'fecha_hasta'=>$_POST['fecha_hasta'],'provincia' =>$_POST['provincia'],'area_tecnica' => $_POST['area_tecnica'] );
			// $verificar = $this->lNegocioProcesoAdministrativo->obtenerConsolidadoProcesosAdministrativos($arrayParametros);
			if ($modeloRegistrosSgc->count() > 0){
				$nombreArchivo = 'reporte_socializacion_documentos' . date('dmy');
				$arrayDatos = array(
					'titulo' => 'REPORTE SOCIALIZACIÓN GESTIÓN DOCUMENTAL',
					'nombreArchivo' => $nombreArchivo);
				$this->lNegocioRegistroSgc->crearExcel($arrayDatos, $modeloRegistrosSgc);
				$rutaArch = REG_CTR_DOC_SGC . "reporteExcel/" . $nombreArchivo . ".xlsx";
			}else{
				$estado = 'ERROR';
				$mensaje = "No existen registros para la busqueda realizada..!!";
			}
		}else{
			$estado = 'ERROR';
			$mensaje = "No selecciono las fechas..!!";
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'rutaArch' => $rutaArch));
	}

	// *********************crear excel eporte docuementos SGC***********************************************************************
	public function generarReporteDocumentosSgc(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$rutaArch = '';
		if (isset($_POST['fecha_aprobacion_desde']) && isset($_POST['fecha_aprobacion_hasta'])){
			$arrayParametros = array(
				'fecha_aprobacion_desde' => $_POST['fecha_aprobacion_desde'],
				'fecha_aprobacion_hasta' => $_POST['fecha_aprobacion_hasta'],
				'coordinacion_busq' => $_POST['coordinacion_busq'],
				'formato_busq' => $_POST['formato_busq'],
				'subproceso_busq' => $_POST['subproceso_busq'],
			    'estado_registro_busq' => $_POST['estado_registro_busq'],
			    'socializar' => $_POST['socializar_busq']
			);
			$modeloRegistrosSgc = $this->lNegocioRegistroSgc->filtrarRevisarRegistrosCompleto($arrayParametros);
			// $arrayParametros = array('fecha_desde' =>$_POST['fecha_desde'],'fecha_hasta'=>$_POST['fecha_hasta'],'provincia' =>$_POST['provincia'],'area_tecnica' => $_POST['area_tecnica'] );
			// $verificar = $this->lNegocioProcesoAdministrativo->obtenerConsolidadoProcesosAdministrativos($arrayParametros);
			if ($modeloRegistrosSgc->count() > 0){
				$nombreArchivo = 'reporte_documento_sgc' . date('dmy');
				$arrayDatos = array(
					'titulo' => 'MATRIZ SGC - ' . date('Y'),
					'nombreArchivo' => $nombreArchivo);
				$this->lNegocioRegistroSgc->crearExcelSGC($arrayDatos, $modeloRegistrosSgc);
				$rutaArch = REG_CTR_DOC_SGC . "reporteExcel/" . $nombreArchivo . ".xlsx";
			}else{
				$estado = 'ERROR';
				$mensaje = "No existen registros para la busqueda realizada..!!";
			}
		}else{
			$estado = 'ERROR';
			$mensaje = "No selecciono las fechas..!!";
		}

		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
			'rutaArch' => $rutaArch));
	}

	// Para descargar archivo
	public function descargaReporteRegistroSgc(){
		$this->rutaArchivo = $_POST['id'];
		if ($_POST['id'] != '0'){
			require APP . 'RegistroControlDocumentos/vistas/formularioDescargaArchivosVista.php';
		}
	}
}

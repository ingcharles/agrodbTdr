<?php
 /**
 * Controlador HistoriaClinica
 *
 * Este archivo controla la lógica del negocio del modelo:  HistoriaClinicaModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-03-16
 * @uses    HistoriaClinicaControlador
 * @package HistoriasClinicas
 * @subpackage Controladores
 */
 namespace Agrodb\HistoriasClinicas\Controladores;
 use Agrodb\HistoriasClinicas\Modelos\HistoriaClinicaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\HistoriaClinicaModelo;
 use Agrodb\HistoriasClinicas\Modelos\ProcedimientoMedicoLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ProcedimientoMedicoModelo;
 use Agrodb\HistoriasClinicas\Modelos\TipoProcedimientoMedicoLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\TipoProcedimientoMedicoModelo;
 use Agrodb\HistoriasClinicas\Modelos\SubtipoProcedimientoMedicoLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\SubtipoProcedimientoMedicoModelo;
 /*****************************************************/
 use Agrodb\HistoriasClinicas\Modelos\HistoriaOcupacionalLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\HistoriaOcupacionalModelo;
 use Agrodb\HistoriasClinicas\Modelos\DetalleHistorialOcupacionalLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\DetalleHistorialOcupacionalModelo;
 use Agrodb\HistoriasClinicas\Modelos\AccidentesLaboralesLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\AccidentesLaboralesModelo;
 use Agrodb\HistoriasClinicas\Modelos\CieLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\CieModelo;
 use Agrodb\HistoriasClinicas\Modelos\AntecedentesSaludFamiliarLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\AntecedentesSaludFamiliarModelo;
 use Agrodb\HistoriasClinicas\Modelos\AntecedentesSaludLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\AntecedentesSaludModelo;
 use Agrodb\HistoriasClinicas\Modelos\DetalleAntecedentesSaludLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\DetalleAntecedentesSaludModelo;
 use Agrodb\HistoriasClinicas\Modelos\RevisionOrganosSistemasLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\RevisionOrganosSistemasModelo;
 use Agrodb\HistoriasClinicas\Modelos\DetalleRevisionOrganosSistemasLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\DetalleRevisionOrganosSistemasModelo;
 use Agrodb\HistoriasClinicas\Modelos\InmunizacionLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\InmunizacionModelo;
 use Agrodb\HistoriasClinicas\Modelos\HabitosLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\HabitosModelo;
 use Agrodb\HistoriasClinicas\Modelos\EstiloVidaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\EstiloVidaModelo;
 use Agrodb\HistoriasClinicas\Modelos\ExamenFisicoLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ExamenFisicoModelo;
 use Agrodb\HistoriasClinicas\Modelos\ExamenesClinicosLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ExamenesClinicosModelo;
 use Agrodb\HistoriasClinicas\Modelos\DetalleExamenesClinicosLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\DetalleExamenesClinicosModelo;
 use Complex\Exception;
 use Agrodb\HistoriasClinicas\Modelos\AdjuntosHistoriaClinicaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\AdjuntosHistoriaClinicaModelo;
 use Agrodb\HistoriasClinicas\Modelos\ExamenParaclinicosLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ExamenParaclinicosModelo;
 use Agrodb\HistoriasClinicas\Modelos\DetalleExamenParaclinicosLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\DetalleExamenParaclinicosModelo;
 use Agrodb\HistoriasClinicas\Modelos\ImpresionDiagnosticaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ImpresionDiagnosticaModelo;
 use Agrodb\HistoriasClinicas\Modelos\AusentismoMedicoLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\AusentismoMedicoModelo;
 use Agrodb\HistoriasClinicas\Modelos\ElementoProteccionLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ElementoProteccionModelo;
 use Agrodb\HistoriasClinicas\Modelos\EnfermedadProfesionalLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\EnfermedadProfesionalModelo;
 use Agrodb\HistoriasClinicas\Modelos\RecomendacionesLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\RecomendacionesModelo;
 use Agrodb\HistoriasClinicas\Modelos\EvaluacionPrimariaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\EvaluacionPrimariaModelo;
 use Agrodb\HistoriasClinicas\Modelos\DetalleEvaluacionPrimariaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\DetalleEvaluacionPrimariaModelo;
 use Agrodb\HistoriasClinicas\Modelos\LogLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\LogModelo;
 use Agrodb\Core\JasperReport;
class HistoriaClinicaControlador extends BaseControlador 
{

		 private $lNegocioHistoriaClinica = null;
		 private $modeloHistoriaClinica = null;
		 private $lNegocioProcedimientoMedico = null;
		 private $modeloProcedimientoMedico = null;
		 private $lNegocioTipoProcedimientoMedico = null;
		 private $modeloTipoProcedimientoMedico = null;
		 private $lNegocioSubtipoProcedimientoMedico = null;
		 private $modeloSubtipoProcedimientoMedico = null;
		 /*****************************************************/
		 private $lNegocioHistoriaOcupacional = null;
		 private $modeloHistoriaOcupacional = null;
		 private $lNegocioDetalleHistorialOcupacional = null;
		 private $modeloDetalleHistorialOcupacional = null;
		 private $lNegocioAccidentesLaborales = null;
		 private $modeloAccidentesLaborales = null;
		 private $lNegocioCie = null;
		 private $modeloCie = null;
		 private $lNegocioAntecedentesSaludFamiliar = null;
		 private $modeloAntecedentesSaludFamiliar = null;
		 private $lNegocioAntecedentesSalud = null;
		 private $modeloAntecedentesSalud = null;
		 private $lNegocioDetalleAntecedentesSalud = null;
		 private $modeloDetalleAntecedentesSalud = null;
		 private $lNegocioRevisionOrganosSistemas = null;
		 private $modeloRevisionOrganosSistemas = null;
		 private $lNegocioDetalleRevisionOrganosSistemas = null;
		 private $modeloDetalleRevisionOrganosSistemas = null;
		 private $lNegocioInmunizacion = null;
		 private $modeloInmunizacion = null;
		 private $lNegocioHabitos = null;
		 private $modeloHabitos = null;
		 private $lNegocioEstiloVida = null;
		 private $modeloEstiloVidad = null;
		 private $lNegocioExamenFisico = null;
		 private $modeloExamenFisico = null;
		 private $lNegocioExamenesClinicos = null;
		 private $modeloExamenesClinicos = null;
		 private $lNegocioDetalleExamenesClinicos = null;
		 private $modeloDetalleExamenesClinicos = null;
		 private $lNegocioAdjuntosHistoriaClinica = null;
		 private $modeloAdjuntosHistoriaClinica = null;
		 private $lNegocioExamenParaclinicos = null;
		 private $modeloExamenParaclinicos = null;
		 private $lNegocioDetalleExamenParaclinicos = null;
		 private $modeloDetalleExamenParaclinicos = null;
		 private $lNegocioImpresionDiagnostica = null;
		 private $modeloImpresionDiagnostica = null;
		 //**********************************************
		 private $lNegocioAusentismoMedico = null;
		 private $modeloAusentismoMedico = null;
		 private $lNegocioElementoProteccion = null;
		 private $modeloElementoProteccion = null;
		 private $lNegocioEnfermedadProfesional = null;
		 private $modeloEnfermedadProfesional = null;
		 private $lNegocioRecomendaciones = null;
		 private $modeloRecomendaciones = null;
		 private $lNegocioEvaluacionPrimaria = null;
		 private $modeloEvaluacionPrimaria = null;
		 private $lNegocioDetalleEvaluacionPrimaria = null;
		 private $modeloDetalleEvaluacionPrimaria = null;
		 private $lNegocioLog = null;
		 private $modeloLog = null;
		 /*****************************************************/
		 private $accion = null;
		 private $idHistorialClinica = null;
		 private $estado = 'nuevo';
		 private $historico = null;
		 private $adjuntoHistoriaClinica = null;
		 
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioHistoriaClinica = new HistoriaClinicaLogicaNegocio();
		 $this->modeloHistoriaClinica = new HistoriaClinicaModelo();
		 $this->lNegocioProcedimientoMedico = new ProcedimientoMedicoLogicaNegocio();
		 $this->modeloProcedimientoMedico = new ProcedimientoMedicoModelo();
		 $this->lNegocioTipoProcedimientoMedico = new TipoProcedimientoMedicoLogicaNegocio();
		 $this->modeloTipoProcedimientoMedico = new TipoProcedimientoMedicoModelo();
		 $this->lNegocioSubtipoProcedimientoMedico = new SubtipoProcedimientoMedicoLogicaNegocio();
		 $this->modeloSubtipoProcedimientoMedico = new SubtipoProcedimientoMedicoModelo();
		 /*****************************************************/
		 $this->lNegocioHistoriaOcupacional = new HistoriaOcupacionalLogicaNegocio();
		 $this->modeloHistoriaOcupacional = new HistoriaOcupacionalModelo();
		 $this->lNegocioDetalleHistorialOcupacional = new DetalleHistorialOcupacionalLogicaNegocio();
		 $this->modeloDetalleHistorialOcupacional = new DetalleHistorialOcupacionalModelo();
		 $this->lNegocioAccidentesLaborales = new AccidentesLaboralesLogicaNegocio();
		 $this->modeloAccidentesLaborales = new AccidentesLaboralesModelo();
		 $this->lNegocioCie = new CieLogicaNegocio();
		 $this->modeloCie = new CieModelo();
		 $this->lNegocioAntecedentesSaludFamiliar= new AntecedentesSaludFamiliarLogicaNegocio();
		 $this->modeloAntecedentesSaludFamiliar = new AntecedentesSaludFamiliarModelo();
		 $this->lNegocioAntecedentesSalud= new AntecedentesSaludLogicaNegocio();
		 $this->modeloAntecedentesSalud = new AntecedentesSaludModelo();
		 $this->lNegocioDetalleAntecedentesSalud= new DetalleAntecedentesSaludLogicaNegocio();
		 $this->modeloDetalleAntecedentesSalud = new DetalleAntecedentesSaludModelo();
		 $this->lNegocioRevisionOrganosSistemas= new RevisionOrganosSistemasLogicaNegocio();
		 $this->modeloRevisionOrganosSistemas = new RevisionOrganosSistemasModelo();
		 $this->lNegocioDetalleRevisionOrganosSistemas= new DetalleRevisionOrganosSistemasLogicaNegocio();
		 $this->modeloDetalleRevisionOrganosSistemas = new DetalleRevisionOrganosSistemasModelo();
		 $this->lNegocioInmunizacion= new InmunizacionLogicaNegocio();
		 $this->modeloInmunizacion = new InmunizacionModelo();
		 $this->lNegocioHabitos= new HabitosLogicaNegocio();
		 $this->modeloHabitos = new HabitosModelo();
		 $this->lNegocioEstiloVida= new EstiloVidaLogicaNegocio();
		 $this->modeloEstiloVidad = new EstiloVidaModelo();
		 $this->lNegocioExamenFisico= new ExamenFisicoLogicaNegocio();
		 $this->modeloExamenFisico = new ExamenFisicoModelo();
		 $this->lNegocioExamenesClinicos= new ExamenesClinicosLogicaNegocio();
		 $this->modeloExamenesClinicos = new ExamenesClinicosModelo();
		 $this->lNegocioDetalleExamenesClinicos= new DetalleExamenesClinicosLogicaNegocio();
		 $this->modeloDetalleExamenesClinicos = new DetalleExamenesClinicosModelo();
		 $this->lNegocioAdjuntosHistoriaClinica= new AdjuntosHistoriaClinicaLogicaNegocio();
		 $this->modeloAdjuntosHistoriaClinica = new AdjuntosHistoriaClinicaModelo();
		 $this->lNegocioExamenParaclinicos= new ExamenParaclinicosLogicaNegocio();
		 $this->modeloExamenParaclinicos = new ExamenParaclinicosModelo();
		 $this->lNegocioDetalleExamenParaclinicos= new DetalleExamenParaclinicosLogicaNegocio();
		 $this->modeloDetalleExamenParaclinicos = new DetalleExamenParaclinicosModelo();
		 $this->lNegocioImpresionDiagnostica = new ImpresionDiagnosticaLogicaNegocio();
		 $this->modeloImpresionDiagnostica = new ImpresionDiagnosticaModelo();
		 //***********************************
		 $this->lNegocioAusentismoMedico = new AusentismoMedicoLogicaNegocio();
		 $this->modeloAusentismoMedico = new AusentismoMedicoModelo();
		 $this->lNegocioElementoProteccion = new ElementoProteccionLogicaNegocio();
		 $this->modeloElementoProteccion = new ElementoProteccionModelo();
		 $this->lNegocioEnfermedadProfesional = new EnfermedadProfesionalLogicaNegocio();
		 $this->modeloEnfermedadProfesional = new EnfermedadProfesionalModelo();
		 $this->lNegocioRecomendaciones = new RecomendacionesLogicaNegocio();
		 $this->modeloRecomendaciones = new RecomendacionesModelo();
		 $this->lNegocioEvaluacionPrimaria = new EvaluacionPrimariaLogicaNegocio();
		 $this->modeloEvaluacionPrimaria = new EvaluacionPrimariaModelo;
		 $this->lNegocioDetalleEvaluacionPrimaria = new DetalleEvaluacionPrimariaLogicaNegocio();
		 $this->modeloDetalleEvaluacionPrimaria = new DetalleEvaluacionPrimariaModelo();
		 $this->lNegocioLog = new LogLogicaNegocio();
		 $this->modeloLog = new LogModelo();
		 
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
    		 $this->perfilUsuario();
    		 if($this->perfilUsuario == 'PFL_MEDICO'){
    		     $modeloHistoriaClinica = $this->lNegocioHistoriaClinica->buscarHistoriaClinica();
    		     $this->tablaHtmlHistoriaClinica($modeloHistoriaClinica);
    		     $this->filtroHistorias();
    		     require APP . 'HistoriasClinicas/vistas/listaHistoriaClinicaVista.php';
    		 }else{
    		     $modeloHistoriaClinica = $this->lNegocioHistoriaClinica->buscarLista("identificador_paciente='".$_SESSION['usuario']."'");
    		     $this->tablaHtmlHistoriaClinicaPaciente($modeloHistoriaClinica);
    		     $this->filtroHistorias();
    		     require APP . 'HistoriasClinicas/vistas/listaHistoriaClinicaPacienteVista.php';
    		 }
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nueva Historia Clínica"; 
		 $this->divInformacionPaciente('');
		 $this->divInformacionCargo('');
		 $this->divInformacionDiscapacidad('');
		 $arrayParametros = array(
		 	'identificador' => $_SESSION['usuario']);
		 $resultFirma = $this->lNegocioHistoriaClinica->obtenerDatosFirma($arrayParametros);
		 if($resultFirma->count()){
		 	$this->divInformacionFirma($resultFirma->current());
		 }else{
		 	$this->divInformacionFirma('');
		 }
		 require APP . 'HistoriasClinicas/vistas/formularioHistoriaClinicaVista.php';
		}	/**
		* Método para registrar en la base de datos -HistoriaClinica
		*/
		public function guardar()
		{
		   $this->lNegocioAccidentesLaborales->guardar($_POST);
		}
		  /**
		   * Método para registrar en la base de datos -HistoriaClinica
		   */
		  public function guardarRegistros()
		  {
		      $estado = 'EXITO';
		      $mensaje = '';
		      $contenido = '';
		      
		      if(isset($_POST['id_historia_clinica'])){
		          if(!empty($_POST['id_historia_clinica'])){
		              $resultado = $this->lNegocioHistoriaClinica->guardarRegistros($_POST);
		              $modeloHistoriaClinica = $this->lNegocioHistoriaClinica->buscar($_POST['id_historia_clinica']);
		              if($resultado){
		                  $rutaReporte = 'HistoriasClinicas/vistas/reportes/historiaClinica.jasper';
		                  $rutaCarpeta = HIST_CLI_URL."adjuntosHistoriaClinica/".$modeloHistoriaClinica->getIdentificadorPaciente();
		                  if (!file_exists('../../' . $rutaCarpeta)) {
		                      mkdir('../../' .$rutaCarpeta, 0777, true);
		                  }
		                 
		                  $nombre = 'historia_clinica_';
		                  $rutaArchivo = "adjuntosHistoriaClinica/".$modeloHistoriaClinica->getIdentificadorPaciente()."/".$nombre.$modeloHistoriaClinica->getIdentificadorPaciente();
		                  try {
		                      $jasper = new JasperReport();
		                      $datosReporte = array();
		                      
		                      $rutaArchivoBase = 'HistoriasClinicas/archivos/';
		                      $datosReporte = array(
		                          'rutaReporte' => $rutaReporte,
		                          'rutaSalidaReporte' => $rutaArchivoBase.$rutaArchivo,
		                          'tipoSalidaReporte' => array('pdf'),
		                          'parametrosReporte' => array(
		                              'idHistoriaClinica' => $_POST['id_historia_clinica'],
		                              'fondoCertificado' => RUTA_IMG_GENE.'fondoCertificado.png'),
		                          'conexionBase' => 'SI'
		                      );
		                      $validar=1;
		                      $jasper->generarArchivo($datosReporte);
		                      $contenido = HIST_CLI_URL.$rutaArchivo.'.pdf';
		                  } catch (\Exception  $e) {
		                      $validar=0;
		                  }
		                  if($validar){
		                      $adjuntoHC = $this->lNegocioAdjuntosHistoriaClinica->buscarLista("id_historia_clinica=".$_POST["id_historia_clinica"]." and id_procedimiento_medico is null");
		                      if($adjuntoHC->count()){
		                          $arrayAdjunto = array(
		                              'id_adjuntos_historia_clinica' => $adjuntoHC->current()->id_adjuntos_historia_clinica,
		                              'id_historia_clinica' => $_POST['id_historia_clinica'],
		                              'archivo_adjunto' => HIST_CLI_URL.$rutaArchivo.'.pdf',
		                              'descripcion_adjunto' => 'Historia Clínica pdf'
		                          );
		                      }else{
		                          $arrayAdjunto = array(
		                              'id_historia_clinica' => $_POST['id_historia_clinica'],
		                              'archivo_adjunto' => HIST_CLI_URL.$rutaArchivo.'.pdf',
		                              'descripcion_adjunto' => 'Historia Clínica pdf'
		                          );
		                      }
		                      $id = $this->lNegocioAdjuntosHistoriaClinica->guardar($arrayAdjunto);
		                      if($id){
		                          $mensaje = 'Registro agregado correctamente';
		                      }else{
		                          $estado = 'FALLO';
		                          $mensaje = 'Error al guardar el registro..!!';
		                      }
		                  }else{
		                      $estado = 'ERROR';
		                      $mensaje = 'Error al crear el archivo pdf de la historia clínica';
		                  }
		              }else {
		                  $estado = 'ERROR';
		                  $mensaje = 'Error al guardar los datos !!';
		              }
		          }else{
		              $estado = 'ERROR';
		              $mensaje = 'Debe crear la historia clínica !!';
		          }
		      }else{
		          $estado = 'ERROR';
		          $mensaje = 'Debe crear la historia clínica !!';
		      }
		      
		      echo json_encode(array(
		          'estado' => $estado,
		          'mensaje' => $mensaje,
		          'contenido' => $contenido
		      ));
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: HistoriaClinica
		*/
		public function editar()
		{
		 $this->perfilUsuario();
		 $this->idHistorialClinica = $_POST['id'];
		 $this->estado = "editar";
		 $this->modeloHistoriaClinica = $this->lNegocioHistoriaClinica->buscar($_POST["id"]);
		 $adjuntoHC = $this->lNegocioAdjuntosHistoriaClinica->buscarLista("id_historia_clinica = ".$_POST["id"]." and id_procedimiento_medico is null");
		 if($adjuntoHC->count()){
		   $this->adjuntoHistoriaClinica = $adjuntoHC->current()->archivo_adjunto;
		 }
		 $ausentismo = $this->lNegocioAusentismoMedico->buscarLista("id_historia_clinica =".$_POST["id"]);
		 if($ausentismo->count()){
		     $this->modeloAusentismoMedico = $this->lNegocioAusentismoMedico->buscar($ausentismo->current()->id_ausentismo_medico);
		 }
		 $enfermedadProfesional = $this->lNegocioEnfermedadProfesional->buscarLista("id_historia_clinica =".$_POST["id"]);
		 if($enfermedadProfesional->count()){
		     $this->modeloEnfermedadProfesional= $this->lNegocioEnfermedadProfesional->buscar($enfermedadProfesional->current()->id_enfermedad_profesional);
		 }
		 $examenFisico = $this->lNegocioExamenFisico->buscarLista("id_historia_clinica =".$_POST["id"]);
		 if($examenFisico->count()){
		     $this->modeloExamenFisico= $this->lNegocioExamenFisico->buscar($examenFisico->current()->id_examen_fisico);
		 }
		 $recomendaciones = $this->lNegocioRecomendaciones->buscarLista("id_historia_clinica =".$_POST["id"]);
		 if($recomendaciones->count()){
		     $this->modeloRecomendaciones= $this->lNegocioRecomendaciones->buscar($recomendaciones->current()->id_recomendaciones);
		 }
		 
		 $this->listarLog($_POST["id"]);
		 $arrayParametros = array(
		     'identificador_paciente' => $this->modeloHistoriaClinica->getIdentificadorPaciente());
		 $resultado = $this->lNegocioHistoriaClinica->buscarInformacionPaciente($arrayParametros);
		 $this->divInformacionPaciente($resultado->current());
		 $resultCargo = $this->lNegocioHistoriaClinica->obtenerDatosContrato($arrayParametros);
		 $this->divInformacionCargo($resultCargo->current());
		 $this->divInformacionDiscapacidad($resultado->current());
		 
		 if($this->perfilUsuario == 'PFL_MEDICO'){
		     $arrayParametros = array(
		         'identificador' => $_SESSION['usuario']);
		     $resultFirma = $this->lNegocioHistoriaClinica->obtenerDatosFirma($arrayParametros);
		     if($resultFirma->count()){
		         $this->divInformacionFirma($resultFirma->current());
		     }else{
		         $this->divInformacionFirma('');
		     }
		     $this->accion = "Editar Historia Clínica"; 
		     require APP . 'HistoriasClinicas/vistas/formularioHistoriaClinicaVista.php';
		 }else{
		     $this->accion = "Historia Clínica"; 
		     $arrayParametros = array(
		         'identificador' => $this->modeloHistoriaClinica->getIdentificadorMedico());
		     $resultFirma = $this->lNegocioHistoriaClinica->obtenerDatosFirma($arrayParametros);
		     if($resultFirma->count()){
		         $this->divInformacionNotificacion($resultFirma->current(),$this->modeloHistoriaClinica->getFechaCreacion());
		     }else{
		         $this->divInformacionNotificacion('');
		     }
		     require APP . 'HistoriasClinicas/vistas/formularioHistoriaClinicaReporteVista.php';
		 }
		
		}	/**
		* Método para borrar un registro en la base de datos - HistoriaClinica
		*/
		public function borrar()
		{
		  $this->lNegocioHistoriaClinica->borrar($_POST['elementos']);
		}	
		  /**
		* Construye el código HTML para desplegar la lista de - HistoriaClinica
		*/
		 public function tablaHtmlHistoriaClinica($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
    		  	$arrayParametros = array(
    		  		'identificador' => $fila['identificador_paciente']);
    		  	$resultConsulta = $this->lNegocioHistoriaClinica->obtenerDatosFirma($arrayParametros);
        		if(isset($resultConsulta->current()->funcionario)){
        		   $this->itemsFiltrados[] = array(
        		  '<tr id="' . $fila['id_historia_clinica'] . '"
        		    class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'HistoriasClinicas\historiaClinica"
        		    data-opcion="editar" ondragstart="drag(event)" draggable="true"
        		    data-destino="detalleItem">
        		    <td>' . ++$contador . '</td>
                      <td style="white - space:nowrap; "><b>' . date('d / m / Y',strtotime($fila['fecha_creacion'])) . '</b></td>
                    <td>'
                    	  . $fila['identificador_paciente'] . '</td>
                    <td>' . $resultConsulta->current()->funcionario . '</td>
                    </tr>');
        		}
		  }
		}
	}
	/**
	 * Construye el código HTML para desplegar la lista de - HistoriaClinica
	 */
	public function tablaHtmlHistoriaClinicaPaciente($tabla) {
	    {
	        $contador = 0;
	        foreach ($tabla as $fila) {
	            $this->itemsFiltrados[] = array(
	                '<tr id="' . $fila['id_historia_clinica'] . '"
    		    class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'HistoriasClinicas\historiaClinica"
    		    data-opcion="editar" ondragstart="drag(event)" draggable="true"
    		    data-destino="detalleItem">
    		    <td>' . ++$contador . '</td>
                  <td style="white - space:nowrap; "><b>' . date('d / m / Y',strtotime($fila['fecha_creacion'])) . '</b></td>
                <td> Historia Clínica</td>
                </tr>');
	        }
	    }
	}
	/**
	 * funcion para buscar informacion del funcionario
	 */
	public function buscarFuncionario(){
		$estado = 'EXITO';
		$mensaje = '';
		$paciente = '';
		$puesto = '';
		$discapacidad = '';
		
		$arrayParametros = array(
			'identificador_paciente' => $_POST['identificador']);
		$resultado = $this->lNegocioHistoriaClinica->buscarInformacionPaciente($arrayParametros);
		if($resultado->count()>0){
			$this->divInformacionPaciente($resultado->current());
			$paciente = $this->divInformacion;
			$resultCargo = $this->lNegocioHistoriaClinica->obtenerDatosContrato($arrayParametros);
			$this->divInformacionCargo($resultCargo->current());
			$puesto = $this->divCargo;
			$this->divInformacionDiscapacidad($resultado->current());
			$discapacidad = $this->divDiscapacidad;
		}else{
			$estado = 'ERROR';
			$mensaje = 'No existe el funcionario buscado !!';
			$this->divInformacionPaciente('');
			$paciente = $this->divInformacion;
			$this->divInformacionCargo('');
			$puesto = $this->divCargo;
			$this->divInformacionDiscapacidad('');
			$discapacidad = $this->divDiscapacidad;
		}
		
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'paciente' => $paciente,
			'puesto' => $puesto,
			'discapacidad' => $discapacidad
		));
	}
	
	/**
	 * funcion para construir la informacion del paciente
	 * 
	 */
	public function divInformacionPaciente($tabla) {
		
		if($tabla == ''){
		$arrayPaciente = array(
			'identificador' => '',
			'funcionario' => '',
			'fecha_nacimiento' => '',
			'genero' => '',
			'estado_civil' => '',
			'edad' => '',
			'tipo_sangre' => '',
			'nivel_instruccion' => '',
			'convencional' => '',
		    'lateralidad' => '',
		    'religion' => '',
		    'canton' =>''
			
		);
		$tabla = $arrayPaciente;
		}
		
		$div = '
		<legend>Información del funcionario</legend>	
		<div data-linea="3">
			<label for="identificador_paciente">Nombres y Apellidos: </label>
			<span>'.$tabla['funcionario'].'</span>
			<input type="hidden" id="identificador_paciente" name="identificador_paciente" value="'.$tabla['identificador'].'" maxlength="13" />
		</div>				

		<div data-linea="4">
			<label for="fecha_nacimiento">Fecha de nacimiento:</label>
			<span>'.$tabla['fecha_nacimiento'].'</span>
		</div>				

		<div data-linea="4">
			<label for="lugar_nacimiento">Lugar de nacimiento: </label>
			<span>'.$tabla['canton'].'</span>
		</div>				

		<div data-linea="5">
			<label for="genero">Género: </label>
			<span>'.$tabla['genero'].'</span>
		</div>				

		<div data-linea="5">
			<label for="convencional">Teléfono:</label>
			<span>'.$tabla['convencional'].'</span>
		</div>				

		<div data-linea="5">
			<label for="estado_civil">Estado civil:</label>
			<span>'.$tabla['estado_civil'].'</span>
		</div>				

		<div data-linea="6">
			<label for="edad">Edad (años):</label>
			<span>'.$tabla['edad'].'</span>
		</div>				

		<div data-linea="6">
			<label for="religion">Religión:</label>
			<span>'.$tabla['religion'].'</span>
		</div>				

		<div data-linea="6">
			<label for="tipo_sangre">Grupo sanguíneo: </label>
			<span>'.$tabla['tipo_sangre'].'</span>
		</div>				

		<div data-linea="7">
			<label for="nivel_instruccion">Nivel de instrucción: </label>
			<span>'.$tabla['nivel_instruccion'].'</span>
		</div>				

		<div data-linea="7">
			<label for="lateralidad">Lateralidad: </label>
			<span>'.$tabla['lateralidad'].'</span>
		</div>	

		<div data-linea="8">
			<label for="genero">Identidad de género: </label>
			<span>'.$tabla['genero'].'</span>
		</div>
	';
		
		$this->divInformacion = $div;
	}
	
	/**
	 * 
	 * funcion para construir la vista del contrato
	 */
	public function divInformacionCargo($tabla) {
		
		if($tabla == ''){
			$arrayCargo = array(
				'nombre_puesto' => '',
				'oficina' => '',
				'coordinacion' => '',
				'direccion' => '',
				'jornada_laboral' => '',
				'fecha_inicial' => ''
				
			);
			$tabla = $arrayCargo;
		}
		
		$div = '
		<legend>Información Ocupacional Cargo Actual</legend>
		<div data-linea="1">
			<label for="nombre_puesto">Cargo:</label>
			<span>'.$tabla['nombre_puesto'].'</span>
		</div>				

		<div data-linea="2">
			<label for="oficina">Oficina: </label>
			<span>'.$tabla['oficina'].'</span>
		</div>				

		<div data-linea="3">
			<label for="coordinacion">Coordinación: </label>
			<span>'.$tabla['coordinacion'].'</span>
		</div>				

		<div data-linea="4">
			<label for="direccion">Dirección - Oficina Técnica: </label>
			<span>'.$tabla['direccion'].'</span>
		</div>				

		<div data-linea="5">
			<label for="jornada_laboral">Jornada laboral: </label>
			<span>'.$tabla['jornada_laboral'].'</span>
		</div>				

		<div data-linea="5">
			<label for="fecha_inicial">Fecha de Ingreso: </label>
			<span>'.$tabla['fecha_inicial'].'</span>
		</div>				
	';
		
		$this->divCargo = $div;
	}
	/**
	 *
	 * funcion para construir la vista del contrato
	 */
	public function divInformacionDiscapacidad($tabla) {
		
		if($tabla == ''){
			$arrayDiscapacidad = array(
				'tiene_discapacidad' => '',
				'carnet_conadis_empleado' => '',
				'representante_familiar_discapacidad' => '',
				'carnet_conadis_familiar' => '',
				'tiene_enfermedad_catastrofica' => '',
				'nombre_enfermedad_catastrofica' => ''
				
			);
			$tabla = $arrayDiscapacidad;
		}
		
		$div = '
		<legend>Información de discapacidad</legend>				

		<div data-linea="1">
			<label for="tiene_discapacidad">Tiene discapacidad:</label>
			<span>'.$tabla['tiene_discapacidad'].'</span>
		</div>				

		<div data-linea="1">
			<label for="carnet_conadis_empleado">No. Carnet: </label>
			<span>'.$tabla['carnet_conadis_empleado'].'</span>
		</div>				

		<div data-linea="2">
			<label for="representante_familiar_discapacidad">¿Es representante de persona con discapacidad?:</label>
			<span>'.$tabla['representante_familiar_discapacidad'].'</span>
		</div>				

		<div data-linea="3">
			<label for="carnet_conadis_familiar">No. Carnet de la persona con discapacidad: </label>
			<span>'.$tabla['carnet_conadis_familiar'].'</span>
		</div>				

		<div data-linea="4">
			<label for="tiene_enfermedad_catastrofica">¿Tiene enfermedad catastrófica?: </label>
			<span>'.$tabla['tiene_enfermedad_catastrofica'].'</span>
		</div>				

		<div data-linea="4">
			<label for="nombre_enfermedad_catastrofica">Nombre enfermedad catastrófica:</label>
			<span>'.$tabla['nombre_enfermedad_catastrofica'].'</span>
		</div>	
	';
		
		$this->divDiscapacidad = $div;
	}
	
	/**
	 *
	 * funcion para construir la vista de la firma
	 */
	public function divInformacionFirma($tabla) {
		
		if($tabla == ''){
			$arrayFirma = array(
				'funcionario' => '',
				'cargo' => '',
				'identificador' => ''
			);
			$tabla = $arrayFirma;
		}
		
		$div = '
		<legend>Firma</legend>				

		<div data-linea="1">
			<label for="funcionario">Nombre del Médico:</label>
			<span>'.$tabla['funcionario'].'</span>
		</div>				

		<div data-linea="2">
			<label for="cargo">Cargo: </label>
			<span>'.$tabla['cargo'].'</span>
		</div>				

		<div data-linea="3">
			<label for="identificador">CMP:</label>
			<span>'.$tabla['identificador'].'</span>
		</div>	
	';
		
		$this->firma= $div;
	}
	
	/**
	 *
	 * funcion para construir la vista de la notificación
	 */
	public function divInformacionNotificacion($tabla,$fecha=null) {
	    
	    if($tabla == ''){
	        $arrayFirma = array(
	            'funcionario' => '',
	            'cargo' => '',
	            'identificador' => '',
	            'fecha_creacion' => ''
	        );
	        $tabla = $arrayFirma;
	    }
	    
	    $div = '
		<legend>Notificación</legend>
	    <div data-linea="1">
			<label for="funcionario">Fecha de creación de la Historia Clínica:</label>
			<span>'.date('Y-m-d', strtotime($fecha)).'</span>
		</div>
		<div data-linea="2">
			<label for="funcionario">Realizado por:</label>
			<span>'.$tabla['funcionario'].'</span>
		</div>
			    
		<div data-linea="3">
			<label for="cargo">Cargo: </label>
			<span>'.$tabla['cargo'].'</span>
		</div>
			    
		<div data-linea="4">
			<label for="identificador">CMP:</label>
			<span>'.$tabla['identificador'].'</span>
		</div>
	';
	    
	    $this->firma= $div;
	}
	/**
	 * crear subtipos
	 */
	public function listarSubtipos($subtipo){
		
		$datos='';
		$i=0;
		foreach ($subtipo as $item) {
			
			if($i==0){
				$datos .= '<tr>';
			}
			$datos .= '<td><input class="case" name="subtipoList[]" type="checkbox" value="'.$item->id_subtipo_proced_medico.'"> '.$item->subtipo.'</td>';
			$i++;
			if($i==3){
				$datos .= '<tr>';
				$i=0;
			}
		}
		
		$html = '
           <br>
			<label for="exposicion">Seleccione uno o varios subtipos de exposición: </label>
			<div>
			<table  style="width: 100%;">
			<tr> <td colspan="3"><input onclick="verificarCheckbox(id);" name="checkTodos" id="checkTodos" type="checkbox" class="checkTodos"> Seleccionar todos</td> </tr>
			'.$datos.'
			
			</table>
         ';
		return $html;
	}
	
	/**
	 * funcion para buscar informacion de subtipos de exposicion
	 */
	public function buscarSubtipos(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		
		$arrayIndex = " id_tipo_procedimiento_medico = ".$_POST['tipoProcedimiento']."";
		$subtipo = $this->lNegocioSubtipoProcedimientoMedico->buscarLista($arrayIndex);
		
		if($subtipo->count()){
			$contenido = $this->listarSubtipos($subtipo);
		}
		
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido
		));
	}
	/**
	 * funcion para crear la historia clínica
	 */
	public function crearHistoriaClinica() {
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		
		if($_POST['identificador_paciente'] != ''){
			$datos = array(
				'identificador_paciente' => $_POST['identificador_paciente'],
				'identificador_medico' => $_SESSION['usuario']
			);
			
			$verificar = $this->lNegocioHistoriaClinica->buscarLista("identificador_paciente='".$_POST['identificador_paciente']."'");			
			if(!$verificar->count()){
				$idHistoria = $this->lNegocioHistoriaClinica->guardar($datos);
				$mensaje = 'Historia clínica creada correctamente';
				$contenido = $idHistoria;
			}else{
				$estado = 'ERROR';
				$mensaje = 'Historia clínica ya registrada !!';
			}
			
		}else{
			$estado = 'ERROR';
			$mensaje = 'Identificador del paciente vacio !!';
		}
		
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido
		));
	}
	/**
	 * funcion para agregar exposiciones en paciente
	 */
	public function agregarExposicion() {

		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$accidente = '';
		
		if(isset($_POST['id_historia_clinica'])){
		    if(!empty($_POST['id_historia_clinica'])){
    			$arrayIndex = "nombre ='Exposición'";
    			$procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
    			$_POST['id_procedimiento_medico'] = $procedi->current()->id_procedimiento_medico;
    			$resultado = $this->lNegocioHistoriaOcupacional->guardarHistoriaDetalle($_POST);
    			if($resultado){
    			    $contenido = $this->listarHistoriaOcupacional($_POST['id_historia_clinica']);
    			    $accidente = $this->comboHistoriaOcupacional($_POST['id_historia_clinica']);
    			    $mensaje = 'Registro agregado correctamente';
    			}else {
    			    $estado = 'ERROR';
    			    $mensaje = 'Error al guardar los datos !!';
    			}
		    }else{
		        $estado = 'ERROR';
		        $mensaje = 'Debe crear la historia clínica !!';
		    }
		}else{
			$estado = 'ERROR';
			$mensaje = 'Debe crear la historia clínica !!';
		}
		
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido,
		    'accidente' => $accidente
		));
	}
	/**
	 * funcion para eliminar exposiciones en paciente
	 */
	public function eliminarExposicion() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $accidente = '';
	    
	    
	    if(isset($_POST['id_historia_ocupacional']) && isset($_POST['id_historia_clinica'])){
	        
	        $verificar = $this->lNegocioAccidentesLaborales->buscarLista("id_historia_ocupacional=".$_POST['id_historia_ocupacional']." and id_historia_clinica=".$_POST['id_historia_clinica']);
	        if($verificar->count() <= 0){
	        $this->lNegocioDetalleHistorialOcupacional->borrarPorParametro("id_historia_ocupacional", $_POST['id_historia_ocupacional']);
	        $this->lNegocioHistoriaOcupacional->borrar($_POST['id_historia_ocupacional']);
	        $contenido = $this->listarHistoriaOcupacional($_POST['id_historia_clinica']);
	        $accidente = $this->comboHistoriaOcupacional($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'No se puede eliminar, se utiliza en Accidentes Laborales !!';
	        }
	        
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido,
	        'accidente' => $accidente
	    ));
	}
	/**
	 * listar historia ocupacional agregada
	 */
	public function listarHistoriaOcupacional($idHistoriaClinica=null,$opt=1){
	    $datos=$html='';
	    $listSubtipo='';
	    if($idHistoriaClinica != null){
		$consulta = $this->lNegocioHistoriaOcupacional->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
		if($consulta->count()){
			foreach ($consulta as $item) {
			   $listSubtipo=' ';
			$tipo =	$this->lNegocioTipoProcedimientoMedico->buscar($item->id_tipo_procedimiento_medico);
			
			$detalle = $this->lNegocioDetalleHistorialOcupacional->buscarLista("id_historia_ocupacional =".$item->id_historia_ocupacional." order by 1");
			
			if($detalle->count()){
			    $listSubtipo=' ';
			    $ban = false;
			    foreach ($detalle as $subItem) {
			        if($ban){
			            $listSubtipo .= ', ';
			        }
				    $subtipo = $this->lNegocioSubtipoProcedimientoMedico->buscar($subItem->id_subtipo_proced_medico);
				    $listSubtipo .= $subtipo->getSubtipo();
				    $ban = true;
				}
			}
			$datos .= '<tr>';
			$datos .= '<td>'.$item->empresa.'</td>';
			$datos .= '<td>'.$item->cargo.'</td>';
			$datos .= '<td>'.$tipo->getTipo().'</td>';
			$datos .= '<td>'.$listSubtipo.'</td>';
			$datos .= '<td>'.$item->tiempo_exposicion.'</td>';
			if($opt){
    			$datos .= '<td><button class="bEliminar icono" onclick="eliminarSubtipo('.$item->id_historia_ocupacional.'); return false; "></button></td>';
			}else{
			    $datos .= '<td></td>';
			}
			$datos .= '<tr>';
			}
			
		$html = '
				<table style="width: 100%;">
					<thead><tr>
						<th>Empresa</th>
						<th>Cargo desempeñado</th>
						<th>Tipo de exposición</th>
						<th>Subtipo(s) de exposición</th>
						<th>Años de exposición</th>
						<th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>
           
          ';
		 }
	    }
		return $html;
	}
	
	/**
	 * combo historia ocupacional agregada
	 */
	public function comboHistoriaOcupacional($idHistoriaClinica=null,$idHistoriaOcupacional=null){
	    $combo = '<option value="">Seleccionar....</option>';
	    if($idHistoriaClinica != null){
	       $consulta = $this->lNegocioHistoriaOcupacional->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	       if($consulta->count()){
	           foreach ($consulta as $item) {
	               if ($idHistoriaOcupacional == $item->id_historia_ocupacional)
	               {
	                   $combo .= '<option value="' . $item->id_historia_ocupacional . '" selected>' . $item->empresa. '</option>';
	               } else
	               {
	                   $combo .= '<option value="' . $item->id_historia_ocupacional . '">' . $item->empresa . '</option>';
	               }
	           }
	       }
	    }
	    
	    return $combo;
	}
	/**
	 * funcion para agregar accidente en paciente
	 */
	public function agregarAccidente() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id_historia_clinica']) && isset($_POST['id_historia_ocupacional'])){
	        if(!empty($_POST['id_historia_clinica'])){
    	        $resultado = $this->lNegocioAccidentesLaborales->guardar($_POST);
    	        if($resultado > 0){
    	            $contenido = $this->listarAccidenteLaboral($_POST['id_historia_clinica']);
    	            $mensaje = 'Registro agregado correctamente';
    	        }else {
    	            $estado = 'ERROR';
    	            $mensaje = 'Error al guardar los datos !!';
    	        }
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'Debe crear la historia clínica !!';
	        }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica !!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * funcion para eliminar accidente en paciente
	 */
	public function eliminarAccidente() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id_accidentes_laborales']) && isset($_POST['id_historia_clinica'])){
	        $this->lNegocioAccidentesLaborales->borrar($_POST['id_accidentes_laborales']);
	        $contenido = $this->listarAccidenteLaboral($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	        
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	
	/**
	 * listar accdientes laborales
	 */
	public function listarAccidenteLaboral($idHistoriaClinica=null,$opt=1){
	    $datos=$html='';
	    if($idHistoriaClinica != null){
	    $consulta = $this->lNegocioAccidentesLaborales->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	    if($consulta->count()){
	        foreach ($consulta as $item) {
	            $historiaOcupacional =	$this->lNegocioHistoriaOcupacional->buscar($item->id_historia_ocupacional);
	            $datos .= '<tr>';
	            $datos .= '<td>'.$item->mes.'/'.$item->anio.'</td>';
	            $datos .= '<td>'.$historiaOcupacional->getEmpresa().'</td>';
	            $datos .= '<td>'.$item->naturaleza_lesion.'</td>';
	            $datos .= '<td>'.$item->parte_afectada.'</td>';
	            $datos .= '<td>'.$item->dias_incapacidad.'</td>';
	            $datos .= '<td>'.$item->secuelas.'</td>';
	            if($opt){
	               $datos .= '<td><button class="bEliminar icono" onclick="eliminarAccidente('.$item->id_accidentes_laborales.'); return false; "></button></td>';
	            }else{
	               $datos .='<td></td>'; 
	            }
	            $datos .= '<tr>';
	        }
	        
	        $html = '
				<table style="width: 100%;">
					<thead><tr>
						<th>Fecha</th>
						<th>Nombre de la empresa</th>
						<th>Naturaleza de la lesión</th>
						<th>Parte del cuerpo afectado</th>
						<th>Días de incapacidad</th>
                        <th>Secuelas</th>
						<th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>
					    
           ';
	      }
	    }
	    return $html;
	}
	
	/**
	 * crear elementos de protección
	 */
	public function listarElementosProteccion($idHistoriaClinica=null){
	  
	    $arrayIndex = "nombre ='Elementos de protección'";
	    $procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
	    $arrayIndex = "id_procedimiento_medico =".$procedi->current()->id_procedimiento_medico."";
	    $tipo = $this->lNegocioTipoProcedimientoMedico->buscarLista($arrayIndex);
	    $datos='';
	    $i=0;
	    foreach ($tipo as $item) {
	        if($i==0){
	            $datos .= '<tr>';
	        }
	        if($idHistoriaClinica != null){
	            $elementos = $this->lNegocioElementoProteccion->buscarLista("id_historia_clinica=".$idHistoriaClinica." and id_tipo_procedimiento_medico=".$item->id_tipo_procedimiento_medico);
	            if($elementos->count()){
	                $datos .= '<td><input class="elemProte" checked name="elementoProteccion[]" type="checkbox" value="'.$item->id_tipo_procedimiento_medico.'"> '.$item->tipo.'</td>';
	            }else{
	                $datos .= '<td><input class="elemProte" name="elementoProteccion[]" type="checkbox" value="'.$item->id_tipo_procedimiento_medico.'"> '.$item->tipo.'</td>';
	            }
	        }else{
	            $datos .= '<td><input class="elemProte" name="elementoProteccion[]" type="checkbox" value="'.$item->id_tipo_procedimiento_medico.'"> '.$item->tipo.'</td>';
	            }
	        
	         $i++;
	        if($i==3){
	            $datos .= '<tr>';
	            $i=0;
	        }
	    }
	    
	    $html = '
			<table  style="width: 100%;">
			'.$datos.'
			</table>
         ';
	    return $html;
	}
	
	/**
	 * Combo de tipo de procedimiento
	 */
	public function comboTipoProcedimiento($tipo,$idTipoProcedimientoMedico=null)
	{
	    $arrayIndex = "nombre ='".$tipo."' and estado='Activo'";
	    $procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
	    if($procedi->count()){
	       $tipoProce = $this->lNegocioTipoProcedimientoMedico->buscarLista("id_procedimiento_medico =".$procedi->current()->id_procedimiento_medico." and estado='Activo' order by 1");
	    }
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($tipoProce as $item)
	    {
	        if ($idTipoProcedimientoMedico == $item->id_tipo_procedimiento_medico)
	        {
	            $combo .= '<option value="' . $item->id_tipo_procedimiento_medico . '" selected>' . $item->tipo. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item->id_tipo_procedimiento_medico . '">' . $item->tipo . '</option>';
	        }
	    }
	    return $combo;
	}

	/**
	 * combo cie 10
	 */
	public function comboCie10($opt, $idCie10=null){
	    $cie10 = $this->lNegocioCie->buscarLista("estado='Activo' order by 1");
	    $combo = '<option value="">Seleccionar....</option>';
	            foreach ($cie10 as $item) {
	                if($opt == 'codigo'){
	                    $text = $item->codigo;
	                }else{
	                    $text = $item->descripcion;
	                }
	                if ($idCie10 == $item->id_cie)
	                {
	                    $combo .= '<option value="' . $item->id_cie . '" selected>' . $text . '</option>';
	                } else
	                {
	                    $combo .= '<option value="' . $item->id_cie . '">' . $text . '</option>';
	                }
	            }
	    return $combo;
	}
	
	/**
	 * funcion para agregar accidente en paciente
	 */
	public function agregarAntecedentesFamiliares() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_historia_clinica']) && isset($_POST['id_cie']) && isset($_POST['id_tipo_procedimiento_medico'])){
	        if(!empty($_POST['id_historia_clinica'])){
    	        $arrayIndex = "nombre ='Parentesco'";
    	        $procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
    	        $_POST['id_procedimiento_medico'] = $procedi->current()->id_procedimiento_medico;
    	        $resultado = $this->lNegocioAntecedentesSaludFamiliar->guardar($_POST);
    	        if($resultado > 0){
    	            $contenido = $this->listarAntecedentesFamiliares($_POST['id_historia_clinica']);
    	            $mensaje = 'Registro agregado correctamente';
    	        }else {
    	            $estado = 'ERROR';
    	            $mensaje = 'Error al guardar los datos !!';
    	        }
    	        
        	    }else{
        	        $estado = 'ERROR';
        	        $mensaje = 'Debe crear la historia clínica !!';
        	    }
        }else{
            $estado = 'ERROR';
            $mensaje = 'Debe crear la historia clínica !!';
        }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * funcion para eliminar accidente en paciente
	 */
	public function eliminarAntecedentesFamiliares() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id_anteced_salud_familiar']) && isset($_POST['id_historia_clinica'])){
	        $this->lNegocioAntecedentesSaludFamiliar->borrar($_POST['id_anteced_salud_familiar']);
	        $contenido = $this->listarAntecedentesFamiliares($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	        
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	
	/**
	 * listar accdientes laborales
	 */
	public function listarAntecedentesFamiliares($idHistoriaClinica=null,$opt=1){
	    $datos=$html='';
	    if($idHistoriaClinica != null){
	    $consulta = $this->lNegocioAntecedentesSaludFamiliar->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	    if($consulta->count()){
	        foreach ($consulta as $item) {
	            $parentesco =	$this->lNegocioTipoProcedimientoMedico->buscar($item->id_tipo_procedimiento_medico);
	            $cie10 = $this->lNegocioCie->buscar($item->id_cie);
	            $datos .= '<tr>';
	            $datos .= '<td>'.$parentesco->getTipo().'</td>';
	            $datos .= '<td>'.$cie10->getDescripcion().'</td>';
	            $datos .= '<td>'.$item->observaciones.'</td>';
	            if($opt){
	               $datos .= '<td><button class="bEliminar icono" onclick="eliminarAntecedentesFamiliares('.$item->id_anteced_salud_familiar.'); return false; "></button></td>';
	            }else{
	               $datos .= '<td></td>';
	                
	            }
	            $datos .= '<tr>';
	        }
	        
	        $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Parentesco</th>
						<th>Enfermedad</th>
						<th>Observaciones</th>
						<th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>
           ';
	      }
	    }
	    return $html;
	}

	/**
	 * Buscar antecedentes de salud
	 */
	public function buscarAntecedentesSalud() {
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if($_POST['id_historia_clinica'] != null && $_POST['id_tipo_procedimiento_medico'] != null){
	        
	        $contenido = $this->crearHtmlAntecedentesSalud($_POST['tipo']);
	        
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * mostrar inputs de ingreso antecedentes de salud
	 */
	public function crearHtmlAntecedentesSalud($tipo) {
	    switch ($tipo) {
	        case 'Clínicos':
	        $txt = '
                <legend>'.$tipo.'</legend>
                <div data-linea="3">
        			<label for="enfermedad_general_salud">Enfermedad General: </label>
        			<select id="enfermedad_general_salud" name= "enfermedad_general_salud" >
        				'. $this->comboCie10('descripcion').'
        			</select>
        		</div>				
        
        		<div data-linea="3">
        			<label for="id_cie_salud">Código CIE 10:</label>
        			<select id="id_cie_salud" name= "id_cie_salud" >
        				'. $this->comboCie10('codigo').'
        			</select>
        		</div>		
        		
        		<div data-linea="4">
        			<label for="diagnostico_salud">Diagnóstico:</label>
        			<input type="text" id="diagnostico_salud" name="diagnostico_salud" value=""
        			placeholder="Diagnostico" maxlength="64" />
        		</div>		
        		<div data-linea="5">
        			<label for="observaciones_salud">Observaciones:</label>
        			<input type="text" id="observaciones_salud" name="observaciones_salud" value=""
        			placeholder="Observaciones" maxlength="256" />
        		</div>			
                <div data-linea="6">
                		<button class="mas" onclick="agregarAntecedentesSalud(); return false;">Agregar</button>
                		</div>';
	        break;
	        case 'Gineco Obstétricos':
	            $txt = '
                    <legend>'.$tipo.'</legend>
                    <div data-linea="1">
            			<label for="ciclo_mestrual">Ciclo mestrual:</label>
            			<select id="ciclo_mestrual" name= "ciclo_mestrual" >
        				'. $this->comboCicloMestrual().'
        			    </select>
            		</div>				
            
            		<div data-linea="1">
            			<label for="fecha_ultima_regla">Fecha de la útima regla: </label>
            			<input type="text" id="fecha_ultima_regla" name="fecha_ultima_regla" value=""
            			 maxlength="13" readonly/>
            		</div>				
            
            		<div data-linea="2">
            			<label for="fecha_ultima_citologia">Fecha de la última citología:</label>
            			<input type="text" id="fecha_ultima_citologia" name="fecha_ultima_citologia" value=""
            			 maxlength="13" readonly/>
            		</div>				
            
            		<div data-linea="2">
                        
            			<label for="nunca_fecha">Nunca: </label>
                        <input id="nunca_fecha" name="nunca_fecha" type="checkbox">
            		</div>				
            
            		<div data-linea="3">
            			<label for="resultado_citologia">Resultados citología: </label>
            			<input type="text" id="resultado_citologia" name="resultado_citologia" value=""
            			placeholder="Resultado de la citologia" maxlength="1024" />
            		</div>				
            
            		<div data-linea="4">
            			<label for="numero_gestaciones">N° Gestaciones:</label>
            			<select id="numero_gestaciones" name= "numero_gestaciones" >
        				'. $this->comboNumeros(10,0).'
        			    </select>
            		</div>				
            
            		<div data-linea="4">
            			<label for="numero_partos">N° Partos:</label>
            			<select id="numero_partos" name= "numero_partos" >
        				'. $this->comboNumeros(10,0).'
        			    </select>
            		</div>				
            
            		<div data-linea="4">
            			<label for="numero_cesareas">N° Cesáreas:</label>
            			<select id="numero_cesareas" name= "numero_cesareas" >
        				'. $this->comboNumeros(10,0).'
        			    </select>
            		</div>				
            
            		<div data-linea="5">
            			<label for="numero_abortos">N° Abortos:</label>
            			<select id="numero_abortos" name= "numero_abortos" >
        				'. $this->comboNumeros(10,0).'
        			    </select>
            		</div>				
            
            		<div data-linea="5">
            			<label for="numero_hijos_vivos">N° Hijos vivos: </label>
            			<select id="numero_hijos_vivos" name= "numero_hijos_vivos" >
        				'. $this->comboNumeros(10,0).'
        			    </select>
            		</div>				
            
            		<div data-linea="5">
            			<label for="numero_hijos_muertos">N° Hijos muertos: </label>
            			<select id="numero_hijos_muertos" name= "numero_hijos_muertos" >
        				'. $this->comboNumeros(10,0).'
        			    </select>
            		</div>				
            
            		<div data-linea="6">
            			<label for="embarazo">¿Embarazo?: </label>
            			<select id="embarazo" name= "embarazo" >
        				'. $this->comboOpcion().'
        			    </select>
            		</div>	
            		<div data-linea="6">
            			<label for="semanas_gestacion">Semanas de gestación: </label>
            			<select id="semanas_gestacion" name= "semanas_gestacion" disabled>
        				'. $this->comboNumeros(40,1).'
        			    </select>
            		</div>				
            
            		<div data-linea="6">
            			<label for="numero_ecos">N° Ecos: </label>
            			<select id="numero_ecos" name= "numero_ecos" disabled>
        				'. $this->comboNumeros(20,1).'
        			    </select>
            		</div>
            		<div data-linea="7">
            			<label for="numero_controles_embarazo">N° Controles embarazo: </label>
            			<select id="numero_controles_embarazo" name= "numero_controles_embarazo" disabled>
        				'. $this->comboNumeros(20,1).'
        			    </select>
            		</div>				
            
            		<div data-linea="8">
            			<label for="complicaciones">Complicaciones: </label>
            			<input type="text" id="complicaciones" name="complicaciones" value=""
            			placeholder="Complicaciones" maxlength="64" disabled/>
            		</div>
            		<div data-linea="9">
            			<label for="vida_sexual_activa">Vida sexual activa: </label>
            			<select id="vida_sexual_activa" name= "vida_sexual_activa" >
        				'. $this->comboOpcion().'
        			    </select>
            		</div>				
            
            		<div data-linea="9">
            			<label for="planificacion_familiar">Planificación familiar: </label>
            			<select id="planificacion_familiar" name= "planificacion_familiar" >
        				'. $this->comboOpcion().'
        			    </select>
            		</div>
            		<div data-linea="10">
            			<label for="metodo_planificacion">Método de planificación: </label>
            			<input type="text" id="metodo_planificacion" name="metodo_planificacion" value=""
            			placeholder="Método planificación" maxlength="32" disabled/>
            		</div>
                    <div data-linea="11">
                    		<button class="mas" onclick="agregarAntecedentesSalud(); return false;">Agregar</button>
                    		</div>
                ';
	        break;
	        
	        default:
	            $txt = '
                <legend>'.$tipo.'</legend>
        		<div data-linea="4">
        			<label for="diagnostico_salud">Diagnóstico:</label>
        			<input type="text" id="diagnostico_salud" name="diagnostico_salud" value=""
        			placeholder="Diagnostico" maxlength="64" />
        		</div>
        		<div data-linea="5">
        			<label for="observaciones_salud">Observaciones:</label>
        			<input type="text" id="observaciones_salud" name="observaciones_salud" value=""
        			placeholder="Observaciones" maxlength="256" />
        		</div>		
                <div data-linea="6">
                		<button class="mas" onclick="agregarAntecedentesSalud(); return false;">Agregar</button>
                		</div>	';
	        break;
	    }
	    return $txt;
	    
	}
	/**
	 * funcion para agregar accidente en paciente
	 */
	public function agregarAntecedentesSalud() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_historia_clinica'])){
	        if(!empty($_POST['id_historia_clinica'])){
    	        $arrayIndex = "nombre ='Antecedentes de salud'";
    	        $procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
    	        $_POST['id_procedimiento_medico'] = $procedi->current()->id_procedimiento_medico;
    	        $resultado = $this->lNegocioAntecedentesSalud->guardarAntecedentesDetalle($_POST);
    	        if($resultado){
    	            $contenido = $this->listarAntecedentesSalud($_POST['id_historia_clinica']);
    	            $mensaje = 'Registro agregado correctamente';
    	        }else {
    	            $estado = 'ERROR';
    	            $mensaje = 'Error al guardar los datos !!';
    	        }
    	    }else{
    	        $estado = 'ERROR';
    	        $mensaje = 'Debe crear la historia clínica !!';
    	    }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica !!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * funcion para eliminar antecedentes de salud
	 */
	public function eliminarAntecedentesSalud() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id_antecedentes_salud']) && isset($_POST['id_historia_clinica'])){
	        
	        $this->lNegocioDetalleAntecedentesSalud->borrarPorParametro("id_antecedentes_salud", $_POST['id_antecedentes_salud']);
	        $this->lNegocioAntecedentesSalud->borrar($_POST['id_antecedentes_salud']);
	        $contenido = $this->listarAntecedentesSalud($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	        
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	
	/**
	 * listar antecedentes de salud
	 */
	public function listarAntecedentesSalud($idHistoriaClinica=null,$opt=1){
	    $datos=$html='';
	    if($idHistoriaClinica != null){
	        $consulta = $this->lNegocioAntecedentesSalud->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	        if($consulta->count()){
	            foreach ($consulta as $item) {
	                $antecedente =	$this->lNegocioTipoProcedimientoMedico->buscar($item->id_tipo_procedimiento_medico);
	                $detalle = $this->lNegocioDetalleAntecedentesSalud->buscarLista("id_antecedentes_salud =".$item->id_antecedentes_salud);
	                if( $antecedente->getTipo() == 'Gineco Obstétricos'){
	                    $prev = '<button class="bPrevisualizar icono" onclick="informacionAntecedentesSalud('.$item->id_antecedentes_salud.'); return false; "></button>';
	                }else{
	                    $prev ='';
	                    }
	                $datos .= '<tr>';
	                $datos .= '<td>'.$antecedente->getTipo().'</td>';
	                $datos .= '<td>'.$detalle->current()->diagnostico.'</td>';
	                $datos .= '<td>'.$detalle->current()->observaciones.'</td>';
	                $datos .= '<td>'.$prev.'</td>';
	                if($opt){
	                    $datos .= '<td><button class="bEliminar icono" onclick="eliminarAntecedentesSalud('.$item->id_antecedentes_salud.'); return false; "></button></td>';
	                }else{
	                    $datos .= '<td></td>';
	                }
	                $datos .= '<tr>';
	            }
	            
	            $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Tipo de antecedente</th>
						<th>Diagnóstico</th>
						<th>Observaciones</th>
                        <th>Información completa</th>
						<th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>
           ';
	        }
	    }
	    return $html;
	}
	/**
	 * Muestra el modal de previsualizacion de detalle de antecedentes de salud
	 */
	public function informacionAntecedentesSalud(){
	    
	    $this->detalleAntecedentes = $this->listarDetalleAntecedentesSalud($_POST['id_antecedentes_salud']);
	    require APP . 'HistoriasClinicas/vistas/formularioDetalleAntecedentesSaludGineco.php';
	}
	
	/**
	 * listar detalle de antecedentes de salud
	 */
	public function listarDetalleAntecedentesSalud($idAntecedentesSalud=null){
	    $datos='';
	    if($idAntecedentesSalud != null){
	        $consulta = $this->lNegocioAntecedentesSalud->buscar($idAntecedentesSalud);
	        if($consulta){
	            $detalle = $this->lNegocioDetalleAntecedentesSalud->buscarLista("id_antecedentes_salud =".$consulta->getIdAntecedentesSalud());
	            foreach ($detalle as $item) {
    	                $datos .= '<tr>';
    	                $datos .= '<td>'.date('Y-m-d',strtotime($consulta->getFechaCreacion())).'</td>';
    	                $datos .= '<td>'.$item->ciclo_mestrual.'</td>';
    	                $datos .= '<td>'.$item->fecha_ultima_regla.'</td>';
    	                $datos .= '<td>'.$item->fecha_ultima_citologia.'</td>';
    	                $datos .= '<td>'.$item->resultado_citologia.'</td>';
    	                $datos .= '<td>'.$item->numero_gestaciones.'</td>';
    	                $datos .= '<td>'.$item->numero_partos.'</td>';
    	                $datos .= '<td>'.$item->numero_cesareas.'</td>';
    	                $datos .= '<td>'.$item->numero_abortos.'</td>';
    	                $datos .= '<td>'.$item->numero_hijos_vivos.'</td>';
    	                $datos .= '<td>'.$item->numero_hijos_muertos.'</td>';
    	                $datos .= '<td>'.$item->embarazo.'</td>';
    	                $datos .= '<td>'.$item->semanas_gestacion.'</td>';
    	                $datos .= '<td>'.$item->numero_ecos.'</td>';
    	                $datos .= '<td>'.$item->numero_controles_embarazo.'</td>';
    	                $datos .= '<td>'.$item->complicaciones.'</td>';
    	                $datos .= '<td>'.$item->vida_sexual_activa.'</td>';
    	                $datos .= '<td>'.$item->planificacion_familiar.'</td>';
    	                $datos .= '<td>'.$item->metodo_planificacion.'</td>';
    	                $datos .= '<tr>';
	            }
	        }
	    }
	    return $datos;
	}
	/**
	 * crear elementos de revisión por aparatos
	 */
	public function listarElementosPorAparatos($idHistoriaClinica=null){
	    
	    $procedi = $this->lNegocioProcedimientoMedico->buscarLista("nombre ='Revisión por aparatos' and estado ='Activo'");
	    $arrayIndex = "id_procedimiento_medico =".$procedi->current()->id_procedimiento_medico." and estado ='Activo' order by 1";
	    $tipo = $this->lNegocioTipoProcedimientoMedico->buscarLista($arrayIndex);
	    $datos='';
	    $i=0;
	    $arraySub = array();

	    foreach ($tipo as $item) {
	        $arraySub [$i] = [$item->id_tipo_procedimiento_medico,$item->tipo];
	        $subtipo = $this->lNegocioSubtipoProcedimientoMedico->buscarLista("id_tipo_procedimiento_medico = ".$item->id_tipo_procedimiento_medico." and estado ='Activo' order by 1");
	        foreach ($subtipo as $sub) {
	            $arraySub [$i][]= [$sub->id_subtipo_proced_medico,$sub->subtipo];
	        }
	        $arraySub [$i][]= [$item->id_tipo_procedimiento_medico,'Otros'];
	        $i++;
	    }
	    
//************************************************************************************************************
	   $total = $this->lNegocioHistoriaClinica->contarElementosArray($arraySub);
	   
	    $i=$j=$v=$z=0;
	    foreach ($arraySub as $item) {
	        if($i==0){
	            $datos .= '<tr>';
	        }
	        $datos .= '<th> '.$item[1].'</th>';
	        $i++; $z++;
	        if($i==3 || $z==$total){
	            if($z==$total){
	                for($a=0; $a <= ($total-$j*3); $a++){
	                    $datos .='<th></th>';
	                }
	            }
	            $datos .= '</tr>';
	            for($i=0,$x=$v ; $i<3;$x++, $i++){
	                if(isset($arraySub[$x])){
	                $max []= ($this->lNegocioHistoriaClinica->contarElementosArray($arraySub[$x])-2);
	                }
	            }
	            for($p=0; $p < max($max); $p++){
    	            $datos .= '<tr>';
    	            for($i=0,$x=$v ; $i<3;$x++, $i++){
    	                if($i < count($max)){
        	                if($p < $max[$i]){
            	                    if($arraySub[$x][$p+2][1] != 'Otros'){
            	                        $datos .= '<td><input name="revisionAparatos[]" type="checkbox" id="'.$arraySub[$x][0].'" value="'.$arraySub[$x][$p+2][0].'"> '.$arraySub[$x][$p+2][1].'</td>';
            	                    }else{
            	                        $datos .= '<td>'.$arraySub[$x][$p+2][1].'<br><input name="revisionAparatosTxt[]" type="text" id="'.$arraySub[$x][$p+2][0].'" maxlength="32"></td>';
            	                    }
        	                    }else{
        	                    $datos .= '<td></td>';
        	                }
    	                }else{
    	                    $datos .= '<td></td>';
    	                }
    	            }
    	            $datos .= '</tr>';
	            }
	            unset($max);
	            $j++;
	            $v=$v+3;
	            $i=0;
	            
	        }
	    }
	    $html = '
			<table  style="width: 100%;">
            <tbody id="bodyOrganos">'.$datos.'</tbody>
			</table>
         ';
	    return $html;
	}
	
	/**
	 * funcion para agregar revision organos sistemas
	 */
	public function agregarRevisionOrganos() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_historia_clinica']) && (isset($_POST['subtipoList']) || isset($_POST['subtipoTxt']))){
	        if(!empty($_POST['id_historia_clinica'])){
    	        $arrayIndex = "nombre ='Revisión por aparatos'";
    	        $procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
    	        $_POST['id_procedimiento_medico'] = $procedi->current()->id_procedimiento_medico;
    	        $resultado = $this->lNegocioRevisionOrganosSistemas->guardarOrganosSistemasDetalle($_POST);
    	        if($resultado){
    	            $contenido = $this->listarRevisionOrganos($_POST['id_historia_clinica']);
    	            $mensaje = 'Registro agregado correctamente';
    	        }else {
    	            $estado = 'ERROR';
    	            $mensaje = 'Error al guardar los datos !!';
    	        }
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'Debe crear la historia clínica !!';
	        }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica o seleccionar un campo!!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * funcion para eliminar revision organos
	 */
	public function eliminarRevisionOrganos() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id_revision_organos_sistemas']) && isset($_POST['id_historia_clinica'])){
	        
	        $this->lNegocioDetalleRevisionOrganosSistemas->borrarPorParametro("id_revision_organos_sistemas", $_POST['id_revision_organos_sistemas']);
	        $this->lNegocioRevisionOrganosSistemas->borrar($_POST['id_revision_organos_sistemas']);
	        $contenido = $this->listarRevisionOrganos($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	        
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * listar organos sistemas
	 */
	public function listarRevisionOrganos($idHistoriaClinica=null,$opt=1){
	    $datos=$html=$subt=$otros='';
	    if($idHistoriaClinica != null){
	        $consulta = $this->lNegocioRevisionOrganosSistemas->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	        if($consulta->count()){
	            foreach ($consulta as $item) {
	                $tipo =	$this->lNegocioTipoProcedimientoMedico->buscar($item->id_tipo_procedimiento_medico);
	                
	                $detalle = $this->lNegocioDetalleRevisionOrganosSistemas->buscarLista("id_revision_organos_sistemas =".$item->id_revision_organos_sistemas);
	                if($detalle->count()){
	                    $subt=' ';
	                    $ban = false;
	                    foreach ($detalle as $subItem) {
	                        if($ban){
	                            $subt .= ', ';
	                        }
	                        if($subItem->id_subtipo_proced_medico != null){
	                           $subtipo = $this->lNegocioSubtipoProcedimientoMedico->buscar($subItem->id_subtipo_proced_medico);
	                           $subt .= $subtipo->getSubtipo();
	                        }else{
	                            $otros=$subItem->otros;
	                        }
	                        $ban = true;
	                    }
	                }
	                
	                $datos .= '<tr>';
	                $datos .= '<td>'.$tipo->getTipo().'</td>';
	                $datos .= '<td>'.$subt.'</td>';
	                $datos .= '<td>'.$otros.'</td>';
	                if($opt){
	                    $datos .= '<td><button class="bEliminar icono" onclick="eliminarRevisionOrganos('.$item->id_revision_organos_sistemas.'); return false; "></button></td>';
	                }else{
	                    $datos .= '<td></td>';
	                }
	                $datos .= '<tr>';
	                $subt=$otros='';
	            }
	            
	            $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Tipo órgano/sistema</th>
						<th>Subtipo</th>
						<th>Otros</th>
						<th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>
           ';
	        }
	    }
	    return $html;
	}
	
	/**
	 * funcion para agregar Inmunizacion
	 */
	public function agregarInmunizacion() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_historia_clinica'])){
	        if(!empty($_POST['id_historia_clinica'])){
	            $arrayIndex = "nombre ='Inmunizaciones'";
	            $procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
	            $_POST['id_procedimiento_medico'] = $procedi->current()->id_procedimiento_medico;
	            $_POST['fecha_ultima_dosis'] =$_POST['fecha_ultima_dosis'].'-01';
	            $resultado = $this->lNegocioInmunizacion->guardar($_POST);
	            if($resultado){
	                $contenido = $this->listarInmunizacion($_POST['id_historia_clinica']);
	                $mensaje = 'Registro agregado correctamente';
	            }else {
	                $estado = 'ERROR';
	                $mensaje = 'Error al guardar los datos !!';
	            }
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'Debe crear la historia clínica !!';
	        }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica o seleccionar un campo!!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * funcion para eliminar Inmunizacion
	 */
	public function eliminarInmunizacion() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id_inmunizacion']) && isset($_POST['id_historia_clinica'])){
	        
	        $this->lNegocioInmunizacion->borrar($_POST['id_inmunizacion']);
	        $contenido = $this->listarInmunizacion($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	        
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * listar Inmunizacion
	 */
	public function listarInmunizacion($idHistoriaClinica=null,$opt=1){
	    $datos=$html='';
	    if($idHistoriaClinica != null){
	        $contador=0;
	        $consulta = $this->lNegocioInmunizacion->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	        if($consulta->count()){
	            foreach ($consulta as $item) {
	                $tipo =	$this->lNegocioTipoProcedimientoMedico->buscar($item->id_tipo_procedimiento_medico);
	                $datos .= '<tr>';
	                $datos .= '<td>'.++$contador.'</td>';
	                $datos .= '<td>'.$tipo->getTipo().'</td>';
	                $datos .= '<td>'.date('Y-m',strtotime($item->fecha_ultima_dosis)).'</td>';
	                $datos .= '<td>'.$item->numero_dosis.'</td>';
	                if($opt){
	                    $datos .= '<td><button class="bEliminar icono" onclick="eliminarInmunizacion('.$item->id_inmunizacion.'); return false; "></button></td>';
	                }else{
	                    $datos .= '<td></td>';
	                }
	                $datos .= '<tr>';
	            }
	            
	            $html = '
				<table style="width:100%">
					<thead><tr>
						<th>#</th>
						<th>Vacuna</th>
						<th>FUD</th>
						<th>N° dosis</th>
                        <th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>
           ';
	        }
	    }
	    return $html;
	}
	/**
	 * Buscar habitos
	 */
	public function buscarHabitos() {
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if($_POST['id_historia_clinica'] != null && $_POST['id_tipo_procedimiento_medico'] != null){
	        
	        $verificar = $this->lNegocioHabitos->buscarLista("id_historia_clinica=".$_POST['id_historia_clinica']."and id_tipo_procedimiento_medico=".$_POST['id_tipo_procedimiento_medico']);
	        if($verificar->count()){
	            $estado = 'ERROR';
	            $mensaje = 'Ya existe el tipo habito seleccionado !!';
	        }else{
	           $contenido = $this->crearHtmlHabitos($_POST['tipo']);
	        }
	        
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * mostrar inputs de ingreso de habitos
	 */
	public function crearHtmlHabitos($tipo) {
	    switch ($tipo) {
	        case 'Alcohol':
	            $txt = '
                <legend>'.$tipo.'</legend>
                <div data-linea="1">
        			<label for="habito" class="habitoRadio">Consumidor actual: </label>
        			<input type="radio" class="habitoRadio" name="habito[]" value="Consumidor actual" />
        		</div>
        				    
        		<div data-linea="1">
        			<label for="habito" class="habitoRadio">Exconsumidor:</label>
        			<input type="radio" class="habitoRadio" name="habito[]" value="Exconsumidor" />
        		</div>
        				    
        		<div data-linea="1">
        			<label for="habito" class="habitoRadio">No Consumidor:</label>
        			<input type="radio" class="habitoRadio" name="habito[]" value="No Consumidor" />
        		</div>
        		<div data-linea="2">
        			<label for="frecuencia_habito">Frecuencia de consumo:</label>
        			<select id="frecuencia_habito" name= "frecuencia_habito" disabled>
        				'. $this->comboAlcohol().'
        			    </select>
        		</div>
                <div data-linea="2">
        			<label for="anios_habito">Años de consumo:</label>
        			<select id="anios_habito" name= "anios_habito" disabled>
        				'. $this->comboNumeros(50,1).'
        			    </select>
        		</div>
                <div data-linea="3">
        			<label for="observaciones_habito">Observaciones:</label>
        			<input type="text" id="observaciones_habito" name="observaciones_habito" value=""
            	    placeholder="Observaciones" maxlength="128" disabled/>
        		</div>
                <div data-linea="6">
                		<button class="mas" onclick="agregarHabitos(); return false;">Agregar</button>
                		</div>';
	            break;
	        case 'Cigarrillo/Tabaco/Pipa':
	            $txt = '
                <legend>'.$tipo.'</legend>
                <div data-linea="1">
        			<label for="habito" class="habitoRadio">Fumador actual: </label>
        			<input type="radio" class="habitoRadio" name="habito[]" value="Fumador actual" />
        		</div>
                    
        		<div data-linea="1">
        			<label for="habito" class="habitoRadio">Exfumador:</label>
        			<input type="radio" class="habitoRadio" name="habito[]" value="Exfumador" />
        		</div>
                    
        		<div data-linea="1">
        			<label for="habito" class="habitoRadio">No Fumador:</label>
        			<input type="radio" class="habitoRadio" name="habito[]" value="No Fumador" />
        		</div>
                <div data-linea="1">
        			<label for="habito" class="habitoRadio">Otros:</label>
        			<input type="radio" class="habitoRadio" name="habito[]" value="Otros" />
        		</div>
        		<div data-linea="2">
        			<label for="frecuencia_habito">Consumo/día:</label>
        			<select id="frecuencia_habito" name= "frecuencia_habito" disabled>
        				'. $this->comboTabaco().'
        			    </select>
        		</div>
                <div data-linea="3">
        			<label for="observaciones_habito">Observaciones:</label>
        			<input type="text" id="observaciones_habito" name="observaciones_habito" value=""
            	    placeholder="Observaciones" maxlength="128" />
        		</div>
                <div data-linea="6">
                		<button class="mas" onclick="agregarHabitos(); return false;">Agregar</button>
                		</div>';
	            break;
	        case 'Otras Sustancias Psicoactivas':
	            $txt = '
                <legend>'.$tipo.'</legend>
                <div data-linea="1">
        			<label for="sustancias">Cuál: </label>
        			<input type="text" id="sustancias" name="sustancias" value=""
            	    placeholder="Sustancia" maxlength="64" />
        		</div>
        		<div data-linea="2">
        			<label for="frecuencia_habito">Frecuencia:</label>
        			<select id="frecuencia_habito" name="frecuencia_habito" disabled>
        				'. $this->comboFrecuencia().'
        			    </select>
        		</div>
                <div data-linea="2">
        			<label for="anios_habito">Años de consumo:</label>
        			<select id="anios_habito" name= "anios_habito" disabled>
        				'. $this->comboNumeros(50,1).'
        			    </select>
        		</div>
                <div data-linea="3">
        			<label for="observaciones_habito">Observaciones:</label>
        			<input type="text" id="observaciones_habito" name="observaciones_habito" value=""
            	    placeholder="Observaciones" maxlength="128" />
        		</div>
                <div data-linea="6">
                		<button class="mas" onclick="agregarHabitos(); return false;">Agregar</button>
                		</div>';
	            break;
	        default:
	            $txt='';
	            break;
	    }
	    return $txt;
	    
	}
	
	/**
	 * funcion para agregar habitos
	 */
	public function agregarHabitos() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $habito ='';
	    if(isset($_POST['id_historia_clinica'])){
	        if(!empty($_POST['id_historia_clinica'])){
	            $arrayIndex = "nombre ='Frecuencia de drogas'";
	            $procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
	            $_POST['id_procedimiento_medico'] = $procedi->current()->id_procedimiento_medico;
	            if(isset($_POST['habito'] )){
    	            foreach ($_POST['habito'] as $value) {
    	                $habito = $value;
    	            }
    	            $_POST['habito'] = $habito;
	            }
	            $resultado = $this->lNegocioHabitos->guardar($_POST);
	            if($resultado){
	                $contenido = $this->listarHabitos($_POST['id_historia_clinica']);
	                $mensaje = 'Registro agregado correctamente';
	            }else {
	                $estado = 'ERROR';
	                $mensaje = 'Error al guardar los datos !!';
	            }
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'Debe crear la historia clínica !!';
	        }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica o seleccionar un campo!!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * funcion para eliminar Inmunizacion
	 */
	public function eliminarHabitos() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id_habitos']) && isset($_POST['id_historia_clinica'])){
	        
	        $this->lNegocioHabitos->borrar($_POST['id_habitos']);
	        $contenido = $this->listarHabitos($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	        
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * listar habitos
	 */
	public function listarHabitos($idHistoriaClinica=null,$opt=1){
	    $datos=$html='';
	    if($idHistoriaClinica != null){
	        $contador=0;
	        $consulta = $this->lNegocioHabitos->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	        if($consulta->count()){
	            foreach ($consulta as $item) {
	                $tipo =	$this->lNegocioTipoProcedimientoMedico->buscar($item->id_tipo_procedimiento_medico);
	                $datos .= '<tr>';
	                $datos .= '<td>'.++$contador.'</td>';
	                $datos .= '<td>'.$tipo->getTipo().'</td>';
	                $datos .= '<td>'.$item->frecuencia_habito.'</td>';
	                $datos .= '<td>'.$item->anios_habito.'</td>';
	                $datos .= '<td>'.$item->observaciones.'</td>';
	                if($opt){
	                    $datos .= '<td><button class="bEliminar icono" onclick="eliminarHabitos('.$item->id_habitos.'); return false; "></button></td>';
	                }else{
	                    $datos .= '<td></td>';
	                }
	                $datos .= '<tr>';
	            }
	            
	            $html = '
				<table style="width:100%">
					<thead><tr>
						<th>#</th>
						<th>Tipo</th>
						<th>Frecuencia consumo</th>
						<th>Años consumo</th>
                        <th>Observaciones</th>
                        <th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>
           ';
	        }
	    }
	    return $html;
	}
	
	/**
	 * funcion para agregar actividad
	 */
	public function agregarActividad() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_historia_clinica'])){
	        if(!empty($_POST['id_historia_clinica'])){
	            $resultado = $this->lNegocioEstiloVida->guardar($_POST);
	            if($resultado){
	                $contenido = $this->listarActividad($_POST['id_historia_clinica']);
	                $mensaje = 'Registro agregado correctamente';
	            }else {
	                $estado = 'ERROR';
	                $mensaje = 'Error al guardar los datos !!';
	            }
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'Debe crear la historia clínica !!';
	        }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica o seleccionar un campo!!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * funcion para eliminar actividad
	 */
	public function eliminarActividad() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id_estilo_vida']) && isset($_POST['id_historia_clinica'])){
	        
	        $this->lNegocioEstiloVida->borrar($_POST['id_estilo_vida']);
	        $contenido = $this->listarActividad($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	        
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * listar actividad 
	 */
	public function listarActividad($idHistoriaClinica=null,$opt=1){
	    $datos=$html='';
	    if($idHistoriaClinica != null){
	        $contador=0;
	        $consulta = $this->lNegocioEstiloVida->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	        if($consulta->count()){
	            foreach ($consulta as $item) {
	                $datos .= '<tr>';
	                $datos .= '<td>'.++$contador.'</td>';
	                $datos .= '<td>'.$item->tipo_actividad.'</td>';
	                $datos .= '<td>'.$item->frecuencia.'</td>';
	                $datos .= '<td>'.$item->observaciones.'</td>';
	                if($opt){
	                    $datos .= '<td><button class="bEliminar icono" onclick="eliminarActividad('.$item->id_estilo_vida.'); return false; "></button></td>';
	                }else{
	                    $datos .= '<td></td>';
	                }
	                $datos .= '<tr>';
	            }
	            
	            $html = '
				<table style="width:100%">
					<thead><tr>
						<th>#</th>
						<th>Tipo</th>
						<th>Frecuencia</th>
                        <th>Observaciones</th>
                        <th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>
           ';
	        }
	    }
	    return $html;
	}
	/**
	 * listar evaluacion primaria
	 */
	public function listarEvaluacion($idHistoriaClinica=null){
	    $procedi = $this->lNegocioProcedimientoMedico->buscarLista("nombre ='Examen físico' and estado ='Activo'");
	    $arrayIndex = "id_procedimiento_medico =".$procedi->current()->id_procedimiento_medico." and estado ='Activo' order by 1";
	    $tipo = $this->lNegocioTipoProcedimientoMedico->buscarLista($arrayIndex);
	    $datos='';
	    
	    
	    foreach ($tipo as $item) {
	        $datos .= '<tr>';
	        $datos .= '<th colspan="4">'.$item->tipo.'</th>';
	        $datos .= '</tr>';
	        $datos .= '<tr>';
	        $datos .= '<td> </td>';
	        $datos .= '<td colspan="2"> Normal</td>';
	        $datos .= '<td> Observaciones</td>';
	        $datos .= '</tr>';
	        if(isset($idHistoriaClinica)){
	        $evaluacionPrima = $this->lNegocioEvaluacionPrimaria->buscarLista("id_historia_clinica = ".$idHistoriaClinica." and id_tipo_procedimiento_medico=".$item->id_tipo_procedimiento_medico);
	        }
	        $subtipo = $this->lNegocioSubtipoProcedimientoMedico->buscarLista("id_tipo_procedimiento_medico = ".$item->id_tipo_procedimiento_medico. " and estado ='Activo' order by 1");
	        foreach ($subtipo as $sub) {
	            $ban=1;
	            $datos .= '<tr>';
	            $datos .= '<td> '.$sub->subtipo.'</td>';
	            if(isset($idHistoriaClinica)){
	                if($evaluacionPrima->count()){
	                    $detalleEva = $this->lNegocioDetalleEvaluacionPrimaria->buscarLista("id_evaluacion_primaria=".$evaluacionPrima->current()->id_evaluacion_primaria." and id_subtipo_proced_medico=".$sub->id_subtipo_proced_medico);
	                    if($detalleEva->count()){
	                        $ban=0;
	                        if($detalleEva->current()->normal == 'Si'){
	                            $datos .= '<td> <input checked name="evaluacionPrimaria[]" type="checkbox" id="Si-'.$item->id_tipo_procedimiento_medico.'-'.$sub->id_subtipo_proced_medico.'" value="Si" onclick="verificarEvaPrimaria(id,'.$item->id_tipo_procedimiento_medico.','.$sub->id_subtipo_proced_medico.');  "> Si</td>';
	                            $datos .= '<td> <input  name="evaluacionPrimaria[]" type="checkbox" id="No-'.$item->id_tipo_procedimiento_medico.'-'.$sub->id_subtipo_proced_medico.'" value="No" onclick="verificarEvaPrimaria(id,'.$item->id_tipo_procedimiento_medico.','.$sub->id_subtipo_proced_medico.');  "> No</td>';
	                            $datos .= '<td> <input name="evaluacionPrimariatxt[]" type="text" id="'.$item->id_tipo_procedimiento_medico.'-'.$sub->id_subtipo_proced_medico.'" value="'.$detalleEva->current()->observaciones.'"> </td>';
	                        }else{
	                            $datos .= '<td> <input name="evaluacionPrimaria[]" type="checkbox" id="Si-'.$item->id_tipo_procedimiento_medico.'-'.$sub->id_subtipo_proced_medico.'" value="Si" onclick="verificarEvaPrimaria(id,'.$item->id_tipo_procedimiento_medico.','.$sub->id_subtipo_proced_medico.');  "> Si</td>';
	                            $datos .= '<td> <input checked name="evaluacionPrimaria[]" type="checkbox" id="No-'.$item->id_tipo_procedimiento_medico.'-'.$sub->id_subtipo_proced_medico.'" value="No" onclick="verificarEvaPrimaria(id,'.$item->id_tipo_procedimiento_medico.','.$sub->id_subtipo_proced_medico.');  "> No</td>';
	                            $datos .= '<td> <input name="evaluacionPrimariatxt[]" type="text" id="'.$item->id_tipo_procedimiento_medico.'-'.$sub->id_subtipo_proced_medico.'" value="'.$detalleEva->current()->observaciones.'"> </td>';
	                            
	                        }
	                    }
	                }
	            }
	            if($ban){
	            $datos .= '<td> <input name="evaluacionPrimaria[]" type="checkbox" id="Si-'.$item->id_tipo_procedimiento_medico.'-'.$sub->id_subtipo_proced_medico.'" value="Si" onclick="verificarEvaPrimaria(id,'.$item->id_tipo_procedimiento_medico.','.$sub->id_subtipo_proced_medico.');  "> Si</td>';
	            $datos .= '<td> <input name="evaluacionPrimaria[]" type="checkbox" id="No-'.$item->id_tipo_procedimiento_medico.'-'.$sub->id_subtipo_proced_medico.'" value="No" onclick="verificarEvaPrimaria(id,'.$item->id_tipo_procedimiento_medico.','.$sub->id_subtipo_proced_medico.');  "> No</td>';
	            $datos .= '<td> <input name="evaluacionPrimariatxt[]" type="text" id="'.$item->id_tipo_procedimiento_medico.'-'.$sub->id_subtipo_proced_medico.'" value=""> </td>';
	            $datos .= '</tr>';
	            }
	        }
	    }
	    
	    $html = '
			<table  style="width: 100%;">
            <tbody id="bodyEvaluacion">'.$datos.'</tbody>
			</table>
         ';
	    return $html;
	}
	/**
	 * Buscar examenes clinicos
	 */
	public function buscarExamenesClinicos() {
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if($_POST['id_historia_clinica'] != null && $_POST['id_tipo_procedimiento_medico'] != null){
	        $contenido = $this->crearHtmlExamenesClinicos($_POST);
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * mostrar inputs de ingreso examenes clinicos
	 */
	public function crearHtmlExamenesClinicos(array $datos) {
	              
	           $txt='';
	           $html = '
                <legend>'.$datos['tipo'].'</legend>
        		<div data-linea="1">
        			<label for="fecha_examen">Fecha de Exámenes:</label>
        			<input type="text" id="fecha_examen" name="fecha_examen" value=""
        			placeholder="Fecha de examen" maxlength="13" readonly/>
        		</div>';
	            $tipo = $this->lNegocioSubtipoProcedimientoMedico->buscarLista("id_tipo_procedimiento_medico = ".$datos['id_tipo_procedimiento_medico']." and estado = 'Activo' order by 1");
	            
	            if($tipo->count()){
	                foreach ($tipo as $value) {
	                    $txt .='<tr>';
	                    $txt .='<td>'.$value->subtipo.'</td>';
	                    $txt .='<td><input type="checkbox" id="'.$datos['id_tipo_procedimiento_medico'].'-'.$value->id_subtipo_proced_medico.'" name="estado_clinico_Check[]"  onclick="verificarExaClinicos(id);" /></td>';
	                    $txt .='<td><select id="s-'.$datos['id_tipo_procedimiento_medico'].'-'.$value->id_subtipo_proced_medico.'" name= "estado_clinico[]" disabled>
	                            		'. $this->comboEstadoClinico().'
	                            	</select></td>';
	                    $txt .='<td>Observaciones:</td>';
                        $txt .='<td><input type="text" id="t-'.$datos['id_tipo_procedimiento_medico'].'-'.$value->id_subtipo_proced_medico.'" name="observaciones_examen_clinico[]" value=""
        			            placeholder="Observaciones" maxlength="128" disabled/></td>';
	                    $txt .='</tr>';
	                }
	            }
	            $html .= '
        			<table  style="width: 100%;">
                    <tbody id="bodyExamenesClinicos">'.$txt.'</tbody>
        			</table>
                    <div data-linea="3">
                		<button class="mas" onclick="agregarExamenesClinicos(); return false;">Agregar</button>
                		</div>	';
	            return $html;
	}
	/**
	 * funcion para agregar accidente en paciente
	 */
	public function agregarExamenesClinicos() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_historia_clinica']) && isset($_POST['id_tipo_procedimiento_medico'])){
	        if(!empty($_POST['id_historia_clinica'])){
 	            $arrayIndex = "nombre ='Laboratorio'";
 	            $procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
 	            $_POST['id_procedimiento_medico'] = $procedi->current()->id_procedimiento_medico;
 	            $resultado = $this->lNegocioExamenesClinicos->guardarExamenesDetalle($_POST);
 	            if($resultado){
 	                $contenido = $this->listarExamenesClinicos($_POST['id_historia_clinica']);
 	                $mensaje = 'Registro agregado correctamente';
 	            }else {
 	                $estado = 'ERROR';
 	                $mensaje = 'Error al guardar los datos !!';
 	            }
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'Debe crear la historia clínica !!';
	        }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica !!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * funcion para eliminar antecedentes de salud
	 */
	public function eliminarExamenesClinicos() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_detalle_examenes_clinicos']) && isset($_POST['id_historia_clinica'])){
	        
	        $verificar = $this->lNegocioDetalleExamenesClinicos->buscar($_POST['id_detalle_examenes_clinicos']);
	        $contador = $this->lNegocioDetalleExamenesClinicos->buscarLista("id_examenes_clinicos = ".$verificar->getIdExamenesClinicos());
	        if($contador->count() == 1){
	            $this->lNegocioDetalleExamenesClinicos->borrarPorParametro("id_examenes_clinicos", $verificar->getIdExamenesClinicos());
	            $this->lNegocioExamenesClinicos->borrar($verificar->getIdExamenesClinicos());
	        }else{
	            $this->lNegocioDetalleExamenesClinicos->borrar($_POST['id_detalle_examenes_clinicos']);
	        }
	        $contenido = $this->listarExamenesClinicos($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	
	/**
	 * listar antecedentes de salud
	 */
	public function listarExamenesClinicos($idHistoriaClinica=null,$opt=1){
	    $datos=$html='';
	    if($idHistoriaClinica != null){
	        $consulta = $this->lNegocioExamenesClinicos->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	        if($consulta->count()){
	            foreach ($consulta as $item) {
	                $tipo =	$this->lNegocioTipoProcedimientoMedico->buscar($item->id_tipo_procedimiento_medico);
	                $detalle = $this->lNegocioDetalleExamenesClinicos->buscarLista("id_examenes_clinicos =".$item->id_examenes_clinicos);
	                if($detalle->count()){
	                    foreach ($detalle as $detall) {
	                        $subtipo = $this->lNegocioSubtipoProcedimientoMedico->buscar($detall->id_subtipo_proced_medico);
        	                $datos .= '<tr>';
        	                $datos .= '<td>'.$tipo->getTipo().'</td>';
        	                $datos .= '<td>'.$subtipo->getSubtipo().'</td>';
        	                $datos .= '<td>'.$item->fecha_examen.'</td>';
        	                $datos .= '<td>'.$detall->estado_clinico.'</td>';
        	                $datos .= '<td>'.$detall->observaciones.'</td>';
        	                if($opt){
        	                    $datos .= '<td><button class="bEliminar icono" onclick="eliminarExamenesClinicos('.$detall->id_detalle_examenes_clinicos.'); return false; "></button></td>';
        	                }else{
        	                    $datos .= '<td></td>';
        	                }
        	                $datos .= '<tr>';
	                }
	            }
	            
	            $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Tipo</th>
						<th>Examen</th>
						<th>Fecha</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
						<th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	           }
	        }
	      }
	    return $html;
	}

	/**
	 * guardar archivoadjunto	 
	 * 
	 * */
	public function agregarDocumentosAdjuntos()
	{
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(!empty($_REQUEST['id_historia_clinica']) && $_REQUEST['id_historia_clinica'] != 'null'){
    	    try {
    	        
    	        $identificador = $this->lNegocioHistoriaClinica->buscar($_REQUEST['id_historia_clinica']);
    	        $nombre_archivo = $_FILES['archivo']['name'];
    	        $tipo_archivo = $_FILES['archivo']['type'];
    	        $tamano_archivo = $_FILES['archivo']['size'];
    	        $tmpArchivo = $_FILES['archivo']['tmp_name'];
    	        $rutaCarpeta = HIST_CLI_URL."adjuntosHistoriaClinica/".$identificador->getIdentificadorPaciente();
    	        $extension = explode(".", $nombre_archivo);
    	        if ($tamano_archivo != '0' ) {
    	            if (strtoupper(end($extension)) == 'PDF' && $tipo_archivo == 'application/pdf') {
    	                if (!file_exists('../../' . $rutaCarpeta)) {
    	                    mkdir('../../' .$rutaCarpeta, 0777, true);
    	                }
    	                $secuencial = date('Ymds').mt_rand(100,999);
    	                $nuevo_nombre = 'examenes_clinicos_'.$identificador->getIdentificadorPaciente().'_'.$secuencial.'.' . end($extension);
    	                $ruta = $rutaCarpeta . '/' . $nuevo_nombre;
    	                move_uploaded_file($tmpArchivo, '../../' . $ruta);
    	                $arrayIndex = "nombre ='Laboratorio'";
    	                $procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
    	                $arrayAdjunto = array(
    	                    'id_historia_clinica' =>$_REQUEST['id_historia_clinica'],
    	                    'id_procedimiento_medico' => $procedi->current()->id_procedimiento_medico,
    	                    'archivo_adjunto' => $ruta,
    	                    'descripcion_adjunto' => $_REQUEST['descripcion_adjunto']
    	                );
    	                $id = $this->lNegocioAdjuntosHistoriaClinica->guardar($arrayAdjunto);
    	                if($id){
    	                    $mensaje = 'Registro agregado correctamente';
    	                    $contenido = $this->listarAdjuntosHistoria($_REQUEST['id_historia_clinica']);;
    	                }else{
    	                    $estado = 'FALLO';
    	                    $mensaje = 'Error al guardar el registro..!!';
    	                    $contenido = $ruta;
    	                }
    	            } else {
    	                $estado = 'FALLO';
    	                $mensaje ='No se cargó archivo. Extención incorrecta';
    	            }
    	            
    	        }else{
    	            $estado = 'FALLO';
    	            $mensaje = 'El archivo supera el tamaño permitido';
    	        }
    	    } catch (\Exception $ex) {
    	        $estado = 'FALLO';
    	        $mensaje= 'No se cargó archivo';
    	    }
	    }else{
	        $estado = 'FALLO';
	        $mensaje = 'Debe crear la historia clínica !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	
	/**
	 * listar archivos adjuntos
	 */
	public function listarAdjuntosHistoria($idHistoriaClinica=null){
	    $html='';
	    if($idHistoriaClinica != null){
	        $consulta = $this->lNegocioAdjuntosHistoriaClinica->buscarLista("id_historia_clinica =".$idHistoriaClinica." and estado='Activo' and id_procedimiento_medico is not null order by 1 ");
	        if($consulta->count()){
	            $count=0;
	            foreach ($consulta as $item) {
	                $html .= '
                    <div data-linea = "'.++$count.'">
	                <label>'.$item->descripcion_adjunto.': </label>
	                <a href="'.$item->archivo_adjunto.'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>
		            </div><br>';
	            }
	        }
	    }
	    return $html;
	    

	}
	
  //**********************examenes paraclinicos*************************
  
	/**
	 * Buscar examenes paraclinicos
	 */
	public function buscarParaclinicos() {
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if($_POST['id_historia_clinica'] != null && $_POST['id_tipo_procedimiento_medico'] != null){
	        $verificar = $this->lNegocioExamenParaclinicos->buscarLista("id_historia_clinica =".$_POST['id_historia_clinica']." and id_tipo_procedimiento_medico=".$_POST['id_tipo_procedimiento_medico']);
	        if($verificar->count() == 0){
	            $contenido = $this->crearHtmlParaclinicos($_POST);
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'Tipo de examen ya registrado !!';
	        }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * mostrar inputs de ingreso examenes paraclinicos
	 */
	public function crearHtmlParaclinicos(array $datos) {
	    
	    $html = '<legend>'.$datos['tipo'].'</legend>';
	    $menu ='Resultado de la imagen';
	        switch ($datos['tipo']) {
	            case 'Audiometría':
	                $menu ='Resultado';
	                $tipo = $this->lNegocioSubtipoProcedimientoMedico->buscarLista("id_tipo_procedimiento_medico = ".$datos['id_tipo_procedimiento_medico']." and estado = 'Activo' order by 1");
	                $txt='';
	                if($tipo->count()){
	                    $txt .='<tr><th colspan="'.$tipo->count().'">'.$menu.'</th></tr>';
	                    $txt .='<tr><td></td><td>Si</td><td>No</td><td>Derecho</td><td>Izquierdo</td><td>Bilateral</td></tr>';
	                    $txt .='<tr>';
	                    foreach ($tipo as $value) {
	                        $txt .='<tr>';
	                        $txt .='<td>'.$value->subtipo.'</td>';
	                        $txt .='<td align="center"><input type="checkbox" id="s-'.$datos['id_tipo_procedimiento_medico'].'-'.$value->id_subtipo_proced_medico.'" name="respuesta_check[]"  value="Si" onclick="verificarRespuestaParacli(id,'.$datos['id_tipo_procedimiento_medico'].','.$value->id_subtipo_proced_medico.');"   /> </td>';
	                        $txt .='<td align="center"><input type="checkbox" id="n-'.$datos['id_tipo_procedimiento_medico'].'-'.$value->id_subtipo_proced_medico.'" name="respuesta_check[]"  value="No" onclick="verificarRespuestaParacli(id,'.$datos['id_tipo_procedimiento_medico'].','.$value->id_subtipo_proced_medico.');"   /> </td>';
	                        $txt .='<td align="center"><input type="checkbox" id="d-'.$datos['id_tipo_procedimiento_medico'].'-'.$value->id_subtipo_proced_medico.'" name="oido_check[]"  value="Derecho" onclick="verificarOidoParaclinicos(id,'.$datos['id_tipo_procedimiento_medico'].','.$value->id_subtipo_proced_medico.');"   /> </td>';
	                        $txt .='<td align="center"><input type="checkbox" id="i-'.$datos['id_tipo_procedimiento_medico'].'-'.$value->id_subtipo_proced_medico.'" name="oido_check[]"  value="Izquierdo" onclick="verificarOidoParaclinicos(id,'.$datos['id_tipo_procedimiento_medico'].','.$value->id_subtipo_proced_medico.');"   /> </td>';
	                        $txt .='<td align="center"><input type="checkbox" id="b-'.$datos['id_tipo_procedimiento_medico'].'-'.$value->id_subtipo_proced_medico.'" name="oido_check[]"  value="Bilateral" onclick="verificarOidoParaclinicos(id,'.$datos['id_tipo_procedimiento_medico'].','.$value->id_subtipo_proced_medico.');"   /> </td>';
	                        $txt .='</tr>';
	                    }
	                    
	                    $html .= '
                			<table  style="width: 100%;">
                            <tbody id="bodyParaclinicos">'.$txt.'</tbody>
                			</table>
                            <div data-linea="3">
                    			<label for="observaciones_paraclinicos">Observaciones:</label>
                    			<input type="text" id="observaciones_paraclinicos" name="observaciones_paraclinicos" value=""
                    			placeholder="Observaciones" maxlength="128" />
                    		</div>
                            <div data-linea="4">
                        		<button class="mas" onclick="agregarParaclinicos(); return false;">Agregar</button>
                        		</div>	';
	                }
	                break;
	            case 'Espirometría':
	                $menu ='Resultado';
	            default:
	                $tipo = $this->lNegocioSubtipoProcedimientoMedico->buscarLista("id_tipo_procedimiento_medico = ".$datos['id_tipo_procedimiento_medico']." and estado = 'Activo' order by 1");
	                $txt='';
	                if($tipo->count()){
	                    $txt .='<tr><th colspan="'.$tipo->count().'">'.$menu.'</th></tr>';
	                    $txt .='<tr>';
	                    foreach ($tipo as $value) {
	                        $txt .='<td align="center"><input type="radio" id="r-'.$datos['id_tipo_procedimiento_medico'].'-'.$value->id_subtipo_proced_medico.'" name="respuesta_check[]" value="'.$value->subtipo.'"  /> '.$value->subtipo.'</td>';
	                    }
	                    $txt .='</tr>';
	                    $html .= '
                			<table  style="width: 100%;">
                            <tbody id="bodyParaclinicos">'.$txt.'</tbody>
                			</table>
                            <div data-linea="3">
                    			<label for="observaciones_paraclinicos">Observaciones:</label>
                    			<input type="text" id="observaciones_paraclinicos" name="observaciones_paraclinicos" value=""
                    			placeholder="Observaciones" maxlength="128" />
                    		</div>
                            <div data-linea="4">
                        		<button class="mas" onclick="agregarParaclinicos(); return false;">Agregar</button>
                        		</div>	';
	                }
	                break;
	        }
	        
	    return $html;
	}
	/**
	 * funcion para agregar examenes paraclinicos
	 */
	public function agregarParaclinicos() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_historia_clinica']) && isset($_POST['id_tipo_procedimiento_medico'])){
	        if(!empty($_POST['id_historia_clinica'])){
	            $arrayIndex = "nombre ='Examen de gabinete'";
	            $procedi = $this->lNegocioProcedimientoMedico->buscarLista($arrayIndex);
	            $_POST['id_procedimiento_medico'] = $procedi->current()->id_procedimiento_medico;
	            $resultado = $this->lNegocioExamenParaclinicos->guardarParaclinicosDetalle($_POST);
	            if($resultado){
	                $contenido = $this->listarParaclinicos($_POST['id_historia_clinica']);
	                $mensaje = 'Registro agregado correctamente';
	            }else {
	                $estado = 'ERROR';
	                $mensaje = 'Error al guardar los datos !!';
	            }
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'Debe crear la historia clínica !!';
	        }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica !!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * funcion para eliminar examenes paraclinicos
	 */
	public function eliminarParaclinicos() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_examen_paraclinicos']) && isset($_POST['id_historia_clinica'])){
	        $this->lNegocioDetalleExamenParaclinicos->borrarPorParametro("id_examen_paraclinicos", $_POST['id_examen_paraclinicos']);
	        $this->lNegocioExamenParaclinicos->borrar($_POST['id_examen_paraclinicos']);
	        $contenido = $this->listarParaclinicos($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	
	/**
	 * listar examenes paraclinicos
	 */
	public function listarParaclinicos($idHistoriaClinica=null,$opt=1){
	    $datos=$html='';
	    if($idHistoriaClinica != null){
	        $consulta = $this->lNegocioExamenParaclinicos->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	        if($consulta->count()){
	            foreach ($consulta as $item) {
	                $tipo =	$this->lNegocioTipoProcedimientoMedico->buscar($item->id_tipo_procedimiento_medico);
	                $detalle = $this->lNegocioDetalleExamenParaclinicos->buscarLista("id_examen_paraclinicos =".$item->id_examen_paraclinicos." order by 1");
	                $datos .= '<tr>';
	                $datos .= '<td>'.$tipo->getTipo().'</td>';
	                if($detalle->count()){
	                    $datos .= '<td>';
	                    foreach ($detalle as $detall) {
	                        $subtipo = $this->lNegocioSubtipoProcedimientoMedico->buscar($detall->id_subtipo_proced_medico);
	                            $datos .= $subtipo->getSubtipo();
	                            if($detall->respuesta){
	                                $datos .= ', '.$detall->respuesta;
	                            }
	                            if($detall->oido){
	                                $datos .= ', '.$detall->oido.'<br>';
	                            }else{
	                                $datos .= '<br>';
	                            }
	                    }
	                    $datos .= '</td>';
	                }
	                $datos .= '<td>'.$item->observaciones.'</td>';
	                if($opt){
	                    $datos .= '<td><button class="bEliminar icono" onclick="eliminarParaclinicos('.$item->id_examen_paraclinicos.'); return false; "></button></td>';
	                }else{
	                    $datos .= '<td></td>';
	                }
	                
	                $datos .= '<tr>';
	                
	                $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Tipo</th>
						<th>Resultado</th>
                        <th>Observaciones</th>
						<th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	            }
	        }
	    }
	    return $html;
	}
	
	//**********************************impresion diagnosticada**************************
	/**
	 * funcion para agregar impresiones diagnosticadas
	 */
	public function agregarDiagnostico() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_historia_clinica']) && isset($_POST['id_cie'])){
	        if(!empty($_POST['id_historia_clinica'])){
	            foreach ($_POST['estado_diagnostico'] as $value) {
	                $estadoDiag = $value;
	            }
	            $_POST['estado_diagnostico'] = $estadoDiag;
	            $resultado = $this->lNegocioImpresionDiagnostica->guardar($_POST);
	            if($resultado){
	                $contenido = $this->listarDiagnostico($_POST['id_historia_clinica']);
	                $mensaje = 'Registro agregado correctamente';
	            }else {
	                $estado = 'ERROR';
	                $mensaje = 'Error al guardar los datos !!';
	            }
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'Debe crear la historia clínica !!';
	        }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Debe crear la historia clínica !!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	/**
	 * funcion para eliminar examenes paraclinicos
	 */
	public function eliminarDiagnostico() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_impresion_diagnostica']) && isset($_POST['id_historia_clinica'])){
	        $this->lNegocioImpresionDiagnostica->borrar($_POST['id_impresion_diagnostica']);
	        $contenido = $this->listarDiagnostico($_POST['id_historia_clinica']);
	        $mensaje = 'Registro eliminado correctamente';
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar el registro !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	
	/**
	 * listar examenes paraclinicos
	 */
	public function listarDiagnostico($idHistoriaClinica=null,$opt=1){
	    $datos=$html='';
	    if($idHistoriaClinica != null){
	        $consulta = $this->lNegocioImpresionDiagnostica->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	        if($consulta->count()){
	            foreach ($consulta as $item) {
	                $valor = $this->lNegocioCie->buscar($item->id_cie);
	                $datos .= '<tr>';
	                $datos .= '<td>'.$valor->getDescripcion().'</td>';
	                $datos .= '<td>'.$item->estado_diagnostico.'</td>';
	                $datos .= '<td>'.$item->observaciones.'</td>';
	                if($opt){
	                    $datos .= '<td><button class="bEliminar icono" onclick="eliminarDiagnostico('.$item->id_impresion_diagnostica.'); return false; "></button></td>';
	                }else{
	                    $datos .= '<td></td>';
	                }
	                $datos .= '<tr>';
	            }
	            $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Enfermedad general</th>
						<th>Estado</th>
                        <th>Observaciones</th>
						<th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	        }
	    }
	    return $html;
	}
	
	public function filtrarInformacion(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
 	    $modeloHistoriaClinica = array();
 	    if(isset($_POST['tipo'])){
	    if($_POST['tipo'] == 'ci'){
 	        $consulta = "identificador_paciente='".$_POST['identificadorFiltro']."' order by 1 ";
 	        $modeloHistoriaClinica = $this->lNegocioHistoriaClinica->buscarLista($consulta);
	    }else if($_POST['tipo'] == 'pasaporte'){
	        $consulta = "identificador_paciente='".$_POST['identificadorFiltro']."'order by 1";
	        $modeloHistoriaClinica = $this->lNegocioHistoriaClinica->buscarLista($consulta);
	    }else {
	        $arrayParametros = array('identificador_paciente' => $_POST['identificadorFiltro']);
            $modeloHistoriaClinica = $this->lNegocioHistoriaClinica->obtenerDatosPorApellido($arrayParametros);
 	    }
 	    
 	    if($modeloHistoriaClinica->count()==0){
 	        $estado = 'FALLO';
 	        $mensaje = 'No existe el paciente buscado..!!';
 	    }
	    
	    $this->tablaHtmlHistoriaClinica($modeloHistoriaClinica);
	    $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido));
	}
	//***************************************************************************************
	/**
	 * listar examenes paraclinicos
	 */
	public function listarLog($idHistoriaClinica=null){
	    $datos=$html='';
	    if($idHistoriaClinica != null){
	        $consulta = $this->lNegocioLog->buscarLista("id_historia_clinica =".$idHistoriaClinica." order by 1 ");
	        if($consulta->count()){
	            foreach ($consulta as $item) {
	                $array = array('identificador' => $item->identificador);
	                $valor = $this->lNegocioHistoriaClinica->obtenerDatosFirma($array);
	                $datos .= '<tr>';
	                $datos .= '<td>'.date('Y-m-d',strtotime($item->fecha_creacion)).'</td>';
	                $datos .= '<td>'.$valor->current()->funcionario.'</td>';
	                $datos .= '<td>'.$valor->current()->cargo.'</td>';
	                $datos .= '<tr>';
	            }
	            $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Fecha de modificación</th>
						<th>Funcionario que registro el cambio</th>
                        <th>Cargo</th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	        }
	    }
	    $this->historico= $html;
	}
	
	
}
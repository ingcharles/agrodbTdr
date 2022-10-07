<?php
 /**
 * Controlador ConsultaMedica
 *
 * Este archivo controla la lógica del negocio del modelo:  ConsultaMedicaModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-03-16
 * @uses    ConsultaMedicaControlador
 * @package HistoriasClinicas
 * @subpackage Controladores
 */
 namespace Agrodb\HistoriasClinicas\Controladores;
 use Agrodb\HistoriasClinicas\Modelos\ConsultaMedicaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ConsultaMedicaModelo;
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
 use Agrodb\HistoriasClinicas\Modelos\AdjuntosConsultaMedicaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\AdjuntosConsultaMedicaModelo;
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
 use Agrodb\HistoriasClinicas\Modelos\ValoracionConsultaMedicaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ValoracionConsultaMedicaModelo;
 use Agrodb\HistoriasClinicas\Modelos\ReportesPdfLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ReportesPdfModelo;

 
 
class ConsultaMedicaControlador extends BaseControlador 
{

		 private $lNegocioConsultaMedica = null;
		 private $modeloConsultaMedica = null;
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
		 //*******************************************************
		 
		 private $lNegocioAdjuntosConsultaMedica = null;
		 private $lNegocioValoracionConsultaMedica = null;
		 private $modeloValoracionConsultaMedica =null;
		 private $lNegocioReportesPdf = null;
		 private $modeloReportesPdf = null;
		 
		 private $accion = null;
		 private $idConsultaMedica = null;
		 private $idHistorialClinica = null;
		 private $antecedentesSalud = null;
		 private $fechaConsulta = null;
		 private $estadoConsultaMedica = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioConsultaMedica = new ConsultaMedicaLogicaNegocio();
		 $this->modeloConsultaMedica = new ConsultaMedicaModelo();
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
		 
		 //**********************************************************
		 $this->lNegocioAdjuntosConsultaMedica= new AdjuntosConsultaMedicaLogicaNegocio();
		 $this->modeloAdjuntosConsultaMedica = new AdjuntosConsultaMedicaModelo();
		 $this->lNegocioValoracionConsultaMedica = new ValoracionConsultaMedicaLogicaNegocio();
		 $this->modeloValoracionConsultaMedica = new ValoracionConsultaMedicaModelo();
		 $this->lNegocioReportesPdf = new ReportesPdfLogicaNegocio();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $this->perfilUsuario();
		 if($this->perfilUsuario == 'PFL_MEDICO'){
		     $modeloConsultaMedica = $this->lNegocioConsultaMedica->buscarConsultaMedica();
		     $this->filtroHistorias();
		     $this->tablaHtmlConsultaMedica($modeloConsultaMedica);
		     require APP . 'HistoriasClinicas/vistas/listaConsultaMedicaVista.php';
		 }else{
		     $consul = $this->lNegocioHistoriaClinica->buscarLista("identificador_paciente='".$_SESSION['usuario']."'");
		     if($consul->count()){
		         $modeloConsultaMedica = $this->lNegocioConsultaMedica->buscarLista("id_historia_clinica=".$consul->current()->id_historia_clinica);
		     }else{
		         $modeloConsultaMedica = array();
		     }
		     $this->filtroHistorias();
		     $this->tablaHtmlConsultaMedicaPaciente($modeloConsultaMedica);
		     require APP . 'HistoriasClinicas/vistas/listaConsultaMedicaPacienteVista.php';
		 }
		 
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nueva Consulta Médica"; 
		 $this->fechaConsulta = date('Y-m-d');
		 $this->divInformacionPaciente('');
		 $this->divInformacionCargo('');
		 $this->divInformacionDiscapacidad('');
		 $this->divAusentismo();
		 $arrayParametros = array(
		     'identificador' => $_SESSION['usuario']);
		 $resultFirma = $this->lNegocioHistoriaClinica->obtenerDatosFirma($arrayParametros);
		 if($resultFirma->count()){
		     $this->divInformacionFirma($resultFirma->current());
		 }else{
		     $this->divInformacionFirma('');
		 }
		 require APP . 'HistoriasClinicas/vistas/formularioConsultaMedicaVista.php';
		}	/**
		* Método para registrar en la base de datos -ConsultaMedica
		*/
		public function guardar()
		{
		  $this->lNegocioConsultaMedica->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: ConsultaMedica
		*/
		public function editar()
		{
		 $this->perfilUsuario();
		 $this->idConsultaMedica= $_POST['id'];
		 $this->modeloConsultaMedica = $this->lNegocioConsultaMedica->buscar($_POST["id"]);
		 $this->modeloHistoriaClinica = $this->lNegocioHistoriaClinica->buscar($this->modeloConsultaMedica->getIdHistoriaClinica());
		 $arrayParametros = array(
		     'identificador_paciente' =>  $this->modeloHistoriaClinica->getIdentificadorPaciente());
		 $resultado = $this->lNegocioConsultaMedica->buscarInformacionPaciente($arrayParametros);
		 $this->divInformacionPaciente($resultado->current());
	     $resultCargo = $this->lNegocioHistoriaClinica->obtenerDatosContrato($arrayParametros);
		 $this->divInformacionCargo($resultCargo->current());
	     $this->divInformacionDiscapacidad($resultado->current());
	     $this->idHistorialClinica = $this->modeloHistoriaClinica->getIdHistoriaClinica();
	     $ausent = $this->lNegocioAusentismoMedico->buscarLista("id_historia_clinica=".$this->modeloHistoriaClinica->getIdHistoriaClinica());
	     if($ausent->count()){
	         $this->modeloAusentismoMedico = $this->lNegocioAusentismoMedico->buscar($ausent->current()->id_ausentismo_medico);
	         $this->divAusentismo();
	     }
	     $this->antecedentesSalud = $this->listarAntecedentesSalud($this->modeloHistoriaClinica->getIdHistoriaClinica());
	     $fisico = $this->lNegocioExamenFisico->buscarLista("id_consulta_medica = ".$_POST["id"]);
	     if($fisico->count()){
	         $this->modeloExamenFisico = $this->lNegocioExamenFisico->buscar($fisico->current()->id_examen_fisico);
	     }
	     $this->fechaConsulta = $this->modeloConsultaMedica->getFechaConsulta();
	     $this->estadoConsultaMedica = $this->modeloConsultaMedica->getEstado();
	     
	     if($this->perfilUsuario == 'PFL_MEDICO'){
    	     if($this->modeloConsultaMedica->getEstado() == 'Finalizado'){
    	         $this->accion = "Reporte Consulta Médica"; 
    	         require APP . 'HistoriasClinicas/vistas/formularioConsultaMedicaReporteVista.php';
    	     }else{
    	         require APP . 'HistoriasClinicas/vistas/formularioConsultaMedicaVista.php';
    	         $this->accion = "Editar Consulta Médica"; 
    	     }
	     }else{
	         if($this->modeloConsultaMedica->getEstado() == 'Finalizado'){
	             $this->accion = "Reporte Consulta Médica";
	             require APP . 'HistoriasClinicas/vistas/formularioConsultaMedicaPacienteVista.php';
	         }
	     }
		 
		}	/**
		* Método para borrar un registro en la base de datos - ConsultaMedica
		*/
		public function borrar()
		{
		  $this->lNegocioConsultaMedica->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - ConsultaMedica
		*/
		 public function tablaHtmlConsultaMedica($tabla) 
		{
    		 $contador = 0;
    		  foreach ($tabla as $fila) {
        		      $historia = $this->lNegocioHistoriaClinica->buscar($fila->id_historia_clinica);
        		      $arrayParametros = array(
        		          'identificador' =>$historia->getIdentificadorPaciente());
        		      $resultConsulta = $this->lNegocioHistoriaClinica->obtenerDatosFirma($arrayParametros);
        		      $this->itemsFiltrados[] = array(
        		      '<tr id="' . $fila['id_consulta_medica'] . '"
        		      class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'HistoriasClinicas\consultaMedica"
        		      data-opcion="editar" ondragstart="drag(event)" draggable="true"
        		      data-destino="detalleItem">
        		      <td>' . ++$contador . '</td>
        		      <td style="white - space:nowrap; "><b>' . $fila['fecha_consulta'] . '</b></td>
                      <td>' . $historia->getIdentificadorPaciente()  . '</td>
                      <td>' . $resultConsulta->current()->funcionario . '</td>
                      </tr>');
    		      
    		}
		}
		
		/**
		 * Construye el código HTML para desplegar la lista de - ConsultaMedica
		 */
		public function tablaHtmlConsultaMedicaPaciente($tabla)
		{
		    $contador = 0;
		    foreach ($tabla as $fila) {
		       if($fila->estado == 'Finalizado') {
		            $this->itemsFiltrados[] = array(
		                '<tr id="' . $fila['id_consulta_medica'] . '"
        		      class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'HistoriasClinicas\consultaMedica"
        		      data-opcion="editar" ondragstart="drag(event)" draggable="true"
        		      data-destino="detalleItem">
        		      <td>' . ++$contador . '</td>
        		      <td style="white - space:nowrap; "><b>' . $fila['fecha_consulta'] . '</b></td>
                      <td>' . $fila->sintomas  . '</td>
                      </tr>');
		        }
		    }
		}
//**********************************************
	/**
	 * funcion para construir la informacion del paciente
	 *
	 */
	 public function divInformacionPaciente($tabla,$fecha=null) {
	     
	     if($tabla == ''){
	         $arrayPaciente = array(
	             'fecha_creacion'=>'',
	             'identificador' => '',
	             'funcionario' => '',
	             'fecha_nacimiento' => '',
	             'genero' => '',
	             'estado_civil' => '',
	             'edad' => '',
	             'tipo_sangre' => '',
	             'nivel_instruccion' => '',
	             'convencional' => ''
	             
	         );
	         $tabla = $arrayPaciente;
	     }
	     
	     $div = '
		<legend>Información del funcionario</legend>
        <div data-linea="1">
			<label for="fecha_creacion">Fecha creación Historia Clínica:</label>
			<span>'.$tabla['fecha_creacion'].'</span>
		</div>
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
			<span></span>
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
			<label for="observaciones_revision_organos">Religión:</label>
			<span>'.$tabla['edad'].'</span>
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
			<span>'.$tabla['edad'].'</span>
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
	  * funcion para construir ausentismo
	  */
	 public function  divAusentismo(){
	     
	     $html = '
         <legend>Ausentismo médico en el último trimestre</legend>				
								 				
		<div data-linea="2">
			<label for="causa">Causa: </label>
			<span>'.$this->modeloAusentismoMedico->getCausa().'</span>
		</div>						 
		<div data-linea="2">
			<label for="tiempo">Tiempo (horas): </label>
			<span>'.$this->modeloAusentismoMedico->getTiempo().'</span>
		</div>		';
	     $this->divAusent = $html;
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
	 //**************************************************************
	 /**
	  * listar antecedentes de salud
	  */
	 public function listarAntecedentesSalud($idHistoriaClinica=null){
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
	                // $datos .= '<td><button class="bEliminar icono" onclick="eliminarAntecedentesSalud('.$item->id_antecedentes_salud.'); return false; "></button></td>';
	                 $datos .= '<tr>';
	             }
	             
	             $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Tipo de antecedente</th>
						<th>Diagnóstico</th>
						<th>Observaciones</th>
                        <th>Información completa</th>
						
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>
           ';
	         }
	     }
	     return $html;
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
	  * funcion para buscar informacion del funcionario
	  */
	 public function buscarFuncionario(){
	     $estado = 'EXITO';
	     $mensaje = '';
	     $paciente = '';
	     $puesto = '';
	     $discapacidad = '';
	     $antecede ='';
	     $ausentismo = '';
	     $idHistoria = '';

        	     $arrayParametros = array(
        	         'identificador_paciente' => $_POST['identificador']);
        	     $resultado = $this->lNegocioConsultaMedica->buscarInformacionPaciente($arrayParametros);
        	     if($resultado->count()>0){
        	         $this->divInformacionPaciente($resultado->current());
        	         $paciente = $this->divInformacion;
        	         $resultCargo = $this->lNegocioHistoriaClinica->obtenerDatosContrato($arrayParametros);
        	         $this->divInformacionCargo($resultCargo->current());
        	         $puesto = $this->divCargo;
        	         $this->divInformacionDiscapacidad($resultado->current());
        	         $discapacidad = $this->divDiscapacidad;
        	         $antecede = $this->listarAntecedentesSalud($resultado->current()->id_historia_clinica);
        	         $ausent = $this->lNegocioAusentismoMedico->buscarLista("id_historia_clinica=".$resultado->current()->id_historia_clinica);
        	         if($ausent->count()){
        	             $this->modeloAusentismoMedico = $this->lNegocioAusentismoMedico->buscar($ausent->current()->id_ausentismo_medico);
        	             $this->divAusentismo();
        	             $ausentismo=$this->divAusent;
        	         }
        	         $idHistoria = $resultado->current()->id_historia_clinica;
        	     }else{
        	         $estado = 'ERROR';
        	         $mensaje = 'No existe el funcionario buscado !!';
        	         $this->divInformacionPaciente('');
        	         $paciente = $this->divInformacion;
        	         $this->divInformacionCargo('');
        	         $puesto = $this->divCargo;
        	         $this->divInformacionDiscapacidad('');
        	         $discapacidad = $this->divDiscapacidad;
        	         $this->divAusentismo();
        	         $ausentismo=$this->divAusent;
        	         
        	     }
	     
	     echo json_encode(array(
	         'estado' => $estado,
	         'mensaje' => $mensaje,
	         'paciente' => $paciente,
	         'puesto' => $puesto,
	         'discapacidad' => $discapacidad,
	         'antecede' => $antecede,
	         'ausentismo' => $ausentismo,
	         'idHistoria' => $idHistoria
	     ));
	 }
	 
	 /**
	  * funcion para agregar diagnostico
	  */
	 public function agregarDiagnostico() {
	     
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     if(isset($_POST['id_consulta_medica']) && isset($_POST['id_cie'])){
	         if(!empty($_POST['id_consulta_medica'])){
	             foreach ($_POST['estado_diagnostico'] as $value) {
	                 $estadoDiag = $value;
	             }
	             $_POST['estado_diagnostico'] = $estadoDiag;
	             $resultado = $this->lNegocioImpresionDiagnostica->guardar($_POST);
	             if($resultado){
	                 $contenido = $this->listarDiagnostico($_POST['id_consulta_medica']);
	                 $mensaje = 'Registro agregado correctamente';
	             }else {
	                 $estado = 'ERROR';
	                 $mensaje = 'Error al guardar los datos !!';
	             }
	         }else{
	             $estado = 'ERROR';
	             $mensaje = 'Debe crear la consulta médica !!';
	         }
	     }else{
	         $estado = 'ERROR';
	         $mensaje = 'Debe crear la consulta médica !!';
	     }
	     
	     echo json_encode(array(
	         'estado' => $estado,
	         'mensaje' => $mensaje,
	         'contenido' => $contenido
	     ));
	 }
	 /**
	  * funcion para eliminar diagnostico
	  */
	 public function eliminarDiagnostico() {
	     
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     if(isset($_POST['id_impresion_diagnostica']) && isset($_POST['id_consulta_medica'])){
	         $this->lNegocioImpresionDiagnostica->borrar($_POST['id_impresion_diagnostica']);
	         $contenido = $this->listarDiagnostico($_POST['id_consulta_medica']);
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
	  * listar diagnostico
	  */
	 public function listarDiagnostico($idConsultaMedica=null,$opt=1){
	     $datos=$html='';
	     if($idConsultaMedica != null){
	         $consulta = $this->lNegocioImpresionDiagnostica->buscarLista("id_consulta_medica =".$idConsultaMedica." order by 1 ");
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
	 /**
	  * funcion para crear la consulta médica
	  */
	 public function crearConsultaMedica() {
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     
	     if($_POST['identificador_paciente'] != ''){
	         $datos = array(
	             'id_historia_clinica' => $_POST['id_historia_clinica'],
	             'identificador_medico' => $_SESSION['usuario'],
	             'fecha_consulta' => date('Y-m-d')
	         );
	             $contenido = $this->lNegocioConsultaMedica->guardar($datos);
	             $mensaje = 'Consulta médica creada correctamente';
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
	  * listar archivos adjuntos
	  */
	 public function listarAdjuntosConsulta($idConsultaMedica=null, $opt=1){
	     $html=$datos='';
	     if($idConsultaMedica != null){
	         $consulta = $this->lNegocioAdjuntosConsultaMedica->buscarLista("id_consulta_medica =".$idConsultaMedica." and descripcion_adjunto not in('Receta médica','Certificado médico') order by 1 ");
	         if($consulta->count()){
	             $count=0;
	                     foreach ($consulta as $item) {
	                         $datos .= '<tr>';
	                         $datos .= '<td>'.++$count.'</td>';
	                         $datos .= '<td>'.$item->descripcion_adjunto.'</td>';
	                         $datos .= '<td><a href="'.$item->documento_adjunto.'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a></td>';
	                         if($opt){
	                             $datos .= '<td><button class="bEliminar icono" onclick="eliminarAdjunto('.$item->id_adjuntos_consulta_medica.'); return false; "></button></td>';
	                         }else{
	                             $datos .= '<td></td>';
	                         }
	                         $datos .= '</tr>';
	                     }
	                     $html = '
				<table style="width:100%">
					<thead><tr>
						<th>#</th>
						<th>Descripción</th>
                        <th>Documento adjunto</th>
						<th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	         }
	     }
	     return $html;
	 }
	 
	 /**
	  * guardar archivo adjunto
	  *
	  * */
	 public function agregarDocumentosAdjuntos()
	 {
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     if(!empty($_REQUEST['id_consulta_medica']) && $_REQUEST['id_consulta_medica'] != 'null' && $_REQUEST['id_historia_clinica'] != 'null'){
	         try {
	             
	             $identificador = $this->lNegocioHistoriaClinica->buscar($_REQUEST['id_historia_clinica']);
	             $nombre_archivo = $_FILES['archivo']['name'];
	             $tipo_archivo = $_FILES['archivo']['type'];
	             $tamano_archivo = $_FILES['archivo']['size'];
	             $tmpArchivo = $_FILES['archivo']['tmp_name'];
	             $rutaCarpeta = HIST_CLI_URL."adjuntosConsultaMedica/".$identificador->getIdentificadorPaciente();
	             $extension = explode(".", $nombre_archivo);
	             if ($tamano_archivo != '0' ) {
	                 if (strtoupper(end($extension)) == 'PDF' && $tipo_archivo == 'application/pdf') {
	                     if (!file_exists('../../' . $rutaCarpeta)) {
	                         mkdir('../../' .$rutaCarpeta, 0777, true);
	                     }
	                     $secuencial = date('Ymds').mt_rand(100,999);
	                     $nuevo_nombre = 'consulta_medica_'.$identificador->getIdentificadorPaciente().'_'.$secuencial.'.' . end($extension);
	                     $ruta = $rutaCarpeta . '/' . $nuevo_nombre;
	                     move_uploaded_file($tmpArchivo, '../../' . $ruta);
	                     $arrayAdjunto = array(
	                         'id_consulta_medica' =>$_REQUEST['id_consulta_medica'],
	                         'documento_adjunto' => $ruta,
	                         'descripcion_adjunto' => $_REQUEST['descripcion_adjunto']
	                     );
	                     $id = $this->lNegocioAdjuntosConsultaMedica->guardar($arrayAdjunto);
	                     if($id){
	                         $mensaje = 'Registro agregado correctamente';
	                         $contenido = $this->listarAdjuntosConsulta($_REQUEST['id_consulta_medica']);
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
	         $mensaje = 'Debe crear la consulta médica !!';
	     }
	     echo json_encode(array(
	         'estado' => $estado,
	         'mensaje' => $mensaje,
	         'contenido' => $contenido
	     ));
	 } 
	 
	 /**
	  * funcion para eliminar adjunto
	  */
	 public function eliminarAdjunto() {
	     
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     if(isset($_POST['id_adjuntos_consulta_medica']) && isset($_POST['id_consulta_medica'])){
	         $ruta = $this->lNegocioAdjuntosConsultaMedica->buscar($_POST['id_adjuntos_consulta_medica']);
	         $this->lNegocioAdjuntosConsultaMedica->borrar($_POST['id_adjuntos_consulta_medica']);
	         unlink('../../'.$ruta->getDocumentoAdjunto());
	         $contenido = $this->listarAdjuntosConsulta($_POST['id_consulta_medica']);
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
	  * funcion para agregar la valoracion médica
	  */
	 public function agregarValoracionMedicamentos() {
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     if(isset($_POST['id_consulta_medica'])){
	         if(!empty($_POST['id_consulta_medica'])){
        	         $id = $this->lNegocioValoracionConsultaMedica->guardar($_POST);
        	         if($id){
        	             $contenido = $this->listarValoracionMedica($_POST['id_consulta_medica']);
        	             $mensaje = 'Valoración médica agregada correctamente';
        	         }else{
        	             $mensaje = 'Error al guardar la valoración médica.. !!';
        	         }
        	         
        	     }else{
        	         $estado = 'ERROR';
        	         $mensaje = 'Debe crear la consulta médica !!';
        	     }
	         }else{
	             $estado = 'ERROR';
	             $mensaje = 'Debe crear la consulta médica  !!';
	         }
	     echo json_encode(array(
	         'estado' => $estado,
	         'mensaje' => $mensaje,
	         'contenido' => $contenido
	     ));
	 }
	 /**
	  * listar valoración médica
	  */
	 public function listarValoracionMedica($idConsultaMedica=null,$opt=1){
	     $html=$datos='';
	     if($idConsultaMedica != null){
	         $consulta = $this->lNegocioValoracionConsultaMedica->buscarLista("id_consulta_medica =".$idConsultaMedica."  order by 1 ");
	         if($consulta->count()){
	             foreach ($consulta as $item) {
	                 $datos .= '<tr>';
	                 $datos .= '<td>'.$item->medicamento.'</td>';
	                 $datos .= '<td>'.$item->forma_farmaceutica.'</td>';
	                 $datos .= '<td>'.$item->concentracion.'</td>';
	                 if($opt){
	                     $datos .= '<td><button class="bEliminar icono" onclick="eliminarValoracion('.$item->id_valoracion_consulta_medica.'); return false; "></button></td>';
	                 }else{
	                     $datos .= '<td></td>';
	                 }
	                 $datos .= '</tr>';
	             }
	             $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Medicamento</th>
						<th>Forma farmacéutica</th>
                        <th>Concentración</th>
						<th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	         }
	     }
	     return $html;
	 }
	 /**
	  * funcion para eliminar valoracion medica
	  */
	 public function eliminarValoracion() {
	     
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     $campo = 0;
	     if(isset($_POST['id_valoracion_consulta_medica']) && isset($_POST['id_consulta_medica'])){
	         $this->lNegocioValoracionConsultaMedica->borrar($_POST['id_valoracion_consulta_medica']);
	         $consulta = $this->lNegocioValoracionConsultaMedica->buscarLista("id_consulta_medica =".$_POST['id_consulta_medica']."  order by 1 ");
	         if($consulta->count()==0){
	             $campo=1;
	         }
	         $contenido = $this->listarValoracionMedica($_POST['id_consulta_medica']);
	         $mensaje = 'Registro eliminado correctamente';
	     }else{
	         $estado = 'ERROR';
	         $mensaje = 'Error al eliminar el registro !!';
	     }
	     echo json_encode(array(
	         'estado' => $estado,
	         'mensaje' => $mensaje,
	         'contenido' => $contenido,
	         'campo' => $campo
	     ));
	 }
	 
	 /**
	  * funcion para imprimir receta
	  */
	 public function crearRecetaMedica() {
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     $archivo = '';
	     if(isset($_POST['id_consulta_medica']) && isset($_POST['id_historia_clinica'])){
	         if(!empty($_POST['id_consulta_medica']) && !empty($_POST['id_historia_clinica'])){
	             $historia = $this->lNegocioHistoriaClinica->buscar($_POST['id_historia_clinica']);
	             $rutaCarpeta = HIST_CLI_URL."adjuntosConsultaMedica/".$historia->getIdentificadorPaciente();
                     if (!file_exists('../../' . $rutaCarpeta)) {
                         mkdir('../../' .$rutaCarpeta, 0777, true);
                     }
                 $secuencial = date('Ymd').'_'.$_POST['id_consulta_medica'];
	             $nombre = 'receta_medica_';
	             $fecha = $this->lNegocioConsultaMedica->buscar($_POST['id_consulta_medica']);
	             $rutaArchivo = "adjuntosConsultaMedica/".$historia->getIdentificadorPaciente()."/".$nombre.$historia->getIdentificadorPaciente().'_'.$secuencial.".pdf";
	             $arrayParametros = array('identificador_paciente' => $historia->getIdentificadorPaciente());
	             $infoPaciente = $this->lNegocioHistoriaClinica->buscarInformacionPaciente($arrayParametros);
	             $arrayParametros = array('identificador_paciente' => $historia->getIdentificadorMedico());
	             $infoMedico = $this->lNegocioHistoriaClinica->buscarInformacionPaciente($arrayParametros);
	             $medicamento = $this->lNegocioValoracionConsultaMedica->buscarLista("id_consulta_medica=".$_POST['id_consulta_medica']." order by 1");
	             $medica = array();
	             if($medicamento->count()){
	                 $medica = array();
	                 foreach ($medicamento as $value) {
	                     $medica []=[$value->medicamento, $value->forma_farmaceutica,$value->concentracion, $value->indicaciones];
	                 }
	             }
	             
	             $arrayParametros = array(
	                 'rutaArchivo' => $rutaArchivo,
	                 'fecha' => $fecha->getFechaConsulta(),
	                 'ciudad' => 'Quito',
	                 'cedula' => $historia->getIdentificadorPaciente(),
	                 'paciente' => $infoPaciente->current()->funcionario,
	                 'cedula_medico' => $historia->getIdentificadorMedico(),
	                 'nombre_medico' => $infoMedico->current()->funcionario,
	                 'medicamento' =>$medica
	                 
	             );
	             
	             $res = $this->lNegocioReportesPdf->generarRecetaMedica($arrayParametros);
	             if($res){
	                 
	                 $arrayAdjunto = array(
	                     'id_consulta_medica' =>$_REQUEST['id_consulta_medica'],
	                     'documento_adjunto' => HIST_CLI_URL.$rutaArchivo,
	                     'descripcion_adjunto' => 'Receta médica'
	                 );
	                 
	                 $verificarReceta = $this->lNegocioAdjuntosConsultaMedica->buscarLista("id_consulta_medica=".$_REQUEST['id_consulta_medica']." and descripcion_adjunto='Receta médica'");
	                 if($verificarReceta->count()){
	                     $arrayAdjunto['id_adjuntos_consulta_medica'] = $verificarReceta->current()->id_adjuntos_consulta_medica;
	                 }
	                 $id = $this->lNegocioAdjuntosConsultaMedica->guardar($arrayAdjunto);
	                 if($id){
	                     $contenido = HIST_CLI_URL.$rutaArchivo;
	                     $mensaje = 'Receta generada correctamente';
	                     $archivo = $this->listarRecetaCertificado($_REQUEST['id_consulta_medica']);
	                 }else{
	                     $estado = 'ERROR';
	                     $mensaje = 'Error al guardar el registro..!!';
	                 }
	                 
	             }else{
	                 $mensaje = 'Error al crear la receta médica.. !!';
	             }
	             
	         }else{
	             $estado = 'ERROR';
	             $mensaje = 'Debe crear la consulta médica !!';
	         }
	     }else{
	         $estado = 'ERROR';
	         $mensaje = 'Debe crear la consulta médica  !!';
	     }
	     echo json_encode(array(
	         'estado' => $estado,
	         'mensaje' => $mensaje,
	         'contenido' => $contenido,
	         'archivo' => $archivo
	     ));
	 }
	 
	 /**
	  * funcion para imprimir Certificado
	  */
	 public function generarCertificado() {
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     $archivo = '';
	     if(isset($_POST['id_consulta_medica']) && isset($_POST['id_historia_clinica'])){
	         if(!empty($_POST['id_consulta_medica']) && !empty($_POST['id_historia_clinica'])){
	             $historia = $this->lNegocioHistoriaClinica->buscar($_POST['id_historia_clinica']);
	             $rutaCarpeta = HIST_CLI_URL."adjuntosConsultaMedica/".$historia->getIdentificadorPaciente();
	             if (!file_exists('../../' . $rutaCarpeta)) {
	                 mkdir('../../' .$rutaCarpeta, 0777, true);
	             }
	             $secuencial = date('Ymd').'_'.$_POST['id_consulta_medica'];
	             $nombre = 'certificado_medico_';
	             $rutaArchivo = "adjuntosConsultaMedica/".$historia->getIdentificadorPaciente()."/".$nombre.$historia->getIdentificadorPaciente().'_'.$secuencial.".pdf";
	             $arrayParametros = array('identificador_paciente' => $historia->getIdentificadorPaciente());
	             $infoPaciente = $this->lNegocioHistoriaClinica->buscarInformacionPaciente($arrayParametros);
	             $arrayParametros = array('identificador_paciente' => $historia->getIdentificadorMedico());
	             $infoMedico = $this->lNegocioHistoriaClinica->buscarInformacionPaciente($arrayParametros);
	             $diagnostico = $this->lNegocioImpresionDiagnostica->buscarLista("id_consulta_medica=".$_POST['id_consulta_medica']);
	             
	             if($diagnostico->count()){
	                         $cie = $this->lNegocioCie->buscar($diagnostico->current()->id_cie);
            	             $arrayParametros = array(
            	                 'rutaArchivo' => $rutaArchivo,
            	                 'fecha' => date('Y-m-d'),
            	                 'ciudad' => 'Quito',
            	                 'cedula' => $historia->getIdentificadorPaciente(),
            	                 'paciente' => $infoPaciente->current()->funcionario,
            	                 'cedula_medico' => $historia->getIdentificadorMedico(),
            	                 'nombre_medico' => $infoMedico->current()->funcionario,
            	                 'cie' => $cie->getCodigo(),
            	                 'descripcion_cie' => $cie->getDescripcion(),
            	                 'fecha_desde' => $_POST['fecha_desde'],
            	                 'fecha_hasta' => $_POST['fecha_hasta'],
            	                 'dias' => $_POST['dias_reposo']
            	             );
            	             
            	             $res = $this->lNegocioReportesPdf->generarCertificadoMedico($arrayParametros);
            	             if($res){
            	                 $arrayAdjunto = array(
            	                     'id_consulta_medica' =>$_REQUEST['id_consulta_medica'],
            	                     'documento_adjunto' => HIST_CLI_URL.$rutaArchivo,
            	                     'descripcion_adjunto' => 'Certificado médico'
            	                 );
            	                 $verificarCert = $this->lNegocioAdjuntosConsultaMedica->buscarLista("id_consulta_medica=".$_REQUEST['id_consulta_medica']." and descripcion_adjunto='Certificado médico'");
            	                 if($verificarCert->count()){
            	                     $arrayAdjunto['id_adjuntos_consulta_medica'] = $verificarCert->current()->id_adjuntos_consulta_medica;
            	                 }
            	                 $id = $this->lNegocioAdjuntosConsultaMedica->guardar($arrayAdjunto);
            	                 if($id){
            	                     $contenido = HIST_CLI_URL.$rutaArchivo;
            	                     $mensaje = 'Certificado generado correctamente';
            	                     $archivo = $this->listarRecetaCertificado($_REQUEST['id_consulta_medica']);
            	                 }else{
            	                     $estado = 'ERROR';
            	                     $mensaje = 'Error al guardar el registro..!!';
            	                 }
            	             }else{
            	                 $mensaje = 'Error al generar el certificado.. !!';
            	             }
	             }else{
	                 $estado = 'ERROR';
	                 $mensaje = 'No existe un diagnostico ingresado.. !!';
	             }
	         }else{
	             $estado = 'ERROR';
	             $mensaje = 'Debe crear la consulta médica !!';
	         }
	     }else{
	         $estado = 'ERROR';
	         $mensaje = 'Debe crear la consulta médica  !!';
	     }
	     echo json_encode(array(
	         'estado' => $estado,
	         'mensaje' => $mensaje,
	         'contenido' => $contenido,
	         'archivo' => $archivo
	     ));
	 }
	 
	 public function sumarFecha() {
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     if($_POST['dias'] != ''){
    	     if(isset($_POST['fecha_desde'])){
    	         $contenido = date('Y-m-d', strtotime($_POST['fecha_desde']."+ ".intval($_POST['dias'])." days"));
    	     }else{
    	         $estado = 'ERROR';
    	         $mensaje = 'No existe la fecha desde !!';
    	     }
	     }else{
	         $estado = 'ERROR';
	         $mensaje = 'Debe seleccionar un número !!';
	     }
	     echo json_encode(array(
	         'estado' => $estado,
	         'mensaje' => $mensaje,
	         'contenido' => $contenido
	     ));
	 }
	 
	 public function guardarConsultaMedica() {
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     if(isset($_POST['id_consulta_medica'])){
	         if(!empty($_POST['id_consulta_medica'])){
	             $ban=1;
	             if($_POST['medicacion'] == 'Si'){
	                 $valoracion =$this->lNegocioValoracionConsultaMedica->buscarLista("id_consulta_medica=".$_POST['id_consulta_medica']);
	                 if($valoracion->count()==0){
	                     $ban=0;
	                 }
	             }
	             if($ban){
	                  $estadoCm= $this->lNegocioConsultaMedica->buscar($_POST['id_consulta_medica']);
	                  if($estadoCm->getEstado() != 'Finalizado'){
	                      $verifi = $this->lNegocioConsultaMedica->guardarDetalle($_POST);
	                      if($verifi){
	                          $mensaje = 'Consulta médica finalizada correctamente';
	                      }else{
	                          $estado = 'ERROR';
	                          $mensaje = 'Error al guardar los datos.. !!';
	                      }
	                  }else{
	                      $estado = 'ERROR';
	                      $mensaje = 'Consulta médica ya finalizada.. !!';
	                  }
	                  
	             }else{
	                 $estado = 'ERROR';
	                 $mensaje = 'No existe medicamentos ingresados.. !!';
	             }
	         }else{
	             $estado = 'ERROR';
	             $mensaje = 'Debe crear la consulta médica !!';
	         }
	     }else{
	         $estado = 'ERROR';
	         $mensaje = 'Debe crear la consulta médica  !!';
	     }
	     echo json_encode(array(
	         'estado' => $estado,
	         'mensaje' => $mensaje,
	         'contenido' => $contenido
	     ));
	 }
    /****
     * funcion para consultar información por ci, apellido, pasaporte
     */
	 public function filtrarInformacion(){
	     $estado = 'EXITO';
	     $mensaje = '';
	     $contenido = '';
	     $modeloCertificadoMedico = array();
	     if(isset($_POST['tipo'])){
	         if($_POST['tipo'] == 'ci' || $_POST['tipo'] == 'pasaporte'){
	             $arrayParametros = array('identificador_paciente' => $_POST['identificadorFiltro']);
	             $modeloCertificadoMedico = $this->lNegocioConsultaMedica->buscarFuncionario($arrayParametros);
	         }else {
	             $arrayParametros = array('apellido' => $_POST['identificadorFiltro']);
	             $modeloCertificadoMedico = $this->lNegocioConsultaMedica->buscarFuncionario($arrayParametros);
	         }
	         if($modeloCertificadoMedico->count()==0){
	             $estado = 'FALLO';
	             $mensaje = 'No existe el paciente buscado..!!';
	         }
	         $this->tablaHtmlConsultaMedica($modeloCertificadoMedico);
	         $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
	     }
	     echo json_encode(array(
	         'estado' => $estado,
	         'mensaje' => $mensaje,
	         'contenido' => $contenido));
	 }
	 /**
	  * listar archivos creados receta medica y certificado medico
	  */
	 public function listarRecetaCertificado($idConsultaMedica=null){
	     $html=$datos='';
	     if($idConsultaMedica != null){
	         $consulta = $this->lNegocioAdjuntosConsultaMedica->buscarLista("id_consulta_medica =".$idConsultaMedica." and descripcion_adjunto in('Receta médica','Certificado médico') order by 1 ");
	         if($consulta->count()){
	             $count=0;
	             foreach ($consulta as $item) {
	                 $datos .= '<tr>';
	                 $datos .= '<td>'.++$count.'</td>';
	                 $datos .= '<td>'.$item->descripcion_adjunto.'</td>';
	                 $datos .= '<td><a href="'.$item->documento_adjunto.'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a></td>';
	                 $datos .= '</tr>';
	             }
	             $html = '
				<table style="width:100%">
					<thead><tr>
						<th>#</th>
						<th>Descripción</th>
                        <th>Documento adjunto</th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	         }
	     }
	     return $html;
	 }
}

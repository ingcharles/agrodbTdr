<?php
 /**
 * Controlador CertificadoMedico
 *
 * Este archivo controla la lógica del negocio del modelo:  CertificadoMedicoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-03-16
 * @uses    CertificadoMedicoControlador
 * @package HistoriasClinicas
 * @subpackage Controladores
 */
 namespace Agrodb\HistoriasClinicas\Controladores;
 use Agrodb\HistoriasClinicas\Modelos\CertificadoMedicoLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\CertificadoMedicoModelo;
 use Agrodb\HistoriasClinicas\Modelos\HistoriaClinicaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\HistoriaClinicaModelo;
 use Agrodb\HistoriasClinicas\Modelos\ImpresionDiagnosticaLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ImpresionDiagnosticaModelo;
 use Agrodb\HistoriasClinicas\Modelos\CieLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\CieModelo;
 use Agrodb\HistoriasClinicas\Modelos\ReportesPdfLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\ReportesPdfModelo;
 use Agrodb\HistoriasClinicas\Modelos\AdjuntosCertificadoMedicoLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\AdjuntosCertificadoMedicoModelo;
 use Agrodb\HistoriasClinicas\Modelos\RecomendacionesLogicaNegocio;
 use Agrodb\HistoriasClinicas\Modelos\RecomendacionesModelo;
 //use Agrodb\Core\JasperReport;
 
class CertificadoMedicoControlador extends BaseControlador 
{

		 private $lNegocioCertificadoMedico = null;
		 private $modeloCertificadoMedico = null;
		 private $lNegocioHistoriaClinica = null;
		 private $modeloHistoriaClinica = null;
		 private $lNegocioImpresionDiagnostica = null;
		 private $modeloImpresionDiagnostica = null;
		 private $lNegocioCie = null;
		 private $modeloCie = null;
		 private $lNegocioReportePdf = null;
		 private $modeloReportePdf = null;
		 private $accion = null;
		 private $lNegocioAdjuntosCertificado = null;
		 private $modeloAdjuntosCertificado = null;
		 private $lNegocioRecomendaciones = null;
		 private $modeloRecomendaciones = null;
		 
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCertificadoMedico = new CertificadoMedicoLogicaNegocio();
		 $this->modeloCertificadoMedico = new CertificadoMedicoModelo();
		 $this->lNegocioHistoriaClinica = new HistoriaClinicaLogicaNegocio();
		 $this->modeloHistoriaClinica = new HistoriaClinicaModelo();
		 $this->lNegocioImpresionDiagnostica = new ImpresionDiagnosticaLogicaNegocio();
		 $this->modeloImpresionDiagnostica = new ImpresionDiagnosticaModelo();
		 $this->lNegocioCie = new CieLogicaNegocio();
		 $this->modeloCie = new CieModelo();
		 $this->lNegocioReportePdf = new ReportesPdfLogicaNegocio();
		 $this->modeloReportePdf = new ReportesPdfModelo();
		 $this->lNegocioAdjuntosCertificado = new AdjuntosCertificadoMedicoLogicaNegocio();
		 $this->modeloAdjuntosCertificado =new AdjuntosCertificadoMedicoModelo();
		 $this->lNegocioRecomendaciones = new RecomendacionesLogicaNegocio();
		 $this->modeloRecomendaciones = new RecomendacionesModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $this->perfilUsuario();
		 if($this->perfilUsuario == 'PFL_MEDICO'){
		     $modeloCertificadoMedico = $this->lNegocioCertificadoMedico->buscarCertificadoMedico();
		     $this->filtroHistorias();
		     $this->tablaHtmlCertificadoMedico($modeloCertificadoMedico);
		     require APP . 'HistoriasClinicas/vistas/listaCertificadoMedicoVista.php';
		 }else{
		     $consul = $this->lNegocioHistoriaClinica->buscarLista("identificador_paciente='".$_SESSION['usuario']."'");
		     if($consul->count()){
		         $modeloCertificadoMedico = $this->lNegocioCertificadoMedico->buscarLista("id_historia_clinica=".$consul->current()->id_historia_clinica);
		     }else{
		         $modeloCertificadoMedico = array();
		     }
		     $this->filtroHistorias();
		     $this->tablaHtmlCertificadoMedicoPaciente($modeloCertificadoMedico);
		     require APP . 'HistoriasClinicas/vistas/listaCertificadoMedicoPacienteVista.php';
		 }
		
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Certificados e Informes"; 
		 require APP . 'HistoriasClinicas/vistas/formularioCertificadoMedicoVista.php';
		}	/**
		* Método para registrar en la base de datos -CertificadoMedico
		*/
		public function guardar()
		{
		  $this->lNegocioCertificadoMedico->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CertificadoMedico
		*/
		public function editar()
		{
		 $this->perfilUsuario();
		 $this->accion = "Certificados e Informes"; 
		 $this->modeloCertificadoMedico = $this->lNegocioCertificadoMedico->buscar($_POST["id"]);
		 $arrayparametros = array(
		     "identificador" => $this->modeloCertificadoMedico->getIdentificadorMedico());
		 $resultFirma = $this->lNegocioHistoriaClinica->obtenerDatosFirma($arrayparametros);
		 $this->divInformacionFirma($resultFirma->current());
		 $historia = $this->lNegocioHistoriaClinica->buscar($this->modeloCertificadoMedico->getIdHistoriaClinica());
		 $arrayparametros = array(
		     "identificador_paciente" => $historia->getIdentificadorPaciente());
		 $resultado = $this->lNegocioHistoriaClinica->buscarInformacionPaciente($arrayparametros);
		 $puesto = $this->lNegocioHistoriaClinica->obtenerDatosContrato($arrayparametros);
		 $adjunto = $this->lNegocioAdjuntosCertificado->buscarLista("id_certificado_medico=".$this->modeloCertificadoMedico->getIdCertificadoMedico()." ");
		 $npuesto=$fechaInicial=$rutaAdjunto=$idAdjunto=$estadoAdj='';
		 if($puesto->count()){
		     $npuesto=$puesto->current()->nombre_puesto;
		     $fechaInicial = $puesto->current()->fecha_inicial;
		 }
		 if($adjunto->count()){
		     $rutaAdjunto=$adjunto->current()->archivo_adjunto;
		     $idAdjunto = $adjunto->current()->id_adjuntos_certificado_medico;
		     $estadoAdj = $adjunto->current()->estado;
		 }
		     $arrayPaciente = array(
		         'fecha' => $this->modeloCertificadoMedico->getFechaCertificado(),
		         'identificador' => $resultado->current()->identificador,
		         'funcionario' => $resultado->current()->funcionario,
		         'genero' => $resultado->current()->genero,
		         'edad' => $resultado->current()->edad,
		         'lateralidad' => '',
		         'nombre_puesto' =>$npuesto,
		         'tipo_restriccion_limitacion' =>$historia->getTipoRestriccionLimitacion(),
		         'descripcion_concepto'=>$historia->getDescripcionConcepto(),
		         'id_historia_clinica' => $historia->getIdHistoriaClinica(),
		         'encabezado' => $this->modeloCertificadoMedico->getDescripcionCertificado(),
		         'fecha_inicial' => $fechaInicial,
		         'descripcion_certificado' => $this->modeloCertificadoMedico->getDescripcionCertificado(),
		         'fecha_salida' => $this->modeloCertificadoMedico->getFechaSalida(),
		         'observacion' => $this->modeloCertificadoMedico->getObservaciones(),
		         'analisis' => $this->modeloCertificadoMedico->getAnalisis(),
		         'recomendaciones' => $this->modeloCertificadoMedico->getRecomendaciones(),
		         'estado' => $estadoAdj,
		         'ruta_archivo' =>$rutaAdjunto
		         
		     );
		  $this->paciente = $this->crearHtmlConsulta($arrayPaciente);
		  $this->adjunto= $this->crearHtmlAdjunto($arrayPaciente);
		  $this->perfilUsuario();
		  if($this->perfilUsuario == 'PFL_MEDICO'){
		      require APP . 'HistoriasClinicas/vistas/formularioCertificadoMedicoConsultaVista.php';
		  }else{
		      $this->rutaArchivo = $rutaAdjunto;
		      $this->idAdjunto = $idAdjunto;
		      $this->estado =$estadoAdj;
		      require APP . 'HistoriasClinicas/vistas/formularioCertificadoMedicoPacienteVista.php';
		  }
		}	/**
		* Método para borrar un registro en la base de datos - CertificadoMedico
		*/
		public function borrar()
		{
		  $this->lNegocioCertificadoMedico->borrar($_POST['elementos']);
		}	
		  
		/**
		* Construye el código HTML para desplegar la lista de - CertificadoMedico
		*/
	public function tablaHtmlCertificadoMedico($tabla) 
		{
		  foreach ($tabla as $fila) {
		   $historia = $this->lNegocioHistoriaClinica->buscar($fila->id_historia_clinica);
		   $arrayParametros = array(
		       'identificador' =>$historia->getIdentificadorPaciente());
		   $resultConsulta = $this->lNegocioHistoriaClinica->obtenerDatosFirma($arrayParametros);
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_certificado_medico'] . '"
		    class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'HistoriasClinicas\certificadoMedico"
		    data-opcion="editar" ondragstart="drag(event)" draggable="true"
		    data-destino="detalleItem">
		    <td style="white - space:nowrap; ">' . $fila['fecha_certificado'] . '</td>
            <td>' . $fila['descripcion_certificado'] . '</td>
            <td>' . $historia->getIdentificadorPaciente()  . '</td>
            <td>' . $resultConsulta->current()->funcionario . '</td>
            </tr>');
		}
	}
	/**
	 * Construye el código HTML para desplegar la lista de - CertificadoMedico
	 */
	public function tablaHtmlCertificadoMedicoPaciente($tabla) {
	    {
	        foreach ($tabla as $fila) {
	        $this->itemsFiltrados[] = array(
	                '<tr id="' . $fila['id_certificado_medico'] . '"
		    class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'HistoriasClinicas\certificadoMedico"
		    data-opcion="editar" ondragstart="drag(event)" draggable="true"
		    data-destino="detalleItem">
		    <td style="white - space:nowrap; ">' . $fila['fecha_certificado'] . '</td>
            <td>' . $fila['descripcion_certificado'] . '</td>
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
	    $firma = '';
	    $validarInfo = $this->lNegocioHistoriaClinica->buscarLista("identificador_paciente='".$_POST['identificador']."' and estado='Registrado'");
	    if($validarInfo->count()>0){
        	        $arrayparametros = array(
        	            "identificador_paciente" => $_POST['identificador']);
        	        $resultado = $this->lNegocioHistoriaClinica->buscarInformacionPaciente($arrayparametros);
        	        $puesto = $this->lNegocioHistoriaClinica->obtenerDatosContrato($arrayparametros);
        	        $npuesto=$fechaInicial='';
        	        if($puesto->count()){
        	            $npuesto=$puesto->current()->nombre_puesto;
        	            $fechaInicial = $puesto->current()->fecha_inicial;
        	        }
            	    if($resultado->count()>0){
            	        $arrayPaciente = array(
            	            'fecha' => date('Y-m-d'),
            	            'identificador' => $resultado->current()->identificador,
            	            'funcionario' => $resultado->current()->funcionario,
            	            'genero' => $resultado->current()->genero,
            	            'edad' => $resultado->current()->edad,
            	            'lateralidad' => '',
            	            'nombre_puesto' =>$npuesto,
            	            'tipo_restriccion_limitacion' =>$validarInfo->current()->tipo_restriccion_limitacion,
            	            'descripcion_concepto'=>$validarInfo->current()->descripcion_concepto,
            	            'id_historia_clinica' => $validarInfo->current()->id_historia_clinica,
            	            'encabezado' => $_POST['descripcion_certificado'],
            	            'fecha_inicial' => $fechaInicial,
            	            'descripcion_certificado' => $_POST['descripcion_certificado']
            	        );
            	        $paciente = $this->crearHtmlConsulta($arrayPaciente);
            	        $arrayparametros = array(
            	            "identificador" => $_SESSION['usuario']);
            	        $resultFirma = $this->lNegocioHistoriaClinica->obtenerDatosFirma($arrayparametros);
            	        if($resultFirma->count()){
            	            $this->divInformacionFirma($resultFirma->current());
            	            $firma=$this->firma;
            	        }else{
            	            $this->divInformacionFirma('');
            	        }
            	    }else{
            	        $estado = 'ERROR';
            	        $mensaje = 'Funcionario inactivo en el sistema !!';
            	        
            	    }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'No ha creado la historia clínica !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'paciente' => $paciente,
	        'firma' => $firma
	    ));
	}
	
	public function crearHtmlConsulta($tabla){
	    $html = '
        <legend>'.$tabla['encabezado'].'</legend>				
		<div data-linea="1">
			<label for="fecha">Fecha: </label>
			<span>'.$tabla['fecha'].'</span>
		</div>	
		<div data-linea="1">
			<label for="identificador">Doc. Identidad: </label>
			<span>'.$tabla['identificador'].'</span>
		</div>	
		<div data-linea="2">
			<label for="funcionario">Nombres y Apellidos: </label>
			<span>'.$tabla['funcionario'].'</span>
		</div>
		<div data-linea="3">
			<label for="edad">Edad(años): </label>
			<span>'.$tabla['edad'].'</span>
		</div>
		<div data-linea="3">
			<label for="genero">Género: </label>
			<span>'.$tabla['genero'].'</span>
		</div>
		<div data-linea="3">
			<label for="lateralidad">Lateralidad: </label>
			<span>'.$tabla['lateralidad'].'</span>
		</div>
		<div data-linea="4">
			<label for="nombre_puesto">Cargo que ocupa: </label>
			<span>'.$tabla['nombre_puesto'].'</span>
		</div>';
	    if($tabla['descripcion_certificado'] != 'Informe Médico'){
	    if($tabla['descripcion_certificado'] == 'Certificado de Egreso'){
	    $html .='
        <div data-linea="5">
			<label for="fecha_ingreso">Fecha ingreso: </label>
			<span>'.$tabla['fecha_inicial'].'</span>
		</div>
		<div data-linea="5">
			<label for="fecha_salida">Fecha salida: </label>';
	    if(isset($tabla['fecha_salida'])){
	    $html .='
			<span>'.$tabla['fecha_salida'].'</span>';
	    }else{
	        $html .='
			<input type="text" id="fecha_salida" name="fecha_salida" value=""
			placeholder="Fecha de salida"  readonly />';
	    }
	    $html .='
		</div>
        ';}
	    $html .='
		<br><hr>
		<label>Epicrisis</label>';
	    $html .= $this->listarDiagnostico($tabla['id_historia_clinica']);
	    $html .='
		<hr><div data-linea="8">
			<label for=tipo_restriccion_limitacion>Tipo de restricciones o limitaciones: </label>
			<span>'.$tabla['tipo_restriccion_limitacion'].'</span>
		</div><br>';
	    switch ($tabla['descripcion_concepto']) {
	        case 'Apto':
	            $html .= '
    		<div data-linea="9">
    			<input type="radio" checked name="descripcion_concepto[]" value="Apto" disabled/>
    			  <label for="descripcion_concepto">Apto </label>
    		</div>
    	                
    		<div data-linea="9">
    			<input type="radio"  name="descripcion_concepto[]" value="Apto condicionado" disabled/>
    			<label for="descripcion_concepto">Apto condicionado</label>
    		</div>
    		<div data-linea="9">
    			<input type="radio"  name="descripcion_concepto[]" value="No apto" disabled/>
    			<label for="descripcion_concepto">No apto</label>
    		</div>';
	        break;
	        case 'Apto condicionado':
	            $html .= '
    		<div data-linea="9">
    			<input type="radio" name="descripcion_concepto[]" value="Apto" disabled/>
    			  <label for="descripcion_concepto">Apto </label>
    		</div>
	                
    		<div data-linea="9">
    			<input type="radio" checked name="descripcion_concepto[]" value="Apto condicionado" disabled/>
    			<label for="descripcion_concepto">Apto condicionado</label>
    		</div>
    		<div data-linea="9">
    			<input type="radio"  name="descripcion_concepto[]" value="No apto" disabled/>
    			<label for="descripcion_concepto">No apto</label>
    		</div>';
	            break;
	        case 'No apto':
	            $html .= '
    		<div data-linea="9">
    			<input type="radio" name="descripcion_concepto[]" value="Apto" disabled/>
    			  <label for="descripcion_concepto">Apto </label>
    		</div>
	                
    		<div data-linea="9">
    			<input type="radio" name="descripcion_concepto[]" value="Apto condicionado" disabled/>
    			<label for="descripcion_concepto">Apto condicionado</label>
    		</div>
    		<div data-linea="9">
    			<input type="radio"  checked name="descripcion_concepto[]" value="No apto" disabled/>
    			<label for="descripcion_concepto">No apto</label>
    		</div>';
	            break;
	        default:
	            $html .= '
    		<div data-linea="9">
    			<input type="radio" name="descripcion_concepto[]" value="Apto" disabled/>
    			  <label for="descripcion_concepto">Apto </label>
    		</div>
    		<div data-linea="9">
    			<input type="radio"  name="descripcion_concepto[]" value="Apto condicionado" disabled />
    			<label for="descripcion_concepto">Apto condicionado</label>
    		</div>
    		<div data-linea="9">
    			<input type="radio" name="descripcion_concepto[]" value="No apto" disabled/>
    			<label for="descripcion_concepto">No apto</label>
    		</div>';
	        break;
	    }
	    if(isset($tabla['observacion'])){
	        $html .= '<br><hr>
    		<div data-linea="10">
    			<label for="observaciones">Observaciones:</label>
    		</div>
           <div data-linea="11">
    			<span>'.$tabla['observacion'].'</span>
    		</div>';
	    }else{
    	    $html .= '<br><hr>
    		<div data-linea="10">
    			<label for="observaciones">Observaciones:</label>
    		</div>	
           <div data-linea="11">
    			<input type="text" id="observaciones" name="observaciones" value=""
    			placeholder="observaciones"  maxlength="1024" />
    		</div>';
	       }
	    }else{
	        
	        if(isset($tabla['analisis'])){
	        $html .= '<br><hr>
            		<div data-linea="10">
            			<label for="analisis">Análisis:</label>
            		</div>
                   <div data-linea="11">
                        <span>'.$tabla['analisis'].'</span>
            		</div>
                    <hr>
                    <div data-linea="12">
            			<label for="recomendaciones">Recomendaciones:</label>
            		</div>
                   <div data-linea="13">
                         <span>'.$tabla['recomendaciones'].'</span>
            		</div>';
	        }else{
        	$html .= '<br><hr>
        		<div data-linea="10">
        			<label for="analisis">Análisis:</label>
        		</div>
               <div data-linea="11">
                    <textarea id="analisis" name="analisis" maxlength="1024" placeholder="Análisis" rows="6"></textarea>
        		</div>
                <hr>
                <div data-linea="12">
        			<label for="recomendaciones">Recomendaciones:</label>
        		</div>
               <div data-linea="13">
                     <textarea id="recomendaciones" name="recomendaciones" maxlength="1024" placeholder="recomendaciones" rows="6"></textarea>
        		</div>';
	        }
	    }
	    return $html;
	}
	
	/****
	 *  crear html adjunto
	 */
	
	public function crearHtmlAdjunto($param) {
	    $html = ' <legend>Notificación</legend>';
	    if($param['ruta_archivo'] != ''){
        	    if($param['estado'] != 'Registrado'){
            	   $html .= '
            		<div data-linea="1">
            			<span><strong>Nota:</strong> El documento no está disponible para descarga</span>
            		</div>';
        	    }else{
        	        $html .= '
            		<div data-linea="1">
                        <a href="'.$param['ruta_archivo'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">'.$param['descripcion_certificado'].' firmado</a>
            		</div>';
        	    }
	    }
	  return $html;
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
	 * listar examenes paraclinicos
	 */
	public function listarDiagnostico($idHistoriaClinica=null){
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
	                $datos .= '<tr>';
	            }
	            $html = '
				<table style="width:100%">
					<thead><tr>
						<th>Enfermedad general</th>
						<th>Estado</th>
                        <th>Observaciones</th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	        }
	    }
	    return $html;
	}
	
	//*************************guardar certificado medico*********************************
	public function guardarRegistros(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $validarInfo = $this->lNegocioHistoriaClinica->buscarLista("identificador_paciente='".$_POST['identificador']."' and estado='Registrado'");
	    if($validarInfo->count()>0){
	        
	        $arrayparametros = array(
	            "identificador_paciente" => $_POST['identificador']);
	        $resultado = $this->lNegocioHistoriaClinica->buscarInformacionPaciente($arrayparametros);
	        $puesto = $this->lNegocioHistoriaClinica->obtenerDatosContrato($arrayparametros);
	        $arrayparametros = array(
	            "identificador" => $_SESSION['usuario']);
	        $resultFirma = $this->lNegocioHistoriaClinica->obtenerDatosFirma($arrayparametros);
	        $recomendaciones = $this->lNegocioRecomendaciones->buscarLista("id_historia_clinica=".$validarInfo->current()->id_historia_clinica);
	        $consultaImpre = $this->lNegocioImpresionDiagnostica->buscarLista("id_historia_clinica =".$validarInfo->current()->id_historia_clinica." order by 1 ");
	        $arrayImpresion = array();
	        if($consultaImpre->count()){
	            foreach ($consultaImpre as $value) {
	                $valor = $this->lNegocioCie->buscar($value->id_cie);
	            $arrayImpresion []=array($valor->getDescripcion(),$value->estado_diagnostico,$value->observaciones);
	            }
	        }
	        $nombre=$titulo='';
	        if($_POST['descripcion_certificado'] == 'Certificado de Aptitud de Ingreso'){
	            $nombre = 'certificado_aptitud_ingreso_';
	            $titulo = $_POST['descripcion_certificado'];
	        }else if($_POST['descripcion_certificado'] == 'Certificado de Aptitud de Egreso'){
	            $nombre = 'certificado_aptitud_egreso_';
	            $titulo = $_POST['descripcion_certificado'];
	        }else{
	            $nombre = 'certificado_informe_medico_';
	            $titulo = 'INFORME MÉDICO';
	        }
	        
	        $arraySecuencial = array(
	            'descripcion_certificado' => $_POST['descripcion_certificado']);
	        $secuencial = $this->lNegocioCertificadoMedico->obtenerSecuencialCertificado($arraySecuencial);
	        $secuencialCertificado = str_pad($secuencial->current()->numero+1, 7, "0", STR_PAD_LEFT);
	        $codiFicacion = $titulo.' Nº AGR/DARH - '.date('Y').' - '.$secuencialCertificado;
	        
	        $secuencialUrl = date('Ymd').'_'.$secuencialCertificado;
	        
	        $rutaCarpeta = HIST_CLI_URL."adjuntosCertificadosMedicos/".$_POST['identificador'];
	                if (!file_exists('../../' . $rutaCarpeta)) {
	                    mkdir('../../' .$rutaCarpeta, 0777, true);
	                }
	                $rutaArchivo = "adjuntosCertificadosMedicos/".$_POST['identificador']."/".$nombre.$_POST['identificador']."_".$secuencialUrl;
	                
	        $arrayParametros = array(
	            'rutaArchivo' => $rutaArchivo,
	            'titulo' => $codiFicacion,
	            'descripcion_certificado' => $_POST['descripcion_certificado'],
	            'funcionario' => $resultado->current()->funcionario,
	            'genero' => $resultado->current()->genero,
	            'edad' => $resultado->current()->edad,
	            'fecha_inicial' => $puesto->current()->fecha_inicial,
	            'fecha_salida' => (isset($_POST['fecha_salida']))? $_POST['fecha_salida']:null ,
	            'analisis' => (isset($_POST['analisis']))? rtrim($_POST['analisis']):null ,
	            'recomendaciones' => (isset($_POST['recomendaciones']))? rtrim($_POST['recomendaciones']):null ,
	            'lateralidad' => $resultado->current()->lateralidad,
	            'nombre_puesto' =>$puesto->current()->nombre_puesto,
	            'tipo_restriccion_limitacion' =>$validarInfo->current()->tipo_restriccion_limitacion,
	            'descripcion_concepto'=>$validarInfo->current()->descripcion_concepto,
	            'identificador' => $_POST['identificador'],
	            'recomendacion' => $recomendaciones->current()->descripcion,
	            'observacion' => (isset($_POST['observaciones']))?$_POST['observaciones']:null,
	            'fecha' => date('Y-m-d'),
	            'nombre_medico' => $resultFirma->current()->funcionario,
	            'cargo_medico' => $resultFirma->current()->cargo,
	            'identificador_medico' => $resultFirma->current()->identificador,
	            'epicrisis' => $arrayImpresion
	            
	        );
	        
	       $res = $this->lNegocioReportePdf->generarCertificado($arrayParametros);
	        
//             $jasper = new JasperReport();
//             $datosReporte = array();
            
//             $rutaArchivoBase = 'HistoriasClinicas/archivos/';
//             $datosReporte = array(
//                 'rutaReporte' => 'HistoriasClinicas/vistas/reportes/certificadoMedico.jasper',
//                 'rutaSalidaReporte' => $rutaArchivoBase.$rutaArchivo,
//                 'tipoSalidaReporte' => array('pdf'),
//                 'parametrosReporte' => array(
//                     'id_historia_clinica' => (integer) $validarInfo->current()->id_historia_clinica,
//                     'identificador' => $_POST['identificador'],
//                     'fecha' => date('Y-m-d'),
//                     'paciente' => $resultado->current()->funcionario,
//                     'edad' => $resultado->current()->edad,
//                     'genero' => $resultado->current()->genero,
//                     'cargo' => $resultFirma->current()->cargo,
//                     'lateridad' => $resultado->current()->lateralidad,
//                     'titulo' => $codiFicacion,
//                     'restriccion' => $validarInfo->current()->tipo_restriccion_limitacion,
//                     'recomendacion' => $recomendaciones->current()->descripcion,
//                     'observacion' => (isset($_POST['observaciones']))?$_POST['observaciones']:null,
//                     'nombreMedico' => $resultFirma->current()->funcionario,
//                     'identificadorMedico' => $resultFirma->current()->identificador,
//                     'cargoMedico' => $resultFirma->current()->cargo,
//                     'apto' => '',
//                     'aptoCondicionado' => '',
//                     'noApto' => '',
//                     'fondoCertificado' => RUTA_IMG_GENE.'fondoCertificado.png'),
//                 'conexionBase' => 'SI'
//             );
            
//             echo '<pre>';
//             var_dump($datosReporte);
//             echo '</pre>';
            
//             $jasper->generarArchivo($datosReporte);
	       if($res){
 	          $arrayParametros = array(
 	                'id_historia_clinica' => $validarInfo->current()->id_historia_clinica,
 	                'identificador_medico' => $_SESSION['usuario'],
 	                'descripcion_certificado' => $_POST['descripcion_certificado'],
 	                'fecha_certificado' => date('Y-m-d'),
 	                'analisis' => (isset($_POST['analisis']))? rtrim($_POST['analisis']):null ,
 	                'recomendaciones' => (isset($_POST['recomendaciones']))? rtrim($_POST['recomendaciones']):null,
 	                'observaciones' => (isset($_POST['observaciones']))?$_POST['observaciones']:null,
 	                'archivo_adjunto' => HIST_CLI_URL.$rutaArchivo,
 	                'identificador_paciente' => $_POST['identificador'],
 	                'mail_personal' => $resultado->current()->mail_personal,
 	                'mail_institucional' => $resultado->current()->mail_institucional,
 	                'fecha_salida' => (isset($_POST['fecha_salida']))? $_POST['fecha_salida']:NULL ,
	                
 	            );
	            
 	            $verifi = $this->lNegocioCertificadoMedico->guardarAdjunto($arrayParametros);
 	            if($verifi){
 	                  $contenido = HIST_CLI_URL.$rutaArchivo;
 	            }else{
 	                $estado = 'ERROR';
 	                $mensaje = 'Error al guardar el certificado';
 	            }
	       }else{
	           $estado = 'ERROR';
	           $mensaje = 'Error al crear el certificado';
	       }
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'No ha creado la historia clínica !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	
	public function filtrarInformacion(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $modeloCertificadoMedico = array();
	    if(isset($_POST['tipo'])){
	        if($_POST['tipo'] == 'ci' || $_POST['tipo'] == 'pasaporte'){
	            $arrayParametros = array('identificador_paciente' => $_POST['identificadorFiltro']);
	            $modeloCertificadoMedico = $this->lNegocioCertificadoMedico->buscarFuncionario($arrayParametros);
	        }else {
	            $arrayParametros = array('apellido' => $_POST['identificadorFiltro']);
	            $modeloCertificadoMedico = $this->lNegocioCertificadoMedico->buscarFuncionario($arrayParametros);
	        }
	        if($modeloCertificadoMedico->count()==0){
	            $estado = 'FALLO';
	            $mensaje = 'No existe el paciente buscado..!!';
	        }
	        $this->tablaHtmlCertificadoMedico($modeloCertificadoMedico);
	        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido));
	}
	
	//**************************************
	/**
	 * guardar archivoadjunto
	 *
	 * */
	public function actualizarDocumentosAdjuntos()
	{
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(!empty($_REQUEST['id_adjuntos_certificado_medico']) && $_REQUEST['id_adjuntos_certificado_medico'] != 'null'){
	        try {
	            
	            $identificador = $this->lNegocioHistoriaClinica->buscar($_REQUEST['id_historia_clinica']);
	            $nombre_archivo = $_FILES['archivo']['name'];
	            $tipo_archivo = $_FILES['archivo']['type'];
	            $tamano_archivo = $_FILES['archivo']['size'];
	            $tmpArchivo = $_FILES['archivo']['tmp_name'];
	            $rutaCarpeta = HIST_CLI_URL."adjuntosCertificadosMedicos/".$identificador->getIdentificadorPaciente();
	            $extension = explode(".", $nombre_archivo);
	            if ($tamano_archivo != '0' ) {
	                if (strtoupper(end($extension)) == 'PDF' && $tipo_archivo == 'application/pdf') {
	                    if (!file_exists('../../' . $rutaCarpeta)) {
	                        mkdir('../../' .$rutaCarpeta, 0777, true);
	                    }
	                    $nombre='';
	                    if($_REQUEST['descripcion_certificado'] == 'Certificado de Ingreso'){
	                        $nombre = 'certificado_ingreso_';
	                    }else if($_REQUEST['descripcion_certificado'] == 'Certificado de Egreso'){
	                        $nombre = 'certificado_egreso_';
	                    }else{
	                        $nombre = 'certificado_informe_medico_';
	                    }
	                    $secuencial = date('Ymds').mt_rand(100,999);
	                    $rutaArchivo = $nombre.$identificador->getIdentificadorPaciente().'_'.$secuencial.'.' . end($extension);
	                   
	                    $ruta = $rutaCarpeta . '/' . $rutaArchivo;
	                    move_uploaded_file($tmpArchivo, '../../' . $ruta);
	                    $arrayAdjunto = array(
	                        'id_adjuntos_certificado_medico' =>$_REQUEST['id_adjuntos_certificado_medico'],
	                        'archivo_adjunto' => $ruta,
	                        'estado' => 'Ingresado'
	                    );
	                    $id = $this->lNegocioAdjuntosCertificado->guardar($arrayAdjunto);
	                    
	                    if($id){
	                        $mensaje = 'Registro agregado correctamente';
	                        $contenido = $this->listarAdjuntosCertificado($_REQUEST['id_certificado_medico']);;
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
	        $mensaje = 'No existe docuemento adjunto en base para actualizar !!';
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
	public function listarAdjuntosCertificado($idCertificadoMedico=null){
	    $html='';
	    if($idCertificadoMedico != null){
	        
	        $certifi = $this->lNegocioCertificadoMedico->buscar($idCertificadoMedico);
	        $consulta = $this->lNegocioAdjuntosCertificado->buscarLista("id_certificado_medico =".$idCertificadoMedico." and estado in ('Ingresado','Registrado') order by 1 ");
	        if($consulta->count()){
	            $count=0;
	            foreach ($consulta as $item) {
	                $html .= '
                    <div data-linea = "'.++$count.'">
	                <label>'.$certifi->getDescripcionCertificado().': </label>
	                <a href="'.$item->archivo_adjunto.'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>
		            </div><br>';
	            }
	        }
	    }
	    return $html;
	}
	
	/**
	 * funcion para aceptar certificado
	 */
	public function aceptarCertificado() {
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id_adjuntos_certificado_medico'])){
	        $verif = $this->lNegocioAdjuntosCertificado->buscarLista("id_adjuntos_certificado_medico=".$_POST['id_adjuntos_certificado_medico']." and estado='Ingresado'");
	        if($verif->count()){
	            $arrayAdjunto = array(
	                'id_adjuntos_certificado_medico' =>$_POST['id_adjuntos_certificado_medico'],
	                'estado' => 'Registrado'
	            );
	            $id = $this->lNegocioAdjuntosCertificado->guardar($arrayAdjunto);
	            if($id){
	                 $mensaje = 'Registro actualizado correctamente';
	            }else{
	                $estado = 'FALLO';
	                $mensaje = 'Error al guardar el registro..!!';
	            }
	        }else{
	            $estado = 'FALLO';
	            $mensaje = 'No ha cargado ningun adjunto..!!';
	        }
	        
	    }else{
	        $estado = 'FALLO';
	        $mensaje = 'No existe un archivo adjunto previo..!!';
	    }
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
}

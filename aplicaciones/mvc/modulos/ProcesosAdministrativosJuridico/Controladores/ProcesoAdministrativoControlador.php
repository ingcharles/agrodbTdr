<?php
 /**
 * Controlador ProcesoAdministrativo
 *
 * Este archivo controla la lógica del negocio del modelo:  ProcesoAdministrativoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-03-17
 * @uses    ProcesoAdministrativoControlador
 * @package ProcesosAdministrativosJuridico
 * @subpackage Controladores
 */
 namespace Agrodb\ProcesosAdministrativosJuridico\Controladores;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\ProcesoAdministrativoLogicaNegocio;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\ProcesoAdministrativoModelo;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\TecnicoProvinciaLogicaNegocio;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\TecnicoProvinciaModelo;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\DetalleTecnicoProvinciaLogicaNegocio;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\DetalleTecnicoProvinciaModelo;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\ModeloAdministrativoLogicaNegocio;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\ModeloAdministrativoModelo;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\TipoDocumentoLogicaNegocio;
 use Agrodb\ProcesosAdministrativosJuridico\Modelos\TipoDocumentoModelo;
 
 
 
class ProcesoAdministrativoControlador extends BaseControlador 
{

		 private $lNegocioProcesoAdministrativo = null;
		 private $modeloProcesoAdministrativo = null;
		 private $lNegocioTecnicoProvincia = null;
		 private $modeloTecnicoProvincia = null;
		 private $lNegocioDetalleTecnicoProvincia = null;
		 private $modeloDetalleTecnicoProvincia = null;
		 private $lNegocioModeloAdministrativo = null;
		 private $modeloModeloAdministrativo = null;
		 private $lNegocioTipoDocumento = null;
		 private $modeloTipoDocumento = null;
		 private $accion = null;
		 private $tecnicoProvincia =null;
		 private $opcion = null;
		 private $listaDetalleDocumento = null;
		 private $rutaArchivo = null;
		 private $opcionMenu = null;
		 private $consultaAnexo = null;
		 private $consultaActos = null;
		 private $funcionario = null;
		 private $url = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioProcesoAdministrativo = new ProcesoAdministrativoLogicaNegocio();
		 $this->modeloProcesoAdministrativo = new ProcesoAdministrativoModelo();
		 $this->lNegocioTecnicoProvincia = new TecnicoProvinciaLogicaNegocio();
		 $this->modeloTecnicoProvincia = new TecnicoProvinciaModelo();
		 $this->lNegocioDetalleTecnicoProvincia = new DetalleTecnicoProvinciaLogicaNegocio();
		 $this->modeloDetalleTecnicoProvincia = new DetalleTecnicoProvinciaModelo();
		 $this->lNegocioModeloAdministrativo = new ModeloAdministrativoLogicaNegocio();
		 $this->modeloModeloAdministrativo = new ModeloAdministrativoModelo();
		 $this->lNegocioTipoDocumento = new TipoDocumentoLogicaNegocio();
		 $this->modeloTipoDocumento = new TipoDocumentoModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloProcesoAdministrativo = $this->lNegocioProcesoAdministrativo->buscarProcesoAdministrativo();
		 $this->tablaHtmlProcesoAdministrativo($modeloProcesoAdministrativo);
		 require APP . 'ProcesosAdministrativosJuridico/vistas/listaProcesoAdministrativoVista.php';
		}
		/**
		* Método de inicio del controlador
		*/
		public function acto()
		{
		 $this->filtroActos();
		 $modeloProcesoAdministrativo = $this->lNegocioProcesoAdministrativo->buscarLista("estado not in ('eliminado') order by 1 desc");
		 $this->tablaHtmlProcesoAdministrativo($modeloProcesoAdministrativo);
		 require APP . 'ProcesosAdministrativosJuridico/vistas/listaActoAdministrativoVista.php';
		}
	 //****************************filtra informacion segun parametros************
       public function filtrarInformacion(){
           $estado = 'EXITO';
           $mensaje = '';
           $contenido = '';
           $modeloSolicitudInspeccion = array();
           if(isset($_POST['provincia'])){
               $arrayParametros = array('numero_proceso'=>$_POST['numero_proceso'],'area_tecnica' => $_POST['area_tecnica'],'fecha_creacion' =>$_POST['fecha_creacion'], 'provincia' => $_POST['provincia']);
               $resultado=$this->lNegocioProcesoAdministrativo->obtenerProcesosAdministrativos($arrayParametros);
               if($resultado->count()){
                   $this->tablaHtmlProcesoAdministrativo($resultado);
                   $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
                   $mensaje='ok';
               }else{
                   $estado = 'ERROR';
                   $mensaje="No existen registros para la busqueda realizada..!!";
               }
           }
           $modeloSolicitudInspeccion = array();
           $this->tablaHtmlProcesoAdministrativo($modeloSolicitudInspeccion);
           $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
           
           echo json_encode(array(
               'estado' => $estado,
               'mensaje' => $mensaje,
               'contenido' => $contenido));
       }
		 /**
		* Método de inicio del controlador
		*/
		public function consultaProceso()
		{
		 $this->filtroConsulta();
		 require APP . 'ProcesosAdministrativosJuridico/vistas/listaConsultaProcesoVista.php';
		}
		/**
		 *Obtenemos los datos del registro seleccionado para editar - Tabla: ProcesoAdministrativo
		 */
		public function consulta()
		{
		    $this->accion = "Solicitud de Proceso Administrativo";
		    $arrayParametros = array('id_proceso_administrativo' => $_POST["id"],'orden' => 9);
		    $this->consultaAnexo = $this->consultaDocumentoAnexo($arrayParametros,'si');
		    $this->consultaActos = $this->consultaListaTipoDocumento($_POST["id"]);
		    $this->modeloProcesoAdministrativo = $this->lNegocioProcesoAdministrativo->buscar($_POST["id"]);
		    $consulta = $this->lNegocioProcesoAdministrativo->obtenerNombreApellido($this->modeloProcesoAdministrativo->getIdentificadorRegistro());
		    if($consulta->count() > 0){
		        $this->funcionario =$consulta->current()->funcionario;
		    }
		     
		    require APP . 'ProcesosAdministrativosJuridico/vistas/formularioConsultaProcesoAdministrativoVista.php';
		}
		
		public function formReporteDocumento(){
		    $this->accion = "Solicitud de Proceso Administrativo";
		    $arrayParametros = array('id_proceso_administrativo' => $_POST["id"],'orden'=>9);
		    $this->consultaAnexo = $this->consultaDocumentoAnexo($arrayParametros,'si');
		    $this->consultaActos = $this->reporteListaTipoDocumento($_POST["id"]);
		    $this->modeloProcesoAdministrativo = $this->lNegocioProcesoAdministrativo->buscar($_POST["id"]);
		    $consulta = $this->lNegocioProcesoAdministrativo->obtenerNombreApellido($this->modeloProcesoAdministrativo->getIdentificadorRegistro());
		    if($consulta->count() > 0){
		        $this->funcionario =$consulta->current()->funcionario;
		    }
		    
		    require APP . 'ProcesosAdministrativosJuridico/vistas/formularioConsultaProcesoAdministrativoVista.php';
		}
		 /**
		* Método de inicio del controlador
		*/
		public function reporte()
		{
		 require APP . 'ProcesosAdministrativosJuridico/vistas/listaReporteVista.php';
		}/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo Acto Administrativo"; 
		 $this->opcion = 'nuevo';
		 require APP . 'ProcesosAdministrativosJuridico/vistas/formularioProcesoAdministrativoVista.php';
		}	/**
		* Método para registrar en la base de datos -ProcesoAdministrativo
		*/
		public function guardar()
		{
		    $prov = explode('-', $_POST['provincia']);
		    $areaTecnica = explode('-', $_POST['area_tecnica']);
		    $codigoProvincia = substr($prov[0],1,2);
		    $arraySecuencial = array('provincia'=> $prov[1]);
		    $secuencial = $this->lNegocioProcesoAdministrativo->obtenerSecuencialProceso($arraySecuencial);
		    $secuencialProceso = str_pad($secuencial->current()->numero, 3, "0", STR_PAD_LEFT);
		    $inicial = $this->lNegocioProcesoAdministrativo->obtenerLetrasNombreApellido($_SESSION['usuario']);
		    $_POST['numero_proceso']= $secuencialProceso.'-'.date('Y').'-'.$areaTecnica[0].'-'.$codigoProvincia.'-'.$inicial->current()->inicial;
		    $_POST['provincia'] = $prov[1];
		    $_POST['area_tecnica'] = $areaTecnica[1];
		    $_POST['identificador_registro'] = $_SESSION['usuario'];
		    $this->lNegocioProcesoAdministrativo->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: ProcesoAdministrativo
		*/
		public function editar()
		{
		 $this->accion = "Acto Administrativo"; 
		 $this->opcion = 'editar';
		 $this->modeloProcesoAdministrativo = $this->lNegocioProcesoAdministrativo->buscar($_POST["id"]);
		 require APP . 'ProcesosAdministrativosJuridico/vistas/formularioProcesoAdministrativoEditarVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - ProcesoAdministrativo
		*/
		public function borrar()
		{
		  $this->lNegocioProcesoAdministrativo->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - ProcesoAdministrativo
		*/
		 public function tablaHtmlProcesoAdministrativo($tabla) {
		{
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_proceso_administrativo'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ProcesosAdministrativosJuridico\procesoAdministrativo"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td><b>' . $fila['numero_proceso'] . '</b></td>
		  <td style="white - space:nowrap; ">' . $fila['area_tecnica'] . '</td>
        <td>' .date('j/n/Y',strtotime($fila['fecha_creacion'])). '</td>
        <td>' . $fila['provincia'] . '</td>
        </tr>');
		}
		}
	}
	
	/**
	 * Construye el código HTML para desplegar la lista de - ProcesoAdministrativo
	 */
	public function tablaHtmlProcesoAdministrativoConsulta($tabla) {
	    {
	        foreach ($tabla as $fila) {
	            $this->itemsFiltrados[] = array(
	                '<tr id="' . $fila['id_proceso_administrativo'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ProcesosAdministrativosJuridico\procesoAdministrativo"
		  data-opcion="consulta" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td><b>' . $fila['numero_proceso'] . '</b></td>
		  <td style="white - space:nowrap; ">' . $fila['area_tecnica'] . '</td>
        <td>' .date('j/n/Y',strtotime($fila['fecha_creacion'])). '</td>
        <td>' . $fila['provincia'] . '</td>
        </tr>');
	        }
	    }
	}
	/**
	 * Construye el código HTML para desplegar la lista de - ProcesoAdministrativo
	 */
	public function tablaHtmlProcesoAdministrativoReporte($tabla) {
	    {
	        foreach ($tabla as $fila) {
	            $this->itemsFiltrados[] = array(
	                '<tr id="' . $fila['id_proceso_administrativo'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'ProcesosAdministrativosJuridico\procesoAdministrativo"
		  data-opcion="formReporteDocumento" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td><b>' . $fila['numero_proceso'] . '</b></td>
		  <td style="white - space:nowrap; ">' . $fila['area_tecnica'] . '</td>
        <td>' .date('j/n/Y',strtotime($fila['fecha_creacion'])). '</td>
        <td>' . $fila['provincia'] . '</td>
        </tr>');
	        }
	    }
	}
	/**
	 * Método para generar documento
	 */
	public function generarDocumento()
	{
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $rutaArch ='';
	    if(isset($_POST['id_proceso_administrativo']) and isset($_POST['id_modelo_administrativo'])){
	        $this->modeloProcesoAdministrativo= $this->lNegocioProcesoAdministrativo->buscar($_POST['id_proceso_administrativo']);
	        
	        if($this->modeloProcesoAdministrativo->getEstado() == 'creado'){
        	        $arrayParametros = array(
        	            'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
        	            'id_modelo_administrativo' => $_POST['id_modelo_administrativo']
        	        );
        	        $validarActo = $this->verificarIngresoDocumento($arrayParametros);
        	        $_POST['identificador_registro']=$_SESSION['usuario'];
        	        if($validarActo['estado'] == 'EXITO'){
        	           $this->lNegocioTipoDocumento->guardar($_POST);
        	           $rutaArch=$validarActo['contenido'];
        	           $mensaje = 'Documento generado';
        	        }else{
        	            $estado = 'ERROR';
        	            $mensaje = $validarActo['mensaje'];
        	        }
	        }else{
	            $this->modeloModeloAdministrativo = $this->lNegocioModeloAdministrativo->buscar($_POST['id_modelo_administrativo']);
	            
	           if($this->modeloModeloAdministrativo->getOrden() == 9  || $this->modeloModeloAdministrativo->getOrden() ==5){
	                $arrayParametros = array(
	                    'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
	                    'id_modelo_administrativo' => $_POST['id_modelo_administrativo']
	                );
	                $validarActo = $this->verificarIngresoDocumento($arrayParametros);
	                $_POST['identificador_registro']=$_SESSION['usuario'];
	                if($validarActo['estado'] == 'EXITO'){
	                    $this->lNegocioTipoDocumento->guardar($_POST);
	                    $rutaArch=$validarActo['contenido'];
	                    $mensaje = 'Documento generado';
	                }else{
	                    $estado = 'ERROR';
	                    $mensaje = $validarActo['mensaje'];
	                }
	            }else{
	                $estado = 'ERROR';
	                $mensaje = 'No puede cargar '.$this->modeloModeloAdministrativo->getDescripcion().' Acto Administrativo cerrado...!!';
	            }
	        }
	        $contenido = $this->listarTipoDocumento($_POST['id_proceso_administrativo']);
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al generar el documento !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido,
	        'rutaArch' => $rutaArch
	    ));
	}	
	
	// Para descargar archivo
	public function descargaModeloJuridico() {
	    $this->rutaArchivo = $_POST['id'];
	    if($_POST['id'] != '0'){
	       require APP . 'ProcesosAdministrativosJuridico/vistas/formularioDescargaArchivosVista.php';
	    }
	}

	/**
	 * Método para generar documento
	 */
	public function listarTipoDocumento($idProcesoAdministrativo)
	{
	    $datos=$html='';
	    $arrayParametros = array('id_proceso_administrativo' => $idProcesoAdministrativo);
	    $consulta = $this->lNegocioTipoDocumento->buscarTipoDocumentoFiltro($arrayParametros);
	    if($consulta->count()){
	        foreach ($consulta as $item) {
	            $parentesco =	$this->lNegocioModeloAdministrativo->buscar($item->id_modelo_administrativo);
	            $datos .= '<tr>';
	            $datos .= '<td>'.$parentesco->getNombreModelo().'</td>';
	            $datos .= '<td><button class="bPrevisualizar icono" onclick="detalleModeloAdministrativo('.$item->id_tipo_documento.'); return false; "></button></td>';
	            $datos .= '<td><button class="bEliminar icono" onclick="eliminarModeloAdministrativo('.$item->id_tipo_documento.'); return false; "></button></td>';
	            
	            $datos .= '<tr>';
	        }
	        $html = '
				<table style="width:100%">
					<tbody>'.$datos.'</tbody>
				</table>
           ';
	    }
	    return $html;
	}
	/**
	 * Método para verificar proceso de ingresar información
	 */
	public function verificarIngresoDocumento($arrayParametros)
	{
	    $estado = 'ERROR';
	    $mensaje = 'Orden de modelo no definido !!';
	    $modelo=$this->lNegocioModeloAdministrativo->buscar($arrayParametros['id_modelo_administrativo']);
	    $contenido=$modelo->getRutaModelo();
	    switch ($modelo->getOrden()) {
            case 1:
                $arrayDatos = array(
                	        'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                	        'orden' => 1
                	    );
                $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                
                if($datos->count() > 0){
                    $estado='ERROR';
                    $mensaje = $modelo->getNombreModelo().' ya registrado !!';
                }else{
                    $estado = 'EXITO';
                }
                
                break;
            case 2:
                $arrayDatos = array(
                'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                'orden' => '1'
                    );
                $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                if($datos->count() <= 0){
                    $estado='ERROR';
                    $mensaje = 'Debe ingresar primero el Modelo de Acto Administrativo !!';
                }elseif ($datos->current()->ruta_documento == ''){
                    $estado='ERROR';
                    $mensaje = 'Debe cargar el documento final en el Modelo de Acto Administrativo !!';
                }else{
                    $arrayDatos = array(
                        'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                        'orden' => '2'
                    );
                    $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                    if($datos->count() > 0){
                        $estado='ERROR';
                        $mensaje = $modelo->getNombreModelo().' ya registrado !!';
                    }else{
                        $estado = 'EXITO';
                    }
                }
                break;
            case 3:
                
                for($i=1; $i<=2; $i++){
                    $arrayDatos = array(
                        'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                        'orden' => $i
                    );
                    $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                    $nombreModelo = $this->lNegocioModeloAdministrativo->buscarLista("orden=".$i." and estado= 'creado'");
                    if($datos->count() <= 0){
                        $estado='ERROR';
                        if($i==1){
                            $mensaje = 'Debe ingresar primero el '.$nombreModelo->current()->nombre_modelo.' !!';
                        }else{
                            $mensaje = 'Debe ingresar el '.$nombreModelo->current()->nombre_modelo.' !!';
                        }
                        break;
                    }elseif ($datos->current()->ruta_documento == '' ){
                        $estado='ERROR';
                        $mensaje = 'Debe cargar el documento final en el '.$nombreModelo->current()->nombre_modelo.' !!';
                        break;
                    }else{
                        $arrayDatos = array(
                            'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                            'orden' => '3'
                        );
                        $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                        if($datos->count() > 0){
                            $estado='ERROR';
                            $mensaje = $modelo->getNombreModelo().' ya registrado !!';
                        }else{
                            $estado = 'EXITO';
                        }
                    }
                }
                break;
            case 4:
                $arrayDatos = array(
                'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                'orden' => '1'
                    );
                $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                if($datos->count() <= 0){
                    $estado='ERROR';
                    $mensaje = 'Debe ingresar primero el Modelo de Acto Administrativo !!';
                }elseif ($datos->current()->ruta_documento == ''){
                    $estado='ERROR';
                    $mensaje = 'Debe cargar el documento final en el Modelo de Acto Administrativo !!';
                }else{
                    $arrayDatos = array(
                        'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                        'orden' => '4'
                    );
                    $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                    if($datos->count() > 0){
                        $estado='ERROR';
                        $mensaje = $modelo->getNombreModelo().' ya registrado !!';
                    }else{
                        $estado = 'EXITO';
                    }
                }
                break;
            case 5:
                $arrayDatos = array(
                'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                'orden' => '1'
                    );
                $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                if($datos->count() <= 0){
                    $estado='ERROR';
                    $mensaje = 'Debe ingresar primero el Modelo de Acto Administrativo !!';
                }elseif ($datos->current()->ruta_documento == ''){
                    $estado='ERROR';
                    $mensaje = 'Debe cargar el documento final en el Modelo de Acto Administrativo !!';
                }else{
                    $arrayDatos = array(
                        'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                        'orden' => '5'
                    );
                    $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                    if($datos->count() > 0){
                        $ban=1;
                        foreach ($datos as $value) {
                            if($value['ruta_documento'] ==''){
                                $ban=0;
                            }
                        }
                        if($ban){
                            $estado = 'EXITO';
                        }else{
                            $estado='ERROR';
                            $mensaje = 'Cargue documento adjunto en Providencia creada anteriormente  !!';
                        }
                    }else{
                        $estado = 'EXITO';
                    }
                }
                break;
            case 6: 
                $arrayDatos = array(
                'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                'orden' => '1'
                    );
                $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                if($datos->count() <= 0){
                     $estado='ERROR';
                     $mensaje = 'Debe ingresar primero el Modelo de Acto Administrativo !!';
                }elseif ($datos->current()->ruta_documento == ''){
                    $estado='ERROR';
                    $mensaje = 'Debe cargar el documento final en el Modelo de Acto Administrativo !!';
                }else{
                    $arrayDatos = array(
                        'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                        'orden' => '6'
                    );
                    $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                    if($datos->count() > 0){
                        $estado='ERROR';
                        $mensaje = $modelo->getNombreModelo().' ya registrado !!';
                    }else{
                        $estado = 'EXITO';
                    }
                }
                break;
            case 7:
                $arrayDatos = array(
                'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                'orden' => '1'
                    );
                $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                if($datos->count() <= 0){
                    $estado='ERROR';
                    $mensaje = 'Debe ingresar primero el Modelo de Acto Administrativo !!';
                }elseif ($datos->current()->ruta_documento == ''){
                    $estado='ERROR';
                    $mensaje = 'Debe cargar el documento final en el Modelo de Acto Administrativo !!';
                }else{
                
                $arrayDatos = array(
                'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                'orden' => '7'
                    );
                $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                if($datos->count() > 0){
                    $estado='ERROR';
                    $mensaje = $modelo->getNombreModelo().' ya registrado !!';
                }else{
                    $estado = 'EXITO';
                }
	        }
                break;
            case 8:
                for($i=1; $i<=7; $i++){
                	if($i==6){
                		$i++;
                	}
                    $arrayDatos = array(
                        'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                        'orden' => $i
                    );
                    $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                    $nombreModelo = $this->lNegocioModeloAdministrativo->buscarLista("orden=".$i." and estado= 'creado'");
                    if($datos->count() <= 0){
                        $estado='ERROR';
                        if($i==1){
                            $mensaje = 'Debe ingresar primero el '.$nombreModelo->current()->nombre_modelo.' !!';
                        }else{
                            $mensaje = 'Debe ingresar el '.$nombreModelo->current()->nombre_modelo.' !!';
                        }
                        break;
                    }elseif ($this->lNegocioProcesoAdministrativo->validarAdjunto($datos) ){
                        $estado='ERROR';
                        $mensaje = 'Debe cargar el documento final en el '.$nombreModelo->current()->nombre_modelo.' !!';
                        break;
                    }else{
                        $arrayDatos = array(
                         'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                            'orden' => '8'
                        );
                        $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                        if($datos->count() > 0){
                            $estado='ERROR';
                            $mensaje = $modelo->getNombreModelo().' ya registrado !!';
                        }else{
                                $estado = 'EXITO';
                            }
                    }
                }
                
                break;
            case 9:
                $arrayDatos = array(
                    'id_proceso_administrativo' => $_POST['id_proceso_administrativo'],
                    'orden' => '9'
                );
                $datos=$this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayDatos);
                if($datos->count() > 0){
                    $ban=1;
                    foreach ($datos as $value) {
                        if($value['ruta_documento'] ==''){
                            $ban=0;
                        }
                    }
                    
                    if($ban){
                        $estado = 'EXITO';
                    }else{
                        $estado='ERROR';
                        $mensaje = 'Cargue documento adjunto en Anexo creado anteriormente  !!';
                    }
                    
                }else{
                    $estado = 'EXITO';
                }
                break;
            default:
                $estado='ERROR';
            break;
        }
        return array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        );
	}
	/**
	 *detalle modelo administrativo
	 */
	public function detalleModeloAdministrativo()
	{
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_tipo_documento'])){
	        $this->modeloTipoDocumento = $this->lNegocioTipoDocumento->buscar($_POST['id_tipo_documento']);
	        $validarIngreso = $this->lNegocioTipoDocumento->buscarLista('id_modelo_administrativo='.$this->modeloTipoDocumento->getIdModeloAdministrativo().' and id_proceso_administrativo='.$this->modeloTipoDocumento->getIdProcesoAdministrativo().' and ruta_documento is null order by 1');
	        if($validarIngreso->count() > 0){
	            $this->modeloTipoDocumento = $this->lNegocioTipoDocumento->buscar($validarIngreso->current()->id_tipo_documento);
	        }
	        $this->modeloModeloAdministrativo =$this->lNegocioModeloAdministrativo->buscar($this->modeloTipoDocumento->getIdModeloAdministrativo());
	        $arrayParametros=array(
	            'id_proceso_administrativo' => $this->modeloTipoDocumento->getIdProcesoAdministrativo(),
	            'id_modelo_administrativo'=>$this->modeloTipoDocumento->getIdModeloAdministrativo(),
	            'resolucion' => $this->modeloModeloAdministrativo->getOrden()
	        );
	        if($this->modeloModeloAdministrativo->getOrden()==9){
	            $this->listaDetalleDocumento = $this->detalleDocumentoAnexo($arrayParametros);
	        }else{
	            $this->listaDetalleDocumento = $this->detalleDocumento($arrayParametros);
	        }
	        $this->accion = "Detalle ".$this->modeloModeloAdministrativo->getNombreModelo();
	        $this->modeloProcesoAdministrativo = $this->lNegocioProcesoAdministrativo->buscar($this->modeloTipoDocumento->getIdProcesoAdministrativo());
	    require APP . 'ProcesosAdministrativosJuridico/vistas/formularioProcesoAdministrativoDetalleVista.php';
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al abrir modelo  !!';
	        echo json_encode(array(
	            'estado' => $estado,
	            'mensaje' => $mensaje,
	            'contenido' => $contenido
	        ));
	    }
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
	    if(!empty($_REQUEST['id_tipo_documento']) && $_REQUEST['id_tipo_documento'] != 'null'){
	        
	        try {
	            
	            $tipoDocumento = $this->lNegocioTipoDocumento->buscar($_REQUEST['id_tipo_documento']);
	            $modeloDocumento = $this->lNegocioModeloAdministrativo->buscar($tipoDocumento->getIdModeloAdministrativo());
	            $nombreDocumento = str_replace(" ", "_", strtolower($modeloDocumento->getNombreModelo()));
	            $this->modeloProcesoAdministrativo=$this->lNegocioProcesoAdministrativo->buscar($tipoDocumento->getIdProcesoAdministrativo());
	            $nombre_archivo = $_FILES['archivo']['name'];
	            $tipo_archivo = $_FILES['archivo']['type'];
	            $tamano_archivo = $_FILES['archivo']['size'];
	            $tmpArchivo = $_FILES['archivo']['tmp_name'];
	            $rutaCarpeta = PROC_JURI_URL."documentosProceso/".$this->modeloProcesoAdministrativo->getNumeroProceso();
	            $extension = explode(".", $nombre_archivo);
	            
	            if($tipoDocumento->getRutaDocumento() == ''){
	            
	            if($tamano_archivo != '0' ) {
	                if (strtoupper(end($extension)) == 'PDF' && $tipo_archivo == 'application/pdf') {
	                    if (!file_exists('../../' . $rutaCarpeta)) {
	                        mkdir('../../' .$rutaCarpeta, 0777, true);
	                    }
	                    //$secuencial = date('Ymds').mt_rand(100,999);
	                    $nuevo_nombre = $nombreDocumento.'_'.$_SESSION['usuario'].'_'.$tipoDocumento->getIdTipoDocumento().'.' . end($extension);
	                    $ruta = $rutaCarpeta . '/' . $nuevo_nombre;
	                    move_uploaded_file($tmpArchivo, '../../' . $ruta);
	                    
	                    if($modeloDocumento->getOrden() == 8){
	                        $arrayProceso = array(
	                            'detalle_sancion' =>$_REQUEST['detalle_sancion'],
	                            'estado' => $_REQUEST['resultado_tramite'],
	                            'observacion' => $_REQUEST['observacion'],
	                            'id_proceso_administrativo' => $tipoDocumento->getIdProcesoAdministrativo()
	                        );
	                        $this->lNegocioProcesoAdministrativo->guardar($arrayProceso);
	                        
	                    }
	                    $nombreAnexo=null;
	                    if(isset($_REQUEST['nombre_anexo'])){
	                        $nombreAnexo=$_REQUEST['nombre_anexo'];
	                    }
	                    $arrayAdjunto = array(
	                        'ruta_documento' =>$ruta,
	                        'id_tipo_documento' => $_REQUEST['id_tipo_documento'],
	                        'nombre_anexo' => $nombreAnexo
	                    );
	                    $id = $this->lNegocioTipoDocumento->guardar($arrayAdjunto);
	                    if($id){
	                        $mensaje = 'Documento agregado correctamente';
	                        $arrayAdjunto = array(
	                            'id_proceso_administrativo'=> $tipoDocumento->getIdProcesoAdministrativo(),
	                            'id_modelo_administrativo'=> $tipoDocumento->getIdModeloAdministrativo(),
	                            'resolucion' => $modeloDocumento->getOrden()
	                        );
	                        if($modeloDocumento->getOrden() == 9){
	                           $contenido = $this->detalleDocumentoAnexo($arrayAdjunto);
	                        }else{
	                           $contenido = $this->detalleDocumento($arrayAdjunto);
	                        }
	                    }else{
	                        $estado = 'FALLO';
	                        $mensaje = 'Error al guardar el Documento..!!';
	                        $contenido = $ruta;
	                    }
	                } else {
	                    $estado = 'FALLO';
	                    $mensaje ='No se cargó archivo. Extención incorrecta';
	                }
	                
	            }else{
	                $estado = 'FALLO';
	                $mensaje = 'El documento supera el tamaño permitido';
	            }
	            }else{
	                $estado = 'FALLO';
	                $mensaje = 'Solo se permite cargar una sola vez el documento...!!';
	            }
	            
	        } catch (\Exception $ex) {
	            $estado = 'FALLO';
	            $mensaje= 'No se cargó documento';
	        }
	    }else{
	        $estado = 'FALLO';
	        $mensaje = 'Debe crear el tipo documento a guardar !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	public function detalleDocumento($arrayParametros){
	   
	   $html='<legend>Detalle de Documento</legend>	';
	   $consulta= $this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayParametros);
	   $contador = 1;
	   foreach ($consulta as $value) {
	       if($contador>=4){
	           $html.='<hr>';
	       }
	       $html .='<div data-linea="'.$contador++.'">
	    <label for="provincia">Tipo de Documento: </label>
	    <span>'.$value['nombre_modelo'].'</span>
		</div>
	           
		<div data-linea="'.$contador++.'">
			<label for="area_tecnica">Fecha creación: </label>
			<span>'.$value['fecha_creacion'].'</span>
		</div>
	           
	   <div data-linea="'.$contador++.'" id="NumProceso">
			<label for="numero_proceso">Plantilla Original: </label>';
	   $html .=($value['ruta_modelo']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$value['ruta_modelo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">tmp-'.$value['nombre_modelo'].'</a>');
       $html .='
		</div>
		<div data-linea="'.$contador++.'">
			<label for="nombre_accionado">Documento Final: </label>';
       $html .=($value['ruta_documento']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$value['ruta_documento'].' target="_blank" class="archivo_cargado" id="archivo_cargado">'.$value['descripcion'].'</a>');
       $html .='</div>';
       
       if($arrayParametros['resolucion'] == 8){
           $html .='<hr><div data-linea="'.$contador++.'">
	                <label for="provincia">Detalle de Sanción: </label>
	                <span>'.$value['detalle_sancion'].'</span>
		          </div>
	        
		          <div data-linea="'.$contador++.'">
			         <label for="area_tecnica">Resultado del Trámite: </label>
			         <span>';
               $html .=($value['estado'] != 'creado')?$value['estado']:"";
               $html .='</span>
		          </div>
			    
	               <div data-linea="'.$contador++.'" id="NumProceso">
			         <label for="numero_proceso">Observación: </label>
                      <span>'.$value['observacion'].'</span>';
           $html .='</div>';
         }
	   }
	  return $html;
	}
	public function detalleDocumentoAnexo($arrayParametros){
	    
	    $html='<legend>Detalle de Documento</legend>	';
	    $consulta= $this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayParametros);
	    $datos='';
	    $contador=0;
	        if($consulta->count()){
	            foreach ($consulta as $item) {
	                if($item->nombre_anexo != ''){
	                $datos .= '<tr>';
	                $datos .= '<td>'.++$contador.'</td>';
	                $datos .= '<td>'.$item->nombre_anexo.'</td>';
	                $datos .= '<td>'.$item->fecha_creacion.'</td>';
	                $datos .= '<td>';
	                $datos .=($item->ruta_documento==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$item->ruta_documento.' target="_blank" class="archivo_cargado" id="archivo_cargado">'.$item->nombre_anexo.'</a>');
	                $datos .= '</td>';
	                $datos .= '<td><button class="bEliminar icono" onclick="eliminarAnexo('.$item->id_tipo_documento.'); return false; "></button></td>';
	                $datos .= '<tr>';
	                }
	            }
	            $html .= '
				<table style="width:100%">
					<thead><tr>
						<th>#</th>
                        <th>Nombre de Documento</th>
						<th>Fecha de Creación</th>
                        <th>Documento Adjunto</th>
                        <th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	        }
	    return $html;
	}
	/**
	 * funcion para eliminar anexos
	 */
	public function eliminarAnexo() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    if(isset($_POST['id_tipo_documento'])){
	        $this->modeloTipoDocumento = $this->lNegocioTipoDocumento->buscar($_POST['id_tipo_documento']);
	        $arrayParametros=array(
	            'id_proceso_administrativo' => $this->modeloTipoDocumento->getIdProcesoAdministrativo(),
	            'id_modelo_administrativo'=>$this->modeloTipoDocumento->getIdModeloAdministrativo(),
	            'resolucion' => $this->modeloModeloAdministrativo->getOrden()
	        );
	        $this->lNegocioTipoDocumento->borrar($_POST['id_tipo_documento']);
	        $contenido=$this->listaDetalleDocumento = $this->detalleDocumentoAnexo($arrayParametros);
	        $mensaje = 'Anexo eliminado correctamente';
	    }else{
	        $estado = 'ERROR';
	        $mensaje = 'Error al eliminar anexo !!';
	    }
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido
	    ));
	}
	//****************************filtra informacion segun parametros************
	public function filtrarInformacionConsulta(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $modeloSolicitudInspeccion = array();
	    if(isset($_POST['provincia'])){
	        $arrayParametros = array('numero_proceso'=>$_POST['numero_proceso'],'area_tecnica' => $_POST['area_tecnica'],'fecha_creacion' =>$_POST['fecha_creacion'], 'provincia' => $_POST['provincia']);
	        $resultado=$this->lNegocioProcesoAdministrativo->obtenerProcesosAdministrativos($arrayParametros);
	        if($resultado->count()){
	            
	            $this->tablaHtmlProcesoAdministrativoConsulta($resultado);
	            $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
	            $mensaje='ok';
	        }else{
	            $estado = 'ERROR';
	            $mensaje="No existen registros para la busqueda realizada..!!";
	        }
	    }
	    $modeloSolicitudInspeccion = array();
	    $this->tablaHtmlProcesoAdministrativoConsulta($modeloSolicitudInspeccion);
	    $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido));
	}
	
	public function consultaDocumentoAnexo($arrayParametros,$opt='no'){
	    
	    $html='<legend>Documentos anexados al Acto Administrativo</legend>	';
	    $consulta= $this->lNegocioTipoDocumento->buscarTipoDocumentoModelo($arrayParametros);
	    $datos='';
	    $contador=0;
	    if($consulta->count()){
	        foreach ($consulta as $item) {
	            if($item->nombre_anexo != ''){
	                $datos .= '<tr>';
	                $datos .= '<td>'.++$contador.'</td>';
	                $datos .= '<td>'.$item->nombre_anexo.'</td>';
	                if($opt == 'si'){
	                $datos .= '<td>';
	                $datos .=($item->ruta_documento==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$item->ruta_documento.' target="_blank" class="archivo_cargado" id="archivo_cargado">'.$item->nombre_anexo.'</a>');
	                $datos .= '</td>';}
	                $datos .= '<td>'.$item->fecha_anexo.'</td>';
	                $datos .= '<tr>';
	            }
	        }
	        $html .= '
				<table style="width:100%">
					<thead><tr>
						<th>#</th>
                        <th>Nombre de Documento</th>';
	        if($opt == 'si'){
	            $html .=' <th>Documento Adjunto</th>';
	        }
             $html .='           <th>Fecha de Creación</th>
                        <th></th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	    }
	    return $html;
	}
	/**
	 * Método para generar documento
	 */
	public function consultaListaTipoDocumento($idProcesoAdministrativo)
	{
	    $datos=$html='';
	    $arrayParametros = array('id_proceso_administrativo' => $idProcesoAdministrativo);
	    $consulta = $this->lNegocioTipoDocumento->buscarTipoDocumentoFiltro($arrayParametros);
	    if($consulta->count()){
	        $contador=0;
	        foreach ($consulta as $item) {
	            $consulta =	$this->lNegocioModeloAdministrativo->buscar($item->id_modelo_administrativo);
	            $tipoDocumento = $this->lNegocioTipoDocumento->buscar($item->id_tipo_documento);
	            $datos .= '<tr>';
	            $datos .= '<td>'.++$contador.'</td>';
	            $datos .= '<td>'.$consulta->getNombreModelo().'</td>';
	            $datos .= '<td>';
	            $datos .=($tipoDocumento->getRutaDocumento()==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$tipoDocumento->getRutaDocumento().' target="_blank" class="archivo_cargado" id="archivo_cargado">'.$consulta->getDescripcion().'</a>');
	            $datos .= '</td>';
	            $datos .= '<td>'.date('j/n/Y',strtotime($tipoDocumento->getFechaCreacion())).'</td>';
	            $datos .= '<tr>';
	        }
	        $html = '
				<table style="width:100%">
					<thead><tr>
						<th>#</th>
                        <th>Tipo de Documento</th>
                        <th>Documento Adjunto</th>
                        <th>Fecha de Creación</th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	    }
	    return $html;
	}
	
	/**
	 * Método para generar documento
	 */
	public function reporteListaTipoDocumento($idProcesoAdministrativo)
	{
	    $datos=$html='';
	    $consulta = $this->lNegocioTipoDocumento->buscarLista('id_proceso_administrativo='.$idProcesoAdministrativo.' order by 1');
	    if($consulta->count()){
	        $contador=0;
	        foreach ($consulta as $item) {
	            $consulta =	$this->lNegocioModeloAdministrativo->buscar($item->id_modelo_administrativo);
	            $tipoDocumento = $this->lNegocioTipoDocumento->buscar($item->id_tipo_documento);
	            $datos .= '<tr>';
	            $datos .= '<td>'.++$contador.'</td>';
	            $datos .= '<td>'.$consulta->getNombreModelo().'</td>';
	            $datos .= '<td>';
	            $datos .=($item->ruta_documento==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$item->ruta_documento.' target="_blank" class="archivo_cargado" id="archivo_cargado">'.$consulta->getNombreModelo().'</a>');
	            $datos .= '</td>';
	                
	            $datos .= '<td>'.date('j/n/Y',strtotime($tipoDocumento->getFechaCreacion())).'</td>';
	            $datos .= '<tr>';
	        }
	        $html = '
				<table style="width:100%">
					<thead><tr>
						<th>#</th>
                        <th>Tipo de Documento</th>
                        <th>Documento Adjunto</th>
                        <th>Fecha de Creación</th>
						</tr></thead>
					<tbody>'.$datos.'</tbody>
				</table>';
	    }
	    return $html;
	}
	
	public function reporteGeneral(){
	    $this->filtroReporte();
	    require APP . 'ProcesosAdministrativosJuridico/vistas/listaReporteGeneralVista.php';
	    
	}
	public function reporteDocumento(){
	    $this->filtroActos();
	    require APP . 'ProcesosAdministrativosJuridico/vistas/listaReporteDocumentosVista.php';
	}
	//****************************filtra informacion segun parametros************
	public function filtrarInformacionReporte(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $modeloSolicitudInspeccion = array();
	    if(isset($_POST['provincia'])){
	        $arrayParametros = array('numero_proceso'=>$_POST['numero_proceso'],'area_tecnica' => $_POST['area_tecnica'],'fecha_creacion' =>$_POST['fecha_creacion'], 'provincia' => $_POST['provincia']);
	        $resultado=$this->lNegocioProcesoAdministrativo->obtenerProcesosAdministrativos($arrayParametros);
	        if($resultado->count()){
	            
	            $this->tablaHtmlProcesoAdministrativoReporte($resultado);
	            $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
	            $mensaje='ok';
	        }else{
	            $estado = 'ERROR';
	            $mensaje="No existen registros para la busqueda realizada..!!";
	        }
	    }
	    $modeloSolicitudInspeccion = array();
	    $this->tablaHtmlProcesoAdministrativoReporte($modeloSolicitudInspeccion);
	    $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido));
	}
	
	public function filtrarInformacionGeneral(){
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    $rutaArch='';
	    $modeloSolicitudInspeccion = array();
	    if(isset($_POST['fecha_desde']) && isset($_POST['fecha_hasta'])){
	        
	        $arrayParametros = array('fecha_desde' =>$_POST['fecha_desde'],'fecha_hasta'=>$_POST['fecha_hasta'],'provincia' =>$_POST['provincia'],'area_tecnica' => $_POST['area_tecnica'] );
	        $verificar = $this->lNegocioProcesoAdministrativo->obtenerConsolidadoProcesosAdministrativos($arrayParametros);
	        
	        if($verificar->count()>0){
	            $nombreArchivo='reporte_procesos_administrativos'.date('dmy');
	        $arrayDatos = array(
	            'titulo' => 'REPORTE DE PROCESOS ADMINISTRATIVOS JURÍDICO',
	            'subtitulo' => 'Período '.$_POST['fecha_desde'].' '.$_POST['fecha_hasta'],
	            'nombreArchivo' => $nombreArchivo,
	            'fecha_desde' => $_POST['fecha_desde'],
	            'fecha_hasta' => $_POST['fecha_hasta']
	        );
	        $this->lNegocioProcesoAdministrativo->crearExcel($arrayDatos,$arrayParametros);
	           $rutaArch = PROC_JURI_URL . "documentosProceso/reporteExcel/" . $nombreArchivo . ".xlsx";
	        }else{
	            $estado = 'ERROR';
	            $mensaje="No existen registros para la busqueda realizada..!!";
	        }
	    }else{
	        $estado = 'ERROR';
	        $mensaje="No selecciono las fechas..!!";
	    }
	    $modeloSolicitudInspeccion = array();
	    $this->tablaHtmlProcesoAdministrativoReporte($modeloSolicitudInspeccion);
	    $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
	    
	    echo json_encode(array(
	        'estado' => $estado,
	        'mensaje' => $mensaje,
	        'contenido' => $contenido,
	        'rutaArch' => $rutaArch
	    ));
	}
	
	/**
	 * funcion para eliminar eliminarModeloAdministrativo
	 */
	public function eliminarModeloAdministrativo() {
	    
	    $estado = 'EXITO';
	    $mensaje = '';
	    $contenido = '';
	    
	    if(isset($_POST['id_tipo_documento']) && isset($_POST['id_proceso_administrativo'])){
	        
	        $arrayParametros = array('id_tipo_documento' => $_POST['id_tipo_documento'], 'ruta_documento' => '', 'id_proceso_administrativo' =>$_POST['id_proceso_administrativo'] );
	        $verificar = $this->lNegocioTipoDocumento->buscarTipoDocumentoFiltro($arrayParametros);
	        if($verificar->count() <= 0){
	            $this->lNegocioTipoDocumento->borrar($_POST['id_tipo_documento']);
	            $contenido = $this->listarTipoDocumento($_POST['id_proceso_administrativo']);
	            $mensaje = 'Registro eliminado correctamente';
	        }else{
	            $estado = 'ERROR';
	            $mensaje = 'No se puede eliminar, el modelo tiene información registrada  !!';
	        }
	        
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
	
	
}

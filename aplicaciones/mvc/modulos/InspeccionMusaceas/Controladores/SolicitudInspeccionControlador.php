<?php
 /**
 * Controlador SolicitudInspeccion
 *
 * Este archivo controla la lógica del negocio del modelo:  SolicitudInspeccionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-09-18
 * @uses    SolicitudInspeccionControlador
 * @package InspeccionMusaceas
 * @subpackage Controladores
 */
 namespace Agrodb\InspeccionMusaceas\Controladores;
 use Agrodb\InspeccionMusaceas\Modelos\SolicitudInspeccionLogicaNegocio;
 use Agrodb\InspeccionMusaceas\Modelos\SolicitudInspeccionModelo;
 use Agrodb\InspeccionMusaceas\Modelos\TemporalProductoresLogicaNegocio;
 use Agrodb\InspeccionMusaceas\Modelos\TemporalProductoresModelo;
 use Agrodb\InspeccionMusaceas\Modelos\ResultadoInspeccionLogicaNegocio;
 use Agrodb\InspeccionMusaceas\Modelos\ResultadoInspeccionModelo;
 
class SolicitudInspeccionControlador extends BaseControlador 
{

		 private $lNegocioSolicitudInspeccion = null;
		 private $modeloSolicitudInspeccion = null;
		 private $lNegocioTemporalProductores = null;
		 private $modeloTemporalProductores = null;
		 private $lNegocioResultadoInspeccion = null;
		 private $modeloResultadoInspeccion = null;
		 private $accion = null;
		 private $paises = null;
		 private $operador =null;
		 private $operadorExterno = null;
		 
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioSolicitudInspeccion = new SolicitudInspeccionLogicaNegocio();
		 $this->modeloSolicitudInspeccion = new SolicitudInspeccionModelo();
		 $this->lNegocioTemporalProductores = new TemporalProductoresLogicaNegocio();
		 $this->modeloTemporalProductores = new TemporalProductoresModelo();
		 $this->lNegocioResultadoInspeccion = new ResultadoInspeccionLogicaNegocio();
		 $this->modeloResultadoInspeccion = new ResultadoInspeccionModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $this->filtroSolicitud(1);
		 $this->perfilUsuario();
		 $modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscarLista("identificador='".$_SESSION['usuario']."' order by 1 desc");
		 $this->tablaHtmlSolicitudInspeccion($modeloSolicitudInspeccion);
		 require APP . 'InspeccionMusaceas/vistas/listaSolicitudInspeccionVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nueva Solicitud Inspección"; 
		 $this->operador = $_SESSION['usuario'];
		 $this->obtenerTecnicoExterno();
		 $this->lNegocioTemporalProductores->borrarTemporalProductores($_SESSION['usuario']);
		 require APP . 'InspeccionMusaceas/vistas/formularioSolicitudInspeccionVista.php';
		}	/**
		* Método para registrar en la base de datos -SolicitudInspeccion
		*/
		public function guardar()
		{
		  $this->lNegocioSolicitudInspeccion->guardar($_POST);
		}/**
		* Método para registrar en la base de datos -SolicitudInspeccion
		*/
		public function guardarSolicitud()
		{
		    $this->lNegocioSolicitudInspeccion->guardarSolicitud($_POST);
		}/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: SolicitudInspeccion
		*/
		public function revisar()
		{
		 $this->accion = "Solicitud de Inspección"; 
		 $this->modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscar($_POST["id"]);
		 $this->operador = $this->modeloSolicitudInspeccion->getIdentificador();
		 $consulta = $this->lNegocioResultadoInspeccion->buscarLista("id_solicitud_inspeccion =".$this->modeloSolicitudInspeccion->getIdSolicitudInspeccion());
		 if($consulta->count()){
		     $this->modeloResultadoInspeccion = $this->lNegocioResultadoInspeccion->buscar($consulta->current()->id_resultado_inspeccion);
		 }
		 require APP . 'InspeccionMusaceas/vistas/formularioSolicitudInspeccionReporteVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - SolicitudInspeccion
		*/
		public function borrar()
		{
		  $this->lNegocioSolicitudInspeccion->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - SolicitudInspeccion
		*/
		 public function tablaHtmlSolicitudInspeccion($tabla) 
		{
    		  foreach ($tabla as $fila) {
    		      $razonSocial='';
    		      $consulta = $this->lNegocioSolicitudInspeccion->obtenerRazonSocial($fila['identificador']);
    		      foreach ($consulta as $value) {
    		          $razonSocial=$value->razon_social;
    		      }
    		      $mes = $this->lNegocioSolicitudInspeccion->nombreMes(date('n',strtotime($fila['fecha_creacion']) ));
    		      $fecha = date('d',strtotime($fila['fecha_creacion']) ).' '.$mes.' '.date('Y',strtotime($fila['fecha_creacion']));
    		   $this->itemsFiltrados[] = array(
    		  '<tr id="' . $fila['id_solicitud_inspeccion'] . '" class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'InspeccionMusaceas\solicitudInspeccion"
		        data-opcion="revisar" ondragstart="drag(event)" draggable="true"
		        data-destino="detalleItem">
    		  <td>' . $fila['codigo_solicitud'] . '</td>
    		  <td style="white - space:nowrap; "><b>' . $razonSocial . '</b></td>
                <td>' . $fila['estado'] . '</td>
                <td>' . $fila['pais_destino'] . '</td>
                <td>' . $fecha . '</td>
                <td>' . $fila['provincia'] .'</td>
                </tr>');
    		}
		}
		
		/***
		 * buscar informacion de productores
		 */
		public function buscarProductor(){
		    $estado = 'EXITO';
		    $mensaje = '';
		    $contenido = '';
	
		    $datos=$html='';
		        if($_POST['codigoBusqueda'] != null){
		            if($_POST['opcion'] == 'acopio'){
		                $valor = explode('.', $_POST['codigoBusqueda']);
		                $codigoProvincia = substr($valor[1],0,2);
		                $codigoS = substr($valor[1],2,2);
		                $codigoA = substr($valor[1],4,2);
		                $secuencial = substr($valor[1],6,2);
		                $identificador = $valor[0];
		                $codigoArea =  $valor[1];
		                
		                $arrayParametros = array('codigo_provincia' => $codigoProvincia,
		                    'codigoS' => $codigoS,
		                    'codigoA' => $codigoA,
		                    'secuencial' => $secuencial,
		                    'identificador' => $identificador,
		                    'codigoArea' => $codigoArea 
		                );
		                $mensaje='El código ingresado no corresponde a ningún Acopiador';
		            }else{
		                $arrayParametros = array('codigo_transaccional' => $_POST['codigoBusqueda']);
		                $mensaje='El código ingresado no corresponde a ningún Productor bananero';
		            }
		            $consulta = $this->lNegocioSolicitudInspeccion->listarProductores($arrayParametros);
		            if($consulta->count()){
		                $validarProvincia = true;
            		                foreach ($consulta as $item) {
            		                    if($item->provincia == ''){
            		                        $validarProvincia=false;
            		                    }
            		                    $datos .= '<tr>';
            		                    $datos .= '<td>'.$item->razon_social.'</td>';
            		                    $datos .= '<td>'.$item->nombre_area.'</td>';
            		                    $datos .= '<td>'.$item->provincia.'</td>';
            		                    if($_POST['opcion'] == 'acopio'){
            		                          $datos .= '<td>'.$item->codigo_area.'</td>';
            		                    }else{
            		                        $datos .= '<td>'.$item->codigo_transaccional.'</td>';
            		                    }
            		                    $datos .= '<td><button class="bPrevisualizar icono" onclick="agregarProductor('.$item->id_area.'); return false; "></button></td>';
            		                    $datos .= '</tr>';
            		                    
            		                }
            		                
                        		                $html = '
                        				<table style="width:100%">
                        					<thead><tr>
                        						<th>Razon Social</th>
                        						<th>Nombre del área</th>
                        						<th>Provincia</th>
                        						<th>Código</th>
                                                <th></th>
                        						</tr></thead>
                        					<tbody>'.$datos.'</tbody>
                        				</table>';
            		                
            		          $mensaje = '';
            		        if($validarProvincia){
            		              
		                    }else{
		                        $estado='ERROR';
		                        $mensaje='No existe una provincia asociada al código ingresado..!!';
		                    }
		          
		            }else{
		                $estado='ERROR';
		            }
		        }else{
		            $estado='ERROR';
		            $mensaje='Código de busqueda vacio';
		        }
		       // return $html;
		    
		    
		    echo json_encode(array(
		        'estado' => $estado,
		        'mensaje' => $mensaje,
		        'contenido' => $html
		    ));
		}
		
		
		/***
		 * agregar productores
		 */
		public function agregarProductor(){
		    $estado = 'EXITO';
		    $mensaje = '';
		    $contenido = '';
		    
		    $html='';
		    if($_POST['lugarInspeccion'] != null || $_POST['lugarInspeccion'] != ''){
		       if($_POST['id'] != null){
		        
		        $validar = $this->lNegocioTemporalProductores->buscarLista("id_area = ".$_POST['id']." and identificador='".$_SESSION['usuario']."'");
		        
		        if($validar->count() == 0){
        		        $arrayParametros = array('id_area' => $_POST['id']);	        
        		        $consulta = $this->lNegocioSolicitudInspeccion->listarProductores($arrayParametros);
            		        if($consulta->count()){
            		            foreach ($consulta as $item) {
            		                $arrayParametros = array(
            		                    'id_area' => $item->id_area,
            		                    'razon_social' => $item->razon_social,
            		                    'provincia' => $item->provincia,
            		                    'codigo_area' => $item->codigo_area,
            		                    'identificador' => $_SESSION['usuario'],
            		                    'nombre_area' => $item->nombre_area,
            		                    'cod_provincia' => $item->cod_provincia,
            		                    'cod_mag' => $item->codigo_transaccional,
            		                    'identificador_operador' =>$item->identificador
            		                );
            		                $validar = $this->lNegocioTemporalProductores->buscarLista("identificador='".$_SESSION['usuario']."'");
            		                if($validar->count()==0){
            		                    $this->lNegocioTemporalProductores->guardar($arrayParametros);
            		                    $html = $this->listarProductoresTmp();
            		                    $mensaje = '';
            		                }else{
            		                    
            		                    if($_POST['lugarInspeccion'] != 'Puerto'){
                		                    $validar = $this->lNegocioTemporalProductores->buscarLista("provincia = '".$item->provincia."' and identificador='".$_SESSION['usuario']."'");
                		                    if($validar->count()){
                		                        $this->lNegocioTemporalProductores->guardar($arrayParametros);
                		                        $html = $this->listarProductoresTmp();
                		                        $mensaje = '';
                		                    }else{
                		                        $estado='ERROR';
                		                        $mensaje='Se deben agregar registros de Fincas/centros de acopio que correspondan a la misma provincia';
                		                    }
            		                    }else{
            		                        $this->lNegocioTemporalProductores->guardar($arrayParametros);
            		                        $html = $this->listarProductoresTmp();
            		                        $mensaje = '';
            		                    }
            		                }
            		            }
            		        }else{
            		            $estado='ERROR';
            		            $mensaje='El código ingresado no corresponde a ningún Acopiador';
            		        }
    		        }else{
    		            $estado='ERROR';
    		            $mensaje='Productor ya registrado...!!';
    		        }
		        
    		    }else{
    		        $estado='ERROR';
    		        $mensaje='Código de busqueda vacio';
    		    }
		    }else{
		        $estado='ERROR';
		        $mensaje='Lugar de inspección no seleccionado...!!';
		    }
		    // return $html;
		    
		    
		    echo json_encode(array(
		        'estado' => $estado,
		        'mensaje' => $mensaje,
		        'contenido' => $html
		    ));
		}
	
	       /**
	        * funcion para buscar informacion de puertos
	        */
	       
	       public function buscarPuertos(){
	           $estado = 'EXITO';
	           $mensaje = '';
	           $contenido = '';
	           
	           $contenido = $this->cargarPuertos($_POST['idPais']);
	           
	           echo json_encode(array(
	               'estado' => $estado,
	               'mensaje' => $mensaje,
	               'contenido' => $contenido
	           ));
	       }
	       /**
	        * funcion para buscar requisitos
	        * 
	        */
	       
	       public function buscarRequisitos(){
	           $estado = 'EXITO';
	           $mensaje = '';
	           $contenido = '';
	           if(isset($_POST['producto']) and isset($_POST['idPais'])){
	               $arrayParametros = array('producto' => $this->lNegocioSolicitudInspeccion->quitar_tildes($_POST['producto']),'idPais' => $_POST['idPais']);
	               $resultado = $this->lNegocioSolicitudInspeccion->obtenerRequisitos($arrayParametros);
	               if($resultado->count()){
	               $contenido .='<table style="width:100%">';
	               foreach ($resultado as $fila) {
	                   $descrip='';
	                   if($fila->detalle  != NULL){
	                       $descrip= '<td>'.$fila->detalle.'</td>';
	                   }else{
	                       $descrip = '<td><strong>SOLO PARA IMPRESO </strong><br>'.$fila->detalle_impreso.'</td>';
	                       }
	                   $contenido .= '<tr>';
	                   $contenido .= '<td> R'.$fila->orden.'</td>';
	                   $contenido .= $descrip;
	                   $contenido .= '</tr>';
	               }
	                   $contenido .='</table>';
	               }else{
	                   $contenido="El producto consultado no cuenta con requisitos fitosanitarios de importación establecidos para el país de destino seleccionado, por favor dirija su consulta al siguiente correo electrónico: vigilancia.fitosanitaria@agrocalidad.gob.ec; o al teléfono (02) 3828860 ext 1063";
	               }
	           }
	           echo json_encode(array(
	               'estado' => $estado,
	               'mensaje' => $mensaje,
	               'contenido' => $contenido
	           ));
	       }
	       /**
	        * funcion para eliminar productor
	        */
	       public function eliminarProductor() {
	           
	           $estado = 'EXITO';
	           $mensaje = '';
	           $contenido = '';
	           $validar = '';
	           
	           if(isset($_POST['id'])){
	               $this->lNegocioTemporalProductores->borrar($_POST['id']);
	               $contenido = $this->listarProductoresTmp();
	               $mensaje = 'Registro eliminado correctamente';
	               
	               $resultado =  $this->lNegocioTemporalProductores->buscarLista("identificador='".$_SESSION['usuario']."' order by 1");
	               if($resultado->count() == 0){
	                   $validar = 'vacio';
	               }
	               
	           }else{
	               $estado = 'ERROR';
	               $mensaje = 'Error al eliminar el registro !!';
	           }
	           echo json_encode(array(
	               'estado' => $estado,
	               'mensaje' => $mensaje,
	               'contenido' => $contenido,
	               'validar' => $validar
	           ));
	       }
	       //**************crear html de lista de productores**************************
	       public function listarProductoresTmp(){
	           $html=$datos='';
	           $resultado =  $this->lNegocioTemporalProductores->buscarLista("identificador='".$_SESSION['usuario']."' order by 1");
	           if($resultado->count()){
        	           foreach ($resultado as $item) {
        	               $codigo=$item->cod_mag == '' ? $item->codigo_area:$item->cod_mag;
        	               $datos .= '<tr>';
        	               $datos .= '<td>'.$item->razon_social.'</td>';
        	               $datos .= '<td>'.$item->nombre_area.'</td>';
        	               $datos .= '<td>'.$item->provincia.'</td>';
        	               $datos .= '<td>'.$codigo.'</td>';
        	               $datos .= '<td><input type="text" id='.$item->id_temporal_productores.' style="width : 40px; " name="numCajas[]" placeholder="Número de cajas" maxlength="7" pattern="[0-9]{1,7}"/></td>';
        	               $datos .= '<td><button class="bEliminar icono" onclick="eliminarProductor('.$item->id_temporal_productores.'); return false; "></button></td>';
        	               $datos .= '</tr>';
        	               $codigo='';
        	           }
        	           $html = '
        				<table style="width:100%">
        					<thead><tr>
        						<th>Razon Social</th>
        						<th>Nombre del área</th>
        						<th>Provincia</th>
        						<th>Código</th>
                                <th>Cajas</th>
                                <th></th>
        						</tr></thead>
        					<tbody>'.$datos.'</tbody>
        				</table>';
	           }
	           return $html;
	       }
	       //****************************filtra informacion segun parametros************
	       public function filtrarInformacion(){
	           $estado = 'EXITO';
	           $mensaje = '';
	           $contenido = '';
	           $modeloSolicitudInspeccion = array();
	           $this->perfilUsuario();
               $arrayParametros = array('fecha' => $_POST['fecha'], 'estadoSolicitud'=>(isset($_POST['estadoSolicitud']))?$_POST['estadoSolicitud']:'','identificador' => $_SESSION['usuario'],'numeroSolicitud' => $_POST['numeroSolicitud']);
	           $modeloSolicitudInspeccion = $this->lNegocioSolicitudInspeccion->buscarPorParametros($arrayParametros);
	           if($modeloSolicitudInspeccion->count()==0){
	               $estado = 'FALLO';
	               $mensaje = 'No existen registros para la busqueda..!!';
	           }
	           $this->tablaHtmlSolicitudInspeccion($modeloSolicitudInspeccion);
	           $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
	           echo json_encode(array(
	               'estado' => $estado,
	               'mensaje' => $mensaje,
	               'contenido' => $contenido));
	       }
	       /**
	        * proceso automatico para eliminar solicitudes
	        */
	       public function procesoAutomatico(){
	           echo "\n" . 'Proceso Automatico -> Finalización de solicitudes de inspección de musáceas' . "\n" . "\n";
	           $modeloCertificado = $this->lNegocioSolicitudInspeccion->obtenerSolicitudes();
	           foreach ($modeloCertificado as $fila){
	               $arrayGuardar = array(
	                   'id_solicitud_inspeccion' => $fila['id_solicitud_inspeccion'],
	                   'estado' => 'Dada de baja');
	               $this->lNegocioSolicitudInspeccion->guardar($arrayGuardar);
	               echo $fila['identificador_operador'] . '->Solicitud de inspección de musaceas dada de baja (Dada de baja)' . "\n";
	           }
	           echo "\n";
	       }
	       
	       public function obtenerTecnicoExterno(){
	       	$consultar = $this->lNegocioSolicitudInspeccion->obtenerTecnicoInspectorExterno();
	       	$combo = '<option value="">Seleccionar....</option>';
	       	foreach ($consultar as $value) { 
	       		$combo .= '<option value="' . $value->identificador . '">' . $value->operador . '</option>';
	       	}
	       	$this->operadorExterno = $combo;
	       	
	       }
}

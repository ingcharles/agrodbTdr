<?php
/**
 * Controlador Operaciones
 *
 * Este archivo controla la lógica del negocio del modelo: OperacionesModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2020-09-18
 * @uses OperacionesControlador
 * @package AdministrarOperacionesGuia
 * @subpackage Controladores
 */
namespace Agrodb\AdministrarOperacionesGuia\Controladores;

use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperacionesModelo;
use Agrodb\RegistroOperador\Modelos\DatosVehiculoTransporteAnimalesLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\DatosVehiculoTransporteAnimalesModelo;
use Agrodb\RegistroOperador\Modelos\VehiculoTransporteAnimalesExpiradoLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\VehiculoTransporteAnimalesExpiradoModelo;
use Agrodb\Catalogos\Modelos\TiposOperacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\TiposOperacionModelo;
use Agrodb\Laboratorios\Modelos\DatosvalidadosinformeLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\DatosVehiculosLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\DatosVehiculosModelo;

class OperacionesControlador extends BaseControlador{

	private $lNegocioOperaciones = null;

	private $modeloOperaciones = null;

	private $accion = null;

	private $operador = null;

	private $sitiosAreas = null;

	private $transporte = null;

	private $idOperacion = null;

	private $area = null;

	private $datosOperacion = null;
	
	private $lNegocioTiposOperacion = null;
	
	private $modeloTiposOperacion = null;
	
	private $modeloDatosVehiculoTransporteAnimales = null;
	
	private $lNegocioDatosVehiculoTransporteAnimales = null;
	
	private $modeloVehiculoTransporteAnimalesExprirado = null;
	
	private $lNegocioVehiculoTransporteAnimalesExpirado = null;
	
	private $modeloDatosVehiculo = null;
	
	private $lNegocioDatosVehiculo = null;
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioOperaciones = new OperacionesLogicaNegocio();
		$this->modeloOperaciones = new OperacionesModelo();		
		$this->lNegocioTiposOperacion = new TiposOperacionLogicaNegocio();
		$this->modeloTiposOperacion = new TiposOperacionModelo();
		$this->lNegocioDatosVehiculoTransporteAnimales = new DatosVehiculoTransporteAnimalesLogicaNegocio();
		$this->modeloVehiculoTransporteAnimales = new DatosVehiculoTransporteAnimalesModelo();
		$this->modeloVehiculoTransporteAnimalesExprirado = new VehiculoTransporteAnimalesExpiradoModelo();
		$this->lNegocioVehiculoTransporteAnimalesExpirado = new VehiculoTransporteAnimalesExpiradoLogicaNegocio();
		$this->lNegocioDatosVehiculo = new DatosVehiculosLogicaNegocio();
		$this->modeloVehiculo = new DatosVehiculosModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Operaciones";
		require APP . 'AdministrarOperacionesGuia/vistas/formularioOperacionesVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Operaciones
	 */
	public function guardar(){
		$this->lNegocioOperaciones->guardar($_POST);
	}

	public function guardarResultado(){
		$estado = 'exito';
		$mensaje = '';
		$banderaActualizar = true;				  
		$observacionPost = '';
		
		$qTipoOperacion = $this->lNegocioTiposOperacion->buscarTipoOperacionPorIdOperacion($_POST);
		$idArea = $qTipoOperacion->current()->id_area;
		$codigoOperacion = $qTipoOperacion->current()->codigo;
		
		switch ($idArea){		    
		    case 'SA':		        
		        switch ($codigoOperacion){		            
		            case 'TAV':                        
                        $datosOperacion = $this->lNegocioOperaciones->buscar($_POST['id_operacion']);
                        $idOperadorTipoOperacion = $datosOperacion->getIdOperadorTipoOperacion();                        
                        
                        $arrayParametros = array('id_operador_tipo_operacion' => $idOperadorTipoOperacion);
                        
                        $qOperacionesTransporteAnimales = $this->lNegocioDatosVehiculoTransporteAnimales->buscarDatosVehiculoTransporteAnimalesPorIdOperadorTipoOperacion($arrayParametros);
                        
						if(isset($qOperacionesTransporteAnimales->current()->id_dato_vehiculo_transporte_animales)){
								
							$idDatoVehiculoAntiguo = $qOperacionesTransporteAnimales->current()->id_dato_vehiculo_transporte_animales;
							
							$arrayParametros = array('id_dato_vehiculo_antiguo' => $idDatoVehiculoAntiguo);
							
							$verificarHabilitarOperaciones = $this->lNegocioVehiculoTransporteAnimalesExpirado->verificarVehiculoTransporteAnimalesExpirado($arrayParametros);
													
							if(isset($verificarHabilitarOperaciones->current()->id_dato_vehiculo_antiguo)){
								$banderaActualizar = false;
								$estado = 'ERROR';
								$mensaje = 'La operación no puede ser habilitada, por que esta atada a un vehiculo registrado por el operador ' . $verificarHabilitarOperaciones->current()->identificador_propietario_vehiculo . '.';
							}
						}else{							
							$banderaActualizar = false;
							$estado = 'ERROR';
							$mensaje = 'La operación no posee vehículos asociados (No puede ser habilitada).';                            
						}						
                    break;		            
		        }		        
		    break;

			case 'AI':
			
				switch ($codigoOperacion){		            
					
					case 'MDT': 
					
						$datosOperacion = $this->lNegocioOperaciones->buscar($_POST['id_operacion']);
		                $idOperadorTipoOperacion = $datosOperacion->getIdOperadorTipoOperacion();
		                $idHistorialOperacion = $datosOperacion->getIdHistorialOperacion();
	 							   
						$arrayParametros = array('id_operador_tipo_operacion' => $idOperadorTipoOperacion,
												'id_historial_operacion' => $idHistorialOperacion,
												'estado' => 'registrado');

						$lNegocioOperaciones = new OperacionesLogicaNegocio();
						$productosObtenidos = $lNegocioOperaciones->obtenerProductosPorIdOperadorTipoOperacion($arrayParametros);
							   
						$arrayParametros = array('id_operador_tipo_operacion' => $idOperadorTipoOperacion);
		 
						$idDatoVehiculo = $this->lNegocioDatosVehiculo->buscarLista($arrayParametros);
						$placaVehiculo = $idDatoVehiculo->current()->placa_vehiculo;
											
						$arrayParametros = array('placa_vehiculo' => $placaVehiculo);

						$idDatoVehiculoActivo = $this->lNegocioDatosVehiculo->buscarDatosVehiculosActivo($arrayParametros);
						
						if(($idDatoVehiculoActivo->count()) > 0 ){
							$vehiculoOperacion =	$idDatoVehiculoActivo->current()->id_operador_tipo_operacion;
						}
						
						$contador=0;
						
						foreach ($_POST['check'] as $value){
							$contador = $contador+1;
						}
						
						if($_POST['resultado'] == 'Habilitado'){
							
							if($contador > 0 ){		
							
								if(($idDatoVehiculoActivo->count()) > 0){
									
									if($vehiculoOperacion == $idOperadorTipoOperacion ){
										$banderaActualizar=true;
									}else{
										$estado = 'ERROR';
										$mensaje = 'Este vehículo a se encuentra registrado en otra operación...!! ' ;
										$banderaActualizar = false;																							  
									}
									
								}else{
									
									$banderaActualizar = true;
									$lNegocioOperaciones->inactivarVehiculo($idOperadorTipoOperacion,'activo','inactivo',$placaVehiculo);
									
								}
							}

						}else if($_POST['resultado'] == 'Inhabilitado'){

							    if(($productosObtenidos->count()) == $contador ){
									
							    		$banderaActualizar=true;
							    	    $lNegocioOperaciones->inactivarVehiculo($idOperadorTipoOperacion,'inactivo','activo',$placaVehiculo); 
							    }else {
							    		$banderaActualizar = true; 
							    }
								
					    }
					
					break;
					
				}

			break;
		}
		
		if ($banderaActualizar){
		
    		if (isset($_POST['check'])){
    			$observacionPost = $_POST['observacion'];
    			foreach ($_POST['check'] as $value){
    				$_POST['id_operacion'] = $value;
    				$operacion = $this->lNegocioOperaciones->buscar($value);
    				$estadoAnterior = $operacion->getEstado();
    				if ($_POST['resultado'] == 'Habilitado'){
    					$estado = 'registrado';
    					$observacion = 'Habilitado por el módulo Administración Operaciones GUIA por el funcionario ' . $_SESSION['usuario'] . ' estado anterior ' . $estadoAnterior . '-' . $observacionPost;
    				}else{
    					$observacion = 'Inactivación realizada por el módulo Administración Operaciones GUIA por el funcionario ' . $_SESSION['usuario'] . ' estado anterior ' . $estadoAnterior . '-' . $observacionPost;
    					$estado = 'noHabilitado';
    				}
    				$_POST['estado'] = $estado;
    				$_POST['observacion'] = $observacion;
    				$_POST['observacion_tecnica'] = $observacion;
    				$_POST['estado_anterior'] = $estadoAnterior;			
    				    
    				// ******************Revision de solicitudes******************//
    
    				$arrayParametros = array(
    					'identificadorOperador' => $operacion->getIdentificadorOperador(),
    					'idOperacion' => $value);
    				$operador = $this->lNegocioOperaciones->abrirOperacion($arrayParametros);
    				foreach ($operador as $item){
    					$arrayRevisionSolicitudes = array(
    					    'identificador_inspector' => $_SESSION['usuario'],
    					    'fecha_asignacion' => 'now()',
    					    'identificador_asignante' => $_SESSION['usuario'],
    					    'tipo_solicitud' => 'Operadores',
    					    'tipo_inspector' => 'Técnico',
    					    'id_operador_tipo_operacion' => $item['id_operador_tipo_operacion'],
    					    'id_historial_operacion' => $item['id_historial_operacion'],
    					    'id_solicitud' => $value,
    					    'estado' => 'Técnico',
    					    'fecha_inspeccion' => 'now()',
    					    'observacion' => $observacion,
    					    'estado_siguiente' => $estado,
    					    'orden' => 1);
    				}				
    
    				$resultado = $this->lNegocioOperaciones->guardarResultado($_POST, $arrayRevisionSolicitudes);    				
    				
    				if ($resultado){
    					$estado = 'exito';					
    				}else{
    					$estado = 'ERROR';
    					$mensaje = 'Error al guardar los datos !!';
    					break;
    				}
    			}
    		}
    		
	   }
       echo json_encode(array(
                        	'estado' => $estado,
                        	'mensaje' => $mensaje));
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Operaciones
	 */
	public function editar(){
		$this->accion = "Editar Operaciones";
		$this->modeloOperaciones = $this->lNegocioOperaciones->buscar($_POST["id"]);
		require APP . 'AdministrarOperacionesGuia/vistas/formularioOperacionesVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Operaciones
	 */
	public function borrar(){
		$this->lNegocioOperaciones->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Operaciones
	 */
	public function tablaHtmlOperaciones($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_operacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'AdministrarOperacionesGuia\operaciones"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_operacion'] . '</b></td>
        <td>' . $fila['id_tipo_operacion'] . '</td>
        <td>' . $fila['identificador_operador'] . '</td>
        <td>' . $fila['estado'] . '</td>
        </tr>');
			}
		}
	}

	/**
	 * Método de inicio del controlador
	 */
	public function inocuidad(){
		$this->perfilUsuario();
		$this->filtroOperaciones();
		$this->area = 'AI';
		require APP . 'AdministrarOperacionesGuia/vistas/listaOperacionesVista.php';
	}
	
	/**
	 * Método de inicio del controlador
	 */
	public function sanidadAnimal(){
	    $this->perfilUsuario();
	    $this->filtroOperaciones();
	    $this->area = 'SA';
	    require APP . 'AdministrarOperacionesGuia/vistas/listaOperacionesVista.php';
	}

	/**
	 * Método de inicio del controlador
	 */
	public function vegetal(){
		// $modeloOperaciones = $this->lNegocioOperaciones->buscarOperaciones();
		// $this->tablaHtmlOperaciones($modeloOperaciones);
		require APP . 'AdministrarOperacionesGuia/vistas/listaOperacionesVista.php';
	}

	/**
	 * filtrar información
	 */
	public function filtrarOperacionesOperador(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		$this->perfilUsuario();
		if ($this->perfilUsuario == 'PFL_TEC_PC'){
			$provincia = $_POST['provincia'];
		}else{
			$provincia = $_SESSION['nombreProvincia'];
		}
		// 'registrado','noHabilitado'
		if (isset($_POST['identificadorOperador']) && $provincia != ''){
			$arrayParametros = array(
				'razon_social' => $_POST['razonSocial'],
				'identificador_operador' => $_POST['identificadorOperador'],
				'estado' => "in ('registrado','noHabilitado')",
				'provincia' => $provincia,
				'id_area' => $_POST['area'],
				'codigo' => $_POST['tipoOperacion']);
			$resultado = $this->lNegocioOperaciones->obtenerOperacionesOperador($arrayParametros);
			if ($resultado->count()){
				foreach ($resultado as $fila){
					$arrayParametros = array(
						'idTipoOperacion' => $fila['id_tipo_operacion'],
						'idSitio' => $fila['id_sitio']);
					$nombreArea = $this->lNegocioOperaciones->buscarNombreAreaPorSitioPorTipoOperacion($arrayParametros);
					$contenido .= '<article
                			id="' . $fila['id_operacion'] . '"
                			class="item"
                			data-rutaAplicacion="' . URL_MVC_FOLDER . 'AdministrarOperacionesGuia"
                			data-opcion="operaciones/listarOperaciones"
                			ondragstart="drag(event)"
                			draggable="true"
                			data-destino="detalleItem">
                			<span><small> # ' . $fila['id_tipo_operacion'] . '-' . $fila['id_sitio'] . ' </small></span>
                						<span><small>' . (strlen($fila['provincia']) > 14 ? (substr($this->reemplazarCaracteres($fila['provincia']), 0, 14) . '...') : (strlen($fila['provincia']) > 0 ? $fila['provincia'] : '')) . '</small></span><br />
                						<span><small>' . (strlen($fila['nombre_tipo_operacion']) > 30 ? (substr($this->reemplazarCaracteres($fila['nombre_tipo_operacion']), 0, 30) . '...') : (strlen($fila['nombre_tipo_operacion']) > 0 ? $fila['nombre_tipo_operacion'] : '')) . '<b> en </b> ' . (strlen($nombreArea->current()->nombre_area) > 42 ? (substr($this->reemplazarCaracteres($nombreArea->current()->nombre_area), 0, 42) . '...') : (strlen($nombreArea->current()->nombre_area) > 0 ? $nombreArea->current()->nombre_area : '')) . '</small></span>
                					<aside class= "estadoOperador"><small> Estado: ' . $fila['estado'] . '<span><div class= "circulo_verde"></div></span></small></aside>
                						</article>';
				}
				$mensaje = 'ok';
			}else{
				$estado = 'ERROR';
				$mensaje = "No existen registros para la busqueda realizada..!!";
			}
		}else{
			$estado = 'ERROR';
			$mensaje = 'Ingresar valores en los campos de busqueda..!!';
		}
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}

	/**
	 * ver información de operacion
	 */
	public function listarOperaciones(){
		$this->accion = "Solicitud Operador";
		$this->modeloOperaciones = $this->lNegocioOperaciones->buscar($_POST['id']);
		$this->sitiosAreas = $this->sitiosAreas($this->modeloOperaciones->getIdentificadorOperador(), $_POST['id']);
		$this->idOperacion = $_POST['id'];
		$this->transporte = $this->medioTransporte($this->modeloOperaciones->getIdentificadorOperador(), $_POST['id']);
		$this->datosOperacion = $this->datosOperacion($this->modeloOperaciones->getIdentificadorOperador(), $_POST['id']);
		require APP . 'AdministrarOperacionesGuia/vistas/formularioOperacionesVista.php';
	}

	/**
	 * Método de para cargar los tipos de operacion
	 */
	public function cargarTipoOperacion(){
		$estado = 'EXITO';
		$mensaje = '';
		$contenido = '';
		if (isset($_POST['identificador'])){
			$arrayParametros = array(
				'identificador_operador' => $_POST['identificador'],
				'estado' => "in ('registrado','noHabilitado')",
				'provincia' => $_POST['provincia'],
				'id_area' => $_POST['area']);
		}elseif (isset($_POST['razonSocial'])){
			$arrayParametros = array(
				'razon_social' => $_POST['razonSocial'],
				'estado' => "in ('registrado','noHabilitado')",
				'provincia' => $_POST['provincia'],
				'id_area' => $_POST['area']);
		}
		$contenido = $this->comboTipoOperacion($arrayParametros);
		echo json_encode(array(
			'estado' => $estado,
			'mensaje' => $mensaje,
			'contenido' => $contenido));
	}
}

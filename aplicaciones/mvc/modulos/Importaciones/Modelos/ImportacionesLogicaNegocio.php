<?php
/**
 * Lógica del negocio de ImportacionesModelo
 *
 * Este archivo se complementa con el archivo ImportacionesControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-06
 * @uses ImportacionesLogicaNegocio
 * @package Importaciones
 * @subpackage Modelos
 */
namespace Agrodb\Importaciones\Modelos;

use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\Importaciones\Modelos\IModelo;
use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
use Agrodb\Catalogos\Modelos\TiposOperacionLogicaNegocio;
use Agrodb\RequisitosComercializacion\Modelos\RequisitosComercializacionLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\AsignacionInspectorLogicaNegocio;
use Agrodb\Catalogos\Modelos\CodigosInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\CodigosInocuidadModelo;
use Zend\Db\ResultSet\ResultSet;
use Agrodb\Vue\Modelos\SolicitudesAtenderLogicaNegocio;

class ImportacionesLogicaNegocio implements IModelo{

	private $modeloImportaciones = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloImportaciones = new ImportacionesModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		try{

			$datos['fecha_modificacion'] = 'now()';
			
			if($datos['fecha_vigencia'] != $datos['fecha_vigencia_antigua']){
				$datos['estado'] = 'ampliado';
				$datos['fecha_ampliacion'] = 'now()';
			}
			
			$tablaModelo = new ImportacionesModelo($datos);

			$procesoValidacion = $this->validarDatosImportacion($datos);

			if($procesoValidacion[0]){
				//TODO: Verificar el nombre edl puerto embarque y puerto de destino y medio de trasnporte  

				$procesoIngreso = $this->modeloImportaciones->getAdapter()
				->getDriver()
				->getConnection();
				$procesoIngreso->beginTransaction();
				
				$datosBd = $tablaModelo->getPrepararDatos();
				if ($tablaModelo->getIdImportacion() != null && $tablaModelo->getIdImportacion() > 0){
					$this->modeloImportaciones->actualizar($datosBd, $tablaModelo->getIdImportacion());
					$idImportacion = $tablaModelo->getIdImportacion();
				}else{
					unset($datosBd["id_importacion"]);
					$idImportacion = $this->modeloImportaciones->guardar($datosBd);
				}
				
				$statement = $this->modeloImportaciones->getAdapter()
				->getDriver()
				->createStatement();
				
				for ($i = 0; $i < count($datos['id_importacion_producto']); $i ++){
					
					$idImportacionProducto = $datos['id_importacion_producto'][$i];
					$partidaProductoVue = $datos['partida_arancelaria'][$i] . $datos['codigo_complementario_suplementario'][$i];
					
					if(isset($datos['codigo_presentacion'][$i])){
						$lNegocioCodigosInocuidad = new CodigosInocuidadLogicaNegocio();
						$nombrePresentacion = $lNegocioCodigosInocuidad->buscarLista(array("id_producto"=> $datos['id_producto'][$i], "subcodigo" => $datos['codigo_presentacion'][$i]));
						$codigoPresentacion = $datos['codigo_presentacion'][$i];
						$datosNombrePresentacion = $nombrePresentacion->current()->presentacion;
					}else{
						$codigoPresentacion = '';
						$datosNombrePresentacion= '';
					}
					
					$codigoProductoVue = $datos['codigo_producto_vue'][$i] .$codigoPresentacion;
					
					$datosDetalleProducto = array(
						'partida_producto_vue' => $partidaProductoVue,
						'codigo_producto_vue' => $codigoProductoVue,
						'unidad' => $datos['unidad'][$i],
						'unidad_medida' => $datos['unidad_medida'][$i],
						'peso' => $datos['peso'][$i],
						'valor_cif' => $datos['valor_cif'][$i],
						'valor_fob' => $datos['valor_fob'][$i],
						'presentacion_producto' => $datosNombrePresentacion
					);
					
					$sqlActualizar = $this->modeloImportaciones->actualizarSql('importaciones_productos', $this->modeloImportaciones->getEsquema());
					$sqlActualizar->set($datosDetalleProducto);
					$sqlActualizar->where(array('id_importacion_producto' => $idImportacionProducto));
					$sqlActualizar->prepareStatement($this->modeloImportaciones->getAdapter(), $statement);
					$statement->execute();
				}
				
				$lNegocioAsignacionInspector = new AsignacionInspectorLogicaNegocio();
				
				$datosRevision = array('identificador_inspector' => $datos['identificador_rectificacion'],
					'fecha_asignacion' => 'now()',
					'identificador_asignante' => $datos['identificador_rectificacion'],
					'tipo_solicitud' => 'Importación',
					'tipo_inspector' => 'Documental',
					'id_operador_tipo_operacion' => 0,
					'id_historial_operacion' => 0,
					'id_solicitud' => $idImportacion,
					'estado' => 'Documental',
					'fecha_inspeccion' => 'now()',
					'observacion' => $datos['observacion_rectificacion'],
					'estado_siguiente' => 'Rectificado',
					'orden'=> 1
				);
				
				$lNegocioAsignacionInspector->guardar($datosRevision);
				
				$lNegocioSolicitudesAtender = new SolicitudesAtenderLogicaNegocio();
				
				$datosRectificacion = array('formulario'=> '101-002-RES',
					'codigo_procesamiento'=> '330',
					'codigo_verificacion'=> '21',
					'solicitud'=> $datos['id_vue'],
					'estado'=> 'Por atender',
					'observacion'=> $datos['observacion_rectificacion'],
					'fecha'=> 'now()'
				);
				
				$lNegocioSolicitudesAtender->guardar($datosRectificacion);
				
				$procesoIngreso->commit();
				return $procesoValidacion;
			}else{
				return $procesoValidacion;
			}
		}catch (GuardarExcepcion $ex){
			$procesoIngreso->rollback();
			throw new \Exception($ex->getMessage());
		}
	}
	
	/*Validacion de datos de rectificacion de importacion
	 * 
	 * @param
	 * 
	 * string Where|array $where
	 * 
	 * @return boolean
	 * */
	public function validarDatosImportacion($datos) {

		$validacion = array();
		$validacion[0] = true;

		$lNegocioOperaciones = new OperacionesLogicaNegocio();
		$lNegocioTiposOperacion = new TiposOperacionLogicaNegocio();
		$lNegocioRequisitosComercializacion = new RequisitosComercializacionLogicaNegocio();

		$tipoOperacion = $lNegocioTiposOperacion->buscarLista(array("id_area" => $datos['id_area'], "codigo"=> "IMP"));
		$idTipoOperacion = $tipoOperacion->current()->id_tipo_operacion;

		for ($i = 0; $i < count($datos['id_importacion_producto']); $i ++){

			$partidaProductoVue = $datos['partida_arancelaria'][$i] . $datos['codigo_complementario_suplementario'][$i];
			//$codigoProductoVue = $datos['codigo_producto_vue'][$i] . (isset($datos['codigo_presentacion'][$i]) == true ? $datos['codigo_presentacion'][$i] : '');
			$codigoProductoVue = $datos['codigo_producto_vue'][$i];
			$idProducto = $datos['id_producto'][$i];
			$idPaisExportacion = $datos['id_pais_exportacion'];
			$identificadorImportador = $datos['identificador_operador'];

			$datosOperacion = $lNegocioOperaciones->buscarLista(array("identificador_operador"=> $identificadorImportador, 
																	"id_pais" => $idPaisExportacion, 
																	"id_producto" => $idProducto, 
																	"id_tipo_operacion" => $idTipoOperacion,
																	"estado" => 'registrado',
																	"subpartida_producto_vue" => $partidaProductoVue,
																	"codigo_producto_vue" => $codigoProductoVue
			));
			
			if(empty($datosOperacion->current())){
				$validacion[0] = false;
				$validacion[1] = 'El importador '.$identificadorImportador.' no tiene registrado el producto con partida '.$partidaProductoVue;
				break;
			}
			
			if($datos['id_area'] == 'SA' || $datos['id_area'] == 'SV'){
				$arrayParametrosRequisitos = array("id_producto" => $idProducto, "id_localizacion"=>$idPaisExportacion, "tipo_requisito"=>'Importación');
				
				$datosRequisito = $lNegocioRequisitosComercializacion->obtenerRequisitoPorProductoTipoRequisitoLocalizacion($arrayParametrosRequisitos);
				
				if(empty($datosRequisito->current())){
					$validacion[0] = false;
					$validacion[1] = 'El producto con partida '.$partidaProductoVue.' se encuentra inactivo para la operación de importación al pais de origen';
					break;
				}
			}

			$arrayParametrosProveedor = array("identificador_operador" => $identificadorImportador, "id_producto"=>$idProducto, "estado"=>"('registrado','registradoObservacion','porCaducar')");
			
			$datosProveedores = $lNegocioOperaciones->buscarOperacionesProveedoresOperadorProducto($arrayParametrosProveedor);
			
			if(empty($datosProveedores->current())){
				$validacion[0] = false;
				$validacion[1] = 'El importador  '.$identificadorImportador.', no posee Proveedores con operaciones en estado registrado para el producto con partida '.$partidaProductoVue;
				break;
			}
		}

		return $validacion;
		
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloImportaciones->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ImportacionesModelo
	 */
	public function buscar($id){
		return $this->modeloImportaciones->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloImportaciones->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloImportaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarImportaciones(){
		$consulta = "SELECT * FROM " . $this->modeloImportaciones->getEsquema() . ". importaciones";
		return $this->modeloImportaciones->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta para actualizacion de datos .
	 *
	 * @param array $datos
	 * @return int
	 */
	public function actualizar(Array $datos){
		
		$tablaModelo = new ImportacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		
		if ($tablaModelo->getIdImportacion() != null && $tablaModelo->getIdImportacion() > 0){
			$this->modeloImportaciones->actualizar($datosBd, $tablaModelo->getIdImportacion());
		}
	}
}

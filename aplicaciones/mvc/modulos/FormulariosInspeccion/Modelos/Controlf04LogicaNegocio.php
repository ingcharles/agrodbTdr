<?php
 /**
 * Lógica del negocio de Controlf04Modelo
 *
 * Este archivo se complementa con el archivo Controlf04Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-02
 * @uses    Controlf04LogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;

use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\FormulariosInspeccion\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\GuardarExcepcionConDatos;
  use Exception;
 
class Controlf04LogicaNegocio implements IModelo 
{

	 private $modeloControlf04 = null;
	 private $lNegocioToken = null;

	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloControlf04 = new Controlf04Modelo();
	 $this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Controlf04Modelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloControlf04->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloControlf04->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloControlf04->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Controlf04Modelo
	*/
	public function buscar($id)
	{
		return $this->modeloControlf04->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloControlf04->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloControlf04->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarControlf04()
	{
	$consulta = "SELECT * FROM ".$this->modeloControlf04->getEsquema().". controlf04";
		 return $this->modeloControlf04->ejecutarSqlNativo($consulta);
	}

	/**
	 * Guardar seguimiento carentenario y ordenes de laboratorio
	 */
	public function guardarSeguimiento(Array $datos, Array $datosLboratorio) {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

			try{
			
				$procesoIngreso = $this->modeloControlf04->getAdapter()
					->getDriver()
					->getConnection();
				$procesoIngreso->beginTransaction();
			
				
				foreach($datos as $registro){

					$statement = $this->modeloControlf04->getAdapter()
					->getDriver()
					->createStatement();

					$campos = array (
						'id_seguimiento_cuarentenario' => $registro['id_seguimiento_cuarentenario'],
						'ruc_operador' => $registro['ruc_operador'],
						'razon_social' => $registro['razon_social'],
						'codigo_pais_origen' => $registro['codigo_pais_origen'],
						'pais_origen' => $registro['pais_origen'],
						'producto' => $registro['producto'],
						'subtipo_producto' => $registro['subtipo'],
						'peso' => $registro['peso'],
						'numero_plantas_ingreso' => $registro['numero_plantas_ingreso'],
						'codigo_provincia' => $registro['codigo_provincia'],
						'provincia' => $registro['provincia'],
						'codigo_canton' => $registro['codigo_canton'],
						'canton' => $registro['canton'],
						'codigo_parroquia' => $registro['codigo_parroquia'],
						'parroquia' => $registro['parroquia'],
						'nombre_scpe' => $registro['nombre_scpe'],
						'tipo_operacion' => $registro['tipo_operacion'],
						'tipo_cuarentena_condicion_produccion' => $registro['tipo_cuarentena_condicion_produccion'],
						'fase_seguimiento' => $registro['fase_seguimiento'],
						'codigo_lote' => $registro['codigo_lote'],
						'numero_seguimientos_planificados' => $registro['numero_seguimientos_planificados'],
						'cantidad_total' => $registro['cantidad_total'],
						'cantidad_vigilada' => $registro['cantidad_vigilada'],
						'actividad' => $registro['actividad'],
						'etapa_cultivo' => $registro['etapa_cultivo'],
						'registro_monitoreo_plagas' => $registro['registro_monitoreo_plagas'],
						'ausencia_plagas' => $registro['ausencia_plagas'],					
						'envio_muestra' => $registro['envio_muestra'],
						'resultado_inspeccion' => $registro['resultado_inspeccion'],
						'numero_plantas_inspeccion' => $registro['numero_plantas_inspeccion'],
						'observaciones' => $registro['observaciones'],
						'usuario' => $registro['usuario'],
						'usuario_id' => $registro['usuario_id'],
						'fecha_inspeccion' => $registro['fecha_creacion'],
						'id_tablet' => $registro['id_tablet'],
						'tablet_id' => $registro['tablet_id'],
						'tablet_version_base' => $registro['tablet_version_base']
					);		

					$tieneNulos = true;

					if ($registro['fase_desarrollo_plaga'] != null){			
						
						$tieneNulos = false;

						$campos+=['cantidad_afectada' => $registro['cantidad_afectada']];
						$campos+=['porcentaje_incidencia' => $registro['porcentaje_incidencia']];
						$campos+=['porcentaje_severidad' => $registro['porcentaje_severidad']];
						$campos+=['fase_desarrollo_plaga' => $registro['fase_desarrollo_plaga']];
						$campos+=['organo_afectado' => $registro['organo_afectado']];
						$campos+=['distribucion_plaga' => $registro['distribucion_plaga']];
						$campos+=['poblacion' => $registro['poblacion']];
						$campos+=['descripcion_sintomas' => $registro['descripcion_sintomas']];
					}
				
					$sqlInsertar = $this->modeloControlf04->guardarSql('controlf04', 'f_inspeccion');
					$sqlInsertar->columns($this->columnasCabecera($tieneNulos));
					$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloControlf04->getAdapter(), $statement);
					$statement->execute();
					$id = $this->modeloControlf04->adapter->driver->getLastGeneratedValue('f_inspeccion'. '.Controlf04_id_seq');
	
					$statement2 = $this->modeloControlf04->getAdapter()
							->getDriver()
							->createStatement();
	
					foreach($datosLboratorio as $orden){
	
						if ($orden['id_padre'] == $registro['id']){
	
							$campos = array(				
								'id_padre' => $id,
								'id_tablet' => $orden['id_tablet'],
								'analisis' => $orden['analisis'],
								'aplicacion_producto_quimico' => $orden['aplicacion_producto_quimico'],
								'codigo_muestra' => $orden['codigo_muestra'],
								'tipo_muestra' => $orden['tipo_muestra'],
								'descripcion_sintomas' => $orden['descripcion_sintomas'],							
								'prediagnostico' => $orden['prediagnostico'],
							);
	
							$sqlInsertar = $this->modeloControlf04->guardarSql('controlf04_detalle_ordenes', 'f_inspeccion');
							$sqlInsertar->columns($this->columnasOrden());
							$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloControlf04->getAdapter(), $statement2);
							$statement2->execute();
							
						}
					}
	
				}
				 
				echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registros almancenados en el Sistema GUIA exitosamente'));
				$procesoIngreso->commit();
			} catch (Exception $ex){
				echo json_encode(array('estado' => 'error', 'mensaje' => $ex->getMessage()));
				$procesoIngreso->rollback();
				throw new GuardarExcepcion($ex, array('origen' => 'Agro servicios', 'ws'=>'RestWsSeguimientoCuarentenarioControlador', 'archivo' => 'Controlf04LogicaNegocio', 'metodo' => 'guardarSeguimiento', 'datos' => $datos, 'datosLaboratorio' => $datosLboratorio));
			}
			
		} else{
			echo json_encode($arrayToken);
		}

	}

	private function columnasCabecera($tieneNulos){
		$campos = array(
			'id_seguimiento_cuarentenario',
			'ruc_operador',
			'razon_social',
			'codigo_pais_origen',
			'pais_origen',
			'producto',
			'subtipo_producto',
			'peso',
			'numero_plantas_ingreso',
			'codigo_provincia',
			'provincia',
			'codigo_canton',
			'canton',
			'codigo_parroquia',
			'parroquia',
			'nombre_scpe',
			'tipo_operacion',
			'tipo_cuarentena_condicion_produccion',
			'fase_seguimiento',
			'codigo_lote',
			'numero_seguimientos_planificados',
			'cantidad_total',
			'cantidad_vigilada',
			'actividad',
			'etapa_cultivo',
			'registro_monitoreo_plagas',
			'ausencia_plagas',	
			'envio_muestra',
			'resultado_inspeccion',
			'numero_plantas_inspeccion',
			'observaciones',
			'usuario',
			'usuario_id',
			'fecha_inspeccion',
			'id_tablet',
			'tablet_id',
			'tablet_version_base'
		);
		   

		if (!$tieneNulos){
		
			$campos[] = 'cantidad_afectada';
			$campos[] = 'porcentaje_incidencia';
			$campos[] = 'porcentaje_severidad';
			$campos[] = 'fase_desarrollo_plaga';
			$campos[] = 'organo_afectado';
			$campos[] = 'distribucion_plaga';
			$campos[] = 'poblacion';
			$campos[] = 'descripcion_sintomas';	

		} 
	
		return $campos;
	}

	private function columnasOrden(){
		return array(
			'id_padre',
			'id_tablet',
			'analisis',
			'aplicacion_producto_quimico',
			'codigo_muestra',
			'tipo_muestra',
			'descripcion_sintomas',			
			'prediagnostico',
		);
	}

}

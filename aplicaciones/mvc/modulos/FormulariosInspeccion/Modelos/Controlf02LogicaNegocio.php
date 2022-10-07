<?php
 /**
 * Lógica del negocio de Controlf02Modelo
 *
 * Este archivo se complementa con el archivo Controlf02Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Controlf02LogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\GuardarExcepcionConDatos;
  use Exception;
 
class Controlf02LogicaNegocio implements IModelo 
{

	 private $modeloControlf02 = null;
	 private $lNegocioToken = null;

	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloControlf02 = new Controlf02Modelo();
	 $this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $arrayIngresos
	* @return int
	*/
	public function guardar(Array $arrayIngresos)
	{
		$tablaModelo = new Controlf02Modelo($arrayIngresos);
		$arrayIngresosBd = $tablaModelo->getPreparararrayIngresos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloControlf02->actualizar($arrayIngresosBd, $tablaModelo->getId());
		} else {
		unset($arrayIngresosBd["id"]);
		return $this->modeloControlf02->guardar($arrayIngresosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloControlf02->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Controlf02Modelo
	*/
	public function buscar($id)
	{
		return $this->modeloControlf02->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloControlf02->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloControlf02->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarControlf02()
	{
	$consulta = "SELECT * FROM ".$this->modeloControlf02->getEsquema().". controlf02";
		 return $this->modeloControlf02->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) para obtener los registros de verificación de tránsito de ingreso.
	 *
	 * Token requerido 
	 * 
	 * @return array|ResultSet
	 */
	public function obtenerTransitoIngreso() {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

			$consulta = "SELECT row_to_json(res) as res FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) as ingresos FROM (
                SELECT                
                    cf02.id AS id_ingreso,
                    cf02.nombre_razon_social,
                    cf02.ruc_ci,
                    cf02.pais_origen,
                    cf02.pais_procedencia,
                    cf02.pais_destino,
                    cf02.punto_ingreso, 
                    cf02.punto_salida,
                    cf02.placa_vehiculo,
                    cf02.dda,
                    cf02.precinto_sticker AS precintoSticker,
                    to_char(cf02.fecha_ingreso, 'YYYY-MM-DD') AS fecha_ingreso,
                    ( 
                    SELECT array_to_json(array_agg(row_to_json(l_a))) FROM (
                        SELECT
                            cf02dp.id id_producto,
                            cf02dp.partida_arancelaria,
                            cf02dp.producto,
                            cf02dp.cantidad,
                            cf02dp.tipo_envase,
							cf02dp.id_padre
                        FROM
                            f_inspeccion.controlf02_detalle_productos cf02dp
                        WHERE
                            cf02.id = cf02dp.id_padre
					) l_a) AS formularioIngresoTransitoProductoList
                FROM
                    f_inspeccion.controlf02 cf02
                WHERE
                    cf02.estado = 'Ingreso'
            ) as listado ) AS res;";
			
			try {
				$res = $this->modeloControlf02->ejecutarSqlNativo($consulta);
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los arrayIngresos han sido obtenidos satisfactoriamente";			
				$array['cuerpo'] = json_decode($res->current()->res);
				echo json_encode($array);
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener arrayIngresos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
			}

		} else{
			echo json_encode($arrayToken);
		}
	}

	/**
	 * Método para actualizar los registros de salida de tránsito.
	 */
	public function actualizarSalida(Array $arrayIngresos) {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){
			
			try{
				
				$procesoIngreso = $this->modeloControlf02->getAdapter()
					->getDriver()
					->getConnection();

				$procesoIngreso->beginTransaction();

				$statement = $this->modeloControlf02->getAdapter()
					->getDriver()
					->createStatement();

				foreach($arrayIngresos as $registro){

					$campos = array (
						'estado_precinto' => $registro['estado_precinto'],
						'tipo_verificacion' => $registro['tipo_verificacion'],
						'estado' => 'Salida',
						'fecha_salida' => $registro['fecha_salida'],
						'usuario_id_salida' => $registro['usuario_id_salida'],
						'usuario_salida' => $registro['usuario_salida'],
						'tablet_id_salida' => $registro['tablet_id_salida'],
						'tablet_version_base_salida' => $registro['tablet_version_base_salida']	
					);

					$sqlActualizar = $this->modeloControlf02->actualizarSql('controlf02', 'f_inspeccion');
					$sqlActualizar->set($campos);
					$sqlActualizar->where(array('id' => $registro['id_ingreso']));
					$sqlActualizar->prepareStatement($this->modeloControlf02->getAdapter(), $statement);
					$statement->execute();
				}

				echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registros almacenados en el Sistema GUIA exitosamente'));
				$procesoIngreso->commit();
			} catch (Exception $ex){
				echo json_encode(array('estado' => 'error', 'mensaje' => $ex->getMessage()));
				$procesoIngreso->rollback();
				throw new GuardarExcepcionConDatos($ex);
			}

		} else{
			echo json_encode($arrayToken);
		}

	}


	/**
	 * Guardar transito ingreso
	 */
	public function guardarIngresos(Array $arrayIngresos, Array $arrayProductos) {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

			try{
			
				$procesoIngreso = $this->modeloControlf02->getAdapter()
					->getDriver()
					->getConnection();
				$procesoIngreso->beginTransaction();
	
				$statement = $this->modeloControlf02->getAdapter()
					->getDriver()
					->createStatement();
				
				
				foreach($arrayIngresos as $registro){					

					$campos = array (						
						'nombre_razon_social' => $registro['nombre_razon_social'],
						'ruc_ci' => $registro['ruc_ci'],
						'id_pais_origen' => $registro['id_pais_origen'],
						'pais_origen' => $registro['pais_origen'],
						'id_pais_procedencia' => $registro['id_pais_procedencia'],
						'pais_procedencia' => $registro['pais_procedencia'],
						'id_pais_destino' => $registro['id_pais_destino'],
						'pais_destino' => $registro['pais_destino'],
						'id_punto_ingreso' => $registro['id_punto_ingreso'],
						'punto_ingreso' => $registro['punto_ingreso'],
						'id_punto_salida' => $registro['id_punto_salida'],
						'punto_salida' => $registro['punto_salida'],
						'placa_vehiculo' => $registro['placa_vehiculo'],
						'dda' => $registro['dda'],
						'precinto_sticker' => $registro['precinto_sticker'],
						'estado' => 'Ingreso',
						'usuario_ingreso' => $registro['usuario_ingreso'],
						'usuario_id_ingreso' => $registro['usuario_id_ingreso'],
						'fecha_ingreso' => $registro['fecha_ingreso'],
						'id_tablet' => $registro['id_tablet'],
						'tablet_version_base_ingreso' => $registro['tablet_version_base_ingreso'],
					);		
				
					$sqlInsertar = $this->modeloControlf02->guardarSql('controlf02', $this->modeloControlf02->getEsquema());
					$sqlInsertar->columns($this->columnasCabecera());
					$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloControlf02->getAdapter(), $statement);
					$statement->execute();
					$id = $this->modeloControlf02->adapter->driver->getLastGeneratedValue($this->modeloControlf02->getEsquema() . '.Controlf02_id_seq');
	
					$statement2 = $this->modeloControlf02->getAdapter()
							->getDriver()
							->createStatement();
	
					foreach($arrayProductos as $producto){
	
						if ($producto['id_padre'] == $registro['id']){
	
							$campos = array(				
								'id_padre' => $id,
								'id_tablet' => $producto['id_tablet'],
								'partida_arancelaria' => $producto['partida_arancelaria'],
								'producto' => $producto['descripcion_producto'],
								'subtipo' => $producto['subtipo'],
								'cantidad' => $producto['cantidad'],
								'tipo_envase' => $producto['tipo_envase'],
							);
	
							$sqlInsertar = $this->modeloControlf02->guardarSql('controlf02_detalle_productos', 'f_inspeccion');
							$sqlInsertar->columns($this->columnasOrden());
							$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloControlf02->getAdapter(), $statement2);
							$statement2->execute();
							
						}
					}
	
				}
				 
				echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registros almancenados en el Sistema GUIA exitosamente'));
				$procesoIngreso->commit();
			} catch (Exception $ex){
				echo json_encode(array('estado' => 'error', 'mensaje' => $ex->getMessage()));
				$procesoIngreso->rollback();
				throw new GuardarExcepcionConDatos($ex);
			}
			
		} else{
			echo json_encode($arrayToken);
		}

	}

	private function columnasCabecera(){
		return array(		
			'nombre_razon_social',
			'ruc_ci',
			'id_pais_origen',
			'pais_origen',
			'id_pais_procedencia',
			'pais_procedencia',
			'id_pais_destino',
			'pais_destino',
			'id_punto_ingreso',
			'punto_ingreso',
			'id_punto_salida',
			'punto_salida',
			'placa_vehiculo',
			'dda',
			'precinto_sticker',
			'estado',
			'usuario_ingreso',
			'usuario_id_ingreso',
			'fecha_ingreso',
			'id_tablet',
			'tablet_version_base_ingreso',
		);
	}

	private function columnasOrden(){
		return array(
			'id_padre',
			'id_tablet',
			'partida_arancelaria',
			'producto',
			'subtipo',
			'cantidad',
			'tipo_envase',
		);
	}


}

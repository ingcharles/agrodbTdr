<?php
 /**
 * Lógica del negocio de Controlf01Modelo
 *
 * Este archivo se complementa con el archivo Controlf01Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Controlf01LogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\BuscarExcepcion;
  use Agrodb\Core\Excepciones\GuardarExcepcion;
  use Exception;
 
class Controlf01LogicaNegocio implements IModelo 
{

	 private $modeloControlf01 = null;
	 private $lNegocioToken = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloControlf01 = new Controlf01Modelo();
	 $this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Controlf01Modelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloControlf01->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloControlf01->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloControlf01->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Controlf01Modelo
	*/
	public function buscar($id)
	{
		return $this->modeloControlf01->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloControlf01->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloControlf01->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarControlf01()
	{
	$consulta = "SELECT * FROM ".$this->modeloControlf01->getEsquema().". controlf01";
		 return $this->modeloControlf01->ejecutarSqlNativo($consulta);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada.
	*
	* @return array|ResultSet	
	*/
	public function obtenerProductosImportados($arrayParametros)
	{
		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

			$consulta = "SELECT row_to_json(res) as res FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) as productos_importados FROM (
				SELECT
					dda.permiso_importacion AS pfi,
					pi.estado,
					dda.id_vue AS dda,
					o.razon_social, 
					dda.pais_exportacion AS pais_origen,
					dda.tipo_certificado,
					(
							SELECT array_to_json(array_agg(row_to_json(l_a))) FROM (
								SELECT ip.nombre_producto as nombre, 
									ip.peso::Text AS cantidad_declarada, 
									ip.unidad_peso AS unidad,
									sp.nombre AS subtipo
								FROM g_importaciones.importaciones_productos ip,
								g_catalogos.productos p,
								g_catalogos.subtipo_productos sp,
								g_dda.destinacion_aduanera_productos dap
								WHERE pi.id_importacion = ip.id_importacion AND 
								ip.id_producto = p.id_producto AND 
								p.id_subtipo_producto = sp.id_subtipo_producto AND
								dap.id_destinacion_aduanera = dda.id_destinacion_aduanera AND
								ip.id_producto = dap.id_producto
					) l_a) AS productos
				FROM
					g_dda.destinacion_aduanera dda
					INNER JOIN g_catalogos.lugares_inspeccion li ON dda.lugar_inspeccion = li.id_lugar
					INNER JOIN g_catalogos.puertos pu ON dda.id_puerto_destino = pu.id_puerto,
					g_importaciones.importaciones pi,
					g_operadores.operadores o
				WHERE
					pi.id_vue = dda.permiso_importacion AND
					pi.identificador_operador = o.identificador AND
					dda.contador_inspeccion = 1 AND
					dda.estado = 'inspeccion' AND
					dda.tipo_certificado = '".$arrayParametros['tipo_certificado']."' AND
					upper(li.nombre_provincia) = upper('".$arrayParametros['provincia']."') AND
					upper(li.nombre_provincia) = upper(pu.nombre_provincia)
			) as listado ) AS res;";

			try {
				$res = $this->modeloControlf01->ejecutarSqlNativo($consulta);
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";				
				$array['cuerpo'] = json_decode($res->current()->res);
				http_response_code(200);
				echo json_encode($array);
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
				throw new BuscarExcepcion($ex, array('origen' => 'Agro servicios', 'archivo' => 'Controlf01LogicaNegocio', 'metodo' => 'obtenerProductosImportados', 'consulta' => $consulta));
			}
		} else{
			echo json_encode($arrayToken);
		}

	}

	/**
	 * Guardar inspeccion productos importados cabecera y detalle
	 * @param array $datos array completo del json
	 * @return json
	 */
	public function guardarInspeccion(Array $datos, Array $datosInspeccion, Array $datosLotes, Array $datosLaboratorio, Array $datosProductos) {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

			try{
			
				$procesoIngreso = $this->modeloControlf01->getAdapter()
					->getDriver()
					->getConnection();
				$procesoIngreso->beginTransaction();
				
				foreach($datosInspeccion as $registro){

					$statement = $this->modeloControlf01->getAdapter()
					->getDriver()
					->createStatement();

					$campos = array (
						'id_tablet' => $registro['id_tablet'],
						'dda' => $registro['dda'],
						'pfi' => $registro['pfi'],
						'observaciones' => $registro['observaciones'],
						'dictamen_final' => $registro['dictamen_final'],
						'envio_muestra' => $registro['envio_muestra'],
						'usuario_id' => $registro['usuario_id'],
						'usuario' => $registro['usuario'],
						'fecha_inspeccion' => $registro['fecha_inspeccion'],
						'tablet_id' => $registro['tablet_id'],
						'tablet_version_base' => $registro['tablet_version_base'],
						'pregunta01' => $registro['pregunta01'],
						'pregunta02' => $registro['pregunta02'],
						'pregunta03' => $registro['pregunta03'],
						'pregunta04' => $registro['pregunta04'],
						'pregunta05' => $registro['pregunta05'],
						'pregunta06' => $registro['pregunta06'],
						'pregunta07' => $registro['pregunta07'],
						'pregunta08' => $registro['pregunta08'],
						'pregunta09' => $registro['pregunta09'],
						'pregunta10' => $registro['pregunta10'],
						'pregunta11' => $registro['pregunta11'],
						'categoria_riesgo' => $registro['categoria_riesgo'],
						'seguimiento_cuarentenario' => $registro['seguimiento_cuarentenario'],
						'provincia' => $registro['provincia'],
						'peso_ingreso' => $registro['peso_ingreso'],
						'numero_embalajes_envio' => $registro['numero_embalajes_envio'],
						'numero_embalajes_inspeccionados' => $registro['numero_embalajes_inspeccionados'],
					);

					if($registro['pregunta03'] == null) unset($campos['pregunta03']);
					if($registro['pregunta04'] == null) unset($campos['pregunta04']);
					if($registro['pregunta05'] == null) unset($campos['pregunta05']);
					if($registro['pregunta06'] == null) unset($campos['pregunta06']);
					if($registro['pregunta07'] == null) unset($campos['pregunta07']);
					if($registro['pregunta08'] == null) unset($campos['pregunta08']);
					if($registro['numero_embalajes_envio'] == null) unset($campos['numero_embalajes_envio']);
					if($registro['numero_embalajes_inspeccionados'] == null) unset($campos['numero_embalajes_inspeccionados']);
					if($registro['observaciones'] == null ) unset($campos["observaciones"]);

					$arrayColumnasCabecera = array_keys($campos);
				
					$sqlInsertar = $this->modeloControlf01->guardarSql('controlf01', $this->modeloControlf01->getEsquema());
					$sqlInsertar->columns($arrayColumnasCabecera);
					$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloControlf01->getAdapter(), $statement);
					$statement->execute();
					$id = $this->modeloControlf01->adapter->driver->getLastGeneratedValue($this->modeloControlf01->getEsquema() . '.Controlf01_id_seq');

					foreach($datosLotes as $lote){

						$statement = $this->modeloControlf01->getAdapter()
						->getDriver()
						->createStatement();
	
						if ($lote['id_padre'] == $registro['id']){

							$campos = array(
								'id_padre' => $id,
								'id_tablet' => $lote['id_tablet'],
								'descripcion' => $lote['descripcion'],
								'numero_cajas' => $lote['numero_cajas'],
								'cajas_muestra' => $lote['cajas_muestra'],
								'porcentaje_inspeccion' => $lote['porcentaje_inspeccion'],
								'ausencia_suelo' => $lote['ausencia_suelo'],
								'ausencia_contaminantes' => $lote['ausencia_contaminantes'],
								'ausencia_sintomas' => $lote['ausencia_sintomas'],
								'ausencia_plagas' => $lote['ausencia_plagas'],
								'dictamen' => $lote['dictamen'],
							);

							$arrayColumnasCabecera = array_keys($campos);
	
							$sqlInsertar = $this->modeloControlf01->guardarSql('controlf01_detalle_lotes', 'f_inspeccion');
							$sqlInsertar->columns($arrayColumnasCabecera);
							$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloControlf01->getAdapter(), $statement);
							$statement->execute();
							
						}
					}
	
					foreach($datosLaboratorio as $orden){

						$statement = $this->modeloControlf01->getAdapter()
						->getDriver()
						->createStatement();
	
						if ($orden['id_padre'] == $registro['id']){
	
							$campos = array(
								'id_padre' => $id,
								'id_tablet' => $orden['id_tablet'],
								'actividad_origen' => $orden['actividad_origen'],
								'analisis' => $orden['analisis'],
								'codigo_muestra' => $orden['codigo_muestra'],
								'conservacion' => $orden['conservacion'],
								'tipo_muestra' => $orden['tipo_muestra'],
								'descripcion_sintomas' => $orden['descripcion_sintomas'],
								'fase_fenologica' => $orden['fase_fenologica'],
								'nombre_producto' => $orden['nombre_producto'],
								'peso_muestra' => $orden['peso_muestra'],
								'prediagnostico' => $orden['prediagnostico'],
								'tipo_cliente' => $orden['tipo_cliente'],
							);

							$arrayColumnasCabecera = array_keys($campos);
	
							$sqlInsertar = $this->modeloControlf01->guardarSql('controlf01_detalle_ordenes', 'f_inspeccion');
							$sqlInsertar->columns($arrayColumnasCabecera);
							$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloControlf01->getAdapter(), $statement);
							$statement->execute();
							
						}
					}

					foreach($datosProductos as $producto){

						$statement = $this->modeloControlf01->getAdapter()
						->getDriver()
						->createStatement();
	
						if ($producto['id_padre'] == $registro['id']){
	
							$campos = array(
								'id_padre' => $id,
								'id_tablet' => $producto['id_tablet'],
								'nombre' => $producto['nombre'],
								'cantidad_declarada' => $producto['cantidad_declarada'],
								'cantidad_ingresada' => $producto['cantidad_ingresada'],
								'unidad' => $producto['unidad'],
								'subtipo' => $producto['subtipo'],
							);

							$arrayColumnasCabecera = array_keys($campos);
	
							$sqlInsertar = $this->modeloControlf01->guardarSql('controlf01_detalle_productos_ingresados', 'f_inspeccion');
							$sqlInsertar->columns($arrayColumnasCabecera);
							$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloControlf01->getAdapter(), $statement);
							$statement->execute();
							
						}
					}
	
				}
				 
				echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registros almacenados en el Sistema GUIA exitosamente'));
				$procesoIngreso->commit();
			} catch (Exception $ex){
				echo json_encode(array('estado' => 'error', 'mensaje' => $ex->getMessage()));
				$procesoIngreso->rollback();
				throw new GuardarExcepcion($ex, array('origen' => 'Agro servicios', 'ws'=>'RestWsProductosImportadosControlador', 'archivo' => 'Controlf01LogicaNegocio', 'metodo' => 'guardarInspeccion', 'datos' => $datos));
			}
			
		} else{
			echo json_encode($arrayToken);
		}

	}

}

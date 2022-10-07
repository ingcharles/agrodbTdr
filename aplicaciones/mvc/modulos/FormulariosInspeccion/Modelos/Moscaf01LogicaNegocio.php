<?php

/**
 * Lógica del negocio de Moscaf01Modelo
 *
 * Este archivo se complementa con el archivo Moscaf01Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Moscaf01LogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */

namespace Agrodb\FormulariosInspeccion\Modelos;

use Agrodb\FormulariosInspeccion\Modelos\IModelo;
use Agrodb\Core\Excepciones\BuscarExcepcion;
use Agrodb\Core\Excepciones\GuardarExcepcionConDatos;
use Agrodb\Core\Excepciones\GuardarExcepcion;

use Agrodb\Token\Modelos\TokenLogicaNegocio;
use Exception;


class Moscaf01LogicaNegocio implements IModelo
{

	private $modeloMoscaf01 = null;
	private $lNegocioToken = null;

	/**
	 * Constructor
	 * 
	 * @retorna void
	 */
	public function __construct()
	{
		$this->modeloMoscaf01 = new Moscaf01Modelo();
		$this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardar(array $datos)
	{
		$tablaModelo = new Moscaf01Modelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
			return $this->modeloMoscaf01->actualizar($datosBd, $tablaModelo->getId());
		} else {
			unset($datosBd["id"]);
			return $this->modeloMoscaf01->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 * @param string Where|array $where
	 * @return int
	 */
	public function borrar($id)
	{
		$this->modeloMoscaf01->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param  int $id
	 * @return Moscaf01Modelo
	 */
	public function buscar($id)
	{
		return $this->modeloMoscaf01->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo()
	{
		return $this->modeloMoscaf01->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null)
	{
		return $this->modeloMoscaf01->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarMoscaf01()
	{
		$consulta = "SELECT * FROM " . $this->modeloMoscaf01->getEsquema() . ". moscaf01";
		return $this->modeloMoscaf01->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta consulta(SQL), para la obtención de las rutas de trampas.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerRutasTrampas($arrayParametros)
	{
		
		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);
		
		if ($arrayToken['estado'] == 'exito') {
			$res = null;

			$consulta = "SELECT row_to_json (res) as res FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) as cuerpo FROM (
				SELECT 
					adt.id_administracion_trampa as id_trampa,
					adt.id_provincia,
					lpr.nombre AS provincia,
					adt.id_canton,
					lca.nombre AS canton,
					adt.id_parroquia,
					lpa.nombre AS parroquia,
					adt.id_lugar_instalacion,
					li.nombre_lugar_instalacion,
					adt.numero_lugar_instalacion,
					adt.id_tipo_atrayente ,
					ta.nombre_tipo_atrayente,
					tt.nombre_tipo_trampa,
					adt.codigo_trampa ,
					adt.coordenadax,
					adt.coordenaday,
					adt.coordenadaz,
					to_char(adt.fecha_instalacion_trampa, 'YYYY-MM-DD') fecha_instalacion,
					adt.estado_trampa,
					(CASE WHEN tso.secuencialorden IS NULL THEN 0 ELSE tso.secuencialorden END)::INT,						
					tfi.fecha_inspeccion
				FROM
					g_administracion_trampas.administracion_trampas adt
					INNER JOIN g_catalogos.localizacion lpr ON lpr.id_localizacion = adt.id_provincia
					INNER JOIN g_catalogos.localizacion lca ON lca.id_localizacion = adt.id_canton
					INNER JOIN g_catalogos.localizacion lpa ON lpa.id_localizacion = adt.id_parroquia
					INNER JOIN g_catalogos.lugar_instalacion li ON li.id_lugar_instalacion = adt.id_lugar_instalacion
					INNER JOIN g_catalogos.tipo_atrayente ta ON ta.id_tipo_atrayente = adt.id_tipo_atrayente
					INNER JOIN g_catalogos.tipo_trampa tt ON tt.id_tipo_trampa = adt.id_tipo_trampa
					LEFT JOIN (SELECT COUNT (codigo_trampa_padre) AS secuencialorden, codigo_trampa_padre FROM f_inspeccion.moscaf01_detalle_ordenes GROUP BY codigo_trampa_padre) tso ON tso.codigo_trampa_padre = adt.codigo_trampa
					LEFT JOIN (SELECT MAX (fecha_inspeccion) AS fecha_inspeccion, codigo_trampa FROM f_inspeccion.moscaf01_detalle_trampas GROUP BY codigo_trampa) tfi ON tfi.codigo_trampa = adt.codigo_trampa
				WHERE
					adt.id_area_trampa =  2-- Área de programa de mosca
					AND adt.estado_trampa = 'activo'
					AND lpr.id_localizacion in (" . $arrayParametros['provincia'] . ")
					ORDER BY 1
				) as listado ) AS res;";

			$array = array();

			try {
				$res = $this->modeloMoscaf01->ejecutarSqlNativo($consulta);
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";
				$cuerpo = json_decode($res->current()->res, true);
				$array['cuerpo'] = $cuerpo['cuerpo'];
				echo json_encode($array);
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
				throw new BuscarExcepcion($ex, array('archivo' => 'Moscaf01LogicaNegocio', 'metodo' => 'obtenerRutasTrampas', 'consulta' => $consulta));
			}
		} else{
			echo json_encode($arrayToken);
		}
	}

	public function guardarTrampasMosca($cabecera, $trampas, $ordenes)
	{

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

			try {

				$procesoIngreso = $this->modeloMoscaf01->getAdapter()
					->getDriver()
					->getConnection();
				$procesoIngreso->beginTransaction();
	
				$statement = $this->modeloMoscaf01->getAdapter()
					->getDriver()
					->createStatement();
				
				foreach ($cabecera as $registro) {
	
					$campos = array(
						"id_tablet" => $registro['id_tablet'],
						"fecha_inspeccion" => $registro['fecha_inspeccion'],
						"usuario_id" => $registro['usuario_id'],
						"usuario" => $registro['usuario'],
						"tablet_id" => $registro['tablet_id'],
						"tablet_version_base" => $registro['tablet_version_base'],
					);
	
					$sqlInsertar = $this->modeloMoscaf01->guardarSql('moscaf01', $this->modeloMoscaf01->getEsquema());
					$sqlInsertar->columns($this->columnasPadre());
					$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloMoscaf01->getAdapter(), $statement);
					$statement->execute();
					$id = $this->modeloMoscaf01->adapter->driver->getLastGeneratedValue($this->modeloMoscaf01->getEsquema() . '.moscaf01_id_seq');
	
					$statement2 = $this->modeloMoscaf01->getAdapter()
						->getDriver()
						->createStatement();
	
					foreach ($trampas as $registroTrampa) {
	
						if ($registroTrampa['id_padre'] == $registro['id']) {
							
							$condicion = $registroTrampa['condicion'] == null ? '' : $registroTrampa['condicion'];
							$cambioTrampa = $registroTrampa['cambio_trampa'] == null ? '' : $registroTrampa['cambio_trampa'];
							$cambioPlug = $registroTrampa['cambio_plug'] == null ? '' : $registroTrampa['cambio_plug'];
							$especiePrincipal = $registroTrampa['especie_principal'] == null ? '' : $registroTrampa['especie_principal'];
							$estadoFenologicoPrincipal = $registroTrampa['estado_fenologico_principal'] == null ? '' : $registroTrampa['estado_fenologico_principal'];
							$especieColindante = $registroTrampa['especie_colindante'] == null ? '' : $registroTrampa['especie_colindante'];
							$estadoFenologicoColindante = $registroTrampa['estado_fenologico_colindante'] == null ? '' : $registroTrampa['estado_fenologico_colindante'];
							$numeroEspecimenes = $registroTrampa['numero_especimenes'] == null ? 0 : $registroTrampa['numero_especimenes'];
							$observaciones = $registroTrampa['observaciones'] == null ? '' : $registroTrampa['observaciones'];
							$envioMuestra = $registroTrampa['envio_muestra'] == null ? '' : $registroTrampa['envio_muestra'];
							
							$campos = array(
								'id_padre' => $id,
								'id_tablet' => $registroTrampa['id_tablet'],
								'id_provincia' => $registroTrampa['id_provincia'],
								'nombre_provincia' => $registroTrampa['nombre_provincia'],
								'id_canton' => $registroTrampa['id_canton'],
								'nombre_canton' => $registroTrampa['nombre_canton'],
								'id_parroquia' => $registroTrampa['id_parroquia'],
								'nombre_parroquia' => $registroTrampa['nombre_parroquia'],
								'id_lugar_instalacion' => $registroTrampa['id_lugar_instalacion'],
								'nombre_lugar_instalacion' => $registroTrampa['nombre_lugar_instalacion'],
								'numero_lugar_instalacion' => $registroTrampa['numero_lugar_instalacion'],
								'id_tipo_atrayente' => $registroTrampa['id_tipo_atrayente'],
								'nombre_tipo_atrayente' => $registroTrampa['nombre_tipo_atrayente'],
								'tipo_trampa' => $registroTrampa['tipo_trampa'],
								'codigo_trampa' => $registroTrampa['codigo_trampa'],
								'semana' => $registroTrampa['semana'],
								'coordenada_x' => $registroTrampa['coordenada_x'],
								'coordenada_y' => $registroTrampa['coordenada_y'],
								'coordenada_z' => $registroTrampa['coordenada_z'],
								'fecha_instalacion' => $registroTrampa['fecha_instalacion'],
								'estado_trampa' => $registroTrampa['estado_trampa'],
								'exposicion' => $registroTrampa['exposicion'],
								'condicion' => $condicion,
								'cambio_trampa' => $cambioTrampa,
								'cambio_plug' => $cambioPlug,
								'especie_principal' => $especiePrincipal,
								'estado_fenologico_principal' => $estadoFenologicoPrincipal,
								'especie_colindante' => $especieColindante,
								'estado_fenologico_colindante' => $estadoFenologicoColindante,
								'numero_especimenes' => $numeroEspecimenes,
								'observaciones' => $observaciones,
								'envio_muestra' => $envioMuestra,
								'estado_registro' => $registroTrampa['estado_registro'],
								'fecha_inspeccion' => $registroTrampa['fecha_inspeccion'],
								'usuario_id' => $registroTrampa['usuario_id'],
								'usuario' => $registroTrampa['usuario'],
								'tablet_id' => $registroTrampa['tablet_id'],
								'tablet_version_base' => $registroTrampa['tablet_version_base'],
							);
	
							$sqlInsertar = $this->modeloMoscaf01->guardarSql('moscaf01_detalle_trampas', 'f_inspeccion');
							$sqlInsertar->columns($this->columnasTrampas());
							$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloMoscaf01->getAdapter(), $statement2);
							$statement2->execute();
						}
					}
	
					$statement3 = $this->modeloMoscaf01->getAdapter()
						->getDriver()
						->createStatement();
	
					foreach ($ordenes as $orden) {
	
						if ($orden['id_padre'] == $registro['id']) {

							$analisis =  $orden['analisis'] == null ? '' : $orden['analisis'];
	
							$campos = array(
								'id_padre' => $id,
								'id_tablet' => $orden['id_tablet'],
								'analisis' => $analisis,
								'codigo_muestra' => $orden['codigo_muestra'],
								'tipo_muestra' => $orden['tipo_muestra'],
								'codigo_trampa_padre' => $orden['codigo_trampa_padre']
							);
	
							$sqlInsertar = $this->modeloMoscaf01->guardarSql('moscaf01_detalle_ordenes', 'f_inspeccion');
							$sqlInsertar->columns($this->columnasOrdenes());
							$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloMoscaf01->getAdapter(), $statement3);
							$statement3->execute();
						}
					}
				}
	
				echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registros almacenados en el Sistema GUIA exitosamente'));
				$procesoIngreso->commit();
			} catch (Exception $ex) {
				echo json_encode(array('estado' => 'error', 'mensaje' => $ex->getMessage()));
				$procesoIngreso->rollback();
				throw new GuardarExcepcionConDatos($ex);
			}

		} else{
			echo json_encode($arrayToken);
		}
		
	}

	private function columnasPadre()
	{
		return array(
			'id_tablet',
			'fecha_inspeccion',
			'usuario_id',
			'usuario',
			'tablet_id',
			'tablet_version_base',
		);
	}

	private function columnasTrampas()
	{
		return array(
			'id_padre',
			'id_tablet',
			'id_provincia',
			'nombre_provincia',
			'id_canton',
			'nombre_canton',
			'id_parroquia',
			'nombre_parroquia',
			'id_lugar_instalacion',
			'nombre_lugar_instalacion',
			'numero_lugar_instalacion',
			'id_tipo_atrayente',
			'nombre_tipo_atrayente',
			'tipo_trampa',
			'codigo_trampa',
			'semana',
			'coordenada_x',
			'coordenada_y',
			'coordenada_z',
			'fecha_instalacion',
			'estado_trampa',
			'exposicion',
			'condicion',
			'cambio_trampa',
			'cambio_plug',
			'especie_principal',
			'estado_fenologico_principal',
			'especie_colindante',
			'estado_fenologico_colindante',
			'numero_especimenes',
			'observaciones',
			'envio_muestra',
			'estado_registro',
			'fecha_inspeccion',
			'usuario_id',
			'usuario',
			'tablet_id',
			'tablet_version_base',
		);
	}

	private function columnasOrdenes()
	{
		return array(
			'id_padre',
			'id_tablet',
			'analisis',
			'codigo_muestra',
			'tipo_muestra',
			'codigo_trampa_padre',
		);
	}
}

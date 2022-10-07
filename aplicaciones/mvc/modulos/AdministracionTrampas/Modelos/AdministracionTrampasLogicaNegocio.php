<?php

/**
 * Lógica del negocio de AdministracionTrampasModelo
 *
 * Este archivo se complementa con el archivo AdministracionTrampasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    AdministracionTrampasLogicaNegocio
 * @package AdministracionTrampas
 * @subpackage Modelos
 */

namespace Agrodb\AdministracionTrampas\Modelos;

use Agrodb\AdministracionTrampas\Modelos\IModelo;
use Agrodb\Core\Excepciones\BuscarExcepcion;
use Agrodb\Core\Excepciones\GuardarExcepcionConDatos;
use Agrodb\Token\Modelos\TokenLogicaNegocio;
use Exception;

class AdministracionTrampasLogicaNegocio implements IModelo
{

	private $modeloAdministracionTrampas = null;
	private $lNegocioToken = null;


	/**
	 * Constructor
	 * 
	 * @retorna void
	 */
	public function __construct()
	{
		$this->modeloAdministracionTrampas = new AdministracionTrampasModelo();
		$this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardar(array $datos)
	{
		$tablaModelo = new AdministracionTrampasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAdministracionTrampa() != null && $tablaModelo->getIdAdministracionTrampa() > 0) {
			return $this->modeloAdministracionTrampas->actualizar($datosBd, $tablaModelo->getIdAdministracionTrampa());
		} else {
			unset($datosBd["id_administracion_trampa"]);
			return $this->modeloAdministracionTrampas->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 * @param string Where|array $where
	 * @return int
	 */
	public function borrar($id)
	{
		$this->modeloAdministracionTrampas->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param  int $id
	 * @return AdministracionTrampasModelo
	 */
	public function buscar($id)
	{
		return $this->modeloAdministracionTrampas->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo()
	{
		return $this->modeloAdministracionTrampas->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null)
	{
		return $this->modeloAdministracionTrampas->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAdministracionTrampas()
	{
		$consulta = "SELECT * FROM " . $this->modeloAdministracionTrampas->getEsquema() . ". administracion_trampas";
		return $this->modeloAdministracionTrampas->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta consulta(SQL), para la obtención de las rutas de trampas.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerRutasTrampasVigilancia($arrayParametros)
	{

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if ($arrayToken['estado'] == 'exito') {

			$arrayParametros['provincia'];			

			$consulta = "SELECT row_to_json (res) as res FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) as cuerpo FROM (
				SELECT 
					adt.id_administracion_trampa as idTrampa,
					adt.id_provincia AS idProvincia,
					lpr.nombre AS provincia,
					adt.id_canton AS idCanton,
					lca.nombre AS canton,
					adt.id_parroquia AS idParroquia,
					lpa.nombre AS parroquia,
					adt.id_lugar_instalacion AS idLugarInstalacion,
					li.nombre_lugar_instalacion AS lugarInstalacion,
					adt.numero_lugar_instalacion AS numeroLugarInstalacion,
					tt.nombre_tipo_trampa AS tipoTrampa,
					adt.codigo_trampa  AS codigoTrampa,
					adt.coordenadax AS coordenadaX,
					adt.coordenaday AS coordenadaY,
					adt.coordenadaz AS coordenadaZ,
					to_char(adt.fecha_instalacion_trampa, 'YYYY-MM-DD') AS fechaInstalacion,
					adt.estado_trampa AS estadoTrampa,
					pl.nombre_plaga AS nombrePlaga,
					(CASE WHEN tso.secuencialorden IS NULL THEN 0 ELSE tso.secuencialorden END)::INT,						
					tfi.fecha_inspeccion					
				FROM
					g_administracion_trampas.administracion_trampas adt
					INNER JOIN g_catalogos.plaga pl ON adt.id_plaga = pl.id_plaga
					INNER JOIN g_catalogos.localizacion lpr ON lpr.id_localizacion = adt.id_provincia
					INNER JOIN g_catalogos.localizacion lca ON lca.id_localizacion = adt.id_canton
					INNER JOIN g_catalogos.localizacion lpa ON lpa.id_localizacion = adt.id_parroquia
					INNER JOIN g_catalogos.lugar_instalacion li ON li.id_lugar_instalacion = adt.id_lugar_instalacion
					INNER JOIN g_catalogos.tipo_trampa tt ON tt.id_tipo_trampa = adt.id_tipo_trampa
					LEFT JOIN (SELECT COUNT (codigo_trampa_padre) AS secuencialorden, codigo_trampa_padre FROM f_inspeccion.vigilanciaf01_detalle_ordenes GROUP BY codigo_trampa_padre) tso ON tso.codigo_trampa_padre = adt.codigo_trampa
					LEFT JOIN (SELECT MAX (fecha_inspeccion) AS fecha_inspeccion, codigo_trampa FROM f_inspeccion.vigilanciaf01_detalle_trampas GROUP BY codigo_trampa) tfi ON tfi.codigo_trampa = adt.codigo_trampa
				WHERE
					adt.id_area_trampa = 1
					AND adt.estado_trampa = 'activo'             
					AND lpr.id_localizacion in (" . $arrayParametros['provincia'] . ")
				ORDER BY 1
			) as listado ) AS res;";

			try {
				$res = $this->modeloAdministracionTrampas->ejecutarSqlNativo($consulta);
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
				throw new BuscarExcepcion($ex, array('archivo' => 'AdministracionTrampasLogicaNegocio', 'metodo' => 'obtenerRutasTrampasVigilancia', 'consulta' => $consulta));
			}
		} else {
			echo json_encode($arrayToken);
		}
	}


	public function guardarTrampaPadreRegistro($arrayParametros)
	{

		$consulta = "INSERT INTO f_inspeccion.vigilanciaf01(
             id_tablet, fecha_inspeccion, usuario_id, usuario, tablet_id, 
            tablet_version_base, fecha_ingreso_guia)
    VALUES (" . $arrayParametros['id_tablet'] . ", '" . $arrayParametros['fecha_inspeccion'] . "', 
			'" . $arrayParametros['usuario_id'] . "', '" . $arrayParametros['usuario'] . "', 
			'" . $arrayParametros['tablet_id'] . "', '" . $arrayParametros['tablet_version_base'] . "', 
            '" . $arrayParametros['fecha_ingreso_guia'] . "') returning id;";

		return $this->modeloAdministracionTrampas->ejecutarSqlNativo($consulta);
	}

	public function guardarTrampas($arrayParametros)
	{

		$rutaArchivo = 'ruta foto';
		$link = '';

		if ($arrayParametros['foto'] != '') {
			$rutaArchivo = 'modulos/AplicacionMovilInternos/archivos/fotosTrampasSV/' . md5(time()) . '.jpg';
			file_put_contents($rutaArchivo, base64_decode($arrayParametros['foto']));
			$rutaArchivo = URL_PROTOCOL . URL_DOMAIN . URL_GUIA . '/mvc/' . $rutaArchivo;
			$link = '<a href="' . $rutaArchivo . '">Foto</a>';
		} else {
			$rutaArchivo = '';
		}



		$consulta = "INSERT INTO f_inspeccion.vigilanciaf01_detalle_trampas (id_padre, id_tablet, fecha_instalacion, codigo_trampa, tipo_trampa, id_provincia, nombre_provincia, id_canton, nombre_canton, 
			id_parroquia, nombre_parroquia, estado_trampa, coordenada_x, coordenada_y, coordenada_z, id_lugar_instalacion, nombre_lugar_instalacion, 
			numero_lugar_instalacion, fecha_inspeccion, semana, usuario_id, usuario, propiedad_finca, condicion_trampa, especie, procedencia, condicion_cultivo,
			etapa_cultivo, exposicion, cambio_feromona, cambio_papel, cambio_aceite, cambio_trampa, numero_especimenes, diagnostico_visual, fase_plaga, 
			observaciones, envio_muestra, tablet_id, tablet_version_base, ruta_foto )
		VALUES (" . $arrayParametros['id_padre'] . ", 
				'" . $arrayParametros['id_tablet'] . "',
				'" . $arrayParametros['fecha_instalacion'] . "',
				'" . $arrayParametros['codigo_trampa'] . "', 
				'" . $arrayParametros['tipo_trampa'] . "',
				'" . $arrayParametros['id_provincia'] . "',
				'" . $arrayParametros['nombre_provincia'] . "',
				'" . $arrayParametros['id_canton'] . "', 
				'" . $arrayParametros['nombre_canton'] . "', 
				'" . $arrayParametros['id_parroquia'] . "',
				'" . $arrayParametros['nombre_parroquia'] . "', 
				'" . $arrayParametros['estado_trampa'] . "', 
				'" . $arrayParametros['coordenada_x'] . "',
				'" . $arrayParametros['coordenada_y'] . "',
				'" . $arrayParametros['coordenada_z'] . "',
				'" . $arrayParametros['id_lugar_instalacion'] . "',
				'" . $arrayParametros['nombre_lugar_instalacion'] . "', 
				'" . $arrayParametros['numero_lugar_instalacion'] . "',
				'" . $arrayParametros['fecha_inspeccion'] . "',
				'" . $arrayParametros['semana'] . "',
				'" . $arrayParametros['usuario_id'] . "', 
				'" . $arrayParametros['usuario'] . "', 
				'" . $arrayParametros['propiedad_finca'] . "',
				'" . $arrayParametros['condicion_trampa'] . "', 
				'" . $arrayParametros['especie'] . "',
				'" . $arrayParametros['procedencia'] . "', 
				'" . $arrayParametros['condicion_cultivo'] . "',
				'" . $arrayParametros['etapa_cultivo'] . "',
				'" . $arrayParametros['exposicion'] . "',
				'" . $arrayParametros['cambio_feromona'] . "',
				'" . $arrayParametros['cambio_papel'] . "', 
				'" . $arrayParametros['cambio_aceite'] . "', 
				'" . $arrayParametros['cambio_trampa'] . "',
				'" . $arrayParametros['numero_especimenes'] . "',
				'" . $arrayParametros['diagnostico_visual'] . "',
				'" . $arrayParametros['fase_plaga'] . "', 
				'" . $arrayParametros['observaciones'] . "', 
				'" . $arrayParametros['envio_muestra'] . "',
				'" . $arrayParametros['tablet_id'] . "', 
				'" . $arrayParametros['tablet_version_base'] . "',
				'" . $link . "') 
				returning id;";

		return $this->modeloAdministracionTrampas->ejecutarSqlNativo($consulta);
	}

	public function guardarOrdenLaboratorio($arrayParametros)
	{

		$consulta = "INSERT INTO f_inspeccion.vigilanciaf01_detalle_ordenes(
            id_padre, id_tablet, actividad_origen, analisis, codigo_muestra, 
            conservacion, tipo_muestra, descripcion_sintomas, fase_fenologica, 
            nombre_producto, peso_muestra, prediagnostico, tipo_cliente, 
            aplicacion_producto_quimico, codigo_trampa_padre)
    	VALUES (" . $arrayParametros['id_padre'] . ", " . $arrayParametros['id_tablet'] . ", '" . $arrayParametros['actividad_origen'] . "', '" . $arrayParametros['analisis'] . "', '" . $arrayParametros['codigo_muestra'] . "','" . $arrayParametros['conservacion'] . "', 
            '" . $arrayParametros['tipo_muestra'] . "', '" . $arrayParametros['descripcion_sintomas'] . "', '" . $arrayParametros['fase_fenologica'] . "', '" . $arrayParametros['nombre_producto'] . "', 
			'" . $arrayParametros['peso_muestra'] . "',  '" . $arrayParametros['prediagnostico'] . "',  '" . $arrayParametros['tipo_cliente'] . "',  '" . $arrayParametros['aplicacion_producto_quimico'] . "', 
			'" . $arrayParametros['codigo_trampa'] . "');
			";

		return $this->modeloAdministracionTrampas->ejecutarSqlNativo($consulta);
	}


	public function obtenerAdministracionTrampaPorCodigoTrampa($arrayParametros)
	{
		$consulta = "SELECT
							*
					FROM
						g_administracion_trampas.administracion_trampas adt, 
						g_catalogos.areas_trampas art, 
						g_catalogos.lugar_instalacion li,
						g_catalogos.plaga pl,
						g_catalogos.tipo_trampa tt,
						g_catalogos.tipo_atrayente ta
					WHERE
						adt.id_area_trampa = art.id_area_trampa
						and adt.id_lugar_instalacion = li.id_lugar_instalacion
						and adt.id_plaga = pl.id_plaga
						and adt.id_tipo_trampa = tt.id_tipo_trampa
						and adt.id_tipo_atrayente = ta.id_tipo_atrayente
						and adt.codigo_trampa = '" . $arrayParametros['codigoTrampa'] . "';";

		return $this->modeloAdministracionTrampas->ejecutarSqlNativo($consulta);
	}

	public function actualizarEstadoAdminstracionTrampa($arrayParametros)
	{
		$consulta = "UPDATE 
						g_administracion_trampas.administracion_trampas 
					SET 
						estado_trampa ='inactivo', observacion = 'Inactivación realizada desde aplicativo móvil'
					WHERE 
						id_administracion_trampa = " . $arrayParametros['idTrampa'] . ";";

		return $this->modeloAdministracionTrampas->ejecutarSqlNativo($consulta);
	}


	public function guardarNuevoHistoriaAdminintracionTrampas($arrayParametros, $identficadorTecnico)
	{

		$numeroLugarInstalacion = $arrayParametros[0]['numero_lugar_instalacion'] == '' ? 'null' :  $arrayParametros[0]['numero_lugar_instalacion'];

		$consulta = "INSERT INTO
				g_administracion_trampas.historia_administracion_trampas(id_administracion_trampa, codigo_trampa, id_area_trampa, etapa_trampa, 
				fecha_instalacion_trampa, id_provincia, id_canton,
				id_parroquia, coordenadax, coordenaday, coordenadaz, id_lugar_instalacion, numero_lugar_instalacion,
				id_plaga, id_tipo_trampa, id_tipo_atrayente, estado_trampa, observacion, identificador_tecnico, fecha_modificacion, codigo_programa_especifico)
				VALUES( " . $arrayParametros[0]['id_administracion_trampa'] . ",'" . $arrayParametros[0]['codigo_trampa'] . "','" . $arrayParametros[0]['id_area_trampa'] .
			"','" . $arrayParametros[0]['etapa_trampa'] . "','" . $arrayParametros[0]['fecha_instalacion_trampa'] . "','" . $arrayParametros[0]['id_provincia'] .
			"','" . $arrayParametros[0]['id_canton'] . "','" . $arrayParametros[0]['id_parroquia'] . "','" . $arrayParametros[0]['coordenadax'] .
			"','" . $arrayParametros[0]['coordenaday'] . "','" . $arrayParametros[0]['coordenadaz'] . "','" . $arrayParametros[0]['id_lugar_instalacion'] .
			"'," . $numeroLugarInstalacion . ",'" . $arrayParametros[0]['id_plaga'] . "','" . $arrayParametros[0]['id_tipo_trampa'] .
			"','" . $arrayParametros[0]['id_tipo_atrayente'] . "','" . $arrayParametros[0]['estado_trampa'] . "','" . $arrayParametros[0]['observacion'] .
			"','" . $identficadorTecnico . "',now(),'" . $arrayParametros[0]['codigo_programa_especifico'] . "');";

		return $this->modeloAdministracionTrampas->ejecutarSqlNativo($consulta);
	}


	public function actualizarEstadoAdminstracionTrampaTransaccion($codigoTrampa)
	{


		$res = $this->modeloAdministracionTrampas->buscarLista("codigo_trampa = '" . $codigoTrampa . "'");

		$res->buffer();

		$this->actualizarEstadoAdminstracionTrampa(array('idTrampa' => $res->current()->id_administracion_trampa));

		$statement = $this->modeloAdministracionTrampas->getAdapter()
			->getDriver()
			->createStatement();

		$campos = $res->toArray();
		$campos[0]['estado_trampa'] = 'inactivo';
		$campos[0]['fecha_modificacion'] = 'now()';

		$sqlInsertar = $this->modeloAdministracionTrampas->guardarSql('historia_administracion_trampas', $this->modeloAdministracionTrampas->getEsquema());
		$sqlInsertar->columns($this->columnasHistorial());
		$sqlInsertar->values($campos[0], $sqlInsertar::VALUES_MERGE);
		$sqlInsertar->prepareStatement($this->modeloAdministracionTrampas->getAdapter(), $statement);
		$statement->execute();
	}

	private function columnasHistorial()
	{
		return array(
			'id_administracion_trampa',
			'codigo_trampa',
			'id_area_trampa',
			'etapa_trampa',
			'fecha_instalacion_trampa',
			'id_provincia',
			'id_canton',
			'id_parroquia',
			'coordenadax',
			'coordenaday',
			'coordenadaz',
			'id_lugar_instalacion',
			'numero_lugar_instalacion',
			'id_plaga',
			'id_tipo_trampa',
			'id_tipo_atrayente',
			'estado_trampa',
			'observacion',
			'identificador_tecnico',
			'fecha_modificacion',
			'codigo_programa_especifico'
		);
	}
}

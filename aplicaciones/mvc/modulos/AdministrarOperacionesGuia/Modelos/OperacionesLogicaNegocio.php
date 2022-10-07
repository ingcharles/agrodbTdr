<?php
/**
 * Lógica del negocio de OperacionesModelo
 *
 * Este archivo se complementa con el archivo OperacionesControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-09-18
 * @uses OperacionesLogicaNegocio
 * @package AdministrarOperacionesGuia
 * @subpackage Modelos
 */
namespace Agrodb\AdministrarOperacionesGuia\Modelos;

use Agrodb\RegistroOperador\Modelos\AsignacionInspectorLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\GruposSolicitudesLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\InspeccionLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\AsignacionInspectorModelo;

class OperacionesLogicaNegocio implements IModelo{

	private $modeloOperaciones = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloOperaciones = new OperacionesModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new OperacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdOperacion() != null && $tablaModelo->getIdOperacion() > 0){
			return $this->modeloOperaciones->actualizar($datosBd, $tablaModelo->getIdOperacion());
		}else{
			unset($datosBd["id_operacion"]);
			return $this->modeloOperaciones->guardar($datosBd);
		}
	}

	public function guardarResultado(Array $datos, Array $resultado){
		try{
			$this->modeloOperaciones = new OperacionesModelo();
			$proceso = $this->modeloOperaciones->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: Guardar operaciones');
			}
			$tablaModelo = new OperacionesModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			if ($tablaModelo->getIdOperacion() != null && $tablaModelo->getIdOperacion() > 0){
				$this->modeloOperaciones->actualizar($datosBd, $tablaModelo->getIdOperacion());
				$idRegistro = $tablaModelo->getIdOperacion();
			}
			if (! $idRegistro){
				throw new \Exception('No se registo los datos en la tabla productos_areas_operacion');
			}
			// *************actualizar prodructos areas operacion*************
			$arrayProdAreaOpe = array(
				'estado' => $datos['estado'],
				'observacion' => $datos['observacion']);
			$statement = $this->modeloOperaciones->getAdapter()
				->getDriver()
				->createStatement();
			$sqlActualizar = $this->modeloOperaciones->actualizarSql('productos_areas_operacion', $this->modeloOperaciones->getEsquema());
			$sqlActualizar->set($arrayProdAreaOpe);
			$sqlActualizar->where(array(
				'id_operacion' => $idRegistro));
			$sqlActualizar->prepareStatement($this->modeloOperaciones->getAdapter(), $statement);
			$statement->execute();
			// ****actualizar operacines en g_revision_solicitudes************

			$statement = $this->modeloOperaciones->getAdapter()
				->getDriver()
				->createStatement();
			$lnegocioAsignacionInspector = new AsignacionInspectorLogicaNegocio();
			$sqlInsertar = $this->modeloOperaciones->guardarSql('asignacion_inspector', 'g_revision_solicitudes');
			$sqlInsertar->columns($lnegocioAsignacionInspector->columnas());
			$sqlInsertar->values($resultado, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloOperaciones->getAdapter(), $statement);
			$statement->execute();
			$idGrupo = $this->modeloOperaciones->adapter->driver->getLastGeneratedValue('g_revision_solicitudes' . '.asignacion_inspector_id_grupo_seq');

			if (! $idGrupo){
				throw new \Exception('No se registo los datos en la tabla asignacion_inspector');
			}
			// *******************************grupos_solicitudes*******************************//
			$grupoSolicitud = array(
				'id_grupo' => $idGrupo,
				'id_solicitud' => $tablaModelo->getIdOperacion(),
				'estado' => $resultado['tipo_inspector']);
			$statement = $this->modeloOperaciones->getAdapter()
				->getDriver()
				->createStatement();
			$lnegocioGruposSolicitudes = new GruposSolicitudesLogicaNegocio();
			$sqlInsertar = $this->modeloOperaciones->guardarSql('grupos_solicitudes', 'g_revision_solicitudes');
			$sqlInsertar->columns($lnegocioGruposSolicitudes->columnas());
			$sqlInsertar->values($grupoSolicitud, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloOperaciones->getAdapter(), $statement);
			$statement->execute();

			// *******************************inspeccion*******************************//
			$arrayInspeccion = array(
				'id_grupo' => $idGrupo,
				'identificador_inspector' => $_SESSION['usuario'],
				'fecha_inspeccion' => date("Y-m-d h:m:s"),
				'estado' => $datos['estado'],
				'observacion' => $datos['observacion']);
			$statement = $this->modeloOperaciones->getAdapter()
				->getDriver()
				->createStatement();
			$lnegocioInspeccion = new InspeccionLogicaNegocio();
			$sqlInsertar = $this->modeloOperaciones->guardarSql('inspeccion', 'g_revision_solicitudes');
			$sqlInsertar->columns($lnegocioInspeccion->columnas());
			$sqlInsertar->values($arrayInspeccion, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloOperaciones->getAdapter(), $statement);
			$statement->execute();
			// ***************************************************************************//

			$proceso->commit();
			return $idRegistro;
		}catch (\Exception $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return 0;
		}
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloOperaciones->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return OperacionesModelo
	 */
	public function buscar($id){
		return $this->modeloOperaciones->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloOperaciones->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloOperaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarOperaciones(){
		$consulta = "SELECT * FROM " . $this->modeloOperaciones->getEsquema() . ". operaciones";
		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * VERIFICAR TIPO DE PERFIL DEL OPERADOR
	 */
	public function verificarPerfil($identificador){
		$sql = "SELECT
					p.nombre, p.codificacion_perfil
			  FROM
					g_usuario.usuarios_perfiles up
					INNER JOIN g_usuario.perfiles p ON up.id_perfil = p.id_perfil
					INNER JOIN g_programas.aplicaciones ap ON ap.id_aplicacion = p.id_aplicacion
			  WHERE
					identificador in ('" . $identificador . "') AND
					ap.codificacion_aplicacion='PRG_ADM_OPR_GUIA';";
		return $this->modeloOperaciones->ejecutarSqlNativo($sql);
	}

	/**
	 */
	public function obtenerOperacionesOperador($arrayParametros){
		$busqueda = '';
		if (array_key_exists('identificador_operador', $arrayParametros)){
			if ($arrayParametros['identificador_operador'] != ''){
				$busqueda .= " and s.identificador_operador  = '" . $arrayParametros['identificador_operador'] . "'";
			}
		}
		if (array_key_exists('razon_social', $arrayParametros)){
			if ($arrayParametros['razon_social'] != ''){
				$busqueda .= " and upper(o.razon_social)  = upper('" . $arrayParametros['razon_social'] . "')";
			}
		}
		if (array_key_exists('codigo', $arrayParametros)){
			if ($arrayParametros['codigo'] != ''){
				$busqueda .= " and upper(t.codigo)  = upper('" . $arrayParametros['codigo'] . "')";
			}
		}
		if (array_key_exists('id_area', $arrayParametros)){
			$busqueda .= " and t.id_area = '" . $arrayParametros['id_area'] . "'";
		}

		$consulta = " select
								distinct min(s.id_operacion) as id_operacion,
								s.identificador_operador,
								s.estado,
								s.id_tipo_operacion,
								t.nombre as nombre_tipo_operacion,
								st.provincia,
								st.id_sitio,
								st.nombre_lugar,
                                t.codigo
							from
								g_operadores.operaciones s,
								g_catalogos.tipos_operacion t,
								g_operadores.operadores o,
								g_operadores.productos_areas_operacion sa,
								g_operadores.areas a,
								g_operadores.sitios st,
								g_operadores.flujos_operaciones fo
							where
								s.id_tipo_operacion = t.id_tipo_operacion and
								s.identificador_operador = o.identificador and
								s.id_operacion = sa.id_operacion and
								sa.id_area = a.id_area and
								a.id_sitio = st.id_sitio and
								s.estado " . $arrayParametros['estado'] . " and
								t.id_flujo_operacion = fo.id_flujo and 
                                upper(st.provincia) = upper('" . $arrayParametros['provincia'] . "')
                                " . $busqueda . "
							group by s.identificador_operador, s.estado, s.id_tipo_operacion, nombre_tipo_operacion, st.provincia, st.id_sitio, a.id_area, t.codigo
							order by id_operacion;";
		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	public function buscarNombreAreaPorSitioPorTipoOperacion($arrayParametros){
		$consulta = "SELECT array_to_string(ARRAY(
													SELECT
														distinct a.nombre_area
													FROM
														g_operadores.areas a INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
														INNER JOIN g_operadores.operaciones o ON pao.id_operacion = o.id_operacion
													WHERE
                                                        a.id_sitio = " . $arrayParametros['idSitio'] . " and o.id_tipo_operacion = " . $arrayParametros['idTipoOperacion'] . "
													),', ') as nombre_area;";

		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * obtener informacion del operador
	 *
	 * @param string $identificador
	 */
	public function obtenerOperador($identificador){
		$consulta = "
                        SELECT row_to_json (operador)
                        FROM (
                            SELECT
                                o1.* ,
                                (
                                    SELECT array_to_json(array_agg(row_to_json(operaciones_n2)))
                                    FROM (
                                            select
                                                distinct on(topc2.id_area, topc2.nombre) topc2.*
                                            from
                                                g_operadores.operadores opr2
                                                , g_operadores.operaciones opc2
                                                , g_catalogos.tipos_operacion topc2
                                            where
                                                opr2.identificador = opc2.identificador_operador
                                                and opc2.id_tipo_operacion = topc2.id_tipo_operacion
                                                and opr2.identificador = o1.identificador
                                            order by
                                                topc2.id_area, topc2.nombre ) operaciones_n2
                                ) operaciones
                            FROM
                                g_operadores.operadores o1
                            WHERE
                                o1.identificador = '$identificador'
                        ) as operador";

		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	public function abrirOperacion($arrayParametros){
		$consulta = "select
							o.id_operacion,
							o.id_tipo_operacion,
							o.identificador_operador,
							o.id_producto,
							o.nombre_producto,
							o.estado,
							o.id_producto,
							o.nombre_producto,
							o.observacion,
							o.nombre_pais,
							o.fecha_aprobacion,
							o.fecha_finalizacion,
							o.id_operador_tipo_operacion,
							o.id_historial_operacion,
							t.nombre,
							t.id_area as codigo_area,
							t.codigo as codigo_tipo_operacion,
							a.nombre_area as area,
							a.tipo_area,
							a.superficie_utilizada,
							ss.provincia,
							ss.canton,
							ss.parroquia,
							ss.id_sitio,
							ss.nombre_lugar as sitio,
							ss.direccion,
							ss.referencia,
							ss.croquis,
							pao.estado as estado_area,
							pao.ruta_archivo,
							pao.id_area,
							pao.observacion as observacion_area,
							ss.identificador_operador||'.'||ss.codigo_provincia || ss.codigo || a.codigo||a.secuencial as codificacion_area
						from
							g_operadores.operaciones o,
							g_operadores.productos_areas_operacion pao,
							g_operadores.areas a,
							g_catalogos.tipos_operacion t,
							g_operadores.sitios ss
						where
							o.identificador_operador = '" . $arrayParametros['identificadorOperador'] . "' and
							o.id_operacion = " . $arrayParametros['idOperacion'] . " and
							o.id_operacion = pao.id_operacion and
							pao.id_area = a.id_area and
							o.id_operacion = pao.id_operacion and
							o.id_tipo_operacion = t.id_tipo_operacion and
							a.id_sitio = ss.id_sitio
						order by
							o.id_producto;";
		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 */
	public function listarItemsPorCodigo($arrayParametros){
		$busqueda = '';
		if (array_key_exists('estado', $arrayParametros)){
			$busqueda = " and c.estado  = '" . $arrayParametros['estado'] . "'";
		}
		if (array_key_exists('codigo', $arrayParametros)){
			$busqueda .= " and codigo  = '" . $arrayParametros['codigo'] . "'";
		}

		$consulta = "SELECT
						c.id_item, c.nombre, c.descripcion, c.estado, c.id_catalogo_negocios
					FROM
                        g_administracion_catalogos.catalogos_negocio cn,
						g_administracion_catalogos.items_catalogo c
					WHERE
                        cn.id_catalogo_negocios = c.id_catalogo_negocios and
                        " . $busqueda . "
					ORDER BY 2 asc;";

		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 */
	public function listarDatosVehiculoXIdAreaXidTipoOperacion($arrayParametros){
		$consulta = "SELECT
                        id_marca_vehiculo, 
                        (select nombre from g_administracion_catalogos.items_catalogo WHERE id_item = id_marca_vehiculo) as marca,
                        id_modelo_vehiculo,
                        (select nombre from g_administracion_catalogos.items_catalogo WHERE id_item = id_modelo_vehiculo) as modelo,
                        id_tipo_vehiculo,
                        (select nombre from g_administracion_catalogos.items_catalogo WHERE id_item = id_tipo_vehiculo) as tipovehiculo,
                        id_color_vehiculo,
                        (select nombre from g_administracion_catalogos.items_catalogo WHERE id_item = id_color_vehiculo) as colorvehiculo,
                        id_clase_vehiculo,
                        (select nombre from g_administracion_catalogos.items_catalogo WHERE id_item = id_clase_vehiculo) as clase,
                        placa_vehiculo,
                        anio_vehiculo,
                        capacidad_vehiculo,
                        codigo_unidad_medida
					FROM
						g_operadores.datos_vehiculos
					WHERE
						id_area = " . $arrayParametros['id_area'] . " and
						id_tipo_operacion = " . $arrayParametros['id_tipo_operacion'] . " and
						id_operador_tipo_operacion = " . $arrayParametros['id_operador_tipo_operacion'] . " and
						estado_dato_vehiculo = '" . $arrayParametros['estado'] . "'
						and id_dato_vehiculo = (SELECT
													max(id_dato_vehiculo)
												FROM
													g_operadores.datos_vehiculos
												WHERE
													id_area = " . $arrayParametros['id_area'] . " and
													id_tipo_operacion = " . $arrayParametros['id_tipo_operacion'] . " and
													id_operador_tipo_operacion = " . $arrayParametros['id_operador_tipo_operacion'] . " and
													estado_dato_vehiculo = '" . $arrayParametros['estado'] . "');";

		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 */
	public function obtenerAreaXIdOperacion($idOperacion){
		$consulta = "SELECT
			        	pao.id_area
			        FROM
				        g_operadores.operaciones op,
				        g_operadores.productos_areas_operacion pao
			        WHERE
				        op.id_operacion=pao.id_operacion
				        and op.id_operacion=" . $idOperacion . ";";
		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	public function obtenerProductosPorIdOperadorTipoOperacionHistorico($arrayParametros){
		$consulta = "SELECT
						o.id_operacion, p.id_producto, nombre_comun, sp.nombre as nombre_subtipo, 
                        codificacion_subtipo_producto, tp.nombre as nombre_tipo, o.estado
					FROM
						g_operadores.operaciones o,
						g_catalogos.productos p,
						g_catalogos.subtipo_productos sp,
						g_catalogos.tipo_productos tp
					WHERE
						o.id_producto = p.id_producto
						and p.id_subtipo_producto = sp.id_subtipo_producto
						and sp.id_tipo_producto = tp.id_tipo_producto
						and id_operador_tipo_operacion in (" . $arrayParametros['id_operador_tipo_operacion'] . ")
						and id_historial_operacion in (" . $arrayParametros['id_historial_operacion'] . ")
                        and o.estado = '" . $arrayParametros['estado'] . "'
                        ;";

		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	public function variedadesXOperacionesXProductos($idOperacion){
		$consulta = "SELECT
								       				v.nombre
								       			FROM
								       				g_operadores.operaciones_variedades ov,
								       				g_catalogos.variedades v,
								       				g_operadores.operaciones ope
								       			WHERE
								       				ov.id_operacion=ope.id_operacion and
								       				ov.id_variedad=v.id_variedad and
								       				ope.id_operacion='$idOperacion'
								       				order by 1";
		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	public function buscarIdImposicionTasaXSolicitud($idSolicitud, $tipoSolicitud, $tipoInspector){
		$consulta = "SELECT
												f.*
											FROM
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.financiero f,
												g_revision_solicitudes.grupos_solicitudes gs
											WHERE
												ai.id_grupo = gs.id_grupo and
												ai.id_grupo  = f.id_grupo and
												gs.id_solicitud = $idSolicitud and
												ai.tipo_solicitud = '$tipoSolicitud' and
												ai.tipo_inspector = '$tipoInspector' and
												monto_recaudado is null and
												f.orden = (	SELECT
															max (orden)
														FROM
															g_revision_solicitudes.asignacion_inspector ai,
															g_revision_solicitudes.financiero f,
															g_revision_solicitudes.grupos_solicitudes gs
														WHERE
															ai.id_grupo = gs.id_grupo and
															ai.id_grupo  = f.id_grupo and
															gs.id_solicitud = $idSolicitud
															and ai.tipo_solicitud = '$tipoSolicitud'
															and ai.tipo_inspector = '$tipoInspector'
															and monto_recaudado is null)
												ORDER BY id_financiero desc;";
		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	public function obtenerOrdenPagoPorIdentificadorSolicitud($idSolicitud, $tipo){
		$consulta = "SELECT
												*
											FROM
												g_financiero.orden_pago
											WHERE
												id_solicitud like ('%$idSolicitud%')
												and tipo_solicitud = '$tipo'
												and estado = 3;";
		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * obtener tipo de operaciones registradas
	 */
	public function obtenerTipoOperacionesOperador($arrayParametros){
		$busqueda = '';

		if (array_key_exists('identificador_operador', $arrayParametros)){
			if ($arrayParametros['identificador_operador'] != ''){
				$busqueda .= " and op.identificador_operador  = '" . $arrayParametros['identificador_operador'] . "'";
			}
		}
		if (array_key_exists('razon_social', $arrayParametros)){
			if ($arrayParametros['razon_social'] != ''){
				$busqueda .= " and upper(o.razon_social)  = upper('" . $arrayParametros['razon_social'] . "')";
			}
		}
		if (array_key_exists('id_area', $arrayParametros)){
			$busqueda .= " and top.id_area = '" . $arrayParametros['id_area'] . "'";
		}

		$consulta = " 
                    SELECT 
                        top.nombre as operaciones_registradas, top.codigo
                    FROM 
                        g_operadores.sitios s
                        INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
                        INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
                        INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
                        INNER JOIN g_operadores.operadores o ON op.identificador_operador = o.identificador
                        INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                    WHERE
                        op.estado " . $arrayParametros['estado'] . " and 
						permite_desplegar_administracion_operacion = 'true' and
                        upper(s.provincia) = upper('" . $arrayParametros['provincia'] . "')  
                        " . $busqueda . "
                    GROUP BY 1,2
								;";
		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}
}

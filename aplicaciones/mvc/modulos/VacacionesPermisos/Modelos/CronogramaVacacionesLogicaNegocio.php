<?php

/**
 * Lógica del negocio de CronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo CronogramaVacacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-10-22
 * @uses    CronogramaVacacionesLogicaNegocio
 * @package VacacionesPermisos
 * @subpackage Modelos
 */

namespace Agrodb\VacacionesPermisos\Modelos;

use Agrodb\VacacionesPermisos\Modelos\IModelo;
use Seld\JsonLint\Undefined;

class CronogramaVacacionesLogicaNegocio implements IModelo
{

	private $modeloCronogramaVacaciones = null;


	/**
	 * Constructor
	 * 
	 * @retorna void
	 */
	public function __construct()
	{
		$this->modeloCronogramaVacaciones = new CronogramaVacacionesModelo();
	}

	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardar(array $datos)
	{
		$tablaModelo = new CronogramaVacacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCronogramaVacacion() != null && $tablaModelo->getIdCronogramaVacacion() > 0) {
			return $this->modeloCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdCronogramaVacacion());
		} else {
			unset($datosBd["id_cronograma_vacacion"]);
			return $this->modeloCronogramaVacaciones->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 * @param string Where|array $where
	 * @return int
	 */
	public function borrar($id)
	{
		$this->modeloCronogramaVacaciones->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param  int $id
	 * @return CronogramaVacacionesModelo
	 */
	public function buscar($id)
	{
		return $this->modeloCronogramaVacaciones->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo()
	{
		return $this->modeloCronogramaVacaciones->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null)
	{
		return $this->modeloCronogramaVacaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarCronogramaVacaciones()
	{
		
		 $consulta = "SELECT * FROM " . $this->modeloCronogramaVacaciones->getEsquema() . ". cronograma_vacaciones";
		return $this->modeloCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

	public function buscarCronogramaVacacionesFiltro($filtroEstado)
	{
		
		$filtro = $filtroEstado;
	    $estado = $filtro != "" ? "'" . $filtro . "'" : "null";	
		$consulta = "SELECT * FROM " . $this->modeloCronogramaVacaciones->getEsquema() . ". cronograma_vacaciones where  ($estado is NULL or estado_cronograma_vacacion = $estado)";
		return $this->modeloCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los datos del empleado y fecha ingreso a la institucion
	 *
	 * @return array
	 */
	public function obtenerDatosEmpleadoFechaIngresoInstitucion($identificador)
	{

		$consulta = "SELECT
				fe.identificador,
				fe.apellido || ' ' || fe.nombre AS nombre, 
				mdc.fecha_inicio AS fecha_ingreso_institucion,
				--ar.id_area_padre, 
				arr.nombre AS nombre_unidad_administrativa,
				--ar.id_area, 
				ar.nombre AS nombre_gestion_administrativa, 
				dc.nombre_puesto AS puesto_institucional
		FROM
				g_estructura.area ar 
				INNER JOIN g_estructura.area arr ON arr.id_area = ar.id_area_padre AND arr.estado = 1
				INNER JOIN g_uath.datos_contrato dc ON ar.id_area = dc.id_gestion AND dc.estado = 1
				INNER JOIN g_uath.ficha_empleado fe ON fe.identificador = dc.identificador AND fe.estado_empleado = 'activo',
				(SELECT dc1.fecha_inicio FROM g_uath.datos_contrato dc1 WHERE dc1.id_datos_contrato = (SELECT MIN(dcc.id_datos_contrato) id_datos_contrato FROM g_uath.datos_contrato dcc WHERE dcc.identificador = '" . $identificador . "') 
				) mdc 
		WHERE
				dc.identificador = '" . $identificador . "' AND ar.estado = 1 ;";

		$res = $this->modeloCronogramaVacaciones->ejecutarSqlNativo($consulta);
		return $res;
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el nombre del empleado
	 *
	 * @return array
	 */
	public function datosFuncionario($conexion, $identificador)
	{

		$sqlScript = "SELECT
						apellido ||' '|| nombre AS nombre
					FROM
						g_uath.ficha_empleado
					WHERE
						identificador = '$identificador' estado_empleado = 'activo'";
		$res = $this->modeloCronogramaVacaciones->ejecutarSqlNativo($sqlScript);
		return $res;
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener saldo de vacaciones funcionarios
	 *
	 * @return array
	 */

	public function consultarSaldoFuncionario($usuario, $activo = 'TRUE')
	{
		$sqlScript = "SELECT
							SUM(minutos_disponibles) AS minutos_disponibles,
							STRING_AGG(DISTINCT anio::CHARACTER varying,', ') AS anio
						FROM
							g_vacaciones.minutos_disponibles_funcionarios
						WHERE
							activo = " . $activo . "
							AND identificador = '" . $usuario . "'
							GROUP BY identificador";
		$res = $this->modeloCronogramaVacaciones->ejecutarSqlNativo($sqlScript);
		return $res;
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener saldo de vacaciones funcionarios en nueva 
	 *
	 * @return array
	 */

	public function consultarSaldoFuncionarioNuevo($usuario, $activo = 'TRUE')
	{
		$sqlScript = "SELECT
							sum(minutos_disponibles) as minutos_disponibles,
							string_agg(DISTINCT anio::CHARACTER varying,', ') as anio
						FROM
							g_vacaciones.tiempo_disponible_funcionarios
						WHERE
							activo = " . $activo . "
							AND identificador = '" . $usuario . "'
							GROUP BY identificador;";
		$res = $this->modeloCronogramaVacaciones->ejecutarSqlNativo($sqlScript);
		return $res;
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para dar formato a dias disponibles de vacaciones
	 *
	 * @return array
	 */

	public function devolverFormatoDiasDisponibles($minutosutilizados)
	{
		if ($minutosutilizados >= 1) {
			$valor = '';
		} else {
			$valor = '- ';
		}
		$minutosutilizados = abs($minutosutilizados);
		$diasDescontados = '';
		$separador = '';
		$dias = floor(intval($minutosutilizados) / 480);
		if ($dias != 0) {
			if ($dias >= 2)
				$diasDescontados .= $valor . $dias . ' días';
			else
				$diasDescontados .= $valor . $dias . ' día';
			$separador = ' ';
			$valor = '';
		}
		$horas = floor((intval($minutosutilizados) - $dias * 480) / 60);
		if ($horas != 0) {
			$valor = '';
			if ($horas >= 2)
				$diasDescontados .= $separador . $valor . $horas . ' horas';
			else
				$diasDescontados .= $separador . $valor . $horas . ' hora';
			$separador = ' ';
		}
		$minutos = (intval($minutosutilizados) - $dias * 480) - $horas * 60;
		if ($minutos != 0) {
			if ($minutos >= 2)
				$diasDescontados .= $separador . $valor . $minutos . ' minutos';
			else
				$diasDescontados .= $separador . $valor . $minutos . ' minuto';
		}
		return $diasDescontados;
	}

	public function guardarPlanificacionVacaciones(array $datos)
	{


		try {
			$this->modeloCronogramaVacaciones = new CronogramaVacacionesModelo();
			$proceso = $this->modeloCronogramaVacaciones->getAdapter()
				->getDriver()
				->getConnection();
			if (!$proceso->beginTransaction()) {
				throw new \Exception('No se pudo iniciar la transacción: Guardar destinatario');
			}

			$tablaModelo = new CronogramaVacacionesModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();


			if ($tablaModelo->getIdCronogramaVacacion() != null && $tablaModelo->getIdCronogramaVacacion() > 0) {
				$this->modeloCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdCronogramaVacacion());
				$idRegistro = $datosBd["id_cronograma_vacacion"];
			} else {
				//unset($datosBd["id_cronograma_vacacion"]);
				$arrayParametros = array(
					'identificador' =>  $_POST['identificador_registro'],
					'fecha_ingreso_institucion' =>  $_POST['fecha_ingreso_institucion'],
					'nombre_puesto' =>  $_POST['nombre_puesto'],
					'identificador_backup' =>  $_POST['identificador_backup'],
					'total_dias_planificados' =>  $_POST['total_dias_planificados'],
					'usuario_creacion' =>  $_POST['identificador_registro'],
					'anio_cronograma_vacacion' =>  $_POST['anio_cronograma_vacacion'],
					'numero_periodos' => $_POST['numero_periodos'],
					'estado_cronograma_vacacion' =>  'RevisionJefe'

				);

				//print_r($arrayParametros);
				$idRegistro = $this->modeloCronogramaVacaciones->guardar($arrayParametros);
				$statement = $this->modeloCronogramaVacaciones->getAdapter()
					->getDriver()
					->createStatement();

				for ($i = 0; $i < count($datos['hFechaInicio']); $i++) {

					$datosDetalle = array(
						'id_cronograma_vacacion' => (int) $idRegistro,
						'numero_periodo' => $i + 1,
						'fecha_inicio' => $datos['hFechaInicio'][$i],
						'fecha_fin' => $datos['hFechaFin'][$i],
						'total_dias' => $datos['hNumeroDias'][$i]
					);

					$sqlInsertar = $this->modeloCronogramaVacaciones->guardarSql('periodo_cronograma_vacaciones', $this->modeloCronogramaVacaciones->getEsquema());
					$sqlInsertar->columns(array_keys($datosDetalle));
					$sqlInsertar->values($datosDetalle, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloCronogramaVacaciones->getAdapter(), $statement);
					$statement->execute();
				}
			}

			if (!$idRegistro) {
				throw new \Exception('No se registo los datos en la tabla cronograma vacacion');
			}


			$proceso->commit();
			return $idRegistro;
		} catch (\Exception $ex) {
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return 0;
		}
	}

	public function actualizarPlanificacionVacaciones(array $datos)
	{
		try {
			$this->modeloCronogramaVacaciones = new CronogramaVacacionesModelo();
			$this->modeloPeriodoVacaciones = new PeriodoCronogramaVacacionesModelo();
			$proceso = $this->modeloCronogramaVacaciones->getAdapter()
				->getDriver()
				->getConnection();
			if (!$proceso->beginTransaction()) {
				throw new \Exception('No se pudo iniciar la transacción: Guardar destinatario');
			}


			$tablaModelo = new CronogramaVacacionesModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			$statement = $this->modeloCronogramaVacaciones->getAdapter()
				->getDriver()
				->createStatement();
			$idRegistro = null;

			if ($tablaModelo->getIdCronogramaVacacion() != null && $tablaModelo->getIdCronogramaVacacion() > 0) {
				$this->modeloCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdCronogramaVacacion());
				$idRegistro = $datosBd["id_cronograma_vacacion"];
				$periodos = $this->modeloPeriodoVacaciones->buscarLista(array('id_cronograma_vacacion' => $idRegistro, 'estado_registro' => 'Activo'));
				foreach ($periodos as $item) {
					$datosDetalle = array(
						'id_periodo_cronograma_vacacion' => (int) $item->id_periodo_cronograma_vacacion,
						'id_cronograma_vacacion' => (int) $idRegistro,
						'numero_periodo' =>  $item->numero_periodo,
						'fecha_inicio' =>  $item->fecha_inicio,
						'fecha_fin' =>  $item->fecha_fin,
						'total_dias' =>  $item->total_dias,
						'estado_registro' => 'Eliminado'
					);
					print_r($datosDetalle);
					$this->modeloPeriodoVacaciones->actualizar($datosDetalle, $item->id_periodo_cronograma_vacacion);
				}

				for ($i = 0; $i < count($datos['hFechaInicio']); $i++) {
					$datosDetalle = array(
						'id_cronograma_vacacion' => (int) $idRegistro,
						'numero_periodo' => $i + 1,
						'fecha_inicio' => $datos['hFechaInicio'][$i],
						'fecha_fin' => $datos['hFechaFin'][$i],
						'total_dias' => $datos['hNumeroDias'][$i]
					);

					$sqlInsertar = $this->modeloCronogramaVacaciones->guardarSql('periodo_cronograma_vacaciones', $this->modeloCronogramaVacaciones->getEsquema());
					$sqlInsertar->columns(array_keys($datosDetalle));
					$sqlInsertar->values($datosDetalle, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloCronogramaVacaciones->getAdapter(), $statement);
					$statement->execute();
				}
			}


			if (!$idRegistro) {
				throw new \Exception('No se registo los datos en la tabla cronograma vacacion');
			}


			$proceso->commit();
			return $idRegistro;
		} catch (\Exception $ex) {
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return 0;
		}
	}
}

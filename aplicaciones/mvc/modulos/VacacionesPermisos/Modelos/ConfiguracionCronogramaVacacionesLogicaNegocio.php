<?php

/**
 * Lógica del negocio de ConfiguracionCronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo ConfiguracionCronogramaVacacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-11-21
 * @uses    ConfiguracionCronogramaVacacionesLogicaNegocio
 * @package VacacionesPermisos
 * @subpackage Modelos
 */

namespace Agrodb\VacacionesPermisos\Modelos;

use Agrodb\VacacionesPermisos\Modelos\IModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\FirmaDocumentos\Modelos\DocumentosLogicaNegocio;
use Agrodb\FirmaDocumentos\Modelos\DocumentosModelo;
use Agrodb\FirmaDocumentos\Modelos\FirmantesLogicaNegocio;
use Agrodb\FirmaDocumentos\Modelos\FirmantesModelo;
use Agrodb\Catalogos\Modelos\ResponsablesCertificadosLogicaNegocio;
use WsdlToPhp\PackageGenerator\Container\PhpElement\Constant;

class ConfiguracionCronogramaVacacionesLogicaNegocio implements IModelo
{

	private $modeloConfiguracionCronogramaVacaciones = null;
	private $lNegocioFirmantesLogicaNegocio = null;
	private $lNegocioResponsablesCertificadosNegocio = null;
	private $modeloCronogramaVacaciones = null;
	/**
	 * Constructor
	 * 
	 * @retorna void
	 */
	public function __construct()
	{
		$this->modeloConfiguracionCronogramaVacaciones = new ConfiguracionCronogramaVacacionesModelo();
		$this->lNegocioFirmantesLogicaNegocio = new FirmantesLogicaNegocio();
		$this->lNegocioResponsablesCertificadosNegocio = new ResponsablesCertificadosLogicaNegocio();
		$this->modeloCronogramaVacaciones = new CronogramaVacacionesModelo();
	}

	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardar(array $datos)
	{

		$tablaModelo = new ConfiguracionCronogramaVacacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdConfiguracionCronogramaVacacion() != null && $tablaModelo->getIdConfiguracionCronogramaVacacion() > 0) {
			return $this->modeloConfiguracionCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdConfiguracionCronogramaVacacion());
		} else {
			unset($datosBd["id_configuracion_cronograma_vacacion"]);
			return $this->modeloConfiguracionCronogramaVacaciones->guardar($datosBd);
		}
	}



	/**
	 * Borra el registro actual
	 * @param string Where|array $where
	 * @return int
	 */
	public function borrar($id)
	{
		$this->modeloConfiguracionCronogramaVacaciones->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param  int $id
	 * @return ConfiguracionCronogramaVacacionesModelo
	 */
	public function buscar($id)
	{
		return $this->modeloConfiguracionCronogramaVacaciones->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo()
	{
		return $this->modeloConfiguracionCronogramaVacaciones->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null)
	{
		return $this->modeloConfiguracionCronogramaVacaciones->buscarLista($where, $order, $count, $offset);
	}

	public function buscarEstadosConfiguracionCronogramaVacaciones()
	{
		$consulta = "SELECT DISTINCT estado_configuracion_cronograma_vacacion as estado FROM " . $this->modeloConfiguracionCronogramaVacaciones->getEsquema() . ". configuracion_cronograma_vacaciones where estado_configuracion_cronograma_vacacion!='Activo'";
		return $this->modeloConfiguracionCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarConfiguracionCronogramaVacaciones()
	{
		$consulta = "SELECT cc.id_configuracion_cronograma_vacacion, cc.anio_configuracion_cronograma_vacacion, cc.descripcion_configuracion_vacacion,
							fe.identificador || ' ' || fe.nombre || ' ' || fe.apellido AS identificador_configuracion_cronograma_vacacion
					FROM " . $this->modeloConfiguracionCronogramaVacaciones->getEsquema() . ". configuracion_cronograma_vacaciones cc
					INNER JOIN
						g_uath.ficha_empleado fe ON fe.identificador = cc.identificador_configuracion_cronograma_vacacion";
		return $this->modeloConfiguracionCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 *Obtenemos los datos del registro seleccionado para editar - Tabla: RevisionCronogramaVacaciones
	 */
	public function aprobarDeCronogramaVacaciones(array $datos)
	{

		$idConfiguracionCronogramaVacacion = $datos['id_configuracion_cronograma_vacacion'];
		$observacion = $datos['observacion'];
		$rutaArchivoPdf = $datos['ruta_consolidado_pdf'];
		$estado = $datos['estado_configuracion_cronograma_vacacion'];

		try {

			$procesoIngreso = $this->modeloConfiguracionCronogramaVacaciones->getAdapter()
				->getDriver()
				->getConnection();



			$procesoIngreso->beginTransaction();
			//Aprueba la configuración cronograma pasa a Finalizado
			if ($estado == 'Finalizado') {


				//Tabla de firmas físicas
				$firmaResponsable = $this->lNegocioResponsablesCertificadosNegocio->obtenerFirmasResponsablePorProvincia('Director Ejecutivo', 'DE');

				$rutaArchivo = Constantes::RUTA_SERVIDOR_OPT . '/' . Constantes::RUTA_APLICACION . '/' . $rutaArchivoPdf;

				//Firma Electrónica

				$parametrosFirma = array(
					'archivo_entrada' => $rutaArchivo,
					'archivo_salida' => $rutaArchivo,
					'identificador' => $firmaResponsable->current()->identificador,
					'razon_documento' => 'Cronograma de Vacaciones',
					'tabla_origen' => 'g_vacaciones.configuracion_cronograma_vacaciones',
					'campo_origen' => 'ruta_consolidado_pdf',
					'id_origen' => $idConfiguracionCronogramaVacacion,
					'estado' => 'Por atender',
					'proceso_firmado' => 'NO'
				);

				//Guardar registro para firma
				$this->lNegocioFirmantesLogicaNegocio->ingresoFirmaDocumento($parametrosFirma);
			}
			$statement = $this->modeloConfiguracionCronogramaVacaciones->getAdapter()
				->getDriver()
				->createStatement();
			
			$arrayParametrosCronograma = array(
				'estado_cronograma_vacacion' => $estado
			);

			// Actualizo los registros del Cronograma de cacaciones con estado = 'Aprobado'
			$sqlActualizar = $this->modeloConfiguracionCronogramaVacaciones->actualizarSql('cronograma_vacaciones', $this->modeloCronogramaVacaciones->getEsquema());
			$sqlActualizar->set($arrayParametrosCronograma);
			$sqlActualizar->where(array('id_configuracion_cronograma_vacacion' => $idConfiguracionCronogramaVacacion));
			$sqlActualizar->prepareStatement($this->modeloConfiguracionCronogramaVacaciones->getAdapter(), $statement);
			$statement->execute();



			$statement = $this->modeloConfiguracionCronogramaVacaciones->getAdapter()
				->getDriver()
				->createStatement();
			$arrayParametrosConfiguracion = array(
				'estado_configuracion_cronograma_vacacion' => $estado, 'observacion' => $observacion
			);

			$sqlActualizar = $this->modeloConfiguracionCronogramaVacaciones->actualizarSql('configuracion_cronograma_vacaciones', $this->modeloConfiguracionCronogramaVacaciones->getEsquema());
			$sqlActualizar->set($arrayParametrosConfiguracion);
			$sqlActualizar->where(array('id_configuracion_cronograma_vacacion' => $idConfiguracionCronogramaVacacion));
			$sqlActualizar->prepareStatement($this->modeloConfiguracionCronogramaVacaciones->getAdapter(), $statement);
			$statement->execute();


			$procesoIngreso->commit();
			return true;
		} catch (GuardarExcepcion $ex) {
			$procesoIngreso->rollback();
			throw new \Exception($ex->getMessage());
			return false;
		}
	}
}

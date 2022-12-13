<?php

/**
 * Lógica del negocio de CentrosAcopioModelo
 *
 * Este archivo se complementa con el archivo CentrosAcopioControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-04-05
 * @uses    CentrosAcopioLogicaNegocio
 * @package RegistroOperador
 * @subpackage Modelos
 */

namespace Agrodb\RegistroOperador\Modelos;

use Agrodb\RegistroOperador\Modelos\IModelo;
use Agrodb\FormulariosInspeccion\Modelos\Acof01LogicaNegocio;
use Agrodb\FormulariosInspeccion\Modelos\Acof01Modelo;
use Agrodb\FormulariosInspeccion\Modelos\Acof01DetalleLogicaNegocio;
use Agrodb\FormulariosInspeccion\Modelos\Acof01DetalleModelo;
use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
use Agrodb\Token\Modelos\TokenLogicaNegocio;
use Exception;
use Agrodb\Core\Constantes;
use Agrodb\Core\JasperReport;
use Agrodb\Core\Excepciones\GuardarExcepcionConDatos;
use Agrodb\Correos\Modelos\CorreosLogicaNegocio;

class CentrosAcopioLogicaNegocio implements IModelo
{

	private $modeloCentrosAcopio = null;
	private $lNegocioAcof01 = null;
	private $modeloAcof01 = null;
	private $modeloAcof01Detalle = null;
	private $lNegocioOperaciones = null;
	private $lNegocioOperadores = null;
	private $lNegocioCorreos = null;
	private $lNegocioToken = null;
	/**
	 * Constructor
	 * 
	 * @retorna void
	 */
	public function __construct()
	{
		$this->modeloCentrosAcopio = new CentrosAcopioModelo();
		$this->lNegocioAcof01 = new Acof01LogicaNegocio();
		$this->modeloAcof01 = new Acof01Modelo();
		$this->modeloAcof01Detalle = new Acof01DetalleModelo();
		$this->lNegocioOperaciones = new OperacionesLogicaNegocio();
		$this->lNegocioOperadores = new OperadoresLogicaNegocio();
		$this->lNegocioCorreos = new CorreosLogicaNegocio();
		$this->lNegocioToken = new TokenLogicaNegocio();
		$this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
	}

	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardar(array $datos)
	{
		$tablaModelo = new CentrosAcopioModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCentroAcopio() != null && $tablaModelo->getIdCentroAcopio() > 0) {
			return $this->modeloCentrosAcopio->actualizar($datosBd, $tablaModelo->getIdCentroAcopio());
		} else {
			unset($datosBd["id_centro_acopio"]);
			return $this->modeloCentrosAcopio->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 * @param string Where|array $where
	 * @return int
	 */
	public function borrar($id)
	{
		$this->modeloCentrosAcopio->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param  int $id
	 * @return CentrosAcopioModelo
	 */
	public function buscar($id)
	{
		return $this->modeloCentrosAcopio->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo()
	{
		return $this->modeloCentrosAcopio->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null)
	{
		return $this->modeloCentrosAcopio->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarCentrosAcopio()
	{
		$consulta = "SELECT * FROM " . $this->modeloCentrosAcopio->getEsquema() . ". centros_acopio";
		return $this->modeloCentrosAcopio->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function guardarDatosInspeccionCentroAcopioAI($arrayParametros)
	{

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if ($arrayToken['estado'] == 'exito') {
		//if (1) {

			try {

				$procesoIngreso = $this->modeloCentrosAcopio->getAdapter()
					->getDriver()
					->getConnection();
				$procesoIngreso->beginTransaction();

				foreach ($arrayParametros['inspeccion'] as $value) {

				$arrayResultadoInspeccion = array();
				$arrayChecklistResumenInspeccion = array();

					foreach ($value->cabecera as $cabeceraLlave => $cabeceraValor) {
						$arrayResultadoInspeccion += [
							$cabeceraLlave => $cabeceraValor
						];
					}

					$idOperadorTipoOperacion = $arrayResultadoInspeccion['id_operador_tipo_operacion'];

					$arrayParametrosCentroAcopio = array(
						'id_operador_tipo_operacion' => $idOperadorTipoOperacion, 'estado_centro_acopio' => 'activo'
					);

					$solicitudCentroAcopio = $this->buscarLista($arrayParametrosCentroAcopio);


				 	if (isset($solicitudCentroAcopio->current()->id_centro_acopio)) {
				 		//Realiza la actualizacion de los campos de la tabla de datos_vehiculo
						//$estadoCentroAcopio = $arrayResultadoInspeccion['estado_checklist'] == 'noHabilitado' ? 'Rechazado' : $arrayResultadoInspeccion['estado_checklist'] == 'noHabilitado' ;
						$statement = $this->modeloCentrosAcopio->getAdapter()->getDriver()->createStatement();
						$sqlActualizar = $this->modeloCentrosAcopio->actualizarSql('centros_acopio', $this->modeloCentrosAcopio->getEsquema());
						$sqlActualizar->set(array('origen_inspeccion' => $arrayResultadoInspeccion['origen_inspeccion'], 'estado_checklist' => $arrayResultadoInspeccion['estado_checklist']));
						$sqlActualizar->where(array('id_operador_tipo_operacion' => $idOperadorTipoOperacion, 'estado_centro_acopio' => 'activo'));
						$sqlActualizar->prepareStatement($this->modeloCentrosAcopio->getAdapter(), $statement);
						$statement->execute();


						//Actualiza los resumenes de inspecciones anteriores
						$statement = $this->modeloCentrosAcopio->getAdapter()->getDriver()->createStatement();
						$sqlActualizar = $this->modeloCentrosAcopio->actualizarSql('acof01', $this->modeloAcof01->getEsquema());
						$sqlActualizar->set(array('estado_generar_checklist' => 'inactivo'));
						$sqlActualizar->where(array('id_operador_tipo_operacion' => $idOperadorTipoOperacion, 'estado_generar_checklist' => 'activo'));
						$sqlActualizar->prepareStatement($this->modeloCentrosAcopio->getAdapter(), $statement);
						$statement->execute();
						$this->lNegocioAcof01->actualizarEstadoInspeccionAcoPorIdSolicitud($arrayResultadoInspeccion['id_solicitud']);

						foreach ($value->checklist_resumen as $checklistResumenLlave => $checklistResumenValor) {

							if (!is_array($checklistResumenValor)) {
								$arrayChecklistResumenInspeccion += [
									$checklistResumenLlave => $checklistResumenValor
								];
							}
						}

						// Guarda el resumen de checklist de inspeccion

						$statement = $this->modeloCentrosAcopio->getAdapter()->getDriver()->createStatement();
						$sqlInsertar = $this->modeloCentrosAcopio->guardarSql('acof01', $this->modeloAcof01->getEsquema());
						$sqlInsertar->columns($this->modeloAcof01->getColumns());
						$sqlInsertar->values($arrayChecklistResumenInspeccion, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloCentrosAcopio->getAdapter(), $statement);
						$statement->execute();
						$idInspeccionAcof01 = $this->modeloCentrosAcopio->adapter->driver->getLastGeneratedValue($this->modeloAcof01->getEsquema() . '.acof01_id_seq');

						foreach ($value->checklist_resumen->checklist_inspeccion as $item) {
							$item->id_padre = $idInspeccionAcof01;
							$array = json_decode(json_encode($item), true);

							unset($array['id_tablet']);
							$statement = $this->modeloCentrosAcopio->getAdapter()->getDriver()->createStatement();
							$sqlInsertar = $this->modeloCentrosAcopio->guardarSql('acof01_detalle', $this->modeloAcof01Detalle->getEsquema());
							$sqlInsertar->columns($this->modeloAcof01Detalle->getColumns());
							$sqlInsertar->values($array, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloCentrosAcopio->getAdapter(), $statement);
							$statement->execute();
						}
						$arrayResultadoInspeccion['id_operacion'] = $arrayResultadoInspeccion['id_solicitud'];
						$arrayResultadoInspeccion['tipo_solicitud'] = "Operadores";
						$this->lNegocioOperaciones->guardarResultadoInspeccion($arrayResultadoInspeccion);
			 	}
				 }


				echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registros almancenados en el Sistema GUIA exitosamente'));
				$procesoIngreso->commit();
			} catch (Exception $ex) {
				echo json_encode(array('estado' => 'error', 'mensaje' => $ex->getMessage()));
				$procesoIngreso->rollback();

				throw new GuardarExcepcionConDatos($ex);
			}
		} else {
			echo json_encode($arrayToken);
		}
	}

	/**
	 * Función para crear el PDF del checklist de inspeccion
	 */
	public function generarChecklistInspeccionCentroAcopio($idSolicitud, $nombreArchivo)
	{
		$jasper = new JasperReport();
		$datosReporte = array();

		$ruta = CEN_ACOPI_CHECK_LIST_TCPDF . $this->rutaFecha . '/';

		if (!file_exists($ruta)) {
			mkdir($ruta, 0777, true);
		}

		$rutaChecklistCentroAcopio = CEN_ACOPI_CHECK_LIST . $this->rutaFecha . '/';

		$datosReporte = array(
			'rutaReporte' => 'RegistroOperador/vistas/reportes/centroAcopioAI/checklistCentroAcopioAI.jasper',
			'rutaSalidaReporte' => 'RegistroOperador/archivos/checkList/centroAcopioAI/' . $this->rutaFecha . '/' . $nombreArchivo,
			'tipoSalidaReporte' => array('pdf'),
			'parametrosReporte' => array('idSolicitud' => $idSolicitud, 'rutaLogoAgro' => RUTA_IMG_GENE . 'agrocalidad.png'),
			'conexionBase' => 'SI'
		);

		$jasper->generarArchivo($datosReporte);

		$rutaChecklist = $rutaChecklistCentroAcopio . $nombreArchivo . '.pdf';

		return $rutaChecklist;
	}

	/**
	 * Función para enviar correo electrónico
	 */
	public function enviarCorreoInspeccionAco($idSolicitud, $rutaChecklist, $idOperadorTipoOperacion)
	{

		$qDatosOperacion = $this->lNegocioOperaciones->buscarLista(array('id_operador_tipo_operacion' => $idOperadorTipoOperacion));


		$qDatosOperador = $this->lNegocioOperadores->buscar($qDatosOperacion->current()->identificador_operador);
		$correo = $qDatosOperador->getCorreo();
		$nombreOperador = ($qDatosOperador->getRazonSocial() == "") ? $qDatosOperador->getApellidoRepresentante() . ' ' . $qDatosOperador->getNombreRepresentante() : $qDatosOperador->getRazonSocial();
		$estadoSolicitud = $qDatosOperacion->current()->estado;
		if ($estadoSolicitud == 'registrado') {
			$estadoSolicitud  = 'Aprobado';
		}
		$rutaChecklist =  Constantes::RUTA_SERVIDOR_OPT . '/' . Constantes::RUTA_APLICACION . '/' . $rutaChecklist;
		$arrayCorreo = array(
			'asunto' => 'Inspección de Centros de Acopio de Leche Cruda (CA)',
			'cuerpo' => 'El área de Inocuidad de los alimentos de la Agencia remite el día ' . $this->rutaFecha . ' el resultado ' . $estadoSolicitud . ' de la inspección Nº ' . $idSolicitud . ', remitido a ' . $nombreOperador . '.',
			'estado' => 'Por enviar',
			'codigo_modulo' => 'PRG_REGISTROOPER',
			'tabla_modulo' => 'g_operadores.centros_acopio',
			'id_solicitud_tabla' => $idSolicitud
		);

		$arrayDestinatario = array(
			$correo
		);
		$arrayAdjuntos = array(
			$rutaChecklist
		);

		return $this->lNegocioCorreos->crearCorreoElectronico($arrayCorreo, $arrayDestinatario, $arrayAdjuntos);
	}
}

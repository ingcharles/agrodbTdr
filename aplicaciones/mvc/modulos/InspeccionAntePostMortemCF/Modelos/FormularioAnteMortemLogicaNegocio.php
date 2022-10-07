<?php
/**
 * Lógica del negocio de FormularioAnteMortemModelo
 *
 * Este archivo se complementa con el archivo FormularioAnteMortemControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-05-27
 * @uses FormularioAnteMortemLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
namespace Agrodb\InspeccionAntePostMortemCF\Modelos;

use Agrodb\Core\Excepciones\GuardarExcepcion;
use Exception;
use Agrodb\Core\Constantes;

class FormularioAnteMortemLogicaNegocio implements IModelo{

	private $modeloFormularioAnteMortem = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloFormularioAnteMortem = new FormularioAnteMortemModelo();
	}

	/**
	 * VERIFICAR TIPO DE PERFIL DEL OPERADOR
	 */
	public function verificarPerfil($identificadorOperador){
		$sql = "SELECT
					p.nombre, p.codificacion_perfil
			  FROM
					g_usuario.usuarios_perfiles up
					INNER JOIN g_usuario.perfiles p ON up.id_perfil = p.id_perfil
					INNER JOIN g_programas.aplicaciones ap ON ap.id_aplicacion = p.id_aplicacion
			  WHERE
					identificador in ('" . $identificadorOperador . "') AND
					ap.codificacion_aplicacion='PRG_A_P_MORTE_CF';";
		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($sql);
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new FormularioAnteMortemModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdFormularioAnteMortem() != null && $tablaModelo->getIdFormularioAnteMortem() > 0){
			return $this->modeloFormularioAnteMortem->actualizar($datosBd, $tablaModelo->getIdFormularioAnteMortem());
		}else{
			unset($datosBd["id_formulario_ante_mortem"]);
			return $this->modeloFormularioAnteMortem->guardar($datosBd);
		}
	}

	/**
	 * Guarda el nuevo registro del formulario de aves
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardarDetalleAves(Array $datos){
		try{
			$this->modeloFormularioAnteMortem = new FormularioAnteMortemModelo();
			$proceso = $this->modeloFormularioAnteMortem->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción en: Guardar formulario de ante mortem de aves');
			}
			$idFormularioAnteMortem = $datos['id_formulario_ante_mortem'];

			if ($idFormularioAnteMortem == ''){
				$arraySecuencial = array(
					'identificador_operador' => $_SESSION['usuario']);
				$secuencial = $this->obtenerSecuencialFormularioAnteMortem($arraySecuencial);
				$secuencialFormulario = str_pad($secuencial->current()->numero, 6, "0", STR_PAD_LEFT);

				$arrayParametros = array(
					'id_centro_faenamiento' => $datos['idCentroFaenamiento']);
				$consulta = $this->buscarRazonSocialOperador($arrayParametros);
				$codigoProvincia = $consulta->current()->codigo_provincia;
				$codigoFormulario = $codigoProvincia . '-AM-' . $secuencialFormulario . '-' . date('dmY');

				$arrayParametros = array(
					'id_centro_faenamiento' => $datos['idCentroFaenamiento'],
					'identificador' => $_SESSION['usuario'],
					'estado' => 'Registrado',
					'codigo_formulario' => $codigoFormulario,
					'especie' => 'Avícola');

				$tablaModelo = new FormularioAnteMortemModelo($arrayParametros);
				$datosBd = $tablaModelo->getPrepararDatos();
				unset($datosBd["id_formulario_ante_mortem"]);
				$idFormularioAnteMortem = $this->modeloFormularioAnteMortem->guardar($datosBd);
			}
			// **********guardar hallazgos si existen********************************************
			$idAvesMuertas = $idCaracteristica = $idProbSist = $idCaractExter = '';
			if ($datos['hallazgos'] == 'Si'){
				// **********agregar hallazgos en aves muertas
				if ($this->verificarDatosAvesMuertas($datos)){
					$lNegocioHallazgosAvesMuertas = new HallazgosAvesMuertasLogicaNegocio();
					$datosAvesMuertas = array(
						'aves_muertas' => $datos['aves_muertas'],
						'porcent_aves_muertas' => $datos['porcent_aves_muertas'],
						'causa_probable' => $datos['causa_probable']);

					$statement = $this->modeloFormularioAnteMortem->getAdapter()
						->getDriver()
						->createStatement();
					$sqlInsertar = $this->modeloFormularioAnteMortem->guardarSql('hallazgos_aves_muertas', $this->modeloFormularioAnteMortem->getEsquema());
					$sqlInsertar->columns($lNegocioHallazgosAvesMuertas->columnas());
					$sqlInsertar->values($datosAvesMuertas, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioAnteMortem->getAdapter(), $statement);
					$statement->execute();
					$idAvesMuertas = $this->modeloFormularioAnteMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioAnteMortem->getEsquema() . '.hallazgos_aves_muertas_id_hallazgos_aves_muertas_seq');
					if (! $idAvesMuertas){
						throw new \Exception('Error al guardar los datos del los hallazgos en aves muertas');
					}
				}
				// **********agregar hallazgos de caracteristicas
				if ($this->verificarDatosCaracteristicas($datos)){
					$lNegocioHallazgosAvesCaract = new HallazgosAvesCaractLogicaNegocio();
					$datosCaracteristica = array(
						'decaidas' => ($datos['decaidas'] != '') ? $datos['decaidas'] : NULL,
						'porcent_decaidas' => ($datos['porcent_decaidas'] != '') ? $datos['porcent_decaidas'] : NULL,
						'num_traumas' => ($datos['num_traumas'] != '') ? $datos['num_traumas'] : NULL,
						'porcent_traumas' => ($datos['porcent_traumas'] != '') ? $datos['porcent_traumas'] : NULL);

					$statement = $this->modeloFormularioAnteMortem->getAdapter()
						->getDriver()
						->createStatement();
					$sqlInsertar = $this->modeloFormularioAnteMortem->guardarSql('hallazgos_aves_caract', $this->modeloFormularioAnteMortem->getEsquema());
					$sqlInsertar->columns($lNegocioHallazgosAvesCaract->columnas());
					$sqlInsertar->values($datosCaracteristica, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioAnteMortem->getAdapter(), $statement);
					$statement->execute();
					$idCaracteristica = $this->modeloFormularioAnteMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioAnteMortem->getEsquema() . '.hallazgos_aves_caract_id_hallazgos_aves_caract_seq');
					if (! $idCaracteristica){
						throw new \Exception('Error al guardar datos de hallazgos en caracteristicas de aves');
					}
				}
				// **********agregar hallazgos de problemas sistemicos
				if ($this->verificarDatosProbSiste($datos)){
					$lNegocioHallazgosSistematicos = new HallazgosAvesSistematicosLogicaNegocio();
					$datosSistematicos = array(
						'probl_respirat' => ($datos['probl_respirat'] != '') ? $datos['probl_respirat'] : NULL,
						'porcent_probl_respirat' => ($datos['porcent_probl_respirat'] != '') ? $datos['porcent_probl_respirat'] : NULL,
						'probl_nerviosos' => ($datos['probl_nerviosos'] != '') ? $datos['probl_nerviosos'] : NULL,
						'porcent_proble_nerviosos' => ($datos['porcent_proble_nerviosos'] != '') ? $datos['porcent_proble_nerviosos'] : NULL,
						'probl_digestivos' => ($datos['probl_digestivos'] != '') ? $datos['probl_digestivos'] : NULL,
						'porcent_probl_digestivos' => ($datos['porcent_probl_digestivos'] != '') ? $datos['porcent_probl_digestivos'] : NULL);

					$statement = $this->modeloFormularioAnteMortem->getAdapter()
						->getDriver()
						->createStatement();
					$sqlInsertar = $this->modeloFormularioAnteMortem->guardarSql('hallazgos_aves_sistematicos', $this->modeloFormularioAnteMortem->getEsquema());
					$sqlInsertar->columns($lNegocioHallazgosSistematicos->columnas());
					$sqlInsertar->values($datosSistematicos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioAnteMortem->getAdapter(), $statement);
					$statement->execute();
					$idProbSist = $this->modeloFormularioAnteMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioAnteMortem->getEsquema() . '.hallazgos_aves_sistematicos_id_hallazgos_aves_sistematicos_seq');
					if (! $idProbSist){
						throw new \Exception('Error al guardar datos de hallazgos en problemas sistematicos');
					}
				}
				// **********agregar hallazgos de caracteristicas externas
				if ($this->verificarDatosCaractExt($datos)){
					$lNegocioHallazgosAvesExternas = new HallazgosAvesExternasLogicaNegocio();
					$datosAvesExternas = array(
						'cabeza_hinchada' => ($datos['cabeza_hinchada'] != '') ? $datos['cabeza_hinchada'] : NULL,
						'porcent_cabeza_hinchada' => ($datos['porcent_cabeza_hinchada'] != '') ? $datos['porcent_cabeza_hinchada'] : NULL,
						'plumas_erizadas' => ($datos['plumas_erizadas'] != '') ? $datos['plumas_erizadas'] : NULL,
						'porcent_plumas_erizadas' => ($datos['porcent_plumas_erizadas'] != '') ? $datos['porcent_plumas_erizadas'] : NULL);

					$statement = $this->modeloFormularioAnteMortem->getAdapter()
						->getDriver()
						->createStatement();
					$sqlInsertar = $this->modeloFormularioAnteMortem->guardarSql('hallazgos_aves_externas', $this->modeloFormularioAnteMortem->getEsquema());
					$sqlInsertar->columns($lNegocioHallazgosAvesExternas->columnas());
					$sqlInsertar->values($datosAvesExternas, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioAnteMortem->getAdapter(), $statement);
					$statement->execute();
					$idCaractExter = $this->modeloFormularioAnteMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioAnteMortem->getEsquema() . '.hallazgos_aves_externas_id_hallazgos_aves_externas_seq');
					if (! $idCaractExter){
						throw new \Exception('Error al guardar datos de hallazgos en problemas sistematicos');
					}
				}
			}
			// registrar el detalle del formulario ante mortem aves
			$lNegocioDetalleAnteAves = new DetalleAnteAvesLogicaNegocio();
			$datosDetalleAnteAves = array(
				'id_formulario_ante_mortem' => $idFormularioAnteMortem,
				'fecha_formulario' => $datos['fecha_formulario'],
				'tipo_ave' => $datos['tipo_ave'],
				'lugar_procedencia' => $datos['lugar_procedencia'],
				'num_csmi' => $datos['num_csmi'],
				'total_aves' => $datos['total_aves'],
				'promedio_aves' => $datos['promedio_aves'],
				'hallazgos' => $datos['hallazgos'],
				'faenamiento_normal' => $datos['faenamiento_normal'],
				'procent_faenamiento_normal' => $datos['procent_faenamiento_normal'],
				'faenamiento_especial' => $datos['faenamiento_especial'],
				'porcent_faenamiento_especial' => $datos['porcent_faenamiento_especial'],
				'faenamiento_emergencia' => $datos['faenamiento_emergencia'],
				'porcent_emergencia' => $datos['porcent_emergencia'],
				'aplazamiento_faenamiento' => $datos['aplazamiento_faenamiento'],
				'porcent_aplazamiento_faenamiento' => $datos['porcent_aplazamiento_faenamiento'],
				'total_faenamiento' => $datos['total_faenamiento'],
				'observacion' => $datos['observacion'],
				'id_hallazgos_aves_muertas' => ($idAvesMuertas != '') ? $idAvesMuertas : NULL,
				'id_hallazgos_aves_caract' => ($idCaracteristica != '') ? $idCaracteristica : NULL,
				'id_hallazgos_aves_sistematicos' => ($idProbSist != '') ? $idProbSist : NULL,
				'id_hallazgos_aves_externas' => ($idCaractExter != '') ? $idCaractExter : NULL);

			$statement = $this->modeloFormularioAnteMortem->getAdapter()
				->getDriver()
				->createStatement();
			$sqlInsertar = $this->modeloFormularioAnteMortem->guardarSql('detalle_ante_aves', $this->modeloFormularioAnteMortem->getEsquema());
			$sqlInsertar->columns($lNegocioDetalleAnteAves->columnas());
			$sqlInsertar->values($datosDetalleAnteAves, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloFormularioAnteMortem->getAdapter(), $statement);
			$statement->execute();
			$idDetalle = $this->modeloFormularioAnteMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioAnteMortem->getEsquema() . '.detalle_ante_aves_id_detalle_ante_aves_seq');
			if (! $idDetalle){
				throw new \Exception('Error al guardar los datos en el detalle del formulario');
			}

			$proceso->commit();
			return $idFormularioAnteMortem;
		}catch (GuardarExcepcion $ex){
			$proceso->rollback();
			// throw new \Exception('Error al guardar los datos en el formulario');
			throw new \Exception($ex->getMessage());
			echo $ex->getMessage();
		}catch (Exception $exc){
			$proceso->rollback();
			throw new \Exception($exc->getMessage());
		}
	}

	/**
	 * verificar datos en hallazgos de aves muertas
	 *
	 * @return array
	 */
	public function verificarDatosAvesMuertas($arrayParametros){
		if ($arrayParametros['aves_muertas'] != ''){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * verificar datos en hallazgos de caracteristicas
	 *
	 * @return array
	 */
	public function verificarDatosCaracteristicas($arrayParametros){
		if ($arrayParametros['decaidas'] != ''){
			return true;
		}else if ($arrayParametros['num_traumas'] != ''){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * verificar datos en hallazgos de problemas sistémicos
	 *
	 * @return array
	 */
	public function verificarDatosProbSiste($arrayParametros){
		if ($arrayParametros['probl_respirat'] != ''){
			return true;
		}else if ($arrayParametros['probl_nerviosos'] != ''){
			return true;
		}else if ($arrayParametros['probl_digestivos'] != ''){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * verificar datos en hallazgos de caracteristicas externas
	 *
	 * @return array
	 */
	public function verificarDatosCaractExt($arrayParametros){
		if ($arrayParametros['cabeza_hinchada'] != ''){
			return true;
		}else if ($arrayParametros['plumas_erizadas'] != ''){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Guarda el nuevo registro del formulario de aves
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardarDetalleAnimales(Array $datos){
		try{
			$this->modeloFormularioAnteMortem = new FormularioAnteMortemModelo();
			$proceso = $this->modeloFormularioAnteMortem->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción en: Guardar formulario de ante mortem de aves');
			}
			$idFormularioAnteMortem = $datos['id_formulario_ante_mortem'];

			if ($idFormularioAnteMortem == ''){
				$arraySecuencial = array(
					'identificador_operador' => $_SESSION['usuario']);
				$secuencial = $this->obtenerSecuencialFormularioAnteMortem($arraySecuencial);
				$secuencialFormulario = str_pad($secuencial->current()->numero, 6, "0", STR_PAD_LEFT);

				$arrayParametros = array(
					'id_centro_faenamiento' => $datos['idCentroFaenamiento']);
				$consulta = $this->buscarRazonSocialOperador($arrayParametros);
				$codigoProvincia = $consulta->current()->codigo_provincia;
				$codigoFormulario = $codigoProvincia . '-AM-' . $secuencialFormulario . '-' . date('dmY');

				$arrayParametros = array(
					'id_centro_faenamiento' => $datos['idCentroFaenamiento'],
					'identificador' => $_SESSION['usuario'],
					'estado' => 'Registrado',
					'codigo_formulario' => $codigoFormulario,
					'especie' => $datos['especie']);

				$tablaModelo = new FormularioAnteMortemModelo($arrayParametros);
				$datosBd = $tablaModelo->getPrepararDatos();
				unset($datosBd["id_formulario_ante_mortem"]);
				$idFormularioAnteMortem = $this->modeloFormularioAnteMortem->guardar($datosBd);
			}
			// **********guardar hallazgos si existen en animales********************************************
			$idAnimalesMuertos = $idSignosClinicos = $idLocomocion = '';
			if ($datos['hallazgos'] == 'Si'){
				// **********agregar hallazgos en aves muertas
				if ($this->verificarDatosAnimalesMuertos($datos)){
					$lNegocioHallazgosAnimalesMuertos = new HallazgosAnimalesMuertosLogicaNegocio();
					$datosAnimalesMuertos = array(
						'num_animales_muertos' => $datos['num_animales_muertos'],
						'causa_probable' => ($datos['causa_probable'] != '') ? $datos['causa_probable'] : NULL,
						'decomiso' => ($datos['decomiso'] != '') ? $datos['decomiso'] : NULL,
						'aprovechamiento' => ($datos['aprovechamiento'] != '') ? $datos['aprovechamiento'] : NULL);

					$statement = $this->modeloFormularioAnteMortem->getAdapter()
						->getDriver()
						->createStatement();
					$sqlInsertar = $this->modeloFormularioAnteMortem->guardarSql('hallazgos_animales_muertos', $this->modeloFormularioAnteMortem->getEsquema());
					$sqlInsertar->columns($lNegocioHallazgosAnimalesMuertos->columnas());
					$sqlInsertar->values($datosAnimalesMuertos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioAnteMortem->getAdapter(), $statement);
					$statement->execute();
					$idAnimalesMuertos = $this->modeloFormularioAnteMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioAnteMortem->getEsquema() . '.hallazgos_animales_muertos_id_hallazgos_animales_muertos_seq');
					if (! $idAnimalesMuertos){
						throw new \Exception('Error al guardar los datos en hallazgos de animales muertos');
					}
				}
				// **********agregar hallazgos en signos clinicos
				if ($this->verificarDatosSignosClinicos($datos)){
					$lNegocioHallazgosAnimalesClinicos = new HallazgosAnimalesClinicosLogicaNegocio();
					$datosSignosClinicos = array(
						'num_animales_nerviosos' => ($datos['num_animales_nerviosos'] != '') ? $datos['num_animales_nerviosos'] : NULL,
						'num_animales_digestivo' => ($datos['num_animales_digestivo'] != '') ? $datos['num_animales_digestivo'] : NULL,
						'num_animales_respiratorio' => ($datos['num_animales_respiratorio'] != '') ? $datos['num_animales_respiratorio'] : NULL,
						'num_animales_vesicular' => ($datos['num_animales_vesicular'] != '') ? $datos['num_animales_vesicular'] : NULL,
						'num_animales_reproductivo' => ($datos['num_animales_reproductivo'] != '') ? $datos['num_animales_reproductivo'] : NULL);

					$statement = $this->modeloFormularioAnteMortem->getAdapter()
						->getDriver()
						->createStatement();
					$sqlInsertar = $this->modeloFormularioAnteMortem->guardarSql('hallazgos_animales_clinicos', $this->modeloFormularioAnteMortem->getEsquema());
					$sqlInsertar->columns($lNegocioHallazgosAnimalesClinicos->columnas());
					$sqlInsertar->values($datosSignosClinicos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioAnteMortem->getAdapter(), $statement);
					$statement->execute();
					$idSignosClinicos = $this->modeloFormularioAnteMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioAnteMortem->getEsquema() . '.hallazgos_animales_clinicos_id_hallazgos_animales_clinicos_seq');
					if (! $idSignosClinicos){
						throw new \Exception('Error al guardar datos de hallazgos signos clinicos');
					}
				}
				// **********agregar hallazgos en animales locomoción
				if ($this->verificarDatosLocomocion($datos)){
					$lNegocioHallazgosLocomocion = new HallazgosAnimalesLocomocionLogicaNegocio();
					$datosLocomocion = array(
						'num_animales_cojera' => ($datos['num_animales_cojera'] != '') ? $datos['num_animales_cojera'] : NULL,
						'num_animales_ambulatorios' => ($datos['num_animales_ambulatorios'] != '') ? $datos['num_animales_ambulatorios'] : NULL);

					$statement = $this->modeloFormularioAnteMortem->getAdapter()
						->getDriver()
						->createStatement();
					$sqlInsertar = $this->modeloFormularioAnteMortem->guardarSql('hallazgos_animales_locomocion', $this->modeloFormularioAnteMortem->getEsquema());
					$sqlInsertar->columns($lNegocioHallazgosLocomocion->columnas());
					$sqlInsertar->values($datosLocomocion, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioAnteMortem->getAdapter(), $statement);
					$statement->execute();
					$idLocomocion = $this->modeloFormularioAnteMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioAnteMortem->getEsquema() . '.hallazgos_animales_locomocion_id_hallazgos_animales_locomoc_seq');
					if (! $idLocomocion){
						throw new \Exception('Error al guardar datos de hallazgos en animales locomoción ');
					}
				}
			}
			// registrar el detalle del formulario ante mortem animales
			$lNegocioDetalleAnteAnimales = new DetalleAnteAnimalesLogicaNegocio();
			$datosDetalleAnteAnimales = array(
				'id_formulario_ante_mortem' => $idFormularioAnteMortem,
				'fecha_formulario' => $datos['fecha_formulario'],
				'num_csmi' => ($datos['num_csmi'] != '') ? $datos['num_csmi'] : NULL,
				'num_lote' => $datos['num_lote'],
				'especie' => $datos['especie'],
				'categoria_etaria' => $datos['categoria_etaria'],
				'peso_vivo_promedio' => $datos['peso_vivo_promedio'],
				'num_machos' => $datos['num_machos'],
				'num_hembras' => $datos['num_hembras'],
				'num_total_animales' => $datos['num_total_animales'],
				'hallazgos' => $datos['hallazgos'],
				'matanza_normal' => $datos['matanza_normal'],
				'matanza_especiales' => $datos['matanza_especiales'],
				'matanza_emergencia' => $datos['matanza_emergencia'],
				'aplazamiento_matanza' => $datos['aplazamiento_matanza'],
				'observacion' => $datos['observacion'],
				'id_hallazgos_animales_muertos' => ($idAnimalesMuertos != '') ? $idAnimalesMuertos : NULL,
				'id_hallazgos_animales_clinicos' => ($idSignosClinicos != '') ? $idSignosClinicos : NULL,
				'id_hallazgos_animales_locomocion' => ($idLocomocion != '') ? $idLocomocion : NULL);

			$statement = $this->modeloFormularioAnteMortem->getAdapter()
				->getDriver()
				->createStatement();
			$sqlInsertar = $this->modeloFormularioAnteMortem->guardarSql('detalle_ante_animales', $this->modeloFormularioAnteMortem->getEsquema());
			$sqlInsertar->columns($lNegocioDetalleAnteAnimales->columnas());
			$sqlInsertar->values($datosDetalleAnteAnimales, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloFormularioAnteMortem->getAdapter(), $statement);
			$statement->execute();
			$idDetalle = $this->modeloFormularioAnteMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioAnteMortem->getEsquema() . '.detalle_ante_animales_id_detalle_ante_animales_seq');
			if (! $idDetalle){
				throw new \Exception('Error al guardar los datos en el detalle del formulario');
			}
			$proceso->commit();
			return $idFormularioAnteMortem;
		}catch (GuardarExcepcion $ex){
			$proceso->rollback();
			// throw new \Exception('Error al guardar los datos en el formulario');
			throw new \Exception($ex->getMessage());
			echo $ex->getMessage();
		}catch (Exception $exc){
			$proceso->rollback();
			throw new \Exception($exc->getMessage());
		}
	}

	/**
	 * verificar datos en hallazgos de animales muertos
	 *
	 * @return array
	 */
	public function verificarDatosAnimalesMuertos($arrayParametros){
		if ($arrayParametros['num_animales_muertos'] != ''){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * verificar datos en hallazgos de signos clinicos
	 *
	 * @return array
	 */
	public function verificarDatosSignosClinicos($arrayParametros){
		if ($arrayParametros['num_animales_nerviosos'] != ''){
			return true;
		}else if ($arrayParametros['num_animales_digestivo'] != ''){
			return true;
		}else if ($arrayParametros['num_animales_respiratorio'] != ''){
			return true;
		}else if ($arrayParametros['num_animales_vesicular'] != ''){
			return true;
		}else if ($arrayParametros['num_animales_reproductivo'] != ''){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * verificar datos en hallazgos de locomocion
	 *
	 * @return array
	 */
	public function verificarDatosLocomocion($arrayParametros){
		if ($arrayParametros['num_animales_cojera'] != ''){
			return true;
		}else if ($arrayParametros['num_animales_ambulatorios'] != ''){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el secuencial
	 *
	 * @return array
	 */
	public function obtenerSecuencialFormularioAnteMortem($arrayParametros){
		$consulta = "SELECT
						COALESCE(count(*)::numeric, 0)+1 AS numero
					FROM
                        g_centros_faenamiento.formulario_ante_mortem
					WHERE
                        identificador = '" . $arrayParametros['identificador_operador'] . "';";

		$resultado = $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
		return $resultado;
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloFormularioAnteMortem->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return FormularioAnteMortemModelo
	 */
	public function buscar($id){
		return $this->modeloFormularioAnteMortem->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloFormularioAnteMortem->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloFormularioAnteMortem->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array ResultSet
	 */
	public function buscarFormularioAnteMortem(){
		$consulta = "SELECT * FROM " . $this->modeloFormularioAnteMortem->getEsquema() . ". formulario_ante_mortem";
		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada buscar centros de faenamiento asignados al operador.
	 *
	 * @return array
	 */
	public function buscarCfAsignados($arrayParametros){
		 $consulta = "SELECT 
                        case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
					    cf.id_centro_faenamiento 
					 FROM 
						g_centros_faenamiento.tipo_inspector ti 
						INNER JOIN g_centros_faenamiento.centro_faenamiento_tipo_inspector fti ON ti.id_tipo_inspector = fti.id_tipo_inspector
						INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON  fti.id_centro_faenamiento = cf.id_centro_faenamiento
						INNER JOIN g_operadores.sitios s ON s.id_sitio = cf.id_sitio 
						INNER JOIN g_operadores.operadores o ON o.identificador = cf.identificador_operador
					 WHERE 
						ti.identificador_operador = '" . $arrayParametros['identificador_operador'] . "' and 
						ti.resultado in ('Registrado','Por revisar') and 
						cf.criterio_funcionamiento in ('Habilitado','Activo') ;";

		return $this->modeloFormularioAnteMortem->ejecutarConsulta($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada buscar por mes formularios en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarFormulariosCfXMes($arrayParametros){
		$busqueda = "'Registrado','Por revisar'";
		if (array_key_exists('estado', $arrayParametros)){
			$busqueda = $arrayParametros['estado'];
		}
		$consulta = "SELECT
						distinct extract( month from fecha_creacion ) as mes, 
						(SELECT count(*) FROM g_centros_faenamiento.formulario_ante_mortem where extract( month from fecha_creacion ) = extract( month from fam.fecha_creacion ) and
						id_centro_faenamiento = " . $arrayParametros['id_centro_faenamiento'] . "  and estado in ($busqueda)) AS cantidad
					 FROM
						g_centros_faenamiento.formulario_ante_mortem fam
					 WHERE
						fam.id_centro_faenamiento = " . $arrayParametros['id_centro_faenamiento'] . " --and
						--fam.identificador = '" . $arrayParametros['identificador_operador'] . "' 
						and fam.estado in ($busqueda)
					 ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada buscar por años formularios en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarFormulariosCfXAnio($arrayParametros){
		$busqueda = "'" . $arrayParametros['estado'] . "'";
		$busq1 = $busq2 = $busq3 = '';
		if (array_key_exists('identificador_operador', $arrayParametros)){
			$busq1 = "and identificador=fam.identificador";
			$busq2 = "fam.identificador='" . $arrayParametros['identificador_operador'] . "' and";
		}
		if (array_key_exists('provincia', $arrayParametros)){
			$busq3 = "s.provincia='" . $arrayParametros['provincia'] . "' and";
		}
		 $consulta = "SELECT distinct extract( year from fam.fecha_creacion ) as anio, 
					(SELECT count(*) FROM g_centros_faenamiento.formulario_ante_mortem 
					where extract( year from fecha_creacion ) = extract( year from fam.fecha_creacion ) 
					$busq1
					and estado in ($busqueda)) AS cantidad 
					FROM g_centros_faenamiento.formulario_ante_mortem fam 
						INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_centro_faenamiento = fam.id_centro_faenamiento
						INNER JOIN g_operadores.operadores o ON o.identificador = cf.identificador_operador
						INNER JOIN g_operadores.sitios s ON s.id_sitio = cf.id_sitio
					WHERE 
					$busq2 $busq3
					fam.estado in ($busqueda) ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada buscar por años formularios en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarFormulariosCfXMeses($arrayParametros){
		$busqueda = "'" . $arrayParametros['estado'] . "'";
		$busq1 = $busq2 = '';
		if (array_key_exists('identificador_operador', $arrayParametros)){
			$busq1 = "and identificador=fam.identificador";
			$busq2 = "fam.identificador='" . $arrayParametros['identificador_operador'] . "' and ";
		}
	   $consulta = "SELECT distinct extract( month from fecha_creacion ) as mes,
					(SELECT count(*) FROM g_centros_faenamiento.formulario_ante_mortem
					where extract( month from fecha_creacion ) = extract( month from fam.fecha_creacion ) and
					extract( year from fecha_creacion ) = extract( year from fam.fecha_creacion )
					".$busq1."
					and estado in (".$busqueda.")) AS cantidad
					FROM g_centros_faenamiento.formulario_ante_mortem fam
					WHERE
					".$busq2."
					fam.estado in (".$busqueda.") and 
					extract( year from fam.fecha_creacion ) = ".$arrayParametros['anio']." ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada buscar por año formularios en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarDetalleFormulariosXAnio($arrayParametros){
		$busqueda = "'" . $arrayParametros['estado'] . "'";
		$busq1 = $busq2 = $busq3 = '';
		if (array_key_exists('identificador_operador', $arrayParametros)){
			$busq1 = "fam.identificador='" . $arrayParametros['identificador_operador'] . "' and ";
		}
		if (array_key_exists('filtro', $arrayParametros)){
			$busq2 = "
			    (" . $arrayParametros['fecha'] . " is NULL or extract( day from daa.fecha_formulario ) = " . $arrayParametros['fecha'] . " ) and
				(" . $arrayParametros['provinvia'] . " is NULL or s.provincia = " . $arrayParametros['provinvia'] . ") and
				(" . $arrayParametros['cFaenamiento'] . " is NULL or (upper(o.nombre_representante ||' '|| o.apellido_representante) like upper(". $arrayParametros['cFaenamiento'] . ") or upper(o.razon_social) like upper(" . $arrayParametros['cFaenamiento'] . "))) and
				(" . $arrayParametros['csmi'] . " is NULL or daa.num_csmi = " . $arrayParametros['csmi'] . ") and
				(" . $arrayParametros['codFormulario'] . " is NULL or fam.codigo_formulario = " . $arrayParametros['codFormulario'] . ") and
				(" . $arrayParametros['especie'] . " is NULL or upper(daa.especie) like upper(" . $arrayParametros['especie'] . ")) and";

			$busq3 = "
			    (" . $arrayParametros['fecha'] . " is NULL or extract( day from daav.fecha_formulario ) = " . $arrayParametros['fecha'] . " ) and
				(" . $arrayParametros['provinvia'] . " is NULL or s.provincia = " . $arrayParametros['provinvia'] . ") and
				(" . $arrayParametros['cFaenamiento'] . " is NULL or (upper(o.nombre_representante ||' '|| o.apellido_representante) like upper(". $arrayParametros['cFaenamiento'] . ") or upper(o.razon_social) like upper(" . $arrayParametros['cFaenamiento'] . "))) and
				(" . $arrayParametros['csmi'] . " is NULL or daav.num_csmi = " . $arrayParametros['csmi'] . ") and
				(" . $arrayParametros['codFormulario'] . " is NULL or fam.codigo_formulario = " . $arrayParametros['codFormulario'] . ") and
				(" . $arrayParametros['especie'] . " is NULL or upper(tipo_ave) like upper(" . $arrayParametros['especie'] . ")) and";
			
		}
	$consulta = "SELECT 
							case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
							fam.estado,
							daa.fecha_formulario,
							fam.codigo_formulario,
							daa.num_csmi,
							daa.especie as tipo_especie,
							fam.id_formulario_ante_mortem,
							fam.id_centro_faenamiento
						 FROM 
							 g_centros_faenamiento.formulario_ante_mortem fam 
							 INNER JOIN g_centros_faenamiento.detalle_ante_animales daa ON fam.id_formulario_ante_mortem = daa.id_formulario_ante_mortem
							 INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_centro_faenamiento = fam.id_centro_faenamiento
							 INNER JOIN g_operadores.operadores o ON o.identificador = cf.identificador_operador
							 INNER JOIN g_operadores.sitios s ON s.id_sitio = cf.id_sitio
						 WHERE 
							$busq2
							$busq1 
						 	fam.estado in ($busqueda)
						 	and extract( year from fam.fecha_creacion ) = " . $arrayParametros['anio'] . " 
						 	and extract( month from fam.fecha_creacion ) = " . $arrayParametros['mes'] . "
					UNION
						SELECT 
							case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
							fam.estado,
							daav.fecha_formulario,
							fam.codigo_formulario,
							daav.num_csmi,
							tipo_ave as especie,
							fam.id_formulario_ante_mortem,
                            fam.id_centro_faenamiento
						 FROM 
							 g_centros_faenamiento.formulario_ante_mortem fam 
							 INNER JOIN g_centros_faenamiento.detalle_ante_aves daav ON fam.id_formulario_ante_mortem = daav.id_formulario_ante_mortem
							 INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_centro_faenamiento = fam.id_centro_faenamiento
						     INNER JOIN g_operadores.operadores o ON o.identificador = cf.identificador_operador
						     INNER JOIN g_operadores.sitios s ON s.id_sitio = cf.id_sitio
						 WHERE 
						 	$busq3
							$busq1
						 	fam.estado in ($busqueda)
						 	and extract( year from fam.fecha_creacion ) = " . $arrayParametros['anio'] . "
							and extract( month from fam.fecha_creacion ) = " . $arrayParametros['mes'] . " 
							ORDER BY 1 ;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	
	/**
	 * Ejecuta una consulta(SQL) personalizada buscar Formularios de post mortem creados
	 *
	 * @return array
	 */
	public function buscarDetalleFormulariosPostMortemXAnio($idForAnteMortem){
	$consulta = "SELECT case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social, 
						fpm.estado, daa.fecha_formulario, fpm.codigo_formulario, daa.num_csmi, daa.especie as tipo_especie, 
						fpm.id_formulario_post_mortem, fam.especie
					FROM g_centros_faenamiento.formulario_ante_mortem fam  
						INNER JOIN g_centros_faenamiento.detalle_ante_animales daa ON fam.id_formulario_ante_mortem = daa.id_formulario_ante_mortem 
						INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_centro_faenamiento = fam.id_centro_faenamiento 
						INNER JOIN g_operadores.operadores o ON o.identificador = cf.identificador_operador 
						INNER JOIN g_operadores.sitios s ON s.id_sitio = cf.id_sitio 
						INNER JOIN g_centros_faenamiento.detalle_post_animales dpa ON dpa.id_detalle_ante_animales = daa.id_detalle_ante_animales 
						INNER JOIN g_centros_faenamiento.formulario_post_mortem fpm ON fpm.id_formulario_post_mortem = dpa.id_formulario_post_mortem
					WHERE 
						fam.id_formulario_ante_mortem = ".$idForAnteMortem."
					UNION 
					SELECT case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social, 
						fpm.estado, daav.fecha_formulario, fpm.codigo_formulario, daav.num_csmi, tipo_ave as tipo_especie, 
						fpm.id_formulario_post_mortem, fam.especie
					FROM 
						g_centros_faenamiento.formulario_ante_mortem fam 
						INNER JOIN g_centros_faenamiento.detalle_ante_aves daav ON fam.id_formulario_ante_mortem = daav.id_formulario_ante_mortem 
						INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_centro_faenamiento = fam.id_centro_faenamiento 
						INNER JOIN g_operadores.operadores o ON o.identificador = cf.identificador_operador 
						INNER JOIN g_operadores.sitios s ON s.id_sitio = cf.id_sitio 
						INNER JOIN g_centros_faenamiento.detalle_post_aves dpa ON dpa.id_detalle_ante_aves = daav.id_detalle_ante_aves 
						INNER JOIN g_centros_faenamiento.formulario_post_mortem fpm ON fpm.id_formulario_post_mortem = dpa.id_formulario_post_mortem
					WHERE 
						fam.id_formulario_ante_mortem = ".$idForAnteMortem." ORDER BY 1 ;";
							
		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada buscar por mes formularios en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarFormulariosCfXMesAux($arrayParametros){
		$busqueda = "'Registrado','Por revisar'";
		if (array_key_exists('estado', $arrayParametros)){
			$busqueda = $arrayParametros['estado'];
		}
		$consulta = "SELECT
						distinct extract( month from fecha_creacion ) as mes,
						(SELECT count(*) FROM g_centros_faenamiento.formulario_ante_mortem where extract( month from fecha_creacion ) = extract( month from fam.fecha_creacion ) and
						id_centro_faenamiento = " . $arrayParametros['id_centro_faenamiento'] . " and identificador = '" . $arrayParametros['identificador_operador'] . "' and estado in ($busqueda)) AS cantidad
					 FROM
						g_centros_faenamiento.formulario_ante_mortem fam
					 WHERE
						fam.id_centro_faenamiento = " . $arrayParametros['id_centro_faenamiento'] . " and
						fam.identificador = '" . $arrayParametros['identificador_operador'] . "'
						and fam.estado in ($busqueda)
					 ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada buscar médico veterinaria
	 *
	 * @return array
	 */
	public function buscarIdentificadorMedicoVeterinario($arrayParametros){
		$consulta = "SELECT 
						ti.identificador_operador 
					 FROM 
						g_centros_faenamiento.centro_faenamiento_tipo_inspector cfti 
						INNER JOIN g_centros_faenamiento.tipo_inspector ti ON cfti.id_tipo_inspector = ti. id_tipo_inspector
					 WHERE 
						cfti.id_centro_faenamiento = ".$arrayParametros['id_centro_faenamiento']." 
						and cfti.estado = 'activo' 
						and ti.tipo_inspector like '%Veterinario%';";
		
		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada buscar formularios en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarFormulariosCf($arrayParametros){
		$busqueda = "and fam.estado in ('Registrado','Por revisar','Aprobado_AM')";
		if (array_key_exists('estado', $arrayParametros)){
			$busqueda = "and fam.estado in (" . $arrayParametros['estado'] . ")";
		}
		$consulta = "SELECT
							id_formulario_ante_mortem ,to_char(fam.fecha_creacion,'YYYY-MM-DD') as fecha_creacion, fam.estado, fam.especie,fam.codigo_formulario
					 FROM
						g_centros_faenamiento.formulario_ante_mortem fam
					 WHERE
						fam.id_centro_faenamiento = " . $arrayParametros['id_centro_faenamiento'] . " and
						--fam.identificador = '" . $arrayParametros['identificador_operador'] . "' and
						
						extract( month from fecha_creacion ) = " . $arrayParametros['mes'] . "
						" . $busqueda . "  ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada buscar formularios en centros de faenamiento de auxiliares.
	 *
	 * @return array
	 */
	public function buscarFormulariosCfAux($arrayParametros){
		$busqueda = "and fam.estado in ('Registrado','Por revisar','Aprobado_AM')";
		if (array_key_exists('estado', $arrayParametros)){
			$busqueda = "and fam.estado in (" . $arrayParametros['estado'] . ")";
		}
		$consulta = "SELECT
							id_formulario_ante_mortem ,to_char(fam.fecha_creacion,'YYYY-MM-DD') as fecha_creacion, fam.estado, fam.especie,fam.codigo_formulario
					 FROM
						g_centros_faenamiento.formulario_ante_mortem fam
					 WHERE
						fam.id_centro_faenamiento = " . $arrayParametros['id_centro_faenamiento'] . " and
						fam.identificador = '" . $arrayParametros['identificador_operador'] . "' and
							
						extract( month from fecha_creacion ) = " . $arrayParametros['mes'] . "
						" . $busqueda . "  ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada detalles de formularios de aves en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarDetalleFormularioAves($idFormularioAnteMortem){
		$consulta = "SELECT 
						daa.id_detalle_ante_aves,to_char(daa.fecha_formulario,'YYYY-MM-DD') as fecha_formulario, daa.num_csmi, daa.tipo_ave
					 FROM 
						g_centros_faenamiento.detalle_ante_aves daa 
					 WHERE 
						id_formulario_ante_mortem = $idFormularioAnteMortem
					 ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada detalles de formularios de animales en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarDetalleFormularioAnimales($idFormularioAnteMortem){
		$consulta = "SELECT
						daa.id_detalle_ante_animales,to_char(daa.fecha_formulario,'YYYY-MM-DD') as fecha_formulario, daa.num_csmi, daa.especie
					 FROM
						g_centros_faenamiento.detalle_ante_animales daa
					 WHERE
						id_formulario_ante_mortem = $idFormularioAnteMortem
					 ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada detalles de formularios de animales en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarAMDetalleFormularioAnimales($idFormularioAnteMortem){
		$consulta = "SELECT
						fam.id_formulario_ante_mortem, id_centro_faenamiento,to_char(fam.fecha_creacion,'YYYY-MM-DD') as fecha_creacion, 
						fam.estado, fam.especie,fam.codigo_formulario,categoria_etaria as tipo,num_csmi,id_detalle_ante_animales as id_detalle
					 FROM
						g_centros_faenamiento.formulario_ante_mortem fam 
						INNER JOIN g_centros_faenamiento.detalle_ante_animales daa ON fam.id_formulario_ante_mortem = daa.id_formulario_ante_mortem
					 WHERE
						fam.id_formulario_ante_mortem = $idFormularioAnteMortem
					 ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada detalles de formularios de aves en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarAMDetalleFormularioAves($idFormularioAnteMortem){
		$consulta = "SELECT
						fam.id_formulario_ante_mortem, id_centro_faenamiento,to_char(fam.fecha_creacion,'YYYY-MM-DD') as fecha_creacion, 
						fam.estado, fam.especie,fam.codigo_formulario,tipo_ave as tipo, num_csmi, id_detalle_ante_aves as id_detalle
					 FROM
						g_centros_faenamiento.formulario_ante_mortem fam 
						INNER JOIN g_centros_faenamiento.detalle_ante_aves daa ON fam.id_formulario_ante_mortem = daa.id_formulario_ante_mortem
					 WHERE
						fam.id_formulario_ante_mortem = $idFormularioAnteMortem
					 ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada detalles de formularios de aves en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarDetalleFormularioXIdAves($idDetalleAnteAves){
		$consulta = "SELECT
						*
					 FROM
						g_centros_faenamiento.detalle_ante_aves daa
					 WHERE
						id_detalle_ante_aves = $idDetalleAnteAves
					 ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada detalles de formularios de animales en centros de faenamiento.
	 *
	 * @return array
	 */
	public function buscarDetalleFormularioXIdAnimales($idDetalleAnteAves){
		$consulta = "SELECT
						*
					 FROM
						g_centros_faenamiento.detalle_ante_aves daa
					 WHERE
						id_detalle_ante_aves = $idDetalleAnteAves
					 ORDER BY 1;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada buscar razon social de operador.
	 *
	 * @return array
	 */
	public function buscarRazonSocialOperador($arrayParametros){
		$consulta = "SELECT  
					    case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
					    s.provincia, s.canton, s.parroquia, especie, codigo_provincia, cf.id_operador_tipo_operacion
					 FROM 
						g_centros_faenamiento.centros_faenamiento cf 
					    INNER JOIN g_operadores.operadores o ON o.identificador = cf.identificador_operador
					    INNER JOIN g_operadores.sitios s ON s.id_sitio = cf.id_sitio
					 WHERE  
						cf.id_centro_faenamiento = " . $arrayParametros['id_centro_faenamiento'] . " ;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada información del operador.
	 *
	 * @return array
	 */
	public function buscarDatosOperador($identificadorOperador){
		$consulta = "SELECT
					    case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_medico,
						o.correo
					 FROM
						g_operadores.operadores o 
					 WHERE
						o.identificador = '" . $identificadorOperador . "' ;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada información del operador.
	 *
	 * @return array
	 */
	public function buscarDatosProductos($idOperadorTipoOperacion, $especie){
		$consulta = "SELECT 
							o.id_producto, o.nombre_producto, sp.nombre 
					FROM 
						g_operadores.operaciones o 
						INNER JOIN g_catalogos.productos p ON o.id_producto = p.id_producto
						INNER JOIN g_catalogos.subtipo_productos sp ON sp.id_subtipo_producto = p.id_subtipo_producto 
					WHERE 
						o.id_operador_tipo_operacion = " . $idOperadorTipoOperacion . " and 
						o.estado = 'registrado' 
						and sp.nombre=rtrim('" . $especie . "') ;";

		return $this->modeloFormularioAnteMortem->ejecutarSqlNativo($consulta);
	}

	// ******devolver rango de fechas a utlizar
	public function fechaInicalAves($fecha){
		$date = new \DateTime($fecha);
		$dias = array(
			"Mon" => 0,
			"Tue" => 1,
			"Wed" => 2,
			"Thu" => 3,
			"Fri" => 4,
			"Sat" => 5,
			"Sun" => 6);
		$resDay = '-' . $dias[$date->format('D')] . ' day';
		$fechaInical = strtotime($resDay, strtotime($fecha));
		$fechaInical = date("Y-m-d", $fechaInical);
		return $fechaInical;
	}

	// ******devolver rango de fechas a utlizar
	public function formatearFecha($fecha){
		$date = new \DateTime($fecha);
		$fechaFormateada = $date->format('Y-m-d');
		return $fechaFormateada;
	}

	// ******devolver el mes****
	public function mesEnLetras($num){
		$meses = array(
			1 => "Enero",
			2 => "Febrero",
			3 => "Marzo",
			4 => "Abril",
			5 => "Mayo",
			6 => "Junio",
			7 => "Julio",
			8 => "Agosto",
			9 => "Septiembre",
			10 => "Octubre",
			11 => "Noviembre",
			12 => "Diciembre");
		return $meses[$num];
	}

	// ***************************Generar formulario de animales****************************************
	public function generarFormularioAnimales($arrayDatos){
		ob_start();
		// ************************************************** INICIO ***********************************************************

		$margen_superior = 10;
		$margen_inferior = 8;
		$margen_izquierdo = 10;
		$margen_derecho = 10;

		// header('Content-type: application/pdf');

		$doc = new ReportesPdfAnteModelo('L', 'mm', 'A4', true, 'UTF-8');

		$tipoLetra = 'helvetica';
		// ******************************************* FIRMA *************************************************************************
		$doc->SetLineWidth(0.1);
		$doc->setCellHeightRatio(1.5);
		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);
		$doc->SetFont($tipoLetra, '', 9);
		$doc->AddPage();

		// ***********************************QR EN FIRMA ELECTRONICA**********************
		$style = array(
			'border' => 0,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(
				0,
				0,
				0),
			'bgcolor' => false,
			'module_width' => 1,
			'module_height' => 1);

		// ****************************************************************************
		$variable = explode('-', $arrayDatos['idFormularioDetalle']);
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[1]);
		$consulta = $this->buscarRazonSocialOperador($arrayParametros);
		
		$identifi = $this->buscarIdentificadorMedicoVeterinario($arrayParametros);
		$datos = $this->buscarDatosOperador($identifi->current()->identificador_operador);
		
		$rutaQRG = '
        FORMULARIO DE INSPECCIÓN ANTE-MORTEM EN CENTROS DE FAENAMIENTO - RUMIANTES Y MONOGÁSTRICOS
        ' . $consulta->current()->razon_social . '
        ' . $datos->current()->nombre_medico . '
        ' . $this->formatearFecha($arrayDatos['fechaCreacion']);

		// ****************************** INICIA *************************************
		$doc->SetTextColor();
		$doc->SetFont($tipoLetra, 'B', 13);
		$xfull = $doc->getPageWidth() / 2;
		$doc->writeHTMLCell(200, 0, $xfull - 100, $margen_superior, $arrayDatos['titulo'], '', 1, 0, true, 'C', true);

		$xfull = $doc->getPageWidth() - 25 - $margen_derecho;
		$doc->write2DBarcode($rutaQRG, 'QRCODE,Q', $xfull, $margen_superior, 25, 25, $style, 'N');
		$doc->SetFont($tipoLetra, '', 11);
		$y = $doc->GetY();
		$doc->writeHTMLCell(0, 0, $margen_izquierdo, $y - 9, $arrayDatos['subtitulo'], '', 1, 0, true, 'C', true);
		// ***********************************seccion A**************************************************************
		$doc->SetFont($tipoLetra, 'B', 8);
		// ***********************************************************************************
		$alto = 10;
		$y = $doc->GetY() + 5;
		$tamañoColumn = $doc->getPageWidth() - $margen_izquierdo - $margen_derecho;
		$doc->crearTabla($tipoLetra, $margen_izquierdo, $tamañoColumn, $y, $arrayDatos['seccionA'], $alto, array(
			247,
			255,
			147), 6);
		$ytxt = 6;
		$doc->SetFont($tipoLetra, 'B', 8);
		$doc->Text($margen_izquierdo, $y + $ytxt, '1. Nombre del centro de faenamiento:');
		$doc->SetFont($tipoLetra, '', 8);
		$doc->writeHTMLCell(80, 0, $margen_izquierdo + 52, $y + $ytxt, $consulta->current()->razon_social, '', 0, 0, false, 'L', false);
		$xtxt = $doc->getPageWidth() / 2;
		$doc->SetFont($tipoLetra, 'B', 8);
		$doc->Text($xtxt, $y + $ytxt, '2. Médico Veterinario Oficial o Autorizado:');
		$doc->SetFont($tipoLetra, '', 8);
		$doc->writeHTMLCell(85, 0, $xtxt + 58, $y + $ytxt, $datos->current()->nombre_medico, '', 0, 0, false, 'L', false);
		$ytxt = 11;
		$doc->SetFont($tipoLetra, 'B', 8);
		$doc->Text($margen_izquierdo, $y + $ytxt, '3. Provincia:');
		$xtxt = 18;
		$doc->SetFont($tipoLetra, '', 8);
		$doc->writeHTMLCell(70, 0, $margen_izquierdo + $xtxt, $y + $ytxt, $consulta->current()->provincia, '', 0, 0, false, 'L', false);
		$xtxt = $doc->getPageWidth() / 3;
		$doc->SetFont($tipoLetra, 'B', 8);
		$doc->Text($margen_izquierdo + $xtxt, $y + $ytxt, '4. Cantón:');
		$doc->SetFont($tipoLetra, '', 8);
		$doc->writeHTMLCell(70, 0, $margen_izquierdo + $xtxt + 15, $y + $ytxt, $consulta->current()->canton, '', 0, 0, false, 'L', false);
		$xtxt = ($doc->getPageWidth() / 3) * 2;
		$doc->SetFont($tipoLetra, 'B', 8);
		$doc->Text($margen_izquierdo + $xtxt, $y + $ytxt, '5. Parroquia:');
		$doc->SetFont($tipoLetra, '', 8);
		$doc->writeHTMLCell(70, 0, $margen_izquierdo + $xtxt + 18, $y + $ytxt, $consulta->current()->parroquia, '', 0, 0, false, 'L', false);

		// *************************************seccion B************************************************************
		$doc->SetFont($tipoLetra, 'B', 8);
		$y = $doc->GetY();
		$alto = 10;
		$tamañoColumn = $doc->getPageWidth() - $margen_izquierdo - $margen_derecho;
		$doc->crearTablaHeader($tipoLetra, $margen_izquierdo, $tamañoColumn, $y - 42.7, $arrayDatos['seccionB'], $alto, array(
			192,
			234,
			255), 6);

		$doc->SetFont($tipoLetra, '', 6);
		$p1 = 109;
		$p2 = 46;
		$p3 = 40;
		$p4 = 18;
		$p5 = 35;
		$p6 = $tamañoColumn - $p1 - $p2 - $p3 - $p4 + 4 - $p5;
		$y = $doc->GetY();
		$genera = $margen_izquierdo;
		$anima = $margen_izquierdo + $p1 - 4;
		$signos = $margen_izquierdo + $p1 + $p2 - 4;
		$locomo = $margen_izquierdo + $p1 + $p2 + $p3 - 4;
		$dictamen = $margen_izquierdo + $p1 + $p2 + $p3 + $p4 - 4;
		$observacion = $margen_izquierdo + $p1 + $p2 + $p3 + $p4 - 4 + $p5;

		$doc->crearEncabezadoTabla($tipoLetra, $genera, $p1 - 4, $y, 'GENERALIDADES', array(
			255,
			255,
			255), 6, 0.1, 0.1);
		$doc->crearEncabezadoTabla($tipoLetra, $anima, $p2, $y, 'ANIMALES MUERTOS', array(
			255,
			255,
			255), 6, 0.1, - 0.5);
		$doc->crearEncabezadoTabla($tipoLetra, $signos, $p3, $y, 'SIGNOS CLÍNICOS VISIBLES', array(
			255,
			255,
			255), 6, 0.1, - 0.6);
		$doc->crearEncabezadoTabla($tipoLetra, $locomo, $p4, $y, 'LOCOMOCIÓN', array(
			255,
			255,
			255), 6, 0.1, - 0.6);
		$doc->crearEncabezadoTabla($tipoLetra, $dictamen, $p5, $y, 'DICTAMEN', array(
			255,
			255,
			255), 6, 0.1, - 0.6);
		$doc->crearEncabezadoTabla($tipoLetra, $observacion, $p6, $y, 'OBSERVACIONES', array(
			255,
			255,
			255), 36.1, 0.1, 12);

		$y1 = $y + 3;
		$x = $genera;
		$ancho = $p1 / 9;
		$doc->textoVertical('Fecha', $x, $y1, $tipoLetra, 6, $ancho, 5, 0);
		$doc->textoVertical('Nro. de CSMI', $x + $ancho, $y1, $tipoLetra, 6, $ancho + 2, 5);
		$doc->textoVertical('Nro. de lote', $x + $ancho * 2, $y1, $tipoLetra, 6, $ancho + 2, 5);
		$doc->textoVertical('Especie', $x + $ancho * 3, $y1, $tipoLetra, 6, $ancho, 5);
		$doc->textoVertical('Etapa productiva <br>(categoría etária)', $x + $ancho * 4, $y1, $tipoLetra, 6, $ancho, 3);
		$doc->textoVertical('Peso vivo promedio <br>(Kg)', $x + $ancho * 5, $y1, $tipoLetra, 6, $ancho - 1, 2);
		$doc->textoVertical('Nro. Machos', $x + $ancho * 6 - 1, $y1, $tipoLetra, 6, $ancho - 1, 4);
		$doc->textoVertical('Nro. Hembras', $x + $ancho * 7 - 2, $y1, $tipoLetra, 6, $ancho - 1, 4);
		$doc->textoVertical('Nro. total de animales', $x + $ancho * 8 - 3, $y1, $tipoLetra, 6, $ancho - 1, 4);

		$doc->SetFont($tipoLetra, '', 7);
		$x = $anima;
		$ancho = $p2 / 4;
		$doc->textoVertical('Nro. de animales<br>muertos', $x, $y1, $tipoLetra, 6, $ancho - 3, 1.5);
		$doc->textoVertical('Causa probable', $x + $ancho - 3, $y1, $tipoLetra, 6, $ancho + 9, 9);
		$doc->textoVertical('Decomiso', $x + $ancho * 2 + 6, $y1, $tipoLetra, 6, $ancho, 3.5);
		$doc->textoVertical('Aprovechamiento', $x + $ancho * 3 + 3, $y1, $tipoLetra, 6, $ancho, 3.2);

		$x = $signos;
		$ancho = $p3 / 5;
		$doc->textoVertical('Nro. de animales con <br>síndrome nervioso', $x, $y1, $tipoLetra, 6, $ancho, 1);
		$doc->textoVertical('Nro. de animales con <br>síndrome digestivo', $x + $ancho, $y1, $tipoLetra, 6, $ancho, 1);
		$doc->textoVertical('Nro. de animales con <br>síndrome respiratorio', $x + $ancho * 2, $y1, $tipoLetra, 6, $ancho, 1);
		$doc->textoVertical('Nro. de animales con <br>síndrome vesicular', $x + $ancho * 3, $y1, $tipoLetra, 6, $ancho, 1);
		$doc->textoVertical('Nro. de animales con <br>síndrome reproductivo)', $x + $ancho * 4, $y1, $tipoLetra, 6, $ancho, 1);

		$x = $locomo;
		$ancho = $p4 / 2;
		$doc->textoVertical('Animales con cojera <br>(Nro.)', $x, $y1, $tipoLetra, 6, $ancho, 2);
		$doc->textoVertical('Animales no ambulatorios<br>(Nro.)', $x + $ancho, $y1, $tipoLetra, 6, $ancho, 2);

		$x = $dictamen;
		$ancho = $p5 / 4;
		$doc->textoVertical('Matanza normal <br>(Nro.)', $x, $y1, $tipoLetra, 6, $ancho, 1);
		$doc->textoVertical('Matanza bajo precauciones<br>especiales (Nro.)', $x + $ancho, $y1, $tipoLetra, 6, $ancho, 1);
		$doc->textoVertical('Matanza de <br>emergencia (Nro.)', $x + $ancho * 2, $y1, $tipoLetra, 6, $ancho, 1);
		$doc->textoVertical('Aplazamiento de <br>matanza (Nro.)', $x + $ancho * 3, $y1, $tipoLetra, 6, $ancho, 1);

		$lnDetalleAnteAnimales = new DetalleAnteAnimalesLogicaNegocio();
		$lnHallazgosAnimalesMuertos = new HallazgosAnimalesMuertosLogicaNegocio();
		$lnHallazgosAnimalesLocomocion = new HallazgosAnimalesLocomocionLogicaNegocio();
		$lnHallazgosAnimalesClinicos = new HallazgosAnimalesClinicosLogicaNegocio();

		$consulta = "id_formulario_ante_mortem = " . $arrayDatos['id_formulario_ante_mortem'] . " order by 1";
		$consultaDetalle = $lnDetalleAnteAnimales->buscarLista($consulta);

		$arrayGenera = array();
		$arraAnima = array();
		$arraySignos = array();
		$arrayLocomo = array();

		foreach ($consultaDetalle as $items){

			if ($items['id_hallazgos_animales_muertos'] != ''){
				$consulta = "id_hallazgos_animales_muertos = " . $items['id_hallazgos_animales_muertos'] . " order by 1";
				$consultaAnimales = $lnHallazgosAnimalesMuertos->buscarLista($consulta);
				foreach ($consultaAnimales as $itemsAnimales){
					$arraAnima = array(
						'num_animales_muertos' => $itemsAnimales['num_animales_muertos'],
						'causa_probable' => $itemsAnimales['causa_probable'],
						'decomiso' => $itemsAnimales['decomiso'],
						'aprovechamiento' => $itemsAnimales['aprovechamiento']);
				}
			}else{
				$arraAnima = array(
					'');
			}
			if ($items['id_hallazgos_animales_clinicos'] != null || $items['id_hallazgos_animales_clinicos'] != ''){
				$consulta = "id_hallazgos_animales_clinicos = " . $items['id_hallazgos_animales_clinicos'] . " order by 1";
				$consultaClinicos = $lnHallazgosAnimalesClinicos->buscarLista($consulta);
				foreach ($consultaClinicos as $itemsClinicos){
					$arraySignos = array(
						'num_animales_nerviosos' => $itemsClinicos['num_animales_nerviosos'],
						'num_animales_digestivo' => $itemsClinicos['num_animales_digestivo'],
						'num_animales_respiratorio' => $itemsClinicos['num_animales_respiratorio'],
						'num_animales_vesicular' => $itemsClinicos['num_animales_vesicular'],
						'num_animales_reproductivo' => $itemsClinicos['num_animales_reproductivo']);
				}
			}else{
				$arraySignos = array(
					'');
			}
			if ($items['id_hallazgos_animales_locomocion'] != null || $items['id_hallazgos_animales_locomocion'] != ''){
				$consulta = "id_hallazgos_animales_locomocion = " . $items['id_hallazgos_animales_locomocion'] . " order by 1";
				$consultaLocomocion = $lnHallazgosAnimalesLocomocion->buscarLista($consulta);
				foreach ($consultaLocomocion as $itemslocomocion){
					$arrayLocomo = array(
						'num_animales_cojera' => $itemslocomocion['num_animales_cojera'],
						'num_animales_ambulatorios' => $itemslocomocion['num_animales_ambulatorios']);
				}
			}else{
				$arrayLocomo = array(
					'');
			}

			$arrayGenera[] = array(
				'fecha_formulario' => $this->formatearFecha($items['fecha_formulario']),
				'num_csmi' => $items['num_csmi'],
				'num_lote' => $items['num_lote'],
				'especie' => $items['especie'],
				'categoria_etaria' => $items['categoria_etaria'],
				'peso_vivo_promedio' => $items['peso_vivo_promedio'],
				'num_machos' => $items['num_machos'],
				'num_hembras' => $items['num_hembras'],
				'num_total_animales' => $items['num_total_animales'],
				'matanza_normal' => $items['matanza_normal'],
				'matanza_especiales' => $items['matanza_especiales'],
				'matanza_emergencia' => $items['matanza_emergencia'],
				'aplazamiento_matanza' => $items['aplazamiento_matanza'],
				'observacion' => $items['observacion'],
				'arraAnima' => $arraAnima,
				'arraySignos' => $arraySignos,
				'arrayLocomo' => $arrayLocomo);
		}

		$y2 = $y1 + 33; // 23 48
		$i = 0;
		foreach ($arrayGenera as $items){
			$doc->crearFilasAnimales($items, $y2, $genera, $anima, $signos, $locomo, $dictamen, $observacion, $tipoLetra, $p1, $p2, $p3, $p4, $p5, $p6);
			$y2 = $y2 + 4;
			if ($doc->verificarFilas($i)){
				$y2 = 12;
				$doc->AddPage();
			}
			$i ++;
		}
		if ($doc->verificarFilas($i, true)){
			$y2 = 12;
			$doc->AddPage();
		}

		// *****************************************************Seccion C******************************
		$doc->firma($margen_izquierdo, $y2 + 10, $tamañoColumn, 6, $arrayDatos['seccionC'], $arrayDatos['seccionFirma'], $tipoLetra, array(
			210,
			210,
			210));
		// ******************************* FIN DE LA EDICION ****************************************************************************************
		$doc->Output(INSP_AP_TCPDF . "reportes/formulariosAM/" . $arrayDatos['nombreArchivo'] . ".pdf", 'F');
		ob_end_clean();
	}

	// ***************************Generar formulario de aves****************************************
	public function generarFormularioAves($arrayDatos){
		ob_start();
		// ************************************************** INICIO ***********************************************************

		$margen_superior = 10;
		$margen_inferior = 8;
		$margen_izquierdo = 10;
		$margen_derecho = 10;

		// header('Content-type: application/pdf');
		$doc = new ReportesPdfAnteModelo('L', 'mm', 'A4', true, 'UTF-8');

		$tipoLetra = 'helvetica';
		// ******************************************* FIRMA *************************************************************************
		$doc->SetLineWidth(0.1);
		$doc->setCellHeightRatio(1.5);
		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);
		$doc->SetFont($tipoLetra, '', 9);
		$doc->AddPage();
		// ***********************************QR EN FIRMA ELECTRONICA**********************

		$style = array(
			'border' => 0,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(
				0,
				0,
				0),
			'bgcolor' => false,
			'module_width' => 1,
			'module_height' => 1);

		// ****************************************************************************
		$variable = explode('-', $arrayDatos['idFormularioDetalle']);
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[1]);
		$consulta = $this->buscarRazonSocialOperador($arrayParametros);
		//$datos = $this->buscarDatosOperador($_SESSION['usuario']);
		$identifi = $this->buscarIdentificadorMedicoVeterinario($arrayParametros);
		$datos = $this->buscarDatosOperador($identifi->current()->identificador_operador);

		$rutaQRG = $arrayDatos['titulo'] . '
		        Centro: ' . $consulta->current()->razon_social . '
		        Médico: ' . $datos->current()->nombre_medico . '
		        ' . $this->formatearFecha($arrayDatos['fechaCreacion']);

		// ****************************** INICIA *************************************
		$doc->SetTextColor();
		$doc->SetFont($tipoLetra, 'B', 13);
		$xfull = $doc->getPageWidth() / 2;
		$doc->writeHTMLCell(200, 0, $xfull - 100, $margen_superior, $arrayDatos['titulo'], '', 1, 0, true, 'C', true);

		$xfull = $doc->getPageWidth() - 25 - $margen_derecho;
		$doc->write2DBarcode($rutaQRG, 'QRCODE,Q', $xfull, $margen_superior, 25, 25, $style, 'N');
		$doc->SetFont($tipoLetra, '', 11);
		$y = $doc->GetY();
		$doc->writeHTMLCell(0, 0, $margen_izquierdo, $y - 9, $arrayDatos['subtitulo'], '', 1, 0, true, 'C', true);
		// ***********************************seccion A**************************************************************
		$doc->SetFont($tipoLetra, 'B', 8);
		// ***********************************************************************************
		$alto = 10;
		$y = $doc->GetY() + 5;
		$tamañoColumn = $doc->getPageWidth() - $margen_izquierdo - $margen_derecho;
		$doc->crearTabla($tipoLetra, $margen_izquierdo, $tamañoColumn, $y, $arrayDatos['seccionA'], $alto, array(
			247,
			255,
			147), 6);
		// $doc->crearTabla($tipoLetra, $margen_izquierdo, $tamañoColumn, $y, $arrayDatos['seccionA'], $alto, array(247, 255, 147), 6);
		$ytxt = 6;
		$doc->SetFont($tipoLetra, 'B', 8);
		$doc->Text($margen_izquierdo, $y + $ytxt, '1. Nombre del centro de faenamiento:');
		$doc->SetFont($tipoLetra, '', 8);
		$doc->writeHTMLCell(80, 0, $margen_izquierdo + 52, $y + $ytxt, $consulta->current()->razon_social, '', 0, 0, false, 'L', false);
		$xtxt = $doc->getPageWidth() / 2;
		$doc->SetFont($tipoLetra, 'B', 8);
		$doc->Text($xtxt, $y + $ytxt, '2. Médico Veterinario Oficial o Autorizado:');
		$doc->SetFont($tipoLetra, '', 8);
		$doc->writeHTMLCell(85, 0, $xtxt + 58, $y + $ytxt, $datos->current()->nombre_medico, '', 0, 0, false, 'L', false);
		$ytxt = 11;
		$doc->SetFont($tipoLetra, 'B', 8);
		$doc->Text($margen_izquierdo, $y + $ytxt, '3. Provincia:');
		$xtxt = 18;
		$doc->SetFont($tipoLetra, '', 8);
		$doc->writeHTMLCell(70, 0, $margen_izquierdo + $xtxt, $y + $ytxt, $consulta->current()->provincia, '', 0, 0, false, 'L', false);
		$xtxt = $doc->getPageWidth() / 3;
		$doc->SetFont($tipoLetra, 'B', 8);
		$doc->Text($margen_izquierdo + $xtxt, $y + $ytxt, '4. Cantón:');
		$doc->SetFont($tipoLetra, '', 8);
		$doc->writeHTMLCell(70, 0, $margen_izquierdo + $xtxt + 15, $y + $ytxt, $consulta->current()->canton, '', 0, 0, false, 'L', false);
		$xtxt = ($doc->getPageWidth() / 3) * 2;
		$doc->SetFont($tipoLetra, 'B', 8);
		$doc->Text($margen_izquierdo + $xtxt, $y + $ytxt, '5. Parroquia:');
		$doc->SetFont($tipoLetra, '', 8);
		$doc->writeHTMLCell(70, 0, $margen_izquierdo + $xtxt + 18, $y + $ytxt, $consulta->current()->parroquia, '', 0, 0, false, 'L', false);

		// *************************************seccion B************************************************************
		$doc->SetFont($tipoLetra, 'B', 8);
		$y = $doc->GetY();
		$alto = 10;
		$tamañoColumn = $doc->getPageWidth() - $margen_izquierdo - $margen_derecho;
		$doc->crearTablaHeader($tipoLetra, $margen_izquierdo, $tamañoColumn, $y - 42.7, $arrayDatos['seccionB'], $alto, array(
			192,
			234,
			255), 6);

		$doc->SetFont($tipoLetra, '', 6);
		$p1 = 65;
		$p2 = 29;
		$p3 = 27;
		$p4 = 40;
		$p5 = 28;
		$p6 = 63;
		$p7 = $tamañoColumn - $p1 - $p2 - $p3 - $p4 - $p5 - $p6;
		$y = $doc->GetY();
		$genera = $margen_izquierdo;
		$aves = $margen_izquierdo + $p1;
		$caract = $margen_izquierdo + $p1 + $p2;
		$probl = $margen_izquierdo + $p1 + $p2 + $p3;
		$exter = $margen_izquierdo + $p1 + $p2 + $p3 + $p4;
		$dictamen = $margen_izquierdo + $p1 + $p2 + $p3 + $p4 + $p5;
		$observacion = $margen_izquierdo + $p1 + $p2 + $p3 + $p4 + $p5 + $p6;

		$doc->crearEncabezadoTabla($tipoLetra, $genera, $p1, $y, 'GENERALIDADES', array(
			255,
			255,
			255), 6, 0.1, 0.1);
		$doc->crearEncabezadoTabla($tipoLetra, $aves, $p2, $y, 'AVES MUERTAS', array(
			255,
			255,
			255), 6, 0.1, - 0.5);
		$doc->crearEncabezadoTabla($tipoLetra, $caract, $p3, $y, 'CARACTERISTICAS', array(
			255,
			255,
			255), 6, 0.1, - 0.6);
		$doc->crearEncabezadoTabla($tipoLetra, $probl, $p4, $y, 'PROBLEMAS SISTÉMICOS', array(
			255,
			255,
			255), 6, 0.1, - 0.6);
		$doc->crearEncabezadoTabla($tipoLetra, $exter, $p5, $y, 'CARACTERISTICAS<br>EXTERNAS', array(
			255,
			255,
			255), 6, 0.1, - 2);
		$doc->crearEncabezadoTabla($tipoLetra, $dictamen, $p6, $y, 'DICTAMEN', array(
			255,
			255,
			255), 6, 0.1, - 0.6);
		$doc->crearEncabezadoTabla($tipoLetra, $observacion, $p7, $y, 'OBSERVACIONES', array(
			255,
			255,
			255), 36.1, 0.1, 12);

		$y1 = $y + 3;
		$x = $genera;
		$ancho = $p1 / 6;
		$doc->textoVertical('Fecha', $x, $y1, $tipoLetra, 6, $ancho + 1, 5, 0);
		$doc->textoVertical('Tipo de ave', $x + $ancho + 1, $y1, $tipoLetra, 6, $ancho + 2, 5);
		$doc->textoVertical('Lugar de prodecencia<br>(GRANJA)', $x + $ancho * 2 + 2, $y1, $tipoLetra, 6, $ancho + 7, 5);
		$doc->textoVertical('Nro. de CSMI', $x + $ancho * 3 + 7, $y1, $tipoLetra, 6, $ancho + 6, 4.2);
		$doc->textoVertical('Nro. de aves<br> (TOTAL)', $x + $ancho * 4 + 7, $y1, $tipoLetra, 6, $ancho + 1, 1.2);
		$doc->textoVertical('Peso promedio de las<br>aves(Kg)', $x + $ancho * 5 + 4, $y1, $tipoLetra, 6, $ancho, 0.4);

		$doc->SetFont($tipoLetra, '', 7);
		$x = $aves;
		$ancho = $p2 / 3;
		$doc->textoVertical('Nro. de aves muertas<br>(AL ARRIBO)', $x, $y1, $tipoLetra, 6, $ancho - 2, 0.7);
		$doc->textoVertical('% de aves muertas', $x + $ancho - 2, $y1, $tipoLetra, 6, $ancho - 4, 1.2);
		$doc->textoVertical('Causa probable', $x + $ancho * 2 - 6, $y1, $tipoLetra, 6, $ancho + 6, 6.4);

		$x = $caract;
		$ancho = $p3 / 4;
		$doc->textoVertical('Nro. de aves decaidas<br>o moribundas', $x, $y1, $tipoLetra, 6, $ancho + 1, 0.8);
		$doc->textoVertical('% de aves decaidas<br>o moribundas', $x + $ancho + 1, $y1, $tipoLetra, 6, $ancho, - 0.2);
		$doc->textoVertical('Nro. de Traumas', $x + $ancho * 2, $y1, $tipoLetra, 6, $ancho + 1, 2.6);
		$doc->textoVertical('%. de Traumas', $x + $ancho * 3 + 1, $y1, $tipoLetra, 6, $ancho - 1, 1.4);

		$x = $probl;
		$ancho = $p4 / 6;
		$doc->textoVertical('Nro. de aves con problemas<br>respiratorios', $x, $y1, $tipoLetra, 6, $ancho + 1, 0);
		$doc->textoVertical('% de aves con problemas<br>respiratorios', $x + $ancho + 1, $y1, $tipoLetra, 6, $ancho - 1, - 0.3);
		$doc->textoVertical('Nro. de aves con problemas<br>nerviosos', $x + $ancho * 2, $y1, $tipoLetra, 6, $ancho + 1, 0.5);
		$doc->textoVertical('% de aves con problemas<br>nerviosos', $x + $ancho * 3 + 1, $y1, $tipoLetra, 6, $ancho - 1, - 0.3);
		$doc->textoVertical('Nro. de aves con problemas<br>digestivos', $x + $ancho * 4, $y1, $tipoLetra, 6, $ancho + 1, 0.6);
		$doc->textoVertical('% de aves con problemas<br>digestivos', $x + $ancho * 5 + 1, $y1, $tipoLetra, 6, $ancho - 1, - 0.3);

		$x = $exter;
		$ancho = $p5 / 4;
		$doc->textoVertical('Nro. de aves con cabeza<br>hinchada', $x, $y1, $tipoLetra, 6, $ancho + 1, 1);
		$doc->textoVertical('% de aves con cabeza<br>hinchada', $x + $ancho + 1, $y1, $tipoLetra, 6, $ancho - 1, 0);
		$doc->textoVertical('Nro. de aves con plumas<br>erizadas', $x + $ancho * 2, $y1, $tipoLetra, 6, $ancho + 1, 0.5);
		$doc->textoVertical('% de aves con plumas<br>erizadas', $x + $ancho * 3 + 1, $y1, $tipoLetra, 6, $ancho - 1, 0);

		$x = $dictamen;
		$ancho = $p6 / 9;
		$doc->textoVertical('Faenamiento normal<br>(Nro. de aves)', $x, $y1, $tipoLetra, 6, $ancho + 1, 1);
		$doc->textoVertical('Faenamiento normal<br>(% de aves)', $x + $ancho + 1, $y1, $tipoLetra, 6, $ancho - 1, - 0.3);
		$doc->textoVertical('Faenamiento bajo precuciones<br>especiales(Nro.)', $x + $ancho * 2, $y1, $tipoLetra, 6, $ancho + 1, 1);
		$doc->textoVertical('Faenamiento bajo precuciones<br>especiales(%)', $x + $ancho * 3 + 1, $y1, $tipoLetra, 6, $ancho - 1, - 0.3);
		$doc->textoVertical('Faenamiento de emergencia<br>(Nro.)', $x + $ancho * 4, $y1, $tipoLetra, 6, $ancho + 1, 1);
		$doc->textoVertical('Faenamiento de emergencia<br>(%)', $x + $ancho * 5 + 1, $y1, $tipoLetra, 6, $ancho - 1, - 0.3);
		$doc->textoVertical('Aplazamiento del faenamiento<br>(Nro.)', $x + $ancho * 6, $y1, $tipoLetra, 6, $ancho + 1, 1);
		$doc->textoVertical('Aplazamiento del faenamiento<br>(%)', $x + $ancho * 7 + 1, $y1, $tipoLetra, 6, $ancho - 1, - 0.3);
		$doc->textoVertical('TOTAL (Nro. de aves)', $x + $ancho * 8, $y1, $tipoLetra, 6, $ancho, 2);

		$lnDetalleAnteAves = new DetalleAnteAvesLogicaNegocio();
		$lnHallazgosAvesCaracte = new HallazgosAvesCaractLogicaNegocio();
		$lnHallazgosAvesExternas = new HallazgosAvesExternasLogicaNegocio();
		$lnHallazgosAvesMuertas = new HallazgosAvesMuertasLogicaNegocio();
		$lnHallazgosAvesSistemati = new HallazgosAvesSistematicosLogicaNegocio();

		$consulta = "id_formulario_ante_mortem = " . $arrayDatos['id_formulario_ante_mortem'] . " order by 1";
		$consultaDetalle = $lnDetalleAnteAves->buscarLista($consulta);

		$arrayGenera = array();
		$arrayAves = array();
		$arrayCaract = array();
		$arrayProble = array();
		$arrayExtern = array();
		foreach ($consultaDetalle as $items){

			if ($items['id_hallazgos_aves_muertas'] != ''){
				$consulta = "id_hallazgos_aves_muertas = " . $items['id_hallazgos_aves_muertas'] . " order by 1";
				$consultaAvesMuertas = $lnHallazgosAvesMuertas->buscarLista($consulta);
				foreach ($consultaAvesMuertas as $itemsAvesMuertas){
					$arrayAves = array(
						'aves_muertas' => $itemsAvesMuertas['num_animales_muertos'],
						'porcent_aves_muertas' => $itemsAvesMuertas['porcent_aves_muertas'],
						'causa_probable' => $itemsAvesMuertas['causa_probable']);
				}
			}else{
				$arrayAves = array(
					'');
			}
			if ($items['id_hallazgos_aves_caract'] != null || $items['id_hallazgos_aves_caract'] != ''){
				$consulta = "id_hallazgos_aves_caract = " . $items['id_hallazgos_aves_caract'] . " order by 1";
				$consultaCaracte = $lnHallazgosAvesCaracte->buscarLista($consulta);
				foreach ($consultaCaracte as $itemsCaracte){
					$arrayCaract = array(
						'decaidas' => $itemsCaracte['decaidas'],
						'porcent_decaidas' => $itemsCaracte['porcent_decaidas'],
						'num_traumas' => $itemsCaracte['num_traumas'],
						'porcent_traumas' => $itemsCaracte['porcent_traumas']);
				}
			}else{
				$arrayCaract = array(
					'');
			}
			if ($items['id_hallazgos_aves_sistematicos'] != null || $items['id_hallazgos_aves_sistematicos'] != ''){
				$consulta = "id_hallazgos_aves_sistematicos = " . $items['id_hallazgos_aves_sistematicos'] . " order by 1";
				$consultaSistemat = $lnHallazgosAvesSistemati->buscarLista($consulta);
				foreach ($consultaSistemat as $itemsSistem){
					$arrayProble = array(
						'probl_respirat' => $itemsSistem['probl_respirat'],
						'porcent_probl_respirat' => $itemsSistem['porcent_probl_respirat'],
						'probl_nerviosos' => $itemsSistem['probl_nerviosos'],
						'porcent_proble_nerviosos' => $itemsSistem['porcent_proble_nerviosos'],
						'probl_digestivos' => $itemsSistem['probl_digestivos'],
						'porcent_probl_digestivos' => $itemsSistem['porcent_probl_digestivos']);
				}
			}else{
				$arrayProble = array(
					'');
			}

			if ($items['id_hallazgos_aves_externas'] != null || $items['id_hallazgos_aves_externas'] != ''){
				$consulta = "id_hallazgos_aves_externas = " . $items['id_hallazgos_aves_externas'] . " order by 1";
				$consultaExter = $lnHallazgosAvesExternas->buscarLista($consulta);
				foreach ($consultaExter as $itemsExter){
					$arrayExtern = array(
						'cabeza_hinchada' => $itemsExter['cabeza_hinchada'],
						'porcent_cabeza_hinchada' => $itemsExter['porcent_cabeza_hinchada'],
						'plumas_erizadas' => $itemsExter['plumas_erizadas'],
						'porcent_plumas_erizadas' => $itemsExter['porcent_plumas_erizadas']);
				}
			}else{
				$arrayExtern = array(
					'');
			}
			$arrayGenera[] = array(
				'fecha_formulario' => $this->formatearFecha($items['fecha_formulario']),
				'tipo_ave' => $items['tipo_ave'],
				'lugar_procedencia' => $items['lugar_procedencia'],
				'num_csmi' => $items['num_csmi'],
				'total_aves' => $items['total_aves'],
				'promedio_aves' => $items['promedio_aves'],
				'faenamiento_normal' => $items['faenamiento_normal'],
				'procent_faenamiento_normal' => $items['procent_faenamiento_normal'],
				'faenamiento_especial' => $items['faenamiento_especial'],
				'porcent_faenamiento_especial' => $items['porcent_faenamiento_especial'],
				'faenamiento_emergencia' => $items['faenamiento_emergencia'],
				'porcent_emergencia' => $items['porcent_emergencia'],
				'aplazamiento_faenamiento' => $items['aplazamiento_faenamiento'],
				'porcent_aplazamiento_faenamiento' => $items['porcent_aplazamiento_faenamiento'],
				'total_faenamiento' => $items['total_faenamiento'],
				'observacion' => $items['observacion'],
				'arrayAves' => $arrayAves,
				'arrayCaract' => $arrayCaract,
				'arrayProble' => $arrayProble,
				'arrayExtern' => $arrayExtern);
		}

		$y2 = $y1 + 33; // 23 48
		$i = 0;
		foreach ($arrayGenera as $items){
			$doc->crearFilasAves($items, $y2, $genera, $aves, $caract, $probl, $exter, $dictamen, $observacion, $tipoLetra, $p1, $p2, $p3, $p4, $p5, $p6, $p7);
			$y2 = $y2 + 4;
			if ($doc->verificarFilas($i)){
				$y2 = 12;
				$doc->AddPage();
			}
			$i ++;
		}
		if ($doc->verificarFilas($i, true)){
			$y2 = 12;
			$doc->AddPage();
		}

		// *****************************************************Seccion C******************************
		$doc->firma($margen_izquierdo, $y2 + 10, $tamañoColumn, 6, $arrayDatos['seccionC'], $arrayDatos['seccionFirma'], $tipoLetra, array(
			210,
			210,
			210));
		// ******************************* FIN DE LA EDICION ****************************************************************************************
		$doc->Output(INSP_AP_TCPDF . "reportes/formulariosAM/" . $arrayDatos['nombreArchivo'] . ".pdf", 'F');
		ob_end_clean();
	}
	
	
	/**
	 * Notificar envío de emails
	 * 
	 */
	public function notificarEmail($arrayEmail)
	{
		$asunto = 'Información ha sido aprobada automáticamente por falta de revisión';
		$familiaLetra = "font-family:Text Me One,Segoe UI, Tahoma, Helvetica, freesans, sans-serif";
		
		$cuerpoMensaje = '<table><tbody>
			<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Estimad@,</tr>
            <tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Se le comunica que la información del siguiente formulario ha sido aprobada de manera automática por el sistema GUIA: </td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Centro de Faenamiento:'. $arrayEmail['centroFaenamiento'].'<br><br>Código formulario: '. $arrayEmail['codigoFormulario'].' <br><br>Fecha creación formulario: '. $this->formatearFecha($arrayEmail['fechaFormulario']).'<br><br>Tipo de Ave/Especie: '. $arrayEmail['especie'].'</td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Ingrese al siguiente link para revisar dicho registro:<br>  </td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;"><a>https://guia.agrocalidad.gob.ec</a><br>  </td></tr>
            <tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">NOTA: Este correo fue generado automáticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
			</tbody></table>';
		
		$arrayMailsDestino = array();
		$datos = $this->buscarDatosOperador($arrayEmail['identificador']);
		$datos = $datos->current();
		if (!empty($datos->correo))
		{
			$arrayMailsDestino[] = $datos->correo;
		}
		$mailsDestino = array_unique($arrayMailsDestino);
		if (count($mailsDestino) > 0)
		{
			$datosCorreo = array(
				'asunto' => $asunto,
				'cuerpo' => $cuerpoMensaje,
				'codigo_modulo' => "PRG_A_P_MORTE_CF",
				'tabla_modulo' => "g_centros_faenamiento.formulario_ante_mortem",
				'id_solicitud_tabla' => $arrayEmail['id_formulario_ante_mortem'],
				'estado' => 'Por enviar'
			);
			$modeloCorreos = new \Agrodb\Correos\Modelos\CorreosModelo();
			$idCorreo = $modeloCorreos->guardar($datosCorreo);
			
			//Guardar correo del destino
			$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
			foreach ($mailsDestino as $val)
			{
				$datosDestino = array('id_correo' => $idCorreo, 'destinatario_correo' => $val);
				$destino->guardar($datosDestino);
			}
		} else
		{
			throw new \Exception(Constantes::EMAIL_INF_VACIO);
		}
	}
	
}





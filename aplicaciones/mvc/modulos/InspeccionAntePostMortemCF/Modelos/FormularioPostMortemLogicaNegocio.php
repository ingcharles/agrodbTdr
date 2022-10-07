<?php
/**
 * Lógica del negocio de FormularioPostMortemModelo
 *
 * Este archivo se complementa con el archivo FormularioPostMortemControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-05-27
 * @uses FormularioPostMortemLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
namespace Agrodb\InspeccionAntePostMortemCF\Modelos;

use Agrodb\Core\Excepciones\GuardarExcepcion;
use Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FormularioPostMortemLogicaNegocio implements IModelo{

	private $modeloFormularioPostMortem = null;

	private $excelPhp = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloFormularioPostMortem = new FormularioPostMortemModelo();
		$this->excelPhp = new ReportesExcelModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new FormularioPostMortemModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdFormularioPostMortem() != null && $tablaModelo->getIdFormularioPostMortem() > 0){
			return $this->modeloFormularioPostMortem->actualizar($datosBd, $tablaModelo->getIdFormularioPostMortem());
		}else{
			unset($datosBd["id_formulario_post_mortem"]);
			return $this->modeloFormularioPostMortem->guardar($datosBd);
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
		$this->modeloFormularioPostMortem->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return FormularioPostMortemModelo
	 */
	public function buscar($id){
		return $this->modeloFormularioPostMortem->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloFormularioPostMortem->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloFormularioPostMortem->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarFormularioPostMortem(){
		$consulta = "SELECT * FROM " . $this->modeloFormularioPostMortem->getEsquema() . ". formulario_post_mortem";
		return $this->modeloFormularioPostMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Guardar detalle de post mortem .
	 *
	 * @return array|ResultSet
	 */
	public function guardarDetallePostAves(Array $datos){
		try{
			$estado = '';
			$this->modeloFormularioPostMortem = new FormularioPostMortemModelo();
			$proceso = $this->modeloFormularioPostMortem->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción en: Guardar formulario de post mortem de aves');
			}
			$idFormularioPostMortem = $datos['id_formulario_post_mortem'];
			// registrar el detalle del formulario post mortem aves
			$datosDetallePostAves = array(
				'id_formulario_post_mortem' => $idFormularioPostMortem,
				'id_detalle_ante_aves' => $datos['id_detalle_ante_aves'],
				'fecha_formulario' => $datos['fecha_formulario'],
				'num_descarte' => $datos['num_descarte'],
				'porcent_num_descarte' => $datos['porcent_num_descarte'],
				'num_colibacilosis' => $datos['num_colibacilosis'],
				'porcent_num_colibacilosis' => $datos['porcent_num_colibacilosis'],
				'num_pododermatitis' => $datos['num_pododermatitis'],
				'porcent_num_pododermatitis' => $datos['porcent_num_pododermatitis'],
				'num_lesiones_piel' => $datos['num_lesiones_piel'],
				'porcent_num_lesiones_piel' => $datos['porcent_num_lesiones_piel'],
				'num_mal_sangrado' => $datos['num_mal_sangrado'],
				'porcent_num_mal_sangrado' => $datos['porcent_num_mal_sangrado'],
				'num_contusion_pierna' => $datos['num_contusion_pierna'],
				'porcent_num_contusion_pierna' => $datos['porcent_num_contusion_pierna'],
				'num_contusion_ala' => $datos['num_contusion_ala'],
				'porcent_num_contusion_ala' => $datos['porcent_num_contusion_ala'],
				'num_contusion_pechuga' => $datos['num_contusion_pechuga'],
				'porcent_num_contusion_pechuga' => $datos['porcent_num_contusion_pechuga'],
				'num_alas_rotas' => $datos['num_alas_rotas'],
				'porcent_num_alas_rotas' => $datos['porcent_num_alas_rotas'],
				'num_piernas_rotas' => $datos['num_piernas_rotas'],
				'porcent_num_piernas_rotas' => $datos['porcent_num_piernas_rotas'],
				'total_canales_aprobados' => $datos['total_canales_aprobados'],
				'peso_total_canales_aprobados_totalmente' => $datos['peso_total_canales_aprobados_totalmente'],
				'total_canales_aprobados_parcialmente' => $datos['total_canales_aprobados_parcialmente'],
				'peso_total_canales_aprobados_parcialmente' => $datos['peso_total_canales_aprobados_parcialmente'],
				'canales_decomiso_parcial' => $datos['canales_decomiso_parcial'],
				'canales_decomiso_total' => $datos['canales_decomiso_total'],
				'peso_promedio_canales' => $datos['peso_promedio_canales'],
				'total_carne_decomisada' => $datos['total_carne_decomisada'],
				'destino_decomisos' => $datos['destino_decomisos'],
				'lugar_disposicion_final' => $datos['lugar_disposicion_final'],
				'observacion' => $datos['observacion']);
			$statement = $this->modeloFormularioPostMortem->getAdapter()
				->getDriver()
				->createStatement();
			if ($idFormularioPostMortem == ''){
				$estado = 'guardado';
				$arraySecuencial = array(
					'identificador_operador' => $_SESSION['usuario']);
				$secuencial = $this->obtenerSecuencialFormularioPostMortem($arraySecuencial);
				$secuencialFormulario = str_pad($secuencial->current()->numero, 6, "0", STR_PAD_LEFT);

				$arrayParametros = array(
					'id_centro_faenamiento' => $datos['idCentroFaenamiento']);
				$lNegocioFormularioAnteMortem = new FormularioAnteMortemLogicaNegocio();
				$consulta = $lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
				$codigoProvincia = $consulta->current()->codigo_provincia;
				$codigoFormulario = $codigoProvincia . '-PM-' . $secuencialFormulario . '-' . date('dmY');

				$arrayParametros = array(
					'identificador' => $_SESSION['usuario'],
					'estado' => 'Registrado',
					'codigo_formulario' => $codigoFormulario);

				$tablaModelo = new FormularioPostMortemModelo($arrayParametros);
				$datosBd = $tablaModelo->getPrepararDatos();
				unset($datosBd["id_formulario_post_mortem"]);
				$idFormularioPostMortem = $this->modeloFormularioPostMortem->guardar($datosBd);

				// registrar el detalle del formulario post mortem aves
				$lNegocioDetallePostAves = new DetallePostAvesLogicaNegocio();
				$datosDetallePostAves['id_formulario_post_mortem'] = $idFormularioPostMortem;
				$sqlInsertar = $this->modeloFormularioPostMortem->guardarSql('detalle_post_aves', $this->modeloFormularioPostMortem->getEsquema());
				$sqlInsertar->columns($lNegocioDetallePostAves->columnas());
				$sqlInsertar->values($datosDetallePostAves, $sqlInsertar::VALUES_MERGE);
				$sqlInsertar->prepareStatement($this->modeloFormularioPostMortem->getAdapter(), $statement);
				$statement->execute();
				$idDetalle = $this->modeloFormularioPostMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioPostMortem->getEsquema() . '.detalle_post_aves_id_detalle_post_aves_seq');
				if (! $idDetalle){
					throw new \Exception('Error al guardar los datos en el detalle del formulario post aves');
				}
			}else{
				$estado = 'actualizado';
				$idDetalle = $datos['id_detalle_post_aves'];
				$sqlActualizar = $this->modeloFormularioPostMortem->actualizarSql('detalle_post_aves', $this->modeloFormularioPostMortem->getEsquema());
				$sqlActualizar->set($datosDetallePostAves);
				$sqlActualizar->where(array(
					'id_detalle_post_aves' => $datos['id_detalle_post_aves']));
				$sqlActualizar->prepareStatement($this->modeloFormularioPostMortem->getAdapter(), $statement);
				$statement->execute();
			}
			$proceso->commit();
			return $idFormularioPostMortem . '-' . $estado . '-' . $idDetalle;
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
	 * Guardar detalle de post mortem .
	 *
	 * @return array|ResultSet
	 */
	public function guardarDetallePostAnimales(Array $datos){
		try{
			$estado = '';
			$this->modeloFormularioPostMortem = new FormularioPostMortemModelo();
			$proceso = $this->modeloFormularioPostMortem->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción en: Guardar formulario de post mortem de aves');
			}
			$idFormularioPostMortem = $datos['id_formulario_post_mortem'];
			// registrar el detalle del formulario post mortem aves
			$datosDetallePostAnimales = array(
				'id_formulario_post_mortem' => $idFormularioPostMortem,
				'id_detalle_ante_animales' => $datos['id_detalle_ante_animales'],
				'fecha_formulario' => $datos['fecha_formulario'],
				'estado_nodulos_linfaticos' => $datos['estado_nodulos_linfaticos'],
				'otro_diagnostico' => $datos['otro_diagnostico'],
				'num_canales_decomiso_parcial' => $datos['num_canales_decomiso_parcial'],
				'peso_total_carne_aprobada' => $datos['peso_total_carne_aprobada'],
				'peso_total_carne_decomisada' => $datos['peso_total_carne_decomisada'],
				'num_canales_decomiso' => $datos['num_canales_decomiso'],
				'peso_total_carne_decomisada_productivo' => $datos['peso_total_carne_decomisada_productivo'],
				'num_canales_aprobadas_totalmente' => $datos['num_canales_aprobadas_totalmente'],
				'num_canales_aprobadas_parcialmente' => $datos['num_canales_aprobadas_parcialmente'],
				'peso_total_carne_aprobada_productivos' => $datos['peso_total_carne_aprobada_productivos'],
				'peso_promedio_canal' => $datos['peso_promedio_canal'],
				'peso_total_visceras_decomisadas' => $datos['peso_total_visceras_decomisadas'],
				'peso_carne_incineracion' => $datos['peso_carne_incineracion'],
				'peso_visceras_incineracion' => $datos['peso_visceras_incineracion'],
				'peso_carne_rendering' => $datos['peso_carne_rendering'],
				'peso_visceras_rendering' => $datos['peso_visceras_rendering'],
				'peso_carne_abono' => $datos['peso_carne_abono'],
				'peso_visceras_abono' => $datos['peso_visceras_abono'],
				'lugar_incineracion' => $datos['lugar_incineracion'],
				'lugar_renderizacion' => $datos['lugar_renderizacion'],
				'lugar_desconposicion' => $datos['lugar_desconposicion'],
				'nombre_gestor_ambiental' => $datos['nombre_gestor_ambiental'],
				'descripcion_actividad_general' => $datos['descripcion_actividad_general'],
				'observacion' => $datos['observacion'],
				'peso_carne_ambiental' => $datos['peso_carne_ambiental'],
				'peso_visceras_ambiental' => $datos['peso_visceras_ambiental'],
				'examen_visual' => $datos['examenVisual'],
				'palpacion' => $datos['palpacion'],
				'insicion' => $datos['insicion'],
				'toma_muestra' => $datos['tomaMuestra'],
				'organo_tejido' => $datos['organoTejido'],
				'descripcion_actividad' => ($datos['descripcion_actividad'] != '') ? $datos['descripcion_actividad'] : NULL);
			$statement = $this->modeloFormularioPostMortem->getAdapter()
				->getDriver()
				->createStatement();
			if ($idFormularioPostMortem == ''){
				$estado = 'guardado';
				$arraySecuencial = array(
					'identificador_operador' => $_SESSION['usuario']);
				$secuencial = $this->obtenerSecuencialFormularioPostMortem($arraySecuencial);
				$secuencialFormulario = str_pad($secuencial->current()->numero, 6, "0", STR_PAD_LEFT);

				$arrayParametros = array(
					'id_centro_faenamiento' => $datos['idCentroFaenamiento']);
				$lNegocioFormularioAnteMortem = new FormularioAnteMortemLogicaNegocio();
				$consulta = $lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
				$codigoProvincia = $consulta->current()->codigo_provincia;
				$codigoFormulario = $codigoProvincia . '-PM-' . $secuencialFormulario . '-' . date('dmY');

				$arrayParametros = array(
					'identificador' => $_SESSION['usuario'],
					'estado' => 'Registrado',
					'codigo_formulario' => $codigoFormulario);

				$tablaModelo = new FormularioPostMortemModelo($arrayParametros);
				$datosBd = $tablaModelo->getPrepararDatos();
				unset($datosBd["id_formulario_post_mortem"]);
				$idFormularioPostMortem = $this->modeloFormularioPostMortem->guardar($datosBd);

				// registrar el detalle del formulario post mortem aves
				$lNegocioDetallePostAnimales = new DetallePostAnimalesLogicaNegocio();
				$datosDetallePostAnimales['id_formulario_post_mortem'] = $idFormularioPostMortem;
				$sqlInsertar = $this->modeloFormularioPostMortem->guardarSql('detalle_post_animales', $this->modeloFormularioPostMortem->getEsquema());
				$sqlInsertar->columns($lNegocioDetallePostAnimales->columnas());
				$sqlInsertar->values($datosDetallePostAnimales, $sqlInsertar::VALUES_MERGE);
				$sqlInsertar->prepareStatement($this->modeloFormularioPostMortem->getAdapter(), $statement);
				$statement->execute();
				$idDetalle = $this->modeloFormularioPostMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioPostMortem->getEsquema() . '.detalle_post_animales_id_detalle_post_animales_seq');
				if (! $idDetalle){
					throw new \Exception('Error al guardar los datos en el detalle del formulario post aves');
				}
				// ****guardar hallazgos diagnostico examenes
				$this->guardarHallazgosDiagnosticosExamen($datos, $idDetalle);
				// ****guardar resultados decomiso
				$this->guardarResultadoDecomiso($datos, $idDetalle);
			}else{
				$estado = 'actualizado';
				$idDetalle = $datos['id_detalle_post_animales'];
				$sqlActualizar = $this->modeloFormularioPostMortem->actualizarSql('detalle_post_animales', $this->modeloFormularioPostMortem->getEsquema());
				$sqlActualizar->set($datosDetallePostAnimales);
				$sqlActualizar->where(array(
					'id_detalle_post_animales' => $datos['id_detalle_post_animales']));
				$sqlActualizar->prepareStatement($this->modeloFormularioPostMortem->getAdapter(), $statement);
				$statement->execute();
				// ****guardar hallazgos diagnostico examenes
				$this->guardarHallazgosDiagnosticosExamen($datos, $idDetalle);
				// ****guardar resultados decomiso
				$this->guardarResultadoDecomiso($datos, $idDetalle);
			}
			$proceso->commit();
			return $idFormularioPostMortem . '-' . $estado . '-' . $idDetalle;
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
	 * Ejecuta una consulta(SQL) personalizada para guardar hallazgos diagnosticos examen
	 *
	 * @return array
	 */
	public function guardarHallazgosDiagnosticosExamen($datos, $idDetalle){
		if (array_key_exists('hallazgos', $datos)){
			$hallazgos = array();
			$endoparasitos = array();
			$ectoparasitos = array();
			foreach ($datos['hallazgos'] as $items){
				switch ($items['tipo']) {
					case 'hallazgos':
						if (array_key_exists('id_examen_post_hallazgos', $items)){
							$idExamenPostHallazgos = $items['id_examen_post_hallazgos'];
						}else{
							$idExamenPostHallazgos = null;
						}
						$hallazgos[] = array(
							'id_detalle_post_animales' => $idDetalle,
							'enfermedad' => $items['enfermedad'],
							'localizacion' => $items['localizacion'],
							'num_animales_afectados' => $items['numAnimalAfec'],
							'id_examen_post_hallazgos' => $idExamenPostHallazgos);
					break;
					case 'endoparasitos':
						if (array_key_exists('id_examen_post_endoparasitos', $items)){
							$idExamenPostEndoparasitos = $items['id_examen_post_endoparasitos'];
						}else{
							$idExamenPostEndoparasitos = null;
						}
						$endoparasitos[] = array(
							'id_detalle_post_animales' => $idDetalle,
							'endoparasitos_presencia' => $items['presencia'],
							'endoparasitos_localizacion' => $items['localizacion'],
							'endoparasitos_num_afectados' => $items['numAnimalAfec'],
							'id_examen_post_endoparasitos' => $idExamenPostEndoparasitos);
					break;
					case 'ectoparasitos':
						if (array_key_exists('id_examen_post_ectoparasitos', $items)){
							$idExamenPostEctoparasitos = $items['id_examen_post_ectoparasitos'];
						}else{
							$idExamenPostEctoparasitos = null;
						}
						$ectoparasitos[] = array(
							'id_detalle_post_animales' => $idDetalle,
							'ectoparasitos_presencia' => $items['presencia'],
							'ectoparasitos_localizacion' => $items['localizacion'],
							'ectoparasitos_num_afectados' => $items['numAnimalAfec'],
							'id_examen_post_ectoparasitos' => $idExamenPostEctoparasitos);
					break;
				}
			}

			// ******************guardar hallazgos si existen************************************
			$this->guardarHallazgos($hallazgos);
			// ******************guardar endoparasitos si existen************************************
			$this->guardarEndoparasitos($endoparasitos);
			// ******************guardar ectoparasitos si existen***********************************
			$this->guardarEctoparasitos($ectoparasitos);
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar resultados y decomisos
	 *
	 * @return array
	 */
	public function guardarResultadoDecomiso($datos, $idDetalle){
		if (array_key_exists('organos', $datos)){
			$organos = array();
			foreach ($datos['organos'] as $items){

				if (array_key_exists('id_resultado_organos', $items)){
					$idResultadoOrganos = $items['id_resultado_organos'];
				}else{
					$idResultadoOrganos = null;
				}
				$organos[] = array(
					'id_detalle_post_animales' => $idDetalle,
					'organo_decomisado' => $items['organo'],
					'razon_decomiso' => $items['razonDecomiso'],
					'num_organos_decomisados' => $items['numOrganoDecomiso'],
					'id_resultado_organos' => $idResultadoOrganos);
			}
			// ******************guardar organos si existen************************************
			$this->guardarOrganos($organos);
		}

		// ******Verificar decomiso parcial
		if (array_key_exists('decomisoParcial', $datos)){
			$decomisoParcial = array();
			foreach ($datos['decomisoParcial'] as $items){

				if (array_key_exists('id_resultado_decomiso_parcial', $items)){
					$idResultadoDecomisoParcial = $items['id_resultado_decomiso_parcial'];
				}else{
					$idResultadoDecomisoParcial = null;
				}
				$decomisoParcial[] = array(
					'id_detalle_post_animales' => $idDetalle,
					'razon_decomiso' => $items['razonDecomiso'],
					'peso_carne_aprobada' => $items['pesoCarneAprobada'],
					'peso_carne_decomisada' => $items['pesoCarneDecomisada'],
					'num_canales_decomisadas' => $items['numCanalesDecomisadas'],
					'id_resultado_decomiso_parcial' => $idResultadoDecomisoParcial);
			}
			// ******************guardar organos si existen************************************
			$this->guardarDecomisoParcial($decomisoParcial);
		}

		// ********Verificar decomiso total
		if (array_key_exists('decomisoTotal', $datos)){
			$decomisoTotal = array();
			foreach ($datos['decomisoTotal'] as $items){

				if (array_key_exists('id_resultado_decomiso_total', $items)){
					$idResultadoDecomisoTotal = $items['id_resultado_decomiso_total'];
				}else{
					$idResultadoDecomisoTotal = null;
				}
				$decomisoTotal[] = array(
					'id_detalle_post_animales' => $idDetalle,
					'razon_decomiso' => $items['razonDecomiso'],
					'num_canales_decomisadas' => $items['numCanalesDecomisadas'],
					'peso_carne_decomisada' => $items['pesoCarneDecomisada'],
					'id_resultado_decomiso_total' => $idResultadoDecomisoTotal);
			}

			// ******************guardar organos si existen************************************
			$this->guardarDecomisoTotal($decomisoTotal);
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar resultado organos
	 *
	 * @return array
	 */
	public function guardarOrganos($organos){
		if (count($organos)){
			$statement = $this->modeloFormularioPostMortem->getAdapter()
				->getDriver()
				->createStatement();
			// registrar el hallazgos de examenes post mortem
			foreach ($organos as $items){
				if ($items['id_resultado_organos'] == null){
					unset($items["id_resultado_organos"]);
					$lNegocioResultadoOrganos = new ResultadoOrganosLogicaNegocio();
					$sqlInsertar = $this->modeloFormularioPostMortem->guardarSql('resultado_organos', $this->modeloFormularioPostMortem->getEsquema());
					$sqlInsertar->columns($lNegocioResultadoOrganos->columnas());
					$sqlInsertar->values($items, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioPostMortem->getAdapter(), $statement);
					$statement->execute();
					$idHallazgos = $this->modeloFormularioPostMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioPostMortem->getEsquema() . '.resultado_organos_id_resultado_organos_seq');
					if (! $idHallazgos){
						throw new \Exception('Error al guardar los datos en resultados organos');
					}
				}
			}
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar resultado decomiso parcial
	 *
	 * @return array
	 */
	public function guardarDecomisoParcial($decomisoParcial){
		if (count($decomisoParcial)){
			$statement = $this->modeloFormularioPostMortem->getAdapter()
				->getDriver()
				->createStatement();
			foreach ($decomisoParcial as $items){
				if ($items['id_resultado_decomiso_parcial'] == null){
					unset($items["id_resultado_decomiso_parcial"]);
					$lNegocioResultadoDecomisoParcial = new ResultadoDecomisoParcialLogicaNegocio();
					$sqlInsertar = $this->modeloFormularioPostMortem->guardarSql('resultado_decomiso_parcial', $this->modeloFormularioPostMortem->getEsquema());
					$sqlInsertar->columns($lNegocioResultadoDecomisoParcial->columnas());
					$sqlInsertar->values($items, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioPostMortem->getAdapter(), $statement);
					$statement->execute();
					$idHallazgos = $this->modeloFormularioPostMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioPostMortem->getEsquema() . '.resultado_decomiso_parcial_id_resultado_decomiso_parcial_seq');
					if (! $idHallazgos){
						throw new \Exception('Error al guardar los datos en resultados decomiso parcial');
					}
				}
			}
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar resultado decomiso total
	 *
	 * @return array
	 */
	public function guardarDecomisoTotal($decomisoTotal){
		if (count($decomisoTotal)){
			$statement = $this->modeloFormularioPostMortem->getAdapter()
				->getDriver()
				->createStatement();
			// registrar el hallazgos de examenes post mortem
			foreach ($decomisoTotal as $items){
				if ($items['id_resultado_decomiso_total'] == null){
					unset($items["id_resultado_decomiso_total"]);
					$lNegocioResultadoDecomisoTotal = new ResultadoDecomisoTotalLogicaNegocio();
					$sqlInsertar = $this->modeloFormularioPostMortem->guardarSql('resultado_decomiso_total', $this->modeloFormularioPostMortem->getEsquema());
					$sqlInsertar->columns($lNegocioResultadoDecomisoTotal->columnas());
					$sqlInsertar->values($items, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioPostMortem->getAdapter(), $statement);
					$statement->execute();
					$idHallazgos = $this->modeloFormularioPostMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioPostMortem->getEsquema() . '.resultado_decomiso_total_id_resultado_decomiso_total_seq');
					if (! $idHallazgos){
						throw new \Exception('Error al guardar los datos en resultados decomiso total');
					}
				}
			}
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar hallazgos
	 *
	 * @return array
	 */
	public function guardarHallazgos($hallazgos){
		if (count($hallazgos)){
			$statement = $this->modeloFormularioPostMortem->getAdapter()
				->getDriver()
				->createStatement();
			// registrar el hallazgos de examenes post mortem
			foreach ($hallazgos as $items){
				if ($items['id_examen_post_hallazgos'] == null){
					unset($items["id_examen_post_hallazgos"]);
					$lNegocioExamenPostHallazgos = new ExamenPostHallazgosLogicaNegocio();
					$sqlInsertar = $this->modeloFormularioPostMortem->guardarSql('examen_post_hallazgos', $this->modeloFormularioPostMortem->getEsquema());
					$sqlInsertar->columns($lNegocioExamenPostHallazgos->columnas());
					$sqlInsertar->values($items, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioPostMortem->getAdapter(), $statement);
					$statement->execute();
					$idHallazgos = $this->modeloFormularioPostMortem->adapter->driver->getLastGeneratedValue($this->modeloFormularioPostMortem->getEsquema() . '.examen_post_hallazgos_id_examen_post_hallazgos_seq');
					if (! $idHallazgos){
						throw new \Exception('Error al guardar los datos en examen post hallazgos');
					}
				}
			}
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar ectoparasitos
	 *
	 * @return array
	 */
	public function guardarEctoparasitos($ectoparasitos){
		if (count($ectoparasitos)){
			$statement = $this->modeloFormularioPostMortem->getAdapter()
				->getDriver()
				->createStatement();
			// registrar el hallazgos de ectoparasitos post mortem
			foreach ($ectoparasitos as $items){
				if ($items['id_examen_post_ectoparasitos'] == null){
					unset($items["id_examen_post_ectoparasitos"]);
					$lNegocioExamenPostEctoparasitos = new ExamenPostEctoparasitosLogicaNegocio();
					$sqlInsertar = $this->modeloFormularioPostMortem->guardarSql('examen_post_ectoparasitos', $this->modeloFormularioPostMortem->getEsquema());
					$sqlInsertar->columns($lNegocioExamenPostEctoparasitos->columnas());
					$sqlInsertar->values($items, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioPostMortem->getAdapter(), $statement);
					$statement->execute();
				}
			}
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar endoparasitos
	 *
	 * @return array
	 */
	public function guardarEndoparasitos($endoparasitos){
		if (count($endoparasitos)){
			$statement = $this->modeloFormularioPostMortem->getAdapter()
				->getDriver()
				->createStatement();
			// registrar el hallazgos de endoparasitos post mortem
			foreach ($endoparasitos as $items){
				if ($items['id_examen_post_endoparasitos'] == null){
					unset($items["id_examen_post_endoparasitos"]);
					$lNegocioExamenPostEndoparasitos = new ExamenPostEndoparasitosLogicaNegocio();
					$sqlInsertar = $this->modeloFormularioPostMortem->guardarSql('examen_post_endoparasitos', $this->modeloFormularioPostMortem->getEsquema());
					$sqlInsertar->columns($lNegocioExamenPostEndoparasitos->columnas());
					$sqlInsertar->values($items, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloFormularioPostMortem->getAdapter(), $statement);
					$statement->execute();
				}
			}
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el secuencial
	 *
	 * @return array
	 */
	public function obtenerSecuencialFormularioPostMortem($arrayParametros){
		$consulta = "SELECT
						COALESCE(count(*)::numeric, 0)+1 AS numero
					FROM
                        g_centros_faenamiento.formulario_post_mortem
					WHERE
                        identificador = '" . $arrayParametros['identificador_operador'] . "';";

		$resultado = $this->modeloFormularioPostMortem->ejecutarSqlNativo($consulta);
		return $resultado;
	}

	/**
	 * Ejecutar una consulta(SQL) para obtener informacion del detalle aves
	 */
	public function obtenerInfoDetallePostAves($idDetalleAnteAves){
		$consulta = "SELECT 
						fpm.estado, dpa.id_detalle_post_aves, fpm.id_formulario_post_mortem, dpa.fecha_formulario  
					FROM 
						g_centros_faenamiento.detalle_post_aves dpa 
						INNER JOIN g_centros_faenamiento.formulario_post_mortem fpm ON dpa.id_formulario_post_mortem = fpm.id_formulario_post_mortem
					WHERE dpa.id_detalle_ante_aves = " . $idDetalleAnteAves . " ;";
		$resultado = $this->modeloFormularioPostMortem->ejecutarSqlNativo($consulta);
		return $resultado;
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada detalles de hallazgos en examen post mortem
	 *
	 * @return array
	 */
	public function buscarDetalleHallazgosPost($idDetallePostAnimales){
		$consulta = "SELECT 
						id_examen_post_hallazgos as identificador, enfermedad, localizacion, num_animales_afectados 
					FROM 
						g_centros_faenamiento.examen_post_hallazgos 
					WHERE 
						id_detalle_post_animales = " . $idDetallePostAnimales . "
					ORDER BY 1;";

		return $this->modeloFormularioPostMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada resultados y decomisos en organos
	 *
	 * @return array
	 */
	public function buscarResultadoOrgano($idDetallePostAnimales){
		$consulta = "SELECT
						id_resultado_organos,id_detalle_post_animales, organo_decomisado, razon_decomiso, num_organos_decomisados
					FROM
						g_centros_faenamiento.resultado_organos
					WHERE
						id_detalle_post_animales = " . $idDetallePostAnimales . "
					ORDER BY 1;";

		return $this->modeloFormularioPostMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada resultados y decomisos parciales
	 *
	 * @return array
	 */
	public function buscarResultadoDecomisoParcial($idDetallePostAnimales){
		$consulta = "SELECT
						id_resultado_decomiso_parcial,id_detalle_post_animales, razon_decomiso, num_canales_decomisadas,peso_carne_aprobada,peso_carne_decomisada
					FROM
						g_centros_faenamiento.resultado_decomiso_parcial
					WHERE
						id_detalle_post_animales = " . $idDetallePostAnimales . "
					ORDER BY 1;";

		return $this->modeloFormularioPostMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada resultados y decomisos totales
	 *
	 * @return array
	 */
	public function buscarResultadoDecomisoTotal($idDetallePostAnimales){
		$consulta = "SELECT
						id_resultado_decomiso_total,id_detalle_post_animales, razon_decomiso, num_canales_decomisadas,peso_carne_decomisada
					FROM
						g_centros_faenamiento.resultado_decomiso_total
					WHERE
						id_detalle_post_animales = " . $idDetallePostAnimales . "
					ORDER BY 1;";

		return $this->modeloFormularioPostMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada detalles de examen_post_endoparasitos en examen post mortem
	 *
	 * @return array
	 */
	public function buscarDetalleEndoparasitosPost($idDetallePostAnimales){
		$consulta = "SELECT
						id_examen_post_endoparasitos as identificador,'Endoparásitos' as enfermedad, endoparasitos_localizacion as localizacion,endoparasitos_num_afectados as num_animales_afectados,endoparasitos_presencia
					FROM
						g_centros_faenamiento.examen_post_endoparasitos
					WHERE
						id_detalle_post_animales = " . $idDetallePostAnimales . "
					ORDER BY 1;";

		return $this->modeloFormularioPostMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada detalles de id_examen_post_ectoparasitos en examen post mortem
	 *
	 * @return array
	 */
	public function buscarDetalleEctoparasitosPost($idDetallePostAnimales){
		$consulta = "SELECT
						id_examen_post_ectoparasitos as identificador, 'Ectoparásitos' as enfermedad, ectoparasitos_localizacion as localizacion, ectoparasitos_num_afectados as num_animales_afectados,ectoparasitos_presencia
					FROM
						g_centros_faenamiento.examen_post_ectoparasitos
					WHERE
						id_detalle_post_animales = " . $idDetallePostAnimales . "
					ORDER BY 1;";

		return $this->modeloFormularioPostMortem->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecutar una consulta(SQL) para obtener informacion del detalle animales
	 */
	public function obtenerInfoDetallePostAnimales($idDetalleAnteAnimales){
		$consulta = "SELECT
						fpm.estado, dpa.id_detalle_post_animales, fpm.id_formulario_post_mortem, dpa.fecha_formulario
					FROM
						g_centros_faenamiento.detalle_post_animales dpa
						INNER JOIN g_centros_faenamiento.formulario_post_mortem fpm ON dpa.id_formulario_post_mortem = fpm.id_formulario_post_mortem
					WHERE dpa.id_detalle_ante_animales = " . $idDetalleAnteAnimales . " ;";
		$resultado = $this->modeloFormularioPostMortem->ejecutarSqlNativo($consulta);
		return $resultado;
	}

	// *****************************************generar excel********************************************************
	public function crearExcel($arrayDatos){
		$documento = new ReportesExcelModelo();
		$documento->getProperties()
			->setCreator("GUIA")
			->setLastModifiedBy('GUIA')
			->
		// sss
		setTitle('Reporte post mortem')
			->setSubject('Reporte')
			->setDescription('Este documento fue creado por el sistema GUIA')
			->setKeywords('')
			->setCategory('');

		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("hoja 1");

		$documento->cuerpoDinamicoHorizontal(6, '1. Provincia: ', 'ffffff', 1, 10, 1, 2, 1);
		$documento->cuerpoDinamicoHorizontal(6, '2. Cantón: ', 'ffffff', 1, 10, 1, 7, 1);
		$documento->cuerpoDinamicoHorizontal(6, '3. Parroquia: ', 'ffffff', 1, 10, 1, 12, 1);
		$documento->cuerpoDinamicoHorizontal(6, '5. Médico veterinario oficial o autorización:', 'ffffff', 1, 10, 1, 17, 4);
		$documento->cuerpoDinamicoHorizontal(6, '6. Fecha:', 'ffffff', 1, 10, 1, 28, 1);

		// ************************************************************************
		$variable = explode('-', $arrayDatos['idFormularioDetalle']);
		$lNegocioFormularioAnteMortem = new FormularioAnteMortemLogicaNegocio();
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[1]);
		$consulta = $lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
		
		$identifi = $lNegocioFormularioAnteMortem->buscarIdentificadorMedicoVeterinario($arrayParametros);
		$datos = $lNegocioFormularioAnteMortem->buscarDatosOperador($identifi->current()->identificador_operador);

		$lnDetallePostAnimales = new DetallePostAnimalesLogicaNegocio();
		$lnDetalleAnteAnimales = new DetalleAnteAnimalesLogicaNegocio();
		
		$lnExamenPostHallazgos = new ExamenPostHallazgosLogicaNegocio();
		$lnExamenPostEndop = new ExamenPostEndoparasitosLogicaNegocio();
		$lnExamenPostEctop = new ExamenPostEctoparasitosLogicaNegocio();
		
		$lnResultadoOrganos = new ResultadoOrganosLogicaNegocio();
		$lnResultadoDecomisoParcial = new ResultadoDecomisoParcialLogicaNegocio();
		$lnResultadoDecomisoTotal = new ResultadoDecomisoTotalLogicaNegocio();
		
		$sql= "id_formulario_post_mortem = " . $arrayDatos['id_formulario_post_mortem'] . " order by 1";
		$consultaDetalle = $lnDetallePostAnimales->buscarLista($sql);
		$arrayDetallePostAnimales = array();
		$arrayDetalleAnteAnimales = array();
		$arrayExamenHallazgos = array();
		$arrayExamenEndoparasitos = array();
		$arrayExamenEctoparasitos = array();
		$arrayResultadoOrganos = array();
		$arrayResultadoDecomisoParcial = array();
		$arrayResultadoDecomisoTotal = array();
		
		foreach ($consultaDetalle as $items){
			
			if ($items['id_detalle_ante_animales'] != ''){
					$sql = "id_detalle_ante_animales = " . $items['id_detalle_ante_animales'] . " order by 1";
					$sqlAnimales = $lnDetalleAnteAnimales->buscarLista($sql);
					foreach ($sqlAnimales as $itemsAni){
						$arrayDetalleAnteAnimales = array(
							'categoria_etaria' => $itemsAni['categoria_etaria'],
							'especie' => $itemsAni['especie'],
							'num_machos' => $itemsAni['num_machos'],
							'num_hembras' => $itemsAni['num_hembras'],
							'num_total_animales' => $itemsAni['num_total_animales']);
					}
			}
			
			$sql = "id_detalle_post_animales = " . $items['id_detalle_post_animales'] . " order by 1";
			//**************buscar examen post mortem******************************** 
			$sqlHallazgos = $lnExamenPostHallazgos->buscarLista($sql);
			foreach ($sqlHallazgos as $itemsHallaz){
				$arrayExamenHallazgos []= array(
					'enfermedad' => $itemsHallaz['enfermedad'],
					'localizacion' => $itemsHallaz['localizacion'],
					'num_animales_afectados' => $itemsHallaz['num_animales_afectados']);
			}
			//***************buscar hallazgos endoparasitos**************************
			$sqlEndoparasitos = $lnExamenPostEndop->buscarLista($sql);
			foreach ($sqlEndoparasitos as $itemsEndo){
				$arrayExamenEndoparasitos []= array(
					'endoparasitos_presencia' => $itemsEndo['endoparasitos_presencia'],
					'endoparasitos_localizacion' => $itemsEndo['endoparasitos_localizacion'],
					'endoparasitos_num_afectados' => $itemsEndo['endoparasitos_num_afectados']);
			}
			//***************buscar hallazgos ectoparasitos**************************
			$sqlEctoparasitos = $lnExamenPostEctop->buscarLista($sql);
			foreach ($sqlEctoparasitos as $itemsEcto){
				$arrayExamenEctoparasitos []= array(
					'ectoparasitos_presencia' => $itemsEcto['ectoparasitos_presencia'],
					'ectoparasitos_localizacion' => $itemsEcto['ectoparasitos_localizacion'],
					'ectoparasitos_num_afectados' => $itemsEcto['ectoparasitos_num_afectados']);
			}
			//****************buscar resultado organos*******************************
			$sqlOrganos = $lnResultadoOrganos->buscarLista($sql);
			foreach ($sqlOrganos as $itemsOrga){
				$arrayResultadoOrganos []= array(
					'organo_decomisado' => $itemsOrga['organo_decomisado'],
					'razon_decomiso' => $itemsOrga['razon_decomiso'],
					'num_organos_decomisados' => $itemsOrga['num_organos_decomisados']);
			}
			//*****************buscar resultados decomiso parcial*******************
			$sqlDecomisoParcial = $lnResultadoDecomisoParcial->buscarLista($sql);
			foreach ($sqlDecomisoParcial as $itemsParcial){
				$arrayResultadoDecomisoParcial []= array(
					'razon_decomiso' => $itemsParcial['razon_decomiso'],
					'num_canales_decomisadas' => $itemsParcial['num_canales_decomisadas'],
					'peso_carne_aprobada' => $itemsParcial['peso_carne_aprobada'],
					'peso_carne_decomisada' => $itemsParcial['peso_carne_decomisada']
				);
			}
			//*****************buscar resultado decomiso total**********************
			$sqlDecomisoTotal = $lnResultadoDecomisoTotal->buscarLista($sql);
			foreach ($sqlDecomisoTotal as $itemsTotal){
				$arrayResultadoDecomisoTotal []= array(
					'razon_decomiso' => $itemsTotal['razon_decomiso'],
					'num_canales_decomisadas' => $itemsTotal['num_canales_decomisadas'],
					'peso_carne_decomisada' => $itemsTotal['peso_carne_decomisada']);
			}
				
			$arrayDetallePostAnimales = array(
				'fecha_formulario' => $lNegocioFormularioAnteMortem->formatearFecha($items['fecha_formulario']),
				'estado_nodulos_linfaticos' => $items['estado_nodulos_linfaticos'],
				'otro_diagnostico' => $items['otro_diagnostico'],
				'num_canales_decomiso_parcial' => $items['num_canales_decomiso_parcial'],
				'peso_total_carne_aprobada' => $items['peso_total_carne_aprobada'],
				'peso_total_carne_decomisada' => $items['peso_total_carne_decomisada'],
				'num_canales_decomiso' => $items['num_canales_decomiso'],
				'peso_total_carne_decomisada_productivo' => $items['peso_total_carne_decomisada_productivo'],
				'num_canales_aprobadas_totalmente' => $items['num_canales_aprobadas_totalmente'],
				'num_canales_aprobadas_parcialmente' => $items['num_canales_aprobadas_parcialmente'],
				'peso_total_carne_aprobada_productivos' => $items['peso_total_carne_aprobada_productivos'],
				'peso_promedio_canal' => $items['peso_promedio_canal'],
				'peso_total_visceras_decomisadas' => $items['peso_total_visceras_decomisadas'],
				'peso_carne_incineracion' => $items['peso_carne_incineracion'],
				'peso_visceras_incineracion' => $items['peso_visceras_incineracion'],
				'peso_carne_rendering' => $items['peso_carne_rendering'],
				'peso_visceras_rendering' => $items['peso_visceras_rendering'],
				'peso_carne_abono' => $items['peso_carne_abono'],
				'peso_visceras_abono' => $items['peso_visceras_abono'],
				'lugar_incineracion' => $items['lugar_incineracion'],
				'lugar_renderizacion' => $items['lugar_renderizacion'],
				'lugar_desconposicion' => $items['lugar_desconposicion'],
				'nombre_gestor_ambiental' => $items['nombre_gestor_ambiental'],
				'descripcion_actividad_general' => $items['descripcion_actividad_general'],
				'observacion' => $items['observacion'],
				'peso_carne_ambiental' => $items['peso_carne_ambiental'],
				'peso_visceras_ambiental' => $items['peso_visceras_ambiental'],
				'examen_visual' => $items['examen_visual'],
				'palpacion' => $items['palpacion'],
				'insicion' => $items['insicion'],
				'toma_muestra' => $items['toma_muestra'],
				'organo_tejido' => $items['organo_tejido'],
				'descripcion_actividad' => $items['descripcion_actividad'],
				'arrayExamenHallazgos' => $arrayExamenHallazgos,
				'arrayExamenEndoparasitos' => $arrayExamenEndoparasitos,
				'arrayExamenEctoparasitos' => $arrayExamenEctoparasitos,
				'arrayResultadoOrganos' => $arrayResultadoOrganos,
				'arrayResultadoDecomisoParcial' => $arrayResultadoDecomisoParcial,
				'arrayResultadoDecomisoTotal' => $arrayResultadoDecomisoTotal,
				'arrayDetalleAnteAnimales' => $arrayDetalleAnteAnimales
				
			);
		}
		
		// *************************************************************************
		$documento->cuerpoDinamicoHorizontal(6, $consulta->current()->provincia, 'ffffff', 4, 10, 1, 4, 2);
		$documento->cuerpoDinamicoHorizontal(6, $consulta->current()->canton, 'ffffff', 4, 10, 1, 9, 2);
		$documento->cuerpoDinamicoHorizontal(6, $consulta->current()->parroquia, 'ffffff', 4, 10, 1, 14, 2);
		$documento->cuerpoDinamicoHorizontal(6, $datos->current()->nombre_medico, 'ffffff', 4, 10, 1, 22, 5);
		$documento->cuerpoDinamicoHorizontal(6, $lNegocioFormularioAnteMortem->formatearFecha($arrayDatos['fechaCreacion']), 'ffffff', 4, 10, 1, 30, 2);
		// *************************************************************************
		
		$fila1 = 9;
		$fila2 = $fila1 + 4;

		$documento->cuerpoInspeccion($fila1, 'Especies de animales de abasto', 'ffffff', 1, 10, 2, 'B', 'E');

		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Especie', 'ffffff', 1, 10, 0, 2, 0, 130, 15);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Total de animales', 'ffffff', 1, 10, 0, 3, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Hembras', 'ffffff', 1, 10, 0, 4, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Machos', 'ffffff', 1, 10, 0, 5, 0, 130, 5);

		//************************************************************************************************
		
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['arrayDetalleAnteAnimales']['especie'], 'ffffff', 1, 9, 0, 2, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['arrayDetalleAnteAnimales']['num_total_animales'], 'ffffff', 5, 9, 0, 3, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['arrayDetalleAnteAnimales']['num_hembras'], 'ffffff', 5, 9, 0, 4, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['arrayDetalleAnteAnimales']['num_machos'], 'ffffff', 5, 9, 0, 5, 0, 9);
		
		//************************************************************************************************
		$fill = $xtxt = 6;
		$j=1;
		foreach ($arrayDetallePostAnimales['arrayExamenHallazgos'] as $items){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2,$items['enfermedad'].' '.$j, 'ffffff', 1, 10, 0, $fill, 1);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Localización enfermedad ' . $j, 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Animales afectados', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$fill = $fill + 2;
			$j++;
			$documento->cuerpoDinamicoHorizontal($fila2, $items['localizacion'], 'ffffff', 1, 9, 0, $xtxt, 0, 9);
			$documento->cuerpoDinamicoHorizontal($fila2, $items['num_animales_afectados'], 'ffffff', 5, 9, 0, $xtxt+1, 0, 9);
			$xtxt = $xtxt + 2;
			
		}
		
		if ( $j == 1 ){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Enfermedad ', 'ffffff', 1, 10, 0, $fill, 1);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Localización enfermedad ', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Animales afectados', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$fill = $fill + 2;
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt, 0, 5);
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt+1, 0, 5);
		}
		
		//**********************************************************************************************
		$xtxt = $fill;
		$j=1;
		foreach ($arrayDetallePostAnimales['arrayExamenEndoparasitos'] as $items){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Endoparástiso ' . $j, 'ffffff', 1, 10, 0, $fill, 2);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Presencia ' . $j, 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Localización', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Animales afectados', 'ffffff', 1, 10, 0, $fill + 2, 0, 130, 5);
			$fill = $fill + 3;
			$documento->cuerpoDinamicoHorizontal($fila2, $items['endoparasitos_presencia'], 'ffffff', 1, 9, 0, $xtxt, 0, 9);
			$documento->cuerpoDinamicoHorizontal($fila2, $items['endoparasitos_localizacion'], 'ffffff', 1, 9, 0, $xtxt+1, 0, 9);
			$documento->cuerpoDinamicoHorizontal($fila2, $items['endoparasitos_num_afectados'], 'ffffff', 1, 9, 0, $xtxt+2, 0, 9);
			$xtxt = $xtxt + 3;
			$j++;
			
		}
		if ( $j == 1 ){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Endoparástiso ', 'ffffff', 1, 10, 0, $fill, 2);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Presencia ', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Localización', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Animales afectados', 'ffffff', 1, 10, 0, $fill + 2, 0, 130, 5);
			$fill = $fill + 3;
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 10, 0, $xtxt, 0, 4);
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 10, 0, $xtxt+1, 0, 4);
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 10, 0, $xtxt+2, 0, 4);
			$xtxt = $xtxt + 3;
		}
		
		//**********************************************************************************************
		$xtxt = $fill;
		$j=1;
		foreach ($arrayDetallePostAnimales['arrayExamenEctoparasitos'] as $items){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Ectoparástiso ' . $j, 'ffffff', 1, 10, 0, $fill, 2);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Presencia ' . $j, 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Localización', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Animales afectados', 'ffffff', 1, 10, 0, $fill + 2, 0, 130, 5);
			$fill = $fill + 3;
			$documento->cuerpoDinamicoHorizontal($fila2, $items['ectoparasitos_presencia'], 'ffffff', 1, 9, 0, $xtxt, 0, 12);
			$documento->cuerpoDinamicoHorizontal($fila2, $items['ectoparasitos_localizacion'], 'ffffff', 1, 9, 0, $xtxt+1, 0, 12);
			$documento->cuerpoDinamicoHorizontal($fila2, $items['ectoparasitos_num_afectados'], 'ffffff', 5, 9, 0, $xtxt+2, 0, 9);
			$xtxt = $xtxt + 3;
			$j++;
			
		}
		if ( $j == 1 ){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Ectoparástiso ', 'ffffff', 1, 10, 0, $fill, 2);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Presencia ', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Localización', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Animales afectados', 'ffffff', 1, 10, 0, $fill + 2, 0, 130, 5);
			$fill = $fill + 3;
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt, 0, 4);
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt+1, 0, 4);
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt+2, 0, 4);
			$xtxt = $xtxt + 3;
		}
		//***************************************************************************************************************************
		$documento->cuerpoDinamicoVertical($fila1 + 2, 'Estado de los nódulos linfáticos', 'ffffff', 1, 10, 1, $fill, 0, 130, 20);
		$documento->cuerpoDinamicoVertical($fila1 + 2, 'Otros', 'ffffff', 1, 10, 1, $fill + 1, 0, 130, 20);
		$documento->cuerpoDinamicoHorizontal($fila1, 'Hallazgos diagnosticados al examen post-mortem', 'ffffff', 1, 10, 1, 6, $fill - 5);

		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['estado_nodulos_linfaticos'], 'ffffff', 1, 9, 0, $xtxt, 0, 12);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['otro_diagnostico'], 'ffffff', 1, 9, 0, $xtxt+1, 0, 12);
		
		//*********************************************************************************************************************************
		$x1 = $fill + 2;
		$fill = $fill + 2;
		$x = $fill;
		
		$xtxt = $fill;
		$j=1;
		foreach ($arrayDetallePostAnimales['arrayResultadoOrganos'] as $items){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, $items['organo_decomisado'] .' '. $j, 'ffffff', 1, 10, 0, $fill, 1);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Razón del decomiso ' . $j, 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Órganos decomisados', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$fill = $fill + 2;
			$documento->cuerpoDinamicoHorizontal($fila2, $items['razon_decomiso'], 'ffffff', 1, 9, 0, $xtxt, 0, 12);
			$documento->cuerpoDinamicoHorizontal($fila2, $items['num_organos_decomisados'], 'ffffff', 5, 9, 0, $xtxt+1, 0, 9);
			$xtxt = $xtxt + 2;
			$j++;
			
		}
		if ( $j == 1 ){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Órganos', 'ffffff', 1, 10, 0, $fill, 1);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Razón del decomiso ' . $j, 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Órganos decomisados', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$fill = $fill + 2;
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt, 0, 4);
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt+1, 0, 4);
			$xtxt = $xtxt + 2;
			$j++;
		}
		
		$yAnch = (($j-1) * 2) - 1;
		$documento->cuerpoDinamicoHorizontal($fila1 + 1, 'Órganos ', 'ffffff', 1, 10, 0, $x, $yAnch);
		//*********************************************************************************************************
		
		$x = $fill;
		$j=1;
		foreach ($arrayDetallePostAnimales['arrayResultadoDecomisoParcial'] as $items){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, $items['razon_decomiso'] .' '. $j, 'ffffff', 1, 10, 0, $fill, 2);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Canales decomisadas ' . $j, 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso de carne aprobada', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso de carne decomisada', 'ffffff', 1, 10, 0, $fill + 2, 0, 130, 5);
			$fill = $fill + 3;
			$documento->cuerpoDinamicoHorizontal($fila2, $items['num_canales_decomisadas'], 'ffffff', 5, 9, 0, $xtxt, 0, 9);
			$documento->cuerpoDinamicoHorizontal($fila2, $items['peso_carne_aprobada'], 'ffffff', 5, 9, 0, $xtxt+1, 0, 9);
			$documento->cuerpoDinamicoHorizontal($fila2, $items['peso_carne_decomisada'], 'ffffff', 5, 9, 0, $xtxt+2, 0, 9);
			$xtxt = $xtxt + 3;
			$j++;
			
		}
		if ( $j == 1 ){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Canales parcial ', 'ffffff', 1, 10, 0, $fill, 2);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Canales decomisadas ', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso de carne aprobada', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso de carne decomisada', 'ffffff', 1, 10, 0, $fill + 2, 0, 130, 5);
			$fill = $fill + 3;
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt, 0, 6);
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt+1, 0, 6);
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt+2, 0, 6);
			$xtxt = $xtxt + 3;
			$j++;
		}
		
		$yAnch = (($j-1) * 3) - 1;
		$documento->cuerpoDinamicoHorizontal($fila1 + 1, 'Canales (Decomiso parcial) ', 'ffffff', 1, 10, 0, $x, $yAnch);
		
//*******************************************************************************************************************
		$x = $fill;
		$j=1;
		foreach ($arrayDetallePostAnimales['arrayResultadoDecomisoTotal'] as $items){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, $items['razon_decomiso'] .' '. $j, 'ffffff', 1, 8, 0, $fill, 1);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Canales decomisadas ' . $j, 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso de carne decomisada', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$fill = $fill + 2;
			$documento->cuerpoDinamicoHorizontal($fila2, $items['num_canales_decomisadas'], 'ffffff', 5, 9, 0, $xtxt, 0, 9);
			$documento->cuerpoDinamicoHorizontal($fila2, $items['peso_carne_decomisada'], 'ffffff', 5, 9, 0, $xtxt+1, 0, 9);
			$xtxt = $xtxt + 2;
			$j++;
			
		}
		if ( $j == 1 ){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Canales Total', 'ffffff', 1, 10, 0, $fill, 1);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Canales decomisadas ', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso de carne decomisada', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
			$fill = $fill + 2;
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt, 0,7);
			$documento->cuerpoDinamicoHorizontal($fila2, '', 'ffffff', 5, 9, 0, $xtxt+1, 0, 7);
			$xtxt = $xtxt + 2;
			$j++;
		}
		$yAnch = (($j-1) * 2) - 1;
		$documento->cuerpoDinamicoHorizontal($fila1 + 1, 'Canales (Decomiso total)', 'ffffff', 1, 10, 0, $x, $yAnch);

		$x = $fill;
		$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Decomiso parcial', 'ffffff', 1, 10, 0, $fill, 2);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Canales con decomiso parcial ' . $j, 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso total de carne aprobada (Kg)', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso total de carne decomisada (Kg)', 'ffffff', 1, 10, 0, $fill + 2, 0, 130, 5);
		
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['num_canales_decomiso_parcial'], 'ffffff', 5, 9, 0, $xtxt, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['peso_total_carne_aprobada'], 'ffffff', 5, 9, 0, $xtxt+1, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['peso_total_carne_decomisada'], 'ffffff', 5, 9, 0, $xtxt+2, 0, 9);
		
		$fill = $xtxt = $fill + 3;
		$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Decomiso total', 'ffffff', 1, 10, 0, $fill, 1);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Canales con decomiso total ' . $j, 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso total de carne decomisada (Kg)', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
		
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['num_canales_decomiso'], 'ffffff', 5, 9, 0, $xtxt, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['peso_total_carne_decomisada_productivo'], 'ffffff', 5, 9, 0, $xtxt+1, 0, 9);
		
		$fill = $xtxt = $fill + 2;
		$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Datos generales', 'ffffff', 1, 10, 0, $fill, 3);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nro. Canales aprobadas ' . $j, 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso total de carne aprobada (Kg)', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso promedio de la carne (Kg)', 'ffffff', 1, 10, 0, $fill + 2, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Peso total de viceras decomisadas (Kg)', 'ffffff', 1, 10, 0, $fill + 3, 0, 130, 5);
		
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['num_canales_aprobadas_totalmente'], 'ffffff', 5, 9, 0, $xtxt, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['peso_total_carne_aprobada_productivos'], 'ffffff', 5, 9, 0, $xtxt+1, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['peso_promedio_canal'], 'ffffff', 5, 9, 0, $xtxt+2, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['peso_total_visceras_decomisadas'], 'ffffff', 5, 9, 0, $xtxt+3, 0, 9);
		
		$fill = $xtxt = $fill + 4;
		$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Destino de los decomisos', 'ffffff', 1, 10, 0, $fill, 3);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'kg destinados a incineración', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'kg destinados a rendering', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'kg destinados a descomposición controlada (abono)', 'ffffff', 1, 10, 0, $fill + 2, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'kg entregados a gestor animal autorizado', 'ffffff', 1, 10, 0, $fill + 3, 0, 130, 5);
		
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['peso_carne_incineracion'], 'ffffff', 5, 9, 0, $xtxt, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['peso_carne_rendering'], 'ffffff', 5, 9, 0, $xtxt+1, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['peso_carne_abono'], 'ffffff', 5, 9, 0, $xtxt+2, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['peso_carne_ambiental'], 'ffffff', 5, 9, 0, $xtxt+3, 0, 9);
		
		
		$fill = $xtxt = $fill + 4;
		$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Lugar de la disposición final', 'ffffff', 1, 10, 0, $fill, 3);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Lugar de incineración', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Lugar de renderización', 'ffffff', 1, 10, 0, $fill + 1, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Lugar de descomposición controlada', 'ffffff', 1, 10, 0, $fill + 2, 0, 130, 5);
		$documento->cuerpoDinamicoVertical($fila1 + 3, 'Nombre del gestor ambiental autorizado', 'ffffff', 1, 10, 0, $fill + 3, 0, 130, 5);
		
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['lugar_incineracion'], 'ffffff', 1, 9, 0, $xtxt, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['lugar_renderizacion'], 'ffffff', 1, 9, 0, $xtxt+1, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['lugar_desconposicion'], 'ffffff', 1, 9, 0, $xtxt+2, 0, 9);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['nombre_gestor_ambiental'], 'ffffff', 1, 9, 0, $xtxt+3, 0, 9);
		
		$fill = $fill + 4;

		$yAnch = 16;
		$documento->cuerpoDinamicoHorizontal($fila1 + 1, 'Datos productivos ', 'ffffff', 1, 10, 0, $x, $yAnch);
		$documento->cuerpoDinamicoHorizontal($fila1, 'Resultados y decomisos', 'ffffff', 1, 10, 0, $x1, $fill - $x1 - 1);

		$fill = $fill;
		$x = $fill;
		$i = 0;
		
			
		if($arrayDetallePostAnimales['examen_visual']){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Examen visual ', 'ffffff', 1, 10, 0, $fill, 0);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Descripción del proceso ', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['descripcion_actividad_general'], 'ffffff', 1, 9, 0, $fill, 0, 9);
			$fill = $fill + 1;
			$i++;
		}
		if($arrayDetallePostAnimales['palpacion']){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Palpación ', 'ffffff', 1, 10, 0, $fill, 0);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Descripción del proceso ', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['descripcion_actividad_general'], 'ffffff', 1, 9, 0, $fill, 0, 9);
			$fill = $fill + 1;
			$i++;
		}
		if($arrayDetallePostAnimales['insicion']){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Insición ', 'ffffff', 1, 10, 0, $fill, 0);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Descripción del proceso ', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['descripcion_actividad_general'], 'ffffff', 1, 9, 0, $fill, 0, 9);
			$fill = $fill + 1;
			$i++;
		}
		if($arrayDetallePostAnimales['toma_muestra']){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Toma muestra ', 'ffffff', 1, 10, 0, $fill, 0);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Descripción del proceso ', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['descripcion_actividad_general'], 'ffffff', 1, 9, 0, $fill, 0, 9);
			$fill = $fill + 1;
			$i++;
		}
		if($arrayDetallePostAnimales['organo_tejido']){
			$documento->cuerpoDinamicoHorizontal($fila1 + 2, 'Órgano o tejido ', 'ffffff', 1, 10, 0, $fill, 0);
			$documento->cuerpoDinamicoVertical($fila1 + 3, 'Descripción del proceso ', 'ffffff', 1, 10, 0, $fill, 0, 130, 5);
			$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['descripcion_actividad'], 'ffffff', 1, 9, 0, $fill, 0, 9);
			$fill = $fill + 1;
			$i++;
		}

		$yAnch = $i - 1;
		switch ($yAnch) {
			case - 1:
			case 0:
				$tamaño = 6;
			break;
			case 1:
				$tamaño = 8;
			break;
			case 2:
				$tamaño = 10;
			break;
			default:
				$tamaño = 10;
			break;
		}

		$documento->cuerpoDinamicoHorizontal($fila1, 'Actividades realizadas por el médico veterinario oficial o autorizado durante la inspección', 'ffffff', 1, $tamaño, 1, $x, $yAnch);

		$x = $x + $yAnch + 1;
		$documento->cuerpoDinamicoHorizontal($fila1, 'Observaciones', 'ffffff', 1, 10, 3, $x, 0, 15);
		$documento->cuerpoDinamicoHorizontal($fila2, $arrayDetallePostAnimales['observacion'], 'ffffff', 1, 9, 0, $x, 0, 12);

		$documento->crearCabeceraExcel(3, $arrayDatos['titulo'], 'ffffff', 0, 12, $x);

		$documento->crearCabeceraExcel(4, $arrayDatos['subtitulo'], 'ffffff', 0, 10, $x);

		$documento->crearCabeceraExcel(5, $arrayDatos['seccionA'], 'f7ff93', 1, 12, $x);

		$documento->crearCabeceraExcel(8, $arrayDatos['seccionB'], 'bfeaff', 1, 12, $x);

		

		$documento->crearCabeceraExcel(18, $arrayDatos['seccionC'], 'c1c1c1', 1, 12, $x);

		$documento->crearCabeceraExcel(26, 'Médico veterinario oficial o autorizado', 'ffffff', 0, 12, $x);
		
		$documento->getActiveSheet()
			->getRowDimension($fila1 + 3)
			->setRowHeight(165);
		$documento->getActiveSheet()
			->getRowDimension($fila1 + 2)
			->setRowHeight(25);
		$documento->getActiveSheet()
			->getRowDimension($fila1 + 1)
			->setRowHeight(25);
		$documento->getActiveSheet()
			->getRowDimension($fila1)
			->setRowHeight(25);
		$documento->getActiveSheet()
			->getRowDimension($fila2)
			->setRowHeight(50);
			

		$writer = new Xlsx($documento);
		$nombreArchivo = INSP_AP_TCPDF . "reportes/formulariosPM/" . $arrayDatos['nombreArchivo'] . ".xlsx";
		$writer->save($nombreArchivo);
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

		$doc = new ReportesPdfModelo('L', 'mm', 'A4', true, 'UTF-8');

		$tipoLetra = 'helvetica';
		// ******************************************* FIRMA *************************************************************************
		$doc->SetLineWidth(0.1);
		$doc->setCellHeightRatio(1.5);
		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);
		$doc->SetFont($tipoLetra, '', 9);
		$doc->AddPage();

		// ***********************************QR EN FIRMA ELECTRONICA**********************
		$lNegocioFormularioAnteMortem = new FormularioAnteMortemLogicaNegocio();
		$variable = explode('-', $arrayDatos['idFormularioDetalle']);
		$arrayParametros = array(
			'id_centro_faenamiento' => $variable[1]);
		$consulta = $lNegocioFormularioAnteMortem->buscarRazonSocialOperador($arrayParametros);
		
		$identifi = $lNegocioFormularioAnteMortem->buscarIdentificadorMedicoVeterinario($arrayParametros);
		$datos = $lNegocioFormularioAnteMortem->buscarDatosOperador($identifi->current()->identificador_operador);
		
		//$datos = $lNegocioFormularioAnteMortem->buscarDatosOperador($_SESSION['usuario']);
		
		$rutaQRG = $arrayDatos['titulo'] . '
		        Centro: ' . $consulta->current()->razon_social . '
		        Médico: ' . $datos->current()->nombre_medico . '
		        ' . $lNegocioFormularioAnteMortem->formatearFecha($arrayDatos['fechaCreacion']);
		// *********************************************************************************
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
		$p1 = 75;
		$p2 = 22;
		$p3 = 138;
		$p4 = $tamañoColumn - $p1 - $p2 - $p3;
		$y = $doc->GetY();
		$genera = $margen_izquierdo;
		$estadoGeneral = $margen_izquierdo + $p1;
		$manejoFae = $margen_izquierdo + $p1 + $p2;
		$observacion = $margen_izquierdo + $p1 + $p2 + $p3;

		$doc->crearEncabezadoTabla($tipoLetra, $genera, $p1, $y, 'GENERALIDADES', array(
			255,
			255,
			255), 6, 0.1, 0.1);
		$doc->crearEncabezadoTabla($tipoLetra, $estadoGeneral, $p2, $y, 'DEL ESTADO <br>GENERAL DEL AVE ', array(
			255,
			255,
			255), 6, 0.1, - 2);
		$doc->crearEncabezadoTabla($tipoLetra, $manejoFae, $p3, $y, 'DEL MANEJO AL FAENAMIENTO', array(
			255,
			255,
			255), 6, 0.1, - 2);
		$doc->crearEncabezadoTabla($tipoLetra, $observacion, $p4, $y, 'OBSERVACIONES', array(
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

		$doc->SetFont($tipoLetra, '', 6);
		$x = $estadoGeneral;
		$ancho = $p2;
		$doc->textoVertical('%. Descartes (caquexia, cianosis y ascitis)', $x, $y1, $tipoLetra, 6, $ancho, 7);

		$x = $manejoFae;
		$ancho = $p3 / 16;
		$doc->textoVertical('%. Colibacilosis', $x, $y1, $tipoLetra, 6, $ancho - 2, 2.5);
		$doc->textoVertical('%. Pododermatitis', $x + $ancho - 2, $y1, $tipoLetra, 6, $ancho - 2, 2.5);
		$doc->textoVertical('%. Lesiones de piel', $x + $ancho * 2 - 4, $y1, $tipoLetra, 6, $ancho - 2, 2.5);
		$doc->textoVertical('%. Mal sangrado', $x + $ancho * 3 - 6, $y1, $tipoLetra, 6, $ancho - 2, 2.5);
		$doc->textoVertical('%. Contusión de pierna', $x + $ancho * 4 - 8, $y1, $tipoLetra, 6, $ancho - 2, 2.5);
		$doc->textoVertical('%. Contusión de ala', $x + $ancho * 5 - 10, $y1, $tipoLetra, 6, $ancho - 2, 2.5);
		$doc->textoVertical('%. Contusión de pechuga', $x + $ancho * 6 - 12, $y1, $tipoLetra, 6, $ancho - 2, 2.5);
		$doc->textoVertical('%. Alas rotas', $x + $ancho * 7 - 14, $y1, $tipoLetra, 6, $ancho - 2, 1.6);
		$doc->textoVertical('%. Piernas rotas', $x + $ancho * 8 - 16, $y1, $tipoLetra, 6, $ancho - 2, 1.6);
		$doc->textoVertical('Nro. Total de canales aprobadas', $x + $ancho * 9 - 18, $y1, $tipoLetra, 6, $ancho - 1, 1);
		$doc->textoVertical('Nro. De canales con decomiso parcial', $x + $ancho * 10 - 19, $y1, $tipoLetra, 6, $ancho - 1, 1);
		$doc->textoVertical('Nro. De canales con decomiso total', $x + $ancho * 11 - 20, $y1, $tipoLetra, 6, $ancho - 1, 1);
		$doc->textoVertical('Peso promedio de las canales', $x + $ancho * 12 - 21, $y1, $tipoLetra, 6, $ancho - 1, 0.8);
		$doc->textoVertical('Peso total de carne decomisada', $x + $ancho * 13 - 22, $y1, $tipoLetra, 6, $ancho - 1, 1);
		$doc->textoVertical('Destino de los decomisos', $x + $ancho * 14 - 23, $y1, $tipoLetra, 6, $ancho + 10, 8.5);
		$doc->textoVertical('Lugar de la disposición final', $x + $ancho * 15 - 13, $y1, $tipoLetra, 6, $ancho + 13, 8.5);

		$lnDetallePostAves = new DetallePostAvesLogicaNegocio();
		$lnDetalleAnteAves = new DetalleAnteAvesLogicaNegocio();

		$consulta = "id_formulario_post_mortem = " . $arrayDatos['id_formulario_post_mortem'] . " order by 1";
		$consultaDetalle = $lnDetallePostAves->buscarLista($consulta);

		$arrayGenera = array();
		$arrayDetallePostAves = array();

		foreach ($consultaDetalle as $items){

			if ($items['id_detalle_ante_aves'] != ''){
				$consulta = "id_detalle_ante_aves = " . $items['id_detalle_ante_aves'] . " order by 1";
				$consultaAves = $lnDetalleAnteAves->buscarLista($consulta);
				foreach ($consultaAves as $itemsAves){
					$arrayGenera = array(
						'fecha_formulario' => $lNegocioFormularioAnteMortem->formatearFecha($itemsAves['fecha_formulario']),
						'tipo_ave' => $itemsAves['tipo_ave'],
						'lugar_procedencia' => $itemsAves['lugar_procedencia'],
						'num_csmi' => $itemsAves['num_csmi'],
						'total_aves' => $itemsAves['total_aves'],
						'promedio_aves' => $itemsAves['promedio_aves']);
				}
			}else{
				$arrayGenera = array(
					'');
			}

			$arrayDetallePostAves[] = array(
				'fecha_formulario' => $lNegocioFormularioAnteMortem->formatearFecha($items['fecha_formulario']),
				'porcent_num_descarte' => $items['porcent_num_descarte'],
				'porcent_num_colibacilosis' => $items['porcent_num_colibacilosis'],
				'porcent_num_pododermatitis' => $items['porcent_num_pododermatitis'],
				'porcent_num_lesiones_piel' => $items['porcent_num_lesiones_piel'],
				'porcent_num_mal_sangrado' => $items['porcent_num_mal_sangrado'],
				'porcent_num_contusion_pierna' => $items['porcent_num_contusion_pierna'],
				'porcent_num_contusion_ala' => $items['porcent_num_contusion_ala'],
				'porcent_num_contusion_pechuga' => $items['porcent_num_contusion_pechuga'],
				'porcent_num_alas_rotas' => $items['porcent_num_alas_rotas'],
				'porcent_num_piernas_rotas' => $items['porcent_num_piernas_rotas'],
				'total_canales_aprobados' => $items['total_canales_aprobados'],
				'canales_decomiso_parcial' => $items['canales_decomiso_parcial'],
				'canales_decomiso_total' => $items['canales_decomiso_total'],
				'peso_promedio_canales' => $items['peso_promedio_canales'],
				'total_carne_decomisada' => $items['total_carne_decomisada'],
				'destino_decomisos' => $items['destino_decomisos'],
				'lugar_disposicion_final' => $items['lugar_disposicion_final'],
				'observacion' => $items['observacion'],
				'arrayDetalleAves' => $arrayGenera);
		}

		$y2 = $y1 + 33; // 23 48
		$i = 0;
		foreach ($arrayDetallePostAves as $items){
			$doc->crearFilasAves($items, $y2, $genera, $estadoGeneral, $manejoFae, $observacion, $tipoLetra, $p1, $p2, $p3, $p4);
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
		$doc->Output(INSP_AP_TCPDF . "reportes/formulariosPM/" . $arrayDatos['nombreArchivo'] . ".pdf", 'F');
		ob_end_clean();
	}
}

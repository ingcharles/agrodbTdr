<?php
/**
 * Lógica del negocio de DetalleAnteAnimalesModelo
 *
 * Este archivo se complementa con el archivo DetalleAnteAnimalesControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-05-27
 * @uses DetalleAnteAnimalesLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
use Exception;
use Agrodb\Core\Excepciones\GuardarExcepcion;

class DetalleAnteAnimalesLogicaNegocio implements IModelo{

	private $modeloDetalleAnteAnimales = null;
	private $lnFormularioAnteMortem = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleAnteAnimales = new DetalleAnteAnimalesModelo();
		$this->lnFormularioAnteMortem = new FormularioAnteMortemLogicaNegocio();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleAnteAnimalesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleAnteAnimales() != null && $tablaModelo->getIdDetalleAnteAnimales() > 0){
			return $this->modeloDetalleAnteAnimales->actualizar($datosBd, $tablaModelo->getIdDetalleAnteAnimales());
		}else{
			unset($datosBd["id_detalle_ante_animales"]);
			return $this->modeloDetalleAnteAnimales->guardar($datosBd);
		}
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada de la especie en animales
	 *
	 * @return array
	 */
	public function buscarEspecieXDetalleFormularioAnimales($idDetalleFormulario){
		$consulta = "SELECT
						cf.especie, cf.id_operador_tipo_operacion
					 FROM
						g_centros_faenamiento.detalle_ante_animales daa
						INNER JOIN g_centros_faenamiento.formulario_ante_mortem fam ON daa.id_formulario_ante_mortem = fam.id_formulario_ante_mortem
						INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_centro_faenamiento = fam.id_centro_faenamiento
					 WHERE
						daa.id_detalle_ante_animales = " . $idDetalleFormulario . " ;";
		
		return $this->modeloDetalleAnteAnimales->ejecutarSqlNativo($consulta);
	}

	/**
	 * actualizar el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function actualizarDatos(Array $datos){
		try{
			$this->modeloDetalleAnteAnimales = new DetalleAnteAnimalesModelo();
			$proceso = $this->modeloDetalleAnteAnimales->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción en: Guardar formulario de ante mortem de aves');
			}
			$id = $datos['id_detalle_ante_animales'];
			$this->modeloDetalleAnteAnimales = $this->buscar($id);
			$idAnimalesMuertos = $idSignosClinicos = $idLocomocion = '';
			// **********actualizar si existen hallazgos********************************************
			if ($datos['hallazgos'] == 'Si'){
				// **********agregar hallazgos en animales muertos
				if ($this->lnFormularioAnteMortem->verificarDatosAnimalesMuertos($datos)){
					$datosAnimalesMuertos= array(
						'num_animales_muertos' => $datos['num_animales_muertos'],
						'causa_probable' => ($datos['causa_probable'] != '') ? $datos['causa_probable'] : NULL,
						'decomiso' => ($datos['decomiso'] != '') ? $datos['decomiso'] : NULL,
						'aprovechamiento' => ($datos['aprovechamiento'] != '') ? $datos['aprovechamiento'] : NULL);
					
					$statement = $this->modeloDetalleAnteAnimales->getAdapter()
						->getDriver()
						->createStatement();
						if ($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesMuertos() == null){
						$lNegocioHallazgosAnimalesMuertos = new HallazgosAnimalesMuertosLogicaNegocio();
						$sqlInsertar = $this->modeloDetalleAnteAnimales->guardarSql('hallazgos_animales_muertos', $this->modeloDetalleAnteAnimales->getEsquema());
						$sqlInsertar->columns($lNegocioHallazgosAnimalesMuertos->columnas());
						$sqlInsertar->values($datosAnimalesMuertos, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloDetalleAnteAnimales->getAdapter(), $statement);
						$statement->execute();
						$idAnimalesMuertos = $this->modeloDetalleAnteAnimales->adapter->driver->getLastGeneratedValue($this->modeloDetalleAnteAnimales->getEsquema() . '.hallazgos_animales_muertos_id_hallazgos_animales_muertos_seq');
						if (! $idAnimalesMuertos){
							throw new \Exception('Error al guardar los datos en hallazgos de animales muertos');
						}
					}else{
						$idAnimalesMuertos = $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesMuertos();
						$sqlActualizar = $this->modeloDetalleAnteAnimales->actualizarSql('hallazgos_animales_muertos', $this->modeloDetalleAnteAnimales->getEsquema());
						$sqlActualizar->set($datosAnimalesMuertos);
						$sqlActualizar->where(array(
							'id_hallazgos_animales_muertos' => $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesMuertos()));
						$sqlActualizar->prepareStatement($this->modeloDetalleAnteAnimales->getAdapter(), $statement);
						$statement->execute();
					}
				}else if ($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesMuertos() != null){
					$idAnimalesMuertos = '';
				}

				// **********agregar hallazgos en signos clinicos
				if ($this->lnFormularioAnteMortem->verificarDatosSignosClinicos($datos)){
					$datosSignosClinicos = array(
						'num_animales_nerviosos' => ($datos['num_animales_nerviosos'] != '') ? $datos['num_animales_nerviosos'] : NULL,
						'num_animales_digestivo' => ($datos['num_animales_digestivo'] != '') ? $datos['num_animales_digestivo'] : NULL,
						'num_animales_respiratorio' => ($datos['num_animales_respiratorio'] != '') ? $datos['num_animales_respiratorio'] : NULL,
						'num_animales_vesicular' => ($datos['num_animales_vesicular'] != '') ? $datos['num_animales_vesicular'] : NULL,
						'num_animales_reproductivo' => ($datos['num_animales_reproductivo'] != '') ? $datos['num_animales_reproductivo'] : NULL);

					$statement = $this->modeloDetalleAnteAnimales->getAdapter()
						->getDriver()
						->createStatement();
					if ($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesClinicos() == null){
						$lNegocioHallazgosAnimalesClinicos = new HallazgosAnimalesClinicosLogicaNegocio();
						$sqlInsertar = $this->modeloDetalleAnteAnimales->guardarSql('hallazgos_animales_clinicos', $this->modeloDetalleAnteAnimales->getEsquema());
						$sqlInsertar->columns($lNegocioHallazgosAnimalesClinicos->columnas());
						$sqlInsertar->values($datosSignosClinicos, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloDetalleAnteAnimales->getAdapter(), $statement);
						$statement->execute();
						$idSignosClinicos = $this->modeloDetalleAnteAnimales->adapter->driver->getLastGeneratedValue($this->modeloDetalleAnteAnimales->getEsquema() . '.hallazgos_animales_clinicos_id_hallazgos_animales_clinicos_seq');
						if (! $idSignosClinicos){
							throw new \Exception('Error al guardar datos de hallazgos en animales clinicos');
						}
					}else{
						$idSignosClinicos = $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesClinicos();
						$sqlActualizar = $this->modeloDetalleAnteAnimales->actualizarSql('hallazgos_animales_clinicos', $this->modeloDetalleAnteAnimales->getEsquema());
						$sqlActualizar->set($datosSignosClinicos);
						$sqlActualizar->where(array(
							'id_hallazgos_animales_clinicos' => $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesClinicos()));
						$sqlActualizar->prepareStatement($this->modeloDetalleAnteAnimales->getAdapter(), $statement);
						$statement->execute();
					}
				}elseif ($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesClinicos() != null){
					$idSignosClinicos = '';
				}
				// **********agregar hallazgos en animales locomoción
				if ($this->lnFormularioAnteMortem->verificarDatosLocomocion($datos)){
					$datosLocomocion = array(
						'num_animales_cojera' => ($datos['num_animales_cojera'] != '') ? $datos['num_animales_cojera'] : NULL,
						'num_animales_ambulatorios' => ($datos['num_animales_ambulatorios'] != '') ? $datos['num_animales_ambulatorios'] : NULL);
					$statement = $this->modeloDetalleAnteAnimales->getAdapter()
						->getDriver()
						->createStatement();
					if ($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesLocomocion() == null){
						$lNegocioHallazgosLocomocion = new HallazgosAnimalesLocomocionLogicaNegocio();
						$sqlInsertar = $this->modeloDetalleAnteAnimales->guardarSql('hallazgos_animales_locomocion', $this->modeloDetalleAnteAnimales->getEsquema());
						$sqlInsertar->columns($lNegocioHallazgosLocomocion->columnas());
						$sqlInsertar->values($datosLocomocion, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloDetalleAnteAnimales->getAdapter(), $statement);
						$statement->execute();
						$idLocomocion = $this->modeloDetalleAnteAnimales->adapter->driver->getLastGeneratedValue($this->modeloDetalleAnteAnimales->getEsquema() . '.hallazgos_animales_locomocion_id_hallazgos_animales_locomoc_seq');
						if (! $idLocomocion){
							throw new \Exception('Error al guardar datos de hallazgos en animales locomocion');
						}
					}else{
						$idLocomocion = $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesLocomocion();
						$sqlActualizar = $this->modeloDetalleAnteAnimales->actualizarSql('hallazgos_animales_locomocion', $this->modeloDetalleAnteAnimales->getEsquema());
						$sqlActualizar->set($datosLocomocion);
						$sqlActualizar->where(array(
							'id_hallazgos_animales_locomocion' => $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesLocomocion()));
						$sqlActualizar->prepareStatement($this->modeloDetalleAnteAnimales->getAdapter(), $statement);
						$statement->execute();
					}
				}else if ($this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesLocomocion() != null){
					$idLocomocion = '';
				}
				
				$statement = $this->modeloDetalleAnteAnimales->getAdapter()
					->getDriver()
					->createStatement();
				$tablaModelo = new DetalleAnteAnimalesModelo($datos);
				$datosBd = $tablaModelo->getPrepararDatos();
				$datosBd["id_hallazgos_animales_muertos"] = ($idAnimalesMuertos != '') ? $idAnimalesMuertos : NULL;
				$datosBd["id_hallazgos_animales_clinicos"] = ($idSignosClinicos != '') ? $idSignosClinicos : NULL;
				$datosBd["id_hallazgos_animales_locomocion"] = ($idLocomocion != '') ? $idLocomocion : NULL;
				$datosBd["num_csmi"] = ($datosBd["num_csmi"] != '') ? $datosBd["num_csmi"] : NULL;
				if ($tablaModelo->getIdDetalleAnteAnimales() != null && $tablaModelo->getIdDetalleAnteAnimales() > 0){
					$sqlActualizar = $this->modeloDetalleAnteAnimales->actualizarSql('detalle_ante_animales', $this->modeloDetalleAnteAnimales->getEsquema());
					$sqlActualizar->set($datosBd);
					$sqlActualizar->where(array(
						'id_detalle_ante_animales' => $tablaModelo->getIdDetalleAnteAnimales()));
					$sqlActualizar->prepareStatement($this->modeloDetalleAnteAnimales->getAdapter(), $statement);
					$statement->execute();
				}else{
					throw new \Exception('Error al guardar datos de con el id del detalle animales');
				}

				if ($idAnimalesMuertos == ''){
					$this->borrarRegistro('hallazgos_animales_muertos', 'id_hallazgos_animales_muertos', $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesMuertos());
				}
				if ($idSignosClinicos == ''){
					$this->borrarRegistro('hallazgos_animales_clinicos', 'id_hallazgos_animales_clinicos', $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesClinicos());
				}
				if ($idLocomocion == ''){
					$this->borrarRegistro('hallazgos_animales_locomocion', 'id_hallazgos_animales_locomocion', $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesLocomocion());
				}
			}else{
				$statement = $this->modeloDetalleAnteAnimales->getAdapter()
					->getDriver()
					->createStatement();
				$tablaModelo = new DetalleAnteAnimalesModelo($datos);
				$datosBd = $tablaModelo->getPrepararDatos();
				$datosBd["id_hallazgos_animales_muertos"] = NULL;
				$datosBd["id_hallazgos_animales_clinicos"] = NULL;
				$datosBd["id_hallazgos_animales_locomocion"] = NULL;
				$datosBd["num_csmi"] = ($datosBd["num_csmi"] != '') ? $datosBd["num_csmi"] : NULL;
				if ($tablaModelo->getIdDetalleAnteAnimales() != null && $tablaModelo->getIdDetalleAnteAnimales() > 0){
					$sqlActualizar = $this->modeloDetalleAnteAnimales->actualizarSql('detalle_ante_animales', $this->modeloDetalleAnteAnimales->getEsquema());
					$sqlActualizar->set($datosBd);
					$sqlActualizar->where(array(
						'id_detalle_ante_animales' => $tablaModelo->getIdDetalleAnteAnimales()));
					$sqlActualizar->prepareStatement($this->modeloDetalleAnteAnimales->getAdapter(), $statement);
					$statement->execute();
				}else{
					throw new \Exception('Error al guardar datos de con el id del detalle en animales');
				}
				$this->borrarRegistro('hallazgos_animales_muertos', 'id_hallazgos_animales_muertos', $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesMuertos());
				$this->borrarRegistro('hallazgos_animales_clinicos', 'id_hallazgos_animales_clinicos', $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesClinicos());
				$this->borrarRegistro('hallazgos_animales_locomocion', 'id_hallazgos_animales_locomocion', $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesLocomocion());
			}

			$proceso->commit();
			return true;
		}catch (GuardarExcepcion $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
		}catch (Exception $exc){
			$proceso->rollback();
			throw new \Exception($exc->getMessage());
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
		$this->modeloDetalleAnteAnimales->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleAnteAnimalesModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleAnteAnimales->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleAnteAnimales->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleAnteAnimales->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleAnteAnimales(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleAnteAnimales->getEsquema() . ". detalle_ante_animales";
		return $this->modeloDetalleAnteAnimales->ejecutarSqlNativo($consulta);
	}

	/**
	 * funcion para borrar
	 */
	public function borrarRegistro($tabla, $id, $idValor){
		if ($idValor != null){
			$statement = $this->modeloDetalleAnteAnimales->getAdapter()
			->getDriver()
			->createStatement();
			$sqlBorrar = $this->modeloDetalleAnteAnimales->borrarSql($tabla, $this->modeloDetalleAnteAnimales->getEsquema());
			$sqlBorrar->where(array(
				$id => $idValor));
			$sqlBorrar->prepareStatement($this->modeloDetalleAnteAnimales->getAdapter(), $statement);
			$statement->execute();
		}
	}
	
	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_formulario_ante_mortem',
			'fecha_formulario',
			'num_csmi',
			'num_lote',
			'especie',
			'categoria_etaria',
			'peso_vivo_promedio',
			'num_machos',
			'num_hembras',
			'num_total_animales',
			'hallazgos',
			'matanza_normal',
			'matanza_especiales',
			'matanza_emergencia',
			'aplazamiento_matanza',
			'observacion',
			'id_hallazgos_animales_muertos',
			'id_hallazgos_animales_clinicos',
			'id_hallazgos_animales_locomocion');
		return $columnas;
	}
}

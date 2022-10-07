<?php
/**
 * Lógica del negocio de DetalleAnteAvesModelo
 *
 * Este archivo se complementa con el archivo DetalleAnteAvesControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-05-27
 * @uses DetalleAnteAvesLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
namespace Agrodb\InspeccionAntePostMortemCF\Modelos;

use Exception;
use Agrodb\Core\Excepciones\GuardarExcepcion;

class DetalleAnteAvesLogicaNegocio implements IModelo{

	private $modeloDetalleAnteAves = null;

	private $lnFormularioAnteMortem = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleAnteAves = new DetalleAnteAvesModelo();
		$this->lnFormularioAnteMortem = new FormularioAnteMortemLogicaNegocio();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleAnteAvesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleAnteAves() != null && $tablaModelo->getIdDetalleAnteAves() > 0){
			return $this->modeloDetalleAnteAves->actualizar($datosBd, $tablaModelo->getIdDetalleAnteAves());
		}else{
			unset($datosBd["id_detalle_ante_aves"]);
			return $this->modeloDetalleAnteAves->guardar($datosBd);
		}
	}

	/**
	 * actualizar el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function actualizarDatos(Array $datos){
		try{
			$this->modeloDetalleAnteAves = new DetalleAnteAvesModelo();
			$proceso = $this->modeloDetalleAnteAves->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción en: Guardar formulario de ante mortem de aves');
			}
			$id = $datos['id_detalle_ante_aves'];
			$this->modeloDetalleAnteAves = $this->buscar($id);
			$idAvesMuertas = $idCaracteristica = $idProbSist = $idCaractExter = '';
			// **********actualizar si existen hallazgos********************************************
			if ($datos['hallazgos'] == 'Si'){
				// **********agregar hallazgos en aves muertas
				if ($this->lnFormularioAnteMortem->verificarDatosAvesMuertas($datos)){
					$datosAvesMuertas = array(
						'aves_muertas' => $datos['aves_muertas'],
						'porcent_aves_muertas' => $datos['porcent_aves_muertas'],
						'causa_probable' => $datos['causa_probable']);
					$statement = $this->modeloDetalleAnteAves->getAdapter()
						->getDriver()
						->createStatement();
					if ($this->modeloDetalleAnteAves->getIdHallazgosAvesMuertas() == null){
						$lNegocioHallazgosAvesMuertas = new HallazgosAvesMuertasLogicaNegocio();
						$sqlInsertar = $this->modeloDetalleAnteAves->guardarSql('hallazgos_aves_muertas', $this->modeloDetalleAnteAves->getEsquema());
						$sqlInsertar->columns($lNegocioHallazgosAvesMuertas->columnas());
						$sqlInsertar->values($datosAvesMuertas, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
						$statement->execute();
						$idAvesMuertas = $this->modeloDetalleAnteAves->adapter->driver->getLastGeneratedValue($this->modeloDetalleAnteAves->getEsquema() . '.hallazgos_aves_muertas_id_hallazgos_aves_muertas_seq');
						if (! $idAvesMuertas){
							throw new \Exception('Error al guardar los datos del los hallazgos en aves muertas');
						}
					}else{
						$idAvesMuertas = $this->modeloDetalleAnteAves->getIdHallazgosAvesMuertas();
						$sqlActualizar = $this->modeloDetalleAnteAves->actualizarSql('hallazgos_aves_muertas', $this->modeloDetalleAnteAves->getEsquema());
						$sqlActualizar->set($datosAvesMuertas);
						$sqlActualizar->where(array(
							'id_hallazgos_aves_muertas' => $this->modeloDetalleAnteAves->getIdHallazgosAvesMuertas()));
						$sqlActualizar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
						$statement->execute();
					}
				}else if ($this->modeloDetalleAnteAves->getIdHallazgosAvesMuertas() != null){
					$idAvesMuertas = '';
				}

				// **********agregar hallazgos de caracteristicas
				if ($this->lnFormularioAnteMortem->verificarDatosCaracteristicas($datos)){
					$datosCaracteristica = array(
						'decaidas' => ($datos['decaidas'] != '') ? $datos['decaidas'] : NULL,
						'porcent_decaidas' => ($datos['porcent_decaidas'] != '') ? $datos['porcent_decaidas'] : NULL,
						'num_traumas' => ($datos['num_traumas'] != '') ? $datos['num_traumas'] : NULL,
						'porcent_traumas' => ($datos['porcent_traumas'] != '') ? $datos['porcent_traumas'] : NULL);

					$statement = $this->modeloDetalleAnteAves->getAdapter()
						->getDriver()
						->createStatement();
					if ($this->modeloDetalleAnteAves->getIdHallazgosAvesCaract() == null){
						$lNegocioHallazgosAvesCaract = new HallazgosAvesCaractLogicaNegocio();
						$sqlInsertar = $this->modeloDetalleAnteAves->guardarSql('hallazgos_aves_caract', $this->modeloDetalleAnteAves->getEsquema());
						$sqlInsertar->columns($lNegocioHallazgosAvesCaract->columnas());
						$sqlInsertar->values($datosCaracteristica, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
						$statement->execute();
						$idCaracteristica = $this->modeloDetalleAnteAves->adapter->driver->getLastGeneratedValue($this->modeloDetalleAnteAves->getEsquema() . '.hallazgos_aves_caract_id_hallazgos_aves_caract_seq');
						if (! $idCaracteristica){
							throw new \Exception('Error al guardar datos de hallazgos en caracteristicas de aves');
						}
					}else{
						$idCaracteristica = $this->modeloDetalleAnteAves->getIdHallazgosAvesCaract();
						$sqlActualizar = $this->modeloDetalleAnteAves->actualizarSql('hallazgos_aves_caract', $this->modeloDetalleAnteAves->getEsquema());
						$sqlActualizar->set($datosCaracteristica);
						$sqlActualizar->where(array(
							'id_hallazgos_aves_caract' => $this->modeloDetalleAnteAves->getIdHallazgosAvesCaract()));
						$sqlActualizar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
						$statement->execute();
					}
				}elseif ($this->modeloDetalleAnteAves->getIdHallazgosAvesCaract() != null){
					$idCaracteristica = '';
				}
				// **********agregar hallazgos de problemas sistemicos
				if ($this->lnFormularioAnteMortem->verificarDatosProbSiste($datos)){
					$datosSistematicos = array(
						'probl_respirat' => ($datos['probl_respirat'] != '') ? $datos['probl_respirat'] : NULL,
						'porcent_probl_respirat' => ($datos['porcent_probl_respirat'] != '') ? $datos['porcent_probl_respirat'] : NULL,
						'probl_nerviosos' => ($datos['probl_nerviosos'] != '') ? $datos['probl_nerviosos'] : NULL,
						'porcent_proble_nerviosos' => ($datos['porcent_proble_nerviosos'] != '') ? $datos['porcent_proble_nerviosos'] : NULL,
						'probl_digestivos' => ($datos['probl_digestivos'] != '') ? $datos['probl_digestivos'] : NULL,
						'porcent_probl_digestivos' => ($datos['porcent_probl_digestivos'] != '') ? $datos['porcent_probl_digestivos'] : NULL);
					$statement = $this->modeloDetalleAnteAves->getAdapter()
						->getDriver()
						->createStatement();
					if ($this->modeloDetalleAnteAves->getIdHallazgosAvesSistematicos() == null){
						$lNegocioHallazgosSistematicos = new HallazgosAvesSistematicosLogicaNegocio();
						$sqlInsertar = $this->modeloDetalleAnteAves->guardarSql('hallazgos_aves_sistematicos', $this->modeloDetalleAnteAves->getEsquema());
						$sqlInsertar->columns($lNegocioHallazgosSistematicos->columnas());
						$sqlInsertar->values($datosSistematicos, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
						$statement->execute();
						$idProbSist = $this->modeloDetalleAnteAves->adapter->driver->getLastGeneratedValue($this->modeloDetalleAnteAves->getEsquema() . '.hallazgos_aves_sistematicos_id_hallazgos_aves_sistematicos_seq');
						if (! $idProbSist){
							throw new \Exception('Error al guardar datos de hallazgos en problemas sistematicos');
						}
					}else{
						$idProbSist = $this->modeloDetalleAnteAves->getIdHallazgosAvesSistematicos();
						$sqlActualizar = $this->modeloDetalleAnteAves->actualizarSql('hallazgos_aves_sistematicos', $this->modeloDetalleAnteAves->getEsquema());
						$sqlActualizar->set($datosSistematicos);
						$sqlActualizar->where(array(
							'id_hallazgos_aves_sistematicos' => $this->modeloDetalleAnteAves->getIdHallazgosAvesSistematicos()));
						$sqlActualizar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
						$statement->execute();
					}
				}else if ($this->modeloDetalleAnteAves->getIdHallazgosAvesSistematicos() != null){
					$idProbSist = '';
				}
				// **********agregar hallazgos de caracteristicas externas
				if ($this->lnFormularioAnteMortem->verificarDatosCaractExt($datos)){
					$datosAvesExternas = array(
						'cabeza_hinchada' => ($datos['cabeza_hinchada'] != '') ? $datos['cabeza_hinchada'] : NULL,
						'porcent_cabeza_hinchada' => ($datos['porcent_cabeza_hinchada'] != '') ? $datos['porcent_cabeza_hinchada'] : NULL,
						'plumas_erizadas' => ($datos['plumas_erizadas'] != '') ? $datos['plumas_erizadas'] : NULL,
						'porcent_plumas_erizadas' => ($datos['porcent_plumas_erizadas'] != '') ? $datos['porcent_plumas_erizadas'] : NULL);

					$statement = $this->modeloDetalleAnteAves->getAdapter()
						->getDriver()
						->createStatement();

					if ($this->modeloDetalleAnteAves->getIdHallazgosAvesExternas() == null){
						$lNegocioHallazgosAvesExternas = new HallazgosAvesExternasLogicaNegocio();
						$sqlInsertar = $this->modeloDetalleAnteAves->guardarSql('hallazgos_aves_externas', $this->modeloDetalleAnteAves->getEsquema());
						$sqlInsertar->columns($lNegocioHallazgosAvesExternas->columnas());
						$sqlInsertar->values($datosAvesExternas, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
						$statement->execute();
						$idCaractExter = $this->modeloDetalleAnteAves->adapter->driver->getLastGeneratedValue($this->modeloDetalleAnteAves->getEsquema() . '.hallazgos_aves_externas_id_hallazgos_aves_externas_seq');
						if (! $idCaractExter){
							throw new \Exception('Error al guardar datos de hallazgos en problemas sistematicos');
						}
					}else{
						$idCaractExter = $this->modeloDetalleAnteAves->getIdHallazgosAvesExternas();
						$sqlActualizar = $this->modeloDetalleAnteAves->actualizarSql('hallazgos_aves_externas', $this->modeloDetalleAnteAves->getEsquema());
						$sqlActualizar->set($datosAvesExternas);
						$sqlActualizar->where(array(
							'id_hallazgos_aves_externas' => $this->modeloDetalleAnteAves->getIdHallazgosAvesExternas()));
						$sqlActualizar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
						$statement->execute();
					}
				}else if ($this->modeloDetalleAnteAves->getIdHallazgosAvesExternas() != null){
					$idCaractExter = '';
				}

				$statement = $this->modeloDetalleAnteAves->getAdapter()
					->getDriver()
					->createStatement();
				$tablaModelo = new DetalleAnteAvesModelo($datos);
				$datosBd = $tablaModelo->getPrepararDatos();
				$datosBd["id_hallazgos_aves_muertas"] = ($idAvesMuertas != '') ? $idAvesMuertas : NULL;
				$datosBd["id_hallazgos_aves_caract"] = ($idCaracteristica != '') ? $idCaracteristica : NULL;
				$datosBd["id_hallazgos_aves_sistematicos"] = ($idProbSist != '') ? $idProbSist : NULL;
				$datosBd["id_hallazgos_aves_externas"] = ($idCaractExter != '') ? $idCaractExter : NULL;
				if ($tablaModelo->getIdDetalleAnteAves() != null && $tablaModelo->getIdDetalleAnteAves() > 0){
					$sqlActualizar = $this->modeloDetalleAnteAves->actualizarSql('detalle_ante_aves', $this->modeloDetalleAnteAves->getEsquema());
					$sqlActualizar->set($datosBd);
					$sqlActualizar->where(array(
						'id_detalle_ante_aves' => $tablaModelo->getIdDetalleAnteAves()));
					$sqlActualizar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
					$statement->execute();
				}else{
					throw new \Exception('Error al guardar datos de con el id del detalle');
				}

				if ($idAvesMuertas == ''){
					$this->borrarRegistro('hallazgos_aves_muertas', 'id_hallazgos_aves_muertas', $this->modeloDetalleAnteAves->getIdHallazgosAvesMuertas());
				}
				if ($idCaracteristica == ''){
					$this->borrarRegistro('hallazgos_aves_caract', 'id_hallazgos_aves_caract', $this->modeloDetalleAnteAves->getIdHallazgosAvesCaract());
				}
				if ($idProbSist == ''){
					$this->borrarRegistro('hallazgos_aves_sistematicos', 'id_hallazgos_aves_sistematicos', $this->modeloDetalleAnteAves->getIdHallazgosAvesSistematicos());
				}
				if ($idCaractExter == ''){
					$this->borrarRegistro('hallazgos_aves_externas', 'id_hallazgos_aves_externas', $this->modeloDetalleAnteAves->getIdHallazgosAvesExternas());
				}
			}else{

				$statement = $this->modeloDetalleAnteAves->getAdapter()
					->getDriver()
					->createStatement();
				$tablaModelo = new DetalleAnteAvesModelo($datos);
				$datosBd = $tablaModelo->getPrepararDatos();
				$datosBd["id_hallazgos_aves_muertas"] = NULL;
				$datosBd["id_hallazgos_aves_caract"] = NULL;
				$datosBd["id_hallazgos_aves_sistematicos"] = NULL;
				$datosBd["id_hallazgos_aves_externas"] = NULL;
				if ($tablaModelo->getIdDetalleAnteAves() != null && $tablaModelo->getIdDetalleAnteAves() > 0){
					$sqlActualizar = $this->modeloDetalleAnteAves->actualizarSql('detalle_ante_aves', $this->modeloDetalleAnteAves->getEsquema());
					$sqlActualizar->set($datosBd);
					$sqlActualizar->where(array(
						'id_detalle_ante_aves' => $tablaModelo->getIdDetalleAnteAves()));
					$sqlActualizar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
					$statement->execute();
				}else{
					throw new \Exception('Error al guardar datos de con el id del detalle');
				}

				$this->borrarRegistro('hallazgos_aves_muertas', 'id_hallazgos_aves_muertas', $this->modeloDetalleAnteAves->getIdHallazgosAvesMuertas());
				$this->borrarRegistro('hallazgos_aves_caract', 'id_hallazgos_aves_caract', $this->modeloDetalleAnteAves->getIdHallazgosAvesCaract());
				$this->borrarRegistro('hallazgos_aves_sistematicos', 'id_hallazgos_aves_sistematicos', $this->modeloDetalleAnteAves->getIdHallazgosAvesSistematicos());
				$this->borrarRegistro('hallazgos_aves_externas', 'id_hallazgos_aves_externas', $this->modeloDetalleAnteAves->getIdHallazgosAvesExternas());
			}

			$proceso->commit();
			return true;
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
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloDetalleAnteAves->borrar($id);
	}

	/**
	 * funcion para borrar
	 */
	public function borrarRegistro($tabla, $id, $idValor){
		if ($idValor != null){
			$statement = $this->modeloDetalleAnteAves->getAdapter()
				->getDriver()
				->createStatement();
			$sqlBorrar = $this->modeloDetalleAnteAves->borrarSql($tabla, $this->modeloDetalleAnteAves->getEsquema());
			$sqlBorrar->where(array(
				$id => $idValor));
			$sqlBorrar->prepareStatement($this->modeloDetalleAnteAves->getAdapter(), $statement);
			$statement->execute();
		}
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleAnteAvesModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleAnteAves->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleAnteAves->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleAnteAves->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada de la especie en aves 
	 *
	 * @return array
	 */
	public function buscarEspecieXDetalleFormularioAves($idDetalleFormulario){
		$consulta = "SELECT
						cf.especie, cf.id_operador_tipo_operacion
					 FROM
						g_centros_faenamiento.detalle_ante_aves daa
						INNER JOIN g_centros_faenamiento.formulario_ante_mortem fam ON daa.id_formulario_ante_mortem = fam.id_formulario_ante_mortem
						INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_centro_faenamiento = fam.id_centro_faenamiento
					 WHERE
						daa.id_detalle_ante_aves = " . $idDetalleFormulario . " ;";

		return $this->modeloDetalleAnteAves->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array ResultSet
	 */
	public function buscarDetalleAnteAves(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleAnteAves->getEsquema() . ". detalle_ante_aves";
		return $this->modeloDetalleAnteAves->ejecutarSqlNativo($consulta);
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
			'tipo_ave',
			'lugar_procedencia',
			'num_csmi',
			'total_aves',
			'promedio_aves',
			'hallazgos',
			'faenamiento_normal',
			'procent_faenamiento_normal',
			'faenamiento_especial',
			'porcent_faenamiento_especial',
			'faenamiento_emergencia',
			'porcent_emergencia',
			'aplazamiento_faenamiento',
			'porcent_aplazamiento_faenamiento',
			'total_faenamiento',
			'observacion',
			'id_hallazgos_aves_muertas',
			'id_hallazgos_aves_caract',
			'id_hallazgos_aves_sistematicos',
			'id_hallazgos_aves_externas');
		return $columnas;
	}
}

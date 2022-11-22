<?php
 /**
 * Lógica del negocio de RevisionCronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo RevisionCronogramaVacacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-10-22
 * @uses    RevisionCronogramaVacacionesLogicaNegocio
 * @package VacacionesPermisos
 * @subpackage Modelos
 */
  namespace Agrodb\VacacionesPermisos\Modelos;
  
  use Agrodb\VacacionesPermisos\Modelos\IModelo;
  use Agrodb\Core\Excepciones\GuardarExcepcion;
 
class RevisionCronogramaVacacionesLogicaNegocio implements IModelo 
{

	 private $modeloRevisionCronogramaVacaciones = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloRevisionCronogramaVacaciones = new RevisionCronogramaVacacionesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{

		try {

		$tablaModelo = new RevisionCronogramaVacacionesModelo($datos);
		
		$procesoIngreso = $this->modeloRevisionCronogramaVacaciones->getAdapter()
                ->getDriver()
                ->getConnection();
            $procesoIngreso->beginTransaction();
		
		$datosBd = $tablaModelo->getPrepararDatos();
		/*echo '<pre>';
		print_r($datos);
		echo '<pre>';*/
		
		if ($tablaModelo->getIdRevisionCronogramaVacacion() != null && $tablaModelo->getIdRevisionCronogramaVacacion() > 0) {
			$idRevisionCronogramaVacacion = $this->modeloRevisionCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdRevisionCronogramaVacacion());
		} else {
			unset($datosBd["id_revision_cronograma_vacacion"]);
			$idRevisionCronogramaVacacion = $this->modeloRevisionCronogramaVacaciones->guardar($datosBd);
		}

		$statement = $this->modeloRevisionCronogramaVacaciones->getAdapter()
		->getDriver()
		->createStatement();

		$idCronogramaVacacion = $datos['id_cronograma_vacacion'];
		$estadoCronogramaVacacion = $datos['estado_cronograma_vacacion'];

		$datosCronogramaVacacion = ['id_cronograma_vacacion' => $idCronogramaVacacion
									, 'estado_cronograma_vacacion' => $estadoCronogramaVacacion ];

		$sqlActualizar = $this->modeloRevisionCronogramaVacaciones->actualizarSql('cronograma_vacaciones', $this->modeloRevisionCronogramaVacaciones->getEsquema());
                    $sqlActualizar->set($datosCronogramaVacacion);
                    $sqlActualizar->where(array('id_cronograma_vacacion' => $idCronogramaVacacion));
                    $sqlActualizar->prepareStatement($this->modeloRevisionCronogramaVacaciones->getAdapter(), $statement);
                    $statement->execute();

		$procesoIngreso->commit();

        return $idRevisionCronogramaVacacion;

        } catch (GuardarExcepcion $ex) {
            $procesoIngreso->rollback();
            throw new \Exception($ex->getMessage());
        }
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloRevisionCronogramaVacaciones->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return RevisionCronogramaVacacionesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloRevisionCronogramaVacaciones->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloRevisionCronogramaVacaciones->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloRevisionCronogramaVacaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarRevisionCronogramaVacaciones()
	{
	$consulta = "SELECT * FROM ".$this->modeloRevisionCronogramaVacaciones->getEsquema().". revision_cronograma_vacaciones";
		 return $this->modeloRevisionCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

}

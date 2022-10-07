<?php
/**
 * Lógica del negocio de GruposSolicitudesModelo
 *
 * Este archivo se complementa con el archivo GruposSolicitudesControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-14
 * @uses GruposSolicitudesLogicaNegocio
 * @package RevisionFormularios
 * @subpackage Modelos
 */
namespace Agrodb\RevisionFormularios\Modelos;

use Agrodb\RevisionFormularios\Modelos\IModelo;

class GruposSolicitudesLogicaNegocio implements IModelo{

	private $modeloGruposSolicitudes = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloGruposSolicitudes = new GruposSolicitudesModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new GruposSolicitudesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdGrupo() != null && $tablaModelo->getIdGrupo() > 0){
			return $this->modeloGruposSolicitudes->actualizar($datosBd, $tablaModelo->getIdGrupo());
		}else{
			unset($datosBd["id_grupo"]);
			return $this->modeloGruposSolicitudes->guardar($datosBd);
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
		$this->modeloGruposSolicitudes->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return GruposSolicitudesModelo
	 */
	public function buscar($id){
		return $this->modeloGruposSolicitudes->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloGruposSolicitudes->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloGruposSolicitudes->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarGruposSolicitudes(){
		$consulta = "SELECT * FROM " . $this->modeloGruposSolicitudes->getEsquema() . ". grupos_solicitudes";
		return $this->modeloGruposSolicitudes->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Devuelve las columnas a ser insertadas.
	 *
	 * @return String
	 */
	public function columnas(){
		$columnas = array(
			'id_grupo',
			'id_solicitud',
			'estado');
		
		return $columnas;
	}
}

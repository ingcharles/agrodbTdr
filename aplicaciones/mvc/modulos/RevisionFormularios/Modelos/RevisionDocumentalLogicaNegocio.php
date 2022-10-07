<?php
/**
 * Lógica del negocio de RevisionDocumentalModelo
 *
 * Este archivo se complementa con el archivo RevisionDocumentalControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-14
 * @uses RevisionDocumentalLogicaNegocio
 * @package RevisionFormularios
 * @subpackage Modelos
 */
namespace Agrodb\RevisionFormularios\Modelos;

use Agrodb\RevisionFormularios\Modelos\IModelo;

class RevisionDocumentalLogicaNegocio implements IModelo{

	private $modeloRevisionDocumental = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloRevisionDocumental = new RevisionDocumentalModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new RevisionDocumentalModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRevisionDocumental() != null && $tablaModelo->getIdRevisionDocumental() > 0){
			return $this->modeloRevisionDocumental->actualizar($datosBd, $tablaModelo->getIdRevisionDocumental());
		}else{
			unset($datosBd["id_revision_documental"]);
			return $this->modeloRevisionDocumental->guardar($datosBd);
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
		$this->modeloRevisionDocumental->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return RevisionDocumentalModelo
	 */
	public function buscar($id){
		return $this->modeloRevisionDocumental->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloRevisionDocumental->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloRevisionDocumental->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarRevisionDocumental(){
		$consulta = "SELECT * FROM " . $this->modeloRevisionDocumental->getEsquema() . ". revision_documental";
		return $this->modeloRevisionDocumental->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Devuelve las columnas a ser insertadas.
	 *
	 * @return String
	 */
	public function columnas(){
		$columnas = array(
			'id_grupo',
			'identificador_inspector',
			'fecha_inspeccion',
			'observacion',
			'estado',
			'orden',
		    'ruta_archivo_documental'
		);
		
		return $columnas;
	}

}

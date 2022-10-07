<?php
 /**
 * Lógica del negocio de PartidasArancelariasModelo
 *
 * Este archivo se complementa con el archivo PartidasArancelariasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    PartidasArancelariasLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class PartidasArancelariasLogicaNegocio implements IModelo 
{

	 private $modeloPartidasArancelarias = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloPartidasArancelarias = new PartidasArancelariasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new PartidasArancelariasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPartidaArancelaria() != null && $tablaModelo->getIdPartidaArancelaria() > 0) {
		return $this->modeloPartidasArancelarias->actualizar($datosBd, $tablaModelo->getIdPartidaArancelaria());
		} else {
		unset($datosBd["id_partida_arancelaria"]);
		return $this->modeloPartidasArancelarias->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloPartidasArancelarias->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return PartidasArancelariasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloPartidasArancelarias->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloPartidasArancelarias->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloPartidasArancelarias->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarPartidasArancelarias()
	{
	$consulta = "SELECT * FROM ".$this->modeloPartidasArancelarias->getEsquema().". partidas_arancelarias";
		 return $this->modeloPartidasArancelarias->ejecutarSqlNativo($consulta);
	}

}

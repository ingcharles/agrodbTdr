<?php
 /**
 * Lógica del negocio de TiposAlertaModelo
 *
 * Este archivo se complementa con el archivo TiposAlertaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    TiposAlertaLogicaNegocio
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilExternos\Modelos;
  
  use Agrodb\AplicacionMovilExternos\Modelos\IModelo;
 
class TiposAlertaLogicaNegocio implements IModelo 
{

	 private $modeloTiposAlerta = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloTiposAlerta = new TiposAlertaModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new TiposAlertaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdTipoAlerta() != null && $tablaModelo->getIdTipoAlerta() > 0) {
		return $this->modeloTiposAlerta->actualizar($datosBd, $tablaModelo->getIdTipoAlerta());
		} else {
		unset($datosBd["id_tipo_alerta"]);
		return $this->modeloTiposAlerta->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloTiposAlerta->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return TiposAlertaModelo
	*/
	public function buscar($id)
	{
		return $this->modeloTiposAlerta->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloTiposAlerta->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloTiposAlerta->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarTiposAlerta()
	{
	$consulta = "SELECT * FROM ".$this->modeloTiposAlerta->getEsquema().". tipos_alerta";
		 return $this->modeloTiposAlerta->ejecutarSqlNativo($consulta);
	}

}

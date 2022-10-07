<?php
 /**
 * Lógica del negocio de CodigosPoaModelo
 *
 * Este archivo se complementa con el archivo CodigosPoaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-01-10
 * @uses    CodigosPoaLogicaNegocio
 * @package RegistroOperador
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\RegistroOperador\Modelos\IModelo;
 
class CodigosPoaLogicaNegocio implements IModelo 
{

	 private $modeloCodigosPoa = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCodigosPoa = new CodigosPoaModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new CodigosPoaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCodigoPoa() != null && $tablaModelo->getIdCodigoPoa() > 0) {
		return $this->modeloCodigosPoa->actualizar($datosBd, $tablaModelo->getIdCodigoPoa());
		} else {
		unset($datosBd["id_codigo_poa"]);
		return $this->modeloCodigosPoa->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCodigosPoa->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CodigosPoaModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCodigosPoa->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCodigosPoa->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCodigosPoa->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCodigoPoaPorOperador($arrayParametros)
	{
	    
	    $identificadorOperador = $arrayParametros['identificadorOperador'];
	    $codigoPoa = $arrayParametros['codigoPoa'];
	    
        $consulta = "SELECT 
                        identificador_operador
                        , codigo_poa
                    FROM 
                        g_operadores.codigos_poa 
                    WHERE 
                        identificador_operador = '" . $identificadorOperador . "'
                        and codigo_poa = '" . $codigoPoa . "'";
        
		 return $this->modeloCodigosPoa->ejecutarSqlNativo($consulta);
	}

}

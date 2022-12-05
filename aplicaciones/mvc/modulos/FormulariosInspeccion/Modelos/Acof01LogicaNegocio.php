<?php
 /**
 * Lógica del negocio de Acof01Modelo
 *
 * Este archivo se complementa con el archivo Acof01Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-04-05
 * @uses    Acof01LogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
 
class Acof01LogicaNegocio implements IModelo 
{

	 private $modeloAcof01 = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAcof01 = new Acof01Modelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Acof01Modelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloAcof01->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloAcof01->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAcof01->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Acof01Modelo
	*/
	public function buscar($id)
	{
		return $this->modeloAcof01->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAcof01->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAcof01->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAcof01()
	{
	$consulta = "SELECT * FROM ".$this->modeloAcof01->getEsquema().". acof01";
		 return $this->modeloAcof01->ejecutarSqlNativo($consulta);
	}
     /**
         * Busca una lista de acuerdo a los parámetros <params> enviados.
         *
         * @return array|ResultSet
         */
		public function actualizarEstadoInspeccionAcoPorIdSolicitud($idSolicitud)
        {
            $consulta = "UPDATE
                    	f_inspeccion.acof01
                    SET
                    	estado_registro = 'Inactivo'
                    WHERE
                    	id_solicitud = " . $idSolicitud . "
                    	and estado_registro = 'Activo';";
            
            return $this->modeloAcof01->ejecutarSqlNativo($consulta);
        }
        	
}

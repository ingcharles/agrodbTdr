<?php
 /**
 * Lógica del negocio de Bpaf01Modelo
 *
 * Este archivo se complementa con el archivo Bpaf01Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-23
 * @uses    Bpaf01LogicaNegocio
 * @package AplicacionMovilBPA
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
 
class Bpaf01LogicaNegocio implements IModelo 
{

	 private $modeloBpaf01 = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloBpaf01 = new Bpaf01Modelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Bpaf01Modelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
			return $this->modeloBpaf01->actualizar($datosBd, $tablaModelo->getId());
		} else {
			unset($datosBd["id_inspeccion_bpa"]);
			return $this->modeloBpaf01->guardar($datosBd);
		}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloBpaf01->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Bpaf01Modelo
	*/
	public function buscar($id)
	{
		return $this->modeloBpaf01->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloBpaf01->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloBpaf01->buscarLista($where, $order, $count, $offset);
	}
         /**
         * Busca una lista de acuerdo a los parámetros <params> enviados.
         *
         * @return array|ResultSet
         */
	public function actualizarEstadoInspeccionBpaPorIdSolicitud($idSolicitud)
        {
            $consulta = "UPDATE
                    	f_inspeccion.bpaf01
                    SET
                    	estado_registro = 'Inactivo'
                    WHERE
                    	id_solicitud = " . $idSolicitud . "
                    	and estado_registro = 'Activo';";
            
            return $this->modeloBpaf01->ejecutarSqlNativo($consulta);
        }
        	   

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarBpaf01()
	{
	$consulta = "SELECT * FROM ".$this->modeloBpaf01->getEsquema().". bpaf01";
		 return $this->modeloBpaf01->ejecutarSqlNativo($consulta);
	}

}

<?php
 /**
 * Lógica del negocio de Mdtf01Modelo
 *
 * Este archivo se complementa con el archivo Mdtf01Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-23
 * @uses    Mdtf01LogicaNegocio
 * @package AplicacionMovilBPA
 * @subpackage Modelos
 */
namespace Agrodb\FormulariosInspeccion\Modelos;
  
use Agrodb\FormulariosInspeccion\Modelos\IModelo;
 
class Mdtf01LogicaNegocio implements IModelo 
{

	 private $modeloMdtf01 = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloMdtf01 = new Mdtf01Modelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Mdtf01Modelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloMdtf01->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloMdtf01->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloMdtf01->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Mdtf01Modelo
	*/
	public function buscar($id)
	{
		return $this->modeloMdtf01->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloMdtf01->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloMdtf01->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarMdtf01()
	{
	$consulta = "SELECT * FROM ".$this->modeloMdtf01->getEsquema().". mdtf01";
		 return $this->modeloMdtf01->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Actualiza el estado de las inspecciones anteriores
	 * @return array|ResultSet
	 */
	public function actualizarEstadoInspeccionMdtPorIdSolicitud($idSolicitud)
        {
            $consulta = "UPDATE
                    	f_inspeccion.mdtf01
                    SET
                    	estado_registro = 'Inactivo'
                    WHERE
                    	id_solicitud = " . $idSolicitud . "
                    	and estado_registro = 'Activo';";
            
            return $this->modeloMdtf01->ejecutarSqlNativo($consulta);
        }


}

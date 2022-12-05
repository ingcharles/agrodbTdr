<?php
 /**
 * Lógica del negocio de ResponsablesCertificadosModelo
 *
 * Este archivo se complementa con el archivo ResponsablesCertificadosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-23
 * @uses    ResponsablesCertificadosLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class ResponsablesCertificadosLogicaNegocio implements IModelo 
{

	 private $modeloResponsablesCertificados = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloResponsablesCertificados = new ResponsablesCertificadosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ResponsablesCertificadosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdResponsableCertificado() != null && $tablaModelo->getIdResponsableCertificado() > 0) {
		return $this->modeloResponsablesCertificados->actualizar($datosBd, $tablaModelo->getIdResponsableCertificado());
		} else {
		unset($datosBd["id_responsable_certificado"]);
		return $this->modeloResponsablesCertificados->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloResponsablesCertificados->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ResponsablesCertificadosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloResponsablesCertificados->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloResponsablesCertificados->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloResponsablesCertificados->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarResponsablesCertificados()
	{
	$consulta = "SELECT * FROM ".$this->modeloResponsablesCertificados->getEsquema().". responsables_certificados";
		 return $this->modeloResponsablesCertificados->ejecutarSqlNativo($consulta);
	}

	
	public function obtenerFirmasResponsablePorProvincia($nombreProvincia, $idArea = 'DE'){
		
		$consulta = "SELECT
						*
  					FROM
						g_catalogos.responsables_certificados
					WHERE 
						'$idArea' = ANY (id_area)
						and nombre_provincia ilike '%$nombreProvincia%' 
						and estado = 'Activo';";
		
		$res = $this->modeloResponsablesCertificados->ejecutarSqlNativo($consulta);
		
		return $res;
	}
}

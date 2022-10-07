<?php
/**
 * Lógica del negocio de CertificadoClvModelo
 *
 * Este archivo se complementa con el archivo CertificadoClvControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses CertificadoClvLogicaNegocio
 * @package CertificadoLibreVenta
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoLibreVenta\Modelos;

use Agrodb\CertificadoLibreVenta\Modelos\IModelo;

class CertificadoClvLogicaNegocio implements IModelo{

	private $modeloCertificadoClv = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloCertificadoClv = new CertificadoClvModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new CertificadoClvModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdClv() != null && $tablaModelo->getIdClv() > 0){
			return $this->modeloCertificadoClv->actualizar($datosBd, $tablaModelo->getIdClv());
		}else{
			unset($datosBd["id_clv"]);
			return $this->modeloCertificadoClv->guardar($datosBd);
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
		$this->modeloCertificadoClv->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return CertificadoClvModelo
	 */
	public function buscar($id){
		return $this->modeloCertificadoClv->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloCertificadoClv->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloCertificadoClv->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarCertificadoClv(){
		$consulta = "SELECT * FROM " . $this->modeloCertificadoClv->getEsquema() . ". certificado_clv";
		return $this->modeloCertificadoClv->ejecutarSqlNativo($consulta);
	}
}

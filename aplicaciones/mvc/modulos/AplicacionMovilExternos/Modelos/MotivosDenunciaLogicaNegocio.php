<?php
/**
 * Lógica del negocio de MotivosDenunciaModelo
 *
 * Este archivo se complementa con el archivo MotivosDenunciaControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-06-06
 * @uses MotivosDenunciaLogicaNegocio
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
namespace Agrodb\AplicacionMovilExternos\Modelos;

use Agrodb\AplicacionMovilExternos\Modelos\IModelo;

class MotivosDenunciaLogicaNegocio implements IModelo{

	private $modeloMotivosDenuncia = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloMotivosDenuncia = new MotivosDenunciaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new MotivosDenunciaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdMotivo() != null && $tablaModelo->getIdMotivo() > 0){
			return $this->modeloMotivosDenuncia->actualizar($datosBd, $tablaModelo->getIdMotivo());
		}else{
			unset($datosBd["id_motivo"]);
			return $this->modeloMotivosDenuncia->guardar($datosBd);
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
		$this->modeloMotivosDenuncia->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return MotivosDenunciaModelo
	 */
	public function buscar($id){
		return $this->modeloMotivosDenuncia->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloMotivosDenuncia->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloMotivosDenuncia->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarMotivosDenuncia(){
		$consulta = "SELECT * FROM " . $this->modeloMotivosDenuncia->getEsquema() . ". motivos_denuncia";
		return $this->modeloMotivosDenuncia->ejecutarSqlNativo($consulta);
	}
}

<?php
/**
 * Lógica del negocio de DocumentoAdjuntoModelo
 *
 * Este archivo se complementa con el archivo DocumentoAdjuntoControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses DocumentoAdjuntoLogicaNegocio
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\RegistroControlDocumentos\Modelos\IModelo;

class DocumentoAdjuntoLogicaNegocio implements IModelo{

	private $modeloDocumentoAdjunto = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDocumentoAdjunto = new DocumentoAdjuntoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DocumentoAdjuntoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDocumentoAdjunto() != null && $tablaModelo->getIdDocumentoAdjunto() > 0){
			return $this->modeloDocumentoAdjunto->actualizar($datosBd, $tablaModelo->getIdDocumentoAdjunto());
		}else{
			unset($datosBd["id_documento_adjunto"]);
			return $this->modeloDocumentoAdjunto->guardar($datosBd);
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
		$this->modeloDocumentoAdjunto->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DocumentoAdjuntoModelo
	 */
	public function buscar($id){
		return $this->modeloDocumentoAdjunto->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDocumentoAdjunto->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDocumentoAdjunto->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDocumentoAdjunto(){
		$consulta = "SELECT * FROM " . $this->modeloDocumentoAdjunto->getEsquema() . ". documento_adjunto";
		return $this->modeloDocumentoAdjunto->ejecutarSqlNativo($consulta);
	}
}

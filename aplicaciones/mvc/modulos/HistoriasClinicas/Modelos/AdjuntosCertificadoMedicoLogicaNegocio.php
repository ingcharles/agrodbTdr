<?php
/**
 * Lógica del negocio de AdjuntosCertificadoMedicoModelo
 *
 * Este archivo se complementa con el archivo AdjuntosCertificadoMedicoControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses AdjuntosCertificadoMedicoLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class AdjuntosCertificadoMedicoLogicaNegocio implements IModelo{

	private $modeloAdjuntosCertificadoMedico = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloAdjuntosCertificadoMedico = new AdjuntosCertificadoMedicoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new AdjuntosCertificadoMedicoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAdjuntosCertificadoMedico() != null && $tablaModelo->getIdAdjuntosCertificadoMedico() > 0){
			return $this->modeloAdjuntosCertificadoMedico->actualizar($datosBd, $tablaModelo->getIdAdjuntosCertificadoMedico());
		}else{
			unset($datosBd["id_adjuntos_certificado_medico"]);
			return $this->modeloAdjuntosCertificadoMedico->guardar($datosBd);
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
		$this->modeloAdjuntosCertificadoMedico->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AdjuntosCertificadoMedicoModelo
	 */
	public function buscar($id){
		return $this->modeloAdjuntosCertificadoMedico->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloAdjuntosCertificadoMedico->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloAdjuntosCertificadoMedico->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAdjuntosCertificadoMedico(){
		$consulta = "SELECT * FROM " . $this->modeloAdjuntosCertificadoMedico->getEsquema() . ". adjuntos_certificado_medico";
		return $this->modeloAdjuntosCertificadoMedico->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_certificado_medico',
			'archivo_adjunto');
		return $columnas;
	}
}

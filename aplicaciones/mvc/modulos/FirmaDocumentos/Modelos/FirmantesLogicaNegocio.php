<?php
/**
 * Lógica del negocio de FirmantesModelo
 *
 * Este archivo se complementa con el archivo FirmantesControlador.
 *
 * @author AGROCALIDAD
 * @date    2022-01-14
 * @uses FirmantesLogicaNegocio
 * @package FirmaDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\FirmaDocumentos\Modelos;

use Agrodb\FirmaDocumentos\Modelos\IModelo;

class FirmantesLogicaNegocio implements IModelo{

	private $modeloFirmantes = null;
	private $lNegocioDocumentosLogicaNegocio = null;
	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloFirmantes = new FirmantesModelo();
		$this->lNegocioDocumentosLogicaNegocio = new DocumentosLogicaNegocio();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new FirmantesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdentificador() != null && $tablaModelo->getIdentificador() > 0){
			return $this->modeloFirmantes->actualizar($datosBd, $tablaModelo->getIdentificador());
		}else{
			unset($datosBd["identificador"]);
			return $this->modeloFirmantes->guardar($datosBd);
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
		$this->modeloFirmantes->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return FirmantesModelo
	 */
	public function buscar($id){
		return $this->modeloFirmantes->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloFirmantes->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloFirmantes->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarFirmantes(){
		$consulta = "SELECT * FROM " . $this->modeloFirmantes->getEsquema() . ". firmantes";
		return $this->modeloFirmantes->ejecutarSqlNativo($consulta);
	}

	
	

	public function ingresoFirmaDocumento($parametrosFirma) {

		$arrayParametros = array('identificador' => $parametrosFirma['identificador']);
    	$firma = $this->modeloFirmantes->buscarLista($arrayParametros);
    	
    	if(count($firma) > 0){
    		$this->lNegocioDocumentosLogicaNegocio->crearDocumentoParaFirmar($parametrosFirma);
    	}
    }
}

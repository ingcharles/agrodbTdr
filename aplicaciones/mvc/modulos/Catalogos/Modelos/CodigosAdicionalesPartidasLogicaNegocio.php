<?php
/**
 * Lógica del negocio de CodigosAdicionalesPartidasModelo
 *
 * Este archivo se complementa con el archivo CodigosAdicionalesPartidasControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-06
 * @uses CodigosAdicionalesPartidasLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class CodigosAdicionalesPartidasLogicaNegocio implements IModelo{

	private $modeloCodigosAdicionalesPartidas = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloCodigosAdicionalesPartidas = new CodigosAdicionalesPartidasModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new CodigosAdicionalesPartidasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdProducto() != null && $tablaModelo->getIdProducto() > 0){
			return $this->modeloCodigosAdicionalesPartidas->actualizar($datosBd, $tablaModelo->getIdProducto());
		}else{
			unset($datosBd["id_producto"]);
			return $this->modeloCodigosAdicionalesPartidas->guardar($datosBd);
		}
	}
	
	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardarProductoRIA(Array $datos)
	{
	    $tablaModelo = new CodigosAdicionalesPartidasModelo($datos);
	    $datosBd = $tablaModelo->getPrepararDatos();
	    
	    return $this->modeloCodigosAdicionalesPartidas->guardar($datosBd);
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloCodigosAdicionalesPartidas->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return CodigosAdicionalesPartidasModelo
	 */
	public function buscar($id){
		return $this->modeloCodigosAdicionalesPartidas->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloCodigosAdicionalesPartidas->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloCodigosAdicionalesPartidas->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarCodigosAdicionalesPartidas(){
		$consulta = "SELECT * FROM " . $this->modeloCodigosAdicionalesPartidas->getEsquema() . ". codigos_adicionales_partidas";
		return $this->modeloCodigosAdicionalesPartidas->ejecutarSqlNativo($consulta);
	}

	/**
	 * Generar un combo de codigos adicionales partida para proceso de rectificacion de importaciones VUE
	 *
	 * @return ResultSet
	 */
	public function comboCodigosAdicionalesPartida($idProducto, $codigoComplementarioSuplementario){
		
		$selectCodigoComplementarioSuplementario = '';
		
		$codigosPartida = $this->buscarLista(array('id_producto'=>$idProducto));

		foreach ($codigosPartida as $codigo){
			if ($codigoComplementarioSuplementario == $codigo['codigo_complementario'].$codigo['codigo_suplementario']){
				$selectCodigoComplementarioSuplementario .= '<option value="' . $codigo['codigo_complementario'].$codigo['codigo_suplementario']. '" selected>' . $codigo['codigo_complementario'].$codigo['codigo_suplementario'] . '</option>';
			}else{
				$selectCodigoComplementarioSuplementario .= '<option value="' . $codigo['codigo_complementario'].$codigo['codigo_suplementario'] . '">' . $codigo['codigo_complementario'].$codigo['codigo_suplementario'] . '</option>';
			}
		}

		return $selectCodigoComplementarioSuplementario;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Elimina todos los registros vinculados a un producto
	 *
	 * @return array|ResultSet
	 */
	public function borrarTodo($idProducto)
	{
	    $consulta = "   DELETE FROM
                            g_catalogos.codigos_adicionales_partidas
                        WHERE
                            id_producto = $idProducto; ";
	    
	    return $this->modeloCodigosAdicionalesPartidas->ejecutarSqlNativo($consulta);
	}
}

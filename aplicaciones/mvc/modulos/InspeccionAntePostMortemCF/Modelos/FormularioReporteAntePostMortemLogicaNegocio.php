<?php
/**
 * Lógica del negocio de DetalleAnteAnimalesModelo
 *
 * Este archivo se complementa con el archivo DetalleAnteAnimalesControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-05-27
 * @uses DetalleAnteAnimalesLogicaNegocio
 * @package InspeccionAntePostMortemCF
 * @subpackage Modelos
 */
namespace Agrodb\InspeccionAntePostMortemCF\Modelos;

class FormularioReporteAntePostMortemLogicaNegocio {

	private $modeloDetalleAnteAnimales = null;
	private $lnFormularioAnteMortem = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleAnteAnimales = new DetalleAnteAnimalesModelo();
		$this->lnFormularioAnteMortem = new FormularioAnteMortemLogicaNegocio();
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada de la especie en animales
	 *
	 * @return array
	 */
	public function buscarEspecieXDetalleFormularioAnimales($idDetalleFormulario){
		$consulta = "SELECT
						cf.especie, cf.id_operador_tipo_operacion
					 FROM
						g_centros_faenamiento.detalle_ante_animales daa
						INNER JOIN g_centros_faenamiento.formulario_ante_mortem fam ON daa.id_formulario_ante_mortem = fam.id_formulario_ante_mortem
						INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_centro_faenamiento = fam.id_centro_faenamiento
					 WHERE
						daa.id_detalle_ante_animales = " . $idDetalleFormulario . " ;";
		
		return $this->modeloDetalleAnteAnimales->ejecutarSqlNativo($consulta);
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloDetalleAnteAnimales->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleAnteAnimalesModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleAnteAnimales->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleAnteAnimales->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleAnteAnimales->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleAnteAnimales(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleAnteAnimales->getEsquema() . ". detalle_ante_animales";
		return $this->modeloDetalleAnteAnimales->ejecutarSqlNativo($consulta);
	}

	/**
	 * funcion para borrar
	 */
	public function borrarRegistro($tabla, $id, $idValor){
		if ($idValor != null){
			$statement = $this->modeloDetalleAnteAnimales->getAdapter()
			->getDriver()
			->createStatement();
			$sqlBorrar = $this->modeloDetalleAnteAnimales->borrarSql($tabla, $this->modeloDetalleAnteAnimales->getEsquema());
			$sqlBorrar->where(array(
				$id => $idValor));
			$sqlBorrar->prepareStatement($this->modeloDetalleAnteAnimales->getAdapter(), $statement);
			$statement->execute();
		}
	}
	
}

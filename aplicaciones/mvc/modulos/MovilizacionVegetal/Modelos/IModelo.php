<?php

 /**
 * Plantilla de métodos de la clase modelo a implementar en la lógica del negocio
 *
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2019-09-02
 * @uses      IModelo
 * @package   MovilizacionVegetal
 * @subpackage Modelos
 */
  namespace Agrodb\MovilizacionVegetal\Modelos;
  
 
interface IModelo
{

	/**
	* Guarda el registro actual
	* @param array $datos
	*/
	public function guardar(Array $datos);

	/**
	* Borra el registro actual
	* @param int $id
	*/
	public function borrar($id);

	/**
	* Busca todos los registros
	*
	*/
	public function buscarTodo();

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null);

}

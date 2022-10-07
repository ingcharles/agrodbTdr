<?php

/**
 * Implementa transacciones sin PDO en postgres.
 *
 * Derivada de la clase Conexion.
 *
 * @version 1.0
 * @author Samuel Villarreal
 */
class Transaccion extends Conexion
{
	public function Begin(){
		$this->ejecutarConsulta('BEGIN');
	}

	public function Commit(){
		$this->ejecutarConsulta('COMMIT');
	}

	public function Rollback(){
		$this->ejecutarConsulta('ROLLBACK');		
	}
}
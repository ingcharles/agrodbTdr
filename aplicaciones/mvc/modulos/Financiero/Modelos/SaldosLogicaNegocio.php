<?php
 /**
 * Lógica del negocio de  SaldosModelo
 *
 * Este archivo se complementa con el archivo   SaldosControlador.
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses       SaldosLogicaNegocio
 * @package financiero
 * @subpackage Modelos
 */
  namespace Agrodb\Financiero\Modelos;
  
  use Agrodb\Financiero\Modelos\IModelo;
 
class SaldosLogicaNegocio implements IModelo 
{

	 private $modeloSaldo = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	    $this->modeloSaldo = new SaldosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new SaldosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdSaldo() != null && $tablaModelo->getIdSaldo() > 0) {
		    return $this->modeloSaldo->actualizar($datosBd, $tablaModelo->getIdSaldo());
		} else {
		unset($datosBd["id_saldo"]);
		return $this->modeloSaldo->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
	    $this->modeloSaldo->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SaldosModelo
	*/
	public function buscar($id)
	{
	    return $this->modeloSaldo->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
	    return $this->modeloSaldo->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
	    return $this->modeloSaldo->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarSaldos()
	{
	$consulta = "SELECT * FROM ".$this->modelo->getEsquema().". saldos";
	   return $this->modeloSaldo->ejecutarSqlNativo($consulta);
	}
	
	public function buscarSaldoUsuarioConsumoFacturas($arrayParametros){
	    
	    $consulta = "SELECT
                    	id_saldo, saldo_disponible, valor_consumo, cantidad_egreso, cantidad_ingreso, razon_social, identificador, direccion
                    FROM
                    	g_financiero.saldos s, (SELECT 
                                                    sum(valor_egreso) as valor_consumo, count(valor_egreso) cantidad_egreso, count(valor_ingreso) cantidad_ingreso, identificador_operador 
                                                FROM 
                                                    g_financiero.saldos s2 
                                                WHERE 
                                                    s2.identificador_operador = '".$arrayParametros['identificador']."' 
													and s2.fecha_deposito >= '".$arrayParametros['fecha_inicio']."'
													and s2.fecha_deposito <= DATE('".$arrayParametros['fecha_fin']."') + INTERVAL '24 hour' 
                                                GROUP BY 
                                                    identificador_operador) as t, g_financiero.clientes c
                    WHERE
                    	s.identificador_operador = t.identificador_operador
                    	and t.identificador_operador = c.identificador
                    	and s.identificador_operador = '".$arrayParametros['identificador']."'
				
                    	and s.id_saldo = (SELECT
                    			max(s1.id_saldo)
                    		FROM
                    			g_financiero.saldos s1
                    		WHERE
								s1.identificador_operador = '".$arrayParametros['identificador']."')";
							   
	    return $this->modeloSaldo->ejecutarSqlNativo($consulta);
	    
	}
	
	public function buscarSaldoUsuario($arrayParametros){
	    
	    $consulta = "SELECT
                    	id_saldo, saldo_disponible, '0' as valor_consumo, '0' as cantidad_factura, razon_social, identificador, direccion
                    FROM
                    	g_financiero.saldos s, g_financiero.clientes c
                    WHERE
                    	s.identificador_operador = c.identificador
                    	and s.identificador_operador = '".$arrayParametros['identificador']."'
                    	and s.id_saldo = (SELECT
                    			max(s1.id_saldo)
                    		FROM
                    			g_financiero.saldos s1
                    		WHERE
                    			s1.identificador_operador = '".$arrayParametros['identificador']."')";
	    
	    return $this->modeloSaldo->ejecutarSqlNativo($consulta);
	    
	}

	/**
     * Método para obtener el total del saldo disponible del usaurio
     */
	public function buscarSaldoUsuarioTotal($arrayParametros){

		$consulta ="SELECT
						s.saldo_disponible
					FROM
						g_financiero.saldos s
					WHERE
						s.identificador_operador = '".$arrayParametros['identificador']."'
						and s.id_saldo = (SELECT max(s.id_saldo) FROM g_financiero.saldos s WHERE s.identificador_operador = '".$arrayParametros['identificador']."');";
		return $this->modeloSaldo->ejecutarSqlNativo($consulta);
	}

	/**
     * Método para obtener el detalle de los ingresos o egresos que componen el saldo del usuario, en un rango de fechas determinado
     */
	public function buscarFacturasConSaldoUsuario($arrayParametros){
	    
	    $consulta="SELECT 
                    	id_saldo, numero_establecimiento, (case when valor_ingreso >= 0.00 then 'COMP' else 'FAC' end) tipo, punto_emision, numero_factura, factura, fecha_facturacion, valor_ingreso, valor_egreso, saldo_disponible
                    FROM 
                    	g_financiero.orden_pago op INNER JOIN g_financiero.saldos s ON op.id_pago = s.id_pago
                    WHERE
                    	op.identificador_operador = '".$arrayParametros['identificador']."'
                        and estado = 4 
                        and estado_sri = 'FINALIZADO'
						and s.fecha_deposito >= '".$arrayParametros['fecha_inicio']."'
						and s.fecha_deposito <= DATE('".$arrayParametros['fecha_fin']."') + INTERVAL '24 hour' 
                    ORDER BY 
						id_saldo desc
						offset ". $arrayParametros['inicio']." row
						fetch next 30 rows only;";
	    
	    return $this->modeloSaldo->ejecutarSqlNativo($consulta);
	    
	}

}

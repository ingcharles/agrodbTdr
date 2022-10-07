<?php
/**
 * Lógica del negocio de DetalleSocializacionModelo
 *
 * Este archivo se complementa con el archivo DetalleSocializacionControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses DetalleSocializacionLogicaNegocio
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\RegistroControlDocumentos\Modelos\IModelo;

class DetalleSocializacionLogicaNegocio implements IModelo{

	private $modeloDetalleSocializacion = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleSocializacion = new DetalleSocializacionModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleSocializacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleSocializacion() != null && $tablaModelo->getIdDetalleSocializacion() > 0){
			return $this->modeloDetalleSocializacion->actualizar($datosBd, $tablaModelo->getIdDetalleSocializacion());
		}else{
			unset($datosBd["id_detalle_socializacion"]);
			return $this->modeloDetalleSocializacion->guardar($datosBd);
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
		$this->modeloDetalleSocializacion->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleSocializacionModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleSocializacion->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleSocializacion->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleSocializacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleSocializacion(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleSocializacion->getEsquema() . ". detalle_socializacion";
		return $this->modeloDetalleSocializacion->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleSocializacionDestinatarrio($arrayParemetros){
		$busqueda = 'true ';
		if (array_key_exists('id_registro_sgc', $arrayParemetros)){
			$busqueda .= "  and id_registro_sgc = '" . $arrayParemetros['id_registro_sgc'] . "'";
		}
		if (array_key_exists('identificador_asignante', $arrayParemetros)){
			$busqueda .= "  and identifcador_asignante = '" . $arrayParemetros['identificador_asignante'] . "'";
		}
		if (array_key_exists('id_detalle_destinatario', $arrayParemetros)){
			$busqueda .= "  and dd.id_detalle_destinatario = '" . $arrayParemetros['id_detalle_destinatario'] . "'";
		}
		$consulta = "
			SELECT * FROM
			 " . $this->modeloDetalleSocializacion->getEsquema() . ". detalle_socializacion ds
             inner join " . $this->modeloDetalleSocializacion->getEsquema() . ". detalle_destinatario dd on dd.id_detalle_destinatario = ds.id_detalle_destinatario
             	where 
					" . $busqueda . " order by 1;
					";
		return $this->modeloDetalleSocializacion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarSocializacionesPendientes($arrayParametros){
	    
	    $consulta = "SELECT 
                    	--r.*,
                    	dd.*/*,
                    	ds.*/
                    FROM 
                    	g_registro_control_documentos.detalle_destinatario dd
                    	/*left outer JOIN  g_registro_control_documentos.detalle_socializacion ds on 
                    		ds.id_detalle_destinatario = dd.id_detalle_destinatario*/
                    	INNER JOIN g_registro_control_documentos.registro_sgc r ON r.id_registro_sgc = dd.id_registro_sgc
                    WHERE
                    	dd.estado='No atendido' and
                    	dd.estado_socializacion in ('registrado', 'pendiente') and
                    	/*ds.estado = 'creado' and
                        (ds.estado_socializar in ('temporal') or 
                    	 ds.estado_socializar is null )and*/
                    	r.id_registro_sgc='" . $arrayParametros['id_registro_sgc'] . "'";
	    
	    return $this->modeloDetalleSocializacion->ejecutarSqlNativo($consulta);
	}
}
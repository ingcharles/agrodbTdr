<?php
 /**
 * Lógica del negocio de TipoDocumentoModelo
 *
 * Este archivo se complementa con el archivo TipoDocumentoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    TipoDocumentoLogicaNegocio
 * @package ProcesosAdministrativosJuridico
 * @subpackage Modelos
 */
  namespace Agrodb\ProcesosAdministrativosJuridico\Modelos;
  
 
class TipoDocumentoLogicaNegocio implements IModelo 
{

	 private $modeloTipoDocumento = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloTipoDocumento = new TipoDocumentoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new TipoDocumentoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdTipoDocumento() != null && $tablaModelo->getIdTipoDocumento() > 0) {
		return $this->modeloTipoDocumento->actualizar($datosBd, $tablaModelo->getIdTipoDocumento());
		} else {
		unset($datosBd["id_tipo_documento"]);
		return $this->modeloTipoDocumento->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloTipoDocumento->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return TipoDocumentoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloTipoDocumento->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloTipoDocumento->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloTipoDocumento->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarTipoDocumento()
	{
	$consulta = "SELECT * FROM ".$this->modeloTipoDocumento->getEsquema().". tipo_documento";
		 return $this->modeloTipoDocumento->ejecutarSqlNativo($consulta);
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarTipoDocumentoModelo($arrayParametros)
	{
	    
	    $busqueda = '';
	    if (array_key_exists('orden', $arrayParametros)) {
	        $busqueda = "and md.orden in ( " . $arrayParametros['orden'].")" ;
	    }
	    if (array_key_exists('id_modelo_administrativo', $arrayParametros)) {
	        $busqueda = "and md.id_modelo_administrativo =  " . $arrayParametros['id_modelo_administrativo'] ;
	    }
	    
	   $consulta = "
                    SELECT 
                           md.nombre_modelo, to_char(td.fecha_creacion, 'DD-MM-YYYY (HH24:MI)') AS fecha_creacion , to_char(td.fecha_creacion, 'DD-MM-YYYY') AS fecha_anexo,
                           md.ruta_modelo, td.ruta_documento, md.descripcion, pa.detalle_sancion, pa.observacion, pa.estado, td.nombre_anexo, td.id_tipo_documento
                    FROM 
                        ".$this->modeloTipoDocumento->getEsquema().". proceso_administrativo pa inner join "
                        .$this->modeloTipoDocumento->getEsquema().". tipo_documento td on pa.id_proceso_administrativo = td.id_proceso_administrativo inner join "
                        .$this->modeloTipoDocumento->getEsquema().". modelo_administrativo md on td.id_modelo_administrativo = md.id_modelo_administrativo
                    WHERE 
                        td.id_proceso_administrativo = ".$arrayParametros['id_proceso_administrativo']."  
                        ".$busqueda." order by td.fecha_creacion;";
	    return $this->modeloTipoDocumento->ejecutarSqlNativo($consulta);
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarTipoDocumentoFiltro($arrayParametros)
	{
	    $busqueda = '';
	    if (array_key_exists('ruta_documento', $arrayParametros)) {
	        $busqueda .= " and ruta_documento is not null" ;
	    }
	    if (array_key_exists('id_tipo_documento', $arrayParametros)) {
	        $busqueda .= " and id_tipo_documento = ". $arrayParametros['id_tipo_documento'] ;
	    }
	    $consulta = "
                    SELECT 
                        distinct l.id_modelo_administrativo,
                        (select id_tipo_documento from g_procesos_administrativos_juridico.tipo_documento j 
                        where j.id_modelo_administrativo = l.id_modelo_administrativo and j.id_proceso_administrativo = ".$arrayParametros['id_proceso_administrativo']." limit 1 ) as id_tipo_documento 
                    FROM 
                            g_procesos_administrativos_juridico. tipo_documento l 
                    WHERE 
                            id_proceso_administrativo = ".$arrayParametros['id_proceso_administrativo']." 
                            ".$busqueda." order by 1 ASC
                    ;";
        return $this->modeloTipoDocumento->ejecutarSqlNativo($consulta);
	}

}

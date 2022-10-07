<?php
 /**
 * Lógica del negocio de TipoInspectorModelo
 *
 * Este archivo se complementa con el archivo TipoInspectorControlador.
 *
 * @author  AGROCALIDAD
 * @date    2018-11-21
 * @uses    TipoInspectorLogicaNegocio
 * @package CentrosFaenamiento
 * @subpackage Modelos
 */
  namespace Agrodb\CentrosFaenamiento\Modelos;
  
  use Agrodb\CentrosFaenamiento\Modelos\IModelo;
use Agrodb\Core\Constantes;
 
class TipoInspectorLogicaNegocio implements IModelo 
{

	 private $modeloTipoInspector = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloTipoInspector = new TipoInspectorModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
	    $datos['identificador_registro'] = $_SESSION['usuario'];
		$tablaModelo = new TipoInspectorModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdTipoInspector() != null && $tablaModelo->getIdTipoInspector() > 0) {
			return $this->modeloTipoInspector->actualizar($datosBd, $tablaModelo->getIdTipoInspector());
		} else {
			unset($datosBd["id_tipo_inspector"]);
			return $this->modeloTipoInspector->guardar($datosBd);
		}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloTipoInspector->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return TipoInspectorModelo
	*/
	public function buscar($id)
	{
		return $this->modeloTipoInspector->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloTipoInspector->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloTipoInspector->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarTipoInspector()
	{
	$consulta = "SELECT * FROM ".$this->modeloTipoInspector->getEsquema().". tipo_inspector";
		 return $this->modeloTipoInspector->ejecutarSqlNativo($consulta);
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar Operadores declarados como auxiliares o no auxiliares.
	 * @return array|ResultSet
	 */
	public function buscarAuxiliares($arrayParametros)
	{
		
	    $consulta = "SELECT 
	    				   ti.id_tipo_inspector, ti.resultado, ti.tipo_inspector, 
					       ti.observacion, ti.id_operador_tipo_operacion, ti.identificador_operador, ti.fecha_creacion , ti.identificador_registro,
					       CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END nombre_operador,
	    				   o.provincia
					FROM 
						  g_operadores.operadores o
						  LEFT JOIN g_centros_faenamiento.tipo_inspector ti ON ti.identificador_operador = o.identificador
					WHERE
						  o.identificador = '" . $arrayParametros['identificador_operador'] ."' and ti.tipo_inspector = '" . $arrayParametros['tipo_inspector'] ."'";
	    
	    $auxiliarInpector = $this->modeloTipoInspector->ejecutarSqlNativo($consulta);
	    
	    if(!$auxiliarInpector->count()){
	        $auxiliarInpector = $this->obtenerDatosOperadorPorIdentificador($arrayParametros);
	    }
	    
	    return $auxiliarInpector;
	}
	
	public function obtenerDatosOperadorPorIdentificador($arrayParametros) {
	    
	    $consulta = "SELECT
	    				   o.identificador as identificador_operador,
					       CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END nombre_operador,
	    				   o.provincia
					FROM
						  g_operadores.operadores o
					WHERE
						  o.identificador = '" . $arrayParametros['identificador_operador'] ."'";
	    
	    return $this->modeloTipoInspector->ejecutarSqlNativo($consulta);
	}
	
	//****************agregar usuarios x perfil**************************************
	public function agregarUsuariosXPerfil ($arrayParametros){
		
	$consulta = "INSERT INTO g_usuario.usuarios_perfiles(identificador, id_perfil) SELECT  '".$arrayParametros['identificador_operador']."', (SELECT id_perfil
											FROM g_usuario.perfiles WHERE codificacion_perfil ='".$arrayParametros['codPerfil']."')
											where not exists (select * from g_usuario.usuarios_perfiles where identificador = '".$arrayParametros['identificador_operador']."'
											and id_perfil= (SELECT id_perfil FROM g_usuario.perfiles WHERE codificacion_perfil ='".$arrayParametros['codPerfil']."') );";
	return $this->modeloTipoInspector->ejecutarSqlNativo($consulta);
	}
	
	//****************agregar aplicaciones x código de aplicación**************************************
	
	public function agregarAplicacionesXCodigoAplicacion ($arrayParametros){
		
		$consulta = "INSERT INTO g_programas.aplicaciones_registradas(id_aplicacion, identificador, cantidad_notificacion, mensaje_notificacion) 
					SELECT (SELECT id_aplicacion FROM g_programas.aplicaciones WHERE codificacion_aplicacion='".$arrayParametros['codAplicacion']."'),'".$arrayParametros['identificador_operador']."',0,'notificaciones'
					WHERE NOT EXISTS (SELECT * FROM g_programas.aplicaciones_registradas WHERE id_aplicacion =(SELECT id_aplicacion FROM g_programas.aplicaciones WHERE codificacion_aplicacion='".$arrayParametros['codAplicacion']."')
 					AND identificador = '".$arrayParametros['identificador_operador']."');";
		
		return $this->modeloTipoInspector->ejecutarSqlNativo($consulta);
	}

}

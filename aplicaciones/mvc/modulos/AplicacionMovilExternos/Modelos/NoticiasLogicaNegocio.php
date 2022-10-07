<?php
 /**
 * Lógica del negocio de NoticiasModelo
 *
 * Este archivo se complementa con el archivo NoticiasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-06-06
 * @uses    NoticiasLogicaNegocio
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
namespace Agrodb\AplicacionMovilExternos\Modelos;
  
use Agrodb\AplicacionMovilExternos\Modelos\IModelo;
 
class NoticiasLogicaNegocio implements IModelo 
{

	 private $modeloNoticias = null;

	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloNoticias = new NoticiasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new NoticiasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdNoticia() != null && $tablaModelo->getIdNoticia() > 0) {
		return $this->modeloNoticias->actualizar($datosBd, $tablaModelo->getIdNoticia());
		} else {
		unset($datosBd["id_noticia"]);
		unset($datosBd["estado"]);							  
		return $this->modeloNoticias->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloNoticias->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return NoticiasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloNoticias->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloNoticias->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloNoticias->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarNoticias()
	{
	$consulta = "SELECT * FROM ".$this->modeloNoticias->getEsquema().". noticias";
		 return $this->modeloNoticias->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta consulta(SQL), para la obtención de las noticias en base a una carga de offset y fectch .
	 *
	 * @return array|ResultSet
	 */
	public function obtenerNoticiasOffset($arrayParametros) {
		
		$consulta = "SELECT
						id_noticia, titulo, noticia, ruta, to_char(fecha_noticia,'YYYY-MM-DD') as fecha_noticia, visitas, fecha_noticia as fecha, fuente, url_fuente
					FROM
						a_movil_externos.noticias
					WHERE
						estado = 'activo'
					ORDER BY
						fecha desc
						offset " . $arrayParametros['incremento'] ." row
						fetch next 10 rows only;";

		return $this->modeloNoticias->ejecutarSqlNativo($consulta);

	}
	
	/**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar noticias usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarNoticiasXFiltro($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['titulo']) && ($arrayParametros['titulo'] != '')) {
            $busqueda .= "and upper(n.titulo) ilike upper('%" . $arrayParametros['titulo'] . "%')";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
            $busqueda .= " and n.fecha_noticia >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
            $busqueda .= " and n.fecha_noticia <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
        }
        
        $consulta = "  SELECT
                        	n.id_noticia, n.titulo, 
                            n.fuente, n.fecha_noticia, 
                            n.visitas, n.estado
                        FROM
                        	a_movil_externos.noticias n
                        WHERE
                            n.estado = '" . $arrayParametros['estado'] . "'" . $busqueda . "
                        ORDER BY
                            n.fecha_noticia, n.titulo ASC;";
        
        return $this->modeloNoticias->ejecutarSqlNativo($consulta);
    }
}

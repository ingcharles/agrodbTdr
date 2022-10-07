<?php
/**
 * Lógica del negocio de AreaModelo
 *
 * Este archivo se complementa con el archivo AreaControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-02-13
 * @uses AreaLogicaNegocio
 * @package Estructura
 * @subpackage Modelos
 */
namespace Agrodb\Estructura\Modelos;

use Agrodb\Estructura\Modelos\IModelo;

class AreaLogicaNegocio implements IModelo{

	private $modeloArea = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloArea = new AreaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new AreaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdArea() != null && $tablaModelo->getIdArea() > 0){
			return $this->modeloArea->actualizar($datosBd, $tablaModelo->getIdArea());
		}else{
			unset($datosBd["id_area"]);
			return $this->modeloArea->guardar($datosBd);
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
		$this->modeloArea->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AreaModelo
	 */
	public function buscar($id){
		return $this->modeloArea->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloArea->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloArea->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarArea(){
		$consulta = "SELECT * FROM " . $this->modeloArea->getEsquema() . ". area";
		return $this->modeloArea->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta de las Coordinaciones, Direcciones Generales y Distritales a nivel nacional.
	 *
	 * @return array|ResultSet
	 */
	public function buscarAreasCategoriaNacional(){
		$where = "categoria_area in (1, 3) and 
                  estado=1 or 
                  (categoria_area=4 and clasificacion='Planta Central')";

		return $this->modeloArea->buscarLista($where, 'nombre');
	}

	/**
	 * Obtiene el registro de una área por medio de su nombre.
	 *
	 * @return array|ResultSet
	 */
	public function buscarAreaPorNombre($nombreBusqueda){
		$where = "upper(unaccent(nombre)) = upper(unaccent('$nombreBusqueda')) and estado = 1";

		return $this->modeloArea->buscarLista($where);
	}

	/**
	 * Obtiene el registro de una área por medio de su código.
	 *
	 * @return array|ResultSet
	 */
	public function buscarAreaPorCodigo($codigoBusqueda){
		$where = "upper(unaccent(id_area)) = upper(unaccent('$codigoBusqueda')) and estado = 1";

		return $this->modeloArea->buscarLista($where);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|String
	 */
	public function buscarAreaPadreHijoPorCodigo($idArea){
		
		$datosArea = $this->buscarAreaPorCodigo($idArea);
			
		$arrayParametros = array('id_area_padre' => $idArea, 'estado' => 1);
			
		$areaPadre = $this->buscarLista($arrayParametros);
			
		$areaSubproceso = "'".$datosArea->current()->nombre."',";
			
			foreach ($areaPadre as $area){
				$areaSubproceso .= "'" . $area['nombre'] . "',";
			}
			
			$areas = "(" . rtrim($areaSubproceso, ',') . ")";
		
		return $areas;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|String
	 */
	public function buscarEstructuraXCodigoPadre($idAreaPadre)
    {
        $areaSubproceso='';
        
        $consulta = "   SELECT
                        	distinct a1.id_area
                        FROM
                        	g_estructura.area a1
                        WHERE
                        	id_area = '$idAreaPadre' or 
                        	id_area_padre IN (SELECT
                        						a.id_area
                        					FROM
                        						g_estructura.area a
                        					WHERE
                        						a.id_area_padre = '$idAreaPadre'
                        					  	and a.estado= 1
                        					UNION
                        					SELECT
                        						a.id_area
                        					FROM
                        						g_estructura.area a
                        					WHERE
                        						a.id_area = '$idAreaPadre'
                        					  	and a.estado= 1)
                        ORDER BY 1;";
        
        $estructura = $this->modeloArea->ejecutarSqlNativo($consulta);
        
        foreach ($estructura as $area){
            $areaSubproceso .= "'" . $area->id_area . "',";
        }
        
        $areas = "(" . rtrim($areaSubproceso, ',') . ")";

        return $areas;
    }
}

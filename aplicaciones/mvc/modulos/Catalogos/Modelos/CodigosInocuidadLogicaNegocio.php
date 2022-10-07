<?php
/**
 * Lógica del negocio de CodigosInocuidadModelo
 *
 * Este archivo se complementa con el archivo CodigosInocuidadControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-06
 * @uses CodigosInocuidadLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class CodigosInocuidadLogicaNegocio implements IModelo{

	private $modeloCodigosInocuidad = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloCodigosInocuidad = new CodigosInocuidadModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new CodigosInocuidadModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdProducto() != null && $tablaModelo->getIdProducto() > 0){
			return $this->modeloCodigosInocuidad->actualizar($datosBd, $tablaModelo->getIdProducto());
		}else{
			unset($datosBd["id_producto"]);
			return $this->modeloCodigosInocuidad->guardar($datosBd);
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
        $tablaModelo = new CodigosInocuidadModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();

        return $this->modeloCodigosInocuidad->guardar($datosBd);
    }

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloCodigosInocuidad->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return CodigosInocuidadModelo
	 */
	public function buscar($id){
		return $this->modeloCodigosInocuidad->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloCodigosInocuidad->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloCodigosInocuidad->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarCodigosInocuidad(){
		$consulta = "SELECT * FROM " . $this->modeloCodigosInocuidad->getEsquema() . ". codigos_inocuidad";
		return $this->modeloCodigosInocuidad->ejecutarSqlNativo($consulta);
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
                            g_catalogos.codigos_inocuidad
                        WHERE
                            id_producto = $idProducto; ";

        return $this->modeloCodigosInocuidad->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Elimina todos los registros vinculados a un producto
     *
     * @return array|ResultSet
     */
    public function borrarRegistro($datos)
    {
        $consulta = "   DELETE FROM
                            g_catalogos.codigos_inocuidad
                        WHERE
                            id_producto = '" .$datos['id_producto']."'
                             and subcodigo = '" .$datos['subcodigo']."'";

        return $this->modeloCodigosInocuidad->ejecutarSqlNativo($consulta);
    }

	/**
	 * Generar un combo de codigos inocuidad presentacion rectificacion de importaciones VUE
	 *
	 * @return ResultSet
	 */
	public function comboCodigosInocuidad($idProducto, $codigoInocuidad){

		$selectCodigoInocuidad = '';

		$codigosInocuidad = $this->buscarLista(array('id_producto'=>$idProducto));

		foreach ($codigosInocuidad as $codigo){
			if ($codigoInocuidad == $codigo['subcodigo']){
				$selectCodigoInocuidad .= '<option value="' . $codigo['subcodigo'] . '" selected>' . $codigo['presentacion'] . '</option>';
				$nombrePresentacion = $codigo['presentacion'];
			}else{
				$selectCodigoInocuidad .= '<option value="' . $codigo['subcodigo'] . '" >' . $codigo['presentacion'] . '</option>';
			}
		}

		$campoCodigoInocuidad = '<div data-linea="4">
									<label>Código presentacion: </label>
										<select name="codigo_presentacion[]" class="validacion">
											<option value="">Seleccionar....</option>
											'.$selectCodigoInocuidad.'
										</select>
								</div>';

		return $campoCodigoInocuidad;
	}

	/**
	 * Genera subcodigo de inocuidad
	 *
	 * @return ResultSet
	 */
	public function obtenerCodigoInocuidad ($idProducto){

	   $consulta = "SELECT
                	    COALESCE(MAX(CAST(tscp.subcodigo as  numeric(5))),0)+1 as codigo
                	FROM
                	    (SELECT
                	        subcodigo
                	        , id_producto
                	     FROM
                	        g_catalogos.codigos_inocuidad
                	     UNION
                	     SELECT
                	        subcodigo, id_producto
                	     FROM
                	        g_modificacion_productos.adiciones_presentaciones ap
                	        INNER JOIN g_modificacion_productos.detalle_solicitudes_productos dsp ON dsp.id_detalle_solicitud_producto = ap.id_detalle_solicitud_producto
                	        INNER JOIN g_modificacion_productos.solicitudes_productos sp ON sp.id_solicitud_producto = dsp.id_solicitud_producto
                	        ) tscp
                	  WHERE
                	        tscp.id_producto = '" . $idProducto . "';";

	    $res = $this->modeloCodigosInocuidad->ejecutarSqlNativo($consulta);

	    return $res;
	}

	/**
	 * Genera subcodigo de inocuidad
	 *
	 * @return ResultSet
	 */
	public function verificarAdicionPresentacion ($arrayParametros){

	    $idProducto =  $arrayParametros['id_producto'];
	    $presentacion =  $arrayParametros['presentacion'];
	    $unidadMedida =  $arrayParametros['unidad_medida'];

	    $consulta = "SELECT
                        sp.id_producto
                    	, ap.presentacion
                    	, ap.unidad_medida
                    FROM
                    	g_modificacion_productos.adiciones_presentaciones ap
                    	INNER JOIN g_modificacion_productos.detalle_solicitudes_productos dsp ON dsp.id_detalle_solicitud_producto = ap.id_detalle_solicitud_producto
                    	INNER JOIN g_modificacion_productos.solicitudes_productos sp ON sp.id_solicitud_producto = dsp.id_solicitud_producto
                    WHERE
                    	id_producto = '" . $idProducto . "'
                    	and presentacion = '" . $presentacion ."'
                    	and unidad_medida = '" . $unidadMedida . "'
                    UNION
                    SELECT
						ci.id_producto
                    	, ci.presentacion
                    	, ci.unidad_medida
					FROM
						g_catalogos.codigos_inocuidad ci
					WHERE
						ci.id_producto = '" . $idProducto . "'						
						and ci.presentacion = '" . $presentacion . "'
                        and ci.unidad_medida = '" . $unidadMedida ."'";


	    $res = $this->modeloCodigosInocuidad->ejecutarSqlNativo($consulta);

	    return $res;
	}

}

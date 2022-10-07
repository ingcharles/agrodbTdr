<?php
/**
 * Lógica del negocio de LocalizacionModelo
 *
 * Este archivo se complementa con el archivo LocalizacionControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-09-03
 * @uses LocalizacionLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;
use Agrodb\Token\Modelos\TokenLogicaNegocio;
use Agrodb\Core\Excepciones\BuscarExcepcion;
use \Exception;

use Agrodb\Catalogos\Modelos\IModelo;

class LocalizacionLogicaNegocio implements IModelo{

	private $modeloLocalizacion = null;
	private $lNegocioToken = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloLocalizacion = new LocalizacionModelo();
		$this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new LocalizacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdLocalizacion() != null && $tablaModelo->getIdLocalizacion() > 0){
			return $this->modeloLocalizacion->actualizar($datosBd, $tablaModelo->getIdLocalizacion());
		}else{
			unset($datosBd["id_localizacion"]);
			return $this->modeloLocalizacion->guardar($datosBd);
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
		$this->modeloLocalizacion->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return LocalizacionModelo
	 */
	public function buscar($id){
		return $this->modeloLocalizacion->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloLocalizacion->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloLocalizacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarLocalizacion(){
		$consulta = "SELECT * FROM " . $this->modeloLocalizacion->getEsquema() . ". localizacion";
		return $this->modeloLocalizacion->ejecutarSqlNativo($consulta);
	}

	/**
	 * Busca el catálogo de países
	 *
	 * @return ResultSet Registro categoria=0
	 */
	public function buscarPaises(){
		$where = "categoria=0";
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}

	/**
	 * Busca el catálogo de países y agrupaciones de paises
	 *
	 * @return ResultSet Registro categoria=0
	 */
	public function buscarVariosPaises(){
		$where = "categoria in (0, 5)";
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}

	/**
	 * Busca el catálogo de las provincias de Ecuador
	 *
	 * @return ResultSet Registron categoria=1
	 */
	public function buscarProvinciasEc(){
		$where = "categoria=1 ORDER BY nombre ASC";
		return $this->modeloLocalizacion->buscarLista($where);
	}

	/**
	 * Busca el catálogo de las provincias
	 *
	 * @return ResultSet Registron categoria=1
	 */
	public function buscarProvincias($idPais){
		$where = "id_localizacion_padre=" . $idPais . " order by codigo";
		return $this->modeloLocalizacion->buscarLista($where);
	}

	/**
	 * Busca el catálogo de las Cantones
	 *
	 * @return ResultSet Registron categoria=1
	 */
	public function buscarCantones($idProvincia){
		$where = "id_localizacion_padre=" . $idProvincia;
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}

	/**
	 * Busca el catálogo de las parroquias
	 *
	 * @return ResultSet Registron categoria=1
	 */
	public function buscarParroquias($idCanton){
		$where = "id_localizacion_padre=" . $idCanton;
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}

	/**
	 * Busca el catálogo de las oficinas
	 *
	 * @return ResultSet Registro categoria=3
	 */
	public function buscarOficinas($idCanton, $categoria){
		$where = "id_localizacion_padre=" . $idCanton . "and 
                  categoria=" . $categoria;
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}

	/**
	 * Busca un determinado país por nombre
	 *
	 * @return ResultSet Registro categoria=0
	 */
	public function buscarPaisesPorNombre($nombrePais){
		$where = "upper(unaccent(nombre)) = upper(unaccent('$nombrePais')) and categoria = 0";
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}

	/**
	 * Busca un determinado país por nombre
	 *
	 * @return ResultSet Registro categoria=0
	 */
	public function buscarVariosPaisesPorNombre($nombrePais){
		$where = "upper(unaccent('$nombrePais')) in ( upper(unaccent(nombre)), upper(unaccent(nombre_ingles)) )
                    and categoria in (0,5)";
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}

	/**
	 * Busca una determinada localización por nombre, identificador padre y categoria.
	 *
	 * @return ResultSet Registro categoria=1
	 */
	public function buscarLocalizacionPorNombrePorIdentificadorPadre($identificadorPadre, $nombreBusqueda, $categoria){
		$where = "id_localizacion_padre=" . $identificadorPadre . " and upper(unaccent(nombre)) = upper(unaccent('$nombreBusqueda')) and categoria = " . $categoria . "";
		return $this->modeloLocalizacion->buscarLista($where);
	}

	/**
	 * Busca un determinado país por idLocalizacion
	 *
	 * @return ResultSet Registro categoria=0
	 */
	public function buscarPaisesPorIdLocalizacion($idLocalizacion){
		$where = "id_localizacion = '" . $idLocalizacion . "' and categoria = 0";
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}

	/**
	 * Busca un determinado país por nombre y por idioma
	 *
	 * @return ResultSet Registro categoria=0
	 */
	public function buscarPaisesPorNombrePorIdioma($nombrePais, $idioma){
		if ($idioma == 'SPA'){
			$clave = 'nombre';
			$where = "upper(unaccent(nombre)) = upper(unaccent('$nombrePais')) and categoria = 0";
		}else{
			$clave = 'nombre_ingles';
			$where = "upper(unaccent(nombre_ingles)) = upper(unaccent('$nombrePais')) and categoria = 0";
		}

		return $this->modeloLocalizacion->buscarLista($where, $clave);
	}

	/**
	 * Busca un determinado país por codigo vue
	 *
	 * @return ResultSet Registro datos de país
	 */
	public function buscarPaisesPorCodigo($codigoPais){
		$where = "upper(codigo_vue) = upper('" . $codigoPais . "') and categoria = 0";
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}

	/**
	 * Busca una determinada localización por nombre, identificador padre y categoria.
	 *
	 * @return ResultSet Registro categoria=1
	 */
	public function buscarLocalizacionPorCodigoPorIdentificadorPadre($identificadorPadre, $codigoBusqueda, $categoria){
		$where = "id_localizacion_padre=" . $identificadorPadre . " and codigo_vue = '" . $codigoBusqueda . "' and categoria = " . $categoria . "";
		return $this->modeloLocalizacion->buscarLista($where);
	}
	
	/**
     * Busca el catálogo de las Cantones
     *
     * @return ResultSet 
     */
    public function obtenerCantones($idProvincia){
        $arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);
        if ($arrayToken['estado'] == 'exito') {
            $where = "id_localizacion_padre=" . $idProvincia;
            try {
				$res = (object) $this->modeloLocalizacion->buscarLista($where, 'nombre'); 
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";				
				$array['cuerpo'] =  $res->toArray();
				echo json_encode($array);		
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
				throw new BuscarExcepcion($ex, array('controlador'=>'RestWsLocalizacion','archivo' => 'AdministracionTrampasLogicaNegocio', 'metodo' => 'obtenerRutasTrampasVigilancia'));
			}
        } else{
            echo json_encode($arrayToken);
        }
    }

	/**
     * Busca el catálogo de Cantones y parroquias por provincia
     *
     * @return ResultSet Registro categoria=1
     */
    public function BuscarCatalogoCantonParroquiaPorProvincia($provincia){

        $arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

        if ($arrayToken['estado'] == 'exito') {

			$busqueda = $provincia != null ? " and l.id_localizacion= $provincia" : "";

            $consulta = "SELECT row_to_json(res) as res FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) as  localizacionCatalogo FROM (
                SELECT 
                    l.id_localizacion as idGuia,
                    l.codigo,
                    l.nombre,
                    l.id_localizacion_padre as idGuiaPadre,
                    l.categoria,
                   (SELECT array_to_json(array_agg(row_to_json(listado_2))) FROM (
                    SELECT 
                        lc.id_localizacion as idGuia,
                        lc.codigo,
                        lc.nombre,
                        lc.id_localizacion_padre as idGuiaPadre,
                        lc.categoria,
                           (SELECT array_to_json(array_agg(row_to_json(listado_3))) FROM (
                            SELECT 
                                lp.id_localizacion as idGuia,
                                lp.codigo,
                                lp.nombre,
                                lp.id_localizacion_padre as idGuiaPadre,
                                lp.categoria
                            FROM 
                               g_catalogos.localizacion lp
                            WHERE 
                               lp.id_localizacion_padre = lc.id_localizacion
                               AND lp.categoria = 4 --Categoría de parroquia 
                            ORDER BY 
                            2 ) AS listado_3	
                           ) AS parroquiaList
                    FROM 
                       g_catalogos.localizacion lc
                    WHERE 
                       lc.id_localizacion_padre = l.id_localizacion
                       AND lc.categoria = 2 --Categoría de cantón 
                    ORDER BY 
                    2 ) AS listado_2	
                   ) AS cantonList
                FROM 
                   g_catalogos.localizacion l
                WHERE 
                   l.categoria = 1 --Categoría de país 
                   $busqueda
                ORDER BY 
                2
                ) as listado ) AS res;";
    
            try {
				$res = $this->modeloLocalizacion->ejecutarSqlNativo($consulta);
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";				
				$array['cuerpo'] = json_decode($res->current()->res);
				echo json_encode($array);		
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
				throw new BuscarExcepcion($ex, array('controlador'=>'RestWsLocalizacion','archivo' => 'AdministracionTrampasLogicaNegocio', 'metodo' => 'obtenerRutasTrampasVigilancia', 'consulta' => $consulta));
			}
        } else{
            echo json_encode($arrayToken);
        }  
    }

	/**
	 * Busca una determinada provincia por nombre
	 *
	 * @return ResultSet Registro categoria=1
	 */
	public function buscarProvinciaPorIdProvincia($idProvincia){
		$where = "id_localizacion = '" . $idProvincia . "' and categoria = 1";
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}
	
	/**
	 * Busca una provincia por nombre
	 *
	 * @return ResultSet Registro categoria=1
	 */
	public function buscarProvinciaXNombre($nombreProvincia){
		$where = "upper(unaccent(nombre)) = upper(unaccent('$nombreProvincia')) and categoria = 1";
		return $this->modeloLocalizacion->buscarLista($where, 'nombre');
	}

    /**
     * Busca el catálogo de las localizaciones por la categoría
     * 
     * Requiere Token
     *
     * @param int idCategoria
     * @return ResultSet 
     */
    public function buscarLocalizacionPorCategoria($idCategoria)
    {
        $arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);
        if ($arrayToken['estado'] == 'exito') {

            $consulta = "SELECT row_to_json(res) as res FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) as localizacionCatalogo FROM (
                SELECT 
                    l.id_localizacion as idGuia,
                    l.codigo,
                    l.nombre,
                    l.id_localizacion_padre as idGuiaPadre,
                    l.categoria
                FROM 
                   g_catalogos.localizacion l
                WHERE 
                   l.categoria = $idCategoria
                ORDER BY 1
                ) as listado ) AS res;";

            try {
                $res = $this->modeloLocalizacion->ejecutarSqlNativo($consulta);
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";				
                $array['cuerpo'] = json_decode($res->current()->res);
				echo json_encode($array);		
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
				throw new BuscarExcepcion($ex, array('controlador'=>'RestWsLocalizacion','archivo' => 'LocalizacionLogicaNegocio', 'metodo' => 'buscarLocalizacionPorCategoria'));
			}
        } else{
            echo json_encode($arrayToken);
        }
    }
}

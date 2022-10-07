<?php
/**
 * Lógica del negocio de TiempoRetiroModelo
 *
 * Este archivo se complementa con el archivo TiempoRetiroControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    TiempoRetiroLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;
use Agrodb\Catalogos\Modelos\ProductosConsumiblesLogicaNegocio;

class TiempoRetiroLogicaNegocio implements IModelo
{

    private $modeloTiempoRetiro = null;
    private $lNegocioProductosConsumibles = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloTiempoRetiro = new TiempoRetiroModelo();
        $this->lNegocioProductosConsumibles = new ProductosConsumiblesLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new TiempoRetiroModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdTiempoRetiro() != null && $tablaModelo->getIdTiempoRetiro() > 0) {
            return $this->modeloTiempoRetiro->actualizar($datosBd, $tablaModelo->getIdTiempoRetiro());
        } else {
            unset($datosBd["id_tiempo_retiro"]);
            return $this->modeloTiempoRetiro->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloTiempoRetiro->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return TiempoRetiroModelo
     */
    public function buscar($id)
    {
        return $this->modeloTiempoRetiro->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloTiempoRetiro->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloTiempoRetiro->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarTiempoRetiro()
    {
        $consulta = "SELECT * FROM " . $this->modeloTiempoRetiro->getEsquema() . ". tiempo_retiro";
        return $this->modeloTiempoRetiro->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos del período de retiro
     * con la información de los items del catálogo
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionTiempoRetiro($idSolicitud)
    {
        $consulta = "SELECT
			             tr.*, pc.producto_consumible as producto_consumo
                    FROM
                         g_dossier_pecuario_mvc.tiempo_retiro tr
                         INNER JOIN g_catalogos.productos_consumibles pc ON tr.id_producto_consumo = pc.id_producto_consumible
                    WHERE
                    	tr.id_solicitud = $idSolicitud
                    ORDER BY
                        producto_consumo;";
        
        return $this->modeloTiempoRetiro->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosTiempoRetiro($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Tiempo Retiro",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $tiemR= $this->buscarLista($query);
        
        foreach($tiemR as $tiemRetiro){
            $arrayTiempoRetiro= array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'ingrediente_activo' => $tiemRetiro->ingrediente_activo,
                'id_producto_consumo' => $tiemRetiro->id_producto_consumo,
                'tiempo_retiro' => $tiemRetiro->tiempo_retiro,
                'id_unidad' => $tiemRetiro->id_unidad,
                'nombre_unidad' => $tiemRetiro->nombre_unidad
            );
            
            //echo 'Tiempo retiro';
            //print_r($arrayTiempoRetiro);
            
            $idReacMat = $this->guardar($arrayTiempoRetiro);
            
            if($idReacMat > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Tiempo Retiro. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Tiempo Retiro. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
    
    /**
     * Función para crear el producto en el módulo RIA
     */
    public function obtenerRegistrosTiempoRetiroRIA($idSolicitud)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Tiempo Retiro. ",
            'ingredienteActivo' => null,
            'productoConsumo' => null,
            'tiempoRetiro' => null,
            'nombreUnidadtiempo' => null
        );
        
        // Período de retiro (tabla Tiempo Retiro)
        $query = "id_solicitud = $idSolicitud ORDER BY 1 LIMIT 1";
        $perRetiro = $this->buscarLista($query);
        
        if (isset($perRetiro->current()->id_tiempo_retiro)) {
            
            $validacion['ingredienteActivo'] = $perRetiro->current()->ingrediente_activo;
            $idProductoConsumo = $perRetiro->current()->id_producto_consumo;
            $validacion['tiempoRetiro'] = $perRetiro->current()->tiempo_retiro;
            $validacion['nombreUnidadTiempo'] = $perRetiro->current()->nombre_unidad;
            
            $productos = $this->lNegocioProductosConsumibles->buscar($idProductoConsumo);
            
            if (! empty($productos)) {
                $validacion['productoConsumo'] = $productos->productoConsumible;
            } else {
                $validacion['productoConsumo'] = "NA";
            }
        } else {
            $validacion['ingredienteActivo'] = "NA";
            $validacion['productoConsumo'] = "NA";
            $validacion['tiempoRetiro'] = "NA";
            $validacion['nombreUnidadTiempo'] = "NA";
        }
        
        return $validacion;
    }
}
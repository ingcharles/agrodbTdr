<?php
/**
 * Lógica del negocio de OrigenProductoModelo
 *
 * Este archivo se complementa con el archivo OrigenProductoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-02
 * @uses    OrigenProductoLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class OrigenProductoLogicaNegocio implements IModelo
{

    private $modeloOrigenProducto = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloOrigenProducto = new OrigenProductoModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new OrigenProductoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdOrigenProducto() != null && $tablaModelo->getIdOrigenProducto() > 0) {
            return $this->modeloOrigenProducto->actualizar($datosBd, $tablaModelo->getIdOrigenProducto());
        } else {
            unset($datosBd["id_origen_producto"]);
            return $this->modeloOrigenProducto->guardar($datosBd);
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
        $this->modeloOrigenProducto->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return OrigenProductoModelo
     */
    public function buscar($id)
    {
        return $this->modeloOrigenProducto->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloOrigenProducto->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloOrigenProducto->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarOrigenProducto()
    {
        $consulta = "SELECT * FROM " . $this->modeloOrigenProducto->getEsquema() . ". origen_producto";
        return $this->modeloOrigenProducto->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para contar los registros creados
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroRegistrosOrigenProducto($idSolicitud)
    {
        $consulta = "SELECT
                        count(id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.origen_producto
                    WHERE
                    	id_solicitud = $idSolicitud;";

        return $this->modeloOrigenProducto->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosOrigenProducto($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Origen Producto. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $org = $this->buscarLista($query);
        
        foreach($org as $origenProd){
            $arrayOrigenProducto= array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'origen_fabricacion' => $origenProd->origen_fabricacion,
                'identificador_fabricante' => $origenProd->identificador_fabricante,
                'nombre_fabricante' => $origenProd->nombre_fabricante,
                'direccion_fabricante' => $origenProd->direccion_fabricante,
                'id_provincia_fabricante' => $origenProd->id_provincia_fabricante,
                'provincia_fabricante' => $origenProd->provincia_fabricante,
                'id_fabricante_extranjero' => $origenProd->id_fabricante_extranjero,
                'id_pais' => $origenProd->id_pais,
                'pais' => $origenProd->pais,
                'tipo_producto_fabricante' => $origenProd->tipo_producto_fabricante
            );
            
            //echo 'Forma origen producto';
            //print_r($arrayOrigenProducto);
            
            $idOrigenProd = $this->guardar($arrayOrigenProducto);
            
            if($idOrigenProd > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Origen Producto. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Origen Producto. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
}
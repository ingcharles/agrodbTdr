<?php
/**
 * Lógica del negocio de PresentacionComercialModelo
 *
 * Este archivo se complementa con el archivo PresentacionComercialControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    PresentacionComercialLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class PresentacionComercialLogicaNegocio implements IModelo
{

    private $modeloPresentacionComercial = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloPresentacionComercial = new PresentacionComercialModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new PresentacionComercialModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdPresentacionComercial() != null && $tablaModelo->getIdPresentacionComercial() > 0) {
            return $this->modeloPresentacionComercial->actualizar($datosBd, $tablaModelo->getIdPresentacionComercial());
        } else {
            unset($datosBd["id_presentacion_comercial"]);
            return $this->modeloPresentacionComercial->guardar($datosBd);
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
        $this->modeloPresentacionComercial->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return PresentacionComercialModelo
     */
    public function buscar($id)
    {
        return $this->modeloPresentacionComercial->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloPresentacionComercial->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloPresentacionComercial->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarPresentacionComercial()
    {
        $consulta = "SELECT * FROM " . $this->modeloPresentacionComercial->getEsquema() . ". presentacion_comercial";
        return $this->modeloPresentacionComercial->ejecutarSqlNativo($consulta);
    }

    /**
     * Genera el número de subcódigo de producto por presentación
     *
     * @return array|ResultSet
     */
    public function generarSubcodigoPresentacion($idSolicitud)
    {
        $consulta = "SELECT
                        max(subcodigo_producto) as numero
                     FROM
                        g_dossier_pecuario_mvc.presentacion_comercial
                     WHERE
                        id_solicitud = $idSolicitud;";

        // echo $consulta;
        $codigo = $this->modeloPresentacionComercial->ejecutarSqlNativo($consulta);
        $fila = $codigo->current();

        $idSubcodigo = array(
            'numero' => $fila['numero']
        );

        $incremento = $idSubcodigo['numero'] + 1;
        $idSubcodigo = str_pad($incremento, 4, "0", STR_PAD_LEFT);
        // echo $idSubcodigo;
        return $idSubcodigo;
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para contar los registros creados
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroRegistrosPresentacionComercial($idSolicitud)
    {
        $consulta = "SELECT
                        count(id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.presentacion_comercial
                    WHERE
                    	id_solicitud = $idSolicitud;";

        return $this->modeloPresentacionComercial->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosPresentacionComercial($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Presentacion Comercial. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $pres = $this->buscarLista($query);
        
        foreach($pres as $presCom){
            $arrayPresentComerc= array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'subcodigo_producto' => $presCom->subcodigo_producto,
                'presentacion' => $presCom->presentacion,
                'cantidad' => $presCom->cantidad,
                'id_unidad' => $presCom->id_unidad,
                'nombre_unidad' => $presCom->nombre_unidad,
                'dosis_envase' => $presCom->dosis_envase
            );
            
            //echo 'Presentacion Comercial';
            //print_r($arrayPresentComerc);
            
            $idPerVidUt = $this->guardar($arrayPresentComerc);
            
            if($idPerVidUt > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Presentacion Comercial. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Presentacion Comercial. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
}
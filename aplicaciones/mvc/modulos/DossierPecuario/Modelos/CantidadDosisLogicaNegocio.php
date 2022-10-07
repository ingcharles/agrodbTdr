<?php
/**
 * Lógica del negocio de CantidadDosisModelo
 *
 * Este archivo se complementa con el archivo CantidadDosisControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    CantidadDosisLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class CantidadDosisLogicaNegocio implements IModelo
{

    private $modeloCantidadDosis = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloCantidadDosis = new CantidadDosisModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new CantidadDosisModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdCantidadDosis() != null && $tablaModelo->getIdCantidadDosis() > 0) {
            return $this->modeloCantidadDosis->actualizar($datosBd, $tablaModelo->getIdCantidadDosis());
        } else {
            unset($datosBd["id_cantidad_dosis"]);
            return $this->modeloCantidadDosis->guardar($datosBd);
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
        $this->modeloCantidadDosis->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return CantidadDosisModelo
     */
    public function buscar($id)
    {
        return $this->modeloCantidadDosis->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloCantidadDosis->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloCantidadDosis->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCantidadDosis()
    {
        $consulta = "SELECT * FROM " . $this->modeloCantidadDosis->getEsquema() . ". cantidad_dosis";
        return $this->modeloCantidadDosis->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosCantidadDosis($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Cantidad Dosis. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $cantDos = $this->buscarLista($query);
        
        foreach($cantDos as $cantidadDosis){
            $arrayCantidadDosis = array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'dosis' => $cantidadDosis->dosis,
                'id_unidad' => $cantidadDosis->id_unidad,
                'nombre_unidad' => $cantidadDosis->nombre_unidad
            );
            
            //echo 'Cantidad Dosis';
            //print_r($arrayCantidadDosis);
            
            $idCantidadDosis = $this->guardar($arrayCantidadDosis);
            
            if($idCantidadDosis > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacioón de la tabla Cantidad Dosis. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Cantidad Dosis. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
}
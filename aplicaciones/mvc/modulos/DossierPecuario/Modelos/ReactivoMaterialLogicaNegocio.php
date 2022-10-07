<?php
/**
 * Lógica del negocio de ReactivoMaterialModelo
 *
 * Este archivo se complementa con el archivo ReactivoMaterialControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    ReactivoMaterialLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class ReactivoMaterialLogicaNegocio implements IModelo
{

    private $modeloReactivoMaterial = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloReactivoMaterial = new ReactivoMaterialModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ReactivoMaterialModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdReactivoMaterial() != null && $tablaModelo->getIdReactivoMaterial() > 0) {
            return $this->modeloReactivoMaterial->actualizar($datosBd, $tablaModelo->getIdReactivoMaterial());
        } else {
            unset($datosBd["id_reactivo_material"]);
            return $this->modeloReactivoMaterial->guardar($datosBd);
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
        $this->modeloReactivoMaterial->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ReactivoMaterialModelo
     */
    public function buscar($id)
    {
        return $this->modeloReactivoMaterial->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloReactivoMaterial->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloReactivoMaterial->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarReactivoMaterial()
    {
        $consulta = "SELECT * FROM " . $this->modeloReactivoMaterial->getEsquema() . ". reactivo_material";
        return $this->modeloReactivoMaterial->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosReactivoMaterial($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Reactivo Material. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $react = $this->buscarLista($query);
        
        foreach($react as $reactMaterial){
            $arrayReactivoMaterial= array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'reactivo_material' => $reactMaterial->reactivo_material
            );
            
            //echo 'Reactivo Material';
            //print_r($arrayReactivoMaterial);
            
            $idReacMat = $this->guardar($arrayReactivoMaterial);
            
            if($idReacMat > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Reactivo Material. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Reactivo Material. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
}
<?php
/**
 * Lógica del negocio de EfectoBiologicoModelo
 *
 * Este archivo se complementa con el archivo EfectoBiologicoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    EfectoBiologicoLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class EfectoBiologicoLogicaNegocio implements IModelo
{

    private $modeloEfectoBiologico = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloEfectoBiologico = new EfectoBiologicoModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new EfectoBiologicoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdEfectoBiologico() != null && $tablaModelo->getIdEfectoBiologico() > 0) {
            return $this->modeloEfectoBiologico->actualizar($datosBd, $tablaModelo->getIdEfectoBiologico());
        } else {
            unset($datosBd["id_efecto_biologico"]);
            return $this->modeloEfectoBiologico->guardar($datosBd);
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
        $this->modeloEfectoBiologico->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return EfectoBiologicoModelo
     */
    public function buscar($id)
    {
        return $this->modeloEfectoBiologico->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloEfectoBiologico->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloEfectoBiologico->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarEfectoBiologico()
    {
        $consulta = "SELECT * FROM " . $this->modeloEfectoBiologico->getEsquema() . ". efecto_biologico";
        return $this->modeloEfectoBiologico->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos de efectos biológicos no deseados
     * con la información de los items del catálogo
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionEfectoBiologico($idSolicitud)
    {
        $consulta = "SELECT
                        eb.*, ebs.efecto_biologico
                    FROM
                        g_dossier_pecuario_mvc.efecto_biologico eb
                        INNER JOIN g_catalogos.efectos_biologicos ebs ON eb.id_efecto = ebs.id_efecto_biologico
                    WHERE
                    	eb.id_solicitud = $idSolicitud
                    ORDER BY
                        efecto_biologico;";

        return $this->modeloEfectoBiologico->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosEfectoBiologico($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Efecto Biologico. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $efec = $this->buscarLista($query);
       
        foreach($efec as $efectoBio){
            $arrayEfectoBio = array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'id_efecto' => $efectoBio->id_efecto,
                'descripcion_efecto_biologico' => $efectoBio->descripcion_efecto_biologico
            );
            
            //echo 'Efecto biologico';
            //print_r($arrayEfectoBio);
            
            $idEfectoBio = $this->guardar($arrayEfectoBio);
            
            if($idEfectoBio > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacióon de la tabla Efecto Biologico. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Efecto Biologico. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
}
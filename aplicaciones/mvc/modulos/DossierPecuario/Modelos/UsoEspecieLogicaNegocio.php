<?php
/**
 * Lógica del negocio de UsoEspecieModelo
 *
 * Este archivo se complementa con el archivo UsoEspecieControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    UsoEspecieLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class UsoEspecieLogicaNegocio implements IModelo
{

    private $modeloUsoEspecie = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloUsoEspecie = new UsoEspecieModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new UsoEspecieModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdUsoEspecie() != null && $tablaModelo->getIdUsoEspecie() > 0) {
            return $this->modeloUsoEspecie->actualizar($datosBd, $tablaModelo->getIdUsoEspecie());
        } else {
            unset($datosBd["id_uso_especie"]);
            return $this->modeloUsoEspecie->guardar($datosBd);
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
        $this->modeloUsoEspecie->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return UsoEspecieModelo
     */
    public function buscar($id)
    {
        return $this->modeloUsoEspecie->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloUsoEspecie->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloUsoEspecie->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarUsoEspecie()
    {
        $consulta = "SELECT * FROM " . $this->modeloUsoEspecie->getEsquema() . ". uso_especie";
        return $this->modeloUsoEspecie->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos de usos y
     * especies con la información de los items del catálogo
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionUsoEspecie($idSolicitud)
    {
        $consulta = "SELECT
                        ue.*, u.nombre_uso as uso, e.nombre as especie
                    FROM
                        g_dossier_pecuario_mvc.uso_especie ue
                        INNER JOIN g_catalogos.usos u ON ue.id_uso = u.id_uso
                        INNER JOIN g_catalogos.especies e ON ue.id_especie = e.id_especies
                    WHERE
                    	ue.id_solicitud = $idSolicitud
                    ORDER BY
                        uso, especie;";

        return $this->modeloUsoEspecie->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para contar los registros creados
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroRegistrosUsoEspecie($idSolicitud)
    {
        $consulta = "SELECT
                        count(id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.uso_especie
                    WHERE
                    	id_solicitud = $idSolicitud;";

        return $this->modeloUsoEspecie->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosUsoEspecie($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Uso Especie",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $usoE= $this->buscarLista($query);
        
        foreach($usoE as $usoEspecie){
            $arrayUsoEspecie= array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'id_uso' => $usoEspecie->id_uso,
                'id_especie' => $usoEspecie->id_especie,
                'nombre_especie' => $usoEspecie->nombre_especie
            );
            
            //echo 'Uso Especie';
            //print_r($arrayUsoEspecie);
            
            $idReacMat = $this->guardar($arrayUsoEspecie);
            
            if($idReacMat > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Uso Especie. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Uso Especie. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
}
<?php
/**
 * Lógica del negocio de FormaAdministracionModelo
 *
 * Este archivo se complementa con el archivo FormaAdministracionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    FormaAdministracionLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;
use Agrodb\Catalogos\Modelos\EspeciesLogicaNegocio;

class FormaAdministracionLogicaNegocio implements IModelo
{

    private $modeloFormaAdministracion = null;
    private $lNegocioEspecies = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloFormaAdministracion = new FormaAdministracionModelo();
        $this->lNegocioEspecies = new EspeciesLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new FormaAdministracionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdFormaAdministracion() != null && $tablaModelo->getIdFormaAdministracion() > 0) {
            return $this->modeloFormaAdministracion->actualizar($datosBd, $tablaModelo->getIdFormaAdministracion());
        } else {
            unset($datosBd["id_forma_administracion"]);
            return $this->modeloFormaAdministracion->guardar($datosBd);
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
        $this->modeloFormaAdministracion->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return FormaAdministracionModelo
     */
    public function buscar($id)
    {
        return $this->modeloFormaAdministracion->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloFormaAdministracion->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloFormaAdministracion->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarFormaAdministracion()
    {
        $consulta = "SELECT * FROM " . $this->modeloFormaAdministracion->getEsquema() . ". forma_administracion";
        return $this->modeloFormaAdministracion->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos de forma de administración
     * con la información de los items del catálogo
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionFormaAdministracion($idSolicitud)
    {
        $consulta = "SELECT
                        fa.*, e.nombre as especie
                    FROM
                        g_dossier_pecuario_mvc.forma_administracion fa
                        INNER JOIN g_catalogos.especies e ON fa.id_especie_destino = e.id_especies
                    WHERE
                    	fa.id_solicitud = $idSolicitud
                    ORDER BY
                        especie;";

        return $this->modeloFormaAdministracion->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosFormaAdministracion($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Forma Administracion. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $forAdm = $this->buscarLista($query);
        
        //if(!empty($forAdm)){
            foreach($forAdm as $composicion){
                $arrayComposicion = array( 'id_solicitud' => $idSolicitudNueva, //null,//
                    'id_especie_destino' => $composicion->id_especie_destino,
                    'nombre_especie' => $composicion->nombre_especie,
                    'caracteristicas_animal' => $composicion->caracteristicas_animal,
                    'cantidad_producto' => $composicion->cantidad_producto
                );
                
                //echo 'forma admin';
                //print_r($arrayComposicion);
                
                $idComposicion = $this->guardar($arrayComposicion);
                
                if($idComposicion > 0){
                    $validacion['estado'] = "exito";
                    $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Forma Administracion. ";
                    $validacion['bandera'] = true;
                }else{
                    $validacion['estado'] = "Fallo";
                    $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Forma Administracion. ";
                    $validacion['bandera'] = false;
                }
            }
        /*}else{
            $validacion['estado'] = "exito";
            $validacion['mensaje'] = " No existen registros en la tabla Forma Administracion. ";
            $validacion['bandera'] = true;
        }*/
        
        return $validacion;
    }
    
    /**
     * Función para crear el producto en el módulo RIA
     */
    public function obtenerRegistrosFormaAdministracionRIA($idSolicitud)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Forma Administracion. ",
            'periodoVidaUtil' => null,
            'nombreEspecie' => null,
            'caracteristicasAnimal' => null,
            'cantidadProducto' => null            
        );
        
        // Forma de Administración en Animales (tabla forma Administración)
        $query = "id_solicitud = $idSolicitud ORDER BY 1 LIMIT 1";
        $formAdmin = $this->buscarLista($query);
        
        if (isset($formAdmin->current()->id_especie_destino)) {
            
            $idEspecie = $formAdmin->current()->id_especie_destino;
            $validacion['nombreEspecie'] = $formAdmin->current()->nombre_especie;
            $validacion['caracteristicasAnimal'] = $formAdmin->current()->caracteristicas_animal;
            $validacion['cantidadProducto'] = $formAdmin->current()->cantidad_producto;
            
            $especies = $this->lNegocioEspecies->buscar($idEspecie);
            
            if (! empty($especies)) {
                $validacion['especie'] = $especies->nombre;
            } else {
                $validacion['especie'] = "NA";
            }
        } else {
            $validacion['especie'] = "NA";
            $validacion['nombreEspecie'] = "NA";
            $validacion['caracteristicasAnimal'] = "NA";
            $validacion['cantidadProducto'] = "NA";
        }
        
        return $validacion;
    }
}
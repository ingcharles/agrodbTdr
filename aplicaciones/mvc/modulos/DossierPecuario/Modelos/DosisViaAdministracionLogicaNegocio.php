<?php
/**
 * Lógica del negocio de DosisViaAdministracionModelo
 *
 * Este archivo se complementa con el archivo DosisViaAdministracionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    DosisViaAdministracionLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;
use Agrodb\Catalogos\Modelos\EspeciesLogicaNegocio;
use Agrodb\Catalogos\Modelos\ViaAdministracionLogicaNegocio;

class DosisViaAdministracionLogicaNegocio implements IModelo
{

    private $modeloDosisViaAdministracion = null;
    
    private $lNegocioEspecies = null;
    private $lNegocioViaAdministracion = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloDosisViaAdministracion = new DosisViaAdministracionModelo();
        
        $this->lNegocioEspecies = new EspeciesLogicaNegocio();
        $this->lNegocioViaAdministracion = new ViaAdministracionLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new DosisViaAdministracionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdDosisViaAdministracion() != null && $tablaModelo->getIdDosisViaAdministracion() > 0) {
            return $this->modeloDosisViaAdministracion->actualizar($datosBd, $tablaModelo->getIdDosisViaAdministracion());
        } else {
            unset($datosBd["id_dosis_via_administracion"]);
            return $this->modeloDosisViaAdministracion->guardar($datosBd);
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
        $this->modeloDosisViaAdministracion->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return DosisViaAdministracionModelo
     */
    public function buscar($id)
    {
        return $this->modeloDosisViaAdministracion->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloDosisViaAdministracion->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloDosisViaAdministracion->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDosisViaAdministracion()
    {
        $consulta = "SELECT * FROM " . $this->modeloDosisViaAdministracion->getEsquema() . ". dosis_via_administracion";
        return $this->modeloDosisViaAdministracion->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos de
     * la dosis y vía de administración
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionDosisViaAdministracion($idSolicitud)
    {
        $consulta = "SELECT
                        dva.*, e.nombre as especie, va.via_administracion
                    FROM
                        g_dossier_pecuario_mvc.dosis_via_administracion dva
			            INNER JOIN g_catalogos.especies e ON e.id_especies = dva.id_especie
                        INNER JOIN g_catalogos.via_administracion va ON va.id_via_administracion = dva.id_via_administracion
                    WHERE
                    	dva.id_solicitud = $idSolicitud;";
        
        return $this->modeloDosisViaAdministracion->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para contar los registros creados
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroRegistrosDosisViaAdministracion($idSolicitud)
    {
        $consulta = "SELECT
                        count(id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.dosis_via_administracion
                    WHERE
                    	id_solicitud = $idSolicitud;";
        
        return $this->modeloDosisViaAdministracion->ejecutarSqlNativo($consulta);
    }

    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosDosisViaAdministracion($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Dosis y Via de Administracion. ",
            'contenido' => null
        );

        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $dosis = $this->buscarLista($query);

        // if(isset($dosis->current()->id_dosis_via_administracion)){
        foreach ($dosis as $dosisViaAdmin) {
            $arrayDosisViaAdmin = array(
                'id_solicitud' => $idSolicitudNueva, // null,//
                'id_especie' => $dosisViaAdmin->id_especie,
                'nombre_especie' => $dosisViaAdmin->nombre_especie,
                'caracteristicas_animal' => $dosisViaAdmin->caracteristicas_animal,
                'id_via_administracion' => $dosisViaAdmin->id_via_administracion,
                'cantidad_dosis' => $dosisViaAdmin->cantidad_dosis,
                'id_unidad_dosis' => $dosisViaAdmin->id_unidad_dosis,
                'nombre_unidad_dosis' => $dosisViaAdmin->nombre_unidad_dosis,
                'cantidad' => $dosisViaAdmin->cantidad,
                'id_unidad' => $dosisViaAdmin->id_unidad,
                'nombre_unidad' => $dosisViaAdmin->nombre_unidad,
                'duracion' => $dosisViaAdmin->duracion,
                'id_unidad_tiempo' => $dosisViaAdmin->id_unidad_tiempo,
                'nombre_unidad_tiempo' => $dosisViaAdmin->nombre_unidad_tiempo
            );

            // echo 'Dosis via administracion';
            // print_r($arrayDosisViaAdmin);

            $idDosisViaAmin = $this->guardar($arrayDosisViaAdmin);

            if ($idDosisViaAmin > 0) {
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Dosis y Via de Administracion. ";
                $validacion['bandera'] = true;
            } else {
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Dosis y Via de Administracion. ";
                $validacion['bandera'] = false;
            }
        }
        /*
         * }else{
         * $validacion['estado'] = "exito";
         * $validacion['mensaje'] = " No existen registros en la tabla Dosis y Via de Administracion. ";
         * $validacion['bandera'] = true;
         * }
         */

        return $validacion;
    }
    
    /**
     * Función para crear el producto en el módulo RIA
     */
    public function obtenerRegistrosDosisViaAdministracionRIA($idSolicitud)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Dosis Via Administracion. ",
            'especie' => null,
            'nombreEspecie' => null,
            'caracteristicasAnimal' => null,
            'viaAdministracion' => null,
            'cantidadDosis' => null,
            'nombreUnidadDosis' => null,
            'cantidad' => null,
            'nombreUnidad' => null,
            'duracion' => null,
            'nombreUnidadTiempo' => null
        );
        
        // Dosis y vías de administración (tabla Dosis Vía Administración)
        $query = "id_solicitud = $idSolicitud ORDER BY 1 LIMIT 1";
        $dosisViaAdmin = $this->buscarLista($query);
        
        if (isset($dosisViaAdmin->current()->id_especie)) {
            $idEspecie = $dosisViaAdmin->current()->id_especie;
            $validacion['nombreEspecie'] = $dosisViaAdmin->current()->nombre_especie;
            $validacion['caracteristicasAnimal'] = $dosisViaAdmin->current()->caracteristicas_animal;
            $idViaAdministracion = $dosisViaAdmin->current()->id_via_administracion;
            $validacion['cantidadDosis'] = $dosisViaAdmin->current()->cantidad_dosis;
            $validacion['nombreUnidadDosis'] = $dosisViaAdmin->current()->nombre_unidad_dosis;
            $validacion['cantidad'] = $dosisViaAdmin->current()->cantidad;
            $validacion['nombreUnidad'] = $dosisViaAdmin->current()->nombre_unidad;
            $validacion['duracion'] = $dosisViaAdmin->current()->duracion;
            $validacion['nombreUnidadTiempo'] = $dosisViaAdmin->current()->nombre_unidad_tiempo;
            
            $especies = $this->lNegocioEspecies->buscar($idEspecie);
            
            if (! empty($especies)) {
                $validacion['especie'] = $especies->nombre;
            } else {
                $validacion['especie'] = "NA";
            }
            
            $viaAdmin = $this->lNegocioViaAdministracion->buscar($idViaAdministracion);
            
            if (! empty($viaAdmin)) {
                $validacion['viaAdministracion'] = $viaAdmin->viaAdministracion;
            } else {
                $validacion['viaAdministracion'] = "NA";
            }
        } else {
            $validacion['especie'] = "NA";
            $validacion['nombreEspecie'] = "NA";
            $validacion['caracteristicasAnimal'] = "NA";
            $validacion['viaAdministracion'] = "NA";
            $validacion['cantidadDosis'] = "NA";
            $validacion['nombreUnidadDosis'] = "NA";
            $validacion['cantidad'] = "NA";
            $validacion['nombreUnidad'] = "NA";
            $validacion['duracion'] = "NA";
            $validacion['nombreUnidadTiempo'] = "NA";
        }
        
        return $validacion;
    }
}
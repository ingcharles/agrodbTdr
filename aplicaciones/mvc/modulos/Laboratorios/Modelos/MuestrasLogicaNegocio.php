<?php

/**
 * Lógica del negocio de  MuestrasModelo
 *
 * Este archivo se complementa con el archivo   MuestrasControlador.
 *
 * @author DATASTAR
 * @uses       MuestrasLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class MuestrasLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new MuestrasModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new MuestrasModelo($datos);
        if ($tablaModelo->getIdMuestra() != null && $tablaModelo->getIdMuestra() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdMuestra());
        } else
        {
            unset($datos["id_muestra"]);
            return $this->modelo->guardar($datos);
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
        $this->modelo->borrar($id);
    }

    public function borrarPorParametro($param, $value)
    {
        $this->modelo->borrarPorParametro($param, $value);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return MuestrasModelo
     */
    public function buscar($id)
    {
        return $this->modelo->buscar($id);
    }

    /**
     * Buscar el registro por un parámetros específico
     * @param array $params
     * @return type
     */
    public function buscarPorParametro(array $params)
    {
        $tbMuestra = $this->modelo->buscarPorParametro($params);
        if ($tbMuestra->getIdPersona() != null)
        {
            $persona = new \Agrodb\laboratorios\Modelos\PersonasLogicaNegocio();
            $tbMuestra->setPersona($persona->buscar($tbMuestra->getIdPersona()));
        }
        return $tbMuestra;
    }

    /**
     * Retorna los registros de las muestras
     * @param type $idSolicitud
     * @param type $idLaboratorio
     * @return type
     */
    public function buscarMuestraLaboratorio($idSolicitud, $idLaboratorio)
    {
        $tbMuestra = $this->modelo->buscarMuestraLaboratorio($idSolicitud, $idLaboratorio);
        if ($tbMuestra->getIdPersona() != null)
        {
            $persona = new \Agrodb\laboratorios\Modelos\PersonasLogicaNegocio();
            $tbMuestra->setPersona($persona->buscar($tbMuestra->getIdPersona()));
        }
        return $tbMuestra;
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modelo->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modelo->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarMuestras()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ".muestras";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Configura la los datos a ser guardados en la tabla muestras
     *
     * @param MuestrasModelo $datos
     *            .Datos enviados desde el formulario
     * @return Array $datos
     */
    public function datosMuestras(MuestrasModelo $datos)
    {
        $datosMuestra = array(
            'id_solicitud' => $datos->getIdSolicitud(),
            'id_localizacion' => ($datos->getIdLocalizacion() > 0) ? $datos->getIdLocalizacion() : NULL,
            'fk_id_localizacion' => ($datos->getFkIdLocalizacion() > 0) ? $datos->getFkIdLocalizacion() : NULL,
            'fk_id_localizacion2' => ($datos->getFkIdLocalizacion2() > 0) ? $datos->getFkIdLocalizacion2() : NULL,
            'id_persona' => $datos->getIdPersona(),
            'referencia_ubicacion' => $datos->getReferenciaUbicacion(),
            'fecha_toma' => $datos->getFechaToma(),
            'fecha_envio' => $datos->getFechaEnvio(),
            'responsable_toma' => $datos->getResponsableToma(),
            'longitud' => $datos->getLongitud(),
            'latitud' => $datos->getLatitud(),
            'altura' => $datos->getAltura(),
            'id_laboratorio' => $datos->getIdLaboratorio()
        );
        return $datosMuestra;
    }

    /**
     * Configura los campos de la tabla de muestras
     */
    public function columnasMuestras()
    {
        $columnasMuestra = array(
            'id_solicitud',
            'id_localizacion',
            'fk_id_localizacion',
            'fk_id_localizacion2',
            'referencia_ubicacion',
            'fecha_toma',
            'fecha_envio',
            'responsable_toma',
            'longitud',
            'latitud',
            'altura',
            'id_laboratorio'
        );
        return $columnasMuestra;
    }

}

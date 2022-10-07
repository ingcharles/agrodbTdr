<?php

/**
 * Lógica del negocio de  PersonasModelo
 *
 * Este archivo se complementa con el archivo   PersonasControlador.
 *
 * @author DATASTAR
 * @uses       PersonasLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class PersonasLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new PersonasModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new PersonasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdPersona() != null && $tablaModelo->getIdPersona() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdPersona());
        } else
        {
            unset($datosBd["id_persona"]);
            return $this->modelo->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return PersonasModelo
     */
    public function buscar($id)
    {
        return $this->modelo->buscar($id);
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
    public function buscarPersonas()
    {
        $consulta = "SELECT * FROM personas";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Busta en g_laboratorios.personas, si existe en g_financiero.cliente lo registra en g_laboratorios.personas
     * @param type $ciRuc
     */
    public function buscarPersona($ciRuc)
    {
        $consulta = "SELECT * FROM g_laboratorios.f_datos_cliente_proforma('$ciRuc');";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Busta en g_laboratorios.personas, si existe en g_financiero.cliente lo registra en g_laboratorios.personas
     * @param type $ciRuc
     */
    public function buscarPersonaOperador($ciRuc)
    {
        $consulta = "SELECT * FROM g_laboratorios.f_datos_operador_proforma('$ciRuc');";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    public function buscarCliente($identificador)
    {
        $consulta = "SELECT
        cl.identificador,
        cl.tipo_identificacion,
        cl.razon_social,
        cl.direccion,
        cl.telefono,
        cl.correo,
        per.id_persona
        FROM
        g_financiero.clientes AS cl
        LEFT JOIN g_laboratorios.personas AS per ON cl.identificador = per.identificador
        WHERE cl.identificador='$identificador'";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Configura la los datos a ser guardados en la tabla muestras
     *
     * @param MuestrasModelo $datos
     *            .Datos enviados desde el formulario
     * @return Array $datos
     */
    public function datosPersona(PersonasModelo $datos)
    {
        $datosPersona = array(
            'identificador' => $datos->getIdentificador(),
            'id_localizacion'=> $datos->getIdLocalizacion(),
            'ci_ruc' => $datos->getCiRuc(),
            'nombre' => $datos->getNombre(),
            'direccion' => $datos->getDireccion(),
            'telefono' => $datos->getTelefono(),
            'email' => $datos->getEmail(),
            'contacto_proforma' => $datos->getContactoProforma(),
            'telefono_proforma' => $datos->getTelefonoProforma()
            
        );
        return $datosPersona;
    }

    /**
     * Columnas para guardar junto con la solicitud
     * @return string[]
     */
    public function columnas()
    {
        $columnas = array(
            'identificador',
            'id_localizacion',
            'ci_ruc',
            'nombre',
            'direccion',
            'telefono',
            'email',
            'contacto_proforma',
            'telefono_proforma',
        );
        return $columnas;
    }

}

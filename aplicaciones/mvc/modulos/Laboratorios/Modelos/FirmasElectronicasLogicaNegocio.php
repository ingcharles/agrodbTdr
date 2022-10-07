<?php

/**
 * Lógica del negocio de  FirmasElectronicasModelo
 *
 * Este archivo se complementa con el archivo   FirmasElectronicasControlador.
 *
 * @author DATASTAR
 * @uses       FirmasElectronicasLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;
use Agrodb\Core\Constantes;

class FirmasElectronicasLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new FirmasElectronicasModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        //Buscamos que no exista otra firma activa

        $where = array("identificador" => $datos['identificador'], "estado" => "ACTIVO");
        $datos = $this->buscarLista($where);
        if ($datos->count() > 0)
        {
            throw new \Exception(Constantes::ERROR_FIRMA_REGISTRADA);
        }

        $tablaModelo = new FirmasElectronicasModelo($datos);
        if ($tablaModelo->getIdFirmaElectronica() != null && $tablaModelo->getIdFirmaElectronica() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdFirmaElectronica());
            unset($datos["contrasenia"]); // La contraseña de registrar desde JAVA, no se puede actualizar 
        } else
        {
            unset($datos["id_firma_electronica"]);
            $token = crypt($tablaModelo->getIdentificador(), '');
            $token = str_replace("/", "A", $token);
            $token = str_replace(".", "8", $token);
            $datos["contrasenia"] = $token;
            ; //Contraseña temporal hasta que el usuario registre la contraseña personal /
            $datos["estado"] = Constantes::ESTADO_PENDIENTE;

            $notificar = new \Agrodb\Correos\Modelos\CorreosLogicaNegocio();
            $id = $this->modelo->guardar($datos);
            $notificar->notificarFirmaElectronica($id, $datos["identificador"], $token);
        }
    }

    /**
     * Cambia el estado del registro
     * @param array $datos
     * @return type
     */
    public function cambiarEstado(Array $datos)
    {

        $tablaModelo = new FirmasElectronicasModelo($datos);

        return $this->modelo->actualizar($datos, $tablaModelo->getIdFirmaElectronica());
    }

    public function reenviarFirma(Array $datos)
    {
        $tablaModelo = $this->buscar($datos["id_firma_electronica"]);
        $token = crypt($tablaModelo->getIdentificador(), '');
        $token = str_replace("/", "A", $token);
        $token = str_replace(".", "8", $token);
        $datos["contrasenia"] = $token;
        ; //Contraseña temporal hasta que el usuario registre la contraseña personal /
        $datos["estado"] = Constantes::ESTADO_PENDIENTE;

        $notificar = new \Agrodb\Correos\Modelos\CorreosLogicaNegocio();
        $this->modelo->actualizar($datos, $tablaModelo->getIdFirmaElectronica());
        $notificar->notificarFirmaElectronica($tablaModelo->getIdFirmaElectronica(), $tablaModelo->getIdentificador(), $token);
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
     * @return FirmasElectronicasModelo
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
     * Buscar firma de acuerdo a la cédula de usuario .
     *
     * @return array|ResultSet
     */
    public function buscarFirmasElectronicas($cedula)
    {

        $consulta = " SELECT
        emp.identificador,
        emp.nombre ||' ' ||emp.apellido as usuario,
        emp.genero,
        fe.estado,
        fe.atributos,
        fe.ruta,
        fe.id_firma_electronica
        FROM
        " . $this->modelo->getEsquema() . ".firmas_electronicas fe
        INNER JOIN g_uath.ficha_empleado emp ON emp.identificador = fe.identificador WHERE fe.estado='ACTIVO' AND  fe.identificador='" . $cedula . "'";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar firma de acuerdo a la contraseña
     * @param type $contrasena
     * @return type
     */
    public function buscarActivarFirma($contrasena)
    {
        $consulta = " SELECT
        emp.identificador,
        emp.nombre ||' ' ||emp.apellido as usuario,
        emp.genero,
        fe.estado,
        fe.atributos,
        fe.ruta,
        fe.id_firma_electronica
        FROM
        " . $this->modelo->getEsquema() . ".firmas_electronicas fe
        INNER JOIN g_uath.ficha_empleado emp ON emp.identificador = fe.identificador WHERE fe.contrasenia='" . $contrasena . "'";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Busca al empleado que tiene la firma
     * @param type $cedula
     * @return type
     */
    public function buscarFirmante()
    {
        $consulta = "SELECT
        fe.identificador,
        per.nombre || ' ' || per.apellido as empleado,
        fe.ruta,
        fe.estado,
        fe.id_firma_electronica,
        fe.contrasenia,
        fe.atributos
        FROM
        g_laboratorios.firmas_electronicas AS fe
        INNER JOIN g_uath.ficha_empleado AS per ON per.identificador = fe.identificador ORDER BY fe.estado DESC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}

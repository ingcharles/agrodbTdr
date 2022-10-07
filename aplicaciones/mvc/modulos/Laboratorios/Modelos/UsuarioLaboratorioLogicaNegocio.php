<?php

/**
 * Lógica del negocio de  UsuarioLaboratorioModelo
 *
 * Este archivo se complementa con el archivo   UsuarioLaboratorioControlador.
 *
 * @author DATASTAR
 * @uses       UsuarioLaboratorioLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class UsuarioLaboratorioLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new UsuarioLaboratorioModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new UsuarioLaboratorioModelo($datos);
        if ($tablaModelo->getIdUsuarioLaboratorio() != null && $tablaModelo->getIdUsuarioLaboratorio() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdUsuarioLaboratorio());
        } else
        {
            unset($datos["id_usuario_laboratorio"]);
            $datos['fecha_registro'] = date('Y-m-d');

            if ($datos['perfil'] == 'Recaudador')
            {
                // verificar que esté habilitado como recaudador en el módulo financiero
                if ($this->buscarRecaudador($datos['identificador']))
                {
                    $this->registrarUsuarioLaboratorio($datos);
                } else
                {
                    Mensajes::fallo(Constantes::INF_USUARIO_FINANCIERO);
                    exit();
                }
            } else if ($datos['perfil'] == 'Guardalmacen')
            {
                $this->registrarUsuarioLaboratorio($datos);
            } else
            {
                //verificar que no existe el registro
                $lNegocioUsuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
                $buscaUsuarioLaboratorio = $lNegocioUsuarioLaboratorio->buscarLista(array(
                    'identificador' => $datos['identificador'],
                    'id_laboratorios_provincia' => $datos['id_laboratorios_provincia'],
                    'id_laboratorio' => $datos['id_laboratorio'],
                    'direccion' => $datos['direccion'],
                    'perfil' => $datos['perfil']));
                if (count($buscaUsuarioLaboratorio) > 0)
                {
                    Mensajes::fallo("El registro ya existe");
                    exit();
                } else
                {
                    $this->modelo->guardar($datos);
                }
            }
            return TRUE;
        }
    }

    /**
     * Registra los datos en g_laboratorios.usuario_laboratorio
     * @param type $datos
     */
    private function registrarUsuarioLaboratorio($datos)
    {
        $idLocalizacion = $datos['provincias'];
        //buscar el laboratorios_provincia segun la localizacion
        $lNegocioLaboratoriosProvincia = new LaboratoriosProvinciaLogicaNegocio();
        $buscaLaboratoriosProvincia = $lNegocioLaboratoriosProvincia->buscarLista(array('id_localizacion' => $idLocalizacion));
        foreach ($buscaLaboratoriosProvincia as $fila)
        {
            //verificar que no existe el registro
            $lNegocioUsuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
            $buscaUsuarioLaboratorio = $lNegocioUsuarioLaboratorio->buscarLista(array(
                'identificador' => $datos['identificador'],
                'id_laboratorios_provincia' => $fila->id_laboratorios_provincia,
                'id_laboratorio' => $fila->id_laboratorio,
                'direccion' => $fila->id_direccion,
                'perfil' => $datos['perfil']));
            if (count($buscaUsuarioLaboratorio) > 0)
            {
                Mensajes::fallo("Algunos registros ya existen");
            } else
            {
                $datosUsuarioLaboratorio = array(
                    'identificador' => $datos['identificador'],
                    'id_laboratorios_provincia' => $fila->id_laboratorios_provincia,
                    'id_laboratorio' => $fila->id_laboratorio,
                    'direccion' => $fila->id_direccion,
                    'perfil' => $datos['perfil'],
                    'estado' => $datos['estado'],
                    'permisos' => $datos['permisos'],
                    'nombre' => $datos['nombre']
                );
                $this->modelo->guardar($datosUsuarioLaboratorio);
            }
        }
    }

    /**
     * Busca si existe recaudador activo
     * @param type $identificador
     * @return boolean
     */
    public function buscarRecaudador($identificador)
    {
        $consulta = "SELECT * FROM g_financiero.distritos d INNER JOIN g_financiero.oficina_recaudacion ofi ON d.ruc = ofi.ruc WHERE d.estado = 'activo' and identificador_firmante = '$identificador'";
        $result = $this->modelo->ejecutarSqlNativo($consulta);
        if (count($result) > 0)
            return true;
        else
            return false;
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
     * @return UsuarioLaboratorioModelo
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
    public function buscarUsuarioLaboratorio($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['fDireccion']))
        {
            $arrayWhere[] = " id_direccion = {$arrayParametros['fDireccion']}";
        }
        if (!empty($arrayParametros['fLaboratorio']))
        {
            $arrayWhere[] = " id_laboratorio = {$arrayParametros['fLaboratorio']}";
        }
        if (!empty($arrayParametros['fUsuario']))
        {
            if (is_numeric($arrayParametros['fUsuario']))
            {
                $arrayWhere[] = " identificador LIKE '%" . $arrayParametros['fUsuario'] . "%'";
            } else
            {
                $arrayWhere[] = " UPPER(usuario) LIKE '%" . strtoupper($arrayParametros['fUsuario']) . "%'";
            }
        }
        if (!empty($arrayParametros['identificador']))
        {
            $arrayWhere[] = " identificador = '{$arrayParametros['identificador']}'";
        }
        if (!empty($arrayParametros['perfil']))
        {
            $arrayWhere[] = " perfil = '{$arrayParametros['perfil']}'";
        }
        if (!empty($arrayParametros['estado'])) //estado de la tabla usuario_laboratorio
        {
            $arrayWhere[] = " estado = '{$arrayParametros['estado']}'";
        }

        if (!empty($arrayParametros['fidLaboratoriosProvincia'])) //estado de la tabla usuario_laboratorio
        {
            $arrayWhere[] = " id_laboratorios_provincia = '{$arrayParametros['fidLaboratoriosProvincia']}'";
        }

        if ($arrayWhere)
        {
            $where = " WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT * FROM g_laboratorios.v_usuario_laboratorio $where"
                . " ORDER BY id_direccion, id_laboratorio, identificador, prov_laboratorio";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Busca los laboratorios del usuario del Analista y RT
     * Usado para mostrar el combo de laboratorios
     * @param type $identificador
     */
    public function buscarLaboratoriosProvinciaUsuario($identificador)
    {
        $consulta = "SELECT 
        identificador, 
        id_laboratorios_provincia, 
        prov_laboratorio, 
        id_laboratorio,
        laboratorio 
        FROM g_laboratorios.v_usuario_laboratorio  
        WHERE  identificador = '$identificador' 
        AND  perfil IN('Responsable Técnico','Analista')
        AND  estado = 'ACTIVO' 
        GROUP BY identificador, id_laboratorios_provincia, prov_laboratorio, id_laboratorio, laboratorio
        ORDER BY identificador, prov_laboratorio";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Busca los laboratorios del usuario del Analista y RT
     * Usado para mostrar el combo de laboratorios
     * @param type $identificador
     */
    public function buscarLaboratoriosUsuario($identificador)
    {
        $consulta = "SELECT identificador, 
        id_laboratorio,
        laboratorio 
        FROM g_laboratorios.v_usuario_laboratorio  
        WHERE  identificador = '$identificador' 
        AND  perfil IN('Responsable Técnico','Analista')
        AND  estado = 'ACTIVO' 
        GROUP BY identificador, id_laboratorio, laboratorio 
        ORDER BY identificador, laboratorio";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los datos del recaudador de provincia
     * @param type $idLaboratorioProvincia
     * @return type
     */
    public function buscarRecaudadorDeProvincia($idLaboratorioProvincia)
    {
        $consulta = "SELECT
        uslab.id_usuario_laboratorio,
        uslab.identificador,
        uslab.id_laboratorios_provincia,
        labprov.id_localizacion,
        uslab.perfil
        FROM
        g_laboratorios.usuario_laboratorio AS uslab
        INNER JOIN g_laboratorios.laboratorios_provincia AS labprov ON labprov.id_laboratorios_provincia = uslab.id_laboratorios_provincia
        WHERE uslab.id_laboratorios_provincia = $idLaboratorioProvincia AND uslab.perfil = 'Recaudador'";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los datos del usuario laboratorio principal
     * @param type $identificador
     * @return type
     */
    public function buscarUsuarioLaboratorioPrincipal($identificador)
    {
        $consulta = "SELECT
        ulab.id_laboratorios_provincia,
        lprov.id_laboratorio,
        lab.nombre AS laboratorio,
        loc.nombre AS prov_laboratorio,
        ulab.identificador,
        lab.codigo_campo,
        lprov.bodega_laboratorios
        FROM
        g_laboratorios.usuario_laboratorio AS ulab
        INNER JOIN g_laboratorios.laboratorios_provincia AS lprov ON lprov.id_laboratorios_provincia = ulab.id_laboratorios_provincia
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = lprov.id_laboratorio
        INNER JOIN g_catalogos.localizacion AS loc ON loc.id_localizacion = lprov.id_localizacion
        WHERE
        ulab.identificador = '$identificador' AND
        lprov.bodega_laboratorios ISNULL";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}

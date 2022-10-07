<?php

/**
 * Lógica del negocio de  ParametrosLaboratoriosModelo
 *
 * Este archivo se complementa con el archivo   ParametrosLaboratoriosControlador.
 *
 * @author DATASTAR
 * @uses       ParametrosLaboratoriosLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;
use Agrodb\Laboratorios\Modelos\LaboratoriosLogicaNegocio;
use Agrodb\Core\Constantes;

class ParametrosLaboratoriosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ParametrosLaboratoriosModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ParametrosLaboratoriosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();

        //Validar que no exista otro párametro con el mismo código del mismo laboratorio
        $where = array("codigo" => $tablaModelo->getCodigo(), "id_laboratorio" => $tablaModelo->getIdLaboratorio());
        $resultado = $this->buscarLista($where);
        $resultado->current();


        if ($tablaModelo->getIdParametrosLaboratorio() != null && $tablaModelo->getIdParametrosLaboratorio() > 0)
        {
            //Validar que no exista otro párametro con el mismo código del mismo laboratorio
            $where = array("codigo" => $tablaModelo->getCodigo(), "id_laboratorio" => $tablaModelo->getIdLaboratorio());
            $resultado = $this->buscarLista($where);
            $resultado->current();
            if ($resultado->count() > 1)
            {
                \Agrodb\Core\Mensajes::fallo('Error: Ya existe un párametro con este código para este laboratorio');
                throw new \Exception('Ya existe un párametro con este código para este laboratorio Clase: ParametrosLaboratoriosLogicaNegocio Método: guardar ');
            }
            //controla que no sea padre así mismo
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdParametrosLaboratorio());
        } else
        {
            if ($resultado->count() > 0)
            {
                \Agrodb\Core\Mensajes::fallo('Error: Ya existe un párametro con este código para este laboratorio');
                throw new \Exception('Ya existe un párametro con este código para este laboratorio Clase: ParametrosLaboratoriosLogicaNegocio Método: guardar ');
            }

            unset($datosBd["id_parametros_laboratorio"]);
            return $this->modelo->guardar($datosBd);
        }
    }

    /**
     * Guardar el formato de las etiquetas de las muestras
     * @param array $datos
     * @return type
     */
    public function guardarEtiquetas(Array $datos)
    {
        $laboratorio = new LaboratoriosLogicaNegocio();
        $datosLab = $laboratorio->buscar($datos['idLaboratorio']);
        $codigoParametro = $datosLab->getCodigo() . Constantes::tipo_parametro()->ETIQUETA_MUESTRA; //formato de la etiqueta del laboratotio seleccionado
        $cadena = $datos['clientesEtiqueta'] . $datos['generalEtiqueta'] . $datos['especificoEtiqueta'] . $datos['analisisEtiqueta'];
        $codigoJson = str_replace("][", ",", $cadena);
        $nombreParametro = "FORMATO DE ETIQUETA DEL " . $datosLab->getNombre();
        $idOrden = $datos['idOrderTrabajo'];
        $datosdb = array("id_direccion" => $datosLab->getFkIdLaboratorio(),
            "id_laboratorio" => $datosLab->getIdLaboratorio(),
            "codigo" => $codigoParametro,
            "nombre" => $nombreParametro,
            "descripcion" => "Parámetro generado por el sistema",
            "valor_aux1" => $datos['clientesEtiqueta'], //campos del cliente
            "valor_aux2" => $datos['generalEtiqueta'] . $datos['especificoEtiqueta'], //campos datos generales de la muestra
            "valor_aux3" => $datos['analisisEtiqueta'], //campos especificos de la muestra
            "atributos_extras" => $codigoJson, //todo junto
            "obligatorio" => "NO");
        if ($datos['id_parametros_laboratorio'] != null && !empty($datos['id_parametros_laboratorio']))
        {
            $datosdb['id_parametros_laboratorio'] = $datos['id_parametros_laboratorio'];
            $this->modelo->actualizar($datosdb, $datos['id_parametros_laboratorio']);
        } else
        {
            $this->modelo->guardar($datosdb);
        }
        //Enviamos a actualizar los codigos en la tabla de muestras recibidas
        $etiquetaMuestra = new \Agrodb\Laboratorios\Modelos\RecepcionMuestrasLogicaNegocio();
        $etiquetaMuestra->crearEtiquetas($codigoJson, $idOrden);
        return true;
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
     * @return ParametrosLaboratoriosModelo
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
    public function buscarParametrosLaboratorios()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". parametros_laboratorios";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Busca los registros hijos
     * @param type $idPadre
     * @return type
     */
    public function buscarCamposResultado($idPadre = null)
    {
        if ($idPadre == null)
        {
            $where = "id_laboratorio IS NULL nombre  by nombre";
        } else
        {
            $where = "id_laboratorio=" . $idPadre . " order  by nombre";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Busca un parámetro de acuerdo al código
     * @param type $codigo
     * @return type
     */
    public function buscarParametro($codigo, $idLaboratorio)
    {
        $where = "codigo ='" . $codigo . "' AND id_laboratorio=" . $idLaboratorio;

        return $this->modelo->buscarLista($where);
    }

    /**
     * Retorna la lista de parametros del servicio
     * @param type $arrayParametros
     * @return type
     */
    public function buscarParametrosL($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_direccion']))
        {
            $arrayWhere[] = " plab.id_direccion = '{$arrayParametros['id_direccion']}'";
        }
        if (!empty($arrayParametros['id_laboratorio']))
        {
            $arrayWhere[] = " plab.id_laboratorio = '{$arrayParametros['id_laboratorio']}'";
        }
        if ($arrayWhere)
        {
            $where = implode(' AND ', $arrayWhere);
        }
        if (!empty($where))
        {
            $where = " WHERE " . $where;
        }
        $consulta = "SELECT
        plab.id_parametros_laboratorio,
        plab.nombre,
        plab.estado,
        dir.nombre AS direccion,
        lab.nombre AS laboratorio
        FROM
        g_laboratorios.parametros_laboratorios AS plab
        INNER JOIN g_laboratorios.laboratorios AS dir ON dir.id_laboratorio = plab.id_direccion
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = plab.id_laboratorio
        $where";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}

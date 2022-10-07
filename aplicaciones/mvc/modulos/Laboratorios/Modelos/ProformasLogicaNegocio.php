<?php

/**
 * Lógica del negocio de  ProformasModelo
 *
 * Este archivo se complementa con el archivo   ProformasControlador.
 *
 * @author DATASTAR
 * @uses       ProformasLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\IModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class ProformasLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ProformasModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        try {

            $proceso = $this->modelo->getAdapter()
                    ->getDriver()
                    ->getConnection();
            if (!$proceso->beginTransaction())
            {
                throw new \Exception('No se pudo iniciar la transacción en: Guardar proforma');
            }
            $idPersona = $datos['id_persona'];
            //si la proforma No es para tercera persona
            if ($datos['opProfTercero'] == 'NO')
            {
                $lNegocioPersonas = new PersonasLogicaNegocio();
                if ($datos['usuario_interno'])
                {
                    //es obligatorio la proforma para un tercero el cual se restringe en la vista
                } else  //si el tipo de usuario es externo se consulta de operador
                {
                    //esta consulta inserta/actualiza los datos del operador de g_laboratorios.personas
                    $buscaPersona = $lNegocioPersonas->buscarPersonaOperador($datos['identificador_usuario'], $datos['contacto_proforma'], $datos['telefono_proforma']);
                    $fila = $buscaPersona->current();
                    if ($fila)
                    {
                        $idPersona = $fila->id_persona;
                    }
                }
            } else
            {    //es para terceta persona
                $datosPersona = new PersonasModelo($datos);
                //insertar los datos de la persona ya que no se encontró los datos (getIdPersona=null)
                if ($datosPersona->getIdPersona() == null || $datosPersona->getIdPersona() == "" || $datosPersona->getIdPersona() <= 0)
                {
                    $lNegocioPersonas = new PersonasLogicaNegocio();
                    $statement = $this->modelo->getAdapter()
                            ->getDriver()
                            ->createStatement();
                    $sqlInsertar = $this->modelo->guardarSql('personas', $this->modelo->getEsquema());
                    $sqlInsertar->columns($lNegocioPersonas->columnas());
                    $sqlInsertar->values($lNegocioPersonas->datosPersona($datosPersona), $sqlInsertar::VALUES_MERGE);
                    $sqlInsertar->prepareStatement($this->modelo->getAdapter(), $statement);
                    $statement->execute();
                    $idPersona = $this->modelo->adapter->driver->getLastGeneratedValue($this->modelo->getEsquema() . '.personas_id_persona_seq');
                    if (!$idPersona)
                    {
                        throw new \Exception('No se registo los datos en la tabla personas');
                    }
                } else
                {

                    $idPersona = $datos['id_persona'];
                }
            }

            //tiempo_estimado en proforma
            $tiempo = $datos['tiempos'];
            $tiempo = max(array_filter(explode(',', $tiempo)));

            //Crear parámetros extras para mostrar en la proforma
            $parametrosImprimir = '[{"codigo":"' . Constantes::PROFORMA_CODIGO
                    . '","revision":"' . Constantes::PROFORMA_REVISION
                    . '","piva"::"IVA ' . Constantes::IVA . '%" }]';
            //buscar datos del laboratorio
            $this->modelo = new ProformasModelo();
            $lNLaboratorios = new LaboratoriosLogicaNegocio();
            $buscaLaboratorio = $lNLaboratorios->buscar($datos['idLaboratorio']);
            $datosProforma = array(
                'codigo_auxiliar' => $datos['codigo_auxiliar'],
                'nom_laboratorio' => $buscaLaboratorio->getNombre(),
                'id_persona' => $idPersona,
                'numero_muestras' => $datos['numero_muestras'],
                'parametros_imprimir' => $parametrosImprimir,
                'tiempo_estimado' => $tiempo
            );
            //buscar si existe un registro con el codigo
            $buscaProforma = $this->modelo->buscarLista(array('codigo_auxiliar' => $datos['codigo_auxiliar']));
            $fila = new ProformasModelo();
            $fila = $buscaProforma->current();
            $idProforma = "";
            if ($fila)
            {
                $idProforma = $fila->id_proforma;
                $this->modelo->actualizar($datosProforma, $fila->id_proforma);
                $this->guardarDetalle($datos, $fila->id_proforma);
            } else
            {
                unset($datosProforma["id_proforma"]);
                $idProforma = $this->modelo->guardar($datosProforma);
                $this->guardarDetalle($datos, $idProforma);
            }
            $proceso->commit();
            return $idProforma;
        } catch (GuardarExcepcion $ex) {
            $proceso->rollback();
            throw new \Exception($ex->getMessage());
        } catch (Exception $exc) {
            $proceso->rollback();
            throw new \Exception($exc->getMessage());
        }
    }

    /**
     * Guardar el detalle de la proforma en g_laboratorios.detalles_proformas
     * @param type $datos
     * @param type $idProforma
     */
    public function guardarDetalle($datos, $idProforma)
    {
        //eliminar detalle de la proforma si existe
        $lNDetalle = new DetallesproformasLogicaNegocio();

        $lNDetalle->borrarPorParametro('id_proforma', $idProforma);

        //guardar el detalle
        $can = $datos['proformaCantidades'];
        $id = array_filter(explode(',', $can));

        foreach ($id as $value)
        {
            $id2 = explode('-', $value);
            $idServicio = $id2[0];
            $cantidad = $id2[1];
            //buscar el valor del servicio
            $buscaValor = $lNDetalle->buscarValor($idServicio);
            $filaValor = $buscaValor->current();
            $datosDetalle = array(
                'id_proforma' => $idProforma,
                'nom_servicio' => $filaValor->rama_nombre,
                'cantidad' => $cantidad,
                'precio_unitario' => $filaValor->valor,
                'precio_total' => $cantidad * $filaValor->valor
            );
            $lNDetalle->guardar($datosDetalle);
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
     * @return ProformasModelo
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
    public function buscarProformas()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". proformas";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}

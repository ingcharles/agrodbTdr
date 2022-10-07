<?php
/**
 * Lógica del negocio de PeriodoRetiroModelo
 *
 * Este archivo se complementa con el archivo PeriodoRetiroControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    PeriodoRetiroLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;
use Agrodb\Catalogos\Modelos\EspeciesLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosConsumiblesLogicaNegocio;

class PeriodoRetiroLogicaNegocio implements IModelo
{

    private $modeloPeriodoRetiro = null;

    private $lNegocioEspecies = null;

    private $lNegocioProductosConsumibles = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloPeriodoRetiro = new PeriodoRetiroModelo();
        $this->lNegocioEspecies = new EspeciesLogicaNegocio();
        $this->lNegocioProductosConsumibles = new ProductosConsumiblesLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new PeriodoRetiroModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdPeriodoRetiro() != null && $tablaModelo->getIdPeriodoRetiro() > 0) {
            return $this->modeloPeriodoRetiro->actualizar($datosBd, $tablaModelo->getIdPeriodoRetiro());
        } else {
            unset($datosBd["id_periodo_retiro"]);
            return $this->modeloPeriodoRetiro->guardar($datosBd);
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
        $this->modeloPeriodoRetiro->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return PeriodoRetiroModelo
     */
    public function buscar($id)
    {
        return $this->modeloPeriodoRetiro->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloPeriodoRetiro->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloPeriodoRetiro->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarPeriodoRetiro()
    {
        $consulta = "SELECT * FROM " . $this->modeloPeriodoRetiro->getEsquema() . ". periodo_retiro";
        return $this->modeloPeriodoRetiro->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos del período de retiro
     * con la información de los items del catálogo
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionPeriodoRetiro($idSolicitud)
    {
        $consulta = "SELECT
			             pr.*, e.nombre as especie, pc.producto_consumible as producto_consumo
                    FROM
                         g_dossier_pecuario_mvc.periodo_retiro pr
                         INNER JOIN g_catalogos.especies e ON pr.id_especie = e.id_especies
			             INNER JOIN g_catalogos.productos_consumibles pc ON pr.id_producto_consumo = pc.id_producto_consumible
                    WHERE
                    	pr.id_solicitud = $idSolicitud
                    ORDER BY
                        especie, producto_consumo;";

        return $this->modeloPeriodoRetiro->ejecutarSqlNativo($consulta);
    }

    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosPeriodoRetiro($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Periodo Retiro. ",
            'contenido' => null
        );

        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $perRet = $this->buscarLista($query);

        foreach ($perRet as $periodoRet) {
            $arrayPeriodoRetiro = array(
                'id_solicitud' => $idSolicitudNueva, // null,//
                'id_especie' => $periodoRet->id_especie,
                'nombre_especie' => $periodoRet->nombre_especie,
                'id_producto_consumo' => $periodoRet->id_producto_consumo,
                'tiempo_retiro' => $periodoRet->tiempo_retiro,
                'id_unidad_tiempo' => $periodoRet->id_unidad_tiempo,
                'nombre_unidad_tiempo' => $periodoRet->nombre_unidad_tiempo
            );

            // echo 'Periodo Retiro';
            // print_r($arrayPeriodoRetiro);

            $idPerRetiro = $this->guardar($arrayPeriodoRetiro);

            if ($idPerRetiro > 0) {
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Periodo Retiro. ";
                $validacion['bandera'] = true;
            } else {
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Periodo Retiro. ";
                $validacion['bandera'] = false;
            }
        }

        return $validacion;
    }

    /**
     * Función para crear el producto en el módulo RIA
     */
    public function obtenerRegistrosPeriodoRetiroRIA($idSolicitud)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Periodo Retiro. ",
            'especie' => null,
            'nombreEspecie' => null,
            'productoConsumo' => null,
            'tiempoRetiro' => null,
            'nombreUnidadtiempo' => null
        );

        // Período de retiro (tabla Periodo Retiro)
        $query = "id_solicitud = $idSolicitud ORDER BY 1 LIMIT 1";
        $perRetiro = $this->buscarLista($query);

        if (isset($perRetiro->current()->id_periodo_retiro)) {

            $idEspecie = $perRetiro->current()->id_especie;
            $validacion['nombreEspecie'] = $perRetiro->current()->nombre_especie;
            $idProductoConsumo = $perRetiro->current()->id_producto_consumo;
            $validacion['tiempoRetiro'] = $perRetiro->current()->tiempo_retiro;
            $validacion['nombreUnidadTiempo'] = $perRetiro->current()->nombre_unidad_tiempo;

            $especies = $this->lNegocioEspecies->buscar($idEspecie);

            if (! empty($especies)) {
                $validacion['especie'] = $especies->nombre;
            } else {
                $validacion['especie'] = "NA";
            }

            $productos = $this->lNegocioProductosConsumibles->buscar($idProductoConsumo);

            if (! empty($productos)) {
                $validacion['productoConsumo'] = $productos->productoConsumible;
            } else {
                $validacion['productoConsumo'] = "NA";
            }
        } else {
            $validacion['especie'] = "NA";
            $validacion['nombreEspecie'] = "NA";
            $validacion['productoConsumo'] = "NA";
            $validacion['tiempoRetiro'] = "NA";
            $validacion['nombreUnidadTiempo'] = "NA";
        }

        return $validacion;
    }
}
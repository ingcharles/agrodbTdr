<?php
/**
 * Lógica del negocio de SolicitudesProductosModelo
 *
 * Este archivo se complementa con el archivo SolicitudesProductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    SolicitudesProductosLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */

namespace Agrodb\ModificacionProductoRia\Modelos;

use Agrodb\Catalogos\Modelos\CodigosInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\ComposicionInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\FabricanteFormuladorLogicaNegocio;
use Agrodb\Catalogos\Modelos\ManufacturadorLogicaNegocio;
use Agrodb\Catalogos\Modelos\PresentacionesPlaguicidasLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductoInocuidadUsoLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoModificacionProductoLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\UsosProductosPlaguicidasLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\Core\JasperReport;
use Agrodb\FirmaDocumentos\Modelos\DocumentosLogicaNegocio;
use Agrodb\ModificacionProductoRia\Modelos\IModelo;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\Catalogos\Modelos\CategoriaToxicologicaLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
use Agrodb\Correos\Modelos\CorreosLogicaNegocio;

class SolicitudesProductosLogicaNegocio implements IModelo
{

    private $modeloSolicitudesProductos = null;
    private $rutaFecha = null;
	private $lNegocioCorreos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloSolicitudesProductos = new SolicitudesProductosModelo();
		$this->lNegocioCorreos = new CorreosLogicaNegocio();
        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        try {

            $numeroSolicitud = $this->generarNumeroSolicitud();
            $numeroSolicitud->current()->f_generar_numero_solicitud;

            $datos['numero_solicitud'] = $numeroSolicitud->current()->f_generar_numero_solicitud;

            $tablaModelo = new SolicitudesProductosModelo($datos);

            $procesoIngreso = $this->modeloSolicitudesProductos->getAdapter()
                ->getDriver()
                ->getConnection();
            $procesoIngreso->beginTransaction();

            $datosBd = $tablaModelo->getPrepararDatos();
            if ($tablaModelo->getIdSolicitudProducto() != null && $tablaModelo->getIdSolicitudProducto() > 0) {
                $idSolicitudModificacionProducto = $this->modeloSolicitudesProductos->actualizar($datosBd, $tablaModelo->getIdSolicitudProducto());
            } else {
                unset($datosBd["id_solicitud_producto"]);
                $idSolicitudModificacionProducto = $this->modeloSolicitudesProductos->guardar($datosBd);
            }

            $statement = $this->modeloSolicitudesProductos->getAdapter()
                ->getDriver()
                ->createStatement();

            for ($i = 0; $i < count($datos['id_tipo_modificacion_producto']); $i++) {
                $datosDetalle = array(
                    'id_solicitud_producto' => (integer)$idSolicitudModificacionProducto,
                    'id_tipo_modificacion_producto' => $datos['id_tipo_modificacion_producto'][$i],
                    'tipo_modificacion' => $datos['tipo_modificacion'][$i],
                    'tiempo_atencion' => $datos['tiempo_atencion'][$i]
                );

                $sqlInsertar = $this->modeloSolicitudesProductos->guardarSql('detalle_solicitudes_productos', $this->modeloSolicitudesProductos->getEsquema());
                $sqlInsertar->columns(array_keys($datosDetalle));
                $sqlInsertar->values($datosDetalle, $sqlInsertar::VALUES_MERGE);
                $sqlInsertar->prepareStatement($this->modeloSolicitudesProductos->getAdapter(), $statement);
                $statement->execute();
            }

            $procesoIngreso->commit();
            return $idSolicitudModificacionProducto;
        } catch (GuardarExcepcion $ex) {
            $procesoIngreso->rollback();
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Guarda el registro de finalizacion de la solicitud
     *
     * @param array $datos
     * @return int
     */
    public function guardarFinalizarSolitud(array $datos)
    {
        try {
            $tablaModelo = new SolicitudesProductosModelo($datos);

            $procesoIngreso = $this->modeloSolicitudesProductos->getAdapter()
                ->getDriver()
                ->getConnection();
            $procesoIngreso->beginTransaction();

            $datosBd = $tablaModelo->getPrepararDatos();
            if ($tablaModelo->getIdSolicitudProducto() != null && $tablaModelo->getIdSolicitudProducto() > 0) {
                $idSolicitudModificacionProducto = $this->modeloSolicitudesProductos->actualizar($datosBd, $tablaModelo->getIdSolicitudProducto());
            }

            $procesoIngreso->commit();
            return $idSolicitudModificacionProducto;
        } catch (GuardarExcepcion $ex) {
            $procesoIngreso->rollback();
            throw new \Exception($ex->getMessage());
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
        $this->modeloSolicitudesProductos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return SolicitudesProductosModelo
     */
    public function buscar($id)
    {
        return $this->modeloSolicitudesProductos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloSolicitudesProductos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloSolicitudesProductos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudesProductos()
    {
        $consulta = "SELECT * FROM " . $this->modeloSolicitudesProductos->getEsquema() . ". solicitudes_productos";
        return $this->modeloSolicitudesProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Consulta datos del operador .
     *
     * @return Operadores
     */
    public function obtenerDatosOperador($identificador)
    {
        $lNegocioOperadores = new OperadoresLogicaNegocio();

        $operador = $lNegocioOperadores->buscar($identificador);

        return $operador;
    }

    /**
     * Consulta tipos de modificacion por producto.
     *
     * @return array|ResultSet
     */
    public function obtenerTipoModificacionProducto($idArea)
    {
        $lNegocioTipoModificacionProducto = new TipoModificacionProductoLogicaNegocio();

        $datos = [
            'id_area' => $idArea
        ];

        $tipoModificacion = $lNegocioTipoModificacionProducto->buscarLista($datos, 'tipo_modificacion');

        return $tipoModificacion;
    }

    /**
     * Genera nuero de soliciud.
     *
     * @return array|ResultSet
     */
    public function generarNumeroSolicitud()
    {
        $consulta = "SELECT * FROM g_modificacion_productos.f_generar_numero_solicitud();";

        return $this->modeloSolicitudesProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Consulta si todas las modificaciones poseen registros.
     *
     * @return array|ResultSet
     */
    public function verificarRegistrosSoliciud($idSolicitudModificacionProducto)
    {
        $consulta = "SELECT * FROM g_modificacion_productos.f_verificar_registros($idSolicitudModificacionProducto)";
        return $this->modeloSolicitudesProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una para obtener los datos ctuales de productos
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionActualPorIdProducto($arayParametros)
    {
        $tipoModificacion = $arayParametros['tipo_modificacion'];

        $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();

        $qDatoActual = $lNegocioProductosInocuidad->buscarLista(array('id_producto' => $arayParametros['id_producto']));


        switch ($tipoModificacion) {

            case 'modificacionCategoriaToxicologica':
                $datoAtual = $qDatoActual->current()->id_categoria_toxicologica;
                break;

            case 'modificacionPeriodoReingreso':
                $datoAtual = $qDatoActual->current()->periodo_reingreso;
                break;
        }

        return $datoAtual;

    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudesProductoXFiltro($arrayParametros)
    {
        $busqueda = '';

        $tipo = $arrayParametros['tipo'];
        $nombreProvincia = $arrayParametros['nombre_provincia'];
        $tipoSolicitud = $arrayParametros['tipo_solicitud'];
        $busquedaOperador = $arrayParametros['busqueda'];
        $identificadorRevisto = $arrayParametros['identificador_revisor'];
        $estadoSolicitudProducto = $arrayParametros['estado_solicitud_producto'];

        if ($tipo === 'razonSocial') {
            if (isset($busquedaOperador) && ($busquedaOperador != '')) {
                $busqueda .= " and psp.razon_social = '" . $busquedaOperador . "' ";
            }
        } else {
            if (isset($busquedaOperador) && ($busquedaOperador != '')) {
                $busqueda .= " and psp.identificador_operador = '" . $busquedaOperador . "' ";
            }
        }

        if (isset($nombreProvincia) && ($nombreProvincia)) {
            $busqueda .= " and psp.provincia_operador = '" . $nombreProvincia . "'";
        }

        if (isset($identificadorRevisto) && ($identificadorRevisto != '')) {
            $busqueda .= " and psp.identificador_revisor = '" . $identificadorRevisto . "'";
        }

        $consulta = "  SELECT
                        	psp.id_solicitud_producto,
                        	psp.numero_solicitud,
                        	psp.identificador_operador,
                        	psp.razon_social,
                        	psp.id_producto,          	
                        	psp.provincia_operador,
                        	psp.identificador_revisor,
                        	psp.id_area,
                        	p.nombre_comun,
                        	fe.nombre ||' '||fe.apellido as nombre_revisor 
                        FROM
                        	g_modificacion_productos.solicitudes_productos psp
                        	INNER JOIN g_catalogos.productos p ON p.id_producto = psp.id_producto 
                            LEFT JOIN g_uath.ficha_empleado fe ON fe.identificador = psp.identificador_revisor
                        WHERE
                            psp.id_area = '$tipoSolicitud' and
                            psp.estado_solicitud_producto IN ($estadoSolicitudProducto) 
                            " . $busqueda . "
                        ORDER BY
                            psp.id_solicitud_producto;";

        return $this->modeloSolicitudesProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para actualizar
     * los datos del estado de la solicitud.
     *
     * @return array|ResultSet
     */
    public function actualizarEstadoModificacionProducto($arrayParametros)
    {
        $idSolicitudProducto = $arrayParametros['id_solicitud_producto'];
        $estadoSolicitudProducto = $arrayParametros['estado_solicitud_producto'];
        $identificadorRevisor = $arrayParametros['idenitificador_revisor'];

        $consulta = "UPDATE
                    	g_modificacion_productos.solicitudes_productos
                    SET
                    	estado_solicitud_producto = '" . $estadoSolicitudProducto . "',
                    	identificador_revisor = '" . $identificadorRevisor . "'
                    WHERE
                    	id_solicitud_producto = '" . $idSolicitudProducto . "';";

        return $this->modeloSolicitudesProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function obtenerSolicitudesProductos()
    {
        $consulta = "SELECT
                        sp.id_solicitud_producto
                        , sp.numero_solicitud
                        , sp.identificador_operador
                        , sp.razon_social
                        , sp.id_area
                        , a.nombre AS nombre_area_tematica
                        , sp.id_producto
                        , p.nombre_comun AS nombre_producto
                        , sp.estado_solicitud_producto
                        , sp.fecha_creacion
                     FROM 
                        g_modificacion_productos.solicitudes_productos sp
                        INNER JOIN g_estructura.area a ON sp.id_area = a.id_area
                        INNER JOIN g_catalogos.productos p ON p.id_producto = sp.id_producto
                    ORDER BY sp.numero_solicitud DESC";

        return $this->modeloSolicitudesProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para actualizar
     * los datos del estado de la solicitud.
     *
     * @return array|ResultSet
     */
    public function actualizarDatosRevisionTecnica($arrayParametros)
    {
        $idSolicitudProducto = $arrayParametros['id_solicitud_producto'];
        $estadoSolicitudProducto = $arrayParametros['estado_solicitud_producto'];
        $identificadorRevisor = $arrayParametros['idenitificador_revisor'];
        $observacionRevisor = $arrayParametros['observacion_revisor'];
        $rutaRevisor = $arrayParametros['ruta_revisor'];
        $rutaCertificado = $arrayParametros['ruta_certificado'] === '' ? null : $arrayParametros['ruta_certificado'];
        $fechaActualizar = "fecha_aprobacion = 'now()'";

        if ($estadoSolicitudProducto === 'subsanacion') {
            $fechaActualizar = "fecha_subsanacion = 'now()'";
        }

        $consulta = "UPDATE
                    	g_modificacion_productos.solicitudes_productos
                    SET
                    	estado_solicitud_producto = '" . $estadoSolicitudProducto . "',
                    	identificador_revisor = '" . $identificadorRevisor . "',
                    	observacion_revisor = '" . $observacionRevisor . "',
                    	ruta_revisor = '" . $rutaRevisor . "',
                    	ruta_certificado = '" . $rutaCertificado . "',
                    	" . $fechaActualizar . "
                    WHERE
                    	id_solicitud_producto = '" . $idSolicitudProducto . "';";

        return $this->modeloSolicitudesProductos->ejecutarSqlNativo($consulta);
    }

    public function guardarDatosProductoOrigen($idSolicitudProducto, $rutaResultadoRevision)
    {

        $solicitudProducto = $this->buscar($idSolicitudProducto);

        $idArea = $solicitudProducto->getIdArea();
        $idProducto = $solicitudProducto->getIdProducto();

        $lNegocioDetalleSolicitudesProducto = new DetalleSolicitudesProductosLogicaNegocio();

        $tiposModificacion = $lNegocioDetalleSolicitudesProducto->obtenerDetallesSolicitudesModificacionProducto($idSolicitudProducto);


        foreach ($tiposModificacion as $tipoModificacion) {

            $modificacion = $tipoModificacion->codigo_modificacion;
            $idDetalleSolicitudProducto = $tipoModificacion->id_detalle_solicitud_producto;

            $datosModificacion = [
                'id_detalle_solicitud_producto' => $idDetalleSolicitudProducto
            ];

            switch ($idArea) {
                case 'IAP':
                    switch ($modificacion) {
                        case 'modificarCategoriaToxicologica':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioCategoriasToxicologicas = new CategoriasToxicologicasLogicaNegocio();

                            $datosCategoria = $lNegocioCategoriasToxicologicas->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'id_categoria_toxicologica' => $datosCategoria->current()->id_tabla_origen,
                                'categoria_toxicologica' => $datosCategoria->current()->categoria_toxicologica,
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                        case 'modificarPeriodoReingreso':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioPeriodosRegingresos = new PeriodosReingresosLogicaNegocio();

                            $datosPeriodoReingreso = $lNegocioPeriodosRegingresos->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'periodo_reingreso' => $datosPeriodoReingreso->current()->periodo_reingreso,
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);

                            break;
                        case 'modificarVidaUtil':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioVidasUtiles = new VidasUtilesLogicaNegocio();

                            $datosVidaUtil = $lNegocioVidasUtiles->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'estabilidad' => $datosVidaUtil->current()->estabilidad,
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                        case 'modificarEstadoRegistro':
                            $lNegocioProductos = new ProductosLogicaNegocio();
                            $lNegocioEstadosRegistros = new EstadosRegistrosLogicaNegocio();

                            $datosEstadoRegistro = $lNegocioEstadosRegistros->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'estado' => $datosEstadoRegistro->current()->estado,
                            ];

                            $lNegocioProductos->guardar($datosActualizacion);
                            break;
                        case 'modificarAdicionPresentacionPlaguicida':
                            $lNegocioPresentacionesPlaguicidas = new PresentacionesPlaguicidasLogicaNegocio();
                            $lNegocioAdicionesPresentacionesPlaguicidas = new AdicionesPresentacionesPlaguicidasLogicaNegocio();

                            $datosPresentacionesPlaguicidas = $lNegocioAdicionesPresentacionesPlaguicidas->buscarLista($datosModificacion);

                            foreach ($datosPresentacionesPlaguicidas as $presentacionPlaguicida) {
                                $idTablaOrigen = $presentacionPlaguicida->id_tabla_origen;
                                if ($idTablaOrigen) {
                                    $datosActualizacion = [
                                        'id_presentacion' => $idTablaOrigen,
                                        'estado' => $presentacionPlaguicida->estado,
                                    ];
                                } else {

                                    $datosActualizacion = [
                                        'id_codigo_comp_supl' => $presentacionPlaguicida->id_codigo_comp_supl,
                                        'codigo_presentacion' => $presentacionPlaguicida->subcodigo,
                                        'presentacion' => $presentacionPlaguicida->presentacion,
                                        'id_unidad' => $presentacionPlaguicida->id_unidad_medida,
                                        'unidad' => $presentacionPlaguicida->unidad_medida,
                                    ];
                                }
                                $lNegocioPresentacionesPlaguicidas->guardar($datosActualizacion);
                            }
                            break;
                        case 'modificarTitularidadProducto':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioTitularesProductos = new TitularesProductosLogicaNegocio();

                            $datosTitularesProducto = $lNegocioTitularesProductos->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'id_operador' => $datosTitularesProducto->current()->identificador_operador,
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                        case 'modificarFabricanteFormulador':
                            $lNegocioFabricanteFormulador = new FabricanteFormuladorLogicaNegocio();
                            $lNegocioFabricantesFormuladores = new FabricantesFormuladoresLogicaNegocio();

                            $datosFabricantesFormuladores = $lNegocioFabricantesFormuladores->buscarLista($datosModificacion);

                            foreach ($datosFabricantesFormuladores as $fabricanteFormulador) {
                                $idTablaOrigen = $fabricanteFormulador->id_tabla_origen;
                                if ($idTablaOrigen) {
                                    $datosActualizacion = [
                                        'id_fabricante_formulador' => $idTablaOrigen,
                                        'estado' => $fabricanteFormulador->estado,
                                    ];
                                } else {
                                    $datosActualizacion = [
                                        'id_producto' => $idProducto,
                                        'nombre' => $fabricanteFormulador->nombre,
                                        'tipo' => $fabricanteFormulador->tipo,
                                        'id_pais_origen' => $fabricanteFormulador->id_pais_origen,
                                        'pais_origen' => $fabricanteFormulador->nombre_pais_origen
                                    ];
                                }
                                $lNegocioFabricanteFormulador->guardar($datosActualizacion);
                            }
                            break;
                        case 'modificarUso':
                            $lNegocioUsosProductosPlaguicidas = new UsosProductosPlaguicidasLogicaNegocio();
                            $lNegocioUsos = new UsosLogicaNegocio();

                            $datosUsos = $lNegocioUsos->buscarLista($datosModificacion);

                            foreach ($datosUsos as $uso) {
                                $idTablaOrigen = $uso->id_tabla_origen;
                                $estado = $uso->estado;
                                if ($idTablaOrigen) {
                                    if ($estado === 'inactivo') {
                                        $lNegocioUsosProductosPlaguicidas->borrar($idTablaOrigen);
                                    }
                                } else {
                                    $datosActualizacion = [
                                        'id_producto' => $idProducto,
                                        'id_plaga' => $uso->id_plaga,
                                        'plaga_nombre_comun' => $uso->nombre_plaga,
                                        'plaga_nombre_cientifico' => $uso->nombre_cientifico_plaga,
                                        'id_cultivo' => $uso->id_cultivo,
                                        'cultivo_nombre_comun' => $uso->nombre_cultivo,
                                        'cultivo_nombre_cientifico' => $uso->nombre_cientifico_cultivo,
                                        'dosis' => $uso->dosis,
                                        'unidad_dosis' => $uso->unidad_dosis,
                                        'periodo_carencia' => $uso->periodo_carencia,
                                        'gasto_agua' => $uso->gasto_agua,
                                        'unidad_gasto_agua' => $uso->unidad_gasto_agua
                                    ];

                                    $lNegocioUsosProductosPlaguicidas->guardar($datosActualizacion);
                                }
                            }
                            break;
                        case 'modificarManufacturador':
                            $lNegocioManufacturador = new ManufacturadorLogicaNegocio();
                            $lNegocioManufacturadores = new ManufacturadoresLogicaNegocio();

                            $datosManufacturadores = $lNegocioManufacturadores->buscarLista($datosModificacion);

                            foreach ($datosManufacturadores as $manufacturador) {
                                $idTablaOrigen = $manufacturador->id_tabla_origen;
                                if ($idTablaOrigen) {
                                    $datosActualizacion = [
                                        'id_manufacturador' => $idTablaOrigen,
                                        'estado' => $manufacturador->estado,
                                    ];
                                } else {
                                    $datosActualizacion = [
                                        'id_fabricante_formulador' => $manufacturador->id_fabricante_formulador,
                                        'manufacturador' => $manufacturador->manufacturador,
                                        'id_pais_origen' => $manufacturador->id_pais_origen,
                                        'pais_origen' => $manufacturador->pais_origen
                                    ];
                                }
                                $lNegocioManufacturador->guardar($datosActualizacion);
                            }
                            break;
                    }
                    break;
                case 'IAV':
                    switch ($modificacion) {
                        case 'modificarCategoriaToxicologica':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioCategoriasToxicologicas = new CategoriasToxicologicasLogicaNegocio();

                            $datosCategoria = $lNegocioCategoriasToxicologicas->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'id_categoria_toxicologica' => $datosCategoria->current()->id_tabla_origen,
                                'categoria_toxicologica' => $datosCategoria->current()->categoria_toxicologica,
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                        case 'modificarViaAdmimistracionDosis':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioViasAdministracionesDosis = new ViasAdministracionesDosisLogicaNegocio();

                            $datosViaAdministracionDosis = $lNegocioViasAdministracionesDosis->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'dosis' => $datosViaAdministracionDosis->current()->dosis,
                                'unidad_dosis' => $datosViaAdministracionDosis->current()->unidad_dosis,
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                        case 'modificarPeriodoRetiro':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioPeriodosRetiros = new PeriodosRetirosLogicaNegocio();

                            $datosPeriodoRetiro = $lNegocioPeriodosRetiros->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'periodo_carencia_retiro' => $datosPeriodoRetiro->current()->periodo_retiro
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                        case 'modificarNombreComercial':
                            $lNegocioProductos = new ProductosLogicaNegocio();
                            $lNegocioNombresComerciales = new NombresComercialesLogicaNegocio();

                            $datosNombreComercial = $lNegocioNombresComerciales->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'nombre_comun' => $datosNombreComercial->current()->nombre_comercial,
                            ];

                            $lNegocioProductos->guardar($datosActualizacion);
                            break;
                        case 'modificarAdicionPresentacion':
                            $lNegocioCodigosInocuidad = new CodigosInocuidadLogicaNegocio();
                            $lNegocioAdicionesPresentaciones = new AdicionesPresentacionesLogicaNegocio();

                            $datosAdicionesPresentaciones = $lNegocioAdicionesPresentaciones->buscarLista($datosModificacion);

                            foreach ($datosAdicionesPresentaciones as $adicionPresentacion) {
                                $estado = $adicionPresentacion->estado;
                                $subCodigo = $adicionPresentacion->subcodigo;

                                $datos = [
                                    'id_producto' => $idProducto,
                                    'subcodigo' => $subCodigo
                                ];

                                $verificaRegistro = $lNegocioCodigosInocuidad->buscarLista($datos);

                                if(!empty($verificaRegistro->current())){
                                    if ($estado === 'inactivo') {
                                        $lNegocioCodigosInocuidad->borrarRegistro($datos);
                                    }
                                } else {
                                    $datosActualizacion = [
                                        'id_producto' => $idProducto,
                                        'subcodigo' => $subCodigo,
                                        'presentacion' => $adicionPresentacion->presentacion,
                                        'unidad_medida' => $adicionPresentacion->unidad_medida,
                                    ];
                                    $lNegocioCodigosInocuidad->guardarProductoRIA($datosActualizacion);
                                }
                            }
                            break;
                        case 'modificarTitularidadProducto':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioTitularesProductos = new TitularesProductosLogicaNegocio();

                            $datosTitularesProducto = $lNegocioTitularesProductos->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'id_operador' => $datosTitularesProducto->current()->identificador_operador,
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                        case 'modificarFabricanteFormulador':
                            $lNegocioFabricanteFormulador = new FabricanteFormuladorLogicaNegocio();
                            $lNegocioFabricantesFormuladores = new FabricantesFormuladoresLogicaNegocio();

                            $datosFabricantesFormuladores = $lNegocioFabricantesFormuladores->buscarLista($datosModificacion);

                            foreach ($datosFabricantesFormuladores as $fabricanteFormulador) {
                                $idTablaOrigen = $fabricanteFormulador->id_tabla_origen;
                                $estado = $fabricanteFormulador->estado;
                                if ($idTablaOrigen) {
                                    if ($estado === 'inactivo') {
                                        $lNegocioFabricanteFormulador->borrar($idTablaOrigen);
                                    }
                                } else {
                                    $datosActualizacion = [
                                        'id_producto' => $idProducto,
                                        'nombre' => $fabricanteFormulador->nombre,
                                        'tipo' => $fabricanteFormulador->tipo,
                                        'id_pais_origen' => $fabricanteFormulador->id_pais_origen,
                                        'pais_origen' => $fabricanteFormulador->nombre_pais_origen
                                    ];
                                    $lNegocioFabricanteFormulador->guardar($datosActualizacion);
                                }
                            }
                            break;
                        case 'modificarUso':
                            $lNegocioProductoInocuidadUso = new ProductoInocuidadUsoLogicaNegocio();
                            $lNegocioUsos = new UsosLogicaNegocio();

                            $datosUsos = $lNegocioUsos->buscarLista($datosModificacion);

                            foreach ($datosUsos as $uso) {
                                $idTablaOrigen = $uso->id_tabla_origen;
                                $estado = $uso->estado;
                                if ($idTablaOrigen) {
                                    if ($estado === 'inactivo') {
                                        $lNegocioProductoInocuidadUso->borrar($idTablaOrigen);
                                    }
                                } else {
                                    $datosActualizacion = [
                                        'id_producto' => $idProducto,
                                        'id_uso' => $uso->id_uso_producto,
                                        'nombre_especie' => $uso->nombre_especie,
                                        'aplicado_a' => $uso->aplicado_a,
                                        'instalacion' => $uso->instalacion,
                                    ];

                                    if($uso->id_especie){
                                        $datosActualizacion += [
                                            'id_especie' => $uso->id_especie
                                        ];
                                    }

                                    $lNegocioProductoInocuidadUso->guardar($datosActualizacion);
                                }
                            }
                            break;
                        case 'modificarComposicion':
                            $lNegocioComposicionInocuidad = new ComposicionInocuidadLogicaNegocio();
                            $lNegocioComposiciones = new ComposicionesLogicaNegocio();

                            $datosComposiciones = $lNegocioComposiciones->buscarLista($datosModificacion);

                            foreach ($datosComposiciones as $composicion) {
                                $idTablaOrigen = $composicion->id_tabla_origen;
                                $estado = $composicion->estado;
                                if ($idTablaOrigen) {
                                    if ($estado === 'inactivo') {
                                        $lNegocioComposicionInocuidad->borrar($idTablaOrigen);
                                    }
                                } else {
                                    $datosActualizacion = [
                                        'id_producto' => $idProducto,
                                        'id_ingrediente_activo' => $composicion->id_ingrediente_activo,
                                        'concentracion' => $composicion->concentracion,
                                        'ingrediente_activo' => $composicion->ingrediente_activo,
                                        'unidad_medida' => $composicion->unidad_medida,
                                        'id_tipo_componente' => $composicion->id_tipo_componente,
                                        'tipo_componente' => $composicion->tipo_componente
                                    ];

                                    $lNegocioComposicionInocuidad->guardar($datosActualizacion);
                                }
                            }
                            break;
                        case 'modificarEtiqueta':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'ruta_etiqueta' => $solicitudProducto->getRutaEtiquetaProducto(),
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                        case 'modificarDeclaracionVenta':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioDenominacionesVentas = new DenominacionesVentasLogicaNegocio();

                            $datosDenominacionesVentas = $lNegocioDenominacionesVentas->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'id_declaracion_venta' => $datosDenominacionesVentas->current()->id_declaracion_venta,
                                'declaracion_venta' => $datosDenominacionesVentas->current()->declaracion_venta
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                    }
                    break;
                case 'IAF':
                    switch ($modificacion) {
                        case 'modificarEstadoRegistro':
                            $lNegocioProductos = new ProductosLogicaNegocio();
                            $lNegocioEstadosRegistros = new EstadosRegistrosLogicaNegocio();

                            $datosEstadoRegistro = $lNegocioEstadosRegistros->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'estado' => $datosEstadoRegistro->current()->estado,
                            ];

                            $lNegocioProductos->guardar($datosActualizacion);
                            break;
                        case 'modificarViaAdmimistracionDosis':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioViasAdministracionesDosis = new ViasAdministracionesDosisLogicaNegocio();

                            $datosViaAdministracionDosis = $lNegocioViasAdministracionesDosis->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'dosis' => $datosViaAdministracionDosis->current()->dosis,
                                'unidad_dosis' => $datosViaAdministracionDosis->current()->unidad_dosis,
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                        case 'modificarAdicionPresentacion':
                            $lNegocioCodigosInocuidad = new CodigosInocuidadLogicaNegocio();
                            $lNegocioAdicionesPresentaciones = new AdicionesPresentacionesLogicaNegocio();

                            $datosAdicionesPresentaciones = $lNegocioAdicionesPresentaciones->buscarLista($datosModificacion);

                            foreach ($datosAdicionesPresentaciones as $adicionPresentacion) {
                                $estado = $adicionPresentacion->estado;
                                $subCodigo = $adicionPresentacion->subcodigo;

                                $datos = [
                                    'id_producto' => $idProducto,
                                    'subcodigo' => $subCodigo
                                ];

                                $verificaRegistro = $lNegocioCodigosInocuidad->buscarLista($datos);

                                if(!empty($verificaRegistro->current())){
                                    if ($estado === 'inactivo') {
                                        $lNegocioCodigosInocuidad->borrarRegistro($datos);
                                    }
                                } else {
                                    $datosActualizacion = [
                                        'id_producto' => $idProducto,
                                        'subcodigo' => $subCodigo,
                                        'presentacion' => $adicionPresentacion->presentacion,
                                        'unidad_medida' => $adicionPresentacion->unidad_medida,
                                    ];
                                    $lNegocioCodigosInocuidad->guardarProductoRIA($datosActualizacion);
                                }
                            }
                            break;
                        case 'modificarTitularidadProducto':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
                            $lNegocioTitularesProductos = new TitularesProductosLogicaNegocio();

                            $datosTitularesProducto = $lNegocioTitularesProductos->buscarLista($datosModificacion);

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'id_operador' => $datosTitularesProducto->current()->identificador_operador,
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                        case 'modificarFabricanteFormulador':
                            $lNegocioFabricanteFormulador = new FabricanteFormuladorLogicaNegocio();
                            $lNegocioFabricantesFormuladores = new FabricantesFormuladoresLogicaNegocio();

                            $datosFabricantesFormuladores = $lNegocioFabricantesFormuladores->buscarLista($datosModificacion);

                            foreach ($datosFabricantesFormuladores as $fabricanteFormulador) {
                                $idTablaOrigen = $fabricanteFormulador->id_tabla_origen;
                                $estado = $fabricanteFormulador->estado;
                                if ($idTablaOrigen) {
                                    if ($estado === 'inactivo') {
                                        $lNegocioFabricanteFormulador->borrar($idTablaOrigen);
                                    }
                                } else {
                                    $datosActualizacion = [
                                        'id_producto' => $idProducto,
                                        'nombre' => $fabricanteFormulador->nombre,
                                        'tipo' => $fabricanteFormulador->tipo,
                                        'id_pais_origen' => $fabricanteFormulador->id_pais_origen,
                                        'pais_origen' => $fabricanteFormulador->nombre_pais_origen
                                    ];
                                    $lNegocioFabricanteFormulador->guardar($datosActualizacion);
                                }
                            }
                            break;
                        case 'modificarUso':
                            $lNegocioProductoInocuidadUso = new ProductoInocuidadUsoLogicaNegocio();
                            $lNegocioUsos = new UsosLogicaNegocio();

                            $datosUsos = $lNegocioUsos->buscarLista($datosModificacion);

                            foreach ($datosUsos as $uso) {
                                $idTablaOrigen = $uso->id_tabla_origen;
                                $estado = $uso->estado;
                                if ($idTablaOrigen) {
                                    if ($estado === 'inactivo') {
                                        $lNegocioProductoInocuidadUso->borrar($idTablaOrigen);
                                    }
                                } else {
                                    $datosActualizacion = [
                                        'id_producto' => $idProducto,
                                        'id_uso' => $uso->id_uso_producto,
                                        'nombre_especie' => $uso->nombre_especie,
                                        'aplicado_a' => $uso->aplicado_a,
                                        'instalacion' => $uso->instalacion,
                                    ];

                                    if($uso->id_especie){
                                        $datosActualizacion += [
                                            'id_especie' => $uso->id_especie
                                        ];
                                    }

                                    $lNegocioProductoInocuidadUso->guardar($datosActualizacion);
                                }
                            }
                            break;
                        case 'modificarEtiqueta':
                            $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();

                            $datosActualizacion = [
                                'id_producto' => $idProducto,
                                'ruta_etiqueta' => $solicitudProducto->getRutaEtiquetaProducto(),
                            ];

                            $lNegocioProductosInocuidad->guardar($datosActualizacion);
                            break;
                    }
                    break;
            }
        }

        $lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
        $fechaActual = date('Y-m-d');

        $datosActualizacion = [
            'id_producto' => $idProducto,
            'observacion' => 'Datos de producto actualizados el '.$fechaActual.' mediante solicitud de modificación de producto Nro. '. $solicitudProducto->getNumeroSolicitud(),
        ];

        $lNegocioProductosInocuidad->guardar($datosActualizacion);

        if($rutaResultadoRevision){
            $lNegocioProductos = new ProductosLogicaNegocio();
            $datosRuta = [
                'id_producto' => $idProducto,
                'ruta' => $rutaResultadoRevision
            ];

            $lNegocioProductos->guardar($datosRuta);
        }

        $datos = [
            'id_area' => $idArea,
            'id_producto' => $idProducto,
            'numero_registro' => $solicitudProducto->getNumeroRegistro(),
            'id_solicitud_producto' => $solicitudProducto->getIdSolicitudProducto()
        ];

        $rutaCertificado = $this->generarCertificado($datos);

        return $rutaCertificado;
    }

    public function generarCertificado($datos)
    {
        $idArea = $datos['id_area'];
        $idProducto = $datos['id_producto'];
        $numeroRegistro = $datos['numero_registro'];
        $idSolicitudProducto = $datos['id_solicitud_producto'];

        $jasper = new JasperReport();

        $rutaCompletaCertificado = MODI_PROD_RIA_URL_REPORTE . 'certificados/' . $this->rutaFecha . '/';

        if (! file_exists($rutaCompletaCertificado)){
            mkdir($rutaCompletaCertificado, 0777, true);
        }

        $rutaCortaCertificado = MODI_PROD_RIA_URL . 'certificados/' . $this->rutaFecha . '/';

        switch ($idArea){
            case 'IAP':
                if(strripos($numeroRegistro, '/NA', strlen($numeroRegistro)-3) > 0){
                    $rutaReporte='ModificacionProductoRIA/vistas/reportes/PlaguicidasComunidadAndina.jasper';
                    $nombreArchivo = "CertificadoComunidadAndina_".$idProducto;
                }else if(strripos($numeroRegistro, '/NA-CL') > 0){
                    $rutaReporte='ModificacionProductoRIA/vistas/reportes/PlaguicidasComunidadAndina.jasper';
                    $nombreArchivo = "CertificadoComunidadAndina_".$idProducto;
                }else{
                    $rutaReporte='ModificacionProductoRIA/vistas/reportes/PlaguicidasNormaNacional.jasper';
                    $nombreArchivo = "CertificadoNormaNacional_".$idProducto;
                }

                $parametros = [
                    'idProducto'=>(int)$idProducto,
                    'ruta' => Constantes::RUTA_DOMINIO.'/'.Constantes::RUTA_APLICACION.'/'.$rutaCortaCertificado.$nombreArchivo
                ];

                break;
            case 'IAV':
                $rutaReporte='ModificacionProductoRIA/vistas/reportes/CertificadoVeterinarios.jasper';
                $nombreArchivo = "CertificadoVeterinario_".$idProducto;

                $parametros = [
                    'idSolicitud'=>(int)$idProducto
                ];
                break;
            case 'IAF':
                $rutaReporte='ModificacionProductoRIA/vistas/reportes/CertificadoFertilizantes.jasper';
                $nombreArchivo = "CertificadoFertilizante_".$idProducto;

                $parametros = [
                    'idSolicitud'=>(int)$idProducto
                ];
                break;
        }

        $datosReporte = array(
            'rutaReporte' => $rutaReporte,
            'rutaSalidaReporte' => 'ModificacionProductoRIA/archivos/certificados/' . $this->rutaFecha . '/' . $nombreArchivo,
            'tipoSalidaReporte' => array('pdf'),
            'parametrosReporte' => $parametros,
            'conexionBase' => 'SI');

        $jasper->generarArchivo($datosReporte);

        $contenido = $rutaCompletaCertificado.$nombreArchivo.'.pdf';

        //Firma Electrónica
        $arrayDocumento = array(
            'archivo_entrada' => $contenido,
            'archivo_salida' => $contenido,
            'identificador' => '1722773189',
            'razon_documento' => 'Certificado de producto',
            'tabla_origen' => 'g_modificacion_productos.solicitudes_productos',
            'campo_origen' => 'id_solicitud_producto',
            'id_origen' => $idSolicitudProducto,
            'estado' => 'Por atender',
            'proceso_firmado' => 'SI'
        );

        $lNegocioDocumentos = new DocumentosLogicaNegocio();
        $lNegocioDocumentos->guardar($arrayDocumento);

        $rutaCertificado = $rutaCortaCertificado.$nombreArchivo.'.pdf';

        return $rutaCertificado;
    }
	
	 /**
     * Función para enviar correo electrónico
     */
    public function enviarCorreo($idSolicitudProducto)
    {
        $solicitud = $this->buscar($idSolicitudProducto);
        $identificadorOperador = $solicitud->getIdentificadorOperador();
        $codigoCreacionSolicitud = $solicitud->getNumeroSolicitud();
        $estadoSolicitud = $solicitud->getEstadoSolicitudProducto();
        
        $operador = $this->obtenerDatosOperador($identificadorOperador);
        $correo = $operador->getCorreo();

        $arrayCorreo = array(
            'asunto' => 'Notificación de atención de la Solicitud de modificación de producto N° ' . $codigoCreacionSolicitud,
            'cuerpo' => 'Estimado usuario, <br> Por medio del presente se comunica que su solicitud de Modificación de producto N° ' . $codigoCreacionSolicitud . ' fue atendida.<br><strong>Resultado de Revisión técnica:</strong> 
            ' . $estadoSolicitud . '<br>Por favor ingresar a su perfil del Sistema GUIA y revisar con mejor detalle su solicitud.<br>
            <strong>NOTA:</strong> Este correo fue generado automáticamente por el sistema GUIA, por favor no responder a este mensaje.<br>
            Saludos cordiales.' ,
            'estado' => 'Por enviar',
            'codigo_modulo' => 'PRG_MOD_PRODUCTO',
            'tabla_modulo' => 'g_modificacion_productos.solicitudes_productos',
            'id_solicitud_tabla' => $idSolicitudProducto
        );
        
        $arrayDestinatario = array(
            $correo
        );
        
        return $this->lNegocioCorreos->crearCorreoElectronico($arrayCorreo, $arrayDestinatario);
    }
}

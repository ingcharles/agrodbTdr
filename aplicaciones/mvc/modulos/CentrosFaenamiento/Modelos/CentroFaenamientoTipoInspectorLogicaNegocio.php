<?php
/**
 * Lógica del negocio de CentroFaenamientoTipoInspectorModelo
 *
 * Este archivo se complementa con el archivo CentroFaenamientoTipoInspectorControlador.
 *
 * @author  AGROCALIDAD
 * @date    2018-11-21
 * @uses    CentroFaenamientoTipoInspectorLogicaNegocio
 * @package CentrosFaenamiento
 * @subpackage Modelos
 */
namespace Agrodb\CentrosFaenamiento\Modelos;

use Agrodb\CentrosFaenamiento\Modelos\IModelo;
use Agrodb\Core\Constantes;

class CentroFaenamientoTipoInspectorLogicaNegocio implements IModelo
{

    private $modeloCentroFaenamientoTipoInspector = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloCentroFaenamientoTipoInspector = new CentroFaenamientoTipoInspectorModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $datos['identificador_registro'] = $_SESSION['usuario'];
        $tablaModelo = new CentroFaenamientoTipoInspectorModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdCentroFaenamientoTipoInspector() != null && $tablaModelo->getIdCentroFaenamientoTipoInspector() > 0) {
            return $this->modeloCentroFaenamientoTipoInspector->actualizar($datosBd, $tablaModelo->getIdCentroFaenamientoTipoInspector());
        } else {
            unset($datosBd["id_centro_faenamiento_tipo_inspector"]);
            unset($datosBd['identificador_operador']);
            return $this->modeloCentroFaenamientoTipoInspector->guardar($datosBd);
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
        $this->modeloCentroFaenamientoTipoInspector->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return CentroFaenamientoTipoInspectorModelo
     */
    public function buscar($id)
    {
        return $this->modeloCentroFaenamientoTipoInspector->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloCentroFaenamientoTipoInspector->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloCentroFaenamientoTipoInspector->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCentroFaenamientoTipoInspector()
    {
        $consulta = "SELECT * FROM " . $this->modeloCentroFaenamientoTipoInspector->getEsquema() . ". centro_faenamiento_tipo_inspector";
        return $this->modeloCentroFaenamientoTipoInspector->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada por tipo inspector.
     *
     * @return array|ResultSet
     */
    public function buscarCentroFaenamientoTipoInspectorPorIdentificador($arrayParametros)
    {
        $busqueda = '';
        if (array_key_exists('id_tipo_inspector', $arrayParametros)) {
            $busqueda = "and ti.id_tipo_inspector = " . $arrayParametros['id_tipo_inspector'];
        }

       $consulta = "SELECT
                    	o.identificador as identificador_operador,
                    	case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
                        ti.tipo_inspector,
                        ti.id_tipo_inspector,
                        (select count(id_centro_faenamiento_tipo_inspector) from g_centros_faenamiento.centro_faenamiento_tipo_inspector cfti where estado = 'activo' and ti.id_tipo_inspector = cfti.id_tipo_inspector ) as contador
                    FROM
                    	g_operadores.operadores o
                        INNER JOIN g_centros_faenamiento.tipo_inspector ti ON ti.identificador_operador = o.identificador
                    WHERE
                        ti.identificador_operador = '" . $arrayParametros['identificador_operador'] . "'
                        and ti.resultado not in ('No habilitado')
                        " . $busqueda . ";";

        return $this->modeloCentroFaenamientoTipoInspector->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los sitios.
     *
     * @return array|ResultSet
     */
    public function buscarSitioFaenamiento($arrayParametros)
    {
    	
       $consulta = "SELECT
                            cf.identificador_operador,
                            cf.id_centro_faenamiento,
                            s.nombre_lugar as sitio,
                            a.nombre_area as area,
                            trim(unaccent(upper(cf.especie))) as especie
                        FROM
                        	g_centros_faenamiento.centros_faenamiento cf
                        	INNER JOIN g_operadores.sitios s ON s.id_sitio = cf.id_sitio
                        	INNER JOIN g_operadores.areas a ON a.id_area = cf.id_area and a.id_sitio = s.id_sitio             	   
                        WHERE
                        	cf.identificador_operador = '" . $arrayParametros['identificador_operador'] . "' and
                        	cf.criterio_funcionamiento in ('Habilitado','Activo')";

        $datosFaenadorSitio = $this->modeloCentroFaenamientoTipoInspector->ejecutarConsulta($consulta);

        $opcionesHtml = '<option value="">Seleccionar....</option>';

        if ($arrayParametros['tipo_inspector'] == Constantes::tipo_inspector()->AUXILIAR || $arrayParametros['tipo_inspector'] == Constantes::tipo_inspector()->AVES || $arrayParametros['tipo_inspector'] == Constantes::tipo_inspector()->AVESOFICIAL) {
            $tipo = 'Aves';
        } else {
            $tipo = 'Otros';
        }

        foreach ($datosFaenadorSitio as $item) {
            $especie = explode(',', $item['especie']);
            foreach ($especie as $valor) {
                if (trim($valor) === 'AVICOLA') {
                    $valoresEspecie[] = 'Aves';
                } else {
                    $valoresEspecie[] = 'Otros';
                }
            }
            //print_r($valoresEspecie);

            $valoresEspecie = array_unique($valoresEspecie);
            
            if (in_array($tipo, $valoresEspecie)) {
                $opcionesHtml .= '<option value="' . $item['id_centro_faenamiento'] . '">' . $item['sitio'] . ' - ' . $item['area'] . '</option>';
            }
        }

        return $opcionesHtml;
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para listar los veterinarios/aux y centros de faenamiento .
     *
     * @return array|ResultSet
     */
    public function buscarCentroFaenamientoInspector($arrayParametros)
    {
        $consulta = "SELECT
                    	case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
                    	string_agg(distinct tp.nombre,', ') as tipo,
                    	a.nombre_area as centro_faenamiento,
                    	s.nombre_lugar as sitio,
                    	cf.especie,
                        cf.id_centro_faenamiento,
                        cfti.id_centro_faenamiento_tipo_inspector
                    FROM
                    	g_centros_faenamiento.centro_faenamiento_tipo_inspector cfti
                    	INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_centro_faenamiento = cfti.id_centro_faenamiento
                    	INNER JOIN g_centros_faenamiento.tipo_inspector ti ON  ti.id_tipo_inspector = cfti.id_tipo_inspector
                    	INNER JOIN g_operadores.operadores o ON o.identificador = ti.identificador_operador
                    	
                    	INNER JOIN g_operadores.sitios s ON s.id_sitio = cf.id_sitio
                    	INNER JOIN g_operadores.areas a ON a.id_sitio = s.id_sitio and a.id_area = cf.id_area
                    	
                    	INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                    	INNER JOIN g_operadores.operaciones op ON op.id_operacion = pao.id_operacion and op.id_operador_tipo_operacion = cf.id_operador_tipo_operacion 
                    	INNER JOIN g_catalogos.productos p ON p.id_producto = op.id_producto
                    	INNER JOIN g_catalogos.subtipo_productos stp ON stp.id_subtipo_producto = p.id_subtipo_producto
                    	INNER JOIN g_catalogos.tipo_productos tp ON tp.id_tipo_producto = stp.id_tipo_producto
                    WHERE
                    	cfti.id_tipo_inspector = " . $arrayParametros['id_tipo_inspector'] . "
                        and cfti.estado = 'activo'
                    GROUP BY
                    	o.identificador, s.id_sitio, a.id_area, cf.especie, cf.id_centro_faenamiento, cfti.id_centro_faenamiento_tipo_inspector;";
        return $this->modeloCentroFaenamientoTipoInspector->ejecutarConsulta($consulta);
    }
    
    public function actualizarEstadoTipoInspector(Array $datos)
    {
        $datos['identificador_registro'] = $_SESSION['usuario'];
        $datos['estado'] = 'inactivo';
        $datos['fecha_creacion'] = 'now()';
        $tablaModelo = new CentroFaenamientoTipoInspectorModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        return $this->modeloCentroFaenamientoTipoInspector->actualizar($datosBd, $tablaModelo->getIdCentroFaenamientoTipoInspector());

    }
    
}

<?php
/**
 * Lógica del negocio de EntregaProductosModelo
 *
 * Este archivo se complementa con el archivo EntregaProductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    EntregaProductosLogicaNegocio
 * @package RegistroEntregaProductos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroEntregaProductos\Modelos;

use Agrodb\RegistroEntregaProductos\Modelos\IModelo;
use Agrodb\Core\JasperReport;

class EntregaProductosLogicaNegocio implements IModelo
{

    private $modeloEntregaProductos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloEntregaProductos = new EntregaProductosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        if((!isset($datos['id_entrega'])) || ($datos['id_entrega']=='')){
            $datos['identificador'] = $_SESSION['usuario'];
            $datos['institucion'] = $_SESSION['entidad'];
            $datos['id_provincia'] = $_SESSION['idProvincia'];
            $datos['provincia'] = $_SESSION['nombreProvincia'];
        }
        
        $tablaModelo = new EntregaProductosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        
        if ($tablaModelo->getIdEntrega() != null && $tablaModelo->getIdEntrega() > 0) {
            return $this->modeloEntregaProductos->actualizar($datosBd, $tablaModelo->getIdEntrega());
        } else {
            unset($datosBd["id_entrega"]);
            return $this->modeloEntregaProductos->guardar($datosBd);
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
        $this->modeloEntregaProductos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return EntregaProductosModelo
     */
    public function buscar($id)
    {
        return $this->modeloEntregaProductos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloEntregaProductos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloEntregaProductos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarEntregaProductos()
    {
        $consulta = "SELECT * FROM " . $this->modeloEntregaProductos->getEsquema() . ". entrega_productos";
        return $this->modeloEntregaProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para generar el número secuencial del certificado con codificación de la provincia.
     * y 5 números seriales.
     * 
     * @return array|ResultSet
     */
    public function buscarNumeroCertificado($idProvincia)
    {
        $consulta = "SELECT
                        max(numero_certificado) as numero
                     FROM
                        g_registro_entrega_producto.entrega_productos
                     WHERE 
                        id_provincia = $idProvincia;";
        
        $codigo = $this->modeloEntregaProductos->ejecutarSqlNativo($consulta);
        $fila = $codigo->current();
        
        $codigoCertificado = array('numero' => $fila['numero']);
        
        $incremento = $codigoCertificado['numero'] + 1;
        $codigoCertificado = $idProvincia . str_pad($incremento, 5, "0", STR_PAD_LEFT);
        
        return $codigoCertificado;
    }
    
    /**
     * Función para crear el PDF del certificado
     */
    public function generarCertificado($identificadorBeneficiario, $codigoCertificado, $tecnico) {
        $jasper = new JasperReport();
        $datosReporte = array();
        
        $anio = date('Y');
        $mes = date('m');
        $dia = date('d');

        
        $ruta = ENT_PROD_CERT_URL_TCPDF . 'certificado/' . $anio . '/' . $mes . '/' . $dia . '/';
        
        if (! file_exists($ruta)){
            mkdir($ruta, 0777, true);
        }
        
        $datosReporte = array(
            'rutaReporte' => 'RegistroEntregaProductos/vistas/reportes/reporteCertificado.jasper',
            'rutaSalidaReporte' => 'RegistroEntregaProductos/archivos/certificado/'. $anio . '/' . $mes . '/' . $dia . '/' .$identificadorBeneficiario.'-'.$codigoCertificado,
            'tipoSalidaReporte' => array('pdf'),
            'parametrosReporte' => array(   'identificadorBeneficiario' => $identificadorBeneficiario,
                                            'codigoCertificado' => $codigoCertificado,
                                            'identificadorTecnico' => $tecnico['identificador'],
                                            'nombreTecnico' => $tecnico['nombre'] . " " .$tecnico['apellido'],
                                            'provinciaTecnico' => (String) $tecnico['provincia'],
                                            'imagenBackground'=> RUTA_IMG_GENE.'fondoCertificado.png'
            ),
            'conexionBase' => 'SI'
        );
        

        
        $jasper->generarArchivo($datosReporte);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para buscar los registros de entrega con la información del beneficiario y unidad.
     *
     * @return array|ResultSet
     */
    public function listarEntregasConDatos($estado, $idProvincia)
    {
        $consulta = "SELECT 
                    	ep.*, b.nombre, b.apellido, p.unidad
                    FROM
                    	g_registro_entrega_producto.entrega_productos ep
                    	INNER JOIN g_registro_entrega_producto.beneficiarios b ON ep.identificador_beneficiario = b.identificador
                    	INNER JOIN g_catalogos.productos_distribucion p ON ep.id_producto = p.id_producto_distribucion
                    WHERE
                    	ep.estado in ('$estado') and
                    	ep.id_provincia = $idProvincia
                    ORDER BY
                        b.apellido, b.nombre, ep.producto ASC;";
        
        $codigo = $this->modeloEntregaProductos->ejecutarSqlNativo($consulta);
        
        return $codigo;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar inventario usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarEntregasXFiltro($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['id_producto']) && ($arrayParametros['id_producto'] != '') && ($arrayParametros['id_producto'] != 'Seleccione....')) {
            $busqueda .= " and ep.id_producto = '" . $arrayParametros['id_producto'] . "' ";
        }
        if (isset($arrayParametros['id_provincia']) && ($arrayParametros['id_provincia'] != '') && ($arrayParametros['id_provincia'] != 'Seleccione....')) {
            $busqueda .= " and ep.id_provincia = '" . $arrayParametros['id_provincia'] . "' ";
        }
        if (isset($arrayParametros['identificador_beneficiario']) && ($arrayParametros['identificador_beneficiario'] != '')) {
            $busqueda .= " and ep.identificador_beneficiario ilike '%" . $arrayParametros['identificador_beneficiario'] . "%' ";
        }
        
        $consulta = "  SELECT
                        	ep.*, b.nombre, b.apellido, p.unidad
                        FROM
                        	g_registro_entrega_producto.entrega_productos ep
                            INNER JOIN g_registro_entrega_producto.beneficiarios b ON ep.identificador_beneficiario = b.identificador
                    	    INNER JOIN g_catalogos.productos_distribucion p ON ep.id_producto = p.id_producto_distribucion
                        WHERE
                            ep.estado = 'Activo' and
                            ep.institucion = '".$arrayParametros['institucion']."' ".
                            $busqueda. "
                        ORDER BY
                        	b.apellido, b.nombre, ep.producto ASC;";
             
       return $this->modeloEntregaProductos->ejecutarSqlNativo($consulta);
    }
}
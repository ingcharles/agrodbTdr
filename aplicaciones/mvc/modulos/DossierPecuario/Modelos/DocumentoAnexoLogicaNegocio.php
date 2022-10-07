<?php
/**
 * Lógica del negocio de DocumentoAnexoModelo
 *
 * Este archivo se complementa con el archivo DocumentoAnexoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    DocumentoAnexoLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class DocumentoAnexoLogicaNegocio implements IModelo
{

    private $modeloDocumentoAnexo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloDocumentoAnexo = new DocumentoAnexoModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new DocumentoAnexoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdDocumentoAnexo() != null && $tablaModelo->getIdDocumentoAnexo() > 0) {
            return $this->modeloDocumentoAnexo->actualizar($datosBd, $tablaModelo->getIdDocumentoAnexo());
        } else {
            unset($datosBd["id_documento_anexo"]);
            return $this->modeloDocumentoAnexo->guardar($datosBd);
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
        $this->modeloDocumentoAnexo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return DocumentoAnexoModelo
     */
    public function buscar($id)
    {
        return $this->modeloDocumentoAnexo->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloDocumentoAnexo->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloDocumentoAnexo->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDocumentoAnexo()
    {
        $consulta = "SELECT * FROM " . $this->modeloDocumentoAnexo->getEsquema() . ". documento_anexo";
        return $this->modeloDocumentoAnexo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos de forma de administración
     * con la información de los items del catálogo
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroDocumentosAnexos($idSolicitud, $tipo)
    {
        if($tipo=='anexo'){
            $busqueda=" and id_tipo_documento not in (0)";
        }else{
            $busqueda=" and id_tipo_documento in (0)";
        }
        
        $consulta = "SELECT
                        count(id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.documento_anexo
                    WHERE
                    	id_solicitud = $idSolicitud $busqueda;";
        
        //echo $consulta;
        return $this->modeloDocumentoAnexo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos de documentos anexos
     * con la información de los items del catálogo
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionDocumentoAnexo($idSolicitud, $tipo)
    {
        if($tipo=='anexo'){
            $parametro = ", ap.anexo_pecuario as documento";
            $tabla = " INNER JOIN g_catalogos.anexos_pecuarios ap ON da.id_tipo_documento = ap.id_anexo_pecuario ";
            $busqueda=" and da.id_tipo_documento not in (0)";
        }else{
            $parametro = " ";
            $tabla = " ";
            $busqueda=" and da.id_tipo_documento in (0)";
        }

        $consulta = "SELECT
                        da.* $parametro
                    FROM
                        g_dossier_pecuario_mvc.documento_anexo da
                        $tabla
                    WHERE
                    	da.id_solicitud = $idSolicitud $busqueda;";
        
        return $this->modeloDocumentoAnexo->ejecutarSqlNativo($consulta);
    }
}
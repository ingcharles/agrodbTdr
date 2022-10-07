<?php
/**
 * Lógica del negocio de FormaFisFarCosProductoModelo
 *
 * Este archivo se complementa con el archivo FormaFisFarCosProductoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    FormaFisFarCosProductoLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;
use Agrodb\Catalogos\Modelos\FormulacionLogicaNegocio;

class FormaFisFarCosProductoLogicaNegocio implements IModelo
{

    private $modeloFormaFisFarCosProducto = null;
    private $lNegocioFormulacion = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloFormaFisFarCosProducto = new FormaFisFarCosProductoModelo();
        $this->lNegocioFormulacion = new FormulacionLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new FormaFisFarCosProductoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdFormaFisFarCosProducto() != null && $tablaModelo->getIdFormaFisFarCosProducto() > 0) {
            return $this->modeloFormaFisFarCosProducto->actualizar($datosBd, $tablaModelo->getIdFormaFisFarCosProducto());
        } else {
            unset($datosBd["id_forma_fis_far_cos_producto"]);
            return $this->modeloFormaFisFarCosProducto->guardar($datosBd);
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
        $this->modeloFormaFisFarCosProducto->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return FormaFisFarCosProductoModelo
     */
    public function buscar($id)
    {
        return $this->modeloFormaFisFarCosProducto->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloFormaFisFarCosProducto->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloFormaFisFarCosProducto->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarFormaFisFarCosProducto()
    {
        $consulta = "SELECT * FROM " . $this->modeloFormaFisFarCosProducto->getEsquema() . ". forma_fis_far_cos_producto";
        return $this->modeloFormaFisFarCosProducto->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos del operador
     * de acuerdo al identificador del operador
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionFormaFisFarCosProducto($idSolicitud)
    {
        $consulta = "SELECT
                        f.*, ff.formulacion
                    FROM
                        g_dossier_pecuario_mvc.forma_fis_far_cos_producto f
                        INNER JOIN g_catalogos.formulacion ff ON ff.id_formulacion = f.id_forma
                    WHERE
                    	f.id_solicitud = $idSolicitud;";

        return $this->modeloFormaFisFarCosProducto->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para contar los registros creados
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroRegistrosFormaFisFarCosProducto($idSolicitud)
    {
        $consulta = "SELECT
                        count(id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.forma_fis_far_cos_producto
                    WHERE
                    	id_solicitud = $idSolicitud;";

        return $this->modeloFormaFisFarCosProducto->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosFormaFisFarCosProducto($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Forma Fir Far Cos. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $formFFC = $this->buscarLista($query);
        
        foreach($formFFC as $formaFFCP){
            $arrayFormaFFC = array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'id_forma' => $formaFFCP->id_forma
            );
            
            //echo 'Forma Fir Far Cos';
            //print_r($arrayFormaFFC);
            
            $idFormApliIns = $this->guardar($arrayFormaFFC);
            
            if($idFormApliIns > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Forma Fir Far Cos. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Forma Fir Far Cos. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
    
    /**
     * Función para generar la forma física, farmacéutica y cosmética para crear el producto en el módulo RIA
     */
    public function obtenerRegistrosFormaFisFarCosRIA($idSolicitud, $grupoProducto)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Forma Fir Far Cos. ",
            'idFormulacion' => 0,
            'formulacion' => null
        );
        
        // Forma física, farmacéutica, cosmética -> Formulación
        // Para fórmula maestra guardar por defecto 0 Sin formulación       
        if ($grupoProducto != 'FM') {
            $query = "id_solicitud = $idSolicitud ORDER BY 1 LIMIT 1";
            $formulaciones = $this->buscarLista($query);
            
            if (! empty($formulaciones)) {
                foreach ($formulaciones as $form) {
                    $validacion['idFormulacion'] = $form->id_forma;
                }
            } else {
                $validacion['idFormulacion'] = 0;
            }
        } else {
            $validacion['idFormulacion'] = 0;
        }
        
        $formulaciones = $this->lNegocioFormulacion->buscar($validacion['idFormulacion']);
        
        if (! empty($formulaciones)) {
            $validacion['formulacion'] = $formulaciones->formulacion;
        } else {
            $validacion['formulacion'] = "NA";
        }
        
        return $validacion;
    }
}
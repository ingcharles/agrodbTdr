<?php
/**
 * Lógica del negocio de FormaAdministracionAlimentoModelo
 *
 * Este archivo se complementa con el archivo FormaAdministracionAlimentoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    FormaAdministracionAlimentoLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class FormaAdministracionAlimentoLogicaNegocio implements IModelo
{

    private $modeloFormaAdministracionAlimento = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloFormaAdministracionAlimento = new FormaAdministracionAlimentoModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new FormaAdministracionAlimentoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdFormaAdministracionAlimento() != null && $tablaModelo->getIdFormaAdministracionAlimento() > 0) {
            return $this->modeloFormaAdministracionAlimento->actualizar($datosBd, $tablaModelo->getIdFormaAdministracionAlimento());
        } else {
            unset($datosBd["id_forma_administracion_alimento"]);
            return $this->modeloFormaAdministracionAlimento->guardar($datosBd);
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
        $this->modeloFormaAdministracionAlimento->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return FormaAdministracionAlimentoModelo
     */
    public function buscar($id)
    {
        return $this->modeloFormaAdministracionAlimento->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloFormaAdministracionAlimento->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloFormaAdministracionAlimento->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarFormaAdministracionAlimento()
    {
        $consulta = "SELECT * FROM " . $this->modeloFormaAdministracionAlimento->getEsquema() . ". forma_administracion_alimento";
        return $this->modeloFormaAdministracionAlimento->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosFormaAdministracionAlimento($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Forma de Administracion en Alimentos. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $formAdAl = $this->buscarLista($query);
        
        foreach($formAdAl as $formaAdmAli){
            $arrayFormaAdminAlim = array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'dosis_alimento' => $formaAdmAli->dosis_alimento,
                'forma_administracion' => $formaAdmAli->forma_administracion
            );
            
            //echo 'Forma administracion alimento';
            //print_r($arrayFormaAdminAlim);
            
            $idDosisViaAmin = $this->guardar($arrayFormaAdminAlim);
            
            if($idDosisViaAmin > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Forma de Administracion en Alimentos. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Forma de Administracion en Alimentos. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
    
    /**
     * Función para crear el producto en el módulo RIA
     */
    public function obtenerRegistrosFormaAdministracionAlimentoRIA($idSolicitud)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Forma Administracion Alimento. ",
            'dosisAlimento' => null,
            'formaAdministracion' => null
        );
        
        // Forma de Administración en Alimento (tabla forma Administración Alimento)
        $query = "id_solicitud = $idSolicitud ORDER BY 1 LIMIT 1";
        $formAdminAlim = $this->buscarLista($query);
        
        if (isset($formAdminAlim->current()->dosis_alimento)) {
            $validacion['dosisAlimento'] = $formAdminAlim->current()->dosis_alimento;
            $validacion['formaAdministracion'] = $formAdminAlim->current()->forma_administracion;
        } else {
            $validacion['dosisAlimento'] = "NA";
            $validacion['formaAdministracion'] = "NA";
        }
        
        return $validacion;
    }
}
<?php
/**
 * Lógica del negocio de FormaAplicacionInstalacionesModelo
 *
 * Este archivo se complementa con el archivo FormaAplicacionInstalacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    FormaAplicacionInstalacionesLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class FormaAplicacionInstalacionesLogicaNegocio implements IModelo
{

    private $modeloFormaAplicacionInstalaciones = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloFormaAplicacionInstalaciones = new FormaAplicacionInstalacionesModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new FormaAplicacionInstalacionesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdFormaAplicacionInstalacion() != null && $tablaModelo->getIdFormaAplicacionInstalacion() > 0) {
            return $this->modeloFormaAplicacionInstalaciones->actualizar($datosBd, $tablaModelo->getIdFormaAplicacionInstalacion());
        } else {
            unset($datosBd["id_forma_aplicacion_instalacion"]);
            return $this->modeloFormaAplicacionInstalaciones->guardar($datosBd);
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
        $this->modeloFormaAplicacionInstalaciones->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return FormaAplicacionInstalacionesModelo
     */
    public function buscar($id)
    {
        return $this->modeloFormaAplicacionInstalaciones->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloFormaAplicacionInstalaciones->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloFormaAplicacionInstalaciones->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarFormaAplicacionInstalaciones()
    {
        $consulta = "SELECT * FROM " . $this->modeloFormaAplicacionInstalaciones->getEsquema() . ". forma_aplicacion_instalaciones";
        return $this->modeloFormaAplicacionInstalaciones->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosFormaAplicacionInstalaciones($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Forma de Aplicacion en Instalaciones. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $formAnIns = $this->buscarLista($query);
        
        foreach($formAnIns as $formaApliInst){
            $arrayFormaApliInst = array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'dosis' => $formaApliInst->dosis,
                'forma_administracion' => $formaApliInst->forma_administracion
            );
            
            //echo 'Forma aplicacion instalaciones';
            //print_r($arrayFormaApliInst);
            
            $idFormApliIns = $this->guardar($arrayFormaApliInst);
            
            if($idFormApliIns > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Forma de Aplicacion en Instalaciones. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Forma de Aplicacion en Instalaciones. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
    
    /**
     * Función para crear el producto en el módulo RIA
     */
    public function obtenerRegistrosFormaAplicacionInstalacionesRIA($idSolicitud)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Forma Aplicacion Instalaciones. ",
            'dosisInst' => null,
            'formaAdministracion' => null
        );
        
        // Forma de Administración en Instalaciones (tabla Forma Aplicacion Instalaciones)
        $query = "id_solicitud = $idSolicitud ORDER BY 1 LIMIT 1";
        $formApliInst = $this->buscarLista($query);
        
        if (isset($formApliInst->current()->dosis)) {
            $validacion['dosisInst'] = $formApliInst->current()->dosis;
            $validacion['formaAdministracion'] = $formApliInst->current()->forma_administracion;
        } else {
            $validacion['dosisInst'] = "NA";
            $validacion['formaAdministracion'] = "NA";
        }
        
        return $validacion;
    }
}
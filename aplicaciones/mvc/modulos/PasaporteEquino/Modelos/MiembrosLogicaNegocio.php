<?php
/**
 * Lógica del negocio de MiembrosModelo
 *
 * Este archivo se complementa con el archivo MiembrosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-15
 * @uses    MiembrosLogicaNegocio
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\PasaporteEquino\Modelos\IModelo;

//use Agrodb\PasaporteEquino\Modelos\OrganizacionEcuestreLogicaNegocio;

use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;

class MiembrosLogicaNegocio implements IModelo
{

    private $modeloMiembros = null;
    
    //private $lNegocioOrganizacionEcuestre = null;
    
    private $lNegocioOperaciones = null;
    
    private $asociacion = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloMiembros = new MiembrosModelo();
        
        //$this->lNegocioOrganizacionEcuestre = new OrganizacionEcuestreLogicaNegocio();
        
        $this->lNegocioOperaciones = new OperacionesLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new MiembrosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        
        if ($tablaModelo->getIdMiembro() != null && $tablaModelo->getIdMiembro() > 0) {
            return $this->modeloMiembros->actualizar($datosBd, $tablaModelo->getIdMiembro());
        } else {
            unset($datosBd["id_miembro"]);
            return $this->modeloMiembros->guardar($datosBd);
        }
    }
    
    /**
     * Valida el registro con los casos planteados y guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function validarMiembro(Array $datos)
    {
        $validacion = array(
            'bandera' => false,
            'estado' => "Fallo",
            'mensaje' => "Ocurrió un error al guardar la información del asociado",
            'contenido' => null
        );
        
        //Busca si existe un registro del miembro y su predio en alguna organización
        $query = "identificador_miembro = '".$datos['identificador_miembro']."' and
                  id_catastro_predio_equidos = '".$datos['id_catastro_predio_equidos']."'";
        
        $listaMiembros = $this->buscarLista($query);
        
        if(isset($listaMiembros->current()->id_miembro)){
            
            $datos['fecha_modificacion'] = 'now()';
            
            if($listaMiembros->current()->id_organizacion_ecuestre = $datos['idAsociacion']){//El registro es de mi asociación
                //print_r('El registro es de mi asociación');
                if($listaMiembros->current()->estado_miembro == 'Activo' || $listaMiembros->current()->estado_miembro == 'Inactivo'){//Modificación de estado y documento de respaldo
                    //Verificar cuando es modificacion(cambio estado) para q no se tome cuando es nuevo ingreso.
                    if(isset($datos['id_miembro'])){//Modificacion, existe el id de mimebro
                        $datos['id_organizacion_ecuestre'] = $datos['idAsociacion'];
                        $validacion['bandera'] = true;
                        $validacion['estado'] = 'exito';
                        $validacion['mensaje'] = 'Se ha actualizado el estado del asociado.';
                        //print_r('Modificación de estado y documento de respaldo');
                    }else{
                        $validacion['bandera'] = false;
                        $validacion['estado'] = 'Fallo';
                        $validacion['mensaje'] = 'El asociado ya se encuentra registrado y en estado ' . $listaMiembros->current()->estado_miembro;
                        //print_r('El asociado ya se encuentra registrado');
                    }
                    
                }else{//Estado liberado, puede pasar a una nueva asociación o vincularse a la misma
                    $datos['id_miembro'] = $listaMiembros->current()->id_miembro;
                    $datos['id_organizacion_ecuestre'] = $datos['idAsociacion'];
                    $datos['estado_miembro'] = 'Activo';
                    $datos['motivo_cambio'] = 'Revinculación a la asociación '.$datos['idAsociacion'];
                    
                    $validacion['bandera'] = true;
                    $validacion['estado'] = 'exito';
                    $validacion['mensaje'] = 'Se ha actualizado el estado del asociado y vinculado nuevamente a la asociación.';
                    /*//Error, no se puede modificar ya que el asociado no está registrado
                    $validacion['bandera'] = false;
                    $validacion['estado'] = 'Fallo';
                    $validacion['mensaje'] = 'No se puede modificar el registro dado que el asociado ya no pertenece a esta organización.';
                    //print_r('Error, no se puede modificar ya que el asociado no está registrado');*/
                }
            }else{//El registro es de otra organización
                if($listaMiembros->current()->estado_miembro == 'Liberado'){//Asignación de un asociado existente liberado de otra organización a la actual
                    $datos['id_miembro'] = $listaMiembros->current()->id_miembro;
                    $datos['id_organizacion_ecuestre'] = $datos['idAsociacion'];
                    $datos['estado_miembro'] = 'Activo';
                    $datos['motivo_cambio'] = 'Vinculación a la asociación '.$datos['idAsociacion'];
                    
                    $validacion['bandera'] = true;
                    $validacion['estado'] = 'exito';
                    $validacion['mensaje'] = 'Se ha vinculado al asociado liberado a su organización.';
                    //print_r('Se ha vinculado al asociado liberado a su organización');
                }else{//Estado Activo o Inactivo, no puede pasar a una nueva asociación
                    //Error, no se puede asignar ya que el asociado está registrado en otra asociación
                    $validacion['bandera'] = false;
                    $validacion['estado'] = 'Fallo';
                    $validacion['mensaje'] = 'No se puede agregar al asociado ya que se encuentra activo en otra organización.';
                    //print_r('No se puede agregar al asociado ya que no pertenece a esta organización');
                }
            }
            
        }else{//El registro no existe en ninguna asociación y puede crearse con el idAsociacion del usuario actual
            $datos['id_organizacion_ecuestre'] = $datos['idAsociacion'];
            $validacion['bandera'] = true;
            $validacion['estado'] = 'exito';
            $validacion['mensaje'] = 'Se ha creado el nuevo miembro en su asociación.';
            //print_r('Se ha creado el nuevo miembro en su asociación');
        }
        
        
        if ($validacion['bandera']) {
            $validacion['contenido'] = $this->guardar($datos);
        }
        
        return $validacion;
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
        $this->modeloMiembros->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return MiembrosModelo
     */
    public function buscar($id)
    {
        return $this->modeloMiembros->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloMiembros->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloMiembros->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarMiembros()
    {
        $consulta = "SELECT * FROM " . $this->modeloMiembros->getEsquema() . ". miembros";
        return $this->modeloMiembros->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar miembros usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarMiembrosFiltrados($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['identificador_miembro']) && ($arrayParametros['identificador_miembro'] != '')) {
            $busqueda .= " and m.identificador_miembro = '" . $arrayParametros['identificador_miembro'] . "' ";
        } 
        
        if (isset($arrayParametros['nombre_miembro']) && ($arrayParametros['nombre_miembro'] != '')) {
            $busqueda .= " and upper(m.nombre_miembro) ilike upper('%" . $arrayParametros['nombre_miembro'] . "%') ";
        } 
        
        if (isset($arrayParametros['nombre_predio']) && ($arrayParametros['nombre_predio'] != '')) {
            $busqueda .= " and upper(cpe.nombre_predio) ilike upper('%" . $arrayParametros['nombre_predio'] . "%') ";
        } 
        
        if (isset($arrayParametros['id_provincia']) && ($arrayParametros['id_provincia'] != '')) {
            $busqueda .= " and cpe.id_provincia = " . $arrayParametros['nombre_predio'] ;
        } 
        
        $consulta = "  SELECT
                        	*
                        FROM
                        	g_pasaporte_equino.miembros m
                            INNER JOIN g_pasaporte_equino.organizacion_ecuestre oe ON m.id_organizacion_ecuestre = oe.id_organizacion_ecuestre
                            INNER JOIN g_programas_control_oficial.catastro_predio_equidos cpe ON m.id_catastro_predio_equidos = cpe.id_catastro_predio_equidos
                        WHERE
                            oe.identificador_organizacion = '".$arrayParametros['identificador_organizacion']."'
                            and estado_miembro not in ('Liberado')
                            " . $busqueda . "
                        ORDER BY
                        	m.nombre_miembro ASC;";
        
        //echo $consulta;
        return $this->modeloMiembros->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde un miembro tiene predios registrados
     *
     * @return array|ResultSet
     */
    public function buscarProvinciasMiembro ($idAsociacion)
    {
        $consulta = "   SELECT 
                        	distinct cpe.id_provincia, cpe.provincia
                        FROM g_pasaporte_equino.miembros m
                        	INNER JOIN g_programas_control_oficial.catastro_predio_equidos cpe ON m.id_catastro_predio_equidos = cpe.id_catastro_predio_equidos
                        WHERE 
                        	m.estado_miembro in ('Activo') and
                        	m.id_organizacion_ecuestre = $idAsociacion;";
        
        return $this->modeloMiembros->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca los miembros de la asociación por provincia
     *
     * @return array|ResultSet
     */
    public function buscarMiembrosXProvincia ($idAsociacion, $idProvincia)
    {
        $consulta = "   SELECT 
                        	distinct m.id_miembro, m.identificador_miembro, m.nombre_miembro
                        FROM g_pasaporte_equino.miembros m
                        	INNER JOIN g_programas_control_oficial.catastro_predio_equidos cpe ON m.id_catastro_predio_equidos = cpe.id_catastro_predio_equidos
                        WHERE 
                        	m.estado_miembro in ('Activo') and
                        	m.id_organizacion_ecuestre = $idAsociacion and
                        	cpe.id_provincia = $idProvincia;";
        
        return $this->modeloMiembros->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca los miembros de la asociación por provincia
     *
     * @return array|ResultSet
     */
    public function buscarPrediosXMiembrosXProvincia ($idAsociacion, $idProvincia, $idMiembro)
    {
        $consulta = "   SELECT
                        	distinct cpe.id_catastro_predio_equidos, cpe.num_solicitud, cpe.nombre_predio
                        FROM g_pasaporte_equino.miembros m
                        	INNER JOIN g_programas_control_oficial.catastro_predio_equidos cpe ON m.id_catastro_predio_equidos = cpe.id_catastro_predio_equidos
                        WHERE
                        	m.estado_miembro in ('Activo') and
                        	m.id_organizacion_ecuestre = $idAsociacion and
                        	cpe.id_provincia = $idProvincia;";
        
        return $this->modeloMiembros->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca los miembros de la asociación por provincia
     *
     * @return array|ResultSet
     */
    public function buscarMiembroXAsociacion ($idAsociacion, $idMiembroActual)
    {
        $consulta = "   SELECT
                        	distinct m.*, cpe.id_catastro_predio_equidos, cpe.num_solicitud, cpe.nombre_predio
                        FROM g_pasaporte_equino.miembros m
                        	INNER JOIN g_programas_control_oficial.catastro_predio_equidos cpe ON m.id_catastro_predio_equidos = cpe.id_catastro_predio_equidos
                        WHERE
                        	m.estado_miembro in ('Activo') and
                        	m.id_organizacion_ecuestre = $idAsociacion and
                            m.id_miembro not in (".$idMiembroActual.");";
        
        return $this->modeloMiembros->ejecutarSqlNativo($consulta);
    }
}
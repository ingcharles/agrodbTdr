<?php
/**
 * Lógica del negocio de CentrosFaenamientoModelo
 *
 * Este archivo se complementa con el archivo CentrosFaenamientoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2018-11-21
 * @uses    CentrosFaenamientoLogicaNegocio
 * @package CentrosFaenamiento
 * @subpackage Modelos
 */
namespace Agrodb\CentrosFaenamiento\Modelos;

use Agrodb\CentrosFaenamiento\Modelos\IModelo;

class CentrosFaenamientoLogicaNegocio implements IModelo
{

    private $modeloCentrosFaenamiento = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloCentrosFaenamiento = new CentrosFaenamientoModelo();
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
        $tablaModelo = new CentrosFaenamientoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdCentroFaenamiento() != null && $tablaModelo->getIdCentroFaenamiento() > 0) {
            
            switch ($tablaModelo->getCriterioFuncionamiento()){
            case 'Habilitado':
            case 'Activo':
                $estado = 'registrado';
            break;
            case 'Clausurado temporalmente':
            case 'Clausurado definitivamente':
            case 'Cerrado temporalmente':
            case 'Cerrado definitivamente':
                $estado = 'noHabilitado';
            break;
            }
            
            $estadoOperacion = $this->obtenerEstadoOperacionesRegistroOperador(array('id_operador_tipo_operacion' => $tablaModelo->getIdOperadorTipoOperacion()));
            
            if( $estadoOperacion->current()->estado != $estado){
                $this->actualizarEstadoOperacion(array('id_operador_tipo_operacion' => $tablaModelo->getIdOperadorTipoOperacion(), 'estado' => $estado));
            }
            
            return $this->modeloCentrosFaenamiento->actualizar($datosBd, $tablaModelo->getIdCentroFaenamiento());
        } else {
            unset($datosBd["id_centro_faenamiento"]);
            return $this->modeloCentrosFaenamiento->guardar($datosBd);
        }
    }
    public function guardarRegistros(Array $datos){
        try{
            $this->modeloCentrosFaenamiento = new CentrosFaenamientoModelo();
            $proceso = $this->modeloCentrosFaenamiento->getAdapter()
            ->getDriver()
            ->getConnection();
            if (! $proceso->beginTransaction()){
                throw new \Exception('No se pudo iniciar la transacción: actualizar centros de faenamiento ');
            }
            
            $datos['identificador_registro'] = $_SESSION['usuario'];
            $tablaModelo = new CentrosFaenamientoModelo($datos);
            $datosBd = $tablaModelo->getPrepararDatos();
            if ($tablaModelo->getIdCentroFaenamiento() != null && $tablaModelo->getIdCentroFaenamiento() > 0) {
                
                switch ($tablaModelo->getCriterioFuncionamiento()){
                    case 'Habilitado':
                    case 'Activo':
                        $estado = 'registrado';
                        break;
                    case 'Clausurado temporalmente':
                    case 'Clausurado definitivamente':
                    case 'Cerrado temporalmente':
                    case 'Cerrado definitivamente':
                        $estado = 'noHabilitado';
                        break;
                }
                
                $estadoOperacion = $this->obtenerEstadoOperacionesRegistroOperador(array('id_operador_tipo_operacion' => $tablaModelo->getIdOperadorTipoOperacion()));
                
                if( $estadoOperacion->current()->estado != $estado){
                    $this->actualizarEstadoOperacion(array('id_operador_tipo_operacion' => $tablaModelo->getIdOperadorTipoOperacion(), 'estado' => $estado));
                }
                
                $this->modeloCentrosFaenamiento->actualizar($datosBd, $tablaModelo->getIdCentroFaenamiento());
            } else {
                unset($datosBd["id_centro_faenamiento"]);
                $idCentroFaenamiento = $this->modeloCentrosFaenamiento->guardar($datosBd);
            }
            
            // *****************detalle canton provincia*********************************************
            if (isset($datos['cantonProvincia'])){
                $arrayEliminar = array();
                $arrayGuardar = array();
                $arrayDatos = array();
                $lnegocioDetalleCantonProvincia = new DetalleCantonProvinciaLogicaNegocio();
                
                $verificarElemento = $lnegocioDetalleCantonProvincia->buscarLista("id_centro_faenamiento = " . $tablaModelo->getIdCentroFaenamiento());
                
                if ($verificarElemento->count()){
                    foreach ($verificarElemento as $valor1){
                        $arrayDatos[] = $valor1->id_localizacion;
                        $ban = 1;
                        foreach ($datos['cantonProvincia'] as $valor2){
                            if ($valor1->id_localizacion == $valor2){
                                $ban = 0;
                            }
                        }
                        if ($ban){
                            $arrayEliminar[] = $valor1->id_detalle_canton_provincia;
                        }
                    }
                    
                    foreach ($datos['cantonProvincia'] as $valor2){
                        $ban = 1;
                        foreach ($arrayDatos as $valor1){
                            if ($valor1 == $valor2){
                                $ban = 0;
                            }
                        }
                        if ($ban){
                            $arrayGuardar[] = $valor2;
                        }
                    }
                    foreach ($arrayEliminar as $value){
                        $statement = $this->modeloCentrosFaenamiento->getAdapter()
                        ->getDriver()
                        ->createStatement();
                        $sqlActualizar = $this->modeloCentrosFaenamiento->borrarSql('detalle_canton_provincia', $this->modeloCentrosFaenamiento->getEsquema());
                        $sqlActualizar->where(array(
                            'id_detalle_canton_provincia' => $value));
                        $sqlActualizar->prepareStatement($this->modeloCentrosFaenamiento->getAdapter(), $statement);
                        $statement->execute();
                    }
                    foreach ($arrayGuardar as $value){
                        $arrayElemento = array(
                            'id_centro_faenamiento' => $datos['id_centro_faenamiento'],
                            'id_localizacion' => $value );
                        $statement = $this->modeloCentrosFaenamiento->getAdapter()
                        ->getDriver()
                        ->createStatement();
                        $sqlInsertar = $this->modeloCentrosFaenamiento->guardarSql('detalle_canton_provincia', $this->modeloCentrosFaenamiento->getEsquema());
                        $sqlInsertar->columns($lnegocioDetalleCantonProvincia->columnas());
                        $sqlInsertar->values($arrayElemento, $sqlInsertar::VALUES_MERGE);
                        $sqlInsertar->prepareStatement($this->modeloCentrosFaenamiento->getAdapter(), $statement);
                        $statement->execute();
                    }
                }else{
                    foreach ($datos['cantonProvincia'] as $value){
                        $arrayElemento = array(
                            'id_centro_faenamiento' => $datos['id_centro_faenamiento'],
                            'id_localizacion' => $value);
                        print_r($arrayElemento);
                        $statement = $this->modeloCentrosFaenamiento->getAdapter()
                        ->getDriver()
                        ->createStatement();
                        $sqlInsertar = $this->modeloCentrosFaenamiento->guardarSql('detalle_canton_provincia', $this->modeloCentrosFaenamiento->getEsquema());
                        $sqlInsertar->columns($lnegocioDetalleCantonProvincia->columnas());
                        $sqlInsertar->values($arrayElemento, $sqlInsertar::VALUES_MERGE);
                        $sqlInsertar->prepareStatement($this->modeloCentrosFaenamiento->getAdapter(), $statement);
                        $statement->execute();
                    }
                }
            }else{
                $statement = $this->modeloCentrosFaenamiento->getAdapter()
                ->getDriver()
                ->createStatement();
                $sqlActualizar = $this->modeloCentrosFaenamiento->borrarSql('detalle_canton_provincia', $this->modeloCentrosFaenamiento->getEsquema());
                $sqlActualizar->where(array(
                    'id_centro_faenamiento' => $datos['id_centro_faenamiento']));
                $sqlActualizar->prepareStatement($this->modeloCentrosFaenamiento->getAdapter(), $statement);
                $statement->execute();
            }
            // ********************************************************************************************** 
            
            $proceso->commit();
            return true;
        }catch (\Exception $ex){
            $proceso->rollback();
            throw new \Exception($ex->getMessage());
            return false;
        }
    }

    /**
     * Borra el regis$lNegocioCentrosFaenamientotro actual
     * 
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloCentrosFaenamiento->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return CentrosFaenamientoModelo
     */
    public function buscar($id)
    {
        return $this->modeloCentrosFaenamiento->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloCentrosFaenamiento->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloCentrosFaenamiento->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCentrosFaenamiento()
    {
        $consulta = "SELECT * FROM " . $this->modeloCentrosFaenamiento->getEsquema() . ". centros_faenamiento";
        return $this->modeloCentrosFaenamiento->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada, para obtener el registro de operador
     *
     * @return array|ResultSet
     *
     */
    public function buscarFaenadorPorIdentificadorOperador($arrayParametros)
    {
        $busqueda = '';
        if (array_key_exists('id_sitio', $arrayParametros)) {
            $busqueda = "and s.id_sitio = " . $arrayParametros['id_sitio']." and a.id_area = ". $arrayParametros['id_area']." and op.id_operador_tipo_operacion = ". $arrayParametros['id_operador_tipo_operacion'] ;
        }
        if (array_key_exists('provincia', $arrayParametros)) {
            $busqueda = "and s.provincia = '" . $arrayParametros['provincia']."'";
        }
        
        $consulta = "SELECT
                    	o.identificador as identificador_operador,
                    	case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
                    	s.provincia,
                        string_agg(distinct stp.nombre,', ') as especie,
                        s.id_sitio,
                        a.id_area,
                        a.nombre_area,
                        s.nombre_lugar,
                        op.id_operador_tipo_operacion,
                        cf.id_centro_faenamiento,
                        cf.criterio_funcionamiento,
                        cf.observacion,
                        cf.codigo,
                        cf.tipo_centro_faenamiento,
                        cf.tipo_habilitacion
                    FROM
                    	g_operadores.operadores o 
                    	INNER JOIN g_operadores.sitios s ON s.identificador_operador = o.identificador
                        INNER JOIN g_operadores.areas a ON a.id_sitio = s.id_sitio
                        INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                        INNER JOIN g_operadores.operaciones op ON op.id_operacion = pao.id_operacion
                        INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = op.id_tipo_operacion
                        INNER JOIN g_catalogos.productos p ON p.id_producto = op.id_producto
                        INNER JOIN g_catalogos.subtipo_productos stp ON stp.id_subtipo_producto = p.id_subtipo_producto
                        LEFT JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_sitio = s.id_sitio and cf.id_area = a.id_area and cf.id_operador_tipo_operacion = op.id_operador_tipo_operacion
                    WHERE
                    	s.identificador_operador = '" . $arrayParametros['identificador_operador'] . "'
                        and top.id_area = '" . $arrayParametros['id_area_tipo_operacion'] . "'
                        and top.codigo = '" . $arrayParametros['codigo'] . "'
                        and op.estado in ('registrado','noHabilitado')
                        " . $busqueda . "
                    GROUP BY 
                        o.identificador, s.provincia, s.id_sitio, a.id_area, s.nombre_lugar, a.nombre_area,op.id_operador_tipo_operacion, cf.id_centro_faenamiento;";
        
        $this->modeloCentrosFaenamiento->setCodigoEjecutable($consulta);
        
        return $this->modeloCentrosFaenamiento->ejecutarSqlNativo($consulta);
    }
    
    public function obtenerEstadoOperacionesRegistroOperador($arrayParametros){
        
        $consulta = "SELECT distinct estado as estado FROM g_operadores.operaciones WHERE id_operador_tipo_operacion = '" . $arrayParametros['id_operador_tipo_operacion'] . "'";
        
        return $this->modeloCentrosFaenamiento->ejecutarSqlNativo($consulta);
    }
    
    public function actualizarEstadoOperacion($arrayParametros){

        $consulta = "update
						g_operadores.operaciones o
					set
						estado_anterior = op.estado,
                        estado = '".$arrayParametros['estado'] . "',
                        observacion = 'Estado actualizado por medio del módulo de centros de faenamiento.',
                        fecha_modificacion = 'now()',
                        observacion_tecnica = 'Estado actualizado por medio del módulo de centros de faenamiento.'
					from
						g_operadores.operaciones op
					where
						o.id_operacion = op.id_operacion and
						op.id_operador_tipo_operacion = '" . $arrayParametros['id_operador_tipo_operacion'] . "'";

        $this->modeloCentrosFaenamiento->ejecutarSqlNativo($consulta);
        
        $consulta = "UPDATE 
                    	g_operadores.productos_areas_operacion pao
                    SET 
                    	estado = op.estado
                    FROM 
                    	g_operadores.operaciones op
                    WHERE
                    	pao.id_operacion = op.id_operacion
                    	and op.id_operador_tipo_operacion = '" . $arrayParametros['id_operador_tipo_operacion'] . "'";

        $this->modeloCentrosFaenamiento->ejecutarSqlNativo($consulta);
        
        $consulta = "UPDATE
                    	g_operadores.operadores_tipo_operaciones
                    SET
                    	estado = '".$arrayParametros['estado'] . "'
                    WHERE
                    	id_operador_tipo_operacion = '" . $arrayParametros['id_operador_tipo_operacion'] . "'";

        $this->modeloCentrosFaenamiento->ejecutarSqlNativo($consulta);
    }
//**************************modificado el 02-12-2020********************************
    public function obtenerNombreLocalizacion($idLocalizacion){
        
        $consulta = "SELECT 
                            nombre 
                    FROM 
                           g_catalogos.localizacion
                    WHERE id_localizacion = $idLocalizacion;";
        
        return $this->modeloCentrosFaenamiento->ejecutarSqlNativo($consulta);
    }
    
}

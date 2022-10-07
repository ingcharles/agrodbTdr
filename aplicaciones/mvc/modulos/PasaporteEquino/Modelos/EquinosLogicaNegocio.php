<?php
/**
 * Lógica del negocio de EquinosModelo
 *
 * Este archivo se complementa con el archivo EquinosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-18
 * @uses    EquinosLogicaNegocio
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\PasaporteEquino\Modelos\IModelo;

use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosEspecieLogicaNegocio;

use Agrodb\PasaporteEquino\Modelos\RegistroDecesosLogicaNegocio;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class EquinosLogicaNegocio implements IModelo
{

    private $modeloEquinos = null;
    
    private $lNegocioCatastroPredioEquidosEspecie = null;
    
    private $lNegocioRegistroDecesos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloEquinos = new EquinosModelo();
        
        $this->lNegocioCatastroPredioEquidosEspecie = new CatastroPredioEquidosEspecieLogicaNegocio();
        
        $this->lNegocioRegistroDecesos = new RegistroDecesosLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new EquinosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdEquino() != null && $tablaModelo->getIdEquino() > 0) {
            return $this->modeloEquinos->actualizar($datosBd, $tablaModelo->getIdEquino());
        } else {
            unset($datosBd["id_equino"]);
            return $this->modeloEquinos->guardar($datosBd);
        }
    }
    
    /**
     * Valida el número de equinos disponibles en un predio para asignar un pasaporte
     *
     * @param array $datos
     * @return int
     */
    public function guardarEquino(Array $datos)
    {
        $resultado = array(
            'bandera' => false,
            'estado' => "Fallo",
            'mensaje' => "Ocurrió un error al guardar la información del equino",
            'contenido' => null
        );
        
        if(!isset($datos['id_equino'])){
            $datos['accion'] = 'guardar';
            $resultado = $this->validarNumeroEquinosXCategoriasXEspecieXPredio($datos);
            
            if ($resultado['bandera']) {
                $datos['pasaporte'] = $this->generarNumeroPasaporte();
                $datos['ubicacion_actual'] = $datos['id_catastro_predio_equidos'];
                
                $resultado['contenido'] = $this->guardar($datos);
                
            }
        }else{
            $equinoActual = $this->buscar($datos['id_equino']);
            
            switch ($datos['estado_equino']){
                case 'Activo':
                case 'Inactivo':{
                    //Verificar que la asociación sea la misma que solicita para poder hacer el cambio
                    if($equinoActual->getIdOrganizacionEcuestre() == $datos['idAsociacion']){
                        $resultado['bandera'] = true;
                        $resultado['estado'] = 'exito';
                        $resultado['mensaje'] = 'Se han actualizado los datos del equino';
                    }else{
                        $resultado['bandera'] = false;
                        $resultado['estado'] = 'Fallo';
                        $resultado['mensaje'] = 'El equino pertenece a otra organización y no puede ser modificado.';
                    }
                                        
                    break;
                }
                case 'Liberado':{
                    //Verificar que la asociación sea la misma que solicita para poder hacer el cambio
                    if($equinoActual->getIdOrganizacionEcuestre() == $datos['idAsociacion']){
                        $resultado['bandera'] = true;
                        $resultado['estado'] = 'exito';
                        $resultado['mensaje'] = 'Se han actualizado los datos del equino';
                    }else{
                        $resultado['bandera'] = false;
                        $resultado['estado'] = 'Fallo';
                        $resultado['mensaje'] = 'El equino pertenece a otra organización y no puede ser modificado.';
                    }                    
                    
                    break;
                }
                case 'Deceso':{
                    if($equinoActual->getIdOrganizacionEcuestre() == $datos['idAsociacion']){
                        $resultado = $this->registrarDeceso($datos);
                        /*$resultado['bandera'] = true;
                        $resultado['estado'] = 'exito';
                        $resultado['mensaje'] = 'Se han actualizado los datos del equino';*/
                    }else{
                        $resultado['bandera'] = false;
                        $resultado['estado'] = 'Fallo';
                        $resultado['mensaje'] = 'El equino pertenece a otra organización y no puede ser modificado.';
                    }
                    
                    break;
                }
                case 'Vinculacion':{
                    //Verificar el estado actual del equino para poder hacer el cambio
                    if($equinoActual->getEstadoEquino() == 'Liberado'){
                        $datos['estado_equino'] = 'Activo';
                        $resultado['bandera'] = true;
                        $resultado['estado'] = 'exito';
                        $resultado['mensaje'] = 'Se han actualizado los datos del equino';
                    }else{
                        $resultado['bandera'] = false;
                        $resultado['estado'] = 'Fallo';
                        $resultado['mensaje'] = 'El equino ya ha sido vinculado a otra organización.';
                    }
                    
                    break;
                }
                case 'Traspaso':{
                    //Verificar el estado actual del equino para poder hacer el cambio
                    if($equinoActual->getIdOrganizacionEcuestre() == $datos['idAsociacion']){
                        $datos['estado_equino'] = 'Activo';
                        $resultado['bandera'] = true;
                        $resultado['estado'] = 'exito';
                        $resultado['mensaje'] = 'Se han actualizado los datos del equino';
                    }else{
                        $resultado['bandera'] = false;
                        $resultado['estado'] = 'Fallo';
                        $resultado['mensaje'] = 'El equino ya ha sido vinculado a otra organización.';
                    }
                    break;
                }
                default:{
                    $resultado['bandera'] = false;
                    $resultado['estado'] = 'Fallo';
                    $resultado['mensaje'] = 'No es un estado de cambio válido';
                }
            }
            
            
            
            /*if($datos['estado_equino'] == 'Vinculacion' || $datos['estado_equino'] == 'Traspaso'){
                $datos['estado_equino'] = 'Activo';
                $resultado['bandera'] = true;
                $resultado['estado'] = 'exito';
                $resultado['mensaje'] = 'Se han actualizado los datos del equino';
                
            }else if($datos['estado_equino'] == 'Deceso'){
                $resultado = $this->registrarDeceso($datos);
                
            }if($datos['estado_equino'] == 'Activo' || $datos['estado_equino'] == 'Inactivo' ){
                $resultado['bandera'] = true;
                $resultado['estado'] = 'exito';
                $resultado['mensaje'] = 'Se han actualizado los datos del equino';
            }else{//Liberado
                $resultado['bandera'] = true;
                $resultado['estado'] = 'exito';
                $resultado['mensaje'] = 'Se han actualizado los datos del equino';
            }*/
            
            if ($resultado['bandera']) {
                $resultado['contenido'] = $this->guardar($datos);                
            }            
        }
        
        return $resultado;
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
        $this->modeloEquinos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return EquinosModelo
     */
    public function buscar($id)
    {
        return $this->modeloEquinos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloEquinos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloEquinos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarEquinos()
    {
        $consulta = "SELECT * FROM " . $this->modeloEquinos->getEsquema() . ". equinos";
        return $this->modeloEquinos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar miembros usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarEquinosFiltrados($arrayParametros)
    {
        $busqueda = '';
        $busquedaUnion = '';
        $estadoEquino = '';
        $union = 'ORDER BY e.nombre_equino ASC';

        if ($arrayParametros['menu'] == "emisionPasaporte" || $arrayParametros['menu'] == "deceso") {
            $estadoEquino = "'Activo', 'Inactivo'";
            
            if (isset($arrayParametros['id_organizacion_ecuestre']) && ($arrayParametros['id_organizacion_ecuestre'] != '')) {
                $busqueda .= " and e.id_organizacion_ecuestre = " . $arrayParametros['id_organizacion_ecuestre'];
            }
            
            if (isset($arrayParametros['id_miembro']) && ($arrayParametros['id_miembro'] != '')) {
                $busqueda .= " and e.id_miembro = " . $arrayParametros['id_miembro'];
            }
            
            if (isset($arrayParametros['id_provincia']) && ($arrayParametros['id_provincia'] != '')) {
                $busqueda .= " and cpe.id_provincia = " . $arrayParametros['id_provincia'];
            }
            
            if (isset($arrayParametros['pasaporte']) && ($arrayParametros['pasaporte'] != '')) {
                $busqueda .= " and upper(e.pasaporte) ilike upper('%" . $arrayParametros['pasaporte'] . "%') ";
            }
            
        }else if ($arrayParametros['menu'] == "liberacionTraspaso") {//revisar con otra asociacion
            $estadoEquino = "'Activo', 'Inactivo', 'Liberado'";
            
            if (isset($arrayParametros['id_organizacion_ecuestre']) && ($arrayParametros['id_organizacion_ecuestre'] != '')) {
                $busqueda .= " and e.id_organizacion_ecuestre = " . $arrayParametros['id_organizacion_ecuestre'];
            
                $busquedaUnion .= " and e.id_organizacion_ecuestre not in ( " . $arrayParametros['id_organizacion_ecuestre'] . ")";
            }
            
            if (isset($arrayParametros['pasaporte']) && ($arrayParametros['pasaporte'] != '')) {
                $busqueda .= " and upper(e.pasaporte) ilike upper('%" . $arrayParametros['pasaporte'] . "%') ";
                
                $busquedaUnion .= " and upper(e.pasaporte) ilike upper('%" . $arrayParametros['pasaporte'] . "%') ";
            }
            
            $union = "  UNION
                
                        SELECT 
                            * 
                        FROM g_pasaporte_equino.equinos e 
                            INNER JOIN g_pasaporte_equino.miembros m ON e.id_miembro = m.id_miembro 
                            INNER JOIN g_pasaporte_equino.organizacion_ecuestre oe ON e.id_organizacion_ecuestre = oe.id_organizacion_ecuestre 
                            INNER JOIN g_programas_control_oficial.catastro_predio_equidos cpe ON m.id_catastro_predio_equidos = cpe.id_catastro_predio_equidos 
                        WHERE 
                            e.estado_equino in ('Liberado') " . $busquedaUnion ;
            
        }else {
            $estadoEquino = "'Inactivo'";
        }
                
                
        $consulta = "  SELECT
                        	*
                        FROM
                        	g_pasaporte_equino.equinos e
                            INNER JOIN g_pasaporte_equino.miembros m ON e.id_miembro = m.id_miembro
                            INNER JOIN g_pasaporte_equino.organizacion_ecuestre oe ON e.id_organizacion_ecuestre = oe.id_organizacion_ecuestre
                            INNER JOIN g_programas_control_oficial.catastro_predio_equidos cpe ON m.id_catastro_predio_equidos = cpe.id_catastro_predio_equidos
                        WHERE
                            e.estado_equino in (" . $estadoEquino . ") 
                            " . $busqueda . " ".
                            $union.";";
                            //revvisar si cpe ON m.id_catastro_predio_equidos = cpe.id_catastro_predio_equidos esta bien la referencia a m.
        //echo $consulta;
        return $this->modeloEquinos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las categorías de especies registradas en un predio
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroEquinosConPasaporte($idPredio, $idEspecie, $idRaza, $idCategoria)
    {
        $consulta = "   SELECT
                        	count(e.id_equino) as numero_pasaportes
                        FROM
                        	g_pasaporte_equino.equinos e
                        WHERE
                        	e.id_catastro_predio_equidos = $idPredio and
                            e.id_especie = $idEspecie and
                            e.id_raza = $idRaza and
                            e.id_categoria = $idCategoria and
	                        e.estado_equino not in ('Deceso');";
        //print_r($consulta);
        return $this->modeloEquinos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Valida el número de equinos disponibles en un predio para asignar un pasaporte
     *
     * @param array $datos
     * @return int
     */
    public function validarNumeroEquinosXCategoriasXEspecieXPredio(Array $datos)
    {
        $validacion = array(
            'bandera' => false,
            'estado' => "Fallo",
            'mensaje' => "Ocurrió un error al guardar la información del equino",
            'contenido' => null,
            'numero_total' => 0,
            'numero_pasaportes' => 0,
            'numero_disponibles' => 0,
            'id_registro_especie' => null
        );
        
        $idPredio = $datos['id_catastro_predio_equidos'];
        $idEspecie = $datos['id_especie'];
        $idRaza = $datos['id_raza'];
        $idCategoria = $datos['id_categoria'];
        
        // Busca el número total de animales disponibles en el predio (por los parámetros enviados)
        $totalAnimales = $this->lNegocioCatastroPredioEquidosEspecie->obtenerNumeroEquinosCategoriasXEspecieXPredio($idPredio, $idEspecie, $idRaza, $idCategoria);
        
        if ($totalAnimales->current()->numero_animales != '') {
            //print_r($totalAnimales->current()->numero_animales.'entro y hay animales');
            $numeroTotal = $totalAnimales->current()->numero_animales;
            $idCatastroPredioEquidosEspecie = $totalAnimales->current()->id_catastro_predio_equidos_especie;
            
            // Busca el número de animales del predio que ya tienen pasaporte (por los parámetros enviados)
            $numeroCreados = $this->obtenerNumeroEquinosConPasaporte($idPredio, $idEspecie, $idRaza, $idCategoria);
            //print_r($numeroCreados->current()->numero_pasaportes.'pasaportes');
            if ($numeroCreados->current()->numero_pasaportes != null) {
                //print_r($numeroCreados->current()->numero_pasaportes.'entro y hay animales con pasaporte');
                $numeroPasaportes = $numeroCreados->current()->numero_pasaportes;
            }else{
                $numeroPasaportes = 0;
            }
            
            $numeroDisponibles = $numeroTotal - $numeroPasaportes;
            
            if($numeroDisponibles > 0){
                $validacion['bandera'] = true;
                $validacion['estado'] = 'exito';
                $validacion['mensaje'] = ($datos['accion'] == 'validar'?'Se puede generar un nuevo pasaporte.':'Se ha generado el pasaporte para el equino');
                $validacion['numero_total'] = $numeroTotal;
                $validacion['numero_pasaportes'] = $numeroPasaportes;
                $validacion['numero_disponibles'] = $numeroDisponibles;
                $validacion['id_registro_especie'] = $idCatastroPredioEquidosEspecie;
            }else{
                $validacion['bandera'] = false;
                $validacion['estado'] = 'Completo';
                $validacion['mensaje'] = 'Todos los animales ya disponen de un pasaporte asignado.';
                $validacion['numero_total'] = $numeroTotal;
                $validacion['numero_pasaportes'] = $numeroPasaportes;
                $validacion['numero_disponibles'] = $numeroDisponibles;
            }
            
        } else {
            $validacion['bandera'] = false;
            $validacion['estado'] = 'Fallo';
            $validacion['mensaje'] = 'No se dispone de animales en el predio con esas características.';
            $validacion['numero_total'] = $numeroTotal;
            $validacion['numero_pasaportes'] = $numeroPasaportes;
            $validacion['numero_disponibles'] = $numeroDisponibles;
        }
        
        return $validacion;
    }
    
    /**
     * Genera el número de pasaporte equino
     *
     * @param array $datos
     * @return String
     */
    public function generarNumeroPasaporte()
    {
        $formatoCodigo = "PE-593-";
        $codigoBase = 'PE-593';
        
        $consulta = "SELECT
						max(split_part(pasaporte, '$formatoCodigo' , 2)::int) as numero
					FROM
						g_pasaporte_equino.equinos
					WHERE pasaporte LIKE '$codigoBase%';";
        //echo $consulta;
        $codigo = $this->modeloEquinos->ejecutarSqlNativo($consulta);
        $fila = $codigo->current();
        
        $numPasaporte = array(
            'numero' => $fila['numero']
        );
        
        $incremento = $numPasaporte['numero'] + 1;
        $pasaporte = $formatoCodigo . str_pad($incremento, 6, "0", STR_PAD_LEFT);
        
        return $pasaporte;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar miembros usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarDetalleEquinos($arrayParametros)
    {
        $consulta = "   SELECT 
                        	distinct e.*, es.nombre as nombre_especie, r.raza, ce.categoria_especie
                        FROM 
                        	g_pasaporte_equino.equinos e 
                        	INNER JOIN g_catalogos.especies es ON e.id_especie = es.id_especies 
                        	INNER JOIN g_catalogos.raza r ON e.id_raza = r.id_raza
                        	INNER JOIN g_catalogos.categoria_especie ce ON e.id_categoria = ce.id_categoria_especie
                        WHERE 
                        	e.estado_equino in (" . $arrayParametros['estado_equino'] . ") and 
                        	
                        	e.id_catastro_predio_equidos = " . $arrayParametros['id_catastro_predio_equidos'] . " and
                            e.id_especie = " . $arrayParametros['id_especie'] . "  and
                            e.id_raza = " . $arrayParametros['id_raza'] . "  and
                            e.id_categoria = " . $arrayParametros['id_categoria'] . " 
                        ORDER BY e.id_equino ASC;";

        /*--e.id_organizacion_ecuestre = " . $arrayParametros['id_organizacion_ecuestre'] . " and 
                        	--e.id_miembro = " . $arrayParametros['id_miembro'] . " and */
        return $this->modeloEquinos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para registrar en tabla auxiliar el decremento de un equino y guardar los resultados.
     *
     * @param array $datos
     * @return int
     */
    public function registrarDeceso(Array $datos)
    {
        $validacion = array(
            'bandera' => false,
            'estado' => "Fallo",
            'mensaje' => "Ocurrió un error al guardar la información del equino",
            'contenido' => null,
            'numero_total' => 0,
            'numero_disponibles' => 0
        );
        
        //Obtener datos del equino
        $equino = $this->buscar($datos['id_equino']);
        
        $idPredio = $equino->getIdCatastroPredioEquidos();
        $idEspecie = $equino->getIdEspecie();
        $idRaza = $equino->getIdRaza();
        $idCategoria = $equino->getIdCategoria();
        $ubicacionActual = $equino->getUbicacionActual();
        $estadoActual = $equino->getEstadoEquino();
        
        // Busca el número total de animales disponibles en el predio (por los parámetros enviados)
        $totalAnimales = $this->lNegocioCatastroPredioEquidosEspecie->obtenerNumeroEquinosCategoriasXEspecieXPredio($idPredio, $idEspecie, $idRaza, $idCategoria);
        
        if ($totalAnimales->current()->numero_animales != '') {
            //print_r($totalAnimales->current()->numero_animales.'entro y hay animales');
            $numeroTotal = $totalAnimales->current()->numero_animales;
            $idCatastroEspecie = $totalAnimales->current()->id_catastro_predio_equidos_especie;
            
            if($numeroTotal > 0){
                $numeroDisponibles = $numeroTotal - 1;
                
                $arrayParametros = array(
                    'id_catastro_predio_equidos_especie' => $idCatastroEspecie,
                    'id_catastro_predio_equidos' => $idPredio,
                    'id_especie' => $idEspecie,
                    'id_raza' => $idRaza,
                    'id_categoria' => $idCategoria,
                    'numero_animales' => $numeroDisponibles
                );
                
                $num = $this->lNegocioCatastroPredioEquidosEspecie->guardar($arrayParametros);
                
                $arrayParametrosDeceso = array(
                    'id_equino' => $datos['id_equino'],
                    'id_catastro_predio_equidos' => $idPredio,
                    'id_catastro_predio_equidos_especie' => $idCatastroEspecie,
                    'numero_total' => $numeroTotal,
                    'numero_actual' => $numeroDisponibles,
                    'identificador' => $_SESSION['usuario']
                );
                
                $numDes = $this->lNegocioRegistroDecesos->guardar($arrayParametrosDeceso);
                
                if($num > 0 && $numDes > 0){
                    $validacion['bandera'] = true;
                    $validacion['estado'] = 'exito';
                    $validacion['mensaje'] = 'Se ha registrado el deceso en el predio';
                    $validacion['numero_total'] = $numeroTotal;
                    $validacion['numero_disponibles'] = $numeroDisponibles;
                    
                }else{
                    $validacion['bandera'] = false;
                    $validacion['estado'] = 'Fallo';
                    $validacion['mensaje'] = 'Ha ocurrido un error al registrar el deceso.';
                    $validacion['numero_total'] = $numeroTotal;
                    $validacion['numero_disponibles'] = $numeroTotal;
                }
            }else{
                $validacion['bandera'] = false;
                $validacion['estado'] = 'Fallo';
                $validacion['mensaje'] = 'Ya no dispone de animales en el predio para registrar el deceso.';
                $validacion['numero_total'] = $numeroTotal;
                $validacion['numero_disponibles'] = $numeroDisponibles;
            }
            
        } else {
            $validacion['bandera'] = false;
            $validacion['estado'] = 'Fallo';
            $validacion['mensaje'] = 'No se dispone de animales en el predio con esas características.';
            $validacion['numero_total'] = $numeroTotal;
            $validacion['numero_disponibles'] = $numeroDisponibles;
        }
        
        return $validacion;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar miembros usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarEquinoXPasaporte($arrayParametros)
    {
        $busqueda = '';
        
        /*if (isset($arrayParametros['identificador']) && ($arrayParametros['identificador'] != '')) {
            $busqueda .= " and oe.identificador_organizacion = '" . $arrayParametros['identificador'] ."' ";
        }*/
        
        if (isset($arrayParametros['identificador']) && ($arrayParametros['identificador'] != '') && isset($arrayParametros['tipo_usuario']) && ($arrayParametros['tipo_usuario'] != '')) {
            
            if($arrayParametros['tipo_usuario'] == 'CentroConcentracion'){
                $busqueda .= " and e.ubicacion_actual in (
                                                            SELECT
                                                                distinct id_catastro_predio_equidos
                                                            FROM
                                                                g_programas_control_oficial.catastro_predio_equidos
                                                            WHERE
                                                                cedula_propietario = '" . $arrayParametros['identificador'] ."'
                                                            ) ";
            }else{
                $busqueda .= " and e.ubicacion_actual in (
                                                        SELECT
                                                            distinct m.id_catastro_predio_equidos
                                                        FROM
                                                            g_pasaporte_equino.miembros m
                                                            INNER JOIN g_pasaporte_equino.organizacion_ecuestre o ON o.id_organizacion_ecuestre = m.id_organizacion_ecuestre
                                                        WHERE
                                                            o.identificador_organizacion = '" . $arrayParametros['identificador'] ."'
                                                        ) ";
            }
        }
        
        if (isset($arrayParametros['pasaporte']) && ($arrayParametros['pasaporte'] != '')) {
            $busqueda .= " and upper(e.pasaporte) = upper('" . $arrayParametros['pasaporte'] . "') ";
        }
            
       $consulta = "  SELECT
                        	e.id_organizacion_ecuestre,
                        	oe.identificador_organizacion,
                        	oe.razon_social,
                        	oe.nombre_asociacion,
                        	e.id_miembro,
                        	m.identificador_miembro,
                        	m.nombre_miembro,
                        	e.id_equino,
                            e.id_especie,
                            e.id_raza,
                            e.id_categoria,
                        	e.pasaporte,
                        	e.id_catastro_predio_equidos,
                        	e.ubicacion_actual,
                        	cpe.num_solicitud,
                        	cpe.nombre_predio,
                            cpe.cedula_propietario,
                            cpe.nombre_propietario,
                        	cpe.id_provincia,
                        	cpe.provincia,
                        	cpe.id_canton,
                        	cpe.canton,
                        	cpe.id_parroquia,
                        	cpe.parroquia,
                        	cpe.direccion_predio,
                            cpe.id_sitio,
                            cpe.id_area,
                            e.ruta_hoja_filiacion,
	                       (SELECT resultado_examen 
                            FROM g_pasaporte_equino.examenes_equino ee 
                            WHERE ee.id_equino = e.id_equino ORDER BY ee.id_examen_equino DESC LIMIT 1) as examen,/**/
							(SELECT
                        		cpee.numero_animales
							FROM
								g_programas_control_oficial.catastro_predio_equidos_especie cpee
							WHERE
								cpee.id_catastro_predio_equidos = e.ubicacion_actual and
								cpee.id_especie = e.id_especie and
								cpee.id_raza = e.id_raza and
								cpee.id_categoria = e.id_categoria and
								cpee.numero_animales > 0) as num_equinos_predio/**/
                        FROM
                        	g_pasaporte_equino.equinos e
                            INNER JOIN g_pasaporte_equino.miembros m ON e.id_miembro = m.id_miembro
                            INNER JOIN g_pasaporte_equino.organizacion_ecuestre oe ON e.id_organizacion_ecuestre = oe.id_organizacion_ecuestre
                            INNER JOIN g_programas_control_oficial.catastro_predio_equidos cpe ON e.ubicacion_actual = cpe.id_catastro_predio_equidos
                        WHERE
                            e.estado_equino in ('Activo') and
                            m.estado_miembro = 'Activo' and
                            oe.estado_organizacion = 'Activo'
                            " . $busqueda . ";";
                            
       return $this->modeloEquinos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar equinos para reporte usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarEquinosReporteFiltrados($arrayParametros)
    {
        $busqueda = '';
                
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '') ) {
            
            $busqueda .= " e.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00'";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '') ) {
            
            $busqueda .= "and e.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00'";
        }
        
        if (isset($arrayParametros['id_provincia']) && ($arrayParametros['id_provincia'] != '') && ($arrayParametros['id_provincia'] != 'Todas')) {
            $busqueda .= " and cpe.id_provincia = " . $arrayParametros['id_provincia'];
        }
        
        if (isset($arrayParametros['id_canton']) && ($arrayParametros['id_canton'] != '') && ($arrayParametros['id_canton'] != 'Todos')) {
            $busqueda .= " and cpe.id_canton = " . $arrayParametros['id_canton'];
        }
        
        if (isset($arrayParametros['estado_equino']) && ($arrayParametros['estado_equino'] != '') && ($arrayParametros['estado_equino'] != 'Todos')) {
            
            $busqueda .= " and e.estado_equino in (" . $arrayParametros['estado_equino'] . ")";
        }
        
        
        $consulta = "  SELECT
                        	e.id_equino,
                            oe.nombre_asociacion,
                        	oe.provincia as provincia_asociacion,
                        	cpe.nombre_predio,
                        	cpe.provincia,
                        	cpe.canton,
                        	cpe.parroquia,
                        	e.pasaporte,
                            e.nombre_equino,
                            es.nombre as especie,
							r.raza,
							c.categoria_especie as categoria,
                        	e.estado_equino,
							(select 
								distinct RTRIM(array_to_string(array_agg( 'Fecha examen: ' || date(ee.fecha_examen) || ' Resultado examen: ' || ee.resultado_examen|| ' Laboratorio: ' || ee.laboratorio), ' - '),' - ') as detalle
							FROM
								g_pasaporte_equino.examenes_equino ee 
							 WHERE 
							 	ee.id_equino = e.id_equino 
							ORDER BY
								1 ASC) as examen_equino,
                        	(select 
								distinct RTRIM(array_to_string(array_agg( 'Fecha vacuna: ' || date(ve.fecha_enfermedad) || ' Enfermedad: ' || ve.enfermedad|| ' Laboratorio/lote: ' || ve.laboratorio_lote), ' - '),' - ') as detalle
							FROM
								g_pasaporte_equino.vacunas_equino ve 
							 WHERE 
							 	ve.id_equino = e.id_equino 
							ORDER BY
								1 ASC) as vacunas_equino,
                        	e.fecha_deceso,
                        	e.causa_muerte
                        FROM
                        	g_pasaporte_equino.equinos e
                        	INNER JOIN g_pasaporte_equino.miembros m ON e.id_miembro = m.id_miembro
                        	INNER JOIN g_pasaporte_equino.organizacion_ecuestre oe ON e.id_organizacion_ecuestre = oe.id_organizacion_ecuestre
                        	INNER JOIN g_programas_control_oficial.catastro_predio_equidos cpe ON e.ubicacion_actual = cpe.id_catastro_predio_equidos
                            INNER JOIN g_catalogos.especies es ON e.id_especie = es.id_especies
							INNER JOIN g_catalogos.raza r ON e.id_raza = r.id_raza
							INNER JOIN g_catalogos.categoria_especie c ON e.id_categoria = c.id_categoria_especie

                        WHERE
                            
                            " . $busqueda . ";";

        //echo $consulta;
        return $this->modeloEquinos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta un reporte en Excel de los pasaportes
     *
     * @return array|ResultSet
     */
    public function exportarArchivoExcelPasaportes($datos){
        
        $hoja = new Spreadsheet();
        $documento = $hoja->getActiveSheet();
        $i = 3;
        $j = 2;
        
        $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Pasaportes Equinos');
        
        $documento->setCellValueByColumnAndRow(1, $j, 'ID');        
        
        $documento->setCellValueByColumnAndRow(2, $j, 'Organización Ecuestre');
        $documento->setCellValueByColumnAndRow(3, $j, 'Provincia organización');
        $documento->setCellValueByColumnAndRow(4, $j, 'Predio (ubicación actual)');
        $documento->setCellValueByColumnAndRow(5, $j, 'Provincia predio');
        $documento->setCellValueByColumnAndRow(6, $j, 'Cantón predio');
        $documento->setCellValueByColumnAndRow(7, $j, 'Parroquia predio');
        
        $documento->setCellValueByColumnAndRow(8, $j, 'Num Pasaporte');
        $documento->setCellValueByColumnAndRow(9, $j, 'Nombre');
        $documento->setCellValueByColumnAndRow(10, $j, 'Especie');
        $documento->setCellValueByColumnAndRow(11, $j, 'Raza');
        $documento->setCellValueByColumnAndRow(12, $j, 'Categoría');
        $documento->setCellValueByColumnAndRow(13, $j, 'Estado pasaporte');
        
        $documento->setCellValueByColumnAndRow(14, $j, 'Exámenes equino');
        $documento->setCellValueByColumnAndRow(15, $j, 'Vacunas equino');
        
        $documento->setCellValueByColumnAndRow(16, $j, 'Fecha deceso');
        $documento->setCellValueByColumnAndRow(17, $j, 'Causa muerte');
        
        if($datos != ''){
            foreach ($datos as $fila){
                $documento->setCellValueByColumnAndRow(1, $i, $fila['id_equino']);
                $documento->setCellValueByColumnAndRow(2, $i, $fila['nombre_asociacion']);
                $documento->setCellValueByColumnAndRow(3, $i, $fila['provincia_asociacion']);
                $documento->setCellValueByColumnAndRow(4, $i, $fila['nombre_predio']);
                $documento->setCellValueByColumnAndRow(5, $i, $fila['provincia']);
                $documento->setCellValueByColumnAndRow(6, $i, $fila['canton']);
                $documento->setCellValueByColumnAndRow(7, $i, $fila['parroquia']);
                
                $documento->setCellValueByColumnAndRow(8, $i, $fila['pasaporte']);
                $documento->setCellValueByColumnAndRow(9, $i, $fila['nombre_equino']);
                $documento->setCellValueByColumnAndRow(10, $i, $fila['especie']);
                $documento->setCellValueByColumnAndRow(11, $i, $fila['raza']);
                $documento->setCellValueByColumnAndRow(12, $i, $fila['categoria']);
                $documento->setCellValueByColumnAndRow(13, $i, $fila['estado_equino']);
                
                $documento->setCellValueByColumnAndRow(14, $i, $fila['examen_equino']);
                $documento->setCellValueByColumnAndRow(15, $i, $fila['vacunas_equino']);
                
                $documento->setCellValueByColumnAndRow(16, $i, ($fila['fecha_deceso']!=null?date('Y-m-d',strtotime($fila['fecha_deceso'])):''));
                $documento->setCellValueByColumnAndRow(17, $i, $fila['causa_muerte']);
                
                $i++;
            }
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="excelPasaportes.xlsx"');
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $writer = IOFactory::createWriter($hoja, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
}
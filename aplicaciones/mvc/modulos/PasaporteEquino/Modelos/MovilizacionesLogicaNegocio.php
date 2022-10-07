<?php
/**
 * Lógica del negocio de MovilizacionesModelo
 *
 * Este archivo se complementa con el archivo MovilizacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-14
 * @uses    MovilizacionesLogicaNegocio
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\PasaporteEquino\Modelos\IModelo;

use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosLogicaNegocio;
use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosModelo;
use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosEspecieLogicaNegocio;
use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosEspecieModelo;

use Agrodb\PasaporteEquino\Modelos\EquinosLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\EquinosModelo;

use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresModelo;

use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionModelo;

use Agrodb\RegistroOperador\Modelos\SitiosLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\SitiosModelo;
use Agrodb\RegistroOperador\Modelos\AreasLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\AreasModelo;

use Agrodb\Core\JasperReport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class MovilizacionesLogicaNegocio implements IModelo
{

    private $modeloMovilizaciones = null;
    
    private $lNegocioCatastroPredioEquidos = null;
    private $modeloCatastroPredioEquidos = null;
    
    private $lNegocioCatastroPredioEquidosEspecie = null;
    private $modeloCatastroPredioEquidosEspecie = null;
    
    private $lNegocioEquinos = null;
    private $modeloEquinos = null;
    
    private $lNegocioOperadores = null;
    private $modeloOperadores = null;
    
    private $lNegocioLocalizacion = null;
    private $modeloLocalizacion = null;
    
    private $lNegocioSitios = null;
    private $modeloSitios = null;
    
    private $lNegocioAreas = null;
    private $modeloAreas = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloMovilizaciones = new MovilizacionesModelo();
        
        $this->lNegocioCatastroPredioEquidos = new CatastroPredioEquidosLogicaNegocio();
        $this->modeloCatastroPredioEquidos = new CatastroPredioEquidosModelo();
        
        $this->lNegocioCatastroPredioEquidosEspecie = new CatastroPredioEquidosEspecieLogicaNegocio();
        $this->modeloCatastroPredioEquidosEspecie = new CatastroPredioEquidosEspecieModelo();
        
        $this->lNegocioEquinos = new EquinosLogicaNegocio();
        $this->modeloEquinos = new EquinosModelo();
        
        $this->lNegocioOperadores = new OperadoresLogicaNegocio();
        $this->modeloOperadores = new OperadoresModelo();
        
        $this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
        $this->modeloLocalizacion = new LocalizacionModelo();
        
        $this->lNegocioSitios = new SitiosLogicaNegocio();
        $this->modeloSitios = new SitiosModelo();
        
        $this->lNegocioAreas = new AreasLogicaNegocio();
        $this->modeloAreas = new AreasModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new MovilizacionesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdMovilizacion() != null && $tablaModelo->getIdMovilizacion() > 0) {
            return $this->modeloMovilizaciones->actualizar($datosBd, $tablaModelo->getIdMovilizacion());
        } else {
            unset($datosBd["id_movilizacion"]);
            return $this->modeloMovilizaciones->guardar($datosBd);
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
        $this->modeloMovilizaciones->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return MovilizacionesModelo
     */
    public function buscar($id)
    {
        return $this->modeloMovilizaciones->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloMovilizaciones->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloMovilizaciones->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarMovilizaciones()
    {
        $consulta = "SELECT * FROM " . $this->modeloMovilizaciones->getEsquema() . ". movilizaciones";
        return $this->modeloMovilizaciones->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar miembros usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarMovilizacionesFiltradas($arrayParametros)
    {
        //print_r($arrayParametros);
        $busqueda = '';        
        
        if($arrayParametros['tipoProceso'] == 'Movilizacion'){
            //Las movilizaciones creadas por su usuario
            $busqueda .= "and m.identificador = '".$_SESSION['usuario']."'";
            
            /*if (isset($arrayParametros['id_asociacion']) && ($arrayParametros['id_asociacion'] != '')) {
                $busqueda .= " and m.id_asociacion = " . $arrayParametros['id_asociacion'];
            }*/
            
        }else{//Fiscalizacion
            if($arrayParametros['tipoUsuario'] == 'CentroConcentracion'){
                $busqueda .= " and m.id_ubicacion_destino in (SELECT id_catastro_predio_equidos 
                                                                FROM g_programas_control_oficial.catastro_predio_equidos    
                                                                WHERE cedula_propietario = '".$_SESSION['usuario']."')";
                
            }else if ($arrayParametros['tipoUsuario'] == 'Tecnico'){
                
            }
            
        }
        
        if (isset($arrayParametros['numero_movilizacion']) && ($arrayParametros['numero_movilizacion'] != '')) {
            $busqueda .= " and m.numero_movilizacion = '" . $arrayParametros['numero_movilizacion']."' ";
        }
        
        if (isset($arrayParametros['identificador_solicitante']) && ($arrayParametros['identificador_solicitante'] != '')) {
            $busqueda .= " and m.identificador_solicitante = '" . $arrayParametros['identificador_solicitante']."' ";
        }
        
        if (isset($arrayParametros['nombre_solicitante']) && ($arrayParametros['nombre_solicitante'] != '')) {
            $busqueda .= " and upper(m.nombre_solicitante) ilike upper('%" . $arrayParametros['nombre_solicitante'] . "%') ";
        }
        
        if (isset($arrayParametros['nombre_ubicacion_origen']) && ($arrayParametros['nombre_ubicacion_origen'] != '')) {
            $busqueda .= " and upper(m.nombre_ubicacion_origen) ilike upper('%" . $arrayParametros['nombre_ubicacion_origen'] . "%') ";
        }
        
        if (isset($arrayParametros['numero_movilizacion']) && ($arrayParametros['numero_movilizacion'] != '')) {
            $busqueda .= " and upper(m.numero_movilizacion) ilike upper('%" . $arrayParametros['numero_movilizacion'] . "%') ";
        }
        
        if (isset($arrayParametros['pasaporte_equino']) && ($arrayParametros['pasaporte_equino'] != '')) {
            $busqueda .= " and upper(m.pasaporte_equino) ilike upper('%" . $arrayParametros['pasaporte_equino'] . "%') ";
        }
        
        $consulta = "  SELECT
                        	*
                        FROM
                        	g_pasaporte_equino.movilizaciones m
                            INNER JOIN g_pasaporte_equino.equinos e ON e.id_equino = m.id_equino
                            INNER JOIN g_pasaporte_equino.miembros mi ON mi.id_miembro = m.id_miembro
                            INNER JOIN g_pasaporte_equino.organizacion_ecuestre oe ON oe.id_organizacion_ecuestre = m.id_asociacion
                            INNER JOIN g_programas_control_oficial.catastro_predio_equidos cpe ON m.id_ubicacion_actual = cpe.id_catastro_predio_equidos
                        WHERE
                            m.estado_movilizacion in ('Vigente', 'Finalizado')  
                            " . $busqueda . ";";
                            
        //echo $consulta;
        return $this->modeloMovilizaciones->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Funcionamiento para generar el secuencia de movilización del equino
     *
     * @param array $datos
     * @return int
     */
    public function buscarNumeroMovilizacion($pasaporte)
    {
        
        $consulta = "SELECT
                        max(secuencial_movilizacion) as numero
                     FROM
                        g_pasaporte_equino.movilizaciones
                     WHERE 
                        pasaporte_equino = '".$pasaporte."';";
        
        $codigo = $this->modeloMovilizaciones->ejecutarSqlNativo($consulta);
        $fila = $codigo->current();
        
        $codigoMovilizacion = array('numero' => $fila['numero']);
        
        if($codigoMovilizacion['numero'] != null){
            $incremento = $codigoMovilizacion['numero'] + 1;
        }else{
            $incremento = 1;
        }
        
        $codigoSecuencial = str_pad($incremento, 4, "0", STR_PAD_LEFT);
        
        return $codigoSecuencial;
    }
    
    /**
     * Función para calcular la fecha de finalización de la movilización
     *
     * @param array $datos
     * @return int
     */
    function generarFechaFinMovilizacion($fechaInicio){
        
        $fecha = date_create($fechaInicio);
        date_add($fecha, date_interval_create_from_date_string("12 hours"));
        
        return date_format($fecha,"Y-m-d H:i:s");
    }
    
    /**
     * Funcionamiento para traslado de equino y guardado de datos
     *
     * @param array $datos
     * @return int
     */
    public function guardarMovilizacionEquino(Array $datos)
    {
        $validacion = array(
            'bandera' => false,
            'estado' => "Fallo",
            'mensaje' => "Ocurrió un error al guardar la información de la movilización",
            'contenido' => null
        );
        
        //Validar existencia de equinos en el predio de origen$idPredio = $datos['id_ubicacion_actual'];
        $idPredio = $datos['id_ubicacion_actual'];
        $idEspecie = $datos['id_especie'];
        $idRaza = $datos['id_raza'];
        $idCategoria = $datos['id_categoria'];
        
        $ubicacionOrigen = $this->lNegocioCatastroPredioEquidosEspecie->obtenerNumeroEquinosCategoriasXEspecieXPredio($idPredio, $idEspecie, $idRaza, $idCategoria);
        
        //Confirma si hay equinos en el predio de origen antes de generar la movilización
        if (isset($ubicacionOrigen->current()->id_catastro_predio_equidos_especie) and $ubicacionOrigen->current()->id_catastro_predio_equidos_especie != '') {// != ''
            
            //Verificar el estado del equino antes de movilizar 
            $equino = $this->lNegocioEquinos->buscar($datos['id_equino']);
            
            if($equino->getEstadoEquino() == 'Activo'){
                //Usuario creación
                $datos['identificador'] = $_SESSION['usuario'];
                
                //Crear fecha finalización de movilización
                $datos['fecha_fin_movilizacion'] = $this->generarFechaFinMovilizacion($datos['fecha_inicio_movilizacion']);
                
                //Crear numeracion de la movilización
                $datos['secuencial_movilizacion'] = $this->buscarNumeroMovilizacion($datos['pasaporte_equino']);
                $datos['numero_movilizacion'] = $datos['pasaporte_equino'] .'-'. $datos['secuencial_movilizacion'];
                
                //Guarda la movilización
                $idMovilizacion = $this->guardar($datos);
                
                if($idMovilizacion > 0){
                    $datos['idMovilizacion'] = $idMovilizacion;
                    
                    $validacion['mensaje'] = 'Se ha guardado el registro de movilización. ';
                    
                    //Guardar el registro del cambio de equino en el catastro de predio de équidos y cambia el estado mientras dura la movilización
                    $actualizarCatastro = $this->lNegocioCatastroPredioEquidosEspecie->actualizarCatastro($datos);
                    
                    if($actualizarCatastro['bandera']){
                        $validacion['mensaje'] .= $actualizarCatastro['mensaje'];
                        $validacion['mensaje'] .= 'Se ha actualizado el catastro. ';
                        
                        //crear array parametros
                        $arrayParametros = array(
                            'id_equino' => $datos['id_equino'],
                            'id_catastro_predio_equidos' => $datos['id_ubicacion_destino'],
                            'ubicacion_actual' => $datos['id_ubicacion_destino'],
                            'estado_equino' => 'Movilizacion'
                        );
                        
                        //Actualizar ubicación actual del equino
                        $ubicacionActual = $this->lNegocioEquinos->guardar($arrayParametros);
                        
                        if($ubicacionActual > 0){
                            $validacion['mensaje'] .= 'Se ha actualizado la ubicación actual del equino. ';
                            
                            $jasper = new JasperReport();
                            $datosReporte = array();
                            
                            $ruta = PAS_EQUI_URL_CERT. $datos['pasaporte_equino'] . '/';
                            
                            if (! file_exists($ruta)){
                                mkdir($ruta, 0777, true);
                            }
                            
                            $datosReporte = array(
                                'rutaReporte' => 'PasaporteEquino/vistas/reportes/movilizacionPasaporteEquinoExtendido.jasper',
                                'rutaSalidaReporte' => 'PasaporteEquino/archivos/'. $datos['pasaporte_equino'] . '/' .$datos['numero_movilizacion'],
                                'tipoSalidaReporte' => array('pdf'),
                                'parametrosReporte' => array(   'idMovilizacion' => $idMovilizacion,
                                                                'selloFirma'=> RUTA_IMG_GENE."logoSeguridadCSM.gif",
                                                                'fondoCertificado'=> RUTA_IMG_GENE."fondoCertificado.png"),
                                'conexionBase' => 'SI'
                            );
                            //PAS_EQUI_URL_SELL_IMG,
                            $rutaCertificado = PAS_EQUI_URL . $datos['pasaporte_equino'] . '/' .$datos['numero_movilizacion'] . ".pdf";
                            
                            $jasper->generarArchivo($datosReporte);
                            
                            //crear array parametros
                            $arrayParametros = array(
                                'id_movilizacion' => $idMovilizacion,
                                'ruta_certificado' => $rutaCertificado
                            );
                            
                            //Generar el PDF del certificado
                            $certificado = $this->guardar($arrayParametros);
                            
                            if($certificado > 0){
                                $validacion['bandera'] = true;
                                $validacion['estado'] = 'exito';
                                $validacion['mensaje'] .= 'Se ha generado el certificado. ';
                                $validacion['contenido'] .= $rutaCertificado;
                                                        
                            }else{
                                $validacion['bandera'] = false;
                                $validacion['estado'] = 'Fallo';
                                $validacion['mensaje'] .= 'No se ha podido generar el certificado.';
                            }
                        }else{
                            $validacion['bandera'] = false;
                            $validacion['estado'] = 'Fallo';
                            $validacion['mensaje'] .= 'No se ha podido actualizar la ubicación actual del equino.';
                        }
                    }else{
                        $validacion['bandera'] = false;
                        $validacion['estado'] = 'Fallo';
                        $validacion['mensaje'] .= 'No se ha podido actualizar la información del catastro.';
                    }
                }else{
                    $validacion['bandera'] = false;
                    $validacion['estado'] = 'Fallo';
                    $validacion['mensaje'] .= 'No se ha podido guardar el registro de movilización.';
                }
            }else{
                $validacion['bandera'] = false;
                $validacion['estado'] = 'Fallo';
                $validacion['mensaje'] .= 'El equino no se encuentra activo para ser movilizado.';
            }
        }else{
            $validacion['bandera'] = false;
            $validacion['estado'] = 'Fallo';
            $validacion['mensaje'] .= 'No existen equinos en el predio de origen para poder emitir la orden de movilización. Contáctese con Agrocalidad.';
        }
        
        return $validacion;
    }
    
    /**
     * Método para obtener los datos del sitio de destino
     */
    public function buscarSitiosDestinoRegistroOperadorPredioEquidos($datos)
    {
        $idProvincia = $datos['idProvincia'];
        $ubicacionActual = $datos['ubicacionActual'];
        $idSitioDestino = $datos['idSitioDestino'];
        $idAreaDestino = $datos['idAreaDestino'];
                
        $validacion = array(
            'bandera' => false,
            'resultado' => "Fallo",
            'mensaje' => "El destino buscado no existe",
            'idCatastroPredioEquidos' => null,
            'numSolicitud' => null,
            'nombrePredio' => null,
            'cedulaPropietario' => null,
            'nombrePropietario' => null,
            'idProvincia' => null,
            'provincia' => null,
            'idCanton' => null,
            'canton' => null,
            'idParroquia' => null,
            'parroquia' => null,
            'direccionPredio' => null,
            'idSitio' => null,
            'idArea' => null
        );
        
        // Registro de Operador
        
        // Busca los datos de los sitios que coincidan con el id de sitio y area de registro de operador
        $query = "id_sitio = $idSitioDestino and id_area = $idAreaDestino";
        $sitioCatastro = $this->lNegocioCatastroPredioEquidos->buscarLista($query);
        
        if (empty($sitioCatastro->current())) {
            // El predio no tiene vinculado información del sitio, se debe buscar con los datos del sitio seleccionado
            // Busca los datos de los sitios que coincidan con el id de sitio y area de registro de operador
             //print_r('busca el sitio en predio');
            $sitio = $this->lNegocioSitios->buscar($idSitioDestino);
            
            $query = "  id_provincia=$idProvincia and id_catastro_predio_equidos not in ($ubicacionActual)
                        and upper(nombre_predio) ilike upper('%" . $sitio->nombreLugar . "%')
                        and cedula_propietario = '" . $sitio->identificadorOperador . "'
                        and canton = '" . $sitio->canton . "'
                        and parroquia = '" . $sitio->parroquia . "'";
            
            $sitioCatastro = $this->lNegocioCatastroPredioEquidos->buscarLista($query);
            
            if (isset($sitioCatastro->current()->id_catastro_predio_equidos)) {
                 //print_r('existe el sitio en predio');
                $validacion['resultado'] = "Exito";
                $validacion['mensaje'] = "El sitio solicitado existe en el catastro.";
                
                // Actualizar catastro predio équidos para incluir el id de sitio y el id de area
                $arrayParametros = array(
                    'id_catastro_predio_equidos' => $sitioCatastro->current()->id_catastro_predio_equidos,
                    'id_sitio' => $idSitioDestino,
                    'id_area' => $idAreaDestino
                );
                
                $idCatastro = $this->lNegocioCatastroPredioEquidos->guardar($arrayParametros);
                
                if ($idCatastro > 0) {
                     //print_r('actualiza el id en area');
                    $validacion['bandera'] = true;
                    $validacion['resultado'] = "Exito";
                    $validacion['mensaje'] .= " Se ha vinculado el sitio del operador al Predio.";
                    
                    $validacion['idCatastroPredioEquidos'] = $sitioCatastro->current()->id_catastro_predio_equidos;
                    $validacion['numSolicitud'] = $sitioCatastro->current()->num_solicitud;
                    $validacion['nombrePredio'] = $sitioCatastro->current()->nombre_predio;
                    $validacion['cedulaPropietario'] = $sitioCatastro->current()->cedula_propietario;
                    $validacion['nombrePropietario'] = $sitioCatastro->current()->nombre_propietario;
                    $validacion['idProvincia'] = $sitioCatastro->current()->id_provincia;
                    $validacion['provincia'] = $sitioCatastro->current()->provincia;
                    $validacion['idCanton'] = $sitioCatastro->current()->id_canton;
                    $validacion['canton'] = $sitioCatastro->current()->canton;
                    $validacion['idParroquia'] = $sitioCatastro->current()->id_parroquia;
                    $validacion['parroquia'] = $sitioCatastro->current()->parroquia;
                    $validacion['direccionPredio'] = $sitioCatastro->current()->direccion_predio;
                    $validacion['idSitio'] = $idSitioDestino;
                    $validacion['idArea'] = $idAreaDestino;
                    
                } else {
                    $validacion['bandera'] = false;
                    $validacion['resultado'] = "Fallo";
                    $validacion['mensaje'] .= " No se ha podido vincular el sitio del operador al Predio.";
                }
            } else {
               //print_r('crea el sitio en predio');
                // El sitio no existe, se debe crear con los datos del sitio seleccionado
                // Busca los datos de los sitios que coincidan con el id de sitio y area de registro de operador
                $sitio = $this->lNegocioSitios->buscar($idSitioDestino);
                
                $canton = $sitio->canton;
                $parroquia = $sitio->parroquia;
                $parroquia = $sitio->parroquia;
                
                $query = "nombre = '" . $parroquia . "' and categoria = 4 and
                            id_localizacion_padre = (Select l.id_localizacion from g_catalogos.localizacion l
                            where l.nombre = '" . $canton . "' and l.categoria = 2 and l.id_localizacion_padre = $idProvincia)";
                
                $datosParroquia = $this->lNegocioLocalizacion->buscarLista($query);
                
                $idParroquia = $datosParroquia->current()->id_localizacion;
                
                // Crear numeración para el predio
                $codigoParroquia = $this->lNegocioCatastroPredioEquidos->crearNumeroCertificado($datosParroquia->current()->codigo);
                
                // Datos Cantón
                $query = "nombre = '" . $canton . "' and categoria = 2 and id_localizacion_padre = $idProvincia";
                
                $datosCanton = $this->lNegocioLocalizacion->buscarLista($query);
                
                $idCanton = $datosCanton->current()->id_localizacion;
                
                // Datos Operador
                $datosOperador = $this->lNegocioOperadores->buscar($sitio->identificadorOperador);
                
                $razonSocial = $datosOperador->razonSocial;
                $telefono = $datosOperador->telefonoUno;
                $correo = $datosOperador->correo;
                
                // Crear registro de catastro predio équidos con datos del sitio del operador
                $arrayParametros = array(
                    'identificador' => $_SESSION['usuario'],
                    'num_solicitud' => $codigoParroquia,
                    'nombre_predio' => $sitio->nombreLugar,
                    'cedula_propietario' => $sitio->identificadorOperador,
                    'nombre_propietario' => $razonSocial,
                    'telefono_propietario' => $telefono,
                    'correo_electronico_propietario' => $correo,
                    'id_provincia' => $idProvincia,
                    'provincia' => $sitio->provincia,
                    'id_canton' => $idCanton,
                    'canton' => $sitio->canton,
                    'id_parroquia' => $idParroquia,
                    'parroquia' => $sitio->parroquia,
                    'direccion_predio' => $sitio->direccion,
                    'id_sitio' => $idSitioDestino,
                    'id_area' => $idAreaDestino,
                    'observaciones' => 'Predio creado automáticamente desde Pasaporte Equino'
                );
                
                $idCatastro = $this->lNegocioCatastroPredioEquidos->guardar($arrayParametros);
                
                if ($idCatastro > 0) {
                    //print_r('se crea el sitio y lee el area');
                    $sitioCatastro = $this->lNegocioCatastroPredioEquidos->buscar($idCatastro);
                    
                    $validacion['bandera'] = true;
                    $validacion['resultado'] = "Exito";
                    $validacion['mensaje'] .= " Se ha creado un predio con la información del operador.";
                    
                    $validacion['idCatastroPredioEquidos'] = $sitioCatastro->idCatastroPredioEquidos;
                    $validacion['numSolicitud'] = $sitioCatastro->numSolicitud;
                    $validacion['nombrePredio'] = $sitioCatastro->nombrePredio;
                    $validacion['cedulaPropietario'] = $sitioCatastro->cedulaPropietario;
                    $validacion['nombrePropietario'] = $sitioCatastro->nombrePropietario;
                    $validacion['idProvincia'] = $sitioCatastro->idProvincia;
                    $validacion['provincia'] = $sitioCatastro->provincia;
                    $validacion['idCanton'] = $sitioCatastro->idCanton;
                    $validacion['canton'] = $sitioCatastro->canton;
                    $validacion['idParroquia'] = $sitioCatastro->idParroquia;
                    $validacion['direccionPredio'] = $sitioCatastro->direccionPredio;
                    $validacion['parroquia'] = $sitioCatastro->parroquia;
                    $validacion['idSitio'] = $sitioCatastro->idSitio;
                    $validacion['idArea'] = $sitioCatastro->idArea;

                } else {
                    $validacion['bandera'] = false;
                    $validacion['resultado'] = "Fallo";
                    $validacion['mensaje'] .= " No se ha podido crear el predio con la información del sitio.";
                }
            }
        } else {
             //print_r('tiene id sitio y area');
            $validacion['bandera'] = true;
            $validacion['resultado'] = "Exito";
            $validacion['mensaje'] = "El sitio solicitado existe.";
            
            $validacion['idCatastroPredioEquidos'] = $sitioCatastro->current()->id_catastro_predio_equidos;
            $validacion['numSolicitud'] = $sitioCatastro->current()->num_solicitud;
            $validacion['nombrePredio'] = $sitioCatastro->current()->nombre_predio;
            $validacion['cedulaPropietario'] = $sitioCatastro->current()->cedula_propietario;
            $validacion['nombrePropietario'] = $sitioCatastro->current()->nombre_propietario;
            $validacion['idProvincia'] = $sitioCatastro->current()->id_provincia;
            $validacion['provincia'] = $sitioCatastro->current()->provincia;
            $validacion['idCanton'] = $sitioCatastro->current()->id_canton;
            $validacion['canton'] = $sitioCatastro->current()->canton;
            $validacion['idParroquia'] = $sitioCatastro->current()->id_parroquia;
            $validacion['parroquia'] = $sitioCatastro->current()->parroquia;
            $validacion['direccionPredio'] = $sitioCatastro->current()->direccion_predio;
            $validacion['idSitio'] = $sitioCatastro->current()->id_sitio;
            $validacion['idArea'] = $sitioCatastro->current()->id_area;
            
        } 
        
        return $validacion;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar movilizaciones para reporte usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarMovilizacionesReporteFiltradas($arrayParametros)
    {
        //$fechaDefault = date('Y-m-d H:i:s', time());
        $busqueda = '';
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '') ) {
            
            $busqueda .= " m.fecha_inicio_movilizacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00'";
        }/*else{
            $busqueda .= " m.fecha_inicio_movilizacion >= '".$fechaDefault. "' ";
        }*/
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '') ) {
            
            $busqueda .= "and m.fecha_fin_movilizacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00'";
        }/*else{
            $busqueda .= "and m.fecha_fin_movilizacion <= '".$fechaDefault. "' ";
        }*/
        
        if (isset($arrayParametros['id_provincia']) && ($arrayParametros['id_provincia'] != '') && ($arrayParametros['id_provincia'] != 'Todas')) {
            $busqueda .= " and m.id_provincia_origen = " . $arrayParametros['id_provincia'];
        }
        
        if (isset($arrayParametros['id_canton']) && ($arrayParametros['id_canton'] != '') && ($arrayParametros['id_canton'] != 'Todos')) {
            $busqueda .= " and m.id_canton_origen = " . $arrayParametros['id_canton'];
        }
        
        if (isset($arrayParametros['estado_movilizacion']) && ($arrayParametros['estado_movilizacion'] != '') && ($arrayParametros['estado_movilizacion'] != 'Todos')) {
            
            $busqueda .= " and m.estado_movilizacion in ('" . $arrayParametros['estado_movilizacion'] . "')";
        }
                
        $consulta = "  SELECT
                        	m.*
                        FROM
                        	g_pasaporte_equino.movilizaciones m
                        WHERE
            
                            " . $busqueda . " 
                        ORDER BY
                            m.pasaporte_equino, m.numero_movilizacion ASC;";
        
        //echo $consulta;
        return $this->modeloMovilizaciones->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta un reporte en Excel de los pasaportes
     *
     * @return array|ResultSet
     */
    public function exportarArchivoExcelMovilizaciones($datos){
        
        $hoja = new Spreadsheet();
        $documento = $hoja->getActiveSheet();
        $i = 3;
        $j = 2;
        
        $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Movilizaciones Equinas');
        
        $documento->setCellValueByColumnAndRow(1, $j, 'ID');
        $documento->setCellValueByColumnAndRow(2, $j, 'Número movilización');
        
        $documento->setCellValueByColumnAndRow(3, $j, 'Provincia origen');
        $documento->setCellValueByColumnAndRow(4, $j, 'Cantón origen');
        $documento->setCellValueByColumnAndRow(5, $j, 'Parroquia origen');
        $documento->setCellValueByColumnAndRow(6, $j, 'Sitio origen');
        $documento->setCellValueByColumnAndRow(7, $j, 'Identificador operador origen');
        $documento->setCellValueByColumnAndRow(8, $j, 'Nombre operador origen');
        
        $documento->setCellValueByColumnAndRow(9, $j, 'Provincia destino');
        $documento->setCellValueByColumnAndRow(10, $j, 'Cantón destino');
        $documento->setCellValueByColumnAndRow(11, $j, 'Parroquia destino');
        $documento->setCellValueByColumnAndRow(12, $j, 'Sitio destino');
        $documento->setCellValueByColumnAndRow(13, $j, 'Identificador operador destino');
        $documento->setCellValueByColumnAndRow(14, $j, 'Nombre operador destino');
        
        $documento->setCellValueByColumnAndRow(15, $j, 'Medio de transporte');
        $documento->setCellValueByColumnAndRow(16, $j, 'Placa del vehículo');        
        $documento->setCellValueByColumnAndRow(17, $j, 'Identificación transportista');
        $documento->setCellValueByColumnAndRow(18, $j, 'Nombre del transportista');
        
        $documento->setCellValueByColumnAndRow(19, $j, 'Número de Pasaporte');
        $documento->setCellValueByColumnAndRow(20, $j, 'Estado movilización');
        $documento->setCellValueByColumnAndRow(21, $j, 'Estado fiscalización');
        
        $documento->setCellValueByColumnAndRow(22, $j, 'Fecha inicio');
        $documento->setCellValueByColumnAndRow(23, $j, 'Fecha fin');
        
        
        if($datos != ''){
            foreach ($datos as $fila){
                $documento->setCellValueByColumnAndRow(1, $i, $fila['id_movilizacion']);
                $documento->setCellValueByColumnAndRow(2, $i, $fila['numero_movilizacion']);
                
                $documento->setCellValueByColumnAndRow(3, $i, $fila['provincia_origen']);
                $documento->setCellValueByColumnAndRow(4, $i, $fila['canton_origen']);
                $documento->setCellValueByColumnAndRow(5, $i, $fila['parroquia_origen']);
                $documento->setCellValueByColumnAndRow(6, $i, $fila['nombre_ubicacion_origen']);             
                $documento->setCellValueByColumnAndRow(7, $i, $fila['identificador_propietario_origen']);
                $documento->setCellValueByColumnAndRow(8, $i, $fila['nombre_propietario_origen']);
                
                $documento->setCellValueByColumnAndRow(9, $i, $fila['provincia_destino']);
                $documento->setCellValueByColumnAndRow(10, $i, $fila['canton_destino']);
                $documento->setCellValueByColumnAndRow(11, $i, $fila['parroquia_destino']);
                $documento->setCellValueByColumnAndRow(12, $i, $fila['nombre_ubicacion_destino']);
                $documento->setCellValueByColumnAndRow(13, $i, $fila['identificador_propietario_destino']);
                $documento->setCellValueByColumnAndRow(14, $i, $fila['nombre_propietario_destino']);
                
                $documento->setCellValueByColumnAndRow(15, $i, $fila['medio_transporte']);
                $documento->setCellValueByColumnAndRow(16, $i, $fila['placa_transporte']);
                $documento->setCellValueByColumnAndRow(17, $i, $fila['identificador_conductor']);
                $documento->setCellValueByColumnAndRow(18, $i, $fila['nombre_conductor']);
                
                $documento->setCellValueByColumnAndRow(19, $i, $fila['pasaporte_equino']);
                $documento->setCellValueByColumnAndRow(20, $i, $fila['estado_movilizacion']);
                $documento->setCellValueByColumnAndRow(21, $i, $fila['estado_fiscalizacion']);
                
                $documento->setCellValueByColumnAndRow(22, $i, $fila['fecha_inicio_movilizacion']);
                $documento->setCellValueByColumnAndRow(23, $i, $fila['fecha_fin_movilizacion']);
                
                $i++;
            }
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="excelMovilizaciones.xlsx"');
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $writer = IOFactory::createWriter($hoja, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
}
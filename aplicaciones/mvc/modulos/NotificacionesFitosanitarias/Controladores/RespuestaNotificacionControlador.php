<?php

/**
 * Controlador RespuestaNotificacion
 *
 * Este archivo controla la lógica del negocio del modelo:  RespuestaNotificacionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-09-09
 * @uses    RespuestaNotificacionControlador
 * @package NotificacionesFitosanitarias
 * @subpackage Controladores
 */

namespace Agrodb\NotificacionesFitosanitarias\Controladores;

use Agrodb\NotificacionesFitosanitarias\Modelos\RespuestaNotificacionLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\RespuestaNotificacionModelo;
use Agrodb\NotificacionesFitosanitarias\Modelos\ListaNotificacionLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\ListaNotificacionModelo;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionesLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionesModelo;
use Agrodb\NotificacionesFitosanitarias\Modelos\AreaTematicaNotificacionLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\AreaTematicaNotificacionModelo;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class RespuestaNotificacionControlador extends BaseControlador {

    private $lNegocioRespuestaNotificacion = null;
    private $modeloRespuestaNotificacion = null;
    private $lNegocioListaNotificacion = null;
    private $modeloListaNotificacion = null;
    private $lNegocioNotificaciones = null;
    private $modeloNotificaciones = null;
    private $accion = null;
    private $article = null;
    private $botones = null;
    private $panelBusqueda = null;
    private $panelBusquedaRespuesta = null;
    private $panelBusquedaNotificaciones = null;
    private $panelBusquedaRevision = null;
    private $panelBusquedaOperadores = null;
    private $panelBusquedaTecnicos = null;
    private $panelRespuestaTecnicos = null;
    private $panelRegresoNotificacion = null;
    private $lNegocioOperadores = null;
    private $modeloOperadores = null;
    private $lNegocioAreaTematica = null;
    private $modeloAreaTematica = null;
    private $datosOperador = null;
    private $areaTematica = null;
    private $datosTecnico = null;

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->lNegocioRespuestaNotificacion = new RespuestaNotificacionLogicaNegocio();
        $this->modeloRespuestaNotificacion = new RespuestaNotificacionModelo();
        $this->lNegocioListaNotificacion = new ListaNotificacionLogicaNegocio();
        $this->modeloListaNotificacion = new ListaNotificacionModelo();
        $this->lNegocioNotificaciones = new NotificacionesLogicaNegocio();
        $this->modeloNotificaciones = new NotificacionesModelo();
        $this->lNegocioOperadores = new OperadoresLogicaNegocio();
        $this->modeloOperadores = new OperadoresModelo();
        $this->lNegocioAreaTematica = new AreaTematicaNotificacionLogicaNegocio();
        $this->modeloAreaTematica = new AreaTematicaNotificacionModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

/**
     * Método de inicio del controlador
     */

    public function index() {
        $this->articleNotificacionesXAnio();
        require APP . 'NotificacionesFitosanitarias/vistas/listaFormularioRespuestaNVista.php';
    }
    /**
     * Método de inicio del controlador
     */
    
    public function respondidas() {
    	$this->articleNotificacionesXAnio('comentadas');
    	require APP . 'NotificacionesFitosanitarias/vistas/listaFormularioRespuestaNVista.php';
    }
    
/**
     * Método para desplegar el formulario vacio
     */

    public function nuevo() {
        $this->accion = "Nuevo RespuestaNotificacion";
        require APP . 'NotificacionesFitosanitarias/vistas/formularioRespuestaNotificacionVista.php';
    }

/**
     * Método para registrar en la base de datos -RespuestaNotificacion
     */

    public function guardar() {
        $idNotificacion = $_POST["idNotificacion"];
        $respuesta = $_POST["respuesta"];
        $archivo = $_POST["archivo"];
        $identificador = $_SESSION["usuario"];
        $contenido = '';
        $this->perfilUsuario();
        if ($this->perfilUsuario == 'PFL_OPE_PRE_NOTI') {   //Perfil de operador
        	$parrafo = $_POST["parrafoNotifi"];
        	$respaldoBibliografico = $_POST["respaldo_bibliografico"];
        	$observaciones = $_POST['observaciones'];
        	$informacionComplementaria = $_POST["informacion_complementaria"];
        	
            $arrayParametros = array('id_notificacion' => $idNotificacion,
                'identificador' => $identificador,
                'tipo' => 'operador',
                'respuesta' => $respuesta,
                'archivo' => $archivo,
                'estado_respuesta' => 'false',
            	'parrafo' => $parrafo,
            	'respaldo_bibliografico' => $respaldoBibliografico,
            	'observaciones' => $observaciones,
            	'informacion_complementaria' => $informacionComplementaria
            );   //Se guarda con estado f porque no tiene respuesta de tecnico
        } else if ($this->perfilUsuario == 'PFL_TEC_RES_NOTI') {
            $arrayParametros = array('id_notificacion' => $idNotificacion,
                'identificador' => $identificador,
                'tipo' => 'tecnico',
                'respuesta' => $respuesta,
                'archivo' => $archivo,
                'estado_respuesta' => 'true');
        }

        $id=$this->lNegocioRespuestaNotificacion->guardar($arrayParametros);
        if ($this->perfilUsuario == 'PFL_TEC_RES_NOTI') {
           $this->modeloRespuestaNotificacion = $this->lNegocioRespuestaNotificacion->buscar($id);
           $contenido = $this->comentarioTecnico($_SESSION['usuario']);
        }
        $datos = array('estado' => 'exito', 'mensaje' => Constantes::GUARDADO_CON_EXITO,'contenido' => $contenido);
        echo \Zend\Json\Json::encode($datos);
       // Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    public function guardarOperador() {
        $this->perfilUsuario();
        
        $idResNotificacion = $_POST["idRespuestaNotificacion1"];
        $idNotificacion = $_POST["idNotificacion1"];
        $identificador = $_SESSION["usuario"];
        $fecha = 'now()';
        $respuesta = $_POST["respuesta"];
        $archivo = $_POST["archivo"];
        
        //Actualiza estado registro padre
        $arrayParametros = array('id_respuesta_notificacion' => $idResNotificacion,
            'estado_respuesta' => 'true');
        
        $this->lNegocioRespuestaNotificacion->guardar($arrayParametros);            
        
        if ($this->perfilUsuario == 'PFL_OPE_PRE_NOTI') {  
            //Guarda nuevo respuesta de operador               
            $arrayParametrosNT = array('id_notificacion' => $idNotificacion,
                                        'identificador' => $identificador,
                                        'id_padre' => $idResNotificacion,
                                        'tipo' => 'operador',
                                        'respuesta' => $respuesta,
                                        'archivo' => $archivo,
                                        'estado_respuesta' => 'false',
                                        'finalizar_respuesta' => 'false'
            );
            
        }else if($this->perfilUsuario == 'PFL_TEC_RES_NOTI'){
                if (isset($_POST["finalizarRespuestaOperador"])) {
                    $finalizarRespuesta = 'true';
                } else {
                    $finalizarRespuesta = 'false';
                }
                      
                $arrayParametrosNT = array('id_notificacion' => $idNotificacion,
                                            'identificador' => $identificador,
                                            'id_padre' => $idResNotificacion,
                                            'tipo' => 'tecnico',
                                            'fecha_respuesta' => $fecha,
                                            'respuesta' => $respuesta,
                                            'archivo' => $archivo,
                                            'estado_respuesta' => 'false',
                                            'finalizar_respuesta' => $finalizarRespuesta);
        }
        
        $this->lNegocioRespuestaNotificacion->guardar($arrayParametrosNT);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Guarda las respuesta del técnico 
     */
    public function guardarTecnico() {
        $idResNotificacion = $_POST["idRespuestaNotificacion1"];
        $idNotificacion = $_POST["idNotificacion1"];
        $idPadre = $_POST["idPadre1"];
        $identificador = $_POST["identificacion1"];
        $fecha = 'now()';
        $respuesta = $_POST["respuesta"];
        $archivo = $_POST["archivo1"];
        if (isset($_POST["finalizarRespuestaOperador"])) {
            $fechaRespuesta = 'true';
        } else {
            $fechaRespuesta = 'false';
        }

        $arrayParametros = array('id_respuesta_notificacion' => $idResNotificacion,
            'estado_respuesta' => 'true');

        $this->lNegocioRespuestaNotificacion->guardar($arrayParametros);
        //Guarda nueva respuesta de técnico             
        $arrayParametrosNT = array('id_notificacion' => $idNotificacion,
            'identificador' => $identificador,
            'id_padre' => $idResNotificacion,
            'tipo' => 'tecnico',
            'fecha_respuesta' => $fecha,
            'respuesta' => $respuesta,
            'archivo' => $archivo,
            'estado_respuesta' => 'false',
            'finalizar_respuesta' => $fechaRespuesta);
        $this->lNegocioRespuestaNotificacion->guardar($arrayParametrosNT);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: RespuestaNotificacion
     */
    public function editar() {
        //$this->accion = "Revisión Notificación"; 
        //$this->formulario = 'abrir';
        $idProceso = $_POST["id"];
        $this->perfilUsuario();
        if ($this->perfilUsuario == 'PFL_OPE_PRE_NOTI') {
            $this->filtroBusqueda(1, $idProceso);
            $this->opcion = 0;
        }
        $this->modeloRespuestaNotificacion = $this->lNegocioRespuestaNotificacion->buscar($_POST["id"]);
        require APP . 'NotificacionesFitosanitarias/vistas/formularioRevisionNotificacionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - RespuestaNotificacion
     */
    public function borrar() {
        $this->lNegocioRespuestaNotificacion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - RespuestaNotificacion
     */
    public function tablaHtmlRespuestaNotificacion($tabla) { {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_respuesta_notificacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'NotificacionesFitosanitarias\respuestaNotificacion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_respuesta_notificacion'] . '</b></td>
                <td>'
                    . $fila['id_notificacion'] . '</td>
                <td>' . $fila['id_padre']
                    . '</td>
                <td>' . $fila['identificador'] . '</td>
                </tr>');
            }
        }
    }

    /**
     * Método para crear los articulos de notificaciones por mes
     */
    public function articleNotificaciones() {
        $this->perfilUsuario();
        $consulta = $this->lNegocioListaNotificacion->buscarLista();
        $contador = 0;
        foreach ($consulta as $fila) {
            $arrayParametros = array(
                'idLista' => $fila['id_lista_notificacion'],
                'rutaAplicacion' => URL_MVC_FOLDER . 'NotificacionesFitosanitarias',
                'opcion' => 'RespuestaNotificacion/listar',
                'destino' => 'listadoItems',
                'contador' => ++$contador,
                'texto1' => $fila['nombre_lista'],
                'texto2' => $fila['anio']);
            $this->article .= $this->articleComun($arrayParametros, 1);
        }
    }
    
    public function articleNotificacionesXAnio($comentadas=null) {
    	
        
        if($comentadas != ''){
        	$opcion = 'RespuestaNotificacion/listarComentadas';
        	$consultaCabecera = $this->lNegocioListaNotificacion->buscarAnioNotificaciones('');
        }else{
        	$opcion = 'RespuestaNotificacion/listar';
        	$consultaCabecera = $this->lNegocioListaNotificacion->buscarAnioNotificaciones();
        }
        $contador = 0;
        foreach ($consultaCabecera as $fila1) {
            $this->article .="<div><h2>".$fila1['anio']."</h2>";
            
            $consulta = $this->lNegocioListaNotificacion->buscarLista("anio = ".$fila1['anio']." ");
            
          
            foreach ($consulta as $fila) {
                
                $arrayParametros = array(
                    'idLista' => $fila['id_lista_notificacion'],
                    'rutaAplicacion' => URL_MVC_FOLDER . 'NotificacionesFitosanitarias',
                	'opcion' => $opcion,
                    'destino' => 'listadoItems',
                    'contador' => ++$contador,
                    'texto1' => $fila['nombre_lista'],
                    'texto2' => $fila['anio']
                );
                $this->article .= $this->articleComun($arrayParametros, 1);
            }
            
            $this->article .="</div>";
        }
    }

    /**
      /* Método para responder notificacion operador
       * Formulario que se abre para mostrar la información de la notificación con preguntas y respuestas
     */
    public function respuestaNotificaciones() {
        $idNotificacion = $_POST['id'];
        $this->perfilUsuario();
        $this->detalleFormulario = 'Revisión Notificación';
        $areaTematicaConsulta = $this->lNegocioAreaTematica->devolverAreaTematicaNotificacion($_POST['id']);
        $this->areaTematica = $areaTematicaConsulta->current()->area_tematica;
        if ($this->perfilUsuario == 'PFL_OPE_PRE_NOTI') {
        	$this->modeloOperadores = $this->lNegocioOperadores->buscar($_SESSION['usuario']);  
            $this->modeloNotificaciones = $this->lNegocioNotificaciones->buscar($_POST["id"]);
            require APP . 'NotificacionesFitosanitarias/vistas/formularioRevisionNotificacionOperadorVista.php';
        } else if ($this->perfilUsuario == 'PFL_TEC_RES_NOTI') {            
            $this->modeloNotificaciones = $this->lNegocioNotificaciones->buscar($_POST["id"]);            
            require APP . 'NotificacionesFitosanitarias/vistas/formularioRevisionNotificacionVista.php';
        }
    }

    /**
     * Método para desplegar la lista de notificaciones
     */
    public function listar() {
        $this->detalleFormulario = 'Lista de Notificaciones';
        
        $idListaNotificacion = $_POST["id"];
        $this->perfilUsuario();
        $this->filtroBusqueda(1, $idListaNotificacion);
       
        $arrayParametros = array('id_lista_notificacion' => $idListaNotificacion);
        $this->botones = $this->crearAccionBotonesLN($arrayParametros);
        $consultaNotificacionXMes = array();
        
        if ($this->perfilUsuario == 'PFL_OPE_PRE_NOTI') {
        	$arrayParametros = array('id_lista_notificacion' => $idListaNotificacion);
        	$consultaNotificacionXMes = $this->lNegocioNotificaciones->buscarNotificacionesXFiltroOperador($arrayParametros);   
           // $consultaNotificacionXMes = $this->lNegocioNotificaciones->buscarLista($busqueda);           
        }else if ($this->perfilUsuario == 'PFL_TEC_RES_NOTI') {  
            $consultaNotificacionXMes = $this->lNegocioNotificaciones->buscarNotificacionesXMes($arrayParametros);       
        }
        
        $this->tablaHtmlFormularioReporte($consultaNotificacionXMes);
        
        require APP . 'NotificacionesFitosanitarias/vistas/listaFormularioFiltroRespuestaVista.php';
    }
    
    /**
     * Método para desplegar la lista de notificaciones
     */
    public function listarComentadas() {
    	$this->detalleFormulario = 'Lista de Notificaciones';
    	
    	$idListaNotificacion = $_POST["id"];
    	$this->perfilUsuario();
    	$this->filtroBusqueda(1, $idListaNotificacion);
    	
    	$arrayParametros = array('id_lista_notificacion' => $idListaNotificacion,'comentadas' => 'si');
    	$this->botones = $this->crearAccionBotonesLN($arrayParametros);
    	$consultaNotificacionXMes = array();
    	
    	if ($this->perfilUsuario == 'PFL_OPE_PRE_NOTI') {
    		$arrayParametros = array('id_lista_notificacion' => $idListaNotificacion,'comentadas' => 'si');
    		$consultaNotificacionXMes = $this->lNegocioNotificaciones->buscarNotificacionesXFiltroOperador($arrayParametros);
    		// $consultaNotificacionXMes = $this->lNegocioNotificaciones->buscarLista($busqueda);
    	}else if ($this->perfilUsuario == 'PFL_TEC_RES_NOTI') {
    		$consultaNotificacionXMes = $this->lNegocioNotificaciones->buscarNotificacionesXMes($arrayParametros);
    	}
    	
    	$this->tablaHtmlFormularioReporte($consultaNotificacionXMes);
    	
    	require APP . 'NotificacionesFitosanitarias/vistas/listaFormularioFiltroRespuestaVista.php';
    }

    /**
     * Tabla que lista las notificaciones filtradas
     * @param type $tabla
     */
    public function tablaHtmlFormularioReporte($arrayParametros) {
        $contador = 0;
        foreach ($arrayParametros as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_notificacion'] . '"
                        class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'NotificacionesFitosanitarias\respuestaNotificacion"
                    	data-opcion="respuestaNotificaciones" ondragstart="drag(event)" draggable="true"
                    	data-destino="detalleItem">
                        <td>' . ++$contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['codigo_documento'] . '</b></td>
                            <td>' . $fila['nombre_pais_notifica'] . '</td>
                            <td>' . $fila['producto'] . '</td>
                            <td>' . $fila['area_tematica'] . '</td>
                            <td>' . date('Y-m-d' , strtotime($fila['fecha_notificacion'])) . '</td>
                            <td>' . date('Y-m-d' , strtotime($fila['fecha_cierre'])) . '</td>
                            </tr>');
        }
    }
    
    /**
     * Tabla que lista las notificaciones filtradas
     * @param type $tabla
     */
    public function tablaHtmlFormularioReporteTecnico($arrayParametros) {
        $contador = 0;
        foreach ($arrayParametros as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_notificacion'] . '"
                        class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'NotificacionesFitosanitarias\respuestaNotificacion"
                    	data-opcion="respuestaNotificaciones" ondragstart="drag(event)" draggable="true"
                    	data-destino="detalleItem">
                        <td>' . ++$contador . '</td>
                        <td style="white - space:nowrap; "><b>' . $fila['codigo_documento'] . '</b></td>
                            <td>' . $fila['nombre_pais_notifica'] . '</td>
                            <td>' . $fila['producto'] . '</td>
                            <td>' . date('Y-m-d' , strtotime($fila['fecha_notificacion'])) . '</td>
                            <td>' . date('Y-m-d' , strtotime($fila['fecha_cierre'])) . '</td>
                            <td>' . ($fila['estado_respuesta']=='true'?'Respondido':'No Respondido') . '</td>
                            </tr>');
        }
    }

    /**
     * Forma la tabla del formulario Revisiones realizadas
     * @param type $tabla
     */
    public function tablaHtmlRevisionesRealizadasOperador($idNotificacion) {
        
        $this->datosTabla ='';
        
        $query = "id_notificacion = " .$idNotificacion. " and tipo = 'operador' and id_padre is NULL order by id_respuesta_notificacion, id_notificacion ASC;";
        $consulta = $this->lNegocioRespuestaNotificacion->buscarLista($query);
        $this->datosTabla .= "<table style='width: 100%;'>
                                        <thead>
                                            <tr>
                                                <th>Nº</th>
                                                <th>Usuario</th>
                                                <th>Fecha revisión</th>
                                                <th>Respuesta AGR</th>
                                                <th>Visualizar</th>
                                            </tr>
                                        </thead>";

        $i=1;
        
        foreach ($consulta as $fila) {
                $estadoRespuesta = $fila->estado_respuesta == "false" ? "NO" : "SI";
                $identificador = $this->consultarTipoUsuario($fila->identificador); 
                $this->datosTabla .= '<tr> 
                                                        <td>' . $i++ . '</td>
                                                        <td style="white - space:nowrap; "><b>' . $identificador['razon_social'] . '</b></td>
                                                        <td>' . $fila->fecha_revision . '</td>
                                                        <td>' . $estadoRespuesta . '</td>
                                                        <td><button type ="button" class="bPrevisualizar icono" onclick="fn_verRespuestaNotificacion(' . $fila->id_respuesta_notificacion . ',' . $fila->id_notificacion . ',' . $fila->identificador . ', ' . $fila->estado_respuesta . ')" ></button></td>
                                                     </tr>';
                
            }
            
            $this->datosTabla .= '</table>';
            
            echo $this->datosTabla;
       
    }
    
    public function consultarTipoUsuario($identificador)
    {  
        $consulta = "identificador='".$identificador."'";
        $usuario = $this->lNegocioOperadores->buscarLista($consulta);
        if(count($usuario) > 0){
           $fila = $usuario->current();
           $usuario = array('identificador' => $fila->identificador,
                             'razon_social' => $fila->razon_social
            );
        return $usuario;
        }
    }
    
    public function tablaHtmlRevisionesRealizadasTecnico($idNotificacion) {
        
        $this->datosTabla ='';
        $query = "  id_notificacion = " .$idNotificacion. " and 
                    tipo in ('operador') and
                    finalizar_respuesta= 'false' 
                    order by id_respuesta_notificacion, id_notificacion ASC;";
        
            $consulta = $this->lNegocioRespuestaNotificacion->buscarLista($query);
            $this->datosTabla .= "<table style='width: 100%;'>
                                            <thead>
                                                <tr>
                                                    <th>Nº</th>
                                                    <th>Fecha</th>
                                                    <th>Operador</th>
                                                    <th>Respuesta</th>
                                                    <th>Visualizar</th>
                                                </tr>
                                            </thead>";

            $i=1;

            foreach ($consulta as $fila) {
                $identificador = $this->consultarTipoUsuario($fila->identificador);

                $this->datosTabla .= '<tr>
                                                            <td>' . $i++ . '</td>
                                                            <td>' . date('Y-m-d', strtotime($fila->fecha_revision)) . '</td>
                                                            <td style="white - space:nowrap; "><b>' . $identificador['razon_social'] . '</b></td>
                                                            <td>' . ($fila->estado_respuesta=='true'?'Respondido':'No Respondido') . '</td>
                                                            <td><button type ="button" class="bPrevisualizar icono" onclick="fn_verRespuestaNotificacion(' . $fila->id_respuesta_notificacion . ',' . $fila->id_notificacion . ',' . $fila->identificador . ', ' . $fila->estado_respuesta . ')" ></button></td>
                                                         </tr>';
            }
        
        $this->datosTabla .= '</table>';
        
        echo $this->datosTabla;
        
    }
    
    
    public function tablaHtmlRevisionesRealizadasTecnicoComentarios($idNotificacion) {
    	
    	$this->datosTabla ='';
    	$query = "  id_notificacion = " .$idNotificacion. " and
                    tipo in ('tecnico') and
                    finalizar_respuesta= 'false'
                    order by id_respuesta_notificacion, id_notificacion ASC;";
    	
    	$consulta = $this->lNegocioRespuestaNotificacion->buscarLista($query);
    	$this->datosTabla .= "<table style='width: 100%;'>
                                            <thead>
                                                <tr>
                                                    <th>Nº</th>
                                                    <th>Fecha</th>
												    <th></th>
                                                    <th>Respuesta técnico</th>
                                                    <th>Visualizar</th>
                                                </tr>
                                            </thead>";
    	
    	$i=1;
    	
    	foreach ($consulta as $fila) {
    		$identificador = $this->consultarTipoUsuario($fila->identificador);
    		
    		$this->datosTabla .= '<tr>
                                                            <td>' . $i++ . '</td>
                                                            <td>' . date('Y-m-d', strtotime($fila->fecha_revision)) . '</td>
                                                            <td> </td>
                                                            <td>' . ($fila->estado_respuesta=='true'?'Respondido':'No Respondido') . '</td>
                                                            <td><button type ="button" class="bPrevisualizar icono" onclick="fn_verRespuestaNotificacionTecnico(' . $fila->id_respuesta_notificacion . ',' . $fila->id_notificacion . ',' . $fila->identificador . ', ' . $fila->estado_respuesta . ')" ></button></td>
                                                         </tr>';
    	}
    	
    	$this->datosTabla .= '</table>';
    	
    	echo $this->datosTabla;
    	
    }
    

    public function tablaHtmlRevisionesTecnicoRealizadas($tabla) {
        if (count($tabla) > 0) {
            $contador = 0;
            $this->itemsFiltrados = "";
            $this->perfilUsuario();
            foreach ($tabla as $fila) {
                $identificador = $this->usuarioPerfiles($fila->identificador, $_SESSION['idAplicacion']);
                foreach ($identificador as $valor) {
                    $usuario = $valor->usuario;
                }
                $this->itemsFiltrados .= '<tr> 
                                    <td>' . ++$contador . '</td>
                                    <td>' . $fila->fecha_revision . '</td>
                                    <td style="white - space:nowrap; "><b>' . $usuario . '</b></td>    
                                    <td><button type ="button" class="bPrevisualizar" onclick="fn_verRespuestaNotificacion(' . $fila->id_respuesta_notificacion . ',' . $fila->id_notificacion . ',' . $fila->identificador . ', ' . $fila->estado_respuesta . ')" ></button></td>
                                </tr>';
            }
        } else
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
    }
    
    public function imprimirPreguntasOperador() {
        $idRespuestaNotificacion = $_POST['idRespuestaNotificacion'];
        $idNotificacion = $_POST['idNotificacion'];
        $identificador = $_POST['identificador'];
        
        $arrayParametros = array(
            'idRespuestaNotificacion' => $idRespuestaNotificacion,
            'idNotificacion' => $idNotificacion,
            'identificador' => $identificador);
        
        $consultaOperador = $this->lNegocioRespuestaNotificacion->buscarRegistrosXOperador($arrayParametros);
        $this->datosPregunta ='';
        $i=1;
        
        $this->perfilUsuario();
        
        foreach ($consultaOperador as $fila) {
            if($fila['id_respuesta_notificacion'] == $idRespuestaNotificacion && $this->perfilUsuario == 'PFL_TEC_RES_NOTI'){
                continue;
            }else{
                if($fila['id_padre'] != null ){
                    $query="id_respuesta_notificacion = ".$fila['id_padre'];
                    $cedulaCreacion = $this->lNegocioRespuestaNotificacion->buscarLista($query);//////////////
                    $cedula = $cedulaCreacion->current();
                    
                    $this->datosPregunta .= '<fieldset>
                                               <legend>'. ($fila['tipo'] === 'operador'? 'Revisión realizada por Operador': 'Respuesta ingresada por Agrocalidad' ).'</legend>
                        
                                                <input type="hidden" id="idRespuestaNotificacion'.$fila['id_respuesta_notificacion'].'" name="idRespuestaNotificacion'.$fila['id_respuesta_notificacion'].'" value="' . $fila['id_respuesta_notificacion'] . '" readonly="readonly">
                                                <input type="hidden" id="idNotificacion'.$fila['id_respuesta_notificacion'].'" name="idNotificacion'.$fila['id_respuesta_notificacion'].'" value="' . $fila['id_notificacion'] . '" readonly="readonly">
                                                <input type="hidden" id="idPadre'.$fila['id_respuesta_notificacion'].'" name="idPadre'.$fila['id_respuesta_notificacion'].'" value="' . $fila['id_padre'] . '" readonly="readonly">';
                    if($fila['tipo'] == 'operador'){
                        $query="identificador = '".$fila['identificador']."'";
                        $datos = $this->lNegocioOperadores->buscarLista($query);
                        
                        $operador = $datos->current();
                        
                        $datosOperador = array( 'razon_social' => $operador->razon_social,
                            'celular' => $operador->celular_uno,
                            'correo_electronico' => $operador->correo
                        );
                        $this->datosPregunta .= '<div data-linea="'.$i++.'">
                                            			<label for="respuesta">Nombre Operador: </label>' . $datosOperador['razon_social'] . '
                                            		</div>';
                        
                        $this->datosPregunta .= '<div data-linea="'.$i++.'">
                                            			<label for="respuesta">Fecha creación: </label>' . date('Y-m-d',strtotime($fila['fecha_revision'])) . '
                                            		</div>';
                    }else{
                        $this->datosPregunta .= '<div data-linea="'.$i++.'">
                                            			<label for="respuesta">Fecha respuesta: </label>' . date('Y-m-d',strtotime($fila['fecha_respuesta'])) . '
                                            		</div>';
                    }
                                        		
                   $this->datosPregunta .= '<div data-linea="'.$i++.'">
                                        			<label for="respuesta">Respuesta ingresada: </label>' . $fila['respuesta'] . '
                                        		</div>
                                        		<div data-linea="'.$i++.'">
                                        			<label for="respuesta">Archivo adjunto: </label>';
                    if ($fila['archivo'] != '0') {
                        $this->datosPregunta .= '<a href="' . $fila['archivo'] . '" target="_blank" >Descargar</a>';
                    } else {
                        $this->datosPregunta .= 'No exite archivo subido';
                    }
                    $this->datosPregunta .= '</div>';
                    
                    if ($this->perfilUsuario == 'PFL_OPE_PRE_NOTI') {   //Si es operador                    
                        //Validación para operador que responde a su registro
                        if (($fila['estado_respuesta'] === 'false' && $fila['finalizar_respuesta'] === 'false') && ($fila['tipo'] === 'tecnico') && ($cedula['identificador'] === $_SESSION['usuario'])) {
                            $this->datosPregunta .= '<div data-linea="'.$i++.'">
                                                    <button id="btnFiltrarNotificacion" onclick="formularioRespuesta('.$fila['id_respuesta_notificacion'].')">Responder</button>
                                                </div>';
                        }
                    } else if ($this->perfilUsuario == 'PFL_TEC_RES_NOTI') {  ////Si es tecnico
                        //Validación para técnico
                        if (($fila['estado_respuesta'] === 'false' && $fila['finalizar_respuesta'] === 'false') && ($fila['tipo'] === 'operador') && ($fila['id_padre'] !== null)) {
                            $this->datosPregunta .= '<div data-linea="'.$i++.'">
                                                    <button id="btnFiltrarNotificacion" onclick="formularioRespuesta('.$fila['id_respuesta_notificacion'].')">Responder</button>
                                                </div>';
                        }
                    }
                    
                    $this->datosPregunta .= '</fieldset>';
                }
            }
        }
        
        echo $this->datosPregunta;
    }

    public function verRevisionesNotificaciones() {
        $idRespuestaNotificacion = $_POST['idRespuestaNotificacion'];
        $idNotificacion = $_POST['idNotificacion'];
        $identificador = $_POST['identificador'];
        $estadoRespuesta = $_POST['estadoRespuesta'];
        $usrLogeado = $_SESSION['usuario'];

        $identificador = $this->usuarioPerfiles($usrLogeado, $_SESSION['idAplicacion']);//session
        
        foreach ($identificador as $valor) {
            $usuario = $valor->usuario;
            $idUsuario = $valor->identificador;
        }
        
        $this->perfilUsuario();
        
        $this->modeloRespuestaNotificacion = $this->lNegocioRespuestaNotificacion->buscar($idRespuestaNotificacion);
        
        $query="identificador = '".$this->modeloRespuestaNotificacion->getIdentificador()."'";
        $datos = $this->lNegocioOperadores->buscarLista($query);
        
        $fila = $datos->current();
        
        $datosOperador = array( 'razon_social' => $fila->razon_social,
                                'celular' => $fila->celular_uno,
                                'correo_electronico' => $fila->correo
        );
        
        require APP . 'NotificacionesFitosanitarias/vistas/formularioDetalleOperadorNotificacionesVista.php';
    }

    public function verRevisionesNotificacionesTecnico() {
    	$idRespuestaNotificacion = $_POST['idRespuestaNotificacion'];
    	$this->modeloRespuestaNotificacion = $this->lNegocioRespuestaNotificacion->buscar($idRespuestaNotificacion);
    	$this->datosTecnico = $this->comentarioTecnico($this->modeloRespuestaNotificacion->getIdentificador());
    	require APP . 'NotificacionesFitosanitarias/vistas/formularioDetalleTecnicoNotificacionesComentariosVista.php';
    }
    //Método para lista tabla de busqueda de notificaciones       
    public function filtroBusqueda($opt, $idListaNotificacion) {
        
        $this->perfilUsuario();
        if ($this->perfilUsuario == 'PFL_OPE_PRE_NOTI') { 
            $consulta = $this->lNegocioNotificaciones->buscarLista("id_lista_notificacion = $idListaNotificacion");
        } else if($this->perfilUsuario == 'PFL_TEC_RES_NOTI'){  
            $consulta = $this->lNegocioRespuestaNotificacion->buscarRegistrosXOperadorRespuesta($idListaNotificacion);  
            $consulta = isset($consulta) ? $consulta : '';
        }
                 
        
        if(isset($consulta)>0 && ($consulta != null)){
            foreach ($consulta as $fila) {
                $arrayParametros = array(
                    'id' => $fila['id_notificacion'],
                    'codigo_documento' => $fila['codigo_documento'],
                    'nombre_pais_notifica' => $fila['nombre_pais_notifica'],
                    'producto' => $fila['producto'],
                    'fecha_notificacion' => $fila['fecha_notificacion'],
                    'fecha_cierre' => $fila['fecha_cierre'],
                    'descripcion' => $fila['descripcion'],
                    'enlace' => $fila['enlace']);
            }
        }    
            switch ($opt) {
                case 1:
                    $this->panelBusqueda = '<table class="filtro" style="width: 450px;">
                        <tbody>
                                <tr>
                                    <th colspan="4">Buscar notificación:</th>
                                    </tr>
                                    <tr style="width: 100%;"> 
                                        <td >Cód Documento: </td>
                                        <td colspan="3">  
                                            <input type="text" id="codDocumento" name="codDocumento" style="width: 100%">
                                        </td>    
                                        <input type="hidden" id="idListaNotificacion" name="idListaNotificacion" value="' . $idListaNotificacion . '" readonly="readonly" >
                                    </tr>
                                    <tr  style="width: 100%;">
                                        <td >País que notifica: </td>
                                        <td colspan="3">
                                            <select id="idPais" name="idPais" style="width: 100%;">
                                                <option value="">Seleccione...</option>
                                                        ' . $this->comboVariosPaises($idLocalizacion = null) . '
                                            </select>
                                        </td>
                                    </tr>
                                    <tr  style="width: 100%;">
                                        <td >Fecha inicio de notificación: </td>
                                        <td>
                                            <input type="text" id="fechaInicioNotificacion" name="fechaInicioNotificacion" readonly style="width: 100%">
                                        </td>
                                    
                                        <td >Fecha fin de notificación: </td>
                                        <td>
                                            <input type="text" id="fechaFinNotificacion" name="fechaFinNotificacion" readonly style="width: 100%">
                                        </td>
                                    </tr>
                                    <tr  style="width: 100%;">
                                        <td>Tipo de documento: </td>
                                        <td colspan="3">
                                            <select id="tipoDocumento" name="tipoDocumento" style="width: 100%;" required>' . $this->comboTipoDocumento() . '</select>
                                       </td>
                                       </tr>
                                    <tr  style="width: 100%;">
                                    <td>Producto: </td>
                                        <td colspan="3">
                                            <input type="text" id="productoNotificacion" name="productoNotificacion" style="width: 100%">
                                       </td>
                                    </tr>
				                    <tr>
				                    <td >Área temática: </td>
				                    <td colspan="3">
				                    <select id="areaTematica" name="areaTematica" style="width: 100%;" >' . $this->comboTipoDocumento('Área temática') . '</select>
				                    </td>
				                    </tr>';
                  //  if ($this->perfilUsuario == 'PFL_TEC_RES_NOTI') {
                    		$this->panelBusqueda .= '<tr>
                                                    <td >Estado: </td>
                                                    <td colspan="3">
                                                        <select id="estadoRespuesta" name="estadoRespuesta" style="width: 100%">
                                                            <option value="">Seleccione...</option>
                                                                    ' . $this->comboEstadosRespuesta() . '
                                                        </select>
                                                    </td>
                                                </tr>';
               //     }
                   
                    $this->panelBusqueda .= '<td colspan="4" style="text-align: end;">
                                        <button id="btnFiltrarLista">Filtrar lista</button>
                                    </td>
                                    </tr>
                        </tbody>
                        </table>';
                    break;
            }
    }
    
    /**
     * Combo de estados para respuestas
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboEstadosRespuesta($opcion = null)
    {
        $combo = "";
        if ($opcion == "Vigente") {
            $combo .= '<option value="true" selected="selected">Respondido</option>';
            $combo .= '<option value="false">No Respondido</option>';
            $combo .= '<option value="Todos">Todos</option>';
        } else if ($opcion == "Caducado") {
            $combo .= '<option value="true" >Respondido</option>';
            $combo .= '<option value="false" selected="selected">No Respondido</option>';
            $combo .= '<option value="Todos">Todos</option>';
        } else if ($opcion == "Todos") {
            $combo .= '<option value="true" >Respondido</option>';
            $combo .= '<option value="false">No Respondido</option>';
            $combo .= '<option value="Todos" selected="selected">Todos</option>';
        } else {
            $combo .= '<option value="true">Respondido</option>';
            $combo .= '<option value="false">No Respondido</option>';
            $combo .= '<option value="vigente">Vigente</option>';
            $combo .= '<option value="Todos">Todos</option>';
        }
        
        return $combo;
    }

    /**
     * Método para devolver la codificacion del perfil
     */
    public function perfilUsuario() {
        $consulta = $this->lNegocioRespuestaNotificacion->verificarPerfil($_SESSION['usuario']);
        $this->perfilUsuario = $consulta->current()->codificacion_perfil;
    }

    /**
     * Método para listar notificaciones registradas
     * */
    public function listarNotificacionesFiltradas() {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        $idListaNotificacion = $_POST["idListaNotificacion"];
        $codDocumento = $_POST["codDocumento"];
        $fechaInicioNotificacion = $_POST["fechaInicioNotificacion"];
        $fechaFinNotificacion = $_POST["fechaFinNotificacion"];
        $idPais = $_POST["idPais"];
        $tipoDocumento = $_POST["tipoDocumento"];
        $producto = $_POST["producto"];
        $areaTematica = $_POST["areaTematica"];
        $estadoRespuesta = $_POST["estadoRespuesta"];
        

        $this->perfilUsuario(); 
      //  if ($this->perfilUsuario == 'PFL_OPE_PRE_NOTI') {  
//             $arrayParametros = array('id_lista_notificacion' => $idListaNotificacion, 'codigo_documento' => $codDocumento, 'fecha_inicio_notificacion' => $fechaInicioNotificacion,
//             	'id_pais_notifica' => $idPais, 'tipo_documento' => $tipoDocumento, 'producto' => $producto, 'estado_respuesta' => $estadoRespuesta, 'areaTematica' => $areaTematica,'fecha_fin_notificacion' => $fechaFinNotificacion);
           
//             $notificaciones = $this->lNegocioNotificaciones->buscarNotificacionesXFiltroOperador($arrayParametros);
//             $this->tablaHtmlFormularioReporte($notificaciones);
            
      //  } else if ($this->perfilUsuario == 'PFL_TEC_RES_NOTI') {  
           
            
            $arrayParametros = array('id_lista_notificacion' => $idListaNotificacion, 'codigo_documento' => $codDocumento, 'fecha_inicio_notificacion' => $fechaInicioNotificacion,
            	'id_pais_notifica' => $idPais, 'tipo_documento' => $tipoDocumento, 'producto' => $producto, 'estado_respuesta' => $estadoRespuesta, 'area_tematica' => $areaTematica,'fecha_fin_notificacion' => $fechaFinNotificacion);

            if($estadoRespuesta === 'Todos'){
                $notificaciones = $this->lNegocioNotificaciones->buscarNotificacionesTecnicoXFiltroTodos($arrayParametros);
            }else{
                //Cambio de consulta
                $notificaciones = $this->lNegocioNotificaciones->buscarNotificacionesTecnicoXFiltro($arrayParametros);
            }
            //$this->tablaHtmlFormularioReporteTecnico($notificaciones);
            $this->tablaHtmlFormularioReporte($notificaciones);
      //  } 
        
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);

        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    
    }

    public function comentarioTecnico($identificador){
        $datos = $this->lNegocioRespuestaNotificacion->buscarInformacionTecnico($identificador);
        $archivo ='';
        if ($this->modeloRespuestaNotificacion->getArchivo() != '0') {
            $archivo = '<a href="' . $this->modeloRespuestaNotificacion->getArchivo() . '" target="_blank" >Descargar</a>';
        } else {
            $archivo = 'No exite archivo subido';
        }
    	$respuesta = '
        <fieldset>
        <legend>Respuesta técnico</legend>
		<div data-linea="1">
			<label for="nombre">Nombre: </label><span>'. $datos->current()->funcionario.'</span>
		</div>
		<div data-linea="2">
			<label for="direccion">Dirección:  </label><span>'. $datos->current()->puesto.'</span>
		</div>
		<div data-linea="3">
			<label for="fecha">Fecha respuesta: </label><span>'. $this->modeloRespuestaNotificacion->getFechaRevision().'</span>
		</div>
		<div data-linea="4">
			<label for="respuesta">Respuesta: </label><span>'. $this->modeloRespuestaNotificacion->getRespuesta().'</span>
		</div>
		<div data-linea="5">
			<label for="adjunto">Archivo adjunto:  </label><span>'.$archivo.'</span>
		</div>
       </fieldset>';
    	
    	return $respuesta;
    }
}

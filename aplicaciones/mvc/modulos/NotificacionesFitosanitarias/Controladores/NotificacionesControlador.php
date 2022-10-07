<?php
/**
 * Controlador Notificaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  NotificacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-09-09
 * @uses    NotificacionesControlador
 * @package NotificacionesFitosanitarias
 * @subpackage Controladores
 */
namespace Agrodb\NotificacionesFitosanitarias\Controladores;

use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionesLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionesModelo;
use Agrodb\NotificacionesFitosanitarias\Modelos\ListaNotificacionLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionPorPaisAfectadoLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionPorPaisAfectadoModelo;
use Agrodb\NotificacionesFitosanitarias\Modelos\AreaTematicaNotificacionLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\NotificacionesFitosanitarias\Modelos\AreaTematicaNotificacionModelo;

class NotificacionesControlador extends BaseControlador
{

    private $lNegocioNotificaciones = null;
    private $modeloNotificaciones = null;
    private $accion = null;
    private $lNegocioListaNotificacion = null;
    private $lNegocioNotificacionPorPaisAfectado = null;    
    private $modeloNotificacionPorPaisAfectado = null;
    private $lNegocioAreaTematica = null;
    private $modeloAreaTematica = null;
    private $areaTematica = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioNotificaciones = new NotificacionesLogicaNegocio();
        $this->modeloNotificaciones = new NotificacionesModelo();
        $this->lNegocioListaNotificacion = new ListaNotificacionLogicaNegocio();
        $this->lNegocioNotificacionPorPaisAfectado = new NotificacionPorPaisAfectadoLogicaNegocio();
        $this->modeloNotificacionPorPaisAfectado = new NotificacionPorPaisAfectadoModelo();
        $this->lNegocioAreaTematica = new AreaTematicaNotificacionLogicaNegocio();
        $this->modeloAreaTematica = new AreaTematicaNotificacionModelo();
        
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        echo($_POST['id']);
        $modeloNotificaciones = $this->lNegocioNotificaciones->buscarNotificaciones();
        $this->tablaHtmlNotificaciones($modeloNotificaciones);
        require APP . 'NotificacionesFitosanitarias/vistas/listaNotificacionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Notificación";
        
        $this->formulario = 'nuevo';
        
        $consulta = $this->lNegocioListaNotificacion->buscarListaNotificacionesFiltro($_POST['id']);
        
        $this->anio = $consulta->current()->anio;
        $this->idLista = $consulta->current()->id_lista_notificacion;

        require APP . 'NotificacionesFitosanitarias/vistas/formularioNotificacionesVista.php';       
    }

    /**
     * Método para registrar en la base de datos -Notificaciones
     */
    public function guardar()
    {
        
        if(isset($_POST['areaTematica'])){
        
                if(!isset($_POST['id_notificacion'])){
                    $fechaNotificacion = date('Y-m-d', strtotime($_POST['fechaNotificacion']));
                    $fechaCierre = strtotime($fechaNotificacion. '+59 days');
                    $fechaCierre = date('Y-m-d', $fechaCierre);
                    $fechaCierre = "'" . $fechaCierre . "'";
                    $arrayParametros = array(
                        'id_lista_notificacion' => $_POST['id_lista_notificacion'],
                        'codigo_documento' => $_POST['codigo_documento'],
                        'tipo_documento' => $_POST['tipoDocumento'],
                        'descripcion' => $_POST['descripcion'],
                        'enlace' => $_POST['enlace'],
                        'producto' => $_POST['producto'],
                        'palabra_clave' => $_POST['palabraClave'],
                        'fecha_notificacion' => $_POST['fechaNotificacion'],
                        'fecha_cierre' => $fechaCierre,
                        'id_pais_notifica' => $_POST['id_pais_notifica'],
                        'nombre_pais_notifica' => $_POST['pais_notifica'],
                        'area_tematica' => $_POST['areaTematica']
                    );
                    $id = $this->lNegocioNotificaciones->guardarRegistros($arrayParametros);
                    
                    if(isset($_POST['idLocalizacion'])){
                        if (count($_POST['idLocalizacion'])>0){                
                            for($i=0; $i < count($_POST['idLocalizacion']); $i++){
                                $arrayValores = array(
                                    'id_notificacion' => $id,
                                    'id_localizacion' => $_POST['idLocalizacion'][$i],
                                    'nombre_pais' => $_POST['nombreLocalizacion'][$i]
                                );
                                
                                $this->lNegocioNotificacionPorPaisAfectado->guardar($arrayValores);
                            }                
                        }
                    }
                    Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
                }else{
                    $fechaNotificacion = date('Y-m-d', strtotime($_POST['fecha_notificacion']));
                    $fechaCierre = strtotime($fechaNotificacion. '+59 days');
                    $fechaCierre = date('Y-m-d', $fechaCierre);
                    $fechaCierre = "'" . $fechaCierre . "'";
                    
                    $arrayParametros = array(
                        'id_notificacion' => $_POST['id_notificacion'],
                        'id_lista_notificacion' => $_POST['notificacion'],
                        'codigo_documento' => $_POST['codigo_documento'],
                        'tipo_documento' => $_POST['tipo_documento'],
                        'fecha_notificacion' => $_POST['fecha_notificacion'],
                        'fecha_cierre' => $fechaCierre,
                        'producto' => $_POST['producto'],
                        'palabra_clave' => $_POST['palabra_clave'],
                        'descripcion' => $_POST['descripcion'],
                        'enlace' => $_POST['enlace'],
                        'id_pais_notifica' => $_POST['id_pais_notifica_editar'],
                        'nombre_pais_notifica' => $_POST['pais_notifica_editar'],
                        'area_tematica' => $_POST['areaTematica']
                    );
                    
                    $this->lNegocioNotificaciones->guardarRegistros($arrayParametros);
                    Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
                }
        }else{
            Mensajes::fallo('Debe seleccionar una área tematica...!!');
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Notificaciones
     */
    public function editar()
    {
        $this->accion = "Editar Notificación";
        $this->formulario = 'abrir';
        
        $this->modeloNotificaciones = $this->lNegocioNotificaciones->buscar($_POST["id"]);
        
        $idListaNo = $this->modeloNotificaciones->getIdListaNotificacion();
        $consulta = $this->lNegocioListaNotificacion->buscarListaNotificacionesFiltro($idListaNo);
        
        $this->anio = $consulta->current()->anio;
        $this->idLista = $consulta->current()->id_lista_notificacion;
        $idNotificaciones = $_POST["id"];
        $this->datosDetalleFormulario = $this->generarDetalleFormularioNotificaciones($idNotificaciones);
        require APP . 'NotificacionesFitosanitarias/vistas/formularioNotificacionesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Notificaciones
     */
    public function borrar()
    {
        $this->lNegocioNotificaciones->borrar($_POST['elementos']);
    }

    public function listarNotificacionesFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        $idListaNotificacion = $_POST["idListaNotificacion"];
        $codDocumento = $_POST["codDocumento"];
        $fechaNotificacion = $_POST["fechaNotificacion"];
        $idPais = $_POST["idPais"];
        $tipoDocumento = $_POST["tipoDocumento"];
        $producto = $_POST["producto"];
        $areaTematica = $_POST["areaTematica"];

        $arrayParametros = array(
            'id_lista_notificacion' => $idListaNotificacion,
            'codigo_documento' => $codDocumento,
            'fecha_notificacion' => $fechaNotificacion,
            'id_pais_notifica' => $idPais,
            'tipo_documento' => $tipoDocumento,
            'producto' => $producto,
            'area_tematica' => $areaTematica
        );
        $notificaciones = $this->lNegocioNotificaciones->buscarNotificacionesXFiltro($arrayParametros);

        $this->tablaHtmlFormularioReporte($notificaciones);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);

        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    
    public function listarNotificacionesFiltradasOperador()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idListaNotificacion = $_POST["idListaNotificacion"];
        $codDocumento = $_POST["codDocumento"];
        $fechaNotificacion = $_POST["fechaNotificacion"];
        $idPais = $_POST["idPais"];
        $tipoDocumento = $_POST["tipoDocumento"];
        
        $arrayParametros = array(
            'id_lista_notificacion' => $idListaNotificacion,
            'codigo_documento' => $codDocumento,
            'fecha_notificacion' => $fechaNotificacion,
            'id_pais_notifica' => $idPais,
            'tipo_documento' => $tipoDocumento
        );
        $notificaciones = $this->lNegocioNotificaciones->buscarNotificacionesXFiltroOperador($arrayParametros);
        
        $this->tablaHtmlFormularioReporte($notificaciones);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }

    public function tablaHtmlFormularioReporte($arrayParametros)
    {
        $contador = 0;
        foreach ($arrayParametros as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_notificacion'] . '"
                    class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'NotificacionesFitosanitarias\Notificaciones"
                    data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    data-destino="detalleItem">
                    <td>' . ++ $contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila['codigo_documento'] . '</b></td>
			<td>' . $fila['nombre_pais_notifica'] . '</td>
			<td>' . $fila['producto'] . '</td>
			<td>' . $fila['area_tematica'] . '</td>
			<td>' . date('Y-m-d', strtotime($fila['fecha_notificacion'])) . '</td>
			<td>' . date('Y-m-d', strtotime($fila['fecha_cierre'])) . '</td>
			</tr>'
            );
        }
    }

    public function generarDetalleFormularioNotificaciones($idNotificaciones)
    {
        $consulta = $this->lNegocioNotificacionPorPaisAfectado->buscarLista("id_notificacion = $idNotificaciones");
        
        $count = 0;
        $html = '';
        foreach ($consulta as $item) {
            $campoLocalizacion = "<input type='hidden' name='idLocalizacion[]' readonly class='list_id_localizacion' value=" . $item['id_notificacion_por_producto'] . ">"; // identificador pais afectado
            $html .= "<tr id='".$item['id_localizacion']."' align = 'center'>";
            $html .= "<td>" . ++ $count . "</td><td>" . $item['nombre_pais'] . "</td>";
            $html .= "<td class='borrar'><button type='button' name='eliminar' id='eliminar' class='icono' onclick='fn_eliminarEditar(" . $item['id_notificacion_por_producto'] . "); return false;'/></td></tr>";
        }
        return $html;
    }
    
    /**
     * Construye el combo para desplegar la lista de notificaciones por años
     */
    public function comboAniosNotificaciones($anio)
    {
        $combo = "";
        
        $listaNotificacion = $this->lNegocioListaNotificacion->buscarAnioNotificaciones();
        
        foreach ($listaNotificacion as $item) {
            if ($anio == $item->anio) {
                $combo .= '<option value="' . $item->anio . '"  selected>' . $item->anio . '</option>';
            } else {
                $combo .= '<option value="' . $item->anio . '" >' . $item->anio . '</option>';
            }
        }
        
        echo $combo;
    }
    
    /**
     * Construye el combo para desplegar la lista de notificaciones por años
     */
    public function comboListaNotificacionesXAnio($anio, $idRegistro = null)
    {
        $combo = "";
        $busqueda = "anio=$anio order by id_lista_notificacion asc";
        
        $listaNotificacion = $this->lNegocioListaNotificacion->buscarLista($busqueda);
        
        foreach ($listaNotificacion as $item) {
            if ($idRegistro == $item->id_lista_notificacion) {
                $combo .= '<option value="' . $item->id_lista_notificacion . '" selected>' . $item->nombre_lista . '</option>';
            } else {
                $combo .= '<option value="' . $item->id_lista_notificacion . '" >' . $item->nombre_lista . '</option>';
            }
        }
        
        echo $combo;
    }
    
    /**
     * Construye el combo para desplegar la lista de notificaciones por años
     */
    public function comboListaNotificacionXAnio($anio)
    {
        $combo = "";
        $combo .= '<option value="">Seleccione....</option>';
        
        $busqueda = "anio=$anio order by id_lista_notificacion asc";
        
        $listaNotificacion = $this->lNegocioListaNotificacion->buscarLista($busqueda);
     
        foreach ($listaNotificacion as $item) {
            $combo .= '<option value="' . $item->id_lista_notificacion . '" >' . $item->nombre_lista . '</option>';
        }
        
        echo $combo;
        exit();
    }
    
    /**
     * Cambios en base a documento de control de cambios 
     */
   
    public function listarAreaTematica($id=null){
        
        $valores = array('Sanidad Animal','Sanidad Vegetal','Inocuidad de los alimentos','Registro de Insumos Agropecuarios','Laboratorios');
        
        $datos='';
        $i=0;
        foreach ($valores as $item) {
            if($i==0){
                $datos .= '<tr>';
            }
            if($id != null){
                $elementos = $this->lNegocioAreaTematica->buscarLista("id_notificacion=".$id." and area_tematica='".$item."'");
                if($elementos->count()){
                    $datos .= '<td><input class="areaTema" checked name="areaTematica[]" type="checkbox" value="'.$item.'"> '.$item.'</td>';
                }else{
                    $datos .= '<td><input class="areaTema" name="areaTematica[]" type="checkbox" value="'.$item.'"> '.$item.'</td>';
                }
            }else{
                $datos .= '<td><input class="areaTema" name="areaTematica[]" type="checkbox" value="'.$item.'"> '.$item.'</td>';
            }
            $i++;
            if($i==2){
                $datos .= '<tr>';
                $i=0;
            }
        }
        $html = '
			<table  style="width: 100%;">
			'.$datos.'
			</table>
         ';
        return $html;
    }
    
    /**
     * Construye el combo para actualizar la opcion de ingresar comentarios
     */
    public function actualizarComentarios()
    {
    	$this->lNegocioNotificaciones->guardar($_POST);
    }
    
    
}

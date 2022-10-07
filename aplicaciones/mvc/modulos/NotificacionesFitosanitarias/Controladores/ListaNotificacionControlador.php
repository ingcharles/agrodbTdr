<?php

/**
 * Controlador ListaNotificacion
 *
 * Este archivo controla la lógica del negocio del modelo:  ListaNotificacionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-09-09
 * @uses    ListaNotificacionControlador
 * @package NotificacionesFitosanitarias
 * @subpackage Controladores
 */

namespace Agrodb\NotificacionesFitosanitarias\Controladores;

use Agrodb\NotificacionesFitosanitarias\Modelos\ListaNotificacionLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\ListaNotificacionModelo;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionesLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionesModelo;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionPorPaisAfectadoLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionPorPaisAfectadoModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ListaNotificacionControlador extends BaseControlador {

    private $lNegocioListaNotificacion = null;
    private $modeloListaNotificacion = null;
    private $lNegocioNotificaciones = null;
    private $modeloNotificaciones = null;
    private $lNegocioNotificacionPorPaisAfectado = null;
    private $modeloNotificacionPorPaisAfectado = null;
    private $accion = null;
    private $article = null;
    private $botones = null;
    private $panelBusqueda = null;
    private $panelBusquedaNotificaciones = null;

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->lNegocioListaNotificacion = new ListaNotificacionLogicaNegocio();
        $this->modeloListaNotificacion = new ListaNotificacionModelo();
        $this->lNegocioNotificaciones = new NotificacionesLogicaNegocio();
        $this->modeloNotificaciones = new NotificacionesModelo();
        $this->lNegocioNotificacionPorPaisAfectado = new NotificacionPorPaisAfectadoLogicaNegocio();
        $this->modeloNotificacionPorPaisAfectado = new NotificacionPorPaisAfectadoModelo();
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index() {
        $this->filtroProduccion();
        $this->articleNotificacionesXAnio();
        require APP . 'NotificacionesFitosanitarias/vistas/listaListaNotificacionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo() {
        $this->accion = "Nuevo Registro Notificaciones";
        require APP . 'NotificacionesFitosanitarias/vistas/formularioListaNotificacionesVista.php';
    }

    /**
     * Método para registrar en la base de datos -ListaNotificacion
     */
    public function guardar() {
        $arrayIndex = "anio = " . $_POST['anio'] . " and mes = '" . $_POST['mes'] . "'  ";
        $consulta = $this->lNegocioListaNotificacion->buscarLista($arrayIndex);
        if (count($consulta) == 0) {
            $this->lNegocioListaNotificacion->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        } else {
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }
    }

    /**
     * Método para cargar las Notificaciones por excel
     */
    public function cargaNotificacion() {
        $this->accion = "Carga masiva de Notificaciones";
        require APP . 'NotificacionesFitosanitarias/vistas/formularioCargaMasivaNotificacion.php';
    }

    /**
     * Método para obtener ruta de archivo excel
     */
    public function cargarDocumentoMasivo() {
        $idListaNotificacion = $_POST['idListaNotificacion'];
        $this->lNegocioNotificaciones->leerArchivoExcelNotificaciones($_POST,$idListaNotificacion);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ListaNotificacion
     */
    public function editar() {
        $this->accion = "Editar Lista Notificacion";
        $this->modeloListaNotificacion = $this->lNegocioListaNotificacion->buscar($_POST["id"]);
        require APP . 'NotificacionesFitosanitarias/vistas/formularioListaNotificacionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ListaNotificacion
     */
    public function borrar() {
        $this->lNegocioListaNotificacion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ListaNotificacion
     */
    public function tablaHtmlListaNotificacion($tabla) {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_lista_notificacion'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'NotificacionesFitosanitarias\listanotificacion"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_lista_notificacion'] . '</b></td>
                    <td>' . $fila['nombre_lista'] . '</td>
                    <td>' . $fila['anio'] . '</td>
                <td>' . $fila['mes'] . '</td>
                </tr>'
            );
        }
    }

    public function agregarDetalleFormularioNotificaciones() {
        $arrayValores = array(
            'id_notificacion' => $_POST['idNotificacion'],
            'id_localizacion' => $_POST['idLocalizacion'],
            'nombre_pais' => $_POST['idNombre']
        );
        $this->lNegocioNotificacionPorPaisAfectado->guardar($arrayValores);
        $consulta = $this->lNegocioNotificacionPorPaisAfectado->buscarLista("id_notificacion = " . $_POST['idNotificacion'] . "");
       
        $count = 0;
        $html = '';
        foreach ($consulta as $item) {
            $campoLocalizacion = "<input type='hidden' name='idLocalizacion[]' readonly class='list_id_localizacion' value=" . $item['id_notificacion_por_producto'] . ">"; // identificador pais afectado
            $html .= "<tr id='".$item['id_localizacion']."' align='center'>";
            $html .= "<td>" . ++$count . "</td><td>" . $item['nombre_pais'] . "</td>";
            $html .= "<td class='borrar'><button type='button' name='eliminar' id='eliminar' class='icono' onclick='fn_eliminarEditar(" . $item['id_notificacion_por_producto'] . "); return false;'/></td></tr>";
        }
        echo $html;
    }

    public function eliminarDetalleFormularioNotificaciones() {
        $this->lNegocioNotificacionPorPaisAfectado->borrar($_POST['idNotificacionProducto']);
        $consulta = $this->lNegocioNotificacionPorPaisAfectado->buscarLista("id_notificacion = " . $_POST['idNotificacion'] . "");

        $count = 0;
        $html = '';
        foreach ($consulta as $item) {
            $campoLocalizacion = "<input type='hidden' name='idLocalizacion[]' readonly class='list_id_localizacion' value=" . $item['id_notificacion_por_producto'] . ">"; // identificador pais afectado
            $html .= "<tr align='center'>";
           
            $html .= "<td>" . ++$count . "</td><td>" . $item['nombre_pais'] . "</td>";
            $html .= "<td class='borrar'><button type='button' name='eliminar' id='eliminar' class='icono' onclick='fn_eliminarEditar(" . $item['id_notificacion_por_producto'] . "); return false;'/></td></tr>";
        }
        echo $html;
    }

    public function filtroProduccion() {
            $this->panelBusquedaNotificaciones = '<table class="filtro" style="width: 450px;">
                    <tbody>
                        <tr><th colspan="2">Buscar lista notificaciones:</th></tr>
                        <tr style="width: 100%;">
                            <td align="right">Nombre lista notificación:</td>
                            <td colspan="3"><input name="nombreNotificacion" id="nombreNotificacion" type="text" style="width: 100%"/></td>		
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: end;">
                                <button id="btnFiltrar">Buscar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>';
    }
    public function articleNotificacionesXAnio() {
       
        $consultaCabecera = $this->lNegocioListaNotificacion->buscarAnioNotificaciones();
        $contador = 0;
        $this->article ="";
            
        foreach ($consultaCabecera as $fila1) {
            
            $this->article .="<h2>".$fila1['anio']."</h2>";        
        
            $consulta = $this->lNegocioListaNotificacion->buscarLista("anio = ".$fila1['anio']." ");
            foreach ($consulta as $fila) {
                
                $arrayParametros = array(
                    'idLista' => $fila['id_lista_notificacion'],
                    'rutaAplicacion' => URL_MVC_FOLDER . 'NotificacionesFitosanitarias',
                    'opcion' => 'ListaNotificacion/listar',
                    'destino' => 'listadoItems',
                    'contador' => ++$contador,
                    'texto1' => $fila['nombre_lista'],
                    'texto2' => $fila['anio']
                );
                $this->article .= $this->articleComun($arrayParametros, 1);
            }
            
        }
      
    }

    public function filtrarDatos() {
        $nombreNotificacion = $_POST["nombreNotificacion"];
        $nombreNotificacion = $nombreNotificacion != "" ? "'%" . $nombreNotificacion . "%'" : "NULL";
        $consultaCabecera = $this->lNegocioListaNotificacion->buscarAnioNotificacionesBusqueda("nombre_lista ilike $nombreNotificacion ");
        $contador = 0;
        $this->article ="";
            
        foreach ($consultaCabecera as $fila1) {
            $this->article .="<h2>".$fila1['anio']."</h2>";        
            $consulta = $this->lNegocioListaNotificacion->buscarLista("upper(nombre_lista) ilike upper(".$nombreNotificacion.") and anio = ".$fila1['anio']." order by id_lista_notificacion");
            foreach ($consulta as $fila) {
                
                $arrayParametros = array(
                    'idLista' => $fila['id_lista_notificacion'],
                    'rutaAplicacion' => URL_MVC_FOLDER . 'NotificacionesFitosanitarias',
                    'opcion' => 'ListaNotificacion/listar',
                    'destino' => 'listadoItems',
                    'contador' => ++$contador,
                    'texto1' => $fila['nombre_lista'],
                    'texto2' => $fila['anio']
                );
                $this->article .= $this->articleComun($arrayParametros, 1);
            }
        }

        echo json_encode(array(
            'estado' => 'EXITO',
            'mensaje' => '',
            'contenido' => $this->article
        ));
    }

    public function listar() {
        $idListaNotificacion = $_POST['id'];
        $arrayParametros = array(
            'id_lista_notificacion' => $idListaNotificacion );
        $this->filtroBusqueda($idListaNotificacion);
        $this->detalleFormulario = 'Lista de Notificaciones';
        $this->botones = $this->crearAccionBotonesCF($arrayParametros);
        // $busqueda = "id_lista_notificacion =". $idListaNotificacion; // order by id_notificacion desc";
        // $consultaNotificacionXMes = $this->lNegocioNotificaciones->buscarLista($busqueda);
        
        $arrayParametros = array("id_lista_notificacion" => $idListaNotificacion);
        $consultaNotificacionXMes = $this->lNegocioNotificaciones->buscarNotificacionesAreasTematicas($arrayParametros);
        
        $this->tablaHtmlDetalleFormularioNotificaciones($consultaNotificacionXMes);
        require APP . 'NotificacionesFitosanitarias/vistas/listaNotificacionesVista.php';
    }

    public function filtroBusqueda($idProceso) {
        $idListaNotificacion = $idProceso;
        $this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <th colspan="4">Buscar notificación:</th>
                                </tr>
                                <tr style="width: 100%;"> 
                                    <td >Cód Documento: </td>
                                    <td >
                                        <input type="text" id="codDocumento" name="codDocumento" style="width: 100%">
                                        <input type="hidden" id="idListaNotificacion" name="idListaNotificacion" value="' . $idListaNotificacion . '" readonly="readonly" >
                                    </td>    
                                    
                                </tr>
                                
                                <tr  style="width: 100%;">
                                    <td >Fecha de notificación: </td>
                                    <td>
                                        <input type="text" id="fechaNotificacion" name="fechaNotificacion" readonly style="width: 100%">
                                    </td>
                                </tr>
                                
                                <tr  style="width: 100%;">
                                    <td >País que notifica: </td>
                                    <td>
                                        <select id="idPais" name="idPais">
                                            <option value="">Seleccione...</option>
                                                    ' . $this->comboVariosPaises($idLocalizacion = null) . '
                                        </select>
                                    </td>
                                </tr>
                                 <tr  style="width: 100%;">
                                    <td>Tipo de documento: </td>
                                    <td>
                                        <select id="tipoDocumento" name="tipoDocumento" style="width: 100%;" required>' . $this->comboTipoDocumento() . '</select>
                                   </td>
                                </tr>
                               <tr  style="width: 100%;">
                                    <td>Área  temática: </td>
                                    <td>
                                        <select id="areaTematica" name="areaTematica" style="width: 100%;" required>' . $this->comboTipoDocumento('Área temática') . '</select>
                                   </td>
                                </tr>
								<tr  style="width: 100%;">
                                    <td>Producto: </td>
                                    <td>
                                        <input type="text" id="productoNotificacion" name="productoNotificacion" style="width: 100%">
                                   </td>
                                </tr>
                                <td colspan="4" style="text-align: end;">
                                    <button id="btnFiltrarLista">Filtrar lista</button>
                                </td>
                                </tr>
                            </tbody>
                    </table>';
    }
    
    /**
	 * Construye el código HTML para desplegar la lista de - FormularioAnteMortem
	 */
	public function tablaHtmlDetalleFormularioNotificaciones($tabla){
            $contador = 0;
            foreach ($tabla as $fila) {
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

}

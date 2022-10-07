<?php
/**
 * Controlador SitiosAreasProductos
 *
 * Este archivo controla la lógica del negocio del modelo:  SitiosAreasProductosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-03-23
 * @uses    SitiosAreasProductosControlador
 * @package CertificacionBPA
 * @subpackage Controladores
 */
namespace Agrodb\CertificacionBPA\Controladores;

use Agrodb\CertificacionBPA\Modelos\SitiosAreasProductosLogicaNegocio;
use Agrodb\CertificacionBPA\Modelos\SitiosAreasProductosModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class SitiosAreasProductosControlador extends BaseControlador
{

    private $lNegocioSitiosAreasProductos = null;

    private $modeloSitiosAreasProductos = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioSitiosAreasProductos = new SitiosAreasProductosLogicaNegocio();
        $this->modeloSitiosAreasProductos = new SitiosAreasProductosModelo();
        
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
        $modeloSitiosAreasProductos = $this->lNegocioSitiosAreasProductos->buscarSitiosAreasProductos();
        $this->tablaHtmlSitiosAreasProductos($modeloSitiosAreasProductos);
        require APP . 'CertificacionBPA/vistas/listaSitiosAreasProductosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo SitiosAreasProductos";
        require APP . 'CertificacionBPA/vistas/formularioSitiosAreasProductosVista.php';
    }

    /**
     * Método para registrar en la base de datos -SitiosAreasProductos
     */
    public function guardar()
    {        
        $this->lNegocioSitiosAreasProductos->guardar($_POST);
        //Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: SitiosAreasProductos
     */
    public function editar()
    {
        $this->accion = "Editar SitiosAreasProductos";
        $this->modeloSitiosAreasProductos = $this->lNegocioSitiosAreasProductos->buscar($_POST["id"]);
        require APP . 'CertificacionBPA/vistas/formularioSitiosAreasProductosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - SitiosAreasProductos
     */
    public function borrar()
    {
        $this->lNegocioSitiosAreasProductos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - SitiosAreasProductos
     */
    public function tablaHtmlSitiosAreasProductos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_sitio_area_producto'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificacionBPA\sitiosareasproductos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_sitio_area_producto'] . '</b></td>
<td>' . $fila['id_solicitud'] . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
<td>' . $fila['identificador_operador'] . '</td>
</tr>'
                );
            }
        }
    }
    
    /**
     * Método para listar los sitios/áreas/productos registrados
     */
    public function construirDetalleSitioAreaProductoVisualizacion($idSolicitud)
    {
        $this->sitios = "";

        $query = "id_solicitud = $idSolicitud ORDER BY nombre_sitio, nombre_area, nombre_producto ASC";
        
        $listaDetalles = $this->lNegocioSitiosAreasProductos->buscarLista($query);
        
        $i=1;
        
        foreach ($listaDetalles as $fila) {
            
            $this->sitios .=
                    '<tr>
                        <td>' . $i++. '</td>
                        <td>' . ($fila['nombre_sitio'] != '' ? $fila['nombre_sitio'] : ''). '</td>
                        <td>' . ($fila['nombre_area'] != '' ? $fila['nombre_area'] : ''). '</td>
                        <td>' . ($fila['nombre_producto'] != '' ? $fila['nombre_subtipo_producto'] .' - ' . $fila['nombre_producto'] : '') . '</td>
                        <td>' . ($fila['nombre_operacion'] != '' ? $fila['nombre_operacion'] : '') . '</td>
                        <td>' . ($fila['superficie'] != '' ? $fila['superficie'] : '') . '</td>
                        <td>' . ($fila['estado'] != '' ? $fila['estado'] : '') . '</td>
                    </tr>';
        }
        
        echo $this->sitios;
    }
    
    /**
     * Método para listar los sitios/áreas/productos registrados
     */
    public function construirDetalleSitioAreaProductoEdicion($idSolicitud)
    {
        $this->sitios = "";
        
        $query = "id_solicitud = $idSolicitud  ORDER BY nombre_sitio, nombre_area, nombre_producto ASC";
        
        $listaDetalles = $this->lNegocioSitiosAreasProductos->buscarLista($query);
        
        $i=1;
        
        foreach ($listaDetalles as $fila) {
            
            $this->sitios .=
            '<tr>
                        <td>' . $i++. '</td>
                        <td>' . ($fila['nombre_sitio'] != '' ? $fila['nombre_sitio'] : ''). '</td>
                        <td>' . ($fila['nombre_area'] != '' ? $fila['nombre_area'] : ''). '</td>
                        <td>' . ($fila['nombre_producto'] != '' ? $fila['nombre_subtipo_producto'] .' - ' . $fila['nombre_producto'] : '') . '</td>
                        <td>' . ($fila['nombre_operacion'] != '' ? $fila['nombre_operacion'] : '') . '</td>
                        <td>' . ($fila['superficie'] != '' ? $fila['superficie'] : '') . '</td>
                        <td>' . ($fila['estado'] != '' ? $fila['estado'] : '') . '<input id="iEstado" name="iEstado[]" value="'.($fila['estado'] != '' ? $fila['estado'] : '').'" type="hidden"></td>
                        <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDetalle(' . $fila['id_sitio_area_producto'] . '); return false;"/></td>
                    </tr>';
        }
        
        echo $this->sitios;
    }
    
    /**
     * Método para obtener los Sitios registrados y crear un combo para la unidad de producción
     * */
    public function buscarSitiosUnidadProduccion(){
        
        $idSolicitud = $_POST['idSolicitud'];
        $idSitio = $_POST['idSitio'];
        
        $sitios = $this->lNegocioSitiosAreasProductos->buscarSitios($idSolicitud);
        
        $comboSitios = "";
        $comboSitios .= '<option value="">Seleccione....</option>';
        
        if($sitios != ''){
            
            foreach ($sitios as $item)
            {
                if($idSitio == $item['id_sitio']){
                    $comboSitios .= '<option value="' . $item['id_sitio'] . '" selected>' . $item['nombre_sitio'] . '</option>';
                } else{
                    $comboSitios .= '<option value="' . $item['id_sitio'] . '" >' . $item['nombre_sitio'] . '</option>';
                }
            }
            
        }
        
        echo $comboSitios;
        exit();
    }
    
    /**
     * Método para obtener los datos de los sitios registrados de una solicitud
     * */
    public function obtenerSitiosRegistrados($idSolicitud)
    {
        $query = "id_solicitud = $idSolicitud";
        
        $sitios = $this->lNegocioSitiosAreasProductos->buscarLista($query);
        
        return $sitios;
    }
    
    /**
     * Método para calcular el total de hectáreas de los sitios declarados
     * */
    public function obtenerHectareas($idSolicitud)
    {
        $validacion = "Fallo";
        $resultado="El operador no tiene sitios registrados.";
        
        //Busca los datos de registros de sitios de una solicitud
        $sitios = $this->lNegocioSitiosAreasProductos->calcularHectareasXSitioXSolicitud($idSolicitud);        
        
        if(isset($sitios->current()->hectareas)){
            $validacion = "Exito";
            $resultado="";
            
            echo json_encode(array( 'resultado' => $resultado,
                                    'hectareas' => $sitios->current()->hectareas,
                                    'validacion' => $validacion));
        }else{
            $resultado="El operador no tiene sitios registrados.";
            
            echo json_encode(array( 'resultado' => $resultado,'validacion' => $validacion));
        }

    }
    
    /**
     * Método para registrar en la base de datos -SitiosAreasProductos
     */
    public function guardarDetalle()
    {
        /*$validacion = "Fallo";
        $resultado="El sitio/área/producto ya existe.";*/
        
        $arrayParametros = array(
            'id_solicitud' => $_POST['id_solicitud'],
            'id_sitio' => $_POST['id_sitio'],
            'id_area' => $_POST['id_area'],
            'id_producto' => $_POST['id_producto'],
            'id_operacion' => $_POST['id_operacion']
        );
        
        $datosSitio = $this->lNegocioSitiosAreasProductos->buscarSitioAreaProductoXSolicitud($arrayParametros);
        
        if(!isset($datosSitio->current()->id_sitio)){
            $this->lNegocioSitiosAreasProductos->guardar($_POST);
            
            /*$validacion = "Exito";
            $resultado = "Registro guardado con éxito";*/  
        }
        
        //echo json_encode(array( 'resultado' => $resultado,'validacion' => $validacion));
    }
}
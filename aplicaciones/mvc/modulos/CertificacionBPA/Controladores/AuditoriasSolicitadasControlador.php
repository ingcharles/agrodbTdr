<?php
/**
 * Controlador AuditoriasSolicitadas
 *
 * Este archivo controla la lógica del negocio del modelo:  AuditoriasSolicitadasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-03-23
 * @uses    AuditoriasSolicitadasControlador
 * @package CertificacionBPA
 * @subpackage Controladores
 */
namespace Agrodb\CertificacionBPA\Controladores;

use Agrodb\CertificacionBPA\Modelos\AuditoriasSolicitadasLogicaNegocio;
use Agrodb\CertificacionBPA\Modelos\AuditoriasSolicitadasModelo;

use Agrodb\CertificacionBPA\Modelos\TiposAuditoriasLogicaNegocio;

class AuditoriasSolicitadasControlador extends BaseControlador
{

    private $lNegocioAuditoriasSolicitadas = null;
    private $modeloAuditoriasSolicitadas = null;
    
    private $lNegocioTiposAuditorias = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioAuditoriasSolicitadas = new AuditoriasSolicitadasLogicaNegocio();
        $this->modeloAuditoriasSolicitadas = new AuditoriasSolicitadasModelo();
        
        $this->lNegocioTiposAuditorias = new TiposAuditoriasLogicaNegocio();
                
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
        $modeloAuditoriasSolicitadas = $this->lNegocioAuditoriasSolicitadas->buscarAuditoriasSolicitadas();
        $this->tablaHtmlAuditoriasSolicitadas($modeloAuditoriasSolicitadas);
        require APP . 'CertificacionBPA/vistas/listaAuditoriasSolicitadasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo AuditoriasSolicitadas";
        require APP . 'CertificacionBPA/vistas/formularioAuditoriasSolicitadasVista.php';
    }

    /**
     * Método para registrar en la base de datos -AuditoriasSolicitadas
     */
    public function guardar()
    {
        $this->lNegocioAuditoriasSolicitadas->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: AuditoriasSolicitadas
     */
    public function editar()
    {
        $this->accion = "Editar AuditoriasSolicitadas";
        $this->modeloAuditoriasSolicitadas = $this->lNegocioAuditoriasSolicitadas->buscar($_POST["id"]);
        require APP . 'CertificacionBPA/vistas/formularioAuditoriasSolicitadasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - AuditoriasSolicitadas
     */
    public function borrar()
    {
        $this->lNegocioAuditoriasSolicitadas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - AuditoriasSolicitadas
     */
    public function tablaHtmlAuditoriasSolicitadas($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_auditoria_solicitada'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificacionBPA\auditoriassolicitadas"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_auditoria_solicitada'] . '</b></td>
<td>' . $fila['id_solicitud_bpa'] . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
<td>' . $fila['id_tipo_auditoria'] . '</td>
</tr>'
                );
            }
        }
    }
    
    /**
     * Método para listar las auditorías registradas
     */
    public function construirDetalleAuditoriaVisualizacion($idSolicitud)
    {
        $this->auditorias = "";
        
        $query = "id_solicitud = $idSolicitud and estado = 'Activo' ORDER BY id_tipo_auditoria ASC";
        
        $listaDetalles = $this->lNegocioAuditoriasSolicitadas->buscarLista($query);
        
        $i=1;
        
        foreach ($listaDetalles as $fila) {
            
            $this->auditorias .=
                    '<tr>
                        <td>' . $i++. '</td>
                        <td>' . ($fila['tipo_auditoria'] != '' ? $fila['tipo_auditoria'] : ''). '</td>
                    </tr>';
        }
        
        echo $this->auditorias;
    }
    
    /**
     * Método para obtener los elementos del catálogo Tipos de Auditorías
     * */
    public function obtenerTiposAuditorias()
    {
        $query = "estado = 'Activo'";
        
        $tipoAuditoria = $this->lNegocioTiposAuditorias->buscarLista($query);
        
        return $tipoAuditoria;
    }
    
    /**
     * Método para obtener los elementos de tipos de auditorías seleccionados en
     * */
    public function obtenerTiposAuditoriasXSolicitud($idSolicitud)
    {
        $query = "estado = 'Activo' and id_solicitud = $idSolicitud";
        
        $tipoAuditoria = $this->lNegocioAuditoriasSolicitadas->buscarLista($query);
        
        return $tipoAuditoria;
    }
    
    /**
     * Checkbox con tipos de auditoría con detalle de registros solicitados
     *
     * @param
     * $respuesta
     * @return string
     */
    public function checkTiposAuditoriaDetalle($idSolicitud)
    {
        $check = '';
        $auditoria = null;
        
        //Busca los datos del catálogo y genera los checkbox
        $tipoAuditoria = $this->obtenerTiposAuditorias();
        
        //Busca los datos del catálogo y genera los radio buttons
        $auditoriaSolicitada = $this->obtenerTiposAuditoriasXSolicitud($idSolicitud);
        
        $check .= '<table style="border-collapse: initial;"><tr>';
        $agregarDiv = 0;
        $cantidadLinea = 0;
        
        foreach($auditoriaSolicitada as $fila){
            $auditoria[] = array('id_tipo_auditoria' => $auditoriaSolicitada->current()->id_tipo_auditoria);
        }
        
        foreach($tipoAuditoria as $fila){
            for($i=0; $i<count($auditoria); $i++){
                
                if($fila['id_tipo_auditoria'] == $auditoria[$i]['id_tipo_auditoria']){
                    $check .= '<td>
                                  <input id="c'.$fila['id_tipo_auditoria'].'" type="checkbox" name="tipoAuditoria[]" value="'.$fila['id_tipo_auditoria'].'" checked class="'.$fila['estado_registros'].'"/>
        			 	          <label for="c'.$fila['id_tipo_auditoria'].'">'.$fila['tipo_auditoria'].'</label>
                              </td>';
                    
                    $agregarDiv++;
                }else{
                    continue;
                }
            }
            
            
            $check .= '<td>
                              <input id="c'.$fila['id_tipo_auditoria'].'" type="checkbox" name="tipoAuditoria[]" value="'.$fila['id_tipo_auditoria'].'" class="'.$fila['estado_registros'].'"/>
    			 	          <label for="c'.$fila['id_tipo_auditoria'].'">'.$fila['tipo_auditoria'].'</label>
                          </td>';
            
            $agregarDiv++;

            
            if(($agregarDiv % 3) == 0){
                $check .= '</tr><tr>';
                $cantidadLinea++;
            }
        }
        $check .= '</tr></table>';
        
        echo $check;
        exit();
    }
}
<?php
/**
 * Controlador DosisViaAdministracion
 *
 * Este archivo controla la lógica del negocio del modelo:  DosisViaAdministracionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    DosisViaAdministracionControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\DosisViaAdministracionLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\DosisViaAdministracionModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class DosisViaAdministracionControlador extends BaseControlador
{

    private $lNegocioDosisViaAdministracion = null;
    private $modeloDosisViaAdministracion = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDosisViaAdministracion = new DosisViaAdministracionLogicaNegocio();
        $this->modeloDosisViaAdministracion = new DosisViaAdministracionModelo();
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
        $modeloDosisViaAdministracion = $this->lNegocioDosisViaAdministracion->buscarDosisViaAdministracion();
        $this->tablaHtmlDosisViaAdministracion($modeloDosisViaAdministracion);
        require APP . 'DossierPecuario/vistas/listaDosisViaAdministracionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo DosisViaAdministracion";
        require APP . 'DossierPecuario/vistas/formularioDosisViaAdministracionVista.php';
    }

    /**
     * Método para registrar en la base de datos -DosisViaAdministracion
     */
    public function guardar()
    {
        //Busca los datos de composicion
        $query = "id_especie = '".$_POST['id_especie']."' and
        quitar_caracteres_especiales(upper(trim(nombre_especie))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_especie']."'))) and
        quitar_caracteres_especiales(upper(trim(caracteristicas_animal))) = quitar_caracteres_especiales(upper(trim('".$_POST['caracteristicas_animal']."'))) and
        id_via_administracion = '".$_POST['id_via_administracion']."' and
        quitar_caracteres_especiales(upper(trim(cantidad_dosis))) = quitar_caracteres_especiales(upper(trim('".$_POST['cantidad_dosis']."'))) and
        id_unidad_dosis = '".$_POST['id_unidad_dosis']."' and
        quitar_caracteres_especiales(upper(trim(nombre_unidad_dosis))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_unidad_dosis']."'))) and
        quitar_caracteres_especiales(upper(trim(cantidad))) = quitar_caracteres_especiales(upper(trim('".$_POST['cantidad']."'))) and
        id_unidad = '".$_POST['id_unidad']."' and
        quitar_caracteres_especiales(upper(trim(nombre_unidad))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_unidad']."'))) and
        quitar_caracteres_especiales(upper(trim(duracion))) = quitar_caracteres_especiales(upper(trim('".$_POST['duracion']."'))) and
        --id_unidad_tiempo = '".$_POST['id_unidad_tiempo']."' and
        --quitar_caracteres_especiales(upper(trim(nombre_unidad_tiempo))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_unidad_tiempo']."'))) and
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaDosisViaAdmin = $this->lNegocioDosisViaAdministracion->buscarLista($query);
        
        if(isset($listaDosisViaAdmin->current()->id_dosis_via_administracion)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioDosisViaAdministracion->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        } 
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DosisViaAdministracion
     */
    public function editar()
    {
        $this->accion = "Editar DosisViaAdministracion";
        $this->modeloDosisViaAdministracion = $this->lNegocioDosisViaAdministracion->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioDosisViaAdministracionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DosisViaAdministracion
     */
    public function borrar()
    {
        $this->lNegocioDosisViaAdministracion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DosisViaAdministracion
     */
    public function tablaHtmlDosisViaAdministracion($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_dosis_via_administracion'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\dosisviaadministracion"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_dosis_via_administracion'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['id_especie'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las dosis y vias de administracion
     */
    public function construirDetalleDosisViaAdministracion()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $listaDetalles = $this->lNegocioDosisViaAdministracion->obtenerInformacionDosisViaAdministracion($idSolicitud);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['especie'] != '' ? $fila['especie'] : ''). ' ' . ($fila['nombre_especie'] != '' ? $fila['nombre_especie'] : ''). ' ' . ($fila['caracteristicas_animal'] != '' ? $fila['caracteristicas_animal'] : '').
                            ': ' . ($fila['cantidad_dosis'] != '' ? $fila['cantidad_dosis'] : '') . ' '. ($fila['nombre_unidad_dosis'] != '' ? $fila['nombre_unidad_dosis'] : '') .
                            ' por '. ($fila['cantidad'] != '' ? $fila['cantidad'] : '') . ' ' . ($fila['nombre_unidad'] != '' ? $fila['nombre_unidad'] : '').
                            ' cada: '. ($fila['duracion'] != '' ? $fila['duracion'] : '') . ' ' . ($fila['nombre_unidad_tiempo'] != '' ? $fila['nombre_unidad_tiempo'] : '').
                            ' ('. ($fila['via_administracion'] != '' ? $fila['via_administracion'] : '') . ')'.
                            '<td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleDosisViaAdministracion(' . $fila['id_dosis_via_administracion'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}
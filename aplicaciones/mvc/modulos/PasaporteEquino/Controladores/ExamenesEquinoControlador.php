<?php
/**
 * Controlador ExamenesEquino
 *
 * Este archivo controla la lógica del negocio del modelo:  ExamenesEquinoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-18
 * @uses    ExamenesEquinoControlador
 * @package PasaporteEquino
 * @subpackage Controladores
 */
namespace Agrodb\PasaporteEquino\Controladores;

use Agrodb\PasaporteEquino\Modelos\ExamenesEquinoLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\ExamenesEquinoModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ExamenesEquinoControlador extends BaseControlador
{

    private $lNegocioExamenesEquino = null;
    private $modeloExamenesEquino = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioExamenesEquino = new ExamenesEquinoLogicaNegocio();
        $this->modeloExamenesEquino = new ExamenesEquinoModelo();
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
        $modeloExamenesEquino = $this->lNegocioExamenesEquino->buscarExamenesEquino();
        $this->tablaHtmlExamenesEquino($modeloExamenesEquino);
        require APP . 'PasaporteEquino/vistas/listaExamenesEquinoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo ExamenesEquino";
        require APP . 'PasaporteEquino/vistas/formularioExamenesEquinoVista.php';
    }

    /**
     * Método para registrar en la base de datos -ExamenesEquino
     */
    public function guardar()
    {
        //Busca los datos de exámenes
        $query = "fecha_examen = '".$_POST['fecha_examen']."' and
        resultado_examen = '".$_POST['resultado_examen']."' and
        quitar_caracteres_especiales(upper(trim(laboratorio))) = quitar_caracteres_especiales(upper(trim('".$_POST['laboratorio']."'))) and
        quitar_caracteres_especiales(upper(trim(num_informe))) = quitar_caracteres_especiales(upper(trim('".$_POST['num_informe']."'))) and
        id_equino = '".$_POST['id_equino']."'";
        
        $listaExamenes = $this->lNegocioExamenesEquino->buscarLista($query);
        
        if(isset($listaExamenes->current()->id_examen_equino)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioExamenesEquino->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ExamenesEquino
     */
    public function editar()
    {
        $this->accion = "Editar ExamenesEquino";
        $this->modeloExamenesEquino = $this->lNegocioExamenesEquino->buscar($_POST["id"]);
        require APP . 'PasaporteEquino/vistas/formularioExamenesEquinoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ExamenesEquino
     */
    public function borrar()
    {
        $this->lNegocioExamenesEquino->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ExamenesEquino
     */
    public function tablaHtmlExamenesEquino($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_examen_equino'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'PasaporteEquino\examenesequino"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_examen_equino'] . '</b></td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['resultado_examen'] . '</td>
                    <td>' . $fila['laboratorio'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar los exámenes del equino
     */
    public function construirDetalleExamenes()
    {
        $idEquino = $_POST['idEquino'];
        $fase = $_POST['fase'];
        
        $query = "id_equino = $idEquino";
        
        $listaDetalles = $this->lNegocioExamenesEquino->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['fecha_examen'] != '' ? date('Y-m-d',strtotime($fila['fecha_examen'])) : ' NA').'</td>
                            <td>' . ($fila['resultado_examen'] != '' ? $fila['resultado_examen'] : 'NA').'</td>
                            <td>' . ($fila['laboratorio'] != '' ? $fila['laboratorio'] : 'NA').'</td>
                            <td>' . ($fila['num_informe'] != '' ? $fila['num_informe'] : 'NA').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editarEquino'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleExamenes(' . $fila['id_examen_equino'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}
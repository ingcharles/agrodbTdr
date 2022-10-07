<?php
/**
 * Controlador VacunasEquino
 *
 * Este archivo controla la lógica del negocio del modelo:  VacunasEquinoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-18
 * @uses    VacunasEquinoControlador
 * @package PasaporteEquino
 * @subpackage Controladores
 */
namespace Agrodb\PasaporteEquino\Controladores;

use Agrodb\PasaporteEquino\Modelos\VacunasEquinoLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\VacunasEquinoModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class VacunasEquinoControlador extends BaseControlador
{

    private $lNegocioVacunasEquino = null;
    private $modeloVacunasEquino = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioVacunasEquino = new VacunasEquinoLogicaNegocio();
        $this->modeloVacunasEquino = new VacunasEquinoModelo();
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
        $modeloVacunasEquino = $this->lNegocioVacunasEquino->buscarVacunasEquino();
        $this->tablaHtmlVacunasEquino($modeloVacunasEquino);
        require APP . 'PasaporteEquino/vistas/listaVacunasEquinoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo VacunasEquino";
        require APP . 'PasaporteEquino/vistas/formularioVacunasEquinoVista.php';
    }

    /**
     * Método para registrar en la base de datos -VacunasEquino
     */
    public function guardar()
    {
        //Busca los datos de vacunas
        $query = "fecha_enfermedad = '".$_POST['fecha_enfermedad']."' and
        quitar_caracteres_especiales(upper(trim(enfermedad))) = quitar_caracteres_especiales(upper(trim('".$_POST['enfermedad']."'))) and
        quitar_caracteres_especiales(upper(trim(laboratorio_lote))) = quitar_caracteres_especiales(upper(trim('".$_POST['laboratorio_lote']."'))) and
        id_equino = '".$_POST['id_equino']."'";
        
        $listaExamenes = $this->lNegocioVacunasEquino->buscarLista($query);
        
        if(isset($listaExamenes->current()->id_vacuna_equino)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioVacunasEquino->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: VacunasEquino
     */
    public function editar()
    {
        $this->accion = "Editar VacunasEquino";
        $this->modeloVacunasEquino = $this->lNegocioVacunasEquino->buscar($_POST["id"]);
        require APP . 'PasaporteEquino/vistas/formularioVacunasEquinoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - VacunasEquino
     */
    public function borrar()
    {
        $this->lNegocioVacunasEquino->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - VacunasEquino
     */
    public function tablaHtmlVacunasEquino($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_vacuna_equino'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'PasaporteEquino\vacunasequino"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_vacuna_equino'] . '</b></td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['id_equino'] . '</td>
                    <td>' . $fila['enfermedad'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las vacunas del equino
     */
    public function construirDetalleVacunas()
    {
        $idEquino = $_POST['idEquino'];
        $fase = $_POST['fase'];
        
        $query = "id_equino = $idEquino";
        
        $listaDetalles = $this->lNegocioVacunasEquino->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['fecha_enfermedad'] != '' ? date('Y-m-d',strtotime($fila['fecha_enfermedad'])) : ' NA').'</td>
                            <td>' . ($fila['enfermedad'] != '' ? $fila['enfermedad'] : 'NA').'</td>
                            <td>' . ($fila['laboratorio_lote'] != '' ? $fila['laboratorio_lote'] : 'NA').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editarEquino'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleVacunas(' . $fila['id_vacuna_equino'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}
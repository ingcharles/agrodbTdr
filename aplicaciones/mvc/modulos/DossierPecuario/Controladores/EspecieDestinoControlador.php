<?php
/**
 * Controlador EspecieDestino
 *
 * Este archivo controla la lógica del negocio del modelo:  EspecieDestinoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    EspecieDestinoControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\EspecieDestinoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\EspecieDestinoModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class EspecieDestinoControlador extends BaseControlador
{

    private $lNegocioEspecieDestino = null;
    private $modeloEspecieDestino = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioEspecieDestino = new EspecieDestinoLogicaNegocio();
        $this->modeloEspecieDestino = new EspecieDestinoModelo();
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
        $modeloEspecieDestino = $this->lNegocioEspecieDestino->buscarEspecieDestino();
        $this->tablaHtmlEspecieDestino($modeloEspecieDestino);
        require APP . 'DossierPecuario/vistas/listaEspecieDestinoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo EspecieDestino";
        require APP . 'DossierPecuario/vistas/formularioEspecieDestinoVista.php';
    }

    /**
     * Método para registrar en la base de datos -EspecieDestino
     */
    public function guardar()
    {
        //Busca los datos de uso y especie
        $query = "id_especie = '".$_POST['id_especie']."' and
        quitar_caracteres_especiales(upper(trim(nombre_especie))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_especie']."'))) and
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaEspecieDestino = $this->lNegocioEspecieDestino->buscarLista($query);
        
        if(isset($listaEspecieDestino->current()->id_especie_destino)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioEspecieDestino->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: EspecieDestino
     */
    public function editar()
    {
        $this->accion = "Editar EspecieDestino";
        $this->modeloEspecieDestino = $this->lNegocioEspecieDestino->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioEspecieDestinoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - EspecieDestino
     */
    public function borrar()
    {
        $this->lNegocioEspecieDestino->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - EspecieDestino
     */
    public function tablaHtmlEspecieDestino($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_especie_destino'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\especiedestino"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_especie_destino'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['id_especie'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las especies de destino
     */
    public function construirDetalleEspecieDestino()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $listaDetalles = $this->lNegocioEspecieDestino->obtenerInformacionEspecieDestino($idSolicitud);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['especie'] != '' ? $fila['especie'] : '').'</td>
                            <td>' . ($fila['nombre_especie'] != '' ? $fila['nombre_especie'] : '').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleEspecieDestino(' . $fila['id_especie_destino'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}
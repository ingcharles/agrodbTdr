<?php
/**
 * Controlador PartidaCodigos
 *
 * Este archivo controla la lógica del negocio del modelo:  PartidaCodigosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    PartidaCodigosControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\PartidaCodigosLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\PartidaCodigosModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class PartidaCodigosControlador extends BaseControlador
{

    private $lNegocioPartidaCodigos = null;
    private $modeloPartidaCodigos = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioPartidaCodigos = new PartidaCodigosLogicaNegocio();
        $this->modeloPartidaCodigos = new PartidaCodigosModelo();
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
        $modeloPartidaCodigos = $this->lNegocioPartidaCodigos->buscarPartidaCodigos();
        $this->tablaHtmlPartidaCodigos($modeloPartidaCodigos);
        require APP . 'DossierPecuario/vistas/listaPartidaCodigosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo PartidaCodigos";
        require APP . 'DossierPecuario/vistas/formularioPartidaCodigosVista.php';
    }

    /**
     * Método para registrar en la base de datos -PartidaCodigos
     */
    public function guardar()
    {
        //Busca y cuenta los registros con la partida arancelaria requerida, 
        //si es la misma partida (1) se agrega el registro, 
        //caso contrario se elimina toda la tabla
        $numRegistros = $this->lNegocioPartidaCodigos->obtenerNumeroRegistrosPartidaCodigos($_POST['id_solicitud']);
        $numRegistrosPartida = $this->lNegocioPartidaCodigos->obtenerNumeroRegistrosPartidaCodigosXPartida($_POST['id_solicitud'], $_POST['partida_arancelaria']);
        
        if(($numRegistros->current()->numero == 0) || ($numRegistros->current()->numero >= 0 && $numRegistrosPartida->current()->numero == 1)){
            //Busca los datos de partida y códigos
            $query = "  partida_arancelaria = '".$_POST['partida_arancelaria']."' and
                        id_codigo_complementario = '".$_POST['id_codigo_complementario']."' and
                        id_codigo_suplementario = '".$_POST['id_codigo_suplementario']."'  and
                        id_solicitud = '".$_POST['id_solicitud']."'";
            
            $listaOrigen = $this->lNegocioPartidaCodigos->buscarLista($query);
            
            if(isset($listaOrigen->current()->id_partida_codigo)){
                Mensajes::fallo(Constantes::ERROR_DUPLICADO);
            }else{
                $this->lNegocioPartidaCodigos->guardar($_POST);
                Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
            } 
        }else{
            Mensajes::fallo(Constantes::ERROR_PARTIDA_DIFERENTE);
        }

    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: PartidaCodigos
     */
    public function editar()
    {
        $this->accion = "Editar PartidaCodigos";
        $this->modeloPartidaCodigos = $this->lNegocioPartidaCodigos->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioPartidaCodigosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - PartidaCodigos
     */
    public function borrar()
    {
        $this->lNegocioPartidaCodigos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - PartidaCodigos
     */
    public function tablaHtmlPartidaCodigos($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_partida_codigo'] . '"
                	  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\partidacodigos"
                	  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                	  data-destino="detalleItem">
                	  <td>' . ++ $contador . '</td>
                	  <td style="white - space:nowrap; "><b>' . $fila['id_partida_codigo'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['partida_arancelaria'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las partidas arancelarias y códigos complementarios/suplementarios
     */
    public function construirDetallePartidaCodigos()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $query = "id_solicitud = $idSolicitud";
        
        $listaDetalles = $this->lNegocioPartidaCodigos->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['partida_arancelaria'] != '' ? $fila['partida_arancelaria'] : 'NA'). '</td>
                            <td>' . ($fila['codigo_complementario'] != '' ? $fila['codigo_complementario'] : 'NA'). '</td>
                            <td>' . ($fila['codigo_suplementario'] != '' ? $fila['codigo_suplementario'] : 'NA') . '</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono"  '. ($fase!='editar'?'style="display:none"':'') . ' onclick="fn_eliminarDetallePartidaCodigos(' . $fila['id_partida_codigo'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}